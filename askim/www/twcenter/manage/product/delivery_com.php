<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<?

if($save == ""){

	if(empty($mode)) $mode = "insert";
	if(!strcmp($mode, "update")) {
		$query = "select * from wiz_delivery_company where idx={$idx} ";
		$result = query($query);
		$row = sql_fetch_arr($result);
	}
?>
<html>
<head>
<title>배송업체</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.del_com.value == ""){
		alert("배송업체명을 입력하세요");
		frm.del_com.focus();
		return false;
	}
//	if(frm.del_phone.value == ""){
//		alert("배송업체 연락처를 입력하세요");
//		frm.del_phone.focus();
//		return false;
//	}
	if(frm.del_trace.value == ""){
		alert("배송추적설정주소를 입력하세요");
		frm.del_trace.focus();
		return false;
	}
}

function del_change(v){

	var del_com = "";
	var selIdx = v.selectedIndex;
	if(selIdx > 0){ del_com = v[selIdx].text; }

	var del_company = $("#del_company option:selected").val();
	if(del_company == ""){
		$("#del_com").val('');
	} else {
		$("#del_com").val(del_com);
	}

	$("#del_trace").val(del_company);

}
//-->
</script>
</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">배송업체 등록</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<form name="frm" id="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
<input type="hidden" name="save" value="true">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="99%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">
	<tr>
		<td width=30% height=25 class="t_name">&nbsp; 배송업체명</td>
		<td width=70% class="t_value">
		<?php
		$del_com_str     = "CJ대한통운,로젠택배,우체국택배,한진택배,현대택배,KG로지스택배";
		$del_com_list    = explode(",", $del_com_str);
		?>

		<select name="del_company" class="select" id="del_company" onchange="del_change(this)">
			<option value="">직접입력</option>
		<?
		for($ii = 0; $ii < count($del_com_list); $ii++) {
			$del_com_url_str[0] = "https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=";
			$del_com_url_str[1] = "http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=";
			$del_com_url_str[2] = "https://parcel.epost.go.kr/auth.EpostLogin.parcel";
			$del_com_url_str[3] = "http://www.hanjinexpress.hanjin.net/customer/plsql/hddcw07_t.result?wbl_num=";
			$del_com_url_str[4] = "http://www.hydex.net/ehydex/jsp/home/distribution/tracking/tracingView.jsp?InvNo=";
			$del_com_url_str[5] = "http://www.kgbls.co.kr/trace/default.asp?sendno=";
		?>
			<option value="<?=$del_com_url_str[$ii]?>" <? if(!strcmp(trim($row['del_com']), $del_com_list[$ii])) echo "selected" ?>><?=$del_com_list[$ii]?></option>
		<? } ?>
		</select>
		<input type="text" name="del_com" id="del_com" value="<?=$row['del_com']?>" class="input">
		</td>
	</tr>
	<tr>
		<td height=25 class="t_name">&nbsp; 이용유무</td>
		<td class="t_value">
			<input type="checkbox" name="del_com2" value="Y" <?php if($row['del_com2'] == "Y") echo "checked"; ?>> 이용
		</td>
	</tr>
	<tr>
		<td height=25 class="t_name">&nbsp; 연락처</td>
		<td class="t_value"><input type="text" name="del_phone" value="<?=$row['del_phone']?>" class="input"></td>
	</tr>
	<tr>
		<td height=25 class="t_name">&nbsp; 배송추적URL</td>
		<td class="t_value"><input type="text" name="del_trace" id="del_trace" value="<?=$row['del_trace']?>" class="input" size="90"></td>
	</tr>
</table>
</center>

<br>
<table width="100%" border=0 cellpadding=0 cellspacing=0 align=center>
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="self.close();">
		</td>
	</tr>
</table>
</form>

<?php
} else {

	if(!strcmp($mode, "insert")) {

		$del_com2 = trim($del_com2);

		$sql = "insert into wiz_delivery_company (idx, del_com, del_com2, del_phone, del_trace) value ('', '$del_com','$del_com2','$del_phone','$del_trace')";
		query($sql);

		echo "<script>alert('저장되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "update")) {

		$del_com2 = trim($del_com2);

		$sql = "update wiz_delivery_company set del_com='$del_com',del_com2='$del_com2',del_phone='$del_phone',del_trace='$del_trace' where idx={$idx} ";
		query($sql);

		echo "<script>alert('수정되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "delete")) {

		$sql = "delete from wiz_delivery_company where idx={$idx} ";
		query($sql);

		echo "<script>alert('삭제되었습니다.');document.location='shop_oper.php';</script>";

	}

}
?>