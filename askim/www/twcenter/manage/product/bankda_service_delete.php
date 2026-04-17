<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/mem_info.php";
include_once "../../inc/bankda_info.php";

?>
<html>
<head>
<title>뱅크다 회원정보수정</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script type="text/javascript" src="/twcenter/js/lib.js"></script>
<script language="JavaScript">
<!--
var service_type  = "<?php echo $oper_info['bankda_service'] ?>";
var partner_id    = "<?php echo $oper_info['bankda_partner_id'] ?>";

function inputCheck(){

	var frm = document.user_join_dr_frm;
	
	if(frm.user_pw.value == ""){
		alert("가입시 등록하셨던 비밀번호를 입력해주세요.");
		frm.user_pw.focus();
		return false;
	}

	var user_id      = $("[name=user_id]").val();
	var user_pw      = $("[name=user_pw]").val();
	var user_pw2     = $("[name=user_pw2]").val();

	var result = false;

	var _params = "directAccess=y";
		_params += "&service_type=" + service_type;
		_params += "&partner_id=" + partner_id;
		_params += "&user_id=" + user_id;
		_params += "&mode=excute";

	$.ajax({
		type: 'POST'
		,url: './bankda_ajaxload.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '9999') {
			alert(data.msg);
			return false;
		} else if(data.result == '0000') {
			alert(data.msg);
			result = true;
		}

		if(result) {

			var $form = $("<form></form>");
			$form.attr("action","https://ssl.bankda.com/partnership/user/user_withdraw.php");
			$form.attr("target", "frminfo");
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='directAccess' value='y'>");
			$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
			$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
			$form.append("<input type='hidden' name='user_id' value='" + user_id + "'>");
			$form.append("<input type='hidden' name='user_pw' value='" + user_pw + "'>");
			$form.append("<input type='hidden' name='command' value='excute'>");
			$form.submit();
			
		}

	});

}

function selfClose() {
	self.close();
	opener.location.reload();
}

//-->
</script>
</head>

<BODY>
<table width="100%" cellpadding=10 cellspacing=0>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" class="popupt">
				<tbody><tr>
					<td width="50%">뱅크다 계정삭제페이지</td>
					<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
				</tr>
			</tbody>
			</table>
			<br>
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="5">
				<tr><td>* 계정삭제는 서비스해지개념으로 서비스이용 불가능합니다.</td></tr>
				<tr><td>* 결과값이 'ok'일 경우 정상적으로 서비스가 해지됩니다.</td></tr>
			</table>
			<br>
			<form name="user_join_dr_frm" method="post">
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="1" class="t_style">
				<tr>
					<td width="30%" height="25" class="t_name">이용자 아이디</td>
					<td class="t_value">
						<input type="text" name="user_id" value="<?php echo $bankda_info['bankda_id'] ?>" class="input" >
					</td>
					</td>
				</tr>
				<tr>
					<td height="25" class="t_name">이용자 비밀번호 </td>
					<td class="t_value"><input type="password" name="user_pw" value="" class="input" placeholder="이용자 비밀번호"></td>
				</tr>
				<tr>
					<td height="25" class="t_name">결과값</td>
					<td class="t_value">
						<iframe id="frminfo" height="20%" frameBorder="0" name="frminfo" scrolling="no"></iframe>
					</td>
				</tr>

			</table>
			
			<br>
			<table width="100%" border=0 cellpadding=0 cellspacing=0 align="center">
			  <tr>
				  <td align="center">
						<input type="button" value="삭제" class="base_btn reg" onclick="inputCheck()">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="selfClose();">
					</td>
			  </tr>
			</table>
			</form>
		</td>
	</tr>
</table>
