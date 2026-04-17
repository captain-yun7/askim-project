<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?php
$c_sql = "select * from wiz_coupon where idx=$idx ";
$c_row = sql_fetch($c_sql);

$cpn_name = $c_row['coupon_name'];
?>
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
		<td width="50%">쿠폰명 : <?=$cpn_name?></td>
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
		<th>회원이름</th>
		<th>회원아이디</th>
		<th>발급시간</th>
		<th>사용여부</th>
	</tr>
	<tr><td class="t_rd" colspan=20></td></tr>
	<?
	$sql = "select wc.coupon_sdate, wc.coupon_edate, wc.wdate, wc.coupon_use, wm.id, wm.name from wiz_mycoupon wc, wiz_member wm where wc.eventidx='$idx' and wc.memid = wm.id";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	$no = $total;

	while($row = sql_fetch_obj($result)){
	?>
	<tr>
		<td height="30" align="center"><?=$no?></td>
		<td align="center"><?=$row->name?></td>
		<td align="center"><?=$row->id?></td>
		<td align="center"><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
		<td align="center"><?=$row->coupon_use?></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
		$no--;
	}
	if($total <= 0){
?>
	<tr bgcolor=ffffff align=center><td height="35" colspan="11">쿠폰발급회원이 없습니다.</td></tr>
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