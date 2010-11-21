<?php

require 'lib.php';
require 'help.php';
global $demo_version;
global $smarty;

$proxy_id = 0;
if (isset($_GET['proxyid']))
{
    $proxy_id = abs(intval($_GET['proxyid']));
}
$proxy = array();
#print_r($_POST);

if ($proxy_id && $proxy_id != 0)
{
    $proxy = get_proxy($proxy_id);
    $smarty->assign("Name","Edit GreenSQL Proxy: ".$proxy['proxyname']);
} else {
    $smarty->assign("SecondaryMenu", get_top_db_menu());
}
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());
$msg = "";
$error = "";

if($proxy['dbtype']=="mysql")
{
 $dbtypes = '<option value="mysql">MySQL</option>
             <option value="pgsql">PostgreSQL</option>';
}
else if($proxy['dbtype']=="pgsql")
{
 $dbtypes = '<option value="pgsql">PostgreSQL</option>
	     <option value="mysql">MySQL</option>';
}
$smarty->assign("DBTypes",$dbtypes);

if (isset($_POST['submit']))
{
    #data posted, db need to be updated
    $proxy['frontend_ip']    = trim(htmlspecialchars($_POST['frontend_ip']));
    $proxy['frontend_port']  = intval(trim($_POST['frontend_port']));
    $proxy['backend_server'] = trim(htmlspecialchars($_POST['backend_server']));
    $proxy['backend_ip']     = trim(htmlspecialchars($_POST['backend_ip']));
    $proxy['backend_port']   = intval(trim($_POST['backend_port']));
    $proxy['proxyname']      = trim(htmlspecialchars($_POST['proxyname']));
    $proxy['proxyid']        = intval(trim($_POST['proxyid']));
    $proxy['dbtype']         = strtolower(trim($_POST['dbtype']));
    #print_r($proxy);

    if ($proxy['dbtype'] != 'mysql' && $proxy['dbtype'] != 'pgsql')
    {
      $proxy['dbtype'] = 'mysql';
    } 
    if (strlen($proxy['proxyname']) == 0)
    {
        $error .= "Proxy Name is empty.<br/>\n";
    }
    else if (!preg_match("/^[a-zA-Z0-9_\.\ ]+$/", $proxy['proxyname']))
    {
        $error .= "Proxy Name is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9, '_', ' ' and '.'.<br/>\n";
    }

    if (strlen($proxy['backend_server']) == 0)
    {
        $error .= "Backend ServerName is empty.<br/>\n";
    }
    else if (!preg_match("/^[a-zA-Z0-9_\.]+$/", $proxy['backend_server']))
    {
        $error .= "Backend Server Name is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9, '_' and '.'.<br/>\n";
    }

    if (strlen($proxy['backend_ip']) == 0)
    {
        $error .= "Backend IP is empty.<br/>\n";
    } else if (ip2long($proxy['backend_ip']) == -1)
    {
        $error .= "Backend IP has wrong IP address format.<br/>\n";
    }
    if ($proxy['backend_port'] == 0)
    {
        $error .= "Backend Port can not be empty.<br/>\n";
        $proxy['backend_port'] = '';
    }

    if (strlen($proxy['frontend_ip']) == 0)
    {
        $error .= "Frontend IP is empty.<br/>\n";
    } else if (ip2long($proxy['frontend_ip']) == -1)
    {
        $error .= "Frontend IP has wrong IP address format.<br/>\n";
    }
    if ($proxy['frontend_port'] == 0)
    {
        $error .= "Frontend Port can not be empty.<br/>\n";
        $proxy['frontend_port'] = '';
    }

    if ($demo_version)
    {
        $error .= "You can not change proxy objects in demo version.<br/>\n";
    }
    else if ($error == "")
    {
        $error = update_proxy($proxy);
	if (!$error)
	{
	    $msg = "Proxy has been succesfully updated.";
	}
    }
    if ($error)
        $msg = "<font color='red'>$error</font>";
    
    $smarty->assign("Name","Edit Proxy: ".$proxy['proxyname']);
    $smarty->assign("msg", $msg); 
}

$smarty->assign("Page","proxy_edit.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());

$dbs = get_databases_list();
$smarty->assign("databases", $dbs);

$proxies = get_proxies();
$smarty->assign("proxies", $proxies);
if (count($proxy) > 1)
{
  $smarty->assign("PROXY_Name",         $proxy['proxyname']);
  $smarty->assign("PROXY_DBType",         $proxy['dbtype']);
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


