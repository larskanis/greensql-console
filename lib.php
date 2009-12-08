<?php

include_once("lib_tables.php");
include_once("lib_sql.php");

if (substr(phpversion(),0,1) == "5" && function_exists('date_default_timezone_set'))
{
  #set default time zone - this prevents PHP5 from 
  #showing strange warning messages
  date_default_timezone_set("America/Los_Angeles");
}

# do not start session if it was started in login.php file
if (!(isset($_SESSION['login']) && $_SESSION['login'] == 1))
{
    session_start();
}

include_once 'config.php';

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 jul 1997 05:00:00 GMT");

validate_installation();
if (isset($smarty_dir) && @is_dir($smarty_dir)) {
  require $smarty_dir . '/Smarty.class.php';
} else {
  require 'libs/Smarty.class.php';
}

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

# check if we came not from the login page and check if user is in session
# otherwise - user is not loged in
if (isset($_SESSION['login']) && $_SESSION['login'] != 1 && !isset($_SESSION['user']))
{
    header("location: login.php");
    exit;
}

# we are logged in - check the token
$good_token = 0;
$tokenname= "token";
$tokenid = "";
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

#generate next token
#a token will be changed every hour
generate_session_token();

function generate_session_token()
{
  global $smarty;
  global $tokenname;
  global $tokenid;
  #generate next token
  #a token will be changed every hour
  if (isset($_SESSION['user']))
    $tokenid = md5($_SESSION['user'].date("G j-m-Y").session_id());
  else
    $tokenid = md5(date("G j-m-Y").session_id());
  $smarty->assign("TokenName", $tokenname);
  $smarty->assign("TokenID", $tokenid );
  $_SESSION[$tokenname] = $tokenid;
}

function db_connect()
{
  global $pgsql_db;
  global $db_type;
  global $db_user;
  global $db_pass;
  global $db_host;
  global $db_port;
  global $db_name;

  if (! $db_type) { $db_type = "mysql"; }

  if ($db_type == "mysql") {
    if (@mysql_connect("$db_host:$db_port",$db_user,$db_pass) == false)
      return "Failed to connect to MySQL server.";

    if (@mysql_select_db($db_name) == false)
      return "Failed to connect to GreenSQL configuration database.";
  } else if ($db_type == "pgsql" || $db_type == "postgresql") {
    if (! $db_port ) { $db_port = "5432"; }

    $pgsql_db = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass")
      or die('Could not connect: ' . pg_last_error());
  }

  return "";  
}

function validate_installation()
{
    global $cache_dir;
    global $db_name;
    global $db_type;
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
        $msg .= "In addition, SELinux can prevent php to connect to MySQL database.<br/>";
        $msg .= "Check if SELinux is enabled: /usr/sbin/sestatus -v";
        $msg .= "In case it is, you can just type the command: setsebool httpd_can_network_connect_db=1";
        $bad = 1;
    } else {
        $msg .= "Connection to <font color='green'>$db_name</font> established.<br/>\n";
    }
     
    # check database structure
    if (!$db_error)
    {
        $msg .= "<h3>3. Database Schema</h3>";        
        if ($db_type == "mysql") {
          $q = "show tables like 'db_perm';";
        } 
        elseif ( $db_type == "pgsql" || $db_type == "postgresql" ) {
          $q = "select * from pg_tables where tablename='db_perm'";
        }

        $result = @db_query($q);
        $row = @db_fetch_array($result);
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
    $user = db_escape_string($user);
    $pass = db_escape_string($pass);
    $q = "SELECT * FROM admin WHERE name = '$user' AND pwd='$pass'";
    $result = db_query($q); 
    $row = db_fetch_array($result);
    return $row;
}

function get_user($adminid)
{
    $adminid=intval($adminid);
    $q = "SELECT * FROM admin WHERE adminid=$adminid";
    $result = db_query($q);
    $row = db_fetch_array($result);
    return $row;
}

function new_user($user)
{
    $pwd = sha1($user['pwd']);
    $q = "INSERT INTO admin(name,pwd,email) VALUES ('".db_escape_string($user['name'])."',".
        "'$pwd','".db_escape_string($user['email'])."');";
    $result = db_query($q);
    return;
}

function delete_proxy($id)
{
	$q = "DELETE FROM proxy WHERE proxyid=".$id;
	$result = db_query($q);
	return;
}

function delete_user($userid)
{
    $q = "DELETE FROM admin WHERE adminid=".$userid;
    $result = db_query($q);
    return;
}

function update_user($user)
{
    $pwd = sha1($user['pwd']);
    $q = "UPDATE admin SET name='".db_escape_string($user['name'])."', ".
    "email = '".db_escape_string($user['email'])."', ".
    "pwd = '$pwd' ".
    "WHERE adminid=".$user['adminid'];
    $result = db_query($q);
    return;
}

