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

<h3>{$Name}</h3>
<!--<a href="whitelist_entry_add.php?db_id={$db_id}&proxyid={$proxy_id}&{$TokenName}={$TokenID}">Add manual entry</a>-->
<div style="float: right;">Show <a href="whitelist.php?db_id={$db_id}&per_page=10&{$TokenName}={$TokenID}">10</a>&nbsp;
				<a href="whitelist.php?db_id={$db_id}&per_page=20&{$TokenName}={$TokenID}">20</a>
</div>

{if $msg }
<pre>{$msg}</pre>
{/if }

{$whitelist}	
<br/>
<center>{$pager}</center>
