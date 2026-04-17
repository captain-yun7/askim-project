<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$id     = $_POST['id'];

if(mobile_check() == true || strpos($_SERVER['PHP_SELF'], "/m/") !== false) {
	$skin = "memberBasic_m";
} else {
	$skin = "memberBasic";
}

$m_sql = "select * from wiz_member where id = '".$id."'  ";
$m_data = sql_fetch($m_sql);

if($m_data['dchange_type'] == 'Y' && $m_data['dchange_date'] !== '0000-00-00 00:00:00') {

	$m_sql2 = "select count(id) as cnt from wiz_member_dormancy where id = '".$id."' and passwd = '".$_POST['passwd']."' ";
	$m_data2 = sql_fetch($m_sql2);

	$u_cnt  = $m_data2['cnt'];
	if($u_cnt == 1) {

		$uid = base64_encode(urlencode($id));
		if($sleep_use['A'] == true || $sleep_use['E'] == true || $sleep_use['S'] == true) {
			$gurl = "/twcenter/member/skin/".$skin."/dormant_info.php";
			echo json_encode(array("result"=>"9999", "url"=>$gurl, "seq"=>$uid));
			exit;
		}

	} else {
		echo json_encode(json_result("9998", "회원정보가 일치하지 않습니다."));
		exit;
	}

} else {
	echo json_encode(array("result"=>"100"));
	exit;
}


?>