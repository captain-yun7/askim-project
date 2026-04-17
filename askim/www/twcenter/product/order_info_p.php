<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
	<tr>
           <td align="left" class="shop_tit">고객님께서 주문하신 상품입니다.</td>
         </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="order_form">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td colspan="7" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td class="table_tit" width="12%">이미지</td>
					<td class="table_tit">구매상품정보</td>
					<td class="table_tit" width="11%">운송장번호</td>
					<td class="table_tit" width="12%">제품가격</td>
					<td class="table_tit" width="5%">수 량</td>
					<td class="table_tit" width="10%">적립금</td>
					<td class="table_tit" width="10%">합 계</td>
				</tr>
				<tr><td colspan="7" height="1" bgcolor="#dddddd"></td></tr>
<?
$sql = "select * from wiz_basket where orderid = '$order_info['orderid']'";
$result = query($sql);
$prd_num = sql_fetch_rows($sql);

$no = 0;
while($row = sql_fetch_obj($result)){

	$_prdidx = $row->idx;
	$prd_d_price      = $row->prdprice * $row->amount;
	$reserve_d_price  = $row->prdreserve * $row->amount;

	$prd_price       += ($row->prdprice * $row->amount);
	if($row->prdimg == "") $row->prdimg = "/twcenter/images/noimage.gif";
	else $row->prdimg = "/twcenter/data/prdimg/".$row->prdimg;

	$prdname = "<strong>".$row->prdname."</strong>";
	$optcode = "";

	list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3);
	$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원)</span>";
	list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4);
	$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원)</span>";
	list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8);
	$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원)</span>";
	list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9);
	$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원)</span>";
	list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10);
	$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원)</span>";
	list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11);
	$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원)</span>";

//	if($row->opttitle5 != '')  $optcode = $row->opttitle5." : ".$row->optcode5."," ;
//	if($row->opttitle6 != '')  $optcode .= $row->opttitle6." : ".$row->optcode6.", ";
//	if($row->opttitle7 != '')  $optcode .= $row->opttitle7." : ".$row->optcode7.",<br> ";

//	if($row->opttitle3 != '')  $optcode .= $row->opttitle3." : ".$optcode3_v." ".$optcode3_v2.", ";
//	if($row->opttitle4 != '')  $optcode .= $row->opttitle4." : ".$optcode4_v." ".$optcode4_v2.",<br> ";
//	if($row->opttitle8 != '')  $optcode .= $row->opttitle8." : ".$optcode8_v." ".$optcode8_v2.", ";
//	if($row->opttitle9 != '')  $optcode .= $row->opttitle9." : ".$optcode9_v." ".$optcode9_v2.",<br> ";
//	if($row->opttitle10 != '') $optcode .= $row->opttitle10." : ".$optcode10_v." ".$optcode10_v2.", ";
//	if($row->opttitle11 != '') $optcode .= $row->opttitle11." : ".$optcode11_v." ".$optcode11_v2.",<br> ";

	if($row->opttitle5 != '' && $row->optcode5 != '')  $optcode = $row->opttitle5." : ".$optcode5."," ;
	if($row->opttitle6 != '' && $row->optcode6 != '')  $optcode .= $row->opttitle6." : ".$row->optcode6.", ";
	if($row->opttitle7 != '' && $row->optcode7 != '')  $optcode .= $row->opttitle7." : ".$row->optcode7.",<br> ";

	if($row->opttitle3 != '' && $row->optcode3 != '')  $optcode .= $row->opttitle3." : ".$optcode3_v." ".$optcode3_v2.", ";
	if($row->opttitle4 != '' && $row->optcode4 != '')  $optcode .= $row->opttitle4." : ".$optcode4_v." ".$optcode4_v2.",<br> ";
	if($row->opttitle8 != '' && $row->optcode8 != '')  $optcode .= $row->opttitle8." : ".$optcode8_v." ".$optcode8_v2.", ";
	if($row->opttitle9 != '' && $row->optcode9 != '')  $optcode .= $row->opttitle9." : ".$optcode9_v." ".$optcode9_v2.",<br> ";
	if($row->opttitle10 != '' && $row->optcode10 != '') $optcode .= $row->opttitle10." : ".$optcode10_v." ".$optcode10_v2.", ";
	if($row->opttitle11 != '' && $row->optcode11 != '') $optcode .= $row->opttitle11." : ".$optcode11_v." ".$optcode11_v2.",<br> ";

	list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
	if($t_optcode_v2 != 0){
		$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
	} else {
		$optcode_v2 = "";
	}

	if($row->opttitle != '') $optcode .= $row->opttitle;
	if($row->opttitle != '' && $row->opttitle2 != '') $optcode .= "/";
	if($row->opttitle2 != '') $optcode .= $row->opttitle2;
	if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= " : ".$optcode_v."".$optcode_v2.", ";

	$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
	$optcode = "<span class='pay_add_tit'>".$optcode."</span>";

	if(!strcmp($row->status, "CA")) $basket_status = "<font color='red'>[취소신청]</font>";
	else if(!strcmp($row->status, "CI")) $basket_status = "<font color='red'>[처리중]</font>";
	else if(!strcmp($row->status, "CC")) $basket_status = "<font color='red'>[취소완료]</font>";
	else $basket_status = "";

	$del_type = "";
	if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
		if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)." : ".number_format($row->del_price)."원)";
		else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
	}
	$del_type = "<span class='pay_add_tit'>".$del_type."</span>";

	//2023-12-28 경로 오류 수정 
	$c_sql = "select wc.* 
		from wiz_cprelation as wcp
		left join wiz_category as wc on wcp.catcode=wc.catcode
		where wcp.prdcode = '$row->prdcode'";
	
	$c_row = sql_fetch($c_sql);

	$prd_view_page = "/".$c_row['purl']."?ptype=view&prdcode=".$row->prdcode;

	list($del_com,$del_code) = explode("|",$row->del_com);

	$query = "select * from wiz_delivery_company where idx='{$del_code}' ";
	$qresult = query($query);
	$_delivery = sql_fetch_arr($qresult);

	$del_trace = $_delivery['del_trace'];
	$del_com   = $_delivery['del_com'];

