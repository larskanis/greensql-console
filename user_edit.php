<?

require 'lib.php';

require 'libs/Smarty.class.php';

$userid = intval($_SESSION[userid]);

$smarty = new Smarty;
$smarty->compile_check = true;
#$smarty->debugging = true;

$user = get_user($userid);
$error = "";
$msg = "";

if ($_POST['submit'])
{
    #data posted, db need to be updated
    $user[name] = trim($_POST[name]);
    $user[email] = trim($_POST[email]);
    
    if (strlen($user[name]) == 0)
    {
        $error .= "User can not be empty.<br/>\n";
    }
    if (strlen($user[email]) == 0)
    {
        $error .= "Email can not be empty.<br/>\n";
    }
    $oldpass = sha1(trim($_POST[oldpass]));
    if ($user[pwd] != $oldpass)
    {
        $error .= "Old password is wrong. Please retype.<br/>$oldpass - $_POST[oldpass]\n";
    }
    $pass = trim($_POST[pass]);
    $pass2 = trim($_POST[pass2]);
    if (strlen($pass) == 0)
    {
        $error .= "New password can not be empty.<br/>\n";
    }
    if ($pass != $pass2)
    {
        $error .= "New passwords are different.<br/>\n";
    }
    $user[pwd] = $pass;
    if ($demo_version)
    {
        $error .= "You can not change passwords in demo version.";
    }
    else if (!$error)
    {
        $error = update_user($user);
	if (!$error)
	{
	    $msg = "Database has been succesfully updated.";
	}
    }
    if ($error)
        $msg = "<font color='red'>$error</font>";

    $smarty->assign("msg", $msg);

}

$dbs = get_databases();

$smarty->assign("databases", $dbs);

$smarty->assign("Name","View user - $user[name]");
$smarty->assign("Page","user_edit.tpl");
$smarty->assign("USER_Name", $user[name]);
$smarty->assign("USER_Email", $user[email]);

$smarty->display('index.tpl');
?>
