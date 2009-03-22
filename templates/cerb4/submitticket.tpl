<form action="{$self}" method="post">
<input type="hidden" name="cmd" value="create_ticket">

<table cellspacing="1" cellpadding="0" class="frame"><tr><td>
<table width="100%" cellpadding="2">
<tr>
	<td width="120" class="fieldarea">{$LANG.supportticketsclientemail}</td>
	<!--<td>{if $loggedin}{$email}{else}<input type="text" name="from" size="50" value="{$email}" />{/if}</td>-->
	<td><input type="text" name="from" size="50" value="{$email}" /></td>
</tr>
<tr>
	<td class="fieldarea">{$LANG.supportticketsticketsubject}</td>
	<td><input type="text" name="subject" size="60" value="{$subject}" /></td>
</tr>

<tr>
	<td colspan="2" class="fieldarea"><textarea name="body" rows="12" cols="60" style="width:100%">{$message}</textarea></td>
</tr>

</table>
</td></tr></table>

<p align="center"><input type="submit" value="{$LANG.supportticketsticketsubmit}" /></p>
</form>
