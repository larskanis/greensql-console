<div id='help'>
<div id="help_content">
<h3>What is database?</h3>
Database name coinside with the back end server database name.<br/>
<center>
<a href="javascript:hide_help();void(0);">hide help</a>
</center>
</div>
<div id="show_help">
<center>
<a href="javascript:show_help();void(0);">show help</a>
</center>
</div>
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
 <td>Select listener:</td>
 <td>
 <select name=proxyid>
 {html_options values=$option_values selected=$option_selected output=$option_output}
 </select>
 </td>
</tr>

<tr>
 <td colspan=2 align=center><br/><input type=submit name=submit value="submit"></input></td>
</tr>

</table>  
