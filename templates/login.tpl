<html>
<head>
<title>{$Name}</title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>
<body>
<font color="#00e000"><h3 align="center">{$Name}</h3></font>

<form method="POST" action="login.php">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
<table cellspacing=0 cellpadding=0 align="center">

{if $demo }
<tr><td colspan=2><strong>Demo user: admin<br/>Demo pass: pwd</strong><br/><br/></td></tr>
{/if}

{if $msg }
<tr><td colspan=2>{$msg}<br/></td></tr>
{/if }

<tr><td valign="top" width="100">Username</td>
    <td><input type="text" name="user" value=""></td>
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
