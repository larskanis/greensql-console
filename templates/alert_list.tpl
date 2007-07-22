{ if $status == 1 }
<div id="help_content">
<div id="hide_help">
<a href="javascript:hide_help();void(0);">hide</a>
</div>
<div id="help_text">
<h3>What is a whitelist?</h3>
A whitelist is a list of SQL patterns that have been approved and will be ignored by GreenSQL.  Patterns can be added to the whitelist once an alert is generated for that pattern.  Find the query in the <a href="alert_list.php">Alerts section</a> and choose to Allow query.<br/>
</div>
</div>
<div id="show_help">
<a href="javascript:show_help();void(0);">help</a>
</div>

{ /if }
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%">
<tr>
 <td width=20>ID</td>
 <td width=90>Time</td>
 <td width=80>Listener</td>
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

<br/>
<center>{$pager}</center>
<br/>

