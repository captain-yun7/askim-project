<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends ADMIN_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model("Admin_Member_model");
	}


	public function member_list() {
		try{
			$this->load->library("form_validation");
			$this->load->library("pagination");
			$this->config->load("cfg_memberField");
			$this->config->load("cfg_siteLanguage");

			$search_type = $this->input->get("search_type", true);
            // 언어별/등급별 검색 기능
			$search_lang = $this->input->get("search_lang", true);
			$search_level = $this->input->get("search_level", true);

			$search = $this->input->get("search", true);
			$per_page = $this->input->get("per_page", true);

			$arr_where = array();
			$arr_where[] = array("yn_status", "y");
			$arr_like = array();
			if(isset($search) && $search) {
				$search_type = $search_type ? $search_type : "userid";
				$arr_like[] = array($search_type, $search);
			}

            // 언어별
            if(ib_isset($search_lang)) {
                $arr_where[] = array("language", $search_lang);
            } else {
				$curr_lang = [];
				$use_lang = $this->config->item("site_language");
				if(count($use_lang['set_language']) > 0) {
					foreach($use_lang['set_language'] as $key => $value) {
						$curr_lang[] = $key;
					}
					$arr_where[] = array("language IN('".implode("', '", $curr_lang)."')", null);
				}
			}

			// 등급별
            if(ib_isset($search_level)) {
                $arr_where[] = array("level", $search_level);
            }

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 10;
			$offset = ($per_page - 1) * $limit;

			$get_data = $this->Admin_Member_model->get_list_member($arr_where, $arr_like, $limit, $offset);

            // 회원 그룹명 추가
            $get_data["member_grade_list"] = get_list_member_grade();
            foreach($get_data["member_grade_list"] as $key => $val) {
                $get_data["member_grade_text"][$val["level"]] = $val["gradenm"];
            }

			$get_data["memberField"] = $this->config->item("memberField");
			$get_data["offset"] = $offset;

			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?search_type=". $search_type ."&search=". $search ."&search_lang=". $search_lang ."&search_level=". $search_level,
				"suffix" => "&search_type=". $search_type ."&search=". $search ."&search_lang=". $search_lang ."&search_level=". $search_level,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();
			$this->set_view("admin/member/member_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_reg() {
		try{

			$this->load->library("form_validation");
			$this->config->load("cfg_memberField");

			$userid = $this->input->get("userid", true);
			$mode = $this->input->post("mode", true);

			if(isset($mode)) {
				$memberField = $this->config->item("memberField");

				$language = $this->input->post("language", true);
				foreach($this->input->post() as $postKey => $postVal){
					if(strpos($postKey, '_'.$language) !== false) {
						$newPostKey = str_replace('_'.$language , '', $postKey);
						$_POST[$newPostKey] = $postVal;
						unset($_POST[$postKey]);
					}
				}

				if($mode == "register") { // 등록
					$this->form_validation->set_rules("userid", $memberField["name"]["kor"]["userid"], "required|trim|xss_clean|min_length[4]|max_length[14]|callback_userid_duplicate_check");
					$this->form_validation->set_rules("password", $memberField["name"]["kor"]["password"], "required|trim|xss_clean|min_length[10]|max_length[16]|callback__password_validate");
				} else if($mode == "modify") { // 수정
					$this->form_validation->set_rules("userid", $memberField["name"]["kor"]["userid"], "required|trim|xss_clean|min_length[4]|max_length[14]");

					if($this->input->post("password", true)) {
						$this->form_validation->set_rules("password", $memberField["name"]["kor"]["password"], "trim|xss_clean|min_length[10]|max_length[16]|callback__password_validate");
					} else {
						//$this->form_validation->set_rules("password", $memberField["name"]["kor"]["password"], "trim|xss_clean|min_length[10]|max_length[16]");
					}
				}
                if(ib_isset($this->input->post("name", true))) {
				    $this->form_validation->set_rules("name", $memberField["name"]["kor"]["name"], "required|trim|xss_clean|max_length[10]");
                }
                if(ib_isset($this->input->post("level", true))) {
				    $this->form_validation->set_rules("level", $memberField["name"]["kor"]["level"], "required|trim|xss_clean|is_natural_no_zero");
                }
                if(ib_isset($this->input->post("sex", true))) {
				    $this->form_validation->set_rules("sex", $memberField["name"]["kor"]["sex"], "trim|xss_clean". (isset($memberField["use"]["sex"]) && isset($memberField["require"]["sex"]) ? "|required" : ""));
                }
                if(ib_isset($this->input->post("birth", true))) {
				    $this->form_validation->set_rules("birth", $memberField["name"]["kor"]["birth"], "trim|xss_clean|is_natural". (isset($memberField["use"]["birth"]) && isset($memberField["require"]["birth"]) ? "|required" : ""));
                }
                if(ib_isset($this->input->post("email", true)) && $this->input->post("email", true) !== "@") {
				    $this->form_validation->set_rules("email", $memberField["name"]["kor"]["email"], "trim|xss_clean|valid_email|callback_email_duplicate_check". (isset($memberField["use"]["email"]) && isset($memberField["require"]["email"]) ? "|required" : ""));
                }
                #$this->form_validation->set_rules("zip", $memberField["name"]["kor"]["zip"], "trim|xss_clean");

				$required_zip = $this->memberField["require"][$this->_site_language]["zip"] == "checked" ? "|required" : "";
				$required_country = $this->memberField["require"][$this->_site_language]["country"] == "checked" ? "|required" : "";
				$required_city = $this->memberField["require"][$this->_site_language]["city"] == "checked" ? "|required" : "";
				$required_state_province_region = $this->memberField["require"][$this->_site_language]["state_province_region"] == "checked" ? "|required" : "";
				$required_mobile_country_code = $this->memberField["require"][$this->_site_language]["mobile_country_code"] == "checked" ? "|required" : "";
				switch($language) {
					case 'kor':
						$this->form_validation->set_rules("zip", "우편번호", "trim|xss_clean".$required_zip);
						break;
					case 'eng':
						$this->form_validation->set_rules("zip", 'POSTAL_CODE', "trim|xss_clean".$required_zip);
						$this->form_validation->set_rules("country", "COUNTRY", "trim|xss_clean".$required_country);
						$this->form_validation->set_rules("city", "CITY", "trim|xss_clean".$required_city);
						$this->form_validation->set_rules("state_province_region", "STATE_PROVINCE_REGION", "trim|xss_clean".$required_state_province_region);
						$this->form_validation->set_rules("mobile_country_code", "MOBILE_COUNTRY_CODE", "trim|xss_clean".$required_mobile_country_code);
						break;
					case 'chn':
						$this->form_validation->set_rules("zip", "邮政编码", "trim|xss_clean".$required_zip);
						$this->form_validation->set_rules("country", "国家", "trim|xss_clean".$required_country);
						$this->form_validation->set_rules("city", "城市", "trim|xss_clean".$required_city);
						$this->form_validation->set_rules("state_province_region", "州/省/地区", "trim|xss_clean".$required_state_province_region);
						$this->form_validation->set_rules("mobile_country_code", "国家代码", "trim|xss_clean".$required_mobile_country_code);
						break;
					case 'jpn':
						$this->form_validation->set_rules("zip", "郵便番号", "trim|xss_clean".$required_zip);
						$this->form_validation->set_rules("country", "配送国", "trim|xss_clean".$required_country);
						$this->form_validation->set_rules("city", "都市", "trim|xss_clean".$required_city);
						$this->form_validation->set_rules("state_province_region", "州/県/地域", "trim|xss_clean".$required_state_province_region);
						$this->form_validation->set_rules("mobile_country_code", "国家コード", "trim|xss_clean".$required_mobile_country_code);
						break;
				}

                $this->form_validation->set_rules("address", $memberField["name"]["kor"]["address"], "trim|xss_clean");
                $this->form_validation->set_rules("address2",  $memberField["name"]["kor"]["address2"], "trim|xss_clean");
                $this->form_validation->set_rules("mobile", $memberField["name"]["kor"]["mobile"], "trim|xss_clean");
                $this->form_validation->set_rules("fax", $memberField["name"]["kor"]["fax"], "trim|xss_clean");
                $this->form_validation->set_rules("yn_mailling", $memberField["name"]["kor"]["yn_mailling"], "trim|xss_clean");
                $this->form_validation->set_rules("yn_sms", $memberField["name"]["kor"]["yn_sms"], "trim|xss_clean");
				$this->form_validation->set_rules("ex1", $memberField["name"]["kor"]["ex1"], "trim|xss_clean");
				$this->form_validation->set_rules("ex2", $memberField["name"]["kor"]["ex2"], "trim|xss_clean");
				$this->form_validation->set_rules("ex3", $memberField["name"]["kor"]["ex3"], "trim|xss_clean");
				$this->form_validation->set_rules("ex4", $memberField["name"]["kor"]["ex4"], "trim|xss_clean");
				$this->form_validation->set_rules("ex5", $memberField["name"]["kor"]["ex5"], "trim|xss_clean");
				$this->form_validation->set_rules("ex6", $memberField["name"]["kor"]["ex6"], "trim|xss_clean");
				$this->form_validation->set_rules("ex7", $memberField["name"]["kor"]["ex7"], "trim|xss_clean");
				$this->form_validation->set_rules("ex8", $memberField["name"]["kor"]["ex8"], "trim|xss_clean");
				$this->form_validation->set_rules("ex9", $memberField["name"]["kor"]["ex9"], "trim|xss_clean");
				$this->form_validation->set_rules("ex10", $memberField["name"]["kor"]["ex10"], "trim|xss_clean");
				$this->form_validation->set_rules("ex11", $memberField["name"]["kor"]["ex11"], "trim|xss_clean");
				$this->form_validation->set_rules("ex12", $memberField["name"]["kor"]["ex12"], "trim|xss_clean");
				$this->form_validation->set_rules("ex13", $memberField["name"]["kor"]["ex13"], "trim|xss_clean");
				$this->form_validation->set_rules("ex14", $memberField["name"]["kor"]["ex14"], "trim|xss_clean");
				$this->form_validation->set_rules("ex15", $memberField["name"]["kor"]["ex15"], "trim|xss_clean");
				$this->form_validation->set_rules("ex16", $memberField["name"]["kor"]["ex16"], "trim|xss_clean");
				$this->form_validation->set_rules("ex17", $memberField["name"]["kor"]["ex17"], "trim|xss_clean");
				$this->form_validation->set_rules("ex18", $memberField["name"]["kor"]["ex18"], "trim|xss_clean");
				$this->form_validation->set_rules("ex19", $memberField["name"]["kor"]["ex19"], "trim|xss_clean");
				$this->form_validation->set_rules("ex20", $memberField["name"]["kor"]["ex20"], "trim|xss_clean");


				if($this->form_validation->run()){
					$set_data = $this->input->post(null, true);
					$arr_where = array();
					$arr_where[] = array("userid", $set_data['userid']);
					$get_data = $this->Admin_Member_model->get_view_member($arr_where);

					if(empty($get_data['member_view']['level']) === false && $get_data['member_view']['level'] > $this->_admin_member["level"]) { // 자신보다 높은 등급인 관리자인 경우 등급수정 X
						unset($set_data['level']);
					} else {
						if($this->_admin_member["level"] < $set_data['level']) {
							throw new Exception("로그인하신 관리자보다 높은 레벨을 등록할 수 없습니다.");
						}

						if($set_data['level'] > "99") {
							throw new Exception("관리자 권한 최대레벨은 99까지만 가능합니다.");
						}
					}

					if(in_array($set_data['level'], array_keys($this->_adm_auth))) {
						if($set_data['language'] != 'kor' ) {
							throw new Exception(sprintf('관리자 등급일 때 언어구분은 %s이어야 합니다.', $this->_site_language['set_language']['kor']));
						}
					}

					$result = $this->Admin_Member_model->member_register($mode, $set_data);
					if($result) {
						if($mode == "register") {
							#msg("등록되었습니다.", "/admin/member/member_reg?userid=". $set_data["userid"]."&registLanguage=".$set_data["language"],"parent");
							msg("등록되었습니다.", "/admin/member/member_list","parent");
						} else if($mode == "modify") {
							//msg("회원정보가 수정되었습니다.", "/admin/member/member_reg?userid=". $set_data["userid"]."&registLanguage=".$set_data["language"],"parent");
							msg("회원정보가 수정되었습니다.", "/admin/member/member_reg?".$this->input->post("ref", true), "parent");
						}
					} else {
						$error = $this->db->error();
						throw new Exception($error["message"]);
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				if(isset($userid)) { // 수정
					$arr_where = array();
					$arr_where[] = array("userid", $userid);
                    
                    // 동일한 아이디의 다국어 아이디가 존재할 수 있음, 언어 구분 조건 추가 2020-06-24
                    $arr_where[] = array("language", $this->input->get("registLanguage", true));

					$get_data = $this->Admin_Member_model->get_view_member($arr_where);
					$get_data["mode"] = "modify";
				} else { // 등록
					$get_data["mode"] = "register";
				}

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

				$this->config->load("cfg_country_info");
				$get_data["country_info"] = $this->config->item("country_info");
				$get_data["default_country_Info"] = $this->config->item("default_country_Info");

				if(!count($get_data["default_country_Info"]) > 0) {
					$default_country_name['eng'] = array('Albania','Algeria','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Belarus','Belgium','Bhutan','Bosnia and Herzegovina','Botswana','Brazil','Brunei Darussalam','Bulgaria','Burma (Myanmar)','Cambodia','Canada','Cape Verde','Chile','China','Costa Rica','Croatia (Hrvatska)','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominican Republic','Ecuador','Egypt','Estonia','Ethiopia','Fiji','Finland','France','Georgia','Germany','Greece','Hong Kong','Hungary','India','Indonesia','Iran','Ireland','Israel','Japan','Jordan','Kazakhstan','Kenya','Laos','Latvia','Luxembourg','Macau','Macedonia','Malaysia','Maldives','Mauritius','Mexico','Mongolia','Morocco','Mozambique','Nepal','Netherlands','Netherlands Antilles','New Zealand (Aotearoa)','Nigeria','Norway','Oman','Pakistan','Panama','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saudi Arabia','Singapore','Slovak Republic','Slovenia','Spain','Sri Lanka','Sweden','Switzerland','Taiwan','Tanzania','Thailand','Tunisia','Turkey','Ukraine','United Arab Emirates','United Kingdom','United States','Uzbekistan','Viet Nam','Zambia');

					$default_country_name['chn'] = array('阿尔巴尼亚','阿尔及利亚','阿根廷','亚美尼亚','澳大利亚','奥地利','阿塞拜疆','巴林','孟加拉国','白俄罗斯','比利时','丁烷','波斯尼亚和黑塞哥维那','博茨瓦纳','巴西','文莱','保加利亚','缅甸(缅甸)','柬埔寨','加拿大','佛得角','智利','中国','哥斯达黎加','克罗地亚','古巴','塞浦路斯','捷克共和国','丹麦','吉布提','多米尼加共和国','厄瓜多尔','埃及','爱沙尼亚','埃塞俄比亚','斐济','芬兰','法国','格鲁吉亚','德国','希腊','香港','匈牙利','印度','印尼','伊朗','爱尔兰','以色列','日本','乔丹','哈萨克斯坦','肯尼亚','老挝','拉脱维亚','卢森堡','澳门','马其顿','马来西亚','马尔代夫','毛里求斯','墨西哥','蒙古','摩洛哥','莫桑比克','尼泊尔','荷兰','荷属安的列斯肯尼思不应该','新西兰(新西兰)','尼日利亚','挪威','阿曼','巴基斯坦','巴拿马','秘鲁','菲律宾','波兰','葡萄牙','卡塔尔','罗马尼亚','俄罗斯','卢旺达','沙特阿拉伯','新加坡','斯洛伐克共和国','斯洛文尼亚','西班牙','斯里兰卡','瑞典','瑞士','台湾','坦桑尼亚','泰国','突尼斯','土耳其','乌克兰','阿拉伯联合酋长国','英国','美国','乌兹别克斯坦','越南','赞比亚');

					$default_country_name['jpn'] = array('アルバニア','アルジェリア','アルゼンチン','アルメニア','オーストラリア','オーストリア','アゼルバイジャン','バーレーン','バングラデシュ','ベラルーシ','ベルギー','ブータン','ボスニア・ヘルツェゴビナ','ボツワナ','ブラジル','ブルネイ','ブルガリア','ミャンマー','カンボジア','カナダ','カーボベルデ','チリ','中国','コスタリカ','クロアチア','キューバ','キプロス','チェコ共和国','デンマーク','ジブチ','ドミニカ共和国','エクアドル','エジプト','エストニア','エチオピア','フィジー','フィンランド','フランス','ジョージア','ドイツ','ギリシャ','香港','ハンガリー','インド','インドネシア','とは','アイルランド','イスラエル','日本','ヨルダン','カザフスタン','ケニア','ラオス','ラトビア','ルクセンブルク','マカオ','マケドニア共和国','マレーシア','モルディブ','モーリシャス','メキシコ','モンゴル','モロッコ','モザンビーク','ネパール','オランダ','オランダ領アンアンティル','ニュージーランド','ナイジェリア','ノルウェー','オマーン','パキスタン','パナマ','ペルー','フィリピン','ポーランド','ポルトガル','カタル','ルーマニア','ロシア','ルワンダ','サウジアラビア','シンガポール','スロバキア','スロベニア','スペイン','スリランカ','スウェーデン','スイス','台湾','タンザニア','タイ','チュニジア','トルコ','ウクライナ','アラブ首長国連邦','イギリス','美國','ウズベキスタン','ベトナム','ザンビア');

					$default_country_mark = array('(Albania)','(Algeria)','(Argentina)','(Armenia)','(Australia)','(Austria)','(Azerbaijan)','(Bahrain)','(Bangladesh)','(Belarus)','(Belgium)','(Bhutan)','(Bosnia and Herzegovina)','(Botswana)','(Brazil)','(Brunei Darussalam)','(Bulgaria)','(Burma (Myanmar))','(Cambodia)','(Canada)','(Cape Verde)','(Chile)','(China)','(Costa Rica)','(Croatia (Hrvatska))','(Cuba)','(Cyprus)','(Czech Republic)','(Denmark)','(Djibouti)','(Dominican Republic)','(Ecuador)','(Egypt)','(Estonia)','(Ethiopia)','(Fiji)','(Finland)','(France)','(Georgia)','(Germany)','(Greece)','(Hong Kong)','(Hungary)','(India)','(Indonesia)','(Iran)','(Ireland)','(Israel)','(Japan)','(Jordan)','(Kazakhstan)','(Kenya)','(Laos)','(Latvia)','(Luxembourg)','(Macau)','(Macedonia)','(Malaysia)','(Maldives)','(Mauritius)','(Mexico)','(Mongolia)','(Morocco)','(Mozambique)','(Nepal)','(Netherlands)','(Netherlands Antilles)','(New Zealand (Aotearoa))','(Nigeria)','(Norway)','(Oman)','(Pakistan)','(Panama)','(Peru)','(Philippines)','(Poland)','(Portugal)','(Qatar)','(Romania)','(Russia)','(Rwanda)','(Saudi Arabia)','(Singapore)','(Slovak Republic)','(Slovenia)','(Spain)','(Sri Lanka)','(Sweden)','(Switzerland)','(Taiwan)','(Tanzania)','(Thailand)','(Tunisia)','(Turkey)','(Ukraine)','(United Arab Emirates)','(United Kingdom)','(United States)','(Uzbekistan)','(Viet Nam)','(Zambia)');

					$default_country_code = array('(+355)','(+213)','(+54)','(+374)','(+61)','(+43)','(+994)','(+973)','(+880)','(+375)','(+32)','(+975)','(+387)','(+267)','(+55)','(+673)','(+359)','(+95)','(+855)','(+1)','(+238)','(+56)','(+86)','(+506)','(+385)','(+53)','(+357)','(+420)','(+45)','(+253)','(+1)','(+593)','(+20)','(+372)','(+251)','(+679)','(+358)','(+33)','(+995)','(+49)','(+30)','(+852)','(+36)','(+91)','(+62)','(+98)','(+353)','(+972)','(+81)','(+962)','(+7)','(+254)','(+856)','(+371)','(+352)','(+853)','(+389)','(+60)','(+960)','(+230)','(+52)','(+976)','(+212)','(+258)','(+977)','(+31)','(+599)','(+64)','(+234)','(+47)','(+968)','(+92)','(+507)','(+51)','(+63)','(+48)','(+351)','(+974)','(+40)','(+7)','(+250)','(+966)','(+65)','(+421)','(+386)','(+34)','(+94)','(+46)','(+41)','(+886)','(+255)','(+66)','(+216)','(+90)','(+380)','(+971)','(+44)','(+1)','(+998)','(+84)','(+260)');

					foreach($default_country_mark as $key => $val){
						$tempArr['mark'] = $val;
						$tempArr['code'] = $default_country_code[$key];
						foreach($this->_site_language["support_language"] as $language_key => $language_value){
							if($language_key != 'kor') {
								$tempArr['name'][$language_key] = $default_country_name[$language_key][$key];
							}
						}
						$get_data["default_country_Info"][] = $tempArr;
					}
				}
				$get_data["country_info"] = array_merge($get_data["default_country_Info"], $get_data["country_info"]);

				$get_data["memberField"] = $this->config->item("memberField");
				$get_data['ref'] = http_build_query($this->input->get(null, true));
				$this->set_view("admin/member/member_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage());
		}
	}

	public function member_delete() {
		try{
			if(!$this->input->post("userid", true)) {
				throw new Exception("회원의 정보를 찾을 수 없습니다.");
			}

			$userid = $this->input->post("userid", true);
			$arr_where = array();
			$arr_where[] = array("userid", $userid, "IN");

			$result = $this->Admin_Member_model->member_delete($arr_where);

			if($result) {
				msg("회원이 삭제되었습니다.", "member_list");
			} else {
				throw new Exception("회원 삭제를 실패하였습니다.\n\n잠시후 다시 시도해주세요.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}

	}

	public function member_grade() {
		try{
			$this->load->library("form_validation");


			$arr_where = array();
			$arr_where[] = array("level", array_keys($this->_adm_auth), "NOTIN");
			$get_data["member_grade_list"] = get_list_member_grade($arr_where);
			$this->set_view("admin/member/member_grade", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_grade_reg() {
		try{
			$this->load->library("form_validation");

			$level = $this->input->get("level", true);
			$mode = $this->input->post("mode", true);

			if(isset($mode)) {
				$level = $this->input->post("level", true);

				if($mode == "register") {
					$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero|is_unique[da_member_grade.level]");
				} else {
					$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero");
				}
				$this->form_validation->set_rules("gradenm", "등급명", "required|trim|xss_clean|max_length[25]");
				if($this->form_validation->run()) {
					if($level >= 80) {
						throw new Exception("관리자 등급을 제외한 등급은 80레벨 이상으로 등록할 수 없습니다.");
					}
					if($mode == "register") {
						$adm_low_level = min(array_keys($this->_adm_auth));
						if($adm_low_level <= $level) {
							throw new Exception("등급은 관리자 최소 레벨보다 높은 수로 사용할 수 없습니다.");
						}
					}
					$set_data = $this->input->post(null, true);
                    $set_data['moddt'] = date("Y-m-d H:i:s"); // 생성/수정시 변경일 업데이트 2020-06-17
					$get_data = table_data_match("da_member_grade", $set_data);
					if($mode == "modify") {
						$result = $this->db->update("da_member_grade", $get_data, array("level" => $level));
						if($result) {
							msg("수정하였습니다.", "/admin/member/member_grade_reg?level=". $level);
						} else {
							$error = $this->db->error();
							throw new Exception($error["message"]);
						}
					} else if($mode == "register"){
						$result = $this->db->insert("da_member_grade", $get_data);
						if($result) {
							msg("등록하였습니다.", "/admin/member/member_grade_reg?level=". $level);
						} else {
							$error = $this->db->error();
							throw new Exception($error["message"]);
						}
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				if(isset($level)) {
					$arr_where = array();
					$arr_where[] = array("level", $level);
					$get_data["member_grade_view"] = get_view_member_grade($arr_where);
					$get_data["mode"] = "modify";
				} else { // 등록
					$get_data["mode"] = "register";
				}
				$this->set_view("admin/member/member_grade_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_grade_delete() {
		try{
			$this->load->library("form_validation");

			$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero");

			if($this->form_validation->run()) {
				$level = $this->input->post("level", true);
				$result = $this->db->delete("da_member_grade", array("level" => $level));
				if($result) {
					msg("삭제하였습니다.", "/admin/member/member_grade");
				} else {
					$error = $this->db->error();
					throw new Exception($error["message"]);
				}
			} else {
				throw new Exception(validation_errors());
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_auth() {
		try{
			$this->load->library("form_validation");

			$arr_where = array();
			$arr_where[] = array("level", array_keys($this->_adm_auth), "IN");
			$get_data["member_grade_list"] = get_list_member_grade($arr_where);
			$this->set_view("admin/member/member_auth", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_auth_reg() {
		try{
			$this->load->library("form_validation");

			$level = $this->input->get("level", true);
			$mode = $this->input->post("mode", true);

			if(isset($mode)) {
				$level = $this->input->post("level", true);

				if($mode == "register") {
					$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero|is_unique[da_member_grade.level]");
				} else {
					$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero");
				}
				$this->form_validation->set_rules("gradenm", "등급명", "required|trim|xss_clean|max_length[25]");

				if($this->form_validation->run()) {
					if($mode == "register") {
						$arr_where = array();
						$arr_where[] = array("level", array_keys($this->_adm_auth), "NOTIN");
						db_where($arr_where);
						$this->db->select_max("level", "user_high_level");
						$get_data = $this->db->get("da_member_grade")->last_row("array");

						if($get_data["user_high_level"] >= $level) {
							throw new Exception("관리자 권한은 회원등급보다 낮은 레벨로 사용할 수 없습니다.");
						}

						if($this->_admin_member["level"] < $level) {
							throw new Exception("로그인하신 관리자보다 높은 레벨을 등록할 수 없습니다.");
						}

						if($level > "99") {
							throw new Exception("관리자 권한은 최대레벨은 99까지만 가능합니다.");
						}

						if($level < "80") {
							throw new Exception("관리자 권한은 최소 80레벨이상부터 등록 가능합니다.");
						}
					}

					$request_nav = $this->input->post("nav", true);
					$this->_adm_auth_write($request_nav, $level);

					$set_data = $this->input->post(null, true);
                    $set_data['moddt'] = date("Y-m-d H:i:s"); // 생성/수정시 변경일 업데이트 2020-06-17
					$get_data = table_data_match("da_member_grade", $set_data);
					if($mode == "modify") {
						$result = $this->db->update("da_member_grade", $get_data, array("level" => $level));
						if($result) {
							msg("수정하였습니다.", "/admin/member/member_auth_reg?level=". $level);
						}
					} else if($mode == "register"){
						$result = $this->db->insert("da_member_grade", $get_data);
						if($result) {
							msg("등록하였습니다.", "/admin/member/member_auth_reg?level=". $level);
						}
					}
				} else {
					throw new Exception(validation_errors());
				}
			} else {
				if(isset($level)) {
					$arr_where = array();
					$arr_where[] = array("level", $level);
					$get_data["member_grade_view"] = get_view_member_grade($arr_where);
					$get_data["mode"] = "modify";
				} else { // 등록
					$get_data["mode"] = "register";
				}
				$this->set_view("admin/member/member_auth_reg", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_auth_delete() {
		try{
			$this->load->library("form_validation");

			$this->form_validation->set_rules("level", "레벨", "required|trim|xss_clean|is_natural_no_zero");

			if($this->form_validation->run()) {
				$level = $this->input->post("level", true);
				if($level == "99") {
					throw new Exception("99는 삭제할 수 없습니다.");
				}
				if($level > $this->_admin_member["level"]) {
					throw new Exception("로그인하신 관리자보다 높은 레벨을 삭제할 수 없습니다.");
				}
				$this->_adm_auth_write(null, $level);
				$result = $this->db->delete("da_member_grade", array("level" => $level));
				if($result) {
					msg("삭제하였습니다.", "/admin/member/member_auth");
				} else {
					$error = $this->db->error();
					throw new Exception($error["message"]);
				}
			} else {
				throw new Exception(validation_errors());
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	/*
	 * 패스워드 영어/숫자/특수문자 체크
	 */
	public function _password_validate(){
		$password = $this->input->post("password", true);
		$memberField = $this->config->item("memberField");

		$pattern = "/[0-9]/";
		$pattern2 = "/[a-z]/";
		$pattern3 = "/[~!@#$%^&*()_\-\+=|<>?:{}\\\]/";
		$pattern4 = "/[A-Z]/";
		$cnt_pattern = 0;

		if(preg_match($pattern, $password)) {
			$cnt_pattern++;
		}

		if(preg_match($pattern2, $password)) {
			$cnt_pattern++;
		}

		if(preg_match($pattern3, $password)) {
			$cnt_pattern++;
		}

		if(preg_match($pattern4, $password)) {
			$cnt_pattern++;
		}

		if($cnt_pattern >= 2)  {
			return true;
		} else {
			$this->form_validation->set_message("_password_validate", $memberField["name"]["kor"]["password"]."는 영어/숫자/특수문자를 2종류 이상 혼용하여 사용해야 합니다.");
			return false;
		}
	}

	/*
	 * 아이디 중복체크
	 */
	public function userid_duplicate_check() {

		$this->config->load("cfg_memberField");
		$memberField = $this->config->item("memberField");
		$userid = $this->input->post("userid", true);
		$language = $this->input->post("language", true);

		if(defined("_IS_AJAX")) {
			if($this->Admin_Member_model->is_userid_duplicate($userid, $language)) {
				$set_data = array("code" => true, "use" => false, "msg" => "이미 등록된 ". $memberField["name"]["kor"]["userid"] ."입니다.");
			} else {
				$set_data = array("code" => true, "use" => true, "msg" => "사용 가능한 ". $memberField["name"]["kor"]["userid"] ."입니다.");
			}
			echo json_encode($set_data);
		} else {
			if($this->Admin_Member_model->is_userid_duplicate($userid, $language)) {
				$this->form_validation->set_message("userid_duplicate_check", "이미 등록된 아이디입니다.");
				return false;
			} else {
				return true;
			}
		}
	}

	/*
	 * 이메일 중복체크
	 */
	public function email_duplicate_check() {

		$this->config->load("cfg_memberField");
		$memberField = $this->config->item("memberField");

		$email = $this->input->post("email", true);
		$userid = $this->input->post("userid", true);

		if(defined("_IS_AJAX")) {
			if($this->Admin_Member_model->is_email_duplicate($userid, $email)) {
				$set_data = array("code" => true, "use" => false, "msg" => "이미 가입되어 있는 ". $memberField["name"]["kor"]["email"] ."입니다.");
			} else {
				$set_data = array("code" => true, "use" => true, "msg" => "사용가능한 ". $memberField["name"]["kor"]["email"] ."입니다.");
			}
			echo json_encode($set_data);
		} else {
			if($this->Admin_Member_model->is_email_duplicate($userid, $email)) {
				$this->form_validation->set_message("email_duplicate_check", "이미 가입되어 있는 ". $memberField["name"]["kor"]["email"] ."입니다.");
				return false;
			} else {
				return true;
			}
		}
	}

	private function _adm_auth_write($data, $level) {
		$adm_auth = $this->_adm_auth;

		unset($adm_auth[$level]);
		if(isset($data)) {
			foreach($data as $key => $value) {
				foreach($value as $subKey => $subValue) {
					$adm_auth[$level][$key][] = $subValue;
				}
			}
		}

		$set_data = "";
		$set_data .= "<?php\n";
		$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
		$set_data .= "\$config = array(\n";
		$set_data .= "\t'auth' => array(\n";
		foreach ($adm_auth as $key => $value) {
			$set_data .= "\t\t'$key'	=> array(\n"; // LEVEL
			foreach ($value as $secondKey => $secondValue) {
				$set_data .= "\t\t\t'$secondKey'	=> array(\n"; // HIGH NAV
					foreach ($secondValue as $thirdValue) {
						$set_data .= "\t\t\t\t'$thirdValue', "; // LOW NAV
					}
				$set_data .= "\n\t\t\t),\n";
			}
			$set_data .= "\t\t),\n";
		}
		$set_data .= "\t),\n";
		$set_data .= ");\n";

		$this->load->library("qfile");
		$this->qfile->open(APPPATH ."/config/cfg_adm_auth.php");
		$this->qfile->write($set_data);
		$this->qfile->close();
	}

	public function member_dormant_list() {
		try{
			$this->load->library("form_validation");
			$this->load->library("pagination");
			$this->load->model("dormant_model");
			$this->config->load("cfg_memberField");

			$search_type = $this->input->get("search_type", true);
			$search = $this->input->get("search", true);
			$per_page = $this->input->get("per_page", true);

			$arr_where = array();
			$arr_where[] = array("yn_status", "y");
			$arr_like = array();
			if(isset($search) && $search) {
				$search_type = $search_type ? $search_type : "userid";
				$arr_like[] = array($search_type, $search);
			}

			if(!$per_page) {
				$per_page = 1;
			}

			$limit = 10;
			$offset = ($per_page - 1) * $limit;

			$get_data = $this->dormant_model->get_list_dormant($arr_where, $arr_like, $limit, $offset);

			$get_data["memberField"] = $this->config->item("memberField");
			$get_data["offset"] = $offset;

			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?search_type=". $search_type ."&search=". $search,
				"suffix" => "&search_type=". $search_type ."&search=". $search,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();
			$this->set_view("admin/member/member_dormant_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_dormant_view() {
		try{

			$this->load->library("form_validation");
			$this->load->model("dormant_model");
			$this->config->load("cfg_memberField");

			$userid = $this->input->get("userid", true);

			$arr_where = array();
			$arr_where[] = array("userid", $userid);
			$get_data = $this->dormant_model->get_view_dormant($arr_where);

			if(!$get_data) {
				throw new Exception("회원정보가 없습니다.");
			}

			$arr_where = array();
			$arr_where[] = array("level <=", $this->_admin_member["level"]);
			$get_data["member_grade_list"] = get_list_member_grade($arr_where);

			$get_data["memberField"] = $this->config->item("memberField");
			$this->set_view("admin/member/member_dormant_view", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function member_withdrawal_list() {
		try {
			$this->load->library("form_validation");
			$this->load->library("pagination");
			$per_page = $this->input->get("per_page", true);
			if(!$per_page) $per_page = 1;

			$limit = 20;
			$offset = ($per_page - 1) * $limit;

			$search_type = $this->input->get("search_type", true);
			if($search_type) $arr_where[] = [$search_type, $this->input->get("search", true)];

			$get_data = $this->Admin_Member_model->member_withdrawal_list($arr_where, $arr_like, $limit, $offset);

			$get_data["offset"] = $offset;

			$config = array(
				"total_rows" => $get_data["total_rows"],
				"per_page" => $limit,
				"first_url" => "?search_type=". $search_type ."&search=". $search ."&search_lang=". $search_lang ."&search_level=". $search_level,
				"suffix" => "&search_type=". $search_type ."&search=". $search ."&search_lang=". $search_lang ."&search_level=". $search_level,
			);

			$this->pagination->initialize($config);
			$get_data["pagination"] = $this->pagination->create_links();

			$this->set_view("admin/member/member_withdrawal_list", $get_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
}