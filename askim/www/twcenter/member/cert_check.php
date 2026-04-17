<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$gubun        = $_POST['gubun'];
$uid          = $_POST['uid'];
$uname        = $_POST['uname'];
$email        = $_POST['email'];
$hphone       = $_POST['hphone'];
$new_passwd   = $_POST['new_passwd'];
$new_passwd2  = $_POST['new_passwd2'];

if($gubun){

	if($gubun == "N") {

		if($new_passwd != $new_passwd2) {
			echo json_encode(array("result"=>"9999", "msg"=>"비밀번호가 일치하지 않습니다."));
			exit;
		} else {

			$sql = "
				update wiz_member
				   set passwd = '".$new_passwd2."'
				 where id = '".$uid."'
			";
			query($sql);

			/*
			작업자		: 이상민
			작업일시	: 2019-10-10
			작업내용	: mem_info에서 모바일 여부를 판단하여 $login_url 변수에 알맞은 주소를 넣고 있는데, 강제로 웹 주소를 넣고있기에 주석처리함.
			*/
		//	$login_url = "/".$mem_info['login_url'];
			echo json_encode(array("result"=>"0000", "msg"=>"비밀번호가 변경되었습니다.", "gourl"=>$login_url));
			exit;
		}

	} else {

		if($gubun == "E") {
			$q_sql = " and email = '$email' ";
		} else if($gubun == "S") {
			$q_sql = " and hphone = '$hphone' ";
		}

		$sql = "
			select id
				 , passwd
				 , name
				 , email
				 , hphone
				 , wdate
			  from wiz_member 
			 where id = '".$uid."'
			   $q_sql
		";
		$result = query($sql);
		if($row = sql_fetch_arr($result)) {

			if($gubun == "E") {

				$re_info['email']    = $email;
				$re_info['cert_chk'] = str_pad(mt_rand(0,999999),6,'0');
				send_mailsms("cert_chk", $re_info, "", $gubun);

				$sql = "
					update wiz_member
					   set cert_number = '".$re_info['cert_chk']."'
					 where id = '".$uid."'
				";
				query($sql);
					
				$msg = "인증번호를 이메일로 보내드렸습니다.\r\n이메일주소 : ".$row['email'];
				echo json_encode(array("result"=>"0000", "msg"=>$msg, "setid"=>$row['id'], "certchk"=>'Y'));
				exit;


			} else if($gubun == "S" || $gubun=="R") {

				if($site_info['sms_use'] == 'Y') {

					$cert_chk = str_pad(mt_rand(0,999999),6,'0');
					$se_num  = $site_info['site_tel'];
					$re_num  = $row['hphone'];

					$sql = "
						update wiz_member
						   set cert_number = '".$cert_chk."'
						 where id = '".$uid."'
					";
					query($sql);

					$message = "고객님의 인증번호는 ".$cert_chk." 입니다.";
					send_sms($se_num, $re_num, str_conv($message, 'euc-kr'));

					$msg = "인증번호를 휴대폰으로 발송해드렸습니다.";
					if($gubun=="R"){
						echo json_encode(array("result"=>"0000", "msg"=>$msg, "setid"=>$row['id'], "certchk"=>'N'));
					}else{
						echo json_encode(array("result"=>"0000", "msg"=>$msg, "setid"=>$row['id'], "certchk"=>'Y'));
					}
					exit;

				}

			}

		} else {

			echo json_encode(array("result"=>"9999", "msg"=>"회원정보가 일치하지 않습니다."));
			exit;

		}

	}

}

?>



