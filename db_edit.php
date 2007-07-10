<?

require 'lib.php';

require 'libs/Smarty.class.php';

$db_id = intval($_GET[id]);
if ($db_id == 0)
{
  $db_id = 1;
}

$smarty = new Smarty;
$smarty->compile_check = true;
#$smarty->debugging = true;


$db  = get_database($db_id);
$error = "";
$msg = "";

if ($_POST['submit'])
{
    #data posted, db need to be updated
    $db[create_perm] = intval(trim($_POST[create_perm]));
    $db[drop_perm]   = intval(trim($_POST[drop_perm]));
    $db[alter_perm]  = intval(trim($_POST[alter_perm]));
    $db[info_perm]   = intval(trim($_POST[info_perm]));
    $db[block_q_perm]= intval(trim($_POST[block_q_perm]));
    $db[proxyid]     = intval(trim($_POST[proxyid]));
    $db[db_name]     = trim($_POST[db_name]); 
    $db[dbpid]       = $db_id;
    
    if ($_POST[proxyid] != 0 && !($proxy = get_proxy($db[proxyid])))
    {
        $error .= "Wrong proxy id. Proxy was not found in the database.";
    }
    if ($db[create_perm] != 0 && $db[create_perm] != 1)
    {
        $error .= "Create table permission is invalid.";
    }
    if ($db[drop_perm] != 0 && $db[drop_perm] != 1)
    {
        $error .= "Drop permission is invalid.";
    }
    if ($db[alter_perm] != 0 && $db[alter_perm]  != 1)
    {
        $error = "Change table structure permission is invalid.";
    }
    if ($db[info_perm] != 0 && $db[info_perm] != 1)
    {
        $error = "Disclose table structure permission is invalid.";
    }
    if ($db[block_q_perm] != 0 && $db[block_q_perm] != 1)
    {
        $error = "Block sensitive queries permission is invalid.";
    }
    if (!ereg("^[a-zA-Z0-9_]+$",$db[db_name]))
    {
        $error = "Database Name is invalid. It contains illegal chars.";
    }
    if (strlen($db[db_name]) > 20)
    {
        $error = "Database name is too long.";
    }
    if (strlen($db[db_name]) == 0)
    {
       $error = "Database name could not be empty.";
    }
    if (!$error)
    {
        $error = update_database($db);
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

$smarty->assign("Name","Edit database - $db[db_name]");
$smarty->assign("Page","db_edit.tpl");
$smarty->assign("DB_Name", $db[db_name]);
$smarty->assign("DB_ProxyName", $db[proxyname]);
$smarty->assign("DB_ProxyID", $db[proxyid]);
$smarty->assign("DB_Listener", $db[listener]);
$smarty->assign("DB_Backend", $db[backend]);
$smarty->assign("DB_Type", $db[dbtype]);
$smarty->assign("DB_ID", $db_id);

$enabled_str = "<font color=\"red\">enabled</font>";

$smarty->assign("DB_StatusId", $db[status]);
if ($db[status] == 1)
{
    $smarty->assign("DB_Status", "<font color=\"green\">Listener is 'OK'</font>");
}

$smarty->assign("DB_Alter", $db[alter_perm]);
$smarty->assign("DB_Create", $db[create_perm]);
$smarty->assign("DB_Drop", $db[drop_perm]);
$smarty->assign("DB_Info", $db[info_perm]);
$smarty->assign("DB_BlockQ", $db[block_q_perm]);

#load list of proxies
$proxies = get_proxies();
$ids = array();
$names = array();

foreach ($proxies as $proxy)
{
    $ids[] = $proxy[proxyid];
    $names[] = $proxy[proxyname];
}

$smarty->assign("option_values", $ids);
$smarty->assign("option_output", $names);
$smarty->assign("option_selected", $db[proxyid]);


$smarty->display('index.tpl');
?>
