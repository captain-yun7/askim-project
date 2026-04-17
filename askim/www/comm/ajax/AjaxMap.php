<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

$address = $_POST['address'];
$param_str = urlencode($address);

$ch = curl_init();

$headers = array(
	"authorization: KakaoAK ".$appkey_api
);

$url = "https://dapi.kakao.com/v2/local/search/address.json?query=".$param_str;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res =curl_exec($ch);
curl_close($ch);

$conv_res = json_decode($res,1);
$longitude= $conv_res['documents'][0]['x'];	//위도
$latitude  = $conv_res['documents'][0]['y'];	//경도
$addrName  = $conv_res['documents'][0]['address_name'];	//주소
$zipcode   = $conv_res['documents'][0]['address']['zip_code'];	//우편번호
$h_code    = $conv_res['documents'][0]['address']['h_code'];	//법정동코드
$b_code    = $conv_res['documents'][0]['address']['b_code'];	//법정동코드
$r3name    = $conv_res['documents'][0]['address']['region_3depth_name'];

$region_3depth_name = trim($r3name);
$region_3depth_name = explode(" ", $region_3depth_name);
if(count($region_3depth_name) >= 2 ) {
	$return_code = $h_code;
} else {
	$return_code = $b_code;
}

//echo $latitude."/".$longitude."/".$addrName."/".$zipcode."/".$return_code;
echo $latitude."/".$longitude;
?>