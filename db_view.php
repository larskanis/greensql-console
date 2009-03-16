<?php

require 'lib.php';
require 'help.php';

global $smarty;

$db_id = 0;
if (isset($_GET['db_id']))
{
    $db_id = abs(intval($_GET['db_id']));
}
if ($db_id == 0)
{
  $db_id = 1;
}

$dbs = get_databases();
$db  = get_database($db_id);

$smarty->assign("databases", $dbs);

$smarty->assign("Name","View database - ".$db['db_name']);
$smarty->assign("Page","db_view.tpl");
$smarty->assign("DB_Name", "Overview");
$smarty->assign("DB_ProxyName", $db['proxyname']);
$smarty->assign("DB_ProxyID", $db['proxyid']);
$smarty->assign("DB_Listener", $db['listener']);
$smarty->assign("DB_Backend", $db['backend']);
$smarty->assign("DB_Type", $db['dbtype']);
$smarty->assign("DB_ID", $db_id);
$smarty->assign("DB_Menu", get_local_db_menu($db['db_name'], $db_id) );

$enabled_str = "<font color=\"red\">enabled</font>";

$smarty->assign("DB_StatusId", $db['proxy_status']);
if ($db['proxy_status'] == 1)
{
    $smarty->assign("DB_Status", "<font color=\"green\">Listener is 'OK'</font>");
} else if ($db) {
    $smarty->assign("DB_Status", "Listener is 'DOWN'");
} else {
    $smarty->assign("DB_Status", "Listener is 'UNKNOWN'");
}
    
$block_status = '';
$mode = get_db_mode($db['status']);
$block_status = '<strong>'.$mode['mode'].'</strong><br/>'.$mode['help'];

if ($db['status'] == 11)
{
    $block_status .= "<br/>This mode enabled from: ".$db['status_changed'];
} else if ($db['status'] == 12)
{
    $block_status .= "<br/>This mode enabled from: ".$db['status_changed'];
}
$smarty->assign("DB_BlockStatus", $block_status);

if ( ($db['perms'] & 4) == 0 )
{
    $smarty->assign("DB_Alter", "blocked");
} else {
    $smarty->assign("DB_Alter", $enabled_str);
}

if ( ($db['perms'] & 1) == 0 )
{
    $smarty->assign("DB_Create", "blocked");
} else {
    $smarty->assign("DB_Create", $enabled_str);
}

if ( ($db['perms'] & 2) == 0 )
{
    $smarty->assign("DB_Drop", "blocked");
} else {
    $smarty->assign("DB_Drop", $enabled_str);
}

if ( ($db['perms'] & 8) == 0 )
{
    $smarty->assign("DB_Info", "blocked");
} else {
    $smarty->assign("DB_Info", $enabled_str);
}

if ( ($db['perms'] & 16) == 0 )
{
    $smarty->assign("DB_BlockQ", "blocked");
} else {
    $smarty->assign("DB_BlockQ", $enabled_str);
}


$smarty->display('index.tpl');
?>
