<? include_once "../../common.php" ?>
<html>
<head>
<title>:: 세금계산서 일괄처리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
</head>
<body>


<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">세금계산서 일괄처리</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td align="center">
			<form action="order_save.php">
			<input type="hidden" name="tmp">
			<input type="hidden" name="mode" value="batchStatusTax">
			<input type="hidden" name="selvalue" value="<?=$selvalue?>">
			<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
			  <tr align=center>
				<td height="23" class="t_name">상태선택</td>
				<td class="t_value">
				<select name="tax_pub" style="width:90" class="select">
					<option value="N">발급대기</option>
					<option value="Y">발급완료</option>
				</select>
				</td>
			  </tr>
			</table>
			<br>
			<table align="center">
			  <tr>
				<td>
					<input type="submit" value="확인" class="search_btn2">&nbsp;
					<input type="button" value="닫기" class="search_default" onClick="self.close();">
				</td>
			  </tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>