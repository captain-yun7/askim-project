<script language="JavaScript" src="/comm/js/common.lib.js"></script>
<script language="javascript">
<!--

// 수량 증가
function incAmount(idx){

	var ii = 0;
	for(ii=0; document.forms[ii].amount == null; ii++){}
	idx = eval(idx) + eval(ii);

	var amount = document.forms[idx].amount.value;
	document.forms[idx].amount.value = ++amount;

}

// 수량 감소
function decAmount(idx){

	var ii = 0;
	for(ii=0; document.forms[ii].amount == null; ii++){}
	idx = eval(idx) + eval(ii);

   var amount = document.forms[idx].amount.value;
	if(amount > 1)
		document.forms[idx].amount.value = --amount;

}

// 수량체크
function checkAmount(frm){

	var amount = frm.amount.value;
	if(!check_Num(amount) || amount < 1){
		frm.amount.value = "1";
	}

}

function checkForm(frmm){
	var amount = frmm.amount.value;
	if(amount==""){
		alert("수량이 비어있습니다.");
		return false;
	}
	if(amount<1){
		alert("수량이 0개 보다 작습니다.");
		return false;
	}
}

// 위시리스트 추가
function addWish(idx){
<? if(empty($wiz_session['id'])){ ?>
  alert('로그인이 필요합니다.\n\n회원가입을 하지 않으신 분은 가입후 이용하시기 바랍니다.');
<? }else{ ?>
  document.location = '/twcenter/product/prd_save.php?mode=my_wish&idx='+idx;
<? } ?>
}
-->
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  	<td colspan="2">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td colspan="7" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="12%" class="table_tit"></td>
					<td class="table_tit">구매상품정보</td>
					<td width="12%" class="table_tit">상품가격</td>
					<td width="12%" class="table_tit">수량</td>
					<?if($oper_info['reserve_use'] == "Y"){?>
					<td width="12%" class="table_tit">적립금</td>
					<?}?>
					<td width="12%" class="table_tit">합계</td>
					<td width="12%" class="table_tit">기능</td>
				</tr>
				<tr>
					<td colspan="7" bgcolor="#d7d7d7" height="1"></td>
				</tr>
				<?php
				$basket_exist = false;
				$no = 0;

				/** product_idx **/
				if(!empty($product_idx))
				{
					$product_idx_val = explode("|", $product_idx);
				
					foreach($product_idx_val as $key => $value){
						if(!empty($value)) $_product_idx .= " OR wb.idx='{$value}'";
					}
				
					$_product_idx = substr($_product_idx,3);
					$product_idx_sql = " and ({$_product_idx})";
				} else {
					$product_idx_sql = "";
				}

				$sql = "
					SELECT wb.*
						 , wp.del_type
						 , wp.del_price
						 , wp.shortage
						 , wp.stock
					  FROM wiz_basket_tmp as wb
					  LEFT JOIN wiz_product as wp
					    on wb.prdcode = wp.prdcode
					 WHERE wb.uniq_id='".$_uniq_id."'
					   $product_idx_sql
					 ORDER BY idx DESC
				";
				$btresult = query($sql);
				$cp=0;
				while($brow = sql_fetch_arr($btresult)){
					if($brow['amount'] < 1) continue;

					if($cp == 0){$cp_prdcode = $brow['prdcode'];
					}else{$cp_prdcode.="|".$brow['prdcode'];} //2020-05-27
					$basket_exist     = true;
					$optcode          = "";
					$prdimg           = "";
					$del_type         = "";
					$prd_d_price      = $brow['prdprice'] * $brow['amount'];
					$reserve_d_price  = $brow['prdreserve'] * $brow['amount'];

					$t_optcode        = $brow['optcode'];
					$t_opttitle       = $brow['opttitle'];

					$t_optcode2 = $t_optcode3 = $t_optcode4 = $t_optcode5 = $t_optcode6 = $t_optcode7 = $t_optcode8 = $t_optcode9 = $t_optcode10 = $t_optcode11 = $t_optcode12 = $t_optcode13 = "";
					$t_opttitle2 = $t_opttitle3 = $t_opttitle4 = $t_opttitle5 = $t_opttitle6 = $t_opttitle7 = $t_opttitle8 = $t_opttitle9 = $t_opttitle10 = $t_opttitle11 = $t_opttitle12 = $t_opttitle13 = "";
					for($o=2; $o<=13; $o++) {
						${'t_optcode'.$o}  .= $brow['optcode'.$o];
						${'t_opttitle'.$o} .= $brow['opttitle'.$o];
					}

					if(
						strpos($brow['optcode'],"&&")   !== false || strpos($brow['optcode2'],"&&") !== false || strpos($brow['optcode3'],"&&")  !== false || strpos($brow['optcode4'],"&&") !== false || strpos($brow['optcode5'],"&&")  !== false || strpos($brow['optcode6'],"&&") !== false || strpos($brow['optcode7'],"&&")  !== false || strpos($brow['optcode8'],"&&") !== false || strpos($brow['optcode9'],"&&")  !== false || strpos($brow['optcode10'],"&&") !== false || strpos($brow['optcode11'],"&&") !== false
						)
					{
						$prd_price       += ($brow['prdprice'] * $brow['amount']);
					} else {
						$prd_price       += ($brow['prdprice'] * $brow['amount']);
					}

					$opt = $opt3 = $opt5 = $opt6 = $opt7 = $opt8 = $opt9 = $opt10 = $opt11 = $opt12 = $opt13 = "";

					$prdname      = "<span class='basket_name'>".$brow['prdname']."</span>";

					// 상품 이미지
					if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$brow['prdimg'])) 
						$prdimg = "/twcenter/images/noimg_S.gif";
					else $prdimg = "/twcenter/data/prdimg/".$brow['prdimg'];

					include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_option_inc.php";
					
					if(!empty($brow['del_type']) && strcmp($brow['del_type'], "DA")) {
						if(!strcmp($brow['del_type'], "DC")) $del_type = "<br>(".deliver_name_prd($brow['del_type'])." : ".number_format($brow['del_price'])."원)";
						else $del_type = "<br>(".deliver_name_prd($brow['del_type']).")";
					}

					$del_type = "<span class='pay_add_tit'>".$del_type."</span>";
	
					$c_sql = "
						select wc.purl
						  from wiz_product as wp 
						  left join wiz_cprelation as wcp 
						    on wp.prdcode = wcp.prdcode
						  left join wiz_category as wc on wcp.catcode = wc.catcode
						 where wp.prdcode = '".$brow['prdcode']."'
					";
					$c_row = sql_fetch($c_sql);

					$prd_view_page = "/".$c_row['purl']."?ptype=view&prdcode=".$brow['prdcode'];

					## 옵션항목이 있을때만 노출
					if(	$brow['opttitle']   != "" || $brow['opttitle2']  != "" || 
						$brow['opttitle3']  != "" || $brow['opttitle4']  != "" || 
						$brow['opttitle5']  != "" || $brow['opttitle6']  != "" || 
						$brow['opttitle7']  != "" || $brow['opttitle8']  != "" || 
						$brow['opttitle9']  != "" || $brow['opttitle10']  != "" || 
						$brow['opttitle11'] != ""){

						//$choice_chg = "<br><span class='opt_chg opt_choice pointer btn_option_add'>+ 선택사항 옵션추가</span>";

					} else {
						$choice_chg = "<span class='opt_chg opt_choice'></span>";
					}

				?>
				<form action="/twcenter/product/prd_save.php" method="post" onsubmit="return checkForm(this);">
				<input type="hidden" name="mode"  value="update">
				<input type="hidden" name="idx"   value="<?=$brow['idx']?>">
				<input type="hidden" name="ptype" value="form">
				<input type="hidden" name="pagetype" value="<?=$PHP_SELF?>">
				<input type="hidden" name="prdcode" value="<?=$brow['prdcode']?>" id="prdcode_<?=$no?>">
				<input type="hidden" name="product_idx" value="<?=$product_idx?>">

				<tr>
					<td style="padding:10px 5px" align=center><a href="<?=$prd_view_page?>" target="prdview"><img src="<?=$prdimg?>" width="72" border="0"></a></td>
					<td style="padding:8px 0">
						<a href="<?=$prd_view_page?>" target="prdview"><?=$prdname?></a>
						<p><?=$optcode?><?=$del_type?><?=$choice_chg?></p>
					</td>
					<td class="bprice" align=center><?=number_format($brow['prdprice'])?>원</td>
					<td align=center>
					<?
					if(
						strpos($brow['optcode'],"&&") !== false  || strpos($brow['optcode2'],"&&") !== false || strpos($brow['optcode3'],"&&") !== false || strpos($brow['optcode4'],"&&") !== false || strpos($brow['optcode5'],"&&") !== false || strpos($brow['optcode6'],"&&") !== false || strpos($brow['optcode7'],"&&") !== false || strpos($brow['optcode8'],"&&") !== false || strpos($brow['optcode9'],"&&") !== false || strpos($brow['optcode10'],"&&") !== false || strpos($brow['optcode11'],"&&") !== false
						)
					{
					?>
						<table border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td valign=top>
									<table border=0 cellpadding=0 cellspacing=0>
									<tr>
										<td rowspan=3><input type=text name="amount" id="amount_<?=$no?>" value="<?=$brow['amount']?>" size=2 class="input" readonly style="text-align:right;">&nbsp;</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>

					<? } else { ?>
						<table border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td valign=top>
									<table border=0 cellpadding=0 cellspacing=0>
									<tr>
										<td><a href="javascript:decAmount('<?=$no?>');"><img src="<?=$skin_dir?>/image/but_vol_down2.gif" border=0></a></td>
										<td><input type=text name="amount" id="amount_<?=$no?>" value="<?=$brow['amount']?>" size=2 class="vol_input"></td>
										<td><a href="javascript:incAmount('<?=$no?>');"><img src="<?=$skin_dir?>/image/but_vol_up2.gif" border=0></a></td>
									</tr>
									</table>
								</td>
							</tr>
							<tr><td style="padding-top:1px;"><input type="submit" value="수정" class="btn_type1"></td></tr>
						</table>
					<? } ?>
					</td>

					<?if($oper_info['reserve_use'] == "Y"){?>
					<td align=center><?=number_format($reserve_d_price)?>원</td>
					<?}?>
					<td class="bprice" align=center><?=number_format($prd_d_price)?>원</td>
					<!-- <td align=center>
						<a href="javascript:addWish('<?=$brow['idx']?>')"><img src="<?=$skin_dir?>/image/but_cart_custody.gif" border=0></a><br>
						<a href="/twcenter/product/prd_save.php?mode=delete&ptype=form&pagetype=<?=$PHP_SELF?>&idx=<?=$brow['idx']?>"><img src="<?=$skin_dir?>/image/but_cart_del.gif" border=0></a>
					</td> -->
					<td align="center">
						<a href="/twcenter/product/prd_save.php?mode=delete_form&ptype=form&pagetype=<?=$PHP_SELF?>&idx=<?=$brow['idx']?>&product_idx=<?=$product_idx?>"><input type="button" value="삭제" class="btn_type1"></a>
					</td>
				</tr>
				<tr><td colspan=7 height=1 bgcolor='#dddddd'></td></tr>
				</form>
				<?
				$no++;
				$cp++;
				}

				if(!$basket_exist){
					echo "<tr><td colspan=7 height=30 align=center>장바구니가 비어있습니다.</td></tr>";
				}

				// 회원할인 [$discount_msg 메세지 생성]
				$discount_price      = level_discount($wiz_session['level'],$prd_price);

				// 배송비
				$deliver_price = deliver_price($prd_price, $oper_info);
				$deliver_price_view = deliver_price2($oper_info['del_method'], $deliver_price);
				if($deliver_price_view == "착불") {
					$deliver_price_view = "<img src='".$skin_dir."/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>0</span> 원";
				} else {
					$deliver_price_view = "<img src='".$skin_dir."/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>".number_format($deliver_price)."</span> 원";
				}

				// 전체결제금액
				$total_price = $prd_price + $deliver_price - $discount_price;

			?>
			</table>

  	</td>
	</tr>
	<tr>
	  <td colspan="2">
		<div class="delivery_box">
			<div class="one"><p class="tb2">[배송비]</p><?=$deliver_msg?></div>
			<div class="two">
				<p class="paytxt">
					상품&nbsp;&nbsp;<span class="tbold"><?=number_format($prd_price)?></span> 원 
					<?php echo $discount_msg ?> 
					<?php echo $discount_price_view ?>
					<?php echo $deliver_price_view ?>
					<p class="paytxt2">주문합계&nbsp;&nbsp;&nbsp;<span class="price_t1"><?=number_format($total_price)?></span> 원
				</p>
			</div>
		</div>
	  </td>
	</tr>

</table>
<div id="OptSel"></div>
<script language="JavaScript">
<!--
function goOrder(){
<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else echo "document.location='/".$prd_info['order_url']."';";	
?>
}

function printEstimate(){
	var uri = "/twcenter/product/print_estimate.php";
	window.open(uri, "printEstimate", "width=667,height=600,scrollbars=yes, top=30, left=50");
}

$(function(){

	$(".opt_choice").click(function(){
		try {
			var idx     = $('span.opt_choice').index($(this));
			var pos     = $(this).position();
			var prdcode = $('#prdcode_'+idx).val();
			var $OptSel = $('#OptSel');

			$('#OptSel').load('/twcenter/product/opt_change.php',{ prdcode: prdcode });

			var top = pos.top-250;
			var left = pos.left;

			$OptSel.css("top", top).css("left", left).show();
		}
		finally {
			idx = top = left = null;
		}

	});

});
-->
</script>
<style type="text/css">
	#OptSel { position: absolute; top: 0; left: 0; display: none; width: 600px; z-index: 1000; background-color:#fff; padding:15px; border:2px solid #000; }
</style>
