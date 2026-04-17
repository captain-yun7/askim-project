<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['email'] = array(
	//'protocol' => 'sendmail',
	'protocol' => 'mail',
	'mailpath' => ini_get('sendmail_path'),
	'charset' => 'utf-8',
	'mailtype' => 'html',
	'wordwrap' => true,
);