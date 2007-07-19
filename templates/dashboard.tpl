<h3>{$Name}</h3>
<br/>
{ if $NUM_Dbs == 1 }
You can start by creating your first database <a href="db_add.php">here</a>.
<br/>
<br/>
You can alter default proxy settings <a href="proxy_add.php?proxyid=1">here</a>.
<br/>
<br/>
{ /if }
{ if $def_pwd == 1 }
Please change default password <a href="user_edit.php">here</a>.
{ /if }
<hr>
<br/>
Number of New Alerts: {$NUM_Alers}<br/>
Number of databases : {$NUM_Dbs}<br/>

