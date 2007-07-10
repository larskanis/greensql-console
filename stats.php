<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","GreenSQL Homepage");
$smarty->assign("Page","stats.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);
$smarty->assign("NUM_Dbs", count($dbs));

$status = 0;
$alerts = array();
$alerts = get_alerts($status);

$smarty->assign("NUM_Alers", count($alerts));

$user = "admin";
$pass = sha1("pwd");
if (check_user($user, $pass))
{
    $smarty->assign("def_pwd", 1);
} else {
    $smarty->assign("def_pwd", 0);
}

$smarty->display('index.tpl');

?>
