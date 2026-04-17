<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

if(!$analy_type) $analy_type = "OH";
if(!$prev_date)  $prev_date  = date("Y-m")."-01";
if(!$next_date)  $next_date  = date("Y-m-d");

if($prev_date){
	$prev_period = $prev_date."00";
	$next_period = $next_date."23";

	$yprev_period = $prev_date;
	$ynext_period = $next_date;

} else {
	$prev_period = date("Ym")."0100";
	$next_period = date("Ymd")."23";

	$yprev_period = date("Ym")."01";
	$ynext_period = date("Ymd");
}

if($analy_type == "OH" || $analy_type == "OT" || $analy_type == "" || $analy_type == "OP"){

	if($analy_type == "OP"){
		$time = time(); 
		$time_date = date("Y-m-d",strtotime("-1 day", $time))." 시간대별 그래프";
	} else if($analy_type == "OT" || $analy_type == "") {
		$time_date = date("Y-m-d")." 시간대별 그래프";
	} else if($analy_type == "OH") {
		$time_date = "시간대별 그래프(기간설정)";
	}

	$substring_sql = "substring(time,9,2)";
	$time_gubun    = $time_date;
	$pr_start      = 0;
	$pr_end        = 23;
	$title         = "시";

}else if($analy_type == "OD"){

	$today = time();
	$substring_sql  = "substring(time,7,2)";
	$substring1_sql = "substring(time,1,8)";
	$time_gubun     = "일별 그래프";
	$pr_start       = 1;
	$pr_end         = 31;
	$title          = "일";

}else if($analy_type == "OM"){

	$substring_sql = "substring(time,5,2)";
	$time_gubun    = "월별 그래프";
	$pr_start      = 1;
	$pr_end        = 12;
	$title         = "월";

}else if($analy_type == "OY"){

	$substring_sql = "substring(time,1,4)";
	$time_gubun    = "년별 그래프";
	$pr_start      = '2016';
	$pr_end        = date("Y")+5;
	$title         = "년";

}else if($analy_type == "YI"){

	$substring_sql = "substring(time,1,8)";
	$time_gubun    = "요일별 그래프";
	$pr_start      = $yprev_period;
	$pr_end        = $ynext_period;
	$title         = "요일";

}

$prev_period = str_replace("-","",$prev_period);
$next_period = str_replace("-","",$next_period);

$period_sql = " WHERE time >= '$prev_period' AND time <= '$next_period' ";

if($analy_type != 'YI'){

	$sql = "
	
		SELECT sum(cnt) as cnt
			 , $substring_sql as time
		  FROM wiz_con_total 
		$period_sql
		  GROUP BY $substring_sql ORDER BY $substring_sql ASC
		
	";

	$result = query($sql);

	while($row = sql_fetch_obj($result)){
		$row->time = $row->time/1;
		$cnt_list[$row->time]['cnt'] = $row->cnt;
	}

	for($i=$pr_start; $i<=$pr_end; $i++){
		$cnt = $cnt_list[$i]['cnt'];
		if(empty($cnt)) $vcnt = 0; else $vcnt = $cnt;
		$total_mem_con  .= $vcnt.",";
		$g_ticks_day	.= "'".$i."".$title."',";
	}
	$total_mem_con  = (substr($total_mem_con, -1) == ',')  ? substr_replace($total_mem_con, '', -1) : $total_mem_con;
	$g_ticks_day    = (substr($g_ticks_day, -1) == ',') ? substr_replace($g_ticks_day, '', -1) : $g_ticks_day;

} else {

	$sql = "
	
		SELECT sum(cnt) as cnt
		     , $substring_sql as time
		  FROM wiz_con_total 
		$period_sql
		  GROUP BY $substring_sql ORDER BY $substring_sql ASC
		
	";
	$result = query($sql);

	$sun = 0;
	$mon = 0;
	$tue = 0;
	$wed = 0;
	$thu = 0;
	$fri = 0;
	$sat = 0;

	$yoil = array("일","월","화","수","목","금","토");

	while($row = sql_fetch_obj($result)){
		$row->time = $row->time/1;
		$cnt_list[$row->time]['cnt'] = $row->cnt;
		$cnt_list[$row->time]['time'] = $row->time;

		$yoil_val = ($yoil[date('w', strtotime($cnt_list[$row->time]['time']))]);

		if($yoil_val == '일') $sun += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '월') $mon += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '화') $tue += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '수') $wed += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '목') $thu += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '금') $fri += $cnt_list[$row->time]['cnt'];
		else if($yoil_val == '토') $sat += $cnt_list[$row->time]['cnt'];
	}

	foreach($yoil as $value){
		$g_ticks_day .= "'".$value."".$title."',";
	}

	$total_mem_con = $sun.",".$mon.",".$tue.",".$wed.",".$thu.",".$fri.",".$sat;
	$g_ticks_day    = (substr($g_ticks_day, -1) == ',') ? substr_replace($g_ticks_day, '', -1) : $g_ticks_day;
}

?>

<?php if($analy_type != 'YI') { ?>

<script type="text/javascript">
$(function() {


	var CON = [<?=$total_mem_con?>];
	var ticks = [<?=$g_ticks_day?>];

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ticks,
			datasets: [{
				borderColor: window.chartColors.blue,
				borderWidth: 0,
				data: CON,
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString()
			}]

		};
		var ctx = document.getElementById('graph2_canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				responsive: true,
				legend: {
					display: false
				},
				title: {
					display: false
				},
				scales: {
					yAxes: [
						{
							ticks: {
								min: 0,
								callback: function(label, index, labels) {
									return label+'명';
								}
							},
							scaleLabel: {
								display: false
							}
						}
					]
				}
			}
		});

});

</script>
<?php } else { ?>
<script type="text/javascript">
$(function() {


	var CON = [<?=$total_mem_con?>];
	var ticks = [<?=$g_ticks_day?>];

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ticks,
			datasets: [{
				borderColor: window.chartColors.blue,
				borderWidth: 0,
				data: CON,
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString()

			}]

		};
		var ctx = document.getElementById('graph2_canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				responsive: true,
				legend: {
					display: false
				},
				title: {
					display: false
				},
				scales: {
					yAxes: [
						{
							ticks: {
								min: 0,
								callback: function(label, index, labels) {
									return label+'명';
								}
							},
							scaleLabel: {
								display: false
							}
						}
					]
				}
			},
				tooltips: {
					enabled: true,
					displayColors: true,
					bodyFontColor: '#fff',
					callbacks: {
						label: function(tooltipItem, data){
							return " "+(tooltipItem.yLabel).toLocaleString()+"명";
						},
						title: function(tooltipItem, data){
							return '';
						},
					}
				}
		});


});

</script>

<?php } ?>

<?php
echo '<div id="graph2" style="width: 95%; max-height: 400px; margin: auto;">
		<canvas id="graph2_canvas" height="78"></canvas>
	 </div>';
?>