{if $invalid_id}
	<p>{$LANG.supportticketinvalid}</p>
{elseif $access_denied}
	<p style="color:red;">Access Denied.</p>
{else}
	<p class="heading2">{$LANG.supportticketsviewticket}: {$ticket->mask}</p>

	<table cellspacing="1" cellpadding="0" class="frame"><tr><td>
	<table width="100%" cellpadding="2">
	<tr><td width="100" class="fieldarea">{$LANG.supportticketsdepartment}:</td><td>{$ticket->team_name}</td></tr>
	<tr><td class="fieldarea">{$LANG.supportticketsdate}:</td><td>{$ticket->created_date|date_format}</td></tr>
	<tr><td class="fieldarea">{$LANG.supportticketssubject}:</td><td>{$ticket->subject}</td></tr>
	<tr><td class="fieldarea">{$LANG.supportticketsstatus}:</td><td>{if $ticket->is_waiting==1}Awaiting Client{else}Open{/if}</td></tr>
	</table>
	</td></tr></table>

	<br />
	
	{foreach from=$messages item=message name=message}
	<div class="{if $reply.admin}admin{else}client{/if}ticketreplyheader">
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td>{$message->headers->from}</td>
	<td align="right">{$message->headers->date}</td>
	</tr>
	</table>
	</div>
	<div class="{if $reply.admin}admin{else}client{/if}ticketreply">{$message->content|nl2br}</div>
	<br />
	{/foreach}
	
	<p><strong>&nbsp;&raquo;&nbsp;&nbsp;{$LANG.supportticketsreply}</strong></p>
	
	<form action="{$self}" method="post">
	<input type="hidden" name="cmd" value="post_reply">
	<input type="hidden" name="tid" value="{$ticket->id}">
	<input type="hidden" name="message_id" value="{$message_id|escape}">
	<input type="hidden" name="from" value="{$email}">
	<input type="hidden" name="subject" value="{$ticket->subject}">
	<table cellspacing="1" cellpadding="0" class="frame"><tr><td>
	<table width="100%" cellpadding="2">
	<tr><td width="100" class="fieldarea">{$LANG.supportticketsclientemail}</td><td>{if $loggedin}{$email}{else}<input type="text" name="replyemail" size=50 value="{$replyemail}" />{/if}</td></tr>
	<tr><td colspan="2" class="fieldarea"><textarea name="body" rows="12" cols="60" style="width:100%"></textarea></td></tr>
	</table>
	</td></tr></table>

	<p align="center"><input type="submit" value="{$LANG.supportticketsticketsubmit}" class="button" /></p>

</form>
{/if}