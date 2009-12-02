{if $HelpPage}
{include file="$HelpPage"}
{/if}
Change db: <select style="width:232px;height:21px;font-size:15px" onchange="location=this.options[this.selectedIndex].value">
<option value="db_edit.php?db_id=0&{$TokenName}={$TokenID}">All Databases</option>
{section name=sec1 loop=$databases}
{if $DB_ID==$databases[sec1].id}
  <option value="db_edit.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}" selected="selected">{$databases[sec1].name}</option>
{else}
  <option value="db_edit.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">{$databases[sec1].name}</option>
{/if}
{/section}
</select>{$DB_Menu}

{if $msg }
<pre style="margin-top:10px;">{$msg}</pre>
{/if }
<form method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<BR style="line-height:15px;"/>

<div align="center">
<table cellspacing=0 cellpadding=0 id="table_cont" width="600px" align="center">
<tr><th colspan="2" style="text-align:center;">Database Settings</th></tr>
<tr>
 <td>Database Name:</td>
 <td><input type=string name="db_name" value="{$DB_Name}"></input></td>
</tr>

{ if $DB_ProxyID }
<tr>
 <td>GreenSQL Proxy:</td>
 <td>
 <select name=proxyid>
 {section name=sec1 loop=$proxies}
  {if $DB_ProxyID == $proxies[sec1].id}
    <option value="{$proxies[sec1].id}" selected="selected">{$proxies[sec1].name}</option>
  {else}
    <option value="{$proxies[sec1].id}">{$proxies[sec1].name}</option>
  {/if}
 {/section}
 </select>
 </td>
</tr>
{ /if }

<tr>
 <td>Blocking Mode:</td>
 <td>
 <select name=block_mode>
 {section name=sec1 loop=$block_modes}
  {if $block_mode == $block_modes[sec1].id}
    <option value="{$block_modes[sec1].id}" selected="selected">{$block_modes[sec1].name}</option>
  {else}
    <option value="{$block_modes[sec1].id}">{$block_modes[sec1].name}</option>
  {/if}
 {/section}
 </select>
 </td>
</tr>
</table>
</div>

<BR style="line-height:15px;"/>

<div align="center">
<table cellspacing=0 cellpadding=0 id="table_cont" width="600px">
<tr>
  <th style="text-align:center">Privileged Operation</th>
  <th style="text-align:center">Allowing Operation</th>
  <th style="text-align:center">Blocking Operation</th>
</tr>
<tr>
 <td>Change DB Structure:</td>
 <td style="text-align:center"><input type=radio class='radio' name="alter_perm" value="1" {if $DB_Alter} checked="true"{/if}>Allow</td>
 <td style="text-align:center"><input type=radio class='radio' name="alter_perm" value="0" {if $DB_Alter ==0} checked="true" {/if}>Block</td>
<tr>

<tr>
 <td>Create tables/indices:</td>
 <td style="text-align:center"><input type=radio class='radio' name="create_perm" value="1" {if $DB_Create} checked="true"{/if}>Allow</td>
 <td style="text-align:center"><input type=radio class='radio' name="create_perm" value="0" {if $DB_Create ==0} checked="true" {/if}>Block</td>
<tr>

<tr>
 <td>Drop tables/indices:</td>
 <td style="text-align:center"><input type=radio class='radio' name="drop_perm" value="1" {if $DB_Drop} checked="true"{/if}>Allow</td>
 <td style="text-align:center"><input type=radio class='radio' name="drop_perm" value="0" {if $DB_Drop ==0} checked="true" {/if}>Block</td>
<tr>

<tr>
 <td>Disclose table stucture:</td>
 <td style="text-align:center"><input type=radio class='radio' name="info_perm" value="1" {if $DB_Info} checked="true"{/if}>Allow</td>
 <td style="text-align:center"><input type=radio class='radio' name="info_perm" value="0" {if $DB_Info ==0} checked="true" {/if}>Block</td>
 </td>
<tr>

<tr>
 <td>Execute sensitive commands:</td>
 <td style="text-align:center"><input type=radio class='radio' name="block_q_perm" value="1" {if $DB_BlockQ} checked="true"{/if}>Allow</td>
 <td style="text-align:center"><input type=radio class='radio' name="block_q_perm" value="0" {if $DB_BlockQ ==0} checked="true" {/if}>Block</td>
<tr>
<tr>
 <td colspan=3 style="text-align:center;"><br/><input type=submit name=submit value="Submit"></input></td>
</tr>
</table>  
</div>
</form>
