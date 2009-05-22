{ $DB_Menu }
{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }
{$alerts}
<br/>
<center>{$pager}</center>
<br/>
