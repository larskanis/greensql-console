<?php

function get_section_help($section)
{
  global $tokenid;
  global $tokenname;

  $help = '';
  if ($section == "proxy_add")
  {
    $help ='<h3>What is a GreenSQL Listener?</h3>A GreenSQL Listener object is the heart of the GreenSQL Databae Firewall.  A Listener is a proxy object used to connect queries from the frontend to a specific backend server.  Before passing the query to the backend it is checked by the Listener to determine if it is malicious and if so how it should be handled (block, alert, pass). <br/><img src="images/listener.gif"><br/>';
  } else if ($section == "log_view")
  {
    global $num_log_lines;
    $help = "<br/><h3>Log Screen Help</h3>Only last $num_log_lines lines are displayed here.<br/>Log events are in reverse order.<br/>";
  } else if ($section == "alert_list")
  {
    $help = "<h3>What is a whitelist?</h3>A whitelist is a list of SQL patterns that have been approved and will be ignored by GreenSQL.  Patterns can be added to the whitelist once an alert is generated for that pattern.  Find the query in the <a href='rawalert_list.php?$tokenname=$tokenid'>Alerts</a> section and choose to \"Allow this query\".<br/>";
  } else if ($section == "db_add")
  {
    $help = "<h3>What is a database?</h3>Database names coincide to the names of databases on the backend SQL server.  A database should be coupled with a GreenSQL <a href='proxy_add.php?$tokenname=$tokenid'>Listener object</a>. A number of databases can use the same GreenSQL Listener.  You can edit the permissions for each database to block actions such as changes to DB structure, execution of sensitive commands.<br/>";
  } else if ($section == "rawalert_list")
  {
    $help = '<br/><h3>Alerts Help</h3>This page shows all suspicious SQL queries.';
  } else if ($section == "user_edit")
  {
    $help = '<br/><h3>Setings Page Help</h3>At this page you can alter your user settings like email address and password.';
  } else if ($section == "cleanall")
  {
    $help = '<br/><h3>Clean Alerts help</h3>If you press on "submit" button, all alerts will be removed. In addition whitelist will be cleaned as well.';
  }
  return $help;
}

function get_db_modes()
{
  $modes = array();
  $modes[0] = array('mode' => 'Database IDS',
                   'help' => 'Block high risk queries based on the heuristics and privileged commands. '.
                             'Whitelist is checked for exceptions.');
  $modes[1] = array('mode' => 'Database IDS (block admin commands only)',
                   'help' => 'Block high privileged commands only for example CREATE TABLE. Whitelist is checked for exceptions.');
  $modes[2] = array('mode' => 'Database IPS (no blocking)',
                   'help' => 'Nothing is blocked. Only warning is generated for suspicious queries. Whitelist is checked for exceptions.');
  $modes[4] = array('mode' => 'Database Firewall',
                   'help' => 'Block all commands unlisted in whitelist. It is recommended to enable this mode after whitelist is build.');
  $modes[10]= array('mode' => 'Learning Mode',
                   'help' => 'During learning mode no queries are blocked. Query patterns are automatically added to the whitelist.');
  $modes[11]= array('mode' => 'Learning Mode for 3 days',
                   'help' => 'Same as <stromg>Learning Mode</strong>. Query patterns are automatically added to the whitelist. '.
                   'After 3 days database is automatically switched to the <strong>Database Firewall</strong> mode.');
  $modes[12]= array('mode' => 'Learning Mode for 7 days',
                   'help' => 'Same as <strong>Learning Mode</strong>. Query patterns are automatically added to the whitelist. '.
                   'After 7 days database is automatically switched to the <strong>Database Firewall</strong> mode.');
  return $modes;
}

function get_db_mode($id)
{
  $modes = get_db_modes();
  return $modes[$id];
}
?>
