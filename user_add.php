<?php
require 'lib.php';
require 'help.php';
global $smarty;
global $tokenid;
global $tokenname;

$smarty->assign("Name","New Administrator");
$smarty->assign("Page","user_add.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

if (isset($_POST['submit']))
{
	$msg = "";
	#data posted, db need to be updated
	$user['name'] = trim(htmlspecialchars($_POST['name']));
	$user['email'] = trim(htmlspecialchars($_POST['email']));
	
	if (strlen($user['name']) == 0)
	{
		$error .= "User can not be empty.<br/>\n";
	} 
	else if (!ereg("^[a-zA-Z0-9_]+$", $user['name']))
			{
			$error .= "Username is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
			}
	if (strlen($user['email']) == 0)
	{
		$error .= "Email can not be empty.<br/>\n";
	} 
	else if (!ereg("^[a-zA-Z0-9_i\@\.]+$", $user['email']))
			{
				$error .= "Email address is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9, '_', '.' and '@'.<br/>\n";
			}
	$pass = trim(htmlspecialchars($_POST['pass']));
	$pass2 = trim(htmlspecialchars($_POST['pass2']));
	if (strlen($pass) == 0)
	{
		$error .= "Password can not be empty.<br/>\n";
	} else if (!ereg("^[a-zA-Z0-9_]+$",$pass))
			{
				$error .= "Password is invalid. It contains illegal characters. Valid characters are a-z, A-Z, 0-9 and '_'.<br/>\n";
			}
	if ($pass != $pass2)
	{
		$error .= "The Password does not match. Please try again.<br/>\n";
	}
	if ($demo_version)
	{
		$error .= "You can not change passwords in demo version.";		
	}
	else if (!$error)
	{
		$user['pwd'] = $pass;
		$error = new_user($user);
		if (!$error)
		{
			$msg = "Database has been successfully updated.";
			#header("location: maintanence.php?$tokenname=$tokenid");
			include("user_list.php");
			exit;
		}
	}
	if($error)
	 $msg = "<font color='red'>$error</font>";
	$smarty->assign("msg", $msg);		
}
$smarty->display('index.tpl');
?>
