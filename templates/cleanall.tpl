{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
Press on the button to clean all alerts.<br/><br/>
<form method="POST">
<input type=submit name=submit value="submit">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<br/>
<br/>
</form>
