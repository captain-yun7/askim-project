<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

$bkmatchres     = $_POST['bkmatchres'];
$bkmatchdate    = $_POST['bkmatchdate'];
$bkorderid      = $_POST['bkorderid'];
$bkmemo         = $_POST['bkmemo'];
$bkidx          = $_POST['bkidx'];

$bkmatchdate    = $bkmatchdate." ".date('H:i:s');

$result = false;
if($bkidx) {

	$sql_com = "";
	$sql_com .= " bkmatchres      = '$bkmatchres'                      ";
	$sql_com .= " , bkmatchdate   = '$bkmatchdate'                     ";
	$sql_com .= " , orderid       = '$bkorderid'                       ";
	
	$sql = "update bankda_io_history set {$sql_com} WHERE idx = '".$bkidx."' ";
	query($sql);

	$sql_com = "";
	$sql_com .= " status            = 'OY'                             ";
	$sql_com .= " , bk_match_result = '$bkmatchres'                    ";
	$sql_com .= " , bk_match_date   = '$bkmatchdate'                   ";
	$sql_com .= " , bk_memo         = '$bkmemo'                        ";
	
	$sql = "update wiz_order set {$sql_com} WHERE orderid = '".$bkorderid."' ";
	query($sql);

	$result = true;	
}

if($result) {
	echo json_encode(json_result("0000", "수동매칭 처리되었습니다."));
	exit;
} else {
	echo json_encode(json_result("9999", "오류발생으로 업데이트가 안될수있습니다.\n정상처리여부를 확인하세요."));
	exit;
}
?>
