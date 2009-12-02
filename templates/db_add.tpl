{if $HelpPage}
{include file="$HelpPage"}
{/if}
{if $msg }
<pre>{$msg}</pre>
{/if }
&nbsp;
<div id="show_help">
<a href="javascript:show_help();void(0);">help</a>
</div>
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<div class="dashboard-space">&nbsp;</div>

<div class="dashboard-block">
<form action='db_add.php?{$TokenName}={$TokenID}' method="POST">
<P>&nbsp;</P>
<table cellspacing=0 cellpadding=0 id="table_cont" width="400px">
<tr><th colspan="2" align="center">Add new Database</th></tr>
<tr>		
	<td>DB name</td>
	<td><input type="text" name="dbname"></td>
</tr>
<tr>
	<td>Database Proxy</td>
	<td>{$proxies_combobox}</td>
</tr>
<tr><td colspan=2 style="text-align:center"><input type=submit name=submit value="Submit" {$DB_Enabled}></input></td></tr>
</table>

</form>
</div><!-- /dashboard-block -->

<div class="dashboard-space">&nbsp;</div>
<div class="dashboard-block">
<form action='proxy_add.php?{$TokenName}={$TokenID}' method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">

<table cellspacing=0 cellpadding=0 id="table_cont" width="400px">
<tr><th colspan="2" style="align:center">Database Proxy</th></tr>
<tr>
 <td>Proxy name:</td>
 <td><input type=string name="proxyname" value="{$PROXY_Name}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td>Database Type:</td>
 <td>
  <select name="dbtype" {$PROXY_Enabled}>
   {$DBTypes}
  </select>
 </td>
</tr>
<tr>
 <td>Frontend IP:</td>
 <td><input type=string name="frontend_ip" value="{$PROXY_FrontendIP}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td>Frontend port:</td>
 <td><input type=string name="frontend_port" value="{$PROXY_FrontendPort}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td>Backend server name:</td>
 <td><input type=string name="backend_server" value="{$PROXY_BackendServer}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td>Backend IP:</td>
 <td><input type=string name="backend_ip" value="{$PROXY_BackendIP}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td>Backend port:</td>
 <td><input type=string name="backend_port" value="{$PROXY_BackendPort}" {$PROXY_Enabled}></input></td>
</tr>
<tr>
 <td colspan=2 style="text-align:center;"><input type=submit name=submit value="submit" {$PROXY_Enabled}></input></td>
</tr>
</table>
<input type="hidden" name="proxyid" value="{$PROXY_ID}"></input>
</form>
</div>
