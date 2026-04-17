<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/api_key.php";
?>
<script type="text/javascript" charset="utf-8" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<?
include $_SERVER['DOCUMENT_ROOT']."/comm/API/xml/xml.php";
include $_SERVER['DOCUMENT_ROOT']."/comm/API/json/JSON.php";

$state1 = $_GET['state'];
$code1  = $_GET['code'];

$client_id = $naver_client_id;
$client_secret = $naver_client_secret;


//ACCESS_TOKEN 생성 
$accesurl = "https://nid.naver.com/oauth2.0/token?client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&state=$state1&code=$code1";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $accesurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
curl_setopt($ch, CURLOPT_COOKIE, '' );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$g = curl_exec($ch);
curl_close($ch);
$data = json_decode($g, true);
$tokenArr = array(
	 'Authorization: '.$data['token_type'].' '.$data['access_token']
);
$access_token = $data['access_token'];

//USER 정보 
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, "https://apis.naver.com/nidlogin/nid/getUserProfile.xml" );
curl_setopt($ch1, CURLOPT_HTTPHEADER, $tokenArr );
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false );
curl_setopt($ch1, CURLOPT_COOKIE, '' );
curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 300);
$h = curl_exec($ch1);
curl_close($ch1);


//USER정도 XML 파싱 
$parser = new XMLParser($h);
$parser->Parse();
$userInfo = $parser->document->response[0];

$email     = $userInfo->email[0]->tagData;
$enc_id    = $userInfo->enc_id[0]->tagData;
$nickname  = $userInfo->nickname[0]->tagData;
$id        = $userInfo->id[0]->tagData;
$gender    = $userInfo->gender[0]->tagData;
$age       = $userInfo->age[0]->tagData;
$birthday  = $userInfo->birthday[0]->tagData;
$name      = $userInfo->name[0]->tagData;

$browser = getBrowser2();
?>
<script type="text/javascript">

$(function(){

	var user_Id     = "<?=$id?>";
	var user_Email  = "<?=$email?>";
	var user_Name   = "<?=$name?>";
	var birthday    = "<?=$birthday?>";
	var login_Type  = "sns";
	var sns_Login   = "NH";

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
		url: "/twcenter/member/snsAjax/naver_ajax.php",
		data: {user_Id: user_Id, user_Name: user_Name, user_Email: user_Email, sns_Login: sns_Login},
		success: function (data) {
			var result = data.split("|");
			if (result[0] == "ok" && result[1] != "") {
				if(confirm("선택하신 계정정보로 가입된 ID가 존재합니다.\n해당ID와 연결하시겠습니까?")){
					var id = result[1];
					var name = encodeURI(user_Name);

					document.cookie = "naver_username="+name+"; path=/";
					document.cookie = "naver_id="+id+"; path=/";
					document.cookie = "naver_email="+user_Email+"; path=/";
					document.cookie = "login_Type="+login_Type+"; path=/";
					document.cookie = "sns_login="+sns_Login+"; path=/";
					document.cookie = "naver_snsId="+user_Id+"; path=/";
					document.cookie = "birthday="+birthday+"; path=/";

					location.href = "<?=$naver_active_location?>";

				}else{
					if("<?=$_COOKIE['PM']?>"=="M"){
						location.href = "http://<?=$SERVER_NAME?>/m/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
					}else{
						opener.location.href = "/member/login.php?prev=<?=urlencode($_COOKIE['prev_page'])?>";
						self.close();
					}

				}

			} else {

				var name = encodeURI(user_Name);
				if (confirm("네이버계정으로 가입하시겠습니까?\n네이버계정으로 가입시 추가정보를 입력하셔야 합니다.")) {
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
					$form.append("<input type='hidden' name='birthday' value="+ birthday +">");
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
