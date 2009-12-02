{if $HelpPage}
{include file="$HelpPage"}
{/if}
Change db: <select style="width:232px;height:21px;font-size:15px" onchange="location=this.options[this.selectedIndex].value">
<option value="whitelist.php?db_id=0&{$TokenName}={$TokenID}">All Databases</option>
{section name=sec1 loop=$databases}
{if $DB_ID==$databases[sec1].id}
  <option value="whitelist.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}" selected="selected">{$databases[sec1].name}</option>
{else}
  <option value="whitelist.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">{$databases[sec1].name}</option>
{/if}
{/section}
</select>{$DB_Menu}

<h3>Whitelist Entry</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing="0" cellpadding="0" width="95%" border=1>
<tr><td width="80" valign="top">Pattern</td><td>{$entry_query}</td></tr>
<tr><td>Entry ID</td><td>{$entry_queryid}</td></tr>
<tr><td>Proxy</td><td>{$entry_proxyname}</td></tr>
<tr><td>DB</td><td>{$entry_db_name}</td></tr>
<tr><td colspan=2><br/>
<form method="post">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<input type="hidden" name="queryid" value={$entry_queryid}>
<input type="submit" name="submit" value="Remove from Whitelist"><input type="checkbox" name="confirm">Confirm deletion.
</form>
</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
</table>
