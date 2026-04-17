<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include "../head.php";
include "../../lib/datepicker_lib.php";
include_once "../../inc/oper_info.php";

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "status=$status&srh_prev=$srh_prev&srh_next=$srh_next";
$param .= "&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";
$param .= "&s_type=$s_type&tax_type=$tax_type&tmp_rows=$tmp_rows";
//--------------------------------------------------------------------------------------------------

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";

if($tax_type == "T") $tax_title = "세금계산서";
else if($tax_type == "C") $tax_title = "현금영수증";
if($tmp_rows == '') $tmp_rows = 20;

?>
<script type="text/javascript">
$(function() {
	TotalList();
});

function selectAll() {

	if($("#checkAll").prop("checked")) {
		$("input[name=select_checkbox]:checkbox").prop("checked", true);
	} else {
		$("input[name=select_checkbox]:checkbox").prop("checked", false);
	}
}

function viewTax(orderid){

	var ccontent = "#ccontent_"+orderid;
		if($(ccontent).css("display") == "none"){
		$(ccontent).show();
	} else {
		$(ccontent).hide();
	}

}

function taxDelete() {

	$.checkCnt = $("input[name=select_checkbox]:checked").length;

	if($.checkCnt == 0){
		alert("삭제할 데이터를 선택하세요.");
		return false;
	} else {

		var select_checkbox = [];
		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		var selected = select_checkbox.join('|');

	}

	if(selected == "") {
		alert("삭제할 데이터를 선택하지 않았습니다.");
		return;
	} else {

		if(confirm("선택한 항목을 정말 삭제하시겠습니까?")){
			document.location = "order_save.php?mode=tax_delete&selvalue=" + selected + "&tax_type=<?=$tax_type?>&<?=$menucodeParam?>";
		}else{
			return;
		}
	}
	return;

}