?>
				<tr>
					<td align="center" style="padding:5px;"><a href="<?=$prd_view_page?>" target="prdview"><img src="<?=$row->prdimg?>" border="0" width="50" height="50"></a></td>
					<td><a href="<?=$prd_view_page?>" target="prdview"><?=$prdname?></a> <?=$basket_status?> <br><?=$optcode?><?=$del_type?></td>
					<td align=center><a href="<?=$del_trace?><?=$row->del_num?>" target="_blank" title="<?=$del_com?>"><?=$row->del_num?></a><br><?=$del_com?></td>
					<td class=price align=center><?=number_format($row->prdprice)?>원</td>
					<td align=center><b><?=$row->amount?></b></td>
					<td align=center><b><?=number_format($reserve_d_price)?>원</b></td>
					<td class=price align=center><?=number_format($prd_d_price)?>원</td>
				</tr>
				<tr><td colspan="7" height="1" bgcolor="#E5E5E5"></td></tr>
<?
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

// 배송비
$deliver_price = deliver_price($order_info['prd_price'], $oper_info);
if($order_info['deliver_price'] > $deliver_price){
	$deliver_msg .= " , 배송비 할증";
}

?>
				<tr bgcolor="#f9f9f9">
					<td align="right" colspan="7" style="padding:8px 15px;">
					<b>총 결제금액 </b>:  상품(<b><?=number_format($order_info['prd_price'])?>원)</b> <?=$discount_msg?> + 배송비(<b><?=number_format($order_info['deliver_price'])?>원</b>)<?=$coupon_msg?><?=$reserve_msg?> = <span class=price><?=number_format($order_info['total_price'])?>원</span>&nbsp;<br>
					<span align="right">배송비 : <?=$deliver_msg?></span>&nbsp;
					</td>
				</tr>
				<tr><td colspan="7" height="1" bgcolor="#E5E5E5"></td></tr>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td>

			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr><td colspan="4" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="20%" class="table_tit2">주문번호</td>
					<td width="35%" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['orderid']?></td>
					<td width="20%" class="table_tit2">주문일</td>
					<td width="25%" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['order_date']?></td>
				</tr>
				<tr>
					<td class="table_tit2">결제방법</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=pay_method($order_info['pay_method'])?></td>
					<td class="table_tit2">주문상태</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=order_status($order_info['status'])?></td>
				</tr>
				<? if($order_info['pay_method'] == "PB"){ ?>
				<tr>
					<td class="table_tit2">입금계좌</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['account']?></td>
					<td class="table_tit2">입금자명</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['account_name']?></td>
				</tr>
				<? }else if($order_info['pay_method'] == "PV"){ ?>
				<tr>
					<td class="table_tit2">입금계좌</td>
					<td colspan="3" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['account']?></b> <font color=red>(가상계좌번호로 입금하셔야 주문이 완료됩니다.)</font></td>
				</tr>
				<? } ?>
			</table>

		</td>
	</tr>

	<tr>
		<td style="padding-top:30px;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr>
					<td class="shop_tit">주문하시는 분</td>
				</tr>
				<tr><td colspan="4" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="20%" class="table_tit2">주문하시는 분</td>
					<td width="80%" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['send_name']?></td>
				</tr>
				<tr>
					<td class="table_tit2">전화번호</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['send_tphone']?></td>
				</tr>
				<tr>
					<td class="table_tit2">휴대전화번호</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['send_hphone']?></td>
				</tr>
				<tr>
					<td class="table_tit2">이메일</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['send_email']?></td>
				</tr>
				<tr>
					<td class="table_tit2">주 소</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">[<?=$order_info['send_post']?>] <?=$order_info['send_address']?></td>
				</tr>
			</table>

		</td>
	</tr>

	<tr>
		<td style="padding-top:30px;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr>
					<td class="shop_tit">받으시는 분</td>
				</tr>
				<tr>
				<tr><td colspan="2" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="20%" class="table_tit2">받으시는 분</td>
					<td width="80%" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['rece_name']?></td>
				</tr>
				<tr>
					<td class="table_tit2">전화번호</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['rece_tphone']?></td>
				</tr>
				<tr>
					<td class="table_tit2">휴대전화번호</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['rece_hphone']?></tr>
				<tr>
					<td class="table_tit2">주 소</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">[<?=$order_info['rece_post']?>] <?=$order_info['rece_address']?></td>
				</tr>
				<? if($order_info['demand'] != ""){ ?>
				<tr>
					<td class="table_tit2">요청사항</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=str_replace("\n","<br>&nbsp;&nbsp;",$order_info['demand'])?></td>
				</tr>
				<? } ?>
			</table>

		</td>
	</tr>
</table>