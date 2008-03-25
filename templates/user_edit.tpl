{if $HelpPage}
{include file="$HelpPage"}
{/if}
<h3>{$Name}</h3>
{if $msg }
<pre>{$msg}</pre>
{/if }

<form method="POST">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<table cellspacing=0 cellpadding=0>
<tr>
 <td>User:</td>
 <td><input type=string name="name" value="{$USER_Name}"></input></td>
</tr>

<tr>
 <td>Email:</td>
 <td><input type=string name="email" value="{$USER_Email}"></input></td>
</tr>
  
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td valign="top" width="100">Old password</td>
    <td><input type="password" name="oldpass" value=""></td>
</tr>

<tr><td valign="top" width="100">New password</td>
    <td><input type="password" name="pass" value=""></td>
</tr>

<tr><td valign="top" width="100">Verify password</td>
    <td><input type="password" name="pass2" value=""></td>
</tr>

<tr>
 <td colspan=2 align=center><br/><input type=submit name=submit value="submit"></input></td>
</tr>

</table>  
</form>
