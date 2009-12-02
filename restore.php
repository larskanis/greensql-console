<?php
require 'lib.php';

global $smarty;

$smarty->assign("Page","backuprestore.tpl");

$error = "";
$msg = "";

if ($_FILES["restorefile"]["error"] > 0)
{
  echo "Return Code: " . $_FILES["restorefile"]["error"] . "<br />";
}

global $cache_dir;
$rand = rand(1,10000000);
$url =  $cache_dir . "/backup_" . $rand . '.xml';
@move_uploaded_file($_FILES["restorefile"]["tmp_name"], $url);

if (file_exists($url)) 
{
  $xml = simplexml_load_file($url);
  if($xml)
  {
    $error = restore_table_data($xml,"db_perm","dbpid");
    if($error == false) {
      $error = "Failed To Restore 'db_perm' Table"; 
    }
    else
    {
      $error = restore_table_data($xml,"proxy","proxyid");
      if($error == false) {
        $error = "Failed To Restore 'proxy' Table";
      }
      else
      {
        $error = restore_table_data($xml,"query","queryid");
	if($error == false) {
	  $error = "Failed To Restore 'query' Table";
        }
	else {
	  $error = restore_table_data($xml,"admin","adminid");
	  if($error == false)
	    $error = "Failed To Restore 'admin' Table";
	  else $error = "No Errors";
	}
      }
    }
  } 
  else { 
    $error = "Bad file format";
  }
}
else $error = "Failed to open file - File does not exist";
	
if ($error == "No Errors")
{
	$msg = "Database has been successfully restored.<br/>You will need to restart GreenSQL firewall for the changes to take effect.<br/>
If the GreenSQL won't be restarted, the firewall behavior is unexpected.";
}
else
	$msg = "<font color='red'>$error</font>";

$smarty->assign("msg", $msg);

$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

$smarty->display('index.tpl');	
?>
