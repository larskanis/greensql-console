<div id='help'>
<div id="help_content">
<h3>What is a listener?</h3>
Listener object is a kind of proxy object used to connect frontend connection to specific backend server. Look at the following chart.
<img src="images/listener.jpg">
<br/>
<center>
<a href="javascript:hide_help();void(0);">hide help</a>
</center>
</div>
<div id="show_help">
<center>
<a href="javascript:show_help();void(0);">show help</a>
</center>
</div>
</div>
<h3>Edit Listener</h3>
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
<tr>
 <td colspan=2 align=center><br/><input type=submit name=submit value="submit"></input></td>
</tr>
</table>
<input type="hidden" name="proxyid" value="{$PROXY_ID}"></input>
</form>
