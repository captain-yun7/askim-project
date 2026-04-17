<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;

///////////////////////////////////////////
/// PG사 결제 완료시 꼭 반환되어야할 값들//
///////////////////////////////////////////
/* $orderid : 주문번호
/* $resmsg : 오류 및 반환 메세지
/* $rescode : 성공반환 메세지
/* $pay_method : 결제종류
*//////////////////////////////////////////
if($oper_info['kakao_pay_use'] == 'Y' && $pay_method == 'KK'){
	$presult = Pay_result("KAKAOPAY",$rescode);
} else {
	$presult = Pay_result($oper_info['pay_agent'],$rescode);
}

/* -- ------------------------------------------------------------------------- -- *\
 * 10일이 지난 Garbage 장바구니 데이터 삭제처리
\* -- ------------------------------------------------------------------------- -- */
query("DELETE FROM wiz_basket_tmp WHERE wdate < (now()- INTERVAL 10 DAY)");

?>

<script language="JavaScript">
<!--
function orderPrint(orderid){
	var url = "/comm/common/order_print.php?orderid=" + orderid + "&print=ok";
	window.open(url, "orderPrint", "height=650, width=736, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>

<div class="shop_level">
	<ul>
		<li><p class="step">STEP1</p><p class="txt">장바구니</p></li>
		<li><p class="step">STEP2</p><p class="txt">주문하기</p></li>
		<li><p class="step">STEP3</p><p class="txt">결제하기</p></li>
		<li class="hover"><p class="step" >STEP4</p><p class="txt">주문완료</p></li>
	</ul>
</div>

<table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
  <tr>
    <td style="padding-top:20px;">
			<?php

			// 주문정보
			$sql = "SELECT * FROM wiz_order WHERE orderid = '{$presult['orderid']}'";
			$order_info = sql_fetch($sql);
			if(!$status) $status = $order_info['status'];

			// 주문성공
			if($presult['rescode'] == "0000" && strlen($presult['rescode']) == 4){

				// 주문완료 메일/sms발송
				include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_mail.php";		// 메일발송내용

				$re_info['name']   = $order_info['send_name'];
				$re_info['email']  = $order_info['send_email'];
				$re_info['hphone'] = $order_info['send_hphone'];

				/* -- -------------------------------------------------------------------------------- *\
				 * 알림톡 주문정보 공통
				\* -- -------------------------------------------------------------------------------- */
				$sql = "select count(orderid) as ordCnt, sum(amount) as ordAmt from wiz_basket where orderid= '{$presult['orderid']}'";
				$result = query($sql);
				$row = sql_fetch_arr($result);

				$total     = $row['ordCnt'];
				$total_sum = $row['ordAmt'];

				$bbsql = "select * from wiz_basket where orderid = '{$presult['orderid']}'";
				$bbresult = query($bbsql);

				while($bbrow = sql_fetch_obj($bbresult)){

					$prdname = "";
					$options = "";
					$amounts = "";

					if($total > 1){
						$payment_prdname = $bbrow->prdname." 외 ".($total-1)."개";
					}else{
						$payment_prdname = $bbrow->prdname;
					}

					$prdnames   = $payment_prdname."\n(총 주문수량 : ".$total_sum.")";

				}

				// email, sms 발송 체크
				$sql = "update wiz_order set send_mailsms = 'Y' where orderid = '{$order_info['orderid']}'";
				query($sql) or error("sql error");

				if($order_info['send_mailsms'] != "Y") {

					if($site_info['alimtalk_use'] == 'Y' && $site_info['alimtalk_senderkey']) {

						$templateCode = "";

						$talk_info['prdname']    = $prdnames;
						$talk_info['prdoption']  = $options;
						$talk_info['prdamount']  = $amounts;
						$talk_info['name']       = $order_info['send_name'];
						$talk_info['email']      = $order_info['send_email'];
						$talk_info['hphone']     = $order_info['send_hphone'];

						$return_code = send_alimtalk($templateCode,$talk_info);

						if($return_code == 'AS') $rtype = "E";
						else $rtype = "";
					}

					if($presult['pay_method'] == 'PC' || $presult['pay_method'] == 'PN' || $presult['pay_method'] == 'PH') {
						send_mailsms("order_pay", $re_info, $ordmail, $rtype);
					} else {
						send_mailsms("order_com", $re_info, $ordmail, $rtype);
					}
				}

			?>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=10></td></tr>
			  <tr>
				<td align="center"><?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_info.php"; ?></td>
			  </tr>
			  <tr><td height=20></td></tr>
			  <tr><td align="center"><strong><font color=red>※</font> 비회원이실 경우 주문번호는 주문/배송조회시에 필요하니 꼭 기억해두시기 바랍니다.</strong></td></tr>
			  
				<tr>
					<td height="60" align="center" valign="bottom">
						<a href="/<?php echo $prd_info['order_list_url'] ?>"><input type="submit" value="주문 조회 가기" class="btn_style2"></a>
						<input type="button" value="프린트 하기" onClick="javascript:orderPrint('<?php echo $presult['orderid'] ?>');" class="btn_style1" >
					</td>
				</tr>
			</table>
			<?php

			// 주문실패
			}else{
			?>
			<table border=0 cellpadding=0 cellspacing=0 width=96%>
				<tr><td height=3 bgcolor=#999999></td></tr>
				<tr>
					<td bgcolor=#F9F9F9 style="padding:10">

						<table border=1 cellpadding=0 cellspacing=2 bgcolor=#ffffff bordercolor=#E1E1E1 width=100%>
						  <tr>
						    <td style="padding:5">
							   <table width=100% border=0 cellpadding=0 cellspacing=0>
								  <tr height=25><td align="center"><font color=red><b>결제시 에러가 발생하였습니다.</b></font></td></tr>
								  <tr height=25><td align="center">결과메세지 : <?php echo $presult['resmsg'] ?></td></tr>
								  <tr><td height=20></td></tr>
								  <tr><td height=1 background="/images/dot.gif"></td></tr>
								  <tr>
									<td align="center" height=80>
										<a href="<?php echo $_SERVER['PHP_SELF'] ?>?ptype=pay&orderid=<?php echo $presult['orderid'] ?>&pay_method=<?php echo $order_info['pay_method'] ?>"><input type="button" value="다시결제" class="btn_style2"></a>
									</td>
								  </tr>
								</table>
						    </td>
						  </tr>
						</table>

				  </td>
				</tr>
		  </table>
			<?php
			}
			?>
		</td>
  </tr>
</table>