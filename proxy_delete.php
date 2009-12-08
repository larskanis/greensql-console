<?php
require_once 'lib.php';

global $demo_version;
global $smarty;

$msg = "";
$error = "";

$proxyid = 0;
if (isset($_REQUEST['proxyid']))
{
    $proxyid = abs(intval($_REQUEST['proxyid']));
}
$proxy = get_proxy($proxyid);

#if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'delete' &&
#    isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == 'on' && $proxy)
if (isset($_POST['delete']))
{
    if (proxy_in_use($proxyid))
    {
        $error .= "Proxy in use of database and can not be deleted.<br/>\n";
    } else if ($demo_version)
    {
        $error .= "You can not delete proxy in demo version.<br/>\n";
    } else {
        $error = delete_proxy($proxyid);
    }

    if (!$error)
    {
        $msg = "Proxy has been successfully deleted.<br/>You need to restart greensql firewall for the changes take effect.";
    } else {
        $msg = "<font color='red'>$error</font>";
    }
    $_SESSION['msg'] = $msg;
    header("location: db_list.php?$tokenname=$tokenid");
} else {
    $msg = "<font color='red'>Failed to delete proxy ".$proxy['proxyname'].$proxyid."</font>";
}

$smarty->assign("Page","delete.tpl");
$smarty->assign("DB_Name",$proxy['proxyname']);
$smarty->assign("Type","proxy");
$smarty->display('index.tpl');
#$_SESSION['msg'] = $msg;
#header("location: db_list.php?$tokenname=$tokenid");
?>
