<h3>Database: {$DB_Name}</h3>
{ if $DB_ProxyID }
<strong>Proxy Settings</strong><br/>
Proxy Object: {$DB_ProxyName}<br/>
Listener: {$DB_Listener}<br/>
Backend:  {$DB_Backend}<br/>
Status:   {$DB_Status}<br/>
Type:     {$DB_Type}<br/>
<a href="proxy_add.php?proxyid={$DB_ProxyID}">Change proxy settings</a><br/>
<br/>
<br/>
{ /if }
<strong>Database Permissions:</strong><br/>
Change DB Structure: {$DB_Alter}<br/>
Create command: {$DB_Create}<br/>
Disclose table stucture: {$DB_Info}<br/>
Drop command:  {$DB_Drop}<br/>
Other sensitive commands: {$DB_BlockQ}<br/>
<a href="db_edit.php?id={$DB_ID}">Change db settings</a><br/>
