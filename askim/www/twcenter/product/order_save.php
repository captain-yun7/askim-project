<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

$order_url = $prd_info['order_url'];

if($rece_name == "") error("받으시는 분 이름이 빠졌습니다.");
if($rece_post == "")  error("받으시는 분 우편번호가 빠졌습니다.");
if($rece_address1 == "" || $rece_address2 == "") error("받으시는 분 주소가 빠졌습니다.");
if($rece_hphone == "" || $rece_hphone2 == "" || $rece_hphone3 == "") error("받으시는 분 휴대 전화번호가 빠졌습니다.");

$send_id      = $wiz_session['id'];
$reserve_use  = $_POST['reserve_use'];

$send_address = $send_address1." ".$send_address2;
$send_tphone  = $send_tphone."-".$send_tphone2."-".$send_tphone3;
$send_hphone  = $send_hphone."-".$send_hphone2."-".$send_hphone3;
$send_email   = $email_1."@".$email_2;

$rece_address = $rece_address1." ".$rece_address2;
$rece_tphone  = $rece_tphone."-".$rece_tphone2."-".$rece_tphone3;
$rece_hphone  = $rece_hphone."-".$rece_hphone2."-".$rece_hphone3;

// 주문번호
$orderid = date("ymdHis").rand(100,999);

// 주문가격 정보(상품가 격, 배송비, 적립금, 전체결제금액)
// 주문정보 입력폼에서 전달시 변조될 위험있음
if(!empty($product_idx))
{
	$_product_idx_val = explode("|", $product_idx);

	foreach($_product_idx_val as $key => $value){
		if(!empty($value)) $_product_idx_tmp .= " OR idx='{$value}'";
	}

	$_product_idx_tmp = substr($_product_idx_tmp,3);
	$_product_idx_sql = " and ({$_product_idx_tmp})";
} else {
	$_product_idx_sql = "";
}

//쿠폰사용가능여부 재확인
if($coupon_idx) {
	use_coupon_check($coupon_idx, $send_id);
} 

$sql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."' $_product_idx_sql";
$bkresult = query($sql);
while($bkinfo = sql_fetch_arr($bkresult)){
//	$prd_price += ($bkinfo['prdprice'] * $bkinfo['amount']);
//	$reserve_price += ($bkinfo['prdreserve'] * $bkinfo['amount']);
	if(
		strpos($bkinfo['optcode'],"&&")  !== false || strpos($bkinfo['optcode2'],"&&") !== false || strpos($bkinfo['optcode3'],"&&") !== false || strpos($bkinfo['optcode4'],"&&") !== false || strpos($bkinfo['optcode5'],"&&") !== false || strpos($bkinfo['optcode6'],"&&") !== false || 
		strpos($bkinfo['optcode7'],"&&") !== false || strpos($bkinfo['optcode8'],"&&") !== false || strpos($bkinfo['optcode9'],"&&") !== false || strpos($bkinfo['optcode10'],"&&") !== false || strpos($bkinfo['optcode11'],"&&") !== false || strpos($bkinfo['optcode12'],"&&") !== false || strpos($bkinfo['optcode13'],"&&") !== false
		)
	{
		$prd_price      += ($bkinfo['prdprice'] * $bkinfo['amount']);
		$reserve_price  += ($bkinfo['prdreserve'] * $bkinfo['amount']);
	} else {
		$prd_price      += ($bkinfo['prdprice'] * $bkinfo['amount']);
		$reserve_price  += ($bkinfo['prdreserve'] * $bkinfo['amount']);
	}

}

$prd_info = "";

// 주문상품 저장
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
	  FROM wiz_basket_tmp as wb 
	  LEFT JOIN wiz_product as wp 
	    ON wb.prdcode = wp.prdcode 
	 WHERE wb.uniq_id='".$_uniq_id."' $product_idx_sql
