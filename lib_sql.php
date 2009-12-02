<?php
function db_escape_string($str)
{
  global $db_type;
  if ($db_type == "pgsql" || $db_type == "postgresql")
  {
    return pg_escape_string($str);
  }
  else
  {
    return mysql_escape_string($str); 
  }
}

# function returns result from db (rows)
function db_query($str)
{
  global $db_type;
  global $pgsql_db;
  if ($db_type == "pgsql" || $db_type == "postgresql")
  {
    $result = pg_query($str);
    if (!$result)
    {
      echo(pg_result_error($result) . "<br />\n");
      echo $str;
    }
    return $result;
  }
  else
  { 	
    return mysql_query($str);
  }	
}

# function returns result from db (rows)
function db_exec($str)
{
  global $db_type;
  global $pgsql_db;
  if ($db_type == "pgsql" || $db_type == "postgresql")
  {
    return pg_query($pgsql_db,$str);
  }
  else
  { 	
    return mysql_query($str);
  }	
}

function db_fetch_array($result)
{
  global $db_type;
  if ($db_type == "pgsql" || $db_type == "postgresql")
  {
    return pg_fetch_array($result);
  }
  else
  {
    return mysql_fetch_array($result);
  }		
}
?>
