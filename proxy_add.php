<?

require 'lib.php';
require 'help.php';
global $demo_version;
global $smarty;

$proxy_id = 0;
if (isset($_GET['proxyid']))
{
    $proxy_id = intval($_GET['proxyid']);
}
$proxy = array();
#print_r($_POST);

if ($proxy_id && $proxy_id != 0)
{
    $proxy = get_proxy($proxy_id);
    $smarty->assign("Name","Edit GreenSQL Listener: ".$proxy['proxyname']);
}
$msg = "";
$error = "";

if (isset($_POST['submit']))
{
    #data posted, db need to be updated
    $proxy['frontend_ip']    = trim($_POST['frontend_ip']);
    $proxy['frontend_port']  = intval(trim($_POST['frontend_port']));
    $proxy['backend_server'] = trim($_POST['backend_server']);
    $proxy['backend_ip']     = trim($_POST['backend_ip']);
    $proxy['backend_port']   = intval(trim($_POST['backend_port']));
    $proxy['proxyname']      = trim($_POST['proxyname']);
    $proxy['proxyid']        = intval(trim($_POST['proxyid']));
    #print_r($proxy);

    if (strlen($proxy['backend_ip']) == 0 ||
        ip2long($proxy['backend_ip']) == -1)
    {
        $error = "Wrong format of the ip address (backend ip).<br/>";
    }
    if (strlen($proxy['frontend_ip']) == 0 ||
        ip2long($proxy['frontend_ip']) == -1)
    {
        $error = "Wrong format of the ip address (frontend ip).<br/>";
    }

    if ($proxy['frontend_port'] == 0 ||
        strlen($proxy['backend_server']) == 0 ||
	$proxy['backend_port'] == 9 ||
	strlen($proxy['proxyname']) == 0)
    {
        $error .= "Some fields are not correct.";
    }

    if ($demo_version)
    {
        $error = "You can not change proxy objects in demo version.";
    }
    else if ($error == "" && !$proxy['proxyid'] )
    {
        $error = add_proxy($proxy);
	if (!$error)
	{
	    $msg = "Listener has been succesfully added.";
	}
    } else if ($error == "")
    {
        $error = update_proxy($proxy);
	if (!$error)
	{
	    $msg = "Listener has been succesfully updated.";
	}
    }
    if ($error)
        $msg = "<font color='red'>$error</font>";
    
    $smarty->assign("Name","Edit listener: ".$proxy['proxyname']);
    $smarty->assign("msg", $msg); 
}

if (!$proxy_id)
{
    $proxy['frontend_ip'] = "127.0.0.1";
    $smarty->assign("Name","Add listener");
}

$smarty->assign("Page","proxy_add.tpl");

$proxies = get_proxies();
$smarty->assign("proxies", $proxies);
if (count($proxy) > 1)
{
  $smarty->assign("PROXY_Name",         $proxy['proxyname']);
  $smarty->assign("PROXY_ID",           $proxy['proxyid']);
#  $smarty->assign("PROXY_FrontendIP",   $proxy['frontend_ip']);
  $smarty->assign("PROXY_FrontendPort", $proxy['frontend_port']);
  $smarty->assign("PROXY_BackendServer",$proxy['backend_server']);
  $smarty->assign("PROXY_BackendIP",    $proxy['backend_ip']);
  $smarty->assign("PROXY_BackendPort",  $proxy['backend_port']);
}
$smarty->assign("PROXY_FrontendIP",   $proxy['frontend_ip']);

$help_msg = get_section_help("proxy_add");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');
?>


