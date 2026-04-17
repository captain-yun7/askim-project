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
<title>은행계좌번호삭제</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
var service_type  = "<?php echo $oper_info['bankda_service'] ?>";
var partner_id    = "<?php echo $oper_info['bankda_partner_id'] ?>";
var d_bkidx       = "<?php echo $bk_row->idx ?>";

function inputCheck() {

	var frm = document.user_login_addaccount_dr_frm;

	if(frm.bkacctno.value == ""){
		alert("계좌번호를 입력하세요.");
		frm.bkacctno.focus();
		return false;
	}

	var result = false;

	var _params = "directAccess=y";
		_params += "&service_type=" + service_type;
		_params += "&partner_id=" + partner_id;
		_params += "&bkidx=" + d_bkidx;
		_params += "&mode=delete";

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
			$form.attr("action","https://ssl.bankda.com/partnership/user/account_del.php");
			$form.attr("target", "frminfo");
			$form.attr("method","post");
			$form.appendTo("body");
			$form.append("<input type='hidden' name='directAccess' value='y'>");
			$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
			$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
			$form.append("<input type='hidden' name='user_id' value='" + user_id + "'>");
			$form.append("<input type='hidden' name='user_pw' value='" + user_pw + "'>");
			$form.append("<input type='hidden' name='Command' value='update'>");
			$form.append("<input type='hidden' name='bkacctno' value='" + bkacctno + "'>");
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

<BODY onLoad="window.focus();">
<table width="100%" cellpadding=10 cellspacing=0><tr><td>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tbody><tr>
		<td width="50%">은행계좌번호 삭제</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onclick="self.close();" style="cursor:pointer"></td>
	</tr>
</tbody>
</table>
<form name="user_login_addaccount_dr_frm" method="post">
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">
  <tr>
    <td height=25 class="t_name">&nbsp; 계좌번호 <font color="red"><strong>*</strong></font></td>
    <td class="t_value">
		<input type="text" name="bkacctno" value="<?php echo $bk_row->bkacctno ?> " class="input Onum" > * '-' 없이 숫자만 입력하세요.
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

