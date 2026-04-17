<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<? include "../head.php"; ?>
<?

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";

$PREV_YEAR  = "2014";
$TO_YEAR    = date("Y");

?>
<link rel="stylesheet" type="text/css" href="/twcenter/js/dist/jquery.jqplot.min.css" />
<script type="text/javascript" src="/twcenter/js/chart/Chart.bundle.js"></script>
<script type="text/javascript" src="/twcenter/js/chart/utils.js"></script>


<script type="text/javascript" src="/twcenter/js/dist/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/excanvas.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.pointLabels.min.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
$(function() {

/*
	$('#srh_prev').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#srh_next').datepicker({
		language: 'kr',
		autoClose: true
	});

*/
});

// 기간설정
function setPeriod(from,to,start,end){
/*
	if(from == '' && to == ''){
		document.frm.s_status.value = 'ALL';
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	} else {
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	}

*/
}
//-->
</script>
<?

$params = array();

if(empty($t_date)) $t_date = "order_date";
if(empty($t_year)) $t_year = date("Y");

$params[] = "t_date=".$t_date;
$params[] = "t_year=".$t_year;


$t_year_sql = " and $t_date like '$t_year%' ";
if(!empty($t_pay_method)){ 
	$t_paymethod_sql = " and pay_method='$t_pay_method' ";
	$params[] = "t_pay_method=".$t_pay_method;
} else {
	$t_paymethod_sql = "";
}

$param = implode("&", $params);

/*if(!empty($t_status)){
	$_arr = implode("/",$t_status);
	$_val = explode("/", $_arr);

	foreach($_val as $key => $value){
		if(!empty($value)) $tmp_staus .= " OR status='$value'";
	}
	$tmp_staus = substr($tmp_staus,3);
	$tmp_staus_sql = " and ({$tmp_staus})";
}*/

## 주문접수
$conn_pc_sql = "

	select 
	
		count(orderid) as total_order_OR,
		sum(total_price) as total_price_OR
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
		
";
$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$pc_total_price_OR = $conn_pc_row['total_price_OR'];
$pc_total_order_OR = $conn_pc_row['total_order_OR'];
if(empty($pc_total_price_OR)) $pc_total_price_OR = 0;
if(empty($pc_total_order_OR)) $pc_total_order_OR = 0;

$conn_mo_sql = "

	select 
	
		count(orderid) as total_order_OR,
		sum(total_price) as total_price_OR
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$mo_total_price_OR = $conn_mo_row['total_price_OR'];
$mo_total_order_OR = $conn_mo_row['total_order_OR'];
if(empty($mo_total_price_OR)) $mo_total_price_OR = 0;
if(empty($mo_total_order_OR)) $mo_total_order_OR = 0;

## 미입금
$conn_pc_sql = "

	select 
	
		sum(total_price) as total_no_price_OR
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and status='OR' and connect_type='PC'
		
";
$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$pc_total_no_price_OR = $conn_pc_row['total_no_price_OR'];
if(empty($pc_total_no_price_OR)) $pc_total_no_price_OR = 0;

$conn_mo_sql = "

	select 
	
		sum(total_price) as total_no_price_OR
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and status='OR' and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$mo_total_no_price_OR = $conn_mo_row['total_no_price_OR'];
if(empty($mo_total_no_price_OR)) $mo_total_no_price_OR = 0;

## 배송비
$conn_pc_sql = "

	select 
	
		sum(deliver_price) as deliver_price
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
		
";
$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$pc_deliver_price = $conn_pc_row['deliver_price'];
if(empty($pc_deliver_price)) $pc_deliver_price = 0;

$conn_mo_sql = "

	select 
	
		sum(deliver_price) as deliver_price
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$mo_deliver_price = $conn_mo_row['deliver_price'];
if(empty($mo_deliver_price)) $mo_deliver_price = 0;

## 쿠폰사용
$conn_pc_sql = "

	select 
	
		sum(coupon_use) as coupon_use
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
		