function barobill(){

	var tax_type = "<?php echo $tax_type ?>";
	$.checkCnt = $("input[name=select_checkbox]:checked").length;

	if($.checkCnt == 0){
		alert("<?php echo $tax_title ?>(을)를 발행할 데이터를 선택하세요.");
		return false;

	} else {

		var select_checkbox = [];
		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		var selected = select_checkbox.join('|');

	}

	if(selected == "") {
		alert("<?php echo $tax_title ?>(을)를 발행할 데이터를 선택하지 않았습니다.");
		return;
	} else {
		
		var ajax_url = (tax_type == 'C') ? 'tax_cashbill_save.php' : 'tax_bill_save.php';
//		console.log(ajax_url);
//		console.log(selected);

		if(confirm("선택하신 데이터의 <?php echo $tax_title ?>(을)를 발행하시겠습니까?")){
//console.log(ajax_url+"?mode=barobill&selected="+selected);
			$.ajax({
				type: "POST"
				,url: ajax_url
				,dataType: "json"
				,data: {"mode": "barobill", "selected": selected}
				,success: function(data, textStatus, jqXHR) {
					//console.log(data.msg);
					if(data.result == "00"){
						alert(data.msg);
						document.location.reload();
					}
				}
				,error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
			return;

		}else{
			return;
		}
	}
	return;

}

/** 리스트 **/
var TotalList = function()
{
	var frm = document.frm;
	var html = $("[name=frm]").serialize();
	//console.log(html);

//	$("#TotalList").empty();
//	var Loading = "<tr><td height='35' align='center' class='loading'><img src='/twcenter/images/loading.gif' border='0' align='absmiddle'></td></tr>";
//	$("#TotalList").append(Loading);

	$.ajax({
		type: "POST"
		,url: "tax_list_ajax.php"
		,data: html
		,dataType: "json"
		,success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);

			var oData = String(JSON.stringify(responseData));
			var oPerson = JSON.parse(oData);

			$("#TotalList").empty();

			if(oPerson.list != null) {

				var val      = "";
				var len = oPerson.list.length;

				for(var i=0; i<len; i++){

					var no					= oPerson.list[i].no;
					var orderid             = oPerson.list[i].orderid;
					var prd_name            = oPerson.list[i].prd_name;
					var com_num             = oPerson.list[i].com_num;
					var com_name            = oPerson.list[i].com_name;
					var com_owner           = oPerson.list[i].com_owner;
					var com_post            = oPerson.list[i].com_post;
					var com_address         = oPerson.list[i].com_address;
					var com_kind            = oPerson.list[i].com_kind;
					var com_class           = oPerson.list[i].com_class;
					var com_tel             = oPerson.list[i].com_tel;
					var com_email           = oPerson.list[i].com_email;
					var shop_num            = oPerson.list[i].shop_num;
					var shop_name           = oPerson.list[i].shop_name;
					var shop_owner          = oPerson.list[i].shop_owner;
					var shop_address        = oPerson.list[i].shop_address;
					var shop_kind           = oPerson.list[i].shop_kind;
					var shop_class          = oPerson.list[i].shop_class;
					var shop_tel            = oPerson.list[i].shop_tel;
					var shop_email          = oPerson.list[i].shop_email;
					var prd_info            = oPerson.list[i].prd_info;
					var supp_price          = oPerson.list[i].supp_price;
					var tax_price           = oPerson.list[i].tax_price;
					var tax_pub             = oPerson.list[i].tax_pub;
					var tax_date            = oPerson.list[i].tax_date;
					var tax_type            = oPerson.list[i].tax_type;
					var tax_no              = oPerson.list[i].tax_no;
					var cash_type           = oPerson.list[i].cash_type;
					var cash_type2          = oPerson.list[i].cash_type2;
					var cash_type2_name  = oPerson.list[i].cash_type2_name;
					var cash_info           = oPerson.list[i].cash_info;
					var cash_info2          = oPerson.list[i].cash_info2;
					var cash_info3          = oPerson.list[i].cash_info3;
					var cash_info4          = oPerson.list[i].cash_info4;
					var cash_name           = oPerson.list[i].cash_name;
					var cash_num            = oPerson.list[i].cash_num;
					var wdate               = oPerson.list[i].wdate;
					var stacolor            = oPerson.list[i].stacolor;
					var bill_err_code       = oPerson.list[i].bill_err_code;
					var bill_err_msg        = oPerson.list[i].bill_err_msg;

					var page                = oPerson.list[i].page;
					var lists               = oPerson.list[i].lists;
					var page_count          = oPerson.list[i].page_count;
					var total               = oPerson.list[i].total;
					var alltotal            = oPerson.list[i].alltotal;

					if(cash_type == "P"){
						var baro = "현금영수증";
					} else {
						var baro = "세금계산서";
					}

					$("#total_all_prd_cnt").html(alltotal);
					$("#total_prd_cnt").html(total);

					var tax_pub_m = "";
					if(tax_pub == "Y") {
						<? if($oper_info['tax_api'] == 'Y' && $oper_info['tax_certkey'] && $oper_info['tax_id']) { ?>tax_pub_m = "<img src='/twcenter/manage/image/bn_barobill.gif' title='바로빌 "+cash_type+" 발행'>";<? } else { ?>tax_pub_m = "발급완료";<? } ?>
					} else {
						tax_pub_m = "발급대기";
					}
					
					if(bill_err_code < 0) {
						var errCode = bill_err_code;
					} else if(tax_pub == "Y" && bill_err_code == 1) {
						var errCode = 1;
					} else {
						var errCode = "";
					}


					var tax_api = "<?php echo $oper_info['tax_api'] ?>";
					if(tax_type == "T" && tax_api == "Y") 
						var tax_btn = "<input type='button' value='적용' class='base_btm blue2' onclick='tax_go(\"" + orderid + "\");'>";
					else
						var tax_btn = "<input type='button' value='적용' class='base_btm blue2' onclick='alert(\"세금계산서 모듈연동이 필요합니다.\")'>";

					if(bill_err_msg)
						var t_bill_err_msg = bill_err_msg;
					else
						var t_bill_err_msg = "";

					val += "<tr height='40'>";
					val += "	<td align='center' height='27'><input type='checkbox' name='select_checkbox' id='select_checkbox' value='"+ orderid +"'></td>";
					val += "	<td align='center'><a href='order_info.php?orderid=" + orderid + "&page=" + page + "&<?php echo $param ?>'>"+ orderid +"</a></td>";
					val += "	<td style='padding:0 10px 0 0'>" + prd_name + "</td>";
					val += "	<td align='center'>" + tax_date + "</td>";
					val += "	<td align='center'>" + wdate + "</td>";
					val += "	<td align='center'>" + supp_price + "원</td>";
					val += "	<td align='center'>" + tax_price + "원</td>";
					val += "	<td align='center'>" + tax_pub_m + "</td>";
					val += "	<td align='center' title='" + t_bill_err_msg + "'>" + t_bill_err_msg + "</td>";

//					val += "		<table cellpadding='2'>";
//					val += "			<tr>";
//					val += "				<td bgcolor=" + stacolor + ">";
//					val += "					<select name='tax_pub' style='width:90' class='select'>";
//					val += "						<option value='N' " + tax_pubN + ">발급대기</option>";
//					val += "						<option value='Y' " + tax_pubY + ">발급완료</option>";
//					val += "					</select>";
//					val += "				</td>";
//					val += "				<td>" + tax_btn + "</td>";
//					val += "			</tr>";
//					val += "		</table>";
//					val += "	</td>";
					val += "	<td align='center'>";
					val += "		<img src='../image/btn_view_s.gif' style='cursor:pointer' align='absmiddle' onClick='viewTax(\"" + orderid + "\")'>";
					val += "	</td>";
					val += "</tr>";
					val += "<tr><td colspan='20' class='t_line'></td></tr>";

					val += "<tr height='40' bgcolor='#FFFFFF' id='ccontent_"+ orderid +"' style='display:none'>";
					val += "	<td height='30' colspan='10' style='padding:3px'>";
									if(tax_type == "T") {
					val += "		<table bgcolor='C8C8C8' width='100%' border='0' cellspacing='1' cellpadding='2'>";
					val += "			<tr>";
					val += "				<td width='15%' height='40' bgcolor='#F9F9F9'>&nbsp; 사업자 번호</td><td width='35%' bgcolor='#FFFFFF'>&nbsp; " + com_num + "</td>";
					val += "				<td width='15%' bgcolor='#F9F9F9'>&nbsp; 상 호</td><td bgcolor='#FFFFFF'>&nbsp; " + com_name + "</td>";
					val += "			</tr>";
					val += "			<tr>";
					val += "				<td height='40' bgcolor='#F9F9F9'>&nbsp; 대표자</td><td width='30%' bgcolor='#FFFFFF'>&nbsp; " + com_owner + "</td>";
					val += "				<td bgcolor='#F9F9F9'>&nbsp; 사업장 소재지</td><td bgcolor='#FFFFFF'>&nbsp; " + com_address + "</td>";
					val += "			</tr>";
					val += "			<tr>";
					val += "				<td height='40' bgcolor='#F9F9F9'>&nbsp; 업 태</td><td bgcolor='#FFFFFF'>&nbsp; " + com_kind + "</td>";
					val += "				<td bgcolor='#F9F9F9'>&nbsp; 종 목</td><td bgcolor='#FFFFFF'>&nbsp; " + com_class + "</td>";
					val += "			</tr>";
					val += "			<tr>";
					val += "				<td height='40' bgcolor='#F9F9F9'>&nbsp; 전화번호</td><td bgcolor='#FFFFFF'>&nbsp; " + com_tel + "</td>";
					val += "				<td bgcolor='#F9F9F9'>&nbsp; 이메일</td><td bgcolor='#FFFFFF'>&nbsp; " + com_email + "</td>";
					val += "			</tr>";
					val += "			<tr>";
					val += "				<td height='40' bgcolor='#F9F9F9'>&nbsp; 세금세산서번호</td><td bgcolor='#FFFFFF'>&nbsp; " + tax_no + "</td>";
					val += "				<td bgcolor='#F9F9F9'>&nbsp; </td><td bgcolor='#FFFFFF'>&nbsp;</td>";
					val += "			</tr>";
					val += "		</table>";
									} else if(tax_type == "C") {
										switch(cash_type2) {
											case 'CARDNUM'	: cash_info = cash_info;	break;
											case 'COMNUM'	: cash_info = cash_info2;	break;
											case 'HPHONE'	: cash_info = cash_info3;	break;
										}
					val += "		<table bgcolor='C8C8C8' width='100%' border='0' cellspacing='1' cellpadding='2'>";
					val += "			<tr>";
					val += "				<td width='15%' height='40' bgcolor='#F9F9F9'>&nbsp; 발급사유</td><td width='35%' bgcolor='#FFFFFF'>&nbsp; " + cash_type + "</td>";
					val += "				<td width='15%' bgcolor='#F9F9F9'>&nbsp; 신청정보 </td><td bgcolor='#FFFFFF'>&nbsp; " + cash_type2_name + "</td>";
					val += "			</tr>";
					val += "			<tr>";
					val += "				<td height='40' bgcolor='#F9F9F9'>&nbsp; 신청자명</td><td width='30%' bgcolor='#FFFFFF'>&nbsp; " + cash_name + "</td>";
					val += "				<td bgcolor='#F9F9F9'>&nbsp; 신청정보 내용</td><td bgcolor='#FFFFFF'>&nbsp; " + cash_info + "</td>";
					val += "			</tr>";
					val += "		</table>";
									}
					val += "	</td>";
					val += "</tr>";

					$("#TotalList").html(val);

				}
				pagenavi(page, lists, total, page_count);

			} else {

				var val = "";
					val += '	<div class="none_list">';
					val += '검색하신 계산서가 없습니다.';
					val += '	</div>';

				$("#TotalList").html(val);

			}


		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});

}

var pagenavi = function(page, lists, total, page_count)
{
	var page_navi = "";
	var spage;
	var epage;
	var tmp;
	var ppage;
	var npage;

	if(page_count == 0){ $("#paging").html(''); return; }

	if((page%lists) == 0) tmp = page-1; else tmp = page;

	spage = Math.floor(tmp/lists)*lists+1;
	if(spage <= 1) ppage = 1;
	else ppage = spage - lists;

	epage = spage+lists-1;

	if(epage >= page_count){
		epage = page_count;
		npage = page_count;
	}else{
		npage = epage + 1;
	}

	if(epage > 0)
	{
		page_navi += "<div class='page_num'><ul class='pagination'>";
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList('page', '1')\" style=\"cursor:pointer\">«</a></li>";
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList('page', '" + ppage + "')\" style=\"cursor:pointer\">‹</a></li>";

		for(i = spage; i <= epage; i++){
			if(page == i) {
				page_navi += "<li><a href=\"javascript:;\" class=\"active\" style=\"cursor:pointer\">" + i + "</a></li>";
			} else {
				page_navi += "<li><a href=\"javascript:;\" onClick=\"searchList('page', '" + i + "')\" style=\"cursor:pointer\">" + i + "</a></li>";
			}
		}

		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList('page', '" + npage + "')\" style=\"cursor:pointer\">›</a></li>";
		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList('page', '" + page_count + "')\" style=\"cursor:pointer\">»</a></li>";
		page_navi += "</ul></div>";
	}

	$("#paging").html(page_navi);
}

var searchList = function(stype, value)
{

	$("#TotalList").empty();
	var Loading = "<tr><td height='35' align='center' class='loading'><img src='/twcenter/images/loading.gif' border='0' align='absmiddle'></td></tr>";
	$("#TotalList").append(Loading);

	var frm = document.frm;
	if(stype == "page"){
		frm.page.value = value;
	}

	TotalList();
	return false;
}


// 선택주문 상태변경
function batchStatus(){

	$.checkCnt = $("input[name=select_checkbox]:checked").length;

	if($.checkCnt == 0){
		alert("변경할 데이터를 선택하세요.");
		return false;
	} else {

		var select_checkbox = [];
		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		var selected = select_checkbox.join('|');

		var url = "tax_status.php?selvalue=" + selected;
		window.open(url,"taxStatus","height=200, width=300, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}


// 증빙서류 엑셀다운
function excelDown(){

	var select_checkbox = [];
	$('input[name=select_checkbox]:checked').each(function(){
		select_checkbox.push(this.value);
	});

	var selorder = select_checkbox.join('|');

	if(selorder != ''){
		var url = "tax_excel.php?selorder="+selorder+"&<?=$param?>";
	} else {
		var url = "tax_excel.php?<?=$param?>";
	}
	location.href = url;
	//window.open(url,"excelDown","height=300, width=570, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, top=100, left=100");

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

function chgStatus(val) {
	document.frm.status.value = val;
	document.frm.submit();
}

</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit"><?=$tax_title?>목록</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt"><?=$tax_title?> 목록 입니다.</td>
	</tr>
</table>

<br>
<form name="frm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="status" value="<?php echo $status ?>">
<input type="hidden" name="s_type" value="<?php echo $s_type ?>">
<input type="hidden" name="tax_type" value="<?php echo $tax_type ?>">
<input type="hidden" name="menucode" value="<?php echo $menucode ?>">
<input type="hidden" name="tmp_rows" value="<?php echo $tmp_rows ?>">

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp; 진행상태</td>
		<td width="85%" class="t_value">
			<table>
				<tr><td>
				<!-- <input type="button" onClick="chgStatus('ALL');" value=" 전체목록 " <? if($status == "") echo "class=btn_all"; else echo "class=btn_all"; ?>> -->
				<input type="button" onClick="chgStatus('Y');" value="발급완료" <? if($status == "Y") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
				<input type="button" onClick="chgStatus('N');" value="발급대기" <? if($status == "N") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="t_name">&nbsp; 기 간</td>
		<td class="t_value">
		<?
		$yes_day      = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*1));
		$to_day       = date('Y-m-d');
		$week_day     = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*7));
		$month_day    = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*30));
		$twomonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));
		$sixmonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*182));
		$prevyear_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*365));

		if(!empty($srh_prev)) $week_day2 = $srh_prev; else $week_day2 = $twomonth_day;
		if(!empty($srh_next)) $to_day2   = $srh_next; else $to_day2   = $to_day;

		?>
		<span class="calendar">
			<input type="text" name="srh_prev" id="srh_prev" class="datepicker-here input2" size="15" value="<?=$week_day2?>"> ~ 
			<input type="text" name="srh_next" id="srh_next" class="datepicker-here input2" size="15" value="<?=$to_day2?>">

			<input type="submit" onClick="setPeriod('<?=$to_day?>','<?=$to_day?>','srh_prev','srh_next',1)" value="오늘" <? if($s_type == "1") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$yes_day?>','<?=$to_day?>','srh_prev','srh_next',2)" value="어제" <? if($s_type == "2") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$week_day?>','<?=$to_day?>','srh_prev','srh_next',3)" value="1주일" <? if($s_type == "3") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$month_day?>','<?=$to_day?>','srh_prev','srh_next',4)" value="1개월" <? if($s_type == "4") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$sixmonth_day?>','<?=$to_day?>','srh_prev','srh_next',5)" value="6개월" <? if($s_type == "5") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$prevyear_day?>','<?=$to_day?>','srh_prev','srh_next',6)" value="1년" <? if($s_type == "6") echo "class=sm_per"; else echo "class=m_per"; ?>>
		</span>
		</td>
	</tr>
	<tr>
		<td class="t_name">&nbsp; 조건검색</td>
		<td class="t_value">
			<select name="searchopt" class="select2">
			<option value="orderid" <? if($searchopt == "orderid") echo "selected"; ?>>주문번호
			<option value="com_name" <? if($searchopt == "com_name") echo "selected"; ?>>상호
			<option value="com_owner" <? if($searchopt == "com_owner") echo "selected"; ?>>대표자
			<option value="com_address" <? if($searchopt == "com_address") echo "selected"; ?>>사업장소재지
			<option value="com_num" <? if($searchopt == "com_num") echo "selected"; ?>>사업자등록번호
			<option value="com_kind" <? if($searchopt == "com_kind") echo "selected"; ?>>업태
			<option value="com_class" <? if($searchopt == "com_class") echo "selected"; ?>>종목
			<option value="com_tel" <? if($searchopt == "com_tel") echo "selected"; ?>>전화번호
			<option value="com_email" <? if($searchopt == "com_email") echo "selected"; ?>>이메일
			</select>
			<input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
			<!-- <input type="image" src="../image/btn_search.gif" align="absmiddle"> -->
		</td>
	</tr>
