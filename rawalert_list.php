<?php

require 'lib.php';
require 'help.php';

require 'libs/Smarty.class.php';

$error = "";
$msg = "";
$start_id = 0;
if (isset($_GET['p']))
{
    $start_id = intval($_GET['p']);
}
$limit_per_page = 10;

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign("Name","View alerts");
$smarty->assign("Page","rawalert_list.tpl");

$dbs = get_databases();
$smarty->assign("databases", $dbs);

$status = 0;
$alerts = get_raw_alerts_bypage($start_id*$limit_per_page, $limit_per_page, $status);
$smarty->assign("alerts", $alerts);

$numResults = get_num_raw_alerts($status);
$list_pages = "";

  $file = 'rawalert_list.php';
  // update list of pages
  $num_pages = ceil($numResults/$limit_per_page)+1;
  if ($start_id > 2)
    $from_id = $start_id - 1;
  else
    $from_id = 1;
  $to_id = $from_id + 5;

  if ($start_id > 1)
    $list_pages .= '<a href="'.$file.'?p='.($start_id-1).'">Previous</a>&nbsp;';
  else if ($start_id == 1)
    $list_pages .= '<a href="'.$file.'">Previous</a>&nbsp;';
    
  for ($i = $from_id; $i < $num_pages && $i < $to_id; $i++)
  {
    if (($i-1) == $start_id)
      $list_pages .= '<b>'.$i . '</b>&nbsp;';
    else
      $list_pages .= '<a href="'.$file.'?p='.($i-1).'">'.$i.'</a>&nbsp;';
  }
  if ($start_id < $num_pages-2)
    $list_pages .= '<a href="'.$file.'?p='.($start_id+1).'">Next</a>&nbsp;';
  $list_pages .= '<br/>';

$smarty->assign("pager", $list_pages);

$help_msg = get_section_help("rawalert_list");
if ($help_msg)
{
  $smarty->assign("HelpPage","help.tpl");
  $smarty->assign("HelpMsg",$help_msg);
}

$smarty->display('index.tpl');

?>
