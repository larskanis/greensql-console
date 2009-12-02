<?php

require 'lib.php';
require 'help.php';
global $smarty;

$error = "";
$msg = "";

$smarty->assign("Name","View GreenSQL Log file");
$smarty->assign("Page","log_view.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

$error = "";

$log = read_log($log_file, $num_log_lines, $error);
if ($error)
{
    $msg = "<font color='red'>$error</font>";
    $smarty->assign("msg", $msg);
}
		    
$log_data = "";
foreach ($log as $l)
{
    $log_data .= $l;
}

$smarty->assign("Log", $log_data);

$help_msg = get_section_help("log_view");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
