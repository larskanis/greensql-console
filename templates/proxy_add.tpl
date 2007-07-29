<div id="help_content">
<div id="hide_help">
<a href="javascript:hide_help();void(0);">hide</a>
</div>
<div id="help_text">
<h3>What is a GreenSQL Listener?</h3>
A GreenSQL Listener object is the heart of the GreenSQL Databae Firewall.  A Listener is a proxy object used to connect queries from the frontend to a specific backend server.  Before passing the query to the backend it is checked by the Listener to determine if it is malicious and if so how it should be handled (block, alert, pass).
<br/><img src="images/listener.gif">
<br/>
</div>
</div>
<div id="show_help">
<a href="javascript:show_help();void(0);">help</a>
</div>
<h3>GreenSQL Listeners</h3>
{section name=sec1 loop=$proxies}
<strong>{$proxies[sec1].proxyname}</strong>&nbsp;
<a href="proxy_add.php?proxyid={$proxies[sec1].proxyid}">edit</a>
<br/>
{/section}

<h3>{$Name}</h3>

{if $msg }
<pre>{$msg}</pre>
{/if }

<form method="POST">
<table cellspacing=0 cellpadding=0>
<tr>
 <td>Listener name:</td>
 <td><input type=string name="proxyname" value="{$PROXY_Name}"></input></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>
  
<tr>
 <td>Frontend IP:</td>
 <td><input type=string name="frontend_ip" value="{$PROXY_FrontendIP}"></input></td>
</tr>

<tr>
 <td>Frontend port:</td>
 <td><input type=string name="frontend_port" value="{$PROXY_FrontendPort}"></input></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

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
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
 <td colspan=2 align=center><input type=submit name=submit value="submit"></input></td>
</tr>
</table>
<input type="hidden" name="proxyid" value="{$PROXY_ID}"></input>
</form>
