<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";


if (!function_exists('json_decode')) {
    function json_decode($content, $assoc=false) {
        require_once $_SERVER["DOCUMENT_ROOT"].'/json.php';
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
		require_once $_SERVER["DOCUMENT_ROOT"].'/json.php';
		$json = new Services_JSON;
		return $json->encode($content);
	}
}

	$admin_key = $oper_info['kakao_admin'];
	//$cid = 'TC0ONETIME';
	$cid = $oper_info['kakao_mid'];

	$req_auth   = 'Authorization: KakaoAK '.$admin_key;
	$req_cont   = 'Content-type: application/x-www-form-urlencoded;charset=utf-8';

	$kakao_header = array( $req_auth, $req_cont );

	$kakao_params = array(
		'cid'               => $cid,                                    // 가맹점코드 10자
		'partner_order_id'  => $partner_order_id,                   // 주문번호
		'partner_user_id'   => $partner_user_id,                   // 유저 id
		'item_name'         => $item_name,               // 상품명
		'quantity'          => $quantity,                    // 상품 수량
		'total_amount'      => $total_amount,                // 상품 총액
		'tax_free_amount'   => '0',                                     // 상품 비과세 금액
		'approval_url'      => $approval_url,                           // 결제성공시 콜백url 최대 255자
		'cancel_url'        => $cancel_url,
		'fail_url'          => $fail_url,
	);

	$ch = curl_init();
	$url = "https://kapi.kakao.com/v1/payment/ready";

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($kakao_params)); //POST로 보낼 데이터 지정하기
	curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0); //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
	 
	curl_setopt($ch, CURLOPT_HEADER, false);//헤더 정보를 보내도록 함(*필수)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $kakao_header); //header 지정하기
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
	$res = curl_exec($ch);
	curl_close($ch);

	$conv_res = json_decode($res,1);
	//print_r($conv_res);

	$_SESSION['kakao']['tid'] = $conv_res['tid'];
	$_SESSION['kakao']['order_id'] = $partner_order_id;
//	setcookie("kakao_tid", $conv_res['tid'], false, "/");
//	setcookie("kakao_order_id", $partner_order_id, false, "/");
	//Header("Location: ./blank.php");


	if(mobile_check()) {
		$pay_url = $conv_res['next_redirect_mobile_url'];
		echo "<script>\n";
		echo "window.open('$pay_url');";
		echo "</script>";
	} else {
		$pay_url = $conv_res['next_redirect_pc_url'];
		echo "<script>\n";
		echo "window.open('".$pay_url."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=540,height=700,left=100,top=100');";
		//echo "var win = window.open('','','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=540,height=700,left=100,top=100');\n";
		//echo "win.document.write('<iframe width=100%, height=650 src=".$pay_url."#none frameborder=0 allowfullscreen></iframe>');\n";
		echo "</script>";
	}
	echo "<BR><BR>".$pay_url;

?>