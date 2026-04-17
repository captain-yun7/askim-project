<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Goods_model.php");

class Front_Goods_model extends Goods_model {

	/**
	 * @author ethan
	 * 메인페이지 상품진열 디자인
	 **/
	public function design_display_main($display_theme_no) {

		$arr_where = array();
		$arr_where[] = array("dt.no", $display_theme_no);
		$data = $this->get_view_display_theme($arr_where);
		$arr_where = array();
		$arr_where[] = array("Go.yn_state", "y");
		$arr_where[] = array("dtg.display_theme_no", $display_theme_no);
		$data = array_merge($data, $this->get_display_theme_goods_data($arr_where, "seq ASC"));

		return $data;
	}
	/*
	 * @override
	 * 상품 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@return array
	 */
	public function get_view_goods($arr_where = null) {

		$arr_where[] = array("Go.yn_state", "y");
		
        //$arr_where[] = array("Ca.yn_state", "y"); 카테고리 활성유무 n이어도 검색 후 컨트롤러에서 예외처리 2020-06-26
		$get_data = parent::get_view_goods($arr_where);

		if(!isset($get_data["goods_view"])) {
			return false;
		}

		return $get_data;
	}

	/*
	 * @override
	 * 상품 리스트정보 가져오기
	 *
	 *	@param string $cate 카테고리
	 *	@param array $arr_where 조건 ex) array(0 => array(field, value, operator, type))
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 */
	public function get_list_goods($cate = null, $arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null) {

		$arr_where[] = array("Go.yn_state", "y");
		$get_data = parent::get_list_goods($cate, $arr_where, $arr_like, $limit, $offset, $arr_orderby);

		return $get_data;
	}
}