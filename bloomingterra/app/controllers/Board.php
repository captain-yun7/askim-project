<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends FRONT_Controller {
	public $_board;
	public function __construct() {
		parent::__construct();
		$this->load->model("Front_Board_model");
		$this->load->model("Terms_model");
		try {
			$this->Front_Board_model->initialize();
			$this->_board = $this->Front_Board_model->get_board();
		} catch(Exception $e) {
			if($e->getCode() == 50) {
				msg($e->getMessage(), '/');
			}else{
				msg($e->getMessage(), -1);
			}
		}
	}

	public function index() {
		go("/board/board_list?code=". $this->_board["code"]);
	}

	public function board_list() {
		try {
			if(!$this->Front_Board_model->is_display_list()) {
				throw new Exception(print_language("no_bulletin_board_information_found"));
			}

			$this->load->library("form_validation");
			$this->load->library("pagination");
			$arr_orderby = array("sort asc");
			$arr_where = array();
			$arr_where[] = array("yn_display_list", "y");
			$board_nav = $this->Front_Board_model->get_board_manege($arr_where, null, null, null, $arr_orderby);

			$arr_like = array();
			$search_type = $this->input->get("search_type", true);
			$search = $this->input->get("search", true);

			if($search) {
				$arr_like[] = array($search_type, $search);
			}

			$per_page = $this->input->get("per_page", true);

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = $this->_board["roundpage"];

			$offset = ($per_page - 1) * $limit;

			$board_list = $this->Front_Board_model->get_list_board(array(array("language", $this->_site_language)), $arr_like, $limit, $offset);

			$config = array(
				"total_rows" => $board_list["total_rows"],
				"per_page" => $limit,
				"first_url" => "?code=". $this->_board["code"]."&search_type=". $search_type ."&search=". $search,
				"suffix" => "&code=". $this->_board["code"]."&search_type=". $search_type ."&search=". $search,
			);

			$this->pagination->initialize($config);
			$board_list["pagination"] = $this->pagination->create_links();

			$form_attribute = array(
				"name" => "frm",
				"method" => "get",
			);
			$this->_board["offset"] = $offset;

			// preface
			if($this->_board['yn_preface'] == "y" && $this->_board['preface']) {
				$preface = explode(",", $this->_board['preface']);
				$this->template_->assign("preface", $preface);
			}

			$temp_no = [];
			foreach($board_list['board_list'] as $key => $value) {
				$temp_no[] = $value['no'];
			}

			$tags = [];
			$temp_tags = $this->dm->get('da_board_gallery_tags', [], [], [], ['bno' => $temp_no]);
			foreach($temp_tags as $key => $value) {
				$tags[$value['bno']][] = $value['tag'];
			}

			$extra = [];
			foreach($board_list['board_list'] as $key => $value) {
				$ext_json = (array)json_decode($value['extraFieldInfo']);
				if(count((array)$ext_json[$this->_site_language]) > 0) {
					foreach($ext_json[$this->_site_language] as $k => $v) {
						if(strpos($v, "^|^") > -1) {
							$tmp = explode("^|^", $v);
							$extra[$value['no']][$k] = $tmp[0];
						} else {
							$extra[$value['no']][$k] = $v;
						}
					}
				}

				// thumbnail
				$upload = $this->input->server('DOCUMENT_ROOT').'/upload/board/'.$this->_board['code'];
				if(file_exists($upload.'/'.$value['thumbnail_image'])) {
					$thumbnail[$value['no']] = '/upload/board/'.$this->_board['code'].'/'.$value['thumbnail_image'];
				} else {
					$thumbnail[$value['no']] = '/upload/board/'.$this->_board['code'].'/'.$value['board_file']['thumbnail'][0]['fname'];
				}
			}
			$this->template_->assign("extra", $extra);
			$this->template_->assign("thumbnail", $thumbnail); // thumbnail
			$this->template_->assign('tags', $tags);

			$this->template_->define("board_display", $this->_skin ."/layout/board/". $this->_board["skin_type"]);
			$this->template_->assign("board_nav", $board_nav);
			$this->template_->assign("form_attribute", $form_attribute);
			$this->template_->assign("board_info", $this->_board);
			$this->template_->assign("board_list", $board_list);
			$this->template_->assign("page_title", $this->_board['name']);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_view() {
		try {
			if(!$this->input->get("no", true)) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			$get = $this->input->get(null, true);

			$cfg_site = $this->config->item("cfg_site");

			$arr_orderby = array("sort asc");
			$arr_where = array();
			$arr_where[] = array("yn_display_list", "y");
			$board_nav = $this->Front_Board_model->get_board_manege($arr_where, null, null, null, $arr_orderby);

			$this->config->load("cfg_terms");
			$cfg_terms = $this->config->item("cfg_terms");

			$searchData = [];

			$searchData = [
				"language" => $this->_site_language,
				"code" => "nonMember"
			];

			$terms["nonMember"] = $this->Terms_model->getTermsData($searchData);

			foreach($cfg_site[$this->_site_language] as $key => $value) {
				$terms["nonMember"] = str_replace("{\$".$key."}", $value	, $terms["nonMember"]);
			}

			$this->load->library("form_validation");
			$no = $this->input->get("no", true);
			$arr_where = array();
			$arr_where[] = array("no", $no);
			$arr_where[] = array("language", $this->_site_language);

			$board_view = $this->Front_Board_model->get_view_board($arr_where);

			if($board_view['board_view']['is_secret'] == 'y'){
				if(!empty($board_view['board_view']['userid'])) {
					if($this->_member['userid'] != $board_view['board_view']['userid']){
						throw new Exception(print_language("do_no_have_is_secret_permission"));
					}
				}else{
                    // 비회원 글에 비밀번호 입력없이 접근시 리다이렉트 추가 2020-06-25
                    $password = $this->input->post("password", true);
                    $code = $board_view['board_view']['code'];
                    $no = $board_view['board_view']['no'];
                    if(empty($password)) {
                        go("/board/board_secret?code=$code&no=$no&page=view");
                    }

					$inputPwd = base64_encode(hash("sha256", $this->input->post("password"), true));
					if(empty($board_view['board_view']['password']) && $inputPwd != $board_view['board_view']['password']){
						throw new Exception(print_language("do_no_have_is_secret_permission"));
					}else if($inputPwd != $board_view['board_view']['password']){
						throw new Exception(print_language("password_does_not_match"));
					}
				}
			}

			if($this->_board["comment"] == "y") { //댓글달기 yes일경우
				$arr_where = array();
				$arr_where[] = array("no", $no);
				$board_comment = $this->Front_Board_model->get_list_board_comment($arr_where);
				$board_view = array_merge($board_view, $board_comment);
			}

			$form_attribute = array(
				"name" => "frm",
				"method" => "get",
			);

			// 추가필드 정보 변환
			if($this->_board['extraFl'] == 'y'){
				$extraFieldData = array();
				if(!empty($this->_board['extraFieldInfo']["use"][$this->_site_language])){
					foreach($this->_board['extraFieldInfo']["use"][$this->_site_language] as $columnKey => $columnVal){
						$columnNm = $this->_board['extraFieldInfo']['name'][$this->_site_language][$columnKey];
						$extraOption = $this->_board["extraFieldInfo"]["option"][$this->_site_language][$columnKey];
						if($this->_board['extraFieldInfo']["option"][$this->_site_language][$columnKey]["type"] == "file") {
							$boardViewVal = explode('^|^', $board_view['board_view']['extraFieldInfo'][$this->_site_language][$columnKey])[0];
						}else{
							$boardViewVal = $board_view['board_view']['extraFieldInfo'][$this->_site_language][$columnKey];
						}

						if(!empty($boardViewVal)){
							if($extraOption["type"] == "file") {
									if($extraOption['file_type'] == 'image') {
										$extraFieldData[$columnKey]["width"] = $extraOption['width'];
										$extraFieldData[$columnKey]["height"] = $extraOption['height'];
									}
									$downloadPath = "/fileRequest/download?file=".urlencode("/board/".$board_view['board_view']['upload_path']."/".$boardViewVal);
									$extraFieldData[$columnKey]["value"] = sprintf("<a href = '%s' target='_blank' style='color:cornflowerblue;'>%s</a>", $downloadPath, $boardViewVal);
									$extraFieldData[$columnKey]["type"] = $extraOption["file_type"];
									$extraFieldData[$columnKey]["file_name"] = $boardViewVal;
									$extraFieldData[$columnKey]["original_file_name"] = explode('^|^', $board_view['board_view']['extraFieldInfo'][$this->_site_language][$columnKey])[1];
									$extraFieldData[$columnKey]["link"] = $downloadPath;
							}else {
								$extraFieldData[$columnKey]["value"] = $boardViewVal;
								$extraFieldData[$columnKey]["type"] = $extraOption['type'];
							}

							$extraFieldData[$columnKey]["name"] = $columnNm;

						}
					}
					$this->template_->assign("extraFieldData", $extraFieldData);
				}
			}

			// previous, next
			$button = [
				'next' => 'nothing',
				'prev' => 'nothing',
			];

			$temp_next = $this->dm->get('da_board_gallery', [], ['no > ' => $get['no']], [], [], ['no' => 'ASC'])[0];
			$temp_prev = $this->dm->get('da_board_gallery', [], ['no < ' => $get['no']], [], [], ['no' => 'DESC'])[0];
			if($temp_next['no'] > 0) $button['next'] = $temp_next['no'];
			if($temp_prev['no'] > 0) $button['prev'] = $temp_prev['no'];

			if($get['tag']) {
				$temp_next = $this->dm->get('da_board_gallery_tags', [], ['tag' => $get['tag'], 'bno > ' => $get['no']], [], [], ['bno' => 'ASC'])[0];
				$temp_prev = $this->dm->get('da_board_gallery_tags', [], ['tag' => $get['tag'], 'bno < ' => $get['no']], [], [], ['bno' => 'DESC'])[0];
				if($temp_next['bno'] > 0) $button['next'] = $temp_next['bno'];
				if($temp_prev['bno'] > 0) $button['prev'] = $temp_prev['bno'];
			}

			if($get['search']) {
				$temp_next = $this->dm->get('da_board_gallery', [], ['no > ' => $get['no']], ['title' => $get['search'].'|both'], [], ['no' => 'ASC'])[0];
				$temp_prev = $this->dm->get('da_board_gallery', [], ['no < ' => $get['no']], ['title' => $get['search'].'|both'], [], ['no' => 'DESC'])[0];
				if($temp_next['no'] > 0) $button['next'] = $temp_next['no'];
				if($temp_prev['no'] > 0) $button['prev'] = $temp_prev['no'];
			}

			$this->template_->assign('button', $button);

			// Related Posts (Insight 게시판만, 어드민이 수동 지정한 글 번호 2개 페치)
			// Front_Board_model::get_view_board가 related_no* 컬럼을 SELECT 안 하므로 별도 쿼리
			if($this->_board['code'] == 'gallery') {
				$main_row = $this->dm->get('da_board_gallery', [], ['no' => $no])[0] ?? null;
				$related_posts = [];
				if($main_row) {
					foreach(['related_no1', 'related_no2'] as $rk) {
						$rno = (int)($main_row[$rk] ?? 0);
						if($rno <= 0 || $rno == (int)$no) continue;
						$row = $this->dm->get('da_board_gallery', [], ['no' => $rno])[0] ?? null;
						if(!$row) continue;
						$thumb = '';
						if(!empty($row['thumbnail_image']) && file_exists($this->input->server('DOCUMENT_ROOT').'/upload/board/gallery/'.$row['thumbnail_image'])) {
							$thumb = '/upload/board/gallery/'.$row['thumbnail_image'];
						}
						$related_posts[] = [
							'no' => $row['no'],
							'title' => $row['title'],
							'regdt' => $row['regdt'],
							'thumb' => $thumb,
						];
					}
				}
				$this->template_->assign('related_posts', $related_posts);
			}

			//2020-05-27 Inbet Matthew 썸네일 복수 등록할때 대표 이미지 등록이 안되 있을시 첫번째 이미지를 대표이미지로 변경
			if(count($board_view['board_view']['board_file']['thumbnail']) > 0 && empty($board_view['board_view']['thumbnail_image'])) {
				$board_view['board_view']['thumbnail_image'] = $board_view['board_view']['board_file']['thumbnail'][0]['fname'];
			}
			//Matthew end
			$this->template_->assign("terms", $terms);
			$this->template_->assign("board_nav", $board_nav);
			$this->template_->assign("board_info", $this->_board);
			$this->template_->assign("board_view", $board_view);
			$this->template_->assign("page_title", $this->_board['name']);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			if($e->getCode() == 50) {
				msg($e->getMessage(), '/');
			}else{
				msg($e->getMessage(), -1);
			}
		}
	}

	public function board_secret() {
		try {
			if(!$this->input->get("no", true) || !$this->input->get("page", true)) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			if(!in_array($this->input->get("page", true), array("view", "write", "delete"))) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			$this->load->library("form_validation");

			$arr_orderby = array("sort asc");
			$arr_where = array();
			$arr_where[] = array("yn_display_list", "y");
			$board_nav = $this->Front_Board_model->get_board_manege($arr_where, null, null, null, $arr_orderby);

			$no = $this->input->get("no", true);
			$page = $this->input->get("page", true);

			$form_attribute = array(
				"action" => "/board/board_". $page ."?code=". $this->_board["code"] ."&no=". $no,
				"attribute" => array(
					"method" => "POST",
					"name" => "frm",
				),
			);

			$this->template_->assign("board_nav", $board_nav);
			$this->template_->assign("board_info", $this->_board);
			$this->template_->assign("form_attribute", $form_attribute);
			$this->template_->assign("page_title", $this->_board['name']);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_write() {
		$this->load->library("form_validation");
		$this->load->library("captcha");
		$mode = $this->input->post("mode", true);

		if($mode) {
			try {
				$this->form_validation->set_rules("no", print_language("board_no"), "trim|xss_clean|is_natural_no_zero");
				if($this->_board['use_captcha'] == "y") {
					if($mode == "write") {
						$sess_captcha = $this->captcha->get_sess_captcha("write");
						if($this->input->post("captcha", true) && $sess_captcha['word']) {
							if($sess_captcha["word"] != $this->input->post("captcha", true)) {
								msg(print_language("does_not_match", print_language("prevent_automatic_subscription")));
								exit;
							}
						} else {
							msg("정상적인 경로로 접근해 주세요.", -1);
							exit;
						}
					}
				}

				if(in_array($mode, array("write", "modify", "answer"))) {
					$this->form_validation->set_rules("title", print_language("board_title"), "trim|required|xss_clean");
					$this->form_validation->set_rules("name", print_language("board_name"), "trim|required|xss_clean");
					if($mode == "write" || $mode == "answer") {
						if(!defined("_IS_LOGIN")) {
								$this->form_validation->set_rules("password", print_language("board_password"), "trim|required|xss_clean");
						}
					} else if($mode == "modify") {
						if(!$this->input->post("write_userid", true)) { // 작성글 회원|비회원 구분
							$this->form_validation->set_rules("password", print_language("board_password"), "trim|required|xss_clean");
						}
					}
                    if($this->_board["thumnail_count"]) {
                        for($i=1; $this->_board["thumnail_count"] >= $i; $i++){
                            $this->form_validation->set_rules("thumnail$i_fname", "썸네일첨부$i", "trim|xss_clean");
                            $this->form_validation->set_rules("thumnail$i_oname", "썸네일첨부$i", "trim|xss_clean");
                            $this->form_validation->set_rules("thumnail$i_image", "대표이미지$i", "trim|xss_clean");
                        }
                    }
                    if($this->_board["file_count"]) {
                        for($i=1; $this->_board["file_count"] >= $i; $i++){
                            $this->form_validation->set_rules("file$i_fname", "파일첨부$i", "trim|xss_clean");
                            $this->form_validation->set_rules("file$i_oname", "파일첨부$i", "trim|xss_clean");
                        }
                    }
					$this->form_validation->set_rules("content", print_language("board_content"), "required");
					$this->form_validation->set_rules("is_secret", print_language("is_secret"), "trim|xss_clean");
					$this->form_validation->set_rules("file_fname", print_language("board_file"), "trim|xss_clean");
					$this->form_validation->set_rules("file_oname", print_language("board_file"), "trim|xss_clean");
					if($this->_board["yn_mobile"] == "y") {
						$this->form_validation->set_rules("mobile", print_language("board_mobile"), "trim|required|xss_clean");
					}

					if($this->_board["yn_email"] == "y") {
						$this->form_validation->set_rules("email", print_language("board_email"), "trim|required|valid_email|xss_clean");
					}

					if($this->_board["yn_video"] == "y") {
						$this->form_validation->set_rules("video_url", print_language("board_video_url"), "trim|required|xss_clean");
					}

					if($this->_board['extraFl'] == "y") {
						if(in_array($mode, array("write", "modify"))) {
							for($idx=1;$idx<=10;$idx++){
								if($this->input->post('cref', true) != '') {
									break;
								}
								if(!empty($this->_board["extraFieldInfo"]["use"][$this->_site_language]["ex".$idx])){
									$this->form_validation->set_rules("ex".$idx."_".$this->_site_language . ((isset($this->_board["extraFieldInfo"]["option"][$this->_site_language]["ex".$idx]["type"]) && $this->_board["extraFieldInfo"]["option"][$this->_site_language]["ex".$idx]["type"] == "file") ? "_fname" : ""), $this->_board["extraFieldInfo"]["name"][$this->_site_language]["ex".$idx], "trim". ($this->_board["extraFieldInfo"]["option"][$this->_site_language]["ex".$idx]["type"] != "editor" ? "|xss_clean" : "") . (isset($this->_board["extraFieldInfo"]["use"][$this->_site_language]["ex".$idx]) && isset($this->_board["extraFieldInfo"]["require"][$this->_site_language]["ex".$idx]) ? "|required" : ""));
									if($this->_board["extraFieldInfo"]["option"][$this->_site_language]["ex".$idx]["type"] == "file"){
										$_POST["ex".$idx."_".$this->_site_language] = $_POST["ex".$idx."_".$this->_site_language."_fname"];
									}
								}else{
									unset($_POST["ex".$idx."_".$this->_site_language]);
								}
							}
						}
					}
				} else if(in_array($mode, array("inquire_answer_write", "inquire_answer_modify"))) {
					$this->form_validation->set_rules("answer_title", print_language("board_answer_title"), "trim|required|xss_clean");
					$this->form_validation->set_rules("answer_content", print_language("board_answer_content"), "trim|required|xss_clean");
				}

				if($this->form_validation->run()){
					$data = $this->input->post(null, false);
					$is_secret = $this->input->post("is_secret", is_secret);
					if($is_secret == 'y') {
						$data['is_secret'] = $is_secret;
 					}

					$no = $this->Front_Board_model->board_write($data, $mode);
					if($no) {
						if($mode == 'write') {
							if($this->_board['yn_admin_email'] == 'y') {
								$this->load->library('Sendemail');
								$title = $data['preface'] ? '['.$data['preface'].']'.$data['title'] : $data['title'];
								$attachment = [];
								if($this->_board['file_count'] > 0) {
									for($i = 1; $i <= $this->_board['file_count']; $i++) {
										$uploaded = FCPATH.$data['file'.$i.'_folder'].'/'.$data['file'.$i.'_fname'];
										if(file_exists($uploaded) === true && $data['file'.$i.'_fname'] != '') {
											$attachment[] = [
												'rename' => $uploaded,
												'original' => $data['file'.$i.'_oname'],
												'mime' => mime_content_type($uploaded)
											];
										}
									}
								}

								$cfg_site = $this->config->item('cfg_site');

								$this->sendemail->setup([
									'language' => 'kor',
									'types' => 'admin',
									'mailto' => ['name' => $cfg_site['kor']['nameKor'], 'email' => $cfg_site['kor']['adminEmail']],
									'subject' => $data['name'].' 님의 문의입니다.',
									'attachment' => $attachment,
									'extrainfos' => [
										'board_name' => $this->_board['name'],
										'title' => $title,
										'name' => $data['name'],
										'area_of_interest' => $data['ex1_kor'],
										'company' => $data['title'],
										'full_name' => $data['name'],
										'phone_number' => $data['mobile'],
										'email' => $data['email'],
										'message' => $data['content'],
									]
								]);
							}
						}
						// 리스트 노출이 사용일때
						if($this->_board['yn_display_list'] == 'y'){
							// 로그인 시
							if(defined("_IS_LOGIN")) {
								go("/board/board_view?code=". $this->_board["code"] ."&no=". $no, "parent");
							} else {
								if($is_secret == "y") {
									go("/board/board_list?code=". $this->_board["code"] ."&no=". $no ."&page=view", "parent");
								} else {
									//go("/board/board_view?code=". $this->_board["code"] ."&no=". $no, "parent");
									go("/board/board_list?code=". $this->_board["code"], "parent");
								}
							}
						} else {
							msg(print_language('inquiry_is_registered'), "/board/board_write?code=". $this->_board["code"], "parent");
						}
					} else {
						msg(print_language("could_not_write") ."\n\n". print_language("please_try_again"));
					}
				} else {
					if(validation_errors()) {
						msg(validation_errors());
					}
				}
			} catch(Exception $e) {
				msg($e->getMessage(), -1, "parent");
			}
		} else {
			try {
				$cfg_site = $this->config->item("cfg_site");

				$searchData = [];

				$searchData = [
					"language" => $this->_site_language,
					"code" => "nonMember"
				];

				$terms["nonMember"] = $this->Terms_model->getTermsData($searchData);

				foreach($cfg_site[$this->_site_language] as $key => $value) {
					$terms["nonMember"] = str_replace("{\$".$key."}", $value	, $terms["nonMember"]);
				}

				if($this->_board['use_captcha'] == "y") {
					$captcha = $this->captcha->get_captcha("write");
					$this->template_->assign("captcha", $captcha);
					$this->session->set_userdata("board_captcha", $captcha['word']);
				}

				$arr_orderby = array("sort asc");
				$arr_where = array();
				$arr_where[] = array("yn_display_list", "y");
				$board_nav = $this->Front_Board_model->get_board_manege($arr_where, null, null, null, $arr_orderby);

				if($this->input->get("cref", true)) {
					$mode = "answer"; // 답글쓰기
				} else if($this->input->get("answer_status", true) == "n") {
					$mode = "inquire_answer_write"; // 답변작성
				} else if($this->input->get("answer_status", true) == "y") {
					$mode = "inquire_answer_modify"; // 답변수정
				} else if($this->input->get("no", true)) {
					$mode = "modify"; // 수정
				} else {
					$mode = "write"; // 쓰기
				}

				if($mode == "inquire_answer_write" || $mode == "inquire_answer_modify") {
					if(!$this->Front_Board_model->is_inquire_answer()) {
						throw new Exception(print_language("do_not_have_permission"));
					}
				} else if(!$this->Front_Board_model->is_write()){
                    // 글 작성페이지 진입시 권한 검사 2020-06-17
                    throw new Exception(print_language("do_not_have_permission"));
                }

				$this->_board["mode"] = $mode;

				if(in_array($mode, array("inquire_answer_modify", "modify", "answer"))) {
					$no = $this->input->get("no", true);
					$arr_where = array();
					$arr_where[] = array("no", $no);
					$board_view = $this->Front_Board_model->get_view_modify($arr_where, $mode);

					if($mode == "answer") {
						if(isset($board_view["board_view"])) {
							$is_secrect = $board_view["board_view"]['is_secret'];
							unset($board_view);
							$board_view['board_view'] = array("is_secret" => $is_secrect);
						}

						if(!empty($this->input->get("cref", true))){
							$board_view['board_view']['no'] = $this->input->get("cref", true);
							$board_view['board_view']['cref'] = $this->input->get("cref", true);
						}

                        $this->_board['extraFl'] = 'n'; // 답글일때 추가필드 입력폼 노출 x 20200427
					}

                    if($mode == "modify") {
                        if($board_view['board_view']['cref'] != $board_view['board_view']['no']) {
                            $this->_board['extraFl'] = 'n'; // 답글 수정일때 추가필드 입력폼 노출 x 20200427
                        }
                        if(!empty($board_view['board_view']['userid'])) {
                            if($this->_member['userid'] != $board_view['board_view']['userid']){
                                throw new Exception(print_language("do_not_have_permission"));
                            }
                        }else{
                            // 비밀번호 입력없이 접근시 리다이렉트 추가 2020-06-25
                            $password = $this->input->post("password", true);
                            $code = $board_view['board_view']['code'];
                            $no = $board_view['board_view']['no'];
                            if(empty($password)) {
                                go("/board/board_secret?page=write&code=$code&no=$no");
                            }

                            $inputPwd = base64_encode(hash("sha256", $this->input->post("password"), true));
                            if(empty($board_view['board_view']['password']) && $inputPwd != $board_view['board_view']['password']){
                                throw new Exception(print_language("do_no_have_is_secret_permission"));
                            }else if($inputPwd != $board_view['board_view']['password']){
                                throw new Exception(print_language("password_does_not_match"));
                            }
                        }
                    }
					$this->template_->assign("board_view", $board_view);
				}

				$this->template_->assign("board_nav", $board_nav);
				$this->template_->assign("terms", $terms);

				$this->template_->assign("board_info", $this->_board);

				$this->template_->assign("page_title", $this->_board['name']);
				$this->template_print($this->template_path());
			} catch(Exception $e) {
				msg($e->getMessage(), -1);
			}
		}
	}

	public function board_delete() {
		try {
			if(!$this->input->get("no", true)) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			$no = $this->input->get("no", true);
			$arr_where = array();
			$arr_where[] = array("no", $no);

			$board_view = $this->Front_Board_model->get_view_board($arr_where);
            if(!empty($board_view['board_view']['userid'])) {
                //회원의 게시글이라면 작성자만 삭제 가능
                if($this->_member['userid'] != $board_view['board_view']['userid']){
                    throw new Exception(print_language("do_not_have_permission"));
                }
            } else {
                // 비밀번호 입력없이 접근시 리다이렉트 추가 2020-06-25
                $password = $this->input->post("password", true);
                $code = $board_view['board_view']['code'];
                $no = $board_view['board_view']['no'];
                if(empty($password)) {
                    go("/board/board_secret?page=delete&code=$code&no=$no");
                }

                $inputPwd = base64_encode(hash("sha256", $this->input->post("password"), true));
                if(empty($board_view['board_view']['password']) && $inputPwd != $board_view['board_view']['password']){
                    throw new Exception(print_language("do_no_have_is_secret_permission"));
                }else if($inputPwd != $board_view['board_view']['password']){
                    throw new Exception(print_language("password_does_not_match"));
                }
            }

			$result = $this->Front_Board_model->board_delete($arr_where);

			if($result) {
                $board_data = array();
                $board_data["master_no"] = $no;
                $board_data["code"] = $this->input->get("code", true);
                $this->Front_Board_model->board_file_delete($board_data);

                // 댓글 삭제
				$arr_where = array();
				$arr_where[] = array("code", $this->input->get("code", true));
				$arr_where[] = array("no", $no);
				$this->Front_Board_model->board_comment_delete_direct($arr_where);

				msg(print_language("delete_post"), "/board/board_list?code=". $this->_board["code"]);
			} else {
				throw new Exception(print_language("failed_to_delete_post") ."\n\n". print_language("please_try_again"));
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function board_comment_write() {
		try {
			if(!defined("_IS_AJAX")) {
				throw new Exception(print_language("inaccessible_pages"));
			}

			$this->load->library("form_validation");

			$this->form_validation->set_rules("mode", print_language("board_mode"), "trim|required|xss_clean");
			$this->form_validation->set_rules("no", print_language("board_no"), "trim|required|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("name", print_language("board_name"), "trim|required|xss_clean");
			$this->form_validation->set_rules("content", print_language("board_content"), "trim|required|xss_clean");
			$this->form_validation->set_rules("file_fname", print_language("board_file"), "trim|xss_clean");
			$this->form_validation->set_rules("file_oname", print_language("board_file"), "trim|xss_clean");
			if(!defined("_IS_LOGIN")) {
				$this->form_validation->set_rules("password", print_language("board_password"), "trim|required|xss_clean");
			}

			if($this->form_validation->run()){
				$mode = $this->input->post("mode", true);
				$data = $this->input->post(null, true);
				$result = $this->Front_Board_model->board_comment_write($data, $mode);
				if($mode == "write") {
					if($result) {
						echo json_encode(array("code" => true, "msg" => print_language("add_comment")));
					} else {
						throw new Exception(print_language("failed_to_comment"));
					}
				} else if($mode == "modify") {
					if($result) {
						echo json_encode(array("code" => true, "msg" => print_language("modify_comment")));
					} else {
						throw new Exception(print_language("comment_modify_failed"));
					}
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}


	public function board_comment_delete() {
		try {
			if(!defined("_IS_AJAX")) {
				throw new Exception(print_language("inaccessible_pages"));
			}
			$this->load->library("form_validation");

			$this->form_validation->set_rules("no", print_language("board_no"), "trim|required|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("idx", print_language("board_idx"), "trim|required|xss_clean|is_natural_no_zero");

			if(!defined("_IS_LOGIN")) {
				$this->form_validation->set_rules("password", print_language("board_password"), "trim|required|xss_clean");
			}

			if($this->form_validation->run()){
				$arr_where = array();
				$arr_where[] = array("code", $this->_board["code"]);
				$arr_where[] = array("no", $this->input->post("no", true));
				$arr_where[] = array("idx", $this->input->post("idx", true));
				$result = $this->Front_Board_model->board_comment_delete($arr_where);

				if($result) {
					echo json_encode(array("code" => true, "msg" => print_language("delete_comment")));
				} else {
					throw new Exception(print_language("failed_to_delete_post") ."\n\n". print_language("please_try_again"));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}

	public function comment_password_check() {
		try {
			if(!defined("_IS_AJAX")) {
				throw new Exception(print_language("inaccessible_pages"));
			}
			$this->load->library("form_validation");

			$this->form_validation->set_rules("no", print_language("board_no"), "trim|required|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("idx", print_language("board_idx"), "trim|required|xss_clean|is_natural_no_zero");
			$this->form_validation->set_rules("password", print_language("board_password"), "trim|required|xss_clean");

			if($this->form_validation->run()){
				$arr_where = array();
				$arr_where[] = array("code", $this->_board["code"]);
				$arr_where[] = array("no", $this->input->post("no", true));
				$arr_where[] = array("idx", $this->input->post("idx", true));

				$result = $this->Front_Board_model->board_comment_password_check($arr_where, $this->input->post("password", true));

				if($result["yn_password"] == "y") {
					echo json_encode(array("code" => true, "confirm" => true));
				} else {
					throw new Exception(print_language("password_does_not_match"));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}
}