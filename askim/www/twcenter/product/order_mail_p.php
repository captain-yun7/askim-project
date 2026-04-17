<?php
$ordmail = "
<table width='98%' border='0' cellpadding='0' cellspacing='0' style='text-align:left;'>
	<tr>
		<td><img src='http://".$_SERVER['HTTP_HOST']."/twcenter/product/image/sett_t01.gif' /></td>
	</tr>
	<tr>
		<td bgcolor='#a9a9a9' height='2'></td>
	</tr>
</table>
<table width='98%' border='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td>
			<table width='100%' border='0' cellpadding='5' cellspacing='0'>
				<tr>
					<td width='45%' height='35' align='center' bgcolor='#f9f9f9'><strong>상품정보</strong></td>
					<td width='10%' align='center' bgcolor='#f9f9f9' ><strong>제품가격</strong></td>
					<td width='10%' align='center' bgcolor='#f9f9f9'><strong>수량</strong></td>
					<td width='10%' align='center' bgcolor='#f9f9f9'><strong>적립금</strong></td>
				</tr>
				<tr>
					<td colspan='5' bgcolor='#d7d7d7' height='1'></td>
				</tr>";

	if(!is_array($order_info)) {
		$sql = "select * from wiz_order where orderid = '".$order_info->orderid."'";
		$result = query($sql);
		$order_info = sql_fetch_arr($result);
	}

	$sql = "select * from wiz_basket where orderid = '".$order_info['orderid']."'";
	$result = query($sql);
	$prd_num = sql_fetch_row($result);

	$no = 0;
	while($row = sql_fetch_obj($result)){

		$prd_price += ($row->prdprice * $row->amount);
		if($row->prdimg == "") $row->prdimg = "http://".$_SERVER['HTTP_HOST']."/images/noimage.gif";
		else $row->prdimg = "http://$HTTP_HOST/twcenter/data/prdimg/".$row->prdimg;

		$optcode = "";
		$opt3    = "";
		$opt4    = "";
		$opt5    = "";
		$opt6    = "";
		$opt7    = "";
		$opt8    = "";
		$opt9    = "";
		$opt10   = "";
		$opt11   = "";

		if(strpos($row->optcode5,"&&") !== false){
			$opt5_val = explode("&&",$row->optcode5);
			for($i=0; $i<count($opt5_val)-1; $i++){
				$exp = $opt5_val[$i];
				list($optcode5_v,$t_optcode5_v2,$t_optcode5_v3,$t_optcode5_v4) = explode("^",$exp);
				$optcode5_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode5_v2)."원 / ".$t_optcode5_v4."개)</span>";
				$opt5 .= "".$optcode5_v." ".$optcode5_v2."<br>";
			}
		} else {
			list($optcode5_v,$t_optcode5_v2) = explode("/",$row->optcode5);
			$optcode5_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode5_v2)."원)</span>";
			$opt5 = "".$optcode5_v." ".$optcode5_v2."<br>";
		}

		if($row->opttitle5 != '' && $row->optcode5 != '')  $optcode .= $opt5;


		if(strpos($row->optcode6,"&&") !== false){
			$opt6_val = explode("&&",$row->optcode6);
			for($i=0; $i<count($opt6_val)-1; $i++){
				$exp = $opt6_val[$i];
				list($optcode6_v,$t_optcode6_v2,$t_optcode6_v3,$t_optcode6_v4) = explode("^",$exp);
				$optcode6_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode6_v2)."원 / ".$t_optcode6_v4."개)</span>";
				$opt6 .= "".$optcode6_v." ".$optcode6_v2."<br>";
			}
		} else {
			list($optcode6_v,$t_optcode6_v2) = explode("/",$row->optcode6);
			$optcode6_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode6_v2)."원)</span>";
			$opt6 = "".$optcode6_v." ".$optcode6_v2."<br>";
		}

		if($row->opttitle6 != '' && $row->optcode6 != '')  $optcode .= $opt6;


		if(strpos($row->optcode7,"&&") !== false){
			$opt7_val = explode("&&",$row->optcode7);
			for($i=0; $i<count($opt7_val)-1; $i++){
				$exp = $opt7_val[$i];
				list($optcode7_v,$t_optcode7_v2,$t_optcode7_v3,$t_optcode7_v4) = explode("^",$exp);
				$optcode7_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode7_v2)."원 / ".$t_optcode7_v4."개)</span>";
				$opt7 .= "".$optcode7_v." ".$optcode7_v2."<br>";
			}
		} else {
			list($optcode7_v,$t_optcode7_v2) = explode("/",$row->optcode7);
			$optcode7_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode7_v2)."원)</span>";
			$opt7 = "".$optcode7_v." ".$optcode7_v2."<br>";
		}

		if($row->opttitle7 != '' && $row->optcode7 != '')  $optcode .= $opt7;



		if(strpos($row->optcode3,"&&") !== false){
			$opt3_val = explode("&&",$row->optcode3);
			for($i=0; $i<count($opt3_val)-1; $i++){
				$exp = $opt3_val[$i];
				list($optcode3_v,$t_optcode3_v2,$t_optcode3_v3,$t_optcode3_v4) = explode("^",$exp);
				$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원 / ".$t_optcode3_v4."개)</span>";
				$opt3 .= "".$row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br> ";
			}
		} else {
			list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3);
			$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원)</span>";
			$opt3 = "".$row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br>";
		}

		if(strpos($row->optcode4,"&&") !== false){
			$opt4_val = explode("&&",$row->optcode4);
			for($i=0; $i<count($opt4_val)-1; $i++){
				$exp = $opt4_val[$i];
				list($optcode4_v,$t_optcode4_v2,$t_optcode4_v3,$t_optcode4_v4) = explode("^",$exp);
				$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원 / ".$t_optcode4_v4."개)</span>";
				$opt4 .= "".$row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br> ";
			}
		} else {
			list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4);
			$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원)</span>";
			$opt4 = "".$row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br>";
		}

		if(strpos($row->optcode8,"&&") !== false){
			$opt8_val = explode("&&",$row->optcode8);
			for($i=0; $i<count($opt8_val)-1; $i++){
				$exp = $opt8_val[$i];
				list($optcode8_v,$t_optcode8_v2,$t_optcode8_v3,$t_optcode8_v4) = explode("^",$exp);
				$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원 / ".$t_optcode8_v4."개)</span>";
				$opt8 .= "".$row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br> ";
			}
		} else {
			list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8);
			$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원)</span>";
			$opt8 = "".$row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br>";
		}

		if(strpos($row->optcode9,"&&") !== false){
			$opt9_val = explode("&&",$row->optcode9);
			for($i=0; $i<count($opt9_val)-1; $i++){
				$exp = $opt9_val[$i];
				list($optcode9_v,$t_optcode9_v2,$t_optcode9_v3,$t_optcode9_v4) = explode("^",$exp);
				$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원 / ".$t_optcode9_v4."개)</span>";
				$opt9 .= "".$row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br> ";
			}
		} else {
			list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9);
			$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원)</span>";
			$opt9 = "".$row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br>";
		}

		if(strpos($row->optcode10,"&&") !== false){
			$opt10_val = explode("&&",$row->optcode10);
			for($i=0; $i<count($opt10_val)-1; $i++){
				$exp = $opt10_val[$i];
				list($optcode10_v,$t_optcode10_v2,$t_optcode10_v3,$t_optcode10_v4) = explode("^",$exp);
				$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원 / ".$t_optcode10_v4."개)</span>";
				$opt10 .= "".$row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br> ";
			}
		} else {
			list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10);
			$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원)</span>";
			$opt10 = "".$row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br>";
		}

		if(strpos($row->optcode11,"&&") !== false){
			$opt11_val = explode("&&",$row->optcode11);
			for($i=0; $i<count($opt11_val)-1; $i++){
				$exp = $opt11_val[$i];
				list($optcode11_v,$t_optcode11_v2,$t_optcode11_v3,$t_optcode11_v4) = explode("^",$exp);
				$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원 / ".$t_optcode11_v4."개)</span>";
				$opt11 .= "".$row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br> ";
			}
		} else {
			list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11);
			$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원)</span>";
			$opt11 = "".$row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br>";
		}


		if($row->opttitle3 != ''  && $row->optcode3 != '')  $optcode .= $opt3;
		if($row->opttitle4 != ''  && $row->optcode4 != '')  $optcode .= $opt4;
		if($row->opttitle8 != ''  && $row->optcode8 != '')  $optcode .= $opt8;
		if($row->opttitle9 != ''  && $row->optcode9 != '')  $optcode .= $opt9;
		if($row->opttitle10 != '' && $row->optcode10 != '') $optcode .= $opt10;
		if($row->opttitle11 != '' && $row->optcode11 != '') $optcode .= $opt11;

		if(strpos($row->optcode,"&&") !== false){
			$opt_val = explode("&&",$row->optcode);
			for($i=0; $i<count($opt_val)-1; $i++){
				$exp = $opt_val[$i];
				list($optcode_v,$t_optcode_v2,$t_optcode_v3,$t_optcode_v4) = explode("^",$exp);
				$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원 / ".$t_optcode_v4."개)</span>";

				if($row->opttitle != '') $topttitle = $row->opttitle;
				if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
				if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

				$opt = "".$topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
			}
		} else {
			list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
			if($t_optcode_v2 != 0){
				$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
			} else {
				$optcode_v2 = "";
			}

			if($row->opttitle != '') $topttitle = $row->opttitle;
			if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
			if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

			$opt = "".$topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
		}


		if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= $opt;

		$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
		$optcode = "<span class='pay_add_tit'>".$optcode."</span>";

		if(!strcmp($row->status, "CA")) $basket_status = "<font color='red'>[취소신청]</font>";
		else if(!strcmp($row->status, "CI")) $basket_status = "<font color='red'>[처리중]</font>";
		else if(!strcmp($row->status, "CC")) $basket_status = "<font color='red'>[취소완료]</font>";
		else $basket_status = "";

		$del_type = "";
		if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
			if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)."".number_format($row->del_price)."원)";
			else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
		}

		$c_sql = "
			select wc.purl
			  from wiz_product as wp 
			  left join wiz_cprelation as wcp 
			    on wp.prdcode = wcp.prdcode
			  left join wiz_category as wc 
			    on wcp.catcode = wc.catcode
			 where wp.prdcode = '".$row->prdcode."'
		";
		$c_result = query($c_sql);
		$c_row = sql_fetch_arr($c_result);

		$c_prd_view_page = "http://".$_SERVER['HTTP_HOST']."/".$c_row['purl']."?ptype=view&prdcode=".$row->prdcode;

		list($del_com,$del_code) = explode("|",$row->del_com);

		$query = "select * from wiz_delivery_company where idx='{$del_code}' ";
		$qresult = query($query);
		$_delivery = sql_fetch_arr($qresult);

		$del_trace = $_delivery['del_trace'];
		$del_com   = $_delivery['del_com'];

		$_delivery2 = "<a href='".$del_trace."".$row->del_num."'>".$row->del_num."</a><br>(".$del_com.")";

