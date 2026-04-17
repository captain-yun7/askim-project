<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Popup_model.php");

class  Front_Popup_model extends Popup_model {

	public function get_list_popup($arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null) {
		if(is_null($arr_where)) {
			$arr_where = array();
		}
		$arr_where[] = array("open", "y");
		$arr_where[] = array("sdate", date('Y-m-d H:i:s'), "<=");
		$arr_where[] = array("edate", date('Y-m-d H:i:s') , ">=");
		$get_data = parent::get_list_popup($arr_where, $arr_like, $limit, $offset, $arr_orderby);
		return $get_data;
	}
}