<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";
basketCheckAmount($uniq_id);

unset($_SESSION['basketidx']);

if($oper_info['nhn_chkout_use'] == "Y" && $oper_info['nhn_host_chk'] == "T"){
	echo "<script type=\"text/javascript\" src=\"//test-pay.naver.com/customer/js/naverPayButton.js\" charset=\"UTF-8\"></script>";
} else if($oper_info['nhn_chkout_use'] == "Y" && $oper_info['nhn_host_chk'] == "S") {
	echo "<script type=\"text/javascript\" src=\"//pay.naver.com/customer/js/naverPayButton.js\" charset=\"UTF-8\"></script>";
}

if($oper_info['nhn_chkout_use'] == "Y"){

?>
<script type="text/javascript">

function buy_nc(url) {

	<?php
	if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
	?>
		alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
		return false;
	<?php
	} else {
	?>
		var check = true;
		if ( check ) {
			window.open("../twcenter/product/nhn_order.php","CheckOutOrder");
		}
		return false;
	<?php } ?>
}
function wishlist_nc(url) {
	<?php
	if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
	?>
		alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
		return false;
	<?php
	} else {
	?>

		window.open(url,"","scrollbars=yes,width=400,height=267");
		return false;

	<?php } ?>
}
function not_buy_nc() {

	<?php
	if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
	?>
		alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
		return false;
	<?php
	} else {
	?>
		alert("죄송합니다. NAVER Checkout으로 구매가 불가한 제품입니다.");
		return false;
	<?php } ?>
}
</script>
<?php
}
?>

<div class="shop_level">
	<ul>
		<li class="hover"><p class="step">STEP1</p><p class="txt">장바구니</p></li>
		<li><p class="step">STEP2</p><p class="txt">주문하기</p></li>
		<li><p class="step">STEP3</p><p class="txt">결제하기</p></li>
		<li><p class="step" >STEP4</p><p class="txt">주문완료</p></li>
	</ul>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
		<td colspan="2">
			<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/basket_list.php"; ?>
		</td>
	</tr>
	<tr>
		<td>
			<div class="btnbox">
				<input type="button" value="견적서 출력" onClick="javascript:printEstimate();" class="btn_style1">
				<?
				/*
				작업자		: 임서연
				작업일시	: 2020-03-05
				작업내용	: 장바구니 비우기 버튼 미작동 오류 수정(수정 반영 작업)
				*/
				?>
				<input type="button" value="장바구니 비우기" class="btn_style1" onclick='location.href="/twcenter/product/prd_save.php?mode=delall"'>
			</div>
		</td>
		<td align="right">
			<div class="btnbox">
				<input type="button" value="선택상품 주문하기" onClick="javascript:selOrder();" class="btn_style1">
				<input type="button" value="전체상품 주문하기" onClick="javascript:allOrder();" class="btn_style2">
			</div>
		</td>
	</tr>
</table>
<?php
if($oper_info['nhn_chkout_use'] == "Y"){
	if($basket_exist == true) {
	
		$ENABLE = "Y";
		if($basket_exist != true) $ENABLE = "N";
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding:15px 7px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right">
					<script type="text/javascript" >//<![CDATA[
							naver.NaverPayButton.apply({
							BUTTON_KEY:"<?php echo $oper_info['nhn_chkout_key'] ?>", //①
							TYPE: "A",									  //② 
							COLOR: 1,									  //③
							COUNT: 1,									  //④
							ENABLE: "<?php echo $ENABLE ?>",			  //⑤
							BUY_BUTTON_HANDLER: buy_nc,					  //⑥
							BUY_BUTTON_LINK_URL:"",						  //⑦
							WISHLIST_BUTTON_HANDLER:wishlist_nc,		  //⑧
							WISHLIST_BUTTON_LINK_URL:"<?php echo WAY_HOST ?>/twcenter/product/nhn_wish.php?prdcode=<?php echo $prdcode ?>", //⑨
							"":""
						});
					//]]></script>

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
	}
}
?>
