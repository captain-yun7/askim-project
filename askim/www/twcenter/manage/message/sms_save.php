<?php
include_once "../../common.php";
include_once "../../inc/site_info.php";
include_once "../../inc/twcenter_check.php";

if($message == "") error("보내는 내용이 없습니다.");
if($se_num == "") error("보내는이 전화번호가 없습니다.");

if ($_POST['mode'] == "SearchSend") {

	$sql_query = "";
	if($sdate     != "")	$sql_query .= " and wdate > '$sdate'";
	if($edate     != "")	$sql_query .= " and wdate <= '$edate 23:59:59'";
	if($searchkey != "")	$sql_query .= " and $searchopt like '%$searchkey%'";
	if($level     != "")	$sql_query .= " and level = '$level'";
	if($birthday == "Y")	$sql_query .= " and birthday like '%$today'";
	if($memorial == "Y")	$sql_query .= " and memorial like '%$today'";
	if($age       != "")	$sql_query .= " and resno > '$age_syear' and resno < '$age_eyear'";
	if($address   != "")	$sql_query .= " and address like '%$address%'";
	if($job       != "")	$sql_query .= " and job = '$job'";
	if($marriage  != "")	$sql_query .= " and marriage = '$marriage'";
	if($resms    == "N")	$sql_query .= " and resms != 'N'";

	$total = sql_fetch("SELECT COUNT(*) AS total FROM wiz_member WHERE id != '' {$sql_query} ");
	$total_cnt = $total['total'];

	if($total_cnt == 0) {
		echo json_encode(json_result("01", "발송할 회원이 없습니다."));
		exit;
	}

	$strTelList = '';
	$sql = "SELECT hphone FROM wiz_member WHERE id != '' {$sql_query}";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){
		$strTelList .= $row['hphone'].";";
	}

	if(send_sms($se_num, $strTelList, $_POST['message'], '', 'SearchSend', 'SMS')) {
		echo json_encode(array("result"=>"00", "msg"=>"SMS가 발송되었습니다."));
	} else {
		echo json_encode(array("result"=>"01", "msg"=>"SMS 발송 중 오류가 발생하였습니다."));
	}

} else if ($_POST['mode'] == "DirectSend") {

	if(send_sms($se_num, $_POST['strTelList'], $_POST['message'], '', 'DirectSend', 'SMS')) {
		echo json_encode(array("result"=>"00", "msg"=>"SMS가 발송되었습니다."));
	} else {
		echo json_encode(array("result"=>"01", "msg"=>"SMS 발송 중 오류가 발생하였습니다."));
	}

}
?>