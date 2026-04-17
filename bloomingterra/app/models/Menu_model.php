<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
	public function get_menus($lang, $depth) {
		//$this->db->where()
		$this->db->from("da_menu");
		$this->db->where("language", $lang);
		$this->db->order_by("code", "ASC");
		$result = $this->db->get();
		return $result->result_array();
	}
}