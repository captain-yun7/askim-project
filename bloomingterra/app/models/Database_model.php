<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function raw_query($query, $crud) {
		if($crud == 'r') return $this->db->query($query)->result_array();
	}

	/*
	 * param array $select ['no', 'name', 'email']
	 * param string $from
	 * param array $where ['no' => 1, 'name' => 'Smith']
	 * param array $like ['column' => 'search text|both'] after|before|both
	 * param array $in ['no' => [1, 2, 3], 'value' => ['James', 'Smith']]
	 * param array $order ['no' => 'DESC']
	 * param integer $limit [offset, limit]
	*/
	public function get($from, $select = [], $where = [], $like = [], $in = [], $order = [], $limit = [], $not = []) {
		if(empty($from) === true) return false;

		if(empty($select) === false) {
			$string = implode(', ', $select);
			$this->db->select($string);
		}

		$this->db->from($from);

		if(empty($where) === false) {
			foreach($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		if(empty($like) === false) {
			foreach($like as $key => $value) {
				if(strpos($value, '|') > -1) {
					$v = explode('|', $value);
				}

				$this->db->like($key, $v[0], $v[1]);
			}
		}

        if(empty($in) === false) {
			foreach($in as $key => $value) {
	            $this->db->where_in($key, $value);
			}
        }

		if(empty($not) === false) {
			foreach($not as $key => $value) {
				$this->db->where_not_in($key, $value);
			}
		}

		if(empty($order) === false) {
			foreach($order as $key => $value) {
				$this->db->order_by($key, $value, false);
			}
		}

        if(empty($limit) === false) {
            $this->db->limit($limit[0], $limit[1]);
        }

		$result = $this->db->get();
		return $result->result_array();
	}

	/*
	 * param string $from
	 * param array $data
	 * param boolean $return
	 * param boolean $batch
	*/
	public function insert($from, $data = [], $return = false, $batch = false) {
		if(empty($from) === true || empty($data) === true) return false;

		if($batch === true) {
			$this->db->insert_batch($from, $data);
		} else {
			$this->db->insert($from, $data);
		}

		if($return === true) return $this->db->insert_id();
	}

	public function update($from, $where = [], $data = [], $in = []) {
		if(empty($from) === true && (empty($where) === true && empty($in) === true)) return false;

		foreach($where as $key => $value) {
			$this->db->where($key, $value);
		}

        if($in['column']) $this->db->where_in($in['column'], $in['value']);

		$this->db->update($from, $data);
	}

	public function update_batch($from, $data, $column) {
		if(empty($from) === true || empty($data) === true || $column == '') return false;
		$this->db->update_batch($from, $data, $column);
	}

	public function remove($from, $where = [], $in = [], $like = []) {
		if(empty($from) === true && (empty($where) === true || empty($in) === true)) return false;
		foreach($where as $key => $value) {
			$this->db->where($key, $value);
		}

        if(empty($in) === false) {
			foreach($in as $key => $value) {
	            $this->db->where_in($key, $value);
			}
        }

		return $this->db->delete($from);
	}
}