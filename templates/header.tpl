<HTML>
<HEAD>
<TITLE>GreenSQL: {$title}</TITLE>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="code.js"></script>
</HEAD>

<BODY bgcolor="#ffffff" onLoad="init_help();">
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
<a href="logout.php">Logout</a>
</div>
</tt>
<hr>
