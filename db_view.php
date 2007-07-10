<?

require 'lib.php';

require 'libs/Smarty.class.php';

$db_id = intval($_GET[id]);
if ($db_id == 0)
{
  $db_id = 1;
}

$smarty = new Smarty;
$smarty->compile_check = true;
#$smarty->debugging = true;


$dbs = get_databases();
$db  = get_database($db_id);

$smarty->assign("databases", $dbs);

$smarty->assign("Name","Vew database - $db[db_name]");
$smarty->assign("Page","db_view.tpl");
$smarty->assign("DB_Name", $db[db_name]);
$smarty->assign("DB_ProxyName", $db[proxyname]);
$smarty->assign("DB_ProxyID", $db[proxyid]);
$smarty->assign("DB_Listener", $db[listener]);
$smarty->assign("DB_Backend", $db[backend]);
$smarty->assign("DB_Type", $db[dbtype]);
$smarty->assign("DB_ID", $db_id);

$enabled_str = "<font color=\"red\">enabled</font>";

$smarty->assign("DB_StatusId", $db[status]);
if ($db[status] == 1)
{
    $smarty->assign("DB_Status", "<font color=\"green\">Listener is 'OK'</font>");
} else if ($db) {
    $smarty->assign("DB_Status", "Listener is 'DOWN'");
} else {
    $smarty->assign("DB_Status", "Listener is 'UNKNOWN'");
}
    

if ($db[alter_perm] == 0)
{
    $smarty->assign("DB_Alter", "blocked");
} else {
    $smarty->assign("DB_Alter", $enabled_str);
}

if ($db[create_perm] == 0)
{
    $smarty->assign("DB_Create", "blocked");
} else {
    $smarty->assign("DB_Create", $enabled_str);
}

if ($db[drop_perm] == 0)
{
    $smarty->assign("DB_Drop", "blocked");
} else {
    $smarty->assign("DB_Drop", $enabled_str);
}

if ($db[info_perm] == 0)
{
    $smarty->assign("DB_Info", "blocked");
} else {
    $smarty->assign("DB_Info", $enabled_str);
}

if ($db[block_q_perm] == 0)
{
    $smarty->assign("DB_BlockQ", "blocked");
} else {
    $smarty->assign("DB_BlockQ", $enabled_str);
}


$smarty->display('index.tpl');
?>
