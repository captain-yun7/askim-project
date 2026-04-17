<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Goods_model.php");

class Admin_Goods_model extends Goods_model {
	public function get_view_goods($arr_where) {
		$get_data = parent::get_view_goods($arr_where);
		// 메인페이지 상품진열 셋팅
		if(ib_isset($get_data["goods_view"])) {
			$arr_where = [];
			$arr_include = array('dtg.display_theme_no');

			$this->db->select($arr_include);
			$this->db->from($this->_display_theme_goods_table ." AS dtg");
			$arr_where[] = array("dtg.goods_no", $get_data["goods_view"]['no']);
			db_where($arr_where);
			$result = $this->db->get();

			if($result->result_id->num_rows) {
				$display_theme_data = $result->result_array();
				$get_data['goods_view']['display_theme'] = array_column($display_theme_data, 'display_theme_no');
			}
		}

		return $get_data;
	}

	public function set_category($datas) {
		$goods = explode("|", $datas['goods']);
		$flag = true;
		foreach($goods as $v) {
			$this->db->where("no", $v);
			$result = $this->db->update("da_goods", ['category' => $datas['category']]);
			if(!$result) {
				$flag = false;
				break;
			}
		}
		return $flag;
	}

	public function get_datas($table, $where) {
		$result = $this->db->get_where($table, $where);
		return $table == "da_goods" ? $result->result_array()[0] : $result->result_array();
	}

	public function post_goods($datas) {
		if($this->db->insert("da_goods", $datas)) {
			return $this->db->insert_id();
		}
	}

	public function post_goods_batch($table, $datas) {
		$this->db->insert_batch($table, $datas);
	}
}