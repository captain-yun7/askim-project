<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";
/*
	$client_id = $naver_client_id;
	$client_secret = $naver_client_secret;
	$code = $_GET["code"];
	$state = $_GET["state"];
	$redirectURI = $naver_redirect_uri;
//	$redirectURI = urlencode($naver_redirect_uri);
	$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
	$result = curl_connect_get($url);
	print_r($result);
	$access_token = $result['access_token'];
	
	if(!$access_token) {
		echo "<script>alert('로그인 오류가 발생했습니다.');self.close();</script>";
		exit;
	}
	$url = "https://openapi.naver.com/v1/nid/me";

	$header = "Bearer ".$access_token; // Bearer 다음에 공백 추가
	$url = "https://openapi.naver.com/v1/nid/me";
	$is_post = false;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = array();
	$headers[] = "Authorization: ".$header;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec ($ch);
	$result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	echo "<xmp>";
	print_r($response);
	print_r($result);
	*/

/*	$response = '{"resultcode":"00","message":"success","response":{"id":"4vhxvfAL0Pv-0iikvtwNd3ZUnhpeP6qQHYgCGKZGt44","email":"pupury23@gmail.com","name":"\uae40\ub098\uc5f0"}}';
	$result = json_decode($response, true);

	$id = "4vhxvfAL0Pv-0iikvtwNd3ZUnhpeP6qQHYgCGKZGt44";
	$decode_id = base64url_decode($id);
	echo $id." => decode : ".$decode_id;
*/
	$id = "NH".date("ymdHis").rand(100,999);
	echo $id." | ".strlen($id);
?>
