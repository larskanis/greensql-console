<?php

require 'lib.php';
require 'help.php';
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

$smarty->assign("Name","Whitelist of allowed queries");
$smarty->assign("Page","whitelist.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);
if ($db_id)
{
  $db  = get_database($db_id);
  $smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );
}

$header = array();
$header[] = array('field' => 'queryid', 'title' => 'ID', 'size'=> 50, 'sort' => 1);
$header[] = array('field' => 'proxyname', 'title' => 'Listener', 'size' => 100);
$header[] = array('field' => 'db_name', 'title' => 'DB', 'size' => 100);
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

$help_msg = get_section_help("alert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
