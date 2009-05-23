<?php

require 'lib.php';
require 'help.php';
global $smarty;
global $demo_version;

$proxy_id = 0;
if (isset($_POST['proxyid']))
{
    $proxy_id = intval($_POST['proxyid']);
}
$db_name = "";
if (isset($_POST['dbname']))
{
    $db_name = trim($_POST['dbname']);
}
$error = "";
$msg = "";

$smarty->assign("Name","Add Database");
$smarty->assign("Page","db_add.tpl");

if ($proxy_id && $db_name)
{
    if (!ereg("^[a-zA-Z0-9_]+$",$db_name))
    {
        $error = "Database Name is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.";
    }
    if ($demo_version)
    {
       $error .= "Can not add new database in the demo version.<br/>\n";
    }
    if (!$error)
    {
        $error = add_database($proxy_id, $db_name);
    }
    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else {
        $msg = "Object has been created successfully.";
    }
    $smarty->assign("msg", $msg);
}

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$proxies = get_proxies();
$ids = array();
$names = array();

foreach ($proxies as $proxy)
{
    $ids[] = $proxy['proxyid'];
    $names[] = $proxy['proxyname'];
}

$smarty->assign("option_values", $ids);
$smarty->assign("option_output", $names);
$smarty->assign("option_selected", "1");

$help_msg = get_section_help("db_add");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
