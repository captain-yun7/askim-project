<? include "../../common.php"; ?>
<? include "../../inc/twcenter_check.php"; ?>
<html>
<head>
<title>:: <?=$name?>(<?=$id?>) 님의 적립금내역 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="../../js/jquery.cookie.js"></script>
<script language="JavaScript" src="../../js/lib.js"></script>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
   var url = "/twcenter/product/order_view.php?orderid=" + orderid;
   window.open(url, "orderView", "height=640, width=671, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}

function inputCheck(frm){

	if(frm.reserve_gubun.value == ""){
		alert("적립금 +,- 를 선택하세요.");
		frm.reserve_gubun.focus();
		return false;
	}
	if(frm.reserve.value == ""){
		alert("적립금을 입력하세요.");
		frm.reserve.focus();
		return false;
	}else{
		if(!check_Num(frm.reserve.value)){
			alert("적립금은 숫자이어야 합니다.");
			frm.reserve.value = "";
			frm.reserve.select();
			frm.reserve.focus();
			return false;
		}
	}
	if(frm.reservemsg.value == "" || frm.reservemsg.value == "적립내용"){
		alert("적립내용을 입력하세요.");
		frm.reservemsg.value = "";
		frm.reservemsg.focus();
		return false;
	}
}

function inputEmpty(obj,msg){
	if(obj.value == msg){
		obj.value = "";
	}
}

function deleteReserve(idx,memid){
	if(confirm('해당 적립내역을 삭제하시겠습니까?')){
		document.location = "member_save.php?mode=delreserve&idx=" + idx + "&memid=" + memid;
	}
}
//-->
</script>
</head>
<body>


<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%"><?php echo $name ?>(<?php echo $id ?>) 님의 적립금내역</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="99%" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
	<tr><td class="t_rd" colspan="20"></td></tr>
  <tr class="t_th">
    <th width="25%">적립일자</th>
    <th>적립내역</th>
    <th width="15%">금액</th>
    <th width="15%">상세보기<th/td>
  </tr>
  <tr><td class="t_rd" colspan="20"></td></tr>
	<?
	$sql = "select sum(reserve) as total_reserve from wiz_reserve where memid = '$id'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
	$total_reserve = $row->total_reserve;


	$sql = "select * from wiz_reserve where memid = '$id' order by wdate desc";
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
	<td height="40"><?=$row->wdate?></td>
	<td align="left"><?=$row->reservemsg?><? if(!empty($row->orderid)) echo "<a href=javascript:orderView('$row->orderid');>($row->orderid)</a>"; ?></td>
	<td><?=number_format($row->reserve)?>원</td>
	<td>
		<?php echo $ord_view_img_link ?>
		<a href="javascript:;" onclick="deleteReserve('<?=$row->idx?>','<?=$row->memid?>');"><img src='../image/btn_delete_s.gif' border='0' style="vertical-align:middle"></a>
	</td>
  </tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
		$rows--;
	}
	if($total <= 0){
	?>
  <tr bgcolor=ffffff align=center><td height="35" colspan="4">적립내역이 없습니다.</td></tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="99%" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
	<tr>
		<td>
			<div class="sms_tit_s">
				<span class="Left">- 상품구입 적립금은 주문관리에서 <strong>'배송완료'</strong> 처리 후 적립됩니다.</span>
				<span class="Right">총 적립금<span class="sp_tab"></span><?php echo number_format($total_reserve) ?> 원</span>
			</div>
		</td>
	</tr>
</table>
<table align="center" width="99%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="40" align="center"><? print_pagelist($page, $lists, $page_count, "&id=$id"); ?></td>
	</tr>
<tr>
<td align="right">

<form name="frm" action="member_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="mode" value="reserve">
<input type="hidden" name="memid" value="<?=$id?>">
<input type="hidden" name="name" value="<?=$name?>">
<table height="10" border="0" cellpadding="0" cellspacing="0">
  <tr><td height="5"></td></tr>
  <tr>
    <td>
    <select name="reserve_gubun" class="select">
    <option value="+">&nbsp; +&nbsp;
    <option value="-">&nbsp; -&nbsp;
    </select>
    </td>
    <td>&nbsp;<input type="text" name="reserve" value="적립금" size="12" class="input" onClick="inputEmpty(this,'적립금');"></td>
    <td>&nbsp;<input type="text" name="reservemsg" value="적립내용" size="35" class="input" onClick="inputEmpty(this,'적립내용');"></td>
    <td>&nbsp;<input type="submit" value="저장" class="base_btn_s blue"><span class="sp_tab"></span></td>
  </tr>
  <tr><td height="5"></td></tr>
</table>
</form>

</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>