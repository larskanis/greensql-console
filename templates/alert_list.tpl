<h3>List of Alerts</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%" align="left" border=1>
<tr>
 <td width=20>ID</td>
 <td width=20%>Time</td>
 <td width=80>Proxy</td>
 <td width=60>DB</td>
 <td>Pattern</td>
</tr>

{section name=sec2 loop=$alerts}
<tr>
<td>{$alerts[sec2].agroupid}</td>
<td>{$alerts[sec2].update_time}</td>
<td>{$alerts[sec2].proxyname}</td>
<td>{$alerts[sec2].db_name}</td>
<td><a href="alert_view.php?agroupid={$alerts[sec2].agroupid}">{$alerts[sec2].short_pattern}</a></td>
</tr>
{/section}

</table>  
