{ if $NUM_Dbs == 1 or $def_pwd == 1 }
<div class="dashboard-block">
<h4>Tips</h4>
{ if $NUM_Dbs == 1 }
<a href="db_add.php?type=newdb&{$TokenName}={$TokenID}">Create New DB</a><br/>
{ /if }
{ if $def_pwd == 1 }
<a href="user_edit.php?user_id=1&{$TokenName}={$TokenID}">Change Default Root Password</a><br>
{ /if }
</div>
<div class="dashboard-space">&nbsp;</div>
{ /if }

<div class="dashboard-block">
<h4>Stats</h4>
New Alerts: {$NUM_Alers}
&nbsp;&nbsp;
Databases : {$NUM_Dbs}
</div>
<div style="clear:both;"></div>
<table width="100%">
  <tr><td><h4>Latest Security Alerts</h4></td></tr>
  <tr><td>
{$alerts}
  </td></tr>

{ if $NUM_Alers > 7 }
  <tr><td><center><a href="{$more_alerts}">More</a></center></td></tr>
{ /if }	
</table>
<div class="dashboard-block">
<h4>GreenSQL Twitter</h4>
<table cellspacing=0 cellpadding=0 width="100%" id="table_cont">
<tr>
 <td width=120>Date & Time</td>
 <td>Description</td>
</tr>
{ if count($twitts) > 0 }
{section name=info loop=$twitts}
<tr bgcolor="#ffffe0">
<td nowrap style="font-size:13px;">{$twitts[info].date}</td>
<td style="overflow:hidden;" nowrap><a href='{$twitts[info].link}'>{$twitts[info].title}</a></td>
</tr>
{/section}
{ else }
<tr><td colspan="2">No twitts available.</td></tr>
{ /if }
</table>
</div>
<div class="dashboard-space">&nbsp;</div>
<div class="dashboard-block">
<h4>Project News</h4>
<table cellspacing=0 cellpadding=0 width="100%" id="table_cont">
<tr>
 <td width=120>Date & Time</td>
 <td>Description</td>
</tr>
{ if count($news) > 0 }
{section name=info loop=$news}
<tr bgcolor="#ffffe0">
<td nowrap style="font-size:13px;">{$news[info].date}</td>
<td style="overflow:hidden;" nowrap><a href='{$news[info].link}'>{$news[info].title}</a></td>
</tr>
{/section}
{ else }
<tr><td colspan="2">No news available.</td></tr>
{ /if }
</table>
</div>
