<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
?>

<script language="javascript">
function getTxnId() {
	// form에 iframe 주소 세팅
	document.payForm.target = "txnIdGetterFrame";
	document.payForm.action = "/twcenter/product/kakaopay/pay_proc.php";
	document.payForm.acceptCharset = "utf-8";

	if (document.payForm.canHaveHTML) { // detect IE
		document.charset = payForm.acceptCharset;
	}
	
	// post로 iframe 페이지 호출
	document.payForm.submit();
	
}
</script>

<?//PRINT_R($_REQUEST)?>
<!--<BR><BR>order_info :<BR-->
<pre>
<?//print_r($order_info)?>
</pre>
<form name="payForm" id="payForm"  method="post" accept-charset = "">

<!-- 결제 파라미터 목록 -->
<input type="hidden" name="cid"				value="<?=$oper_info['kakao_mid']?>">				<!-- 가맹점 코드.        -->
<input type="hidden" name="cid_secret"        value="">			<!-- 가맹점 코드 인증키. 24자 숫자+영문 소문자         -->
<input type="hidden" name="partner_order_id"  value="<?=$orderid?>">				<!-- 가맹점 주문번호       -->
<input type="hidden" name="partner_user_id"   value="<?=$wiz_session['id']?>">	<!-- 가맹점 회원 id       -->
<input type="hidden" name="item_name"         value="<?=$payment_prdname?>">									<!-- 상품명       -->
<input type="hidden" name="item_code"         value="">		<!-- 상품코드     -->
<input type="hidden" name="quantity"          value="<?=$total?>">		<!-- 상품 수량        -->
<input type="hidden" name="total_amount"		value="<?=$order_info->total_price?>">		<!-- 상품 총액    -->
<input type="hidden" name="tax_free_amount"	value="">			<!-- 상품 비과세 금액       -->
<input type="hidden" name="vat_amount"		value="">			<!-- 상품 부가세 금액       -->
<input type="hidden" name="approval_url"		value="http://<?=$_SERVER['HTTP_HOST']?>/twcenter/product/kakaopay/pay_success.php">			<!-- 결제 성공시 redirect url       -->
<input type="hidden" name="cancel_url"		value="http://<?=$_SERVER['HTTP_HOST']?>/twcenter/product/kakaopay/pay_cancel.php">			<!-- 결제 취소시 redirect url     -->
<input type="hidden" name="fail_url"			value="http://<?=$_SERVER['HTTP_HOST']?>/twcenter/product/kakaopay/pay_fail.php">			<!-- 결제 실패시 redirect url  -->
<input type="hidden" name="available_cards"	value="">			<!-- 카드사 제한 목록(없을 경우 전체)        -->
<input type="hidden" name="payment_method_type"	value="">		<!-- 결제 수단 제한(없을 경우 전체) (CARD,MONEY중 하나)       -->
<input type="hidden" name="install_month"			value="">		<!-- 카드할부개월수. 0~12(개월) 사이의 값       -->
<input type="hidden" name="custom_json"			value="">		<!-- 결제화면에 보여주고 싶은 custom message. -->
<input type="hidden" name="ism"		value="<?=mobile_check()?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding:15px 0px 10px 0px">
			<table border=0 cellpadding=0 cellspacing=0 width=100% class="order_form">
				<tr><td colspan="2" bgcolor="#a9a9a9" height="2"></td></tr>
				<tr>
					<td width="20%" height="40" class="tit" style="padding:6px;">결제방법</td>
					<td width="80%" class="val"><?=pay_method($pay_method)?></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="d7d7d7" ></td>
				</tr>
				<tr>
					<td class="tit" height="40" style="padding:6px;">결제금액</td>
					<td class="val"><span class="price_a"><?=number_format($order_info->total_price)?>원</span></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="d7d7d7" ></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<!-- <a href="javascript:getTxnId()"><img src="/twcenter/product/image/but_pay.gif" border="0" /></a>
			<a href="/"><img src="/twcenter/product/image/but_cancel.gif" border="0" /></a> -->
			<input type="button" value="결제하기" class="btn_style2" onclick="javascript:getTxnId()">
			<input type="button" value="취소하기" class="btn_style1" onclick="location.href='/'">

		</td>
	</tr>
</table>
<!--getTxnId 응답-->
<input id="resultCode" type="hidden" value=""/>
<input id="resultMsg" type="hidden" value=""/>
<input id="txnId" type="hidden" value=""/>
<input id="prDt" type="hidden" value=""/>
<!-- DLP호출에 대한 응답 -->
<!--DLP 응답-->
<input type="hidden" name="SPU" value=""/>
<input type="hidden" name="SPU_SIGN_TOKEN" value=""/>
<input type="hidden" name="MPAY_PUB" value=""/>
<input type="hidden" name="NON_REP_TOKEN" value=""/>

</form>
<!-- TODO :  LayerPopup의 Target DIV 생성 -->
<div id="kakaopay_layer"  style="display: none"></div>
<iframe name="txnIdGetterFrame" id="txnIdGetterFrame" src="" width="500" height="300" style="display:none"></iframe>
