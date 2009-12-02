<html>
	<head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>{$Name}</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
<body style="margin:0; width:100%; height:100%;">
<div id="login-wrapper">

<form method="POST" action="login.php">
<input type="hidden" name="{$TokenName}" value="{$TokenID}">
	<div id="login-box">
	<table>
	{if $demo }
	<tr><td colspan=2><strong>Demo user: admin<br/>Demo pass: pwd</strong><br/><br/></td></tr>
	{/if}
	<tr>
		<td valign="top" width="100">Username&nbsp;</td>
		<td><input type="text" name="user" value=""></td>
	</tr><tr>
		<td valign="top" width="100">Password</td>
		<td><input type="password" name="pass" value="" autocomplete="off"></td>
	</tr><tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="center" colspan="2">
		<input type="submit" name="login" value="Login" class="formbtn"></td>
	</tr></table>
	</div>
	</form>
</div>
<div id="footer-wrapper">
<div id="footer">
<center>
<tt><a href="http://www.greensql.net/">GreenSQL Open Source Database Firewall</a></tt>
</center>
</div>
</div>
</body>
</html>

