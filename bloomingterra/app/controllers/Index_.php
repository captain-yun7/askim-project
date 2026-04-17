<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_ extends FRONT_Controller {

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
	public function index() {
        
       /**
        *   휴면회원 전환용 테스트 코드 (데이터베이스에서 최종 로그인 1년 전으로 변경 후 해당 로직 실행)
        *   2020-06-09
        *   $this->load->model("Dormant_model"); 
        *   $this->Dormant_model->run();
        */
        
		try {
			$this->load->model("Front_Popup_model");
			$this->load->helper("cookie");

			$arr_where = array();
			$arr_where[] = array("language", $this->_site_language);
			$popup_list = $this->Front_Popup_model->get_list_popup($arr_where);

			$this->config->load("cfg_mainImageSlide");
			$cfg_mainImageSlide = $this->config->item("cfg_mainImageSlide");

			if($cfg_mainImageSlide["pc"][$this->_site_language]["form"] == "responsive"){
				$this->template_->assign("responsiveFl", true);
			}else {
				$this->template_->assign("responsiveFl", 0);
			}

			$this->template_->assign("main_image_slide", $this->config->item("cfg_mainImageSlide"));
			$this->template_->assign("lang", $this->_site_language);

			//2020-03-24 Inbet Matthew 개행문자로 인해 자바스크립트 변수에 안담아지는 오류때문에 개행문자 없앰
			foreach($popup_list['popup_list'] as $pkey => $pval){
				$popup_list['popup_list'][$pkey]['content'] = preg_replace('/\r\n|\r|\n/','',$pval['content']);
			}
			//Matthew end

			$this->template_->assign("popup_list", $popup_list);

			$this->load->model("Front_Board_model");
			/* 메인 게시글 폼 설정 */
            $inquiry = $this->Front_Board_model->get_board_manege(array(array('code',  'inquiry'),array('adminview', 'y')));
            if($inquiry['total_rows_manage']) {
                $this->Front_Board_model->initialize('inquiry');//게시판 변경시 게시판 code 값 변경후 사용.
                $board_info = $this->Front_Board_model->get_board();
                $board_info['mode'] = 'write';
                $this->template_->assign("board_info", $board_info);
            }
			/* 메인 게시글 폼 설정 */

			$arr_where =array();
			$arr_where[] = array("mainview", "y");

			$board_manage = $this->Front_Board_model->get_board_manege($arr_where);
			$this->template_->assign("board_manage", $board_manage['board_manage_list']);
			if(isset($board_manage["board_manage_list"])) {
				foreach($board_manage["board_manage_list"] as $key => $value) {
					/*if($value['adminview'] == 'n'){
						continue;
					}*/

					$board_manage['board_manage_list'][$key]['file_exists'] = file_exists($_SERVER["DOCUMENT_ROOT"]."/data/skin/".$this->_skin. "/layout/main_". $value["code"] .".html");
					$board_model = $value["code"]."_Board";
					$this->load->model("Front_Board_model", $board_model);
					$this->$board_model->initialize($value["code"], true);
					$board_info = $this->$board_model->get_board();
					$board_lists = $this->$board_model->get_list_board(null, null, $board_info["mainview_count"]);

					// 추가 필드
					$extra = [];
					foreach($board_lists['board_list'] as $k => $val) {
						$ext_json = (array)json_decode($val['extraFieldInfo']);
						if(count((array)$ext_json[$this->_site_language]) > 0) {
							foreach($ext_json[$this->_site_language] as $cols => $values) {
								if(strpos($values, "^|^") > -1) {
									$tmp = explode("^|^", $values);
									$extra[$val['no']][$cols] = $tmp[0];
								} else {
									$extra[$val['no']][$cols] = $values;
								}
							}
						}
					}
					$this->template_->assign($value["code"]. "_extra", $extra);

					$this->template_->assign($value["code"] ."_list", $board_lists);
					$this->template_->assign($value["code"] ."_info", $board_info);
					$this->template_->assign($value["code"] ."_display", file_exists($_SERVER["DOCUMENT_ROOT"]."/data/skin/".$this->_skin. "/layout/main_". $value["code"] .".html"));
					$this->template_->define($value["code"] ."_display", $this->_skin. "/layout/main_". $value["code"] .".html");
				}
			}

			$instagram_json = APPPATH.'/config/json_instagram.json';
			$this->load->library('qfile');
			$this->qfile->open($instagram_json);
			$instagram = json_decode($this->qfile->read($instagram_json));
			if($instagram->yn_use == 'y') {
				$this->load->library('MY_Instagram');
				$my_feed = $this->my_instagram->get_my_feed();
				$this->template_->assign('instagram', $instagram);
				$this->template_->define('instagram', $this->_skin.'/layout/main_instagram.html');
			}

			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage());
		}
	}
}