function get_proxy_list()
{
    global $tokenname;
    global $tokenid;

    $q = "SELECT * FROM proxy";
    $result = db_query($q);
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        $row['Proxy name'] = $row['proxyname'];
	$row['Db type'] = $row['dbtype'];
        switch($row['status'])
	{
	 case 0:
            $row['Status'] = "Starting Up";
	    break;
         case 1:
            $row['Status'] = '<font color="green">Active</font>';
            break;
         case 2:
            $row['Status'] = '<font color="red">Failed</font>';
            break;
         case 3:
            $row['Status'] = "Disabled";
            break;
	}
        $row['Options'] = '<a href="proxy_edit.php?proxyid='.$row['proxyid'].'&'.$tokenname.'='.$tokenid.'">Settings</a>';
	$row['Delete'] = '<form action="proxy_delete.php?proxyid='.$row['proxyid'].'&'.$tokenname.'='.$tokenid.'" method="POST">'.
                         '<input type="hidden" name="proxyid" value="'.$row['proxyid'].'">'.
                         '<input type="submit" name="submit" value="Delete">'.#&nbsp;<input type="checkbox" name="confirm"> Confirm'.
                         '</form>';
        $proxy[] = $row;
    }
    return $proxy;
}

function get_databases_list()
{
    $q = "SELECT db_perm.*,proxy.dbtype,proxy.proxyname FROM db_perm LEFT JOIN proxy ON db_perm.proxyid=proxy.proxyid";
    $result = db_query($q);
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        $dbs[] = array("id" => $row['dbpid'],
            "name" => $row['db_name'],
            "proxy_id" => $row['proxyid'],
            "dbtype" => $row['dbtype']);
    }
    return $dbs;
}

function get_databases($header,$from,$count)
{
    global $tokenid;
    global $tokenname;

    $q = "SELECT db_perm.*,proxy.dbtype,proxy.proxyname FROM db_perm LEFT JOIN proxy ON db_perm.proxyid=proxy.proxyid";

    $q = add_query_sort($header, $q);
    $q = add_query_limit($q, $from, $count );

    $result = db_query($q);

    $dbs = array();
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        $mode = get_db_mode($row['status']);
        $row['Mode'] = $mode['mode'];
	$row['Db type'] = $row['dbtype'];
        $row['Options'] = '<a href="db_view.php?db_id='.$row['dbpid'].'&'.$tokenname.'='.$tokenid.'">Overview</a>&nbsp | '.
                          '<a href="rawalert_list.php?db_id='.$row['dbpid'].'&'.$tokenname.'='.$tokenid.'">Alerts</a>&nbsp | '.
                          '<a href="whitelist.php?db_id='.$row['dbpid'].'&per_page=10&'.$tokenname.'='.$tokenid.'">Whitelist</a>&nbsp | '.
                          '<a href="db_edit.php?db_id='.$row['dbpid'].'&'.$tokenname.'='.$tokenid.'">Settings</a>';
        $row['Delete'] = '<form action="db_delete.php?db_id='.$row['dbpid'].'&'.$tokenname.'='.$tokenid.'" method="POST">'.
                         '<input type="hidden" name="db_id" value="'.$row['dbpid'].'">'.
                         '<input type="submit" name="submit" value="Delete">'.#&nbsp;<input type="checkbox" name="confirm"> Confirm'.
                         '</form>';
        if (!$row['proxyname'])
	      $row['proxyname'] = 'All Proxies';
   //         $row['proxyname'] = 'system type: '.$row['sysdbtype'];
        $dbs[] = $row;
    }
    return $dbs;
}

function proxy_in_use($id)
{
 $q = 'SELECT proxyid FROM db_perm WHERE proxyid='.$id;
 $result = db_query($q);
 $row = array();
 $row = db_fetch_array($result);
 if($row)
      return true;
 else return false;
}

function get_last_proxy()
{
 $q = "SELECT * FROM proxy ORDER BY proxyid DESC LIMIT 1";
 $result = db_query($q);
 $row = db_fetch_array($result);
 return $row;
}

function restore_table_data($xml,$table_name,$key)
{
    global $db_type;

    // Truncate Table
    $q = "TRUNCATE ".$table_name;
    $error = db_query($q);

    if($error == false)
        return $error;

    $table = (array)$xml->$table_name;
    foreach($table as $node => $record)
    {
      foreach($record as $row)
      {
        $q = $q_names = $q_values = '';
        if(count($row) > 1)
        {
          foreach($row as $name => $value)
          {
            $q_names .= "$name,";
	    if ($table_name == 'db_perm' && $name = 'status_changed' && $value == '') {
              $q_values .= "NOW(),";
            } else {
	      $q_values .= "'$value',";
            }
          }
        }
        else {
          foreach($record as $name => $value)
          {
            $q_names .= "$name,";
            if ($table_name == 'db_perm' && $name = 'status_changed' && $value == '') {
              $q_values .= "NOW(),";
            } else {
              $q_values .= "'$value',";
            }
          }
	}

        $q_names = rtrim($q_names,","); // remove ,
        $q_values = rtrim($q_values,","); // remove ,
        $q = "INSERT INTO $table_name($q_names) VALUES ($q_values);";
        $error = db_query($q);
        if($error == false)
          return $error;
      } // end foreach rows
    } // end foreach tables

    return $error;
}


