<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

if(!$analy_type) $analy_type = "RA";
if(!$prev_date) $prev_date = date("Y-m")."-01";
if(!$next_date) $next_date = date("Y-m-d");

if($prev_date){
	$prev_period = $prev_date;
	$next_period = $next_date;
} else {
	$prev_period = date("Y-m")."-01";
	$next_period = date("Y-m-d");
}

$period_sql = " wdate >= '$prev_period' and wdate <= '$next_period' ";

$param = "&$menucodeParam";
if(!empty($analy_type))   $param .= "&analy_type=$analy_type";
if(!empty($search_engin)) $param .= "&search_engin=$search_engin";
if(!empty($prev_date))    $param .= "&prev_date=$prev_date";
if(!empty($next_date))    $param .= "&next_date=$next_date";

?>

<script language="javascript">
<!--
$(function(){
<?php if($analy_type == 'RA' || $analy_type == ''){ ?>
	$('#c_class1').removeClass('tab_off_r').addClass('tab_on');
	$('#c_class2').removeClass('tab_on').addClass('tab_off_r');
<?php } else if($analy_type == 'RB'){ ?>
	$('#c_class1').removeClass('tab_on').addClass('tab_off_r');
	$('#c_class2').removeClass('tab_off_r').addClass('tab_on');
<?php } ?>
});

function analyType(analy_type){

	var frm = document.frm;
	frm.analy_type.value = analy_type;
	frm.submit();

}

function delConnect(){
	if(confirm("접속경로분석 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=delrefer&<?=$menucodeParam?>';
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

-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">접속경로분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">접속경로를 분석합니다.</td>
	</tr>
</table>

<br>
<form name="frm" id="frm" action="<?php echo ACT_URL ?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="setperiod" value="">

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp;접속경로기간 검색</td>
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
	<tr>
		<td class="t_name">&nbsp; 검색엔진</td>
		<td class="t_value">
			<select name="search_engin" class="select">
				<option value="">:: 검색엔진 선택 ::
				<option value="google"   <? if($search_engin == "google")   echo "selected"; ?>>구글
				<option value="naver"    <? if($search_engin == "naver")    echo "selected"; ?>>네이버
				<option value="daum"     <? if($search_engin == "daum")     echo "selected"; ?>>다음
			</select>
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
		<td width="18%" align="center" id="c_class1" class="tab_on" onClick="analyType('RA');" style="cursor:pointer">접속경로 현황</td>
		<td width="18%" align="center" id="c_class2" class="tab_off_r" onClick="analyType('RB');" style="cursor:pointer">검색엔진/링크사이트 현황</td>
		<td width="64%" style="border-bottom:1px solid #353944;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="10" height="5"></td>
	</tr>

</table>
</form>
<br>
<?php if($analy_type == "RA" || $analy_type == "") { 

	$sql = "
		SELECT SUM(cnt) AS cnt
		  FROM wiz_con_total
		 WHERE host LIKE '%$search_engin%'
		   AND $period_sql
	";
	$row = sql_fetch_object($sql);
	$total = $row->cnt;

?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="38">
		<td width="70%" align="center" class="t_name3">접속자수</td>
		<td width="15%" align="center"><span class="con_tbold"><?=number_format($total)?></span> 명</td>
		<td width="15%" align="center"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="8%" align="center" class="t_name">접속순위</td>
		<td width="47%" align="center" class="t_name">방문경로</td>
		<td width="10%" align="center" class="t_name">접속자수</td>
		<td width="35%" align="center" class="t_name">그래프(비율(%))</td>
	</tr>
<?php

	$lists = 5;
	$rows = 20;
	if(empty($page)) $page = 1;
	$page_count = ceil($total/$rows);
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select sum(cnt) as cnt
		      ,referer 
		  from wiz_con_total 
		 where host like '%$search_engin%' 
		   and $period_sql 
		 group by referer order by cnt desc limit $start, $rows
	";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){

		$day_percent  = round(($row->cnt/$total)*100,2);
		$day_percent2 = substr($day_percent,0,strpos($day_percent,'.')+3)."%";

		$title_referer = $row->referer;

		if($row->referer == "") $_referer = "즐겨찾기나 직접방문";
		else                    $_referer = "<a href='$row->referer' target='_blank'>".cut_str($row->referer,100)."</a>";

?>
	<tr height="38">
		<td align="center"><?=++$idx?></td>
		<td style="padding:0 0 0 15px" title="<?php echo $title_referer ?>"><?=$_referer?></td>
		<td align="center"><?=number_format($row->cnt)?>명</td>
		<td>
			<table width="<?php echo $day_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $day_percent2 ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
<?
	}
?>
</table>
<br>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<?php echo print_pagelist($page, $lists, $page_count, $param); ?>
		</td>
	</tr>
</table>

<?php } else if($analy_type == "RB") { 

	$sql = "
		SELECT SUM(cnt) AS cnt
		  FROM wiz_con_total
		 WHERE host LIKE '%$search_engin%'
		   AND $period_sql
	";
	$row = sql_fetch_object($sql);
	$total = $row->cnt;

?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="38">
		<td width="70%" align="center" class="t_name3">접속자수</td>
		<td width="15%" align="center"><span class="con_tbold"><?=number_format($total)?></span> 명</td>
		<td width="15%" align="center"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="8%" align="center" class="t_name">접속순위</td>
		<td width="47%" align="center" class="t_name">검색엔진 / 링크사이트</td>
		<td width="10%" align="center" class="t_name">접속자수</td>
		<td width="35%" align="center" class="t_name">그래프(비율(%))</td>
	</tr>
<?php
	$lists = 5;
	$rows = 20;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select sum(cnt) as cnt
		     , host
		  from wiz_con_total
		 where host like '%$search_engin%'
		   and $period_sql
		 group by host order by cnt desc 
		 limit $start, $rows
	";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){

		$h_day_percent  = round(($row->cnt/$total)*100,2);
		$h_day_percent2 = substr($h_day_percent,0,strpos($h_day_percent,'.')+3)."%";

		$title_host = $row->host;

		if($row->host == "") $_host = "즐겨찾기나 직접방문";
		else                 $_host = "<a href='$row->host' target='_blank'>".cut_str($row->host,100)."</a>";

?>
	<tr height="38">
		<td align="center"><?=++$idx?></td>
		<td style="padding:0 0 0 15px" title="<?php echo $title_host ?>"><?=$_host?></td>
		<td align="center"><?=number_format($row->cnt)?>명</td>
		<td>
			<table width="<?php echo $h_day_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $h_day_percent2 ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}
?>
</table>
<br>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<?php echo print_pagelist($page, $lists, $page_count, $param); ?>
		</td>
	</tr>
</table>

<?php } ?>

<?php include "../foot.php"; ?>