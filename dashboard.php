<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","Dashboard");
$smarty->assign("Page","dashboard.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);
$smarty->assign("NUM_Dbs", count($dbs));

$status = 0;
$num_alerts = get_num_raw_alerts($status);

$smarty->assign("NUM_Alers", $num_alerts);

$user = "admin";
$pass = sha1("pwd");
if (check_user($user, $pass))
{
    $smarty->assign("def_pwd", 1);
} else {
    $smarty->assign("def_pwd", 0);
}

$status = 0;
$alerts = get_raw_alerts_bypage(0, 10, $status);
$smarty->assign("alerts", $alerts);

$smarty->display('index.tpl');

?>
