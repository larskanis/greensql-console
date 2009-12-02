<?php

require_once 'lib.php';
require_once 'help.php';
global $smarty;
global $demo_version;

$proxy_id = 0;
if (isset($_GET['proxyid']))
{
    $proxy_id = intval($_GET['proxyid']);
}
$db_id = "";
if (isset($_GET['db_id']))
{
    $db_id = trim($_GET['db_id']);
}
$db  = get_database($db_id);

$error = "";
$msg = "";

$smarty->assign("Name","Add Whitelist");
$smarty->assign("Page","whitelist_entry_add.tpl");
$smarty->assign("PrimaryMenu", get_primary_menu());
$smarty->assign("SecondaryMenu", get_top_db_menu());

$smarty->assign("db_name",$db['db_name']);

if($proxy_id == 1)
    $smarty->assign("proxy","Default Proxy");
else if($proxy_id == 2)
    $smarty->assign("proxy","Default PostgreSQL");

if (isset($_POST['submit']))
{
    $msg = "";

    $pattern['proxy'] = $proxy_id;
    $pattern['db_name'] = $db['db_name'];
    $pattern['pattern'] = trim(htmlspecialchars($_POST['pattern']));
    
    if (strlen($pattern['pattern']) == 0)
    {
        $error .= "Pattern can not be empty.<br/>\n";
    }
    if ($demo_version)
    {
        $error .= "You can not Add whitelists in demo version.";        
    }
    else if (!$error)
    {
        $error = add_whitelist_entry($pattern);
        if (!$error)
        {
            $msg = "Database has been successfully updated.";            
            header("location: whitelist.php?db_id={$db_id}&$tokenname=$tokenid");
            #include("whitelist.php");
            #exit;
        }
    }
    else if($error)
    {
        $msg = "<font color='red'>$error</font>";        
    }
    $smarty->assign("msg", $msg);
}

$help_msg = get_section_help("whitelist_entry_add");
if ($help_msg)
{
    $smarty->assign("HelpPage","help.tpl");
    $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>


