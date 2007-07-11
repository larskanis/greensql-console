<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","Clean GreenSQL alerts");
$smarty->assign("Page","cleanall.tpl");

$msg = "";
if ($_POST['submit'])
{
    truncate_alerts();
    $msg = "Allerts has been removed.";
    $smarty->assign("msg", $msg);
}

$dbs = get_databases();
$smarty->assign("databases", $dbs);


$smarty->display('index.tpl');

?>
