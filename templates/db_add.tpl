{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>Databases</h3>
{section name=sec1 loop=$databases}
<strong>{$databases[sec1].name}</strong>&nbsp;
<a href="db_view.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">view</a>&nbsp;-&nbsp;
<a href="db_edit.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">edit</a>
<br/>
{/section}
<h3>Add new database</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
<form method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
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
</form>
