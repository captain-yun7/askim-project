<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php"; ?>
<?php
if(!empty($_SESSION['wiz_admin']['id'])) {
echo "<script>document.location='./manage/main/main.php';</script>";
exit;
}
/*
	# 관리자모드 특정아이피만 접근허용
	$allow_ip_array = array(
	"118.130.111.142"
	);

	if(!in_array($_SERVER['REMOTE_ADDR'], $allow_ip_array)){
	echo "
	<script>
		alert('관리자만 접속 가능합니다.');
		location.href='/';
	</script>
	";
	}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$site_info['admin_title']?></title>
<link href="./manage/wiz_style.css" rel="stylesheet" type="text/css" />
<link href='//fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.admin_id.value == ""){
		alert("관리자 아이디를 입력하세요");
		frm.admin_id.focus();
		return false;
	}

	if(frm.admin_pw.value == ""){
		alert("관리자 비밀번호를 입력하세요");
		frm.admin_pw.focus();
		return false;
	}

	if(frm.secure_login != undefined) {
		if(!frm.secure_login.checked){
			frm.action = "/twcenter/login.php";
		}
	}
}

function loginFocus(){

	var frm = document.frm;
	var admin_id = frm.admin_id.value;
	var admin_pw = frm.admin_pw.value;

	if(admin_id == ""){
		frm.admin_id.focus();
	}else{
		if(admin_pw == "") frm.admin_pw.focus();
	}

}

<?php if($autologin == true){ ?>
$(window).on("load", function(){
	$("input[name='admin_id']").val("twcenter");
	$("input[name='admin_pw']").val("1234");
	$(".login_button").trigger("click");
});
<?php } ?>

-->
</script>
</head>
<body style="background-color:#fff" onLoad="loginFocus();">

<div class="twcenter_login">
	<div class="twcenter_login_in">
		<img src="/twcenter/manage/image/adLogin_img.gif" alt="LOGIN Page">
		<h1>ADMIN <span class="po_b">LOGIN</span></h1>
		<p class="stit">관리자 로그인</p>
		<dl>
			<form name="frm" action="<?php //echo $ssl ?>/twcenter/login.php" method="post" onSubmit="return inputCheck(this);">
				<dd><input type="text" name="admin_id" class="twcenter_login_input" placeholder="아이디"></dd>
				<dd><input type="password" name="admin_pw" class="twcenter_login_input" placeholder="비밀번호"></dd>
				<dt><button type="submit" class="login_button">로그인</button></dt>
				<!-- dd class="secure"><?php echo $hide_ssl_start ?><input type="checkbox" name="secure_login" value="Y" checked>보안접속<?php echo $hide_ssl_end ?>
				</dd -->
			</form>
		</dl>
	</div>
</div>

</body>
</html>