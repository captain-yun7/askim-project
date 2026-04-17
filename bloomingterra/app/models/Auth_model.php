<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
	public function remove_display_from_main($theme_no, $goodsNo) {
		$this->db->where("display_theme_no", $theme_no);
		$this->db->where_in("goods_no", $goodsNo);
		return $this->db->delete("da_display_theme_goods");
	}

	public function add_display($theme_no, $goods_no) {
		$this->db->where("display_theme_no", $theme_no);
		$this->db->order_by("seq", "DESC");
		$get_max = $this->db->get("da_display_theme_goods");
		$no = $get_max->result_array()[0]['seq'] > 0 ? ($get_max->result_array()[0]['seq'] + 1) : 1;
		$datas = [];
		for($i = 0; $i < count($goods_no); $i++) {
			$datas[] = [
				"display_theme_no" => $theme_no,
				"goods_no" => $goods_no[$i],
				"seq" => $no
			];
			$no++;
		}

		return $this->db->insert_batch("da_display_theme_goods", $datas);
	}

	public function sort_display($datas) {
		$this->db->update_batch("da_display_theme_goods", $datas, "no");
	}

	public function manage_menu($lang) {
		$this->db->order_by("code", "ASC");
		$query = $this->db->get_where("da_menu", ['language' => $lang]);
		$result = $query->result_array();
		return $result;
	}

	public function has_child($code) {
		$get = $this->db->query("SELECT COUNT(*) AS cnt FROM `da_menu` WHERE `code` LIKE '".$code."%' AND LENGTH(`code`) > ".strlen($code));
		return $get->result_array()[0]['cnt'];
	}

	public function remove_menus($data) {
		if($data['no']) {
			return $this->db->delete("da_menu", ['no' => $data['no']]);
		} else {
			$this->db->where("language", $data['lang']);
			$this->db->like("code", $data['code'], "after");
			return $this->db->delete("da_menu");
		}
	}

	public function add_menu($data) {
		$set = [
			'language' => $data['lang'],
			'code' => $data['code'],
			'name' => $data['name'],
			'url' => $data['url']
		];
		return $this->db->insert("da_menu", $set);
	}

	public function change_use($no, $yn) {
		$this->db->where("no", $no);
		$this->db->update("da_menu", ['use' => $yn]);
	}

	public function put_menu($data) {
		$this->db->where("no", $data['no']);
		$this->db->update("da_menu", [$data['col'] => $data['str']]);
	}
}