";
$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$pc_coupon_use = $conn_pc_row['coupon_use'];
if(empty($pc_coupon_use)) $pc_coupon_use = 0;

$conn_mo_sql = "

	select 
	
		sum(coupon_use) as coupon_use
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$mo_coupon_use = $conn_mo_row['coupon_use'];
if(empty($mo_coupon_use)) $mo_coupon_use = 0;

## 적립금사용
$conn_pc_sql = "

	select 
	
		sum(reserve_use) as reserve_use
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
		
";
$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$pc_reserve_use = $conn_pc_row['reserve_use'];
if(empty($pc_reserve_use)) $pc_reserve_use = 0;

$conn_mo_sql = "

	select 
	
		sum(reserve_use) as reserve_use
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$mo_reserve_use = $conn_mo_row['reserve_use'];
if(empty($mo_reserve_use)) $mo_reserve_use = 0;

## 주문취소
$conn_pc_sql = "

	select 
	
		sum(total_price) as total_price_OC,
		count(orderid) as total_order_OC
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and status='OC' and connect_type='PC'
		
";

$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$total_pc_price_OC = $conn_pc_row['total_price_OC'];
$total_pc_order_OC = $conn_pc_row['total_order_OC'];

if(empty($total_pc_price_OC)) $total_pc_price_OC = 0;
if(empty($total_pc_order_OC)) $total_pc_order_OC = 0;

$conn_mo_sql = "

	select 
	
		sum(total_price) as total_mo_price_OC,
		count(orderid) as total_mo_order_OC
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and status='OC' and connect_type='M'
		
";
$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$total_mo_price_OC = $conn_mo_row['total_mo_price_OC'];
$total_mo_order_OC = $conn_mo_row['total_mo_order_OC'];

if(empty($total_mo_price_OC)) $total_mo_price_OC = 0;
if(empty($total_mo_order_OC)) $total_mo_order_OC = 0;

## 환불/교환관련
$conn_pc_sql = "

	select 
	
		sum(total_price) as total_price_EX,
		count(orderid) as total_order_EX
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='PC'
		
";

$conn_pc_result = query($conn_pc_sql);
$conn_pc_row = sql_fetch_arr($conn_pc_result);

$total_pc_price_EX = $conn_pc_row['total_price_EX'];
$total_pc_order_EX = $conn_pc_row['total_order_EX'];

if(empty($total_pc_price_EX)) $total_pc_price_EX = 0;
if(empty($total_pc_order_EX)) $total_pc_order_EX = 0;

$conn_mo_sql = "

	select 
	
		sum(total_price) as total_price_EX,
		count(orderid) as total_order_EX
		
	from 
	
		wiz_order 
		
	where 1
	
		$t_year_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='M'
		
";

$conn_mo_result = query($conn_mo_sql);
$conn_mo_row = sql_fetch_arr($conn_mo_result);

$total_mo_price_EX = $conn_mo_row['total_price_EX'];
$total_mo_order_EX = $conn_mo_row['total_order_EX'];

if(empty($total_mo_price_EX)) $total_mo_price_EX = 0;
if(empty($total_mo_order_EX)) $total_mo_order_EX = 0;

//-----------------------------------------------------------

$total_price_OR      = (int)($pc_total_price_OR + $mo_total_price_OR);
$total_order_OR      = (int)($pc_total_order_OR + $mo_total_order_OR);

$total_no_price_OR   = (int)($pc_total_no_price_OR + $mo_total_no_price_OR);

$total_deliver_price = (int)($pc_deliver_price + $mo_deliver_price);

$total_coupon_use    = (int)($pc_coupon_use + $mo_coupon_use);

$total_reserve_use   = (int)($pc_reserve_use + $mo_reserve_use);

$total_price_OC      = (int)($total_pc_price_OC + $total_mo_price_OC);
$total_order_OC      = (int)($total_pc_order_OC + $total_mo_order_OC);

