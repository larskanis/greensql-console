{ if $status == 1 }
<div id='help'>
<div id="help_content">
<h3>What is whitelist?</h3>
Whitelist is a list of SQL patterns that have been approved.<br/>
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
{ /if }
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing=0 cellpadding=0 width="100%">
<tr>
 <td width=20>ID</td>
 <td width=90>Time</td>
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

<br/>
<center>{$pager}</center>
<br/>

