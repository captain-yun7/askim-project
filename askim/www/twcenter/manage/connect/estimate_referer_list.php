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

$period_sql = " where savetime >= '$prev_period 00:00:00' and savetime <= '$next_period 23:59:59' ";

$param = "&$menucodeParam";
if(!empty($s_type))       $param .= "&s_type=$s_type";
if(!empty($analy_type))   $param .= "&analy_type=$analy_type";
if(!empty($search_engin)) $param .= "&search_engin=$search_engin";
if(!empty($prev_date))    $param .= "&prev_date=$prev_date";
if(!empty($next_date))    $param .= "&next_date=$next_date";

$sql = "
	select count(*) as srh_total_cnt 
	  from wiz_estimate_referer 
	 where 1=1
	   $period_sql2
";
$row = sql_fetch_object($sql);
$srh_total_cnt = $row->srh_total_cnt;

$sql2 = "
	select * 
	  from wiz_estimate_referer 
	  $period_sql 
";
$result2 = query($sql2) or error("sql error");
$total = sql_fetch_row($result2);

?>
<script language="javascript">
<!--
function delConnect(){
	if(confirm("OS/브라우저 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=deltotal';
	}
}

function popOpen(ip,key){
	if(key == ""){
		alert("아이피분석키값이 등록되어 있지 않습니다.");
		return false;
	} else {
		var url = "connect_search_ip.php?query="+ip;
		window.open(url, "popOpen", "height=200, width=700, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
	}
}
-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">견적문의 접수 분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">견적문의 접수건의 접속 경로 등을 분석합니다.</td>
	</tr>
</table>
<br>
<form name="frm" id="frm" action="<?php echo ACT_URL ?>" method="get">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="analy_type" id="analy_type" value="<?php echo $analy_type ?>">
<input type="hidden" name="setperiod" value="">
<input type="hidden" name="ip" id="ip" value="<?=$ip?>">

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
<!-- 			<input type="button" value="분석초기화" class="conn_initialization" onclick="delConnect();"> -->
		</td>
	</tr>
</table>

</form>
<br>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr height="38">
		<td width="55%" align="center" class="t_name3">접수자수</td>
		<td width="11%" align="center"><span class="con_tbold"><?=number_format($srh_total_cnt)?></span> 명</td>
		<td width="34%" align="center"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="11%" align="center" class="t_name">저장시간</td>
		<td width="11%" align="center" class="t_name">접속IP</td>
		<td width="11%" align="center" class="t_name">접속형태</td>
		<td width="34%" align="center" class="t_name">리퍼러정보(URL)</td>
		<td width="11%" align="center" class="t_name">게시글 바로가기</td>
	</tr>
<?php
	$lists = 5;
	$rows = 20;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select *
		  from wiz_estimate_referer
		   $period_sql 
		 order by savetime desc
		 limit $start, $rows 
	";

	$result = query($sql);
	while($row = sql_fetch_obj($result)){
?>
	<tr height="38">
		<td align="center"><?=$row->savetime?></td>
		<td align="center"><a href="javascript:;" onclick="popOpen('<?=$row->referer_ip?>','<?=$site_info['ipkisakey']?>');"><?=$row->referer_ip?></a></td>
		<td align="center"><?=$row->connect_device?></td>
		<td align="center">
		<?if($row->referer_origin != ""){?>
		<a href='<?=$row->referer_origin?>' target='_blank'><?=$row->referer_origin?></a>
		<? }else{ ?>
		<?=$row->referer_origin?>
		<? } ?>

		</td>
		<td align="center"><input type="button" value="상세보기" class="base_btm reg" onclick="document.location='/twcenter/manage/bbs/list.php?ptype=view&idx=<?=$row->bidx?>&code=estimate'"></td>
	</tr>
<?
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

<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/foot.php"; ?>