$ordmail .= "
				<tr>
					<td style='padding-left:10px;'>
						<table width='100%' border='0' cellpadding='5' cellspacing='0'>
							<tr>
								<td width='20%' align='center' class='rpad_10'><a href='".$c_prd_view_page."'><img src='".$row->prdimg."' width='80' height='60' border='0' /></a></td>
								<td><a href='".$c_prd_view_page."'>".$row->prdname."</a> ".$basket_status." <br>".$optcode.$del_type."</td>
							</tr>
						</table>
					</td>
					<td align='center'><strong>".number_format($row->prdprice)."원</strong></td>
					<td align='center'>".$row->amount."</td>
					<td align='center'>".number_format($row->prdreserve*$row->amount)."원</td>
				</tr>
				<tr>
					<td colspan='5' bgcolor='#d7d7d7' height='1'></td>
				</tr>";

		$no++;
	}

// 회원할인
if($order_info['discount_price'] > 0){
	$discount_msg = " - 회원할인(<b>".number_format($order_info['discount_price'])."원</b>)";
}

// 적립금 사용
if($order_info['reserve_use'] > 0){
	$reserve_msg = " - 적립금 사용(<b>".number_format($order_info['reserve_use'])."원</b>)";
}

// 쿠폰사용
if($order_info['coupon_use'] > 0){
	$coupon_msg = " - 쿠폰 사용(<b>".number_format($order_info['coupon_use'])."원</b>)";
}

