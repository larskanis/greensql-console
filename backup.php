<?php

require 'lib.php';

global $tokenid;
global $tokenname;

$smarty->assign("Page","backuprestore.tpl");

$error = "";
$msg = "";

$xml_doc = new DOMDocument();
$xml_doc->formatOutput = true;

$xml = $xml_doc->createElement("GreenSQL_Backup");

$xml = get_table_data($xml_doc,$xml,"db_perm");
$xml = get_table_data($xml_doc,$xml,"proxy");
$xml = get_table_data($xml_doc,$xml,"query");
$xml = get_table_data($xml_doc,$xml,"admin");

$xml_doc->appendChild($xml);

#echo $xml_doc->saveXML();

global $cache_dir;

$filename = $cache_dir . '/greensql_backup_'.date('d_m_Y_h_s_m').'.xml';
$error = $xml_doc->save($filename);

if ($error != false)
{
 $msg = "Backup File has been successfully created.<BR>";
 $msg .= "Please Right Click on the XML file and download to your PC";
}
else
 $msg = "<font color='red'>$error</font>";

$_SESSION['msg'] = $msg;
$_SESSION['file'] = $filename;
header('Location: backuprestore.php?'.$tokenname.'='.$tokenid);

#$smarty->display('index.tpl');	
?>
