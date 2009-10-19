<?php

# Here you can find all functions related to displaying of tables

#
# this function actually displays tables with a sorting possibility
#
function display_table($header, $rows)
{
  $sort_field = "";
  $sort_order = "asc";
  $sorted = get_sort_order($header, $sort_field, $sort_order);

  $out = '<table cellspacing=0 cellpadding=0 width="100%" id="table_cont">';
  $out .= "\n<tr>";
  foreach ($header as $row)
  {
    if (isset($row['size']) && intval($row['size']))
    {
      $out .= '<th width='.$row['size'].'>';
    } else {
      $out .= '<th>';
    }
    if (isset($row['field']))
    {
      if ($sorted && isset($row['field']) && $row['field'] == $sort_field)
      {
        if ($sort_order == "asc")
        {
          $out .= '<a href="'.url_change_sort_order($row['field'], 'desc').
                  '" title="Sort by '.$row['title'].'">'.$row['title'].'</a>';
          $out .= '<img src="images/arrow-asc.gif">';
        }
        else
        {
          $out .= '<a href="'.url_change_sort_order($row['field'], 'asc').
                  '" title="Sort by '.$row['title'].'">'.$row['title'].'</a>';

          $out .= '<img src="images/arrow-desc.gif">';
        }
      } else {
        $out .= '<a href="'.url_change_sort_order($row['field'], 'asc').
                '" title="Sort by '.$row['title'].'">'.$row['title'].'</a>';

      }
    } else {
      # if field is not provided
      $out .= $row['title'];
    }
    $out .= '</th>';
  }
  $out .= "</tr>\n";
  foreach ($rows as $row)
  {
    $out .= '<tr>';
    foreach ($header as $row2)
    {
      if (isset($row2['size']) && $row2['size'] == 'auto')
      {
        $out .= '<td style="overflow:hidden;" nowrap>';
      } else {
        $out .= '<td nowrap>';
      }
      if (isset($row2['field']))
      {
        $out .= $row[$row2['field']].'</td>';
      } else {
        # we do not have a field, we have only Title
        $out .= $row[$row2['title']].'</td>';
      }
    }
    $out .= "</tr>\n";
  }
  if (count($rows) == 0)
  {
    $out .= '<tr><td colspan='.count($header).'>The list is empty.</td></tr>';
  }
  $out .= "</table>";
  return $out;
}

#
# the fellowing function ads LIMIT SQL token for the query spesified
#
function add_query_limit($q, $from, $count )
{
  # check if we need to add counter
  if ($from == 0 && $count == 0)
    return $q;
  return $q . " LIMIT $from, $count";
}

#
# the following function ads ORDER BY SQL token for the query spesified
#
function add_query_sort($header, $q )
{
  $sort_field = "";
  $sort_order = "asc";
  $found = get_sort_order($header, $sort_field, $sort_order);
  if ($found)
    return $q . " ORDER BY $sort_field $sort_order";
  else
    return $q;
}

#
# this function returns sort order specifued by the script arguments or a default one.
#
function get_sort_order($header, &$sort_field, &$sort_order)
{
  $sort_field = "";
  $sort_order = "asc";
  if (isset($_REQUEST['sort']))
  {
    $sort_field = $_REQUEST['sort'];
    // sort key can have the following format [a-z0-9\_\.]
    $sort_field = preg_replace("/[^a-zA-Z0-9\_\.]/", "", $sort_field);

    foreach ($header as $row)
    {
      if (isset($row['field']) && $row['field'] == $sort_field)
      {
        if (isset($_REQUEST['order']) && strtolower($_REQUEST['order']) == "desc")
        {
          $sort_order = "desc";
        }
        return 1;
      }
    }
  }
  // get default sort field
  foreach ($header as $row)
  {
    if (isset($row['sort']))
    {
      $sort_field = $row['field'];
      if (strtolower($row['sort']) == "desc" )
      {
        $sort_order = "desc";
      }
      return 1;
    }
  }
  return 0;
}


#
# this function alters current query and adds sort order parameters
#
function url_change_sort_order($sort_field, $sort_order)
{
  global $tokenid;
  global $tokenname;

  $url = $_SERVER['REQUEST_URI'];
  if (!$url)
  {
    $url = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
  }
  # fix token id if we generate a new one
  $url = preg_replace("/\Q$tokenname\E=[a-zA-Z0-9]*/", "$tokenname=$tokenid", $url);
  # remove sort order from the url
  $url = preg_replace("/sort=[a-zA-Z0-9\_\.]*&?/", "", $url);
  $url = preg_replace("/order=[a-zA-Z]*&?/", "", $url);

  $sort = '';
  if ($sort_order == "desc")
  {
    $sort = "sort=$sort_field&order=$sort_order";
  } else {
    $sort = "sort=$sort_field";
  }
  if (preg_match("/\?/", $url))
  {
    $url = preg_replace("/\?/", "?$sort&", $url);
  } else {
    $url .= "?$sort";
  }
  return $url;
}

#
# this function generates a pager to all return results from the database
#
function get_pager($numResults)
{
  global $tokenid;
  global $tokenname;
  global $limit_per_page;
  if (!$limit_per_page)
    $limit_per_page = 10;

  $url = $_SERVER['REQUEST_URI'];
  if (!url)
  {
    $url = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
  }
  # fix token id if we generate a new one
  $url = preg_replace("/\Q$tokenname\E=[a-zA-Z0-9]*/", "$tokenname=$tokenid", $url);
  # remove page number
  $url = preg_replace("/&p=[a-zA-Z0-9]*/", "", $url);
  $url = preg_replace("/\?p=[a-zA-Z0-9]*/", "?", $url);

  $start_id = 0;
  if (isset($_REQUEST['p']))
  {
    $start_id = abs(intval($_REQUEST['p']));
  }

  // update list of pages
  $num_pages = ceil($numResults/$limit_per_page)+1;
  if ($start_id > 2)
    $from_id = $start_id - 1;
  else
    $from_id = 1;
  $to_id = $from_id + 5;

  if ($start_id > 1)
    $list_pages .= '<a href="'.$url.'&p='.($start_id-1).'">Previous</a>&nbsp;';
  else if ($start_id == 1)
    $list_pages .= '<a href="'.$url.'">Previous</a>&nbsp;';

  for ($i = $from_id; $i < $num_pages && $i < $to_id; $i++)
  {
    if (($i-1) == $start_id)
      $list_pages .= '<b>'.$i . '</b>&nbsp;';
    else if ($i-1 != 0)
      $list_pages .= '<a href="'.$url.'&p='.($i-1).'">'.$i.'</a>&nbsp;';
    else
      $list_pages .= '<a href="'.$url.'">'.$i.'</a>&nbsp;';
  }
  if ($start_id < $num_pages-2)
    $list_pages .= '<a href="'.$url.'&p='.($start_id+1).'">Next</a>&nbsp;';
  $list_pages .= '<br/>';
  return $list_pages;
}

?>
