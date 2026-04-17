<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>:: 쿠폰목록 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
<meta name="viewport" content="width=device-width" />
<meta name="robots" content="noindex,nofollow" />
<link rel="stylesheet" type="text/css" href="/wiz_style.css">
<script src="//code.jquery.com/jquery-1.12.4.js"></script>	<!-- 기본 제이쿼리 -->	
<script type='text/javascript' src='/comm/js/script.js?v=<?php echo time(); ?>'></script>
<script language="JavaScript">
<!--
function couponCheck(frm){

	if(frm.coupon_check.checked == true){
		if(frm.prd_exsist.value == "false"){
			frm.coupon_check.checked = false;
			alert("장바구니에 해당 상품이 없습니다.");
		}else{
			setCoupon();
		}
	}

}

function setCoupon(){

	var i;
	var discount_price = 0;
	var coupon_price_limit = 0;
	var coupon_idx = "";
	var total_prdprice = Number( (document.getElementById("total_prdprice").value != '') ? document.getElementById("total_prdprice").value : "0" );
	console.log(total_prdprice);
try
{
	
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].id != null){
			if(document.forms[i].coupon_check){
				if(document.forms[i].coupon_check.checked){
					if(eval('document.forms['+i+'].coupon_price_limit') != null) {
						coupon_price_limit = document.forms[i].coupon_price_limit.value;
					}
					discount_price = discount_price + (document.forms[i].discount_price.value*1);
					coupon_idx = coupon_idx + document.forms[i].idx.value + "|";
				} 
			}
		}
	}
	if(coupon_price_limit != '' && coupon_price_limit > 0 && total_prdprice < coupon_price_limit) {
		alert(won_Comma(coupon_price_limit) + "원 이상 주문 시 사용가능합니다.");
		for(i=0;i<document.forms.length;i++){
			document.forms[i].coupon_check.checked = false;
		}
	} else if (total_prdprice < discount_price){
		alert("할인액이 주문액보다 큽니다.");
		for(i=0;i<document.forms.length;i++){
			document.forms[i].coupon_check.checked = false;
		}
	} else {
		opener.frm.coupon_use.value = discount_price;
		opener.frm.coupon_idx.value = coupon_idx;

		alert("적용되었습니다.");
		self.close();
	}
}
catch (e)
{
	console.log(e);
}

}
//-->
</script>
</head>

