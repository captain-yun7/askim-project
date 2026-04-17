<?php
include_once $_SERVER['DOCUMENT_ROOT']."/comm/lib/datepicker_lib.php";

//if(!empty($send_name)) $param = "send_name=$send_name&orderid=$orderid&order_guest=$order_guest";

if(!empty($send_name)) $param = "period_type=$period_type&send_name=$send_name&orderid=$orderid&order_guest=$order_guest";

$yes_day      = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*1));
$to_day       = date('Y-m-d');
$week_day     = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*7));
$month_day    = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*30));
$month2_day   = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));
$month3_day   = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*90));
$month6_day   = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*182));
$prevyear_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*365));

if(!empty($prev_date)){
	$prev_period = $prev_date." 00:00:00";
	$next_period = $next_date." 23:59:59";
} else {
	$prev_period = $month3_day." 00:00:00";
	$next_period = date("Y-m-d")." 23:59:59";
}

$period_sql = " AND wo.order_date between '$prev_period' and '$next_period' ";

if(!empty($prev_date)) $week_day2 = $prev_date; else $week_day2 = $month3_day;
if(!empty($next_date)) $to_day2   = $next_date; else $to_day2   = $to_day;

?>
<script type="text/javascript">
function setPeriod(from,to,type){

	var period_1 = 'period_1';
	var period_2 = 'period_2';

	if(type == 1){
		$("#set1").attr('class',period_1);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
		$("#set6").attr('class',period_2);
	} else if(type == 2){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_1);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
		$("#set6").attr('class',period_2);
	} else if(type == 3){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_1);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
		$("#set6").attr('class',period_2);
	} else if(type == 4){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_1);
		$("#set5").attr('class',period_2);
		$("#set6").attr('class',period_2);
	} else if(type == 5){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_1);
		$("#set6").attr('class',period_2);
	} else if(type == 6){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
		$("#set6").attr('class',period_1);
	}

	$("#type").val(type);

	if(from == '' && to == ''){
		$("#prev_date").val(from);
		$("#next_date").val(to);
	} else {
		$("#prev_date").val(from);
		$("#next_date").val(to);
	}
}

$(function(){

	var period_type = '<?php echo $period_type ?>';
	if(period_type) {
		$("#set" + period_type).addClass('period_1');
		$(".period").not("#set" + period_type).addClass('period_2');
	} else {
		$("#set1").addClass('period_2');
		$("#set2").addClass('period_2');
		$("#set3").addClass('period_2');
		$("#set4").addClass('period_2');
		$("#set5").addClass('period_2');
		$("#set6").addClass('period_2');
	}

});

</script>

<div class="compad2_b">

<form name="frm">
<input type="hidden" name="period_type" id="type">
<?php if(!$wiz_session['id']) { ?>
<input type="hidden" name="order_guest" value="<?php echo $order_guest ?>">
<input type="hidden" name="send_name" value="<?php echo $send_name ?>">
<input type="hidden" name="orderid" value="<?php echo $orderid ?>">
<?php } ?>
<div class="order_list_top">
	<div class="left">
		<div class="button">
			<input type="button" class="period" onClick="setPeriod('<?php echo $to_day ?>','<?php echo $to_day ?>',1)" value="오늘" id="set1"><input type="button" class="period" onClick="setPeriod('<?php echo $week_day ?>','<?php echo $to_day ?>',3)" value="1주일" id="set3"><input type="button" class="period" onClick="setPeriod('<?php echo $month_day ?>','<?php echo $to_day ?>',4)" value="1개월" id="set4"><input type="button" class="period" onClick="setPeriod('<?php echo $month3_day ?>','<?php echo $to_day ?>',5)" value="3개월" id="set5"><input type="button" class="period" onClick="setPeriod('<?php echo $month6_day ?>','<?php echo $to_day ?>',6)" value="6개월" id="set6">
		</div>
		<div class="cal">
			<span><input type="text" name="prev_date" id="prev_date" class="input2" value="<?php echo $week_day2 ?>"></span><span class="hipen">~</span>
			<span><input type="text" name="next_date" id="next_date" class="input2" value="<?php echo $to_day2 ?>"></span>
		</div>
	</div>
	<div class="chech_btn"><input type="submit" value="조회" class="btn_type2"></div>

