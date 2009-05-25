{ $DB_Menu }
{ if $status == 1 }
{if $HelpPage}
{include file="$HelpPage"}
{/if}
{ /if }
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

{$alerts}

<br/>
<center>{$pager}</center>
<br/>

