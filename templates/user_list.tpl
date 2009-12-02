{if $HelpPage}
{include file="$HelpPage"}
{/if}
{if $msg }
<pre>{$msg}</pre>		
{/if }
<a href="user_add.php?{$TokenName}={$TokenID}"><img src="images/admin-add.gif" title="Create new Administrator" border="0" style="vertical-align:middle">Add new Administrator</a><br/>
{$admins}
