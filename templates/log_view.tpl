{if $HelpPage}
{include file="$HelpPage"}
<div style="clear:both"></div>
{/if}
{if $msg }
<pre>{$msg}</pre>
{/if }
<div style="border:solid 1px; text-align:left; height:70%; overflow:scroll;">
{$Log}
</div>
