<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

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
if(isset($t_conn)     && $t_conn)     $tmp_param[] = "t_conn=".$t_conn;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

//--------------------------------------------------------------------------------------------------


$prev_period = $srh_prev;
$next_period = $srh_next." 23:59:59";

$where = array();

if(!empty($s_status)) {

	if($s_status == 'OR')        $ordsearch_sql = "order_date";
	else if($s_status == 'OY')   $ordsearch_sql = "pay_date";
	else if($s_status == 'DC')   $ordsearch_sql = "send_date";
	else if($s_status == 'DR')   $ordsearch_sql = "send_pre_date";
	else if($s_status == 'DI')   $ordsearch_sql = "send_pro_date";
	else if($s_status == 'OC')   $ordsearch_sql = "cancel_date";
	else if($s_status == 'RD')   $ordsearch_sql = "cancel_request_date";
	else if($s_status == 'RC')   $ordsearch_sql = "cancel_date";
	else if($s_status == 'CD')   $ordsearch_sql = "ex_request_date";
	else if($s_status == 'CC')   $ordsearch_sql = "exchange_date";
	else if($s_status == 'MI')   $ordsearch_sql = "order_date";

} 

if(isset($srh_prev) && $srh_prev) {
	if($s_status == 'ALL' || $s_status == ''){
		$where[] = "wo.order_date >= '$prev_period' and order_date <= '$next_period'";
	} else {
		$where[]  = "wo.$ordsearch_sql >= '$prev_period' and wo.$ordsearch_sql <= '$next_period' ";
	}
} 

if($s_status == "" || $s_status == 'ALL') $where[] = "wo.status != ''";
else if($s_status == "MI") $where[] = "wo.status = ''";
else $where[] = "wo.status = '$s_status'";

if($searchopt && $searchkey) $where[] = "wo.$searchopt like '%$searchkey%'";

if(!empty($t_pay_method)){
	$_arr = implode("/",$t_pay_method);
	$_val = explode("/", $_arr);

	foreach($_val as $key => $value){
		if(!empty($value)) $tmp_paymethod .= " OR pay_method='$value'";
	}
	$tmp_paymethod  = substr($tmp_paymethod,3);
	$where[]        = "({$tmp_paymethod})";
}

if(!empty($t_conn)) $where[] = "connect_type = '$t_conn'";

$search_query   = ($where) ? " AND ".implode(" AND ", $where) : "";

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$six_btn = "<img src='../image/sicon_sixmonth.gif' border='0' align='absmiddle'>";
$yer_btn = "<img src='../image/sicon_year.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";

$_ord_part_sql = "
	SELECT COUNT(CASE status WHEN 'OR' THEN 1 END) or_cnt					-- 주문접수
		 , COUNT(CASE status WHEN 'OY' THEN 1 END) oy_cnt					-- 결제완료
		 , COUNT(CASE status WHEN 'DR' THEN 1 END) dr_cnt					-- 배송준비중
		 , COUNT(CASE status WHEN 'DI' THEN 1 END) di_cnt					-- 배송처리
		 , COUNT(CASE status WHEN 'DC' THEN 1 END) dc_cnt					-- 배송완료
		 , COUNT(CASE status WHEN 'OC' THEN 1 END) oc_cnt					-- 주문취소
		 , COUNT(CASE status WHEN 'RD' THEN 1 END) rd_cnt					-- 취소요청
		 , COUNT(CASE status WHEN 'RC' THEN 1 END) rc_cnt					-- 취소완료
		 , COUNT(CASE status WHEN 'CD' THEN 1 END) cd_cnt					-- 교환요청
		 , COUNT(CASE status WHEN 'CC' THEN 1 END) cc_cnt					-- 교환완료
		 , COUNT(CASE WHEN status='MI' OR status='' THEN 1 END) mi_cnt		-- 미주문
	  FROM wiz_order
	 WHERE orderid != ''
";
$_ord_part_res = query($_ord_part_sql);
$_ord_part_row = sql_fetch_arr($_ord_part_res);

$or_cnt = number_format($_ord_part_row['or_cnt']);
$oy_cnt = number_format($_ord_part_row['oy_cnt']);
$dr_cnt = number_format($_ord_part_row['dr_cnt']);
$di_cnt = number_format($_ord_part_row['di_cnt']);
$dc_cnt = number_format($_ord_part_row['dc_cnt']);
$oc_cnt = number_format($_ord_part_row['oc_cnt']);
$rd_cnt = number_format($_ord_part_row['rd_cnt']);
$rc_cnt = number_format($_ord_part_row['rc_cnt']);
$cd_cnt = number_format($_ord_part_row['cd_cnt']);
$cc_cnt = number_format($_ord_part_row['cc_cnt']);
$mi_cnt = number_format($_ord_part_row['mi_cnt']);