function get_table_data($xml_doc,$xml,$table_name)
{
    $table_name = db_escape_string($table_name);
    $q = "SELECT * FROM ".$table_name;
    $result = db_query($q);

    $table = $xml_doc->createElement($table_name);
    $xml->appendChild($table);

    while($row = db_fetch_array($result))
    {
        $b = $xml_doc->createElement("Record");
        foreach($row as $name => $value)
        {
            if(intval($name) == 0 && $name!="0")
            {
                $field = $xml_doc->createElement($name);
                $field->appendChild($xml_doc->createTextNode($value));
                $b->appendChild($field);
            }
        }
        $table->appendChild($b);
    }
    return $xml;
}

function get_admins()
{
 global $tokenid;
 global $tokenname;

 $q = "select adminid,name,email from admin";

 $result = db_query($q);

 $admins = array();
 $row = array();
 while ($row = db_fetch_array($result) )
 {
  $row['Email'] = $row['email'];
  $row['Options'] = '<a href="user_edit.php?user_id='.$row['adminid'].'&'.$tokenname.'='.$tokenid.'">Edit</a>&nbsp;';
  $row['Delete'] = "<form action='user_delete.php?$tokenname=$tokenid' method='POST'>".
                   '<input type="hidden" name="user_id" value="'.$row['adminid'].'">'.
                   '<input type="submit" name="submit" value="delete">&nbsp;<input type="checkbox" name="confirm"> Confirm'.
                   '</form>';
   $admins[] = $row;		
 }
 return $admins;
}

function get_database($db_id)
{
    $db_id = abs(intval($db_id));
    $q = "SELECT db_name, sysdbtype, frontend_ip, ".
         "frontend_port, dbtype, proxy.status as proxy_status,".
         "proxyname, db_perm.proxyid, ".
         "backend_server, backend_port, ".
         "backend_ip, ".
         "perms, perms2, db_perm.status as status, status_changed ".
         "FROM db_perm left join proxy USING (proxyid) ".
         "WHERE dbpid=$db_id ";
    $result = db_query($q);
    $row = db_fetch_array($result);
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
    $name = db_escape_string($name);

    # check if we have this proxy id
    if (!get_proxy($proxyid))
        return "Proxy object not found";

    #check if we have the same db
    $q = "SELECT * from db_perm where proxyid=$proxyid and db_name='$name'";
    $result = db_query($q);
    $row = db_fetch_array($result);
    if ($row)
        return "Object already created";
    $q = "INSERT into db_perm (proxyid,db_name) VALUES ($proxyid,'$name')"; 
    $result = db_exec($q);
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
    $result = db_query($q); 
}

function delete_database($db_id)
{
    $q = 'DELETE from db_perm WHERE dbpid='.$db_id;
    $result = db_query($q);
}

function get_proxy($proxyid)
{
    $proxyid = intval($proxyid);
    $q = "SELECT proxyname, proxyid, frontend_ip, ".
         "frontend_port, dbtype, status, backend_server, ".
         "backend_ip, backend_port ".
         "FROM proxy WHERE proxyid=$proxyid";
    $result = db_query($q);
    $row = db_fetch_array($result);
    return $row;
}

function add_proxy($proxy)
{
    $proxy['frontend_ip'] = db_escape_string($proxy['frontend_ip']);
    $proxy['backend_ip'] = db_escape_string($proxy['backend_ip']);
    $proxy['backend_server'] = db_escape_string($proxy['backend_server']);

    #check if this backend already used
    $q = "SELECT * from proxy WHERE ".
    "frontend_ip = '".$proxy['frontend_ip']."' AND ".
    "frontend_port = ".$proxy['frontend_port'];
    $result = db_query($q);
    $row = db_fetch_array($result);
    if ($row)
        return "Failed to add new proxy, same frontend ip and port already used.";
    $q = "INSERT into proxy (proxyname, frontend_ip, frontend_port, ".
    "backend_server, backend_ip, backend_port, dbtype, status) VALUES (".
    "'".$proxy['proxyname']."', ".
    "'".$proxy['frontend_ip']."', ".
    $proxy['frontend_port'].", ".
    "'".$proxy['backend_server']."', ".
    "'".$proxy['backend_ip']."', ".
    $proxy['backend_port'].", ".
    "'".$proxy['dbtype']."',0)"; 
    $result = db_exec($q);
}

function update_proxy($proxy)
{
    $proxy['frontend_ip'] = db_escape_string($proxy['frontend_ip']);
    $proxy['backend_ip'] = db_escape_string($proxy['backend_ip']);
    $proxy['backend_server'] = db_escape_string($proxy['backend_server']);

    #check if this backend already used
    $q = "SELECT * from proxy WHERE ".
    "frontend_ip='".$proxy['frontend_ip']."' AND ".
    "frontend_port = ".$proxy['frontend_port']." AND ".
    "proxyid != ".$proxy['proxyid'];

    $result = db_query($q);
    $row = db_fetch_array($result);
    if ($row)
        return "Failed to update proxy, same frontend ip and port already used.";
    $q = "UPDATE proxy SET ".
    "proxyname = '".$proxy['proxyname']."', ".
    "frontend_ip = '".$proxy['frontend_ip']."', ".
    "frontend_port = ".$proxy['frontend_port'].", ".
    "backend_server = '".$proxy['backend_server']."', ".
    "backend_ip = '".$proxy['backend_ip']."', ".
    "backend_port = ".$proxy['backend_port'].", ".
    "dbtype = '".$proxy['dbtype']."', ".
    "status = 0 WHERE proxyid = ".$proxy['proxyid'];
    $result = db_query($q);
}