</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center">
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?tax_type=<?=$tax_type?>&<?=$menucodeParam?>'">
		</td>
	</tr>
</table>

</form>

<br>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 <?=$tax_title?> : <strong id="total_all_prd_cnt"><?=$all_total?></strong> , 검색결과 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
		<td align="right">
		<form>
			<select name="tmp_rows" onchange="location.href='?<?=$param?>&tax_type=<?=$tax_type?>&tmp_rows='+this.value" class="select">
				<option value="10"  <? if($tmp_rows==10)  echo "selected";?>>10개씩 출력</option>
				<option value="20"  <? if($tmp_rows=="" || $tmp_rows==20)  echo "selected";?>>20개씩 출력</option>
				<option value="30"  <? if($tmp_rows==30)  echo "selected";?>>30개씩 출력</option>
				<option value="50"  <? if($tmp_rows==50)  echo "selected";?>>50개씩 출력</option>
				<option value="70"  <? if($tmp_rows==70)  echo "selected";?>>70개씩 출력</option>
				<option value="100" <? if($tmp_rows==100) echo "selected";?>>100개씩 출력</option>
			</select> 
			<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
		</form>
		</td>
	</tr>
	<tr><td height=5></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="3%"><input type="checkbox" id="checkAll" onclick="selectAll()"></th>
		<th width="10%">주문번호</th>
		<th>품 명</th>
		<th width="8%">발급일</th>
		<th width="8%">발급완료일</th>
		<th width="8%">공급가액</th>
		<th width="8%">세액</th>
		<th width="8%">처리상태</th>
		<th width="20%">에러내용</th>
		<th width="6%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<thead></thead>
	<tbody id="TotalList">

	</tbody>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="33%">
			<input type="button" value="선택삭제" class="btnListchk gray2" onclick="taxDelete()">
			<input type="button" value="상태일괄변경" class="btnListchk" onclick="batchStatus()">
			<? 
				/* 바로빌 세금계산서 버튼 추가 - 2020-03-18 update 김나연 */
				if($oper_info['tax_api'] == 'Y' && $oper_info['tax_certkey'] && $oper_info['tax_id']) { 
			?>
			<input type="button" value="바로빌 <?php echo $tax_title ?>" class="btnListchk" onclick="barobill()">
			<? 
				} 
			?>
		</td>
		<td width="33%" id="paging"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
		<td width="33%"></td>
	</tr>
</table>

<?php include "../foot.php"; ?>