";
$bkiresult = query($sql);
while($bkirow = sql_fetch_arr($bkiresult)) {
	$sql_com = "";
	$sql_com .= " orderid              = '".$orderid."'                             ";
	$sql_com .= " , basketidx          = '".$bkirow['idx']."'                       ";
	$sql_com .= " , prdcode            = '".$bkirow['prdcode']."'                   ";
	$sql_com .= " , prdname            = '".$bkirow['prdname']."'                   ";
	$sql_com .= " , prdimg             = '".$bkirow['prdimg']."'                    ";
	$sql_com .= " , prdprice           = '".$bkirow['prdprice']."'                  ";
	$sql_com .= " , prdreserve         = '".$bkirow['prdreserve']."'                ";
//	$sql_com .= " , use_coupon         = 0                ";
//	$sql_com .= " , use_reserve         = 0                ";
	$sql_com .= " , opttitle           = '".$bkirow['opttitle']."'                  ";
	$sql_com .= " , optcode            = '".$bkirow['optcode']."'                   ";
	$sql_com .= " , opttitle2          = '".$bkirow['opttitle2']."'                 ";
	$sql_com .= " , optcode2           = '".$bkirow['optcode2']."'                  ";
	$sql_com .= " , opttitle3          = '".$bkirow['opttitle3']."'                 ";
	$sql_com .= " , optcode3           = '".$bkirow['optcode3']."'                  ";
	$sql_com .= " , opttitle4          = '".$bkirow['opttitle4']."'                 ";
	$sql_com .= " , optcode4           = '".$bkirow['optcode4']."'                  ";
	$sql_com .= " , opttitle5          = '".$bkirow['opttitle5']."'                 ";
	$sql_com .= " , optcode5           = '".$bkirow['optcode5']."'                  ";
	$sql_com .= " , opttitle6          = '".$bkirow['opttitle6']."'                 ";
	$sql_com .= " , optcode6           = '".$bkirow['optcode6']."'                  ";
	$sql_com .= " , opttitle7          = '".$bkirow['opttitle7']."'                 ";
	$sql_com .= " , optcode7           = '".$bkirow['optcode7']."'                  ";
	$sql_com .= " , opttitle8          = '".$bkirow['opttitle8']."'                 ";
	$sql_com .= " , optcode8           = '".$bkirow['optcode8']."'                  ";
	$sql_com .= " , opttitle9          = '".$bkirow['opttitle9']."'                 ";
	$sql_com .= " , optcode9           = '".$bkirow['optcode9']."'                  ";
	$sql_com .= " , opttitle10         = '".$bkirow['opttitle10']."'                ";
	$sql_com .= " , optcode10          = '".$bkirow['optcode10']."'                 ";
	$sql_com .= " , opttitle11         = '".$bkirow['opttitle11']."'                ";
	$sql_com .= " , optcode11          = '".$bkirow['optcode11']."'                 ";
	$sql_com .= " , opttitle12         = '".$bkirow['opttitle12']."'                ";
	$sql_com .= " , optcode12          = '".$bkirow['optcode12']."'                 ";
	$sql_com .= " , opttitle13         = '".$bkirow['opttitle13']."'                ";
	$sql_com .= " , optcode13          = '".$bkirow['optcode13']."'                 ";
	$sql_com .= " , amount             = '".$bkirow['amount']."'                    ";
	$sql_com .= " , wdate              = now()                                      ";
	$sql_com .= " , status             = ''                                         ";
	$sql_com .= " , del_type           = '".$bkirow['del_type']."'                  ";
	$sql_com .= " , del_price          = '".$bkirow['del_price']."'                 ";

	$sql = "insert into wiz_basket set {$sql_com} ";
	query($sql);

	$bakset_prd_price += $bkirow['prdprice'];
	$prd_info .= $bkirow['prdname']."^".$bkirow['prdprice']."^".$bkirow['amount']."^^";
}
// 배송비
$deliver_price = deliver_price($prd_price, $oper_info);

// 배송방법
$deliver_method = $oper_info['del_method'];

// 회원할인 [$discount_msg 메세지 생성]
$discount_price = level_discount($wiz_session['level'],$prd_price);

// 배송할증료 적용(고정값,구매가격별에서만 적용)
if($deliver_method == "DC" || $deliver_method == "DD"){
	$tmp_post = str_replace("-","",$rece_post);
	if($oper_info['del_extrapost1'] <= $tmp_post && $tmp_post <= $oper_info['del_extrapost12']) 
		$deliver_price = $deliver_price + $oper_info['del_extraprice1'];

	if($oper_info['del_extrapost2'] <= $tmp_post && $tmp_post <= $oper_info['del_extrapost22']) 
		$deliver_price = $deliver_price + $oper_info['del_extraprice2'];

	if($oper_info['del_extrapost3'] <= $tmp_post && $tmp_post <= $oper_info['del_extrapost32']) 
		$deliver_price = $deliver_price + $oper_info['del_extraprice3'];
}

