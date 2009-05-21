<?php

require 'lib.php';
global $smarty;
global $demo_version;
global $tokenid;
global $tokenname;

$error = "";
$msg = "";

$queryid = 0;
if (isset($_REQUEST['queryid']))
{
    $queryid = intval($_REQUEST['queryid']);
}

$smarty->assign("Name","Vew Whitelist Entry");
$smarty->assign("Page","whitelist_entry_view.tpl");

$entry = array();
$entry = get_whitelist_entry($queryid);
$db  = get_database($entry['db_id']);
$smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $entry['db_id']) );

if (isset($_POST['action']) && $_POST['action'] == "delete" && 
    $entry && $queryid && isset($_POST['confirm']))
{

#print_r($_POST);

    if ($demo_version)
    {
        $error .= "You can not delete whitelist entry in the demo mode.<br/>\n";
    }

    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
        $smarty->assign("msg", $msg);

    } else {
        del_whitelist_entry($entry);
        header("location: whitelist.php?db_id=".$entry['db_id']."&$tokenname=$tokenid");
        exit;
    }
    $smarty->assign("msg", $msg);
}
#if ($_POST['action'] == "delete" && $agroupid && $bad == 1)
#{
#    delete_alert($agroupid);
#    header("location: alert_list.php");
#    exit;
#}

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$smarty->assign("entry_queryid", $queryid);
$smarty->assign("entry_proxyname", $entry['proxyname']);
$smarty->assign("entry_db_name", $entry['db_name']);
$smarty->assign("entry_query", $entry['query']);

$smarty->display('index.tpl');

?>
