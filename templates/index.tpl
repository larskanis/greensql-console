{config_load file=test.conf section="setup"}
{include file="header.tpl" title=$Name}

<table cellspacing=0 cellpadding=0>
<tr>
<td valign="top" width="100">
Databases:<br/>
{section name=sec1 loop=$databases}
<a href="db_view.php?id={$databases[sec1].id}">{$databases[sec1].name}</a><br/>
{/section}
</td>

<td valign="top" align="left" width="80%">
{include file="$Page"}
</td>
<tr>
</table>
<p>

{include file="footer.tpl"}
