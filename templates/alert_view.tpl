<h3>View Alert</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing="0" cellpadding="0" width="95%" align="left" border=1>
<tr><td width="80" valign="top">Pattern</td><td>{$AGROUP_pattern}</td></tr>
<tr><td>Alert ID</td><td>{$AGROUP_agroupid}</td></tr>
<tr><td>Time</td><td>{$AGROUP_update_time}</td></tr>
<tr><td>Proxy</td><td>{$AGROUP_proxyname}</td></tr>
<tr><td>DB</td><td>{$AGROUP_db_name}</td></tr>
{if $AGROUP_status == 0 && $AGROUP_bad == 0}
<tr><td colspan=2><br/>
<form method="post">
<input type="hidden" name="action" value="approve">
In order to always allow this SQL pattern press on "Allow this query" button.</br><br/>
In order to ignore this SQL pattern press on the "Ingore this query" button.<br/>In case query selected is blocked you will not get any report in the console.<br/><br/>
<input type="submit" name="submit" value="Allow this query">
<input type="submit" name="submit" value="Ingore this query">
</form>
</td></tr>
{ elseif $AGROUP_status == 0 && $AGROUP_bad == 1 }
<tr><td colspan=2><br/>
This query has bad format. You can only ignore it.<br/><br/>
<form method="post">
<input type="hidden" name="action" value="approve">
<input type="submit" name="submit" value="Ingore this query">
</form>
</td></tr>

{ else }
<tr><td colspan=2>&nbsp;</td></tr>
{ /if }
{section name=sec2 loop=$alerts}
<tr><td width="80" valign="top">Query:</td><td>{$alerts[sec2].query}</td></tr>
<tr><td>Time:</td><td>{$alerts[sec2].event_time}</td><tr>
<tr><td>DB User:</td><td>{$alerts[sec2].user}&nbsp;</td></tr>
<tr><td>Risk:</td><td>{$alerts[sec2].risk} {$alerts[sec2].block_str}</td></tr>
<tr><td>Reason:</td><td>{$alerts[sec2].reason}</td></tr>
<tr><td>ID:</td><td>{$alerts[sec2].alertid}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
{/section}
</table>  
