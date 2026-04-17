<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
	protected $_category; // 카테고리정보
	protected $_category_table = "da_category";
	protected $_category_multi_table = "da_category_multi";

	public function initialize($cate = null) {
		if(!$cate) {
			$cate = $this->input->get_post("cate", true);
		}

		$this->db->where("category", $cate);

		$this->_category = $this->db->get($this->_category_table)->last_row("array");

		if(!isset($this->_category)) {
			throw new Exception(print_language("not_category_information_found"), 50);
		}

		if($this->_cfg_siteLanguage["multilingual"]){

			//다국어 필드값 초기화
			foreach($this->_cfg_siteLanguage["set_language"] as $key => $val){
				$this->_category["categorynm_".$key] = "";
			}

			$this->db->select(array("language","categorynm"));
			$this->db->from($this->_category_multi_table);
			$this->db->where("category",$cate);
			$result = $this->db->get();
			if($result->result_id->num_rows){
				foreach($result->result_array() as $key => $val){
					$this->_category["categorynm_".$val["language"]] = $val["categorynm"];
				}
			}
		}
	}

	public function get_category() {
		return $this->_category;
	}


	/*
	 * 카테고리 수정
	 *	@pamra string $mode (register, modify)
	 *	@pamra array $data
	 *
	 *	@return boolean
	 */
	public function category_register($mode = "register", $data = null) {
		if($this->_cfg_siteLanguage["multilingual"]){
			$data["categorynm"] = $data["categorynm_kor"];
		}

		if($mode == "register") {
			$data["regdt"] = date('Y-m-d H:i:s');
			$data["yn_end"] = "y";

			if($data["category4"]) {
				$data["category"] = $data["category4"];
				$data["level"] = "5";
			} else if($data["category3"]) {
				$data["category"] = $data["category3"];
				$data["level"] = "4";
			} else if($data["category2"]) {
				$data["category"] = $data["category2"];
				$data["level"] = "3";
			} else if($data["category1"]) {
				$data["category"] = $data["category1"];
				$data["level"] = "2";
			} else {
				$data["category"] = "";
				$data["level"] = "1";
			}

			$get_data = table_data_match($this->_category_table, $data);
			$this->db->select("IFNULL(RIGHT(CONCAT('00', MAX(category) + 1), LENGTH('". $this->db->escape_str($get_data["category"]) ."') + 3), 	'". $this->db->escape_str($get_data["category"]) ."001') AS category");

			$this->db->from($this->_category_table);
			$this->db->where("category LIKE '". $this->db->escape_str($get_data["category"]) ."%' AND LENGTH(category) > LENGTH('". $this->db->escape_str($get_data["category"]) ."')");
			if($get_data["level"] == "1"){
				$this->db->where("level = '1'");
			}

			$get_data["category"] = $this->db->get()->last_row("array")['category'];

			$this->db->set("sort", "IF('". $get_data["sort"] ."' != '', '". $get_data["sort"] ."', (SELECT IFNULL(MAX(sort) + 1, 1) FROM da_category B WHERE category LIKE '". $this->db->escape_str($get_data["category"]) ."%' AND LENGTH(category) = LENGTH('". $this->db->escape_str($data["category"]) ."') + 3))", false);
			unset($get_data["sort"]);

			$result = $this->db->insert($this->_category_table, $get_data);


		} else if($mode == "modify") {
			$data["moddt"] = date('Y-m-d H:i:s');
			if($data["category5"]) {
				$data["category"] = $data["category5"];
			} else if($data["category4"]) {
				$data["category"] = $data["category4"];
			} else if($data["category3"]) {
				$data["category"] = $data["category3"];
			} else if($data["category2"]) {
				$data["category"] = $data["category2"];
			} else {
				$data["category"] = $data["category1"];
			}

			$get_data = table_data_match($this->_category_table, $data);

			$result =  $this->db->update($this->_category_table, $get_data, array("category" => $get_data["category"]));
		}

		if($result) {
			if($mode == "register"){
				$result = $this->db->update($this->_category_table, array("yn_end" => "n"), array("category" =>
				$data["category"]));
			}
			$data["category"] = $get_data["category"];
			$this->set_multi_language_category_process($data);

			// 현재 카테고리가 비활성 상태라면 하위 카테고리도 전부 비활성 처리
			if($get_data["yn_state"] == "n"){
				$result = $this->db->update($this->_category_table, array("yn_state" => "n"), array("category LIKE" => $get_data["category"]."%"));
			}

			return $result;
		}
	}

	/**
	 *@date 2018-10-11
	 *
	 *@author James
	 *
	 *다국어 카테고리 테이블 처리
	 *
	 */
	public function set_multi_language_category_process($data)
	{
		//delete & insert
		$successFl = true;
		//다국어
		if(!$this->_cfg_siteLanguage["multilingual"]){
			$data["categorynm_kor"] = $data["categorynm"];
		}
		foreach($this->_cfg_siteLanguage["support_language"] as $key => $val){

			$this->db->delete($this->_category_multi_table,array("category" => $data["category"],"language" => $key));

			$set_data = $get_data = [];
			$set_data = [
				"language" => $key,
				"category" => $data["category"],
				"categorynm" => $data["categorynm_".$key],
			];

			$get_data = table_data_match($this->_category_multi_table,$set_data);

			$result = $this->db->insert($this->_category_multi_table, $get_data);

			if($successFl === true){
				$successFl = $result;
			}
		}

		return $successFl;
	}
	/*
	 * 카테고리 삭제 (하위카테고리도 삭제)
	 *
	 *	@return boolean
	 */
	public function category_delete() {
		$this->db->like("category", $this->_category["category"], "after", true);

		$result = $this->db->delete($this->_category_table);

		$this->db->like("category", $this->_category["category"], "after", true);

		$this->db->delete($this->_category_multi_table);

		return $result;
	}

	/*
	 * 최상위 카테고리 리스트
	 *
	 * @param array $arr_where
	 * @param array $arr_orderby
	 *
	 *	@return array
	 */
	public function get_list_category_top($arr_where = null, $arr_orderby = null) {
		$get_data = array();
		$cate = $this->_category["category"];

		$arr_where[] = array("Ca.level", "1");
		db_where($arr_where);

		$this->db->from($this->_category_table." AS Ca");

		//다국어
		if($this->_cfg_siteLanguage["multilingual"]){
			$this->db->select(array("Ca.*","Cm.categorynm"));
			$this->db->join($this->_category_multi_table." AS Cm","Ca.category = Cm.category AND Cm.language = '".$this->_site_language."'","left");
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
		} else {
			$this->db->order_by("Ca.sort", "asc");
			$this->db->order_by("Ca.category", "asc");
		}

		$result = $this->db->get();
		if($result->num_rows()) {
			$get_data["top_category_list"] = $result->result_array();
		}
		return $get_data;
	}

	/*
	 * 같은 단계 카테고리 리스트
	 *
	 * @param array $arr_where
	 * @param array $arr_orderby
	 *
	 *	@return array
	 */
	public function get_list_category_same($arr_where = null, $arr_orderby = null) {
		$get_data = array();
		$cate = $this->_category["category"];

		$arr_where[] = array("Ca.level", $this->_category["level"]);

		db_where($arr_where);
		if($this->_category["level"] > "1") {
			$high_cate = substr($cate, 0, -3);
			$this->db->like("Ca.category", $high_cate, "after", true);
		}
		$this->db->from($this->_category_table." AS Ca");

		//다국어
		if($this->_cfg_siteLanguage["multilingual"]){
			$this->db->select(array("Ca.*","Cm.categorynm"));
			$this->db->join($this->_category_multi_table." AS Cm","Ca.category = Cm.category AND Cm.language = '".$this->_site_language."'","left");
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
		} else {
			$this->db->order_by("Ca.sort", "asc");
			$this->db->order_by("Ca.category", "asc");
		}

		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["same_category_list"] = $result->result_array();
		}
		return $get_data;
	}

	/*
	 * 하위 카테고리 리스트
	 *
	 * @param array $arr_where
	 * @param array $arr_orderby
	 *
	 *	@return array
	 */
	public function get_list_category_low($arr_where = null, $arr_orderby = null) {
		$get_data = array();
		$cate = $this->_category["category"];
		if($this->_category["yn_end"] == "y") {
			return $get_data;
		}

		$this->config->load("cfg_siteLanguage");
		$cfg_siteLanguage = $this->config->item("site_language");

		$arr_where[] = array("level", $this->_category["level"] + 1);
		db_where($arr_where);
		$this->db->like("Ca.category", $cate, "after", true);
		$this->db->from($this->_category_table." AS Ca");

		//다국어
		if($this->_cfg_siteLanguage["multilingual"]){
			$this->db->select(array("Ca.*","Cm.categorynm"));
			$this->db->join($this->_category_multi_table." AS Cm","Ca.category = Cm.category AND Cm.language = '".$this->_site_language."'","left");
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
		} else {
			$this->db->order_by("sort", "asc");
			$this->db->order_by("Ca.category", "asc");
		}

		$result = $this->db->get();

		if($result->num_rows()) {
			$get_data["low_category_list"] = $result->result_array();
		}

		return $get_data;
	}

	/*
	 * 카테고리 트리구조로 가져오기(아직미구현)
	 *
	 * @param array $arr_where
	 *
	 *	@return array
	 */
	public function get_category_tree($arr_where = null, $arr_like = null) {
		$get_data = array();

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}
		$this->db->order_by("level", "asc");
		$this->db->order_by("sort", "asc");
		$this->db->order_by("category", "asc");

		$this->db->from($this->_category_table);

		$result = $this->db->get();
		if($result->num_rows()) {
			$get_data["category_list"] = $result->result_array();
		}

		if($this->_cfg_siteLanguage["multilingual"]){
			foreach($get_data["category_list"] as $key => &$val){
				$this->db->select(array("language","categorynm"));
				$this->db->from($this->_category_multi_table);
				$this->db->where("category",$val["category"]);
				$rs = $this->db->get();

				if($rs->result_id->num_rows){
					foreach($rs->result_array() as $fkey => $fval){
						if(!empty($fval["categorynm"])){
							$val["multi"]["categorynm"][$fval["language"]] = $fval["categorynm"];
						}
					}
					if(!empty($val["multi"]["categorynm"][$this->_cfg_siteLanguage["default"]])){
						$val["categorynm"] = $val["multi"]["categorynm"][$this->_cfg_siteLanguage["default"]];
					}
				}
			}
		}
		return $get_data;
	}


	public function get_select_category($cate = null, $level = null) {

		$this->db->where("level", $level);
		if($level > 1) {
			$this->db->where("LEFT(category, 3 * (". $level ." - 1)) =", "LEFT('". $cate ."', 3 * (". $level ." - 1))", false);
		}
		$this->db->order_by("sort", "asc");
		$this->db->order_by("category", "asc");
		$this->db->from($this->_category_table);

		$result = $this->db->get()->result_array();

		if($this->_cfg_siteLanguage["multilingual"]){
			foreach($result as $fkey => &$fval){

				$this->db->select(array("language","categorynm"));
				$this->db->from($this->_category_multi_table);
				$this->db->where("category",$fval["category"]);

				$rs = $this->db->get();

				if($rs->result_id->num_rows){
					foreach($rs->result_array() as $skey => $sval){
						$fval["categorynm_".$sval["language"]] = $sval["categorynm"];
					}

					if(!empty($fval["categorynm_".$this->_cfg_siteLanguage["default"]])){
						$fval["categorynm"] = $fval["categorynm_".$this->_cfg_siteLanguage["default"]];
					}
				}
			}
		}

		return $result;
	}

	public function get_blocked_category($category) {
		$temp = [];
		for($i = 1; $i <= strlen($category); $i++) {
			if($i % 3 === 0) {
				$j = $i;
			} else {
				continue;
			}
			$temp[] = substr($category, 0, $j);
		}

		$this->db->where_in('category', $temp);
		return $this->db->get_where('da_category', ['yn_use' => 'n'])->result_array();
	}
}