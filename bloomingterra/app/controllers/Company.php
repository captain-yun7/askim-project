<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends FRONT_Controller {
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
/*
	public $menus = [];
	public function _construct() {
		$this->load->model("Menu_model");
		$get_menus = $this->Menu_model->get_menus($this->_site_language, null);
	}
*/
	public function about() {
		$this->template_print($this->template_path());
	}

	public function history() {
		$this->template_print($this->template_path());
	}

	public function location() {
		$this->template_print($this->template_path());
	}

	public function work() {
		$this->template_print($this->template_path());
	}
	public function introduce() {
		$this->template_print($this->template_path());
	}

	public function technology() {
		$this->template_print($this->template_path());
	}
	public function business() {
		$this->template_print($this->template_path());
	}
	public function partners() {
		$this->template_print($this->template_path());
	}
	public function ceo() {
		$this->template_print($this->template_path());
	}
}