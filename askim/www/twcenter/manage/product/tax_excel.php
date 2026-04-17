<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?  include_once "../../inc/site_info.php";?>
<? include_once "../../inc/oper_info.php"; ?>
<?php

	if($tax_type == "T") $tax_title = "세금계산서";
	else if($tax_type == "C") $tax_title = "현금영수증";

	$filename = $tax_title."[".date('Ymd')."].xls";
	$filename = iconv("utf-8","euc-kr",$filename);

	header( "Content-type: application/vnd.ms-excel;charset=euc-kr" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

?>
<style>
.TextFormat {mso-number-format:"0_ "; text-align:"center"; font-size:12px;}
.TextFormat2 { font-size:12px; font-family:NanumGothic, 나눔고딕, NG, Tahoma, Geneva, sans-serif; text-align:"center"; }
.TextFormat3 {mso-number-format:"\@"; text-align:"center"; font-size:12px;}
</style>
<table border='1'>
	<tr bgcolor="#cccccc">
		<td class="TextFormat2">발급</td>
		<td class="TextFormat2">TID</td>
		<td class="TextFormat2">상점ID</td>
		<td class="TextFormat2">주문번호</td>
		<td class="TextFormat2">판매자 사업번호</td>
		<td class="TextFormat2">가맹점이름</td>
		<td class="TextFormat2">가맹점대표자명</td>
		<td class="TextFormat2">가명점연락처</td>
		<td class="TextFormat2">구매자주민등록번호(사업자번호)</td>
		<td class="TextFormat2">원거래승인번호</td>
		<td class="TextFormat2">원거래승인일자</td>
		<td class="TextFormat2">총거래금액</td>
		<td class="TextFormat2">실거래금액</td>
		<td class="TextFormat2">부가세</td>
		<td class="TextFormat2">봉사료</td>
		<td class="TextFormat2">면세금액</td>
		<td class="TextFormat2">거래자구분(1(소득공제), 2(지출증빙) 중 택일)</td>
	</tr>
	<?
	$array_selorder = explode('|',$selorder ?? '');
	if($array_selorder && count($array_selorder)-1 > 0){

		$tmp_selorder = "";
		foreach($array_selorder as $key => $value){
			if(!empty($value)) $tmp_selorder .= " or orderid = '{$value}'";
		}
		$tmp_selorder = substr($tmp_selorder,3);
		$selorder_sql = " and ({$tmp_selorder})";
	} else {
		$selorder_sql = " and orderid != '' ";
	}

	if($tax_type != "") $tax_sql = " and tax_type='$tax_type' ";

	$prev_period = $srh_prev;
	$next_period = $srh_next." 23:59:59";

	if(($status == "ALL" || empty($status)) && !empty($srh_prev)){
		$period_sql  = " and tax_date >= '$prev_period' and tax_date <= '$next_period'";
	} else if(!empty($status)) {
		if($status == 'ALL'){
			$period_sql  = "";
		} else {
			$period_sql  = " and tax_date >= '$prev_period' and tax_date <= '$next_period'";
		}
	} else if($status == "ALL" || empty($status)){
		$period_sql  = "";
	} else {
		$period_sql  = " and tax_date >= '$prev_period' and tax_date <= '$next_period'";
	}

	if($status == "" || $status == 'ALL') $status_sql = "and tax_pub != ''";
	else $status_sql = "and tax_pub = '$status'";

	if($searchopt && $searchkey) $searchopt_sql = " and $searchopt like '%$searchkey%'";

	$sql = "select * from wiz_tax where tax_date != '' $tax_sql $status_sql $period_sql $searchopt_sql $selorder_sql order by tax_date desc";
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

			$supp_tax = $row['supp_price'] + $row['tax_price'];

	?>
	<tr>
		<td class="TextFormat2">발급</td>
		<td class="TextFormat2">TID</td>
		<td class="TextFormat2"><?=$oper_info['pay_id']?></td>
		<td class="TextFormat3"><?=$row['orderid']?></td>
		<td class="TextFormat2"><?=$site_info['com_num']?></td>
		<td class="TextFormat2"><?=$site_info['com_name']?></td>
		<td class="TextFormat2"><?=$site_info['com_owner']?></td>
		<td class="TextFormat2"><?=$site_info['com_tel']?></td>
		<td class="TextFormat2"><?=$row['cash_info']?></td>
		<td class="TextFormat2"></td>
		<td class="TextFormat2"></td>
		<td class="TextFormat2"><?=$supp_tax?></td>
		<td class="TextFormat2"><?=$row['supp_price']?></td>
		<td class="TextFormat2"><?=$row['tax_price']?></td>
		<!-- <td class="TextFormat2"><?=$row['tax_pub']?></td>
		<td class="TextFormat2"><?=$row['prd_info']?></td> -->
		<td class="TextFormat2">0</td>
		<td class="TextFormat2">0</td>
		<td class="TextFormat2"></td>
	</tr>

	<?
		} else if($tax_type == "C") {

			$cash_type2 = get_cash_type2_name($row['cash_type2']);

			if($row['cash_type']=="P"){
				$cash_type=1; //소득공제
			}else if($row['cash_type']=="C"){
				$cash_type=2; //지출증빙
			}

			$supp_tax = $row['supp_price'] + $row['tax_price'];
	?>

	<tr>
		<td class="TextFormat2">발급</td>
		<td class="TextFormat2"></td>
		<td class="TextFormat2"><?=$oper_info['pay_id']?></td>
		<td class="TextFormat3"><?=$row['orderid']?></td>
		<td class="TextFormat2"><?=$site_info['com_num']?></td>
		<td class="TextFormat2"><?=$site_info['com_name']?></td>
		<td class="TextFormat2"><?=$site_info['com_owner']?></td>
		<td class="TextFormat2"><?=$site_info['com_tel']?></td>
		<td class="TextFormat2"><?=$row['cash_info']?></td>
		<td class="TextFormat2"></td>
		<td class="TextFormat2"></td>
		<td class="TextFormat2"><?=$supp_tax?></td>
		<td class="TextFormat2"><?=$row['supp_price']?></td>
		<td class="TextFormat2"><?=$row['tax_price']?></td>
		<td class="TextFormat2">0</td>
		<td class="TextFormat2">0</td>
		<td class="TextFormat2"><?=$cash_type?></td>

	</tr>
	<?
		}
	}
	?>