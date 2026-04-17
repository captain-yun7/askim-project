<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dormant_model extends CI_Model {
	private $_table = "da_dormant";
	private $_member_table = "da_member";

	/*
	 * 휴면회원정보 리스트 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 *
	 *	@return array
	 */
	public function get_list_dormant($arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null) {
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
			"dormant_dt",
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
			"DATE_FORMAT(dormant_dt, '%Y-%m-%d') AS dormant_dt_date",
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
            // 휴면회원은 휴면일 기준으로 정렬하도록 
			$this->db->order_by("dormant_dt", "DESC");
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table);
		$this->db->limit($limit, $offset);
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
		$get_data["total_rows"] = $this->db->count_all_results();

		return $get_data;
	}

	public function get_view_dormant($arr_where = null) {
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
			"dormant_dt",
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
			"DATE_FORMAT(dormant_dt, '%Y-%m-%d') AS dormant_dt_date",
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
	 * 휴면계정처리 스케쥴러
	 *
	 */
	public function run() {
		/*
		$this->config->load("cfg_mailForm");
		$this->load->library("emailsend");
		$this->emailsend->get_mail_form("dormant");
		*/
		$cfg_site = $this->config->item("cfg_site");
		$this->load->library('MY_Email');

		$arr_mail = array();
		$arr_mail["subject"] = $cfg_site["nameKor"] ." 휴면계정 분리보관 안내메일";

		$log	= '';
		$log	.= '===================================================='.chr(10);
		$log	.= '휴면예정안내 스케쥴러 '. date('Y-m-d H:i:s') .chr(10);

		//$query = $this->db->query("SELECT * FROM `da_member` WHERE `yn_status` = 'y' AND DATEDIFF(IFNULL(`last_login`, `regdt`), CURRENT_DATE()) < -270");
		//$rows = $query->result_array();
		//debug($rows);
		//exit;
		$get_data = $this->db->select("userid, email, name, DATE_FORMAT(IF(last_login IS NOT NULL AND last_login != '', DATE_ADD(last_login, INTERVAL 1 YEAR), DATE_ADD(regdt, INTERVAL 1 YEAR)), '%Y년 %m월 %d일') dormant_dt", false)
			->where("yn_status", "y")
			->group_start()
				->where("yn_dormant_mail !=", "y")
				->or_where("yn_dormant_mail IS NULL")
			->group_end()
			->group_start()
				->group_start()
					->where("last_login IS NOT NULL")
					->where("last_login !=", "")
					->where("DATE_FORMAT(last_login, '%Y-%m-%d') < ", "DATE_ADD(CURRENT_DATE(), INTERVAL -9 MONTH)", false)
				->group_end()

				->or_group_start()
					->group_start()
						->where("last_login IS NULL")
						->or_where("last_login", "")
					->group_end()
					->where("DATE_FORMAT(regdt, '%Y-%m-%d') < ", "DATE_ADD(CURRENT_DATE(), INTERVAL -9 MONTH)", false)
				->group_end()
			->group_end()
		->get($this->_member_table)->result_array();
		$log	.= '대상인원 :'. count($get_data) .'명'.chr(10);
		foreach($get_data as $value) {
			$log	.= ' --------------------------------------------------'.chr(10);
			$log	.= '아이디 : '.$value["userid"].chr(10);
			$log	.= '사용언어 : '.$value["language"].chr(10);
			$log	.= '종류 : 휴면예정회원 안내'.chr(10);
			$log	.= '발송메일 : '.$value["email"].chr(10);
			$current_date = date('Y-m-d H:i:s');
			$log	.= '처리시간 : '. $current_date .chr(10);
			$arr_mail["to"] = $value["email"];
			/*
			$this->emailsend->message_bind(array("dormant_dt" => $value["dormant_dt"]));
			$this->emailsend->mail_form($arr_mail);
			*/
			$to[] = ['email' => $value['email'], 'name' => $value['name']];
			$this->my_email->set_mail('mail_dormant.html', ['title' => $cfg_site['nameKor'].' 휴면계정 분리보관 안내메일', 'dormant_dt' => $value['dormant_dt']], $to);
			//if($this->emailsend->send()) {}
			$log	.= '메일발송 결과 : 성공'.chr(10);

			$dormant_mail = $this->db->update($this->_member_table, array("yn_dormant_mail" => "y", "dormant_mail_dt" => $current_date), array("userid" => $value["userid"]));
			if($dormant_mail) {
				$log	.= '메일발송 정보수정 : 성공'.chr(10);
			} else {
				$log	.= '메일발송 정보수정 : 실패'.chr(10);
			}
			/*
			} else {
				$log	.= '메일발송 결과 : 실패'.chr(10);
			}
			*/
		}
		$log	.= '===================================================='.chr(10);
		weblog($log, "schedule");



		$log	= '';
		$log	.= '===================================================='.chr(10);
		$log	.= '휴면처리 스케쥴러 '. date('Y-m-d H:i:s') .chr(10);

		$get_data = $this->db
			->where("yn_status", "y")
			->group_start()
				->group_start()
					->where("last_login IS NOT NULL")
					->where("last_login !=", "")
					->where("DATE_FORMAT(last_login, '%Y-%m-%d') < ", "DATE_ADD(CURRENT_DATE(), INTERVAL -1 YEAR)", false)
				->group_end()

				->or_group_start()
					->group_start()
						->where("last_login IS NULL")
						->or_where("last_login", "")
					->group_end()
					->where("DATE_FORMAT(regdt, '%Y-%m-%d') < ", "DATE_ADD(CURRENT_DATE(), INTERVAL -1 YEAR)", false)
				->group_end()
			->group_end()
		->get($this->_member_table)->result_array();

		$log	.= '대상인원 :'. count($get_data) .'명'.chr(10);
		foreach($get_data as $value) {
			$tempArray = array('userid' => $value["userid"], 'language' => $value["language"]);
			$result = $this->db->select("userid")->where($tempArray)->get($this->_table);
			if(!$result->num_rows()) {
				$log	.= ' --------------------------------------------------'.chr(10);
				$log	.= '아이디 : '.$value["userid"].chr(10);
				$log	.= '사용언어 : '.$value["language"].chr(10);
				$log	.= '종류 : 휴면회원 전환'.chr(10);
				$log	.= '처리시간 : '.date('Y-m-d H:i:s').chr(10);

				$value["dormant_dt"] = date('Y-m-d H:i:s');
				$get_value = table_data_match($this->_table, $value);
				$dormant_result = $this->db->insert($this->_table, $get_value);
				if($dormant_result) {
					$log	.= '휴면테이블 회원삽입 : 성공'.chr(10);
					$member_delete = $this->db->delete($this->_member_table, array("userid" => $value["userid"]));
					if($member_delete) {
						$log	.= '회원테이블 회원삭제 : 성공'.chr(10);
					} else {
						$log	.= '회원테이블 회원삭제 : 실패'.chr(10);
					}
				} else {
					$log	.= '휴면테이블 회원삽입 : 실패'.chr(10);
				}
			}
		}
		$log	.= '===================================================='.chr(10);
		weblog($log, "schedule");


		$log	= '';
		$log	.= '===================================================='.chr(10);
		$log	.= '휴면파기 스케쥴러 '. date('Y-m-d H:i:s') .chr(10);

		$get_data = $this->db->where("DATE_FORMAT(dormant_dt, '%Y-%m-%d') < ", "DATE_ADD(CURRENT_DATE(), INTERVAL -1 YEAR)", false)
		->get($this->_table)->result_array();

		$arr_delete_id = array();
		$log	.= '대상인원 :'. count($get_data) .'명'.chr(10);
		if(count($get_data)) {
			$log	.= ' --------------------------------------------------'.chr(10);
			foreach($get_data as $value) {
				$log	.= '아이디 : '.$value["userid"].chr(10);
				$arr_delete_id[] = $value["userid"];
			}
			$log	.= ' --------------------------------------------------'.chr(10);
			$log	.= '종류 : 휴면파기'.chr(10);
			$log	.= '처리시간 : '.date('Y-m-d H:i:s').chr(10);

			$result = $this->db->where_in("userid", $arr_delete_id)->delete($this->_table);
			if($result) {
				$log	.= '휴면테이블 회원삭제: 성공'.chr(10);
			} else {
				$log	.= '휴면테이블 회원삭제: 실패'.chr(10);
			}
		}
		$log	.= '===================================================='.chr(10);
		weblog($log, "schedule");

		return true;
	}

	public function dormant_chk($data) {
		//if($data["encrypt"] == "p") {
		//	$password = hash('sha512', $data["password"]);
		//} else if($data["encrypt"] == "p2") {
			$password = base64_encode(hash("sha256", $data["password"], true));
		//}
		$this->db->select("*");
		$this->db->select("IF(password = '". $password ."', 'y', 'n') yn_password", false);

		$this->db->from($this->_table);
		$this->db->where(array("userid" => $data["userid"], "language" => $data["language"]));
		return $this->db->get()->row_array();
	}

	public function dormant_release($data) {
		$cfg_site = $this->config->item("cfg_site");
		//$this->load->library("emailsend");
		$this->load->library('MY_Email');

		//$this->emailsend->get_mail_form("dormant_release");

		$arr_mail = array();
		$arr_mail["subject"] = $cfg_site["nameKor"] ." 휴면해제 안내메일";

		$set_data = $this->dormant_chk($data);

		if($set_data["yn_password"] == "y") {
			$set_data["yn_dormant_mail"] = "n";
			$get_data = table_data_match($this->_member_table, $set_data);
			$result = $this->db->insert($this->_member_table, $get_data);
			if($result) {
				$dormant_delete = $this->db->delete($this->_table, array("userid" => $get_data["userid"], "language" => $get_data["language"]));

				$arr_mail["to"] = $get_data["email"];
				//$this->emailsend->message_bind(array("dormant_release_dt" => date('Y년 m월 d일')));
				//$this->emailsend->mail_form($arr_mail);
				//$this->emailsend->send();
				$to[] = ['email' => $get_data['email'], 'name' => $get_data['name']];
				$this->my_email->set_mail('mail_dormant_release.html', ['title' => $cfg_site['nameKor'].' 휴면해제 안내메일', 'dormant_release_dt' => date('Y년 m월 d일')], $to);
				return true;
			}
		}
		return false;
	}


}