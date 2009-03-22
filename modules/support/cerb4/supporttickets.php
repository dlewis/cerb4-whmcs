<?php
require_once("libs/Cerb4Init.php");

$params
	=sprintf('<first_wrote_address_id oper="eq" value="%s"/>',$Cerb4_address_id)
	.'<is_closed oper="eq" value="0"/>'
	.'<is_deleted oper="eq" value="0"/>';

$url = WEBAPI_URL . 'tickets/search.xml';
$payload = sprintf('<search><params>%s</params></search>', $params);
$out = $cerb4->post($url, $payload);
$xml = simplexml_load_string($out);
$tickets = $xml->ticket;

$smarty->assign('tickets',$tickets);

$templatefile = "supporttickets";
?>