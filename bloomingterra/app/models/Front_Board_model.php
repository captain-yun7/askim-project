<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Board_model.php");

class Front_Board_model extends Board_model {
	private $_auth; // 관리자 권한

	public function initialize($code = null, $exceptFlag = false) {
		parent::initialize($code);
		$this->config->load("cfg_adm_auth");
		$this->_auth = array_keys($this->config->item("auth"));
		$this->_board["is_write"] = $this->is_write();

        // 관리자 미사용 게시판 접근 제어
        if($this->_board["adminview"] == "n" && $exceptFlag == false) {
			throw new Exception(print_language("no_bulletin_board_information_found"), 50);
        }
	}


	/*
	 * @override
	 * 게시판 nav정보 가져오기
	 *
	 *	@return array
	 */
	public function get_board_manege($arr_where = null, $array_like = null, $limit = null, $offset = null, $arr_orderby = null) {
        $get_data = parent::get_board_manege($arr_where, $array_like, $limit, $offset, $arr_orderby);

        if(ib_isset($get_data["board_manage_list"])) {
            // 다국어 가져오기
            foreach($get_data["board_manage_list"] as $key => $val) {
                if(!is_array($this->_site_language)) {
                    $get_data["board_manage_list"][$key]["global"] = $this->get_board_global($val["code"], $this->_site_language);
                }
            }
        }
		return $get_data;
	}

	/*
	 * @override
	 * 게시판 리스트정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *
	 * @return array
	 */
	public function get_list_board($arr_where = null, $arr_like = null, $limit = null, $offset = null) {
        // 다국어 게시판 list 기능 추가
        if(ib_isset($this->_site_language)) {
            $arr_where[] = array("language", $this->_site_language);
        }

		$category = $this->input->get("category", true);
		if($category) $arr_where[] = array("preface", $category);

		$get_data = parent::get_list_board($arr_where, $arr_like, $limit, $offset);

		if(isset($get_data["notice_list"])) {
			foreach($get_data["notice_list"] as $key => $value) {
				$get_data["notice_list"][$key]["is_read"] = $this->is_read($get_data["notice_list"][$key], "list");
				$get_data["notice_list"][$key]["display_read"] = $this->display_read($get_data["notice_list"][$key], "list");
			}
		}
		if(isset($get_data["board_list"])) {
			foreach($get_data["board_list"] as $key => $value) {
				$get_data["board_list"][$key]["is_read"] = $this->is_read($get_data["board_list"][$key], "list");
				$get_data["board_list"][$key]["display_read"] = $this->display_read($get_data["board_list"][$key], "list");
			}
		}
		return $get_data;
	}

	/*
	 * @override
	 * 게시판 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *
	 * @return array
	 */
	public function get_view_board($arr_where = null) {
		$get_data = parent::get_view_board($arr_where);

		if(!isset($get_data["board_view"])) {
			throw new Exception(print_language("wrong_approach"), 50);
			//throw new Exception(print_language("no_information_found_in_the_post"));
		}

		if(($get_data["is_read"] = $this->is_read($get_data["board_view"], "view")) === false) {
			throw new Exception(print_language("do_not_have_permission"));
		}

		$get_data["board_view"] = $this->board_hits($get_data["board_view"], $arr_where);

		$get_data["is_modify"] = $this->is_modify($get_data["board_view"]);
		$get_data["is_delete"] = $this->is_delete($get_data["board_view"]);

		$get_data["is_answer_write"] = $this->is_answer_write($get_data["board_view"]);
		$get_data["is_comment_write"] = $this->is_comment_write();
		$get_data["is_inquire_answer"] = $this->is_inquire_answer();

		return $get_data;
	}

	/*
	 * @override
	 * 게시판 답글리스트정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 */
	public function get_list_board_comment($arr_where = null, $arr_like = null, $limit = null, $offset = null) {
		$get_data = parent::get_list_board_comment($arr_where, $arr_like, $limit, $offset);
		if(isset($get_data["board_list_comment"])) {
			foreach($get_data["board_list_comment"] as $key => $value) {
				$get_data["board_list_comment"][$key]["is_comment_modify"] = $this->is_comment_modify($get_data["board_list_comment"][$key]);
				$get_data["board_list_comment"][$key]["is_comment_delete"] = $this->is_comment_delete($get_data["board_list_comment"][$key]);
			}
		}
		return $get_data;
	}

