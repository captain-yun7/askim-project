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

//체크박스선택 반전
function onSelect(form){

	if(form.select_tmp.checked){
		selectAll();
	}
	else{
		selectEmpty();
	}

}

//체크박스 전체선택
function selectAll(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	total_price()
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				console.log(document.forms[i].select_checkbox.checked);
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	total_price()
	return;
}



function total_price(){

	var i;
	var prd_price = 0;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdprice != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					prd_price = parseInt(prd_price) + (parseInt(document.forms[i].prdprice.value) * parseInt(document.forms[i].prdamount.value)) ;
				}
			}
		}
	}

	$("#total_price").text(commaNum(prd_price)+" 원");

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
				<form>
				<tr><td colspan="7" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="6%" class="table_tit"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)" checked></td>
					<td width="12%" class="table_tit"></td>
					<td class="table_tit">구매상품정보</td>
					<td width="12%" class="table_tit">상품가격</td>
					<td width="12%" class="table_tit">수량</td>
					<?if($oper_info['reserve_use'] == "Y"){?>
					<td width="12%" class="table_tit">적립금</td>
					<?}?>
					<!-- <td width="12%">합 계</td> -->
					<td width="12%" class="table_tit">기능</td>
				</tr>
				</form>
				<tr>
					<td colspan="7" bgcolor="#d7d7d7" height="1"></td>
				</tr>
				<?
				$basket_exist = false;
				$no = 0;

				$sql = "
					SELECT wb.*
						 , wp.del_type
						 , wp.del_price
						 , wp.shortage
						 , wp.stock
					  FROM wiz_basket_tmp as wb
					  LEFT JOIN wiz_product as wp
					    ON wb.prdcode = wp.prdcode
					 WHERE wb.uniq_id = '".$_uniq_id."'
					   AND direct = 'basket'
					 ORDER BY idx DESC
				";
