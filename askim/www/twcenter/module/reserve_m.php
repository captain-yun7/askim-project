<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

//echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

?>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
	var url = "/twcenter/product/order_view.php?orderid=" + orderid;
	window.open(url, "orderView", "height=640, width=736, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>

<!--적립금내역-->
<?
// 적립금
$sql = "select sum(reserve) as total_reserve from wiz_reserve where memid = '$wiz_session['id']'";
$result = query($sql) or error("sql_error");
$row = sql_fetch_obj($result);
$total_reserve = $row->total_reserve;

// 적립예정금액
$sql = "select sum(reserve_price) as pre_reserve from wiz_order where send_id = '$wiz_session['id']' and (status = 'OR' or status = 'OY' or status = 'DR' or status = 'DI')";
$result = query($sql) or error("sql_error");
$row = sql_fetch_obj($result);
$pre_reserve = $row->pre_reserve;
?>


				<div class="reserve_top">
					<h6>고객님의 <span class="point_txt"> 적립금</span>을 안내드립니다.</h6>
					<div>
						<span class="point_txt">사용 가능 적립금</span><span class="point_txt2"><?=number_format($total_reserve)?></span>원<span class="slash">/</span>
						<span class="point_txt">적립 예정금</span><span class="point_txt2"><?=number_format($pre_reserve)?></span>원
					</div>
				</div>

				<ul class="reserve_list">
					<?
					$sql = "select idx from wiz_reserve where memid = '$wiz_session['id']' order by wdate desc";
					$result = query($sql) or error("sql_error");
					$total = sql_fetch_row($result);

					$rows = 12;
					$lists = 5;
					$page_count = ceil($total/$rows);
					if(!$page || $page > $page_count) $page = 1;
					$start = ($page-1)*$rows;

					$sql = "select * from wiz_reserve where memid = '$wiz_session['id']' order by wdate desc limit $start, $rows";
					$result = query($sql) or error("sql_error");

					while(($row = sql_fetch_obj($result)) && $rows){
					?>

				<li>
					<div>	<strong>적립일</strong><p><?=$row->wdate?></p></div>
					<div>	<strong>주문번호</strong><p><a href="javascript:orderView('<?=$row->orderid?>');"><?=$row->orderid?></a></p></div>
					<div><strong>적립내역</strong><p><?=$row->reservemsg?></p></div>
					<div>	<strong>금액</strong><p><?=number_format($row->reserve)?>원</p></div>	
				</li>

<?
	$rows--;
}

if($total <= 0){
?>
								<li class="no">적립금내역이 없습니다.</li>

<?
}
?>
              </ul>

              <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr><td height="30"></td></tr>
                <tr>
                  <td height="50" align="center"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
                </tr>
              </table>