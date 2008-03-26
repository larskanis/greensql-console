<h3>{$Name}</h3>
{ if $NUM_Dbs == 1 }
You can start by creating your first database <a href="db_add.php?{$TokenName}={$TokenID}">here</a>.
<br/>
<br/>
You can alter default listener settings <a href="proxy_add.php?proxyid=1&{$TokenName}={$TokenID}">here</a>.
<br/>
<br/>
{ /if }
{ if $def_pwd == 1 }
Please change default password <a href="user_edit.php?{$TokenName}={$TokenID}">here</a>.
<br/>
<br/>
{ /if }
Number of new alerts: {$NUM_Alers}<br/>
Number of databases : {$NUM_Dbs}<br/>
<br/>
<table cellspacing=0 cellpadding=0 width="100%" >
<tr>
 <td width=20%>Time</td>
 <td width=60>DB</td>
 <td>Pattern</td>
 <td>Action</td>
</tr>

{section name=sec2 loop=$alerts}
<tr bgcolor={$alerts[sec2].color}>
<td>{$alerts[sec2].event_time}</td>
<td>{$alerts[sec2].db_name}</td>
<td><a href="alert_view.php?agroupid={$alerts[sec2].agroupid}&{$TokenName}={$TokenID}">{$alerts[sec2].short_query}</a></td>
<td>{$alerts[sec2].block_str}</td>
</tr>
{/section}

</table>
<br/>
{ if $NUM_Alers > 10 }
<center><a href="rawalert_list.php?p=1&{$TokenName}={$TokenID}">More</a></center>
{ /if }
