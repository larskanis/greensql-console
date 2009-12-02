<?php

session_start();
$_SESSION['login'] = 1;

require 'lib.php';
global $demo_version;
global $smarty;

$_SESSION['login'] = "";

$error = "";
$msg = "";

if (isset($_POST['login']) && isset($_POST['user']) && isset($_POST['pass']))
{
    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);
    $pass = sha1($pass);
    if ($u = check_user($user, $pass))
    {
        global $tokenid;
        global $tokenname;
        $_SESSION['userid']= $u['adminid'];
        $_SESSION['user'] = $user;
        generate_session_token();
	header("location: dashboard.php?$tokenname=$tokenid");
	exit;
    } else {
        $error = "Bad username/password.";
        $msg = "<font color='red'>$error</font>";
	$smarty->assign("msg", $msg);
    }
}

$smarty->assign("Name","GreenSQL");
$smarty->assign("demo",$demo_version);
$error = "";
$smarty->display('login.tpl');

# download news once a day
{
  $app = 'get_news.php';
  global $cache_dir;
  $news_file = $cache_dir . DIRECTORY_SEPARATOR . "news.txt";
  $twitts_file = $cache_dir . DIRECTORY_SEPARATOR . "twitts.txt";
  
  if ((file_exists($news_file) && filesize($news_file) > 0) &&
      (file_exists($twitts_file) && filesize($twitts_file) > 0))
  {
    // we will fetch list of news once a day
    $stat = array();
    $stat = stat($news_file);
    $file_mdate = date ("F d Y", $stat['mtime']);
    $today = date ("F d Y", time());

    $stat = stat($twitts_file);
    $tfile_mdate = date ("F d Y", $stat['mtime']);

    if ($today == $file_mdate && $today == $tfile_mdate)
    {
      return;
    }
  }

  exec_php_file($app);
}

?>
