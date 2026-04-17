<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends FRONT_Controller {
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

	public $memberField;
    public $arr_global_subject = array(
        "kor" => array(
            "mail_id" => "아이디찾기 메일발송", "mail_pw" => "비밀번호찾기 메일발송", "find_id" => "회원님의 아이디 찾기가 완료되었습니다.", "find_pw" =>"임시 비밀번호를 발송하였습니다.",
        ),
        "eng" => array(
            "mail_id" => "Send ID Finder", "mail_pw" => "Send password search mail", "find_id" => "Your ID is now complete.", "find_pw" =>"We have sent you a temporary password.",
        ),
        "chn" => array(
            "mail_id" => "发送ID查找器", "mail_pw" => "发送密码搜索邮件", "find_id" => "您的身份证现已完成。", "find_pw" =>"我们已经发给您一个临时密码。",
        ),
        "jpn" => array(
            "mail_id" => "ID検索メール送信", "mail_pw" => "パスワードを忘れたメールを送信", "find_id" => "会員のIDを検索が完了しました。", "find_pw" =>"仮パスワードを送信しました。",
        ),
    );
    public $arr_global_message = array(
        "kor" => array(
            "find_id" => "회원님의 아이디를 발송하였습니다.", "find_pw" => "비밀번호를 발송하였습니다.<br/>로그인 하신 후 비밀번호를 변경해주세요.",
        ),
        "eng" => array(
            "find_id" => "I have sent out your ID.", "find_pw" => "You have sent your password. <br/> Please log in and change your password",
        ),
        "chn" => array(
            "find_id" => "我已经发出了你的身份证。", "find_pw" => "您已发送密码。<br/>请登录并更改密码。",
        ),
        "jpn" => array(
            "find_id" => "会員のIDを送信しました。", "find_pw" => "パスワードを送信しました。<br/>ログインした後、パスワードを変更してください。",
        ),
    );

	public $pageTitles;
	public function __construct() {
		parent::__construct();

		$this->load->model("Front_Member_model");
		$this->load->library("form_validation");
		$this->config->load("cfg_memberField");
		$this->memberField = $this->config->item("memberField");
		$this->load->model('Database_model', 'dm');
		$this->load->library('Sendemail');

		//2018-10-04 James terms model 추가
		$this->load->model("Terms_model");
	}

	/*
	 * 로그인
	 */
	public function login() {
		try {
			if(defined("_IS_LOGIN")) {
				throw new Exception(print_language("already_signed_in"));
			}

			print_language("member_who_withdrew", $this->memberField["name"][$this->_site_language]["userid"]);
			$this->form_validation->set_rules("userid", $this->memberField["name"][$this->_site_language]["userid"], "trim|required|xss_clean");
			$this->form_validation->set_rules("password", $this->memberField["name"][$this->_site_language]["password"], "trim|required|xss_clean");

			if ($this->form_validation->run() == true) {
				//if($this->input->post('userid', true) == "superman" && $this->input->post("password", true)) {
				//	$this->session->set_userdata(
				//		array(
				//			"member" => array(
				//				"userid" => "superman",
				//				"name" => "관리자",
				//				"group" => "",
				//				"level" => 99,
				//			)
				//		)
				//	);
				//} else {
				$data = array(
					"userid" => $this->input->post("userid", true),
					"password" => $this->input->post("password", true),
					"encrypt" => $this->input->post("encrypt", true),
				);

				$get_data = $this->Front_Member_model->login_chk($data);
				$msg = "";

				if(!$get_data) {
					throw new Exception(print_language("does_not_exist", $this->memberField["name"][$this->_site_language]["userid"], 1));
				} else if($get_data["yn_password"] != "y") {
					throw new Exception(print_language("does_not_match", $this->memberField["name"][$this->_site_language]["password"]));
				} else if($get_data["yn_status"] != "y"){
					throw new Exception(print_language("member_who_withdrew"));
				}

				$set_data = array();

				$tmp_member = $this->session->__get('member');
				if(ib_isset($tmp_member)) {
					$set_data["member"] = $tmp_member;
					unset($tmp_member);
				}

				$set_data["member"][ib_isset($this->_site_language, "kor")] = array(
					"userid" => $get_data["userid"],
					"name" => $get_data["name"],
					"group" => $get_data["group"],
					"level" => $get_data["level"],
					"password_skip_cnt" => $get_data["password_skip_cnt"],
					"yn_change_password" => $get_data["yn_change_password"],
					"language" => $this->_site_language,
				);

				$this->Front_Member_model->login_ok($data);
				$this->session->set_userdata($set_data);

				$now = date_create(date("Y-m-d"));
				$regdt = date_create($get_data['regdt']);
				$diff = date_diff($regdt, $now);
				$months = $diff->days / 30;

				/*
				if(가입한 지 3개월) {
					if(비밀번호 변경한 지 3개월) {
					}
				}
				*/
				if($diff->invert == 0 && $months > 2 ) { // 3 months
					if($get_data['password_moddt']) {
						$change_date = date_create($get_data['password_moddt']);
						$change_diff = date_diff($change_date, $now);
						$cmonths = $change_diff->days / 30;
						if($change_diff->invert == 0 && $cmonths > 2) {
							redirect("/member/periodic_change_pw");
						}
					} else {
						redirect("/member/periodic_change_pw");
					}
				}

				if($this->input->post("return_url", true)) {
					redirect(base_url($this->input->post("return_url", true)));
				} else {
					redirect(base_url());
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}

			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_->assign("return_url", urlencode($this->input->get("return_url", true)));
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function logout() {
		$this->session->unset_userdata("member.".$this->_site_language);
		unset($_SESSION["member"][$this->_site_language]);
		redirect(base_url());
	}

	public function find_id() {
		try {
			$manage = $this->dm->get('da_manage')[0];
			if($manage['smtp_userid'] === '' || $manage['smtp_password'] === '') throw new Exception('아이디찾기를 하시려면 SMTP 설정을 하셔야 합니다.');
			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function find_pw() {
		try {
			$manage = $this->dm->get('da_manage')[0];
			if($manage['smtp_userid'] === '' || $manage['smtp_password'] === '') throw new Exception('비밀번호찾기를 하시려면 SMTP 설정을 하셔야 합니다.');
			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function find() {
		try {
			$mode = $this->input->post("mode", true);

			switch($mode) {
				case "find_id" :
					$this->form_validation->set_rules("name", $this->memberField["name"][$this->_site_language]["name"], "required|trim|xss_clean");
					$this->form_validation->set_rules("email", $this->memberField["name"][$this->_site_language]["email"], "trim|xss_clean|valid_email|required");
				break;
				case "find_pw" :
					$this->form_validation->set_rules("userid", $this->memberField["name"][$this->_site_language]["userid"], "required|trim|xss_clean");
					$this->form_validation->set_rules("name", $this->memberField["name"][$this->_site_language]["name"], "required|trim|xss_clean");
					$this->form_validation->set_rules("email", $this->memberField["name"][$this->_site_language]["email"], "trim|xss_clean|valid_email|required");
				break;
				default :
					redirect("/member/login");
				break;
			}
			if($this->form_validation->run()) {
				$data = $this->input->post(null, true);
				$data["yn_status"] = "y";
                $data["language"] = $this->_site_language;

				$get_member_data = table_data_match("da_member", $data);
				$this->db->select(array("userid", "language"));
				$this->db->where($get_member_data);

				$memberResult = $this->db->get("da_member")->row_array(); // 회원 테이블 조회

				$dormantFlag = false; // 휴면 회원 여부 true -> 휴면

				if(!isset($memberResult)) { // array_merge를 위해 userid,language 생성
					$memberResult['userid'] = null;
					$memberResult['language'] = null;
					$dormantFlag = true;
				}

				$get_dormant_data = table_data_match("da_dormant", $data);
				$this->db->select(array("userid", "language"));
				$this->db->where($get_dormant_data);

				$dormantResult = $this->db->get("da_dormant")->row_array(); // 휴면 회원 테이블 조회

				if(!isset($dormantResult)) {
					$dormantResult['userid'] = null;
					$dormantResult['language'] = null;
					$dormantFlag = false;
				}
				if($dormantFlag) {
					$result = array_merge($memberResult, $dormantResult);
				}else{
					$result = array_merge($dormantResult, $memberResult);
				}

				if(isset($result['userid'])) {
                    $extrainfos = [];
					if($mode === 'find_id') {
						$extrainfos = ['find_id' => $result['userid'], 'user_name' => $data['name']];
					}
					if($mode === 'find_pw') {
						$password = (string)rand(0000000000, 9999999999);
						$this->dm->update('da_member', [
                            'userid' => $result['userid'],
                            'language' => $result['language']
                        ], [
                            'password' => base64_encode(hash('sha256', $password, true))
                        ]);
                        $extrainfos = ['find_password' => $password, 'user_name' => $data['name']];
					}

                    $this->sendemail->setup([
                        'language' => 'kor',
                        'type' => $mode,
                        'mailto' => ['name' => $data['name'], 'email' => $data['email']],
                        'extrainfos' => $extrainfos,
                    ]);

					$form = form_open('member/find_ok', ['name' => 'frm', 'method' => 'POST'], $data).form_close();
					$this->template_->assign('form', $form);
					$this->template_print($this->template_path());
				} else {
					throw new Exception(print_language("no_member_information_found"));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
				throw new Exception(print_language("no_member_information_found"));
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function find_ok() {
		try {
			$this->form_validation->set_rules("mode", "", "trim|xss_clean|required");
			$this->form_validation->set_rules("email", "", "trim|xss_clean|required");

			if($this->form_validation->run()) {
				$mode = $this->input->post("mode", true);
				$email = $this->input->post("email", true);
				$find = array();

				$mailid = preg_replace("/@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", "", $email);
				$mailid_strlen = mb_strlen($mailid);
				$rpad_mailid = str_pad(substr($mailid, 0, round($mailid_strlen / 2)), $mailid_strlen, "*", STR_PAD_RIGHT);
				$rpad_mail = str_replace($mailid, $rpad_mailid, $email);

				$find = array(
					"mode" => $mode,
					"email" => $rpad_mail,
				);

				if($mode =="find_id") {
					//$find["subject"] = "회원님의 아이디 찾기가 완료되었습니다.";
					$find["subject"] = $this->arr_global_subject[$this->_site_language]["find_id"];
					//$find["message"] = "회원님의 아이디를 발송하였습니다.";
					$find["message"] = $this->arr_global_message[$this->_site_language]["find_id"];
				} else if($mode == "find_pw") {
					//$find["subject"] = "임시 비밀번호를 발송하였습니다.";
					$find["subject"] = $this->arr_global_subject[$this->_site_language]["find_pw"];
					//$find["message"] = "비밀번호를 발송하였습니다.<br/>로그인 하신 후 비밀번호를 변경해주세요.";
					$find["message"] = $this->arr_global_message[$this->_site_language]["find_pw"];
				}

				$this->template_->assign("mode", $mode);
				$this->template_->assign("find", $find);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
				$this->template_print($this->template_path());
			} else {
				throw new Exception(print_language("no_member_information_found"));
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}




	public function join_agreement() {
		try {
			if(defined("_IS_LOGIN")) {
				throw new Exception(print_language("already_signed_in"));
			}

			$cfg_site = $this->config->item("cfg_site");

			$site_language = $this->session->userdata("site_language");
			$terms = array();

			$termsNm = [
				"agreement",
				"usePolicy"
			];

			foreach($termsNm as $val){
				$searchData = [
					"code"=>$val,
					"language"=>$site_language,
				];

				$terms[$val] = $this->Terms_model->getTermsData($searchData);
			}

			foreach($cfg_site[$site_language] as $key => $value) {
				foreach($terms as &$val){
					$val = str_replace("{\$".$key."}", $value	, $val);
				}
			}

			$this->template_->assign("terms", $terms);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function join() {
		try {
			if(defined("_IS_LOGIN")) {
				throw new Exception(print_language("already_signed_in"));
			}
			//2020-02-25 Inbet Matthew 약관동의 없이 페이지 접근시 경고창 호출 후 리다이렉트 처리
			if(isset($_REQUEST['agree']) == false && isset($_REQUEST['agree2']) == false) {
				switch($this->_site_language){
					case "kor":
						$msg = "이용약관에 동의하셔야 합니다. 약관동의 페이지로 이동합니다";
						break;
					case "eng":
						$msg = "You must agree to the Terms of Use. Go to the agreement page.";
						break;
					case "chn":
						$msg = "您必须同意使用条款。进入协议页面。";
						break;
					case "jpn":
						$msg = "利用規約に同意する必要があります。利用規約に同意のページに移動します。";
						break;
				}
				echo '<script>alert("'.$msg.'");window.location.href = "/member/join_agreement" ;</script>';
			}
			//Matthew End
			$this->load->library("captcha");
			$this->template_->assign("memberField", $this->memberField);

			$captcha = $this->captcha->get_captcha("join");
			$this->template_->assign("captcha", $captcha);

			$this->config->load("cfg_country_info");
			$country_info["country_info"] = $this->config->item("country_info");
			$country_info["default_country_Info"] = $this->config->item("default_country_Info");
			if(!count($country_info["default_country_Info"]) > 0) {
				$default_country_name['eng'] = array('Albania','Algeria','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Belarus','Belgium','Bhutan','Bosnia and Herzegovina','Botswana','Brazil','Brunei Darussalam','Bulgaria','Burma (Myanmar)','Cambodia','Canada','Cape Verde','Chile','China','Costa Rica','Croatia (Hrvatska)','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominican Republic','Ecuador','Egypt','Estonia','Ethiopia','Fiji','Finland','France','Georgia','Germany','Greece','Hong Kong','Hungary','India','Indonesia','Iran','Ireland','Israel','Japan','Jordan','Kazakhstan','Kenya','Laos','Latvia','Luxembourg','Macau','Macedonia','Malaysia','Maldives','Mauritius','Mexico','Mongolia','Morocco','Mozambique','Nepal','Netherlands','Netherlands Antilles','New Zealand (Aotearoa)','Nigeria','Norway','Oman','Pakistan','Panama','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saudi Arabia','Singapore','Slovak Republic','Slovenia','Spain','Sri Lanka','Sweden','Switzerland','Taiwan','Tanzania','Thailand','Tunisia','Turkey','Ukraine','United Arab Emirates','United Kingdom','United States','Uzbekistan','Viet Nam','Zambia');

				$default_country_name['chn'] = array('阿尔巴尼亚','阿尔及利亚','阿根廷','亚美尼亚','澳大利亚','奥地利','阿塞拜疆','巴林','孟加拉国','白俄罗斯','比利时','丁烷','波斯尼亚和黑塞哥维那','博茨瓦纳','巴西','文莱','保加利亚','缅甸(缅甸)','柬埔寨','加拿大','佛得角','智利','中国','哥斯达黎加','克罗地亚','古巴','塞浦路斯','捷克共和国','丹麦','吉布提','多米尼加共和国','厄瓜多尔','埃及','爱沙尼亚','埃塞俄比亚','斐济','芬兰','法国','格鲁吉亚','德国','希腊','香港','匈牙利','印度','印尼','伊朗','爱尔兰','以色列','日本','乔丹','哈萨克斯坦','肯尼亚','老挝','拉脱维亚','卢森堡','澳门','马其顿','马来西亚','马尔代夫','毛里求斯','墨西哥','蒙古','摩洛哥','莫桑比克','尼泊尔','荷兰','荷属安的列斯肯尼思不应该','新西兰(新西兰)','尼日利亚','挪威','阿曼','巴基斯坦','巴拿马','秘鲁','菲律宾','波兰','葡萄牙','卡塔尔','罗马尼亚','俄罗斯','卢旺达','沙特阿拉伯','新加坡','斯洛伐克共和国','斯洛文尼亚','西班牙','斯里兰卡','瑞典','瑞士','台湾','坦桑尼亚','泰国','突尼斯','土耳其','乌克兰','阿拉伯联合酋长国','英国','美国','乌兹别克斯坦','越南','赞比亚');

				$default_country_name['jpn'] = array('アルバニア','アルジェリア','アルゼンチン','アルメニア','オーストラリア','オーストリア','アゼルバイジャン','バーレーン','バングラデシュ','ベラルーシ','ベルギー','ブータン','ボスニア・ヘルツェゴビナ','ボツワナ','ブラジル','ブルネイ','ブルガリア','ミャンマー','カンボジア','カナダ','カーボベルデ','チリ','中国','コスタリカ','クロアチア','キューバ','キプロス','チェコ共和国','デンマーク','ジブチ','ドミニカ共和国','エクアドル','エジプト','エストニア','エチオピア','フィジー','フィンランド','フランス','ジョージア','ドイツ','ギリシャ','香港','ハンガリー','インド','インドネシア','とは','アイルランド','イスラエル','日本','ヨルダン','カザフスタン','ケニア','ラオス','ラトビア','ルクセンブルク','マカオ','マケドニア共和国','マレーシア','モルディブ','モーリシャス','メキシコ','モンゴル','モロッコ','モザンビーク','ネパール','オランダ','オランダ領アンアンティル','ニュージーランド','ナイジェリア','ノルウェー','オマーン','パキスタン','パナマ','ペルー','フィリピン','ポーランド','ポルトガル','カタル','ルーマニア','ロシア','ルワンダ','サウジアラビア','シンガポール','スロバキア','スロベニア','スペイン','スリランカ','スウェーデン','スイス','台湾','タンザニア','タイ','チュニジア','トルコ','ウクライナ','アラブ首長国連邦','イギリス','美國','ウズベキスタン','ベトナム','ザンビア');

				$default_country_mark = array('(Albania)','(Algeria)','(Argentina)','(Armenia)','(Australia)','(Austria)','(Azerbaijan)','(Bahrain)','(Bangladesh)','(Belarus)','(Belgium)','(Bhutan)','(Bosnia and Herzegovina)','(Botswana)','(Brazil)','(Brunei Darussalam)','(Bulgaria)','(Burma (Myanmar))','(Cambodia)','(Canada)','(Cape Verde)','(Chile)','(China)','(Costa Rica)','(Croatia (Hrvatska))','(Cuba)','(Cyprus)','(Czech Republic)','(Denmark)','(Djibouti)','(Dominican Republic)','(Ecuador)','(Egypt)','(Estonia)','(Ethiopia)','(Fiji)','(Finland)','(France)','(Georgia)','(Germany)','(Greece)','(Hong Kong)','(Hungary)','(India)','(Indonesia)','(Iran)','(Ireland)','(Israel)','(Japan)','(Jordan)','(Kazakhstan)','(Kenya)','(Laos)','(Latvia)','(Luxembourg)','(Macau)','(Macedonia)','(Malaysia)','(Maldives)','(Mauritius)','(Mexico)','(Mongolia)','(Morocco)','(Mozambique)','(Nepal)','(Netherlands)','(Netherlands Antilles)','(New Zealand (Aotearoa))','(Nigeria)','(Norway)','(Oman)','(Pakistan)','(Panama)','(Peru)','(Philippines)','(Poland)','(Portugal)','(Qatar)','(Romania)','(Russia)','(Rwanda)','(Saudi Arabia)','(Singapore)','(Slovak Republic)','(Slovenia)','(Spain)','(Sri Lanka)','(Sweden)','(Switzerland)','(Taiwan)','(Tanzania)','(Thailand)','(Tunisia)','(Turkey)','(Ukraine)','(United Arab Emirates)','(United Kingdom)','(United States)','(Uzbekistan)','(Viet Nam)','(Zambia)');

				$default_country_code = array('(+355)','(+213)','(+54)','(+374)','(+61)','(+43)','(+994)','(+973)','(+880)','(+375)','(+32)','(+975)','(+387)','(+267)','(+55)','(+673)','(+359)','(+95)','(+855)','(+1)','(+238)','(+56)','(+86)','(+506)','(+385)','(+53)','(+357)','(+420)','(+45)','(+253)','(+1)','(+593)','(+20)','(+372)','(+251)','(+679)','(+358)','(+33)','(+995)','(+49)','(+30)','(+852)','(+36)','(+91)','(+62)','(+98)','(+353)','(+972)','(+81)','(+962)','(+7)','(+254)','(+856)','(+371)','(+352)','(+853)','(+389)','(+60)','(+960)','(+230)','(+52)','(+976)','(+212)','(+258)','(+977)','(+31)','(+599)','(+64)','(+234)','(+47)','(+968)','(+92)','(+507)','(+51)','(+63)','(+48)','(+351)','(+974)','(+40)','(+7)','(+250)','(+966)','(+65)','(+421)','(+386)','(+34)','(+94)','(+46)','(+41)','(+886)','(+255)','(+66)','(+216)','(+90)','(+380)','(+971)','(+44)','(+1)','(+998)','(+84)','(+260)');

				$this->config->load("cfg_siteLanguage");
				$support_language = $this->config->item("site_language")['support_language'];

				foreach($default_country_mark as $key => $val){
					$tempArr['mark'] = $val;
					$tempArr['code'] = $default_country_code[$key];
					foreach($support_language as $language_key => $language_value){
						if($language_key != 'kor') {
							$tempArr['name'][$language_key] = $default_country_name[$language_key][$key];
						}
					}
					$country_info["default_country_Info"][] = $tempArr;
				}
			}

			$country_info["country_info"] = array_merge($country_info["default_country_Info"], $country_info["country_info"]);
			$this->template_->assign("country_info", $country_info["country_info"]);
			$arr_ex = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
							"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드
			$this->template_->assign("arr_ex", $arr_ex);

			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function join_ok() {
		try {
			$this->load->library("captcha");

			$arr_ex = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
							"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드
			foreach($this->input->post(null, true) as $key => $val){
				if(in_array($key, $arr_ex) && $this->memberField["type"][$this->_site_language][$key] == 'email') {
					$_POST[$key] = $_POST[$key.'_id']. '@' .$_POST[$key.'_domain'];
				}
			}

			$this->form_validation->set_rules("userid", $this->memberField["name"][$this->_site_language]["userid"], "required|trim|xss_clean|min_length[4]|max_length[14]|callback_userid_duplicate_check");
			$this->form_validation->set_rules("name", $this->memberField["name"][$this->_site_language]["name"], "required|trim|xss_clean|max_length[10]");
			$this->form_validation->set_rules("password", $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean|min_length[10]|max_length[16]callback__password_validate");
			$this->form_validation->set_rules("password2", $this->memberField["name"][$this->_site_language]["password"]. " confirm", "required|trim|xss_clean|min_length[10]|max_length[16]|matches[password]");

			$this->form_validation->set_rules("sex", $this->memberField["name"][$this->_site_language]["sex"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["sex"]) && isset($this->memberField["require"][$this->_site_language]["sex"]) ? "|required" : ""));
			$this->form_validation->set_rules("birth", $this->memberField["name"][$this->_site_language]["birth"], "trim|xss_clean|is_natural". (isset($this->memberField["use"][$this->_site_language]["birth"]) && isset($this->memberField["require"][$this->_site_language]["birth"]) ? "|required" : ""));
			//2020-03-02 Inbet Matthew 이메일 중복확인 이메일 사용 체크가 안되있을 경우 검사하지 않게 변경
			$this->form_validation->set_rules("email", $this->memberField["name"][$this->_site_language]["email"], "trim|xss_clean|valid_email".(isset($this->memberField["use"][$this->_site_language]["email"]) && isset($this->memberField["require"][$this->_site_language]["email"]) ? "|callback_email_duplicate_check" : ""). (isset($this->memberField["use"][$this->_site_language]["email"]) && isset($this->memberField["require"][$this->_site_language]["email"]) ? "|required" : ""));
			//Matthew End

			// 필수 입력 추가 @20200922
			$required_zip = $this->memberField["require"][$this->_site_language]["zip"] == "checked" ? "|required" : "";
			$required_country = $this->memberField["require"][$this->_site_language]["country"] == "checked" ? "|required" : "";
			$required_city = $this->memberField["require"][$this->_site_language]["city"] == "checked" ? "|required" : "";
			$required_state_province_region = $this->memberField["require"][$this->_site_language]["state_province_region"] == "checked" ? "|required" : "";
			$required_mobile_country_code = $this->memberField["require"][$this->_site_language]["mobile_country_code"] == "checked" ? "|required" : "";
			switch($this->_site_language) {
				case 'kor':
					//$this->form_validation->set_rules("zip", "우편번호", "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|".$required_zip : ""));
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

			$this->form_validation->set_rules("address", $this->memberField["name"][$this->_site_language]["address"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) && isset($this->memberField["require"][$this->_site_language]["address"]) ? "|required" : ""));
			$this->form_validation->set_rules("address2",  $this->memberField["name"][$this->_site_language]["address2"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address2"]) && isset($this->memberField["require"][$this->_site_language]["address2"]) ? "|required" : ""));
			$this->form_validation->set_rules("mobile", $this->memberField["name"][$this->_site_language]["mobile"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["mobile"]) && isset($this->memberField["require"][$this->_site_language]["mobile"]) ? "|required" : ""));
			$this->form_validation->set_rules("fax", $this->memberField["name"][$this->_site_language]["fax"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["fax"]) && isset($this->memberField["require"][$this->_site_language]["fax"]) ? "|required" : ""));
			$this->form_validation->set_rules("yn_mailling", $this->memberField["name"][$this->_site_language]["yn_mailling"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["yn_mailling"]) && isset($this->memberField["require"][$this->_site_language]["yn_mailling"]) ? "|required" : ""));
			$this->form_validation->set_rules("yn_sms", $this->memberField["name"][$this->_site_language]["yn_sms"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["yn_sms"]) && isset($this->memberField["require"][$this->_site_language]["yn_sms"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex1", $this->memberField["name"][$this->_site_language]["ex1"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex1"]) && isset($this->memberField["require"][$this->_site_language]["ex1"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex2", $this->memberField["name"][$this->_site_language]["ex2"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex2"]) && isset($this->memberField["require"][$this->_site_language]["ex2"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex3", $this->memberField["name"][$this->_site_language]["ex3"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex3"]) && isset($this->memberField["require"][$this->_site_language]["ex3"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex4", $this->memberField["name"][$this->_site_language]["ex4"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex4"]) && isset($this->memberField["require"][$this->_site_language]["ex4"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex5", $this->memberField["name"][$this->_site_language]["ex5"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex5"]) && isset($this->memberField["require"][$this->_site_language]["ex5"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex6", $this->memberField["name"][$this->_site_language]["ex6"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex6"]) && isset($this->memberField["require"][$this->_site_language]["ex6"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex7", $this->memberField["name"][$this->_site_language]["ex7"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex7"]) && isset($this->memberField["require"][$this->_site_language]["ex7"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex8", $this->memberField["name"][$this->_site_language]["ex8"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex8"]) && isset($this->memberField["require"][$this->_site_language]["ex8"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex9", $this->memberField["name"][$this->_site_language]["ex9"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex9"]) && isset($this->memberField["require"][$this->_site_language]["ex9"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex10", $this->memberField["name"][$this->_site_language]["ex10"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex10"]) && isset($this->memberField["require"][$this->_site_language]["ex10"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex11", $this->memberField["name"][$this->_site_language]["ex11"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex11"]) && isset($this->memberField["require"][$this->_site_language]["ex11"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex12", $this->memberField["name"][$this->_site_language]["ex12"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex12"]) && isset($this->memberField["require"][$this->_site_language]["ex12"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex13", $this->memberField["name"][$this->_site_language]["ex13"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex13"]) && isset($this->memberField["require"][$this->_site_language]["ex13"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex14", $this->memberField["name"][$this->_site_language]["ex14"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex14"]) && isset($this->memberField["require"][$this->_site_language]["ex14"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex15", $this->memberField["name"][$this->_site_language]["ex15"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex15"]) && isset($this->memberField["require"][$this->_site_language]["ex15"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex16", $this->memberField["name"][$this->_site_language]["ex16"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex16"]) && isset($this->memberField["require"][$this->_site_language]["ex16"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex17", $this->memberField["name"][$this->_site_language]["ex17"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex17"]) && isset($this->memberField["require"][$this->_site_language]["ex17"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex18", $this->memberField["name"][$this->_site_language]["ex18"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex18"]) && isset($this->memberField["require"][$this->_site_language]["ex18"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex19", $this->memberField["name"][$this->_site_language]["ex19"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex19"]) && isset($this->memberField["require"][$this->_site_language]["ex19"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex20", $this->memberField["name"][$this->_site_language]["ex20"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["ex20"]) && isset($this->memberField["require"][$this->_site_language]["ex20"]) ? "|required" : ""));

			$this->form_validation->set_rules("captcha", $this->memberField["name"][$this->_site_language]["auto_regist_prevention_text"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["auto_regist_prevention_text"]) && isset($this->memberField["require"][$this->_site_language]["auto_regist_prevention_text"]) ? "|required" : ""));
			#$this->form_validation->set_rules("captcha", "자동가입 방지", "trim|xss_clean|required");


			if($this->form_validation->run()){
				$sess_captcha = $this->captcha->get_sess_captcha("join");

				if(isset($this->memberField["use"][$this->_site_language]["auto_regist_prevention_text"]) && isset($this->memberField["require"][$this->_site_language]["auto_regist_prevention_text"])) {
					if($sess_captcha["word"] != $this->input->post("captcha", true)) {
						throw new Exception(print_language("prevent_automatic_does_not_match", $this->memberField["name"][$this->_site_language]["auto_regist_prevention_text"]));
					}
				}

				$set_data = $this->input->post(null, true);

				//2018-10-01 James 회원 필드 language 추가
				$set_data['language'] = $this->session->userdata('site_language');

				$result = $this->Front_Member_model->member_register("register", $set_data);

				if($result) {
					go("/member/join_ok", "parent");
				} else {
					throw new Exception(print_language("already_member"));
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}

			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
				$this->template_print($this->template_path());
			}
		} catch(Exception $e) {
			msg($e->getMessage());
		}
	}


	public function mypage() {
		try {
			if(!defined("_IS_LOGIN")) {
				msg(print_language("go_to_the_login_page"), "/member/login?return_url=". urlencode(current_full_url()));
			}
			$arr_ex = array("ex1", "ex2", "ex3", "ex4", "ex5", "ex6", "ex7", "ex8", "ex9", "ex10",
							"ex11", "ex12", "ex13", "ex14", "ex15", "ex16", "ex17", "ex18", "ex19", "ex20"); // 관리자 커스텀 필드
			foreach($this->input->post(null, true) as $key => $val){
				if(in_array($key, $arr_ex) && $this->memberField["type"][$this->_site_language][$key] == 'email') {
					$_POST[$key] = $_POST[$key.'_id']. '@' .$_POST[$key.'_domain'];
				}
			}

			$this->form_validation->set_rules("name", $this->memberField["name"][$this->_site_language]["name"], "required|trim|xss_clean|max_length[10]");
			//$this->form_validation->set_rules("password", $this->memberField["name"][$this->_site_language]["password"], "trim|xss_clean|min_length[10]|max_length[16]");
			//$this->form_validation->set_rules("password2", $this->memberField["name"][$this->_site_language]["password"] ." ". print_language("confirm"), "trim|xss_clean|min_length[10]|max_length[16]|matches[password]");
			$this->form_validation->set_rules("sex", $this->memberField["name"][$this->_site_language]["sex"], "trim|xss_clean". (isset($this->memberField["use"]["sex"]) && isset($this->memberField["require"]["sex"]) ? "|required" : ""));
			$this->form_validation->set_rules("birth", "sdfsd", "trim|xss_clean|is_natural". (isset($this->memberField["use"]["birth"]) && isset($this->memberField["require"]["birth"]) ? "|required" : ""));
			$this->form_validation->set_rules("email", $this->memberField["name"][$this->_site_language]["email"], "trim|xss_clean|valid_email". (isset($this->memberField["use"]["email"]) && isset($this->memberField["require"]["email"]) ? "|required" : ""));

			// 필수 입력 추가 @20200922
			$required_zip = $this->memberField["require"][$this->_site_language]["zip"] == "checked" ? "|required" : "";
			$required_country = $this->memberField["require"][$this->_site_language]["country"] == "checked" ? "|required" : "";
			$required_city = $this->memberField["require"][$this->_site_language]["city"] == "checked" ? "|required" : "";
			$required_state_province_region = $this->memberField["require"][$this->_site_language]["state_province_region"] == "checked" ? "|required" : "";
			$required_mobile_country_code = $this->memberField["require"][$this->_site_language]["mobile_country_code"] == "checked" ? "|required" : "";
			switch($this->_site_language) {
				case 'kor':
					//$this->form_validation->set_rules("zip", "우편번호", "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|".$required_zip : ""));
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
/*
			switch($this->_site_language) {
				case 'kor':
					$this->form_validation->set_rules("zip", $this->memberField["name"][$this->_site_language]["zip"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					break;
				case 'eng':
					$this->form_validation->set_rules("zip", $this->memberField["name"][$this->_site_language]["zip"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("country", $this->memberField["name"][$this->_site_language]["country"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("city", $this->memberField["name"][$this->_site_language]["city"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("state_province_region", $this->memberField["name"][$this->_site_language]["state_province_region"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("mobile_country_code", "MOBILE_COUNTRY_CODE", "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["mobile"]) ? "|required" : ""));
					break;
				case 'chn':
					$this->form_validation->set_rules("zip", $this->memberField["name"][$this->_site_language]["zip"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("country", $this->memberField["name"][$this->_site_language]["country"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("city", $this->memberField["name"][$this->_site_language]["city"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("state_province_region", $this->memberField["name"][$this->_site_language]["state_province_region"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("mobile_country_code", "国家代码", "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["mobile"]) ? "|required" : ""));
					break;
				case 'jpn':
					$this->form_validation->set_rules("zip", $this->memberField["name"][$this->_site_language]["zip"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("country", $this->memberField["name"][$this->_site_language]["country"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("city", $this->memberField["name"][$this->_site_language]["city"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("state_province_region", $this->memberField["name"][$this->_site_language]["state_province_region"], "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["address"]) ? "|required" : ""));
					$this->form_validation->set_rules("mobile_country_code", "国家コード", "trim|xss_clean". (isset($this->memberField["use"][$this->_site_language]["mobile"]) ? "|required" : ""));
					break;
			}
*/
			$this->form_validation->set_rules("address", $this->memberField["name"][$this->_site_language]["address"], "trim|xss_clean". (isset($this->memberField["use"]["address"]) && isset($this->memberField["require"]["address"]) ? "|required" : ""));
			$this->form_validation->set_rules("address2",  $this->memberField["name"][$this->_site_language]["address2"], "trim|xss_clean". (isset($this->memberField["use"]["address2"]) && isset($this->memberField["require"]["address2"]) ? "|required" : ""));
			$this->form_validation->set_rules("mobile", $this->memberField["name"][$this->_site_language]["mobile"], "trim|xss_clean". (isset($this->memberField["use"]["mobile"]) && isset($this->memberField["require"]["mobile"]) ? "|required" : ""));

			$this->form_validation->set_rules("fax", $this->memberField["name"][$this->_site_language]["fax"], "trim|xss_clean". (isset($this->memberField["use"]["fax"]) && isset($this->memberField["require"]["fax"]) ? "|required" : ""));
			$this->form_validation->set_rules("yn_mailling", $this->memberField["name"][$this->_site_language]["yn_mailling"], "trim|xss_clean". (isset($this->memberField["use"]["yn_mailling"]) && isset($this->memberField["require"]["yn_mailling"]) ? "|required" : ""));
			$this->form_validation->set_rules("yn_sms", $this->memberField["name"][$this->_site_language]["yn_sms"], "trim|xss_clean". (isset($this->memberField["use"]["yn_sms"]) && isset($this->memberField["require"]["yn_sms"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex1", $this->memberField["name"][$this->_site_language]["ex1"], "trim|xss_clean". (isset($this->memberField["use"]["ex1"]) && isset($this->memberField["require"]["ex1"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex2", $this->memberField["name"][$this->_site_language]["ex2"], "trim|xss_clean". (isset($this->memberField["use"]["ex2"]) && isset($this->memberField["require"]["ex2"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex3", $this->memberField["name"][$this->_site_language]["ex3"], "trim|xss_clean". (isset($this->memberField["use"]["ex3"]) && isset($this->memberField["require"]["ex3"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex4", $this->memberField["name"][$this->_site_language]["ex4"], "trim|xss_clean". (isset($this->memberField["use"]["ex4"]) && isset($this->memberField["require"]["ex4"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex5", $this->memberField["name"][$this->_site_language]["ex5"], "trim|xss_clean". (isset($this->memberField["use"]["ex5"]) && isset($this->memberField["require"]["ex5"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex6", $this->memberField["name"][$this->_site_language]["ex6"], "trim|xss_clean". (isset($this->memberField["use"]["ex6"]) && isset($this->memberField["require"]["ex6"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex7", $this->memberField["name"][$this->_site_language]["ex7"], "trim|xss_clean". (isset($this->memberField["use"]["ex7"]) && isset($this->memberField["require"]["ex7"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex8", $this->memberField["name"][$this->_site_language]["ex8"], "trim|xss_clean". (isset($this->memberField["use"]["ex8"]) && isset($this->memberField["require"]["ex8"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex9", $this->memberField["name"][$this->_site_language]["ex9"], "trim|xss_clean". (isset($this->memberField["use"]["ex9"]) && isset($this->memberField["require"]["ex9"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex10", $this->memberField["name"][$this->_site_language]["ex10"], "trim|xss_clean". (isset($this->memberField["use"]["ex10"]) && isset($this->memberField["require"]["ex10"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex11", $this->memberField["name"][$this->_site_language]["ex11"], "trim|xss_clean". (isset($this->memberField["use"]["ex11"]) && isset($this->memberField["require"]["ex11"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex12", $this->memberField["name"][$this->_site_language]["ex12"], "trim|xss_clean". (isset($this->memberField["use"]["ex12"]) && isset($this->memberField["require"]["ex12"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex13", $this->memberField["name"][$this->_site_language]["ex13"], "trim|xss_clean". (isset($this->memberField["use"]["ex13"]) && isset($this->memberField["require"]["ex13"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex14", $this->memberField["name"][$this->_site_language]["ex14"], "trim|xss_clean". (isset($this->memberField["use"]["ex14"]) && isset($this->memberField["require"]["ex14"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex15", $this->memberField["name"][$this->_site_language]["ex15"], "trim|xss_clean". (isset($this->memberField["use"]["ex15"]) && isset($this->memberField["require"]["ex15"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex16", $this->memberField["name"][$this->_site_language]["ex16"], "trim|xss_clean". (isset($this->memberField["use"]["ex16"]) && isset($this->memberField["require"]["ex16"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex17", $this->memberField["name"][$this->_site_language]["ex17"], "trim|xss_clean". (isset($this->memberField["use"]["ex17"]) && isset($this->memberField["require"]["ex17"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex18", $this->memberField["name"][$this->_site_language]["ex18"], "trim|xss_clean". (isset($this->memberField["use"]["ex18"]) && isset($this->memberField["require"]["ex18"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex19", $this->memberField["name"][$this->_site_language]["ex19"], "trim|xss_clean". (isset($this->memberField["use"]["ex19"]) && isset($this->memberField["require"]["ex19"]) ? "|required" : ""));
			$this->form_validation->set_rules("ex20", $this->memberField["name"][$this->_site_language]["ex20"], "trim|xss_clean". (isset($this->memberField["use"]["ex20"]) && isset($this->memberField["require"]["ex20"]) ? "|required" : ""));

			if($this->form_validation->run()){
				$set_data = $this->input->post(null, true);
				$set_data['language'] = $this->_site_language;
				$get_data = table_data_match("da_member", $set_data);
				$result = $this->Front_Member_model->member_register("modify", $set_data);

				if($result) {
					msg(print_language("edit_membership_information"), "/member/mypage");
				}
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
			$this->config->load("cfg_country_info");
			$country_info["country_info"] = $this->config->item("country_info");
			$country_info["default_country_Info"] = $this->config->item("default_country_Info");
			if(!count($country_info["default_country_Info"]) > 0) {
				$default_country_name['eng'] = array('Albania','Algeria','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Belarus','Belgium','Bhutan','Bosnia and Herzegovina','Botswana','Brazil','Brunei Darussalam','Bulgaria','Burma (Myanmar)','Cambodia','Canada','Cape Verde','Chile','China','Costa Rica','Croatia (Hrvatska)','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominican Republic','Ecuador','Egypt','Estonia','Ethiopia','Fiji','Finland','France','Georgia','Germany','Greece','Hong Kong','Hungary','India','Indonesia','Iran','Ireland','Israel','Japan','Jordan','Kazakhstan','Kenya','Laos','Latvia','Luxembourg','Macau','Macedonia','Malaysia','Maldives','Mauritius','Mexico','Mongolia','Morocco','Mozambique','Nepal','Netherlands','Netherlands Antilles','New Zealand (Aotearoa)','Nigeria','Norway','Oman','Pakistan','Panama','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saudi Arabia','Singapore','Slovak Republic','Slovenia','Spain','Sri Lanka','Sweden','Switzerland','Taiwan','Tanzania','Thailand','Tunisia','Turkey','Ukraine','United Arab Emirates','United Kingdom','United States','Uzbekistan','Viet Nam','Zambia');

				$default_country_name['chn'] = array('阿尔巴尼亚','阿尔及利亚','阿根廷','亚美尼亚','澳大利亚','奥地利','阿塞拜疆','巴林','孟加拉国','白俄罗斯','比利时','丁烷','波斯尼亚和黑塞哥维那','博茨瓦纳','巴西','文莱','保加利亚','缅甸(缅甸)','柬埔寨','加拿大','佛得角','智利','中国','哥斯达黎加','克罗地亚','古巴','塞浦路斯','捷克共和国','丹麦','吉布提','多米尼加共和国','厄瓜多尔','埃及','爱沙尼亚','埃塞俄比亚','斐济','芬兰','法国','格鲁吉亚','德国','希腊','香港','匈牙利','印度','印尼','伊朗','爱尔兰','以色列','日本','乔丹','哈萨克斯坦','肯尼亚','老挝','拉脱维亚','卢森堡','澳门','马其顿','马来西亚','马尔代夫','毛里求斯','墨西哥','蒙古','摩洛哥','莫桑比克','尼泊尔','荷兰','荷属安的列斯肯尼思不应该','新西兰(新西兰)','尼日利亚','挪威','阿曼','巴基斯坦','巴拿马','秘鲁','菲律宾','波兰','葡萄牙','卡塔尔','罗马尼亚','俄罗斯','卢旺达','沙特阿拉伯','新加坡','斯洛伐克共和国','斯洛文尼亚','西班牙','斯里兰卡','瑞典','瑞士','台湾','坦桑尼亚','泰国','突尼斯','土耳其','乌克兰','阿拉伯联合酋长国','英国','美国','乌兹别克斯坦','越南','赞比亚');

				$default_country_name['jpn'] = array('アルバニア','アルジェリア','アルゼンチン','アルメニア','オーストラリア','オーストリア','アゼルバイジャン','バーレーン','バングラデシュ','ベラルーシ','ベルギー','ブータン','ボスニア・ヘルツェゴビナ','ボツワナ','ブラジル','ブルネイ','ブルガリア','ミャンマー','カンボジア','カナダ','カーボベルデ','チリ','中国','コスタリカ','クロアチア','キューバ','キプロス','チェコ共和国','デンマーク','ジブチ','ドミニカ共和国','エクアドル','エジプト','エストニア','エチオピア','フィジー','フィンランド','フランス','ジョージア','ドイツ','ギリシャ','香港','ハンガリー','インド','インドネシア','とは','アイルランド','イスラエル','日本','ヨルダン','カザフスタン','ケニア','ラオス','ラトビア','ルクセンブルク','マカオ','マケドニア共和国','マレーシア','モルディブ','モーリシャス','メキシコ','モンゴル','モロッコ','モザンビーク','ネパール','オランダ','オランダ領アンアンティル','ニュージーランド','ナイジェリア','ノルウェー','オマーン','パキスタン','パナマ','ペルー','フィリピン','ポーランド','ポルトガル','カタル','ルーマニア','ロシア','ルワンダ','サウジアラビア','シンガポール','スロバキア','スロベニア','スペイン','スリランカ','スウェーデン','スイス','台湾','タンザニア','タイ','チュニジア','トルコ','ウクライナ','アラブ首長国連邦','イギリス','美國','ウズベキスタン','ベトナム','ザンビア');

				$default_country_mark = array('(Albania)','(Algeria)','(Argentina)','(Armenia)','(Australia)','(Austria)','(Azerbaijan)','(Bahrain)','(Bangladesh)','(Belarus)','(Belgium)','(Bhutan)','(Bosnia and Herzegovina)','(Botswana)','(Brazil)','(Brunei Darussalam)','(Bulgaria)','(Burma (Myanmar))','(Cambodia)','(Canada)','(Cape Verde)','(Chile)','(China)','(Costa Rica)','(Croatia (Hrvatska))','(Cuba)','(Cyprus)','(Czech Republic)','(Denmark)','(Djibouti)','(Dominican Republic)','(Ecuador)','(Egypt)','(Estonia)','(Ethiopia)','(Fiji)','(Finland)','(France)','(Georgia)','(Germany)','(Greece)','(Hong Kong)','(Hungary)','(India)','(Indonesia)','(Iran)','(Ireland)','(Israel)','(Japan)','(Jordan)','(Kazakhstan)','(Kenya)','(Laos)','(Latvia)','(Luxembourg)','(Macau)','(Macedonia)','(Malaysia)','(Maldives)','(Mauritius)','(Mexico)','(Mongolia)','(Morocco)','(Mozambique)','(Nepal)','(Netherlands)','(Netherlands Antilles)','(New Zealand (Aotearoa))','(Nigeria)','(Norway)','(Oman)','(Pakistan)','(Panama)','(Peru)','(Philippines)','(Poland)','(Portugal)','(Qatar)','(Romania)','(Russia)','(Rwanda)','(Saudi Arabia)','(Singapore)','(Slovak Republic)','(Slovenia)','(Spain)','(Sri Lanka)','(Sweden)','(Switzerland)','(Taiwan)','(Tanzania)','(Thailand)','(Tunisia)','(Turkey)','(Ukraine)','(United Arab Emirates)','(United Kingdom)','(United States)','(Uzbekistan)','(Viet Nam)','(Zambia)');

				$default_country_code = array('(+355)','(+213)','(+54)','(+374)','(+61)','(+43)','(+994)','(+973)','(+880)','(+375)','(+32)','(+975)','(+387)','(+267)','(+55)','(+673)','(+359)','(+95)','(+855)','(+1)','(+238)','(+56)','(+86)','(+506)','(+385)','(+53)','(+357)','(+420)','(+45)','(+253)','(+1)','(+593)','(+20)','(+372)','(+251)','(+679)','(+358)','(+33)','(+995)','(+49)','(+30)','(+852)','(+36)','(+91)','(+62)','(+98)','(+353)','(+972)','(+81)','(+962)','(+7)','(+254)','(+856)','(+371)','(+352)','(+853)','(+389)','(+60)','(+960)','(+230)','(+52)','(+976)','(+212)','(+258)','(+977)','(+31)','(+599)','(+64)','(+234)','(+47)','(+968)','(+92)','(+507)','(+51)','(+63)','(+48)','(+351)','(+974)','(+40)','(+7)','(+250)','(+966)','(+65)','(+421)','(+386)','(+34)','(+94)','(+46)','(+41)','(+886)','(+255)','(+66)','(+216)','(+90)','(+380)','(+971)','(+44)','(+1)','(+998)','(+84)','(+260)');

				$this->config->load("cfg_siteLanguage");
				$support_language = $this->config->item("site_language")['support_language'];

				foreach($default_country_mark as $key => $val){
					$tempArr['mark'] = $val;
					$tempArr['code'] = $default_country_code[$key];
					foreach($support_language as $language_key => $language_value){
						if($language_key != 'kor') {
							$tempArr['name'][$language_key] = $default_country_name[$language_key][$key];
						}
					}
					$country_info["default_country_Info"][] = $tempArr;
				}
			}

			$country_info["country_info"] = array_merge($country_info["default_country_Info"], $country_info["country_info"]);
			$this->template_->assign("country_info", $country_info["country_info"]);
			$arr_where = array();
			$arr_where[] = array("userid", $this->_member["userid"]);
			$arr_where[] = array("language", $this->_member["language"]);
			$mypage = $this->Front_Member_model->get_view_member($arr_where);

			foreach($this->memberField['name'][$this->_site_language] as $key => $val){
				if(in_array($key, $arr_ex) && $this->memberField['use'][$this->_site_language][$key] == 'checked') {
					if($this->memberField['type'][$this->_site_language][$key] == 'email') {
						$temp = explode('@', $mypage['member_view'][$key]);
						$mypage['member_view'][$key."_email_id"] = $temp[0];
						$mypage['member_view'][$key."_email_domain"] = $temp[1];
					}
				}
			}

			$this->template_->assign("arr_ex", $arr_ex);
			$this->template_->assign("mypage", $mypage);
			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());

		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function change_pw() {
		try {
			if(!defined("_IS_LOGIN")) {
				msg(print_language("go_to_the_login_page"), "/member/login?return_url=". urlencode(current_full_url()));
			}

			$this->form_validation->set_rules("old_password", $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean");
			$this->form_validation->set_rules("password", print_language("new") ." ". $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean|min_length[10]|max_length[16]|callback__password_validate");
			$this->form_validation->set_rules("password2", print_language("new") ." ". $this->memberField["name"][$this->_site_language]["password"] ." ". print_language("confirm"), "required|trim|xss_clean|min_length[10]|max_length[16]|matches[password]");

			if($this->form_validation->run()){
				$set_data = $this->input->post(null, true);

				$data = array(
					"userid" => $this->_member["userid"],
					"password" => $set_data["old_password"]
				);

				$get_data = $this->Front_Member_model->login_chk($data);

				if($get_data["yn_password"] != "y") {
					throw new Exception(print_language("does_not_match", $this->memberField["name"][$this->_site_language]["password"]));
				} else if($get_data["yn_password"] == "y"){
					$set_data["password_moddt"] = date("Y-m-d H:i:s");
					$set_data["password_skip_cnt"] = "0";
					$set_data["language"] = $get_data["language"];

					$result = $this->Front_Member_model->member_register("modify", $set_data);
					if($result) {
						msg(print_language("change_password"), "/member/change_pw");
					}
				}
				throw new Exception(print_language("no_member_information_found"));
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}

			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function withdrawal() {
		try {
			if(!defined("_IS_LOGIN")) {
				msg(print_language("go_to_the_login_page"), "/member/login?return_url=". urlencode(current_full_url()));
			}
			$this->form_validation->set_rules("password", $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean");
			$this->form_validation->set_rules("withdrawal_reason", $this->memberField["name"][$this->_site_language]["withdrawal_reason"], "required|trim|xss_clean|max_length[100]");

			if($this->form_validation->run()) {
				$set_data = $this->input->post(null, true);
				$set_data["userid"] = $this->_member["userid"];

				$get_data = $this->Front_Member_model->login_chk($set_data);
				if($get_data["yn_password"] != "y") {
					throw new Exception(print_language("does_not_match", $this->memberField["name"][$this->_site_language]["password"]));
				} else if($get_data["yn_password"] == "y"){
					unset($set_data["userid"]);
					unset($set_data["password"]);
					$set_data["yn_status"] = "n";
					$set_data["withdrawal_reason"] = nl2br(htmlspecialchars($set_data["withdrawal_reason"]));
					$set_data["withdrawal_dt"] = date('Y-m-d H:i:s');
					$withdrawal_data = table_data_match("da_member", $set_data);
					$result = $this->db->update("da_member", $withdrawal_data, array("userid" => $this->_member["userid"]));
					if($result) {
						msg(print_language("member_withdrawal_processing"), "/member/logout");
					}
				}
				throw new Exception(print_language("no_member_information_found"));
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function periodic_change_pw() {
		try {
			if(!defined("_IS_LOGIN")) {
				msg(print_language("go_to_the_login_page"), "/member/login?return_url=". urlencode(current_full_url()));
			}
			$mode = $this->input->post("mode", true);
			if($mode == "change") {
				$this->form_validation->set_rules("old_password", $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean");
				$this->form_validation->set_rules("password", print_language("new") ." ". $this->memberField["name"][$this->_site_language]["password"], "required|trim|xss_clean|min_length[10]|max_length[16]|callback__password_validate");
				$this->form_validation->set_rules("password2", print_language("new") ." ". $this->memberField["name"][$this->_site_language]["password"] ." ". print_language("confirm"), "required|trim|xss_clean|min_length[10]|max_length[16]|matches[password]");
				//$this->db->set("password_moddt", "DATE_ADD(NOW(), INTERVAL +3 MONTH)", false);
				//$this->db->set("password", base64_encode(hash("sha256", $this->input->post("password", true), true)));
				//$this->db->update("da_member", null, array("userid" => $this->_member["userid"], "language" => $this->session->userdata('site_language')));
			} else if($mode == "nextChange") {
				$this->db->set("password_skip_cnt", "IFNULL(password_skip_cnt, 0) + 1", false);
				$this->db->set("password_moddt", "DATE_ADD(NOW(), INTERVAL +1 MONTH)", false);
				$result = $this->db->update("da_member", null, array("userid" => $this->_member["userid"], "language" => $this->session->userdata('site_language')));
				if($result) {
                    // 회원 데이터 변경 2018-11-09
                    $tmp_member = $this->session->__get('member');
                    if(ib_isset($tmp_member)) {
                        $tmp_member["member"]["password_change_status"] = "y";
                        $tmp_member["member"][$this->_skin_language]["password_change_status"] = "y";
                        $this->_member = $tmp_member;
                    } else {
    					$this->_member["password_change_status"] = "y";
                    }
					$this->session->set_userdata(array("member" => $this->_member));
					redirect(base_url());
				}
			}

			if($this->form_validation->run()){
				$set_data = $this->input->post(null, true);

				$data = array(
					"userid" => $this->_member["userid"],
					"password" => $set_data["old_password"]
				);
				$get_data = $this->Front_Member_model->login_chk($data);

				if($get_data["yn_password"] != "y") {
					throw new Exception(print_language("does_not_match", $this->memberField["name"][$this->_site_language]["password"]));
				} else if($get_data["yn_password"] == "y"){
					$set_data["password_moddt"] = date("Y-m-d H:i:s", strtotime("+3 months", strtotime(date("Y-m-d H:i:s"))));
					$set_data["password_skip_cnt"] = "0";

//debug($set_data);
//exit;
					$result = $this->Front_Member_model->member_register("modify", $set_data);
					if($result) {
                        // 회원 데이터 변경 2018-11-09
                        $tmp_member = $this->session->__get('member');
                        if(ib_isset($tmp_member)) {
                            $tmp_member["password_change_status"] = "y";
                            $tmp_member[$this->_skin_language]["password_change_status"] = "y";
                            $this->_member = $tmp_member;
                        } else {
                            $this->_member["password_change_status"] = "y";
                        }
						$this->session->set_userdata(array("member" => $this->_member));
						msg(print_language("change_password"), base_url());
					}
				}
				throw new Exception(print_language("no_member_information_found", $this->memberField["name"][$this->_site_language]["password"]));
			} else {
				if(validation_errors()) {
					throw new Exception(validation_errors());
				}
			}
			$this->template_->assign("memberField", $this->memberField);
			$this->template_->assign("page_title", $this->pageTitles[$this->_site_language][__FUNCTION__]);
			$this->template_print($this->template_path());
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	/*
	 * 패스워드 영어/숫자/특수문자 체크
	 */
	public function _password_validate(){
		$password = $this->input->post("password", true);

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
			$this->form_validation->set_message("_password_validate", print_language("password_validation", $this->memberField["name"][$this->_site_language]["password"]));
			return false;
		}
	}

	/*
	 * 아이디 중복체크
	 */
	public function userid_duplicate_check(){
		$userid = $this->input->post("userid", true);

		if(defined("_IS_AJAX")) {
			if($this->Front_Member_model->is_userid_duplicate($userid, $this->_site_language)) {
				$set_data = array("code" => true, "use" => false, "msg" => print_language("already_registered", $this->memberField["name"][$this->_site_language]["userid"]));
			} else {
				$set_data = array("code" => true, "use" => true, "msg" => print_language("available", $this->memberField["name"][$this->_site_language]["userid"]));
			}
			echo json_encode($set_data);
		} else {
			if($this->Front_Member_model->is_userid_duplicate($userid, $this->_site_language)) {
				$this->form_validation->set_message("userid_duplicate_check", print_language("already_registered", $this->memberField["name"][$this->_site_language]["userid"]));
				return false;
			} else {
				return true;
			}
		}
	}

	/*
	 * 이메일 중복체크
	 */
	public function email_duplicate_check(){
		$this->memberField = $this->config->item("memberField");

		$userid = $this->input->post("userid", true);
		$email = $this->input->post("email", true);

		if(defined("_IS_AJAX")) {
			if($this->Front_Member_model->is_email_duplicate($userid, $email)) {
				$set_data = array("code" => true, "use" => false, "msg" => print_language("already_registered", $this->memberField["name"][$this->_site_language]["email"]));
			} else {
				$set_data = array("code" => true, "use" => true, "msg" => print_language("available", $this->memberField["name"][$this->_site_language]["email"]));
			}
			echo json_encode($set_data);
		} else {
			if($this->Front_Member_model->is_email_duplicate($userid, $email)) {
				$this->form_validation->set_message("email_duplicate_check", print_language("already_registered", $this->memberField["name"][$this->_site_language]["email"]));
				return false;
			} else {
				return true;
			}
		}
	}

	public function dormant_check() {
		try {
			if(!defined("_IS_AJAX")) {
				throw new Exception(print_language("inaccessible_pages"));
			}

			$this->load->model("dormant_model");
			$cfg_site = $this->config->item("cfg_site");

			$set_data = array(
				"userid" => $this->input->post("userid", true),
				"password" => $this->input->post("password", true),
				"language" => $this->_site_language
			);

			$get_data = $this->dormant_model->dormant_chk($set_data);

			if($get_data["yn_password"] == "y") {
				$set_data = array("code" => true, "dormant" => true, "msg" => print_language("dormant_release_notification_message", $get_data["name"]));
			} else if($get_data["yn_password"] == "n") {
				$set_data = array("code" => true, "dormant" => false, "msg" => print_language("does_not_match", $this->memberField["name"][$this->_site_language]["password"]));
			} else {
				$set_data = array("code" => false, "dormant" => false);
			}
			echo json_encode($set_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function dormant_release() {
		try {
			if(!defined("_IS_AJAX")) {
				throw new Exception(print_language("inaccessible_pages"));
			}

			$this->load->model("dormant_model");

			$set_data = array(
				"userid" => $this->input->post("userid", true),
				"password" => $this->input->post("password", true),
				"language" => $this->_site_language
			);

			$result = $this->dormant_model->dormant_release($set_data);

			if($result) {
				$set_data = array("code" => true);
			} else {
				$set_data = array("code" => false, "error" => print_langauge("dormant_release_process_fail") ."\n". print_langauge("please_try_again"));
			}
			echo json_encode($set_data);
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}
}
