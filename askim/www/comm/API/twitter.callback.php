<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";

echo "<script type=\"text/JavaScript\" src=\"/comm/js/jquery-1.8.2.min.js\"></script>";

$url = "https://api.twitter.com/oauth/access_token";

$post_data["oauth_verifier"] = $_GET['oauth_verifier'];
$post_data["oauth_token"]    = $_GET['oauth_token'];

$curlsession = curl_init (); 
curl_setopt ($curlsession, CURLOPT_URL, $url); 
curl_setopt ($curlsession, CURLOPT_POST, 1); 
curl_setopt ($curlsession, CURLOPT_POSTFIELDS, $post_data); 
curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec ($curlsession); 
$res_iconv = $res;

$res_cut1 = explode("&",$res_iconv);

$res_oauth_token	= explode("=",$res_cut1[0]);
$oauth_token_secret = explode("=",$res_cut1[1]);
$user_id			= explode("=",$res_cut1[2]);
$screen_name		= explode("=",$res_cut1[3]);
$x_auth_expires		= explode("=",$res_cut1[4]);


$res_oauth_token	= $res_oauth_token[1];
$oauth_token_secret = $oauth_token_secret[1];
$user_id			= $user_id[1];
$screen_name		= $screen_name[1];
$x_auth_expires		= $x_auth_expires[1];

$browser = getBrowser2();

if($browser['name']=="Google Chrome"){
	$name = $screen_name;
}else{
	$name = iconv("EUC-KR","UTF-8",$screen_name);
}
?>
<script type="text/javascript">
$(function(){
	
	var user_Id   = "<?=$user_id?>";
	var user_Name = "<?=$screen_name?>";
	var login_Type  = "sns";
	var sns_Login   = "TT";

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
		url: "/twcenter/member/snsAjax/twitter_ajax.php",
		data: {user_Id: user_Id, user_Name: user_Name, sns_Login: sns_Login, login_Type: login_Type},
		success: function (data) {

			if (data == "ok") {

				if(confirm("선택하신 계정정보로 가입된 ID가 존재합니다.\n해당ID와 연결하시겠습니까?")){

					var name = encodeURI(user_Name);
					document.cookie = "twitter_username="+name+"; path=/";
					document.cookie = "twitter_id="+user_Id+"; path=/";
					document.cookie = "login_Type="+login_Type+"; path=/";
					document.cookie = "sns_login="+sns_Login+"; path=/";

					location.href = "<?=$twitter_active_location?>";

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "http://<?=$SERVER_NAME?>/m/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
					}else{
						self.close();
					}

				}

			} else {

				var name = encodeURI(user_Name);
				if (confirm("트위터계정으로 가입하시겠습니까?\n트위터계정으로 가입시 추가정보를 입력하셔야 합니다.")) {

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