$total_price_EX      = (int)($total_pc_price_EX + $total_mo_price_EX);
$total_order_EX      = (int)($total_pc_order_EX + $total_mo_order_EX);

$total_pc_price      = $pc_total_price_OR;
$total_mo_price      = $mo_total_price_OR;
$total_price         = $total_price_OR;


for($i=1; $i<=12; $i++){

	$g_month_play       = (strlen($i)==1) ? "0".$i : $i;
	$g_year_month_sql   = " and $t_date like '".$t_year."-".$g_month_play."%' ";

	## 주문접수
	//-- PC
	${"conn_pc_sql_".$i} = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$g_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
			
	";
	${"conn_pc_result_".$i} = query(${"conn_pc_sql_".$i});
	${"conn_pc_row_".$i} = sql_fetch_arr(${"conn_pc_result_".$i});

	${"g_pc_total_price_OR_".$i} .= ${"conn_pc_row_".$i}['total_price_OR'];
	if(empty(${"conn_pc_row_".$i}['total_price_OR'])) ${"g_pc_total_price_OR_".$i} = 0;

	//-- 모바일
	${"conn_mo_sql_".$i} = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$g_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
			
	";
	${"conn_mo_result_".$i} = query(${"conn_mo_sql_".$i});
	${"conn_mo_row_".$i} = sql_fetch_arr(${"conn_mo_result_".$i});

	${"g_mo_total_price_OR_".$i} .= ${"conn_mo_row_".$i}['total_price_OR'];
	if(empty(${"conn_mo_row_".$i}['total_price_OR'])) ${"g_mo_total_price_OR_".$i} = 0;

	## 주문접수(PG)
	${"conn_pg_sql_".$i} = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$g_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and (pay_method='PC' or pay_method='PN' or pay_method='PV' or pay_method='KK')
			
	";
	${"conn_pg_result_".$i} = query(${"conn_pg_sql_".$i});
	${"conn_pg_row_".$i} = sql_fetch_arr(${"conn_pg_result_".$i});

	${"g_pg_total_price_OR_".$i} .= ${"conn_pg_row_".$i}['total_price_OR'];
	if(empty(${"conn_pg_row_".$i}['total_price_OR'])) ${"g_pg_total_price_OR_".$i} = 0;


	## 주문접수(무통장)
	${"conn_ac_sql_".$i} = "

		select 
		
			count(orderid) as total_order_OR,
			sum(total_price) as total_price_OR
			
		from 
		
			wiz_order 
			
		where 1
		
			$g_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and pay_method='PB'
			
	";
	${"conn_ac_result_".$i} = query(${"conn_ac_sql_".$i});
	${"conn_ac_row_".$i} = sql_fetch_arr(${"conn_ac_result_".$i});

	${"g_ac_total_price_OR_".$i} .= ${"conn_ac_row_".$i}['total_price_OR'];
	if(empty(${"conn_ac_row_".$i}['total_price_OR'])) ${"g_ac_total_price_OR_".$i} = 0;

	$g_pc_total_price_OR     .= ${"g_pc_total_price_OR_".$i}.",";
	$g_mo_total_price_OR     .= ${"g_mo_total_price_OR_".$i}.",";
	$g_pg_total_price_OR     .= ${"g_pg_total_price_OR_".$i}.",";
	$g_ac_total_price_OR     .= ${"g_ac_total_price_OR_".$i}.",";

}
$g_pc_total_price_OR = (substr($g_pc_total_price_OR, -1) == ',') ? substr_replace($g_pc_total_price_OR, '', -1) : $g_pc_total_price_OR;
$g_mo_total_price_OR = (substr($g_mo_total_price_OR, -1) == ',') ? substr_replace($g_mo_total_price_OR, '', -1) : $g_mo_total_price_OR;
$g_pg_total_price_OR = (substr($g_pg_total_price_OR, -1) == ',') ? substr_replace($g_pg_total_price_OR, '', -1) : $g_pg_total_price_OR;
$g_ac_total_price_OR = (substr($g_ac_total_price_OR, -1) == ',') ? substr_replace($g_ac_total_price_OR, '', -1) : $g_ac_total_price_OR;

