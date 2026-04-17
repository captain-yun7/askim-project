<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/mem_info.php"; ?>
<?php
$server = $site_info['alimtalk_temp_url'];
$senderKey = $site_info['alimtalk_senderkey'];
$custGubun = $site_info['alimtalk_custgubun'];

//템플릿 조회
$url = $server."/api/v1/".$custGubun."/template/request";

$post_data = "";
$post_data .= "senderKey=".$senderKey;
$post_data .= "&templateCode=".$templateCode;
$ch = curl_init ();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt ($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/x-www-form-urlencoded;charset=UTF-8')); 
$ch_result = curl_exec ($ch);
$json_response = json_decode($ch_result, true);

$return_code = $json_response['code'];

if($return_code=="200"){
	echo "<script>alert('템플릿 검수요청 되었습니다.');location.href='talk_list.php'</script>";
}else{
	echo "<script>alert('error_code:".templete_error_code($return_code)."');location.href='talk_list.php'</script>";
}
?>