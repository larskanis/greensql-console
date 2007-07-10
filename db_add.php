<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$proxy_id = intval($_POST[proxyid]);
$db_name = $_POST[dbname];
$error = "";
$msg = "";

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","Add Database");
$smarty->assign("Page","db_add.tpl");

if ($proxy_id && $db_name)
{
    $error = add_database($proxy_id, $db_name);
    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    } else {
        $msg = "Object has been created sucsesfully";
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
    $ids[] = $proxy[proxyid];
    $names[] = $proxy[proxyname];
}

$smarty->assign("option_values", $ids);
$smarty->assign("option_output", $names);
$smarty->assign("option_selected", "1");

$smarty->display('index.tpl');

?>
