<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/bankda_info.php";

$directAccess  = $_POST['directAccess'];
$service_type  = $_POST['service_type'];
$partner_id    = $_POST['partner_id'];
$partner_name  = $_POST['partner_name'];
$user_id       = $_POST['user_id'];
$user_pw       = $_POST['user_pw'];
$user_pw_new   = $_POST['user_pw_new'];
$user_name     = $_POST['user_name'];
$user_tel      = $_POST['user_tel'];
$user_tel2     = $_POST['user_tel2'];
$user_email    = $_POST['user_email'];
$char_set      = $_POST['char_set'];
$mode          = $_POST['mode'];

/*if(!empty($bankda_info['bankda_id']) && !empty($bankda_info['bankda_pw'])) {
	echo json_encode(json_result("9999", "뱅크다 이용아이디 및 비밀번호가 존재합니다.\n분실의경우 관리자에게 문의하세요."));
	exit;

} else {*/

	if($mode == "insert") {

		/* 뱅크다 최초가입시 정보 */
		$sql_com = "";
		$sql_com .= " bankda_id                  = '".$user_id."'                   ";
		$sql_com .= " , bankda_pw                = '".$user_pw."'                   ";
		$sql_com .= " , bankda_name              = '".$user_name."'                 ";
		$sql_com .= " , bankda_phone             = '".$user_tel2."'                 ";
		$sql_com .= " , bankda_email             = '".$user_email."'                ";
		$sql_com .= " , bankda_wdate             = now()                            ";

		$sql = "insert into bankda_member set {$sql_com} ";
		query($sql);

		bankda_conn('I', $user_id, $user_pw, $user_name, $user_tel2, $user_email);
		
		echo json_encode(json_result("0000", "서비스신청을 뱅크다에 전송했습니다.\n아이디 및 비밀번호를 반드시 기억하세요."));
		exit;

	} else if($mode == "modify") {

		if(!empty($user_pw_new)) $user_pw = $user_pw_new;
		else                     $user_pw = $bankda_info['bankda_pw'];

		$sql_com = "";
		$sql_com .= " bankda_name                = '".$user_name."'                 ";
		$sql_com .= " , bankda_pw                = '".$user_pw."'                   ";
		$sql_com .= " , bankda_phone             = '".$user_tel2."'                 ";
		$sql_com .= " , bankda_email             = '".$user_email."'                ";
		$sql_com .= " , bankda_mdate             = now()                            ";

		$sql = "
			update bankda_member 
			   set {$sql_com} 
		";
		query($sql);

		bankda_conn('M', $user_id, $user_pw, $user_name, $user_tel2, $user_email);

		echo json_encode(json_result("0000", "회원정보수정내용을 뱅크다에 전송했습니다.\n결과값을 확인하세요."));
		exit;

	} else if($mode == "excute") {

		$sql = "delete from bankda_member";
		query($sql);

		bankda_conn('D', $user_id);
	
		echo json_encode(json_result("0000", "해지요청을 뱅크다에 전송했습니다.\n결과값을 확인하세요."));
		exit;

	}

//}
?>