</div>
</form>
<br>

<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="order_table">
	<tr>
		<td width="12%" class="table_tit">주문일자</td>
		<td width="16%" class="table_tit">주문번호</td>
		<td width="12%" class="table_tit">결제금액</td>
		<td width="10%" class="table_tit">결제방법</td>
		<td width="10%" class="table_tit">배송상태</td>
		<td class="table_tit">운송장번호</td>
		<td class="table_tit" width="10%">상세보기</td>
		<td class="table_tit" width="10%">영수증</td>
	</tr>
<?
if($wiz_session['id'] != ""){
	$search_sql = " wo.send_id = '$wiz_session['id']' ";
} else {
	$search_sql = " wo.orderid = '$orderid' and send_name = '$send_name' ";
}

$sql = "select orderid from wiz_order as wo where $search_sql and wo.status != '' order by wo.order_date desc";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$no = 0;
$rows = 12;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;

$sql = "

	select 
	
		wo.* ,
		wb.prdname,
		wb.del_com as bas_del_com,
		wb.del_num,
		wb.deliver_date
		
	from 
	
		wiz_order as wo,
		wiz_basket as wb
		
	where 
	
		wo.orderid = wb.orderid and
		$search_sql and 
		wo.status != '' 
		$period_sql 
		group by orderid order by wo.order_date desc limit $start, $rows
		
";

$result = query($sql);
while(($row = sql_fetch_obj($result)) && $rows){

	$stacolor = "259D28";
	if($row->status == "OC" || $row->status == "RC" || $row->status == "RD") $stacolor = "ED1C24";

	$ord_view_page = $_SERVER['PHP_SELF']."?ptype=pview&orderid=".$row->orderid."&page=".$page."&".$param;

	$row->order_date = substr($row->order_date, 0, 10);
	list($del_com,$del_code) = explode("|",$row->bas_del_com);

	if(!empty($row->bas_del_com)){

		$query = "select * from wiz_delivery_company where idx='{$del_code}' ";
		$qresult = query($query);
		$_delivery = sql_fetch_arr($qresult);

		$del_trace = $_delivery['del_trace'];
		$del_com   = $_delivery['del_com'];

	} else {
	
		$del_trace = $oper_info['del_trace'];
		$del_com   = $oper_info['del_com'];
	}
?>
	<tr>
	  <td align="center" style="padding:10px 0;"><?=$row->order_date?></td>
	  <td align="center"><a href="<?=$ord_view_page?>"><?=$row->orderid?></a></td>
	  <td align="center"><?=number_format($row->total_price)?>원</td>
	  <td align="center"><?=pay_method($row->pay_method)?></td>
	  <td align="center"><font color="<?=$stacolor?>"><?=order_status($row->status)?></font></td>
	  <td align="center"><?php if($row->del_num != '' ){ ?><a href="<?=$ord_view_page?>">운송장확인</a><?}?></td>
	  <td align="center">
<!-- 		<a href="<?=$ord_view_page?>"><img src="/twcenter/product/image/btn_detail.gif" border="0" align="absmiddle" /></a> -->
		<input type="button" onClick="location.href='<?php echo $ord_view_page ?>'" value="보기" class="btn_type1">
	  </td>
	  <td align="center"><?=receipt_link($oper_info, $row)?></td>
	</tr>
<?
	$rows--;
}
if($total <= 0){
	echo "<tr><td colspan=15 align=center height=60><img src=/twcenter/images/no_icon.gif align=absmiddle>주문내역이 없습니다.</td></tr>";
	echo "<tr><td colspan=15 bgcolor=#dddddd height=1></td></tr>";
}
?>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" align="center"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
  </tr>
</table>
</div>