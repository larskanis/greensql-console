<?php

require 'lib.php';
require 'help.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","Clean GreenSQL alerts");
$smarty->assign("Page","cleanall.tpl");

$msg = "";
if (isset($_POST['submit']))
{
    #truncate_alerts();
    $msg = "Allerts have been removed.";
    $smarty->assign("msg", $msg);
}

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$help_msg = get_section_help("cleanall");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
