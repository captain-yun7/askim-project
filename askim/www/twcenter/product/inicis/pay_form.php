<?php
require_once('libs/INIStdPayUtil.php');
$SignatureUtil = new INIStdPayUtil();

if(!strcmp($oper_info['pay_test'], "Y")) {
	$oper_info['pay_id']  = $oper_info['pay_id'];  										
	$oper_info['pay_key'] = $oper_info['pay_key'];
	
	echo '<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>';
}else{
	if(($pay_method=="PN" || $pay_method=="PV") && $oper_info['pay_escrow'] == "Y" && $oper_info['pay_id_escrow'] != "" && $oper_info['pay_key_escrow'] != ""){
		$oper_info['pay_id']  = $oper_info['pay_id_escrow'];	# 가맹점 에스크로 ID
		$oper_info['pay_key'] = $oper_info['pay_key_escrow'];	# 가맹점에 제공된 에스크로 키
	}else{
		$oper_info['pay_id']  = $oper_info['pay_id'];			# 가맹점 ID
		$oper_info['pay_key'] = $oper_info['pay_key'];			# 가맹점에 제공된 키
	}
	echo '<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>';
}
$mid		 = $oper_info['pay_id'];			# 가맹점 ID(가맹점 수정후 고정)				
$orderNumber = $order_info->orderid;			# 가맹점 주문번호(가맹점에서 직접 설정)
$price		 = $order_info->total_price;		# 상품가격(특수기호 제외, 가맹점에서 직접 설정)
$signKey	 = $oper_info['pay_key'];		    # 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
$timestamp   = $SignatureUtil->getTimestamp();  # util에 의해서 자동생성


###################################
## 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
###################################
$mKey 	     = $SignatureUtil->makeHash($signKey, "sha256");

/*
 **** 위변조 방지체크를 signature 생성 ***
 * oid, price, timestamp 3개의 키와 값을
 * key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값
 * ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004
 * key기준 알파벳 정렬
 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그데로 사용하여야함
 */
$params = array(
    "oid" => $orderNumber,
    "price" => $price,
    "timestamp" => $timestamp
);
$sign		= $SignatureUtil->makeSignature($params);
$http_host 	= $_SERVER['HTTP_HOST'];

// 결제타입
switch($pay_method){
	case "PC"://신용카드
		$_paymethod = "Card";
		break;
	case "PN"://계좌이체
		$_paymethod = "DirectBank";
		if($order_info->tax_type == "N"){
			$acceptmethod_val = "useescrow:no_receipt";
		} else {
			$acceptmethod_val = "useescrow";
		}
		$escrow_input = "<input type='hidden' name='acceptmethod' value='".$acceptmethod_val."'>";
		break;
	case "PV"://가상계좌
		$_paymethod = "VBank";
		if($order_info->tax_type == "N"){
			$acceptmethod_val = "useescrow:no_receipt";
		} else {
			$acceptmethod_val = "useescrow";
		}
		$escrow_input = "<input type='hidden' name='acceptmethod' value='".$acceptmethod_val."'>";
		break;
	case "PH";//휴대폰
		$_paymethod = "HPP";
		// HPP(1) : 컨텐츠구매 결제, HPP(2) : 실제상품구매 결제
		$hpp_input = "<input type='hidden' name='acceptmethod' value='HPP(2)'>";
		break;
}


/* 기타 */
// Ex) returnURL이 http://localhost:8082/demo/INIpayStdSample/INIStdPayReturn.jsp 라면
//                 http://localhost:8082/demo/INIpayStdSample 까지만 기입한다.
$siteDomain = WAY_HOST."/twcenter/product/inicis"; // 가맹점 도메인 입력