	/*
	 * 게시판 수정모드 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *
	 * @return array
	 */
	public function get_view_modify($arr_where = null, $mode) {

		$get_data = parent::get_view_board($arr_where);
		if($mode == "inquire_answer_modify" || $mode == "modify") {
			if(!isset($get_data["board_view"])) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			if($this->is_modify($get_data["board_view"]) === false) {
				throw new Exception(print_language("do_not_have_permission"));
			}

			$get_data['board_view']['input_password'] = base64_encode(hash("sha256", $this->input->post("password", true), true));

			if($this->board_password_check($get_data["board_view"]) === false ) {
				throw new Exception(print_language("password_does_not_match"));
			}

		} else {
			if(!$this->is_answer_write($get_data["board_view"])) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		}

        // 프론트 게시글 수정시에 파일 데이터 가공 2020-06-17
        foreach($get_data['board_view']['extraFieldInfo'] as $_languageKey => &$_val) {
            foreach($_val as $_fieldKey => &$_fieldVal){
                if($this->_board['extraFieldInfo']['option'][$_languageKey][$_fieldKey]['type'] == "file") {
                    if(strpos($_fieldVal, "^|^")) {
                        $tmpFileInfo = explode("^|^",$_fieldVal);
                        $_val[$_fieldKey."_fname"] = $tmpFileInfo[0];
                        $_val[$_fieldKey."_oname"] = $tmpFileInfo[1];
                        $_val[$_fieldKey] = $tmpFileInfo[1];
                        unset($tmpFileInfo);
                    }
                }
            }
        }
		return $get_data;
	}

	/*
	 * @override
	 * 게시판 작성
	 *
	 *	@param array $data 데이터
	 *	@param string $mode (write, modify, answer)
	 *
	 *	@return int $no 글번호
	 */
	public function board_write($data = null, $mode = null) {
		if($mode =="write"){
			if(!$this->is_write()) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		} else if($mode == "modify") {
			$no = $this->input->post("no", true);
			$cref = $this->input->post("cref", true);
			$arr_where = array();
			$arr_where[] = array("no", $no);
			$get_data = parent::get_view_board($arr_where);

			if(!isset($get_data["board_view"])) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			if(!$this->is_write()) {
				throw new Exception(print_language("do_not_have_permission"));
			}

			if($this->board_password_check($get_data["board_view"]) === false ) {
				throw new Exception(print_language("password_does_not_match"));
			}

            /*
            Q. 이게 왜 있는지? 로직 파악한 바로는 답글 작성할 수 있는지 유무로 판단되는데 왜 수정 로직에서 해당 condition check 수행?
			주석처리 2020-06-10
            if($cref) { // 답글수정
				if(!$this->is_answer_write($get_data["board_view"])) {
					throw new Exception(print_language("do_not_have_permission"));
				}
			}*/

			if($this->is_modify($get_data["board_view"]) === false) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		} else if($mode =="answer") {
			$no = $this->input->post("no", true);
			$arr_where = array();
			$arr_where[] = array("no", $no);
			$get_data = parent::get_view_board($arr_where);

			if(!isset($get_data["board_view"])) {
				throw new Exception(print_language("no_information_found_in_the_post"));
			}

			if(!$this->is_write()) {
				throw new Exception(print_language("do_not_have_permission"));
			}

			if(!$this->is_answer_write($get_data["board_view"])) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		} else if($mode == "inquire_answer_write" || $mode == "inquire_answer_modify" ){
			if(!$this->is_inquire_answer()) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		}

        if(!isset($data["language"])) {
            if(!is_array($this->_site_language)) {
                $data["language"] = $this->_site_language;
            }
        }
		return parent::board_write($data, $mode);
	}

	/*
	 * @override
	 * 게시판 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function board_delete($arr_where = null) {
		$get_data = parent::get_view_board($arr_where);
		if(!isset($get_data["board_view"])) {
			throw new Exception(print_language("no_information_found_in_the_post"));
		}

		if($this->is_delete($get_data["board_view"]) === false) {
			throw new Exception(print_language("do_not_have_permission"));
		}

		$get_data['board_view']['input_password'] = base64_encode(hash("sha256", $this->input->post("password", true), true));

		if($this->board_password_check($get_data["board_view"]) === false ) {
			throw new Exception(print_language("password_does_not_match"));
		}

		return parent::board_delete($arr_where);
	}


	/*
	 * @override
	 * 게시판 댓글 작성
	 *
	 *	@param array $data 데이터
	 *	@param string $mode (write, modify)
	 *
	 *	@return boolean
	 */
	public function board_comment_write($data = null, $mode = null) {
		if($mode =="write"){
			if(!$this->is_comment_write()) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		} else if($mode == "modify") {
			$arr_where = array();
			$arr_where[] = array("no", $data["no"]);
			$arr_where[] = array("idx", $data["idx"]);

			$get_data = parent::get_view_board_comment($arr_where);
			if(!isset($get_data["board_view_comment"])) {
				throw new Exception(print_language("could_not_find_information_in_the_comment"));
			}

			if(!$this->is_comment_write($get_data["board_view_comment"])) {
				throw new Exception(print_language("do_not_have_permission"));
			}

			if($this->board_password_check($get_data["board_view_comment"]) === false ) {
				throw new Exception(print_language("password_does_not_match"));
			}

			if($this->is_comment_modify($get_data["board_view_comment"]) === false) {
				throw new Exception(print_language("do_not_have_permission"));
			}
		}
		return parent::board_comment_write($data, $mode);
	}


