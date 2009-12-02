<?php
require_once 'lib.php';
require_once 'help.php';

global $smarty;
global $tokenid;
global $tokenname;

$userid = 0;
if (isset($_REQUEST['user_id']))
{
	$userid = intval($_REQUEST['user_id']);
}

$user = get_user($userid);

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'delete' &&
    isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == 'on' && $user)
{
    if ($user['adminid'] == 1)
    {
        $error = "Default admin can not be deleted.";
    } else {
        $error = delete_user($userid);
    }
    if (!$error)
    {
        $msg = "Administrator ".$user['name']." has been successfully deleted.";
    }
    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    }
} else {
    $msg = "<font color='red'>Failed to delete administrator ".$user['name']."</font>";
}
    $_SESSION['msg'] = $msg;
    header("location: user_list.php?$tokenname=$tokenid");
?>
