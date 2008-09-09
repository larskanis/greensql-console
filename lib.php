<?php

#set default time zone - this prevents PHP5 from 
#showing strange warning messages
date_default_timezone_set("America/Los_Angeles");

# do not start session if it was started in login.php file
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 1))
{
    session_start();
}

include_once 'config.php';

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 jul 1997 05:00:00 GMT");

validate_installation();
require 'libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_check = true;
global $cache_dir;
$smarty->compile_dir = $cache_dir;
global $version;
$smarty->assign("Version", $version);
#$smarty->debugging = true;

#if ( ($error = db_connect()) != "")
#{
#    die( $error);
#}

# check if we came not from the login page and ceck if user is in session
# otherwise - user is not loged in
if (isset($_SESSION['login']) && $_SESSION['login'] != 1 && !isset($_SESSION['user']))
{
    header("location: login.php");
    exit;
}

# we are logged in - check the token
$good_token = 0;
$tokenname= "token";
if (isset($_SESSION[$tokenname]) && isset($_REQUEST[$tokenname]) )
{
    if ($_SESSION[$tokenname] != "" && $_SESSION[$tokenname] == $_REQUEST[$tokenname])
    {
        $good_token = 1;
    }
}
#if we came from login page, do not check the token
if (isset($_SESSION['login']) && $_SESSION['login'] == 1)
{
    $good_token = 1;
}
if ($good_token == 0)
{
    header("location: login.php");
    exit;
}

#denerate next token
#a token will be changed every hour
$tokenid = md5($_SESSION['user'].date("G j-m-Y").session_id());
$smarty->assign("TokenName", $tokenname);
$smarty->assign("TokenID", $tokenid );
$_SESSION[$tokenname] = $tokenid;

function db_connect()
{
  include 'config.php';
  
  #print "dbuser $dbuser $db_user\n";
  if (@mysql_connect("$db_host:$db_port",$db_user,$db_pass) == false)
      return "Failed to connect to MySQL server.";
    
  if (@mysql_select_db($db_name) == false)
      return "Failed to connect to GreenSQL configuration database.";

  return "";
  
}

function validate_installation()
{
    global $cache_dir;
    global $db_name;
    $msg = '';
    $bad = 0;
    
    $msg .= "<h2>GreenSQL configuration error</h2>";
    $msg .= "<h3>1. Cache Directory</h3>\n";
    if ($cache_dir && !is_writable($cache_dir)) {
        $msg .= "<i>Cache Directory</i> specifies location of the directory used to create temproary files.<br/>This directory <i><font color='red'>$cache_dir</font></i> is not writable. Run the following shell command to fix this:<br/><blockquote><strong>chmod 0777 $cache_dir</strong></blockquote>";
        $bad = 1;
    } else if ($cache_dir) {
        $msg .= "<font color='green'>$cache_dir</font> directory is writable.";
    } else {
        $msg .= "<font color='red'>Bad configuration file</font> Directory used to store cache pages is not defined.";
        $bad = 1;
    }
    $msg .= "</br>\n";

    $msg .= "<h3>2. Database Connectivity</h3>\n";
    $db_error = '';
    if ( ($db_error = db_connect()) != "")
    {
        $msg .= "<font color='red'>$db_error</font><br/>";
        $msg .= "Please alter <i>config.php</i> file with a proper database settings.<br/>This file is found in the application directory.<br/>";
        $bad = 1;
    } else {
        $msg .= "Connection to <font color='green'>$db_name</font> established.<br/>\n";
    }
     
    # check database structure
    if (!$db_error)
    {
        $msg .= "<h3>3. Database Schema</h3>";
        $q = "desc db_perm";
        $result = @mysql_query($q);
        $row = @mysql_fetch_array($result);
        if (!$row)
        {
            $msg .= "<font color='red'>Tables not found.</font> ";
            $msg .= "Please ensure that configuration database was created while installing greensql-fw package.<br/>";
            $bad = 1;
        } else {
            $msg .= "<font color='green'>Table structure is ok.</font>";
        }
    }
    $msg .= "<br/><br/><br/>";
    $msg .= "If you are not able to resolve this problems, please check <a href='http://www.greensql.net/forum/1'>GreenSQL Support Forum.</a>";
    if ($bad == 1)
    {
      print $msg;
      exit;
    }
}

function check_user($user, $pass)
{
    $user = mysql_escape_string($user);
    $pass = mysql_escape_string($pass);
    $q = "SELECT * FROM user WHERE name = '$user' AND pwd='$pass'";
    $result = mysql_query($q); 
    $row = mysql_fetch_array($result);
    return $row;
}

function get_user($userid)
{
    $userid=intval($userid);
    $q = "SELECT * FROM user WHERE userid=$userid";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    return $row;
}

