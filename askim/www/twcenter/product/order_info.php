<div style="padding:10px;">

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
					<td class="table_tit" width="12%"></td>
					<td class="table_tit">구매상품정보</td>
					<td class="table_tit" width="12%">상품가격</td>
					<td class="table_tit" width="12%">수 량</td>
					<?
					if($oper_info['reserve_use'] == "Y"){
					?>
					<td class="table_tit" width="12%">적립금</td>
					<?
					}
					?>
					<td class="table_tit" width="12%">합 계</td>
					<td class="table_tit" width="12%">상품평</td>
				</tr>
				<tr><td colspan="7" height="1" bgcolor="#dddddd"></td></tr>
<?
$sql = "select * from wiz_basket where orderid = '".$order_info['orderid']."' order by idx desc";
$result = query($sql);
$prd_num = sql_fetch_rows($sql);

$no = 0;
while($row = sql_fetch_obj($result)){

	$_prdidx = $row->idx;
	$prd_d_price      = $row->prdprice * $row->amount;
	$reserve_d_price  = $row->prdreserve * $row->amount;

	if(
		strpos($row->optcode,"&&")   !== false || strpos($row->optcode2,"&&")  !== false || strpos($row->optcode3,"&&") !== false || 
		strpos($row->optcode4,"&&")  !== false || strpos($row->optcode5,"&&")  !== false || strpos($row->optcode6,"&&") !== false || 
		strpos($row->optcode7,"&&")  !== false || strpos($row->optcode8,"&&")  !== false || strpos($row->optcode9,"&&") !== false || 
		strpos($row->optcode10,"&&") !== false || strpos($row->optcode11,"&&") !== false || strpos($row->optcode12,"&&") !== false || 
		strpos($row->optcode13,"&&") !== false
		)
	{
		$prd_price       += ($row->prdprice * $row->amount);
	} else {
		$prd_price       += ($row->prdprice * $row->amount);
	}

	if($row->prdimg == "" || !@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row->prdimg)) $row->prdimg = "/twcenter/images/noimage.gif";
	else $row->prdimg = "/twcenter/data/prdimg/".$row->prdimg;

	$prdname = "<span class='basket_name'>".$row->prdname."</span>";

	$t_optcode        = $row->optcode;
	$t_opttitle       = $row->opttitle;
	for($o=2; $o<=13; $o++) {
		${'t_optcode'.$o}  = $row->{'optcode'.$o};
		${'t_opttitle'.$o} = $row->{'opttitle'.$o};
	}

	$optcode = $opt = $opt3 = $opt5 = $opt6 = $opt7 = $opt8 = $opt9 = $opt10 = $opt11 = $opt12 = $opt13 = "";

	include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_option_inc.php";

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

	$c_sql = "select wc.* 
		from wiz_cprelation as wcp
		left join wiz_category as wc on wcp.catcode=wc.catcode
		where wcp.prdcode = '$row->prdcode'";
	$c_row = sql_fetch($c_sql);

	$prd_view_page = "/".$c_row['purl']."?ptype=view&prdcode=".$row->prdcode;

?>
				<tr>
					<td align="center" style="padding:10px 5px;"><a href="<?=$prd_view_page?>" target="prdview"><img src="<?=$row->prdimg?>" border="0" width="72" height="72"></a></td>
					<td>
						<a href="<?=$prd_view_page?>" target="prdview"><?=$prdname?></a>
						<p><?=$basket_status?><?=$optcode?><?=$del_type?></p>
					</td>
					<td class="bprice" align="center"><?=number_format($row->prdprice)?>원</td>
					<td align="center"><?=$row->amount?></td>
					<?
					if($oper_info['reserve_use'] == "Y"){
					?>
					<td align="center"><?=number_format($reserve_d_price)?>원</td>
					<?
					}
					?>
					<td class="bprice" align="center"><?=number_format($prd_d_price)?>원</td>
					<td align="center"><? if($order_info['status'] == 'DC' || $order_info['status'] == 'CC') {?><input type="button" onclick="document.location='/member/review.php?ptype=input&prdcode=<?=$row->prdcode?>';" value="상품평 쓰기" class="btn_review"><? }?></td>
				</tr>
				<tr><td colspan="7" height="1" bgcolor="#E5E5E5"></td></tr>
<?
	$no++;
}

// 회원할인
if($order_info['discount_price'] > 0){
	$discount_msg = "<img src='/twcenter/product/image/icon_plus.gif'>회원할인&nbsp;&nbsp;<span class='tbold'>".number_format($order_info['discount_price'])."</span> 원";
}

// 적립금 사용
if($order_info['reserve_use'] > 0){
	$reserve_msg = "<img src='/twcenter/product/image/icon_plus.gif'>적립금 사용&nbsp;&nbsp;<span class='tbold'>".number_format($order_info['reserve_use'])."</span> 원";
}

// 쿠폰사용
if($order_info['coupon_use'] > 0){
	$coupon_msg = "<img src='/twcenter/product/image/icon_plus.gif'>쿠폰 사용&nbsp;&nbsp;<span class='tbold'>".number_format($order_info['coupon_use'])."</span> 원";
}

// 배송비
$deliver_price = deliver_price($order_info['prd_price'], $oper_info);
$deliver_price_view = deliver_price2($oper_info['del_method'], $deliver_price);
if($deliver_price_view == "착불") {
	$deliver_price_view = "<img src='/twcenter/product/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>0</span> 원";
} else {
	$deliver_price_view = "<img src='/twcenter/product/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>".number_format($deliver_price)."</span> 원";
}

if($order_info['deliver_price'] > $deliver_price){
	$deliver_msg .= " , 배송비 할증";
}

?>
				<tr>
					<td align="right" colspan="7">
						<div class="delivery_box">
							<div class="two">
							<p class="paytxt">
							
							총 결제금액 : 상품&nbsp;&nbsp;<span class="tbold"><?=number_format($order_info['prd_price'])?></span> 원
							<?php echo $discount_msg ?> 
							<?php echo $discount_price_view ?>
							<?php echo $deliver_price_view ?>
							<?php echo $coupon_msg ?> 
							<?php echo $reserve_msg ?>
							<img src="/twcenter/product/image/icon_plus3.gif"><span class="tbold"><?=number_format($order_info['total_price'])?> 원</span>
							<p class="paytxt2" style="margin-top:10px;">배송비 : <?=$deliver_msg?></p>
							</div>
						</div>
					</td>
				</tr>
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
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">
					<?php echo order_status($order_info['status']) ?>
					<?php
					// 결제완료,배송준비중,배송처리일때만 배송조회버튼 보임
					$status_array = array('OY','DR','DI');
					if(in_array($order_info['status'], $status_array) && $order_info['deliver_num']) {
					?>
					<span class="prd_table_stxt">(<?php echo $del_com ?> : <?php echo $order_info['deliver_num'] ?>)</span>
					<a href="javascript:;" onclick="delivery_status_popup('<?php echo $del_trace ?>','<?php echo $order_info['deliver_num'] ?>')" title="<?=$del_com?>" class="btn_type1">배송조회</a>
					<?php } ?>
					</td>
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
				<?		if($order_info['account_dueDate'] != '' && $order_info['status'] == 'OR') { ?>
				<tr>
					<td class="table_tit2">입금기한</td>
					<td colspan="3" style="border-bottom:1px solid #d7d7d7; padding:5px 10px;"><?=$order_info['account_dueDate']?></td>
				</tr>
				<?		} ?>
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
</div>