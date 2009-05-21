{ $DB_Menu }
<h3>Whitelist Entry</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<table cellspacing="0" cellpadding="0" width="95%" border=1>
<tr><td width="80" valign="top">Pattern</td><td>{$entry_query}</td></tr>
<tr><td>Entry ID</td><td>{$entry_queryid}</td></tr>
<tr><td>Listener</td><td>{$entry_proxyname}</td></tr>
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