function update_user($user)
{
    $q = "UPDATE user SET name='".mysql_escape_string($user['name'])."', ".
    "email = '".mysql_escape_string($user['email'])."', ".
    "pwd = SHA1('".mysql_escape_string($user['pwd'])."') ".
    "WHERE userid=".$user['userid'];
    $result = mysql_query($q);
    return;
}

function get_databases()
{

    $q = "SELECT * from db_perm";
    $result = mysql_query($q);

    $dbs = array();
    while ($row = mysql_fetch_array($result) )
    {
        $dbs[] = array("id" => $row['dbpid'], "name" => $row['db_name'],
                       "proxy_id" => $row['proxyid']);
    }
    return $dbs;
}

function get_database($dbid)
{
    $dbid = intval($dbid);
    $q = "SELECT db_name, INET_NTOA(frontend_ip) as 'frontend_ip', ".
         "frontend_port, dbtype, proxy.status as 'proxy_stataus',".
         "proxyname, db_perm.proxyid, ".
         "backend_server, backend_port, ".
	 "INET_NTOA(backend_ip) as 'backend_ip', ".
	 "perms, perms2, db_perm.status as 'status', status_changed ".
         "FROM db_perm left join proxy USING (proxyid) ".
	 "WHERE dbpid=$dbid ";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if (!$row)
        return $row;
    $row['listener'] = $row['frontend_ip'].":".$row['frontend_port'];
    $row['backend'] = $row['backend_server'].":".$row['backend_port'];
    $row['alter_perm']  = ($row['perms'] & 4) ? 1 : 0;
    $row['create_perm'] = ($row['perms'] & 1) ? 1 : 0;
    $row['drop_perm']   = ($row['perms'] & 2) ? 1 : 0;
    $row['info_perm']   = ($row['perms'] & 8) ? 1 : 0;
    $row['block_q_perm']= ($row['perms'] & 16)? 1 : 0;
    return $row;
}

function add_database($proxyid, $name)
{
    $proxyid = intval($proxyid);
    $name = mysql_escape_string($name);

    # check if we have this proxy id
    if (!get_proxy($proxyid))
        return "Proxy object not found";

    #check if we have the same db
    $q = "SELECT * from db_perm where proxyid=$proxyid and db_name='$name'";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if ($row)
        return "Object already created";
    $q = "INSERT into db_perm (proxyid,db_name) VALUES ($proxyid,'$name')"; 
    $result = mysql_query($q);
}

function update_database($db)
{
    $q = "UPDATE db_perm SET ".
    "proxyid=".$db['proxyid'].", ".
    "db_name='".$db['db_name']."', ".
    "perms=".$db['perms'].", ".
    "status=".$db['status'].", ".
    "status_changed=now() ".
    "WHERE dbpid=".$db['dbpid'];
    $result = mysql_query($q); 
}

function get_proxy($proxyid)
{
    $proxyid = intval($proxyid);
    $q = "SELECT proxyname, proxyid, INET_NTOA(frontend_ip) as 'frontend_ip', ".
         "frontend_port, dbtype, status, backend_server, ".
	 "INET_NTOA(backend_ip) as 'backend_ip', backend_port ".
         "FROM proxy WHERE proxyid=$proxyid";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    return $row;
}

function add_proxy($proxy)
{
    $proxy['frontend_ip'] = mysql_escape_string($proxy['frontend_ip']);
    $proxy['backend_ip'] = mysql_escape_string($proxy['backend_ip']);
    $proxy['backend_server'] = mysql_escape_string($proxy['backend_server']);

    #check if this backend already used
    $q = "SELECT * from proxy WHERE ".
    "frontend_ip = INET_ATON('".$proxy['frontend_ip']."') AND ".
    "frontend_port = ".$proxy['frontend_port'];
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if ($row)
        return "Failed to add new proxy, same frontend ip and port already used.";
    $q = "INSERT into proxy (proxyname, frontend_ip, frontend_port, ".
    "backend_server, backend_ip, backend_port, dbtype, status) VALUES (".
    "'".$proxy['proxyname']."', ".
    "INET_ATON('".$proxy['frontend_ip']."'), ".
    $proxy['frontend_port'].", ".
    "'".$proxy['backend_server']."', ".
    "INET_ATON('".$proxy['backend_ip']."'), ".
    $proxy['backend_port'].", ".
    "'mysql',0)"; 
    $result = mysql_query($q);
}