	/*
	 * @override
	 * 게시판 댓글 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function board_comment_delete($arr_where = null) {
		$get_data = parent::get_view_board_comment($arr_where);
		if(!isset($get_data["board_view_comment"])) {
			throw new Exception(print_language("no_information_found_in_the_post"));
		}

		if($this->is_comment_delete($get_data["board_view_comment"]) === false) {
			throw new Exception(print_language("do_not_have_permission"));
		}

		if($this->board_password_check($get_data["board_view_comment"]) === false ) {
			throw new Exception(print_language("password_does_not_match"));
		}

		return parent::board_comment_delete($arr_where);
	}

	/*
	 * @override
	 * 게시판 댓글 삭제
     * 기존 검사로직 미진행 (댓글이 없는 경우 오류 제어)
	 */
	public function board_comment_delete_direct($arr_where = null) {
		//$get_data = parent::get_view_board_comment($arr_where);
        return parent::board_comment_delete($arr_where);
	}

	/*
	 * 조회수 증가
	 *
	 * @param $data array
	 * @param $arr_where array
	 *
	 * @return array
	 */
	public function board_hits($data = null, $arr_where = null) {
		if(defined("_IS_LOGIN")) {
			if($this->_member["userid"]  == $data["userid"]) {
				return $data;
			}
		}

		db_where($arr_where);
		$this->db->set("hit", "hit + 1", false);
		$this->db->update("da_board_". $this->_board["code"]);
		$data["hit"] += 1;

		return $data;
	}

	/*
	 * 비회원글 비밀번호 체크
	 *
	 * @return boolean
	 */
	public function board_password_check($data) {

		if(!$data["userid"]) { //비회원글일때만 비밀번호 검사
			if(!$data["userid"] || !$data["origin_userid"]) { //비회원글일때만 비밀번호 검사
				if(in_array($this->_member["level"], $this->_auth)) {
					return true;
				}
			}

            //비회원글 비밀번호 검사 로직 다시 작성 2020-06-10
            $origin_password = $data["origin_password"]; // 원글 패스워드
            $board_password = $data['password'];

            //$data["password"]가, 1.게시글 비밀번호(DB)를 의미하기도 하고 2.입력 비밀번호(request value)일 때도 있음. -> 설계가 잘못된건지 유지보수 과정에서 꼬인건지 추적불가
            // ->파악 불가능하여 원본로직 고장나지 않도록 스위칭 처리
            $input_password = empty($data["input_password"]) ? $data['password'] : $data["input_password"];

            if($board_password == $input_password) {
                return true;
            } else {
                return false;
            }

            //@deprecated 2020-06-10 Q.아래 로직 왜 필요한지?
			if(!empty($data["password"])){
				//$password = base64_encode(hash("sha256", $this->input->post("password", true), true));
				$password = $data["password"];
				if(!($password == $data["password"] || (isset($data["origin_password"]) && $password == $data["origin_password"]))) {
					return false;
				}
			}
		}

		return true;
	}


	/*
	 * 쓰기권한
	 *
	 * @return boolean
	 */
	public function is_write() {
		if(defined("_IS_LOGIN")) { // 로그인유무
			if(in_array($this->_member["level"], $this->_auth)) {
				return true;
			} else if($this->_member["level"] >= $this->_board["write_auth"])  { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
				return true;
			}
		} else {
			if($this->_board["write_auth"] == "0") { // 권한이 비회원
				return true;
			}
		}
		return false;
	}

	/*
	 * 답변권한
	 *
	 * @return boolean
	 */
	public function is_inquire_answer() {
		if(defined("_IS_LOGIN")) { // 로그인유무
			if(in_array($this->_member["level"], $this->_auth)) {
				return true;
			}
		}
		return false;
	}

