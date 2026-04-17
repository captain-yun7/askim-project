<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<html>
<head>
<title>:: 이벤트할인쿠폰 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../js/lib.js"></script>
<script type="text/javascript">
function event_coupon(idx){
	var link_url = "<?='/twcenter/product/coupon_down.php?eventidx='?>"+idx;

	opener.frm.eventcouponlink.value = link_url;
	opener.document.getElementById('eventcouponidx').value = idx;
	alert("선택하신 링크주소를 적용시킵니다.")
	self.close();
}
</script>
</head>
<body>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">이벤트 할인쿠폰 리스트</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="center">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan=20></td></tr>
	<tr class="t_th">
		<th width="8%">번호</th>
		<th>쿠폰명</th>
		<th>기간</th>
		<th>할인</th>
		<th>수량</th>
		<th>기능</th>
	</tr>
	<tr><td class="t_rd" colspan=20></td></tr>
<?
	$t_day = date("Y-m-d");
	$sql = "select * from wiz_coupon where coupon_edate >= '$t_day' order by idx desc";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	$no = $total;

	while($row = sql_fetch_arr($result)){
	if($row['coupon_limit'] == "N") $row['coupon_amount'] = "수량제한없음";

?>
	<tr>
		<td height="30" align="center"><?=$no?></td>
		<td><?=$row['coupon_name']?></td>
		<td align="center"><?=$row['coupon_sdate']?> ~ <?=$row['coupon_edate']?></td>
		<td align="center"><?=$row['coupon_dis']?><?=$row['coupon_type']?></td>
		<td align="center"><?=$row['coupon_amount']?></td>
		<td align="center"><input type="radio" onclick="event_coupon(<?=$row['idx']?>)"></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
		$no--;
	}
	if($total <= 0){
?>
  <tr bgcolor=ffffff align=center><td height="35" colspan="11">등록된 쿠폰이 없습니다.</td></tr>
  <tr><td colspan="20" class="t_line"></td></tr>
<?
}
?>
</table>


</td>
</tr>
</table>
</body>
</html>