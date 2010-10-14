<?php
require_once 'lib.php';
require 'help.php';

global $demo_version;
global $smarty;
global $tokenid;
global $tokenname;

$type = "";
if (isset($_GET['type']))
{
    $type = $_GET['type'];
}

$proxy_id = 0;
if (isset($_POST['proxyid']))
{
    $proxy_id = abs(intval($_POST['proxyid']));
}

$db_name = "";
if (isset($_POST['dbname']))
{
    $db_name = trim($_POST['dbname']);
}

if (isset($msg))
{
  $smarty->assign("msg", $msg);
} else if (isset($_SESSION['msg']) && strlen($_SESSION['msg']) > 0)
{
  $smarty->assign("msg", $_SESSION['msg']);
  $_SESSION['msg'] = '';
}

$error = "";
$msg = "";

if ($proxy_id && $db_name)
{
    if (!ereg("^[a-zA-Z0-9_\ -]+$",$db_name))
    {
        $error .= "Database Name is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
    }
    if (strlen($db_name) > 100)
    {
        $error .= "Database name is too long.<br/>\n";
    }
    if ($demo_version)
    {
       $error .= "Can not add new database in the demo version.<br/>\n";
    }
    if (!$error)
    {
        $error = add_database($proxy_id, $db_name);
    }
    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else {
        $msg = "Database has been created successfully.";
        $_SESSION['msg'] = $msg;
        header("location: db_list.php?$tokenname=$tokenid");
        exit;
    }
    $smarty->assign("msg", $msg);
}

switch($type)
{
    case "newdb":
        $display = "disabled";
        $db_enabled = "enabled";
        if (isset($_GET['proxyid']))
            $proxyid = abs(intval($_GET['proxyid']));
        else
        {
            $proxies = get_proxy_list();
            if(count($proxies) > 0)
                $proxyid = $proxies[0]["proxyid"];
        }
        break;                
    case "newproxy":            
        $display = "enabled";
         $db_enabled = "disabled";
        $proxyid = 0;                    
        break;            
}

$proxy = get_proxies();
$proxies_combobox = '<select name="proxyid" onchange="ShowProxy(\''.$tokenname.'\',\''.$tokenid.'\');">';
foreach($proxy as $p)
{    
    $proxies_combobox .= '<option value="'.$p["proxyid"].'" ';
    if($proxyid == $p["proxyid"])
        $proxies_combobox .= 'selected>'.$p["proxyname"].'</option>';
    else $proxies_combobox .= '>'.$p["proxyname"].'</option>';    
}
$proxies_combobox .= '<option value="0"';

if($type == "newproxy")
    $proxies_combobox .= ' selected>Create New Proxy</option>';
else $proxies_combobox .= '>Create New Proxy</option>';

$proxies_combobox .= '</select>';

$smarty->assign("proxies_combobox",$proxies_combobox);

if($type == "newdb")
{
    $proxy = get_proxy($proxyid);

    $smarty->assign("PROXY_Name",         $proxy['proxyname']);
    $smarty->assign("PROXY_DBType",       $proxy['dbtype']);
    $smarty->assign("PROXY_ID",           $proxy['proxyid']);
    $smarty->assign("PROXY_FrontendIP",   $proxy['frontend_ip']);
    $smarty->assign("PROXY_FrontendPort", $proxy['frontend_port']);
    $smarty->assign("PROXY_BackendServer",$proxy['backend_server']);
    $smarty->assign("PROXY_BackendIP",    $proxy['backend_ip']);
    $smarty->assign("PROXY_BackendPort",  $proxy['backend_port']);
    $smarty->assign("PROXY_Enabled",      "disabled");

    $dbtypes = '<option>'.$proxy['dbtype'].'</option>';
}
else
{
    $smarty->assign("PROXY_Name",         '');
    $smarty->assign("PROXY_DBType",       '');
    $smarty->assign("PROXY_ID",           '');
    $smarty->assign("PROXY_FrontendIP",   '');
    $smarty->assign("PROXY_FrontendPort", '');
    $smarty->assign("PROXY_BackendServer",'');
    $smarty->assign("PROXY_BackendIP",    '');
    $smarty->assign("PROXY_BackendPort",  '');
    $smarty->assign("PROXY_Enabled",      "enabled");

    $proxy["proxyname"] = "";
    $proxy["frontend_ip"] = "";
    $proxy["frontend_port"] = "";
    $proxy["backend_server"] = "";
    $proxy["backend_ip"] = "";
    $proxy["backend_port"] = "";

     $dbtypes = '<option value="mysql">MySQL</option>'.
	         '<option value="pgsql">PostgreSQL</option>';

}
$smarty->assign("DBTypes",$dbtypes);
$smarty->assign("DB_Enabled",$db_enabled);
#$smarty->assign("proxyform",$proxyform);

$smarty->assign("Name","NewDatabase");
$smarty->assign("Page","db_add.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

$help_msg = get_section_help("db_add");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}


$smarty->display('index.tpl');

?>
