<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {
	protected $_table = "da_member";
	/*
	 * 회원등록/수정
	 *	@pamra string $mode (register, modify)
	 *	@pamra array $data
	 *
	 *	@return boolean
	 */
	public function member_register($mode = "register", $data = null) {
		if($mode == "register") {
			$data["password"]  = base64_encode(hash("sha256", $data["password"], true));
			$data["group"] = "1";
			$data["yn_status"] = "y";
			$data["regdt"] = date('Y-m-d H:i:s');
			$get_data = table_data_match($this->_table, $data);

			return $result = $this->db->insert($this->_table, $get_data);
		} else if($mode == "modify") {
			if(isset($data["password"]) && $data["password"]) {
				$data["password"] = base64_encode(hash("sha256", $data["password"], true));
			} else {
				unset($data["password"]);
			}
			$get_data = table_data_match($this->_table, $data);

			$result = $this->db->update($this->_table, $get_data, array("userid" => $get_data["userid"], "language" => $get_data['language']));
			return $result;
		}
	}
	/*
	 * 회원정보 리스트 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 *
	 *	@return array
	 */
	public function get_list_member($arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null, $day = null) {
		$get_data = array();  // return

		$arr_include = array(
            "language",
			"userid",
			"level",
			"group",
			"name",
			"password_moddt",
			"yn_status",
			"sex",
			"birth",
			"email",
			"zip",
			"address",
			"address2",
			"phone",
			"mobile",
			"fax",
			"yn_mailling",
			"yn_sms",
			"regdt",
			"last_login",
			"last_login_ip",
			"cnt_login",
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
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(last_login, '%Y-%m-%d') AS last_login_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
		} else {
			$this->db->order_by("regdt", "DESC");
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table);
		$this->db->limit($limit, $offset);

		if($day) {
			$this->db->where("`regdt` > ", 'date_add(now(),interval -'.$day.' day)', false);
		}
		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["member_list"] = $result->result_array();
		}

		// 리스트 총 로우갯수 가져오기
		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$this->db->from($this->_table);
		if($day) {
			$this->db->where("`regdt` > ", 'date_add(now(),interval -'.$day.' day)', false);
		}
		$get_data["total_rows"] = $this->db->count_all_results();

		return $get_data;
	}

	public function get_view_member($arr_where = null) {
		$get_data = array();  // return

		$arr_include = array(
            "language",
			"userid",
			"level",
			"group",
			"name",
			"password_moddt",
			"yn_status",
			"sex",
			"birth",
			"email",
			"zip",
			"country",
			"city",
			"state_province_region",
			"address",
			"address2",
			"phone",
			"mobile_country_code",
			"mobile",
			"fax",
			"yn_mailling",
			"yn_sms",
			"regdt",
			"last_login",
			"last_login_ip",
			"cnt_login",
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
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(last_login, '%Y-%m-%d') AS last_login_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table);
		$result = $this->db->get();

		if($result->result_id->num_rows) {
			$get_data["member_view"] = $result->last_row("array");
		}

		return $get_data;
	}

	/*
	 * 아이디 중복체크
	 * @param string $userid
	 *
	 * @return boolean
  	 */
	public function is_userid_duplicate($userid, $language) {
		$this->db->select("userid");
		$this->db->where("userid", $userid);
		$this->db->where("language", $language);
		$userid_chk = $query = $this->db->get($this->_table)->row_array();

		$this->db->select("userid");
		$this->db->where("userid", $userid);
		$this->db->where("language", $language);
		$dormant_userid_chk = $query = $this->db->get("da_dormant")->row_array();
		return isset($userid_chk["userid"]) || isset($dormant_userid_chk);
	}

	/*
	 * 이메일 중복체크
	 * @param string $userid
	 * @param string $email
	 *
	 * @return boolean
  	 */
	public function is_email_duplicate($userid, $email) {
		$this->db->select("userid");
		$this->db->where("email", $email);
		$this->db->where("userid !=", $userid);
		$this->db->where("yn_status", "y");
		$email_chk = $query = $this->db->get($this->_table)->row_array();
    
        if(isset($email_chk["userid"])) {
            return true;
        } 

        // 휴면회원들 대상으로도 중복검사 2020-06-17
		$this->db->select("userid");
		$this->db->where("email", $email);
		$this->db->where("userid !=", $userid);
		$this->db->where("yn_status", "y");
		$email_chk = $query = $this->db->get("da_dormant")->row_array();
        
        return isset($email_chk["userid"]);
	}

	public function login_chk($data) {
		//if($data["encrypt"] == "p") {
		//	$password = hash('sha512', $data["password"]);
		//} else if($data["encrypt"] == "p2") {
			$password = base64_encode(hash("sha256", $data["password"], true));
		//}
		$this->db->select("*");
		$this->db->select("IF(password = '". $password ."', 'y', 'n') yn_password", false);
		$this->db->select("IF(IF(password_moddt IS NULL OR password_moddt = '', regdt, password_moddt) < DATE_ADD(CURRENT_DATE(), INTERVAL -6 MONTH), 'y', 'n') AS yn_change_password", false);
		$this->db->from($this->_table);

		if(_CONNECT_PAGE == "ADMIN") {
			$this->db->where(array("userid" => $data["userid"], "language"=> 'kor'));
		} else {
			//2018-10-01 James site_language 추가
			$this->db->where(array("userid" => $data["userid"], "language"=> $this->session->userdata('site_language')));
		}
		$test = $this->db->get()->row_array();
		return $test;
	}

	public function login_ok($data) {
		$set_data = array();
		$set_data["last_login"] = date('Y-m-d H:i:s');
		//$set_data["last_login_ip"] = $this->input->ip_address();

		$this->db->set("cnt_login", "cnt_login + 1", false);
		//2018-10-01 James site_language 추가
		return $this->db->update("da_member", $set_data, array("userid" => $data["userid"],"language"=>$this->session->userdata('site_language')));
	}

	/*
	 * 회원 삭제
	 *
	 * @param array $arr_where 조건
	 *
	 * @return boolean
	 */
	public function member_delete($arr_where = null) {
		if(!isset($arr_where)) {
			return false;
		}
		db_where($arr_where);
		return $this->db->delete($this->_table);
	}
}