function update_proxy($proxy)
{
    $proxy['frontend_ip'] = mysql_escape_string($proxy['frontend_ip']);
    $proxy['backend_ip'] = mysql_escape_string($proxy['backend_ip']);
    $proxy['backend_server'] = mysql_escape_string($proxy['backend_server']);

    #check if this backend already used
    $q = "SELECT * from proxy WHERE ".
    "frontend_ip=INET_ATON('".$proxy['frontend_ip']."') AND ".
    "frontend_port = ".$proxy['frontend_port']." AND ".
    "proxyid != ".$proxy['proxyid'];

    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if ($row)
        return "Failed to update proxy, same frontend ip and port already used.";
    $q = "UPDATE proxy SET ".
    "proxyname = '".$proxy['proxyname']."', ".
    "frontend_ip = INET_ATON('".$proxy['frontend_ip']."'), ".
    "frontend_port = ".$proxy['frontend_port'].", ".
    "backend_server = '".$proxy['backend_server']."', ".
    "backend_ip = INET_ATON('".$proxy['backend_ip']."'), ".
    "backend_port = ".$proxy['backend_port'].", ".
    "dbtype = 'mysql', ".
    "status = 0 WHERE proxyid = ".$proxy['proxyid'];
    $result = mysql_query($q);
}

function get_proxies()
{
    $q = "SELECT proxyname, proxyid, INET_NTOA(frontend_ip) as 'frontend_ip', ".         "frontend_port, dbtype, status, backend_server, ".
         "INET_NTOA(backend_ip) as 'backend_ip', backend_port ".
         "FROM proxy";
    $result = mysql_query($q);
    
    $proxies = array();
    $row = array();
    while ($row = mysql_fetch_array($result) )
    {
        $proxies[] = $row;
    }
    return $proxies;
}

function get_alerts($status)
{
    $status = intval($status);
    $q = "SELECT alert_group.*,proxy.proxyname ".
         "FROM alert_group, proxy ".
	 "WHERE alert_group.proxyid=proxy.proxyid ".
	 "AND alert_group.status = $status ".
	 "ORDER BY update_time DESC";
    $result = mysql_query($q);

    $alerts = array();
    $row = array();
    while ($row = mysql_fetch_array($result) )
    {
        if (strlen($row['pattern']) > 85)
	{
            $row['short_pattern'] = htmlspecialchars(substr($row['pattern'], 0, 80)."...");
	} else {
	    $row['short_pattern'] = htmlspecialchars($row['pattern']);
	}
        $row['pattern'] = htmlspecialchars($row['pattern']);
        $alerts[] = $row;
    }
    return $alerts;
}

function get_alerts_bypage($from,$count,$status)
{
    $status = intval($status);
    $q = "SELECT alert_group.*,proxy.proxyname ".
         "FROM alert_group, proxy ".
         "WHERE alert_group.proxyid=proxy.proxyid ".
         "AND alert_group.status = $status ".
         "ORDER BY update_time DESC ".
         "LIMIT $from, $count";
    $result = mysql_query($q);

    $alerts = array();
    $row = array();
    while ($row = mysql_fetch_array($result) )
    {
        if (strlen($row['pattern']) > 85)
        {
            $row['short_pattern'] = htmlspecialchars(substr($row['pattern'], 0, 80)."...");
        } else {
            $row['short_pattern'] = htmlspecialchars($row['pattern']);
        }
        $row['pattern'] = htmlspecialchars($row['pattern']);
        $alerts[] = $row;
    }
    return $alerts;
}

function get_num_alerts($status)
{
    $status = intval($status);
    $q = "SELECT count(*) FROM alert_group ".
         "WHERE status = $status";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if (!$row)
      return 0;
    return $row[0];
}

function get_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "SELECT alert_group.*,proxy.proxyname ".
         "FROM alert_group, proxy ".
         "WHERE alert_group.proxyid=proxy.proxyid AND ".
         "agroupid = $agroupid ".
         "ORDER BY update_time DESC";
    $result = mysql_query($q);

    $row = array();
    $row = mysql_fetch_array($result);
    
    if (!$row)
        return $row;
    if (strlen($row['pattern']) > 85)
    {
        $row['short_pattern'] = htmlspecialchars(substr($row['pattern'], 0, 80)."...");
    } else {
        $row['short_pattern'] = htmlspecialchars($row['pattern']);
    }
    $row['pattern'] = htmlspecialchars($row['pattern']);
    return $row;
}

function get_raw_alerts($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "SELECT * FROM alert WHERE agroupid=$agroupid ".
         "ORDER BY event_time DESC";
    $result = mysql_query($q);

    $alerts = array();
    $row = array();
    while ($row = mysql_fetch_array($result) )
    {
        $row['reason'] = str_replace("\n", "<br/>\n", $row['reason']);

	if ($row['block'] == 1)
	{
	    $row['block_str'] = "<font color='red'>blocked</font>";
	} else if ($row['block'] == 0)
	{
	    $row['block_str'] = "<font color='orange'>warning</font>";
        } else if ($row['block'] == 2)
        {
            $row['block_str'] = "<font color='red'>high risk</font>";
        } else if ($row['block'] == 3)
        {
            $row['block_str'] = "<font color='black'>low</font>";
	} else {
	    $row['block_str'] = "unknown";
	}
        $row['query'] = htmlspecialchars($row['query']);
        $alerts[] = $row;
    }
    return $alerts;
}

