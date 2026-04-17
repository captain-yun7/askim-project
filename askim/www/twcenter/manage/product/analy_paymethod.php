<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";

?>
<script language="JavaScript" type="text/javascript">
<!--
$(function() {

	$('#srh_prev').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#srh_next').datepicker({
		language: 'kr',
		autoClose: true
	});

});

// 기간설정
function setPeriod(from,to,start,end){

	if(from == '' && to == ''){
		document.frm.s_status.value = 'ALL';
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	} else {
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	}
}
//-->
</script>

		<table border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td><img src="../image/ic_tit.gif"></td>
		    <td valign="bottom" class="tit">매출통계분석</td>
		    <td width="2"></td>
		    <td valign="bottom" class="tit_alt">결제수단별, 일자별 통계분석</td>
		  </tr>
		</table>
		<br>

		<form name="frm" action="<?=$PHP_SELF?>" method="get">
		<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
			<tr>
				<td width="15%" class="t_name">&nbsp; 분석방법</td>
				<td width="85%" class="t_value">
					<input type="radio" name="analy_type" value="OP" onClick="this.form.submit();" <? if($analy_type == "OP" || $analy_type == "") echo "checked"; ?>>결제수단별
					<input type="radio" name="analy_type" value="OY" onClick="this.form.submit();" <? if($analy_type == "OY") echo "checked"; ?>>년별
					<input type="radio" name="analy_type" value="OM" onClick="this.form.submit();" <? if($analy_type == "OM") echo "checked"; ?>>월별
					<input type="radio" name="analy_type" value="OD" onClick="this.form.submit();" <? if($analy_type == "OD") echo "checked"; ?>>일별
					<input type="radio" name="analy_type" value="OW" onClick="this.form.submit();" <? if($analy_type == "OW") echo "checked"; ?>>요일별
				</td>
			</tr>
			<tr>
				<td class="t_name">&nbsp; 기 간</td>
				<td class="t_value">
				<?
				$yes_day      = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*1));
				$to_day       = date('Y-m-d');
				$week_day     = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*7));
				$month_day    = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*30));
				$twomonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));

				if(!empty($srh_prev)) $week_day2 = $srh_prev; else $week_day2 = $week_day;
				if(!empty($srh_next)) $to_day2   = $srh_next; else $to_day2   = $to_day;

				?>
					<input type="text" name="srh_prev" id="srh_prev" class="datepicker-here input2" size="15" value="<?=$week_day2?>"> ~ 
					<input type="text" name="srh_next" id="srh_next" class="datepicker-here input2" size="15" value="<?=$to_day2?>">

					<a href="javascript:setPeriod('<?=$to_day?>','<?=$to_day?>','srh_prev','srh_next')"><?=$to_btn?></a>
					<a href="javascript:setPeriod('<?=$yes_day?>','<?=$to_day?>','srh_prev','srh_next')"><?=$ye_btn?></a>
					<a href="javascript:setPeriod('<?=$week_day?>','<?=$to_day?>','srh_prev','srh_next')"><?=$we_btn?></a>
					<a href="javascript:setPeriod('<?=$month_day?>','<?=$to_day?>','srh_prev','srh_next')"><?=$mo_btn?></a>
					<a href="javascript:setPeriod('<?=$twomonth_day?>','<?=$to_day?>','srh_prev','srh_next')"><?=$tmo_btn?></a>

					<input type="image" src="../image/btn_search.gif" align="absmiddle">

				</td>
			</tr>

		</table>
		</form>

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>

      <?
		$prev_period = $week_day2;
		$next_period = $to_day2." 23:59:59";
		$period_sql = " and order_date >= '$prev_period' and order_date <= '$next_period'";

      // 총 매출액
      $sql = "select sum(total_price) as total_price from wiz_order where (status = 'DC' or status = 'CC') $period_sql";
      $result = query($sql) or error("sql error");
      $row = sql_fetch_obj($result);
      $total_perprice = $row->total_price;

			// 결제수단별
			if($analy_type == "OP" || $analy_type == ""){

				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>\n";
				echo "  <tr class='t_th'> \n";
				echo "    <th width='15%'>결제방법</th>\n";
				echo "    <th width='15%'>매출액</th>\n";
				echo "    <th width='15%'>주문건수</th>\n";
				echo "    <th width='15%'>비율</th>\n";
				echo "    <th width='40%'></th>\n";
				echo "  </tr>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>\n";

				$sql = "select pay_method from wiz_order where (status = 'DC' or status = 'CC') $period_sql group by pay_method";
				$result = query($sql) or error("sql error");

				$lists = 10;
				$rows = 24;
				$total = sql_fetch_row($result);
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = 0;

				$sql = "select pay_method , count(*) as count, sum(total_price) as total_price from wiz_order where (status = 'DC' or status = 'CC') $period_sql group by pay_method limit $start, $rows";
				$result = query($sql) or error("sql error");

				while(($row = sql_fetch_obj($result)) && $rows){

					$percent = ceil(($row->total_price/$total_perprice)*100);

					echo "<tr> \n";
					echo "  <td height='30' align='center'>".pay_method($row->pay_method)."</td>\n";
					echo "  <td align='center'>".number_format($row->total_price)."원</td>\n";
					echo "  <td align='center'>".$row->count."</td>\n";
					echo "  <td align='center'>".$percent."%</td>\n";
					echo "  <td><img src='../image/mark_bar.gif' width='".($percent*2)."' height='20' align='absmiddle'> ".number_format($row->total_price)."</td>\n";
					echo "</tr>\n";
					echo "<tr><td colspan='20' class='t_line'></td></tr>\n";

					$no--;
					$rows--;

				}

				if($total <= 0){
					echo "<tr><td height='30' colspan=9 align=center>등록된 매출이 없습니다.</td></tr>";
					echo "<tr><td colspan='20' class='t_line'></td></tr>\n";
				}
				echo "</table>\n";

	    // 년별통계
			}else{

				function getPeriod($priod){

					global $analy_type;

					if($analy_type == "OW"){
				     if($priod == 2) return "월";
				     if($priod == 3) return "화";
				     if($priod == 4) return "수";
				     if($priod == 5) return "목";
				     if($priod == 6) return "금";
				     if($priod == 7) return "토";
				     if($priod == 1) return "일";
				  }else{
				  	return $priod;
				  }
				}

				$sql = "select sum(total_price) as sum_total from wiz_order where status = 'CC' or status = 'DC'";
				$result = query($sql) or error("sql error");
				$row = sql_fetch_obj($result);
				$sum_total = $row->sum_total;

				if($analy_type == "OY"){
					$sql = "select count(orderid) as ordercnt, substring(order_date,1,4) as priod, sum(total_price) as total_price from wiz_order where (status = 'CC' or status = 'DC') $period_sql group by substring(order_date,1,4)";
					$period = "년";
				}else if($analy_type == "OM"){
					$sql = "select count(orderid) as ordercnt, substring(order_date,6,2) as priod, sum(total_price) as total_price from wiz_order where (status = 'CC' or status = 'DC') $period_sql group by substring(order_date,6,2)";
					$period = "월";
				}else if($analy_type == "OD"){
					$sql = "select count(orderid) as ordercnt, substring(order_date,9,2) as priod, sum(total_price) as total_price from wiz_order where (status = 'CC' or status = 'DC') $period_sql group by substring(order_date,9,2)";
					$period = "일";
				}else if($analy_type == "OW"){
					$sql = "select count(orderid) as ordercnt, DAYOFWEEK(order_date) as priod, sum(total_price) as total_price from wiz_order where (status = 'CC' or status = 'DC') $period_sql group by DAYOFWEEK(order_date)";
					$period = "요일";
				}

				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>\n";
				echo " <tr class='t_th'> \n";
				echo "    <th width='10%'>$period</th>\n";
				echo "    <th width='15%'>주문건수</th>\n";
				echo "    <th width='15%'>매출액</th>\n";
				echo "    <th width='10%'>비율</th>\n";
				echo "    <th width='20%'></th>\n";
				echo "  </tr>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>\n";

				$result = query($sql) or error("sql error");
				$total = sql_fetch_row($result);

				while($row = sql_fetch_obj($result)){

					$percent = ($row->total_price/$sum_total)*100;
					$percent = substr($percent,0,strpos($percent,'.')+3);

					echo "<tr> \n";
					echo "  <td height='30' align='center'>".getPeriod($row->priod)."</td>\n";
					echo "  <td align='center'>".$row->ordercnt."</td>\n";
					echo "  <td align='center'>".number_format($row->total_price)."원</td>\n";
					echo "  <td align='center'>".$percent."%</td>\n";
					echo "  <td><img src='../image/mark_bar.gif' width='".($percent*2)."'  height='20' align='absmiddle'></td>\n";
					echo "</tr>\n";
					echo "<tr><td colspan='20' class='t_line'></td></tr>\n";

				}

				if($total <= 0){
					echo "<tr><td height=30 colspan=10 align=center>등록된 매출이 없습니다.</td></tr>";
					echo "<tr><td colspan='20' class='t_line'></td></tr>\n";
				}
				echo "</table>\n";
			}
			?>

<? include "../foot.php"; ?>