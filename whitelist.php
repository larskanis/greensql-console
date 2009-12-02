<?php
require_once 'lib.php';
require_once 'help.php';

global $smarty;
global $tokenid;
global $tokenname;
global $limit_per_page;

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

$smarty->assign("Name","Whitelist of allowed queries");
$smarty->assign("Page","whitelist.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

$dbs = get_databases_list();
$smarty->assign("databases", $dbs);
if ($db_id)
{
  $db  = get_database($db_id);
  $smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );
  $smarty->assign("DB_Name", $db['db_name']);
  $smarty->assign("DB_ID",$db_id);
}

$smarty->assign("DB_Name",$db['db_name']);

$header = array();
$header[] = array('field' => 'queryid', 'title' => 'ID', 'size'=> 50, 'sort' => 1);
$header[] = array('field' => 'proxyname', 'title' => 'Proxy', 'size' => 150);
$header[] = array('field' => 'db_name', 'title' => 'Database', 'size' => 75);
$header[] = array('field' => 'query', 'title' => 'Pattern', 'size' => 'auto');

$whitelist = get_whitelist($header, $db['proxyid'], $db_id, $db['db_name'], 
                           $start_id*$limit_per_page, $limit_per_page);
for ($i = 0; $i < count($whitelist); $i++)
{
  $whitelist[$i]['query'] = '<a href="whitelist_entry_view.php?queryid='.
       $whitelist[$i]['queryid'].'&'.$tokenname.'='.$tokenid.'">'.$whitelist[$i]['query']."</a>";
}
$smarty->assign("whitelist", display_table($header, $whitelist));

$numResults = get_whitelist_size($db['proxyid'], $db_id, $db['db_name']);
$smarty->assign("pager", get_pager($numResults));
$smarty->assign("db_id",$db_id);
$smarty->assign("proxy_id",$db['proxyid']);

$help_msg = get_section_help("alert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
