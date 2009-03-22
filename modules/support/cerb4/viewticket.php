<?php
require_once("libs/Cerb4Init.php");

@$ticket_id = Cerb4_WebApi::importGPC($_REQUEST['tid'],'integer',0);

if ($_REQUEST['cmd'] == 'post_reply') {
	@$from = Cerb4_WebApi::importGPC($_REQUEST['from'],'string');
	@$subject = Cerb4_WebApi::importGPC($_REQUEST['subject'],'string');
	@$reply_to = html_entity_decode(Cerb4_WebApi::importGPC($_REQUEST['message_id'],'string',''));
	@$body = Cerb4_WebApi::importGPC($_REQUEST['body'],'string');
	$to = SUPPORT_EMAIL;
	$message_id = Cerb4_WebApi::generateMessageId();
	
	$mail =
		 "<message><source><![CDATA["
		."From: $from\r\n"
		."To: $to\r\n"
		."Subject: $subject\r\n"
		."In-Reply-To: $reply_to\r\n"
		."Message-ID: $message_id\r\n"
		."Date: " . date(DATE_RFC822) . "\r\n"
		."\r\n"
		.$body
		."\r\n]]></source></message>"
		;
	
	$url = WEBAPI_URL . 'parser/parse.xml';
	$out = $cerb4->post($url,$mail);
}

if($ticket_id == 0) {
	$smarty->assign('invalid_id',true);
} else {
	// Get ticket
	$url = WEBAPI_URL . 'tickets/' . $ticket_id . '.xml';
	$out = $cerb4->get($url);
	$ticket = simplexml_load_string($out);
	$smarty->assign('ticket',$ticket);

	// Check for access - owner or org member?
	if($ticket->first_wrote_address_id != $Cerb4_address_id) {
		$smarty->assign('access_denied',true);
	} else {
		// Get message list
		$url = WEBAPI_URL . 'messages/list.xml?ticket_id=' . $ticket_id;
		$out = $cerb4->get($url);
		$message_list = simplexml_load_string($out);
		
		// Get individual messages
		$messages = array();
		foreach($message_list as $msg) {
			$url = WEBAPI_URL . 'messages/' . $msg->id . '.xml';
			$out = $cerb4->get($url);
			$message = simplexml_load_string($out);
			$messages[] = $message;
		}
		//$messages = array_reverse($messages); //Uncomment this to show latest message first
		$smarty->assign('messages',$messages);
		
		$messageID = '';
		
		foreach($messages[0]->headers->children() as $k => $v) {
		//	echo("key: '" . $k . "'  Value: '" . $v . "'<br>\r\n");
			if($k == 'message-id')
				$messageID = $v;
		}
		
		$smarty->assign('message_id', $messageID);
	}
}

$templatefile = "viewticket";
?>