?>
<script type="text/javascript">
$(function() {


	var PC = [<?=$g_pc_total_price_OR?>];
	var MO = [<?=$g_mo_total_price_OR?>];
	var PG = [<?=$g_pg_total_price_OR?>];
	var AC = [<?=$g_ac_total_price_OR?>];
	var ticks = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
	var color = Chart.helpers.color;
	var barChartData = {
		labels: ticks,
		datasets: [{
			type: 'bar',
			label: 'PC주문',
			borderColor: window.chartColors.red,
			backgroundColor: color(window.chartColors.red).alpha(0.6).rgbString(),
			borderWidth: 1,
			data: PC
		},
		{
			type: 'bar',
			label: 'Mobile주문',
			borderColor: window.chartColors.blue,
			backgroundColor: color(window.chartColors.blue).alpha(0.6).rgbString(),
			borderWidth: 1,
			data: MO
		},
		{
			type: 'line',
			label: 'PG결제',
			borderColor: '#3cba9f',
			backgroundColor: color(window.chartColors.black).alpha(0).rgbString(),
			borderWidth: 2,
			data: PG
		},
		{
			type: 'line',
			label: '무통장결제',
			borderColor: '#847979',
			backgroundColor: color(window.chartColors.black).alpha(0).rgbString(),
			borderWidth: 2,
			data: AC
		}]

	};

	var ctx = document.getElementById('graph_canvas').getContext('2d');
	window.myBar = new Chart(ctx, {
		type: 'bar',
		data: barChartData,
		options: {
			responsive: true,
			legend: {
				position: 'left',
			},
			title: {
				display: false,
			},
			scales: {
				yAxes: [
					{
						ticks: {
							min: 0,
							callback: function(label, index, labels) {
								return label.toLocaleString()+'원';
							},
						},
						scaleLabel: {
							display: false
						}
					}
				]
			}
		}
	});

	/*
	var PC = [<?=$g_pc_total_price_OR?>];
	var MO = [<?=$g_mo_total_price_OR?>];
	var PG = [<?=$g_pg_total_price_OR?>];
	var AC = [<?=$g_ac_total_price_OR?>];
	var ticks = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

	$.jqplot("graph",[PC,MO,PG,AC],{
		animate: !$.jqplot.use_excanvas,
		seriesDefaults: {
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: false }
		},
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			pad : 1.2,
			tickOptions: {
				fontFamily: 'NanumGothic, 나눔고딕',
				textColor: '#000000',
				fontSize: '8pt'
			}
		},
		series:[{renderer:$.jqplot.BarRenderer, label:'PC주문'}, {renderer:$.jqplot.BarRenderer, label:'Mobile주문'}, {label:'PG결제', disableStack:true}, {label:'무통장결제', disableStack:true}],
		seriesDefaults: {
			rendererOptions:{ 
				highlightMouseOver:true
			}
		},
		//series:[
		//	{label:'PC주문',shadow: true},
		//	{label:'Mobile주문',shadow: true},
		//	{label:'PG결제',shadow: true},
		//	{label:'무통장결제',shadow: true}
		//],
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			},
			yaxis: {
				pad: 0,
				tickOptions: {
					formatString: "%'d원",
					show: true
				}
			}
		},
		highlighter : {
			show: true,
			sizeAdjust: 6,
			formatString: "<a %s /> %s"
		},
		legend:{
			show:true,
			location: 'nw',
			xoffset: 0,
			yoffset: 0,
			placement: 'outsideGrid'
		},
		grid:{
			show:true,
			background:'#ffffff'
		}

	});
	*/
	
});

function excelDown(){
	location.href="_total_month_analy_excel.php?<?php echo $param; ?>";
}

