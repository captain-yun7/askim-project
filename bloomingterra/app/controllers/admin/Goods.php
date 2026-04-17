<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends ADMIN_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("Admin_Goods_model");
		$this->load->model("category_model");
        $this->load->model('Database_model', 'dm');

		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$access_function = explode('/', $request_uri)[count(explode('/', $request_uri)) - 1];

	}

	public function goods_list() {
		try{
			$this->load->library("form_validation");
			$this->load->library("pagination");
			$this->config->load("cfg_goodsField");

			$search_type = $this->input->get("search_type", true);
			$sort_type = $this->input->get("sort_type", true);
			$search = $this->input->get("search", true);
			$per_page = $this->input->get("per_page", true);

			$arr_where = array();
			$arr_like = array();
			$order_key = [
				"regDt",
				"hitCnt",
			];

			$where_key = [
				"category"
			];
			if(isset($search) && $search && !in_array($search_type,$order_key) && !in_array($search_type,$where_key)) {
				$search_type = $search_type ? $search_type : "Go.no";
				$arr_like[] = array($search_type, $search);
			}else if(in_array($search_type,$order_key)){
				$arr_order_by = $search_type." DESC";
			}else if(isset($search) && $search && in_array($search_type,$where_key)){
				if($search_type == "category"){
					$category = $this->Admin_Goods_model->getCateNumByNm($search);

					if(isset($category) && $category){
						$arr_where[] = array("Go.".$search_type,$category);
					}
				}
			}

			if(!empty($sort_type)){
				$arr_order_by = $sort_type." DESC";
			}

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 10;
			$offset = ($per_page - 1) * $limit;

			$get_data = $this->Admin_Goods_model->get_list_goods(null, $arr_where, $arr_like, $limit, $offset, $arr_order_by);

			$get_data["goodsField"] = $this->config->item("goodsField");
			$get_data["offset"] = $offset;
			$get_data['search'] = $search;

			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?search_type=". $search_type ."&search=". $search."&sort_type=".$sort_type,
				"suffix" => "&search_type=". $search_type ."&search=". $search."&sort_type=".$sort_type,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();
			$this->set_view("admin/goods/goods_list", $get_data);

		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
	// 상품필드셋팅
	public function goods_field() {
		$this->load->library("form_validation");
		try{
			$mode = $this->input->post("mode", true);
			if(isset($mode)) {
				$nameField = $this->input->post("nameField", true);
				$useField = $this->input->post("useField", true);
				$reqField = $this->input->post("reqField", true);
				$optionField = $this->input->post("optionField", true);

				$multiField = [
					"name" => "checked",
					"info" => "checked",
					"ex1"  => "checked",
					"ex2"  => "checked",
					"ex3"  => "checked",
					"ex4"  => "checked",
					"ex5"  => "checked",
					"ex6"  => "checked",
					"ex7"  => "checked",
					"ex8"  => "checked",
					"ex9"  => "checked",
					"ex10"  => "checked",
					"ex11"  => "checked",
					"ex12"  => "checked",
					"ex13"  => "checked",
					"ex14"  => "checked",
					"ex15"  => "checked",
					"ex16"  => "checked",
					"ex17"  => "checked",
					"ex18"  => "checked",
					"ex19"  => "checked",
					"ex20"  => "checked",
				];

				if(!isset($nameField) || !isset($nameField) || !isset($reqField) || !isset($optionField)) {
					throw new Exception("상품필드 정보가 없습니다.");
				}
				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";

				$set_data .= "\$config = array(\n";
				$set_data .= "\t'goodsField' => array(\n";
				$set_data .= "\t\t'name' => array(\n";
				foreach ($nameField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'use' => array(\n";
				foreach ($useField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'require' => array(\n";
				foreach ($reqField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach ($language_value as $key => $value) {
						$set_data .= "\t\t\t\t'$key'	=> '$value',\n";
					}
					$set_data .= "\t\t\t),\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'multi' => array(\n";
				foreach ($multiField as $key => $value) {
					$set_data .= "\t\t\t'$key'	=> '$value',\n";
				}
				$set_data .= "\t\t),\n";

				$set_data .= "\t\t'option' => array(\n";
				foreach($optionField as $language_key => $language_value) {
					$set_data .= "\t\t\t'$language_key'	=> array(\n";
					foreach($language_value as $columnKey => $columnValue){
						$set_data .= "\t\t\t\t'$columnKey'	=> array(\n";
						foreach($columnValue as $key => $value){
							if($key != "itemValue" && $key != "itemName"){
								$set_data .= "\t\t\t\t\t'$key' => '$value',\n";
							}
						}
						$set_data .= "\t\t\t\t\t'item' => array(\n";
						if(!empty($columnValue["itemName"]) && !empty($columnValue["itemValue"]) && is_array($columnValue["itemName"]) && is_array($columnValue["itemValue"])){
							for($i = 0; $i < count($columnValue["itemName"]); $i++){
								$set_data .= "\t\t\t\t\t\t'".$columnValue['itemName'][$i]."' => '".$columnValue['itemValue'][$i]."',\n";
							}
						}
						$set_data .= "\t\t\t\t\t),\n";
						$set_data .= "\t\t\t\t),\n";
					}
					$set_data .= "\t\t\t),\n";
				}

				$set_data .= "\t\t),\n";
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_goodsField.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "/admin/goods/goods_field");
			} else {
				$this->config->load("cfg_goodsField");
				$this->config->load("cfg_uploadValidate");

				$get_data = array();
				$get_data["goodsField"] = $this->config->item("goodsField");
				$uploadValidate = $this->config->item("cfg_uploadValidate");
				$extension = array_keys($uploadValidate["extension"]);
				$get_data["extension"] = array_diff($extension, array("favicon", "snsImage", "sitemap"));

				$get_data["arr_ex"] = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
										"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드

				$get_data["readonly"] = array("img1", "img2", "info", "upload_fname", "detail_img"); //변경불가

				$get_data["fieldset"] = array(
					"default"	=> array( // 기본
						"no",
						"name",
						"category",

					), "hidden" => array( // 숨김처리
						"yn_state",
						"regdt",
						"moddt",
						'upload_path',
						'upload_oname',
					), "etc" => array( // 기타
						"img1",
						"detail_img",
						'upload_fname',
						"info",
						"ex1",
						"ex2",
						"ex3",
						"ex4",
						"ex5",
						"ex6",
						"ex7",
						"ex8",
						"ex9",
						"ex10",
						"ex11",
						"ex12",
						"ex13",
						"ex14",
						"ex15",
						"ex16",
						"ex17",
						"ex18",
						"ex19",
						"ex20",
					),
					"etc2" => array(
						"img2",
					),
				);

				$get_data["mode"] = "register";
				$get_data["multiUseArr"] = [
					"name",
					"info"
				];

				$this->set_view("admin/goods/goods_field", $get_data);

			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
	public function goods_reg() {
		try{
			$this->load->library("form_validation");
			$this->config->load("cfg_goodsField");
			$no = $this->input->get("no", true);
			$mode = $this->input->post("mode", true);
			$site_language = "kor";

			if(isset($mode)) {

				$goodsField = $this->config->item("goodsField");

				if($mode == "modify") {
					$this->form_validation->set_rules("no", $goodsField["name"][$site_language]["no"], "required|trim|xss_clean|is_natural_no_zero");
				}

				$this->form_validation->set_rules("category1", $goodsField["name"][$site_language]["category1"], "required|trim|xss_clean|exact_length[3]|is_natural");
				$this->form_validation->set_rules("category2", $goodsField["name"][$site_language]["category2"], "trim|xss_clean|exact_length[6]");
				$this->form_validation->set_rules("category3", $goodsField["name"][$site_language]["category3"], "trim|xss_clean|exact_length[9]");
				$this->form_validation->set_rules("category4", $goodsField["name"][$site_language]["category4"], "trim|xss_clean|exact_length[12]");
				$this->form_validation->set_rules("category5", $goodsField["name"][$site_language]["category5"], "trim|xss_clean|exact_length[15]");
				if($this->_site_language["multilingual"]){
					foreach($this->_site_language["set_language"] as $languageKey => $languageVal){
						$this->form_validation->set_rules("name_".$languageKey, $goodsField["name"][$site_language]["name"], "required|trim|xss_clean");
						$this->form_validation->set_rules("info_".$languageKey, $goodsField["name"][$site_language]["info"], "trim".(isset($goodsField["use"][$languageKey]["info"]) && isset($goodsField["require"][$languageKey]["info"]) ? "|required" : ""));
					}
				}else{
					$this->form_validation->set_rules("name", $goodsField["name"][$site_language]["name"], "required|trim|xss_clean");
					$this->form_validation->set_rules("info", $goodsField["name"][$site_language]["info"], "trim".(isset($goodsField["use"][$site_language]["info"]) && isset($goodsField["require"][$site_language]["info"]) ? "|required" : ""));
				}

				for($idx=1;$idx<=20;$idx++){
					if($this->_site_language["multilingual"] && !empty($goodsField["multi"]["ex".$idx])){
						foreach($this->_site_language["set_language"] as $languageKey => $languageVal){
							$this->form_validation->set_rules("ex".$idx."_".$languageKey . ((isset($goodsField["option"][$languageKey]["ex".$idx]["type"]) && $goodsField["option"][$languageKey]["ex".$idx]["type"] == "file") ? "_fname" : ""), $goodsField["name"][$languageKey]["ex".$idx], "trim". ($goodsField["option"][$languageKey]["ex".$idx]["type"] != "editor" ? "|xss_clean" : "") . (isset($goodsField["use"][$languageKey]["ex".$idx]) && isset($goodsField["require"][$languageKey]["ex".$idx]) ? "|required" : ""));
							if($goodsField["option"][$languageKey]["ex".$idx]["type"] == "file"){
								$_POST["ex".$idx."_".$languageKey] = $_POST["ex".$idx."_".$languageKey."_fname"];
							}
						}
					}else{
						$this->form_validation->set_rules("ex".$idx . (isset($goodsField["option"][$site_language]["ex".$idx]["type"]) && $goodsField["option"][$site_language]["ex".$idx]["type"] == "file" ? "_fname" : ""), $goodsField["name"][$site_language]["ex".$idx], "trim". ($goodsField["option"][$site_language]["ex".$idx]["type"] != "editor" ? "|xss_clean" : "") .(isset($goodsField["use"][$site_language]["ex".$idx]) && isset($goodsField["require"][$site_language]["ex".$idx]) ? "|required" : ""));
					}
				}

				$this->form_validation->set_rules("img1_fname", $goodsField["name"][$site_language]["img1"], "trim|xss_clean". (isset($goodsField["use"][$site_language]["img1"]) && isset($goodsField["require"][$site_language]["img1"]) ? "|required" : ""));
				$this->form_validation->set_rules("img2_fname", $goodsField["name"][$site_language]["img2"], "trim|xss_clean". (isset($goodsField["use"][$site_language]["img2"]) && isset($goodsField["require"][$site_language]["img2"]) ? "|required" : ""));
				$this->form_validation->set_rules("yn_state", $goodsField["name"][$site_language]["img2"], "required|trim|xss_clean");
				$this->form_validation->set_rules("upload_path", $goodsField["name"][$site_language]["upload_path"], "trim|xss_clean". (isset($goodsField["use"][$site_language]["upload_fname"]) && isset($goodsField["require"][$site_language]["upload_fname"]) ? "|required" : ""));
				$this->form_validation->set_rules("upload_fname_fname", $goodsField["name"][$site_language]["upload_fname"], "trim|xss_clean". (isset($goodsField["use"][$site_language]["upload_fname"]) && isset($goodsField["require"][$site_language]["upload_fname"]) ? "|required" : ""));
				$this->form_validation->set_rules("upload_fname_oname", $goodsField["name"][$site_language]["upload_oname"], "trim|xss_clean". (isset($goodsField["use"][$site_language]["upload_fname"]) && isset($goodsField["require"][$site_language]["upload_fname"]) ? "|required" : ""));



				// 메인페이지 상품진열 셋팅
				$this->form_validation->set_rules("display_theme_no", "메인페이지 상품진열", "trim|xss_clean");

				if($this->form_validation->run()){
					$set_data = $this->input->post();

					$set_data['upload_fname'] = $set_data['upload_fname_fname'];
					$set_data['upload_oname'] = $set_data['upload_fname_oname'];

					$tempArr['img1']['oname'] = $set_data["img1_oname"];
					$tempArr['img1']['fname'] = $set_data["img1_fname"];
					$tempArr['img2']['oname'] = $set_data["img2_oname"];
					$tempArr['img2']['fname'] = $set_data["img2_fname"];
					$set_data["img1"] = json_encode($tempArr['img1'], JSON_UNESCAPED_UNICODE);
					$set_data["img2"] = json_encode($tempArr['img2'], JSON_UNESCAPED_UNICODE);

                    
                    if($this->_site_language["multilingual"]){
                        foreach($goodsField['use'] as $key => $val){
                            foreach($val as $skey => $sval){
                                if(($goodsField['option'][$key][$skey]['type'] == 'file' || $goodsField['option'][$key][$skey]['type'] == 'image') && in_array($skey, array_keys($goodsField['multi']))) {
                                    if($set_data[$skey.'_'.$key]) {
                                        $tempArr[$skey.'_'.$key]['oname'] = $set_data[$skey.'_'.$key.'_oname'];
                                        $tempArr[$skey.'_'.$key]['fname'] = $set_data[$skey.'_'.$key.'_fname'];
                                        $set_data[$skey.'_'.$key] = json_encode($tempArr[$skey.'_'.$key], JSON_UNESCAPED_UNICODE);
                                    }
                                }
                            }
                        }
                    } else {
                        foreach($goodsField['use'][$site_language] as $_field => $_checked) {
                            if($goodsField['option'][$site_language][$_field]['type'] == 'file' || $goodsField['option'][$site_language][$_field]['type'] == 'image') {
                                if($_field == "upload_fname" || $_field == "upload_oname") { continue; }
                                $tempArr[$_field]['oname'] = $set_data[$_field.'_oname'];
                                $tempArr[$_field]['fname'] = $set_data[$_field.'_fname'];
                                $set_data[$_field] = json_encode($tempArr[$_field], JSON_UNESCAPED_UNICODE);
                            }
                        }
                    }

					$result = $this->Admin_Goods_model->goods_register($mode, $set_data);
					if($result["result"]) {
                        $post = $this->input->post(null, true);
                        $goodsno = $post['no'] > 0 ? $post['no'] : $result['no'];
                        $languages = ['kor', 'eng', 'chn', 'jpn'];
                        foreach($languages as $val) {
                            if ($post[$val . '_title'] || $post[$val . '_author'] || $post[$val . '_description'] || $post[$val . '_keywords']) {
                                $variables = [
                                    'language' => $val,
                                    'goodsno' => $goodsno,
                                    'title' => $post[$val . '_title'],
                                    'author' => $post[$val . '_author'],
                                    'description' => $post[$val . '_description'],
                                    'keywords' => $post[$val . '_keywords'],
                                ];
                                if ($post[$val . '_no'] > 0) {
                                    $this->dm->update('da_goods_seo', ['no' => $post[$val . '_no']], $variables);
                                } else {
                                    $this->dm->insert('da_goods_seo', $variables);
                                }
                            }
                        }

						// category
						$categories = [];
						foreach($post['category'] as $value) {
							$categories[] = [
								'language' => 'kor',
								'goodsno' => $goodsno,
								'category' => $value,
							];
						}
						$this->dm->insert('da_goods_category', $categories, false, true);

						if($mode == "register") {
							redirect("/admin/goods/goods_reg?no=". $result["no"]);
						} else {
							msg("수정되었습니다.", "goods_reg?no=". $set_data["no"]."&".$this->input->post("ref", true));
						}
					} else {
						$error = $this->db->error();
						throw new Exception($error["message"]);
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				if(isset($no)) { // 수정
					$arr_where = array();
					$arr_where[] = array("no", $no);
					$get_data = $this->Admin_Goods_model->get_view_goods($arr_where);
					$get_data["mode"] = "modify";
				} else { // 등록
					$get_data["mode"] = "register";
				}
				$goodsField = $this->config->item("goodsField");
				$arrMulti = array(
					"info",
					"ex1",
					"ex2",
					"ex3",
					"ex4",
					"ex5",
					"ex6",
					"ex7",
					"ex8",
					"ex9",
					"ex10",
					"ex11",
					"ex12",
					"ex13",
					"ex14",
					"ex15",
					"ex16",
					"ex17",
					"ex18",
					"ex19",
					"ex20",
				);
				if($this->_site_language["multilingual"]){
					foreach($arrMulti as $multiKey){
						if(array_key_exists($multiKey, $goodsField["option"])){
							foreach($this->_site_language["set_language"] as $key => $value){
								$goodsField["option"][$multiKey."_".$key] = $goodsField["option"][$multiKey];
							}
						}
					}

				}

				$temp_seo = $this->dm->get('da_goods_seo', [], ['goodsno' => $no]);
                foreach($temp_seo as $key => $value) {
                    $get_data['seo'][$value['language']] = $value;
                }

				$this->load->library('qfile');
				$this->qfile->open(APPPATH.'/config/json_categories.json');
				$get_data['categories'] = json_decode($this->qfile->read(), true);
				//debug($get_data['categories']);

				$temp_categories = $this->dm->get('da_goods_category', [], ['goodsno' => $no]);
				foreach($temp_categories as $value) {
					$length = strlen($value['category']) / 3;
					for($i = 0; $i < $length; $i++) {
						$j = $i * 3;
						//debug(substr($value['category'], 0, $j));
					}
					//debug($value['category']);
				}

				$get_data["fieldOption"] = $goodsField;
				$get_data["goodsField"] = $this->config->item("goodsField");
				$get_data = array_merge($get_data, $this->Admin_Goods_model->get_list_display_theme());

				//upload max file size
				$get_data["max_file_uploads"] = ini_get("max_file_uploads");
				$get_data["site_language"] = $site_language;
				$get_data['ref'] = http_build_query($this->input->get(null, true));
				$this->set_view("admin/goods/goods_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function goods_delete() {
		try{
			if(!$this->input->post("no", true)) {
				throw new Exception("상품의 정보를 찾을 수 없습니다.");
			}

			$no = $this->input->post("no", true);
			$arr_where = $arr_multi_where = array();
			$arr_where[] = array("no", $no, "IN");
			$arr_multi_where[] = array("master_no", $no, "IN");
			$arr_theme_where[] = array("goods_no", $no, "IN");

			$result = $this->Admin_Goods_model->goods_delete($arr_where);

			if($result) {
				$this->Admin_Goods_model->goods_multi_delete($arr_multi_where);
				$this->Admin_Goods_model->delete_display_theme_goods($arr_theme_where);
				msg("상품이 삭제되었습니다.", "goods_list");
			} else {
				throw new Exception("상품의 삭제를 실패하였습니다.\n\n잠시후 다시 시도해주세요.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}

	}

	/**
	 *@date 2018-10-04
	 *@author James
	 *상품 리스트 저장 컨트롤러
	 */
	public function goods_list_save() {
		try{
			if(!$this->input->post("goodsNo",true)){
				throw new Exception("상품의 정보를 찾을 수 없습니다.");
				return false;
			}

			$goodsNo = $this->input->post("goodsNo",true);
			$sortNum = $this->input->post("sortNum",true);
			$yn_state = $this->input->post("yn_state",true);

			$set_data = [
				"goodsNo"=>$goodsNo,
				"sortNum"=>$sortNum,
				"yn_state"=>$yn_state,
			];
			$result = $this->Admin_Goods_model->setGoodsSortNum($set_data);

			if($result) {
				msg("수정되었습니다.", "/admin/goods/goods_list");
			} else {
				$error = $this->db->error();
				throw new Exception($error["message"]);
			}

		}catch(Exception $e){
			msg($e->getMessage(),-1);
		}
	}

	public function category_list() {
		try{
			$this->load->library("form_validation");

			$mode = $this->input->post("mode", true);
			if(isset($mode)) {
				$this->form_validation->set_rules("category1", "카테고리코드", "required|trim|xss_clean|exact_length[3]|is_natural");
				$this->form_validation->set_rules("category2", "카테고리코드", "trim|xss_clean|exact_length[6]");
				$this->form_validation->set_rules("category3", "카테고리코드", "trim|xss_clean|exact_length[9]");
				$this->form_validation->set_rules("category4", "카테고리코드", "trim|xss_clean|exact_length[12]");
				$this->form_validation->set_rules("category5", "카테고리코드", "trim|xss_clean|exact_length[15]");
				if($this->_site_language["multilingual"]){
					foreach($this->_site_language["set_language"] as $key => $val){
						$this->form_validation->set_rules("categorynm_".$key, "카테고리명", "required|trim|xss_clean|max_length[20]");
					}
				}else{
					$this->form_validation->set_rules("categorynm", "카테고리명", "required|trim|xss_clean|max_length[20]");
				}
				$this->form_validation->set_rules("sort", "순서", "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("yn_use", "사용유무", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_state", "활성유무", "required|trim|xss_clean");
				$this->form_validation->set_rules("access_auth", "접근권한", "required|trim|xss_clean");
				if($this->form_validation->run()){
					$set_data = $this->input->post(null, true);
					unset($set_data["moddt"]);

					$result = $this->category_model->category_register($mode, $set_data);
					if($result) {
                        $this->make_json_category();
						msg("수정되었습니다.", "/admin/goods/category_list");
					} else {
						$error = $this->db->error();
						throw new Exception($error["message"]);
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				$get_data = $this->category_model->get_category_tree();
				$get_data["mode"] = "modify";
				#$get_data["member_grade_list"] = get_list_member_grade();

				$arr_where = array();
				$arr_where[] = array("level <=", $this->_admin_member["level"]);
				$grade_list = get_list_member_grade($arr_where);

				$get_data["member_grade_list"] = $get_data["admin_grade_list"] = array();
				foreach($grade_list as $key => $val){
					if(in_array($val["level"], array_keys($this->_adm_auth))){
						$get_data["admin_grade_list"][] = $val;
					}else{
						$get_data["member_grade_list"][] = $val;
					}
				}

				$this->set_view("admin/goods/category_list", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function category_reg() {
		try {
			$this->load->library("form_validation");
			$mode = $this->input->post("mode", true);
			if(isset($mode)) {
				$this->form_validation->set_rules("category1", "카테고리코드", "trim|xss_clean|exact_length[3]");
				$this->form_validation->set_rules("category2", "카테고리코드", "trim|xss_clean|exact_length[6]");
				$this->form_validation->set_rules("category3", "카테고리코드", "trim|xss_clean|exact_length[9]");
				$this->form_validation->set_rules("category4", "카테고리코드", "trim|xss_clean|exact_length[12]");
				$this->form_validation->set_rules("category5", "카테고리코드", "trim|xss_clean|exact_length[15]");
				if($this->_site_language["multilingual"]){
					foreach($this->_site_language["set_language"] as $key => $val){
						$this->form_validation->set_rules("categorynm_".$key, "카테고리명", "required|trim|xss_clean|max_length[20]");
					}
				}else{
					$this->form_validation->set_rules("categorynm", "카테고리명", "required|trim|xss_clean|max_length[20]");
				}

				$this->form_validation->set_rules("sort", "순서", "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("yn_use", "사용유무", "required|trim|xss_clean");
				$this->form_validation->set_rules("yn_state", "활성유무", "required|trim|xss_clean");
				if($this->form_validation->run()){
					$set_data = $this->input->post(null, true);
					$result = $this->category_model->category_register($mode, $set_data);
					if($result) {
                        $this->make_json_category();
						msg("등록되었습니다.", "category_reg");
					} else {
						$error = $this->db->error();
						throw new Exception($error["message"]);
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				$get_data = $this->category_model->get_category_tree();
				$get_data["mode"] = "register";
				#$get_data["member_grade_list"] = get_list_member_grade();

				$arr_where = array();
				$arr_where[] = array("level <=", $this->_admin_member["level"]);
				$grade_list = get_list_member_grade($arr_where);

				$get_data["member_grade_list"] = $get_data["admin_grade_list"] = array();
				foreach($grade_list as $key => $val){
					if(in_array($val["level"], array_keys($this->_adm_auth))){
						$get_data["admin_grade_list"][] = $val;
					}else{
						$get_data["member_grade_list"][] = $val;
					}
				}

				$this->set_view("admin/goods/category_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}


	public function category_delete() {
		try{
			$this->load->library("form_validation");

			$this->form_validation->set_rules("category1", "카테고리코드", "required|trim|xss_clean|exact_length[3]");
			$this->form_validation->set_rules("category2", "카테고리코드", "trim|xss_clean|exact_length[6]");
			$this->form_validation->set_rules("category3", "카테고리코드", "trim|xss_clean|exact_length[9]");
			$this->form_validation->set_rules("category4", "카테고리코드", "trim|xss_clean|exact_length[12]");
			$this->form_validation->set_rules("category5", "카테고리코드", "trim|xss_clean|exact_length[15]");

			if($this->form_validation->run()) {
				if($this->input->post("category5", true)) {
					$cate = $this->input->post("category5", true);
				} else if($this->input->post("category4", true)) {
					$cate = $this->input->post("category4", true);
				} else if($this->input->post("category3", true)) {
					$cate = $this->input->post("category3", true);
				} else if($this->input->post("category2", true)) {
					$cate = $this->input->post("category2", true);
				} else {
					$cate = $this->input->post("category1", true);
				}

				$this->category_model->initialize($cate);
				$result = $this->category_model->category_delete();
				if($result) {
                    $this->make_json_category();
					msg("삭제하였습니다.", "/admin/goods/category_list");
				} else {
					$error = $this->db->error();
					throw new Exception($error["message"]);
				}
			} else {
				throw new Exception(validation_errors());
			}
			throw new Exception("카테고리 정보가 없습니다.");
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function category_goods_empty_check() {
		try{
			if(!defined("_IS_AJAX")) {
				msg("접근할 수 없는 페이지입니다.", -1);
			}

			$cate = $this->input->post_get("category", true);
			$this->category_model->initialize($cate);
			$this->load->model("Admin_Goods_model");
			$arr_where = array();
			$arr_where[] = array("category", $cate);
			$get_data = array();
			$get_data["cnt"] = $this->Admin_Goods_model->goods_count_check($arr_where);
			echo json_encode(array("code" => true, "data" => $get_data));
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}

	public function select_category() {
		try{
			if(!defined("_IS_AJAX")) {
				msg("접근할 수 없는 페이지입니다.", -1);
			}

			$cate = $this->input->post_get("category", true);
			$level = $this->input->post_get("level", true);
			if(!isset($cate) || !isset($level)) {
				echo json_encode(array("code" => false, "error" => "카테고리 정보가 없습니다."));
				exit;
			}

			$get_data = $this->category_model->get_select_category($cate, $level);
			echo json_encode(array("code" => true, "data" => $get_data));
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}

	public function category_info() {
		try{
			if(!defined("_IS_AJAX")) {
				msg("접근할 수 없는 페이지입니다.", -1);
			}

			$cate = $this->input->post_get("category", true);
			$this->category_model->initialize($cate);
			echo json_encode(array("code" => true, "data" => $this->category_model->get_category()));
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}


	public function goods_state_change() {
		try{
			if(!defined("_IS_AJAX")) {
				msg("접근할 수 없는 페이지입니다.", -1);
			}

			$no = $this->input->post("no", true);
			$yn_state = $this->input->post("yn_state", true);

			if(!$no || !$yn_state) {
				throw new Exception("상품의 정보를 찾을 수 없습니다.");
			}

			$result = $this->db->update("da_goods", array("yn_state" => $yn_state), array("no" => $no));
			if($result) {
				echo json_encode(array("code" => true));
			} else {
				$error = $this->db->error();
				throw new Exception($error["message"]);
			}
		} catch(Exception $e) {
			echo json_encode(array("code" => false, "error" => $e->getMessage()));
		}
	}

	public function category_pop() {
		$datas = $this->input->post(null, true);
		if($datas['goods'] && $datas['category']) {
			if($this->Admin_Goods_model->set_category($datas)) {
				msg("정상적으로 변경되었습니다.", "close");
			} else {
				msg("오류가 발생하였습니다.");
			}
		}
		$this->load->view("admin/goods/category_pop");
	}

	public function copy_goods() {
		$no = explode("|", $this->input->get("no", true));
		foreach($no as $v) {
			// da_goods
			$goods = $this->Admin_Goods_model->get_datas("da_goods", ["no" => $v]);
			$data_goods = $data_goods_image = $data_goods_multi = [];
			$data_goods['name'] = $goods['name'];
			$data_goods['category'] = $goods['category'];
			$data_goods['info'] = $goods['info'];
			if($goods['img1']) $data_goods['img1'] = $this->copy_file("img1", $goods['img1'], "y");
			if($goods['img2']) $data_goods['img2'] = $this->copy_file("img2", $goods['img2'], "y");
			$data_goods['yn_state'] = $goods['yn_state'];
			$data_goods['hitCnt'] = $goods['hitCnt'];
			$data_goods['sortNum'] = $goods['sortNum'];
			$data_goods['regdt'] = date("Y-m-d H:i:s");
			$data_goods['moddt'] = date("Y-m-d H:i:s");
			$data_goods['upload_path'] = $goods['upload_path'];
			if($goods['upload_fname']) $data_goods['upload_fname'] = $this->copy_file("file", $goods['upload_fname'], "n");
			$data_goods['upload_oname'] = $goods['upload_oname'];

			for($i = 1; $i <= 20; $i++) {
				$tmp = json_decode($goods['ex'.$i]);
				if(is_object($tmp)) {
					$data_goods['ex'.$i] = $tmp->fname ? $this->copy_file("ex".$i, $goods['ex'.$i], "y") : "";
				} else {
					$data_goods['ex'.$i] = $goods['ex'.$i];
				}
			}

			$gno = $this->Admin_Goods_model->post_goods($data_goods);
			if($gno) {
				// da_goods_image
				$goods_image = $this->Admin_Goods_model->get_datas("da_goods_image", ["master_no" => $v]);
				foreach($goods_image as $key => $value) {
					$data_goods_image[$key]['master_no'] = $gno;
					$data_goods_image[$key]['image_no'] = $value['image_no'];
					$data_goods_image[$key]['fname'] = $this->copy_file("detail_img", $value['fname'], "n");
					$data_goods_image[$key]['oname'] = $value['oname'];
					$data_goods_image[$key]['regDt'] = date("Y-m-d H:i:s");
				}
				$this->Admin_Goods_model->post_goods_batch("da_goods_image", $data_goods_image);

				// da_goods_multi
				$goods_multi = $this->Admin_Goods_model->get_datas("da_goods_multi", ["master_no" => $v]);
				foreach($goods_multi as $key => $value) {
					$data_goods_multi[$key]['language'] = $value['language'];
					$data_goods_multi[$key]['master_no'] = $gno;
					$data_goods_multi[$key]['name'] = $value['name'];
					$data_goods_multi[$key]['info'] = $value['info'];
					$data_goods_multi[$key]['regDt'] = date("Y-m-d H:i:s");
					$data_goods_multi[$key]['modDt'] = date("Y-m-d H:i:s");

					for($i = 1; $i <= 20; $i++) {
						$tmp = json_decode($value['ex'.$i]);
						if(is_object($tmp)) {
							$data_goods_multi[$key]['ex'.$i] = $tmp->fname ? $this->copy_file("ex".$i, $value['ex'.$i], "y") : "";
						} else {
							$data_goods_multi[$key]['ex'.$i] = $value['ex'.$i];
						}
					}
				}
				$this->Admin_Goods_model->post_goods_batch("da_goods_multi", $data_goods_multi);
				msg("상품이 정상적으로 복사되었습니다.", "goods_list");
			} else {
				msg("상품 복사에 실패하였습니다.", -1);
			}
		}
	}

	private function copy_file($dest, $files, $json) {
		$dir = $_SERVER['DOCUMENT_ROOT']."/upload/goods/";
		if($json == "y") {
			$file = json_decode($files);
			$ext = pathinfo($file->fname, PATHINFO_EXTENSION);
			if(file_exists($dir.$dest."/".$file->fname)) {
				$rename = date("YmdHis")."_".mt_rand(1000 ,9999).".".$ext;
				$result = ['oname' => $file->oname, 'fname' => $rename];
				copy($dir.$dest."/".$file->fname, $dir.$dest."/".$rename);
			}
			return json_encode($result, JSON_UNESCAPED_UNICODE);
		} else {
			$ext = pathinfo($files, PATHINFO_EXTENSION);
			if(file_exists($dir.$dest."/".$files)) {
				$rename = date("YmdHis")."_".mt_rand(1000 ,9999).".".$ext;
				copy($dir.$dest."/".$files, $dir.$dest."/".$rename);
			}
			return $rename;
		}
	}

    private function make_json_category() {
        $categories = [];
        //$temp = $this->dm->get('da_category', [], [], [], [], ['category' => 'ASC']);
		$temp = $this->dm->raw_query('SELECT dcm.* FROM `da_category` AS dc LEFT JOIN `da_category_multi` AS dcm ON dc.`category` = dcm.`category` ORDER BY dcm.`category`, FIELD(dcm.`language`, "kor", "eng", "chn", "jpn") ASC', 'r');
        foreach($temp as $key => $value) {
            $categories[$value['language']][] = [
                'code' => $value['category'],
                'name' => $value['categorynm']
            ];
        }
        $json = json_encode($categories);
        $this->load->library('qfile');
        $this->qfile->open(APPPATH.'/config/json_categories.json');
        $this->qfile->write($json);
        $this->qfile->close();
    }

	public function get_next_categories() {
		$post = $this->input->post(null, true);
		$length = ($post['length']  * 3) + 3;
		$temp = $this->dm->raw_query('SELECT dcm.* FROM `da_category` AS dc LEFT JOIN `da_category_multi` AS dcm ON dc.`category` = dcm.`category` WHERE dcm.`language` = "'.$post['language'].'" AND LENGTH(dcm.`category`) = '.$length.' AND dcm.`category` LIKE "'.$post['v'].'%" ORDER BY dcm.`category`', 'r');
		echo json_encode($temp);
	}
}