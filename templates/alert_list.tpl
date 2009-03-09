{ $DB_Menu }
{ if $status == 1 }
{if $HelpPage}
{include file="$HelpPage"}
{/if}
{ /if }
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%" id="table_cont">
<tr>
 <td width=20>ID</td>
 <td width=150>Time</td>
 <td width=100>Listener</td>
 <td width=100>DB</td>
 <td>Pattern</td>
</tr>

{ if count($alerts) > 0 }
{section name=sec2 loop=$alerts}
<tr>
<td>{$alerts[sec2].agroupid}</td>
<td nowrap>{$alerts[sec2].update_time}</td>
<td>{$alerts[sec2].proxyname}</td>
<td>{$alerts[sec2].db_name}</td>
<td style="overflow:hidden;" nowrap><a href="alert_view.php?agroupid={$alerts[sec2].agroupid}&{$TokenName}={$TokenID}">{$alerts[sec2].short_pattern}</a></td>
</tr>
{/section}
{ else }
<td colspan="5">The list is empty.</td>
{ /if }
</table>  

<br/>
<center>{$pager}</center>
<br/>

