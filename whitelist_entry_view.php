<?php

require 'lib.php';
global $smarty;
global $demo_version;
global $tokenid;
global $tokenname;

$error = "";
$msg = "";

$queryid = intval($_REQUEST['queryid']);

$smarty->assign("Name","Vew Whitelist Entry");
$smarty->assign("Page","whitelist_entry_view.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

$entry = get_whitelist_entry($queryid);
$db  = get_database($entry['db_id']);
$smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $entry['db_id']) );

if ($_POST['submit'] && $queryid)
{
    if ($demo_version)
    {
        $error .= "You can not delete whitelist entry in the demo mode.<br/>\n";
    }

    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else if ($_POST['submit'] == 'Remove Whitelist') {
        del_whitelist_entry($entry);
        header("location: whitelist.php?db_id=".$entry['db_id']."&$tokenname=$tokenid");
        exit;
    } else if ($_POST['submit'] == 'Move To Alerts') {
        disapprove_alert($entry);
        $msg = "Whitelist has been moved to Alerts";
    }

    $smarty->assign("msg", $msg);
}

$entry = array();
$entry = get_whitelist_entry($queryid);

$dbs = get_databases_list();
$smarty->assign("databases", $dbs);

$smarty->assign('DB_ID', $entry[db_id]);

$smarty->assign("entry_queryid", $entry[queryid]);
$smarty->assign("entry_proxyname", $entry['proxyname']);
$smarty->assign("entry_db_name", $entry['db_name']);
$smarty->assign("entry_query", $entry['query']);

$smarty->display('index.tpl');

?>
