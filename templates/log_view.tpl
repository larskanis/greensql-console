{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>View Application Logs</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
<br/>
<div style="border:solid 1px;">
{$Log}
</div>
