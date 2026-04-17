<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends ADMIN_Controller {
	public $_board;
	public $_main_notice_auth;
	public function __construct() {
		parent::__construct();

		try{
			$this->_main_notice_auth["member"] = $this->_admin_member["super"] ? $this->_admin_member["super"] : in_array("member_list", $this->_adm_auth[$this->_admin_member["level"]]["member"]);
			$this->_main_notice_auth["board"] = $this->_admin_member["super"] ? $this->_admin_member["super"] : in_array("board_list", $this->_adm_auth[$this->_admin_member["level"]]["board"]);
			$this->_main_notice_auth["goods"] = $this->_admin_member["super"] ? $this->_admin_member["super"] : in_array("goods_list", $this->_adm_auth[$this->_admin_member["level"]]["goods"]);
			$this->_main_notice_auth["popup"] = $this->_admin_member["super"] ? $this->_admin_member["super"] : in_array("popup_list", $this->_adm_auth[$this->_admin_member["level"]]["popup"]);

			if($this->_main_notice_auth['member']) {
				$this->load->model("Admin_Member_model");
			}
			if($this->_main_notice_auth['board']) {
				$this->load->model("Admin_Board_model");
				//상품 등록 모델 필요
				$code = "notice";
				$this->Admin_Board_model->initialize($code);
				$this->_board = $this->Admin_Board_model->get_board();
			}
			if($this->_main_notice_auth['goods']) {
				$this->load->model("Admin_Goods_model");
			}
			if($this->_main_notice_auth['popup']) {
				$this->load->model("Admin_Popup_model");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}

	}

	public function index() {
		try{
			$this->set_view("admin/main/index", array("main_notice_auth" => $this->_main_notice_auth));
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function get_list() {
		try{
			$pageName = $this->input->post("page_name", true);
			$per_page = $this->input->post("per_page", true);

			$limit = 5;
			$offset = ($per_page - 1) * $limit;
			$day = 7; //arr_where에 넣으면 'date_add(now(),interval -7 day'이렇게 되어서 에러가 나서 각 모델 function에 day를 넘겨서 처리

			if($pageName == "member") {
				$arr_where = array();
				$arr_where[] = array("yn_status", "y");
				$returnData = $this->Admin_Member_model->get_list_member($arr_where, null, $limit, $offset, null, $day);
			} else if($pageName == "popup") {
				$returnData = $this->Admin_Popup_model->get_list_popup(null, null, $limit, $offset);
			} else if($pageName == "board") {
				$returnData = $this->Admin_Board_model->get_list_board_all($limit, $offset, $day);
			} else if($pageName == "goods") {
				$returnData = $this->Admin_Goods_model->get_list_goods(null, null, null, $limit, $offset, null, $day);
			}

			$returnData["max_page"] = $returnData["total_rows"]%5 == 0 ? $returnData["total_rows"]/5 : $returnData["total_rows"]/5 + 1;

			array_walk_recursive($returnData, function(&$val, &$key) {
				if(is_null($val)){
					$val = "";
				}
			});

			echo json_encode($returnData);

		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
}
