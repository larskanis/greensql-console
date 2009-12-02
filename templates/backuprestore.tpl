{if $HelpPage}
{include file="$HelpPage"}
{/if}
{if $msg }
<pre>{$msg}</pre>		
{/if }
&nbsp;
<center>
 <table>
  <tr height="40%">
   <td colspan="4">&nbsp;</td>
 </tr>
 <tr>
  <td align="left">
   <a href="backup.php?{$TokenName}={$TokenID}" style="padding-left:5%;"><img src="images/db-backup.gif" title="Backup Data" border="0" style="vertical-align:middle">&nbsp;Backup</a>
  </td>
  <td colspan="2" width="10%">&nbsp;</td>
  <td align="right">
   <a href="backuprestore.php?type=restore&{$TokenName}={$TokenID}" style="padding-left:5%;"><img src="images/db-restore.gif" title="Restore Data" border="0" style="vertical-align:middle">&nbsp;Restore</a>
  </td>
 </tr>
 <tr height="40%">
  <td colspan="4">&nbsp;</td>
 </tr>		                
</table>
{$restore}	
{$filelink}
</center>
