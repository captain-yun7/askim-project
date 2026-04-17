<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if($oper_info['coupon_use'] == "Y"){
?>

			<table class="coupon_table" summary="쿠폰내역">
				<caption>쿠폰내역</caption>
				<colgroup>
					<col width="30%" />
					<col width="40%" />
					<col width="" />
					<col width="15%" />
				</colgroup>
				<thead>
					<tr>
						<th>기간</th>
						<th>쿠폰명</th>
						<th>할인액</th>
						<th>사용여부</th>
					</tr>
				</thead>
				<tbody>

<?
$sql = "select * from wiz_mycoupon where memid='{$wiz_session['id']}' and coupon_sdate <= curdate() and coupon_edate >= curdate() order by idx desc";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$rows = 12;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
if($start>1) mysqli_data_seek($result,$start);
$no = $total - $start;
while(($row = sql_fetch_obj($result)) && $rows){
	if($row->coupon_use == "Y") $row->coupon_use = "사용함";
	else  $row->coupon_use = "미사용";
	if($row->coupon_price_limit) $coupon_price_limit = "<br><font color='#aaaaaa'>(".@number_format($row->coupon_price_limit)."원 이상 주문시 사용)";
	else $coupon_price_limit = "";
?>

				<tr>
					<td class="cen"><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
					<td><?=$row->coupon_name?><?=$coupon_price_limit?></td>
					<td class="cen"><?=@number_format($row->coupon_dis)?><?=$row->coupon_type?></td>
					<td class="cen"><?=$row->coupon_use?></td>
				</tr>

<?
	$no--;
	$rows--;
}

if($total <= 0){
?>
				<tr>
					<td colspan="4">쿠폰이 없습니다.</td>
				</tr>

<?
}
?>
			</table>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="50" align="center"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
				</tr>
			</table>

<?php
} else {
	error("쿠폰 기능을 사용할 수 없습니다.");
}
?>