<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<html>
<head>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
</head>

<body topmargin=0 leftmargin=0>
<?php
switch($payment) {
	case "C" : $payment = "충전제"; break;
	case "J" : $payment = "정액제"; break;
	default : $payment = "기타"; break;
}
?>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">충전내용</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="t_style">
  <tr align="center">
    <td width="25%" class="t_name">요금제</td>
    <td width="25%" class="t_name">잔액</td>
    <td width="25%" class="t_name">건당비용</td>
    <td width="25%" class="t_name">남은SMS건수</td>
  </tr>
  <tr align="center">
    <td class="t_value"><?=$payment?></td>
    <td class="t_value"><?=@number_format($coin)?>원</td>
    <td class="t_value"><?=@number_format($gpay)?>원</td>
    <td class="t_value"><? if($gpay > 0) echo @number_format($coin/$gpay); else echo "0"; ?>건</td>
  </tr>
</table>
</body>
</html>