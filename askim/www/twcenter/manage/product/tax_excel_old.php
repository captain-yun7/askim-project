<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?php

	if($tax_type == "T") $tax_title = "세금계산서";
	else if($tax_type == "C") $tax_title = "현금영수증";

	$filename = $tax_title."[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	if($tax_type == "T") {

		$excel_title = "발급일자	";
		$excel_title .= "상호	";
		$excel_title .= "사업자등록번호	";
		$excel_title .= "대표자	";
		$excel_title .= "품목명	";
		$excel_title .= "공급가액	";
		$excel_title .= "세액	";
		$excel_title .= "소계	";
		$excel_title .= "처리상태	";
		$excel_title .= "상품정보";

	} else if($tax_type == "C") {

		$excel_title = "발급일자	";
		$excel_title .= "발급사유	";
		$excel_title .= "신청정보	";
		$excel_title .= "신청정보 내용	";
		$excel_title .= "신청자명	";
		$excel_title .= "처리상태	";

	}

	echo $excel_title."\n";

  if($tax_type != "") $tax_sql = " and tax_type='$tax_type' ";

  if($prev_year){
     $prev_period = $prev_year."-".$prev_month."-".$prev_day;
     $next_period = $next_year."-".$next_month."-".$next_day." 23:59:59";
     $period_sql = " and tax_date >= '$prev_period' and tax_date <= '$next_period'";
  }
  if($status == "") $status_sql = "and tax_pub != ''";
  else $status_sql = "and tax_pub = '$status'";

  if($searchopt && $searchkey) $searchopt_sql = " and $searchopt like '%$searchkey%'";

	$sql = "select * from wiz_tax where tax_date != '' $tax_sql $status_sql $period_sql $searchopt_sql order by tax_date desc";
	$result = query($sql) or error("sql error");

	while($row = sql_fetch_arr($result)){

		$prd_name = "";

		$prd_info = explode("^^", $row['prd_info']);
		$no = 0;
		if($prd_info) {
			for($ii = 0; $ii < count($prd_info); $ii++) {

				if(!empty($prd_info[$ii])) {
					$tmp_prd = explode("^", $prd_info[$ii]);
					if($ii < 1) $prd_name = $tmp_prd[0];
					$no++;
				}
			}
		} else {
			$prd_name = "";
		}
		if($no > 1) {
			$prd_name .= " 외 ".($no-1)."건";
		}

		if($tax_type == "T") {

			$excel_data = "";
			$excel_data .= $row['tax_date']."	";
			$excel_data .= $row['com_name']."	";
			$excel_data .= $row['com_num']."	";
			$excel_data .= $row['com_owner']."	";
			$excel_data .= $prd_name."	";
			$excel_data .= $row['supp_price']."	";
			$excel_data .= $row['tax_price']."	";
			$excel_data .= $row['supp_price'] + $row['tax_price']."	";
			$excel_data .= $row['tax_pub']."	";
			$excel_data .= $row['prd_info'];

		} else if($tax_type == "C") {

			$cash_type = get_cash_type_name($row['cash_type']);
			$cash_type2 = get_cash_type2_name($row['cash_type2']);

			$excel_data = "";
			$excel_data .= $row['tax_date']."	";
			$excel_data .= $cash_type."	";
			$excel_data .= $cash_type2."	";
			$excel_data .= $row['cash_info']."	";
			$excel_data .= $row['cash_name']."	";
			$excel_data .= $row['tax_pub']."	";

		}

		echo $excel_data."\n";
	}
?>