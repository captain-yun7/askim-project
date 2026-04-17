<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup extends ADMIN_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("Admin_Popup_model");
	}
	public function popup_list() {
		try{
			$this->load->library("form_validation");
			$this->load->library("pagination");
			$this->load->config("cfg_memberField");

			$language = $this->input->get("language", true);
			$search_type = $this->input->get("search_type", true);
			$search = $this->input->get("search", true);
			$per_page = $this->input->get("per_page", true);

			$arr_where = array();
			$arr_like = array();

			if($language) {
				$arr_where[] = array("language", $language);
			}

			if(isset($search) && $search) {
				$arr_like[] = array($search_type, $search);
			}

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 10;
			$offset = ($per_page - 1) * $limit;

			$get_data = $this->Admin_Popup_model->get_list_popup($arr_where, $arr_like, $limit, $offset);

			$get_data["offset"] = $offset;

			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?language=". $language ."&search_type=". $search_type ."&search=". $search,
				"suffix" => "&language=". $language ."&search_type=". $search_type ."&search=". $search,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();
			$this->set_view("admin/popup/popup_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function popup_reg() {

		try{
			$this->load->library("form_validation");
			$no = $this->input->get("no", true);
			$mode = $this->input->post("mode", true);
			if(isset($mode)) {

				$sdate = $this->input->post("sdate", true);
				$edate = $this->input->post("edate", true);
				$popupform = $this->input->post("popupform");

				$this->form_validation->set_rules("language", "언어", "required|trim|xss_clean");

                // 제목을 사용하고 싶지 않은 경우 존재한다면 해당 벨리데이션 없앨 것.... 2020-06-11
                //if(empty(trim($this->input->post("title", true))) === false) {
                    $this->form_validation->set_rules("title", "제목", "required|trim|xss_clean");
                //}
                $this->form_validation->set_rules("type", "형태", "required|trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("content", "내용", "required|trim");
				$this->form_validation->set_rules("open", "공개여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("sdate", "시작일", "required|trim|xss_clean|datetime[". $sdate ."]");
				$this->form_validation->set_rules("edate", "종료일", "required|trim|xss_clean|datetime[". $edate ."]");
				$this->form_validation->set_rules("popupform", "팝업 ", "required|trim|xss_clean");

				if($popupform == "fixed") {
					$this->form_validation->set_rules("toppx_pc", "pc 상단간격", "required|trim|xss_clean|is_natural");
					$this->form_validation->set_rules("toppx_mobile", "모바일 상단간격", "required|trim|xss_clean|is_natural");

					$this->form_validation->set_rules("leftpx_pc", "pc 좌측간격", "required|trim|xss_clean|is_natural");
					//$this->form_validation->set_rules("leftpx_mobile", "모바일 좌측간격", "required|trim|xss_clean|is_natural");

					$this->form_validation->set_rules("width_pc", "pc 너비", "required|trim|xss_clean|is_natural_no_zero");
					$this->form_validation->set_rules("width_mobile", "모바일 너비", "required|trim|xss_clean|is_natural_no_zero");

					$this->form_validation->set_rules("height_pc", "pc 높이", "trim|xss_clean|is_natural");
					//$this->form_validation->set_rules("height_mobile", "모바일 높이", "trim|xss_clean|is_natural");
				}else if($popupform == "responsive") {
					$this->form_validation->set_rules("recognition_pc", "반응형 pc 인식넓이", "required|trim|xss_clean|is_natural");
					$this->form_validation->set_rules("recognition_tablet", "반응형 테블릿 인식넓이", "required|trim|xss_clean|is_natural");

					$this->form_validation->set_rules("toppx_responsive_pc", "반응형 pc 상단간격", "required|trim|xss_clean|is_natural");
					$this->form_validation->set_rules("toppx_responsive_tablet", "반응형 테블릿 상단간격", "required|trim|xss_clean|is_natural");
					$this->form_validation->set_rules("toppx_responsive_mobile", "반응형 모바일 상단간격", "required|trim|xss_clean|is_natural");

					$this->form_validation->set_rules("leftpx_responsive_pc", "반응형 pc 좌측간격", "required|trim|xss_clean|is_natural");
					$this->form_validation->set_rules("leftpx_responsive_tablet", "반응형 테블릿 좌측간격", "required|trim|xss_clean|is_natural");
					//$this->form_validation->set_rules("leftpx_responsive_mobile", "반응형 모바일 좌측간격", "required|trim|xss_clean|is_natural");

					$this->form_validation->set_rules("width_responsive_pc", "반응형 pc 너비", "required|trim|xss_clean|is_natural_no_zero");
					$this->form_validation->set_rules("width_responsive_tablet", "반응형 테블릿 너비", "required|trim|xss_clean|is_natural_no_zero");
					$this->form_validation->set_rules("width_responsive_mobile", "반응형 모바일 너비", "required|trim|xss_clean|is_natural_no_zero");

					$this->form_validation->set_rules("height_responsive_pc", "반응형 pc 높이", "trim|xss_clean|is_natural");
					$this->form_validation->set_rules("height_responsive_tablet", "반응형 테블릿 높이", "trim|xss_clean|is_natural");
					//$this->form_validation->set_rules("height_responsive_mobile", "반응형 모바일 높이", "trim|xss_clean|is_natural");
				}

				if($this->form_validation->run()) {
					$set_data = $this->input->post();

					if($mode == "register"){
						$set_data["regdt"] = date('Y-m-d H:i:s');
						$set_data["regname"] = $this->_admin_member["name"];
						$get_data = table_data_match("da_popup", $set_data);
						$this->db->set("sort", "(SELECT COUNT(A.no) + 1 FROM da_popup A WHERE language = '". $set_data['language'] ."')", FALSE);

						$result = $this->db->insert("da_popup", $get_data);
						if($result) {
							redirect("/admin/popup/popup_reg?no=". $this->db->insert_id());
						}
					} else if($mode == "modify") {
						$set_data["updatedt"] = date('Y-m-d H:i:s');
						$set_data["updatename"] = $this->_admin_member["name"];
						$get_data = table_data_match("da_popup", $set_data);
						$result = $this->db->update("da_popup", $get_data, array("no" => $set_data["no"]));
						if($result) {
							msg("수정되었습니다.", "/admin/popup/popup_reg?no=". $set_data["no"]."&".$this->input->post("ref", true));
						}
					}
				} else {
					if(validation_error()) {
						throw new Exception(validation_error());
					}
				}
				throw new Exception("팝업 정보가 없습니다.");
			} else {
				$get_data = array();
				if(isset($no)) {
					$arr_where[] = array("no", $no);
					$get_data = $this->Admin_Popup_model->get_view_popup($arr_where);
					$get_data["mode"] = "modify";
				} else {
					$get_data["mode"] = "register";
				}
				$get_data['ref'] = http_build_query($this->input->get(null, true));
				$this->set_view("admin/popup/popup_reg", $get_data);
			}


		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function popup_display() {
		try{
			if(!defined("_IS_AJAX")) {
				throw new Exception("접근 할 수 없는 페이지입니다.");
			}

			$this->load->library("form_validation");

			$this->form_validation->set_rules("no", "번호", "required|trim|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("open", "순서", "required|trim|xss_clean");

			if($this->form_validation->run()) {
				$set_data = $this->input->post(null, true);
				$get_data = table_data_match("da_popup", $set_data);
				$result = $this->db->update("da_popup", $get_data, array("no" => $set_data["no"]));
				if($result) {
					echo json_encode(array("code" => true));
					exit;
				}
			} else {
				if(validation_error()) {
					echo json_encode(array("code" => false, "error" => validation_error()));
					exit;
				}
			}
			echo json_encode(array("code" => false, "error" => "팝업 정보가 없습니다."));
			exit;
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function popup_sort() {
		try{
			if(!defined("_IS_AJAX")) {
				throw new Exception("접근 할 수 없는 페이지입니다.");
			}

			$this->load->library("form_validation");

			$this->form_validation->set_rules("no", "번호", "required|trim|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("sort", "순서", "required|trim|xss_clean|is_natural_no_zero");

			if($this->form_validation->run()) {
				$set_data = $this->input->post(null, true);
				$result = $this->Admin_Popup_model->set_popup_sort($set_data);
				if($result) {
					echo json_encode(array("code" => true));
					exit;
				}
			} else {
				if(validation_error()) {
					echo json_encode(array("code" => false, "error" => validation_error()));
					exit;
				}
			}
			echo json_encode(array("code" => false, "error" => "팝업 정보가 없습니다."));
			exit;
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function popup_delete() {
		try {
			if(!$this->input->post("no", true)) {
				throw new Exception("팝업 정보를 찾을 수 없습니다.");
			}
            
            // 오류수정 2020-06-11
			$no = $this->input->post("no", true);

			$arr_where = array();
			$arr_where[] = array("no", $no, "IN");

			$result = $this->Admin_Popup_model->popup_delete($arr_where);

			if($result) {
				if($this->input->post("return_url", true)) {
					msg("삭제되었습니다.", $this->input->post("return_url", true));
				} else {
					msg("삭제되었습니다.", "/admin/popup/popup_list?".$this->input->post("ref", true));
				}
			}else{
				throw new Exception("삭제를 실패하였습니다.\n\n잠시후 다시 시도해주세요.");
			}

		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

}
