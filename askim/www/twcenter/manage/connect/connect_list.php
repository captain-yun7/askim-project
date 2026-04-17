<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

if(!$analy_type) $analy_type = "OH";
if(!$prev_date) $prev_date = date("Y-m")."-01";
if(!$next_date) $next_date = date("Y-m-d");

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

$sql = "SELECT sum(cnt) as total_cnt FROM wiz_con_total";
$row = sql_fetch_object($sql);
$total_cnt = $row->total_cnt;

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
	$pr_end         = date("t", $today);
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

?>

<script src="<?php echo WAY_HOST ?>/plugin/chart/amchart/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<link rel="stylesheet" type="text/css" href="/twcenter/js/dist/jquery.jqplot.min.css" />
<script type="text/javascript" src="/twcenter/js/chart/Chart.bundle.js"></script>
<script type="text/javascript" src="/twcenter/js/chart/utils.js"></script>

<script type="text/javascript">

$(function() {



})

$(function() {
	var param = "";
		param += "analy_type=<?php echo $analy_type ?>";
		param += "&prev_date=<?php echo $prev_date ?>";
		param += "&next_date=<?php echo $next_date ?>";

	$("#analy_graph").load('./ajax_connect_list.php?' + param);
//	$("#analy_ratio").load('./connect_ratio.php?' + param);
});

function analyType(analy_type){

	var param = "";
		param += "analy_type="+analy_type;
		param += "&prev_date=<?php echo $prev_date ?>";
		param += "&next_date=<?php echo $next_date ?>";

	$.ajax({
		type: "post",
		url: "./ajax_connect_list.php",
		data : param,
		async: false,
		success: function(data, textStatus, jqXHR) {
			if(analy_type == 'OH') {
				$('#c_class1').removeClass('tab_off_r').addClass('tab_on');
				$('#c_class2').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class3').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class4').removeClass('tab_on').addClass('tab_off_r');
			} else if(analy_type == 'OD') {
				$('#c_class1').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class2').removeClass('tab_off_r').addClass('tab_on');
				$('#c_class3').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class4').removeClass('tab_on').addClass('tab_off_r');
			} else if(analy_type == 'YI') {
				$('#c_class1').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class2').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class3').removeClass('tab_off_r').addClass('tab_on');
				$('#c_class4').removeClass('tab_on').addClass('tab_off_r');
			} else if(analy_type == 'OM') {
				$('#c_class1').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class2').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class3').removeClass('tab_on').addClass('tab_off_r');
				$('#c_class4').removeClass('tab_off_r').addClass('tab_on');
			}

			$("#analy_graph").html(data);
			var refresh_url = "./connect_ratio.php?" + param;
//			$("#analy_ratio").load(refresh_url);

			//$("#ResultPush").pushStack('analy_type',analy_type);
		},
		error: function(x, o, e) {
			var msg = "페이지 호출 중 에러 발생 \n" + x.status + " : " + o + " : " + e; 
			console.log(msg);
		}
	});
	return false;

}

