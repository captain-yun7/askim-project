<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup_model extends CI_Model {
	protected $_table = "da_popup";

	/*
     * 팝업 리스트 가져오기
	 *
	 *	@pamra array $arr_where 조건
	 *	@pamra array $arr_like like조건
	 *	@pamra int $limit 시작
	 *	@pamra int $offset 갯수
	 *	@pamra array $arr_orderby 정렬
	 *
	 *	@return array
	 */
	public function get_list_popup($arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null) {
		$get_data = array();  // return

		$arr_include = array(
			"*",
			"DATE_FORMAT(sdate, '%Y-%m-%d') AS sdate_date",
			"DATE_FORMAT(edate, '%Y-%m-%d') AS edate_date",
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(updatedt, '%Y-%m-%d') AS updatedt_date",
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
			$this->db->order_by("regdt", "desc");
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table);
		$this->db->limit($limit, $offset);


		$result = $this->db->get();
		#debug($result->result_array());
		if($result->num_rows()) {
			$get_data["popup_list"] = $result->result_array();
		}

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

	/*
     * 팝업 상세 가져오기
	 *
	 *	@pamra array $arr_where 조건
	 *
	 *	@return array
	 */
	public function get_view_popup($arr_where = null) {
		$get_data = array();  // return

		$arr_include = array(
			"*",
			"DATE_FORMAT(sdate, '%Y-%m-%d') AS sdate_date",
			"DATE_FORMAT(edate, '%Y-%m-%d') AS edate_date",
			"DATE_FORMAT(regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(updatedt, '%Y-%m-%d') AS updatedt_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_table);
		$result = $this->db->get();

		if($result->result_id->num_rows) {
			$get_data["popup_view"] = $result->last_row("array");
		}

		return $get_data;
	}

	public function popup_delete($arr_where) {
		if(!isset($arr_where)) {
			throw new Exception("팝업 정보를 찾을 수 없습니다.");
		}
		db_where($arr_where);
		return $this->db->delete($this->_table);
	}

	public function set_popup_sort($data) {
		$get_data = table_data_match($this->_table, $data);
		$query = $this->db->select("sort")->where(array("no" => $data["no"]))->get_compiled_select($this->_table);
		$this->db->set("sort", "(SELECT sort FROM (". $query .") tbl)", false);
		$this->db->update($this->_table, null, array("sort" => $data["sort"]));
		$this->db->update($this->_table, $get_data, array("no" => $data["no"]));
		return true;
	}
}