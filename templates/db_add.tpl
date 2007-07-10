<h3>Add New Database</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
<form method="POST">
<table cellspacing=0 cellpadding=0>
<tr>
 <td>Database Name:</td>
 <td><input type=string name="dbname" value=""></input></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
 <td>Select Proxy Listener:</td>
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
