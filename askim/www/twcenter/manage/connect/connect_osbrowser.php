<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

if(!$analy_type) $analy_type = "OS";
if(!$prev_date) $prev_date = date("Y-m")."-01";
if(!$next_date) $next_date = date("Y-m-d");

if(!empty($prev_date)){
	$prev_period = $prev_date;
	$next_period = $next_date;
} else {
	$prev_period = date("Y-m")."-01";
	$next_period = date("Y-m-d");
}

$period_sql = " where wdate >= '$prev_period' and wdate <= '$next_period' ";

$sql = "
	select sum(cnt) as srh_total_cnt 
	  from wiz_con_total 
	  $period_sql 
	   and (os != '' or os is not null) 
";
$row = sql_fetch_object($sql);
$srh_total_cnt = $row->srh_total_cnt;

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<!-- datepicker_lib.php-->
<link href="/comm/jquery-ui/jquery-ui-1.11.3.min.css?ver='.MWS_VERSION.'" rel="stylesheet" type="text/css" >
<link href="/comm/jquery-ui/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/comm/jquery-ui/jquery-ui-1.11.3.min.js"></script>
<script type="text/javascript" src="/twcenter/js/datepicker.js"></script>

<script language="javascript">
<!--
$(function(){
<?php if($analy_type == 'OS' || $analy_type == ''){ ?>
	$('#c_class1').removeClass('tab_off_r').addClass('tab_on');
	$('#c_class2').removeClass('tab_on').addClass('tab_off_r');
<?php } else if($analy_type == 'WB'){ ?>
	$('#c_class1').removeClass('tab_on').addClass('tab_off_r');
	$('#c_class2').removeClass('tab_off_r').addClass('tab_on');
<?php } ?>
});

function analyType(analy_type){

	if(analy_type == 'OS'){
		$("#c_class1").attr('class','conn_Tab1');
		$("#c_class2").attr('class','conn_Tab2');
	} 

	var frm = document.frm;
	frm.analy_type.value = analy_type;
	frm.submit();

}

function delConnect(){
	if(confirm("OS/브라우저 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=delos';
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
	<?php } ?>
});

-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">접속자 환경분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">사이트접속한 방문자의 운영제처(OS)및 브라우저를를 분석합니다.</td>
	</tr>
</table>
<br>
<form name="frm" id="frm" action="<?php echo ACT_URL ?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="setperiod" value="">

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp;기간검색</td>
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
		<td width="18%" align="center" id="c_class1" class="tab_on" onClick="analyType('OS');" style="cursor:pointer">운영체제 현황</td>
		<td width="18%" align="center" id="c_class2" class="tab_off_r" onClick="analyType('WB');" style="cursor:pointer">브라우저 현황</td>
		<td width="64%" style="border-bottom:1px solid #353944;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="10" height="5"></td>
	</tr>

</table>
</form>
<br>
<?php if($analy_type == "OS" || $analy_type == ""){ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="30">
		<td width="50%" align="center" class="t_name3">접속자수</td>
		<td width="25%" align="center"><span class="con_tbold"><?=number_format($srh_total_cnt)?></span> 명</td>
		<td width="25%" align="center"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="25%" align="center" class="t_name">접속순위</td>
		<td width="25%" align="center" class="t_name">운영체제</td>
		<td width="25%" align="center" class="t_name">접속자수</td>
		<td width="25%" align="center" class="t_name">비율(%)</td>
	</tr>
<?php
	$sql = "
		select ip
			 , browser
			 , os
			 , device
			 , referer
			 , sum(cnt) cnt
		  from wiz_con_total 
		  $period_sql 
		  and (os != '' or os is not null) 
		 group by os
		 order by cnt desc 
	";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){

		$day_percent = round(($row->cnt/$srh_total_cnt)*100,2);

		if($row->os == 'unknown'){
			$row->os = "기타 운영체제";
		} else {
			$row->os = $row->os;
		}

?>
	<tr height="30">
		<td align="center"><?=++$idx?></td>
		<td align="center"><?=$row->os?></td>
		<td align="center"><?=$row->cnt?>명</td>
		<td align="center"><?=$day_percent?>%</td>
	</tr>
<?php
	}
?>
</table>

<?php } else if($analy_type == "WB") { 

	$sql = "
		select sum(cnt) as b_total_cnt 
		  from wiz_con_total 
		  $period_sql 
		   and (browser != '' or browser is not null) 
	";
	$row = sql_fetch_object($sql);
	$b_total_cnt = $row->b_total_cnt;

?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="30">
		<td width="60%" align="center" class="t_name3">총 접속자수</td>
		<td width="20%" align="center"><span class="num_size2"><?=number_format($b_total_cnt)?></span>명</td>
		<td width="20%" align="center"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="20%" align="center" class="t_name">접속순위</td>
		<td width="20%" align="center" class="t_name">웹브라우저</td>
		<td width="20%" align="center" class="t_name">접속디바이스</td>
		<td width="20%" align="center" class="t_name">접속자수</td>
		<td width="20%" align="center" class="t_name">비율(%)</td>
	</tr>
<?php
	$sql = "
		select ip
			 , browser
			 , os
			 , device
			 , referer
			 , sum(cnt) cnt
		  from wiz_con_total 
		  $period_sql 
		   and (browser != '' or browser is not null) 
		 group by browser
		 order by cnt desc 
	";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){

		$day_percent = round(($row->cnt/$b_total_cnt)*100,2);

?>
	<tr height="30">
		<td align="center"><?=++$idx?></td>
		<td align="center"><?=$row->browser?></td>
		<td align="center"><?=$row->device?></td>
		<td align="center"><?=$row->cnt?>명</td>
		<td align="center"><?=$day_percent?>%</td>
	</tr>
<?php
	}
?>
</table>
<?php } ?>
<?php include "../foot.php"; ?>