	/*
	 * 읽기권한
	 *
	 * @param $data array
	 *
	 * @return boolean
	 */
	public function is_read($data, $location = "view") {
		if($this->_board["yn_display_list"] == "y" && $location == "list") {
			if($data["userid"]) { // 작성글이 회원
				if(defined("_IS_LOGIN")) { // 로그인유무
					if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
						return "y";
					} else if($this->_member["userid"] == $data["userid"] || (isset($data["origin_id"]) && $this->_member["userid"] == $data["origin_id"])){ // 본인이 작성한 글이거나 본인이 작성한 글에 대한 답변글
						return "y";
					} else {
						if($data["is_secret"] != "y") { // 비밀글인경우
							if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						}
					}
				} else {
					if($data["is_secret"] != "y") {
						if($this->_board["read_auth"] == "0") {
							return "y";
						}
					}

					if($data["origin_no"] && $data["origin_id"] == "") { // 답글이면서 비회원일경우
						return "s";
					}
				}
			} else {
				if(defined("_IS_LOGIN")) { // 로그인유무
					if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
						return "y";
					} else {
						if($data["is_secret"] != "y") { // 비밀글인경우
							if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						} else {
							return "s";
						}
					}
				} else {
					if($data["is_secret"] != "y") {
						if($this->_board["read_auth"] == "0") {
							return "y";
						}
					} else {
						return "s";
					}
				}
			}
		} else {
			if($data["userid"]) { // 작성글이 회원
				if(defined("_IS_LOGIN")) { // 로그인유무
					if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
						return "y";
					} else if($this->_member["userid"] == $data["userid"] || (isset($data["origin_id"]) && $this->_member["userid"] == $data["origin_id"])){ // 본인이 작성한 글이거나 본인이 작성한 글에 대한 답변글
						return "y";
					}else {
						if($data["is_secret"] != "y") { // 비밀글인경우
							if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						}
					}
				}else {
					if($data["is_secret"] != "y") { // 비밀글인이 아닌 경우
						if(isset($this->_memeber["level"])) {
							if($this->_member["level"] >= $this->_board["read_auth"]) {
								return "y";
							}
						}else{
							if($this->_board["read_auth"] == "0") {
								return "y";
							}
						}
					}else {
						return "s";
					}
				}
			}else{
				if(defined("_IS_LOGIN")) { // 로그인유무
					if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
						return "y";
					} else {
						if($data["is_secret"] != "y") { // 비밀글인경우
							if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						} else {
							return "s";
						}
					}
				} else {
					if($data["is_secret"] != "y") {
						if($this->_board["read_auth"] == "0") {
							return "y";
						}
					} else {
						return "s";
					}
				}
			}
		}
		return false;
	}

	/*
	 * 수정권한
	 *
	 * @param $data array
	 *
	 * @return boolean
	 */
	public function is_modify($data) {
		if($data["userid"]) { // 작성글이 회원
			if(defined("_IS_LOGIN")) { // 로그인유무
				if(in_array($this->_member["level"], $this->_auth)) {
					return "y";
				}/* else if($this->_member["userid"] == $data["userid"] && $this->_member["language"] == $data["language"]){ // 본인이 작성한 글
					return "y";
				}*/
				else if($this->_member["level"] >= $this->_board["write_auth"])  { // 회원의 권한이 게시판 쓰기권한보다 높거나 같은지 체크
					return "y";
				}
			}
		} else {
			if(defined("_IS_LOGIN")) { // 로그인유무
				if(in_array($this->_member["level"], $this->_auth)) {
					return "y";
				}
			}
			return "s";
		}
		return false;
	}

	public function is_delete($data) {
		return $this->is_modify($data);
	}

	/*
	 * 답글쓰기권한
	 *
	 * @param $data array
	 *
	 * @return boolean
	 */
	public function is_answer_write($data) {
		if($this->_board["tree"] == "y") {
			if($data["clevel"] == "0") {
				if($data["userid"]) {
					if(defined("_IS_LOGIN")) { // 로그인유무
						if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
							return "y";
						} else {
							if($this->_member["level"] >= $this->_board["anwrite_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						}
					} else {
						if($this->_board["anwrite_auth"] == "0") {
							return "y";
						}
					}
				} else {
					if(defined("_IS_LOGIN")) { // 로그인유무
						if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
							return "y";
						} else {
							if($this->_member["level"] >= $this->_board["anwrite_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						}
					} else {
						if($this->_board["anwrite_auth"] == "0") {
							return "y";
						}
					}
				}
			}
		}
		return false;
	}

	/*
	 * 댓글쓰기권한
	 *
	 * @return boolean
	 */
	public function is_comment_write() {
		if($this->_board["comment"] == "y") { // 게시판 댓글 유무
			if(defined("_IS_LOGIN")) { // 로그인유무
				if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
					return true;
				} else {
					if($this->_member["level"] >= $this->_board["comment_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
						return true;
					}
				}
			} else {
				if($this->_board["comment_auth"] == "0") {
					return true;
				}
			}
		}
		return false;
	}

	/*
	 * 댓글수정권한
	 *
	 * @param $data array
	 *
	 * @return boolean
	 */
	public function is_comment_modify($data) {
		if($data["userid"]) { // 작성글이 회원
			if(defined("_IS_LOGIN")) { // 로그인유무

                /*
                    2020-06-26 프론트에서는 관리자여도 본인글만 수정하도록 정책
                    if(in_array($this->_member["level"], $this->_auth)) {
                        return "y";
                    } else
                */
				if($this->_member["userid"] == $data["userid"]){ // 본인이 작성한 글
					return "y";
				}
			}
		} else {
            /*
                2020-06-26 프론트에서는 관리자여도 본인글만 수정하도록 정책
                if(defined("_IS_LOGIN")) { // 로그인유무
                    if(in_array($this->_member["level"], $this->_auth)) {
                        return "y";
                    }
                }
            */
			return "s";
		}
		return false;
	}

	public function is_comment_delete($data) {
		return $this->is_comment_modify($data);
	}

	/*
	 * 리스트노출 체크
	 *
	 * @return boolean
	 */
	public function is_display_list() {
		if($this->_board["yn_display_list"] == "y") {
			return true;
		} else {
			if(defined("_IS_LOGIN")) { // 로그인유무
				if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
					return true;
				}
			}
		}

		return false;
	}

	public function board_comment_password_check($arr_where, $password) {
		$this->db->select("IF(password = '". $password ."', 'y', 'n') yn_password", false);
		$this->db->from($this->_comment_table);
		db_where($arr_where);
		$result = $this->db->get()->last_row("array");
		if(!$result) {
			throw new Exception(print_language("could_not_find_information_in_the_comment"));
		}
		return $result;
	}


	/*
	 * list 비밀글 이미지 표시
	 *
	 * @param $data array
	 *
	 * @return boolean
	 */
	public function display_read($data) {
		if($this->_board["yn_display_list"] == "y") {
			if($data["userid"]) { // 작성글이 회원
				if(defined("_IS_LOGIN")) { // 로그인유무
					/*
                        프론트에서는 관리자여도 권한 확인을 따라야합니다 2020-06-26
                        if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
                            return "y";
                        } else
                    */
                    if(($this->_member["userid"] == $data["userid"] || (isset($data["origin_id"]) && $this->_member["userid"] == $data["origin_id"])) && $this->_member["language"] == $data["language"]){ // 본인이 작성한 글이거나 본인이 작성한 글에 대한 답변글
						return "y";
					} else {
						if($data["is_secret"] != "y") { // 비밀글인경우
							if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
								return "y";
							}
						} else {
							return "s";
						}
					}
				} else {
					if($data["is_secret"] != "y") {
						if($this->_board["read_auth"] == "0") {
							return "y";
						}
					} else {
						return "s";
					}
				}
			} else {
				if(defined("_IS_LOGIN")) { // 로그인유무
                    /*
                        관리자도 프론트에서는 권한 확인을 해야함다 2020-06-26
                        if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
                            return "y";
                        } else {
                        }
                    */

                    if($data["is_secret"] != "y") { // 비밀글인경우
                        if($this->_member["level"] >= $this->_board["read_auth"]) { // 회원의 권한이 게시판 권한보다 높거나 같은지 체크
                            return "y";
                        }
                    } else {
                        return "s";
                    }

				} else {
					if($data["is_secret"] != "y") {
						if($this->_board["read_auth"] == "0") {
							return "y";
						}
					} else {
						return "s";
					}
				}
			}
		} else {
			if($data["userid"]) { // 작성글이 회원
				if(defined("_IS_LOGIN")) { // 로그인유무

                    /*
                        관리자도 프론트에서는 권한체크를 따라야함다 2020-06-26
                        if(in_array($this->_member["level"], $this->_auth)) { // 관리자 권한
                            return "y";
                        } else
                    */

                    if(($this->_member["userid"] == $data["userid"] || (isset($data["origin_id"]) && $this->_member["userid"] == $data["origin_id"])) && $this->_member["language"] == $data["language"]){ // 본인이 작성한 글이거나 본인이 작성한 글에 대한 답변글
						return "y";
					}
				}
			}
		}
		return false;
	}

	public function board_file_delete($data) {
		return parent::board_file_delete($data);
	}

	public function board_global_delete($data) {
		return parent::board_global_delete($data);
	}
}