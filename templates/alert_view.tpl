{if $HelpPage}
{include file="$HelpPage"}
{/if}
Change db: <select style="width:232px;height:21px;font-size:15px" onchange="location=this.options[this.selectedIndex].value">
<option value="rawalert_list.php?db_id=0&{$TokenName}={$TokenID}">All Databases</option>
{section name=sec1 loop=$databases}
{if $DB_ID==$databases[sec1].id}
  <option value="rawalert_list.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}" selected="selected">{$databases[sec1].name}</option>
{else}
  <option value="rawalert_list.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">{$databases[sec1].name}</option>
{/if}
{/section}
</select>{$DB_Menu}
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing="0" cellpadding="0" width="95%" border=1>
<tr><td width="80" valign="top">Pattern</td><td>{$AGROUP_pattern}</td></tr>
<tr><td>Alert ID</td><td>{$AGROUP_agroupid}</td></tr>
<tr><td>Time</td><td>{$AGROUP_update_time}</td></tr>
<tr><td>Listener</td><td>{$AGROUP_proxyname}</td></tr>
<tr><td>DB</td><td>{$AGROUP_db_name}</td></tr>
{if $AGROUP_status == 0 && $AGROUP_bad == 0}
<tr><td colspan=2><br/>
<form method="post">
<input type="hidden" name="action" value="approve">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<input type="submit" name="submit" value="Add to Whitelist">
<input type="submit" name="submit" value="Hide Pattern">
</form>
</td></tr>
{ elseif $AGROUP_status == 0 && $AGROUP_bad == 1 }
<tr><td colspan=2><br/>
This query has bad format. You can only ignore it.<br/><br/>
<form method="post">
<input type="hidden" name="action" value="approve">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<input type="submit" name="submit" value="Ingore this query">
</form>
</td></tr>

{ else }
<tr><td colspan=2>&nbsp;</td></tr>
{ /if }
</table>
<h3>Matching queries:</h3>
<table cellspacing="0" cellpadding="0" width="95%" border=1>
{section name=sec2 loop=$alerts}
<tr><td width="80" valign="top">Query:</td><td>{$alerts[sec2].query}</td></tr>
<tr><td>Time:</td><td>{$alerts[sec2].event_time}</td><tr>
<tr><td>DB User:</td><td>{$alerts[sec2].user}&nbsp;</td></tr>
<tr><td>Risk:</td><td>{$alerts[sec2].risk} {$alerts[sec2].block_str}</td></tr>
<tr><td>Reason:</td><td>{$alerts[sec2].reason}</td></tr>
<tr><td>ID:</td><td>{$alerts[sec2].alertid}</td></tr>
<tr><td colspan=2>
<form method="POST">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="alertid" value="{$alerts[sec2].alertid}">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<input type="submit" name="submit" value="Remove Alert">
</form><br/>
</td></tr>
{/section}
</table>  
