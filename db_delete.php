<?php
require_once 'lib.php';

$msg = "";
$error = "";

$db_id = 0;
if (isset($_REQUEST['db_id']))
{
    $db_id = abs(intval($_REQUEST['db_id']));
}
$db  = get_database($db_id);

#if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'delete' && 
#    isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == 'on' && $db)
if (isset($_POST['delete']))
{
    if ($db_id == 0)
    {
        $error = "Default db can not be deleted.";
    } else {
        $error = delete_database($db_id);
    }
    if (!$error)
    {
        $msg = "Database has been successfully deleted.<br/>You need to restart greensql firewall for the changes take effect.";
    }
    if ($error)
    {
        $msg = "<font color='red'>$error</font>";
    }
    $_SESSION['msg'] = $msg;
    header("location: db_list.php?$tokenname=$tokenid");
} else {
    $msg = "<font color='red'>Failed to delete database ".$db['dbname']."</font>";
}
$smarty->assign("Page","delete.tpl");
$smarty->assign("DB_Name",$db['db_name']);
$smarty->assign("Type","database");
$smarty->display('index.tpl');  
#$_SESSION['msg'] = $msg;
#header("location: db_list.php?$tokenname=$tokenid");
?>