function get_proxies()
{
    $q = "SELECT proxyname, proxyid, frontend_ip, ".
         "frontend_port, dbtype, status, backend_server, ".
         "backend_ip, backend_port ".
         "FROM proxy";
    $result = db_query($q);
    
    $proxies = array();
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        $proxies[] = $row;
    }
    return $proxies;
}

function get_alerts($header, $status, $proxyid, $db_id, $db_name, $from, $count )
{
    $status = intval($status);
    $q = "SELECT alert_group.*,proxy.proxyname, db_name ".
         "FROM alert_group, proxy ".
         "WHERE alert_group.proxyid=proxy.proxyid ".
         "AND alert_group.status = $status ";
    if ($proxyid)
      $q .= "AND proxy.proxyid = $proxyid AND alert_group.db_name='$db_name' ";
    # check if default db
    #else if ($db_id == 1)
    #  $q .= "AND alert_group.db_name='' ";
    $q = add_query_sort($header, $q);
    $q = add_query_limit($q, $from, $count );
    #print "q: $q<br/>";
    $result = db_query($q);

    $alerts = array();
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        //if (strlen($row['pattern']) > 85)
    //{
        //    $row['short_pattern'] = htmlspecialchars(substr($row['pattern'], 0, 80)."...");
    //} else {
    //    $row['short_pattern'] = htmlspecialchars($row['pattern']);
    //}
        $row['pattern'] = htmlspecialchars($row['pattern']);
        $alerts[] = $row;
    }
    return $alerts;
}

function get_whitelist($header, $proxyid, $db_id, $db_name, $from, $count )
{
    $q = "SELECT queryid, proxy.proxyname, query, db_name FROM query, proxy ".
         "WHERE query.proxyid = proxy.proxyid ";
    if ($proxyid)
      $q .= "AND query.proxyid = $proxyid AND query.db_name='$db_name' ";
    # check if default db
    else if ($db_id == 1)
      $q .= "AND query.db_name='' ";
    $q = add_query_sort($header, $q);
    $q = add_query_limit($q, $from, $count );
    #print "q: $q<br/>";
    $result = db_query($q);
    $rows = array();
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        //if (strlen($row['query']) > 85)
        //{
        //    $row['short_pattern'] = htmlspecialchars(substr($row['query'], 0, 80)."...");
        //} else {
        //    $row['short_pattern'] = htmlspecialchars($row['query']);
        //}
        $row['query'] = htmlspecialchars($row['query']);
        $rows[] = $row;
    }
    return $rows;
}

function get_whitelist_size($proxyid, $db_id, $db_name)
{
    $q = "SELECT count(*) FROM query, proxy ".
         "WHERE query.proxyid = proxy.proxyid ";
    if ($proxyid)
      $q .= "AND query.proxyid = $proxyid AND query.db_name='$db_name' ";
    # check if default db
    else if ($db_id == 1)
      $q .= "AND query.db_name='' ";

    $result = db_query($q);
    $row = db_fetch_array($result);
    if (!$row)
      return 0;
    return $row[0];
}

function get_whitelist_entry($queryid)
{
    $agroupid=intval($agroupid);
    //$q = "SELECT query.*, proxy.proxyname ".
    //     "FROM query, proxy ".
    //     "WHERE query.proxyid = proxy.proxyid AND query.queryid = $queryid";

    $q = "SELECT query.*, proxy.proxyname, db_perm.dbpid as db_id ". 
         "FROM query, proxy, db_perm ".
         "WHERE query.proxyid = proxy.proxyid AND query.queryid = $queryid ".
         "AND ((query.db_name = db_perm.db_name AND proxy.proxyid = db_perm.proxyid ) OR ".
               "(query.db_name = '' AND db_perm.dbpid = 1))";
    $result = db_query($q);

    $row = array();
    $row = db_fetch_array($result);

    if (!$row)
        return $row;
    $row['query'] = htmlspecialchars($row['query']);
    return $row;
}

function del_whitelist_entry($entry)
{
  $q = 'SELECT agroupid from alert_group WHERE proxyid='.
       $entry['proxyid']." AND db_name='".$entry['db_name']."' ".
       "AND pattern='".$entry['query']."'";
  $result = db_query($q);
  $row = db_fetch_array($result);
  if ($row)
  {
    $agroupid = $row[0];
    //ignore_alert($agroupid);
    delete_alert($agroupid);
  }
  $q = 'DELETE from query WHERE queryid='.$entry['queryid'];
  $result = db_query($q);
}

function add_whitelist_entry($pattern)
{
    $q = "INSERT INTO query(proxyid,db_name,query) VALUES ('".db_escape_string($pattern['proxy']).
         "','".db_escape_string($pattern['db_name'])."','".db_escape_string($pattern['pattern'])."');";
    $result = db_exec($q);
    return;
}

function get_num_alerts( $status, $proxyid, $db_id, $db_name)
{
    $status = intval($status);
    $q = "SELECT count(*) ".
         "FROM alert_group ".
         "WHERE alert_group.status = $status ";
    if ($proxyid)
      $q .= "AND alert_group.proxyid = $proxyid AND alert_group.db_name='$db_name' ";
    # check if default db
    #else if ($db_id == 1)
    #  $q .= "AND alert_group.db_name='' ";
    
    $result = db_query($q);
    $row = db_fetch_array($result);
    if (!$row)
      return 0;
    return $row[0];
}


