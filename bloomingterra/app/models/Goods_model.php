<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_model extends CI_Model {
	protected $_goods_table = "da_goods";
	protected $_category_table = "da_category";
	protected $_category_multi_table = "da_category_multi";
	protected $_goods_multi_table = "da_goods_multi";
	protected $_goods_img_table = "da_goods_image";
	protected $_display_theme_table = "da_display_theme";
	protected $_display_theme_goods_table = "da_display_theme_goods";

	/*
	 * 상품등록/수정
	 *	@param string $mode (register, modify)
	 *	@param array $data
	 *
	 *	@return boolean
	 */
	public function goods_register($mode = "register", $data = null) {
		$this->config->load("cfg_goodsField");
		$goodsField = $this->config->item("goodsField");

		//다중 상세이미지 데이터 정리
		foreach($data["detail_fname"] as $key => $val){
			if(empty($val)){
				unset($data["detail_fname"][$key]);
				unset($data["detail_oname"][$key]);
			}else{
				//파일 존재 여부 확인
				if(!file_exists(_DIR.$data["detail1_folder"]."/".$val)){
					unset($data["detail_fname"][$key]);
					unset($data["detail_oname"][$key]);
				}
			}
		}

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

		foreach($goodsField["option"] as $key => $value) {
			if(isset($goodsField["option"][$key]["type"]) && $goodsField["option"][$key]["type"] == "file") {
				$data[$key] = $data[$key ."_fname"];
			}
		}

		$arrMultiKey = array(
			"name",
			"info",
		);
		for($i = 1;$i <= 20;$i++){
			$arrMultiKey[] = "ex".$i;
		}

		if($this->_site_language["multilingual"] && $data["multi"]){
			foreach($this->_site_language["support_language"] as $key => $val){
				foreach($arrMultiKey as $keyNm){
					if(array_key_exists($keyNm."_".$key, $data)){
						$multiArr[$keyNm][$key] = $data[$keyNm."_".$key];
					}
				}
			}

			foreach($arrMultiKey as $keyNm){
				if(array_key_exists($keyNm."_".$this->_site_language["default"], $data)){
					$data[$keyNm] = $data[$keyNm."_".$this->_site_language["default"]];
				}
			}
		}else{
			foreach($arrMultiKey as $keyNm){
				if(array_key_exists($keyNm, $data)){
					$multiArr[$keyNm]["kor"] = $data[$keyNm];
				}
			}

		}

		$data["regdt"] = date('Y-m-d H:i:s');
		$data["moddt"] = date('Y-m-d H:i:s');
		$get_data = table_data_match($this->_goods_table, $data);
		if($mode == "register") {
			unset($get_data["moddt"]);
			$result = $this->db->insert($this->_goods_table, $get_data);
			//상품번호
			$no = $this->db->insert_id();

			//다국어 상품명, 내용 insert
			if($result && !empty($multiArr)){
				$data["no"] = $this->db->insert_id();
				$rs = $this->set_multi_language_goods_process($data,$multiArr);
			}

			//상세 이미지
			$this->detail_image_process($data);

			// 메인페이지 상품진열 셋팅
			$display_theme_data = array();
			if(ib_isset($data["display_theme_no"])) {
				foreach($data["display_theme_no"] as $display_theme_no) {
					$display_theme_data[] = ["display_theme_no" => $display_theme_no, "goods_no" => $no];
				}
			}

			if(ib_isset($display_theme_data)) {
				$this->db->insert_batch($this->_display_theme_goods_table, $display_theme_data);
			}
		} else if($mode == "modify") {
			unset($get_data["regdt"]);
			$result = $this->db->update($this->_goods_table, $get_data, array("no" => $get_data["no"]));

			//다국어 상품명, 내용 update
			if($result && !empty($multiArr)){
				$rs = $this->set_multi_language_goods_process($data,$multiArr);
			}

			//상세 이미지
			$this->detail_image_process($data);

			// 메인페이지 상품진열 셋팅
			$display_theme_data = array();
			if(ib_isset($data["display_theme_no"])) {
				foreach($data["display_theme_no"] as $display_theme_no) {
					$display_theme_data[] = ["display_theme_no" => $display_theme_no, "goods_no" => $get_data["no"]];
				}
			}
			$this->db->delete($this->_display_theme_goods_table, "goods_no = '". $get_data["no"] ."'");
			if(ib_isset($display_theme_data)) {
				$this->db->insert_batch($this->_display_theme_goods_table, $display_theme_data);
			}
		}
		$returndata = array(
			"result" => $result,
			"no" => $no,
		);

		$this->goods_multi_register($data, $returndata['no']);

		return $returndata;
	}

	/**
	 *@date 2018-10-10
	 *
	 *@author James
	 *
	 *상품 상세 이미지 처리
	 */
	public function detail_image_process($data){
		try{
			$this->db->where("master_no",$data["no"]);
			$this->db->delete($this->_goods_img_table);

			foreach($data["detail_fname"] as $key => $val){
				$set_data = [
					"master_no" => $data["no"],
					"fname" => $val,
					"oname" => $data["detail_oname"][$key],
					"regDt" => date("Y-m-d H:i:s")
				];

				$get_data = table_data_match($this->_goods_img_table, $set_data);
				$result = $this->db->insert($this->_goods_img_table, $get_data);
			}
		}catch(Exception $e){

		}
	}
	/**
	 *@date 2018-10-10
	 *
	 *@author James
	 *
	 *다국어 상품 테이블 처리
	 *
	 *@param array $data 상품 데이터
	 *
	 *@return boolean
	 */
	public function set_multi_language_goods_process($data,$multiArr = array())
	{

		$result = true;
		if($data["multi"]){
			$tmpArr = $this->_site_language["support_language"];
		}else{
			$tmpArr = [
				"kor" => "kor"
			];
		}

		foreach($tmpArr as $languageKey => $languageVal){

			$set_data = $get_data = $arr_where = [];

			$set_data = [
				"language" => $languageKey,
				"master_no" => $data["no"],
				"regDt" => date("Y-m-d H:i:s"),
				"modDt" => date("Y-m-d H:i:s"),
			];

			foreach($multiArr as $mkey => $mval){
				$set_data[$mkey] = $mval[$languageKey];
			}

			$arr_where = [
				"language" => $languageKey,
				"master_no" => $data["no"],
			];

			foreach($multiArr as $fkey => $fval){
				if(is_array($fval)){
					$set_data[$fkey] = $fval[$languageKey];
				}else{
					$set_data[$fkey] = $fval;
				}
			}

			$this->db->from($this->_goods_multi_table);
			$this->db->where($arr_where);

			$select_rs = $this->db->get();

			$get_data = table_data_match($this->_goods_multi_table,$set_data);

			if($select_rs->num_rows()){
				unset($get_data["regDt"]);
				$rs = $this->db->update($this->_goods_multi_table, $get_data, $arr_where);
			}else{
				unset($get_data["modDt"]);
				$rs = $this->db->insert($this->_goods_multi_table, $get_data);
			}

			if($result === true){
				$result = $rs;
			}
		}

		return $result;
	}
	/*
	 * 상품 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function goods_delete($arr_where = null) {
		if(!isset($arr_where)) {
			return false;
		}
		db_where($arr_where);
		return $this->db->delete($this->_goods_table);
	}

	/**
	 *@date 2018-10-12
	 *@author James
	 *다국어 테이블 상품 삭제
	 */
	public function goods_multi_delete($arr_where = null)
	{
		if(!isset($arr_where)) {
			return false;
		}
		db_where($arr_where);
		return $this->db->delete($this->_goods_multi_table);
	}
	/*
	 * 상품 리스트정보 가져오기
	 *
	 *	@param string $cate 카테고리
	 *	@param array $arr_where 조건 ex) array(0 => array(field, value, operator, type))
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 */
	public function get_list_goods($cate = null,  $arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null, $day = null) {
		$get_data = array();  // return

		// 게시판 쿼리 시작
		$arr_include = array(
			"Ca.categorynm",
			"Ca.level",
			"Go.no",
			"Go.name",
			"Go.category",
			"Go.info",
			"Go.img1",
			"Go.img2",
			"Go.yn_state",
			"Go.sortNum",
			"Go.regdt",
			"Go.moddt",
			"Go.upload_path",
			"Go.upload_fname",
			"Go.upload_oname",
			"Go.ex1",
			"Go.ex2",
			"Go.ex3",
			"Go.ex4",
			"Go.ex5",
			"Go.ex6",
			"Go.ex7",
			"Go.ex8",
			"Go.ex9",
			"Go.ex10",
			"Go.ex11",
			"Go.ex12",
			"Go.ex13",
			"Go.ex14",
			"Go.ex15",
			"Go.ex16",
			"Go.ex17",
			"Go.ex18",
			"Go.ex19",
			"Go.ex20",
		);

		if(isset($cate)) {
			if(_CONNECT_PAGE != 'ADMIN') {
				$this->db->like("Go.category", $cate, "after", true);
			}else{
				$this->db->like("Cm.category", $cate, "after", true);
			}
		}

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
			$this->db->order_by("sortNum = ''","ASC");
			$this->db->order_by("sortNum","ASC");
		} else {
			$this->db->order_by("sortNum = ''","ASC");
			$this->db->order_by("sortNum","ASC");
			$this->db->order_by("regdt", "DESC");
		}

		$this->db->from($this->_goods_table ." AS Go");
		$this->db->join($this->_category_table ." AS Ca", "Go.category = Ca.category", "left");
		$this->db->limit($limit, $offset);

		if(_CONNECT_PAGE != 'ADMIN') {
			//다국어 사용 시
			$arr_delete = [
				"Go.name",
				"Go.info",
				"Go.ex1",
				"Go.ex2",
				"Go.ex3",
				"Go.ex4",
				"Go.ex5",
				"Go.ex6",
				"Go.ex7",
				"Go.ex8",
				"Go.ex9",
				"Go.ex10",
				"Go.ex11",
				"Go.ex12",
				"Go.ex13",
				"Go.ex14",
				"Go.ex15",
				"Go.ex16",
				"Go.ex17",
				"Go.ex18",
				"Go.ex19",
				"Go.ex20",
				"Ca.categorynm"
			];

			foreach($arr_delete as $val){
				if(($key = array_search($val,$arr_include)) !== false){
					unset($arr_include[$key]);
				}
			}

			array_push($arr_include, "Gm.name", "Gm.info", "Gm.info", "Gm.ex1", "Gm.ex2", "Gm.ex3", "Gm.ex4", "Gm.ex5", "Gm.ex6", "Gm.ex7", "Gm.ex8", "Gm.ex9", "Gm.ex10", "Gm.ex11", "Gm.ex12", "Gm.ex13", "Gm.ex14", "Gm.ex15", "Gm.ex16", "Gm.ex17", "Gm.ex18", "Gm.ex19", "Gm.ex20", "Cm.categorynm");

			$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no");
			$this->db->join($this->_category_multi_table ." AS Cm", "Ca.category = Cm.category");
			$this->db->group_by("Gm.no");
			if($this->_cfg_siteLanguage["multilingual"]){
				if(!is_array($this->_site_language)){
					$this->db->where("Gm.language",$this->_site_language);
					$this->db->where("Cm.language",$this->_site_language);
				}else{
					$this->db->where("Gm.language","kor");
					$this->db->where("Cm.language","kor");
				}
			}else{
				$this->db->where("Gm.language","kor");
				$this->db->where("Cm.language","kor");
			}
		}else{
			//if(_CONNECT_PAGE == 'ADMIN' && $this->_site_language['multilingual'] == 1 && !empty($this->_site_language['default']) && !is_array($this->_site_language['default'])){
			array_push($arr_include, "Gm.name as multi_name");
			if($this->_site_language['multilingual'] == 1) {
				if(isset($cate)) {
					$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no ");
				}else{
					$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no AND Gm.language = '".$this->_site_language['default']."'");
				}
			}else{
				$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no AND Gm.language = '".$this->_site_language['default']."'");
			}
			$this->db->join($this->_category_multi_table ." AS Cm", "Ca.category = Cm.category");
			$this->db->group_by("Go.no");
		}

		$this->db->select($arr_include);

		if($day) {
			$this->db->where("`Go`.`regdt` > ", 'date_add(now(),interval -'.$day.' day)', false);
		}

		$result = $this->db->get();

		if($result->result_id->num_rows) {
			$get_data["goods_list"] = $result->result_array();
		}

		if(isset($cate)) {
			if(_CONNECT_PAGE != 'ADMIN') {
				$this->db->like("Go.category", $cate, "after", true);
			}else{
				$this->db->like("Cm.category", $cate, "after", true);
			}
		}
		// 리스트 총 로우갯수 가져오기
		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}
		$this->db->from($this->_goods_table ." AS Go");
		$this->db->join($this->_category_table ." AS Ca", "Go.category = Ca.category");
		$this->db->select("Go.*");

		if(_CONNECT_PAGE != 'ADMIN') {
			$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no");
		}else{
			if($this->_site_language['multilingual'] == 1) {
				if(isset($cate)) {
					$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no ");
				}else{
					$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no AND Gm.language = '".$this->_site_language['default']."'");
				}
			}else{
				$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no AND Gm.language = '".$this->_site_language['default']."'");
			}
		}

		$this->db->join($this->_category_multi_table ." AS Cm", "Ca.category = Cm.category");

		if(_CONNECT_PAGE != 'ADMIN') {
			$this->db->group_by("Gm.no");
		}else{
			$this->db->group_by("Go.no");
		}

		$this->db->group_by("Cm.category");

		if(_CONNECT_PAGE != 'ADMIN') {
			if($this->_cfg_siteLanguage["multilingual"]){
				if(!is_array($this->_site_language)){
					$this->db->where("Gm.language",$this->_site_language);
					$this->db->where("Cm.language",$this->_site_language);
				}else{
					$this->db->where("Gm.language","kor");
					$this->db->where("Cm.language","kor");
				}
			}else{
				$this->db->where("Gm.language","kor");
				$this->db->where("Cm.language","kor");
			}
		}
		if($day) {
			$this->db->where("`Go`.`regdt` > ", 'date_add(now(),interval -'.$day.' day)', false);
		}
		$get_data["total_rows"] = $this->db->count_all_results();

		foreach($get_data['goods_list'] as $key => $val){
			foreach($val as $skey => $sval){
				if($sval && $sval != '' && $sval != null && !is_numeric($sval)){
					$temp = json_decode($sval, true);
					$errCheck = json_last_error();
					if($errCheck == JSON_ERROR_NONE) {
						$get_data['goods_list'][$key][$skey] = $temp['fname'];
						$get_data['goods_list'][$key][$skey.'_oname'] = $temp['oname'];
					}
				}
			}
		}

		return $get_data;
	}

	/*
	 * 상품 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@return array
	 */
	public function get_view_goods($arr_where = null) {
		$get_data = array();  // return

		// 게시판 쿼리 시작
		$arr_include = array(
			"Ca.categorynm",
			"Ca.level",
            "Ca.yn_state as cate_yn_state",
			"Go.no",
			"Go.name",
			"Go.category",
			"Go.info",
			"Go.img1",
			"Go.img2",
			"Go.yn_state",
			"Go.regdt",
			"Go.moddt",
			"Go.upload_path",
			"Go.upload_fname",
			"Go.upload_oname",
			"Go.ex1",
			"Go.ex2",
			"Go.ex3",
			"Go.ex4",
			"Go.ex5",
			"Go.ex6",
			"Go.ex7",
			"Go.ex8",
			"Go.ex9",
			"Go.ex10",
			"Go.ex11",
			"Go.ex12",
			"Go.ex13",
			"Go.ex14",
			"Go.ex15",
			"Go.ex16",
			"Go.ex17",
			"Go.ex18",
			"Go.ex19",
			"Go.ex20",
            "Go.use_seo",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_goods_table ." AS Go");
		$this->db->join($this->_category_table ." AS Ca", "Go.category = Ca.category");

		$result = $this->db->get();
		if($result->result_id->num_rows) {
			$get_data["goods_view"] = $result->last_row("array");

			//상세이미지
			$arrDetail = array(
				"fname",
				"oname"
			);

			$this->db->select($arrDetail);
			$this->db->where("master_no",$get_data["goods_view"]["no"]);
			$this->db->from($this->_goods_img_table);
			$this->db->order_by("no","ASC");
			$detail_rs = $this->db->get();

			if($detail_rs->result_id->num_rows){
				foreach($detail_rs->result_array() as $key => $val){
					$get_data["goods_view"]["detail_img"][] = $val["fname"];
					$get_data["goods_view"]["detail_img_oname"][] = $val["oname"];
				}
			}
			//다국어 사용 시
			if($this->_cfg_siteLanguage["multilingual"]){

				//다국어 상품 테이블 정보 select
				$arr_include = [
					"language",
					"name",
					"info"
				];

				for($i = 1; $i <= 20; $i++){
					$arr_include[] = "ex".$i;
				}

				$this->db->select($arr_include);
				$this->db->where(array("master_no" => $get_data["goods_view"]["no"]));
				$this->db->from($this->_goods_multi_table);

				$rs = $this->db->get();
				if($rs->result_id->num_rows){
					foreach($rs->result_array() as $row){
						$language = $row["language"];
						unset($row["language"]);
						foreach($row as $rkey => $rval){
							$get_data["goods_view"]["multi"][$rkey][$language] = $rval;
						}
					}
				}
			}

		}
		$temp = json_decode($get_data['goods_view']['img1'], true);
		$errCheck = json_last_error();
		if($errCheck == JSON_ERROR_NONE) {
			$get_data['goods_view']['img1'] = $temp['fname'];
			$get_data['goods_view']['img1_oname'] = $temp['oname'];
		}
		$temp = json_decode($get_data['goods_view']['img2'], true);
		$errCheck = json_last_error();

		if($errCheck == JSON_ERROR_NONE) {
			$get_data['goods_view']['img2'] = $temp['fname'];
			$get_data['goods_view']['img2_oname'] = $temp['oname'];
		}

        
		if($this->_cfg_siteLanguage["multilingual"]){
        # 다국어 사용시
            foreach($get_data['goods_view']['multi'] as $key => $val){
                if($key == 'info' || $key == 'name') continue;
                foreach($val as $skey => $sval){
                    if($sval && $sval != '' && $sval != null && !is_numeric($sval)) {
                        $temp = json_decode($sval, true);
                        $errCheck = json_last_error();
                        if($errCheck == JSON_ERROR_NONE) {
                            $get_data['goods_view']['multi'][$key][$skey] = $temp['fname'];
                            $get_data['goods_view'][$key.'_'.$skey.'_oname'] = $temp['oname'];
                        }
                    }
                }
            }
        } else {
        # 다국어 아닐 때 2020-06-10
            foreach($get_data['goods_view'] as $key => $val){
                if($key == 'info' || $key == 'name' || $key == 'detail_img' || $key == 'detail_img_oname') continue;
                if($val && $val != '' && $val != null && !is_numeric($val)) {
                    $temp = json_decode($val, true);
                    $errCheck = json_last_error();
                    if($errCheck == JSON_ERROR_NONE) {
                        $get_data['goods_view'][$key.'_oname'] = $temp['oname'];
                        $get_data['goods_view'][$key] = $temp['fname'];
                    }
                }
            }
        }

		return $get_data;
	}


	public function goods_count_check($arr_where = null, $arr_like = null) {
		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}
		$this->db->from($this->_goods_table);
		return $this->db->get()->num_rows();
	}

	/**
	 *@date 2018-10-04
	 *@author James
	 *카테고리 이름으로 카테고리 번호 찾기
	 */
	public function getCateNumByNm($categoryNm)
	{
		$this->db->where("categorynm",$categoryNm);
		$this->db->from($this->_category_table);

		$result = $this->db->get();

		if($result->num_rows()){
			return $result->row_array()["category"];
		}
	}

	/**
	 *@date 2018-10-04
	 *@author James
	 *상품 sort 번호 저장하기
	 */
	public function setGoodsSortNum($setData)
	{
		//sort update
		//yn_state update
		foreach($setData["goodsNo"] as $fkey => $fval){
			if(in_array($fval,$setData["yn_state"])){
				$yn_state = "y";
			}else{
				$yn_state = "n";
			}

            
            $set_data = [];
            $set_data["yn_state"] = $yn_state;
            if($setData["sortNum"][$fkey]) {
                $set_data["sortNum"] = $setData["sortNum"][$fkey];
            }

			$set_data = table_data_match($this->_goods_table,$set_data);
			$result = $this->db->update($this->_goods_table, $set_data,["no" => $fval]);
		}

		return true;
	}

	/**
	 *@date 2018-10-11
	 *@author James
	 *프론트 다국어 상품 상세 세팅
	 */
	public function set_multi_goods_view($goods_view,$language = "kor")
	{
		$arr_where = array(
			"language" => $language,
			"category" => $goods_view["category"],
		);

		$this->db->select(array("category","categorynm"));
		$this->db->from($this->_category_multi_table);
		$this->db->where($arr_where);

		$result = $this->db->get();

		if($result->result_id->num_rows){
			$row = $result->last_row("array");
			$goods_view["categorynm"] = $row["categorynm"];
		}

		$arrMultiKey = array(
			"name",
			"info",
		);

		for($i = 1;$i <= 20;$i++){
			$arrMultiKey[] = "ex".$i;
		}
		if(!is_array($this->_site_language)){
			foreach($arrMultiKey as $keyNm){
				$goods_view[$keyNm] = $goods_view["multi"][$keyNm][$this->_site_language];
			}
		}

		return $goods_view;
	}

	/**
	 *@date 2018-10-15
	 *@author James
	 *상품 조회수 상승
	 */
	public function set_hit_cnt_up($no)
	{
		$this->db->where("no",$no);
		$this->db->set("hitCnt","`hitCnt` + 1",FALSE);
		$this->db->update($this->_goods_table);
	}

	public function get_display_theme_goods_data($arr_where = null, $arr_orderby = null) {
		$get_data = array();  // return

		// 게시판 쿼리 시작
		$arr_include = array(
			"Go.no",
			"Go.name",
			"Go.category",
			"Go.info",
			"Go.img1",
			"Go.img2",
			"Go.yn_state",
			"Go.sortNum",
			"Go.regdt",
			"Go.moddt",
			"Go.upload_path",
			"Go.upload_fname",
			"Go.upload_oname",
			"Go.ex1",
			"Go.ex2",
			"Go.ex3",
			"Go.ex4",
			"Go.ex5",
			"Go.ex6",
			"Go.ex7",
			"Go.ex8",
			"Go.ex9",
			"Go.ex10",
			"Go.ex11",
			"Go.ex12",
			"Go.ex13",
			"Go.ex14",
			"Go.ex15",
			"Go.ex16",
			"Go.ex17",
			"Go.ex18",
			"Go.ex19",
			"Go.ex20",
		);

		if(isset($cate)) {
			$this->db->like("Go.category", $cate, "after", true);
		}

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
			$this->db->order_by("sortNum = ''","ASC");
			$this->db->order_by("sortNum","ASC");
		} else {
			$this->db->order_by("sortNum = ''","ASC");
			$this->db->order_by("sortNum","ASC");
			$this->db->order_by("regdt", "DESC");
		}

		if(_CONNECT_PAGE != 'ADMIN') {
			//다국어 사용 시
			$arr_delete = [
				"Go.name",
				"Go.info",
				"Go.ex1",
				"Go.ex2",
				"Go.ex3",
				"Go.ex4",
				"Go.ex5",
				"Go.ex6",
				"Go.ex7",
				"Go.ex8",
				"Go.ex9",
				"Go.ex10",
				"Go.ex11",
				"Go.ex12",
				"Go.ex13",
				"Go.ex14",
				"Go.ex15",
				"Go.ex16",
				"Go.ex17",
				"Go.ex18",
				"Go.ex19",
				"Go.ex20",
			];

			foreach($arr_delete as $val){
				if(($key = array_search($val,$arr_include)) !== false){
					unset($arr_include[$key]);
				}
			}

			array_push($arr_include,"Gm.name","Gm.info", "Gm.ex1", "Gm.ex2", "Gm.ex3", "Gm.ex4", "Gm.ex5", "Gm.ex6", "Gm.ex7", "Gm.ex8", "Gm.ex9", "Gm.ex10", "Gm.ex11", "Gm.ex12", "Gm.ex13", "Gm.ex14", "Gm.ex15", "Gm.ex16", "Gm.ex17", "Gm.ex18", "Gm.ex19", "Gm.ex20");

			$this->db->join($this->_goods_multi_table ." AS Gm", "Go.no = Gm.master_no");
			$this->db->group_by("Gm.no");
			if($this->_cfg_siteLanguage["multilingual"]){
				if(!is_array($this->_site_language)){
					$this->db->where("Gm.language",$this->_site_language);
				}else{
					$this->db->where("Gm.language","kor");
				}
			}else{
				$this->db->where("Gm.language","kor");
			}
		}

		array_push($arr_include, "dtg.`no` AS n");
		$this->db->select($arr_include);
		$this->db->from($this->_goods_table ." AS Go");
		$this->db->join($this->_display_theme_goods_table ." AS dtg", "Go.no = dtg.goods_no", "inner");

		$result = $this->db->get();
		if($result->result_id->num_rows) {
			$get_data["display_main_list"] = $result->result_array();
		}
		foreach($get_data['display_main_list'] as $key => $val){
			foreach($val as $skey => $sval){
				if($sval && $sval != '' && $sval != null && !is_numeric($sval)){
					$temp = json_decode($sval, true);
					$errCheck = json_last_error();
					if($errCheck == JSON_ERROR_NONE) {
						$get_data['display_main_list'][$key][$skey] = $temp['fname'];
						$get_data['display_main_list'][$key][$skey.'_oname'] = $temp['oname'];
					}
				}
			}
		}

		return $get_data;
	}

	/*
	 * 메인페이지 상품진열 페이지 가져오기
	 *
	 *	@param array $arr_where 조건 ex) array(0 => array(field, value, operator, type))
	 *	@param array $arr_like 조건
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *	@pamra array $arr_orderby 정렬
	 */
	public function get_list_display_theme($arr_where = null, $arr_like = null, $limit = null, $offset = null, $arr_orderby = null) {
		$get_data = array();  // return

		// 게시판 쿼리 시작
		$arr_include = array(
			"dt.no",
			"dt.theme_name",
			"dt.theme_description",
			"dt.skin_type",
			"dt.regdt",
			"dt.moddt",
			"DATE_FORMAT(dt.regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(dt.moddt, '%Y-%m-%d') AS moddt_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			$this->db->order_by($arr_orderby);
			$this->db->order_by("regdt","DESC");
		} else {
			$this->db->order_by("regdt", "DESC");
		}

		$this->db->select($arr_include);
		$this->db->from($this->_display_theme_table ." AS dt");
		$this->db->limit($limit, $offset);

		$result = $this->db->get();
		if($result->result_id->num_rows) {
			$get_data["display_main_list"] = $result->result_array();
		}

		// 리스트 총 로우갯수 가져오기
		if(isset($arr_where)) {
			db_where($arr_where);
		}
		if(isset($arr_like)) {
			db_like($arr_like);
		}

		$this->db->from($this->_display_theme_table ." AS dt");
		$get_data["total_rows"] = $this->db->count_all_results();

		return $get_data;
	}



	/*
	 * 상품 상세정보 가져오기
	 *
	 *	@param array $arr_where 조건
	 *	@return array
	 */
	public function get_view_display_theme($arr_where = null) {
		$get_data = array();  // return

		// 게시판 쿼리 시작
		$arr_include = array(
			"dt.no",
			"dt.theme_name",
			"dt.theme_description",
			"dt.skin_type",
			"dt.regdt",
			"dt.moddt",
			"DATE_FORMAT(dt.regdt, '%Y-%m-%d') AS regdt_date",
			"DATE_FORMAT(dt.moddt, '%Y-%m-%d') AS moddt_date",
		);

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		$this->db->select($arr_include);
		$this->db->from($this->_display_theme_table ." AS dt");

		$result = $this->db->get();
		if($result->result_id->num_rows) {
			$get_data["display_main"] = $result->last_row("array");
		}
		return $get_data;
	}

	/*
	 * 상품진열 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function delete_display_theme($arr_where = null) {
		if(!isset($arr_where)) {
			return false;
		}
		db_where($arr_where);
		return $this->db->delete($this->_display_theme_table);
	}

	/*
	 * 상품진열상품 삭제
	 *
	 *	@param array $arr_where 조건
	 *
	 *	@return boolean
	 */
	public function delete_display_theme_goods($arr_where = null) {
		if(!isset($arr_where)) {
			return false;
		}

		db_where($arr_where);
		return $this->db->delete($this->_display_theme_goods_table);
	}

	protected function goods_multi_register($data, $no) {
		$multiData = [];
		foreach($this->_site_language['support_language'] as $key => $value) {
			if($this->_site_language["multilingual"] && $data["multi"]) {
				if(!$data['name_'.$key]) {
					$multiData[] = [
						'language' => $key,
						'master_no' => $no,
						'name' => $data['name_kor']
					];
				}
			} else {
				if($key != 'kor') {
					$multiData[] = [
						'language' => $key,
						'master_no' => $no,
						'name' => $data['name']
					];
				}
			}
		}

		if($this->_site_language["multilingual"]) {
			foreach($multiData as $key => $value) {
				$this->db->where('language', $value['language']);
				$this->db->where('master_no', $value['master_no']);
				$this->db->update($this->_goods_multi_table, ['name' => $value['name']]);
			}
		} else {
			foreach($multiData as $key => $value) {
				$res = $this->db->insert($this->_goods_multi_table, $value);
			}
		}
	}
}