<?
	switch($order_info->pay_method) {
		case "PC" :
			$t_pay_method = "카드";
			break;
		case "PV" :
			$t_pay_method = "가상계좌";
			break;		
		case "PN" :
			$t_pay_method = "계좌이체";
			break;	
		case "PH" :
			$t_pay_method = "휴대폰";
			break;
	}
	if($order_info->tax_type =='C' || $order_info->tax_type == 'T') { 
		$sql_tax = "select * from wiz_tax where orderid='$orderid'";
		$row_tax = sql_fetch($sql_tax);
		$cash_type = $row_tax['cash_type'];  // P:소득공제, C:지출증빙
	}
	if(preg_match("/MSIE */", $_SERVER["HTTP_USER_AGENT"])) {
		echo "<script>alert('인터넷 익스플로러 10 이하 버전에서는 결제가 정상적으로 이루어지지 않습니다.\\n다른 브라우저를 이용하시거나 브라우저를 최신 버전으로 업데이트 해주세요.');</script>";
	} else {
		echo '<script src="https://js.tosspayments.com/v1"></script>';
	}
?>
<script language="javascript">
function launchCrossPlatform() {

	var clientKey = '<?=$oper_info['pay_id']?>';
	var tossPayments = TossPayments(clientKey);
try
{
	
	tossPayments.requestPayment('<?=$t_pay_method?>', {
	  amount: <?=floor($order_info->total_price)?>,
	  orderId: '<?=$orderid?>',
	  orderName: '<?=strip_tags($payment_prdname)?>',
	  customerName: '<?=$order_info->send_name?>',
	  customerEmail: '<?=$order_info->send_email?>',
<?// if($order_info->pay_method == 'PC') { ?>
	  customerMobilePhone : '<?=str_replace("-", "", $order_info->send_hphone)?>',
<? if($order_info->pay_method == 'PV') { ?>
	  //virtualAccountCallbackUrl : window.location.origin + '/twcenter/product/toss/order_update_vir.php',
	  useEscrow : <? echo ($oper_info['pay_escrow'] == 'Y') ? 'true' : 'false' ?>,
<? } ?>
	  successUrl: window.location.origin + '/twcenter/product/toss/order_update.php',
	  failUrl: window.location.origin + '/twcenter/product/toss/fail.php'
	});

}
catch (e)
{
	console.log("E");
}
}
</script>
<form method="post"  name="FRM_PAYINFO" id="FRM_PAYINFO">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding:15px 0px 30px 0px">
			<table border=0 cellpadding=0 cellspacing=0 width=100% class="order_form2">
				<tr>
					<th width="20%" class="table_tit2">결제방법</th>
					<td width="80%" class="val"><?=pay_method($pay_method)?></td>
				</tr>
				<tr>
					<th class="table_tit2">결제금액</th>
					<td class="val"><span class="price_a"><?=number_format($order_info->total_price ?? 0)?>원</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<input type="button" value="결제하기" onclick="launchCrossPlatform();" class="btn_style2">
			<a href="/"><input type="button" value="취소하기" class="btn_style1"></a>
		</td>
	</tr>
</table>
</form>
