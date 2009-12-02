<?php

require 'lib.php';
require 'help.php';
global $smarty;
global $demo_version;

$error = "";
$msg = "";

$smarty->assign("Name","Clean GreenSQL alerts");
$smarty->assign("Page","cleanall.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

$msg = "";
if (isset($_POST['submit']))
{
    if ($demo_version)
    {
        $error .= "You can not clean alerts in demo version.<br/>\n";
    } else {
        truncate_alerts();
        $msg = "All alerts have been removed.";
    }

    if ($error)
        $msg = "<font color='red'>$error</font>";

    $smarty->assign("msg", $msg);
}

$help_msg = get_section_help("cleanall");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
