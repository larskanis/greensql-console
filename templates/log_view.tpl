<h3>View Application Logs</h3>
Only last {$lines} lines are displayed here.<br/>
Log events are in reverse order.<br/>
{if $msg }
<pre>{$msg}</pre>
{/if }
<br/>
<div style="border:solid 1px;">
{$Log}
</div>
