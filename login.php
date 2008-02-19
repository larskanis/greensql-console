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
        $_SESSION['userid']= $u['userid'];
        $_SESSION['user'] = $user;
	header("location: dashboard.php");
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

?>
