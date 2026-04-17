<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:: 뱅크다 아이디 중복 체크 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<style>
.input {border:0px; font-size:13px; color:#545454; border:1px solid #d4d4d4; background-color:#ffffff; padding:8px 4px;}
input.input_s1 {width:180px;}
.t_style {font-size:12px; background-color: #e1e1e1;}
.t_style td {background:#fff;}
.t_name {font-size:13px; color: #333; font-weight:600; letter-spacing:-0.3pt; background: #f5f5f5 !important; line-height: 28px; padding: 5px 12px; height:28px}
.t_value {font-size:12px; color: #555555; background: #ffffff !important; line-height: 20px; padding: 7px 10px; clear: both;}
.t_name a {color: #11809f;}
.t_name a:hover {text-decoration: underline;}
.btn_blue {display:inline-block; background:#34a2ef; text-align:center; color:#fff; font-weight:500; border:none;}
.btn_blue:hover{background-color:#118cdf}
.btn_red {display:inline-block; background:#6b6b6b; text-align:center; color:#fff; font-weight:500; border:none;}

#pop_search .t_style .btn_blue {width:91px; height:36px; cursor:pointer; font-family:NanumGothic,'NanumGothic', 나눔고딕, NG, "돋움", 굴림, sans-serif; font-size:13px;}
#pop_search .t_style .btn_red {width:50px; height:36px; cursor:pointer; font-family:NanumGothic,'NanumGothic', 나눔고딕, NG, "돋움", 굴림, sans-serif; font-size:13px;}

</style>
<script type="text/javascript" src="/twcenter/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/twcenter/js/lib.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function idCheck(){

	var frm = document.frm;
	var idchk   = frm.user_id.value;
	var alpha   = idchk.search(/^[a-z]/ig);
	var alpha2  = idchk.search(/[A-Z]/ig);
	var number  = idchk.search(/[0-9]/g);

	if(idchk == "") {
		alert("검색할 아이디를 입력하세요.");
		return false;
	}

	if(idchk.length < 3 || idchk.length > 12){
		alert("아이디는 3~12자리의 영문, 숫자만 가능합니다.");
		return false;
	}

	if(idchk.search(/\s/) != -1){
		alert("아이디는 공백없이 입력해주세요.");
		return false;
	}
	
	
	if(number < 0 || alpha < 0){
		alert("아이디는 영문+숫자조합으로 3~12자리만 가능합니다.");
		return false;
	}
	

	if(!check_Char(idchk)){
		alert("아이디는 특수문자를 사용할수 없습니다.");
		idchk = "";
		frm.user_id.focus();
		return false;
	}

/*	var _params = "directAccess=y";
		_params += "&service_type=premium";
		_params += "&partner_id=3way2005";
		_params += "&user_id=" + idchk;

	$.ajax({
		url: "https://ssl.bankda.com/partnership/user/user_id_chk.php",
		dataType: 'jsonp',
		jsonpCallback: "myCallback",
		success: function(data) {
		  console.log('성공 - ', data);
		},
		error: function(xhr) {
		  console.log('실패 - ', xhr);
		}
	});*/
  /*$.ajax({
		type: 'GET'
		,url: 'https://ssl.bankda.com/partnership/user/user_id_chk.php'
		,cache: false
		,data: _params
		,dataType: 'jsonp'
		,jsonpCallback: "Callback",
	}).done(function(data){
		$('#dynamic_div').text('https://ssl.bankda.com/partnership/user/user_id_chk.php');
		/*if(data.result == '0000') {
			alert('ok');
			return false;
		} else {
			alert('fail');
			return false;
		}*/
	//});

	var $form = $("<form></form>");
	$form.attr("action","https://ssl.bankda.com/partnership/user/user_id_chk.php");
	$form.attr("target", "frminfo");
	//window.open("", "frminfo", "height=200, width=200, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	$form.attr("method","post");
	$form.appendTo("body");
	$form.append("<input type='hidden' name='directAccess' value='y'>");
	$form.append("<input type='hidden' name='service_type' value='premium'>");
	$form.append("<input type='hidden' name='partner_id' value='3way2005'>");
	$form.append("<input type='hidden' name='user_id' value='" + frm.user_id.value + "'>");
	$form.submit();

	$("#apply").html("<input type='button' value='적용' class='btn_red' onclick=setId('" + frm.user_id.value + "')>");


}

// 아이디 입력폼으로 전송
function setId(user_id){
	opener.user_join_dr_frm.user_id.value = user_id;
	opener.user_join_dr_frm.idchk.value = 'Y';
	self.close();
}
//-->
</script>
</head>
<body onLoad="document.frm.user_id.focus();" topmargin="0" leftmargin="0">
<div id="pop_search">
	<table border="0" cellpadding="0" cellspacing="0" class="popupt">
		<tbody><tr>
			<td width="50%">뱅크다 아이디 중복확인</td>
			<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
		</tr>
	</tbody>
	</table>
	<br>
	<table width="100%" align="center" border="0" cellpadding="3" cellspacing="5">
		<tr><td>* 중복확인 체크시 나오는 결과값은 "뱅크다" 에서 호출되는 값입니다.</td></tr>
		<tr><td>* 아이디중복 : <font color="red">error:ID already exists</font></td></tr>
	</table>
	<br>
	<table width="100%" align="center" border="0" cellpadding="3" cellspacing="1" class="t_style">
		<tr>
			<td width="25%" height="25" class="t_name">이용자 아이디</td>
			<td class="t_value">
				<!-- 아이디 검색 -->
				<form name="frm" method="post">
				<input type="hidden" name="directAccess" value="y">
				<input type="hidden" name="service_type" value="premium">
				<input type="hidden" name="partner_id" value="3way2005">
					<fieldset>
						<input type="text" name="user_id" class="input input_s1" size="10" value="<?php echo $user_id ?>" maxlength="12">
						<input type="button" value="중복확인" class="btn_blue" onclick="idCheck();"> 
						<span id="apply"></span>
					</fieldset>
				</form>
			</td>
		</tr>
		<tr>
			<td height="25" class="t_name">결과값</td>
			<td class="t_value">
				<iframe id="frminfo" height="20%" frameBorder="0" name="frminfo" scrolling="no"></iframe>
			</td>
		</tr>

	</table>
</div>
</body>
</html>