function get_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "SELECT alert_group.*,proxy.proxyname, db_perm.dbpid as db_id ".
         "FROM alert_group, proxy, db_perm ".
         "WHERE alert_group.proxyid=proxy.proxyid AND agroupid = $agroupid ".
         "AND alert_group.db_name = db_perm.db_name AND proxy.proxyid = db_perm.proxyid ".
         "ORDER BY update_time DESC";
    $result = db_query($q);

    $row = array();
    $row = db_fetch_array($result);
    
    if (!$row)
    {
      $q = "SELECT alert_group.*,proxy.proxyname, db_perm.dbpid as db_id ".
         "FROM alert_group, proxy, db_perm ".
         "WHERE alert_group.proxyid=proxy.proxyid AND agroupid = $agroupid ".
         "AND db_perm.dbpid = 1 ".
         "ORDER BY update_time DESC";
      $result = db_query($q);
      $row = db_fetch_array($result);
    }
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

function get_raw_alerts_with_limit($agroupid, $limit)
{
    $agroupid=intval($agroupid);
    $q = "SELECT * FROM alert WHERE agroupid=$agroupid ".
         "ORDER BY event_time DESC LIMIT $limit";
    $result = db_query($q);

    $alerts = array();
    $row = array();
    while ($row = db_fetch_array($result) )
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
        } else if ($row['block'] == 4)
        {
            $row['block_str'] = "error";
    } else {
        $row['block_str'] = "unknown";
    }
        $row['query'] = htmlspecialchars($row['query']);
        $alerts[] = $row;
    }

    return $alerts;
}

function get_num_raw_alerts($status, $proxyid = 0, $sysdbtype = 'user_db', $db_id = 0, $db_name = "")
{
    if ($db_id == 0)
    {
      // no database selected
      $q = "SELECT count(alert.alertid) FROM alert, alert_group, proxy ".
         "WHERE alert.agroupid = alert_group.agroupid AND alert_group.proxyid=proxy.proxyid";
    } else if ($sysdbtype == 'user_db') {
      // non default db selected
      $q = "SELECT count(alert.alertid) FROM alert, alert_group, proxy ".
           "WHERE alert.agroupid = alert_group.agroupid ".
           "AND alert_group.proxyid=proxy.proxyid AND alert_group.db_name='$db_name'";
    } else if ($sysdbtype == 'empty_mysql') {
      // all mysql alerts on an empty mysql database
      $q = "SELECT count(alert.alertid) ".
           "FROM alert, alert_group, proxy ".
           "WHERE alert.agroupid = alert_group.agroupid ".
           "AND alert_group.proxyid=proxy.proxyid AND alert_group.db_name='' ".
           "AND proxy.dbtype='mysql'";
    } else if ($sysdbtype == 'default_mysql') {
      // all mysql alerts on databases that were not priorly defined
      $q = "SELECT count(alert.alertid) ".
           "FROM alert INNER JOIN alert_group ON (alert.agroupid = alert_group.agroupid) ".
           "INNER JOIN proxy ON (alert_group.proxyid=proxy.proxyid) ".
           "LEFT JOIN db_perm ON (alert_group.db_name=db_perm.db_name) ".
           "WHERE db_perm.db_name IS NULL AND alert_group.db_name!='' ".
           "AND proxy.dbtype='mysql'";
    } else if ($sysdbtype == 'default_pgsql') {
      $q = "SELECT count(alert.alertid) ".
           "FROM alert INNER JOIN alert_group ON (alert.agroupid = alert_group.agroupid) ".
           "INNER JOIN proxy ON (alert_group.proxyid=proxy.proxyid) ".
           "LEFT JOIN db_perm ON (alert_group.db_name=db_perm.db_name) ".
           "WHERE db_perm.db_name IS NULL AND alert_group.db_name!='' ".
           "AND proxy.dbtype='pgsql'";
    }
    if ($status != -1)
    {
        $q .= " AND alert_group.status = $status ";
    } else {
        $q .= " AND alert_group.status IN (0,2) ";
    }

    $result = db_query($q);
    $row = db_fetch_array($result);
    if (!$row)
      return 0;
    return $row[0];
}

