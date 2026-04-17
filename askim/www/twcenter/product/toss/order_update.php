<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

$paymentKey = $_GET['paymentKey'];
$orderid = $_GET['orderId'];
$amount = $_GET['amount'];

$sql = "select * from wiz_order where orderid='$orderid'";
$order_info = sql_fetch_object($sql);

if($order_info->total_price == $amount) {
	$url = "https://api.tosspayments.com/v1/payments/".$paymentKey;
	$header_data = array(
		'Authorization: Basic '.base64_encode($oper_info['pay_key'].":"),
		'Content-Type: application/json'
	);
	$post_data = array("orderId"=>$orderid, "amount"=>$amount);
	$json_post_data = json_encode($post_data);

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_post_data);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt( $ch, CURLOPT_TIMEOUT, 180 );
	$json_return = curl_exec( $ch );
	$result = json_decode($json_return);
	curl_close( $ch ); 

	if($result->status == 'DONE' || $result->status == 'WAITING_FOR_DEPOSIT') {
		$rescode = "0000";

		if($order_info->pay_method == "PV"){
			$status = "OR";// 가상계좌인경우 입금이 안되었으므로 주문접수
		}else{
			$status = "OY";
		}

		$_Payment['status'] = $status; //결제상태

		$_Payment['orderid']			= $orderId;								//-- 주문번호
		$_Payment['paymethod']		= $order_info->pay_method;				//-- 결제종류
		$_Payment['ttno']				= $result->paymentKey;			//-- 거래번호
		$_Payment['bankkind']			= $result->virtualAccount->bankCode; //-- 은행코드(가상계좌일경우)
		$_Payment['accountname']		= $result->virtualAccount->customerName;
		$_Payment['accountno']		= $result->virtualAccount->accountNumber;  //-- 계좌번호(가상계좌일경우)
		
		//가상계좌 입금기한
		$dueDateRaw = $result->virtualAccount->dueDate ?? '';
		$_Payment['account_dueDate'] = strtotime($dueDateRaw) ? date("Y-m-d H:i:s", strtotime($dueDateRaw)) : '';

		//$_Payment['account_dueDate']	= date("Y-m-d H:i:s", strtotime($result->virtualAccount->dueDate));  
		
		$_Payment['pgname']			= "toss";								//-- PG사 종류
		$_Payment['es_stats']			= "IN";									//-- 에스크로 상태(데이콤으로 기본정보 발송)
		$_Payment['tprice']			= $amount; //결제금액
		$_Payment['vir_secret']		= $result->secret;
//		$_Payment['cash_num']         = $xpay->Response("LGD_CASHRECEIPTNUM",0);		//현금영수증 승인번호
//		$_Payment['cash_type']        = $xpay->Response("LGD_CASHRECEIPTKIND",0);		//현금영수증 종류
//		$_Payment['financename']      = $xpay->Response("LGD_FINANCENAME",0);		//결제카드사
//		$_Payment['financename_num']  = $xpay->Response("LGD_CARDNUM",0);			//카드번호

		//2025-06-23 에스크로 사용여부 체크 수정
		if($oper_info['pay_escrow'] == "Y"){
			if($result->useEscrow === false) {
				$_Payment['es_check'] = "N";
			}else{
				$_Payment['es_check'] = "Y";
			}
		}

		if($order_info->pay_method == "PV"){
			$_Payment['financename']      = $result->virtualAccount->bankCode; //가상계좌 은행 코드
			$_Payment['financename_num']  = $result->virtualAccount->accountNumber; //가상계좌 계좌번호
		}else{
			$_Payment['financename'] = iconv("utf-8", "euc-kr//IGNORE", $result->card->company ?? '');
			$_Payment['financename_num']  = $result->card->number;			//카드번호
		}

		$_Payment['cardquota']		= $result->card->installmentPlanMonths;			//할부 0이면 일시불
		$_Payment['approvedAt']		= $result->approvedAt;			//카드 승인시간

		$product_idx                = $product_idx;								//장바구니에 담긴 idx값

		foreach($_Payment as $key => $value){
			$logs .="$key : $value\r";
		}

		@make_log(LOG_PATH."toss_log.log","\r---------------------------[".date("Ymd")."] order_update.php start----------------------------------\r".$logs."\r---------------------------order_update.php END----------------------------------\r");

		$isDBOK = false;

		Exe_payment($_Payment);												//-- 결제처리(상태변경,주문 업데이트)

		//최종결제요청 결과 성공 DB처리 실패시 Rollback 처리
		//$isDBOK = true; //DB처리 실패시 false로 변경해 주세요.

		if( !$isDBOK ) {
			//echo "<p>";
			$cancel_url = "https://api.tosspayments.com/v1/payments/".$paymentKey."/cancel";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $cancel_url );
			curl_setopt( $ch, CURLOPT_POST, true);
			curl_setopt( $ch, CURLOPT_HEADER, true);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $header_data);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_post_data);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
			curl_setopt( $ch, CURLOPT_TIMEOUT, 180 );
			$json_return = curl_exec( $ch );
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close( $ch ); 

			$cancel_post_data = array("orderId"=>$orderid, "amount"=>$amount);
			$json_cancel_post_data = json_encode($cancel_post_data);
			
			alert("주문 처리 중 오류로 결제가 취소되었습니다.\\n처음부터 다시 진행해주세요.", "/shop/basket.php");
		} else {
			Exe_reserve();														//-- 적립금 처리 : 적립금 사용시 적립금 감소
			Exe_stock();														//-- 재고처리
			Exe_delbasket();													//-- 장바구니 삭제

			$resultMSG ="OK";
		}
	} else {
		alert($result->message, "/shop/basket.php");
	}
} else {
	$order_pay_url = "/".$prd_info['order_url']."?ptype=pay&orderid=".$orderid."&pay_method=".$order_info->pay_method;
	alert("결제금액이 틀립니다.", $order_pay_url);
}

	if(mobile_check() == true) {
		$go_url = "/m/sub/order_ok.php";
	} else {
		$go_url = "/".$prd_info['order_url'];
	}
?>

<form name="frm" action="<?=$go_url?>" method="post">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="rescode" value="<?=$rescode?>">
<input type="hidden" name="resmsg" value="<?=$resmsg?>">
<input type="hidden" name="pay_method" value="<?=$order_info->pay_method?>">
<input type="hidden" name="ptype" value="ok">
</form>
<script>document.frm.submit();</script> 
