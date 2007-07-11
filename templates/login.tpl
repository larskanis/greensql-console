<html>
<head>
<title>{$Name}</title>
</head>
<body>
<h3 align="center">{$Name}</h3>
<br/>
<br/>

<form method="POST">
<table cellspacing=0 cellpadding=0 align="center">

{if $demo }
<tr><td colspan=2><strong>Demo User: admin<br/>Demo Pass: pwd</strong><br/><br/></td></tr>
{/if}

{if $msg }
<tr><td colspan=2>{$msg}<br/></td></tr>
{/if }

<tr><td valign="top" width="100">Username</td>
    <td><input type="string" name="user" value=""></td>
</tr>
<tr><td valign="top" width="100">Password</td>
    <td><input type="password" name="pass" value=""></td>
</tr>

<tr><td colspan=2" align="center">&nbsp;<br/>
    <input type="submit" name="login" value="login">
    <br/>&nbsp;
</td></tr>
</table>
</form>

{include file="footer.tpl"}
