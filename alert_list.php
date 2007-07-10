<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";

$status = intval($_GET[status]);
if (!$status)
{
    $status = 0;
}
$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","List of Alerts");
$smarty->assign("Page","alert_list.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$alerts = get_alerts($status);
$smarty->assign("alerts", $alerts);

$smarty->display('index.tpl');

?>
