<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
if($mode == "update"){
	$sql = "select * from wiz_option where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
}
?>
<html>
<head>
<title>:: 옵션항목 관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){
	if(frm.opttitle.value == ""){
		alert("옵션명을 입력하세요.");
		frm.opttitle.focus();
		return false;
	}
	if(frm.optcode.value == ""){
		alert("옵션항목을 입력하세요.");
		frm.optcode.focus();
		return false;
	}
}

//-->
</script>
</head>
<body>


<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">옵션목록</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" cellpadding="4">
	<tr>
		<td align="center">

			<form name="frm" action="option_save.php" method="post" onSubmit="return inputCheck(this);">
			<input type="hidden" name="mode" value="<?=$mode?>">
			<input type="hidden" name="idx" value="<?=$idx?>">
			<input type="hidden" name="menucode" value="<?=$menucode?>">
			<table width="100%" align="center" cellspacing="1" cellpadding="2" class="t_style">
			  <tr>
				<td width="120" class="t_name">옵션명</td>
				<td class="t_value"><input type="text" size="32" name="opttitle" value="<?=$row->opttitle?>" class="input"></td>
			  </tr>
			  <tr>
				<td class="t_name">옵션항목</td>
				<td class="t_value">
				<textarea name="optcode" rows="10" cols="37" class="textarea"><?=$row->optcode?></textarea><br>
				* 한줄에 하나의 옵션을 입력하세요
				</td>
			  </tr>
			</table>
			<br>
			<table width="100%" align="center" border="0">
				<tr>
				<td align="center">
				  <input type="submit" value="확인" class="base_btn reg">&nbsp;<input type="button" value="닫기" class="base_btn gray" onClick="self.close();">
				</td>
			  </tr>
			</table>
			</form>

			</td>
		</tr>
</table>
</body>
</html>