<body topmargin="0" leftmargin="0">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:6px solid #979797;">
	<tr>
	  <td width="351" height="54" style="padding-left:15px;" class="coutit">쿠폰목록</td>
	  <td width="60" align="right" style="padding-right:15px;"><a href="javascript:window.close();"><img src="<?=$skin_dir?>/image/id_check_close.gif" width="21" height="21" border="0"></a></td>
	</tr>
	<tr><td colspan="2" height="1" bgcolor="#d2d2d2"></td></tr>
	<tr>
	  <td colspan="2" align="center" valign="top" style="padding-top:10px">

	  	<table border="0" width="96%" align="center">
				<tr><td class="coustit"><span>상품할인</span> 쿠폰</td></tr>
			</table>
	  	<table border="0" width="96%" align="center" class="cupoon_table">
				<tr bgcolor="#f9f9f9">
					<th width="8%" height="35" align="center">번호</th>
					<th align="center">상품명</th>
					<th width="13%" align="right">가격</th>
					<th width="13%" align="right">쿠폰</th>
					<th width="10%" align="right">수량</th>
					<th width="13%" align="right">할인액</th>
					<th width="13%" align="right">쿠폰적용가</th>
					<th width="10%" align="center">사용</th>
				</tr>
				<?
				$today = date('Y-m-d');
				$memid = $wiz_session['id'];
				//$basket_list = $HTTP_SESSION_VARS["basket_list"];
				//$basket_cnt = count($basket_list);

				/*
				작업자명	: 이상민
				작업일시	: 2020-10-14
				작업내용	: 바로구매 또는 장바구니 일부 구매 시 금액계산오류 수정
				*/
				if($product_idx != ""){
					if(substr($product_idx, -1) == "|"){
						$product_idx = substr($product_idx, 0, -1);
					}

					$sql_common = " and idx in ('".preg_replace("/\|/i", "','", $product_idx)."') ";
				}

				$sql = "select distinct prdcode, prdprice, amount from wiz_basket_tmp where uniq_id='".$_uniq_id."' ".$sql_common;
				$result = query($sql) or error("sql error");
				$no = 0;
				$total_prdprice = 0;

				while($row = sql_fetch_arr($result)) {

					$basket_list[$no]['prdcode'] = $row['prdcode'];
					$basket_list[$no]['prdprice'] = $row['prdprice'];
					$basket_list[$no]['amount'] = $row['amount'];

					$total_prdprice += $row['prdprice'] * $row['amount'];

					$no++;
				}

				$basket_cnt = count($basket_list);

				$pdcode = explode("|", $prdcode);

				$sql = "select wc.*, wp.prdname, wp.sellprice from wiz_mycoupon wc, wiz_product wp where wc.memid='$memid' and wc.prdcode != '' and wc.coupon_use = 'N' and wc.coupon_sdate <= '$today' and wc.coupon_edate >= '$today' and wc.prdcode = wp.prdcode order by wc.idx desc";
				$result = query($sql) or error("sql error");
				$total = sql_fetch_row($result);
				$no = $total;
				while($row = sql_fetch_obj($result)){

					$prd_amount = 0;
					$prd_exsist = "false";
					for($ii = 0; $ii < $basket_cnt; $ii++){
						if($pdcode[$ii] == $row->prdcode){
							$prd_amount += $basket_list[$ii]['amount'];
							//$row->sellprice = $basket_list[$ii][prdprice];	// 옵션추가금액
							$prd_exsist = "true";
							//break;
						}
					}
					if($prd_amount == 0) $prd_amount = 1;
					if($prd_amount > 1) $prd_amount = 1;

					if($row->coupon_type == "%"){
						$discount_price = $row->sellprice*($row->coupon_dis/100);
					}else{
						$discount_price = $row->coupon_dis;
					}
					$coupon_price = $row->sellprice - $discount_price;

					$discount_price = $discount_price * $prd_amount;
					$coupon_price = $coupon_price * $prd_amount;

				?>
			  <form>
			  <input type="hidden" name="idx" value="<?=$row->idx?>">
			  <input type="hidden" name="prd_exsist" value="<?=$prd_exsist?>">
			  <input type="hidden" name="discount_price" value="<?=$discount_price?>">
				<tr>
					<td align="center" height="25"><?=$no?></td>
					<td><?=$row->prdname?><?=$basket_prd?></td>
					<td align="right"><?=number_format($row->sellprice)?>원</td>
					<td align="right"><?=$row->coupon_dis?><?=$row->coupon_type?></td>
					<td align="right"><?=number_format($prd_amount)?></td>
					<td align="right"><?=number_format($discount_price)?>원</td>
					<td align="right"><?=number_format($coupon_price)?>원</td>
					<td align="center"><input type="checkbox" name="coupon_check" value="<?=$prd_exsist?>" onClick="couponCheck(this.form)"></td>
				</tr>
				<tr><td colspan=10 style="border-bottom:1px solid #ddd;"></td></tr>
			  </form>
				<?
					$no--;
				}
				if($total <= 0){
				?>
				<tr><td colspan=10 height="35" align="center">등록된 쿠폰이 없습니다.</td></tr>
				<tr><td colspan=10 style="border-bottom:1px solid #ddd;"></td></tr>
				<?
				}
				?>
			</table>
			<br>
			<?
			$basket_cnt = count($basket_list);
			for($ii = 0; $ii < $basket_cnt; $ii++){
				if($basket_list[$ii] != null){
					$prd_price += ($basket_list[$ii]['prdprice'] * $basket_list[$ii]['amount']);
				}
			}
			?>

			<table border="0" width="96%" align="center">
				<tr><td class="coustit"><span>이벤트</span> 쿠폰</td></tr>
			</table>
			<input type="hidden" name="total_prdprice" id="total_prdprice" value="<?=$total_prdprice?>">
			<table border="0" width="96%" align="center" class="cupoon_table">
				<tr bgcolor="#f9f9f9">
					<th height="35" width="8%" align="center">번호</th>
					<th align="center">쿠폰명</th>
					<th width="30%" align="center">기간</th>
					<th width="10%" align="center">할인액</th>
					<th width="10%" align="center">사용</th>
				</tr>
				<?
				$sql = "select wc.* from wiz_mycoupon wc where wc.memid='$memid' and wc.eventidx != '' and wc.coupon_use = 'N' and wc.coupon_sdate <= '$today' and wc.coupon_edate >= '$today' order by wc.idx desc";
				$result = query($sql) or error("sql error");
				$total = sql_fetch_row($result);
				$no = $total;
				while($row = sql_fetch_obj($result)){

					if($row->coupon_type == "%"){
						$discount_price = $prd_price*($row->coupon_dis/100);
					}else{
						$discount_price = $row->coupon_dis;
					}
				?>
			  <form>
			  <input type="hidden" name="idx" value="<?=$row->idx?>">
			  <input type="hidden" name="prd_exsist" value="<?=$prd_exsist?>">
			  <input type="hidden" name="discount_price" value="<?=$discount_price?>">
			  <input type="hidden" name="coupon_price_limit" value="<?=$row->coupon_price_limit?>">
				<tr>
					<td height="25" align="center"><?=$no?></td>
					<td><?=$row->coupon_name?><?if($row->coupon_price_limit) echo "<br>(".@number_format($row->coupon_price_limit)."원 이상 사용가능)";?></td>
					<td><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
					<td align="center"><?=@number_format($row->coupon_dis)?><?=$row->coupon_type?></td>
					<td align="center"><input type="checkbox" name="coupon_check" onClick="setCoupon();"></td>
				</tr>
				<tr><td colspan=10 style="border-bottom:1px solid #ddd;"></td></tr>
				</form>
				<?
					$no--;
				}
				if($total <= 0){
				?>
			  <tr><td colspan=10 height="35" align="center">등록된 쿠폰이 없습니다.</td></tr>
				<tr><td colspan=10 style="border-bottom:1px solid #ddd;"></td></tr>
				<?
				}
				?>
			</table>

	  </td>
	</tr>
</table>
</body>
</html>