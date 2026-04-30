<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends FRONT_Controller {
	public $parent_category;//layout정리190513

	public function __construct() {
		parent::__construct();
		$this->load->model("front_Goods_model");
	}

	public function goods_list() {
		try {
			$this->load->model("category_model");
			$this->category_model->initialize();
			$this->load->library("form_validation");
			$this->load->library("pagination");

			$cate = $this->input->get("cate", true);
			$display_type = $this->input->get("display_type", true);

			$per_page = $this->input->get("per_page", true);

			$category_arr_where = array();
			$category_arr_where[] = array("yn_use", "y");

			$category_list = $this->category_model->get_list_category_low($category_arr_where);
			$category_list = array_merge($category_list, $this->category_model->get_list_category_same($category_arr_where), $this->category_model->get_list_category_top($category_arr_where));//190618 변경

			$category_info = $this->category_model->get_category();

			// 카테고리 접근권한 체크
			if($this->_member["level"] < $category_info["access_auth"] && $category_info["access_auth"] != 0){
				msg(print_language("category_permission_denied"), -1);
			}

			$blocked = $this->category_model->get_blocked_category($cate);
			if(isset($blocked[0]) === true) msg(print_language('category_permission_denied'), -1);

			if($this->_cfg_siteLanguage["multilingual"]){
				if(!empty($category_info["categorynm_".$this->_site_language])){
					$category_info["categorynm"] = $category_info["categorynm_".$this->_site_language];
				}
			}

			if(!$display_type) {
				$display_type = "list";
			}
			if($display_type == "gallery") {
				$goods_display = "gallery.html";
			} else {
				$goods_display = "list.html";
			}

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 99;
			$offset = ($per_page - 1) * $limit;

			$goods_list = $this->front_Goods_model->get_list_goods($cate, null, null, $limit, $offset, null);

			$config = array(
				"total_rows" => $goods_list["total_rows"],
				"per_page" => $limit,
				"first_url" => "?cate=". $cate . "&display_type=" . $display_type,
				"suffix" => "&cate=". $cate . "&display_type=" . $display_type,
			);

			$form_attribute = array(
				"action" => "/goods/goods_list",
				"attribute" => array("name" => "list_frm", "method" => "GET"),
				"hidden" => array("cate" => $cate, "display_type" => $display_type)
			);

			$this->pagination->initialize($config);
			$goods_list["pagination"] = $this->pagination->create_links();

			//
			$sub_categories = [];
			$temp_categories = $this->dm->get('da_category', [], [], [], [], ['category' => 'ASC']);
			foreach($temp_categories as $v) {
				if(strlen($v['category']) === 6) {
					$sub_categories[substr($v['category'], 0, 3)][] = [
						'category' => $v['category'],
						'categorynm' => $v['categorynm'],
						'yn_use' => $v['yn_use'],
					];
				}
			}

			//layout정리190513
			if($this->input->get("cate", true)) {
				$this->load->model("Etc_model");
				$pcategory['name'] = $this->Etc_model->get_my_parent($this->input->get("cate", true));
				$pcategory['current'] = $this->input->get("cate", true);
			}
			$this->template_->assign("parent_category", $pcategory);
			//layout정리190513
			$this->template_->assign("form_attribute", $form_attribute);
			$this->template_->assign("display_type", $display_type);
			$this->template_->assign("category_info", $category_info);
			$this->template_->assign("page_title", $category_info['categorynm']);
			$this->template_->assign("category_list", $category_list);
			$this->template_->assign('sub_categories', $sub_categories);
			$this->template_->assign("goods_list", $goods_list);
			$this->template_->define("goods_display", $this->_skin ."/layout/goods/". $goods_display);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function goods_search() {
		try {
			$this->load->library("form_validation");
			$this->load->library("pagination");

			$this->config->load("cfg_goodsField");
			$goodsField = $this->config->item("goodsField");
			$search_type = $this->input->get("search_type", true); // 검색종류
			$search = $this->input->get("search", true); // 검색키워드
			$arr_search_list = $this->input->get("arr_search_list", true); // 결과 내 검색 list
			$search_include = $this->input->get("search_include", true); // 결과 내 검색 checkbox 값
			$display_type = $this->input->get("display_type", true);
			$per_page = $this->input->get("per_page", true);
			if(!$display_type) {
				$display_type = "list";
			}
			if($display_type == "gallery") {
				$goods_display = "gallery.html";
			} else {
				$goods_display = "list.html";
			}

			$page_url = urlencode("display_type") ."=". urlencode($display_type);
			$arr_like = array();
			$search_keyword = array();
			$input_hidden = array("display_type" => $display_type);
			$search_cnt = 0;

			if(isset($search) && $search) {
				$search_type = $search_type ? $search_type : "Gm.name";
				$input_hidden["arr_search_list[". $search_cnt ."][search_type]"] = $search_type;
				$input_hidden["arr_search_list[". $search_cnt ."][search]"] = $search;
				$arr_like[] = array($search_type, $search);
				$search_keyword[] = $search;
				$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search_type]") ."=". urlencode($search_type);
				$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search]") ."=". urlencode($search);
				$search_cnt++;
			}

			if($search_include == "y") {
				$page_url .= "&search_include=". $search_include;
				foreach($arr_search_list as $key) {
					$arr_like[] = array($key["search_type"], $key["search"]);
					$input_hidden["arr_search_list[". $search_cnt ."][search_type]"] = $key["search_type"];
					$input_hidden["arr_search_list[". $search_cnt ."][search]"] = $key["search"];
					$search_keyword[] = $key["search"];
					$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search_type]") ."=". urlencode($key["search_type"]);
					$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search]") ."=". urlencode($key["search"]);
					$search_cnt++;
				}
			} else {
				if(count($arr_search_list) && !$search) {
					foreach($arr_search_list as $key) {
						$arr_like[] = array($key["search_type"], $key["search"]);
						$input_hidden["arr_search_list[". $search_cnt ."][search_type]"] = $key["search_type"];
						$input_hidden["arr_search_list[". $search_cnt ."][search]"] = $key["search"];
						$search_keyword[] = $key["search"];
						$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search_type]") ."=". urlencode($key["search_type"]);
						$page_url .= "&". urlencode("arr_search_list[". $search_cnt ."][search]") ."=". urlencode($key["search"]);
					}
				}
			}
			//if(count($arr_like)) { 검색없이 페이지 왔을경우 기본적으로 뿌리도록 처리
				if(!$per_page) {
					$per_page = 1;
				}

				$limit = 12;
				$offset = ($per_page - 1) * $limit;

				$goods_list = $this->front_Goods_model->get_list_goods(null, null, $arr_like, $limit, $offset, null);
				$config = array(
					"total_rows" => $goods_list["total_rows"],
					"per_page" => $limit,
					"first_url" => "?". $page_url,
					"suffix" => "&". $page_url,
				);

				$this->pagination->initialize($config);
				$goods_list["pagination"] = $this->pagination->create_links();

				$this->template_->assign("goods_list", $goods_list);
			//}
			$form_attribute = array(
				"action" => "/goods/goods_search",
				"attribute" => array("name" => "list_frm", "method" => "GET"),
				"hidden" => $input_hidden
			);

			if(count($search_keyword)) {
				$this->template_->assign("search_keyword", $search_keyword);
			}
			
			$this->template_->assign("goodsField", $goodsField);
			$this->template_->assign("display_type", $display_type);
			$this->template_->assign("form_attribute", $form_attribute);
			$this->template_->define("goods_display", $this->_skin ."/layout/goods/". $goods_display);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language]['search']);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	/**
	 * /service/<slug> 라우트 처리. 내부적으로 goods_view 호출.
	 * slug로 da_goods 조회 후 ?no=<no>&cate=<cate>로 set한 다음 goods_view 동작.
	 */
	public function goods_view_by_slug($slug = null) {
		if (empty($slug)) show_404();
		// CodeIgniter route(:any)는 URL-encode되어 들어옴 → 원래 한글 복원
		$slug = urldecode($slug);
		$row = $this->dm->get('da_goods', ['no', 'category'], ['slug' => $slug])[0] ?? null;
		if (!$row) show_404();
		$_GET['no'] = (int)$row['no'];
		$_GET['cate'] = $row['category'];
		$_REQUEST['no'] = $_GET['no'];
		$_REQUEST['cate'] = $_GET['cate'];
		$_GET['_via_slug'] = '1'; // goods_view에서 redirect 무한루프 방지 플래그
		// template_path()가 goods/goods_view.html을 반환하도록 rsegments 변조
		$this->uri->rsegments = [1 => 'goods', 2 => 'goods_view'];
		$this->goods_view();
	}

	public function goods_view() {
		try {
			$this->load->model("category_model");
			$this->config->load("cfg_goodsField");
			$goodsField = $this->config->item("goodsField");
			$no = $this->input->get("no", true);
			$arr_where = array();
			$arr_where[] = array("no" , $no);
			$get = $this->input->get(null, true);

			$goods_view = $this->front_Goods_model->get_view_goods($arr_where);

			if(!$goods_view) {
				throw new Exception(print_language("could_not_get_the_goods_information"));
			} else if($goods_view['goods_view']['cate_yn_state'] == "n") {
                // 카테고리 활성유무 확인하여 예외처리 2020-06-26
                throw new Exception(print_language("category_permission_denied"));
            }

			// URL Slug: 신규 글(slug 있음) 한정 query URL → /service/<slug> 301 redirect
			// (단, /service/<slug>로 들어온 요청은 _via_slug 플래그로 redirect 스킵 — loop 방지)
			$slug_row = $this->dm->get('da_goods', ['slug'], ['no' => $no])[0] ?? null;
			$canonical_slug = $slug_row['slug'] ?? null;
			if (!empty($canonical_slug) && empty($get['_via_slug'])) {
				header('Location: /service/' . rawurlencode($canonical_slug), true, 301);
				exit;
			}
			$this->template_->assign('canonical_slug', $canonical_slug);

			$this->category_model->initialize($goods_view["goods_view"]["category"]);

			$category_arr_where = array();
			$category_arr_where[] = array("yn_use", "y");

			$category_list = $this->category_model->get_list_category_low($category_arr_where);
			$category_list = array_merge($category_list, $this->category_model->get_list_category_same($category_arr_where), $this->category_model->get_list_category_top($category_arr_where));//190618 변경
			$category_info = $this->category_model->get_category();

			//다국어 사용 시
			if($this->_cfg_siteLanguage["multilingual"]){
				$goods_view["goods_view"] = $this->front_Goods_model->set_multi_goods_view($goods_view["goods_view"],$this->_site_language);
				if(!empty($category_info["categorynm_".$this->_site_language])){
					$category_info["categorynm"] = $category_info["categorynm_".$this->_site_language];
				}
			}

			$arrDefaultKey = array(
				"categorynm",
				"level",
				"no",
				"name",
			);

			if(array_key_exists("upload_fname", $goodsField["use"][$this->_site_language])){
				$arrDefaultKey[] = "upload_path";
				$arrDefaultKey[] = "upload_oname";
			}

			$new_goods_view = array();
			foreach($goodsField["use"][$this->_site_language] as $key => $val){
				$new_goods_view["goods_view"][$key] = $goods_view["goods_view"][$key];

			}

			foreach($arrDefaultKey as $key => $val){
				$new_goods_view["goods_view"][$val] = $goods_view["goods_view"][$val];
			}
            

			//layout정리190513
			$this->load->model("Etc_model");
			$pcategory['name'] = $this->Etc_model->get_my_parent($goods_view['goods_view']['category']);
			$pcategory['current'] = $goods_view['goods_view']['category'];
			$this->template_->assign("parent_category", $pcategory);
			//layout정리190513

            $old_goods_view = $goods_view;
			//$goods_view = $new_goods_view;


			//2020-03-24 Inbet Matthew 예비 필드들 html 태그 만들어서 넘기도록 수정
			$extraFieldData = array();
			$customField = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10", "ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드
			if(!empty($goodsField["use"][$this->_site_language])){
				foreach($goodsField["use"][$this->_site_language] as $columnKey => $columnVal){
					if(in_array($columnKey, $customField)) {
						$columnNm = $goodsField['name'][$this->_site_language][$columnKey];
						$extraOption = $goodsField["option"][$this->_site_language][$columnKey];
						$goodsViewVal = $goods_view['goods_view'][$columnKey];
                        if($this->_cfg_siteLanguage["multilingual"]){
                            $goodsViewOriginFileName = $old_goods_view['goods_view'][$columnKey.'_'.$this->_site_language.'_oname'];
                        } else {
                            $goodsViewOriginFileName = $old_goods_view['goods_view'][$columnKey.'_oname'];
                        }
						if(!empty($goodsViewVal)){
							switch($extraOption["type"]) {
								case 'file':
									if($extraOption["file_type"] == "image") {
										$srcPath = "/upload/goods/".$columnKey."/".$goodsViewVal;
										$width = (!empty($extraOption['width']) ? $extraOption['width'] : "");
										$height = (!empty($extraOption['height']) ? $extraOption['height'] : "");
										$extraFieldData[$columnKey]["value"] = sprintf("<img src='%s' onerror='this.src=\'../images/goods/noimg.gift\'' width='%s' height='%s'>", $srcPath, $width, $height);
									}else {
										$downloadPath = "/fileRequest/download?file=".urlencode("/goods/".$columnKey."/".$goodsViewVal)."&save=".$goodsViewOriginFileName;
										$extraFieldData[$columnKey]["value"] = sprintf("<div><a href = '%s' target='_blank' style='color:cornflowerblue;'>%s</a></div>", $downloadPath, $goodsViewOriginFileName);
									}
								break;
								default:
									$extraFieldData[$columnKey]["value"] = $goodsViewVal;
								break;
							}
							$extraFieldData[$columnKey]["name"] = $columnNm;
						}
					}
				}
				$this->template_->assign("extraFieldData", $extraFieldData);
			}
			//Matthew end
            unset($old_goods_view);

			// 본문(info)에서 Region/Period/Client/Location/Media 패턴 자동 추출 → 좌측 info dl
			// 운영자가 본문 평문에 메타정보를 적어둔 케이스를 askim 스타일로 정렬
			$info_html_raw = $goods_view['goods_view']['info'] ?? '';
			$auto_meta = [];
			$keys_in_order = ['Client', 'Location', 'Region', 'Media', 'Period'];
			foreach ($keys_in_order as $k) {
				if (preg_match('/' . $k . '\s*[:：]\s*([^<\r\n]+?)(?:<|$|\r|\n)/iu', $info_html_raw, $m)) {
					$val = trim(strip_tags($m[1]));
					if ($val !== '') $auto_meta[$k] = $val;
				}
			}
			if (!empty($auto_meta)) {
				$cleaned = $info_html_raw;
				foreach (array_keys($auto_meta) as $k) {
					// <p>Region: ...</p> 한 줄 단위로 제거
					$cleaned = preg_replace('/<p[^>]*>\s*' . $k . '\s*[:：][^<]*<\/p>/iu', '', $cleaned);
					// <p><span>...</span>Region: ...</p> 등 변형
					$cleaned = preg_replace('/<p[^>]*>(?:<[^>]+>)?\s*' . $k . '\s*[:：][^<]*?(?:<\/[^>]+>)?\s*<\/p>/iu', '', $cleaned);
				}
				$goods_view['goods_view']['info'] = $cleaned;
			}
			$this->template_->assign('auto_meta', $auto_meta);

			// previous, next
			$button = [
				'next' => 'nothing',
				'prev' => 'nothing',
			];

			if($get['cate']) {
				$temp_next = $this->dm->get('da_goods', [], ['no > ' => $get['no']], ['category' => $get['cate'].'|both'], [], ['no' => 'ASC'])[0];
				$temp_prev = $this->dm->get('da_goods', [], ['no < ' => $get['no']], ['category' => $get['cate'].'|both'], [], ['no' => 'DESC'])[0];
				if($temp_next['no'] > 0) $button['next'] = $temp_next['no'];
				if($temp_prev['no'] > 0) $button['prev'] = $temp_prev['no'];
				$this->template_->assign('button', $button);
			}

			// Recent Posts (현재 글 제외 최근 30개)
			$recent_arr_where = array();
			$recent_arr_where[] = array("Go.no !=", $no);
			$recent_goods = $this->front_Goods_model->get_list_goods(null, $recent_arr_where, null, 30, 0, null);
			// slug 매핑 + url + thumb_url (모델은 slug SELECT 안 함 → 별도 조회)
			if (!empty($recent_goods['goods_list'])) {
				$no_list = array_column($recent_goods['goods_list'], 'no');
				$slug_map = [];
				if ($no_list) {
					$rows = $this->dm->get('da_goods', ['no', 'slug'], [], [], ['no' => $no_list]);
					foreach ($rows as $r) $slug_map[$r['no']] = $r['slug'];
				}
				$noimg = '/data/skin/respon_default_en/images/common/noimg.gif';
				foreach ($recent_goods['goods_list'] as &$g) {
					$slug = $slug_map[$g['no']] ?? null;
					$g['slug'] = $slug;
					$g['url'] = $slug
						? '/service/' . rawurlencode($slug)
						: '/goods/goods_view?no=' . (int)$g['no'] . (!empty($g['category']) ? '&cate=' . $g['category'] : '');

					// thumb URL — img1 JSON({oname,fname}) 파싱, 없으면 detail_img 시도, 최종 fallback noimg
					$thumb = '';
					foreach (['img1', 'img2'] as $field) {
						$raw = $g[$field] ?? '';
						if (!$raw) continue;
						if ($raw[0] === '{') {
							$dec = json_decode($raw, true);
							$fname = $dec['fname'] ?? '';
							if ($fname) {
								$thumb = '/upload/goods/' . $field . '/' . $fname;
								break;
							}
						} elseif (is_string($raw) && trim($raw) !== '') {
							$thumb = '/upload/goods/' . $field . '/' . $raw;
							break;
						}
					}
					if (!$thumb) {
						// detail_img 1~10 중 첫 번째 비어있지 않은 거
						for ($di = 1; $di <= 10; $di++) {
							$d = $g['detail_img' . $di] ?? '';
							if (!$d) continue;
							if (is_string($d) && $d[0] === '{') {
								$dec = json_decode($d, true);
								$fname = $dec['fname'] ?? '';
								if ($fname) {
									$thumb = '/upload/goods/detail_img' . $di . '/' . $fname;
									break;
								}
							} elseif (is_string($d) && trim($d) !== '') {
								$thumb = '/upload/goods/detail_img' . $di . '/' . $d;
								break;
							}
						}
					}
					$g['thumb_url'] = $thumb ?: $noimg;
				}
				unset($g);
			}
			$this->template_->assign("recent_goods", $recent_goods);

			//조회수 상승
			$this->front_Goods_model->set_hit_cnt_up($no);
			$this->template_->assign("category_info", $category_info);
			$this->template_->assign("category_list", $category_list);
			$this->template_->assign("goodsField", $goodsField);
			$this->template_->assign("goods_view", $goods_view);
			$this->template_->assign("page_title", $category_info['categorynm']);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			if($e->getCode() == 50) {
				msg($e->getMessage(), '/');
			}else{
				msg($e->getMessage(), -1);
			}
		}

	}
}