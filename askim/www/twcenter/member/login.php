<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";
include $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$cut = explode("/",$_SERVER['PHP_SELF']);
$PM_value = ($cut[1] == "m" || mobile_check()) ? "M" : "PC";

?>
<script> document.cookie = 'PM=<?php echo $PM_value ?>; expires=os.date("!%a, %e %h %Y %H:%M:%S GMT", os.time() + 60); path=/';</script>
<script> document.cookie = 'prev_page=<?=$prev?>; expires=os.date("!%a, %e %h %Y %H:%M:%S GMT", os.time() + 60); path=/';</script>

<?php
if($oper_info['sns_login_use'] == 'Y' && strpos($oper_info['sns_login_method'], 'GG') !== false) { 
	## 구글 로그인
?>
<script src="https://apis.google.com/js/api:client.js"></script>
<script>
var googleUser = {};
var startApp = function() {
	gapi.load('auth2', function(){
		// Retrieve the singleton for the GoogleAuth library and set up the client.
		auth2 = gapi.auth2.init({
			client_id: '<?=$google_client_id?>',
			cookiepolicy: 'single_host_origin',
			// Request scopes in addition to 'profile' and 'email'
			//scope: 'additional_scope'
		});
		attachSignin(document.getElementById('customBtn'));
	});
};

function attachSignin(element) {
	auth2.attachClickHandler(element, {},
	function(googleUser) {
		//			console.log(googleUser.getBasicProfile().getId());
		var user_Id   = googleUser.getBasicProfile().getId();
		var user_Name = googleUser.getBasicProfile().getName();
		var user_Email = googleUser.getBasicProfile().getEmail();
		var login_Type  = "sns";
		var sns_Login   = "GG";

		$.ajax({
			type: "post",
			url: "/twcenter/member/snsAjax/google_ajax.php",
			data: {user_Id: user_Id, user_Name: user_Name, sns_Login: sns_Login, login_Type: login_Type},
			success: function (data) {

				if (data == "ok") {

					var name = encodeURI(user_Name);
					document.cookie = "google_username="+name+"; path=/";
					document.cookie = "google_id="+user_Id+"; path=/";
					document.cookie = "google_email="+user_Email+"; path=/";
					document.cookie = "login_Type="+login_Type+"; path=/";
					document.cookie = "sns_login="+sns_Login+"; path=/";
					location.href = "<?=$google_active_location?>";

				} else if(data == "is not"){
					location.href = "http://<?=$SERVER_NAME?>/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
				} else {

					var name = encodeURI(user_Name);
					if (confirm("구글계정으로 가입하시겠습니까?\n구글계정으로 가입시 추가정보를 입력하셔야 합니다.")) {
						var $form = $("<form></form>");
						//$form.attr("action","/member/join.php");
						if("<?=$cut[1]?>"=="m"){
							$form.attr("action","/m/member/join_agree.php");
						}else{
							$form.attr("action","/member/join.php");
						}
						$form.attr("method","post");
						$form.appendTo("body");
						$form.append("<input type='hidden' name='user_Name' value="+ user_Name +">");
						$form.append("<input type='hidden' name='user_Email' value="+ user_Email +">");
						$form.append("<input type='hidden' name='user_Id' value="+ user_Id +">");
						$form.append("<input type='hidden' name='login_Type' value="+ login_Type +">");
						$form.append("<input type='hidden' name='sns_Login' value="+ sns_Login +">");
						$form.submit();
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

	}, function(error) {
		//alert(JSON.stringify(error, undefined, 2));
	});
}
</script>
<?php } ?>

<?php
if($oper_info['sns_login_use'] == 'Y' && strpos($oper_info['sns_login_method'], 'KT') !== false) { 
	## 카카오 로그인
?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<!-- <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> -->

<script type="text/javascript">
function kakao_popup(url){
	window.open(url,'','width=600, height=600, scrollbars=yes');
}
</script>
<?php } ?>

<?php
if($oper_info['sns_login_use'] == 'Y' && strpos($oper_info['sns_login_method'], 'NH') !== false) { 
	## 네이버 로그인
?>
<script type="text/javascript" charset="utf-8" src="/comm/API/naver/oauth/jquery.cookie.js"></script>
<script type="text/javascript" charset="utf-8" src="/comm/API/naver/oauth/naverLogin.js"></script>

<script type="text/javascript">
var naver_client_id = "<?=$naver_client_id?>";
var naver_client_secret = "<?=$naver_client_secret?>";
var naver_redirect_uri = "<?=$naver_redirect_uri?>";

function generateState() {
	var oDate = new Date();
	return oDate.getTime();
}

function saveState(state) {
	$.removeCookie("state_token");
	$.cookie("state_token", state);
}

var naver = NaverAuthorize({
	client_id : naver_client_id,
	redirect_uri : naver_redirect_uri,
	client_secret : naver_client_secret
});

function naver_login() {
	var state = generateState();
	saveState(state);
	var uurl = "https://nid.naver.com/oauth2.0/authorize?client_id=" + naver_client_id + 
							"&response_type=code&redirect_uri=" + encodeURIComponent(naver_redirect_uri) + "&state=" + state;;
	if("<?=$cut[1]?>"=="m"){
	document.location.href = uurl;
	}else{
	window.open(uurl,'네이버 로그인','width=600, height=500, scrollbars=yes');
	}
	
	getNaverUserInfo();
}

$("#NaverIdLoginBTN").click( function () {
	var state = generateState();
	saveState(state);
	naver.login(state);
});

function logoutNaver(){
	document.cookie = "naver_id=; path=/";
	document.cookie = "naver_username=; path=/";
	location.href = "./n_mail.php";
}

function getNaverUserInfo(){
	naver.api(URL, tokenInfo.access_token, function(data) {
		var response = data._response.responseJSON;
		alert(response);
		//console.log("success to get user info", response);
		//alert(response.response.email);
	});
}

</script>
<?php } ?>

<?php
if($oper_info['sns_login_use'] == 'Y' && strpos($oper_info['sns_login_method'], 'FB') !== false && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	## 페이스북 로그인
?>
<div id="fb-root"></div>

<script>
	var facebook_appId="<?=$facebook_appId?>";
	var facebook_login_location = "<?=$facebook_login_location?>";

	window.fbAsyncInit = function() {
		FB.init({
			appId: facebook_appId, //사용자 키값
			status: true,
			cookie: true,
			xfbml: true
		});

		// 페이스북 로그인버튼 함수 체크 및 변경
		FB.getLoginStatus( function(response) {

			$.ajax({
				type:"post",
				url : "/twcenter/member/snsAjax/facebook_btnMake.php",
				data : {status : response.status},
				success: function (data) {
					if($("#FBbtn").attr('onclick') == undefined){
					} else {
						$("#FBbtn").attr('onclick',data);
					}
				}
			});

		});

	  };

	  (function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol +
		  '//connect.facebook.net/ko_KR/all.js';
		document.getElementById('fb-root').appendChild(e);
	  }());




	//로그인 후에 사용자 정보 얻어오는 부분
	function fetchUserDetail()
	{
		FB.api('/me', {fields: 'email,name'}, function(response) {

			if(response.id==undefined) return false;

			var user_Id     = response.id;
			var user_Name   = response.name;
			var user_Email  = response.email;
			var login_Type  = "sns";
			var sns_Login   = "FB";

			if(user_Id == ""){
				if("<?=$_COOKIE['PM']?>"=="M"){
					if("<?=$_COOKIE['PM']?>"=="M"){
						alert("SNS로그인 정보를 불러올수없습니다.\n재시도 바랍니다.");
						location.href = "http://<?=$SERVER_NAME?>/m/sub/login.php";
					}else{
						alert("SNS로그인 정보를 불러올수없습니다.\n재시도 바랍니다.");
						self.close();
					}
				}
			}

			$.ajax({
				type: "post",
				url: "/twcenter/member/snsAjax/facebook_ajax.php",
				data: {user_Id: user_Id, user_Name: user_Name, user_Email: user_Email, sns_Login: sns_Login},
				success: function (data) {

					if (data == "ok") {

						if(confirm("선택하신 계정정보로 가입된 ID가 존재합니다.\n해당ID와 연결하시겠습니까?")){

							document.cookie = "facebook_username="+response.name+"; path=/";
							document.cookie = "facebook_id="+response.id+"; path=/";
							document.cookie = "facebook_email="+response.email+"; path=/";
							document.cookie = "login_Type="+login_Type+"; path=/";
							document.cookie = "sns_login="+sns_Login+"; path=/";
							location.href= facebook_login_location;

							return;

						}

					} else {

						var name = encodeURI(user_Name);
						if (confirm("Facebook 계정으로 가입하시겠습니까?\nFacebook 계정으로 가입시 추가정보를 입력하셔야 합니다.")) {
							var $form = $("<form></form>");
							if("<?=$cut[1]?>"=="m"){
								$form.attr("action","/m/sub/join_agree.php");
							}else{
								$form.attr("action","/member/join.php");
							}
							$form.attr("method","post");
							$form.appendTo("body");
							$form.append("<input type='hidden' name='user_Name' value="+ name +">");
							$form.append("<input type='hidden' name='user_Id' value="+ user_Id +">");
							$form.append("<input type='hidden' name='user_Email' value="+ user_Email +">");
							$form.append("<input type='hidden' name='login_Type' value="+ login_Type +">");
							$form.append("<input type='hidden' name='sns_Login' value="+ sns_Login +">");
							$form.submit();
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
	}


	//로그인
	function checkFacebookLogin()
	{
		FB.getLoginStatus(function(response) {		//이미 로그인 되어있는 경우
			if (response.status === 'connected') {
				fetchUserDetail();
			}
			else //새롭게 로그인해야 하는 경우 (로그인 창이 뜬다)
			{
				initiateFBLogin();
			}
		}, {scope: 'email,public_profile'});
	}


	//새로운 로그인창
	function initiateFBLogin()
	{
		FB.login(function(response) {
			fetchUserDetail();
		}, {scope: 'email,public_profile'});
	}

</script>
<?php } ?>
<?php
if($oper_info['sns_login_use'] == 'Y' && strpos($oper_info['sns_login_method'], 'TT') !== false) { 
?>
<?php

## 트위터 로그인 START
DEFINE( 'CONSUMER_KEY', $twitter_consumer_key );
DEFINE( 'CONSUMER_SECRET', $twitter_consumer_secret );

$url = 'https://api.twitter.com/oauth/request_token';

require_once $_SERVER['DOCUMENT_ROOT']."/comm/API/js/Compat/Function/hash_hmac.php";

$Oauth = Array();
$Oauth['oauth_consumer_key'] = CONSUMER_KEY;
$Oauth['oauth_nonce'] = md5(microtime() . mt_rand());
$Oauth['oauth_signature_method'] = 'HMAC-SHA1';
$Oauth['oauth_timestamp'] = time();
$Oauth['oauth_version'] = '1.0';



$Oauth['oauth_signature'] = calculateSignature( 'POST', $url, $Oauth );
$authorization = getAuthorizationHeader( $Oauth );


$curl_session = curl_init( $url );
curl_setopt( $curl_session, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $curl_session, CURLOPT_POST, true );
curl_setopt( $curl_session, CURLINFO_HEADER_OUT, true );
curl_setopt( $curl_session, CURLOPT_HTTPHEADER, Array( 'Authorization: ' . $authorization ) );
$result = curl_exec( $curl_session );
$result_cut1 = explode('&',$result);
$result_cut2 = explode('=',$result_cut1[0]);
$oauth_token_cut = $result_cut2[1];



function getAuthorizationHeader( $parameters )
{
	$authorization = 'OAuth ';

	$j = count( $parameters );
	foreach( $parameters as $key => $val )
	{
		$authorization .= $key . '="' . urlencode( $val ) . '"';

		if( $j-- > 1 )
		{
			$authorization .= ', ';
		}
	}

	return $authorization;
}

function calculateSignature( $method, $url, $parameters, $accessToken = '' )
{
	foreach( $parameters as $key => $val )
	{
		$foo = urlencode( $key );
		unset( $parameters[$key] );

		$parameters[$foo] = urlencode( $val );
	}

	ksort( $parameters );

	$signBase = '';
	$j = count( $parameters );
	foreach( $parameters as $key => $val )
	{
		$signBase .= "{$key}={$val}";

		if( $j-- > 1 )
		{
			$signBase .= '&';
		}
	}

	$signBase = strtoupper( $method ) . '&' . urlencode( $url ) . '&' . urlencode( $signBase );
	$signKey = urlencode( CONSUMER_SECRET ) . '&' . urlencode( $accessToken );
	$signature = base64_encode(php_compat_hash_hmac( 'SHA1', $signBase, $signKey, true));

	return $signature;
}

?>


<script>
	function twitter_login(){
		var oauth_token = '<?=$oauth_token_cut?>';
		var url = "https://api.twitter.com/oauth/authenticate?oauth_token="+oauth_token;

		if("<?=$cut[1]?>"=="m"){
		document.location.href = url;
		}else{
		window.open(url,'트위터 로그인','width=510, height=800');
		}
	}
</script>
<?php } ?>

<?php

$kakao_href = "https://kauth.kakao.com/oauth/authorize?client_id=$kakao_api_key&redirect_uri=$kakao_login_location&response_type=code";

if($cut[1] == "m" || mobile_check()){
	if(trim($kakao_api_key) == "") {
		$kakao_link = "javascript:alert(\"카카오 kakao_api_key 를 설정해주세요.\"); return false;";
	} else {
		$kakao_link = "document.location=\"$kakao_href\"";
	}
} else {	
	if(trim($kakao_api_key) == "") {
		$kakao_link = "javascript:alert(\"카카오 kakao_api_key 를 설정해주세요.\"); return false;";
	} else {
		$kakao_link = "kakao_popup(\"$kakao_href\");";
	}
}

/** SNS 로그인연동 **/
$sns_login_method = explode("/",$oper_info['sns_login_method']);
for($ii=0; $ii<count($sns_login_method)-1; $ii++){
	if($sns_login_method[$ii] == "FB"){
		if(trim($facebook_appId) == "") {
			$onchk = "javascript:alert(\"페이스북 facebook_appid 를 설정해주세요.\"); return false;";
		} else if(!$_SERVER['HTTPS']) {
			$onchk = "javascript:alert(\"페이스북 로그인을 위해 SSL설치가 필요합니다.\"); return false;";
		} else {
			$onchk = "checkFacebookLogin()";
		}
		$facebook_btn = "<img src='/twcenter/member/skin/memberBasic/image/login_facebook_bt.gif' onclick='".$onchk."' id='FBbtn' style='cursor:pointer'>";
	}
	if($sns_login_method[$ii] == "TT"){
		if(trim($twitter_consumer_key) == "") {
			$onchk = "javascript:alert(\"트위터 twitter_consumer_key 를 설정해주세요.\"); return false;";
		} else {
			$onchk = "twitter_login()";
		}
		$twitter_btn  = "<img src='/twcenter/member/skin/memberBasic/image/login_twitter_bt.gif' onclick='".$onchk."' style='cursor:pointer'>";
	}
	if($sns_login_method[$ii] == "KT"){
		$kakaotalk_btn  = "<img src='/twcenter/member/skin/memberBasic/image/login_kakao_bt.gif' onclick='".$kakao_link."' style='cursor:pointer'>";		
	}
	if($sns_login_method[$ii] == "NH"){
		if(trim($naver_client_id) == "") {
			$onchk = "javascript:alert(\"네이버 naver_client_id 를 설정해주세요.\"); return false;";
		} else {
			$onchk = "naver_login()";
		}
		$naver_btn  = "<img src='/twcenter/member/skin/memberBasic/image/login_naver_bt.gif' onclick='".$onchk."' style='cursor:pointer'>";
	}
	if($sns_login_method[$ii] == "GG"){
		if(trim($google_client_id) == "") {
			$onchk = "javascript:alert(\"구글 google_client_id 를 설정해주세요.\"); return false;";
		} else {
			$onchk = "google_login()";
		}
		$google_btn  = "<img src='/twcenter/member/skin/memberBasic/image/login_google_bt.gif' id='customBtn' class='customGPlusSIgnIn' style='cursor:pointer'>
		<script>startApp();</script>";
	}
}


if(!empty($product_idx)){
	$product_idx_get = "&product_idx=$product_idx";
} else {
	$product_idx_get = "";
}


include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/login.php";

?>