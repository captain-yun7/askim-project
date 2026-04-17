<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";

echo PHP_EOL.'<link href="/comm/jquery-ui/jquery-ui-1.11.3.min.css?ver='.VERSION.'" rel="stylesheet" type="text/css">'.PHP_EOL;
echo '<link href="/comm/jquery-ui/style.css" rel="stylesheet" type="text/css">'.PHP_EOL;
echo '<script type="text/javascript" src="/comm/jquery-ui/jquery-ui-1.11.3.min.js"></script>'.PHP_EOL;

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$tmp_param = array();
if(isset($s_status)   && $s_status)   $tmp_param[] = "s_status=".$s_status;
if(isset($srh_prev)   && $srh_prev)   $tmp_param[] = "srh_prev=".$srh_prev;
if(isset($srh_next)   && $srh_next)   $tmp_param[] = "srh_next=".$srh_next;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;
if(isset($tmp_rows)   && $tmp_rows)   $tmp_param[] = "tmp_rows=".$tmp_rows;
if(isset($setperiod)  && $setperiod)  $tmp_param[] = "setperiod=".$setperiod;
if(isset($setperiod2) && $setperiod2) $tmp_param[] = "setperiod2=".$setperiod2;
if(isset($sdate)      && $sdate)      $tmp_param[] = "sdate=".$sdate;
if(isset($edate)      && $edate)      $tmp_param[] = "edate=".$edate;
if(isset($s_bank)     && $s_bank)     $tmp_param[] = "s_bank=".$s_bank;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

//--------------------------------------------------------------------------------------------------

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$six_btn = "<img src='../image/sicon_sixmonth.gif' border='0' align='absmiddle'>";
$yer_btn = "<img src='../image/sicon_year.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";


?>
<style>
.period_4 {
	margin: 1px 0;
	font-size: 11px;
	color: #FFF;
	border: 1px solid #CBCBCB;
	padding: 5px;
	background-repeat: no-repeat;
	background-position: right 3px center;
	background: #484C57;
	cursor: pointer;
	vertical-align: middle
}
</style>
<script type="text/javascript">
var bankda_service      = "<?php echo $oper_info['bankda_use'] ?>";
var bankda_service_date = "<?php echo $oper_info['bankda_service_date'] ?>";
var bankda_match_time   = "<?php echo $oper_info['bankda_match_time'] ?>";
var this_day            = "<?php echo THIS_TIME_YMD ?>";
var prev_day            = "<?php echo date('Y-m-d',strtotime('-1 week')); ?>";

if(bankda_service == "Y" && this_day >= bankda_service_date) {
	var time = bankda_match_time;
	$(function() {
		setInterval(function(){
			AccountLoad();
		},time);
	});
}

$(function() {

	var setperiod = '<?php echo $setperiod ?>';
	if(setperiod == '') {

		$("#srh_prev").val(prev_day);
		$("#srh_next").val(this_day);
		$("#week").addClass("period_2");

	} else {

		$(".btn_period").each(function () {
			var abd = $(this).attr('id');
			var abe = setperiod;
			if(abd == abe){
				$(this).addClass("period_2");
				$(this).siblings().removeClass("period_2");
			}
		});

	}

	var setperiod2 = '<?php echo $setperiod2 ?>';
	if(setperiod2 == '') {

		$("#sdate").val(prev_day);

	} else {

		$(".btn_period2").each(function () {
			var abd = $(this).attr('id');
			var abe = setperiod2;
			if(abd == abe){
				$(this).addClass("period_4");
				$(this).siblings().removeClass("period_4");
			}
		});

	}

});

$(function() {

	var calendar3 = {
		showOn: "both", 
		buttonImage: "/twcenter/images/calendar_btn2.gif", 
		buttonImageOnly: true,
		showButtonPanel: true,
		dateFormat: "yy-mm-dd",
		currentText: '오늘', 
		closeText: '닫기', 
		changeMonth: true, 
		changeYear: true, 
		dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesMin: ['<font color=red>일</font>', '월', '화', '수', '목', '금', '<font color=#0071bf>토</font>'], 
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		yearRange: 'c-10:c+5',
		minDate: "<?php echo $oper_info['bankda_service_date'] ?>"
	};

	$("#srh_prev,#srh_next").datepicker(calendar3);
	$("#sdate,#edate").datepicker(calendar3);

});
-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">입금조회/실시간입금확인 </td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt"></td>
	</tr>
