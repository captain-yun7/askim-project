<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etc_model extends CI_Model {
	public function get_my_parent($category) {
		$get = $this->db->get_where("da_category", array("category" => substr($category, 0, 3)));
		if($get->result_id->num_rows > 0) {
			return $get->result_array()[0]['categorynm'];
		}
	}

	public function get_1st_category() {
		$this->db->order_by("sort", "ASC");
		$categories = $this->db->get_where("da_category", ["LENGTH(category)" => 3, "yn_use" => "y"]);
		return $categories->result_array();
	}
}