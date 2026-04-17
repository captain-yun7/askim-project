<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<link href="../../wiz_style.css" rel="stylesheet" type="text/css">
<?php

$server = $site_info['alimtalk_temp_url'];
$senderKey = $site_info['alimtalk_senderkey'];
$custGubun = $site_info['alimtalk_custgubun'];

//템플릿 조회
$url = $server."/api/v1/".$custGubun."/template";

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

$comments = $json_response['data']['comments'];

for($i=0;$i<count($comments);$i++){
	$id = $comments[$i]['id'];
	$content = $comments[$i]['content'];
	$userName = $comments[$i]['userName'];
	$createdAt = $comments[$i]['createdAt'];
	echo "[".($i+1)."번째 코멘트]<br>";
	echo "id : ".$id."<br>";
	echo "content : ".$content."<br>";
	echo "userName : ".$userName."<br>";
	echo "createdAt : ".$createdAt."<br><br><br>";
}
?>

<script>
function inputCheck(frm){
	if(frm.comment.value == ""){
		alert("문의사항을를 입력하세요.");
		frm.comment.focus();
		return false;		
	}
}
</script>

[카카오톡에 템플릿관련 문의하기]<br><br>
<form action="./talk_comment.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="templateCode" value="<?=$templateCode?>">
<textarea name="comment" rows="7" cols="50"></textarea>
<br><br>
<input type="image" src="../image/btn_confirm_s.gif">
</form>
