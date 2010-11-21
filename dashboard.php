<?php

require 'lib.php';
global $smarty;

$error = "";
$msg = "";

$smarty->assign("Name","Dashboard");
$smarty->assign("Page","dashboard.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());

$dbs = get_databases_list();
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
$header = array();
$header[] = array('field' => 'event_time', 'title' => 'Date & Time', 'size'=> 170, 'sort' => 'desc');
$header[] = array('field' => 'proxyname', 'title' => 'Proxy', 'size' => 150);
$header[] = array('field' => 'db_name', 'title' => 'Database', 'size' => 100);
#$header[] = array('field' => 'user', 'title' => 'User', 'size' => 100);
$header[] = array('field' => 'pattern', 'title' => 'Pattern', 'size' => 'auto');
$header[] = array('field' => 'block', 'title' => 'Status', 'size' => 55);

$alerts = get_raw_alerts($header, $status);
$smarty->assign("alerts", display_table($header, $alerts));
if ($num_alerts >= 7)
{
  global $tokenname;
  global $tokenid;
  $more_alerts = 'rawalert_list.php?'.$tokenname.'='.$tokenid;
  if (isset($_REQUEST['sort']))
    $more_alerts .= '&sort='.htmlspecialchars($_REQUEST['sort']);
  if (isset($_REQUEST['order']))
    $more_alerts .= '&order='.htmlspecialchars($_REQUEST['order']);
  $smarty->assign("more_alerts", $more_alerts);
}
$news = get_news();
$smarty->assign("news", $news);

$twitts = get_twitts();
$smarty->assign("twitts",$twitts);

$smarty->display('index.tpl');

?>