$total_price = $prd_price + $deliver_price - $discount_price;

// 적립금사용시 결제액 감소, 적림금감소
if($oper_info['reserve_use'] == "Y" && $reserve_use > 0 && $wiz_session['id'] != ""){

	// 회원적립금 가져오기
	$sql = "SELECT SUM(reserve) AS reserve FROM wiz_reserve WHERE memid = '{$wiz_session['id']}'";
	$mem_info = sql_fetch_object($sql);
	if($mem_info->reserve == "") $mem_info->reserve = 0;

	// 적립금 사용금액이 실제 적립금보다 많다면
	if($reserve_use > $mem_info->reserve){
		error("실제적립금 보다 사용액이 많습니다.");
	}else{
		$total_price = $total_price - $reserve_use;
	}

}

// 쿠폰사용
if($coupon_use != "" && $coupon_use > 0){
	$total_price = $total_price - $coupon_use;
}

if(mobile_check() == true) {
	$connect_type = "M";
}else{
	$connect_type = "PC";
}

/* 신용카드 and 카카오페이 */
if($pay_method == 'PC' && $kakaopay == 'KAKAOPAY') $pay_method = "KK";
else											   $pay_method = $pay_method;

/*
if($pay_method != 'PC') {
	if($reserve_use > 0) {
		if($total_price != $reserve_use) {
			$se_num = '028675860';
			$re_num = '01044464110';
			$message = "[주문번호:".$orderid."] 주문상품금액과 포인트사용금액 틀림(주문시도)";
			send_sms($se_num, $re_num, $message);
		}
	}
}
*/

if($pay_method == "PC" || $pay_method == "PH") {
	$tax_type = "N";
}

// 주문정보 저장
$sql_com = "";
$sql_com .= " orderid                = '".$orderid."'                  ";
$sql_com .= " , send_id		         = '".$send_id."'                  ";
$sql_com .= " , send_name		     = '".$send_name."'                ";
$sql_com .= " , send_tphone		     = '".$send_tphone."'              ";
$sql_com .= " , send_hphone		     = '".$send_hphone."'              ";
$sql_com .= " , send_email		     = '".$send_email."'               ";
$sql_com .= " , send_post		     = '".$send_post."'                ";
$sql_com .= " , send_address		 = '".$send_address."'             ";
$sql_com .= " , demand		         = '".$demand."'                   ";
$sql_com .= " , message		         = '".$message."'                  ";
$sql_com .= " , cancelmsg		     = '".$cancelmsg."'                ";
$sql_com .= " , rece_name		     = '".$rece_name."'                ";
$sql_com .= " , rece_tphone		     = '".$rece_tphone."'              ";
$sql_com .= " , rece_hphone		     = '".$rece_hphone."'              ";
$sql_com .= " , rece_post		     = '".$rece_post."'                ";
$sql_com .= " , rece_address		 = '".$rece_address."'             ";
$sql_com .= " , pay_method		     = '".$pay_method."'               ";
$sql_com .= " , account_name		 = '".$account_name."'             ";
$sql_com .= " , account		         = '".$account."'                  ";
$sql_com .= " , coupon_use		     = '".$coupon_use."'               ";
$sql_com .= " , coupon_idx		     = '".$coupon_idx."'               ";
$sql_com .= " , reserve_use		     = '".$reserve_use."'              ";
$sql_com .= " , reserve_price		 = '".$reserve_price."'            ";
$sql_com .= " , deliver_method		 = '".$deliver_method."'           ";
$sql_com .= " , deliver_price		 = '".$deliver_price."'            ";
$sql_com .= " , deliver_num		     = '".$deliver_num."'              ";
$sql_com .= " , discount_price		 = '".$discount_price."'           ";
$sql_com .= " , prd_price		     = '".$prd_price."'                ";
$sql_com .= " , total_price		     = '".$total_price."'              ";
$sql_com .= " , status		         = '".$status."'                   ";
$sql_com .= " , order_date		     = now()                           ";
$sql_com .= " , pay_date		     = '".$paydate."'                  ";
$sql_com .= " , send_date		     = '".$sendddate."'                ";
$sql_com .= " , cancel_date		     = '".$canceldate."'               ";
$sql_com .= " , descript		     = '".$descript."'                 ";
$sql_com .= " , tax_type		     = '".$tax_type."'                 ";
$sql_com .= " , connect_type		 = '".$connect_type."'             ";
$sql_com .= " , basketidx		     = '".$product_idx."'              ";

