{if $HelpPage}
{include file="$HelpPage"}
{/if}
{if $msg }
<pre>{$msg}</pre>
{/if }

<div class="dashboard-block" align="center">
<form method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">

<table cellspacing=0 cellpadding=0 id="table_cont" width="400px">
<tr><th colspan="2" style="text-align:center;">{$Name}</th></tr>
<tr>
 <td>Proxy name:</td>
 <td><input type=string name="proxyname" value="{$PROXY_Name}"></input></td>
</tr>

<tr>
 <td>Database Type:</td>
 <td>
  <select name="dbtype">
   <option value="mysql">MySQL</option>
   <option value="pgsql">PostgreSQL</option>
  </select> 
 </td>
</tr>

<tr>
 <td>Frontend IP:</td>
 <td><input type=string name="frontend_ip" value="{$PROXY_FrontendIP}"></input></td>
</tr>

<tr>
 <td>Frontend port:</td>
 <td><input type=string name="frontend_port" value="{$PROXY_FrontendPort}"></input></td>
</tr>

<tr>
 <td>Backend server name:</td>
 <td><input type=string name="backend_server" value="{$PROXY_BackendServer}"></input></td>
</tr>

<tr>
 <td>Backend IP:</td>
 <td><input type=string name="backend_ip" value="{$PROXY_BackendIP}"></input></td>
</tr>
<tr>
 <td>Backend port:</td>
 <td><input type=string name="backend_port" value="{$PROXY_BackendPort}"></input></td>
</tr>
<tr>
 <td colspan=2 style="text-align:center;"><input type=submit name=submit value="Submit"></input></td>
</tr>
</table>

<input type="hidden" name="proxyid" value="{$PROXY_ID}"></input>
<input type="hidden" name="type" value="proxy_add"></input>
</form>
</div>
