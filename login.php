<?php

session_start();
$_SESSION[login] = 1;

require 'lib.php';

$_SESSION[login] = undef;

require 'libs/Smarty.class.php';
$error = "";
$msg = "";
$lines = 200;

$smarty = new Smarty;
$smarty->compile_check = true;

if ($_POST[login] && isset($_POST[user]) && isset($_POST[pass]))
{
    $user = trim($_POST[user]);
    $pass = trim($_POST[pass]);
    $pass = sha1($pass);
    if ($u = check_user($user, $pass))
    {
        $_SESSION[userid]= $u[userid];
        $_SESSION[user] = $user;
	header("location: stats.php");
	exit;
    } else {
        $error = "Bad username/password.";
        $msg = "<font color='red'>$error</font>";
	$smarty->assign("msg", $msg);
    }
}

$smarty->assign("Name","GreenSQL Login Page");
$smarty->assign("demo",$demo_version);
$error = "";

$smarty->display('login.tpl');

?>
