<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";


if (!function_exists('json_decode')) {
    function json_decode($content, $assoc=false) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/json.php';
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        }
        else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }
}

if (!function_exists('json_encode')) {
	function json_encode($content) {
		require_once $_SERVER['DOCUMENT_ROOT'].'/json.php';
		$json = new Services_JSON;
		return $json->encode($content);
	}
}

	$admin_key = $oper_info['kakao_admin'];
	$cid = $oper_info['kakao_mid'];

	$order_info = sql_fetch("select * from wiz_order where orderid='$orderid'");

	$req_auth   = 'Authorization: KakaoAK '.$admin_key;
	$req_cont   = 'Content-type: application/x-www-form-urlencoded;charset=utf-8';

	$kakao_header = array( $req_auth, $req_cont );

	$kakao_params = array(
		'cid'               => $cid,		// 가맹점코드 
		'cid_secret'		=> '',	// 가맹점 코드 인증키. 24자 숫자+영문 소문자
		'tid'				=> $order_info['tid'],	// 결제 고유번호. 20자.
		'cancel_amount'     => $order_info['total_price'],	// 취소 금액
		'cancel_tax_free_amount'   => 0,		// 취소 비과세 금액
		'cancel_available_amount'	=> $order_info['total_price']
	);

//	print_r($kakao_params);
//	exit;

	$ch = curl_init();
	$url = "https://kapi.kakao.com/v1/payment/cancel";

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($kakao_params));
	curl_setopt ($ch, CURLOPT_POSTFIELDSIZE, 0);
	 
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $kakao_header);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($ch);
	curl_close($ch);

	
	$conv_res = json_decode($res,1);
	//print_r($conv_res);

	if($conv_res['status'] == "CANCEL_PAYMENT" || $conv_res['status'] == "PART_CANCEL_PAYMENT")	{
		$k_status  = "SUCCESS";
		$alert_msg = "결제 취소가 완료되었습니다.";
	} else if($conv_res['status'] == "FAIL_PAYMENT") {
		$k_status  = "FAILED";
		$alert_msg = "결제 취소 중 오류가 발생하였습니다.";
	} else {
		if($conv_res['status']) {
			$k_status  = $conv_res['status'];
			$alert_msg = "결제 취소 중 오류가 발생하였습니다.";
		} else {
			$k_status  = $conv_res['code'];
			$alert_msg = $conv_res['msg'];
		}
		
	}

	if($k_status == "SUCCESS") {
		
		# 적립금 사용했을 시 적립금 환불
		if($order_info['reserve_use'] != "" && $order_info['reserve_use'] != "0"){

			$reservemsg = "주문(결제)취소";
			$sql_com = "";
			$sql_com .= " memid                    = '".$order_info['send_id']."'                     ";
			$sql_com .= " , reservemsg             = '".$reservemsg."'                                ";
			$sql_com .= " , reserve                = '".$order_info['reserve_use']."'                 ";
			$sql_com .= " , orderid                = '".$orderid."'                                   ";
			$sql_com .= " , wdate                  = now()                                            ";

			$reserve_sql = "insert into wiz_reserve set {$sql_com} ";
			query($reserve_sql);
		}

	}
?>