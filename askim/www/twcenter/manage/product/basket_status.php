<? include_once "../../common.php" ?>
<html>
<head>
<title>:: 주문상태 일괄처리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
</head>
<body>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">취소상태 일괄처리</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" cellpadding=0 cellspacing=10><tr><td align="center">

<form action="order_save.php">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="batchStatusBasket">
<input type="hidden" name="selbasket" value="<?=$selbasket?>">
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
  <tr align=center>
    <td height="23" class="t_name"><b>상태선택</b></td>
    <td class="t_value">
    <select name="chg_status" style="width:90" class="select">
	    <option value="CA">취소신청</option>
	    <option value="CI">처리중</option>
	    <option value="CC">취소완료</option>
    </select>
    </td>
  </tr>
</table>
<br>
<table align="center">
  <tr>
    <td>
		<input type="submit" value="확인" class="base_btn reg">&nbsp;
		<input type="button" value="닫기" class="base_btn gray" onclick="self.close();">
    </td>
  </tr>
</table>
</form>
</td></tr></table>
</body>
</html>