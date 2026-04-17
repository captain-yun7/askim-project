<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

$filename = "member_info_".date('Ymd').".csv";

header('Expires: 0');
header('Content-Encoding: utf-8');
header('Content-type: text/csv; charset=utf-8');  //추가
header('Cache-Control: private, no-transform, no-store, must-revalidate');
header('Content-Disposition: attachment;filename="'.$filename.'"');
echo "\xEF\xBB\xBF"; //추가



$excel_data = "";

$excel_data .= implode(",", array("이름", "이메일주소", "주소"));

if($slevel != "") $level_sql .= " and level = '$slevel'"; //등급
if($searchopt != "") $search_sql .= " and $searchopt like '%$searchkey%'"; //조건검색
if($sdate && $edate) $memperi_sql = "and (wdate BETWEEN '$sdate 00:00:00' AND '$edate 23:59:59') "; //회원가입기간
if($lsdate && $ledate) $visit_sql = "and visit_time BETWEEN '$lsdate 00:00:00' AND '$ledate 23:59:59' "; //최종방문기간
if ($last_date !== null && trim($last_date)) $last_sql = "and visit_time <= date_sub(now(), interval ".$last_date." day) "; //최종방문일
if($s_sns) {
	for($ii=0; $ii<sizeof($s_sns); $ii++) {
		if($s_sns[$ii]) {
			$sns_sql .= ($sns_sql) ? " or " : " and (";
			$sns_sql .= "sns_login='".$s_sns[$ii]."'";
		}
	}
	if($sns_sql) $sns_sql .= ")";
	$search_sql .= $sns_sql;
}

$array_seluser = explode('|', $seluser ?? '');
if(count($array_seluser)-1 > 0){

	$tmp_seluser = "";
	foreach($array_seluser as $key => $value){
		if(!empty($value)) $tmp_seluser .= " or id = '{$value}'";
	}
	$tmp_seluser = substr($tmp_seluser,3);
	$seuser_sql = " ({$tmp_seluser})";
} else {
	$seuser_sql = "id != '' and dchange_type != 'Y' ";
}

$sql = "select * from wiz_member where $seuser_sql $level_sql $search_sql $memperi_sql $visit_sql $last_sql order by idx desc";
$result = query($sql) or error("sql error");
while($row = sql_fetch_arr($result)){
	$addr = "\"(".$row['post'].") ".$row['address1']." ".$row['address2']."\"";;

	$excel_data .= "\r\n";
	$excel_data .= $row['name'].",".$row['email'].",".$addr;
}

echo $excel_data;
?>