$sql = "select orderid from wiz_order where status != ''";
$all_total = sql_fetch_rows($sql);


?>
<script type="text/javascript">
// 주문상태 변경
function chgStatus(s_status){
	if(s_status == 'ALL'){
		$("#srh_prev").val('');
		$("#srh_next").val('');
		document.frm.s_status.value = s_status;
		document.frm.submit();
	} else {
		document.frm.s_status.value = s_status;
		document.frm.submit();
	}
}

//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
function selectAll(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function orderDelete(){

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].orderid.value + "|";
			}
		}
	}

	if(selorder == ""){
		alert("삭제할 주문을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 주문을 정말 삭제하시겠습니까?")){
			document.location = "order_save.php?mode=delete&selorder=" + selorder + "&<?=$param?>";
		}else{
			return;
		}
	}
	return;

}

// 선택 주문서 출력
function orderPrint() {

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].orderid.value + "|";
				}
			}
	}

	if(selorder == ""){
		alert("출력할 주문을 선택하지 않았습니다.");
		return;
	}else{
		<?
		if($oper_info['deliveryType'] == "P"){
		?>
		document.order_print.location = "order_print_p.php?selorder=" + selorder;
		<? } else { ?>
		document.order_print.location = "order_print.php?selorder=" + selorder;
		<? } ?>
	}
	return;

}

// 선택주문 상태변경
function batchStatus(){

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].orderid.value + ":" + document.forms[i].status.value + "|";
				}
			}
	}

	if(selorder == ""){
		alert("변경할 주문을 선택하지 않았습니다.");
		return;
	}else{
		var url = "order_status.php?selorder=" + selorder;
		window.open(url,"batchStatus","height=250, width=200, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
	return;

}

// 배송정보 엑셀입력
function excelDelUp(num) {

	if(num == 0){
		alert("결제완료된 주문건이 없습니다.");
		return;
	} else {
		<? if($oper_info['deliveryType'] == 'P'){ ?>
		var url = "new_excelup_prd.php";
		<? } else { ?>
		var url = "new_excelup.php";
		<? } ?>
		window.open(url,"excelUp","height=520, width=600, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 주문정보 엑셀다운
function excelDown(){

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].orderid.value + "|";
				}
			}
	}

	if(selorder != ''){
		var url = "order_excel.php?selorder="+selorder+"&<?=$param?>";
	} else {
		var url = "order_excel.php?<?=$param?>";
	}
	window.open(url,"excelDown","height=350, width=570, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, top=100, left=100");
}


// 선택 주문정보 엑셀다운
function excelDown_check(){

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].orderid.value + "|";
				}
			}
	}

	if(selorder == ""){
		alert("엑셀파일로 저장할 주문을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 주문을 다운받으시겠습니까?")){
				var url = "order_excel_check.php?selorder=" + selorder + "&<?=$param?>";
				window.open(url,"excelDown","height=350, width=570, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, top=100, left=100");
		}else{
			return;
		}
	}

}


// 기간설정
function setPeriod(from,to,start,end,type){

	if(from == '' && to == ''){
		document.frm.s_status.value = 'ALL';
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	} else {
		document.frm.s_type.value = type;
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	}

	document.frm.submit();
}

function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

