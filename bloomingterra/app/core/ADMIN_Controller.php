<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ADMIN_Controller extends MY_Controller {
	public $_admin_member; // 관리자 정보
	public $_skin; // 스킨
	public $_site_language;
	public $_adm_menu; // 관리자 메뉴
	public $_adm_auth; // 관리자 권한
	public $_cfg_site; // 사이트 설정
	public $_cfg_siteLanguage;

	public function __construct() {
		parent::__construct();

		// 접속페이지 확인체크
		define("_CONNECT_PAGE", "ADMIN");

		// 관리자 로그인 체크
		$this->_admin_member = $this->session->__get("admin_member");
		if(isset($this->_admin_member)) {
			define("_IS_ADMIN_LOGIN", true);
		}

		if(!defined("_IS_ADMIN_LOGIN")) {
			if($this->uri->rsegments[1] != "index_") {
				redirect("/admin?return_url=". urlencode(current_full_url()));
			}
		}

		// 언어
		$this->config->load("cfg_siteLanguage");
		$this->_site_language = $this->config->item("site_language");
		$this->_cfg_siteLanguage = $this->config->item("site_language");

		// 스킨 설정
		$this->config->load("cfg_skin");
		$cfg_skin = $this->config->item("cfg_skin");
		$this->_skin = $cfg_skin["kor"]["skin"];

		// 사이트 설정
		$this->_cfg_site = $this->config->item("cfg_site");


		//2020-04-07 Inbet Matthew 다국어 설정에 따라 기본언어가 바뀌도록 기능 추가
		if($this->_cfg_siteLanguage['multilingual'] === 1) { //다국어 설정을 했을 시 기본 로직
        // 기존 로직에 벗어나지 않도록 기본언어 설정
			if(isset($this->_cfg_site['standard_mall'])) {
				$this->_site_language['default'] = $this->_cfg_site['standard_mall'];
			} else {
				reset($this->_site_language['set_language']);
				$this->_site_language['default'] = key($this->_site_language['set_language']);
			}
		}else{ //다국어 설정을 하지 않았을 시 무조건 국문
			$this->_site_language['default'] = 'kor';
		}
		//Matthew end

		// 관리자 메뉴
		$this->config->load("cfg_adm_menu");
		$this->_adm_menu = $this->config->item("adm_menu");

		// 관리자 권한
		$this->config->load("cfg_adm_auth");
		$this->_adm_auth = $this->config->item("auth");

		$this->config->set_item("language", "kor");

		$this->set_header();
	}

	public function set_view($body, $data = null) {
		$this->load->view("admin/_header");
		if($this->uri->rsegments[1] != "index_") {
			$this->load->view("admin/_nav");
			$this->load->view("admin/_left");
		}
		$this->load->view($body, $data);
		$this->load->view("admin/_footer");
	}



	public function set_header() {
		$this->output->set_header('Content-Type: text/html; charset=UTF-8');
		$this->output->set_header('HTTP/1.0 200 OK');
		$this->output->set_header('HTTP/1.1 200 OK');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: -1');
		$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		$this->output->set_header('X-UA-Compatible: IE=edge');
		$this->output->set_header('viewport: width=device-width, initial-scale=1');
		$this->output->set_header('robots: noindex,nofollow');
		$this->output->set_header('googlebot: noindex,nofollow');
		$this->output->set_header('viewport: noindex,nofollow');
	}
}