<HTML>
<HEAD>
<TITLE>GreenSQL: {$title}</TITLE>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="code.js"></script>
</HEAD>

<BODY bgcolor="#ffffff" onLoad="init_help();">
<table width="100%">
<tr><td width="10%"><img src="images/greensql-logo.gif"></td>
<td style="vertical-align:middle">
<b><font color="#00dd00" size=4>GreenSQL</font></b><br>
<tt>
<div id="menu">
<div style="display:inline; align:left; ">
<a href="dashboard.php?{$TokenName}={$TokenID}">Dashboard</a> | 
<a href="rawalert_list.php?{$TokenName}={$TokenID}">Alerts</a> | 
<a href="alert_list.php?status=1&{$TokenName}={$TokenID}">Whitelist</a> | 
<a href="db_add.php?{$TokenName}={$TokenID}">Databases</a> | 
<a href="proxy_add.php?{$TokenName}={$TokenID}">Listeners</a> | 
<a href="log_view.php?{$TokenName}={$TokenID}">View Log</a> | 
<a href="user_edit.php?{$TokenName}={$TokenID}">Settings</a> | 
<a href="cleanall.php?{$TokenName}={$TokenID}">Clean All</a> | 
<a target="_blank" href="http://www.greensql.net/forum">Support</a> |
<a href="logout.php">Logout</a>
</div>
</tt>
</td>
</tr>
</table>
<hr>
<table width="100%">
<tr>
<td width="10%" id="menu">
<b>Databases:</b></br>
{section name=sec1 loop=$databases}
<a href="db_view.php?db_id={$databases[sec1].id}&{$TokenName}={$TokenID}">{$databases[sec1].name}</a><br/>
{/section}
</td>
<td>
