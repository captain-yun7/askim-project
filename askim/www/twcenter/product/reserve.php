<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

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
$sql = "select sum(reserve) as total_reserve from wiz_reserve where memid = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$total_reserve = $row->total_reserve;

// 적립예정금액
$sql = "select sum(reserve_price) as pre_reserve from wiz_order where send_id = '{$wiz_session['id']}' and (status = 'OR' or status = 'OY' or status = 'DR' or status = 'DI')";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$pre_reserve = $row->pre_reserve;
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:0px 0px;">

    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="bpad_30">

<div class="reserve_top">
	<h6>고객님의 <span class="point_txt"> 적립금</span>을 안내드립니다.</h6>
	<div><span class="point_txt">사용 가능 적립금</span><span class="point_txt2"><?=number_format($total_reserve ?? 0)?></span>원<span class="slash">/</span>
	<span class="point_txt">적립 예정금</span><span class="point_txt2"><?=number_format($pre_reserve ?? 0)?></span>원
	</div>
</div>

                <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="218"><img src="<?=$skin_dir?>/image/point_tit.gif" width="218" height="48" border="0"></td>
                    <td align="right" style="background:url(<?=$skin_dir?>/image/point_bg.gif) top left repeat-x; padding-top:5px;">
                    	<table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td class="rpad_10"><img src="<?=$skin_dir?>/image/point01.gif"></td>
                          <td class="rpad_30"><strong><?=number_format($total_reserve)?>원</strong></td>
                          <td class="rpad_10"><img src="<?=$skin_dir?>/image/point02.gif"></td>
                          <td><strong><?=number_format($pre_reserve)?>원</strong></td>
                        </tr>
                      </table>
                    </td>
                    <td width="15" align="right"><img src="<?=$skin_dir?>/image/point_end.gif" width="15" height="48"></td>
                  </tr>
                  <tr><td height="5"></td></tr>
                </table>
 -->
            </td>
          </tr>
          <tr>
            <td>

              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="order_table">
                <tr>
                  <td width="20%" class="table_tit">적립일자</td>
                  <td width="50%" class="table_tit">적립내역</td>
                  <td width="20%" class="table_tit">주문번호</td>
                  <td width="15%" class="table_tit">금액</td>
                </tr>

<?
$sql = "select idx from wiz_reserve where memid = '{$wiz_session['id']}' order by wdate desc";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$rows = 12;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;

$sql = "select * from wiz_reserve where memid = '{$wiz_session['id']}' order by wdate desc limit $start, $rows";
$result = query($sql) or error("sql error");

while(($row = sql_fetch_obj($result)) && $rows){
?>
                <tr height="35">
                  <td align="center"><?=$row->wdate?></td>
                  <td style="padding-left:10px;"><?=$row->reservemsg?></td>
                  <td align="center"><a href="javascript:orderView('<?=$row->orderid?>');"><?=$row->orderid?></a></td>
                  <td align="center" style="padding-left:10px"><?=number_format($row->reserve)?>원</td>
                </tr>

<?
	$rows--;
}

if($total <= 0){
?>
								<tr><td colspan="4" align="center" height="50">적립금내역이 없습니다.</td></tr>

<?
}
?>
              </table>

              <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr><td height="30"></td></tr>
                <tr>
                  <td height="50" align="center"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>

    </td>
  </tr>
</table>