function get_raw_alerts($header, $status, $sysdbtype = 'user_db', $proxyid = 0, $db_id = 0, $db_name = "", $from = 0, $count=7 )
{
    global $tokenname;
    global $tokenid;
    $q = '';
    if ($db_id == 0)
    {
      // no database selected
      $q = "SELECT alert.*, proxy.proxyname, alert_group.db_name, alert_group.status ".
           "FROM alert, alert_group, proxy ".
           "WHERE alert.agroupid = alert_group.agroupid AND alert_group.proxyid=proxy.proxyid";
    } else if ($sysdbtype == 'user_db') {
      // non default db selected
      $q = "SELECT alert.*, proxy.proxyname, alert_group.db_name, alert_group.status ".
           "FROM alert, alert_group, proxy ".
           "WHERE alert.agroupid = alert_group.agroupid ".
           "AND alert_group.proxyid=proxy.proxyid AND alert_group.db_name='$db_name'";
    } else if ($sysdbtype == 'empty_mysql') {
      // all mysql alerts on an empty mysql database
      $q = "SELECT alert.*, proxy.proxyname, alert_group.db_name ".
           "FROM alert, alert_group, proxy ".
           "WHERE alert.agroupid = alert_group.agroupid ".
           "AND alert_group.proxyid=proxy.proxyid AND alert_group.db_name='' ".
           "AND proxy.dbtype='mysql'";
    } else if ($sysdbtype == 'default_mysql') {
      // all mysql alerts on databases that were not priorly defined
      $q = "SELECT alert.*, proxy.proxyname, alert_group.db_name, alert_group.status ".
           "FROM alert INNER JOIN alert_group ON (alert.agroupid = alert_group.agroupid) ".
           "INNER JOIN proxy ON (alert_group.proxyid=proxy.proxyid) ".
           "LEFT JOIN db_perm ON (alert_group.db_name=db_perm.db_name) ".
           "WHERE db_perm.db_name IS NULL AND alert_group.db_name!='' ".
           "AND proxy.dbtype='mysql'";
    } else if ($sysdbtype == 'default_pgsql') {
      $q = "SELECT alert.*, proxy.proxyname, alert_group.db_name, alert_group.status ".
           "FROM alert INNER JOIN alert_group ON (alert.agroupid = alert_group.agroupid) ".
           "INNER JOIN proxy ON (alert_group.proxyid=proxy.proxyid) ".
           "LEFT JOIN db_perm ON (alert_group.db_name=db_perm.db_name) ".
           "WHERE db_perm.db_name IS NULL AND alert_group.db_name!='' ".
           "AND proxy.dbtype='pgsql'";
    }
    if ($status != -1)
    {
        $q .= " AND alert_group.status = $status ";
    } else {
        $q .= " AND alert_group.status IN (0,2) ";
    }

    #  $q .= "AND alert_group.db_name='' ";
    $q = add_query_sort($header, $q);
    $q = add_query_limit($q, $from, $count );

    #print "q: $q<br/>"; 
    $result = db_query($q);

    $alerts = array();
    $row = array();
    while ($row = db_fetch_array($result) )
    {
        $row['Description'] = join("; ", split("\n", $row['reason']));
        # fix .; inside the description string
        $row['Description'] = preg_replace("/\.;/",";",$row['Description']);
        $row['Description'] = '<a href="alert_view.php?agroupid='.$row['agroupid'].
                              '&'.$tokenname.'='.$tokenid.'">'.$row['Description'].'</a>';
        if ($row['status'])
        {
          $row['Description'] = '<font color="gray" size="1.2">hidden: </font>' . $row['Description'];
        }
        $row['reason'] = str_replace("\n", "<br/>\n", $row['reason']);
        if ($row['block'] == 1)
        {
            $row['block'] = "<font color='red'>blocked</font>";
        $row['color'] = "#ffe9e9"; # a light red
        } else if ($row['block'] == 0)
        {
            $row['block'] = "<font color='orange'>warning</font>";
        $row['color'] = "#ffffe0"; # a light orange
        } else if ($row['block'] == 2)
        {
            $row['block'] = "<font color='red'>high risk</font>";
            $row['color'] = "#ffffe0"; # a light orange
        } else if ($row['block'] == 3)
        {
            $row['block'] = "low";
            $row['color'] = "#f9f9f9"; # a light grey
        } else if ($row['block'] == 4)
        {
            $row['block'] = "error";
            $row['color'] = "#f9f9f9"; # a light grey
        } else {
            $row['block'] = "unknown";
        $row['color'] = "#f9f9f9"; # a light grey
        }

        // if (strlen($row['description']) > 120)
        //{
        //    $row['short_description'] = substr($row['description'], 0, 100)."...";
        //} else {
        //    $row['short_description'] = $row['description'];
        //}
        //if (strlen($row['query']) > 85)
        //{
        //    $row['short_query'] = htmlspecialchars(substr($row['query'], 0, 80)."...");
        //} else {
        //    $row['short_query'] = htmlspecialchars($row['query']);
        //}
        $row['query'] = htmlspecialchars($row['query']);
        //$row['pattern'] = htmlspecialchars($row['pattern']);

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
           "db_name='".db_escape_string($alert['db_name'])."' AND ".
           "proxyid=".intval($alert['proxyid']);
    }
    $result = db_query($q);
    $row = db_fetch_array($result);
    if (!$row)
    {
        # this object was not found
        $q = "INSERT INTO db_perm (proxyid, db_name) ".
         "values (".$alert['proxyid'].",".
         "'".db_escape_string($alert['db_name'])."')"; 
         #print $q;
       $result = db_query($q);
    }

    # decode html tags
    $pattern = preg_replace(array('/&lt;/s', '/&gt;/s', '/&quot;/s'), array('<', '>', '"'), $alert['pattern']);
    $pattern = db_escape_string($pattern);

    $q = "INSERT INTO query (proxyid,perm,db_name,query) ".
         "VALUES(".$alert['proxyid'].",1,'".$alert['db_name']."','".$pattern."')";
    $result = db_query($q);

    $q = "UPDATE alert_group set status=1 WHERE agroupid=$agroupid";
    $result = db_query($q);
}

