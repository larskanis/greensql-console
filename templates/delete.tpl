<form method="POST">
	<input type="hidden" name="{$TokenName}" value="{$TokenID}">
	{if $msg }
		<pre>{$msg}</pre>
	{/if }
	<center>
<font color="red" style="font-weight:bold;">Warning:</font> By Pressing Delete, The {$Type} "{$DB_Name}" will be deleted.<br/>
<br/>
<font color="red" style="font-weight:bold;">You will need to restart GreenSQL firewall for the changes to take effect.<br/>
If the GreenSQL won't be restarted, the firewall behavior is unexpected.</font>
		<br/>
		<br/>
		<input type=submit name=delete value="Delete">
		<br/>
		<br/>
	</center>
</form>
