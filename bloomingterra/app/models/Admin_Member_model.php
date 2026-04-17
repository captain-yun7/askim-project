<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Member_model.php");

class  Admin_Member_model extends Member_model {
	
	/*
	 * @override
	 *
	 * 회원등록/수정
	 *	@pamra string $mode (register, modify)
	 *	@pamra array $data 
	 *
	 *	@return boolean
	 */
	public function member_register($mode = "register", $data = null) {
		if($mode == "register") {
			if(get_man_age($data["birth"]) < 14) {
				throw new Exception("14세 미만 회원가입 시 법정대리인의 동의가 필요합니다. 고객센터로 문의해주세요.");
			}
		}
		if($set_data["level"] > $this->_admin_member["level"]) {
			throw new Exception("관리자 등급보다 높은 등급을 줄 수 없습니다.");
		}

		return parent::member_register($mode, $data);
	}

	public function member_withdrawal_list($arr_where = null, $arr_like = null, $limit = null, $offset = null) {
		if(isset($arr_where)) db_where($arr_where);
		if(isset($arr_like)) db_like($arr_like);

		$this->db->select("language, userid, name, withdrawal_reason, withdrawal_dt");
		$this->db->from("da_member");
		$this->db->where("withdrawal_dt <> ''");
		$this->db->order_by("withdrawal_dt", "DESC");
		$this->db->limit($limit, $offset);
		$result = $this->db->get();
		if($result->num_rows()) $get_data["member_list"] = $result->result_array();

		if(isset($arr_where)) db_where($arr_where);
		if(isset($arr_like)) db_like($arr_like);
		$this->db->from("da_member");
		$this->db->where("withdrawal_dt <> ''");
		$get_data["total_rows"] = $this->db->count_all_results();

		return $get_data;
	}
}