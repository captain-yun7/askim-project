<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->config->load("cfg_debug");
		$cfg_debug = $this->config->item("cfg_debug");

		$this->load->helper("date");
		define("CURRENT_DATE", mdate("%Y-%m-%d %h:%i:%s", time()));

		if($this->input->ip_address() == "218.38.171.86") {
			define("_DEVELOP", true);
			error_reporting(E_ERROR | E_WARNING);
		} else {
			error_reporting(0);
		}

		if($cfg_debug["debug_mode"] === 1) {
			define("_DEBUG", true);
		}

		if($this->input->is_ajax_request()) {
			define('_IS_AJAX', true);
		}

		define('_DIR', $_SERVER['DOCUMENT_ROOT']);
		define('_UPLOAD', "/upload");

		//$this->scheduleproc->run();
	}
}
include_once(APPPATH. '/core/ADMIN_Controller.php');
include_once(APPPATH. '/core/FRONT_Controller.php');