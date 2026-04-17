<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bankda_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/xml/xml.php";

$bk_sql = "select count(idx) as cnt from bankda_member ";
$bk_res = query($bk_sql);
$bk_row = sql_fetch_arr($bk_res);
$bk_cnt = $bk_row['cnt'];

$tmp_param = array();
if(isset($srh_prev)   && $srh_prev)   $tmp_param[] = "srh_prev=".$srh_prev;
if(isset($srh_next)   && $srh_next)   $tmp_param[] = "srh_next=".$srh_next;
if(isset($s_bank)     && $s_bank)     $tmp_param[] = "s_bank=".$s_bank;

$params   = ($tmp_param) ? implode("&", $tmp_param) : "";
$params   = $params."&".$menucodeParam;


if($srh_prev) $sdate = str_replace("-","",$srh_prev); else $sdate = str_replace("-","",$oper_info['bankda_service_date']);
if($srh_next) $edate = str_replace("-","",$srh_next); else $edate = "";
if($s_bank)   $accountnum = $s_bank;

$param = array(
	'service_type'  => $oper_info['bankda_service'],
	'partner_id'    => $oper_info['bankda_partner_id'],
	'partner_pw'    => $oper_info['bankda_partner_pw'],
	'user_id'       => $bankda_info['bankda_id'],
	'bkcode'        => 0,
	'char_set'      => 'utf-8',
	'sdate'         => $sdate,
	'edate'         => $edate,
	'accountnum'    => $accountnum,
	'sort_order'    => 'D'
);

$get_url = "https://ssl.bankda.com/partnership/partner/xmldown.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $get_url);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($curl);

$parser = new myXMLParser($xml);
$parser->Parse();

foreach($parser->document->account as $account) {
	$total = $account->tagAttrs['record'];
}

