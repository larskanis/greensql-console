<?php
require_once 'lib.php';
require_once 'help.php';

global $smarty;
global $limit_per_page;
global $msg;

$start_id = 0;
if (isset($_GET['p']))
{
	$start_id = abs(intval($_GET['p']));
}

$smarty->assign("Name","Databases");
$smarty->assign("Page","db_list.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

if (isset($msg))
{
  $smarty->assign("msg", $msg);
} else if (isset($_SESSION['msg']) && strlen($_SESSION['msg']) > 0)
{
  $smarty->assign("msg", $_SESSION['msg']);
  $_SESSION['msg'] = '';
}

$header = array();
$header[] = array('field' => 'dbpid', 'title' => 'ID', 'size'=> '30px', 'sort' => 'asc');
$header[] = array('field' => 'db_name', 'title' => 'Database name', 'size'=> 'auto');
$header[] = array('title' => 'dbtype',  'title' => 'Db type', 'size' => '100px');
$header[] = array('field' => 'proxyname', 'title' => 'Proxy', 'size' => '200px');
$header[] = array('title' => 'status',  'title' => 'Mode', 'size' => '100px');
$header[] = array('title' => 'Options', 'size' => '250px');
$header[] = array('title' => 'Delete', 'size' => '100px');


$dbs = get_databases($header,$start_id*$limit_per_page,$limit_per_page);

$smarty->assign("databases", display_table($header, $dbs));

$header = array();
$header[] = array('field' => 'proxyid', 'title' => 'ID', 'size'=> '30px', 'sort' => 'asc');
$header[] = array('field' => 'proxyname', 'title' => 'Proxy name', 'size'=> 'auto');
$header[] = array('title' => 'Db type', 'size' => '100px');
$header[] = array('title' => 'Status', 'size' => '306px');
$header[] = array('title' => 'Options', 'size' => '250px');
$header[] = array('title' => 'Delete', 'size' => '100px');

$proxy = get_proxy_list();
$smarty->assign("proxy", display_table($header, $proxy));

$help_msg = get_section_help("db_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');
?>