$ordmail .= "
				<tr>
					<td colspan='5' height='10'></td>
				</tr>
				<tr>
					<td colspan='5' bgcolor='#d7d7d7' height='1'></td>
				</tr>
				<tr>
					<td height='50' colspan='5' bgcolor='#f9f9f9'>
						<table width='100%' border='0' cellpadding='0' cellspacing='0'>
							<tr>
								<td style='padding-left:10px; text-align:left;'></td>
								<td align='right' style='padding-right:10px;'>상품(<strong>".number_format($order_info['prd_price'])."원</strong>) ".$discount_msg." + 배송비(<strong>".number_format($order_info['deliver_price'])."원</strong>) ".$coupon_msg.$reserve_msg." = 주문합계 <span style='color:#d43d60; font-weight:bold;'>".number_format($order_info['total_price'])."원</span></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan='5' bgcolor='#d7d7d7' height='1'></td>
				</tr>
			</table>

		</td>
	</tr>

	<tr>
		<td style='padding-top:30px;'>

			<table width='100%' border='0' cellpadding='5' cellspacing='0' style='text-align:left;'>
				<tr><td colspan='4' bgcolor='#a9a9a9' height='2'></td></tr>
				<tr>
				   <td width=20% bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주문번호</strong></td>
				   <td width='35%' style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['orderid']." </td>
				  <td width='20%' bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주문일</strong></td>
				   <td width='25%' style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['order_date']."</td>
				</tr>
				<tr>
				   <td bgcolor='#f9f9f9'  style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>결제방법</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".pay_method($order_info['pay_method'])."</td>
				   <td bgcolor='#f9f9f9'  style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주문상태</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".order_status($status)."</td>
				</tr>";


