{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%" >
<tr>
 <td width=160>Date & Time</td>
 <td width=100>User [DB]</td>
 <td>Description</td>
 <td>Status</td>
</tr>

{section name=sec2 loop=$alerts}
<tr bgcolor={$alerts[sec2].color}>
<td>{$alerts[sec2].event_time}</td>
<td>{$alerts[sec2].user} [{$alerts[sec2].db_name}]</td>
<td><a href="alert_view.php?agroupid={$alerts[sec2].agroupid}&{$TokenName}={$TokenID}">{$alerts[sec2].short_description}</a></td>
<td>{$alerts[sec2].block_str}</td>
</tr>
{/section}

</table>  
<br/>
<center>{$pager}</center>
<br/>
