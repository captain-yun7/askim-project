<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once WAY_PATH."/twcenter/inc/prd_info.php";
include_once WAY_PATH."/twcenter/inc/site_info.php";
include_once WAY_PATH."/twcenter/inc/oper_info.php";

$status  = "OR";					// 주문상태
$rescode = "0000";					// 결제결과
$resmsg  = "정상적으로 결제되었습니다.";	// 결제메세지

// 주문정보
$sql = "select * from wiz_order where orderid='$orderid'";
$result = query($sql);
$order_info = sql_fetch_obj($result);

$_Payment['status']		= "OR"; //결제상태

// 적립금으로 모두 결제시 결제완료[OY]
if($order_info->total_price == 0){
  $_Payment['status']  = "OY";
  $_Payment['paydate'] = date('Y-m-d h:i:s');
}

##;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
## 주문정보 업데이트
##;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
$_Payment['orderid']		= $orderid;			//주문번호
$_Payment['paymethod']		= "PB";				//결제종류
$_Payment['accountno']		= $account;			//계좌번호
$_Payment['accountname']	= $account_name;	// 예금주
$_Payment['pgname']			= "PB";				//PG사 종류

Exe_payment($_Payment);						//결제처리(상태변경,주문 업데이트)
Exe_reserve();								// 적립금 처리 : 적립금 사용시 적립금 감소

// 재고처리(결제완료[OY]인 경우에만 재고 감소 -> 재고가 마이너스되는 경우 때문에 주문완료 시 재고 감소)
//if(!strcmp($status, "OY")) Exe_stock();
Exe_stock();
Exe_delbasket();							// 장바구니 삭제
$resp = true;
$resultMSG ="OK";

// 세금계산서 업데이트
if(!strcmp($oper_info['tax_use'], "Y")) {
	$sql = "
		update wiz_tax 
		   set tax_date = now() 
		 where orderid = '$orderid'
	";
	query($sql);
}

if(mobile_check() == true) {
	$go_url = $mobile_path."/sub/order_ok.php";
} else {
	$go_url = $prd_info['order_url'];
}

?>

<form name="frm" action="/<?php echo $go_url ?>" method="post">
<input type="hidden" name="orderid" value="<?php echo $orderid ?>">
<input type="hidden" name="rescode" value="<?php echo $rescode ?>">
<input type="hidden" name="resmsg" value="<?php echo $resmsg ?>">
<input type="hidden" name="pay_method" value="<?php echo $pay_method ?>">
<input type="hidden" name="ptype" value="ok">
</form>
<script>document.frm.submit();</script> 