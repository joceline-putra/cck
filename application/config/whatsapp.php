<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $vendor = "ruangwa.id";	
$vendor = "umbrella.co.id";	
// $vendor = "fonnte.com";	

$whatsapp_server = '';
$whatsapp_token  = '';
$whatsapp_key  = '';
$whatsapp_sender  = '';
$whatsapp_username  = '';
$whatsapp_service = array();

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
		$whatsapp_server = 'https://wa.umbrella.co.id/';
		$whatsapp_token    = '21'; // Deprecated
		$whatsapp_key      = '21'; // Deprecated
		$whatsapp_auth     = '7Ho5mMjZMKELeLiqaZd5MK3NIjw6TM';
		$whatsapp_sender   = '628989900149';
		break;
	case "fonnte.com":
		$whatsapp_server = 'https://api.fonnte.com/';
		$whatsapp_token = 'send';		
		$whatsapp_auth   = 'wEw4c@Mcrt@Emi@XtFEs';
		// $whatsapp_sender   = '628989900149';
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
	)
);