{if $HelpPage}
 {include file="$HelpPage"}
{/if}
&nbsp;
{if $msg }
 <pre>{$msg}</pre>
{/if }
<div style="clear: both;"></div>
<form method="POST">
 <input type="hidden" name="{$TokenName}" value="{$TokenID}">
 &nbsp;
 <center>
  <table cellspacing=0 cellpadding=0 id="table_cont" width="400px">
   <tr><th colspan="2" align="center">Add New User</th></tr>
   <tr>
    <td>User:</td>
    <td><input type=string name="name" value="{$USER_Name}"></input></td>
   </tr>
   <tr>
    <td>Email:</td>
    <td><input type=string name="email" value="{$USER_Email}"></input></td>
   </tr>		
   <tr>
    <td valign="top">Password</td>
    <td><input type="password" name="pass" value="" autocomplete="off"></td>
   </tr>
   <tr>
    <td valign="top">Verify password</td>
    <td><input type="password" name="pass2" value="" autocomplete="off"></td>
   </tr>
   <tr><td colspan=2 style="text-align:center"><br/><input type=submit name=submit value="submit"></input></td></tr>
  </table>
 </center>  
</form>
