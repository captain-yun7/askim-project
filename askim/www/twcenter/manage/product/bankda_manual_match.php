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
/*
작업일시	: 2020-10-13
작업자명	: 이상민
작업내용	: 입금자명과 금액이 동일한 입금내역이 2건이상 존재할 때 자동으로 매칭시키지 않고 실패처리하여 관리자가 수동매칭시킬 수 있도록 구분값 추가
*/
$manual_array = array("MA","MB","MF","MS");
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
var prev_day            = bankda_service_date;

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
		//$("#srh_next").val(this_day);
		//$("#week").addClass("period_2");

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
		//$("#sdate").val(prev_day);
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
	$("[name='bkmatchdate[]']").datepicker(calendar3);

});

$(function () {
	manual_match();
	order_match();
});

var bk_manual_up = function(idx) {

	var bkmatchres  = $("[id=bkmatchres_"+idx+"]").val();
	var bkmatchdate = $("[id=bkmatchdate_"+idx+"]").val();
	var bkorderid   = $("[id=bkorderid_"+idx+"]").val();

	if(bkmatchdate == "") {
		alert("최종매칭일을 선택해주세요.");
		return false;
	}

	if(bkorderid == "") {
		alert("매칭시킬 주문번호를 입력해주세요.");
		return false;
	}

	if(bkorderid) {
		/*
		작업일시	: 2020-10-13
		작업자명	: 이상민
		작업내용	: 입금자명과 금액이 동일한 입금내역이 2건이상 존재할 때 자동으로 매칭시키지 않고 실패처리하여 관리자가 수동매칭시킬 수 있도록 구분값 추가
		*/
		if(bkmatchres == 'MA' || bkmatchres == 'MB' || bkmatchres == 'MS') {
			alert("매칭실패상태에서 주문번호를 입력할수 없습니다.");
			return false;
		}
	}

	var params = "";
	params += "page=" + $("[name=page]").val();
	params += "&bkmatchres=" + $("[id=bkmatchres_"+idx+"]").val();
	params += "&bkmatchdate=" + $("[id=bkmatchdate_"+idx+"]").val();
	params += "&bkorderid=" + $("[id=bkorderid_"+idx+"]").val();
	params += "&bkmemo=" + $("[id=bkmemo_"+idx+"]").val();
	params += "&bkidx=" + idx;
	//console.log(params);
	$.ajax({
		type:"post"
		, cache: false
		, url: "./bankda_ajax_manual_match_update.php"
		, data: params
		, dataType: "json"
		, success: function(data) {
			if(data.result == '0000') {
				alert(data.msg);
				document.location.reload();
			} else {
				alert(data.msg);
				return false;
			}
		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});

};


-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">입금내역 주문서 수동매칭 </td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="right"><input type="button" value="자동매칭" class="search_btn3" onclick="AccountLoad();" title="수동매칭체크전 매칭을 통해 다시한번 실시간입금조회를 합니다."></td>
	</tr>
</table>
<br>
<form name="manualFrm" id="manualFrm">
<input type="hidden" name="page"        value="<?=$page?>">
<input type="hidden" name="menucode"    value="<?=$menucode?>">
<input type="hidden" name="setperiod"   value="<?php echo $setperiod ?>">
<input type="hidden" name="setperiod2"  value="<?php echo $setperiod2 ?>">
<input type="hidden" name="sorter"      id="sorter">
<input type="hidden" name="sorter2"     id="sorter2">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 실시간 입금내역 검색</td>
				</tr>
			</table>
			<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
				<tr>
					<td width="15%" class="t_name">&nbsp; 입금일</td>
					<td width="85%" class="t_value">
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
					<td class="t_name">&nbsp; 조건검색</td>
					<td class="t_value">
						<select name="searchopt" class="select2">
							<option value="bkjukyo"  <? if($searchopt == "bkjukyo") echo "selected"; ?>>입금자명
							<option value="bkinput"  <? if($searchopt == "bkinput") echo "selected"; ?>>입금액
							<option value="orderid"  <? if($searchopt == "orderid") echo "selected"; ?>>주문번호
						</select>
						<input type="text" name="searchkey" value="<?=$searchkey?>" size="25" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">&nbsp; 현재상태/은행명</td>
					<td class="t_value">
						<select name="s_status" class="select2">
							<?php
							foreach($_bk_manual_status_code AS $val) {
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
						<input type="button" value="검색" class="search_btn2" onclick="manual_match();">
					</td>
				</tr>
			</table>
			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="t_rd" colspan="6"></td></tr>
				<tr class="t_th">
					<th width="10%">번호</th>
					<th width="18%">입금일</th>
					<th width="18%">계좌번호</th>
					<th width="18%">입금액</th>
					<th width="18%">입금자명</th>
					<th width="18%">현재상태</th>
				</tr>
			</table>
			<div id="manual_match_list"></div>
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tpad_30" id="paging"></td>
				</tr>
			</table>

		</td>
		<td width="2%">&nbsp;</td>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 입금대기(주문접수) 검색</td>
				</tr>
			</table>
			<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
				<tr>
					<td width="15%" class="t_name">&nbsp; 주문일</td>
					<td width="85%" class="t_value">
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
					<td class="t_name">&nbsp; 조건검색</td>
					<td class="t_value">
						<select name="searchopt2" class="select2">
							<option value="account_name"  <? if($searchopt2 == "account_name") echo "selected"; ?>>입금자명
							<option value="send_name"     <? if($searchopt2 == "send_name") echo "selected"; ?>>주문자명
							<option value="rece_name"     <? if($searchopt2 == "rece_name") echo "selected"; ?>>수령자명
							<option value="orderid"       <? if($searchopt2 == "orderid") echo "selected"; ?>>주문번호
						</select>
						<input type="text" name="searchkey2" value="<?=$searchkey2?>" size="25" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">&nbsp; 결제금액</td>
					<td class="t_value">
						<input type="text" name="price1" class="input" size="15" value="<?=$price1?>"> ~ 
						<input type="text" name="price2" class="input" size="15" value="<?=$price2?>">
					</td>
				</tr>
			</table>
			<br>
			<table width="100%" cellspacing="1" cellpadding="3" border="0">
				<tr>
					<td align="center">
						<input type="button" value="검색" class="search_btn2" onclick="order_match();">
					</td>
				</tr>
			</table>
			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="t_rd" colspan="20"></td></tr>
				<tr class="t_th">
					<th width="10%">번호</th>
					<th width="18%">주문일자</th>
					<th width="18%">주문번호</th>
					<th width="18%">결제금액</th>
					<th width="18%">입금자</th>
					<th width="18%">처리상태</th>
				</tr>
			</table>
			<div id="order_match_list"></div>
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tpad_30" id="paging2"></td>
				</tr>
			</table>

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
		<th width="5%">입금완료일</th>
		<th width="13%">계좌번호</th>
		<th width="10%">은행명</th>
		<th width="10%">입금(예정)금액</th>
		<th width="10%">입금자명</th>
		<th width="10%">현재상태</th>
		<th width="10%">최종매칭일</th>
		<th width="10%">주문번호</th>
		<th width="10%">간단메모</th>
		<th width="7%"></th>
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

		// 주문접수시 접수대기로 노출시킴 (실시간입금확인등에서 같이 노출하려고 했으나 불필요함), 일단놔두세요.
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

		$ordidLink = "<a href='order_info.php?orderid=".$orderid."&mresult=".$mresult_code."&page=".$page."&".$param."' target='_blank'>".$orderid."</a>";

		$ord_in = sql_fetch("select bk_memo from wiz_order where orderid = '".$orderid."' ");
		$bk_memo = htmlspecialchars($ord_in['bk_memo']);
		$bk_memo = str_replace("\n", "<br>", $bk_memo);

	?>
	<form name="frm_<?php echo $row->idx ?>">
	<tr onmouseover="this.style.backgroundColor='#e8f3f7'" onmouseout="this.style.backgroundColor='#ffffff'">
		<td align="center" class="t_line" height="38"></td>
		<td align="center" class="t_line"><?php echo $bkdate ?></td>
		<td align="center" class="t_line"><?php echo $actnumber ?></td>
		<td align="center" class="t_line"><?php echo $bkname ?></td>
		<td align="center" class="t_line"><?php echo $bkinput ?>원</td>
		<td align="center" class="t_line"><?php echo $bkjukyo ?></td>
		<?php
		if(in_array($row->bkmatchres, $manual_array)) {
		?>
		<td align="center" class="t_line">
			<select name="bkmatchres" id="bkmatchres_<?php echo $row->idx ?>" class="select2">
				<?php
				foreach($_bk_status_code AS $val) {
				?>
				<option value="<?php echo $val[0] ?>" <?php if($row->bkmatchres == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
				<?php
				}
				?>
			</select> 
		</td>
		<td align="center" class="t_line">
			<input type="text" id="bkmatchdate_<?php echo $row->idx ?>" name="bkmatchdate[]" class="input" size="10">
		</td>
		<td align="center" class="t_line">
			<input type="text" id="bkorderid_<?php echo $row->idx ?>" name="bkorderid" class="input" size="15" value="">
		</td>
		<td align="center" class="t_line">
			<input type="text"id="bkmemo_<?php echo $row->idx ?>" name="bkmemo" class="input" size="15">
		</td>
		<td align="center" class="t_line">
			<input type="button" value="수동매칭" class="base_btm blue2" onclick="bk_manual_up('<?php echo $row->idx ?>')">
		</td>
		<?php
		} else {
		?>
		<td align="center" class="t_line"><?php echo $match_result ?></td>
		<td align="center" class="t_line"><?php echo $match_date ?></td>
		<td align="center" class="t_line"><?php echo $ordidLink ?></td>
		<td align="center" class="t_line">
			<div class="memo_area">
				<a class="btn_memo_s2">메모
					<div class="name_box" style="text-align:left; display:none;">
						<div class="cont"><?php echo $bk_memo ?></div>
					</div>
				</a>
			</div>		
		</td>
		<td align="center" class="t_line"></td>
		<?php
		}
		?>
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

<br>
<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0">
	 <tr>
		<td width="100%" align="center">
			<input type="button" value="일괄수정" class="search_btn2" onclick="order_match();">		
		</td>
	</tr>
</table> -->

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" align="center"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
	</tr>
</table>
<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">[수동매칭] </div>
	  <div class="explain">
	    - 수동매칭이란? 자동매칭이 정상적으로 이루어지지 않아 관리자가 직접 매칭을 시키는 방식입니다.<br>
		<font color='#fff'>-</font> 통신장애 등의 문제로 상태값들이 정상적으로 변경되지 않을 수 있으므로, <font color='red'><strong>매칭이후 주문내역을 반드시 확인하여야 합니다.</strong></font><br>
		- 실시간 입금내역 검색 : 현재상태가 매칭실패에 대한 내역을 노출시킵니다.<br>
		- 입금대기(주문점수) 검색 : 처리상태가 입금대기(주문접수)만 노출시킵니다.<br>
		- 매칭주기 1시간마다 자동매칭되며 그렇지 않은경우 2개의 검색을 비교하여 수동매칭 처리합니다.
	  </div>
	</div>
</div>
<?php include "../foot.php"; ?>