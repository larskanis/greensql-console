The greensql-console package is web management tool used
to manage GreenSQL Database Firewall.

Basic Requirements
------------------

In order to use this application you need a running version
of Apache webserver configured to work with PHP4 or PHP5.
In addition access to MySQL server used to store greensql-fw
configuration is requred.


Application Installation
========================

1. Greensql-firewall configuration db

If GreenSQL configuration database was not installed together
with greensql-fw package, check "greensql-mysql-db.txt" file 
found in the the package. Tis file all commands required to
configure the database.

2. Alter configuration settings

Please change config.php file according to your needs. You can
alter database location, name, username and password by changing
appropriate configuration settings.

3. "templates_c" Directory Permissions

Final step is to alter templates_c directory permissions.
This directory is used to store generated pages used by smarty
library. You need to make this directory world-writable.
You can simply run the following command (in a shell):

chmod 0777 templates_c

