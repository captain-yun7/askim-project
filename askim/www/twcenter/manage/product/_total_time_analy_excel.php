<?php
include "../../common.php";
include "../../inc/twcenter_check.php";

$PREV_YEAR  = "2014";
$TO_YEAR    = date("Y");

if(empty($t_date))  $t_date = "order_date";
if(empty($t_year))  $t_year = date("Y");
if(empty($t_month)) $t_month = date("m");
if(empty($t_date2)) $t_date2 = date("d");

$s_time = ":00:00";
$e_time = ":59:59";
$last_month_day = date("t",strtotime($t_year."-".$t_month."-01"));

$t_year_sql = " and $t_date like '".$t_year."-".$t_month."-".$t_date2."%' ";

if(!empty($t_pay_method)){ 
	$t_paymethod_sql = " and pay_method='$t_pay_method' ";
	$params[] = "t_pay_method=".$t_pay_method;
} else {
	$t_paymethod_sql = "";
}

$filename = "total_time_analy_".date('Ymd').".xls";
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=$filename" );
header( "Content-Description: PHP4 Generated Data" );
?>

<table width="100%" border="0" cellpadding="0" cellspacing="1" style="font-size:12px; background-color: #e1e1e1;">
	<tr>
		<td rowspan='2' colspan='2' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">구분</td>
		<td colspan='2' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">주문</td>
		<td rowspan='2' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">미입금</td>
		<td colspan='5' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">결제</td>
		<td colspan='2' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">주문취소</td>
		<td colspan='2' style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">반품/교환</td>
	</tr>
	<tr>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">건수</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">주문금액</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">배송비</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">할인가</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">쿠폰</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">적립금</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">실결제금액</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">건수</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">금액</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">건수</td>
		<td style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">금액</td>
	</tr>
	<?PHP 
	for($i=0; $i<=23; $i++){

		$time_play            = (strlen($i)==1) ? "0".$i : $i;
//				$t_year_month_day_sql = " and $t_date like '".$t_year."-".$t_month."-".$t_date2." ".$time_play."%' ";
				$g_start_time  = $t_year."-".$t_month."-".$t_date2." ".$time_play.$s_time;
				$g_end_time    = $t_year."-".$t_month."-".$t_date2." ".$time_play.$e_time;

				$t_year_month_day_sql = " and $t_date between '".$g_start_time."' and '".$g_end_time."' ";

				//error_reporting(E_ALL); 
				## 주문접수
				$conn_pc_sql = "

					select 
					
						count(orderid) as total_order_OR,
						sum(total_price) as total_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_total_price_OR = $conn_pc_row['total_price_OR'];
				$pc_total_order_OR = $conn_pc_row['total_order_OR'];
				if(empty($pc_total_price_OR)) $pc_total_price_OR = 0;
				if(empty($pc_total_order_OR)) $pc_total_order_OR = 0;

				$conn_mo_sql = "

					select 
					
						count(orderid) as total_order_OR,
						sum(total_price) as total_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_total_price_OR = $conn_mo_row['total_price_OR'];
				$mo_total_order_OR = $conn_mo_row['total_order_OR'];
				if(empty($mo_total_price_OR)) $mo_total_price_OR = 0;
				if(empty($mo_total_order_OR)) $mo_total_order_OR = 0;

				## 미입금
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_no_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and status='OR' and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_total_no_price_OR = $conn_pc_row['total_no_price_OR'];
				if(empty($pc_total_no_price_OR)) $pc_total_no_price_OR = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_no_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and status='OR' and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_total_no_price_OR = $conn_mo_row['total_no_price_OR'];
				if(empty($mo_total_no_price_OR)) $mo_total_no_price_OR = 0;

				## 배송비
				$conn_pc_sql = "

					select 
					
						sum(deliver_price) as deliver_price
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_deliver_price = $conn_pc_row['deliver_price'];
				if(empty($pc_deliver_price)) $pc_deliver_price = 0;

				$conn_mo_sql = "

					select 
					
						sum(deliver_price) as deliver_price
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_deliver_price = $conn_mo_row['deliver_price'];
				if(empty($mo_deliver_price)) $mo_deliver_price = 0;

				## 쿠폰사용
				$conn_pc_sql = "

					select 
					
						sum(coupon_use) as coupon_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_coupon_use = $conn_pc_row['coupon_use'];
				if(empty($pc_coupon_use)) $pc_coupon_use = 0;

				$conn_mo_sql = "

					select 
					
						sum(coupon_use) as coupon_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_coupon_use = $conn_mo_row['coupon_use'];
				if(empty($mo_coupon_use)) $mo_coupon_use = 0;

				## 적립금사용
				$conn_pc_sql = "

					select 
					
						sum(reserve_use) as reserve_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_reserve_use = $conn_pc_row['reserve_use'];
				if(empty($pc_reserve_use)) $pc_reserve_use = 0;

				$conn_mo_sql = "

					select 
					
						sum(reserve_use) as reserve_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_reserve_use = $conn_mo_row['reserve_use'];
				if(empty($mo_reserve_use)) $mo_reserve_use = 0;

				## 주문취소
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_price_OC,
						count(orderid) as total_order_OC
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and status='OC' and connect_type='PC'
						
				";

				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$total_pc_price_OC = $conn_pc_row['total_price_OC'];
				$total_pc_order_OC = $conn_pc_row['total_order_OC'];

				if(empty($total_pc_price_OC)) $total_pc_price_OC = 0;
				if(empty($total_pc_order_OC)) $total_pc_order_OC = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_mo_price_OC,
						count(orderid) as total_mo_order_OC
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and status='OC' and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$total_mo_price_OC = $conn_mo_row['total_mo_price_OC'];
				$total_mo_order_OC = $conn_mo_row['total_mo_order_OC'];

				if(empty($total_mo_price_OC)) $total_mo_price_OC = 0;
				if(empty($total_mo_order_OC)) $total_mo_order_OC = 0;

				## 환불/교환관련
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_price_EX,
						count(orderid) as total_order_EX
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='PC'
						
				";

				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$total_pc_price_EX = $conn_pc_row['total_price_EX'];
				$total_pc_order_EX = $conn_pc_row['total_order_EX'];

				if(empty($total_pc_price_EX)) $total_pc_price_EX = 0;
				if(empty($total_pc_order_EX)) $total_pc_order_EX = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_price_EX,
						count(orderid) as total_order_EX
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_day_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='M'
						
				";

				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$total_mo_price_EX = $conn_mo_row['total_price_EX'];
				$total_mo_order_EX = $conn_mo_row['total_order_EX'];

				if(empty($total_mo_price_EX)) $total_mo_price_EX = 0;
				if(empty($total_mo_order_EX)) $total_mo_order_EX = 0;

				//-----------------------------------------------------------

				$total_price_OR      = (int)($pc_total_price_OR + $mo_total_price_OR);
				$total_order_OR      = (int)($pc_total_order_OR + $mo_total_order_OR);

				$total_no_price_OR   = (int)($pc_total_no_price_OR + $mo_total_no_price_OR);

				$total_deliver_price = (int)($pc_deliver_price + $mo_deliver_price);

				$total_coupon_use    = (int)($pc_coupon_use + $mo_coupon_use);

				$total_reserve_use   = (int)($pc_reserve_use + $mo_reserve_use);

				$total_price_OC      = (int)($total_pc_price_OC + $total_mo_price_OC);
				$total_order_OC      = (int)($total_pc_order_OC + $total_mo_order_OC);

				$total_price_EX      = (int)($total_pc_price_EX + $total_mo_price_EX);
				$total_order_EX      = (int)($total_pc_order_EX + $total_mo_order_EX);

				$total_pc_price      = $pc_total_price_OR;
				$total_mo_price      = $mo_total_price_OR;
				$total_price         = $total_price_OR;

	?>
	<tr>
		<td rowspan="3" class="t_name_analy"><?=$time_play?>시</td>
		<td class="t_name_analy">PC</td>
		<td class="t_value_analy"><?=number_format($pc_total_order_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_total_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_total_no_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_deliver_price)?></td>
		<td class="t_value_analy">0</td>
		<td class="t_value_analy"><?=number_format($pc_coupon_use)?></td>
		<td class="t_value_analy"><?=number_format($pc_reserve_use)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_order_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_order_EX)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price_EX)?></td>
	</tr>
	<tr>
		<td class="t_name_analy">Mobile</td>
		<td class="t_value_analy"><?=number_format($mo_total_order_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_total_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_total_no_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_deliver_price)?></td>
		<td class="t_value_analy">0</td>
		<td class="t_value_analy"><?=number_format($mo_coupon_use)?></td>
		<td class="t_value_analy"><?=number_format($mo_reserve_use)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_order_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_order_EX)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price_EX)?></td>
	</tr>
	<tr>
		<td class="t_name_analy"><strong>합계</strong></td>
		<td class="t_value_analy_total"><?=number_format($total_order_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_no_price_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_deliver_price)?></td>
		<td class="t_value_analy_total">0</td>
		<td class="t_value_analy_total"><?=number_format($total_coupon_use)?></td>
		<td class="t_value_analy_total"><?=number_format($total_reserve_use)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price)?></td>
		<td class="t_value_analy_total"><?=number_format($total_order_OC)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_OC)?></td>
		<td class="t_value_analy_total"><?=number_format($total_order_EX)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_EX)?></td>
	</tr>
	<?
	}
	## 주문접수
	$conn_pc_sql = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
			
	";
	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$pc_total_price_OR = $conn_pc_row['total_price_OR'];
	$pc_total_order_OR = $conn_pc_row['total_order_OR'];
	if(empty($pc_total_price_OR)) $pc_total_price_OR = 0;
	if(empty($pc_total_order_OR)) $pc_total_order_OR = 0;

	$conn_mo_sql = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$mo_total_price_OR = $conn_mo_row['total_price_OR'];
	$mo_total_order_OR = $conn_mo_row['total_order_OR'];
	if(empty($mo_total_price_OR)) $mo_total_price_OR = 0;
	if(empty($mo_total_order_OR)) $mo_total_order_OR = 0;

	## 미입금
	$conn_pc_sql = "

		select 
		
			sum(total_price) as total_no_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and status='OR' and connect_type='PC'
			
	";
	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$pc_total_no_price_OR = $conn_pc_row['total_no_price_OR'];
	if(empty($pc_total_no_price_OR)) $pc_total_no_price_OR = 0;

	$conn_mo_sql = "

		select 
		
			sum(total_price) as total_no_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and status='OR' and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$mo_total_no_price_OR = $conn_mo_row['total_no_price_OR'];
	if(empty($mo_total_no_price_OR)) $mo_total_no_price_OR = 0;

	## 배송비
	$conn_pc_sql = "

		select 
		
			sum(deliver_price) as deliver_price
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
			
	";
	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$pc_deliver_price = $conn_pc_row['deliver_price'];
	if(empty($pc_deliver_price)) $pc_deliver_price = 0;

	$conn_mo_sql = "

		select 
		
			sum(deliver_price) as deliver_price
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$mo_deliver_price = $conn_mo_row['deliver_price'];
	if(empty($mo_deliver_price)) $mo_deliver_price = 0;

	## 쿠폰사용
	$conn_pc_sql = "

		select 
		
			sum(coupon_use) as coupon_use
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
			
	";
	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$pc_coupon_use = $conn_pc_row['coupon_use'];
	if(empty($pc_coupon_use)) $pc_coupon_use = 0;

	$conn_mo_sql = "

		select 
		
			sum(coupon_use) as coupon_use
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$mo_coupon_use = $conn_mo_row['coupon_use'];
	if(empty($mo_coupon_use)) $mo_coupon_use = 0;

	## 적립금사용
	$conn_pc_sql = "

		select 
		
			sum(reserve_use) as reserve_use
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
			
	";
	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$pc_reserve_use = $conn_pc_row['reserve_use'];
	if(empty($pc_reserve_use)) $pc_reserve_use = 0;

	$conn_mo_sql = "

		select 
		
			sum(reserve_use) as reserve_use
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$mo_reserve_use = $conn_mo_row['reserve_use'];
	if(empty($mo_reserve_use)) $mo_reserve_use = 0;

	## 주문취소
	$conn_pc_sql = "

		select 
		
			sum(total_price) as total_price_OC,
			count(orderid) as total_order_OC
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and status='OC' and connect_type='PC'
			
	";

	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$total_pc_price_OC = $conn_pc_row['total_price_OC'];
	$total_pc_order_OC = $conn_pc_row['total_order_OC'];

	if(empty($total_pc_price_OC)) $total_pc_price_OC = 0;
	if(empty($total_pc_order_OC)) $total_pc_order_OC = 0;

	$conn_mo_sql = "

		select 
		
			sum(total_price) as total_mo_price_OC,
			count(orderid) as total_mo_order_OC
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and status='OC' and connect_type='M'
			
	";
	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$total_mo_price_OC = $conn_mo_row['total_mo_price_OC'];
	$total_mo_order_OC = $conn_mo_row['total_mo_order_OC'];

	if(empty($total_mo_price_OC)) $total_mo_price_OC = 0;
	if(empty($total_mo_order_OC)) $total_mo_order_OC = 0;

	## 환불/교환관련
	$conn_pc_sql = "

		select 
		
			sum(total_price) as total_price_EX,
			count(orderid) as total_order_EX
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='PC'
			
	";

	$conn_pc_result = query($conn_pc_sql);
	$conn_pc_row = sql_fetch_arr($conn_pc_result);

	$total_pc_price_EX = $conn_pc_row['total_price_EX'];
	$total_pc_order_EX = $conn_pc_row['total_order_EX'];

	if(empty($total_pc_price_EX)) $total_pc_price_EX = 0;
	if(empty($total_pc_order_EX)) $total_pc_order_EX = 0;

	$conn_mo_sql = "

		select 
		
			sum(total_price) as total_price_EX,
			count(orderid) as total_order_EX
			
		from 
		
			wiz_order 
			
		where 1
		
			$t_year_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='M'
			
	";

	$conn_mo_result = query($conn_mo_sql);
	$conn_mo_row = sql_fetch_arr($conn_mo_result);

	$total_mo_price_EX = $conn_mo_row['total_price_EX'];
	$total_mo_order_EX = $conn_mo_row['total_order_EX'];

	if(empty($total_mo_price_EX)) $total_mo_price_EX = 0;
	if(empty($total_mo_order_EX)) $total_mo_order_EX = 0;

	//-----------------------------------------------------------

	$total_price_OR      = (int)($pc_total_price_OR + $mo_total_price_OR);
	$total_order_OR      = (int)($pc_total_order_OR + $mo_total_order_OR);

	$total_no_price_OR   = (int)($pc_total_no_price_OR + $mo_total_no_price_OR);

	$total_deliver_price = (int)($pc_deliver_price + $mo_deliver_price);

	$total_coupon_use    = (int)($pc_coupon_use + $mo_coupon_use);

	$total_reserve_use   = (int)($pc_reserve_use + $mo_reserve_use);

	$total_price_OC      = (int)($total_pc_price_OC + $total_mo_price_OC);
	$total_order_OC      = (int)($total_pc_order_OC + $total_mo_order_OC);

	$total_price_EX      = (int)($total_pc_price_EX + $total_mo_price_EX);
	$total_order_EX      = (int)($total_pc_order_EX + $total_mo_order_EX);

	$total_pc_price      = $pc_total_price_OR;
	$total_mo_price      = $mo_total_price_OR;
	$total_price         = $total_price_OR;
	?>
	<tr>
		<td rowspan="3" style="font-size:12px; font-weight:600; color: #333; background: #f5f5f5 !important; line-height: 15px; height:33px; text-align:center;"">총계</td>
		<td class="t_name_analy">PC</td>
		<td class="t_value_analy"><?=number_format($pc_total_order_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_total_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_total_no_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($pc_deliver_price)?></td>
		<td class="t_value_analy">0</td>
		<td class="t_value_analy"><?=number_format($pc_coupon_use)?></td>
		<td class="t_value_analy"><?=number_format($pc_reserve_use)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_order_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_order_EX)?></td>
		<td class="t_value_analy"><?=number_format($total_pc_price_EX)?></td>
	</tr>
	<tr>
		<td class="t_name_analy">Mobile</td>
		<td class="t_value_analy"><?=number_format($mo_total_order_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_total_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_total_no_price_OR)?></td>
		<td class="t_value_analy"><?=number_format($mo_deliver_price)?></td>
		<td class="t_value_analy">0</td>
		<td class="t_value_analy"><?=number_format($mo_coupon_use)?></td>
		<td class="t_value_analy"><?=number_format($mo_reserve_use)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_order_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price_OC)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_order_EX)?></td>
		<td class="t_value_analy"><?=number_format($total_mo_price_EX)?></td>
	</tr>
	<tr>
		<td class="t_name_analy"><strong>합계</strong></td>
		<td class="t_value_analy_total"><?=number_format($total_order_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_no_price_OR)?></td>
		<td class="t_value_analy_total"><?=number_format($total_deliver_price)?></td>
		<td class="t_value_analy_total">0</td>
		<td class="t_value_analy_total"><?=number_format($total_coupon_use)?></td>
		<td class="t_value_analy_total"><?=number_format($total_reserve_use)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price)?></td>
		<td class="t_value_analy_total"><?=number_format($total_order_OC)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_OC)?></td>
		<td class="t_value_analy_total"><?=number_format($total_order_EX)?></td>
		<td class="t_value_analy_total"><?=number_format($total_price_EX)?></td>
	</tr>
</table>