<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?

$templateCode = $_POST['templateCode'];
$comment = $_POST['comment'];

$templateName_conv = $templateName;
$comment_conv = $comment;

if($templateCode=="" || $comment==""){
	echo "<script>alert('필수값이 입력되지 않았습니다.');self.close();'</script>";
	exit;
}

$server = $site_info['alimtalk_temp_url'];
$senderKey = $site_info['alimtalk_senderkey'];
$custGubun = $site_info['alimtalk_custgubun'];

//템플릿 등록
$url = $server."/api/v1/".$custGubun."/template/comment";

$post_data = "";
$post_data .= "senderKey=".$senderKey;
$post_data .= "&templateCode=".$templateCode;
$post_data .= "&comment=".$comment_conv;


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
	echo "<script>alert('등록되었습니다.');location.href='talk_rej.php?templateCode=$templateCode'</script>";
}else{
	echo "<script>alert('error_code:".templete_error_code($return_code)."');location.href='talk_rej.php?templateCode=$templateCode'</script>";
}
?>