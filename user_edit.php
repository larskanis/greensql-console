<?

require 'lib.php';
require 'help.php';
global $demo_version;
global $smarty;

$userid = 0;
if (isset($_SESSION['userid']))
{
    $userid = intval($_SESSION['userid']);
}

$user = get_user($userid);
$error = "";
$msg = "";

if (isset($_POST['submit']))
{
    #data posted, db need to be updated
    $user['name'] = trim(htmlspecialchars($_POST['name']));
    $user['email'] = trim(htmlspecialchars($_POST['email']));
    
    if (strlen($user['name']) == 0)
    {
        $error .= "User can not be empty.<br/>\n";
    } else if (!ereg("^[a-zA-Z0-9_]+$", $user['name']))
    {
        $error .= "Username is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
    }
    if (strlen($user['email']) == 0)
    {
        $error .= "Email can not be empty.<br/>\n";
    } else if (!ereg("^[a-zA-Z0-9_i\@\.]+$", $user['email']))
    {
        $error .= "Email address is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9, '_', '.' and '@'.<br/>\n";
    }
    $_POST['oldpass'] = htmlspecialchars(trim($_POST['oldpass']));
    $oldpass = sha1($_POST['oldpass']);
    if ($user['pwd'] != $oldpass)
    {
        $error .= "Old password is wrong. Please retype.<br/>\n";
    } else if (!ereg("^[a-zA-Z0-9_]+$",$_POST['oldpass']))
    {
        $error .= "Old password is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
    }

    $pass = trim(htmlspecialchars($_POST['pass']));
    $pass2 = trim(htmlspecialchars($_POST['pass2']));
    if (strlen($pass) == 0)
    {
        $error .= "New password can not be empty.<br/>\n";
    } else if (!ereg("^[a-zA-Z0-9_]+$",$pass))
    {
        $error .= "New password is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
    }

    if ($pass != $pass2)
    {
        $error .= "The new password does not match. Please try again.<br/>\n";
    }
    if ($demo_version)
    {
        $error .= "You can not change passwords in demo version.";
    }
    else if (!$error)
    {
        $user['pwd'] = $pass;
        $error = update_user($user);
	if (!$error)
	{
	    $msg = "Database has been successfully updated.";
	}
    }
    if ($error)
        $msg = "<font color='red'>$error</font>";

    $smarty->assign("msg", $msg);

}

$dbs = get_databases();

$smarty->assign("databases", $dbs);

$smarty->assign("Name","View user - ".$user['name']);
$smarty->assign("Page","user_edit.tpl");
$smarty->assign("USER_Name", $user['name']);
$smarty->assign("USER_Email", $user['email']);

$help_msg = get_section_help("user_edit");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');
?>
