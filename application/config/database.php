<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'cecekost.com',
	'username' => 'cecekost_root',
	'database' => 'cecekost_app_demo',
	'password' => '@Masterjoe00',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	// 'char_set' => 'utf8',
	// 'dbcollat' => 'utf8_general_ci',
	'char_set' => 'utf8mb4',
	'dbcollat' => 'utf8mb4_unicode_ci',	
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
