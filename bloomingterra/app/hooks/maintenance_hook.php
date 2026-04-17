<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance_hook {
	public function __construct() {
		log_message("debug", "Accessing maintenance hook!");
		$CI =& get_instance();
	}

	public function under_construction() {
		if(file_exists(APPPATH."config/config.php")) {
			include(APPPATH."config/config.php");
			$CI->config->load("cfg_siteLanguage");
			$CI->_cfg_siteLanguage = $this->config->item("site_language");

			if($config['under_construction'] === true) {
				include(APPPATH."views/under_construction.html");
				if($_SERVER['REMOTE_ADDR'] != "210.121.177.33") exit;
			}
		}
	}
}