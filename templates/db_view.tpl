{ $DB_Menu }
<h3>{$DB_Name}</h3>
{ if $DB_ProxyID }
<strong>Listener settings</strong><br/>
Name: {$DB_ProxyName}<br/>
Frontend: {$DB_Listener}<br/>
Backend:  {$DB_Backend}<br/>
Status:   {$DB_Status}<br/>
Type:     {$DB_Type}<br/>
<a href="proxy_add.php?proxyid={$DB_ProxyID}&{$TokenName}={$TokenID}">Change listener settings</a><br/>
<br/>
<br/>
{ /if }
<strong>Blocking Setting:</strong><br/>
{$DB_BlockStatus}<br/><br/>
<strong>Privileged Operations:</strong><br/>
Change database structure: {$DB_Alter}<br/>
Create command: {$DB_Create}<br/>
Disclose table stucture: {$DB_Info}<br/>
Drop command:  {$DB_Drop}<br/>
Other sensitive commands: {$DB_BlockQ}<br/>
<a href="db_edit.php?db_id={$DB_ID}&{$TokenName}={$TokenID}">Change database settings</a><br/>
