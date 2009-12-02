<?php
require_once 'lib.php';
require_once 'help.php';
global $smarty;

$smarty->assign("Name","Administrators");
$smarty->assign("Page","user_list.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

if (isset($msg))
{
  $smarty->assign("msg", $msg);
} else if (isset($_SESSION['msg']) && strlen($_SESSION['msg']) > 0)
{
  $smarty->assign("msg", $_SESSION['msg']);
  $_SESSION['msg'] = '';
}

$header = array();
$header[] = array('field' => 'name', 'title' => 'Admin name', 'size'=> '20%');
$header[] = array('title' => 'Email', 'size'=> '20%');
$header[] = array('title' => 'Options', 'size' => '40%');
$header[] = array('title' => 'Delete', 'size' => '20%');

$admins=get_admins();

$smarty->assign("admins", display_table($header, $admins));
#$smarty->assign("admins",$admins);

$help_msg = get_section_help("user_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');
?>