?>

        <script type="text/javascript">
            function paybtn() {
                INIStdPay.pay('SendPayForm_id');
            }
        </script>
    </head>
    <body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
		<form id="SendPayForm_id" name="" method="POST" >
				<!--***** 필 수 *****-->
				<input  type="hidden" name="version" value="1.0" >
				<input  type="hidden" name="mid" value="<?php echo $mid ?>" >
				<input  type="hidden" name="goodname" value="<?php echo $payment_prdname?>" >
				<input  type="hidden" name="oid" value="<?php echo $orderNumber ?>" >
				<input  type="hidden" name="price" value="<?php echo $price ?>" >
				<input  type="hidden" name="currency" value="WON" >				

				<input  type="hidden" name="buyername" value="<?php echo $order_info->send_name?>" >
				<input  type="hidden" name="buyertel" value="<?php echo $order_info->send_hphone?>" >
				<input  type="hidden" name="buyeremail" value="<?php echo $order_info->send_email?>" >

				<!--timestamp : -->
				<input type="hidden" name="timestamp" value="<?php echo $timestamp ?>" >

				<?php if($pay_method == "PC" && $price >= 50000 && $oper_info['card_quota_use'] == "Y") { ?>
				<input type="hidden" name="quotabase" value="<?php echo $oper_info['card_quotabase'] ?>" >
				<?php } ?>
				<!--signature: -->
				<input type="hidden"  name="signature" value="<?php echo $sign ?>" >
				<input type="hidden" name="returnUrl" value="<?php echo $siteDomain ?>/INIStdPayReturn.php" >
				<input type="hidden"  name="mKey" value="<?=$mKey?>" >
		  

				<!--***** 기본 옵션 *****-->
				<!--
				<option value="">[ 결제방법 선택 ]</option>
				<option value="Card">신용카드 결제</option>
				<option value="DirectBank">실시간 은행계좌이체 </option>
				<option value="VBank">무통장 입금 </option>
				<option value="HPP">핸드폰 결제</option>
				<option value="PhoneBill">받는전화결제</option>
				<option value="OCBPoint">OK 캐쉬백포인트 결제</option>
				<option value="Culture">문화상품권 결제</option>
				<option value="TeenCash">틴캐시 결제</option>
				<option value="DGCL">스마트문화 상품권 결제</option>
				<option value="BCSH">도서문화 상품권 결제</option>
				<option value="YPAY">옐로페이 결제</option>
				<option value="KPAY">케이페이 결제</option>
				<option value="EasyPay">간편 결제</option>
				<option value="EWallet">전자지갑 결제</option>
				<option value="POINT">포인트 결제</option>
				<option value="GiftCard">상품권 결제</option>	
				-->
				<input type="hidden" type="hidden" name="gopaymethod" value="<?php echo $_paymethod?>" >
				<?=$hpp_input?>
				<?$escrow_input?>
				<!--offerPeriod : 제공기간
				ex)20150101-20150331, [Y2:년단위결제, M2:월단위결제, yyyyMMdd-yyyyMMdd : 시작일-종료일] -->
				<!--<input  type="hidden" name="offerPeriod" value="20160101-20161231" >-->

				<!-- 카드   : BILLAUTH(card):FULLVERIFY 
					 휴대폰 : BILLAUTH(HPP):HPP(4) : 이니시스와 계약한 빌링방법이 컨텐츠인경우 
					 휴대폰 : BILLAUTH(HPP):HPP(5) : 이니시스와 계약한 빌링방법이 실물인경우 -->
				<input type="hidden" id="acceptmethod" name="acceptmethod" value="CARDPOINT:useescrow:HPP(2):va_receipt:below1000:SKIN():KWPY_TYPE(0):KWPY_VAT(0)" >

				<!-- 결제일 알림 메세지 -->
				<input  type="hidden" id="billPrint_msg" name="billPrint_msg" value="" >

				<!--***** 표시 옵션 *****-->
				<!--언어 : [ko|en] (default:ko)-->
				<input type="hidden" name="languageView" value="" >

				<!--리턴 인코딩 : [UTF-8|EUC-KR] (default:UTF-8)-->
				<input type="hidden" name="charset" value="" >

				<!-- 결제창 표시방법: [overlay] (default:overlay)-->
				<input type="hidden" name="payViewType" value="" >

				<!--
				closeUrl : payViewType='overlay','popup'시 취소버튼 클릭시 창닥기 처리 URL(가맹점에 맞게 설정)
				close.jsp 샘플사용(생략가능, 미설정시 사용자에 의해 취소 버튼 클릭시 인증결과 페이지로 취소 결과를 보냅니다.)
				-->
				<input type="hidden" name="closeUrl" value="<?php echo $siteDomain ?>/close.php" >

				<!--
				popupUrl : payViewType='popup'시 팝업을 띄울수 있도록 처리해주는 URL(가맹점에 맞게 설정)
				popup.jsp 샘플사용(생략가능,payViewType='popup'으로 사용시에는 반드시 설정)
				-->
				<input type="hidden" name="popupUrl" value="<?php echo $siteDomain ?>/popup.php" >

				<!--***** 추가 옵션 *****-->
				<!--merchantData : 가맹점 관리데이터(2000byte)
					인증결과 리턴시 함께 전달됨
				-->
				<input  type="hidden" name="merchantData" value="" >
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
						<?
						/*
						작업자		: 임서연
						작업일시		: 2020-03-05
						작업내용		: 결제모듈 호출 미작동 오류 수정(수정 반영 작업)
						*/
						?>
							<input type="button" value="결제하기" class="btn_style2" onclick="javascript:paybtn()"></a>
							<input type="button" value="취소하기" class="btn_style1" onclick="location.href='/'" ></a>
						</td>
					</tr>
				</table>
		</form>
    </body>
</html>
