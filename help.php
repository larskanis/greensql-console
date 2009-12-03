<?php

function get_section_help($section)
{
  global $tokenid;
  global $tokenname;

  $help = '';
  if ($section == "proxy_add")
  {
    $help ='<h3>What is a GreenSQL Proxy?</h3><br/>GreenSQL proxy object is the heart of the GreenSQL Database Firewall. A proxy is an object used to connect queries from the frontend to a specific backend server. Before passing the query to the backend, it will be processed by GreenSQL to determine if it is malicious and if so how it should be handled (block, alert, pass).</br><img src="images/listener.gif"><br/>';
  } else if ($section == "log_view")
  {
    global $num_log_lines;

    $help = "<br/><h3>Log Screen Help</h3>The View log option allows you to see debug information of the GreenSQL engines, the log file provides you a low level view of the behavior and the protocols negotiations, which might come handy when trouble shooting problem, only last $num_log_lines logs are displayed in the View log option, log events are viewed in reverse order.";
  } else if ($section == "alert_list")
  {
    $help = "<h3>What is a whitelist?</h3>The white list option allows you to set a specific list of SQL patterns that have been approved and will be ignored by GreenSQL . Patterns can be added to the whitelist once an alert is generated for that pattern. Find the query in the <a href='rawalert_list.php?$tokenname=$tokenid'>Alerts</a> section and you can choose to \"Add to Whitelist\"";
  } else if ($section == "alert_view")
  {
    $help = "<h3>Alert Pattern help Page</h3></br>The Alerts options allows you to view all suspicious SQL queries been detected by GreenSQL, by default the Alert option shows the Alerts from all protected Databases, you have the option to customize the view to a specific database by selecting a specific database.<br/>";

  } else if ($section == "db_add")
  {
    $help = "<h3>What is a database?</h3>Database names coincide to the names of databases on the backend SQL server. A database should be coupled with a GreenSQL  <a href='proxy_add.php?$tokenname=$tokenid'>Proxy object</a>.<br/>A number of databases can use the same GreenSQL Proxy. You can edit the permissions for each database to block actions such as changes to DB structure, execution of sensitive commands.";
  } else if ($section == "rawalert_list")
  {
    $help = '<h3>Alerts Help</h3>The Alerts option allows you to view all suspicious SQL queries that have been detected by GreenSQL. By default the Alert option shows the Alerts from all protected databases. You have the option to customize the view to a specific database by selecting it.';
  } else if ($section == "user_edit")
  {
    $help = '<br/><h3>Setings Page Help</h3>The edit user option allows you to edit a GreenSQL administrator parameters, such as user name,  e-mail address and password.';
  } else if ($section == "cleanall")
  {
    $help = '<br/><h3>Clean Alerts help</h3>The clean alerts option allows you to clear all alerts from all databases.';
  }
    else if($section == "db_list")
  {
    $help = '<br/><h3>Databases Help</h3>The Databases option allows you to view and manage the current GreenSQL databases and proxies.';
  }
    else if($section == "user_list")
  {
    $help = '<h3>User Help</h3>The Users option allows you to create and remove administrators and to change password of the GreenSQL administrators. This page shows the administrator list and allows you to alter user setting such as name, email and password.';
  }
    else if($section == "backuprestore")
  {
    $help = '<h3>Users Help</h3>The Backup & Restore options allow you to back up and restore your GreenSQL configuration to your PC. The generated configuration file is in XML format, and you can edit it after you Backup and save it to your PC, and restore it with the new configuration. Passwords are encrypted in the XML file.</h3>';
  }
    else if ($section == "db_view")
  {
    $help = '<br/><h3>Overview</h3>The Overview option allows you to view the entire configuration of a specific Database,<BR> which includes the mode of the database and the Privileged Operations.';
  }
    else if ($section == "db_edit")
  {
    $help = '<br/><h3>Settings</h3>The Settings option allows you to modify the configuration of a specific database,<BR>which includes changing the database name, the blocking mode, and configuration of the Privileged Operations.';
  }

  return $help;
}

function get_db_modes()
{
  $modes = array();
  $modes[0] = array('mode' => 'IPS',
                   'help' => 'Block high risk queries based on the heuristics and privileged commands. '.
                             'Whitelist is checked for exceptions.');
  $modes[1] = array('mode' => 'IPS (block admin commands only)',
                   'help' => 'Block high privileged commands only for example CREATE TABLE. Whitelist is checked for exceptions.');
  $modes[2] = array('mode' => 'IDS (no blocking)',
                   'help' => 'Nothing is blocked. Only warning is generated for suspicious queries. Whitelist is checked for exceptions.');
  $modes[4] = array('mode' => 'Firewall',
                   'help' => 'Block all commands unlisted in whitelist. It is recommended to enable this mode after whitelist is build.');
  $modes[10]= array('mode' => 'Learning Mode',
                   'help' => 'During learning mode no queries are blocked. Query patterns are automatically added to the whitelist.');
  $modes[11]= array('mode' => 'Learning Mode for 3 days',
                   'help' => 'Same as <stromg>Learning Mode</strong>. Query patterns are automatically added to the whitelist.<br/>'.
                   'After 3 days database is automatically switched to the <strong>Database Firewall</strong> mode.');
  $modes[12]= array('mode' => 'Learning Mode for 7 days',
                   'help' => 'Same as <strong>Learning Mode</strong>. Query patterns are automatically added to the whitelist.<br/>'.
                   'After 7 days database is automatically switched to the <strong>Database Firewall</strong> mode.');
  return $modes;
}

function get_db_mode($id)
{
  $modes = get_db_modes();
  return $modes[$id];
}
?>