$(function() {

	var setperiod = '<?php echo $setperiod ?>';
	if(setperiod == '') {

		var this_day = "<?php echo THIS_TIME_YMD ?>";
		var prev_day = "<?php echo date('Y-m-d',strtotime('-1 week')); ?>";

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
});

-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">주문목록</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">주문검색 목록 입니다.</td>
	</tr>
</table>

<br>
<form name="frm" id="frm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="">
<input type="hidden" name="s_status" value="<?=$s_status?>">
<input type="hidden" name="s_type" value="<?=$s_type?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="setperiod" value="<?php echo $setperiod ?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp; 진행상태</td>
		<td width="85%" class="t_value" colspan="3">
			<table>
				<tr><td>
				<input type="button" onClick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'" value="전체 (<?php echo $all_total ?>)" <? if($s_status == "") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('OR');" value="주문접수 (<?php echo $or_cnt ?>)" <? if($s_status == "OR") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('OY');" value="결제완료 (<?php echo $oy_cnt ?>)" <? if($s_status == "OY") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('DR');" value="배송준비중 (<?php echo $dr_cnt ?>)" <? if($s_status == "DR") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('DI');" value="배송처리 (<?php echo $di_cnt ?>)" <? if($s_status == "DI") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('DC');" value="배송완료 (<?php echo $dc_cnt ?>)" <? if($s_status == "DC") echo "class=btn_pm"; else echo "class=btn_p"; ?>><br>
				<input type="button" onClick="chgStatus('OC');" value="주문취소 (<?php echo $oc_cnt ?>)" <? if($s_status == "OC") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('RD');" value="취소요청 (<?php echo $rd_cnt ?>)" <? if($s_status == "RD") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('RC');" value="취소완료 (<?php echo $rc_cnt ?>)" <? if($s_status == "RC") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('CD');" value="교환요청 (<?php echo $cd_cnt ?>)" <? if($s_status == "CD") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('CC');" value="교환완료 (<?php echo $cc_cnt ?>)" <? if($s_status == "CC") echo "class=btn_pm"; else echo "class=btn_p"; ?>>
				<input type="button" onClick="chgStatus('MI');" value="미완료주문 (<?php echo $mi_cnt ?>)" <? if($s_status == "MI") echo "class=btn_pm"; else echo "class=btn_pms"; ?> title="주문진행이거나 주문진행을 취소한 내역">
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="t_name">&nbsp; 기간검색</td>
		<td class="t_value" colspan="3">
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
	</tr>
	<tr>
		<td width="15%" class="t_name">&nbsp; 조건검색</td>
		<td width="35%" class="t_value">
			<select name="searchopt" class="select2">
				<option value="send_name"    <? if($searchopt == "send_name")    echo "selected"; ?>>주문자명
				<option value="rece_name"    <? if($searchopt == "rece_name")    echo "selected"; ?>>수취인명
				<option value="orderid"      <? if($searchopt == "orderid")      echo "selected"; ?>>주문번호
				<option value="send_id"      <? if($searchopt == "send_id")      echo "selected"; ?>>아이디
				<option value="send_hphone"  <? if($searchopt == "send_hphone")  echo "selected"; ?>>휴대폰
				<option value="rece_tphone"  <? if($searchopt == "rece_tphone")  echo "selected"; ?>>전화번호
				<option value="send_email"   <? if($searchopt == "send_email")   echo "selected"; ?>>이메일
				<option value="account_name" <? if($searchopt == "account_name") echo "selected"; ?>>입금자명
			</select>
			<input type="text" name="searchkey" value="<?=$searchkey?>" size="25" class="input">
		</td>
		<td width="15%" class="t_name">&nbsp; 결제방식</td>
		<td width="35%" class="t_value">
			<?
			if(!empty($t_pay_method)){
				$t_pay_arr = implode("/",$t_pay_method);
				$arr_tmp   = explode("/",$t_pay_arr);
				for($ii=0; $ii<count($arr_tmp); $ii++){
					$srh_paymethod[$arr_tmp[$ii]] = "checked";
				}
			}

			if($oper_info['kakao_pay_use'] == "Y") $pay_method_type = $oper_info['pay_method']."KK/";
			else                                   $pay_method_type = $oper_info['pay_method'];

			$pay_method = explode("/",$pay_method_type);
			for($ii=0; $ii<count($pay_method)-1; $ii++){
				$pay_title = pay_method($pay_method[$ii]);
				$check_val = $srh_paymethod[$pay_method[$ii]];
			?>
			<span style='vertical-align: middle;'><input type="checkbox" name="t_pay_method[]" value="<?=$pay_method[$ii]?>" <?=$check_val?>></span><?=$pay_title?>&nbsp;
			<?
			}
			?>
			<!-- <span style="vertical-align: middle"><input type="checkbox" name="kk_pay_method" value="KK" <? if($kk_pay_method == 'KK') echo "checked";?>></span>카카오페이 -->
		</td>
	</tr>
	<?if($oper_info['chk_connect_type']=="Y"){?>
	<tr>
		<td class="t_name">&nbsp; 주문경로</td>
		<td class="t_value" colspan="3">
			<span style='vertical-align: middle;'><input type="radio" name="t_conn" value="PC" <? if($t_conn == "PC") echo "checked";?>></span>PC&nbsp;
			<span style='vertical-align: middle;'><input type="radio" name="t_conn" value="M"  <? if($t_conn == "M") echo "checked";?>></span>Mobile
		</td>
	</tr>
	<? } ?>
</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center"><!-- <input type="image" src="../image/btn_search.gif" align="absmiddle"> -->
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
		</td>
	</tr>
</table>

</form>

<br>
<?php
$sql = "
	select count(distinct wo.orderid) total
	  from wiz_order wo 
	  left join wiz_basket wb
	    on wo.orderid = wb.orderid
	 where wo.orderid !='' 
	   $search_query
";
$result = query($sql);
$row = sql_fetch_arr($result);
$total = $row['total'];

/**
 * 결제완료시에만 가능
 */
$_sql2 = "select * from wiz_basket where orderid !='' and status = 'OY' ";
$_total2 = sql_fetch_rows($_sql2);

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
		<td><span class="title_msg">총 주문수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색결과 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
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
			<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
			<input type="button" value="운송장번호 엑셀일괄등록" class="btnExcel2" onClick="excelDelUp(<?=$_total2?>);">

		</form>
		</td>
	</tr>
	<tr><td height=5></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
		<th width="8%">주문번호</th>
		<th width="19%">주문상품</th>
		<th width="15%">주문자명</th>
		<?
		$sql_connect = "select * from wiz_operinfo";
		$row_connect = sql_fetch($sql_connect);
		?>
		<?if($row_connect['chk_connect_type']=="Y"){?>
		<th width="5%">주문경로</th>
		<?}?>
		<th width="10%">결제수단</th>
		<th width="8%">금액</th>
		<th width="10%">주문일시</th>
		<th width="12%">처리상태</th>
		<th width="13%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	</form>
	<?php
	$orderid = "";

	$sql = "
		select wo.order_date
			 , wo.orderid
			 , wo.send_name
			 , wo.send_id
			 , wo.pay_method
			 , wo.total_price
			 , wo.status
			 , wo.deliver_num
			 , wo.deliver_date
			 , wo.escrow_check
			 , wo.connect_type
		  from wiz_order wo
		  left join wiz_basket wb
		    on wo.orderid = wb.orderid
		where wo.orderid !=''
		  $search_query
		order by wo.orderid desc 
		limit $start, $rows
	";
	$result = query($sql);
	while(($row = sql_fetch_obj($result)) && $rows){

		if($orderid == $row->orderid) continue;
		else $orderid = $row->orderid; $ordernum = 0;

		if($row->status == "OY") $stacolor = "6DCFF6";
		else if($row->status == "DC" || $row->status == "CC") $stacolor = "BD8CBF";
		else if($row->status == "OC" || $row->status == "RC" || $row->status == "RD") $stacolor = "ED1C24";
		else $stacolor = "";

		$ord_status = order_status($row->status); 

		if(!strcmp($row->escrow_check, "Y")) $escrow_check = "<br><font color='green'>[에스크로]</font>";
		else  $escrow_check = "";

		$_orderid = "<font color='#ff6600'>".$row->orderid."</font>";

		$_sql = "select * from wiz_basket where orderid='".$orderid."'";
		$_result = query($_sql);
		$_total = sql_fetch_rows($_sql);
		while($_row = sql_fetch_obj($_result)){

			if($_total>1){
				$payment_prdname = $_row->prdname." 외".($_total-1)."개";
			}else{
				$payment_prdname = $_row->prdname;
			}

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
	<input type="hidden" name="searchopt"  value="<?=$searchopt?>">
	<input type="hidden" name="searchkey"  value="<?=$searchkey?>">
	<input type="hidden" name="menucode"  value="<?=$menucode?>">

	<tr onmouseover="this.style.backgroundColor='#e8f3f7'" onmouseout="this.style.backgroundColor='#ffffff'">
		<td align="center" class="t_line" height="40"><input type="checkbox" name="select_checkbox"></td>
		<td align="center" class="t_line"><a href="order_info_p.php?orderid=<?=$row->orderid?>&page=<?=$page?>&<?=$param?>"><?=$_orderid?></a></td>
		<td class="t_line"><?=$payment_prdname?> <?=$escrow_check?></td>
		<td align="center" class="t_line">
		<?
		if($row->send_id == "") echo "$row->send_name [비회원]";
		else echo "<a href='../member/member_input.php?mode=update&id=$row->send_id&prdparam=".urlencode("page=".$page."&".$param)."'>$row->send_name [$row->send_id]</a>";
		?>
		</td>
		<?if($row_connect['chk_connect_type']=="Y"){?>
			<?if($row->connect_type == "M"){?>
				<td align="center" style="color:red" class="t_line">모바일</td>
			<?}else if($row->connect_type == "PC"){?>
				<td align="center" style="color:blue" class="t_line">PC</td>
			<?}else{?>
				<td align="center" class="t_line"></td>
			<?}?>
		<?}?>

		<td align="center" class="t_line"><?=pay_method2($row->pay_method)?></td>
		<td align="right" class="t_line"><?=number_format($row->total_price)?>원 &nbsp; &nbsp;</td>
		<td align="center" class="t_line"><?=substr($row->order_date,0,16)?></td>
		<td align="center" class="t_line">
			<?php echo $ord_status ?>
		</td>
		<td align="center" class="t_line">
			<input type="button" value="상세보기" class="base_btm reg" onClick="document.location='order_info_p.php?orderid=<?=$row->orderid?>&page=<?=$page?>&<?=$param?>'">
		</td>
	</tr>
	</form>
	<?
		$no--;
		$rows--;
	}
	if($total <= 0){
	?>
	<tr><td height=38 colspan=11 align=center>검색된 주문내역이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="33%">
			<input type="button" value="선택삭제" class="btnListchk gray2" onclick="orderDelete()">
			<input type="button" value="상태일괄변경" class="btnListchk2" onclick="batchStatus()">
			<input type="button" value="주문서출력" class="btnprint" onclick="orderPrint()">

		</td>
		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
		<td width="33%"></td>
	</tr>
</table>

<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">[진행상태]</div>
	  <div class="explain">
		- <font color="red">'미완료주문'</font>은 고객이 <font color="red">'주문진행중'</font>이거나 <font color="red">'주문진행을 취소'</font>한 상태입니다. 미완료주문 내역을 삭제하시면 안됩니다.<br>
		- 위 주문 목록에서 주문번호 밑에 [에스크로] 표시가 되있다면 반드시 등록되어야 합니다.<br>
		- [에스크로]가 표시된것은 운영정보설정 > 에스크로 사용함으로 설정된 상태에서 고객이 10만원 이상 주문시 계좌이체, 가상계좌를 이용해서 주문한경우입니다.<br>
		- 에스크로 주문인 경우 실제 결제가 완료되어 결제시스템 회사의 상태가 결제된 이후에 배송정보가 결제시스템 회사로 등록됩니다.
	  </div>
	  <div class="title">[에스크로 주문 처리 주의사항]</div>
	  <div class="explain">
		- 에스크로 주문인경우 반드시 배송정보(택배사,송장번호,발송일자)를 결제시스템 회사에 등록해야 합니다.<br>
		- 위 주문 목록에서 주문번호 밑에 [에스크로] 표시가 되있다면 반드시 등록되어야 합니다.<br>
		- [에스크로]가 표시된것은 운영정보설정 > 에스크로 사용함으로 설정된 상태에서 고객이 10만원 이상 주문시 계좌이체, 가상계좌를 이용해서 주문한경우입니다.<br>
		- 에스크로 주문인 경우 실제 결제가 완료되어 결제시스템 회사의 상태가 결제된 이후에 배송정보가 결제시스템 회사로 등록됩니다.
	  </div>
	  <div class="title">[배송정보 등록방법]</div>
	  <div class="explain">
		- 주문상세보기에서 운송장번호를 입력 후 처리상태를 "배송처리", "배송완료" 로 변경한 경우 결제시스템 회사에 배송정보가 등록됩니다.<br>
		- 위 주문 목록에서 주문번호 밑에 [에스크로] 표시가 되있다면 반드시 등록되어야 합니다.<br>
		- 운송장 번호,발송일자를 입력 후 적용하면 배송정보가 결제시스템 회사로 등록됩니다.
	  </div>
	  <div class="title">[재고 수량 확인]</div>
	  <div class="explain">
		- "주문완료" 시 수량이 감소되며 결제가 완료되지 않으면 직접 "주문취소" 처리를 하셔야 수량이 증가합니다.
	  </div>
	</div>
</div>

<iframe SRC="" width="0" height="0" frameborder="0" border="0" scrolling="no" marginheight="0" marginwidth="0"  name="order_print"></iframe>

<?php include "../foot.php"; ?>