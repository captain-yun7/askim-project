<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$t_rtype   = $_POST['t_rtype'];
$search    = $_POST['search'];
$subsearch = $_POST['subsearch'];
$submode   = $_POST['submode'];
$name      = $_POST['name'];
$email     = $_POST['email'];
$hphone    = $_POST['hphone'];
$id        = $_POST['id'];
$cert_num  = $_POST['cert_number'];

if($search == "ok"){

	if(!strcmp($submode, "id")) {

		$subname = "아이디";
		$mailtype = "mem_id";

		if($t_rtype == "email" || $t_rtype == "") {
			$q_sql = " and email = '$email' ";
		} else if($t_rtype == "sms") {
			$q_sql = " and hphone = '$hphone' ";
		}

	} else if(!strcmp($submode, "passwd")) {

		$subname = "비밀번호";
		$mailtype = "mem_pw";
		if(isset($emailv) && $emailv) $email = $emailv;

		$id_sql = " and id = '$id' ";

	} else if(!strcmp($submode, "do")) {

		$subname = "휴면해제";
		$mailtype = "mem_dormancy_email";
		if(isset($emaild) && $emaild) $email = $emaild;

	}

	$resno = $resno1."-".$resno2;
	$mTbl = ($submode == "do") ? "wiz_member_dormancy" : "wiz_member";
	$sql = "
		select id
			 , passwd
			 , name
			 , email
			 , hphone
			 , wdate
			 , cert_number
			 , sns_login
		  from {$mTbl} 
		 where name = '$name' 
		   $q_sql
		   $id_sql
	";
	$result = query($sql);
	if($row = sql_fetch_arr($result)){

		switch($row['sns_login']) {
			case "NH" :
				$sns_name = "네이버";
				break;
			case "KK" :
				$sns_name = "카카오톡";
				break;
			case "FB" :
				$sns_name = "페이스북";
				break;
			case "TT" :
				$sns_name = "트위터";
				break;
		}

		if($row['sns_login'] && $sns_name) {
			echo json_encode(array("result"=>"9999", "msg"=>"회원님은 ".$sns_name." 가입회원입니다.\n".$sns_name." 로그인을 시도해주세요."));
			exit;
		}

		if(empty($rtype)) $rtype = "email";

		if($submode == "do") {
			InactiveMemberChangeTbl($id, 'm');
		}

		// 비밀번호인 경우 새로운 비밀번호로 update
		if($submode == "passwd" || $submode == "do") {

			$row['passwd'] = get_rand_str(10);

			$sql = "
				update wiz_member 
				   set old_user='N' 
					 , passwd = '".md5($row['passwd'])."' 
				 where id = '$id'
			";
			query($sql);

		}

		if(!strcmp($mem_info['method'], "A")) {

			for($ii = 0; $ii < strlen($row['id']); $ii++) {
				if($ii < 2) $id .= substr($row['id'], $ii, 1);
				else $id .= "*";
			}
			for($ii = 0; $ii < strlen($row['passwd']); $ii++) {
				if($ii < 2) $passwd .= substr($row['passwd'], $ii, 1);
				else $passwd .= "*";
			}

			alert("회원님의 ".$subname." 입니다. \\n\\".$subname." : ".${$submode}."");

		} else {

			if($submode == "id") {
				if($subsearch == "idsrh") {
					$id         = get_masking_phone('N',$row['id'],'');
					$email      = get_masking_email($row['email']);
					$hphone     = get_masking_phone('P',$row['hphone'],'B');
					$entry_date = substr($row['wdate'], 0, 10);
					if($row) {
						echo json_encode(array("result"=>"0000", "setid"=>$id, "name"=>$row['name'], "email"=>$email, "hp"=>$hphone, "entry"=>$entry_date, "oEmail"=>$row['email'], "oHp"=>$row['hphone']));
						exit;
					}
				} else if($subsearch == "idsend") {
					if($t_rtype == "email" || $t_rtype == "") {
						$re_info = $row;
						send_mailsms($mailtype, $re_info, "", $submode);

						$msg = "회원님의 ".$subname."를 이메일로 보내드렸습니다.\r\n이메일주소 : ".$row['email'];
						echo json_encode(array("result"=>"0000", "msg"=>$msg, "gourl"=>$idpw_url));
						exit;
					} else if($t_rtype == "sms") {
						if($site_info['sms_use'] == 'Y') {
							$se_num  = $site_info['site_tel'];
							$re_num  = $row['hphone'];
							$message = "고객님의 아이디는 ".$row['id']." 입니다.";
							send_sms($se_num, $re_num, str_conv($message, 'euc-kr'));

							$msg = "회원님의 아이디를 휴대폰으로 발송해드렸습니다.";
							echo json_encode(array("result"=>"0000", "msg"=>$msg, "gourl"=>$idpw_url));
							exit;
						}
					}
				}
				
			} else if($submode == "do") {
				$re_info = $row;
				send_mailsms($mailtype, $re_info, "", $submode);
				$msg = "입력하신 이메일로 인증메일을 전송하였습니다.\\n이메일주소 : ".$row['email'];
				alert($msg, $login_url);

			} else {

				if($subsearch == "pwsrh") {
					
					if($t_rtype == "email" || $t_rtype == "sms") {

						if($cert_num == $row['cert_number']) {
							echo json_encode(array("result"=>"0000", "setid"=>$row['id']));
							exit;
						} else {

							$sql = "
								update wiz_member
								   set cert_number = ''
								 where id = '".$uid."'
							";
							query($sql);

							echo json_encode(array("result"=>"9999", "msg"=>"인증번호가 일치하지 않습니다."));
							exit;
						}

					}

				}
				
			}
		}

	} else {

		echo json_encode(array("result"=>"9999", "msg"=>"회원정보가 일치하지 않습니다."));
		exit;

	}

} else {

	if(!strcmp($mem_info['method'], "A")) {
		$msg_id = "이름과 이메일을 입력하시면 경고창으로 아이디를 알려드립니다.<br>아이디의 2글자만 보여지며 나머지는 *로 치환됩니다.";
		$msg_pw = "아이디와 이름과 이메일을 입력하시면 경고창으로 비밀번호를 알려드립니다.<br>비밀번호의 2글자만 보여지며 나머지는 *로 치환됩니다.";
	} else {
		$msg_id = "이름과 이메일을 입력하시면 가입시 작성하신 이메일로<br>아이디를 보내 드립니다.";
		$msg_pw = "아이디와 이름과 이메일을 입력하시면 가입시 작성하신 이메일로<br>비밀번호를 보내 드립니다.";
	}

	if(!strcmp($stype ?? "", "id") === 0) { $pw_hidden_start = "<!--"; $pw_hidden_end = "-->"; }
	if(!strcmp($stype ?? "", "pw") === 0) { $id_hidden_start = "<!--"; $id_hidden_end = "-->"; }
	if(!strcmp($stype ?? "", "do") === 0) { $do_hidden_start = "<!--"; $do_hidden_end = "-->"; }

	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/idpw.php";

}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;

?>