function delConnect(){
	if(confirm("접속자분석 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=dellist&<?=$menucodeParam?>';
	}
}

$(function() {
	<?php if($setperiod) {?>
	$(".btn_period").each(function () {
		var abd = $(this).attr('id');
		var abe = '<?php echo $setperiod; ?>';
		if(abd == abe){
			$(this).addClass("period_2");
			$(this).siblings().removeClass("period_2");
		}
	});
	<? } ?>
});

</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit"> 접속자분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">접속자를 오늘/시간/일별/월별/년별 로 분석합니다.</td>
	</tr>
</table>

<br>
<form name="frm" id="frm" action="<?php echo ACT_URL ?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="setperiod" value="">

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp;접속기간</td>
		<td width="85%" class="t_value">
			<span class="calendar">

			<input name="prev_date" id="prev_date" type="text" value="<?php echo $prev_date; ?>" class="input2" data-date-s='prev_date'> ~
			<input name="next_date" id="next_date" type="text" value="<?php echo $next_date; ?>" class="input2" data-date-e='next_date'>

			<input type="button" id="today" name="period" value="오늘" class="period_1 btn_period">
			<input type="button" id="yesday" name="period" value="어제" class="period_1 btn_period">
			<input type="button" id="week" name="period" value="7일" class="period_1 btn_period">
			<input type="button" id="fifteen" name="period" value="15일" class="period_1 btn_period">
			<input type="button" id="month" name="period" value="1개월" class="period_1 btn_period">
			<input type="button" id="thmonth" name="period" value="3개월" class="period_1 btn_period">

			</span>
		</td>
	</tr>
</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center">
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">&nbsp;
			<input type="button" value="분석초기화" class="conn_initialization" onclick="delConnect();">
		</td>
	</tr>
</table>
<br>
<br>



<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td width="15%" align="center" id="c_class1" class="tab_on" onClick="analyType('OH');" style="cursor:pointer">시간대별 현황</td>
		<td width="15%" align="center" id="c_class2" class="tab_off_r" onClick="analyType('OD');" style="cursor:pointer">일별 현황</td>
		<td width="15%" align="center" id="c_class3" class="tab_off_r" onClick="analyType('YI');" style="cursor:pointer">요일별 현황</td>
		<td width="15%" align="center" id="c_class4" class="tab_off_r" onClick="analyType('OM');" style="cursor:pointer">월별 현황</td>
		<td width="40%" style="border-bottom:1px solid #353944;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="10" height="5"></td>
	</tr>

</table>
</form>
<br>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_style">
	<tr>
		<td align="center" height="400">
			<div id="analy_graph"></div>
		</td>
	</tr>
</table>
<br>
<?php
// 오늘 방문자
$sql = "select time, sum(cnt) as cnt from wiz_con_total group by substring(time,1,8) order by substring(time,1,8) desc";
$result = query($sql) or error("sql error");
$visit_cnt = sql_fetch_obj($result);
$today_cnt = $visit_cnt->cnt*1;

// 어제 방문자
$visit_cnt = sql_fetch_obj($result);
$yester_cnt = $visit_cnt->cnt*1;

// 총 접속자 수
$sql = "select sum(cnt) as total_cnt from wiz_con_total";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$total_cnt = $row->total_cnt*1;

// 카운터 시작일
$sql = "select min(time) as stime from wiz_con_total";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$stime = $row->stime;

// 이번달 방문자
$tmonth = date('Ym')."0000";
$sql = "select sum(cnt) as month_cnt from wiz_con_total where time >= '$tmonth'";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$month_cnt = $row->month_cnt*1;

// DB 비어있을 때 $stime이 null이면 오늘 기준으로 설정 (PHP 8+ mktime은 int만 허용)
$end_day = mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y'));
if ($stime !== null && $stime !== '') {
	$start_day = mktime(0, 0, 0, (int)substr($stime, 4, 2), (int)substr($stime, 6, 2), (int)substr($stime, 0, 4));
} else {
	$start_day = $end_day;
}

$total_period = ($end_day - $start_day)/(3600*24);
if($total_period <= 0) $total_period = 1;

?>
<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
	<tr>
		<td width="25%" align='center' height="25" class="t_name">총 접속자 수</td>
		<td width="25%" class="t_value">&nbsp; <span class="con_tbold"><?=$total_cnt?></span> 명</td>
		<td width="25%" align='center' class="t_name">평균 접속자 수</td>
		<td width="25%" class="t_value">&nbsp; <span class="con_tbold"><?=round($total_cnt/$total_period,1)?></span> 명</td>
	</tr>
	<tr>
		<td height="25" align='center' class="t_name">오늘 접속자 수</td>
		<td class="t_value">&nbsp; <span class="con_tbold"><?=$today_cnt?></span> 명</td>
		<td align='center' class="t_name">어제 접속자 수</td>
		<td class="t_value">&nbsp; <span class="con_tbold"><?=$yester_cnt?></span> 명</td>
	</tr>
	<tr>
		<td height="25" align='center' class="t_name">이번달 접속자 수</td>
		<td class="t_value">&nbsp; <span class="con_tbold"><?=$month_cnt?></span> 명</td>
		<td align='center' class="t_name">이번달 평균 접속자 수</td>
		<td class="t_value">&nbsp; <span class="con_tbold"><?=round($month_cnt/date('d'),1)?></span> 명</td>
	</tr>
</table>

<?php include "../foot.php"; ?>