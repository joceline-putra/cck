<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $vendor = "ruangwa.id";	
// $vendor = "umbrella.co.id";	
$vendor = "fonnte.com";	
// $vendor = "wam.umbrella.co.id";

$whatsapp_server = '';
$whatsapp_token  = '';
$whatsapp_key  = '';
$whatsapp_sender  = '';
$whatsapp_username  = '';
$whatsapp_service = array();
$whatsapp_watermark = '';

switch($vendor){
	case "ruangwa":
		$whatsapp_server   = 'http://ruang/v2/api/';
		$whatsapp_token    = 'ObbRfzVuBinT9cTgH2zdnoKxQJviNMmVNJMRdyc6sk2YKRYRhP';
		$whatsapp_username = 'joceline.putra';	
		$whatsapp_service = array(
			'send-message' => 'send-message.php',
			'send-image' => 'send-image.php',
			'send-video' => 'send-video.php',
			'send-document' => 'send-document.php',		
			'send-link' => 'send-link.php',		
			'send-location' => 'send-location.php',		
		);
		break;
	case "umbrella.co.id":
		$whatsapp_server = 'https://njs.umbrella.co.id/';
		$whatsapp_token    = '21'; // Deprecated
		$whatsapp_key      = '21'; // Deprecated
		$whatsapp_auth     = '7Ho5mMjZMKELeLiqaZd5MK3NIjw6TM';
		$whatsapp_sender   = '628989900149';
		break;
	case "fonnte.com":
		$whatsapp_server = 'https://api.fonnte.com/send';
		
		//Cece
		// $whatsapp_token = 'kWG9kt7kNAgUoe_ANzGg';		
		// $whatsapp_auth   = 'ir4ompavH@-AJGYnNq36E@Ko5#sC1Ngj';

		//Lily
		$whatsapp_token = 'jLzJPYC_HsTV6gYnFmoP';		
		$whatsapp_auth   = 'ir4ompavH@-AJGYnNq36E@Ko5#sC1Ngj';		
		// $whatsapp_sender   = '628989900149'; 
		break;		
	case "wam.umbrella.co.id":
		$whatsapp_server   = 'https://teksmu.com/';				
		$whatsapp_sender   = '628989900149';      
		
		//Client ID instance.client_id
		$whatsapp_key      = 'eyJ1aWQiOiJMeHlHUVBQYkhicm55TFhYOTl3NWVLRk9XZ2h6QzJSTyIsImNsaWVudF9pZCI6IjYyODk4OTkwMDE0OSJ9';
	
		//Token / API Keys user.api
		$whatsapp_token    = '8kprFJ0mRKGFcIKAqAvk415xOS7xYm2OVChepxFxAP7L6kJYsD';
		$whatsapp_auth     = 'LxyGQPPbHbrnyLXX99w5eKFOWghzC2RO'; //user.uid
		break;				
	default:
		$whatsapp_server = '';
		$whatsapp_token  = '';
}

$config = array(
	'whatsapp_vendor' => $vendor,
	'whatsapp_auth' => $whatsapp_auth,
	'whatsapp_token' => $whatsapp_token,
	'whatsapp_key' => $whatsapp_key,
	'whatsapp_username' => $whatsapp_username,
	'whatsapp_server_service' => $whatsapp_service,
	'whatsapp_sender' => $whatsapp_sender,
	'whatsapp_server' => $whatsapp_server,
	'whatsapp_action' => array(
		'send-message' => 'devices?action=send-message&auth='.$whatsapp_auth,
		'restart' => 'devices?action=restart&auth='.$whatsapp_auth.'&token='.$whatsapp_token.'&key='.$whatsapp_key,
		'check-status' => 'devices?action=check-status&auth='.$whatsapp_auth.'&token='.$whatsapp_token.'&key='.$whatsapp_key,
		'request-qrcode' => 'devices?action=request-qrcode&auth='.$whatsapp_auth.'&token='.$whatsapp_token.'&key='.$whatsapp_key,
		'restart' => 'devices?action=restart&auth='.$whatsapp_auth.'&token='.$whatsapp_token.'&key='.$whatsapp_key		
	),
	'whatsapp_action_v1' => array(
		'send-message' => 'api/v1/send',
		'create-instance' => 'api/v1/request', //?id=sQ0yYbcpbOtc952dW72zn5E6zxGFOTaZ&name=628989900148&isLegacy=false
		'delete-instance' => 'api/v1/delete',
		'check-instance' => 'api/v1/status',
	),
	'whatsapp_watermark' => $whatsapp_watermark
);