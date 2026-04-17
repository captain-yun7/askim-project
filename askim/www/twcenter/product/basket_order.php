<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan="2">

		 <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="12%" class="table_tit"></td>
				<td class="table_tit">구매상품정보</td>
				<td width="12%" class="table_tit">상품가격</td>
				<td width="12%" class="table_tit">수량</td>
				<?if($oper_info['reserve_use'] == "Y"){?>
				<td width="12%" class="table_tit">적립금</td>
				<?}?>
				<td width="12%" class="table_tit">합계</td>
			</tr>
			<tr>
        <td colspan="6" bgcolor="#d7d7d7" height="1"></td>
      </tr>
			<?php

			// 주문정보
			$sql = "select * from wiz_order where orderid='".$orderid."'";
			$result = query($sql) or error("sql error");
			$order_info = sql_fetch_obj($result);

			$order_prdname = "";

			// 주문상품 정보
			$sql = "select * from wiz_basket where orderid='".$orderid."' order by idx desc";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);
			$product_idx = "";
			$no = 1;

			while($row = sql_fetch_obj($result)){

				if($no == 1) { 
					$order_prdname = $row->prdname;
					if($total > 1) $order_prdname .= " 외";
				}

				$product_idx .= str_replace(" ","",$row->basketidx)."|";
				$del_type = "";

				///////////////////////////////////////////////////////////////////////////////////
				//결제에 들어갈 상품이름 (1개일경우 :마우스 , 2개이상일경우 마우스 외1개 로 출력)//
				///////////////////////////////////////////////////////////////////////////////////
				if($total>1){//1개 이상일경우
					$payment_prdname = $row->prdname." 외".($total-1)."개";
				}else{//한개일경우
					$payment_prdname = $row->prdname;
				}
				////////////////////////////////////////////////////////////////////////////////////
				$prdname = "<span class='basket_name'>".$row->prdname."</span>";

				if(
					strpos($row->optcode,"&&")   !== false || strpos($row->optcode2,"&&")  !== false || strpos($row->optcode3,"&&") !== false || 
					strpos($row->optcode4,"&&")  !== false || strpos($row->optcode5,"&&")  !== false || strpos($row->optcode6,"&&") !== false || 
					strpos($row->optcode7,"&&")  !== false || strpos($row->optcode8,"&&")  !== false || strpos($row->optcode9,"&&") !== false || strpos($row->optcode10,"&&") !== false || strpos($row->optcode11,"&&") !== false || strpos($row->optcode12,"&&") !== false || strpos($row->optcode13,"&&") !== false
					)
				{
					$prd_price       += ($row->prdprice * $row->amount);
				} else {
					$prd_price       += ($row->prdprice * $row->amount);
				}

				$t_optcode        = $row->optcode;
				$t_opttitle       = $row->opttitle;

				$t_optcode2 = $t_optcode3 = $t_optcode4 = $t_optcode5 = $t_optcode6 = $t_optcode7 = $t_optcode8 = $t_optcode9 = $t_optcode10 = $t_optcode11 = "";
				$t_opttitle2 = $t_opttitle3 = $t_opttitle4 = $t_opttitle5 = $t_opttitle6 = $t_opttitle7 = $t_opttitle8 = $t_opttitle9 = $t_opttitle10 = $t_opttitle11 = $t_opttitle12 = $t_opttitle13 = "";
				for($o=2; $o<=13; $o++) {
					${'t_optcode'.$o}  .= $row->{'optcode'.$o};
					${'t_opttitle'.$o} .= $row->{'opttitle'.$o};
				}

				$optcode = $opt = $opt3 = $opt5 = $opt6 = $opt7 = $opt8 = $opt9 = $opt10 = $opt11 = $opt12 = $opt13 = "";

				include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_option_inc.php";

				if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
					if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)." : ".number_format($row->del_price)."원)";
					else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
				}
				$del_type = "<span class='pay_add_tit'>".$del_type."</span>";

				// 상품 이미지
				if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row->prdimg)) $row->prdimg = "/twcenter/images/noimg_S.gif";
				else $row->prdimg = "/twcenter/data/prdimg/".$row->prdimg;

				$c_sql = "select wc.purl
									from wiz_product as wp left join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
									left join wiz_category as wc on wcp.catcode = wc.catcode
									where wp.prdcode = '$row->prdcode'";
				$c_result = query($c_sql) or error("sql error");
				$c_row = sql_fetch_arr($c_result);

				$prd_view_page = "/".$c_row['purl']."?ptype=view&prdcode=".$row->prdcode;
			?>
			<tr>

				<td style="padding:10px 5px"" align=center><a href="<?=$prd_view_page?>" target="prdview"><img src="<?=$row->prdimg?>" width="72" height="72" border="0"></a></td>
				<td>
					<a href="<?=$prd_view_page?>" target="prdview"><?=$prdname?></a>
					<p><?=$optcode?><?=$del_type?></p>
				</td>
				<td align=center class="bprice"><?=number_format($row->prdprice)?>원</td>
				<td align=center><?=$row->amount?></td>
				<?if($oper_info['reserve_use'] == "Y"){?>
				<? if(!isset($reserve_price)) $reserve_price = 0.00; ?>
				<td align=center><?=number_format($reserve_price)?>원</td>
				<?}?>
				<td align=center class="bprice"><?=number_format($row->prdprice * $row->amount)?>원</td>
			</tr>
			<tr>
        <td colspan="6" bgcolor="#d7d7d7" height="1"></td>
      </tr>
			</form>
			<?
			}

			if($total <= 0){
			?>
			<tr><td colspan=10 height=30 align=center>장바구니가 비어있습니다.</td></tr>
			<tr><td colspan="6" bgcolor="#d7d7d7" height="1"></td></tr>
			<?
			}

			// 배송비
			$deliver_price = deliver_price($order_info->prd_price, $oper_info);
			$deliver_price_view = deliver_price2($oper_info['del_method'], $deliver_price);
			if($deliver_price_view == "착불") {
				$deliver_price_view = "<img src='".$skin_dir."/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>0</span> 원";
			} else {
				$deliver_price_view = "<img src='".$skin_dir."/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>".number_format($deliver_price)."</span> 원";
			}

			if($order_info->deliver_price > $deliver_price){
				$deliver_msg .= " , 배송비 할증";
			}

			// 회원할인 [$discount_msg 메세지 생성]
			$discount_price = level_discount($wiz_session['level'],$order_info->prd_price);

			// 적립금 사용
			if($order_info->reserve_use > 0){
				//$reserve_msg = " <img src='/twcenter/product/image/icon_plus.gif'> 적립금 사용(<b>".number_format($order_info->reserve_use)."</b>)";
				$reserve_msg = " <img src='/twcenter/product/image/icon_plus.gif'> 적립금 사용&nbsp;&nbsp;<span class='tbold'>".number_format($order_info->reserve_use)."</span> 원";
			}

			// 쿠폰사용
			if($order_info->coupon_use > 0){
				//$coupon_msg = " <img src='/twcenter/product/image/icon_plus.gif'> 쿠폰 사용(<b>".number_format($order_info->coupon_use)."</b>)";
				$coupon_msg = " <img src='/twcenter/product/image/icon_plus.gif'> 쿠폰 사용&nbsp;&nbsp;<span class='tbold'>".number_format($order_info->coupon_use)."</span> 원";
			}

?>
		 </table>

    </td>
  </tr>
  <tr>
  	<td colspan="6">
		<div class="delivery_box">
			<div class="one"><p class="tb2">[배송비]</p><?=$deliver_msg?></div>

			<div class="two">
				<p class="paytxt">
				상품&nbsp;&nbsp;<span class="tbold"><?=number_format($prd_price ?? 0)?></span> 원
				<?php echo $discount_msg ?> 
				<?php echo $discount_price_view ?>
				<?php echo $deliver_price_view ?>
				<?php echo $coupon_msg ?> 
				<?php echo $reserve_msg ?>
				<p class="paytxt2">주문합계&nbsp;&nbsp;&nbsp;<span class="price_t1"><?=number_format($order_info->total_price ?? 0)?></span> 원</p>
			</div>
		</div>
	</tr>
</table>