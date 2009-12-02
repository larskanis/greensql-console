{if $HelpPage}
	{include file="$HelpPage"}
{/if}
&nbsp;
<center>
	<u>
		<h3>{$Name}</h3>
	</u>
</center>
{if $msg }
	<pre>{$msg}</pre>
{/if }
<form method="POST">
	<input type="hidden" name="{$TokenName}" value="{$TokenID}">
	&nbsp;
	<center>
		<table cellspacing=0 cellpadding=5 width="90%">
			<tr>
				 <td width="10%">Database:</td>
				 <td>{$db_name}</td>
			</tr>
			<tr>
				 <td width="10%">Proxy:</td>
				 <td>{$proxy}</td>
			</tr>		
			<tr>
				<td>Pattern:</td>
				<td>
					<input type="text" name="pattern" value="" autocomplete="off" class="bigtext">
				</td>
			</tr>
			<tr>
				 <td colspan=2 align=center><br/><input type=submit name=submit value="submit"></input></td>
			</tr>
		</table>
	</center>  
</form>