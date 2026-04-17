<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CaptchaRequest extends FRONT_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct() {
		parent::__construct();
		$this->load->library("captcha");
		$this->load->library("form_validation");
	}

	public function get(){	
		if(defined("_IS_AJAX")) {
			if($this->input->post("page", true)) {
				$captcha = $this->captcha->get_captcha($this->input->post("page", true));
				echo json_encode(array("code"=> true, "captcha" => array("image" => $captcha["image"])));
			} else {
				echo json_encode(array("code"=> true, "error" => print_language("an_error_has_occurred") ."\n\n". print_language("please_try_again")));
			}
		} else {
			msg(print_language("inaccessible_pages"), -1);
		}
	}
}
