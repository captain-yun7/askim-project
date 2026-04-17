<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheduleproc {
	private $CI;
	private $_table = "da_schedule";

	public function __construct() {
		$this->CI =& get_instance();

		$this->CI->db->where(array("yn_status" => "y", "run_dt !=" => date("Y-m-d")));
		$this->_schedule = $this->CI->db->get($this->_table)->result_array();
	}

	private function proc() {
		$arr_model = array();
		$arr_name = array();
		foreach($this->_schedule as $key => $value) {
			$arr_model[] = $value["class_name"];
			$arr_name[] = "schedule". $key;

			$this->CI->load->model($arr_model[$key] ."_model", $arr_name[$key]);
			$result = $this->CI->$arr_name[$key]->run();
			
			if($result) {
				$this->CI->db->update($this->_table, array("run_dt" => date("Y-m-d")), array("no" => $value["no"]));
			}
		}
	}

	public function run() {
		if(count($this->_schedule)) {
			$this->proc();
		} 
	}
}