function ignore_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "UPDATE alert_group set status=2 WHERE agroupid=$agroupid";
    $result = db_query($q);
}

function delete_alert($agroupid)
{
    $agroupid=intval($agroupid);
    $q = "DELETE from alert_group WHERE agroupid=$agroupid";
    $result = db_query($q);
    $q = "DELETE from alert WHERE agroupid=$agroupid";
    $result = db_query($q);
}

function delete_raw_alert($agroupid, $alertid)
{
    $q = "DELETE from alert WHERE agroupid=$agroupid AND alertid=$alertid";
    $result = db_query($q);
}

function truncate_alerts()
{
    $q = "truncate alert_group";
    $result = db_query($q);
    $q = "truncate alert";
    $result = db_query($q);
    $q = "truncate query";
    $result = db_query($q);
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
    @fclose($fp);
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

function get_news()
{
    global $cache_dir;
    $file = $cache_dir . DIRECTORY_SEPARATOR . "news.txt";
    $fp = @fopen($file, "r");
    if (!$fp)
    {
        error_log("GreenSQL Console: failed to read news file $file");
        return array();
    }
    $fsize = filesize($file);
    if ($fsize == 0)
    {
        fclose($fp);
        return array("");
    }
    $data = array();
    $row = array();
    for ($i = 0; $i < 4 && !feof($fp); $i++)
    {
        $str = fgets($fp);
        $str = chop($str);
        $str = htmlspecialchars($str);
        $row = explode("|", $str);
        $data[] = array("date" => $row[0], "title" => $row[1],
                       "link" => $row[2]);
    }
    @fclose($fp);
    return $data;
}

function get_twitts()
{
    global $cache_dir;
    $file = $cache_dir . DIRECTORY_SEPARATOR . "twitts.txt";
    $fp = @fopen($file, "r");
    if (!$fp)
    {
        error_log("GreenSQL Console: failed to read twitts file $file");
        return array();
    }
    $fsize = filesize($file);
    if ($fsize == 0)
    {
        fclose($fp);
        return array("");
    }
    $data = array();
    $row = array();
    for ($i = 0; $i < 4 && !feof($fp); $i++)
    {
        $str = fgets($fp);
        $str = chop($str);
        $str = htmlspecialchars($str);
        $row = explode("|", $str);
        $data[] = array("date" => $row[0], "title" => $row[1],
                        "link" => $row[2]);
    }
    @fclose($fp);
    return $data;
}

function exec_php_file($app)
{
	print "getting news\n";
  global $cache_dir;
  if (function_exists('pcntl_fork'))
  {
    $pid = pcntl_fork();
    if ($pid == 0)
    {
      include_once($app);
      exit;
    }
    return;
  }
  // check if we are in unix like os
  if (!check_fn_disabled('exec') && file_exists("/dev/null") )
  {
    if (file_exists("/usr/bin/php"))
    {
      if (exec("/usr/bin/php $app >/dev/null &") !== FALSE && is_file($cache_dir.DIRECTORY_SEPARATOR.'news.txt'))
      {
        return;
      }
    }# else if (exec("php $app >/dev/null &") !== FALSE)
    #{
    #  return;
    #}
  }
  // few variants for windows
  if (DIRECTORY_SEPARATOR == '\\')
  {
    $out = "";
    $ret = "";
    if (!check_fn_disabled('exec') && 
      exec('start /B php $app',$out,$ret) !== FALSE)
    {
      return;
    }

    if (class_exists("COM"))
    {
      $WshShell = new COM("WScript.Shell");
      if ($WshShell)
      {
        $oExec = $WshShell->Run($app, 0, false);
        $WshShell->Release();
        return;
      }
    }

    if (!check_fn_disabled('popen') && !check_fn_disabled('pclose') &&
        pclose(popen("start php $app", "r")) !== FALSE)
    {
      return;
    }
  }
  #if we fail to start $app in the background, include it now.
  include_once($app);
}

// this function checks if specific high priviledge command is enabled
function check_fn_disabled($fn)
{
  return in_array( $fn, explode( ',',ini_get( 'disable_functions' ) ) );
}


function get_primary_menu()
{
  global $tokenname;
  global $tokenid;

  $script = ereg_replace(".*/", "", $_SERVER['SCRIPT_NAME']);
  $msg = '<ul class="menu">';
  if ($script == "dashboard.php")
    $msg .= '<li class="active"><a href="dashboard.php?'.$tokenname.'='.$tokenid.'">Dashboard</a></li>';
  else
    $msg .= '<li><a href="dashboard.php?'.$tokenname.'='.$tokenid.'">Dashboard</a></li>';
  if (substr($script, 0,2) == "db" || substr($script, 0,5) == "proxy" || 
      substr($script,0, strlen('whitelist')) == "whitelist" || $script =="alert_view.php")
    $msg .= '<li class="active"><a href="db_list.php?'.$tokenname.'='.$tokenid.'">Databases</a></li>';
  else
    $msg .= '<li><a href="db_list.php?'.$tokenname.'='.$tokenid.'">Databases</a></li>';
  if ($script == "rawalert_list.php")
    $msg .= '<li class="active"><a href="rawalert_list.php?'.$tokenname.'='.$tokenid.'">Alerts</a></li>';
  else
    $msg .= '<li><a href="rawalert_list.php?'.$tokenname.'='.$tokenid.'">Alerts</a></li>';

  if (substr($script, 0, 4) == "user" || substr($script, 0,6) == "backup" || 
      $script == "cleanall.php" || $script == "log_view.php")
    $msg .= '<li class="active"><a href="user_list.php?'.$tokenname.'='.$tokenid.'">System</a></li>';
  else
    $msg .= '<li><a href="user_list.php?'.$tokenname.'='.$tokenid.'">System</a></li>';
  $msg .= '<li><a href="http://www.greensql.net/forum">Forums</a></li>';
  $msg .= '<li class="logout"><a href="logout.php">Logout</a></li></ul>';

  return $msg;

}

function get_top_system_menu()
{
  global $tokenname;
  global $tokenid;

  $script = ereg_replace(".*/", "", $_SERVER['SCRIPT_NAME']);
  $msg = '<div id="secondary-menu"><ul class="menu">';
  if ($script == "user_list.php" || $script == "user_add.php" || $script == "user_delete.php" || $script == "user_edit.php" )
    $msg .= '<li class="active"><a href="user_list.php?'.$tokenname.'='.$tokenid.'">Users</a></li>';
  else
    $msg .= '<li><a href="user_list.php?'.$tokenname.'='.$tokenid.'">Users</a></li>';
  if ($script == "backuprestore.php")
    $msg .= '<li class="active"><a href="backuprestore.php?'.$tokenname.'='.$tokenid.'">Backup&Restore</a></li>';
  else
    $msg .= '<li><a href="backuprestore.php?'.$tokenname.'='.$tokenid.'">Backup&Restore</a></li>';
  if ($script == "log_view.php")
    $msg .= '<li class="active"><a href="log_view.php?'.$tokenname.'='.$tokenid.'">View Log</a></li>';
  else
    $msg .= '<li><a href="log_view.php?'.$tokenname.'='.$tokenid.'">View Log</a></li>';
  if ($script == "cleanall.php")
    $msg .= '<li class="active"><a href="cleanall.php?'.$tokenname.'='.$tokenid.'">Clean alerts</a></li>';
  else
    $msg .= '<li><a href="cleanall.php?'.$tokenname.'='.$tokenid.'">Clean alerts</a></li>';
  $msg .= '</ul></div>';
  return $msg;
}

function get_top_db_menu()
{
  global $tokenname;
  global $tokenid;

  $script = ereg_replace(".*/", "", $_SERVER['SCRIPT_NAME']);
  $msg = '<div id="secondary-menu"><ul class="menu">';
  if ((substr($script, 0,2) == "db" && $script != "db_add.php") || $script == "rawalert_list.php" || $script == "proxy_edit.php" ||
      substr($script,0, strlen('whitelist')) == "whitelist" || $script == "alert_view.php")
    $msg .= '<li class="active"><a href="db_list.php?'.$tokenname.'='.$tokenid.'">Databases</a></li>';
  else
    $msg .= '<li><a href="db_list.php?'.$tokenname.'='.$tokenid.'">Databases</a></li>';
  if ($script == "db_add.php")
    $msg .= '<li class="active"><a href="db_add.php?type=newdb&'.$tokenname.'='.$tokenid.'">Add Database</a></li>';
  else
    $msg .= '<li><a href="db_add.php?type=newdb&'.$tokenname.'='.$tokenid.'">Add Database</a></li>';
  if ($script == "proxy_add.php")
    $msg .= '<li class="active"><a href="proxy_add.php?'.$tokenname.'='.$tokenid.'">Add Proxy</a></li>';
  else
    $msg .= '<li><a href="proxy_add.php?'.$tokenname.'='.$tokenid.'">Add Proxy</a></li>';
  $msg .= '</ul></div>';
  return $msg;
}


function get_local_db_menu($db_name = "", $db_id = 0)
{
  global $tokenname;
  global $tokenid;
  $msg = "&nbsp;More for $db_name: ";
  $script = ereg_replace(".*/", "", $_SERVER['SCRIPT_NAME']);
  if ($script == "db_view.php")
  {
    $msg .= "<strong>Overview</strong> | ";

  } else {
    $msg .= "<a href='db_view.php?db_id=$db_id&$tokenname=$tokenid'>Overview</a> | ";
  }
  if ($script == "rawalert_list.php")
  {
    $msg .= "<strong>Alerts</strong> | ";
  } else {
    $msg .= "<a href='rawalert_list.php?db_id=$db_id&$tokenname=$tokenid'>Alerts</a> | ";
  }
  if ($script == "whitelist.php")
  {
    $msg .= "<strong>Whitelist</strong> | ";
  } else {
    $msg .= "<a href='whitelist.php?db_id=$db_id&$tokenname=$tokenid'>Whitelist</a> | ";
  }
  if ($script == "db_edit.php")
  {
    $msg .= "<strong>Settings</strong>";
  } else {
    $msg .= "<a href='db_edit.php?db_id=$db_id&$tokenname=$tokenid'>Settings</a>";
  }

  return $msg;
}

?>
