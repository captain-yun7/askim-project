<?php
header("Content-Type:application/json");
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$re_name   = $_POST['re_name'];
$re_post   = $_POST['re_post'];
$re_addr   = $_POST['re_addr'];
$re_addr2  = $_POST['re_addr2'];
$re_tphone = $_POST['re_tphone'];
$re_hphone = $_POST['re_hphone'];
$basicde   = $_POST['basicde'];
$bucode    = $_POST['bucode'];
$type      = $_POST['type'];
$idx       = $_POST['idx'];
$uType     = $_POST['uType'];
$sid       = $_POST['sid'];

$usid = $wiz_session['id'];

if($re_post) $post_sql = " AND re_post='$re_post' and re_addr = '$re_addr' and re_addr2 = '$re_addr2' ";

$de_sql = "SELECT COUNT(re_post) AS cnt FROM wiz_delivery_set WHERE de_id != '' $post_sql AND de_id='{$usid}' ";
if($idx) {
	$de_sql .= " AND idx!='$idx'";
}
$rs = sql_fetch( $de_sql );
if($rs['cnt'] && $type != 'd') {

	echo json_encode(json_result("00", "등록된 배송지입니다.".$de_sql));
	exit;

} else {

	if($type == 'i') {

		if($basicde == "Y") {
			$sql = "UPDATE wiz_delivery_set SET basicdelivery='' WHERE de_id='{$usid}' ";
			query($sql);
		}

		$wdate = time();

		$sql_com = "";
		$sql_com .= " de_id           = '{$usid}'       ";
		$sql_com .= " , re_name       = '{$re_name}'                 ";
		$sql_com .= " , re_post       = '{$re_post}'                 ";
		$sql_com .= " , re_addr       = '{$re_addr}'                 ";
		$sql_com .= " , re_addr2      = '{$re_addr2}'                ";
		$sql_com .= " , re_tphone     = '{$re_tphone}'               ";
		$sql_com .= " , re_hphone     = '{$re_hphone}'               ";
		$sql_com .= " , basicdelivery = '{$basicde}'                 ";
		$sql_com .= " , bucode        = '{$bucode}'                  ";
		$sql_com .= " , wdate         = '{$wdate}'                   ";
		
		$sql = "INSERT INTO wiz_delivery_set SET {$sql_com} ";
		query($sql);

	} else if($type == 'u') {

		if($basicde == "Y" && $idx) {
			$sql = "UPDATE wiz_delivery_set SET basicdelivery='' WHERE de_id='{$usid}' ";
			query($sql);
		}

		if($bucode) $bucode_sql = ", bucode = '{$bucode}' ";
		$sql_com = "";
		$sql_com .= " re_name         = '{$re_name}'                 ";
		$sql_com .= " , re_post       = '{$re_post}'                 ";
		$sql_com .= " , re_addr       = '{$re_addr}'                 ";
		$sql_com .= " , re_addr2      = '{$re_addr2}'                ";
		$sql_com .= " , re_tphone     = '{$re_tphone}'               ";
		$sql_com .= " , re_hphone     = '{$re_hphone}'               ";
		$sql_com .= " , basicdelivery = '{$basicde}'                 ";
		$sql_com .= " $bucode_sql                                    ";
		
		$sql = "UPDATE wiz_delivery_set SET {$sql_com} WHERE idx='{$idx}' ";
		query($sql);

		echo json_encode(json_result("00", "배송지가 수정되었습니다."));
		exit;

	} else if($type == 'd') {

		if($idx) {

			//-- 삭제하려는 배송지가 기본배송인지...
			$_data = sql_fetch( "SELECT basicdelivery FROM wiz_delivery_set WHERE idx='{$idx}'" );
	
			$sql = "DELETE FROM wiz_delivery_set WHERE idx='{$idx}' ";
			query($sql);

			//-- 배송지가 한곳만 남은경우 남은한곳을 기본배송지로 업데이트
			$de_cnt = sql_fetch( "SELECT COUNT(idx) AS cnt FROM wiz_delivery_set WHERE de_id='{$usid}' " );
			if($de_cnt['cnt'] == 1) {
				$sql = "UPDATE wiz_delivery_set SET basicdelivery='Y' WHERE de_id='{$usid}' ";
				query($sql);
			} else if($de_cnt['cnt'] > 1 && $_data['basicdelivery'] == "Y") {

				$sql = "
					UPDATE wiz_delivery_set
					SET basicdelivery='Y'
					WHERE idx in
					(
					  SELECT idx from
					  (
						SELECT idx
						FROM wiz_delivery_set
						WHERE de_id='{$usid}'
						ORDER BY wdate DESC
						LIMIT 1
					  ) AS x
					)
				";
				query($sql);

			}

			echo json_encode(json_result("00", "배송지가 삭제되었습니다."));
			exit;

		}

	}

	echo json_encode(json_result("00", "배송지가 등록되었습니다."));
	exit;

}
?>