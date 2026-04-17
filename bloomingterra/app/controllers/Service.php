<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends FRONT_Controller {
        protected $cfg_site;

        public function __construct() {
                parent::__construct();
                $this->load->model("Terms_model");
                $this->cfg_site = $this->config->item("cfg_site");
        }

        public function agreement() {
                $searchData = array();

                $searchData = array(
                        "language" => $this->_site_language,
                        "code" => "agreement"
                );

                $terms["agreement"] = $this->Terms_model->getTermsData($searchData);

                foreach($this->cfg_site[$this->_site_language] as $key => $value) {
                        $terms["agreement"] = str_replace("{\$".$key."}", $value        , $terms["agreement"]);
                }

                $searchData = array(
                        "language" => $this->_site_language,
                        "code" => "usePolicy"
                );

                $terms["usePolicy"] = $this->Terms_model->getTermsData($searchData);

                foreach($this->cfg_site[$this->_site_language] as $key => $value) {
                        $terms["usePolicy"] = str_replace("{\$".$key."}", $value        , $terms["usePolicy"]);
                }

                $this->template_->assign("terms", $terms);
				$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
                $this->template_print($this->template_path());
        }

        public function usepolicy() {
                $searchData = array();

                $searchData = array(
                        "language" => $this->_site_language,
                        "code" => "usePolicy"
                );

                $terms["usePolicy"] = $this->Terms_model->getTermsData($searchData);

                foreach($this->cfg_site[$this->_site_language] as $key => $value) {
                        $terms["usePolicy"] = str_replace("{\$".$key."}", $value        , $terms["usePolicy"]);
                }

                $searchData = array(
                        "language" => $this->_site_language,
                        "code" => "agreement"
                );

                $terms["agreement"] = $this->Terms_model->getTermsData($searchData);

                foreach($this->cfg_site[$this->_site_language] as $key => $value) {
                        $terms["agreement"] = str_replace("{\$".$key."}", $value        , $terms["agreement"]);
                }
                
                //-- 비회원 수집항목 동의 추가 2020-06-12 
                $searchData = array(
                        "language" => $this->_site_language,
                        "code" => "nonMember"
                );

                $terms["nonMember"] = $this->Terms_model->getTermsData($searchData);
                
                foreach($this->cfg_site[$this->_site_language] as $key => $value) {
                        $terms["nonMember"] = str_replace("{\$".$key."}", $value        , $terms["nonMember"]);
                }
                //__ 비회원 수집항목 동의 추가 2020-06-12

                $this->template_->assign("terms", $terms);
				$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
                $this->template_print($this->template_path());
        }
       
	public function index2() {
		$this->template_print($this->template_path());
	}
}