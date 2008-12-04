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
        $_SESSION['userid']= $u['userid'];
        $_SESSION['user'] = $user;
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
  $file = $cache_dir . DIRECTORY_SEPARATOR . "news.txt";

  if (file_exists($file) && filesize($file) > 0)
  {
    // we will fetch list of news once a day
    $stat = array();
    $stat = stat($file);
    $file_mdate = date ("F d Y", $stat['mtime']);
    $today = date ("F d Y", time());
    if ($today == $file_mdate)
    {
      return;
    }
  }

  exec_php_file($app);
}

?>
