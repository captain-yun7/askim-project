<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT'].'/comm/API/json/JSON.php'; ?>
<? include "../head.php"; ?>
<?
$json = new Services_JSON();

$url = "http://whois.kisa.or.kr/openapi/whois.jsp?query=188.143.234.155&key=2016120710162860772291&answer=json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);

var_dump($data);
curl_close($ch);

if(!empty($prev_date)){
	$prev_period = $prev_date;
	$next_period = $next_date;
} else {
	$prev_period = date("Y-m-d");
	$next_period = date("Y-m-d");
}

$period_sql = " where wdate >= '$prev_period' and wdate <= '$next_period' ";

if($s_type == 1){
	$_on1 = "period_1";
	$_on2 = "period_2";
	$_on3 = "period_2";
	$_on4 = "period_2";
	$_on5 = "period_2";
} else if($s_type == 2) {
	$_on1 = "period_2";
	$_on2 = "period_1";
	$_on3 = "period_2";
	$_on4 = "period_2";
	$_on5 = "period_2";
} else if($s_type == 3) {
	$_on1 = "period_2";
	$_on2 = "period_2";
	$_on3 = "period_1";
	$_on4 = "period_2";
	$_on5 = "period_2";
} else if($s_type == 4) {
	$_on1 = "period_2";
	$_on2 = "period_2";
	$_on3 = "period_2";
	$_on4 = "period_1";
	$_on5 = "period_2";
} else if($s_type == 5) {
	$_on1 = "period_2";
	$_on2 = "period_2";
	$_on3 = "period_2";
	$_on4 = "period_2";
	$_on5 = "period_1";
} else {
	$_on1 = "period_2";
	$_on2 = "period_2";
	$_on3 = "period_2";
	$_on4 = "period_2";
	$_on5 = "period_2";
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script language="javascript">
<!--

function setPeriod(from,to,type){

	var period_1 = 'period_1';
	var period_2 = 'period_2';

	if(type == 1){
		$("#set1").attr('class',period_1);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
	} else if(type == 2){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_1);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
	} else if(type == 3){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_1);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_2);
	} else if(type == 4){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_1);
		$("#set5").attr('class',period_2);
	} else if(type == 5){
		$("#set1").attr('class',period_2);
		$("#set2").attr('class',period_2);
		$("#set3").attr('class',period_2);
		$("#set4").attr('class',period_2);
		$("#set5").attr('class',period_1);
	}


	if(from == '' && to == ''){
		document.frm.s_status.value = 'ALL';
		$("#prev_date").val(from);
		$("#next_date").val(to);
	} else {
		document.frm.s_type.value = type;
		$("#prev_date").val(from);
		$("#next_date").val(to);
	}
}


function delConnect(){
	if(confirm("OS/브라우저 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=deltotal';
	}
}

$(function() {
	var calendar = {
		showButtonPanel: true, 
		dateFormat: "yy-mm-dd",
		currentText: '오늘', 
		closeText: '닫기', 
		changeMonth: true, 
		changeYear: true, 
		dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesMin: ['<font color=red>일</font>', '월', '화', '수', '목', '금', '<font color=#0071bf>토</font>'], 
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
	};
	$("#prev_date,#next_date").datepicker(calendar);

});

$(function() {
	$('#close').click(function() {
		$('#pop').hide();
	});
	$('#pop').hide();
});

function popOpen(ip){
	$('#pop').show();
}
-->
</script>
<style type="text/css">
#pop{
	width:300px; height:400px; background:#fff; color:#000; 
	position:absolute; top:10px; left:100px; text-align:center; 
	border:2px solid #000;
}
</style>
<div id="pop">
	<div style="height:370px;">

	</div>
	<div>
		<div id="close" style="width:100px; margin:auto;">close</div>
	</div>
</div>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">접속자 IP분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">사이트접속한 방문자의 IP를 분석합니다. 불법적인 아이피접근시 아이피접근을 차단하는 기본적인 토대로 사용가능합니다.</td>
	</tr>
</table>
<br>
<form name="frm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="s_type" value="<?=$s_type?>">
<input type="hidden" name="analy_type" value="<?=$analy_type?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp;기간검색</td>
		<td width="85%" class="t_value">
		<?
		$yes_day      = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*1));
		$to_day       = date('Y-m-d');
		$week_day     = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*7));
		$month_day    = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*30));
		$twomonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));
		$sixmonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*182));
		$prevyear_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*365));

		//if(!empty($prev_date)) $week_day2 = $prev_date; else $week_day2 = $twomonth_day;
		//-- 금일날짜 재셋팅
		if(!empty($prev_date)) $week_day2 = $prev_date; else $week_day2 = date("Y-m")."-01";
		if(!empty($next_date)) $to_day2   = $next_date; else $to_day2   = $to_day;

		?>
			<input type="text" name="prev_date" id="prev_date" class="datepicker-here input2" value="<?=$week_day2?>"> ~ 
			<input type="text" name="next_date" id="next_date" class="datepicker-here input2" value="<?=$to_day2?>">&nbsp;

			<input type="button" onClick="setPeriod('<?=$to_day?>','<?=$to_day?>',1)" value="오늘" id="set1" class="<?=$_on1?>">
			<input type="button" onClick="setPeriod('<?=$yes_day?>','<?=$to_day?>',2)" value="전일" id="set2" class="<?=$_on2?>">
			<input type="button" onClick="setPeriod('<?=$week_day?>','<?=$to_day?>',3)" value="1주일" id="set3" class="<?=$_on3?>">
			<input type="button" onClick="setPeriod('<?=$month_day?>','<?=$to_day?>',4)" value="1개월" id="set4" class="<?=$_on4?>">
			<input type="button" onClick="setPeriod('<?=$twomonth_day?>','<?=$to_day?>',5)" value="3개월" id="set5" class="<?=$_on5?>">

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

</form>
<br>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="15%" align="center" class="t_name">접속시간</td>
		<td width="15%" align="center" class="t_name">접속IP</td>
		<td width="12%" align="center" class="t_name">웹브라우저</td>
		<td width="12%" align="center" class="t_name">운영체제</td>
		<td width="12%" align="center" class="t_name">접속자수</td>
		<td align="center" class="t_name">방문경로</td>
	</tr>
<?
	$sql = "SELECT sum(cnt) as total_cnt FROM wiz_con_total $period_sql AND ip != '' ";
	$row = sql_fetch_object($sql);
	$total_cnt = $row->total_cnt;

	$sql = "SELECT * FROM wiz_con_total $period_sql AND ip != '' ORDER BY wdate, wtime DESC ";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){

		$day_percent = ceil(($row->cnt/$total_cnt)*100);
		if($row->os == 'unknown'){
			$row->os = "기타 운영체제";
		} else {
			$row->os = $row->os;
		}

		$wdate = $row->wdate." ".$row->wtime;

?>
	<tr height="30">
		<td align="center"><?=$wdate?></td>
		<td align="center"><?=$row->ip?> <a href="javascript:;" onclick="popOpen('<?=$row->ip?>');"><font color=red>[분석]</font></a></td>
		<td align="center"><?=$row->browser?></td>
		<td align="center"><?=$row->os?></td>
		<td align="center"><?=$row->cnt?>명</td>
		<td style="padding:0 0 0 15px"><?=$row->referer?></td>
	</tr>
<?
	}
?>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="30">
		<td width="60%" align="center" class="t_name3">총 접속자수</td>
		<td width="20%" align="center"><span class="num_size2"><?=number_format($total_cnt)?></span>명</td>
		<td width="20%" align="center"></td>
	</tr>
</table>
<? include "../foot.php"; ?>