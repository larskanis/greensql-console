<h3>Edit Database Settings: {$DB_Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
<form method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">

{ if ! $DB_ProxyID }
<input type=hidden name="proxyid" value="0">
{ /if }

<table cellspacing=0 cellpadding=0>
<tr>
 <td>Database Name:</td>
 <td><input type=string name="db_name" value="{$DB_Name}"></input></td>
</tr>

{ if $DB_ProxyID }
<tr><td colspan=2>&nbsp;</td></t
<tr>
 <td>GreenSQL Listener:</td>
 <td>
 <select name=proxyid>
 {html_options values=$option_values selected=$option_selected output=$option_output}
 </select>
 </td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
 <td>Blocking Mode:</td>
 <td>
 <select name=block_mode>
 {html_options values=$block_values selected=$block_selected output=$block_output}
 </select>
 </td>
</tr>
{ else }
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
 <td>Blocking Mode:</td>
 <td>Block based on Risk</td>
</tr>
{ /if }
<tr><td colspan=2><br><strong>Privileged Operations</strong><br/></td></tr>

<tr>
 <td>Change DB Structure:</td>
 <td>
 <input type=radio name="alter_perm" value="0" {if $DB_Alter ==0} checked="true" {/if}>Block
 <input type=radio name="alter_perm" value="1" {if $DB_Alter} checked="true"{/if}>Allow
 </td>
<tr>

<tr>
 <td>Create tables/indices:</td>
 <td>
 <input type=radio name="create_perm" value="0" {if $DB_Create ==0} checked="true" {/if}>Block
 <input type=radio name="create_perm" value="1" {if $DB_Create} checked="true"{/if}>Allow
 </td>
<tr>

<tr>
 <td>Drop tables/indices:</td>
 <td>
 <input type=radio name="drop_perm" value="0" {if $DB_Drop ==0} checked="true" {/if}>Block
 <input type=radio name="drop_perm" value="1" {if $DB_Drop} checked="true"{/if}>Allow
 </td>
<tr>

<tr>
 <td>Disclose table stucture:</td>
 <td>
 <input type=radio name="info_perm" value="0" {if $DB_Info ==0} checked="true" {/if}>Block
 <input type=radio name="info_perm" value="1" {if $DB_Info} checked="true"{/if}>Allow
 </td>
<tr>

<tr>
 <td>Execute sensitive commands:</td>
 <td>
 <input type=radio name="block_q_perm" value="0" {if $DB_BlockQ ==0} checked="true" {/if}>Block
 <input type=radio name="block_q_perm" value="1" {if $DB_BlockQ} checked="true"{/if}>Allow
 </td>
<tr>

<tr>
 <td colspan=2 align=center><br/><input type=submit name=submit value="submit"></input></td>
</tr>

</table>  
</form>
