<div id="help_content">
<div id="hide_help">
<a href="javascript:hide_help();void(0);">hide</a>
</div>
<div id="help_text">
<h3>What is a database?</h3>
Database names coincide to the names of databases on the backend SQL server.  A database should be coupled with a GreenSQL <a href="proxy_add.php">Listener object</a>. A number of databases can use the same GreenSQL Listener.  You can edit the permissions for each database to block actions such as changes to DB structure, execution of sensitive commands.  <br/>
</div>
</div>
<div id="show_help">
<a href="javascript:show_help();void(0);">help</a>
</div>

<h3>Databases</h3>
{section name=sec1 loop=$databases}
<strong>{$databases[sec1].name}</strong>&nbsp;
<a href="db_view.php?id={$databases[sec1].id}">view</a>&nbsp;-&nbsp;
<a href="db_edit.php?id={$databases[sec1].id}">edit</a>
<br/>
{/section}
<h3>Add new database</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
<form method="POST">
<table cellspacing=0 cellpadding=0>
<tr>
 <td>Database name:</td>
 <td><input type=string name="dbname" value=""></input></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
 <td>GreenSQL Listener:</td>
 <td>
 <select name=proxyid>
 {html_options values=$option_values selected=$option_selected output=$option_output}
 </select>
 </td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
 <td colspan=2 align=center><input type=submit name=submit value="submit"></input></td>
</tr>

</table>  
