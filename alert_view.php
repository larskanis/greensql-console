<?php

require 'lib.php';
global $smarty;
global $demo_version;

$error = "";
$msg = "";

$agroupid = 0;
if (isset($_GET['agroupid']))
{
    $agroupid = intval($_GET['agroupid']);
}

$smarty->assign("Name","Vew Alert");
$smarty->assign("Page","alert_view.tpl");

$aler = array();
$alert = get_alert($agroupid);

if (isset($_POST['action']) && $_POST['action'] == "approve" && $agroupid)
{

    if ($demo_version)
    {
        $error .= "You can not change alert status in demo mode.<br/>\n";
    }

    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else {
        if ($_POST['submit'] == "Ignore this query")
        {
            ignore_alert($agroupid);
            $alert['status'] = 2;
        } elseif ($_POST['submit'] == "Allow this query") {
            approve_alert($agroupid,$alert);
            $alert['status'] = 1;
        }
    }
    $smarty->assign("msg", $msg);
}
#check if this query has bad format
if (strstr($alert['pattern'], "??") !== FALSE)
{
    $bad = 1;
} else {
    $bad = 0;
}
#if ($_POST['action'] == "delete" && $agroupid && $bad == 1)
#{
#    delete_alert($agroupid);
#    header("location: alert_list.php");
#    exit;
#}

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$smarty->assign("AGROUP_agroupid", $alert['agroupid']);
$smarty->assign("AGROUP_update_time", $alert['update_time']);
$smarty->assign("AGROUP_proxyname", $alert['proxyname']);
$smarty->assign("AGROUP_db_name", $alert['db_name']);
$smarty->assign("AGROUP_pattern", $alert['pattern']);
$smarty->assign("AGROUP_status", intval($alert['status']));
$smarty->assign("AGROUP_bad", $bad);

$alerts = get_raw_alerts_with_limit($agroupid, 20);
$smarty->assign("alerts", $alerts);

$smarty->display('index.tpl');

?>
