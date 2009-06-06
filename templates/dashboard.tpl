<h3>{$Name}</h3>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr>
{ if $NUM_Dbs == 1 or $def_pwd == 1 }
<td width=50%><h4>Tips</h4>
{ if $NUM_Dbs == 1 }
You can start by creating your first database <a href="db_add.php?{$TokenName}={$TokenID}">here</a>.
<br/>
You can alter default listener settings <a href="proxy_add.php?proxyid=1&{$TokenName}={$TokenID}">here</a>.
<br/>
{ /if }
{ if $def_pwd == 1 }
Please change default password <a href="user_edit.php?{$TokenName}={$TokenID}">here</a>.
<br/>
{ /if }
</td>
{ /if }
<td width=50%>
<h4>Stats</h4>
Number of new alerts: {$NUM_Alers}<br/>
Number of databases : {$NUM_Dbs}<br/>
</td></tr>
</table>
<h4>Alerts</h4>
{$alerts}
{ if $NUM_Alers > 10 }
<center><a href="{$more_alerts}">More</a></center>
{ /if }
<h4>News</h4>
<table cellspacing=0 cellpadding=0 width="100%" id="table_cont">
<tr>
 <td width=150>Date & Time</td>
 <td>Description</td>
</tr>
{ if count($news) > 0 }
{section name=info loop=$news}
<tr bgcolor="#ffffe0">
<td nowrap style="font-size:13px;">{$news[info].date}</td>
<td style="overflow:hidden;" nowrap><a href='{$news[info].link}'>{$news[info].title}</a></td>
</tr>
{/section}
{ else }
<td colspan="2">No news available.</td>
{ /if }
</table>
