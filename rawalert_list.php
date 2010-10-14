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
if (isset($_GET['per_page']))
{
    $limit_per_page = abs(intval($_GET['per_page']));
}

$smarty->assign("Name","View alerts");
$smarty->assign("Page","rawalert_list.tpl");

$dbs = get_databases_list();
$smarty->assign("databases", $dbs);
$db = array();
if ($db_id)
{
  $db  = get_database($db_id);
  $smarty->assign("PrimaryMenu", get_primary_menu());
  $smarty->assign("SecondaryMenu", get_top_db_menu());
  $smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );
  $smarty->assign("DB_Name", $db['db_name']);
  $smarty->assign("DB_ID",$db_id);
} else {
  $smarty->assign("PrimaryMenu", get_primary_menu());
  $db['proxyid'] = 0;
  $db['db_name'] = '';
}

#generate show/hide alerts link
$url = $_SERVER['REQUEST_URI'];
if (!$url)
{
  $url = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
}
$status = 0;
# fix token id if we generate a new one
$url = preg_replace("/\Q$tokenname\E=[a-zA-Z0-9]*/", "$tokenname=$tokenid", $url);
if (isset($_REQUEST['showhidden']))
{
  $url = preg_replace("/&showhidden=[a-zA-Z0-9]*/", "", $url);
  $url = preg_replace("/\?showhidden=[a-zA-Z0-9]*/", "?", $url);  
  $smarty->assign("ShowAll", '<a href="'.$url.'">Show Regular</a>');
  $show_status = 1;
  $status = -1;
} else {
  $url .= '&showhidden=1';
  $smarty->assign("ShowAll", '<a href="'.$url.'">Show Hidden</a>');
  $show_status = 0;
}

$header = array();
$header[] = array('field' => 'event_time', 'title' => 'Date & Time', 'size'=> 190, 'sort' => 'desc');
$header[] = array('field' => 'proxyname', 'title' => 'Proxy', 'size' => 150);
$header[] = array('field' => 'db_name', 'title' => 'DB', 'size' => 100);
$header[] = array('field' => 'pattern','title' => 'Pattern', 'size' => 'auto');
$header[] = array('field' => 'block', 'title' => 'Status', 'size' => 60);

$alerts = get_raw_alerts($header, $status, $db['sysdbtype'], $db['proxyid'], $db_id, $db['db_name'],
            $start_id*$limit_per_page,$limit_per_page);
$smarty->assign("alerts", display_table($header, $alerts, $show_status));

$numResults = get_num_raw_alerts($status, $db['proxyid'], $db['sysdbtype'], $db_id, $db['db_name']);
$smarty->assign("pager", get_pager($numResults));

$help_msg = get_section_help("rawalert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
