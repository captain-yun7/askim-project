<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/bankda_info.php";

if(!empty($bkno)) {

	$sql = "
		select bkname
			 , bkrnames
			 , bkacctno
			 , bkacctno2
		  from bank_account
		 where idx = '".$bkno."' 
	";
	$account_tmp = sql_fetch($sql);

	$bank = $account_tmp['bkname'];
	$account = $account_tmp['bkacctno2'];
	$name = $account_tmp['bkrnames'];

}

?>
<html>
<head>
<title>은행계좌번호등록</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
var service_type  = "<?php echo $oper_info['bankda_service'] ?>";
var partner_id    = "<?php echo $oper_info['bankda_partner_id'] ?>";

function inputCheck() {

	var frm = document.user_login_addaccount_dr_frm;

	if(frm.bkcode.value == ""){
		alert("계좌정보를 선택하세요.");
		frm.bkcode.focus();
		return false;
	}
	if(frm.bkdiv.value == ""){
		alert("구분을 선택하세요.");
		frm.bkdiv.focus();
		return false;
	}

	if(frm.bkacctno.value == ""){
		alert("계좌번호를 입력하세요.");
		frm.bkacctno.focus();
		return false;
	}
	if(frm.bkacctpno_pw.value == ""){
		alert("계좌비밀번호를 입력하세요.");
		frm.bkacctpno_pw.focus();
		return false;
	}

	var bkcode = $("#bkcode").val();
	if(bkcode == '04') {
		if(frm.webid.value == ""){
			alert("인터넷뱅킹 ID를 입력하세요.");
			return false;
		}
	} else if(bkcode == '26' || bkcode == '48') {
		if(frm.webid.value == ""){
			alert("간편조회용 ID를 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("간편조회용 PW를 입력하세요.");
			return false;
		}
	} else if(bkcode == '31') {
		if(frm.webid.value == ""){
			alert("안심계좌번호를 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("안심계좌번호 PW를 입력하세요.");
			return false;
		}
	} else if(bkcode == '91') {
		if(frm.webid.value == ""){
			alert("뱅킹 ID 입력하세요.");
			return false;
		}
		if(frm.webpw.value == ""){
			alert("뱅킹 PW를 입력하세요.");
			return false;
		}
	}

	var bkdiv = $("#bkdiv").val();
	if(bkdiv == "C") {
		if(frm.Bjumin_1.value == "" || frm.Bjumin_2.value == "" || frm.Bjumin_3.value == "") {
			alert("사업자등록번호를 입력해주세요");
			return false;
		}
	} else if(bkdiv == "P") {
		if(frm.Mjumin_1.value == "") {
			alert("주민등록번호 앞자리를 입력해주세요");
			return false;
		}
	}

	if(frm.user_id.value == ""){
		alert("이용자 아이디를 입력하세요");
		frm.user_id.focus();
		return false;
	}
	if(frm.user_pw.value == ""){
		alert("이용자 비밀번호를 입력하세요");
		frm.user_pw.focus();
		return false;
	}

	if(frm.bkacctholer.value == ""){
		alert("예금주를 입력하세요");
		frm.bkacctholer.focus();
		return false;
	}

	var result = false;

	var _params = "directAccess=y";
		_params += "&service_type=" + service_type;
		_params += "&partner_id=" + partner_id;
		_params += "&bkcode=" + bkcode;
		_params += "&bkdiv=" + bkdiv;
		_params += "&bkacctno=" + frm.bkacctno.value;
		_params += "&bkrnames=" + frm.renames.value;
		_params += "&bkacctholer=" + frm.bkacctholer.value;
		_params += "&mode=insert";
		_params += "&bkno=<?php echo $bkno ?>";

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
			result = true;
		}

		if(result) {

			var $form = $("<form></form>");
			$form.attr("action","https://ssl.bankda.com/partnership/user/account_add.php");
			$form.attr("target", "frminfo");
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='directAccess' value='y'>");
			$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
			$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
			$form.append("<input type='hidden' name='user_id' value='" + frm.user_id.value + "'>");
			$form.append("<input type='hidden' name='user_pw' value='" + frm.user_pw.value + "'>");
			$form.append("<input type='hidden' name='Command' value='update'>");
			$form.append("<input type='hidden' name='bkdiv' value='" + frm.bkdiv.value + "'>");
			$form.append("<input type='hidden' name='bkcode' value='" + frm.bkcode.value + "'>");
			$form.append("<input type='hidden' name='bkacctno' value='" + frm.bkacctno.value + "'>");
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

	<?php
	if(!empty($bkno)) {
	?>
		var bkcode = $("#bkcode").val();
		var _params = "bkcode=" + bkcode;

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

		$.ajax({
			type: 'POST'
			,url: './bankda_requestinfo.php'
			,cache: false
			,data: _params
			,dataType: 'json'
		}).done(function(data){
			$("#bank_name").html(data.bank_name);
			$("#corporation").html('법인 : ' + data.COPR);
			$("#individual").html('개인 : ' + data.INDI);

		}).fail(function(request, status, error){
		});
	<?php
	} else {
	?>
	$("#bkcode").on("change", function() {

		var bkcode = $("#bkcode").val();
		var _params = "bkcode=" + bkcode;

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

		$.ajax({
			type: 'POST'
			,url: './bankda_requestinfo.php'
			,cache: false
			,data: _params
			,dataType: 'json'
		}).done(function(data){
			$("#bank_name").html(data.bank_name);
			$("#corporation").html('법인 : ' + data.COPR);
			$("#individual").html('개인 : ' + data.INDI);

		}).fail(function(request, status, error){
		});

	});

	<?php
	}
	?>

	$("#bkdiv").on("change", function() {
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
		<td width="50%">은행계좌번호 추가</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
	</tr>
</tbody>
</table>
<form name="user_login_addaccount_dr_frm" method="post">
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">
  <tr>
    <td width=20% height=25 class="t_name">&nbsp; 계좌정보 <font color="red"><strong>*</strong></font></td>
    <td width=80% class="t_value">
		<select name="bkcode" id="bkcode" class="select">
			<option value="">계좌정보</option>
			<?php foreach($_bank_code AS $val){ ?>
			<option value="<?php echo $val[0] ?>" <?php if($bank == $val[1]) echo "selected";?>><?php echo $val[1] ?></option>
			<?php } ?>
		</select> 
		<select name="bkdiv" id="bkdiv" class="select">
			<option value="">구분선택</option>
			<option value="C">법인</option>
			<option value="P">개인</option>
		</select> 
	</td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 계좌번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="bkacctno" value="<?php echo $account ?>" class="input"> * 하이픈('-')포함해서 입력해주세요.
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
		<input type="text" name="renames" value="" class="input">
	</td>
  </tr>

  <tr>
    <td height=25 class="t_name">&nbsp; 이용자 아이디 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="user_id" value="<?php echo $bankda_info['bankda_id'] ?>" class="input" readonly>
	</td>
  </tr>

  <tr>
    <td height=25 class="t_name">&nbsp; 이용자 비밀번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="password" name="user_pw" value="" class="input">
	</td>
  </tr>

  <tr>
    <td height=25 class="t_name">&nbsp; 예금주 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="bkacctholer" value="" class="input">
	</td>
  </tr>

	<tr>
		<td height="25" class="t_name">&nbsp; 결과값</td>
		<td class="t_value">
			<iframe id="frminfo" height="20%" frameBorder="0" name="frminfo" scrolling="no"></iframe>
		</td>
	</tr>

	<tr>
		<td height="25" class="t_name">&nbsp; 요구정보</td>
		<td class="t_value">
			<div id="bank_name"></div>
			<div id="corporation"></div>
			<div id="individual"></div>
		</td>
	</tr>

</table>
<br>
<table width="100%" border=0 cellpadding=0 cellspacing=0 align=center>
  <tr>
	  <td>
	    &nbsp;<font color='red'>* 계좌추가시 은행별 요구정보가 틀립니다. 반드시 확인해주세요.</font>
	  </td>
  </tr>
</table>

<br>
<table width="100%" border=0 cellpadding=0 cellspacing=0 align=center>
  <tr>
	  <td align="center">
			<input type="button" value="확인" class="base_btn reg" onclick="inputCheck()">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="selfClose()">
		</td>
  </tr>
</table>
</form>
</td></tr></table>