if(!empty($tmp_rows)) $tmp_rows = $tmp_rows; else $tmp_rows = 10;
$rows = $tmp_rows;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$rrows = $rows * $page;
?>
<script>
// 기간설정
$(function() {

	var setperiod = '<?php echo $setperiod ?>';
	if(setperiod == '') {

		var this_day = "";
		var prev_day = "";

		$("#srh_prev").val(prev_day);
		$("#srh_next").val(this_day);
		$("#all").addClass("period_2");

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
});
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">통장입금내역 실시간 조회 </td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">입금일을 기준으로 입금확인된 내역을 단순히 조회하는 기능입니다.</td>
	</tr>
</table>
<br>
<form name="searchForm" id="searchForm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="setperiod" value="<?php echo $setperiod ?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">입금일</td>
		<td width="35%" class="t_value">
			<span class="calendar">
			<input type="text" name="srh_prev" id="srh_prev" class="input2" size="15" value="<?=$srh_prev?>" data-date-s='srh_prev'> ~ 
			<input type="text" name="srh_next" id="srh_next" class="input2" size="15" value="<?=$srh_next?>" data-date-e='srh_next'>

			<input type="button" id="today" name="period" value="오늘" class="period_1 btn_period">
			<input type="button" id="week" name="period" value="7일" class="period_1 btn_period">
			<input type="button" id="fifteen" name="period" value="15일" class="period_1 btn_period">
			<input type="button" id="month" name="period" value="한달" class="period_1 btn_period">
			<input type="button" id="year" name="period" value="1년" class="period_1 btn_period">
			<input type="button" id="all" name="period" value="전체" class="period_1 btn_period">
			</span>	
		</td>
		<td width="15%" class="t_name">은행명</td>
		<td width="35%" class="t_value">
			<select name="s_bank" class="select2">
				<option value="">은행명</option>
				<?php
				foreach($tmp_bka_list AS $k=>$val) {
					$bnkname = $tmp_bkn_list[$k];
				?>
				<option value="<?php echo $val ?>" <?php if($s_bank == $val) echo "selected";?>><?php echo $bnkname ?></option>
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
			<!-- <input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">&nbsp; -->
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
		</td>
	</tr>
</table>
</form>
<br>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 거래수 : <strong id="total_prd_cnt"><?php echo $total?></strong></span>
		</td>
		<td align="right">
		</td>
	</tr>
</table>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="20%">거래일자</th>
		<th width="*">거래은행명</th>
		<th width="15%">거래내역</th>
		<th width="15%">입금자</th>
		<th width="15%">입금액</th>
		<th width="15%">출금액</th>
		<th width="13%">잔액</td>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<?php
	$_actnumber = "";
	$_bkname    = "";
	$_bkcode    = "";
	$_bkdate    = "";
	$_bktime    = "";
	$_bkjukyo   = "";
	$_bkcontent = "";
	$_bketc     = "";
	$_bkinput   = "";
	$_bkoutput  = "";
	$_bkjango   = "";
	$_mid       = "";

	foreach($parser->document->account as $account) {
		foreach($account->accinfo as $accinfo) {

			$_actnumber         .= $accinfo->tagAttrs['actnumber']."|";		//-- 계좌번호
			$_bkname            .= $accinfo->tagAttrs['bkname']."|";		//-- 거래은행명
			$_bkcode            .= $accinfo->tagAttrs['bkcode']."|";		//-- 거래내역id(뱅크다의 일련번호)
			$_bkdate            .= $accinfo->tagAttrs['bkdate']."|";		//-- 거래일자
			$_bktime            .= $accinfo->tagAttrs['bktime']."|";		//-- 거래시간 (제공안하는 은행도 있어 노출안시킴)
			$_bkjukyo           .= $accinfo->tagAttrs['bkjukyo']."|";		//-- 입금자명
			$_bkcontent         .= $accinfo->tagAttrs['bkcontent']."|";		//-- 거래방식 등 부가정보
			$_bketc             .= $accinfo->tagAttrs['bketc']."|";			//-- 점포명 등 부가정보
			$_bkinput           .= $accinfo->tagAttrs['bkinput']."|";		//-- 입금액
			$_bkoutput          .= $accinfo->tagAttrs['bkoutput']."|";		//-- 출금액
			$_bkjango           .= $accinfo->tagAttrs['bkjango']."|";		//-- 거래후 잔액
			$_mid               .= $accinfo->tagAttrs['mid']."|";			//-- 계좌소유자 ID

		}
	}

	for($k=$start; $k<$rrows; $k++) {
		
		$tactnumber  = explode("|", $_actnumber);
		$tbkname     = explode("|", $_bkname);
		$tbkcode     = explode("|", $_bkcode);
		$tbkdate     = explode("|", $_bkdate);
		$tbktime     = explode("|", $_bktime);
		$tbkjukyo    = explode("|", $_bkjukyo);
		$tbkcontent  = explode("|", $_bkcontent);	
		$tbketc      = explode("|", $_bketc);	
		$tbkinput    = explode("|", $_bkinput);	
		$tbkoutput   = explode("|", $_bkoutput);	
		$tbkjango    = explode("|", $_bkjango);	
		$tmid        = explode("|", $_mid);	

		if($tbkdate[$k]) {
			$tbk_year  = substr($tbkdate[$k],0,4);
			$tbk_month = substr($tbkdate[$k],4,2);
			$tbk_day   = substr($tbkdate[$k],6,2);

			$tbkdate = $tbk_year.".".$tbk_month.".".$tbk_day;
	?>
	<tr>
		<td height="38" align="center"><strong><?php echo $tbkdate ?><!-- --<?php echo $tbkcode[$k] ?> --></strong></td>
		<td align="center"><?php echo $tbkname[$k] ?></td>
		<td align="center"><?php echo $tbketc[$k] ?></td>
		<td align="center"><!-- <?php echo $tbkjukyo[$k] ?> --></td>
		<td align="center"><!-- <?php echo number_format($tbkinput[$k]) ?> --></td>
		<td align="center"><!-- <?php echo number_format($tbkoutput[$k]) ?> --></td>
		<td align="center"><!-- <?php echo number_format($tbkjango[$k]) ?> --></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?php
		}
	}
	if($total <= 0) {
	?>
	<tr>
		<td height="38" align="center" colspan="20">-- 내역이 없습니다 --</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="100%" align="center"><? print_pagelist($page, $lists, $page_count, $params); ?></td>
	</tr>
</table>

<? include "../foot.php"; ?>