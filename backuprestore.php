<?php
require 'lib.php';
require 'help.php';
global $smarty;
global $tokenid;
global $tokenname;

if (isset($msg))
{
  $smarty->assign("msg", $msg);
} else if (isset($_SESSION['msg']) && strlen($_SESSION['msg']) > 0)
{
  $smarty->assign("msg", $_SESSION['msg']);
  $_SESSION['msg'] = '';
}

if (isset($_SESSION['file']) && strlen($_SESSION['file']) > 0)
{
  global $cache_dir;

 $filename = substr($_SESSION['file'],strlen($cache_dir)+1);
 $filelink = 'Saved backup File:&nbsp;<a style="text-decoration:underline;" href="'.$_SESSION['file'].'">'.$filename.'</a>';
 $smarty->assign("filelink",$filelink);

 /*$fi = array();
 $fi["name"] = basename($_SESSION['file']); // name reported to the browser
 $fi["size"] = filesize($_SESSION['file']);
 $fi["time"] = filemtime($_SESSION['file']);
 if (function_exists("mime_content_type"))
  $fi["mime"] = mime_content_type($_SESSION['file']);
 else
  $fi["mime"] = "application/octet-stream";

 if (!preg_match("#^(\w*)/(\w*)$#i", $fi["mime"])) $fi["mime"] = "application/octet-stream";

 // make sure no caching headers are set
 header("Cache-Control:");
 header("Pragma:");
 header("Expires:");
 // unset other headers we (might) set
 header("Content-Range:");

 header("Content-Type: "); // unset content type

 header("HTTP/1.1 200 Ok");
 #header("Content-Type: ".$fi["mime"]);
 header("Content-Length: ".$fi["size"]);
 header("Cache-Control: no-cache");
 header("Content-Disposition: attachment; filename=".$fi["name"]);

 $fp = fopen($_SESSION['file'], "rb");
 if (!$fp) {
  header("HTTP/1.1 500 Internal Server Error");
  echo "Unable to open file for reading.";
  return true;
 }

 while (!connection_status() && !feof($fp)) {
  echo fread($fp, 2048);
 }
 fclose($fp);*/
 
 $_SESSION['file'] = '';

 #exit;
}

$type = "";
if (isset($_GET['type']))
{
 $type = $_GET['type'];
 $out = '<form enctype="multipart/form-data" action="restore.php?'.$tokenname.'='.$tokenid.'" method="POST">
          <table border=0>
           <tr>
            <td colspan=4>					
             <input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
             Choose a file to restore: <input name="restorefile" type="file"/>
            </td>
           </tr>							
          <tr>
          <td colspan=4 align="center">
           <input type="submit" value="Restore File" />				
          </td>
         </tr>
        </table>
       </form>';
 $smarty->assign("restore",$out);
}

$error = "";
$msg = "";

$smarty->assign("Name","Backup & Restore");
$smarty->assign("Page","backuprestore.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_system_menu());

$help_msg = get_section_help("backuprestore");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');
?>

