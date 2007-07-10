<?php

require 'lib.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";
$lines = 200;

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","View GreenSQL Log file");
$smarty->assign("Page","log_view.tpl");
$error = "";

$log = read_log($log_file, $lines, $error);
if ($error)
{
    print "error\n";
    $msg = "<font color='red'>$error</font>";
    $smarty->assign("msg", $msg);
}
		    
$log_data = "";
foreach ($log as $l)
{
    $log_data .= $l;
}

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$smarty->assign("Log", $log_data);
$smarty->assign("lines", $lines);

$smarty->display('index.tpl');

?>
