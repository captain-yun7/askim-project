<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends ADMIN_Controller {
	private $_cfg_terms;
	public function __construct() {
		parent::__construct();

		$this->config->load("cfg_terms");
		$this->_cfg_terms = $this->config->item("cfg_terms");
	}

	public function terms_list() {
		try{
			$get_data = array();
			foreach($this->_cfg_terms as $key => $value) {
				$get_data["terms"][] = parse_ini_file(APPPATH ."/config/". $value, true);
			}
			$this->set_view("admin/terms/terms_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function terms_reg() {
		try{
			$this->load->library("form_validation");

			$code = $this->input->get_post("code", true);
			$mode = $this->input->post("mode", true);

			if(!isset($this->_cfg_terms[$code])) {
				throw new Exception("잘못된 접근입니다.");
			}

			if(isset($mode)) {
				$this->form_validation->set_rules("code", "코드", "required|trim|xss_clean");
				$this->form_validation->set_rules("title", "레벨", "required|trim|xss_clean");
				$this->form_validation->set_rules("text", "레벨", "required|trim|xss_clean");

				if($this->form_validation->run()) {
					$set_data = "[". $code ."]\n";
					$set_data .= "title = \"". htmlspecialchars($this->input->post("title", true)) ."\"\n";
					$set_data .= "text = \"". htmlspecialchars($this->input->post("text", true)) ."\"\n";

					$this->load->library("qfile");
					$this->qfile->open(APPPATH ."/config/". $this->_cfg_terms[$code]);
					$this->qfile->write($set_data);
					$this->qfile->close();

					msg("저장되었습니다.", "/admin/terms/terms_reg?code=". $code);
				} else {
					if(validation_error()) {
						throw new Exception(validation_error());
					}
				}
				throw new Exception("약관정보가 없습니다.");
			} else {
				$get_data = array();
				$get_data["terms"] = parse_ini_file(APPPATH ."/config/". $this->_cfg_terms[$code], true);
				$get_data["mode"] = "register";
				$this->set_view("admin/terms/terms_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
}
