{if $HelpPage}
{include file="$HelpPage"}
{/if}
{if $msg }
<pre>{$msg}</pre>
{/if }
<br/>
<h4>List of Databases</h4>
{$databases}
<br/>
<h4>List of Proxies</h4>
{$proxy}
