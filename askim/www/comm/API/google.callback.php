<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";

echo "<script type=\"text/JavaScript\" src=\"/comm/js/jquery-1.8.2.min.js\"></script>";

include $_SERVER['DOCUMENT_ROOT']."/comm/API/json/JSON.php";

if (!function_exists('json_decode')) {
	function json_decode($content, $assoc=false) {
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
		$json = new Services_JSON;
		return $json->encode($content);
	}
}

$code						= $_GET['code'];
$client_id					= $google_client_id;				//발급받은 클라이언트 아이디값
$client_secret				= $google_client_secret;			//발급받은 클라이언트 시크릿값
$redirect_uri				= $google_redirect_uri;				//지정한 콜백 url값

//ACCESS_TOKEN 발급 API
$url = 'https://accounts.google.com/o/oauth2/token';
$post_data["code"]			= $code;
$post_data["client_id"]		= $client_id;
$post_data["client_secret"] = $client_secret;
$post_data["redirect_uri"]	= $redirect_uri;
$post_data["grant_type"]	= "authorization_code";

$curlsession = curl_init ();
curl_setopt ($curlsession, CURLOPT_URL, $url); 
curl_setopt ($curlsession, CURLOPT_POST, 1); 
curl_setopt ($curlsession, CURLOPT_POSTFIELDS, $post_data);
curl_setopt ($curlsession, 156, 2500);
curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curlsession);
curl_close($curlsession);

$data = json_decode($res, true);
$access_token = $data['access_token'];

//로그인된 사용자 정보 읽어오는 API
$accesurl = "https://www.googleapis.com/plus/v1/people/me?access_token=$access_token";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $accesurl);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_COOKIE, '');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$g = curl_exec($ch);
$h = iconv("UTF-8","EUC-KR",$g);

$info = json_decode($g, true);
$google_id = $info['id'];
$google_name = $info['displayName'];
$google_email = $info['emails'][0]['value'];

$browser = getBrowser2();


if($browser['name']=="Google Chrome"){
	$name = $google_name;
}else{
	$name = iconv("EUC-KR","UTF-8",$google_name);
}

?>
<script type="text/javascript">
$(function(){
	
	var user_Id     = "<?=$google_id?>";
	var user_Email  = "<?=$google_email?>";
	var user_Name   = "<?=$google_name?>";
	var login_Type  = "sns";
	var sns_Login   = "GG";

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

	$.ajax({
		type: "post",
		url: "/twcenter/member/snsAjax/google_ajax.php",
		data: {user_Id: user_Id, user_Name: user_Name, user_Email: user_Email, sns_Login: sns_Login, login_Type: login_Type},
		success: function (data) {
			if (data == "ok") {

				if(confirm("선택하신 계정정보로 가입된 ID가 존재합니다.\n해당ID와 연결하시겠습니까?")){

					var name = encodeURI(user_Name);
					document.cookie = "google_username="+name+"; path=/";
					document.cookie = "google_id="+user_Id+"; path=/";
					document.cookie = "google_email="+user_Email+"; path=/";
					document.cookie = "login_Type="+login_Type+"; path=/";
					document.cookie = "sns_login="+sns_Login+"; path=/";

					location.href = "<?=$google_active_location?>";

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "http://<?=$SERVER_NAME?>/m/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
					}else{
						self.close();
					}				
				}

			} else {

				var name = encodeURI(user_Name);
				if (confirm("구글계정으로 가입하시겠습니까?\n구글계정으로 가입시 추가정보를 입력하셔야 합니다.")) {

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
					$form.append("<input type='hidden' name='user_Email' value="+ user_Email +">");
					$form.append("<input type='hidden' name='login_Type' value="+ login_Type +">");
					$form.append("<input type='hidden' name='sns_Login' value="+ sns_Login +">");
					$form.submit();
					
					if("<?=$_COOKIE['PM']?>"!="M"){
					self.close();
					}

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "http://<?=$SERVER_NAME?>/m/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
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