$sql = "insert into wiz_order set {$sql_com} ";
query($sql);

discount_update($orderid);

// 세금계산서 저장
if(!strcmp($oper_info['tax_use'], "Y") && $tax_type != "N") {

	$supp_price = intval($total_price/1.1);
	$tax_price  = $total_price - $supp_price;

	// 신청정보
	//if(is_array($cash_info_arr)) $cash_info = implode("-", $cash_info_arr);
	if(isset($_POST['cash_info']) && $_POST['cash_info']) {
		$cash_info = implode("-", array_filter($_POST['cash_info']));
		$cash_info = (strpos($cash_info, '--') !== false) ? '' : $cash_info;
	} else {
		$cash_info = "";
	}

	if(isset($_POST['cash_info2']) && $_POST['cash_info2']) {
		$cash_info2 = implode("-", array_filter($_POST['cash_info2']));
		$cash_info2 = (strpos($cash_info2, '--') !== false) ? '' : $cash_info2;
	} else {
		$cash_info2 = "";
	}

	if(isset($_POST['cash_info3']) && $_POST['cash_info3']) {
		$cash_info3 = implode("-", array_filter($_POST['cash_info3']));
		$cash_info3 = (strpos($cash_info3, '--') !== false) ? '' : $cash_info3;
	} else {
		$cash_info3 = "";
	}

	if(isset($_POST['cash_info4']) && $_POST['cash_info4']) {
		$cash_info4 = implode("-", array_filter($_POST['cash_info4']));
		$cash_info4 = (strpos($cash_info4, '--') !== false) ? '' : $cash_info4;
	} else {
		$cash_info4 = "";
	}
	$sql_com = "";
	$sql_com .= " orderid		          = '".$orderid."'                ";
	$sql_com .= " , com_num		          = '".$com_num."'                ";
	$sql_com .= " , com_name		      = '".$com_name."'               ";
	$sql_com .= " , com_owner		      = '".$com_owner."'              ";
	$sql_com .= " , com_post		      = '".$com_post."'               ";
	$sql_com .= " , com_address1	      = '".$com_address1."'           ";
	$sql_com .= " , com_address2	      = '".$com_address2."'           ";
	$sql_com .= " , com_kind		      = '".$com_kind."'               ";
	$sql_com .= " , com_class		      = '".$com_class."'              ";
	$sql_com .= " , com_tel		          = '".$com_tel."'                ";
	$sql_com .= " , com_email		      = '".$com_email."'              ";
	$sql_com .= " , prd_info		      = '".$prd_info."'               ";
	$sql_com .= " , supp_price		      = '".$supp_price."'             ";
	$sql_com .= " , tax_price		      = '".$tax_price."'              ";
	$sql_com .= " , tax_pub		          = 'N'                           ";
	$sql_com .= " , tax_type		      = '".$tax_type."'               ";
	$sql_com .= " , cash_type		      = '".$cash_type."'              ";
	$sql_com .= " , cash_type2		      = '".$cash_type2."'             ";
	$sql_com .= " , cash_info		      = '".$cash_info."'              ";
	$sql_com .= " , cash_info2		      = '".$cash_info2."'             ";
	$sql_com .= " , cash_info3		      = '".$cash_info3."'             ";
	$sql_com .= " , cash_info4		      = '".$cash_info4."'             ";
	$sql_com .= " , cash_name		      = '".$cash_name."'              ";

	$sql = "insert into wiz_tax set {$sql_com} ";
	query($sql);

}

if(mobile_check() == true) {
	$go_url = "/".$mobile_path."/sub/order_pay.php?orderid=".$orderid."&pay_method=".$pay_method;
} else {
	$go_url = "/".$order_url."?ptype=pay&orderid=".$orderid."&pay_method=".$pay_method;
}

header("Location: $go_url");

?>