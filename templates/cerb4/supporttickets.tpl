<p class="heading2">{$LANG.clientareanavsupporttickets}</p>

<br />

<table align="center" class="clientareatable" cellspacing="1">

<tr class="clientareatableheading">

<td>{$LANG.supportticketsdate}</td>
<td>Reference</td>
<td>{$LANG.supportticketssubject}</td>
<td>{$LANG.supportticketsstatus}</td>

</tr>

{foreach from=$tickets item=ticket namet=ticket}

<tr class="clientareatableactive">

<td>{$ticket->created_date|date_format}</td>
<td>{$ticket->mask}</td>
<td><a href="viewticket.php?tid={$ticket->id}">{$ticket->subject}</a></td>
<td>{if $ticket->is_waiting==1}Awaiting Client{else}Open{/if}</td>

</tr>

{foreachelse}

<tr class="clientareatableactive"><td colspan="5">{$LANG.norecordsfound}</td></tr>

{/foreach}

</table>

<br />