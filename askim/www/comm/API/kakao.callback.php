<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";

echo "<script type=\"text/JavaScript\" src=\"/comm/js/jquery-1.8.2.min.js\"></script>";

$CLIENT_ID     = $kakao_api_key; 
$REDIRECT_URI  = "http://".$_SERVER['HTTP_HOST']."/".$api_info['kakao_redirect_url'];
$TOKEN_API_URL = "https://kauth.kakao.com/oauth/token"; 

$code   = $_GET["code"]; 
$param = array(
	'grant_type'   => 'authorization_code',
	'client_id'    => $CLIENT_ID,
	'redirect_uri' => $REDIRECT_URI,
	'code'         => $code
);

$post_param = http_build_query($param);

$curlSession = curl_init(); 
curl_setopt($curlSession, CURLOPT_URL, $TOKEN_API_URL);
curl_setopt($curlSession, CURLOPT_POST, 1);
curl_setopt($curlSession, CURLOPT_POSTFIELDS, $post_param);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlSession, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($curlSession, CURLOPT_FORBID_REUSE, 1);
curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlSession, CURLOPT_CONNECTTIMEOUT, 1);
curl_setopt($curlSession, CURLOPT_TIMEOUT, 5);

$accessTokenJson = curl_exec($curlSession); 
curl_close($curlSession); 

$accessTokenJson = json_decode($accessTokenJson, true);
$access_token = $accessTokenJson['access_token'];
/*
작업자	: 임서연
작업일시	: 2020-03-05
작업내용	: 카카오로그인 API 버전업에 따른 호출주소 변경 v1 -> v2(수정 반영 작업)
*/
$TOKEN_API_URL = "https://kapi.kakao.com/v2/user/me"; 

$curlSession = curl_init(); 
curl_setopt($curlSession, CURLOPT_URL, $TOKEN_API_URL);
curl_setopt($curlSession, CURLOPT_POST, 0);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlSession, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$access_token));
curl_setopt($curlSession, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($curlSession, CURLOPT_FORBID_REUSE, 1);
curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlSession, CURLOPT_CONNECTTIMEOUT, 1);
curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);

$accessTokenJson = curl_exec($curlSession); 
curl_close($curlSession);

$userInfo = json_decode($accessTokenJson, true);
$email    = $userInfo['kaccount_email'];
$id       = $userInfo['id'];
$username    = $userInfo['properties']['nickname'];

$browser = getBrowser2();

if($browser['name']=="Google Chrome"){
	$name = $username;
}else{
	$name = iconv("EUC-KR","UTF-8",$username);
}

?>
<script type="text/javascript">
$(function(){
	
	var id          = "<?php echo $id?>";
	var user_Id     = id;
	var user_Name   = "<?php echo $username?>";
	var user_Email  = "<?php echo $email?>";
	var login_Type  = "sns";
	var sns_Login   = "KK";
	var kakao_login_location = "/comm/API/kakao.callback.act.php";

	/* SNS 아이디정보를 못불러올경우 재시도 유도 */
	if(user_Id == ""){
		if("<?=$_COOKIE['PM']?>" == "M"){
			if("<?=$_COOKIE['PM']?>"=="M"){
				alert("SNS로그인 정보를 불러올수없습니다.\n재시도 바랍니다.");
				location.href = "http://<?=$SERVER_NAME?>/m/member/login.php";
			}else{
				alert("SNS로그인 정보를 불러올수없습니다.\n재시도 바랍니다.");
				self.close();
			}
		}
	}

	if(id == "") { self.close(); return true; } // 가입 동의 안함 시. 리턴

	$.ajax({
		type: "post",
		url: "/twcenter/member/snsAjax/kakao_ajax.php",
		data: {user_Id: user_Id, user_Name: user_Name, sns_Login: sns_Login},
		success: function (data) {

			if (data == "ok") {

				if(confirm("선택하신 계정정보로 가입된 ID가 존재합니다.\n해당ID와 연결하시겠습니까?")){

					document.cookie = "kakao_username="+user_Name+"; path=/";
					document.cookie = "kakao_id="+id+"; path=/";
					document.cookie = "user_Email="+user_Email+"; path=/";
					document.cookie = "login_Type="+login_Type+"; path=/";
					document.cookie = "sns_login="+sns_Login+"; path=/";

					setTimeout(function() {
						location.href = kakao_login_location;
					}, 100);

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "/m/member/login.php";
					}else{
						self.close();
					}
				}

			} else {

				var name = encodeURI(user_Name);
				if (confirm("카카오톡 계정으로 가입하시겠습니까?\n카카오톡 계정으로 가입시 추가정보를 입력하셔야 합니다.")) {
					var $form = $("<form></form>");
					if("<?=$_COOKIE['PM']?>"=="M"){
						$form.attr("action","/m/member/join_agree.php");
					}else{
						$form.attr("action","/member/join.php");
					}

					if("<?=$_COOKIE['PM']?>"!="M"){
						window.opener.name = "parentPage";
						$form.attr("target","parentPage");
					}

					$form.attr("method","post");
					$form.appendTo("body");
					$form.append("<input type='hidden' name='user_Name' value="+ user_Name +">");
					$form.append("<input type='hidden' name='user_Id' value="+ user_Id +">");
					$form.append("<input type='hidden' name='login_Type' value="+ login_Type +">");
					$form.append("<input type='hidden' name='sns_Login' value="+ sns_Login +">");
					$form.append("<input type='hidden' name='user_Email' value="+ user_Email +">");
					$form.submit();

					if("<?=$_COOKIE['PM']?>"!="M"){
					self.close();
					}

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "/m/member/login.php";
					}else{
						self.close();
					}
				}
				return;
			}
		},
		error: function (data, status, err) {
			alert("서버와의 통신이 실패했습니다.");
			alert(err)
			return;
		}
	});

});
</script>