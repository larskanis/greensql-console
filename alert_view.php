<?php

require 'lib.php';
require 'help.php';
global $smarty;
global $demo_version;

$error = "";
$msg = "";

$agroupid = 0;
if (isset($_REQUEST['agroupid']))
{
    $agroupid = abs(intval($_REQUEST['agroupid']));
}
$alertid=0;
if (isset($_REQUEST['alertid']))
{
    $alertid = abs(intval($_REQUEST['alertid']));
}

$smarty->assign("Name","Vew Alert Pattern");
$smarty->assign("Page","alert_view.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

if (isset($_POST['submit']) && $agroupid && $alertid)
{
    if ($demo_version)
    {
        $error .= "You can not change alert status in demo mode.<br/>\n";
    }

    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else if ($_POST['submit'] == "Add to Whitelist") {
        if (approve_alert($agroupid,$alertid)) {
          $msg = "Pattern has been added to whitelist";
        }        
    } else if ($_POST['submit'] == "Remove Alert") {
      if (delete_raw_alert($agroupid,$alertid)) {
        $msg = "Alert has been removed";
      }
    }
}

$dbs = get_databases_list();
$smarty->assign("databases", $dbs);

$alert = array();
$alert = get_alert($agroupid,$alertid);
$db  = get_database($alert['db_id']);
$smarty->assign("DB_Menu", get_local_db_menu($alert['db_name'], $alert['db_id']) );

#check if this query has bad format
if (strstr($alert['pattern'], "??") !== FALSE)
{
    $bad = 1;
} else {
    $bad = 0;
}

$smarty->assign('DB_ID',$alert['db_id']);
$smarty->assign("AGROUP_agroupid", $alert['agroupid']);
$smarty->assign("AGROUP_update_time", $alert['update_time']);
$smarty->assign("AGROUP_proxyname", $alert['proxyname']);
$smarty->assign("AGROUP_db_name", $alert['db_name']);
$smarty->assign("AGROUP_pattern", $alert['pattern']);
$smarty->assign("AGROUP_status", intval($alert['status']));
$smarty->assign("AGROUP_bad", $bad);

$smarty->assign('AlertID',$alert['alertid']);
$smarty->assign('AlertDate',$alert['event_time']);
$smarty->assign('AlertRisk',$alert['risk']);
$smarty->assign('AlertReason',$alert['reason']);
$smarty->assign('AlertUser',$alert['dbuser']);
$smarty->assign('AlertUserIP',$alert['userip']);
$smarty->assign('AlertBlockString',$alert['block_str']);

$alerts = get_raw_alerts_with_limit($agroupid, 20);
$smarty->assign("alerts", $alerts);

$help_msg = get_section_help("alert_view");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

if ($msg) {
  $smarty->assign('msg',$msg);
}

$smarty->display('index.tpl');

?>
