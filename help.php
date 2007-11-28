<?php

function get_section_help($section)
{
  $help = '';
  if ($section == "proxy_add")
  {
    $help ='<br/><h3>What is a GreenSQL Listener?</h3>A GreenSQL Listener object is the heart of the GreenSQL Databae Firewall.  A Listener is a proxy object used to connect queries from the frontend to a specific backend server.  Before passing the query to the backend it is checked by the Listener to determine if it is malicious and if so how it should be handled (block, alert, pass). <br/><img src="images/listener.gif"><br/>';
  } else if ($section == "log_view")
  {
    global $num_log_lines;
    $help = "<br/><h3>Log Screen Help</h3>Only last $num_log_lines lines are displayed here.<br/>Log events are in reverse order.<br/>";
  } else if ($section == "alert_list")
  {
    $help = '<br/><h3>What is a whitelist?</h3>A whitelist is a list of SQL patterns that have been approved and will be ignored by GreenSQL.  Patterns can be added to the whitelist once an alert is generated for that pattern.  Find the query in the <a href="alert_list.php">Alerts section</a> and choose to Allow query.<br/>';
  } else if ($section == "db_add")
  {
    $help = '<h3>What is a database?</h3>Database names coincide to the names of databases on the backend SQL server.  A database should be coupled with a GreenSQL <a href="proxy_add.php">Listener object</a>. A number of databases can use the same GreenSQL Listener.  You can edit the permissions for each database to block actions such as changes to DB structure, execution of sensitive commands.  <br/>';
  } else if ($section == "rawalert_list")
  {
    $help = '<br/><h3>Alerts Help</h3>This page shows all suspicios SQL queries.';
  } else if ($section == "user_edit")
  {
    $help = '<br/><h3>Setings Page Help</h3>At this page you can alter your user settings like email address and password.';
  }
  return $help;
}
?>