function get_num_raw_alerts( $status)
{
    $q = "SELECT count(*) FROM alert, alert_group ".
    "WHERE alert.agroupid = alert_group.agroupid AND status = $status ";
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if (!$row)
      return 0;
    return $row[0];
}

function get_raw_alerts_bypage($from, $count, $status)
{
    $q = "SELECT * FROM alert, alert_group ".
         "WHERE alert.agroupid = alert_group.agroupid AND status = $status ".
         "ORDER BY event_time DESC LIMIT $from, $count";
    $result = mysql_query($q);

    $alerts = array();
    $row = array();
    while ($row = mysql_fetch_array($result) )
    {
        $row['reason'] = str_replace("\n", "<br/>\n", $row['reason']);
        if ($row['block'] == 1)
        {
            $row['block_str'] = "<font color='red'>blocked</font>";
	    $row['color'] = "#ffe9e9"; # a light red
        } else if ($row['block'] == 0)
        {
            $row['block_str'] = "<font color='orange'>warning</font>";
	    $row['color'] = "#ffffe0"; # a light orange
        } else if ($row['block'] == 2)
        {
            $row['block_str'] = "<font color='red'>high risk</font>";
            $row['color'] = "#ffffe0"; # a light orange
        } else if ($row['block'] == 3)
        {
            $row['block_str'] = "low";
            $row['color'] = "#f9f9f9"; # a light grey
        } else {
            $row['block_str'] = "unknown";
	    $row['color'] = "#f9f9f9"; # a light grey
        }

        if (strlen($row['query']) > 85)
        {
            $row['short_query'] = htmlspecialchars(substr($row['query'], 0, 80)."...");
        } else {
            $row['short_query'] = htmlspecialchars($row['query']);
        }
        $row['query'] = htmlspecialchars($row['query']);
        $row['pattern'] = htmlspecialchars($row['pattern']);

        $alerts[] = $row;
    }
    return $alerts;
}

function approve_alert($agroupid, $alert)
{
    $agroupid=intval($agroupid);
    # first we will check we we have this database created
    $q = "";
    if ($alert['db_name'] == "")
    {
      # load default db name  
      $q = "SELECT * from db_perm WHERE dbpid = 1";
    } else {
      # load default db name
      $q = "SELECT * from db_perm WHERE ".
           "db_name='".mysql_escape_string($alert['db_name'])."' AND ".
           "proxyid=".intval($alert['proxyid']);
    }
    $result = mysql_query($q);
    $row = mysql_fetch_array($result);
    if (!$row)
    {
        # this object was not found
        $q = "INSERT INTO db_perm (proxyid, db_name) ".
	     "values (".$alert['proxyid'].",".
	     "'".mysql_escape_string($alert['db_name'])."')"; 
	#print $q;
	$result = mysql_query($q);
    }
    $q = "INSERT INTO query (proxyid,perm,db_name,query) ".
    "VALUES(".$alert['proxyid'].",1,'".$alert['db_name']."','".$alert['pattern']."')";
    $result = mysql_query($q);

    $q = "UPDATE alert_group set status=1 WHERE agroupid=$agroupid";
    $result = mysql_query($q);
}

function ignore_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "UPDATE alert_group set status=2 WHERE agroupid=$agroupid";
    $result = mysql_query($q);
}

function delete_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "DELETE from alert_group WHERE agroupid=$agroupid";
    $result = mysql_query($q);
    $q = "DELETE from alert WHERE agroupid=$agroupid";
    $result = mysql_query($q);
}

function truncate_alerts()
{
    $q = "truncate alert_group";
    $result = mysql_query($q);
    $q = "truncate alert";
    $result = mysql_query($q);
    $q = "truncate query";
    $result = mysql_query($q);
}

function read_log($file, $lines, &$error)
{
    $fp = @fopen($file, "r");
    if (!$fp)
    {
        $error = "Failed to read log file.";
	return array();
    }
    $fsize = filesize($file);
    if ($fsize == 0)
    {
        fclose($fp);
	return array("");
    }
    $p = $fsize - $lines * 1024;
    if ($p > 0)
    {
        fseek($fp, $p);
    }
    $str = "";
    $data = array();
    while ( !feof($fp))
    {
        $str = fgets($fp);
	$str = chop($str); 
	$str = htmlspecialchars($str);
	$str .= "<br/>\n";
	$data[] = $str;
    }
    $num = count($data) - $lines-1;
    if ($num < 0)
    {
        return array_reverse($data);
    }
    while ($num > 0)
    {
        array_shift($data);
	$num--;
    }
    return array_reverse($data);
}

?>
