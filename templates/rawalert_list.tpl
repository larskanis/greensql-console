<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%" >
<tr>
 <td width=90>Time</td>
 <td width=60>DB</td>
 <td>Pattern</td>
 <td>Action</td>
</tr>

{section name=sec2 loop=$alerts}
<tr bgcolor={$alerts[sec2].color}>
<td>{$alerts[sec2].event_time}</td>
<td>{$alerts[sec2].db_name}</td>
<td><a href="alert_view.php?agroupid={$alerts[sec2].agroupid}">{$alerts[sec2].short_query}</a></td>
<td>{$alerts[sec2].block_str}</td>
</tr>
{/section}

</table>  
<br/>
<center>{$pager}</center>
<br/>
