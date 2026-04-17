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
var partner_name  = "<?php echo $oper_info['bankda_partner_name'] ?>";
var sdigit        = "<?php echo $mem_info['sdigit'] ?>";
var edigit        = "<?php echo $mem_info['edigit'] ?>";
var spechk        = "<?php echo $spectial_Char ?>";

function inputCheck(){

	var frm = document.user_join_dr_frm;

	if(frm.user_pw.value == ""){
		alert("이용자 비밀번호를 입력하세요");
		frm.user_pw.focus();
		return false;
	}

	if(frm.user_name.value == ""){
		alert("이용자이름(업체명)을 입력하세요");
		frm.user_name.focus();
		return false;
	}

	var tphone0 = $.trim($("#tphone0").val());
	var tphone1 = $.trim($("#tphone1").val());
	var tphone2 = $.trim($("#tphone2").val());

	if(tphone0 == "") {
		alert("전화번호를 입력하세요");
		$("#tphone0").focus();
		return false;
	} else if(!check_Num(tphone0)) {
		alert("지역번호는 숫자만 가능합니다.");
		$("#tphone0").focus();
		return false;
	}

	if(tphone1 == "") {
		alert("전화번호를 입력하세요");
		$("#tphone1").focus();
		return false;
	} else if(!check_Num(tphone1)) {
		alert("국번은 숫자만 가능합니다.");
		$("#tphone1").focus();
		return false;
	}

	if(tphone2 == "") {
		alert("전화번호를 입력하세요");
		$("#tphone2").focus();
		return false;
	} else if(!check_Num(tphone2)) {
		alert("전화번호는 숫자만 가능합니다");
		$("#tphone2").focus();
		return false;
	}

	var s_email_1 = $("#s_email_1");
	var s_email_2 = $("#s_email_2");

	if(!check_Email2(s_email_1, s_email_2, "올바른 이메일주소를 입력하세요.")) return false;

	var user_id      = $("[name=user_id]").val();
	var user_pw      = $("[name=user_pw]").val();
	var user_pw_new  = $("[name=user_pw_new]").val();
	var user_name    = $("[name=user_name]").val();
	var tmp_tphone   = [];
	$("[name='user_tel[]']").each(function(i){
		tmp_tphone.push($('#tphone' + i).val());
	});
	var user_tel     = tmp_tphone.join('');

	var tmp_tphone2  = [];
	$("[name='user_tel[]']").each(function(i){
		tmp_tphone2.push($('#tphone' + i).val());
	});
	var user_tel2    = tmp_tphone2.join('-');

	var user_email   = $("#s_email_1").val() + "@" + $("#s_email_2").val()

	var result = false;

	var _params = "directAccess=y";
		_params += "&service_type=" + service_type;
		_params += "&partner_id=" + partner_id;
		_params += "&partner_name=" + partner_name;
		_params += "&user_id=" + user_id;
		_params += "&user_pw=" + user_pw;
		_params += "&user_pw_new=" + user_pw_new;
		_params += "&user_name=" + user_name;
		_params += "&user_tel=" + user_tel;
		_params += "&user_tel2=" + user_tel2;
		_params += "&user_email=" + user_email;
		_params += "&char_set=utf-8";
		_params += "&mode=modify";

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
			$form.attr("action","https://ssl.bankda.com/partnership/user/user_info_edit.php");
			$form.attr("target", "frminfo");
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='directAccess' value='y'>");
			$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
			$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
			$form.append("<input type='hidden' name='partner_name' value='" + partner_name + "'>");
			$form.append("<input type='hidden' name='user_id' value='" + user_id + "'>");
			$form.append("<input type='hidden' name='user_pw' value='" + user_pw + "'>");
			$form.append("<input type='hidden' name='user_pw_new' value='" + user_pw_new + "'>");
			$form.append("<input type='hidden' name='user_name' value='" + user_name + "'>");
			$form.append("<input type='hidden' name='user_tel' value='" + user_tel + "'>");
			$form.append("<input type='hidden' name='user_email' value='" + user_email + "'>");
			$form.append("<input type='hidden' name='char_set' value='utf-8'>");
			$form.submit();
			
		}

	});

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
					<td width="50%">뱅크다 회원정보수정</td>
					<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
				</tr>
			</tbody>
			</table>
			<br>
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="5">
				<tr><td>* 결과값은 "뱅크다" 에서 호출되는 값입니다.</td></tr>
				<tr><td>* 이용자 비밀번호 분실시 확인할 방법이 없습니다. "뱅크다"에 확인이 필요합니다.</font></td></tr>
				<tr><td>* 무통장 자동입금확인서비스의 모든정보는 "뱅크다" 에서 관리하고 있습니다.</font></td></tr>
				<tr><td>* 정보수정시 변경하지 않을 항목은 "빈공란" 으로 남겨두세요.</font></td></tr>
			</table>
			<br>
			<form name="user_join_dr_frm" method="post">
			<table width="100%" align="center" border="0" cellpadding="3" cellspacing="1" class="t_style">
				<tr>
					<td width="30%" height="25" class="t_name">이용자 아이디</td>
					<td width="70%" class="t_value">
						<input type="text" name="user_id" value="<?php echo $bankda_info['bankda_id'] ?>" class="input" readonly> <font color="red">* 변경불가</font>
					</td>
				</tr>
				<tr>
					<td height="25" class="t_name">이용자 비밀번호 </td>
					<td class="t_value"><input type="password" name="user_pw" value="" class="input" placeholder="이용자 비밀번호"></td>
				</tr>
				<tr>
					<td height="25" class="t_name">변경할 비밀번호 </td>
					<td class="t_value"><input type="password" name="user_pw_new" value="" class="input" placeholder="변경할 비밀번호"></td>
				</tr>
				<tr>
					<td height="25" class="t_name">이용자 이름(업체명)</td>
					<td class="t_value"><input type="text" name="user_name" value="<?php echo $bankda_info['bankda_name'] ?>" class="input" placeholder="이용자 이름(업체명)"></td>
				</tr>
				<tr>
					<td height="25" class="t_name">연락처</td>
					<td class="t_value">
						<input type="text" name="user_tel[]" value="<?php echo $bk_tphone1?>" id="tphone0" size="5" maxlength="3" class="input Onum"> - 
						<input type="text" name="user_tel[]" value="<?php echo $bk_tphone2?>" id="tphone1" size="5" maxlength="4" class="input Onum"> - 
						<input type="text" name="user_tel[]" value="<?php echo $bk_tphone3?>" id="tphone2" size="5" maxlength="4" class="input Onum">
					</td>
				</tr>
				<tr>
					<td height="25" class="t_name">이메일</td>
					<td class="t_value">
						<input name="user_email[]" id="s_email_1" type="text" class="input" value="<?php echo $bk_email1 ?>"> @ 
						<input name="user_email[]" id="s_email_2" type="text" class="input" value="<?php echo $bk_email2 ?>">
						<select name="email_select" id="email_select" class="select" onchange="change_email(this.value);">
							<?php foreach($_email_data AS $val){ ?>
							<option value="<?php echo $val[0] ?>" <?php if($bk_email2 == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
							<?php } ?>
						</select> 
					</td>
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
						<input type="button" value="확인" class="base_btn reg" onclick="inputCheck()">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="self.close();">
					</td>
			  </tr>
			</table>
			</form>
		</td>
	</tr>
</table>
