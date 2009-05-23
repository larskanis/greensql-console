<?php

require 'lib.php';
require 'help.php';
global $smarty;
global $tokenid;
global $tokenname;
global $limit_per_page;

$error = "";
$msg = "";

$status = abs(intval($_GET['status']));
if (!$status)
{
    $status = 0;
}
$start_id = 0;
if (isset($_GET['p']))
{
    $start_id = abs(intval($_GET['p']));
}
$db_id = 0;
if (isset($_GET['db_id']))
{
    $db_id = abs(intval($_GET['db_id']));
}

if ($status == 1)
{
  $smarty->assign("Name","Whitelist of approved queries");
} else {
  $smarty->assign("Name","List of Alerts");
}
$smarty->assign("Page","alert_list.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);
if ($db_id)
{
  $db  = get_database($db_id);
  $smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );
}
$smarty->assign("status", $status);

$header = array();
$header[] = array('field' => 'agroupid', 'title' => 'ID', 'size'=> 50, 'sort' => 1);
$header[] = array('field' => 'update_time', 'title' => 'Date & Time', 'size'=> 150);
$header[] = array('field' => 'proxyname', 'title' => 'Listener', 'size' => 100);
$header[] = array('field' => 'db_name', 'title' => 'DB', 'size' => 100);
$header[] = array('field' => 'pattern', 'title' => 'Pattern', 'size' => 'auto');

$alerts = get_alerts($header, $status, $db['proxyid'], $db_id, $db['db_name'],
            $start_id*$limit_per_page,$limit_per_page);
for ($i = 0; $i < count($alerts); $i++)
{
  $alerts[$i]['pattern'] = '<a href="alert_view.php?agroupid='.$alerts[$i]['agroupid'].
      '&'.$tokenname.'='.$tokenid.'">'.$alerts[$i]['pattern'].'</a>';
}

$smarty->assign("alerts", display_table($header, $alerts));

$numResults = get_num_alerts($status, $db['proxyid'], $db_id, $db['db_name']);
$smarty->assign("pager", get_pager($numResults));

$help_msg = get_section_help("alert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
