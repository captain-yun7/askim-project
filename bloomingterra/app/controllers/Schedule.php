<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library("scheduleproc");
	}

	public function run() {
		try {
			$this->scheduleproc->run();
		} catch(Exception $e) {
			weblog($e->getMessage(), "schedule");
		}
	}
}