//				if($_SERVER['REMOTE_ADDR'] == "118.130.111.140") {
//					echo nl2br($sql);
//				}


				$btresult = query($sql);
				while($brow = sql_fetch_arr($btresult)){
				
					$basket_exist     = true;
					$optcode          = "";
					$prdimg           = "";
					$del_type         = "";
					$prd_d_price      = $brow['prdprice'] * $brow['amount'];
					$reserve_d_price  = $brow['prdreserve'] * $brow['amount'];

					$t_optcode        = $brow['optcode'];
					$t_opttitle       = $brow['opttitle'];
					for($o=2; $o<=13; $o++) {
						${"t_optcode".$o} = "";
						${"t_opttitle".$o} = "";
						${'t_optcode'.$o}  = $brow['optcode'.$o];
						${'t_opttitle'.$o} = $brow['opttitle'.$o];
					}

					$hasDoubleAmp = false;

					for ($i = 0; $i <= 13; $i++) {
					    $key = $i === 0 ? 'optcode' : "optcode{$i}";
					    if (isset($brow[$key]) && strpos($brow[$key], '&&') !== false) {
					        $hasDoubleAmp = true;
					        break;
					    }
					}

					if ($hasDoubleAmp) {
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
					if(	$brow['opttitle']  != "" || $brow['opttitle2']  != "" || 
						$brow['opttitle3'] != "" || $brow['opttitle4']  != "" || 
						$brow['opttitle5'] != "" || $brow['opttitle6']  != "" || 
						$brow['opttitle7'] != "" || $brow['opttitle8']  != "" || 
						$brow['opttitle9'] != "" || $brow['opttitle10'] != "" || 
						$brow['opttitle11'] != ""){

						//$choice_chg = "<br><span class='opt_chg opt_choice pointer btn_option_add'>+ 선택사항 옵션추가</span>";

					} else {
						$choice_chg = "<span class='opt_chg opt_choice'></span>";
					}
					$soldout = ($brow['shortage'] == "Y" || ($brow['shortage'] == "S" && $brow['stock'] < 1)) ? true : false;
				?>
				<form action="/twcenter/product/prd_save.php" method="post" onsubmit="return checkForm(this);">
				<input type="hidden" name="mode"  value="update">
				<input type="hidden" name="idx"   value="<?=$brow['idx']?>">
				<input type="hidden" name="ptype" value="form">
				<input type="hidden" name="pagetype" value="<?=$PHP_SELF?>">
				<input type="hidden" name="prdcode" value="<?=$brow['prdcode']?>" id="prdcode_<?=$no?>">
				<input type="hidden" name="prdprice" value="<?=$brow['prdprice']?>">
				<input type="hidden" name="prdamount" value="<?=$brow['amount']?>">

				<tr>
					<td align="center"><input type="checkbox" name="select_checkbox" onclick="total_price()"  <? echo ($soldout) ? "disabled" : "checked"?>></td>
					<td width="12%" style="padding:10px 0" align=center><a href="<?=$prd_view_page?>" target="_self"><img src="<?=$prdimg?>" width="72" border="0"></a></td>
					<td style="padding:8px 0">
						<?if($soldout) echo '<img src="/twcenter/images/icon_not.gif" border="0" align="absmiddle"><br>'; ?>
						<a href="<?=$prd_view_page?>" target="_self"><?=$prdname?></a>
						<p><?=$optcode?><?=$del_type?><?=$choice_chg?></p>
					</td>
					<td class="bprice" align=center><?=number_format($brow['prdprice'])?>원</td><?//주문가격에 장바구니에서 추가한 수량?>
					<td align=center style="padding:5px 0">
					<?
					if($soldout) {
						echo '-<input type="hidden" name="amount" id="amount_'.$no.'" value="'.$brow['amount'].'">';
					} else if(
						strpos($brow['optcode'],"&&") !== false || strpos($brow['optcode2'],"&&") !== false || strpos($brow['optcode3'],"&&") !== false || strpos($brow['optcode4'],"&&") !== false || strpos($brow['optcode5'],"&&") !== false || strpos($brow['optcode6'],"&&") !== false || strpos($brow['optcode7'],"&&") !== false || strpos($brow['optcode8'],"&&") !== false || strpos($brow['optcode9'],"&&") !== false || strpos($brow['optcode10'],"&&") !== false || strpos($brow['optcode11'],"&&") !== false
						)
					{
					?>
						<table border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td valign=top>
									<table border=0 cellpadding=0 cellspacing=0>
									<tr>
										<td rowspan=3><input type=text name="amount" id="amount_<?=$no?>" value="<?=$brow['amount']?>" size=2 class="input" style="text-align:right;">&nbsp;</td>
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
							<tr><td style="padding-top:1px;"><input type="submit" value="수정" class="btn_type1"><!-- <input type="image" src="<=$skin_dir>/image/but_modify.gif"> --></td></tr>
						</table>
					<? } ?>
					</td>

					<?if($oper_info['reserve_use'] == "Y"){?>
					<td align=center><?=number_format($reserve_d_price)?>원</td>
					<?}?>
					<!-- <td class="price" align=center><?=number_format($prd_d_price)?>원</td> -->
					<td align=center>
						<p style="margin-bottom:1px;"><input type="button" value="관심상품" onClick="javascript:addWish('<?=$brow['idx']?>')" class="btn_type1"></p>
						<?
						/*
						작업자		: 임서연
						작업일시		: 2020-03-05
						작업내용		: 장바구니 삭제버튼 미작동 오류 수정(수정 반영 작업)
						*/
						?>
						<input type="button" value="삭제" class="btn_type1" onclick='location.href="/twcenter/product/prd_save.php?mode=delete&ptype=form&pagetype=<?=$PHP_SELF?>&idx=<?=$brow['idx']?>"'>
						<!-- <a href="javascript:addWish('<=$brow['idx']?>')"><img src="<=$skin_dir>/image/but_cart_custody.gif" border=0></a><br>
						<a href="/twcenter/product/prd_save.php?mode=delete&ptype=form&pagetype=<=$PHP_SELF>&idx=<=$brow['idx']>"><img src="<=$skin_dir>/image/but_cart_del.gif" border=0></a> -->
					</td>
				</tr>
				<tr><td colspan=7 height=1 bgcolor='#E5E5E5'></td></tr>
				</form>
				<?
				$no++;
				}

				if(!$basket_exist){
					echo "<tr><td colspan=7 align=center style=padding:15px 0 5px 0>장바구니가 비어있습니다.</td></tr>";
				}

				// 회원할인 [$discount_msg 메세지 생성]
				$discount_price = level_discount($wiz_session['level'],$prd_price);

				// 배송비
				$deliver_price      = deliver_price($prd_price, $oper_info);
				$deliver_price_view = "<img src='".$skin_dir."/image/icon_plus2.gif'>배송비&nbsp;&nbsp;<span class='tbold'>".number_format($deliver_price)."</span> 원";

				// 전체결제금액
				$total_price = $prd_price + $deliver_price - $discount_price;
				//$total_price = $prd_price;
			?>
			</table>

  	</td>
	</tr>
	<tr>
	  <td colspan="2">
		<div class="delivery_box">
			<!-- <div class="one"><p class="tb">[배송비]</p><?=$deliver_msg?></div>
			<div class="two">주문합계&nbsp;&nbsp;&nbsp;<span class="price_t1"><?=number_format($total_price)?></span> 원</div> -->
			<div class="one"><p class="tb2">[배송비]</p><?=$deliver_msg?></div>
			<div class="two">
				<p class="paytxt">
					상품&nbsp;&nbsp;<span class="tbold"><?=number_format($prd_price ?? 0)?></span> 원 
					<?php echo $discount_msg ?> 
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
function selOrder(){

	var i;
	var product_idx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					product_idx = product_idx + document.forms[i].idx.value + "|";
				}
			}
		}
	}

	if(!product_idx){
		alert("주문할 상품을 선택하세요.");
		return;
	}

	var url = "/<?=$prd_info['order_url']?>?product_idx="+product_idx;
	document.location = url;

}

function allOrder(){

	selectAll();
	var i;
	var product_idx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					product_idx = product_idx + document.forms[i].idx.value + "|";
				}
			}
		}
	}

<?
	if(!$basket_exist){
		echo "alert('주문할 상품이 없습니다.');";
	} else {
?>
		product_idx = product_idx.slice(0, -1);
		var url = "/<?php echo $prd_info['order_url'] ?>?product_idx="+product_idx;
		document.location = url;
		/*var goURL = "/<?=$prd_info['order_url']?>";
		var $form = $("<form></form>");
			$form.attr("action",goURL);
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='product_idx' value="+ product_idx +">");
			$form.submit();*/

<?	} ?>

}

function printEstimate(){
	var basket_exist = '<?php echo $basket_exist ?>';
	if(!basket_exist) {
		alert("장바구니가 비어있습니다.");
		return false;
	}
	var product_idx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					product_idx = product_idx + document.forms[i].idx.value + "|";
				}
			}
		}
	}
	if(product_idx == '') {
		alert("견적서에 포함될 상품을 선택해주세요.");
	} else {
		var uri = "/twcenter/product/print_estimate.php?product_idx="+product_idx;
		window.open(uri, "printEstimate", "width=800,height=600,scrollbars=yes, top=30, left=50");
	}
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
	#OptSel { position: absolute; top: 0; left: 0; display: none; width: 600px; z-index: 1000; background-color:#fff; padding:15px 30px; border:2px solid #000; }
</style>
