<?php
/*
 * Access keys - from your Cerb4 admin API config
 */
$access_key = "xxxxxxxxxxxxxxx"; 
$secret_key = "xxxxxxxxxxxxxxx";

/*
 * URL to your webapi. May not need index.php if you have .htaccess to remove it
 */
define('WEBAPI_URL','http://yourcerb4domain.com/index.php/webapi/');

/*
 * Define the default email for your install eg support@yourdomain.com
 * You can then move to specific groups from within Cerb4 admin
 */
define('SUPPORT_EMAIL','support@yourcerb4domain.com');


//*****************************************************************************
// You shouldn't need to edit anything below this line.
require_once("Cerb4WebApi.php");
require_once("./includes/clientfunctions.php");

$cerb4 = new Cerb4_WebApi($access_key, $secret_key);

// Get client details array from WHMCS.
$Cerb4Client = getClientsDetails($clientsdetails['id']);

// Get address id and org id from Cerb4 based on email address
$url = WEBAPI_URL . 'addresses/search.xml';
$payload = sprintf('<search><params><email oper="eq" value="%s"/></params></search>', $Cerb4Client['email']);
$out = $cerb4->post($url, $payload);
$xml = simplexml_load_string($out);
$address = $xml->address;
$Cerb4_address_id = (integer) $address->id;
$Cerb4_contact_org_id = (integer) $address->contact_org_id;

$smarty->assign('email',$Cerb4Client['email']);
$smarty->assign('self',$_SERVER['PHP_SELF']);
?>