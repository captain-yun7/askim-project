<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

if(!$prev_date) $prev_date = date("Y-m")."-01";
if(!$next_date) $next_date = date("Y-m-d");

if(!empty($prev_date)){
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
function openParameter(){
	var url = "connect_param.php";
	window.open(url,"orderList","height=300, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

-->
</script>


<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">검색키워드분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">검색키워드를 분석합니다.</td>
	</tr>
</table>

<br>
<form name="frm" id="frm" action="<?php echo ACT_URL ?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="setperiod" value="">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td bgcolor="ffffff">
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
						<input type="button" id="month" name="period" value="한달" class="period_1 btn_period">
						<input type="button" id="year" name="period" value="1년" class="period_1 btn_period">

						</span>
					</td>
				</tr>
				<tr>
					<td class="t_name">검색엔진</td>
					<td class="t_value">
						<select name="search_engin" class="select">
							<option value="">:: 검색엔진 선택 ::
							<!--option value="google"   <? if($search_engin == "google")   echo "selected"; ?>>구글-->
							<option value="naver"    <? if($search_engin == "naver")    echo "selected"; ?>>네이버
							<option value="daum"     <? if($search_engin == "daum")     echo "selected"; ?>>다음
						</select>
						<input type="button" value="검색파라메터 설정" class="base_btn2 gray" onclick="openParameter()">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
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
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="15%" align="center" class="t_name">검색순위</td>
		<td width="55%" align="center" class="t_name">검색키워드</td>
		<td width="15%" align="center" class="t_name">접속자수</td>
		<td width="15%" align="center" class="t_name">비율(%)</td>
	</tr>
<?
	if(!empty($search_engin)) $search_engin = "host like '%$search_engin%' and";
	else                      $search_engin = "";

	$sql = "select sum(cnt) as total_cnt from wiz_con_total where $search_engin $period_sql";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
	$total_cnt = $row->total_cnt;

	// 분석할 파라메터 가져오기
	$sql = "select con_parameter from wiz_siteinfo";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);

	$parameter = explode(",",$row->con_parameter);

	$sql = "select * from wiz_con_total where $search_engin $period_sql order by cnt desc";
	$result = query($sql) or error("sql error");
	$key_list_tmp = Array();
	$no = 0;
	while($row = sql_fetch_obj($result)){

		if($row->referer != ""){

			for($ii=0; $ii < count($parameter) && $parameter[$ii] != ""; $ii++){

				$key_start = strpos($row->referer, $parameter[$ii]."=");
				if($key_start > 0){
					$key_start = $key_start + strlen($parameter[$ii]."=");
					$key_end =  strpos($row->referer, "&", $key_start);
					if($key_end <= 0) $key_end = strlen($row->referer);

					$keyword = substr($row->referer, $key_start, $key_end-$key_start);
					$keyword = str_replace("%u", "%", $keyword);
					$keyword = urldecode($keyword);
					$keyword = str_conv($keyword, "EUC-KR");

					$key_list_tmp[$no]['name'] = $keyword;
					$key_list_tmp[$no]['cnt'] = $row->cnt;

					$no++;
				}

			}

		}


	}

	if(count($key_list_tmp) > 1) sort($key_list_tmp);

	$key_name_tmp = "";
	$key_cnt_tmp = 0;
	$no = -1;

	for($ii=0; $ii < count($key_list_tmp); $ii++){

		if($key_name_tmp != $key_list_tmp[$ii]['name']){
			$no++;
			$key_name_tmp = $key_list_tmp[$ii]['name'];
			$key_list[$no]['cnt'] = $key_list_tmp[$ii]['cnt'];
			$key_list[$no]['name'] = $key_list_tmp[$ii]['name'];
		}else{
			$key_list[$no]['cnt'] += $key_list_tmp[$ii]['cnt'];
		}
	}
	if($key_list) {
		if(count($key_list) > 0) rsort($key_list);
		$no = count($key_list);
	} else {
		$no = 0;
	}
	$lists = 5;
	$rows = 20;
	if(empty($page)) $page = 1;
	$total = ($key_list) ? count($key_list) : 0;
	$page_count = ($total && $rows) ? ceil($total/$rows) : 0;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$cnt = 0;

	if($key_list && count($key_list) > 0){

		for($ii=$start; $ii < count($key_list) && $rows > 0; $ii++){

			if(!empty($key_list[$ii]['name'])){

				$day_percent = ceil(($key_list[$ii]['cnt']/$total_cnt)*100);

?>
	<tr height="30">
		<td align="center"><?=++$idx?></td>
		<td style="padding:0 0 0 15px"><?=$key_list[$ii]['name']?></td>
		<td align="center"><?=number_format($key_list[$ii]['cnt'])?>명</td>
		<td align="center"><?=$day_percent?>%</td>
	</tr>
<?
				$cnt++;
			
			}

			$rows--;
			$no--;

		}
			
	}
?>
</table>
<br>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<? print_pagelist($page, $lists, $page_count, $param); ?>
		</td>
	</tr>
</table>

<? include "../foot.php"; ?>