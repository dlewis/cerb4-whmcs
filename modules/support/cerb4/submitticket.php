<?php
require_once("libs/Cerb4Init.php");

// creating a new ticket
if($_REQUEST['cmd'] == 'create_ticket') {
	@$from = Cerb4_WebApi::importGPC($_REQUEST['from'],'string');
	@$subject = Cerb4_WebApi::importGPC($_REQUEST['subject'],'string');
	@$body = Cerb4_WebApi::importGPC($_REQUEST['body'],'string');
	$to = SUPPORT_EMAIL;
	$message_id = Cerb4_WebApi::generateMessageId();
	
	$mail =
		 "<message><source><![CDATA["
		."From: $from\r\n"
		."To: $to\r\n"
		."Subject: $subject\r\n"
		."Message-ID: $message_id\r\n"
		."Date: " . date(DATE_RFC822) . "\r\n"
		."\r\n"
		.$body
		."\r\n]]></source></message>"
		;
	
	$url = WEBAPI_URL . 'parser/parse.xml';
	$out = $cerb4->post($url,$mail);
	
	$templatefile = "ticketcreated";
} else {
	$templatefile = "submitticket";
}
?>