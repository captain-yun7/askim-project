<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$type  = $_POST['type'];
$id    = $_POST['id'];
$name  = $_POST['name'];
$email = $_POST['email'];

if($type == 'E' || $type == 'S') {

	$sql = "
		select id
			 , passwd
			 , name
			 , email
			 , hphone 
		  from wiz_member_dormancy 
		 where name = '$name' 
		   and email = '$email' 
	";
	$result = query($sql);
	if($row = sql_fetch_arr($result)) {

		InactiveMemberChangeTbl($id, 'm');

		$row['passwd'] = get_rand_str(10);
		$sql = "
			update wiz_member 
			   set old_user='N' 
			     , passwd = '".md5($row['passwd'])."' 
			 where id = '".$id."'
		";
		query($sql);

		$re_info = $row;
		send_mailsms('mem_dormancy_email', $re_info, "", $type);
		
		$email  = get_masking_email($row['email']);
		$hphone = get_masking_phone('P',$row['hphone']);

		if($type == 'E') {
			$msg = "회원가입시 등록된 이메일로 인증메일을 전송하였습니다.\n이메일주소 : ".$email;
		} else if($type == 'S') {
			$msg = "회원가입시 등록된 휴대전화로 인증번호를 전송하였습니다.\n휴대폰번호 : ".$hphone;
		}

		echo json_encode(json_result("100", $msg));
		exit;

	}

	
}
?>