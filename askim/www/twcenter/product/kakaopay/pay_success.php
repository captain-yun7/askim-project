<?php
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

	$req_auth   = 'Authorization: KakaoAK '.$admin_key;
	$req_cont   = 'Content-type: application/x-www-form-urlencoded;charset=utf-8';
 
	$kakao_header = array( $req_auth, $req_cont );
 
	$kakao_params = array(
		'cid'               => $cid,                            // 가맹점코드 10자
		'tid'               => $kakao['tid'],         // 결제 고유번호. 결제준비 API의 응답에서 얻을 수 있음
		'partner_order_id'  => $kakao['order_id'],    // 가맹점 주문번호. 결제준비 API에서 요청한 값과 일치해야 함
		'partner_user_id'   => $wiz_session['id'],           // 가맹점 회원 id. 결제준비 API에서 요청한 값과 일치해야 함
		'pg_token'          => $_REQUEST['pg_token']    // 결제승인 요청을 인증하는 토큰. 사용자가 결제수단 선택 완료시 approval_url로 redirection해줄 때 pg_token을 query string으로 넘겨줌
		//'payload'           => ,                              // 해당 Request와 매핑해서 저장하고 싶은 값. 최대 200자
	);

	$ch = curl_init();
	$url = "https://kapi.kakao.com/v1/payment/approve?tid=".$kakao['tid'];

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($kakao_params)); //POST로 보낼 데이터 지정하기
	curl_setopt ($ch, CURLOPT_POSTFIELDSIZE, 0); //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
	 
	curl_setopt($ch, CURLOPT_HEADER, false);//헤더 정보를 보내도록 함(*필수)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $kakao_header); //header 지정하기
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
	$res = curl_exec($ch);
	curl_close($ch);

	$conv_res = json_decode($res,1);
	//print_r($conv_res);

if($conv_res['aid'])	$paySuccess = true;

if($paySuccess) {
	$moid = $conv_res['partner_order_id'];
	$tid = $conv_res['tid'];
	$resultCode = '3001';
	$resultMsg = "결제성공";
	//주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid = '$moid'";
	$result = query($sql);
	$order_info = sql_fetch_obj($result);

	$_Payment['status']				= "OY";								// 결제상태
	$_Payment['orderid']				= $moid;							// 주문번호
	$_Payment['paymethod']			= $order_info->pay_method;			// 결제종류
	$_Payment['ttno']					= $conv_res['tid'];					// 거래번호
	$_Payment['pgname']				= "kakaopay";						// PG사 종류
	$_Payment['tprice']				= $conv_res['amount']['total'];		//= $order_info->total_price;		// 결제금액
	$_Payment['kakao_nonRepToken']	= $kakao_params['pg_token'];		// 부인방지토큰
	
	$_Payment['cardcode']				= $conv_res['card_info']['purchase_corp_code'];			// 카드사코드
//	$_Payment['cardname']				= $conv_res['card_info']['purchase_corp'];				// 결제카드사명
	$_Payment['cardquota']			= $conv_res['card_info']['install_month'];				// 할부개월수
	$_Payment['cardinterest']			= $conv_res['card_info']['interest_free_install'];		// 무이자여부( Y: 무이자,  N : 일반)
	$_Payment['cardci']				= ($conv_res['card_info']['card_type'] == "체크") ? "Y" : "N";	// 체크카드여부
	$_Payment['cardbin']				= $conv_res['card_info']['bin'];						// 카드BIN번호
	$_Payment['cardpoint']			= "";			// 카드사포인트사용여부
	
	$_Payment['tid']					= $conv_res['tid'];							// 거래아이디


		Exe_payment($_Payment);
		Exe_reserve();
		Exe_stock();
		Exe_delbasket();

$_SESSION['kakao'] = null;

if(mobile_check() == true){
	$_ord_url = $prd_info['m_orderResult_url'];
	$_ord_target = "opener.parent";
} else {
	$_ord_url = $prd_info['order_url'];
	$_ord_target = "parent.opener.parent";
}

?>
<form name="frm" action="/<?=$_ord_url?>" method="post">
<input type="hidden" name="orderid" value="<?=$moid?>">
<input type="hidden" name="rescode" value="<?=$resultCode?>">
<input type="hidden" name="resmsg" value="<?=$resultMsg?>">
<input type="hidden" name="pay_method" value="<?=$order_info->pay_method?>">
<input type="hidden" name="ptype" value="ok">
</form>

<script>
	<?=$_ord_target?>.name = "targetPage";
	document.frm.target = "targetPage";
	document.frm.submit();
	window.close();
</script>
<?
}else{
	// 결제 실패
	$resultCode = $conv_res['code'];
	$resultMsg = $conv_res['extras']['method_result_message'];

	//print_r($conv_res);

?>
<script language='javascript'>
	var childWindow = window.parent;
	alert('<?=$resultMsg?>');
	childWindow.close();
</script>
<?
}
?>
