<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Auth extends ADMIN_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it"s displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    //삭제 불가 기본 게시판
    private $_basic_board = array("inquiry", "gallery", "notice", "review");

	public function __construct() {
		parent::__construct();
		$this->load->library("form_validation");
	}

    // 언어설정
	public function language_reg() {
		try{
			$mode = $this->input->post("mode", true);
			$this->load->library("form_validation");

			if(isset($mode)) {
				$language = $this->input->post("language", true);

				if(!isset($language)) {
					throw new Exception("언어 정보가 없습니다.");
				}

                // 기본언어 세팅
                if(!$language["multilingual"]) {
                    $language["set_language"]['kor'] = '한국';
                } else {
                    $language["set_language"] = array_flip($language["set_language"]);
                    foreach($language["set_language"] as $key => $val) {
                        $language["set_language"][$key] = $this->_site_language["support_language"][$key];
                    }
                }

				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'site_language' => array(\n";
				$set_data .= "\t\t'multilingual' => ". intval($language["multilingual"]) .",\n";
				$set_data .= "\t\t'set_language' => array(\n";
                foreach($language["set_language"] as $lang => $text) {
					$set_data .= "\t\t\t'$lang'	=> '$text',\n";
                }
				$set_data .= "\t\t),\n";
				$set_data .= "\t\t'support_language' => array(\n";
				foreach ($this->_site_language["support_language"] as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_siteLanguage.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "language_reg");
			} else {
				$get_data = array();
				$get_data["mode"] = "register";
				$this->set_view("admin/auth/language_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 회원필드셋팅
	public function member_field() {
		try{
			$mode = $this->input->post("mode", true);
			if(isset($mode)) {
				$nameField = $this->input->post("nameField", true);
				$useField = $this->input->post("useField", true);
				$reqField = $this->input->post("reqField", true);
				$typeField = $this->input->post("typeField", true);
				$optionField = $this->input->post("optionField", true);

				if(!isset($nameField) || !isset($nameField) || !isset($reqField) || !isset($optionField)) {
					throw new Exception("회원필드 정보가 없습니다.");
				}
				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'memberField' => array(\n";
				$set_data .= "\t\t'name' => array(\n";
				foreach ($nameField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'use' => array(\n";
				foreach ($useField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'require' => array(\n";
				foreach ($reqField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'type' => array(\n";
				foreach ($typeField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";
				$set_data .= "\t\t'option' => array(\n";
				foreach($optionField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> array(\n";
					foreach($value as $secondKey => $secondValue) {
						if(!is_array($secondValue)) {
							$set_data .= "\t\t\t\t'$secondKey'	=> '$secondValue',\n";
						}
					}
					$set_data .= "\t\t\t\t'item'	=> array(\n";
					foreach($value as $language_key => $language_value) {
						$set_data .= "\t\t\t\t\t'$language_key'	=> array(\n";
						if(isset($language_value["itemName"]) && isset($language_value["itemValue"])) {
							for($i = 0; $i < count($language_value["itemName"]); $i++) {
								$set_data .= "\t\t\t\t\t'". $language_value["itemName"][$i]. "'	=> '". $language_value["itemValue"][$i] ."',\n";
							}
						}
						$set_data .= "\t\t\t\t\t),\n";
					}
					$set_data .= "\t\t\t\t),\n";
					$set_data .= "\t\t\t),\n";
				}

				$set_data .= "\t\t),\n";
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_memberField.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "/admin/auth/member_field");
			} else {
				$this->config->load("cfg_memberField");
				$get_data = array();
				$get_data["memberField"] = $this->config->item("memberField");
				$get_data["arr_ex"] = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
							"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드

				$get_data["readonly"] = array("sex", "birth", "yn_mailling", "yn_sms"); //변경불가
				$get_data["radio"] = array(
					"sex" => array(
						"m" => "남자",
						"w" => "여자"
					),
					"yn_mailling" => array(
						"y" => "수신동의",
						"n" => "수신거부"
					),
					"yn_sms" => array(
						"y" => "수신동의",
						"n" => "수신거부"
					),
				);

				$get_data["fieldset"] = array(
					"default"	=> array( // 기본
						"userid",
						"name",
						"password",
					), "hidden" => array( // 숨김처리
						"level",
						"group",
						"password_moddt",
						"password_skip_cnt",
						"regdt",
						"last_login",
						"last_login_ip",
						"cnt_login",
						"withdrawal_reason",
						"withdrawal_dt",
						"yn_dormant_mail",
						"dormant_mail_dt",
					), "etc" => array( // 기타
						"sex",
						"birth",
						"email",
						"zip",
						"address",
						"address2",
						"mobile",
						"fax",
						"yn_mailling",
						"yn_sms",
						"ex1",
						"ex2",
						"ex3",
						"ex4",
						"ex5",
						"ex6",
						"ex7",
						"ex8",
						"ex9",
						"ex10",
						"ex11",
						"ex12",
						"ex13",
						"ex14",
						"ex15",
						"ex16",
						"ex17",
						"ex18",
						"ex19",
						"ex20",
					)
				);

				$get_data["mode"] = "register";
				$this->set_view("admin/auth/member_field", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 상품필드셋팅
	public function goods_field() {
		try{
			$mode = $this->input->post("mode", true);
			if(isset($mode)) {
				$nameField = $this->input->post("nameField", true);
				$useField = $this->input->post("useField", true);
				$reqField = $this->input->post("reqField", true);
				$optionField = $this->input->post("optionField", true);
				$multiField = [
					"name" => "checked",
					"info" => "checked",
				];

				if(!isset($nameField) || !isset($nameField) || !isset($reqField) || !isset($optionField)) {
					throw new Exception("상품필드 정보가 없습니다.");
				}
				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'goodsField' => array(\n";
				$set_data .= "\t\t'name' => array(\n";
				foreach ($nameField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'use' => array(\n";
				foreach ($useField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'require' => array(\n";
				foreach ($reqField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'multi' => array(\n";
				foreach ($multiField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'option' => array(\n";
				foreach($optionField as $key => $value) {
					if($value["type"]) {
						$set_data .= "\t\t\t'$key'	=> array(\n";
						foreach($value as $secondKey => $secondValue) {
							if(!is_array($secondValue)) {
								$set_data .= "\t\t\t\t'$secondKey'	=> '$secondValue',\n";
							}
						}
						$set_data .= "\t\t\t\t'item'	=> array(\n";
						foreach($value as $language_key => $language_value) {
							//is_array를 붙임 php 5.3 버전은 isset()으로 배열검사를 실행시 string 첫글자를 불러옴
							if(is_array($language_value) && isset($language_value["itemName"]) && isset($language_value["itemValue"])) {
								$set_data .= "\t\t\t\t\t'$language_key'	=> array(\n";
								for($i = 0; $i < count($language_value["itemName"]); $i++) {
									$set_data .= "\t\t\t\t\t\t'". $language_value["itemName"][$i]. "'	=> '". $language_value["itemValue"][$i] ."',\n";
								}
								$set_data .= "\t\t\t\t\t),\n";
							}
						}
						$set_data .= "\t\t\t\t),\n";
						$set_data .= "\t\t\t),\n";
					}
				}

				$set_data .= "\t\t),\n";
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_goodsField.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "/admin/auth/goods_field");
			} else {
				$this->config->load("cfg_goodsField");
				$this->config->load("cfg_uploadValidate");

				$get_data = array();
				$get_data["goodsField"] = $this->config->item("goodsField");
				$uploadValidate = $this->config->item("cfg_uploadValidate");
				$extension = array_keys($uploadValidate["extension"]);
				$get_data["extension"] = array_diff($extension, array("favicon", "snsImage", "sitemap"));

				$get_data["arr_ex"] = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
										"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드

				$get_data["readonly"] = array("img1", "img2", "info", "upload_fname", "detail_img"); //변경불가

				$get_data["fieldset"] = array(
					"default"	=> array( // 기본
						"no",
						"name",
						"category",

					), "hidden" => array( // 숨김처리
						"yn_state",
						"regdt",
						"moddt",
						'upload_path',
						'upload_oname',
					), "etc" => array( // 기타
						"img2",
						"img1",
						"detail_img",
						"info",
						'upload_fname',
						"ex1",
						"ex2",
						"ex3",
						"ex4",
						"ex5",
						"ex6",
						"ex7",
						"ex8",
						"ex9",
						"ex10",
						"ex11",
						"ex12",
						"ex13",
						"ex14",
						"ex15",
						"ex16",
						"ex17",
						"ex18",
						"ex19",
						"ex20",
					)
				);

				$get_data["mode"] = "register";
				$get_data["multiUseArr"] = [
					"name",
					"info"
				];
				$this->set_view("admin/auth/goods_field", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 게시판관리
	public function board_manage() {
		try{
			$this->load->library("pagination");

			$per_page = $this->input->get("per_page", true);

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 10;
			$offset = ($per_page - 1) * $limit;

			$this->load->model("Admin_Board_model");

			$arr_orderby = array("name asc");
			$get_data = $this->Admin_Board_model->get_board_manege(null, null, $limit, $offset, $arr_orderby);
			$get_data["offset"] = $offset;

			$config = array(
				"total_rows" => $get_data["total_rows_manage"],
				"per_page" => $limit,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();

			$this->set_view("admin/auth/board_manage", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_manage_reg() {
		try {
			$this->load->dbforge();
			$code = $this->input->get("code", true);
			$mode = $this->input->post("mode", true);

			if(isset($mode)) {
				if($mode == "register"){
					$this->form_validation->set_rules("code", "게시판코드", "required|trim|xss_clean|is_unique[da_board_manage.code]");
				} else if($mode == "modify") {
					$this->form_validation->set_rules("code", "게시판코드", "required|trim|xss_clean");
				}
				$this->form_validation->set_rules("name", "게시판명", "required|trim|xss_clean");
                foreach($this->_site_language["set_language"] as $key => $val) {
				    $this->form_validation->set_rules("name_$key", "노출명 $val", "required|trim|xss_clean");
                }

				$this->form_validation->set_rules("board_type", "게시판 유형", "required|trim|xss_clean");
				$this->form_validation->set_rules("read_auth", "글읽기 권한", "required|trim|xss_clean|is_natural");
				$this->form_validation->set_rules("write_auth", "글쓰기 권한", "required|trim|xss_clean|is_natural");
				$this->form_validation->set_rules("tree", "답글하기 여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("anwrite_auth", "답글하기 권한", "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("comment", "댓글달기 여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("comment_auth", "댓글달기 권한", "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("files", "첨부파일 여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("filesize", "첨부파일 용량제한", "trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("file_count", "첨부파일 갯수제한", "trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("roundpage", "페이지당 글 갯수", "required|trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("sort_type", "게시판 정렬", "required|trim|xss_clean");
				$this->form_validation->set_rules("skin_type", "게시판 스킨", "required|trim|xss_clean");
				$this->form_validation->set_rules("secret", "게시판 비밀글설정", "required|trim|xss_clean|is_natural");
				$this->form_validation->set_rules("yn_mobile", "휴대폰 작성", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_email", "이메일 작성", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_video", "동영상주소 작성", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_send_mail", "답변 메일발송", "trim|xss_clean");
				$this->form_validation->set_rules("mail_form", "메일폼", "trim|xss_clean");
				$this->form_validation->set_rules("yn_display_list", "리스트 노출", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_preface", "말머리 여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("preface", "말머리", "trim|xss_clean");
				$this->form_validation->set_rules("thumbnail", "썸네일 여부", "required|trim|xss_clean");
				$this->form_validation->set_rules("thumbnail_count", "썸네일 갯수제한", "trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("thumbnail_width", "썸네일 가로사이즈", "trim|xss_clean|is_natural_no_zero");
				$this->form_validation->set_rules("thumbnail_height", "썸네일 세로사이즈", "trim|xss_clean|is_natural_no_zero");

				if($this->form_validation->run()) {
					$set_data = $this->input->post(null, true);
                    // 말머리 재가공
                    if(ib_isset($set_data)) {
                        $arrPreface = explode(",",$set_data['preface']);
                        $filterPreface = array_filter(array_map('trim',$arrPreface));
                        if(count($filterPreface) > 5) {
							throw new Exception("말머리는 최대 5개 까지 등록 가능합니다.");
                        }
                        $set_data['preface'] = implode(",",$filterPreface);
                    }

                    // 다국어 저장
                    if(ib_isset($this->_site_language["set_language"])) {
                        $result = $this->db->delete("da_board_global", array("code" => $set_data["code"]));
                        if($result) {
                            $set_language_data["code"] = $set_data["code"];
                            foreach($this->_site_language["set_language"] as $key => $val) {
                                $set_language_data["language"] = $key;
                                $set_language_data["name"] = $set_data["name_".$key];
                                $get_language_data = table_data_match("da_board_global", $set_language_data);
                                $result = $this->db->insert("da_board_global", $get_language_data);
                            }
                        }
                    }

					if($mode == "register"){
						$this->config->load("cfg_dbTable");
						$table = $this->config->item("dbTable");
						$this->dbforge->add_field($table["da_board"]);
						$this->dbforge->add_key("no", true);
						$result = $this->dbforge->create_table("da_board_". $set_data["code"], true);

						if($result) {
							$set_data["regdt"] = date('Y-m-d H:i:s');
							$set_data["mainview"] = 'n';
							$get_data = table_data_match("da_board_manage", $set_data);
							$result = $this->db->insert("da_board_manage", $get_data);
							if($result) {
								redirect("/admin/auth/board_manage_reg?code=". $set_data["code"]);
							} else {
								$this->dbforge->drop_table("da_board_". $set_data["code"]);
								throw new Exception("오류가 발생하였습니다.\n\n잠시후 다시시도해주세요.");
							}
						}
					} else if($mode == "modify") {
						$set_data["updatedt"] = date('Y-m-d H:i:s');
						$get_data = table_data_match("da_board_manage", $set_data);
						$result = $this->db->update("da_board_manage", $get_data, array("code" => $set_data["code"]));
						if($result) {
							msg("수정되었습니다.", "/admin/auth/board_manage_reg?code=". $set_data["code"]);
						}
					}
				} else {
					if(validation_errors()) {
						throw new Exception(validation_errors());
					}
				}

				throw new Exception("게시판 정보가 없습니다.");
			} else {
				$get_data = array();
				if(isset($code)) {
					$this->load->model("Admin_Board_model");
					$this->Admin_Board_model->initialize($code);

					$get_data["board_manage"] = $this->Admin_Board_model->get_board();
					$get_data["mode"] = "modify";
				} else {
					$get_data["mode"] = "register";
				}
				$arr_where = array();
				//$arr_where[] = array("level", array_keys($this->_adm_auth), "NOTIN");
				$get_data["member_grade_read_list"] = get_list_member_grade($arr_where);
				$get_data["member_grade_write_list"] = get_list_member_grade();

				$arr_skin = @scandir(APPPATH. "../data/skin/".$this->_skin."/layout/board");
				foreach($arr_skin as $key => $val){
					if(strpos($val, "html") === false){
						unset($arr_skin[$key]);
					}
				}
				$get_data["skin_files"] = $arr_skin;
				$this->config->load("cfg_mailForm");
				$get_data["mail_form"] = $this->config->item("cfg_mailForm");

				$this->set_view("admin/auth/board_manage_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_manage_delete() {
		try{
			if(!$this->input->get("code", true)) {
				throw new Exception("게시판의 정보를 찾을 수 없습니다.");
			}

			$code = $this->input->get("code", true);

			/*매니저 레코드만 삭제
			$this->load->dbforge();
			$result = $this->dbforge->drop_table("da_board_". $code, true);*/

			$result = $this->db->delete("da_board_manage", array("code" => $code));
			if($result) {
				msg("게시판이 삭제되었습니다.", "/admin/auth/board_manage");
			} else {
				throw new Exception("게시판의 삭제를 실패하였습니다.\n\n잠시후 다시 시도해주세요.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_mainview() {
		try{
			if(!defined("_IS_AJAX")) {
				throw new Exception("접근 할 수 없는 페이지입니다.");
			}

			$this->form_validation->set_rules("code", "게시판코드", "required|trim|xss_clean");
			$this->form_validation->set_rules("mainview", "메인뷰", "required|trim|xss_clean");
			$this->form_validation->set_rules("mainview_count", "게시물 수", "required|trim|xss_clean|is_natural_no_zero");

			$code = $this->input->post("code", true);
			$mainview = $this->input->post("mainview", true);
			$mainview_count = $this->input->post("mainview_count", true);
			if($this->form_validation->run()) {
				$set_data = $this->input->post(null, true);
				$get_data = table_data_match("da_board_manage", $set_data);
				$result = $this->db->update("da_board_manage", $get_data, array("code" => $set_data["code"]));

				if($result) {
					echo json_encode(array("code" => true));
				} else {
					echo json_encode(array("code" => false, "msg" => $this->db->_error_message()));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
				throw new Exception("게시판 정보가 없습니다.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_adminview() {
		try{
			if(!defined("_IS_AJAX")) {
				throw new Exception("접근 할 수 없는 페이지입니다.");
			}

			$this->form_validation->set_rules("code", "게시판코드", "required|trim|xss_clean");
			$this->form_validation->set_rules("adminview", "메인뷰", "required|trim|xss_clean");

			if($this->form_validation->run()) {
				$set_data = $this->input->post(null, true);
				$get_data = table_data_match("da_board_manage", $set_data);
				$result = $this->db->update("da_board_manage", $get_data, array("code" => $set_data["code"]));

				if($result) {
					echo json_encode(array("code" => true));
				} else {
					echo json_encode(array("code" => false, "msg" => $this->db->_error_message()));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
				throw new Exception("게시판 정보가 없습니다.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_delete() {
		try{
			if(!defined("_IS_AJAX")) {
				throw new Exception("접근 할 수 없는 페이지입니다.");
			}

			if(!$this->input->post("code", true)) {
				throw new Exception("게시판의 정보를 찾을 수 없습니다.");
			}

            // 초기 게시판 제외 코드
            // @TODO Exception 정상적으로 반영되지 못함
            if(in_array($this->input->post("code", true), $this->_basic_board)) {
                throw new Exception("기본 게시판은 삭제할 수 없습니다.");
            }

			$this->form_validation->set_rules("code", "게시판코드", "required|trim|xss_clean");

			if($this->form_validation->run()) {

			    $code = $this->input->post("code", true);
                $this->load->dbforge();
                $result = $this->dbforge->drop_table("da_board_". $code, true);

				if($result) {
                    $result = $this->db->delete("da_board_manage", array("code" => $code));
                    if($result) {
                        // 첨부파일 삭제
                        $this->db->delete("da_board_file", array("code" => $code));
                        //댓글 삭제
                        $this->db->delete("da_board_comment", array("code" => $code));
					    echo json_encode(array("code" => true));
                    } else {
					    echo json_encode(array("code" => false, "msg" => $this->db->_error_message()));
                    }
				} else {
					echo json_encode(array("code" => false, "msg" => $this->db->_error_message()));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
				throw new Exception("게시판 정보가 없습니다.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	//메뉴관리
	public function menu_manage() {
		try{
			$mode = $this->input->post("mode", true);
			$this->load->library("form_validation");

			if(isset($mode)) {
				$front_menu = $this->input->post("front_menu", true);
				if(!isset($front_menu)) {
					throw new Exception("메뉴 정보가 없습니다.");
				}
				$set_front_menu = array();

				foreach($front_menu as $language_key => $language_value) {
					foreach($language_value as $value1) {
						foreach($value1 as $key2 => $value2) {
							if(is_array($value2)) {
								foreach($value2 as $value3) {
									foreach($value3 as $key4 => $value4) {
										if(is_array($value4)) {
											foreach($value4 as $value5) {
												foreach($value5 as $key6 => $value6) {
													if(isset($set_front_menu[$language_key][$value1["sort"]]["menu"][$value3["sort"]][$key4][$value5["sort"]][$key6])) {
														throw new Exception('같은 등급의 메뉴를 동일한 번호로 순서를 입력할 수 없습니다.');
													}
													$set_front_menu[$language_key][$value1["sort"]]["menu"][$value3["sort"]][$key4][$value5["sort"]][$key6] = $value6;
												}
											}
										} else {
											if(isset($set_front_menu[$language_key][$value1["sort"]]["menu"][$value3["sort"]][$key4])) {
												throw new Exception('같은 등급의 메뉴를 동일한 번호로 순서를 입력할 수 없습니다.');
											}
											$set_front_menu[$language_key][$value1["sort"]]["menu"][$value3["sort"]][$key4] = $value4;
										}
									}
								}
							} else {
								if(isset($set_front_menu[$language_key][$value1["sort"]][$key2])) {
									throw new Exception('같은 등급의 메뉴를 동일한 번호로 순서를 입력할 수 없습니다.');
								}
								$set_front_menu[$language_key][$value1["sort"]][$key2] = $value2;
							}
						}
					}
				}

				// 1단계
				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
				$result = ksort($set_front_menu);
				$set_data .= "\$config = array(\n";
				$set_data .= "\t'cfg_menu' => array(\n";

				foreach ($set_front_menu as $language_key => $language_value) { //처음
					$set_data .= "\t\t'$language_key' => array(\n"; // NAV
					foreach ($language_value as $key1 => $value1) { //처음
						$set_data .= "\t\t\t'$key1' => array(\n"; // NAV
						if($value1["sort"]) {
							unset($value1["sort"]);
						}
						if($value1["menu"]) {
							ksort($value1["menu"]);
						}
						foreach ($value1 as $key2 => $value2) {
							//2단계
							if(is_array($value2)) {
								$set_data .= "\t\t\t\t'$key2' => array(\n"; // NAV
									foreach ($value2 as $key3 => $value3) {
										if($value3["sort"]) {
											unset($value3["sort"]);
										}
										if($value3["menu"]) {
											ksort($value3["menu"]);
										}
										$set_data .= "\t\t\t\t\t'$key3' => array(\n";
										foreach ($value3 as $key4 => $value4) {
											//3단계
											if(is_array($value4)) {
												$set_data .= "\t\t\t\t\t\t'$key4' => array(\n"; // NAV
													foreach ($value4 as $key5 => $value5) {
														if($value5["sort"]) {
															unset($value5["sort"]);
														}
														if($value5["menu"]) {
															ksort($value5["menu"]);
														}
														$set_data .= "\t\t\t\t\t\t\t'$key5' => array(\n";
														foreach ($value5 as $key6 => $value6) {
															//4단계
															if(is_array($value6)) {
																$set_data .= "\t\t\t\t\t\t\t\t'$key6' => array(\n"; // NAV
																	foreach ($value6 as $key7 => $value7) {
																		if($value7["sort"]) {
																			unset($value7["sort"]);
																		}
																		if($value7["menu"]) {
																			ksort($value7["menu"]);
																		}
																		$set_data .= "\t\t\t\t\t\t\t\t\t'$key7' => array(\n";
																		foreach ($value7 as $key8 => $value8) {

																			$set_data .= "\t\t\t\t\t\t\t\t\t\t'$key8' => '$value8',\n";
																		}
																		$set_data .= "\n\t\t\t\t\t\t\t\t\t),\n";
																	}
																$set_data .= "\t\t\t\t\t\t\t\t),";
															} else {
																$set_data .= "\t\t\t\t\t\t\t\t'$key6' => '$value6',\n";
															}
														//3단계
														}
														$set_data .= "\n\t\t\t\t\t\t\t),\n";
													}
												$set_data .= "\t\t\t\t\t\t),";
											} else {
												$set_data .= "\t\t\t\t\t\t'$key4' => '$value4',\n";
											}
										//2단계
										}
										$set_data .= "\n\t\t\t\t\t),\n";
									}
								$set_data .= "\t\t\t\t),";
							} else {
								$set_data .= "\t\t\t\t'$key2' => '$value2',\n";
							}
						//1단계
						}
						$set_data .= "\n\t\t\t),\n";
					}
					$set_data .= "\n\t\t),\n";
				}
				$set_data .= "\t),\n";
				$set_data .= ");";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_menu.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "/admin/auth/menu_manage");
			} else {
				$this->config->load("cfg_menu");
				$get_data = array();
				$get_data["cfg_menu"] = $this->config->item("cfg_menu");
				$get_data["mode"] = "register";
				$this->set_view("admin/auth/menu_manage", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 개발자모드
	public function debug_mode() {
		try{
			$mode = $this->input->post("mode", true);

			if(isset($mode)) {
				$this->form_validation->set_rules("debug_mode", "디버그모드", "trim|xss_clean");

				if($this->form_validation->run()) {
					$debug_mode = $this->input->post("debug_mode", true);
					$set_data = "";
					$set_data .="<?php\r\n";
					$set_data .="defined('BASEPATH') OR exit('No direct script access allowed');\r\n\r\n";

					$set_data .="\$config = array(\r\n";
					$set_data .="\t'cfg_debug' => array(\r\n";
					$set_data .="\t\t'debug_mode' => ". intval($debug_mode) ."\r\n";
					$set_data .="\t),\r\n";
					$set_data .=");\r\n";

					$this->load->library("qfile");
					$this->qfile->open(APPPATH."/config/cfg_debug.php");
					$this->qfile->write($set_data);
					$this->qfile->close();
					msg("변경되었습니다.", "/admin/auth/debug_mode");
				} else {
					if(validation_errors()) {
						throw new Exception(validation_errors());
					}
				}
				throw new Exception("디버그모드 정보가 없습니다.");
			} else {
				$this->config->load("cfg_debug");
				$get_data = $this->config->item("cfg_debug");
				$get_data["mode"] = "register";
				$this->set_view("admin/auth/debug_mode", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 홈페이지 정보설정
	public function conf_reg() {
		try{

			$mode = $this->input->post("mode", true);
			$this->load->library("form_validation");

			if(isset($mode)) {
				$conf = $this->input->post("conf", true);
				$standard_mall = $this->input->post("standard_mall",true);
				$cfg_site = $this->_cfg_site;

				if(!isset($conf)) {
					throw new Exception("사이트 정보가 없습니다.");
				}else if(!isset($standard_mall)){
					throw new Exception("기준몰이 설정되있지 않습니다.");
				}

				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'cfg_site' => array(\n";
				$set_data .= "\t\t\t'standard_mall'	=>	'$standard_mall',\n";
				//언어별로 저장하도록 변경
				foreach ($conf as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}

				//seo
				$set_data .= "\t\t'seo' => array(\n";
				foreach($cfg_site["seo"] as $key => $val){
					$set_data .= "\t\t\t'$key'	=> '$val',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_site.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "conf_reg");
			} else {
				$get_data = array();
				$get_data["cfg_site"] = $this->_cfg_site;
				$get_data["mode"] = "register";

				$this->set_view("admin/auth/conf_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 약관 및 개인정보정책 설정
	public function terms_list() {
		try{
			$this->config->load("cfg_terms");
			$cfg_terms = $this->config->item("cfg_terms");

		    $this->load->model("Terms_model");

			$get_data = array();

			$get_data["terms"] = $this->Terms_model->getTermsList();

			$this->set_view("admin/auth/terms_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function terms_reg() {
		try{
			$this->load->library("form_validation");
			$this->config->load("cfg_terms");
			$cfg_terms = $this->config->item("cfg_terms");

		    $this->load->model("Terms_model");

			$code = $this->input->get_post("code", true);
			$mode = $this->input->post("mode", true);
			$language = $this->input->post("language",true);
			if(!isset($cfg_terms[$code])) {
				throw new Exception("잘못된 접근입니다.");
			}

			if(isset($mode)) {
				$this->form_validation->set_rules("code", "코드", "required|trim|xss_clean");
				$this->form_validation->set_rules("title", "레벨", "required|trim|xss_clean");
				$this->form_validation->set_rules("text", "레벨", "required|trim|xss_clean");

				if($this->form_validation->run()) {

					//2018-10-04 James ini 저장 => DB 저장으로 변경
					$result = $this->Terms_model->setTermsData($this->input->post(null, true));
					msg("저장되었습니다.", "terms_reg?code=". $code."&language=".$language);
				} else {
					throw new Exception(validation_error());
				}
				throw new Exception("약관정보가 없습니다.");
			} else {
				$get_data = array();
				$get_data["terms"] = $this->Terms_model->getTermsData($this->input->get(null, true));

				$get_data["mode"] = "register";
				$this->set_view("admin/auth/terms_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

    // 검색엔진 최적화(SEO) 설정
	public function search_engine_opt() {
		try{
			$mode = $this->input->post("mode", true);
			$this->load->library("form_validation");

			if(isset($mode)) {
				$conf = $this->input->post("conf", true);

				if(!isset($conf)) {
					throw new Exception("사이트 정보가 없습니다.");
				}

				$cfg_site = $this->_cfg_site;
				unset($cfg_site['seo']);

				$standard_mall = $cfg_site["standard_mall"];
				unset($cfg_site["standard_mall"]);

				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'cfg_site' => array(\n";
				$set_data .= "\t\t\t'standard_mall'	=>	'".$standard_mall."',\n";

				foreach ($cfg_site as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t'seo' => array(\n";
				foreach($conf as $key => $val){
					$set_data .= "\t\t\t'$key'	=> '$val',\n";
				}
				$set_data .= "\t\t),\n";
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_site.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "search_engine_opt");
			} else {
				$get_data = array();
				$get_data["cfg_site"] = $this->_cfg_site;
				$get_data["mode"] = "register";
				$this->set_view("admin/auth/search_engine_opt", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function display_main_list() {
		try {
			$this->load->library("pagination");
			$this->load->model("Admin_Goods_model");

			$arr_like = array();
			$language = $this->input->get("language", true);
			$search_type = $this->input->get("search_type", true);
			$search = $this->input->get("search", true);

			$limit = 10;

			if(isset($search_type)) {
				if($search) {
					if($search_type) {
						$arr_like[] = array($search_type, $search);
					} else {
						$arr_like[] = array("theme_name", $search, null, "or");
						$arr_like[] = array("theme_description", $search, null, "or");
					}
				}
			}

			$per_page = $this->input->get("per_page", true);

			if(!$per_page) {
				$per_page = 1;
			}

			$offset = ($per_page - 1) * $limit;

			$get_data = $this->Admin_Goods_model->get_list_display_theme($arr_where, $arr_like, $limit, $offset);
			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?code=". $this->_board["code"]."&language=". $language ."&search_type=". $search_type ."&search=". $search  ."&files=". $files,
				"suffix" => "&code=". $this->_board["code"]."&language=". $language ."&search_type=". $search_type ."&search=". $search ."&files=". $files,
			);
			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();
			$get_data["board_info"] = $this->_board;
			$get_data["offset"] = $offset;
			$get_data["offset"] = $search;

			$this->set_view("admin/auth/display_main_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function display_main_reg() {
		try {
			$no = $this->input->get("no", true);
			$mode = $this->input->post("mode", true);
			$this->load->model("Admin_Goods_model");

			if(isset($mode)) {
				if($mode == "modify") {
					$this->form_validation->set_rules("no", "진열번호", "required|trim|xss_clean");
				}
				$this->form_validation->set_rules("theme_name", "진열명", "required|trim|xss_clean");
				$this->form_validation->set_rules("theme_description", "진열설명", "required|trim|xss_clean");
				$this->form_validation->set_rules("skin_type", "게시판 스킨", "required|trim|xss_clean");

				if($this->form_validation->run()) {
					$set_data = $this->input->post(null, true);

					if($mode == "register"){
						$set_data["regdt"] = date('Y-m-d H:i:s');
						$set_data["mainview"] = 'n';
						$get_data = table_data_match("da_display_theme", $set_data);
						$result = $this->db->insert("da_display_theme", $get_data);
						if($result) {
							//$no = $this->db->insert_id();
							redirect("/admin/auth/display_main_list");
						} else {
							$this->dbforge->drop_table("da_board_". $set_data["code"]);
							throw new Exception("오류가 발생하였습니다.\n\n잠시후 다시시도해주세요.");
						}
					} else if($mode == "modify") {
						$set_data["moddt"] = date('Y-m-d H:i:s');
						$get_data = table_data_match("da_display_theme", $set_data);
						$result = $this->db->update("da_display_theme", $get_data, array("no" => $set_data["no"]));

						if($result) {
							msg("수정되었습니다.", "/admin/auth/display_main_list");
						}
					}
				} else {
					if(validation_errors()) {
						throw new Exception(validation_errors());
					}
				}

				throw new Exception("진열 정보가 없습니다.");
			} else {
				$get_data = array();
				if(isset($no)) {
					$arr_where = $arr_goods_where =array();
					$arr_where[] = array("no", $no);
					$arr_goods_where[] = array("display_theme_no", $no);

					$get_data = $this->Admin_Goods_model->get_view_display_theme($arr_where);
					$get_data = array_merge($get_data, $this->Admin_Goods_model->get_display_theme_goods_data($arr_goods_where));

					$get_data["mode"] = "modify";
				} else {
					$get_data["mode"] = "register";
				}

				$arr_skin = @scandir(APPPATH. "../data/skin/".$this->_skin."/layout/display");
				foreach($arr_skin as $key => $val){
					if(strpos($val, "html") === false){
						unset($arr_skin[$key]);
					}
				}

				$get_data["skin_files"] = $arr_skin;

				$this->set_view("admin/auth/display_main_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function delete_display_theme() {
		try{
			if(!$this->input->post("no", true)) {
				throw new Exception("상품진열의 정보를 찾을 수 없습니다.");
			}

			$no = $this->input->post("no", true);
			$arr_where = array();
			$arr_where[] = array("no", $no, "IN");

			$this->load->model("Admin_Goods_model");
			$result = $this->Admin_Goods_model->delete_display_theme($arr_where);

			if($result) {
				$this->load->model("Admin_Goods_model");
				$this->Admin_Goods_model->delete_display_theme_goods($arr_where);
				msg("상품진열이 삭제되었습니다.", "display_main_list");
			} else {
				throw new Exception("상품진열의 삭제를 실패하였습니다.\n\n잠시후 다시 시도해주세요.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}

	}
}