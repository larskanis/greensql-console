The greensql-console package is web management tool used
to manage GreenSQL Database Firewall.

Basic Requirements
------------------

In order to use this application you need a running version
of Apache configured to work with PHP4 or PHP5. In addition
access to MySQL server that is used to store greensql-fw
configuration is requred.


Installation
------------

1. Greensql Configuration Database

You can pass this part if you already configured GreenSQL
configuration database as part of the greensql-fw installation.

In order to create the db, you can run the following command:

 cat /usr/share/doc/greensql-fw/greensql-mysql-db.txt | mysql -h 127.0.0.1

Description:
This file "greensql-mysql-db.txt" is a part of the greensql-fw
package.
127.0.0.1 is a location of MySQL server used to store configuration.
Yoy can change it to other IP address where you want the database
to be stored.

2. Alter Configuration Settings

Please change config.php file according to your needs. You can
alter database location, name, username and password by changing
this file.

3. "templates_c" Directory Permissions
Final step is to alter templates_c directory permissions.
This directory is used to store generated pages used by smarty
library. You need to make this directory world-writable.
You can simply run the following command:

chmod 777 templates_c