</table>
<br>
<form name="frm" id="frm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="">
<input type="hidden" name="s_status" value="<?=$s_status?>">
<input type="hidden" name="s_type" value="<?=$s_type?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="setperiod" value="<?php echo $setperiod ?>">
<input type="hidden" name="setperiod2" value="<?php echo $setperiod2 ?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td class="t_name">&nbsp; 입금일</td>
		<td class="t_value" colspan="3">
			<span class="calendar">
			<input type="text" name="srh_prev" id="srh_prev" class="input2" size="15" value="<?=$srh_prev?>" data-date-s='srh_prev'> ~ 
			<input type="text" name="srh_next" id="srh_next" class="input2" size="15" value="<?=$srh_next?>" data-date-e='srh_next'>

			<input type="button" id="today" name="period" value="오늘" class="period_1 btn_period">
			<input type="button" id="week" name="period" value="7일" class="period_1 btn_period">
			<input type="button" id="fifteen" name="period" value="15일" class="period_1 btn_period">
			<input type="button" id="month" name="period" value="한달" class="period_1 btn_period">
			<input type="button" id="year" name="period" value="1년" class="period_1 btn_period">
			</span>	
		</td>
	</tr>
	<tr>
		<td class="t_name">&nbsp; 최종매칭일</td>
		<td class="t_value" colspan="3">
			<span class="calendar">
			<input type="text" name="sdate" id="sdate" class="input2" size="15" value="<?=$sdate?>" data-date-s='sdate'> ~ 
			<input type="text" name="edate" id="edate" class="input2" size="15" value="<?=$edate?>" data-date-e='edate'>

			<input type="button" id="today1" name="period" value="오늘" class="period_1 btn_period2">
			<input type="button" id="week1" name="period" value="7일" class="period_1 btn_period2">
			<input type="button" id="fifteen1" name="period" value="15일" class="period_1 btn_period2">
			<input type="button" id="month1" name="period" value="한달" class="period_1 btn_period2">
			<input type="button" id="year1" name="period" value="1년" class="period_1 btn_period2">
			</span>	
		</td>
	</tr>
	<tr>
		<td width="15%" class="t_name">&nbsp; 조건검색</td>
		<td width="35%" class="t_value">
			<select name="searchopt" class="select2">
				<option value="bkjukyo"  <? if($searchopt == "bkjukyo") echo "selected"; ?>>입금자명
				<option value="bkinput"  <? if($searchopt == "bkinput") echo "selected"; ?>>입금액
				<option value="orderid"  <? if($searchopt == "orderid") echo "selected"; ?>>주문번호
			</select>
			<input type="text" name="searchkey" value="<?=$searchkey?>" size="25" class="input">
		</td>
		<td width="15%" class="t_name">&nbsp; 현재상태/은행명</td>
		<td width="35%" class="t_value">
			<select name="s_status" class="select2">
				<?php
				foreach($_bk_status_code AS $val) {
				?>
				<option value="<?php echo $val[0] ?>" <?php if($s_status == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
				<?php
				}
				?>
			</select> 
			<select name="s_bank" class="select2">
				<option value="">은행명</option>
				<?php
				foreach($_bank_code2 AS $val) {
				?>
				<option value="<?php echo $val ?>" <?php if($s_bank == $val) echo "selected";?>><?php echo $val ?></option>
				<?php
				}
				?>
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
			<input type="button" value="실시간입금확인 실행가기" class="search_default3" onclick="AccountLoad()">
		</td>
	</tr>
</table>

</form>

<br>
<?php
$prev_period = $srh_prev;
$next_period = $srh_next." 23:59:59";

$prev_period1 = $sdate;
$next_period1 = $edate." 23:59:59";

if(!empty($oper_info['bankda_service_date'])) {
	$service_date = " AND Y.bkdate >= '".$oper_info['bankda_service_date']."' ";
	$start_date   = $oper_info['bankda_service_date'];
	$start_date1  = $oper_info['bankda_service_date'];
} else {
	$service_date = "";
	$start_date   = $prev_period;
	$start_date1  = $prev_period1;
}


$where = array();

if(!empty($s_status)) {
	$where[] = "Y.bkmatchres = '$s_status'";
} 

if(!empty($s_bank)) {
	$where[] = "Y.bkname = '$s_bank'";
} 

if(isset($srh_prev) && $srh_prev && isset($srh_next) && $srh_next) {
	$where[] = "Y.bkdate >= '".$start_date."' and Y.bkdate <= '$next_period'";
}
if(isset($sdate) && $sdate && isset($edate) && $edate) {
	$where[] = "Y.bkmatchdate >= '".$start_date1."' and Y.bkmatchdate <= '$next_period1'";
}

if($searchopt && $searchkey) $where[] = " INSTR(Y.$searchopt, '".$searchkey."') > 0";

$search_query   = ($where) ? " AND ".implode(" AND ", $where) : "";

$sql = "
	SELECT COUNT(Y.idx) AS total 
	  FROM bankda_io_history Y
	 WHERE 1
	   $service_date
	   $search_query
";
$result = query($sql);
$row = sql_fetch_arr($result);
$total = $row['total'];

if(!empty($tmp_rows)) $tmp_rows = $tmp_rows; else $tmp_rows = 20;
$rows = $tmp_rows;
$lists = 5;
$page_count = ceil($total/$rows);
if($page < 1 || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td><span class="title_msg">검색결과 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
		<td align="right">
		<form>
			<select name="tmp_rows" onchange="location.href='?<?=$param?>&tmp_rows='+this.value" class="select">
				<option value="10"  <? if($tmp_rows==10)  echo "selected";?>>10개씩 출력</option>
				<option value="20"  <? if($tmp_rows==20)  echo "selected";?>>20개씩 출력</option>
				<option value="30"  <? if($tmp_rows==30)  echo "selected";?>>30개씩 출력</option>
				<option value="50"  <? if($tmp_rows==50)  echo "selected";?>>50개씩 출력</option>
				<option value="70"  <? if($tmp_rows==70)  echo "selected";?>>70개씩 출력</option>
				<option value="100" <? if($tmp_rows==100) echo "selected";?>>100개씩 출력</option>
			</select> 
		</form>
		</td>
	</tr>
	<tr><td height=5></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="5%"></th>
		<th width="10%">입금완료일</th>
		<th width="15%">계좌번호</th>
		<th width="15%">은행명</th>
		<th width="10%">입금(예정)금액</th>
		<th width="10%">입금자명</th>
		<th width="10%">현재상태</th>
		<th width="12%">최종매칭일</th>
		<th width="13%">주문번호</th>
		<th width="13%"></th>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	</form>
	<?php
	$sql = "
		SELECT Y.*
		  FROM bankda_io_history Y
		 WHERE 1
		   $service_date
		   $search_query
		 LIMIT $start, $rows
	";
	//echo "<xmp>".$sql."</xmp>";
	$result = query($sql);
	while(($row = sql_fetch_obj($result)) && $rows){

		$match_date   = substr($row->bkmatchdate,0,10);

		if($row->bk_match_result == 'MT') {

			$account = trim($row->account);
			list($obkname, $toaccount, $odepositor) = explode(" ",$account);
			$oaccount     = trim(str_replace("-", "", $toaccount));
			$account_name = trim($row->account_name);

			$bkdate    = "";
			$actnumber = $oaccount;
			$bkname    = $obkname;
			$bkinput   = number_format($row->total_price);
			$bkjukyo   = $account_name;
			$match_result = bk_match_result($row->bk_match_result);
			$mresult_code = $row->bk_match_result;
			$match_date   = substr($row->bk_match_date,0,10);
			$orderid = $row->orderid;

		} else {

			$bkdate    = $row->bkdate;							## 입금완료일
			$actnumber = $row->actnumber;						## 계좌번호
			$bkname    = $row->bkname;							## 은행명
			$bkinput   = number_format($row->bkinput);			## 입금액
			$bkjukyo   = $row->bkjukyo;							## 입금자
			$match_result = bk_match_result($row->bkmatchres);	## 현재상태
			$mresult_code = $row->bkmatchres;
			$match_date   = substr($row->bkmatchdate,0,10);		## 최종매칭일
			$orderid = $row->orderid;

		}


	?>
	<form action="order_save.php" name="<?=$row->prdcode?>" method="get">
	<input type="hidden" name="mode"       value="chgstatus">
	<input type="hidden" name="page"       value="<?=$page?>">
	<input type="hidden" name="orderid"    value="<?=$row->orderid?>">

	<input type="hidden" name="status"     value="<?=$row->status?>">

	<input type="hidden" name="s_status"   value="<?=$s_status?>">
	<input type="hidden" name="srh_prev"   value="<?=$srh_prev?>">
	<input type="hidden" name="srh_next"   value="<?=$srh_next?>">
	<input type="hidden" name="sdate"      value="<?=$sdate?>">
	<input type="hidden" name="edate"      value="<?=$edate?>">
	<input type="hidden" name="searchopt"  value="<?=$searchopt?>">
	<input type="hidden" name="searchkey"  value="<?=$searchkey?>">
	<input type="hidden" name="menucode"  value="<?=$menucode?>">

	<tr onmouseover="this.style.backgroundColor='#e8f3f7'" onmouseout="this.style.backgroundColor='#ffffff'">
		<td align="center" class="t_line" height="38"></td>
		<td align="center" class="t_line"><?php echo $bkdate ?></td>
		<td align="center" class="t_line"><?php echo $actnumber ?></td>
		<td align="center" class="t_line"><?php echo $bkname ?></td>
		<td align="center" class="t_line"><?php echo $bkinput ?>원</td>
		<td align="center" class="t_line"><?php echo $bkjukyo ?></td>
		<td align="center" class="t_line"><?php echo $match_result ?></td>
		<td align="center" class="t_line"><?php echo $match_date ?></td>
		<td align="center" class="t_line"><a href="order_info.php?orderid=<?=$orderid?>&mresult=<?php echo $mresult_code ?>&page=<?=$page?>&<?=$param?>" target="_blank"><?php echo $orderid ?></a></td>
	</tr>
	</form>
	<?
		$no--;
		$rows--;
	}
	if($total <= 0){
	?>
	<tr><td height=38 colspan=11 align=center>검색된 내역이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<td width="100%" align="center"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
</table>
<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">[주문정보 매칭] 입금조회서비스</div>
	  <div class="explain">
		- 뱅크다와 연동으로 무통장입금에 대해 실시간으로 입금여부를 확인할수 있는 서비스입니다.<br>
		- 서비스시작일을 기준으로 1시간 간격으로 입금내역을 자동으로 확인합니다. (서비스시작일 이전 주문정보는 매칭이 안됩니다.)<br>
		- 매칭범위 : 기본 7일간의 입금내역을 조회할수있으며 서비스 시작일이후의 모든 주문정보에 대해 매칭이 자동으로 이루어집니다.<br>
		- 매칭기준 : 은행, 계좌번호, 금액, 입금자명으로 매칭작업이 이루어집니다.<br>
		- 동일주문의 경우 : 은행, 계좌번호, 금액, 입금자명이 동일한 주문의 경우 '매칭실패(동명이인)' 으로 처리되며 반드시 수작업으로 입금확인 처리해야 합니다.<br>
		- 입금된 경우 : 은행, 계좌번호, 금액, 입금자명중 주문정보 내역의 정보와 한가지라도 틀리면 '매칭실패(불일치)' 로 처리되며 반드시 수작업으로 입금확인 처리해야 합니다.<br>
		- 매칭주기 : 1시간 간격
	  </div>
	  <div class="title">[주문정보 매칭] 실시간입금확인 실행하기</div>
	  <div class="explain">
		- 매칭주기 1시간 간격보다 빠르게 입금확인이 필요한 경우 클릭하시면 됩니다.<br>
		- <font color='red'>단, 뱅크다에서 가져오는 정보이므로 잦은 클릭은 데이터로딩에 문제가 생길수 있습니다.</font><br>
	  </div>
	</div>
</div>
<?php include "../foot.php"; ?>

