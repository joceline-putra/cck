<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_auth' => true,
    'smtp_host' => 'umbrella.co.id', 
    'smtp_port' => 465,
    'smtp_user' => 'notif@umbrella.co.id',
    'smtp_pass' => 'masterjoe00',
    'mail_set_from' => 'notif@umbrella.co.id',
    'mail_set_reply_to' => 'notif@umbrella.co.id',    
    'mail_set_from_alias' => 'Notif Aplikasi',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'text', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);

// $config = array(
//     'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
//     // 'smtp_host' => 'srv42.niagahoster.com', 
//     'smtp_host' => 'mail.murba.co.id',
//     'smtp_port' => 465,
//     'smtp_user' => 'moonpact@murba.co.id',
//     'smtp_pass' => 'masterjoe00',
//     'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
//     'mailtype' => 'text', //plaintext 'text' mails or 'html'
//     'smtp_timeout' => '4', //in seconds
//     'charset' => 'iso-8859-1',
//     'wordwrap' => TRUE
// );