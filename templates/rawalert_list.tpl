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
<div style="clear:both; line-height:10px;" >&nbsp;</div>
<h3 style="display:inline;">{$Name}</h3>
<div style="float:right;">{$ShowAll}</div>
{if $msg }
<pre>{$msg}</pre>
{/if }
{$alerts}
<br/>
<center>{$pager}</center>
