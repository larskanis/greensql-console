<?php

require 'lib.php';
require 'help.php';
global $smarty;
global $tokenid;
global $tokenname;
global $limit_per_page;

$error = "";
$msg = "";
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

$smarty->assign("Name","View alerts");
$smarty->assign("Page","rawalert_list.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);
if ($db_id)
{
  $db  = get_database($db_id);
  $smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );
}
$status = 0;

$header = array();
$header[] = array('field' => 'event_time', 'title' => 'Date & Time', 'size'=> 150, 'sort' => 'asc');
$header[] = array('field' => 'proxyname', 'title' => 'Listener', 'size' => 100);
$header[] = array('field' => 'db_name', 'title' => 'DB', 'size' => 100);
#$header[] = array('field' => 'user', 'title' => 'User', 'size' => 100);
$header[] = array('title' => 'Description', 'size' => 'auto');
$header[] = array('field' => 'block', 'title' => 'Status', 'size' => 100);

$alerts = get_raw_alerts($header, $status, $db['proxyid'], $db_id, $db['db_name'],
            $start_id*$limit_per_page,$limit_per_page);
$smarty->assign("alerts", display_table($header, $alerts));

$numResults = get_num_raw_alerts($status, $db['proxyid'], $db_id, $db['db_name']);
$smarty->assign("pager", get_pager($numResults));

$help_msg = get_section_help("rawalert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
