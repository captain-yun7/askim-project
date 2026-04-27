<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board_model extends CI_Model {
	protected $_board; // 게시판 정보
	protected $_table; // 게시판 테이블명
	protected $_comment_table = "da_board_comment"; // 댓글 테이블

	const DEFAULT_BOARD = "1"; // 일반
	const QNA_BOARD = "2"; // 문의
	const GOOGLE_API_KEY = "AIzaSyCsyATWQjrmTmGhMA3d0PvrD6ZMgGUelzo";

	public function initialize($code = null) {
		if(!$code) {
			$code = $this->input->get_post("code", true);
		}
		$this->_table = "da_board_". $code;

		if(!$this->db->table_exists($this->_table)) {
			throw new Exception(print_language("no_bulletin_board_information_found"));
		}

		$this->db->where("code", $code);
		$this->_board = $this->db->get("da_board_manage")->last_row("array");
		if(!isset($this->_board)) {
			throw new Exception(print_language("no_bulletin_board_information_found"));
		}

        // 다국어 설정 추가
        if(ib_isset($this->_site_language)) {
            $this->_board["global"] = $this->get_board_global($this->_board["code"]);
            if(!is_array($this->_site_language)) {
                $this->_board["name"] = $this->_board["global"][$this->_site_language]["name"];
            }
        }
	}

	/*
	 * 게시판 정보 가져오기
	 *
	 *	@return array
	 */
	public function get_board() {
		$this->_board["DEFAULT_BOARD"] = self::DEFAULT_BOARD;
		$this->_board["QNA_BOARD"] = self::QNA_BOARD;
		if(!empty($this->_board['extraFieldInfo'])){
			$this->_board['extraFieldInfo'] = json_decode($this->_board['extraFieldInfo'], true);
		}
		return $this->_board;
	}

	/*
	 * 게시판 nav정보 가져오기
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 *
	 *	@return array
	 */
	public function get_board_manege($arr_where = null, $array_like = null, $limit = null, $offset = null, $arr_orderby = null) {
		$get_data = array();

		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			foreach($arr_orderby as $key => $value) {
				$this->db->order_by($value);
			}
		}

		$this->db->limit($limit, $offset);
		$this->db->from("da_board_manage");

		$result = $this->db->get();

		if($result->result_id->num_rows) {
			$get_data["board_manage_list"] = $result->result_array();
		}

		//게시판 리스트 총 로우갯수 가져오기
		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$this->db->from("da_board_manage");
		$get_data["total_rows_manage"] = $this->db->count_all_results();

		return $get_data;
	}

	/*
	 * 게시판 리스트정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *
	 *	@return array
	 */
	public function get_list_board($arr_where = null, $arr_like = null, $limit = null, $offset = null) {
		$get_data = array();  // return

		// 공지사항 쿼리 시작
		$arr_include = array(
			"'". $this->_board["code"] ."' AS code",
			"BOARD.no",
			"BOARD.language",
			"BOARD.userid",
			"BOARD.name",
			"BOARD.password",
			"BOARD.title",
			"BOARD.content",
			"BOARD.fname",
			"BOARD.oname",
			"BOARD.userip",
			"BOARD.fixed",
			"BOARD.cref",
			"BOARD.clevel",
			"BOARD.cstep",
			"BOARD.hit",
			"BOARD.regdt",
			"BOARD.updatedt",
			"BOARD.origin_no",
			"BOARD.email",
			"BOARD.mobile",
			"BOARD.video_url",
			"BOARD.video_thumbnail_url",
			"BOARD.is_secret",
			"BOARD.upload_path",
			"BOARD.answer_status",
			"BOARD.answer_regdt",
			"BOARD.answer_updatedt",
			"BOARD.answer_title",
			"BOARD.answer_content",
			"BOARD.answer_userid",
			"BOARD.answer_name",
            "BOARD.preface",
            "BOARD.thumbnail_image",
			"COMMENT.comment",
			"DATE_FORMAT(BOARD.regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(BOARD.updatedt, '%Y-%m-%d') AS updatedt_date",
		);

		$this->db->select($arr_include);
		$this->db->from($this->_table ." AS BOARD");
		$this->db->join("(SELECT code, no, count(no) AS comment FROM da_board_comment group by code, no) AS COMMENT", "'". $this->_board["code"] ."' = COMMENT.code AND BOARD.no = COMMENT.no", "LEFT");
		$this->db->where("fixed >=", "1");

		if(isset($arr_where)) {
			foreach($arr_where as $key => $value) {
				if($key == 0) {
					$this->db->where("BOARD.".$value[0]." = ", $value[1]);
				}else{
					$this->db->where($value[0]." = ", $value[1]);
				}
			}
		}

		$this->db->order_by("fixed", "asc"); // 고정글 오름차순으로 출력

		$get_data["notice_list"] = $this->db->get()->result_array();

		// 공지사항 쿼리 끝

		// 2020-03-05 Inbet Matthew 공지사항 썸네일/첨부파일 가져오기 추가
        if($this->_board["files"] == "y" || $this->_board["thumbnail"] == "y") {
            if(isset($get_data["notice_list"])) {
                foreach($get_data["notice_list"] as $key => $val) {
                    $file_data = $this->get_board_file($val["no"], $val["code"]);
                    $get_data["notice_list"][$key]["board_file"] = $file_data;

                    // 썸네일 이미지 설정
                    if(!ib_isset($val["thumbnail_image"])) {
                        if(isset($file_data["thumbnail"])) {
                            $get_data["notice_list"][$key]["thumbnail_image"] = $file_data["thumbnail"][0]["fname"];
                        } elseif(isset($file_data["file"])) {
                            foreach($file_data["file"] as $file) {
                                if(preg_match("/\.(gif|jpg|bmp|png)$/i",$file["fname"])) $get_data["notice_list"][$key]["thumbnail_image"] = $file["fname"];
								break;
                            }
                        }
                    }
                }
            }
        }
		//Matthew End

		$tno = [];
		if($this->_board['code'] === 'gallery') {
			$tag = $this->input->get('tag', true);
			if(isset($tag)) {
				$result = $this->db->get_where('da_board_gallery_tags', ['tag' => $tag]);//->result_array();
				if($result->num_rows() > 0) {
					foreach($result->result_array() as $value) {
						$tno[] = $value['bno'];
					}
				}
			}
		}

		// 게시판 쿼리 시작
		$arr_include = array(
			"'". $this->_board["code"] ."' AS code",
			"BOARD.no",
			"BOARD.language",
			"BOARD.userid",
			"BOARD.name",
			"BOARD.password",
			"BOARD.title",
			"BOARD.content",
			"BOARD.fname",
			"BOARD.oname",
			"BOARD.userip",
			"BOARD.fixed",
			"IF(BOARD_ORIGIN.no = 0 OR BOARD_ORIGIN.no IS NULL, BOARD.no, BOARD.cref) as cref",
			"IF(BOARD_ORIGIN.no = 0 OR BOARD_ORIGIN.no IS NULL, 0, BOARD.clevel) as clevel",
			"IF(BOARD_ORIGIN.no = 0 OR BOARD_ORIGIN.no IS NULL, 10, BOARD.cstep) as cstep",
			//"BOARD.cref",
			//"BOARD.clevel",
			//"BOARD.cstep",
			"BOARD.hit",
			"BOARD.regdt",
			"BOARD.updatedt",
			"BOARD.origin_no",
			"BOARD_ORIGIN.no AS origin_no",
			"BOARD_ORIGIN.userid AS origin_id",
			"BOARD_ORIGIN.password AS origin_password",
			"BOARD.is_secret",
			"BOARD.email",
			"BOARD.mobile",
			"BOARD.video_url",
			"BOARD.video_thumbnail_url",
			"BOARD.upload_path",
			"BOARD.answer_status",
			"BOARD.answer_regdt",
			"BOARD.answer_updatedt",
			"BOARD.answer_title",
			"BOARD.answer_content",
			"BOARD.answer_userid",
			"BOARD.answer_name",
            "BOARD.preface",
            "BOARD.thumbnail_image",
			"BOARD.extraFieldInfo",
			"COMMENT.comment",
			"DATE_FORMAT(BOARD.regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(BOARD.updatedt, '%Y-%m-%d') AS updatedt_date",
		);
		$arr_where[] = ["fixed", 0 ];
		if(isset($arr_where)) {
			foreach($arr_where as $key => $value) {
				$arr_where[$key][0] = "BOARD.". $value[0];
			}
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			foreach($arr_like as $key => $value) {
				if(strpos($value[0], "CONCAT") === false){
					$arr_like[$key][0] = "BOARD.". $value[0];
				}
			}
			db_like($arr_like);
		}

		if(isset($this->_board["sort_type"])) {
			if($this->_board["sort_type"] == "regdt") {
				//$this->db->order_by("BOARD.cref", "DESC");
				//$this->db->order_by("BOARD.clevel", "ASC");
				//$this->db->order_by("BOARD.cstep", "ASC");
				$this->db->order_by("cref", "DESC");
				$this->db->order_by("clevel", "ASC");
				$this->db->order_by("cstep", "ASC");
			} else {
				$this->db->order_by($this->_board["sort_type"], "DESC");
			}
		} else {
			//$this->db->order_by("BOARD.cref", "DESC");
			//$this->db->order_by("BOARD.clevel", "ASC");
			//$this->db->order_by("BOARD.cstep", "ASC");
			$this->db->order_by("cref", "DESC");
			$this->db->order_by("clevel", "ASC");
			$this->db->order_by("cstep", "ASC");
		}

		if(count($tno) > 0) $this->db->where_in('BOARD.no', $tno);

		$this->db->select($arr_include);
		$this->db->from($this->_table." AS BOARD");
		$this->db->join($this->_table." AS BOARD_ORIGIN", "BOARD.cref = BOARD_ORIGIN.no", "LEFT");
		$this->db->join("(SELECT code, no, count(no) AS comment FROM da_board_comment group by code, no) AS COMMENT", "'". $this->_board["code"] ."' = COMMENT.code AND BOARD.no = COMMENT.no", "LEFT");
		$this->db->limit($limit, $offset);
		$result = $this->db->get();
//echo $this->db->last_query();
		if($result->num_rows()) {
			$get_data["board_list"] = $result->result_array();
		}

		// 썸네일/첨부파일 가져오기
        if($this->_board["files"] == "y" || $this->_board["thumbnail"] == "y") {
            if(isset($get_data["board_list"])) {
                foreach($get_data["board_list"] as $key => $val) {
                    $file_data = $this->get_board_file($val["no"], $val["code"]);
                    $get_data["board_list"][$key]["board_file"] = $file_data;

                    // 썸네일 이미지 설정
                    if(!ib_isset($val["thumbnail_image"])) {
                        if(isset($file_data["thumbnail"])) {
                            $get_data["board_list"][$key]["thumbnail_image"] = $file_data["thumbnail"][0]["fname"];
                        } elseif(isset($file_data["file"])) {
                            foreach($file_data["file"] as $file) {
                                if(preg_match("/\.(gif|jpg|bmp|png)$/i",$file["fname"])) $get_data["board_list"][$key]["thumbnail_image"] = $file["fname"];
								break;
                            }
                        }
                    }
                }
            }
        }

		//게시판 리스트 총 로우갯수 가져오기
		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}
		$this->db->from($this->_table." AS BOARD");
		$this->db->join($this->_table." AS BOARD_ORIGIN", "BOARD.cref = BOARD_ORIGIN.no", "LEFT");
		$get_data["total_rows"] = $this->db->count_all_results();

		// 게시판 쿼리 끝
		return $get_data;
	}

	/*
	 * 게시판 댓글리스트정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *
	 *	@return array
	 */
	public function get_list_board_comment($arr_where = null, $arr_like = null, $limit = null, $offset = null) {
		$get_data = array();

		$arr_include = array(
			"code",
			"no",
			"idx",
			"userid",
			"name",
			"content",
			"fname",
			"oname",
			"is_secret",
			"userip",
			"regdt",
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
		);

		$this->db->select($arr_include);
		$this->db->from($this->_comment_table);


		$arr_where[] = array("code", $this->_board["code"]);
		db_where($arr_where);

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$this->db->order_by("idx", "asc");
		$this->db->limit($limit, $offset);
		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["board_list_comment"] = $result->result_array();
		}

		$this->db->from($this->_comment_table);
		db_where($arr_where);

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$get_data["total_rows_comment"] = $this->db->count_all_results();

		return $get_data;
	}

	/*
	 * 게시판 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *
	 * @return array
	 */
	public function get_view_board($arr_where = null) {
		$get_data = array();  // return
		// 게시판 쿼리 시작
		$arr_include = array(
			"'". $this->_board["code"] ."' AS code",
			"BOARD.no",
			"BOARD.language",
			"BOARD.userid",
			"BOARD.name",
			"BOARD.password",
			"BOARD.title",
			"BOARD.content",
			"BOARD.fname",
			"BOARD.oname",
			"BOARD.userip",
			"BOARD.fixed",
			"BOARD.cref",
			"BOARD.clevel",
			"BOARD.cstep",
			"BOARD.hit",
			"BOARD.regdt",
			"BOARD.updatedt",
			"BOARD.is_secret",
			"BOARD.email",
			"BOARD.mobile",
			"BOARD.video_url",
			"BOARD.video_thumbnail_url",
			"BOARD.upload_path",
			"BOARD.answer_status",
			"BOARD.answer_regdt",
			"BOARD.answer_updatedt",
			"BOARD.answer_title",
			"BOARD.answer_content",
			"BOARD.answer_userid",
			"BOARD.answer_name",
            "BOARD.preface",
            "BOARD.thumbnail_image",
			"BOARD_ORIGIN.no AS origin_no",
			"BOARD_ORIGIN.userid AS origin_id",
			"BOARD_ORIGIN.password AS origin_password",
			"DATE_FORMAT(BOARD.regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(BOARD.updatedt, '%Y-%m-%d') AS updatedt_date",
			"BOARD.extraFieldInfo",
			"BOARD.use_seo",
			"BOARD.seo_title",
			"BOARD.seo_author",
			"BOARD.seo_description",
			"BOARD.seo_keywords",
		);

		if(isset($arr_where)) {
			foreach($arr_where as $key => $value) {

				$arr_where[$key][0] = "BOARD.". $value[0];
			}
			db_where($arr_where);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table." AS BOARD");
		$this->db->join($this->_table." AS BOARD_ORIGIN", "BOARD.cref = BOARD_ORIGIN.no", "LEFT");
		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["board_view"] = $result->last_row("array");

			if($this->_board["yn_video"] == "y") {
				$video_data = $this->get_video_thumbnail_url($get_data["board_view"]["video_url"]);
				$get_data["board_view"]["video_html"] = $video_data["video_html"];
			}

			if($this->_board["files"] == "y" || $this->_board["thumbnail"] == "y") {
				$file_data = $this->get_board_file($get_data["board_view"]["no"], $get_data["board_view"]["code"]);
				$get_data["board_view"]["board_file"] = $file_data;
			}

			$get_data["board_view"]["extraFieldInfo"] = json_decode($get_data["board_view"]["extraFieldInfo"], true);
		}

		if(empty($get_data['board_view']['origin_password'])) {
			unset($get_data['board_view']['origin_password']);
		}

		return $get_data;
	}

	/*
	 * 게시판 댓글정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return array
	 */
	public function get_view_board_comment($arr_where = null) {
		$get_data = array();

		$arr_include = array(
			"code",
			"no",
			"idx",
			"userid",
			"name",
			"password",
			"content",
			"fname",
			"oname",
			"is_secret",
			"userip",
			"regdt",
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_comment_table);
		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["board_view_comment"] = $result->last_row("array");
		}

		return $get_data;
	}

	/**
	 * 답변글 삭제
	 *
	 * @param string $code 게시판 코드
	 * @param int $no 게시글 번호
	 *
	 * @return bool 성공여부
	 */
	public function board_inquire_answer_delete($code, $no) {
		$this->_table = "da_board_". $code;
		$data = array(
			'answer_title' => null,
			'answer_content' => null,
			'answer_userid' => null,
			'answer_name' => null,
			'answer_status' => 'n',
			'answer_regdt' => null,
		);
		$get_data = table_data_match($this->_table, $data);
		$result = $this->db->update($this->_table, $get_data, array("no" => $no));

		return $result;
	}
	/*
	 * 게시판 작성
	 *
	 *	@param array $data 데이터
	 *	@param string $mode (write, modify, answer)
	 *
	 *	@return int $no 글번호
	 */
	public function board_write($data = null, $mode = null) {
		if(!isset($mode)) {
			return false;
		}

		$no = $data["no"];

		$set_data = array();
		if(in_array($mode, array("write", "answer", "modify"))) {
			$set_data["language"] = $data["language"];
			$set_data["title"] = $data["title"];
			$set_data["name"] = $data["name"];
			$set_data["password"] = $data["password"];
			$set_data["content"] = $data["content"];
			$set_data["fname"] = $data["fname"];
			$set_data["is_secret"] = $data["is_secret"];
			$set_data["upload_path"] = $data["upload_path"];
			$set_data["mobile"] = $data["mobile"];
			$set_data["email"] = $data["email"];
			$set_data["video_url"] = $data["video_url"];
			$set_data = array_merge($set_data, $this->get_video_thumbnail_url($data["video_url"]));
			$set_data["preface"] = $data["preface"];
			$set_data["fname"] = $data["fname"];
			$set_data['use_seo'] = $data['use_seo'];
			$set_data['seo_title'] = $data['seo_title'];
			$set_data['seo_author'] = $data['seo_author'];
			$set_data['seo_description'] = $data['seo_description'];
			$set_data['seo_keywords'] = $data['seo_keywords'];
			// related posts (gallery 테이블에만 컬럼 존재 - table_data_match가 자동 필터)
			$set_data['related_no1'] = !empty($data['related_no1']) ? intval($data['related_no1']) : null;
			$set_data['related_no2'] = !empty($data['related_no2']) ? intval($data['related_no2']) : null;
			// 수정 시 이전 extraFieldInfo 가져오기
			if($mode == "write") {
				$extraFieldInfo = array();
			}else if($mode == "modify") {
				list($extraFieldInfo) = $this->db->select("extraFieldInfo")->get_where($this->_table, array("no" => $no))->last_row("array");
				$extraFieldInfo = json_decode($extraFieldInfo, true);
			}

            if(in_array($mode, array("write", "modify"))){
				// set extraFieldInfo
				foreach($this->_board["extraFieldInfo"]["use"][$set_data["language"]] as $columnKey => $columnVal) {
					if($this->_board["extraFieldInfo"]["option"][$set_data["language"]][$columnKey]["type"] == 'file') {
						$extraFieldInfo[$set_data["language"]][$columnKey] = $data[$columnKey."_".$set_data["language"]."_fname"]."^|^".$data[$columnKey."_".$set_data["language"]."_oname"];
                        if($extraFieldInfo[$set_data["language"]][$columnKey] === "^|^"){
                            // 업로드 파일이 없음에도 구분자 처리하여 없는 파일 처리에 지장이 있음, 보정처리 2020-06-17
                            $extraFieldInfo[$set_data["language"]][$columnKey] = "";
                        }
                    }else{
						$extraFieldInfo[$set_data["language"]][$columnKey] = $data[$columnKey."_".$set_data["language"]];
					}


				}
				$set_data["extraFieldInfo"] = json_encode($extraFieldInfo, JSON_UNESCAPED_UNICODE);
			}

		} else if(in_array($mode, array("inquire_answer_write", "inquire_answer_modify"))) {
			$set_data["answer_title"] = $data["answer_title"];
			$set_data["answer_content"] = $data["answer_content"];
		}

		if (($mode == 'write' || $mode == 'answer') && isset($set_data['password']) && $set_data["password"]) {
			$set_data["password"] = base64_encode(hash("sha256", $set_data["password"], true));
		}

		if($mode == "write" || $mode == "answer") { //게시판 글쓰기 // 답글
			if(_CONNECT_PAGE == "ADMIN") {
				$set_data["userid"] = $this->_admin_member["userid"];
			} else {
				if(defined("_IS_LOGIN")) {
					$set_data["userid"] = $this->_member["userid"];
				}
			}

			$set_data["regdt"] = date('Y-m-d H:i:s');
			$set_data["userip"] = $this->input->ip_address();
			$set_data["fixed"] = array_key_exists("fixed", $data) ? $data["fixed"] : "0";
			$set_data["hit"] = 0;

			if($mode == "write") {
				$this->db->set("cref", "(SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '". $this->_table ."')", false);
				$set_data["answer_status"] = "n"; // 답변대기
			} else {
				$set_data["cref"] = $data["cref"];
			}

			$get_data = table_data_match($this->_table, $set_data);

			$this->db->set("clevel", "(SELECT IFNULL(MAX(a.clevel) + 1, 0) FROM ". $this->_table ." a WHERE a.no = '". $no ."')", false);
			$this->db->set("cstep",  "(SELECT IFNULL(MAX(a.cstep) + 1, 0) FROM ". $this->_table ." a WHERE a.cref = '". $no ."')", false);

			$result = $this->db->insert($this->_table, $get_data);

			if($result) {
				$no = $this->db->insert_id();
			} else {
				return false;
			}
		} else if($mode == "modify"){ // 수정
			$set_data["fixed"] = array_key_exists("fixed", $data) ? $data["fixed"] : "0";
			$set_data["updatedt"] = date('Y-m-d H:i:s');
			$get_data = table_data_match($this->_table, $set_data);
			$result = $this->db->update($this->_table, $get_data, array("no" => $no));
		} else if($mode == "inquire_answer_write") { // 문의답변 등록
			if(_CONNECT_PAGE == "ADMIN") {
				$set_data["answer_userid"] = $this->_admin_member["userid"];
				$set_data["answer_name"] = $this->_admin_member["name"];
			} else {
				if(defined("_IS_LOGIN")) {
					$set_data["answer_userid"] = $this->_member["userid"];
					$set_data["answer_name"] = $this->_member["name"];
				}
			}
			$set_data["answer_status"] = "y"; // 답변완료
			$set_data["answer_regdt"] = date('Y-m-d H:i:s');

			if($this->_board["yn_send_mail"] == "y111") {
				list($email, $upload_path, $fname, $oname, $qustion_title, $qustion_content) = $this->db->select("email AS '0', upload_path AS '1', fname AS '2', oname AS '3', title AS '4', content AS '5'")->get_where($this->_table, array("no" => $no))->last_row("array");

				$arr_mail = array(
					"to" => $email,
					"subject" => $data["answer_title"]
				);

				$arr_message = array(
					"answer_title" => $data["answer_title"],
					"answer_content" => $data["answer_content"],
					"qustion_title" => $qustion_title,
					"qustion_content" => $qustion_content
				);

				if($fname) {
					$arr_mail["attach"][] = array(
						"fname" => "/board/". $upload_path."/".$fname,
						"oname" => $oname,
						"disposition" => "",
						"mime" => ""
					);
				}

				$this->load->library("emailsend");
				$this->emailsend->get_mail_form($this->_board["mail_form"]);
				$this->emailsend->message_bind($arr_message);
				$this->emailsend->mail_form($arr_mail);
				if(!$this->emailsend->send()) {
					throw new Exception(print_language("please_try_again"));
				}
			}

			$get_data = table_data_match($this->_table, $set_data);
			$result = $this->db->update($this->_table, $get_data, array("no" => $no));
		} else if($mode == "inquire_answer_modify") { // 문의답변 수정
			if(_CONNECT_PAGE == "ADMIN") {
				$set_data["answer_userid"] = $this->_admin_member["userid"];
				$set_data["answer_name"] = $this->_admin_member["name"];
			} else {
				if(defined("_IS_LOGIN")) {
					$set_data["answer_userid"] = $this->_member["userid"];
					$set_data["answer_name"] = $this->_member["name"];
				}
			}
			$set_data["answer_updatedt"] = date('Y-m-d H:i:s');

			if($this->_board["yn_send_mail"] == "y") {
				list($email, $upload_path, $fname, $oname, $qustion_title, $qustion_content) = $this->db->select("email AS '0', upload_path AS '1', fname AS '2', oname AS '3', title AS '4', content AS '5'")->get_where($this->_table, array("no" => $no))->last_row("array");

				$arr_mail = array(
					"to" => $email,
					"subject" => $data["answer_title"]
				);

				$arr_message = array(
					"answer_title" => $data["answer_title"],
					"answer_content" => $data["answer_content"],
					"qustion_title" => $qustion_title,
					"qustion_content" => $qustion_content
				);

				if($fname) {
					$arr_mail["attach"][] = array(
						"fname" => "/board/". $upload_path."/".$fname,
						"oname" => $oname,
						"disposition" => "",
						"mime" => ""
					);
				}

				$this->load->library("emailsend");
				$this->emailsend->get_mail_form($this->_board["mail_form"]);
				$this->emailsend->message_bind($arr_message);
				$this->emailsend->mail_form($arr_mail);
				if(!$this->emailsend->send()) {
					throw new Exception(print_language("please_try_again"));
				}
			}

			$get_data = table_data_match($this->_table, $set_data);

			$result = $this->db->update($this->_table, $get_data, array("no" => $no));
		} else {
			return false;
		}

        // 썸네일/첨부파일 저장 시작
        if($mode == "write" || $mode == "modify" || $mode == "answer") {
            if($this->_board["file_count"] > 0 && $this->_board["files"] == "y") {
                $result = $this->db->delete("da_board_file", array("master_no" => $no, "type" => "file", "code" => $this->_board["code"]));
                if($result) {
                    $set_file_data = array();
                    for($i = 1; $this->_board["file_count"] >= $i; $i++) {
                        if(ib_isset($data["file".$i."_fname"])) {
                            $set_file_data[$i]["fname"] = $data["file".$i."_fname"];
                            $set_file_data[$i]["oname"] = $data["file".$i."_oname"];
                            $set_file_data[$i]["type"] = "file";
                            $set_file_data[$i]["master_no"] = $no;
                            $set_file_data[$i]["code"] = $this->_board["code"];
                            $get_file_data = table_data_match("da_board_file", $set_file_data[$i]);
                            $this->db->insert("da_board_file", $get_file_data);
                        }
                    }
                }
            }
            if($this->_board["thumbnail_count"] > 0 && $this->_board["thumbnail"] == "y") {
                $result = $this->db->delete("da_board_file", array("master_no" => $no, "type" => "thumbnail", "code" => $this->_board["code"]));
                if($result) {
                    $set_thumbnail_data = array();
                    for($i = 1; $this->_board["thumbnail_count"] >= $i; $i++) {
                        if(ib_isset($data["thumbnail".$i."_fname"])) {
                            $set_thumbnail_data[$i]["fname"] = $data["thumbnail".$i."_fname"];
                            $set_thumbnail_data[$i]["oname"] = $data["thumbnail".$i."_oname"];
                            $set_thumbnail_data[$i]["type"] = "thumbnail";
                            $set_thumbnail_data[$i]["master_no"] = $no;
                            $set_thumbnail_data[$i]["code"] = $this->_board["code"];
                            $get_thumbnail_data = table_data_match("da_board_file", $set_thumbnail_data[$i]);
                            $this->db->insert("da_board_file", $get_thumbnail_data);
                            if(ib_isset($data["thumbnail".$i."_image"])) {
			                    $this->db->update($this->_table, array("thumbnail_image" => $data["thumbnail".$i."_image"]), array("no" => $no));
                            }
                        }
                    }
                }
            }
        }

        //-- 썸네일/첨부파일 저장 끝
		return $no;
	}

	/*
	 * 게시판 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function board_delete($arr_where = null) {
		if(!isset($arr_where)) {
			return false;
		}

		db_where($arr_where);

		return $this->db->delete($this->_table);
	}

    /**
     * 게시판 파일 삭제
     *
     *  @param array $board_data 게시판 정보
     *
     *  @return boolean
     */
    public function board_file_delete($board_data = null) {
        if(!isset($board_data)) {
            return false;
        }

        $arr_where = array();
        $arr_where[] = array("master_no", $board_data["master_no"]);
        $arr_where[] = array("code", $board_data["code"]);

        db_where($arr_where);
        return $this->db->delete("da_board_file");
    }

    /**
     * 게시판 다국어 설정 삭제
     *
     *  @param array $arr_where 조건
     *
     *  @return boolean
     */
    public function board_global_delete($board_data = null) {
        if(!isset($board_data)) {
            return false;
        }

        $arr_where = array();
        $arr_where[] = array("code", $board_data["code"]);

        db_where($arr_where);
        return $this->db->delete("da_board_global");
    }

	/*
	 * 게시판 댓글 작성
	 *
	 *	@param array $data 데이터
	 *	@param string $mode (write, modify)
	 *
	 *	@return boolean
	 */
	public function board_comment_write($data = null, $mode = null) {
		$set_data = array();
		$set_data["name"] = htmlspecialchars($data["name"]);
		$set_data["content"] = (htmlspecialchars($data["content"]));
		$set_data["fname"] = htmlspecialchars($data["file_fname"]);
		$set_data["oname"] = htmlspecialchars($data["file_oname"]);
		//debug($set_data['content']);
		//debug($data["content"]);
		//exit;
		if($mode == "write") {
			if(defined("_IS_LOGIN")) {
				$set_data["userid"] = $this->_member["userid"];
			} else if(defined("_IS_ADMIN_LOGIN")) {
				$set_data["userid"] = $this->_admin_member["userid"];
			} else {
				$set_data["password"] = $data["password"];
			}

			$set_data["code"] = $this->_board["code"];
			$set_data["no"] = $data["no"];
			$set_data["regdt"] = date('Y-m-d H:i:s');
			$set_data["userip"] = $_SERVER["REMOTE_ADDR"];
			$get_data = table_data_match($this->_comment_table, $set_data);

			$this->db->set("idx", "(SELECT IFNULL(MAX(a.idx) + 1, 0) FROM ". $this->_comment_table ." a)", false);
			$result = $this->db->insert($this->_comment_table, $get_data);
			if($result) {
				return true;
			}
		} else if($mode == "modify"){ // 수정
			$get_data = table_data_match($this->_comment_table, $set_data);
			$result = $this->db->update($this->_comment_table, $get_data, array("idx" => $data["idx"]));
			if($result) {
				return true;
			}
		}
		return false;
	}

	/*
	 * 게시판 댓글 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function board_comment_delete($arr_where = null) {
		if(!$arr_where) {
			return false;
		}
		db_where($arr_where);
		return $this->db->delete($this->_comment_table);
	}

	/*
	 * 동영상 및 이미지 추출
	 *
	 *	@param array $video_url 조건
	 *
	 *	@return array
	 */
	public function get_video_thumbnail_url($video_url) {
		$data = array();

		/**
		 * youtube 동영상URL이 인풋될 수 있는 값들
		 *
		 * youtube.com/v/vidid
		 * youtube.com/vi/vidid
		 * youtube.com/?v=vidid
		 * youtube.com/?vi=vidid
		 * youtube.com/watch?v=vidid
		 * youtube.com/watch?vi=vidid
		 * youtu.be/vidid
		 * youtube.com/embed/vidid
		 * http://youtube.com/v/vidid
		 * http://www.youtube.com/v/vidid
		 * https://www.youtube.com/v/vidid
		 * youtube.com/watch?v=vidid&wtv=wtv
		 * http://www.youtube.com/watch?dev=inprogress&v=vidid&feature=related
		 * https://m.youtube.com/watch?v=vidid
		 **/

		// id 추출
		$regex = array(
			"youtube" => "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
		);

		$api_url = array(
			"youtube" => "https://www.googleapis.com/youtube/v3/videos",
			"vimeo" => "https://vimeo.com/api/oembed.json"
		);

		if(preg_match("/(youtu\.be|youtube)/i", $video_url)) {
			preg_match($regex["youtube"], $video_url, $matches);
			if(isset($matches[1])) {
				$this->load->library("curl");
				$result = $this->curl->simple_get($api_url["youtube"], array("key" => self::GOOGLE_API_KEY, "id" => $matches[1], "part" => "snippet"));
				if($result) {
					$result = json_decode($result, JSON_UNESCAPED_UNICODE);
					$data["video_thumbnail_url"] = $result['items'][0]['snippet']['thumbnails']['standard']['url'] ? $result['items'][0]['snippet']['thumbnails']['standard']['url'] : $result['items'][0]['snippet']['thumbnails']['high']['url'];
					$data["video_html"] = "<iframe width=\"100%\" height=\"480\" src=\"https://www.youtube.com/embed/". $matches[1] ."\" frameborder=\"0\"></iframe>";
				}
			}
		} else if(preg_match("/vimeo/i", $video_url)) {
			$this->load->library("curl");
			$result = $this->curl->simple_get($api_url["vimeo"], array("url" => $video_url));

			if($result) {
				$result = json_decode($result, JSON_UNESCAPED_UNICODE);
				$data["video_thumbnail_url"] = $result['thumbnail_url'];
				$data["video_html"] = preg_replace("/(width=)\"([\d]+)\"/", "$1 100%", $result['html']);

			}
		}
		return $data;
	}

    /**
	 * 다국어 언어 설정 불러오기
	 *
	 *	@param string $code 게시판 코드
	 *	@param string $language 게시판 언어
	 *
	 *	@return array
     */
    public function get_board_global($code, $language = null) {
		$get_data = array();  // return

		// 공지사항 쿼리 시작
		$arr_include = array(
			"code",
			"language",
			"name",
		);

		$this->db->select($arr_include);
		$this->db->from("da_board_global");

        if(isset($code)) {
		    $this->db->where("code =", $code);
        }
        if(isset($language)) {
		    $this->db->where("language =", $language);
        }

		$get_data = $this->db->get()->result_array();

        // 다국어 key로 재배열
        foreach($get_data as $key => $val) {
            $result[$val["language"]] = $val;
        }

        return $result;
    }

    /**
	 * 썸네일/첨부파일 가져오기
	 *
     *  @param int $no 게시글 번호
	 *	@param string $code 게시판 코드
	 *	@param string $type thumbnail/file
	 *
	 *	@return array
     */
    public function get_board_file($no, $code, $type = null) {
		$get_data = array();  // return
		$result = array();  //

		// 공지사항 쿼리 시작
		$arr_include = array(
            "master_no",
			"code",
			"fname",
			"oname",
            "type",
		);

		$this->db->select($arr_include);
		$this->db->from("da_board_file");
		$this->db->where("master_no =", $no);
		$this->db->where("code =", $code);
		$this->db->order_by("no", "ASC");

        if(isset($type)) {
            $this->db->where("type =", $type);
        }

		$get_data = $this->db->get()->result_array();

        // 파일 type 재배열
        foreach($get_data as $key => $val) {
            $result[$val["type"]][$key] = $val;
        }

        if(is_array($result)) {
            if(array_key_exists("file", $result)) {
                $result["file"] = array_values($result["file"]);
            }
            if(array_key_exists("thumbnail", $result)) {
                $result["thumbnail"] = array_values($result["thumbnail"]);
            }
        }

        return $result;
    }

	private function get_no($tag) {
		$this->db->from('da_board_gallery_tags');
		$this->db->where('tag', $tag);
		$result = $this->db->get();
		debug($this->db->last_query());
		debug($tag);
	}
}