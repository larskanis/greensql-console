{if $HelpPage}
{include file="$HelpPage"}
{/if}
Change db: <select style="width:232px;height:21px;font-size:15px" onchange="location=this.options[this.selectedIndex].value">
<option value="db_view.php?db_id=0&{$TokenName}={$TokenID}">All Databases</option>
{section name=sec1 loop=$databases}
{if $DB_ID==$databases[sec1].id}
  <option value="db_view.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}" selected="selected">{$databases[sec1].name}</option>
{else}
  <option value="db_view.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">{$databases[sec1].name}</option>
{/if}
{/section}
</select>
{$DB_Menu}

<div style="line-height:15px;">&nbsp;</div>
<table cellspacing=0 cellpadding=0 id="table_cont" width="100%" align="center">
<tr>
  <th width="200px">Mode</th>
  <th>Reason</th>
</tr>
<tr>
  <td style="text-align:center;">{$DB_BlockStatus}</td>
  <td>{$DB_BlockStatus_Extra}</td>
</tr>
</table>
<BR style="line-height:15px;"/>

<div class="dashboard-block">
<table cellspacing=0 cellpadding=0 id="table_cont" width="100%">
<tr><th>Privileged Operations</th><th width="40%">Status</th></tr>
<tr>
  <td>Change database structure:</td>
  <td style="text-align:center;">{$DB_Alter}</td>
</tr>
<tr>
  <td>Create command:</td>
  <td style="text-align:center;">{$DB_Create}</td>
</tr>
<tr>
  <td>Disclose table stucture:</td>
  <td style="text-align:center;">{$DB_Info}</td>
</tr>
<tr>
  <td>Drop command:</td>
  <td style="text-align:center;">{$DB_Drop}</td>
</tr>
<tr>
  <td>Other sensitive commands: 
  <td style="text-align:center;">{$DB_BlockQ}</td>
</tr>
<tr><td colspan="2" style="text-align:center;">
<a href="db_edit.php?db_id={$DB_ID}&{$TokenName}={$TokenID}">Change database settings</a><br/>
</td></tr>
</table>
</div>

{ if $DB_ProxyID }
<div class="dashboard-space">&nbsp;</div>
<div class="dashboard-block">
<table cellspacing=0 cellpadding=0 id="table_cont" width="100%">
<tr><th style="text-align:center;">Proxy Settings</th><th style="text-align:center;" width="40%">Value</th></tr>
<tr>
  <td>Name:</td>
  <td>{$DB_ProxyName}</td>
</tr>
<tr>
  <td>Frontend:</td>
  <td>{$DB_Listener}</td>
</tr>
<tr>
  <td>Backend:</td>
  <td>{$DB_Backend}</td>
</tr>
<tr>
  <td>Status:</td>
  <td>{$DB_Status}</td>
</tr>
<tr>
  <td>Type:</td>
  <td>{$DB_Type}</td>
</tr>
<tr><td colspan="2" style="text-align:center;">
  <a href="proxy_add.php?proxyid={$DB_ProxyID}&{$TokenName}={$TokenID}">Change proxy settings</a>
</td></tr>
</table>
</div>
{ else }
<div class="dashboard-space">&nbsp;</div>
<div class="dashboard-block">
<table cellspacing=0 cellpadding=0 id="table_cont" width="100%">
<tr><th colspan="2" style="text-align:center;">Proxy Settings</th></tr>
<tr>
  <td colspan="2" style="text-align:center;line-height:100px;">No Proxy Available</td>
</tr>
</table>
</div>

{ /if }

