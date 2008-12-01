<h3>{$Name}</h3>
<h4>Tips</h4>
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
<h4>Stats</h4>
Number of new alerts: {$NUM_Alers}<br/>
Number of databases : {$NUM_Dbs}<br/>
<h4>Alerts</h4>
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
{ if $NUM_Alers > 10 }
<center><a href="rawalert_list.php?p=1&{$TokenName}={$TokenID}">More</a></center>
{ /if }
<h4>News</h4>
<table cellspacing=0 cellpadding=0 width="100%" >
<tr>
 <td width=160>Date & Time</td>
 <td>Description</td>
</tr>
{section name=info loop=$news}
<tr bgcolor="#ffffe0">
<td>{$news[info].date}</td>
<td><a href='{$news[info].link}'>{$news[info].title}</a></td>
</tr>
{/section}
</table>