</script>

		<table border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td><img src="../image/ic_tit.gif"></td>
		    <td valign="bottom" class="tit">월별매출</td>
		    <td width="2"></td>
		    <td valign="bottom" class="tit_alt">결제수단별, 일자별 통계분석</td>
		  </tr>
		</table>
		<br>

		<form name="frm" action="<?=$PHP_SELF?>" method="get">
		<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
			<tr>
				<td width="12%" class="t_name">&nbsp; 월별기간</td>
				<td width="38%" class="t_value">
					<select name="t_year" class="select">
						<?
						for($i=$PREV_YEAR; $i<=$TO_YEAR; $i++){
						?>
						<option value="<?=$i?>" <? if($i==$t_year) echo "selected";?>><?=$i?>년</option>
						<?
						}
						?>
					</select>
				</td>
				<td width="12%" class="t_name">&nbsp; 기준일자</td>
				<td width="38%" class="t_value">
					<select name="t_date" class="select">
						<option value="order_date">주문일</option>
						<option value="pay_date">입금일</option>
						<option value="send_date">배송완료일</option>
					</select>
					<script language="javascript">
					<!--
					t_date = document.frm.t_date;
					for(ii=0; ii<t_date.length; ii++){
					 if(t_date.options[ii].value == "<?=$t_date?>")
						t_date.options[ii].selected = true;
					}
					-->
					</script>
				</td>
			</tr>
			<tr>
				<!-- <td width="12%" class="t_name">&nbsp; 처리상태</td>
				<td width="38%" class="t_value">
					<input type="checkbox" name="t_status[]" value="OR">주문접수&nbsp;
					<input type="checkbox" name="t_status[]" value="OY">결제완료&nbsp;
					<input type="checkbox" name="t_status[]" value="DR">배송준비중&nbsp;
					<input type="checkbox" name="t_status[]" value="DI">배송처리&nbsp;
					<input type="checkbox" name="t_status[]" value="DC">배송완료
				</td> -->
				<td width="12%" class="t_name">&nbsp; 결제수단</td>
				<td width="88%" class="t_value" colspan="3" height="35">
					<span style="vertical-align: middle"><input type="radio" name="t_pay_method" value="" <? if($t_pay_method == '') echo "checked";?>></span>전체
					<?
					$pay_method = explode("/",$oper_info['pay_method']);
					for($ii=0; $ii<count($pay_method)-1; $ii++){
						$pay_title = pay_method($pay_method[$ii]);
					?>
					<span style='vertical-align: middle;'><input type="radio" name="t_pay_method" value="<?=$pay_method[$ii]?>" <? if($pay_method[$ii]==$t_pay_method) echo "checked";?>></span><?=$pay_title?>&nbsp;
					<?
					}
					?>
					<?
					if($oper_info['kakao_pay_use'] == "Y"){
					?>
					<span style="vertical-align: middle"><input type="radio" name="t_pay_method" value="KK" <? if($t_pay_method == 'KK') echo "checked";?>></span>카카오페이
					<?
					}
					?>
				</td>
			</tr>
		</table>
		<!-- <table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center"><input type="image" src="../image/btn_search.gif" align="absmiddle"></td>
			</tr>
		</table> -->
		<br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>'">
				</td>
			</tr>
		</table>
		</form>

		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> <?=$t_year?>년 총 매출집계</td>
				<td style="text-align:right;padding-bottom:5px;">
					<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
			<tr>
				<td rowspan='2' class="t_name_analy">구분</td>
				<td colspan='2' class="t_name_analy">주문</td>
				<td rowspan='2' class="t_name_analy">미입금</td>
				<td colspan='5' class="t_name_analy">결제</td>
				<td colspan='2' class="t_name_analy">주문취소</td>
				<td colspan='2' class="t_name_analy">반품/교환</td>
			</tr>
			<tr>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">주문금액</td>
				<td class="t_name_analy">배송비</td>
				<td class="t_name_analy">할인가</td>
				<td class="t_name_analy">쿠폰</td>
				<td class="t_name_analy">적립금</td>
				<td class="t_name_analy">실결제금액</td>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">금액</td>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">금액</td>
			</tr>
			<tr>
				<td class="t_name_analy">PC</td>
				<td class="t_value_analy"><?=number_format($pc_total_order_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_total_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_total_no_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_deliver_price)?></td>
				<td class="t_value_analy">0</td>
				<td class="t_value_analy"><?=number_format($pc_coupon_use)?></td>
				<td class="t_value_analy"><?=number_format($pc_reserve_use)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_order_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_order_EX)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price_EX)?></td>
			</tr>
			<tr>
				<td class="t_name_analy">Mobile</td>
				<td class="t_value_analy"><?=number_format($mo_total_order_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_total_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_total_no_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_deliver_price)?></td>
				<td class="t_value_analy">0</td>
				<td class="t_value_analy"><?=number_format($mo_coupon_use)?></td>
				<td class="t_value_analy"><?=number_format($mo_reserve_use)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_order_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_order_EX)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price_EX)?></td>
			</tr>
			<tr>
				<td class="t_name_analy"><strong>합계</strong></td>
				<td class="t_value_analy_total"><?=number_format($total_order_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_no_price_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_deliver_price)?></td>
				<td class="t_value_analy_total">0</td>
				<td class="t_value_analy_total"><?=number_format($total_coupon_use)?></td>
				<td class="t_value_analy_total"><?=number_format($total_reserve_use)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price)?></td>
				<td class="t_value_analy_total"><?=number_format($total_order_OC)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_OC)?></td>
				<td class="t_value_analy_total"><?=number_format($total_order_EX)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_EX)?></td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="10"></td></tr>
		</table>
		<br>

		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> <?=$t_year?>년 월별 매출통계</td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
			<tr>
				<td>
					<table width="100%" cellspacing="1" cellpadding="3" border="0">
						<tr>
							<td align="center" height="250">
								<div id="graph2" style='width: 95%; max-height: 230px; margin: auto;'>
									<canvas id="graph_canvas" height="47"></canvas>
								</div>
								<!-- <div id="chart2" style="margin-left:7px; width: 100%; height: 220px; margin: auto;"></div> -->
							</td>
						</tr>
					</table>

				</td>
			</tr> 
		</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="10"></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
			<tr>
				<td rowspan='2' colspan='2' class="t_name_analy">구분</td>
				<td colspan='2' class="t_name_analy">주문</td>
				<td rowspan='2' class="t_name_analy">미입금</td>
				<td colspan='5' class="t_name_analy">결제</td>
				<td colspan='2' class="t_name_analy">주문취소</td>
				<td colspan='2' class="t_name_analy">반품/교환</td>
			</tr>
			<tr>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">주문금액</td>
				<td class="t_name_analy">배송비</td>
				<td class="t_name_analy">할인가</td>
				<td class="t_name_analy">쿠폰</td>
				<td class="t_name_analy">적립금</td>
				<td class="t_name_analy">실결제금액</td>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">금액</td>
				<td class="t_name_analy">건수</td>
				<td class="t_name_analy">금액</td>
			</tr>
			<?PHP 
			for($i=1; $i<=12; $i++){

				$month_play       = (strlen($i)==1) ? "0".$i : $i;
				$t_year_month_sql = " and $t_date like '".$t_year."-".$month_play."%' ";

				//error_reporting(E_ALL); 
				## 주문접수
				$conn_pc_sql = "

					select 
					
						count(orderid) as total_order_OR,
						sum(total_price) as total_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_total_price_OR = $conn_pc_row['total_price_OR'];
				$pc_total_order_OR = $conn_pc_row['total_order_OR'];
				if(empty($pc_total_price_OR)) $pc_total_price_OR = 0;
				if(empty($pc_total_order_OR)) $pc_total_order_OR = 0;

				$conn_mo_sql = "

					select 
					
						count(orderid) as total_order_OR,
						sum(total_price) as total_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OR' or status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_total_price_OR = $conn_mo_row['total_price_OR'];
				$mo_total_order_OR = $conn_mo_row['total_order_OR'];
				if(empty($mo_total_price_OR)) $mo_total_price_OR = 0;
				if(empty($mo_total_order_OR)) $mo_total_order_OR = 0;

				## 미입금
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_no_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and status='OR' and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_total_no_price_OR = $conn_pc_row['total_no_price_OR'];
				if(empty($pc_total_no_price_OR)) $pc_total_no_price_OR = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_no_price_OR
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and status='OR' and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_total_no_price_OR = $conn_mo_row['total_no_price_OR'];
				if(empty($mo_total_no_price_OR)) $mo_total_no_price_OR = 0;

				## 배송비
				$conn_pc_sql = "

					select 
					
						sum(deliver_price) as deliver_price
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_deliver_price = $conn_pc_row['deliver_price'];
				if(empty($pc_deliver_price)) $pc_deliver_price = 0;

				$conn_mo_sql = "

					select 
					
						sum(deliver_price) as deliver_price
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_deliver_price = $conn_mo_row['deliver_price'];
				if(empty($mo_deliver_price)) $mo_deliver_price = 0;

				## 쿠폰사용
				$conn_pc_sql = "

					select 
					
						sum(coupon_use) as coupon_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_coupon_use = $conn_pc_row['coupon_use'];
				if(empty($pc_coupon_use)) $pc_coupon_use = 0;

				$conn_mo_sql = "

					select 
					
						sum(coupon_use) as coupon_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_coupon_use = $conn_mo_row['coupon_use'];
				if(empty($mo_coupon_use)) $mo_coupon_use = 0;

				## 적립금사용
				$conn_pc_sql = "

					select 
					
						sum(reserve_use) as reserve_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='PC'
						
				";
				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$pc_reserve_use = $conn_pc_row['reserve_use'];
				if(empty($pc_reserve_use)) $pc_reserve_use = 0;

				$conn_mo_sql = "

					select 
					
						sum(reserve_use) as reserve_use
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='OY' or status='DR' or status='DI' or status='DC') and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$mo_reserve_use = $conn_mo_row['reserve_use'];
				if(empty($mo_reserve_use)) $mo_reserve_use = 0;

				## 주문취소
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_price_OC,
						count(orderid) as total_order_OC
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and status='OC' and connect_type='PC'
						
				";

				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$total_pc_price_OC = $conn_pc_row['total_price_OC'];
				$total_pc_order_OC = $conn_pc_row['total_order_OC'];

				if(empty($total_pc_price_OC)) $total_pc_price_OC = 0;
				if(empty($total_pc_order_OC)) $total_pc_order_OC = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_mo_price_OC,
						count(orderid) as total_mo_order_OC
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and status='OC' and connect_type='M'
						
				";
				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$total_mo_price_OC = $conn_mo_row['total_mo_price_OC'];
				$total_mo_order_OC = $conn_mo_row['total_mo_order_OC'];

				if(empty($total_mo_price_OC)) $total_mo_price_OC = 0;
				if(empty($total_mo_order_OC)) $total_mo_order_OC = 0;

				## 환불/교환관련
				$conn_pc_sql = "

					select 
					
						sum(total_price) as total_price_EX,
						count(orderid) as total_order_EX
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='PC'
						
				";

				$conn_pc_result = query($conn_pc_sql);
				$conn_pc_row = sql_fetch_arr($conn_pc_result);

				$total_pc_price_EX = $conn_pc_row['total_price_EX'];
				$total_pc_order_EX = $conn_pc_row['total_order_EX'];

				if(empty($total_pc_price_EX)) $total_pc_price_EX = 0;
				if(empty($total_pc_order_EX)) $total_pc_order_EX = 0;

				$conn_mo_sql = "

					select 
					
						sum(total_price) as total_price_EX,
						count(orderid) as total_order_EX
						
					from 
					
						wiz_order 
						
					where 1
					
						$t_year_month_sql $t_paymethod_sql and (status='RD' or status='RC' or status='CD' or status='CC') and connect_type='M'
						
				";

				$conn_mo_result = query($conn_mo_sql);
				$conn_mo_row = sql_fetch_arr($conn_mo_result);

				$total_mo_price_EX = $conn_mo_row['total_price_EX'];
				$total_mo_order_EX = $conn_mo_row['total_order_EX'];

				if(empty($total_mo_price_EX)) $total_mo_price_EX = 0;
				if(empty($total_mo_order_EX)) $total_mo_order_EX = 0;

				//-----------------------------------------------------------

				$total_price_OR      = (int)($pc_total_price_OR + $mo_total_price_OR);
				$total_order_OR      = (int)($pc_total_order_OR + $mo_total_order_OR);

				$total_no_price_OR   = (int)($pc_total_no_price_OR + $mo_total_no_price_OR);

				$total_deliver_price = (int)($pc_deliver_price + $mo_deliver_price);

				$total_coupon_use    = (int)($pc_coupon_use + $mo_coupon_use);

				$total_reserve_use   = (int)($pc_reserve_use + $mo_reserve_use);

				$total_price_OC      = (int)($total_pc_price_OC + $total_mo_price_OC);
				$total_order_OC      = (int)($total_pc_order_OC + $total_mo_order_OC);

				$total_price_EX      = (int)($total_pc_price_EX + $total_mo_price_EX);
				$total_order_EX      = (int)($total_pc_order_EX + $total_mo_order_EX);

				$total_pc_price      = $pc_total_price_OR;
				$total_mo_price      = $mo_total_price_OR;
				$total_price         = $total_price_OR;

			?>
			<tr>
				<td rowspan="3" class="t_name_analy"><?=$month_play?>월</td>
				<td class="t_name_analy">PC</td>
				<td class="t_value_analy"><?=number_format($pc_total_order_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_total_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_total_no_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($pc_deliver_price)?></td>
				<td class="t_value_analy">0</td>
				<td class="t_value_analy"><?=number_format($pc_coupon_use)?></td>
				<td class="t_value_analy"><?=number_format($pc_reserve_use)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_order_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_order_EX)?></td>
				<td class="t_value_analy"><?=number_format($total_pc_price_EX)?></td>
			</tr>
			<tr>
				<td class="t_name_analy">Mobile</td>
				<td class="t_value_analy"><?=number_format($mo_total_order_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_total_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_total_no_price_OR)?></td>
				<td class="t_value_analy"><?=number_format($mo_deliver_price)?></td>
				<td class="t_value_analy">0</td>
				<td class="t_value_analy"><?=number_format($mo_coupon_use)?></td>
				<td class="t_value_analy"><?=number_format($mo_reserve_use)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_order_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price_OC)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_order_EX)?></td>
				<td class="t_value_analy"><?=number_format($total_mo_price_EX)?></td>
			</tr>
			<tr>
				<td class="t_name_analy"><strong>합계</strong></td>
				<td class="t_value_analy_total"><?=number_format($total_order_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_no_price_OR)?></td>
				<td class="t_value_analy_total"><?=number_format($total_deliver_price)?></td>
				<td class="t_value_analy_total">0</td>
				<td class="t_value_analy_total"><?=number_format($total_coupon_use)?></td>
				<td class="t_value_analy_total"><?=number_format($total_reserve_use)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price)?></td>
				<td class="t_value_analy_total"><?=number_format($total_order_OC)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_OC)?></td>
				<td class="t_value_analy_total"><?=number_format($total_order_EX)?></td>
				<td class="t_value_analy_total"><?=number_format($total_price_EX)?></td>
			</tr>
			<?
			}
			?>
		</table>


<? include "../foot.php"; ?>