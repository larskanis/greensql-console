<?php

require 'lib.php';
global $smarty;

$error = "";
$msg = "";

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
$header = array();
$header[] = array('field' => 'event_time', 'title' => 'Date & Time', 'size'=> 150, 'sort' => 'desc');
$header[] = array('field' => 'proxyname', 'title' => 'Listener', 'size' => 100);
$header[] = array('field' => 'db_name', 'title' => 'DB', 'size' => 100);
#$header[] = array('field' => 'user', 'title' => 'User', 'size' => 100);
$header[] = array('title' => 'Description', 'size' => 'auto');
$header[] = array('field' => 'block', 'title' => 'Status', 'size' => 100);

$alerts = get_raw_alerts($header, $status);
$smarty->assign("alerts", display_table($header, $alerts));
if ($num_alerts >= 10)
{
  global $tokenname;
  global $tokenid;
  $more_alerts = 'rawalert_list.php?p=1&'.$tokenname.'='.$tokenid;
  if (isset($_REQUEST['sort']))
    $more_alerts .= '&sort='.$_REQUEST['sort'];
  if (isset($_REQUEST['order']))
    $more_alerts .= '&order='.$_REQUEST['order'];
  $smarty->assign("more_alerts", $more_alerts);
}
$news = get_news();
$smarty->assign("news", $news);

$smarty->display('index.tpl');

?>
