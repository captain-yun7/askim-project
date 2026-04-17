<? include "../../common.php"; ?>
<? include "../../inc/twcenter_check.php"; ?>
<html>
<head>
<title>:: <?=$name?>(<?=$id?>) 님의 주문내역 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
   var url = "/twcenter/product/order_view.php?orderid=" + orderid;
   window.open(url, "orderView", "height=640, width=671, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%"><?php echo $name ?>(<?php echo $id ?>) 님의 주문내역</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>
<div style="text-align:center;">
<table width="99%"cellpadding=0 cellspacing=0 style="margin:0 auto;">
	<tr><td class="t_rd" colspan="20"></td></tr>
  <tr class="t_th">
    <th class="grid60">주문일자</th>
    <th class="grid150">주문번호</th>
    <th class="grid80">결제금액</th>
    <th class="grid80">결제방법</th>
    <th class="grid100">배송상태</th>
    <th class="grid150">운송장번호</th>
    <th class="grid60">상세보기</th>
  </tr>
  <tr><td class="t_rd" colspan="20"></td></tr>
	<?
		$sql = "select * from wiz_order where send_id = '$id' and status != '' order by order_date desc";
		$result = query($sql) or error("sql error");
		$total = sql_fetch_row($result);

		$rows = 12;
		$lists = 5;
		if(!$page) $page = 1;
		$page_count = ceil($total/$rows);
		$start = ($page-1)*$rows;
		if($start>1) mysqli_data_seek($result,$start);

		while(($row = sql_fetch_obj($result)) && $rows){
	?>
  <tr bgcolor=ffffff align=center>
    <td height="30"><?=substr($row->order_date,0,10)?></td>
    <td><?=$row->orderid?></td>
    <td><?=number_format($row->total_price)?> 원</td>
    <td><?=pay_method($row->pay_method)?></td>
    <td><?=order_status($row->status)?></td>
    <td><?=$row->deliver_num?></td>
    <td><a href="javascript:orderView('<?=$row->orderid?>');"><img src="../image/btn_view_s.gif" border="0"></a></td>
  </tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
		$rows--;
	}
	if($total <= 0){
	?>
	  <tr bgcolor=ffffff align=center><td height="35" colspan="7">구매내역이 없습니다.</td></tr>
	  <tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="99%" height="10" border="0" cellpadding="0" cellspacing="0"style="margin:0 auto;">
	<tr><td height="5"></td></tr>
  <tr><td><? print_pagelist($page, $lists, $page_count, "&id=$id"); ?></td></tr>
</table>

</td>
</tr>
</table>
</div>
</body>
</html>