<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class terms_model extends CI_Model {
	protected $_table = "da_terms";

	public function getTermsData($searchData)
	{
		$arr_where = array(
			array("language",$searchData['language']),
			array("code",$searchData['code'])
		);

		$this->db->from($this->_table);
		db_where($arr_where);
		$select_result = $this->db->get();

		return $select_result->row_array();
	}

	public function getTermsList()
	{
		$this->db->from($this->_table);
		$select_result = $this->db->get();

		$returnArr = array();

		foreach($select_result->result_array() as $val){
			$returnArr[$val['language']][$val['code']]['title'] = $val['title'];
			$returnArr[$val['language']][$val['code']]['text'] = $val['text'];
		}
		return $returnArr;
	}

	public function setTermsData($setData)
	{
		//2018-10-04 James ini 저장 => DB 저장으로 변경
		$arrInclude = array(
			"no",
			"language",
			"code",
		);

		$arr_where = array(
			array("language", $setData['language']),
			array("code", $setData['code'])
		);

		$this->db->select($arrInclude);
		$this->db->from($this->_table);
		db_where($arr_where);
		$select_result = $this->db->get();

		$set_data = $this->input->post();
		$set_data = table_data_match($this->_table,$set_data);

		if($select_result->num_rows()){
			$select_data = $select_result->row_array();
			$this->db->set('updateDt', 'NOW()', FALSE);
			$result = $this->db->update($this->_table, $set_data, array("no"=>$select_data["no"]));
		}else{
			$this->db->set('regDt', 'NOW()', FALSE);
			$result = $this->db->insert($this->_table, $set_data);
			$no = $this->db->insert_id();
		}

		return $result;
	}
}