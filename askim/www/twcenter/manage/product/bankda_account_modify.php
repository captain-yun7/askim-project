<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/oper_info.php";

if(isset($idx) && $idx) {
	$bk_sql = "select * from bank_account where idx = '".$idx."' ";
	$bk_res = query($bk_sql);
	$bk_row = sql_fetch_obj($bk_res);
}
?>
<html>
<head>
<title>은행계좌번호수정</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
var service_type  = "<?php echo $oper_info['bankda_service'] ?>";
var partner_id    = "<?php echo $oper_info['bankda_partner_id'] ?>";
var d_bkcode      = "<?php echo $bk_row->bkcode ?>";
var d_bkdiv       = "<?php echo $bk_row->bkdiv ?>";
var d_bkidx       = "<?php echo $bk_row->idx ?>";


function inputCheck() {

	var frm = document.user_login_addaccount_dr_frm;

	if(frm.bkacctpno_pw.value == ""){
		alert("계좌비밀번호를 입력하세요.");
		frm.bkacctpno_pw.focus();
		return false;
	}

	if(d_bkcode == '04') {
		if(frm.webid.value == ""){
			alert("인터넷뱅킹 ID를 입력하세요.");
			return false;
		}
	} else if(d_bkcode == '26' || d_bkcode == '48') {
		if(frm.webid.value == ""){
			alert("간편조회용 ID를 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("간편조회용 PW를 입력하세요.");
			return false;
		}
	} else if(d_bkcode == '31') {
		if(frm.webid.value == ""){
			alert("안심계좌번호를 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("안심계좌번호 PW를 입력하세요.");
			return false;
		}
	} else if(d_bkcode == '91') {
		if(frm.webid.value == ""){
			alert("뱅킹 ID 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("뱅킹 PW를 입력하세요.");
			return false;
		}
	}

	if(d_bkdiv == "C") {
		if(frm.Bjumin_1.value == "" || frm.Bjumin_2.value == "" || frm.Bjumin_3.value == "") {
			alert("사업자등록번호를 입력해주세요");
			return false;
		}
	} else if(d_bkdiv == "P") {
		if(frm.Mjumin_1.value == "") {
			alert("주민등록번호 앞자리를 입력해주세요");
			return false;
		}
	}

	var result = false;

	var _params = "directAccess=y";
		_params += "&service_type=" + service_type;
		_params += "&partner_id=" + partner_id;
		_params += "&bkrnames=" + frm.renames.value;
		_params += "&bkidx=" + d_bkidx;
		_params += "&mode=modify";

	$.ajax({
		type: 'POST'
		,url: './bankda_account_ajaxload.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '9999') {
			alert(data.msg);
			return false;
		} else if(data.result == '0000') {
			alert(data.msg);
			var user_id  = data.req1;
			var user_pw  = data.req2;
			var bkacctno = frm.bkacctno.value;
			result = true;
		}

		if(result) {

			var $form = $("<form></form>");
			$form.attr("action","https://ssl.bankda.com/partnership/user/account_fix.php");
			$form.attr("target", "frminfo");
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='directAccess' value='y'>");
			$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
			$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
			$form.append("<input type='hidden' name='user_id' value='" + user_id + "'>");
			$form.append("<input type='hidden' name='user_pw' value='" + user_pw + "'>");
			$form.append("<input type='hidden' name='Command' value='update'>");
			$form.append("<input type='hidden' name='bkdiv' value='P'>");
			$form.append("<input type='hidden' name='bkacctno' value='" + bkacctno + "'>");
			$form.append("<input type='hidden' name='bkacctpno_pw' value='" + frm.bkacctpno_pw.value + "'>");
			$form.append("<input type='hidden' name='Mjumin_1' value='" + frm.Mjumin_1.value + "'>");
			$form.append("<input type='hidden' name='Mjumin_2' value='0000000'>");
			$form.append("<input type='hidden' name='Bjumin_1' value='" + frm.Bjumin_1.value + "'>");
			$form.append("<input type='hidden' name='Bjumin_2' value='" + frm.Bjumin_2.value + "'>");
			$form.append("<input type='hidden' name='Bjumin_3' value='" + frm.Bjumin_3.value + "'>");
			$form.append("<input type='hidden' name='webid' value='" + frm.webid.value + "'>");
			$form.append("<input type='hidden' name='webpw' value='" + frm.webpw.value + "'>");
			$form.append("<input type='hidden' name='renames' value='" + frm.renames.value + "'>");
			$form.append("<input type='hidden' name='char_set' value='utf-8'>");
			$form.submit();
			
		}

	});

}

$(function() {

	var bkcode = $("#bkcode").val();
	if(bkcode == '04') {
		$("#bankIdShow").show();
	} else if(bkcode == '26' || bkcode == '48') {
		$("#bankIdShow").show();
		$("#requireInfo").text('간편조회용');
		$("#requirePw").show();
	} else if(bkcode == '31') {
		$("#bankIdShow").show();
		$("#requireInfo").text('안심계좌번호');
		$("#requirePw").show();
	} else if(bkcode == '91') {
		$("#bankIdShow").show();
		$("#requireInfo").text('뱅킹');
		$("#requirePw").show();
	} else {
		$("#bankIdShow").hide();
	}

	var bkdiv = $("#bkdiv").val();
	if(bkdiv == "C") {
		$("#Cshow").show();
		$("#Pshow").hide();
	} else if(bkdiv == "P") {
		$("#Cshow").hide();
		$("#Pshow").show();
	} else {
		$("#Cshow").hide();
		$("#Pshow").hide();
	}

});

function selfClose() {
	self.close();
	opener.location.reload();
}

//-->
</script>
</head>

<BODY onLoad="window.focus();">
<table width="100%" cellpadding=10 cellspacing=0><tr><td>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tbody><tr>
		<td width="50%">은행계좌번호 수정</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
	</tr>
</tbody>
</table>
<br>
<table width="100%" align="center" border="0" cellpadding="3" cellspacing="5">
	<tr><td>* 계좌정보수정이후 <font color='red'>'반드시 계좌조회리스트에서 정확한 결과값을 확인'</font>하시기 바랍니다.</td></tr>
</table>
<br>
<form name="user_login_addaccount_dr_frm" method="post">
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">
  <tr>
    <td width=20% height=25 class="t_name">&nbsp; 계좌정보 <font color="red"><strong>*</strong></font></td>
    <td width=80% class="t_value">
		<select name="bkcode" id="bkcode" class="select" disabled>
			<option value="">계좌정보</option>
			<?php foreach($_bank_code AS $val){ ?>
			<option value="<?php echo $val[0] ?>" <?php if($bk_row->bkcode == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
			<?php } ?>
		</select> 
		<select name="bkdiv" id="bkdiv" class="select" disabled>
			<option value="">구분선택</option>
			<option value="C" <?php echo get_selected($bk_row->bkdiv, 'C') ?>>법인</option>
			<option value="P" <?php echo get_selected($bk_row->bkdiv, 'P') ?>>개인</option>
		</select> <font color="red">* 변경불가</font>
	</td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 계좌번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="bkacctno" value="<?php echo $bk_row->bkacctno ?>" class="input Onum" readonly> <font color="red">* 계좌번호를 변경하시려면 계좌삭제후 다시 등록해주세요.</font>
	</td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 계좌비밀번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value"><input type="password" name="bkacctpno_pw" value="" class="input"> * 은행거래용 비밀번호</td>
  </tr>

  <tr id="Pshow" style="display:none">
    <td height=25 class="t_name">&nbsp; 주민등록번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="Mjumin_1" value="" class="input Onum" size="10" maxlength="6"> * 주민등록번호 앞자리 6자리
	</td>
  </tr>

  <tr id="Cshow" style="display:none">
    <td height=25 class="t_name">&nbsp; 사업자등록번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="Bjumin_1" value="" class="input Onum" size="6"> - 
		<input type="text" name="Bjumin_2" value="" class="input Onum" size="6"> - 
		<input type="text" name="Bjumin_3" value="" class="input Onum" size="6">
	</td>
  </tr>

  <tr id="bankIdShow" style="display:none">
    <td height=25 class="t_name">&nbsp; <span id="requireInfo">인터넷뱅킹</span> <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		ID <input type="text" name="webid" value="" class="input"> &nbsp;&nbsp;&nbsp;
		<span id="requirePw" style="display:none">
		PW <input type="password" name="webpw" value="" class="input">
		</span>
	</td>
  </tr>

  <tr>
    <td height=25 class="t_name">&nbsp; 계좌별명</td>
    <td class="t_value">
		<input type="text" name="renames" value="<?php echo $bk_row->bkrnames ?>" class="input">
	</td>
  </tr>

	<tr>
		<td height="25" class="t_name">&nbsp; 결과값</td>
		<td class="t_value">
			<iframe id="frminfo" height="20%" frameBorder="0" name="frminfo" scrolling="no"></iframe>
		</td>
	</tr>

</table>
<div id="bank_name"></div>
<div id="corporation"></div>
<div id="individual"></div>


<br>
<table width="100%" border=0 cellpadding=0 cellspacing=0 align=center>
  <tr>
	  <td align="center">
			<input type="button" value="확인" class="base_btn reg" onclick="inputCheck()">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="selfClose();">
		</td>
  </tr>
</table>
</form>
</td></tr></table>