if($order_info['pay_method'] == "PB"){

$ordmail .= "
				<tr>
				   <td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>입금계좌</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['account']."</td>
				   <td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>입금자명</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['account_name']."</td>
				</tr>";

}else if($order_info['pay_method'] == "PV"){

$ordmail .= "
				<tr>
				   <td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>입금계좌</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['account']." <font color=red>(가상계좌번호로 입금하셔야 주문이 완료됩니다.)</font></td>
				</tr>";

}

if($order_info['deliver_num'] != ""){

$ordmail .= "
				<tr>
				   <td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>운송장번호</strong></td>
				   <td style='border-bottom:1px solid #d7d7d7; padding:5px; '>".$order_info['deliver_num']."</td>
				</tr>";

}

$ordmail .= "
    	</table>

		</td>
	</tr>

	<tr>
		<td style='padding-top:30px;'>

			<table width='100%' border='0' cellpadding='0' cellspacing='0' style='text-align:left;'>
				<tr>
					<td><img src='http://".$_SERVER['HTTP_HOST']."/twcenter/product/image/order_tit01.gif' border='0' /></td>
				</tr>
				<tr>
					<td>
						<table width='100%' border='0' cellpadding='5' cellspacing='0'>
							<tr><td colspan='2' bgcolor='#a9a9a9' height='2'></td></tr>
							<tr>
								<td width=20% bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주문하시는 분</strong></td>
								<td width='80%' style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['send_name']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9'  style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>전화번호</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['send_tphone']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>휴대전화번호</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['send_hphone']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>이메일</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['send_email']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주 소</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>[".$order_info['send_post']."] ".$order_info['send_address']."</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

		</td>
	</tr>

	<tr>
  	<td style=padding-top:30px;>

			<table width='100%' border='0' cellpadding='0' cellspacing='0' style='text-align:left;'>
				<tr>
					<td><img src='http://".$_SERVER['HTTP_HOST']."/twcenter/product/image/order_tit02.gif' border='0' /></td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%' border='0' cellpadding='5' cellspacing='0'>
							<tr><td colspan='2' bgcolor='#a9a9a9' height='2'></td></tr>
							<tr>
								<td width=20% bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>받으시는 분</strong></td>
								<td width='80%' style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['rece_name']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9'  style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>전화번호</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['rece_tphone']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>휴대전화번호</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".$order_info['rece_hphone']."</td>
							</tr>
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>주 소</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>[".$order_info['rece_post']."] ".$order_info['rece_address']."</td>
							</tr>";

if($order_info['demand'] != ""){
$ordmail .= "
							<tr>
								<td bgcolor='#f9f9f9' style='padding-left:10px; border-right:1px solid #d7d7d7; border-bottom:1px solid #d7d7d7;'><strong>요청사항</strong></td>
								<td style='border-bottom:1px solid #d7d7d7; padding:5px;'>".str_replace("\n","<br>&nbsp;&nbsp;",$order_info['demand'])."</td>
							</tr>";
}
$ordmail .= "
						</table>

					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>";
?>