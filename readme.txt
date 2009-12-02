The greensql-console package is web management tool used
to manage GreenSQL Database Firewall.

Basic Requirements
------------------

In order to use this application you need a running version
of Apache webserver configured to work with PHP4 or PHP5.
In addition access to MySQL server used to store greensql-fw
configuration is required.

In debian/Ubuntu make sure that php5-mysql is installed.
You can do it using the following command:

shell> sudo apt-get install php5-mysql


Application Installation
========================

1. Greensql-firewall configuration db

If GreenSQL configuration database was not installed together
with greensql-fw package, run "greensql-create-db.sh" script
found in the greensql-fw package. This script performs all
necessary steps required to configure the database.

2. Alter configuration settings

Please change config.php file according to your needs. You can
specify database server, db name, username and password by changing
appropriate configuration settings.

3. "templates_c/" Directory Permissions

Final step is to alter "templates_c/" directory permissions.
This directory is used to store cached pages of the management
console. You need to make this directory world-writable.
You can simply run the following command (in a shell):

shell> chmod 0777 templates_c

SELinux Section
===============
1. mysql fails to connect

If SELinux is installed on your server, it can prevent the apache php
module from connecting to the MySQL server. You can check whether 
SELinux is enabled by running: 

shell> /usr/sbin/sestatus -v

In case it is, you can just type the command: 

shell> setsebool httpd_can_network_connect_db=1

2. templates_c directory is not writable
The caching directory must be placed in within the /tmp/ directory.

shell> mkdir /tmp/greensql_templates

shell> chmod 0777 /tmp/greensql_templates/

shell> ln -s /tmp/greensql_templates templates_c

