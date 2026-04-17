<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Member_model.php");

class  Front_Member_model extends Member_model {

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
				throw new Exception(print_language("join_under_14"));
			}
			$data["level"] = "1";
		} else if($mode == "modify") {
			$data["userid"] = $this->_member["userid"];
		}
		

		return parent::member_register($mode, $data);
	}
}