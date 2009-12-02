<?php

# Uncomment the following line to switch to demo version
#$demo_version = 1;

# greensql version
$version = "1.2.0";

# Database Type
$db_type = "mysql";

# Database IP address
$db_host = "127.0.0.1";

# Database Port Value.
#$db_port = 3306;

# database name used to store greensql confiuration and alerts
$db_name = "greendb";

# database user and password
$db_user = "green";
$db_pass = "pwd";

# If you run greensql-fw service on the same computer you can specify
# location of it's log file. It will be visible as part of the console.
$log_file = "/var/log/greensql.log";

# Number of lines to show when viewing log file.
$num_log_lines = 200;

# Number of lines to show when displaying a table.
$limit_per_page = 10;

# Generated web pages cache
$cache_dir = "templates_c";

# Smarty directory location (optional)
$smarty_dir = "/usr/share/php/smarty";

?>
