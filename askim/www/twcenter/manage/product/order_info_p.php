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

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

// 주문정보 가져오기
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql);
$order_info = sql_fetch_obj($result);

if(!empty($order_info->deliver_date)){
	$order_info->deliver_date = $order_info->deliver_date;
} else {
	$order_info->deliver_date = "";
}

$escrow_check = ($order_info->escrow_check == 'N') ? "<font color=\"FA2828\">사용안함</font>" : "<font color=\"1D7CD4\">사용</font>";

// 할부개월
if($order_info->cardquota != "") {
	$order_info->cardquota = (int)$order_info->cardquota;
	if($order_info->cardquota > 0)
		$order_info->cardquota = "할부개월 : ".$order_info->cardquota."개월";
	else
		$order_info->cardquota = "일시불";
}

// 배송비
deliver_price($order_info->prd_price, $oper_info);

?>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="javascript">
<!--
// 고객 메일발송
function sendEmail(name,email){
	var url = "../member/mail_popup.php?seluser=" + name + ":" + email;
	window.open(url,"sendEmail","height=600, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 고객 sms발송
//function sendSms(name,hphone){
//	var url = "../member/sms_popup.php?seluser=" + hphone;
//	window.open(url,"sendSms","height=450, width=430, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
//}

function sendSms(sms_u,hphone,status){

	if(hphone == ''){
		alert('휴대폰번호가 없습니다.');
		return false;
	}

	if(status == 'OC' || status == 'RC') {
		alert('주문취소나 취소완료일경우 SMS발송을 할수 없습니다.');
		return false;
	}

	var url = "../member/sms_popup.php?sms_u=" + sms_u + "&seluser=" + hphone;
	window.open(url,"sendSmsT","height=640, width=350, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 우편번호 찾기
function searchZip(kind) {

	if(kind == undefined) kind = "";
	new daum.Postcode({
		oncomplete: function(data) {

			var frm = document.frm;

			var extraAddr = '';
			var fullAddr = '';

			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			if(data.userSelectedType === 'R'){

				if(data.bname !== ''){
					extraAddr += data.bname;
				}

				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');

			}

			eval('frm.'+kind+'post').value = data.zonecode;
			eval('frm.'+kind+'address').value = fullAddr;

			if(eval('frm.'+kind+'address2') != null)
				eval('frm.'+kind+'address2').focus();
		}
	}).open();

}

function basketCancel( idx, prdname ) {

<? if(!strcmp($order_info->status, "OR") || !strcmp($order_info->status, "OY") || !strcmp($order_info->status, "DR")) { ?>

	if(cancel.style.display == "" && document.cFrm.idx.value == idx) cancel.style.display = "none";
	else cancel.style.display = "";

	document.cFrm.idx.value = idx;
	document.getElementById("cPrd").innerHTML = prdname;

<? } else { ?>

	alert("배송처리/주문취소된 주문의 상품은 취소할 수 없습니다.");

<? } ?>

}

function delivery_sel(idx,orderid) {
	var del_com = $("#del_com_"+idx+" option:selected").val();
	document.location = "order_save_p.php?mode=deliverySel&idx="+idx+"&orderid="+orderid+"&del_com="+del_com+"&<?=$menucodeParam?>";
}

function resetCancel() {
	document.cFrm.idx.value = "";
	document.getElementById("cPrd").innerHTML = "";
	cancel.style.display = "none";
}

function cancelCheck( frm ) {

	if(frm.idx.value == "") {
		alert("취소상품이 선택되지 않았습니다.");
		return false;
	}

	if(frm.reason.value == "") {
		alert("취소사유를 선택해주세요.");
		frm.reason.focus();
		return false;
	}

	if(frm.bank != undefined) {

		if(frm.repay[0].checked != true && frm.repay[1].checked != true) {
			alert("환불방법을 선택하세요.")
			return false;
		}
		if(frm.repay[1].checked == true) {
			if(frm.bank.value == "") {
				alert("은행을 선택하세요.");
				frm.bank.focus();
				return false;
			}

			if(frm.account.value == "") {
				alert("입금계좌를 입력하세요.");
				frm.account.focus();
				return false;
			}

			if(frm.acc_name.value == "") {
				alert("예금주를 입력하세요.");
				frm.acc_name.focus();
				return false;
			}
		}

	}

}

function viewCancel(idx){

	var ccontent = "#ccontent_"+idx;
		if($(ccontent).css("display") == "none"){
		$(ccontent).show();
	} else {
		$(ccontent).hide();
	}

}

function orderPrint() {
	<?
	if($oper_info['deliveryType'] == "P"){
	?>
	var url = "order_print_p.php?selorder=<?=$orderid?>";
	<? } else { ?>
	var url = "order_print.php?selorder=<?=$orderid?>";
	<? } ?>
	window.open(url,"OderPrint","height=650, width=750, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 카카오페이 결제취소
function Kakaopay_Cancel(tid) {
	var url = "/twcenter/product/lgcns/kakaopayCancelRequest.php?tid="+tid;
	window.open(url,"kakaocancel","height=250, width=750, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 세금계산서발행
function qclick(idnum) {

	tax00.style.display='none';
	tax01.style.display='none';
	tax02.style.display='none';

	if(idnum != ""){
		tax=eval("tax"+idnum+".style");
		tax.display='';
		tax00.style.display='';
	}
}

// 세금계산서 출력
function printTax(orderid) {

	var url = "/twcenter/product/print_tax_sup.php?orderid=" + orderid;
	window.open(url, "taxPub", "height=750, width=670, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");

}

function taxType(tname) {

	if(tname == 'CARDNUM') {
		$("#CARDNUM").show();
		$("#COMNUM").hide();
		$("#HPHONE").hide();
		$("#RESNO").hide();
		$("#type_name").text('카드번호');
		$("#taxcode").val(tname);
	} else if(tname == 'COMNUM') {
		$("#CARDNUM").hide();
		$("#COMNUM").show();
		$("#HPHONE").hide();
		$("#RESNO").hide();
		$("#type_name2").text('사업자번호');
		$("input[name=taxcode]").val(tname);
	} else if(tname == 'HPHONE') {
		$("#CARDNUM").hide();
		$("#COMNUM").hide();
		$("#HPHONE").show();
		$("#RESNO").hide();
		$("#type_name3").text('휴대전화번호');
		$("input[name=taxcode]").val(tname);
	} else if(tname == 'RESNO') {
		$("#CARDNUM").hide();
		$("#COMNUM").hide();
		$("#HPHONE").hide();
		$("#RESNO").show();
		$("#type_name4").text('주민등록번호');
		$("input[name=taxcode]").val(tname);
	}

}

function delivery(orderid,n){
	<?
	if($order_info->status == "OC" || $order_info->status == "RD" || $order_info->status == "RC"){
	?>
	alert("주문취소 및 취소요청단계에서는 배송업체설정이 불가능합니다.");
	return false;
	<?
	}
	?>
	var url = "prd_delivery_add.php?orderid="+orderid+"&basIdx=" + n;
	window.open(url,"delivery","height=400, width=650, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function basApply(bas_idx, orderid, status) {

	var bas_chg_status, params;
	
	bas_chg_status = $("#bas_chg_status_" + bas_idx).val();
	if(bas_chg_status == '') {
		alert('처리상태를 선택해주세요.');
		return false;
	}

	if(status == bas_chg_status) {
		alert('다른 처리상태값을 선택해주세요.');
		return false;
	}

	params = "";
	params += "basketidx=" + bas_idx;
	params += "&orderid=" + orderid;
	params += "&mode=basApply";
	params += "&bas_chg_status=" + bas_chg_status;
	params += "&status=" + status;

	$.ajax( {
		url: 'order_save_p.php'
		, type: 'POST'
		, data: params
		, cache: false
		, async: false
	}).done(function(data) {
		if(data == "ok") {
			alert("처리상태가 변경되었습니다.");
			document.location.reload(true);
		} else if(data == "no_delnum") {
			alert("운송장번호가 비어있습니다.\n배송정보입력에서 운송장번호를 입력해주세요.");
			document.location.reload(true);
		} else if(data == "no_delcom") {
			alert("배송업체를 선택해주세요");
			document.location.reload(true);
		}
	}).fail(function(request, status, error){
		console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
	});

}
-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">주문정보</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">주문상세 정보입니다.</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="8%">상품코드</th>
		<th width="5%">이미지</th>
		<th>상품명</th>
		<th width="8%">상품가격</th>
		<?
		if($oper_info['deliveryType'] == "P"){
		?>
		<th width="15%">배송업체</th>
		<? } ?>
		<th width="25%">옵 션</th>
		<th width="5%">수 량</th>
		<?
		if($oper_info['reserve_use'] == "Y"){
		?>
		<th class="table_tit" width="12%">적립금</th>
		<?
		}
		?>
		<th width="8%">합 계</th>
		<th width="7%">취 소</th>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<?
	$orderid = $order_info->orderid;
	$sql = "select * from wiz_basket where orderid = '$orderid'";
	$result = query($sql);
	$total = sql_fetch_rows($sql);

	$prd_info = "";
	$no = 0;
	while($row = sql_fetch_obj($result)){
		if($row->prdimg == "") $row->prdimg = "/twcenter/images/noimage.gif";
		else                   $row->prdimg = "/twcenter/data/prdimg/$row->prdimg";

		$prd_d_price    = $row->prdprice*$row->amount;

		if(
			strpos($row->optcode,"&&")   !== false || strpos($row->optcode2,"&&")  !== false || strpos($row->optcode3,"&&") !== false || 
			strpos($row->optcode4,"&&")  !== false || strpos($row->optcode5,"&&")  !== false || strpos($row->optcode6,"&&") !== false || 
			strpos($row->optcode7,"&&")  !== false || strpos($row->optcode8,"&&")  !== false || strpos($row->optcode9,"&&") !== false || 
			strpos($row->optcode10,"&&") !== false || strpos($row->optcode11,"&&") !== false
			)
		{
			$prd_price       += $row->prdprice * $row->amount;
			$reserve_price   += $row->prdreserve * $row->amount;
		} else {
			$prd_price       += $row->prdprice * $row->amount;
			$reserve_price   += $row->prdreserve * $row->amount;
		}

		$optcode = $opt = $opt3 = $opt5 = $opt6 = $opt7 = $opt8 = $opt9 = $opt10 = $opt11 = "";

		if(strpos($row->optcode5,"&&") !== false){
			$opt5_val = explode("&&",$row->optcode5);
			for($i=0; $i<count($opt5_val)-1; $i++){
				$exp = $opt5_val[$i];
				list($optcode5_v,$t_optcode5_v2,$t_optcode5_v3,$t_optcode5_v4) = explode("^",$exp);
				$optcode5_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode5_v2)."원 / ".$t_optcode5_v4."개)</span>";
				$opt5 .= "- ".$row->opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";
			}
		} else {
			list($optcode5_v,$t_optcode5_v2) = explode("/",$row->optcode5);
			$optcode5_v2 = "";
			$opt5 = "- ".$row->opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";
		}

		if($row->opttitle5 != '' && $row->optcode5 != '')  $optcode .= $opt5;


		if(strpos($row->optcode6,"&&") !== false){
			$opt6_val = explode("&&",$row->optcode6);
			for($i=0; $i<count($opt6_val)-1; $i++){
				$exp = $opt6_val[$i];
				list($optcode6_v,$t_optcode6_v2,$t_optcode6_v3,$t_optcode6_v4) = explode("^",$exp);
				$optcode6_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode6_v2)."원 / ".$t_optcode6_v4."개)</span>";
				$opt6 .= "- ".$row->opttitle6." : ".$optcode6_v." ".$optcode6_v2."<br>";
			}
		} else {
			list($optcode6_v,$t_optcode6_v2) = explode("/",$row->optcode6);
			$optcode6_v2 = "";
			$opt6 = "- ".$row->opttitle6." : ".$optcode6_v." ".$optcode6_v2."<br>";
		}

		if($row->opttitle6 != '' && $row->optcode6 != '')  $optcode .= $opt6;


		if(strpos($row->optcode7,"&&") !== false){
			$opt7_val = explode("&&",$row->optcode7);
			for($i=0; $i<count($opt7_val)-1; $i++){
				$exp = $opt7_val[$i];
				list($optcode7_v,$t_optcode7_v2,$t_optcode7_v3,$t_optcode7_v4) = explode("^",$exp);
				$optcode7_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode7_v2)."원 / ".$t_optcode7_v4."개)</span>";
				$opt7 .= "- ".$row->opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
			}
		} else {
			list($optcode7_v,$t_optcode7_v2) = explode("/",$row->optcode7);
			$optcode7_v2 = "";
			$opt7 = "- ".$row->opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
		}

		if($row->opttitle7 != '' && $row->optcode7 != '')  $optcode .= $opt7;



		if(strpos($row->optcode3,"&&") !== false){
			$opt3_val = explode("&&",$row->optcode3);
			for($i=0; $i<count($opt3_val)-1; $i++){
				$exp = $opt3_val[$i];
				list($optcode3_v,$t_optcode3_v2,$t_optcode3_v3,$t_optcode3_v4) = explode("^",$exp);
				$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원 / ".$t_optcode3_v4."개)</span>";
				$opt3 .= "- ".$row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br> ";
			}
		} else {
			list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3);
			$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원)</span>";
			$opt3 = "- ".$row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br>";
		}

		if(strpos($row->optcode4,"&&") !== false){
			$opt4_val = explode("&&",$row->optcode4);
			for($i=0; $i<count($opt4_val)-1; $i++){
				$exp = $opt4_val[$i];
				list($optcode4_v,$t_optcode4_v2,$t_optcode4_v3,$t_optcode4_v4) = explode("^",$exp);
				$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원 / ".$t_optcode4_v4."개)</span>";
				$opt4 .= "- ".$row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br> ";
			}
		} else {
			list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4);
			$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원)</span>";
			$opt4 = "- ".$row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br>";
		}

		if(strpos($row->optcode8,"&&") !== false){
			$opt8_val = explode("&&",$row->optcode8);
			for($i=0; $i<count($opt8_val)-1; $i++){
				$exp = $opt8_val[$i];
				list($optcode8_v,$t_optcode8_v2,$t_optcode8_v3,$t_optcode8_v4) = explode("^",$exp);
				$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원 / ".$t_optcode8_v4."개)</span>";
				$opt8 .= "- ".$row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br> ";
			}
		} else {
			list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8);
			$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원)</span>";
			$opt8 = "- ".$row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br>";
		}

		if(strpos($row->optcode9,"&&") !== false){
			$opt9_val = explode("&&",$row->optcode9);
			for($i=0; $i<count($opt9_val)-1; $i++){
				$exp = $opt9_val[$i];
				list($optcode9_v,$t_optcode9_v2,$t_optcode9_v3,$t_optcode9_v4) = explode("^",$exp);
				$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원 / ".$t_optcode9_v4."개)</span>";
				$opt9 .= "- ".$row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br> ";
			}
		} else {
			list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9);
			$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원)</span>";
			$opt9 = "- ".$row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br>";
		}

		if(strpos($row->optcode10,"&&") !== false){
			$opt10_val = explode("&&",$row->optcode10);
			for($i=0; $i<count($opt10_val)-1; $i++){
				$exp = $opt10_val[$i];
				list($optcode10_v,$t_optcode10_v2,$t_optcode10_v3,$t_optcode10_v4) = explode("^",$exp);
				$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원 / ".$t_optcode10_v4."개)</span>";
				$opt10 .= "- ".$row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br> ";
			}
		} else {
			list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10);
			$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원)</span>";
			$opt10 = "- ".$row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br>";
		}

		if(strpos($row->optcode11,"&&") !== false){
			$opt11_val = explode("&&",$row->optcode11);
			for($i=0; $i<count($opt11_val)-1; $i++){
				$exp = $opt11_val[$i];
				list($optcode11_v,$t_optcode11_v2,$t_optcode11_v3,$t_optcode11_v4) = explode("^",$exp);
				$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원 / ".$t_optcode11_v4."개)</span>";
				$opt11 .= "- ".$row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br> ";
			}
		} else {
			list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11);
			$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원)</span>";
			$opt11 = "- ".$row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br>";
		}


		if($row->opttitle3 != ''  && $row->optcode3 != '')  $optcode .= $opt3;
		if($row->opttitle4 != ''  && $row->optcode4 != '')  $optcode .= $opt4;
		if($row->opttitle8 != ''  && $row->optcode8 != '')  $optcode .= $opt8;
		if($row->opttitle9 != ''  && $row->optcode9 != '')  $optcode .= $opt9;
		if($row->opttitle10 != '' && $row->optcode10 != '') $optcode .= $opt10;
		if($row->opttitle11 != '' && $row->optcode11 != '') $optcode .= $opt11;

		if(strpos($row->optcode,"&&") !== false){
			$opt_val = explode("&&",$row->optcode);
			for($i=0; $i<count($opt_val)-1; $i++){
				$exp = $opt_val[$i];
				list($optcode_v,$t_optcode_v2,$t_optcode_v3,$t_optcode_v4) = explode("^",$exp);
				$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원 / ".$t_optcode_v4."개)</span>";

				if($row->opttitle != '') $topttitle = $row->opttitle;
				if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
				if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

				$opt .= "- ".$topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
			}
		} else {
			list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
			if($t_optcode_v2 != 0){
				$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
			} else {
				$optcode_v2 = "";
			}

			if($row->opttitle != '') $topttitle = $row->opttitle;
			if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
			if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

			$opt .= "- ".$topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
		}


		if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= $opt;

		$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
		$optcode = "<span class='pay_add_tit'>".$optcode."</span>";



		$del_type = "";

		if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
			if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)." : ".number_format($row->del_price)."원)";
			else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
		}

		$prd_info .= $row->prdname."^".$row->prdprice."^".$row->amount."^^";

		list($del_com,$del_idx) = explode("|",$row->del_com);
		if($del_com) {
			$t_del_com = $del_com;
		} else {
			$t_del_com = "<a href='javascript:;' onclick='delivery(\"".$orderid."\",".$row->idx.")'>[배송업체 입력]</a>";
		}

		$c_sql = "
			select wc.purl
			  from wiz_product as wp 
			  left join wiz_cprelation as wcp 
			    on wp.prdcode = wcp.prdcode
			  left join wiz_category as wc 
			    on wcp.catcode = wc.catcode
			 where wp.prdcode = '$row->prdcode'
		";
		$c_row = sql_fetch($c_sql);

	?>
	<tr bgcolor="#FFFFFF" height="55">
		<td align="center"><?=$row->prdcode?></td>
		<td align="center" style="padding:5px 0">
			<a href='/<?=$c_row['purl']?>?ptype=view&prdcode=<?=$row->prdcode?>' target='_blank'><img src='<?=$row->prdimg?>' width='50' height='50' border='0'></a>
		</td>
		<td><a href='/<?=$c_row['purl']?>?ptype=view&prdcode=<?=$row->prdcode?>' target='_blank'><?=$row->prdname?></a><?=$del_type?></td>
		<td align="center"><?=number_format($row->prdprice)?>원</td>

		<td align="center">
			<!-- <? if(empty($row->del_com)){ ?>
			<input type="button" value="배송정보 입력" class="btnInputNum" onclick="delivery('<?=$orderid?>',<?=$row->idx?>)">
			<? } else { ?>
			<span align="left"><img src="../image/icon_delivery.gif" align="absmiddle"> <?=$del_com?><br><img src="../image/icon_transport.gif" align="absmiddle"> <?=$row->del_num?><br><img src="../image/icon_sandDay.gif" align="absmiddle"> <?=$row->deliver_date?></span>
			<? } ?>
			<input type="hidden" name="bas_idx" value="<?=$row->idx?>" id="bas_<?=$no?>">
			<input type="hidden" name="bas_orderid" value="<?=$row->orderid?>" id="orderid_<?=$no?>"> -->
			<dl class="delivery">
				<dt>
					<? if(!strcmp($row->status, "OC") || !strcmp($row->status, "RC")) {	//주문취소,취소완료인 경우 상태변경 불가능 ?>
					<b><font color="#ED1C24"><center><?=order_status($row->status);?></center></font></b>
					<? } else { ?>
					<select name="bas_chg_status" id="bas_chg_status_<?php echo $row->basketidx ?>" style="width:100px; vertical-align:top;" class="select2" >
						<option value="">--------------------</option>
						<?
						if($row->status == "" || $row->status == "OR") {
						?>
							<option value="OR" <? if($row->status == "OR" || $row->status == "") echo "selected"; ?>>주문접수</option>
							<option value="OY" <? if($row->status == "OY") echo "selected"; ?>>결제완료</option>
							<option value="OC" <? if($row->status == "OC") echo "selected"; ?>>주문취소</option>
						<?
						} else {
						?>
							<option value="OY" <? if($row->status == "OY") echo "selected"; ?>>결제완료</option>
							<option value="DR" <? if($row->status == "DR") echo "selected"; ?>>배송준비중</option>
							<option value="DI" <? if($row->status == "DI") echo "selected"; ?>>배송처리</option>
							<option value="DC" <? if($row->status == "DC") echo "selected"; ?>>배송완료</option>
							<option value="OC" <? if($row->status == "OC") echo "selected"; ?>>주문취소</option>
							<option value="">--------------------</option>
							<option value="RD" <? if($row->status == "RD") echo "selected"; ?>>취소/환불요청</option>
							<option value="RC" <? if($row->status == "RC") echo "selected"; ?>>취소/환불완료</option>
							<option value="CD" <? if($row->status == "CD") echo "selected"; ?>>교환요청</option>
							<option value="CC" <? if($row->status == "CC") echo "selected"; ?>>교환완료</option>
						<? } ?>
					</select>
					<input type="button" value="적용" class="deliveryBtn blue2" onclick="basApply(<?php echo $row->basketidx ?>, '<?php echo $row->orderid ?>', '<?php echo $row->status ?>')" style="cursor:pointer">
					<? } ?>
					<!-- // -->
					<!-- ## 181205 취소접수(환불요청)이 들어왔을 때 취소거부(환불거부) 버튼 노출 ##-->
					<?
					if($row->status == "OC" || $row->status == "RD"){
					?>
					<input type="button" value="취소(환불)거부" class="order_no" onclick="orderRtn('<?php echo $orderid ?>',<?php echo $row->basketidx ?>)">
					<? } ?>
				</dt>
				<!-- // -->
				<!-- # 190103 배송상태 #-->
				<dd>
					<? if(empty($row->del_com) && empty($row->del_num)){ ?>
					<input type="button" value="배송정보 입력" class="btnInputNum" onclick="delivery('<?=$orderid?>',<?=$row->idx?>)">
					<? } else { ?>
					<div class="delInfo">
						<img src="../image/icon_delivery.gif" align="absmiddle"> <?=$t_del_com?><br>
						<img src="../image/icon_transport.gif" align="absmiddle"> <?=$row->del_num?><br>
						<img src="../image/icon_sandDay.gif" align="absmiddle"> <?=$row->deliver_date?>
					</div>
					<? } ?>
					<input type="hidden" name="bas_idx" value="<?=$row->idx?>" id="bas_<?=$no?>">
					<input type="hidden" name="bas_orderid" value="<?=$row->orderid?>" id="orderid_<?=$no?>">
				</dd>
			</dl>
		</td>

		<td><?=$optcode?></td>
		<td align="center"><?=$row->amount?></td>
		<?
		if($oper_info['reserve_use'] == "Y"){
		?>
		<td align="center"><?=number_format($row->prdreserve*$row->amount)?>원</td>
		<?
		}
		?>
		<td align="center"><?=number_format($prd_d_price)?>원</td>
		<td align="center">
		<?
		if(!strcmp($row->status, "CA") || !strcmp($row->status, "CI") || !strcmp($row->status, "CC")) {
			if(!strcmp($row->status, "CA")) $basket_status = "취소신청<br><input type='button' value='취소내역보기' class='list_s_btn' onClick=\"viewCancel('$row->idx')\">";
			else if(!strcmp($row->status, "CI")) $basket_status = "처리중<br><input type='button' value='취소내역보기' class='list_s_btn' onClick=\"viewCancel('$row->idx')\">";
			else if(!strcmp($row->status, "CC")) $basket_status = "취소완료<br><input type='button' value='취소내역보기' class='list_s_btn' onClick=\"viewCancel('$row->idx')\">";
		?>
		<?=$basket_status?>
		<?
		} else {
		?>
			<input type="button" value="취소" class="cancel_s_btn" onClick="basketCancel('<?=$row->idx?>', '<?=$row->prdname?>')">
		<?
		}
		?>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<tr bgcolor="#FFFFFF" id="ccontent_<?=$row->idx?>" style="display:none">
		<td colspan="10" style="padding:3px">
			<table border="0"width="100%" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" align="align" class="t_name">취소사유</td>
					<td class="t_value" colspan="5"><?=$row->reason?></td>
				</tr>
				<tr>
					<td align="align" class="t_name">메모</td>
					<td class="t_value" colspan="5"><?=$row->memo?></td>
				</tr>
				<?
				if(!empty($row->repay)) {
					if(!strcmp($row->repay, "R")) $repay = "적립금";
					if(!strcmp($row->repay, "C")) $repay = "계좌이체";

				?>
				<tr>
					<td width="100" align="align" class="t_name">환불방법</td>
					<td class="t_value" colspan="5"><?=$repay?></td>
				</tr>
				<?
				}
				if(!empty($row->bank)) {
				?>
				<tr>
					<td width="100" align="align" class="t_name">은행명</td>
					<td class="t_value"><?=$row->bank?></td>
					<td width="100" align="align" class="t_name">계좌번호</td>
					<td class="t_value"><?=$row->account?></td>
					<td width="100" align="align" class="t_name">예금주</td>
					<td class="t_value"><?=$row->acc_name?></td>
				</tr>
				<?
				}
				?>
			</table>
		</td>
	</tr>
	<?
	$no++;
	}
	// 회원할인
	if($order_info->discount_price > 0){
		//$discount_msg = " <img src='/twcenter/product/image/icon_plus.gif' align='absmiddle'> 회원할인( <span class='price_size3'>".number_format($order_info->discount_price)."</span> 원 )";
		$discount_msg = " <span class=\"price_color2\">－ 회원할인 금액 (".number_format($order_info->discount_price)." 원)</span>";
		$_drow = 1;
	}
	// 적립금 사용
	if($order_info->reserve_use > 0){
		//$reserve_msg = " <img src='/twcenter/product/image/icon_plus.gif' align='absmiddle'> 적립금 사용( <span class='price_size3'>".number_format($order_info->reserve_use)."</span> 원)";
		$reserve_msg = " <span class=\"price_color2\">－ 적립금 사용 (".number_format($order_info->reserve_use)." 원)</span>";
		$_rrow = 1;
	}

	// 쿠폰사용
	if($order_info->coupon_use > 0){
		//$coupon_msg = " <img src='/twcenter/product/image/icon_plus.gif' align='absmiddle'> 쿠폰 사용( <span class='price_size3'>".number_format($order_info->coupon_use)."</span> 원)";
		$coupon_msg = " <span class=\"price_color2\">－ 쿠폰사용 (".number_format($order_info->coupon_use)." 원)</span>";
		$_crow = 1;
	}

	// 배송비
	$deliver_price = " <span class=\"price_color1\">＋ 배송비 (".number_format($order_info->deliver_price)." 원)</span>";
	$deliver_price_view = deliver_price2($order_info->deliver_method, $order_info->deliver_price);	
	if($deliver_price_view == "착불") $deliver_price_view = " <span class=\"price_color1\">＋ 배송비 (0 원)</span>";
	else                             $deliver_price_view = $deliver_price;

	//$rows_cnt = (int)3 + $_drow + $_rrow + $_crow;
	?>
</table>

<form name="cFrm" action="order_save_p.php" method="post" onSubmit="return cancelCheck(this)">
<input type="hidden" name="orderid"       value="<?=$orderid?>">
<input type="hidden" name="orderstatus"   value="<?=$order_info->status?>">
<input type="hidden" name="mode"          value="cancel">
<input type="hidden" name="idx"           value="">

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="38" id="cancel" style="display:none">
	<tr><td><br></td></tr>
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 상품취소</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">취소상품</td>
					<td class="t_value" id="cPrd" colspan="5"></td>
				</tr>
				<tr>
					<td class="t_name">취소사유</td>
					<td class="t_value" colspan="5">
						<select name="reason" class="select">
							<option value="">:: 취소사유를 선택하세요 ::</option>
							<option value="고객변심">고객변심</option>
							<option value="품절">품절</option>
							<option value="배송지연">배송지연</option>
							<option value="이중주문">이중주문</option>
							<option value="시스템오류">시스템오류</option>
							<option value="누락배송">누락배송</option>
							<option value="택배분실">택배분실</option>
							<option value="상품불량">상품불량</option>
							<option value="기타">기타</option>
						</select>

					</td>
				</tr>
				<tr>
					<td class="t_name">메모</td>
					<td class="t_value" style="padding: 4px 0 4px 10px" colspan='3'>
						<textarea name="memo" rows="6" cols="60" class="textarea" style="width:99%"></textarea>
					</td>
				</tr>
				<?
				if(strcmp($order_info->status, "OR") && strcmp($order_info->pay_method, "PC")) {
				?>
				<tr>
					<td class="t_name">환불방법</td>
					<td class="t_value" colspan="5">
						<input type="radio" name="repay" value="R"> 적립금
						<input type="radio" name="repay" value="C"> 계좌이체
					</td>
				</tr>
				<tr>
					<td class="t_name">환불계좌</td>
					<td class="t_value">
						<select name="bank" class="select">
							<option value="">:: 선택하세요 :: </option>
							<option value="경남은행">경남은행 </option>
							<option value="광주은행">광주은행 </option>
							<option value="국민은행">국민은행 </option>
							<option value="기업은행">기업은행 </option>
							<option value="농협">농협 </option>
							<option value="대구은행">대구은행 </option>
							<option value="도이치뱅크">도이치뱅크 </option>
							<option value="부산은행">부산은행 </option>
							<option value="산업은행">산업은행 </option>
							<option value="상호저축은행">상호저축은행 </option>
							<option value="새마을금고">새마을금고 </option>
							<option value="수협중앙회">수협중앙회 </option>
							<option value="신용협동조합">신용협동조합 </option>
							<option value="신한은행">신한은행 </option>
							<option value="외환은행">외환은행 </option>
							<option value="우리은행">우리은행 </option>
							<option value="우체국">우체국 </option>
							<option value="전북은행">전북은행 </option>
							<option value="제주은행">제주은행 </option>
							<option value="하나은행">하나은행 </option>
							<option value="한국시티은행">한국시티은행 </option>
							<option value="HSBC">HSBC </option>
							<option value="SC제일은행">SC제일은행 </option>
						</select>
					</td>
					<td class="t_name">계좌번호</td>
					<td class="t_value">
						<input type="text" name="account" class="input">
					</td>
					<td class="t_name">예금주</td>
					<td class="t_value">
						<input type="text" name="acc_name" class="input">
					</td>
				</tr>
				<?
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" height="35">
			<input type="submit" value="확인" class="confirm_s_btn"> &nbsp;
			<input type="button" value="취소" class="cancel_s_btn" onClick="resetCancel()">
		</td>
	</tr>
</table>
</form>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="38">
	<tr><td height="10"></td></tr>
	<tr><td colspan="9" height="1" bgcolor="#e1e1e1"></td></tr>
	<tr class="t_name">
		<td rowspan="2" style="padding:10px"><b>배송비 : <?=deliver_name($order_info->deliver_method)?></b></td>
		<td align="right" style="padding:10px">
			<span class="price_color">상품합계 <?=number_format($order_info->prd_price)?></span> 원</span>
			<?=$discount_msg?>
			<?=$deliver_price_view?>
			<?=$coupon_msg?>
			<?=$reserve_msg?>
		</td>
	</tr>
	<tr class="t_name_ord">
		<td width="80%" class="Right">
			<span class="price_size5">총 결제금액</span>
			<span class="price_size4"><?=number_format($order_info->total_price)?></span> <b><font color="#282828">원</font></b>
		</td>
	</tr>
	<tr><td colspan="9" height="1" bgcolor="#e1e1e1"></td></tr>
	<tr><td height="10"></td></tr>
</table>
<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" height="38">
	<tr><td height="10"></td></tr>
	<tr><td colspan="9" height="1" bgcolor="#e1e1e1"></td></tr>
	<tr class="t_name_ord">
		<td rowspan="<?php echo $rows_cnt ?>" width="15%" style="padding:10px"><b>배송비 : <?php echo deliver_name($order_info->deliver_method) ?></b></td>
		<td class="Right">총 상품금액</td>
		<td width="10%" class="Right"><span class="price_color"><?=number_format($order_info->prd_price)?> 원</span></td>
	</tr>
	<tr class="t_name_ord">
		<td class="Right">배송비</td>
		<td width="10%" class="Right"><?php echo $deliver_price_view ?></td>
	</tr>
	<?php if($order_info->discount_price > 0){ ?>
	<tr class="t_name_ord">
		<td class="Right">회원 할인금액</td>
		<td width="10%" class="Right"><?php echo $discount_msg ?></td>
	</tr>
	<?php } ?>
	<?php if($order_info->reserve_use > 0){ ?>
	<tr class="t_name_ord">
		<td class="Right">적립금 사용금액</td>
		<td width="10%" class="Right"><?php echo $reserve_msg ?></td>
	</tr>
	<?php } ?>
	<?php if($order_info->coupon_use > 0){ ?>
	<tr class="t_name_ord">
		<td class="Right">쿠폰 사용금액</td>
		<td width="10%" class="Right"><?php echo $coupon_msg ?></td>
	</tr>
	<?php } ?>
	<tr class="t_name_ord">
		<td class="Right"><span class="price_size5">총 결제금액</span></td>
		<td width="10%" class="Right"><span class="price_size4"><?=number_format($order_info->total_price)?></span> <b><font color="#282828">원</font></b></td>
	</tr>
	<tr><td colspan="9" height="1" bgcolor="#e1e1e1"></td></tr>
	<tr><td height="10"></td></tr>
</table> -->
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 주문기본정보</td>
	</tr>
</table>
<form name="frm" action="order_save_p.php" method="post">
<input type="hidden" name="tmp">
<input type="hidden" name="mode"        value="update">
<input type="hidden" name="page"        value="<?=$page?>">
<input type="hidden" name="orderid"     value="<?=$orderid?>">

<input type="hidden" name="total_price" value="<?=$order_info->total_price?>">
<input type="hidden" name="prd_info"    value="<?=$prd_info?>">

<input type="hidden" name="s_status"    value="<?=$s_status?>">
<input type="hidden" name="prev_year"   value="<?=$prev_year?>">
<input type="hidden" name="prev_month"  value="<?=$prev_month?>">
<input type="hidden" name="prev_day"    value="<?=$prev_day?>">
<input type="hidden" name="next_year"   value="<?=$next_year?>">
<input type="hidden" name="next_month"  value="<?=$next_month?>">
<input type="hidden" name="next_day"    value="<?=$next_day?>">
<input type="hidden" name="searchopt"   value="<?=$searchopt?>">
<input type="hidden" name="searchkey"   value="<?=$searchkey?>">
<input type="hidden" name="menucode"    value="<?=$menucode?>">
<input type="hidden" name="mresult"     value="<?=$mresult?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">주문번호</td>
					<?if($order_info->f_orderid==""){?>
					<td width="35%" class="t_value"><?=$orderid?></td>
					<?}else{?>
					<td width="35%" class="t_value"><?=$order_info->f_orderid?></td>
					<?}?>

					<td width="15%" class="t_name">결제방법</td>
					<td width="35%" class="t_value"><?=pay_method($order_info->pay_method)?></td>
					</tr>
					<tr>
					<td class="t_name">주문일자</td>
					<td class="t_value"><?=$order_info->order_date?></td>
					<td class="t_name">에스크로여부</td>
					<td class="t_value"><?php echo $escrow_check ?></td>
					</tr>
					<? if($order_info->pay_method == "PC"){ ?>
					<tr>
					<td class="t_name">결제카드</td>
					<td class="t_value"><?=$order_info->financename?> (<?php echo $order_info->cardquota ?>)</td>
					<td class="t_name">카드번호</td>
					<td class="t_value"><?=$order_info->financename_num?></td>
					</tr>
					<? } else { ?>
					<tr>
					<td class="t_name">결제계좌</td>
					<td class="t_value"><?=$order_info->account?></td>
					<td class="t_name">입금인</td>
					<td class="t_value"><?=$order_info->account_name?></td>
				</tr>
					<? } ?>
				<?
				if($oper_info['deliveryType'] != "P"){
				?>
				<tr>
					<td class="t_name">운송장번호</td>
					<td class="t_value">
						<input name="deliver_num" type="text" value="<?=$order_info->deliver_num?>" class="input">
						<?
						if($oper_info['deliveryType'] == "M"){
						?>
						<select name="del_com" class="select">
							<option value="">배송업체선택</option>
						<?
							$query = "select * from wiz_delivery_company ";
							$result = query($query);
							while($_delivery = sql_fetch_arr($result)){
								$del_code = $_delivery['del_com']."|".$_delivery['idx'];
						?>
							<option value="<?=$del_code?>" <? if($del_code == $order_info->del_com) echo "selected"?>><?=$_delivery['del_com']?></option>
						<?
							}
						}
						?>
					</td>
					<td class="t_name">발송일자</td>
					<td class="t_value">
						<input name="deliver_date" id="deliver_date" type="text" value="<?=$order_info->deliver_date?>" class="input">
						<b>발송일자 입력형식(년월일시분)</b><br>
						예) <?=date('Y')?>년 <?=date('m')?>월 <?=date('d')?>일 <?=date('H')?>시 <?=date('i')?>분 =
						<?=date('Y').date('m').date('d').date('H').date('i')?>
					</td>
				</tr>
				<?
				}
				?>
				<tr>
					<td class="t_name">처리시간</td>
					<td class="t_value" colspan="3" style="padding: 4px 0 4px 10px">
						<table width="99%" border="0" cellspacing="1" cellpadding="6" class="t_style">
							<tr>
								<td width="15%" align="center" height="25" class="t_name">주문접수</td>
								<td width="15%" align="center" class="t_name">결제완료</td>
								<td width="14%" align="center" class="t_name">배송완료</td>
								<td width="14%" align="center" class="t_name">주문취소</td>
								<td width="14%" align="center" class="t_name">취소/환불요청</td>
								<!-- <td width="14%" align="center" class="t_name">교환요청</td>
								<td width="14%" align="center" class="t_name">교환완료</td> -->
							</tr>
							<tr>
								<td align="center" height="25">
									<? if($order_info->order_date == "0000-00-00 00:00:00") echo "-"; else echo $order_info->order_date; ?>
								</td>
								<td align="center"> 
									<? if($order_info->pay_date == "0000-00-00 00:00:00") echo "-"; else echo $order_info->pay_date; ?>
								</td>
								<td align="center">
									<? if($order_info->send_date == "0000-00-00 00:00:00") echo "-"; else echo $order_info->send_date; ?>
								</td>
								<td align="center">
									<? if($order_info->cancel_date == "0000-00-00 00:00:00") echo "-"; else echo $order_info->cancel_date; ?>
								</td>
								<td align="center">
									<? if($order_info->cancel_request_date == "0000-00-00 00:00:00" || $order_info->cancel_request_date == "") echo "-"; else echo $order_info->cancel_request_date; ?>
								</td>
								<!-- <td align="center">
									<? if($order_info->ex_request_date == "0000-00-00 00:00:00" || $order_info->ex_request_date == "") echo "-"; else echo $order_info->ex_request_date; ?>
								</td>
								<td align="center">
									<? if($order_info->exchange_date == "0000-00-00 00:00:00" || $order_info->exchange_date == "") echo "-"; else echo $order_info->exchange_date; ?>
								</td> -->
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 주문자정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">주문자명</td>
					<td width="35%" class="t_value"><input name="send_name" type="text" value="<?=$order_info->send_name?>" class="input"></td>
					<td width="15%" class="t_name">이메일</td>
					<td width="35%" class="t_value"><input name="send_email" type="text" value="<?=$order_info->send_email?>" class="input" size="30"> 
					<input type="button" value="메일발송" class="base_btm reg" onClick="sendEmail('<?=$order_info->send_name?>','<?=$order_info->send_email?>','<?=$order_info->status?>')";></td>
				</tr>
				<tr>
					<td class="t_name">전화번호</td>
					<td class="t_value"><input name="send_tphone" type="text" value="<?=$order_info->send_tphone?>" class="input"></td>
					<td class="t_name">휴대폰</td>
					<td class="t_value">
						<input name="send_hphone" type="text" value="<?=$order_info->send_hphone?>" class="input"> 
						<input type="button" value="SMS발송" class="base_btm reg" onClick="sendSms('S','<?=$order_info->send_hphone?>','<?=$order_info->status?>')";>
					</td>
				</tr>
				<tr>
					<td class="t_name">우편번호</td>
					<td class="t_value" colspan="3">
						<? $post = $order_info->send_post ?>
						<input name="send_post" type="text" value="<?=$post?>" size="5" class="input"> 
						<input type="button" value="우편번호검색" class="base_btn2"  onClick="searchZip('send_');">
					</td>
				</tr>
				<tr>
					<td class="t_name">주소</td>
					<td class="t_value" colspan="3"><input name="send_address" type="text" value="<?=$order_info->send_address?>" size="90" class="input"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 수령자정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td class="t_name">수취인명</td>
					<td class="t_value" colspan="3"><input name="rece_name" type="text" value="<?=$order_info->rece_name?>" class="input"></td>
				</tr>
				<tr>
					<td width="15%" class="t_name">전화번호</td>
					<td width="35%" class="t_value"><input name="rece_tphone" type="text" value="<?=$order_info->rece_tphone?>" class="input"></td>
					<td width="15%" class="t_name">휴대폰</td>
					<td width="35%" class="t_value"><input name="rece_hphone" type="text" value="<?=$order_info->rece_hphone?>" class="input"></td>
				</tr>
				<tr>
					<td class="t_name">우편번호</td>
					<td class="t_value" colspan="3">
					<? $post = $order_info->rece_post; ?>
						<input name="rece_post" type="text" value="<?=$post?>" size="5" class="input">
						<input type="button" value="우편번호검색" class="base_btn2"  onClick="searchZip('_rece');">
					</td>
				</tr>
				<tr>
					<td class="t_name">주소</td>
					<td class="t_value" colspan="3"><input name="rece_address" type="text" value="<?=$order_info->rece_address?>" size="90" class="input"></td>
				</tr>
				<tr>
					<td class="t_name">요청사항</td>
					<td class="t_value" colspan="3" style="width:100%"><textarea name="demand" rows="6" cols="60" class="textarea" style="width:99%"><?=$order_info->demand?></textarea></td>
				</tr>
				<tr>
					<td class="t_name">주문취소 사유</td>
					<td class="t_value" colspan="3" style="width:100%"><textarea name="cancelmsg" rows="6" cols="60" class="textarea" style="width:99%"><?=$order_info->cancelmsg?></textarea></td>
				</tr>
				<!-- <tr> 옵션에 대한 해결필요
					<td class="t_name">교환요청 사유</td>
					<td class="t_value" colspan="3" style="width:100%"><textarea name="exchangemsg" rows="6" cols="60" class="textarea" style="width:99%"><?=$order_info->exchangemsg?></textarea></td>
				</tr> -->
				<tr>
					<td class="t_name">관리자메모</td>
					<td class="t_value" colspan="3" style="width:100%"><textarea name="descript" rows="6" cols="60" class="textarea" style="width:99%"><?=$order_info->descript?></textarea></td>
				</tr>
				<?php
				if($oper_info['bankda_use'] == "Y") {
				?>
				<tr>
					<td class="t_name">입금내역 메모</td>
					<td class="t_value" colspan="3" style="width:100%"><textarea name="bk_memo" rows="6" cols="60" class="textarea" style="width:99%"><?=$order_info->bk_memo?></textarea></td>
				</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>

<?php
if(!strcmp($oper_info['tax_use'], "Y")) {
	$sql = "select * from wiz_tax where orderid = '$orderid'";
	$tax_info = sql_fetch($sql);

	$display_cash   = ($tax_info['cash_type2'] == 'CARDNUM') ? "" : " style='display:none'";
	$display_comnum = ($tax_info['cash_type2'] == 'COMNUM')  ? "" : " style='display:none'";
	$display_hphone = ($tax_info['cash_type2'] == 'HPHONE')  ? "" : " style='display:none'";
	$display_resno  = ($tax_info['cash_type2'] == 'RESNO')   ? "" : " style='display:none'";
?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 증빙서류 정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">발급여부</td>
					<td width="85%" class="t_value" colspan="3">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<label><input type="radio" name="tax_type" value="N" onClick="qclick('');" <? if(!strcmp($order_info->tax_type, "N") || empty($order_iinfo->tax_type)) echo "checked" ?>> 발행안함</label>&nbsp;
									<label><input type="radio" name="tax_type" value="T" onClick="qclick('01');" <? if(!strcmp($order_info->tax_type, "T")) echo "checked" ?>> 세금계산서 신청</label>&nbsp;
									<label><input type="radio" name="tax_type" value="C" onClick="qclick('02');" <? if(!strcmp($order_info->tax_type, "C")) echo "checked" ?>> 현금영수증 신청</label>
									<!--font color="red" onClick="printTax('<?=$orderid?>')" style="cursor:pointer">[출력]</font-->
								</td>
							</tr>
							<tr>
								<td height="2"></td>
							</tr>
							<tr>
								<td width="100%">

									<table id="tax00" style="display:<? if(strcmp($order_info->tax_type, "N")) echo ""; else echo "none"; ?>" width="60%" border="0" cellspacing="1" cellpadding="6" class="t_style">
										<tr height="35">
											<td width="10%" class="t_name_proof">&nbsp; 발급여부</td>
											<td width="50%" class="t_value_proof" colspan="3">
												<input type="hidden" name="tmp_tax_pub" value="<?=$tax_info['tax_pub']?>">
												<label><input type="radio" name="tax_pub" value="Y" <? if(!strcmp($tax_info['tax_pub'], "Y")) echo "checked" ?>> 발급완료</label>
												<label><input type="radio" name="tax_pub" value="N" <? if(!strcmp($tax_info['tax_pub'], "N") || empty($tax_info['tax_pub'])) echo "checked" ?>> 발급대기</label>
											</td>
										</tr>
									</table>
									<br>
									<table id="tax01" style="display:<? if(!strcmp($order_info->tax_type, "T")) echo ""; else echo "none"; ?>" width="60%" border="0" cellspacing="1" cellpadding="6" class="t_style">
										<tr>
											<td width="10%" class="t_name_proof">&nbsp; 사업자 번호</td>
											<td width="50%" colspan="3" class="t_value_proof">
												<?php
												list($com_num1, $com_num2, $com_num3) = explode("-", $tax_info['com_num']);
												?>
												<input type="text" name="com_num[]" value="<?=$com_num1?>" class="input Onum" size="4" maxlength="3" style="text-align:center"> - 
												<input type="text" name="com_num[]" value="<?=$com_num2?>" class="input Onum" size="3" maxlength="2" style="text-align:center"> - 
												<input type="text" name="com_num[]" value="<?=$com_num3?>" class="input Onum" size="6" maxlength="5" style="text-align:center">
											</td>
										</tr>
										<tr>
											<td width="10%" class="t_name_proof">&nbsp; 상 호</td>
											<td width="20%" class="t_value_proof">
												<input type="text" name="com_name" value="<?=$tax_info['com_name']?>" class="input inputful">
											</td>
											<td width="10%" class="t_name_proof">&nbsp; 대표자</td>
											<td width="20%" class="t_value_proof">
												<input type="text" name="com_owner" value="<?=$tax_info['com_owner']?>" class="input inputful">
											</td>
										</tr>
										<tr>
											<td width="10%" class="t_name_proof">&nbsp; 사업장 소재지</td>
											<td width="50%" colspan="3" class="t_value_proof">
												<?
												$post = $tax_info['com_post'];
												if(strlen($post) == 7) $post = $post;
												else				   $post = str_replace("-","",$post);
												
												?>
												<input name="com_post" type="text" value="<?=$post?>" size="7" class="input" style="text-align:center">
												<input type="button" value="우편번호검색" class="base_btn2" onClick="searchZip('com_');">
												<span class="tip_br"></span>
												<input type="text" name="com_address" value="<?=$tax_info['com_address1']?>" class="input inputful">
												<span class="tip_br"></span>
												<input type="text" name="com_address2" value="<?=$tax_info['com_address2']?>" class="input inputful">
											</td>
										</tr>
										<tr>
											<td width="10%" class="t_name_proof">&nbsp; 업 태</td>
											<td width="20%" class="t_value_proof">
												<input type="text" name="com_kind" value="<?=$tax_info['com_kind']?>" class="input inputful">
											</td>
											<td width="10%" class="t_name_proof">&nbsp; 종 목</td>
											<td width="20%" class="t_value_proof">
												<input type="text" name="com_class" value="<?=$tax_info['com_class']?>" class="input inputful">
											</td>
										</tr>
										<tr>
											<td width="10%" class="t_name_proof">&nbsp; 전화번호</td>
											<td width="20%" class="t_value_proof">
												<?php
												list($com_tel_1, $com_tel_2, $com_tel_3) = explode("-", $tax_info['com_tel']);
												?>
												<input type="text" name="com_tel[]" value="<?=$com_tel_1?>" class="input Onum" size="4" maxlength="4"> - 
												<input type="text" name="com_tel[]" value="<?=$com_tel_2?>" class="input Onum" size="4" maxlength="4"> - 
												<input type="text" name="com_tel[]" value="<?=$com_tel_3?>" class="input Onum" size="4" maxlength="4">

											</td>
											<td width="10%" class="t_name_proof">&nbsp; 발행 이메일</td>
											<td width="20%" class="t_value_proof">
												<input type="text" name="com_email" value="<?=$tax_info['com_email']?>" class="input inputful">
											</td>
										</tr>
									</table>

									<table id="tax02" style="display:<? if(!strcmp($order_info->tax_type, "C")) echo ""; else echo "none"; ?>" width="60%" border="0" cellspacing="1" cellpadding="6" class="t_style">
										<tr height="35">
											<td width="10%" class="t_name_proof">&nbsp; 발급사유</td>
											<td width="50%" class="t_value_proof">
												<label><input type="radio" name="cash_type" value="C" <? if(!strcmp($tax_info['cash_type'], "C")) echo "checked" ?>> 사업자 지출증빙용</label>
												<label><input type="radio" name="cash_type" value="P" <? if(!strcmp($tax_info['cash_type'], "P")) echo "checked" ?>> 개인소득 공제용</label>
											</td>
										</tr>
										<tr height="35">
											<td class="t_name_proof">&nbsp; 신청정보</td>
											<td class="t_value_proof">
												<label><input type="radio" name="cash_type2" value="CARDNUM" <? if(!strcmp($tax_info['cash_type2'], "CARDNUM")) echo "checked" ?> onclick="taxType('CARDNUM')"> 현금영수증 카드번호</label>
												<label><input type="radio" name="cash_type2" value="COMNUM" <? if(!strcmp($tax_info['cash_type2'], "COMNUM")) echo "checked" ?> onclick="taxType('COMNUM')"> 사업자 등록번호</label>
												<label><input type="radio" name="cash_type2" value="HPHONE" <? if(!strcmp($tax_info['cash_type2'], "HPHONE")) echo "checked" ?> onclick="taxType('HPHONE')"> 휴대전화번호</label>
												<label><input type="radio" name="cash_type2" value="RESNO" <? if(!strcmp($tax_info['cash_type2'], "RESNO")) echo "checked" ?> onclick="taxType('RESNO')"> 주민등록번호</label>
											</td>
										</tr>
										<tr id="CARDNUM" <?php echo $display_cash ?>>
											<?php
											list($cd_info1, $cd_info2, $cd_info3, $cd_info4) = explode("-", $tax_info['cash_info']);
											?>
											<td class="t_name_proof">&nbsp; <span id="type_name">카드번호</span></td>
											<td class="t_value_proof">
												<input type="text" name="cash_info[]" value="<?=$cd_info1?>" class="input Onum" size="5" maxlength="4" style="text-align:center"> - 
												<input type="text" name="cash_info[]" value="<?=$cd_info2?>" class="input Onum" size="5" maxlength="4" style="text-align:center"> - 
												<input type="text" name="cash_info[]" value="<?=$cd_info3?>" class="input Onum" size="5" maxlength="4" style="text-align:center"> - 
												<input type="text" name="cash_info[]" value="<?=$cd_info4?>" class="input Onum" size="5" maxlength="4" style="text-align:center">
											</td>
										</tr>
										<tr id="COMNUM" <?php echo $display_comnum ?>>
											<?php
											list($cn_info1, $cn_info2, $cn_info3) = explode("-", $tax_info['cash_info2']);
											?>
											<td class="t_name_proof">&nbsp; <span id="type_name2">사업자번호</span></td>
											<td class="t_value_proof">
												<input type="text" name="cash_info2[]" value="<?=$cn_info1?>" class="input Onum" size="4" maxlength="3" style="text-align:center"> - 
												<input type="text" name="cash_info2[]" value="<?=$cn_info2?>" class="input Onum" size="3" maxlength="2" style="text-align:center"> - 
												<input type="text" name="cash_info2[]" value="<?=$cn_info3?>" class="input Onum" size="6" maxlength="5" style="text-align:center">
											</td>
										</tr>
										<tr id="HPHONE" <?php echo $display_hphone ?>>
											<td class="t_name_proof">&nbsp; <span id="type_name3">휴대전화번호</span></td>
											<td class="t_value_proof">
												<?php
												list($hp_info1, $hp_info2, $hp_info3) = explode("-", $tax_info['cash_info3']);
												?>
												<input type="text" name="cash_info3[]" value="<?=$hp_info1?>" class="input Onum" size="4" maxlength="4" style="text-align:center"> - 
												<input type="text" name="cash_info3[]" value="<?=$hp_info2?>" class="input Onum" size="4" maxlength="4" style="text-align:center"> - 
												<input type="text" name="cash_info3[]" value="<?=$hp_info3?>" class="input Onum" size="4" maxlength="4" style="text-align:center">
											</td>
										</tr>
										<tr id="RESNO" <?php echo $display_resno ?>>
											<td class="t_name_proof">&nbsp; <span id="type_name4">주민등록번호</span></td>
											<td class="t_value_proof">
												<?php
												list($res_info1, $res_info2) = explode("-", $tax_info['cash_info4']);
												?>
												<input type="text" name="cash_info4[]" value="<?=$res_info1?>" class="input Onum" size="8" maxlength="6" style="text-align:center"> - 
												<input type="text" name="cash_info4[]" value="<?=$res_info2?>" class="input Onum" size="9" maxlength="7" style="text-align:center">
											</td>
										</tr>
										
										<tr>
											<td class="t_name_proof">&nbsp; 신청자명</td>
											<td class="t_value_proof">
												<input type="text" name="cash_name" value="<?=$tax_info['cash_name']?>" class="input" size="30">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? } ?>

<? if(!strcmp($oper_info['pay_agent'], "KCP") && strcmp($order_info->paymethod, "PC")) { ?>
<br>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 현금영수증 정보</td>
		<td valign="bottom" class="tit_alt">KCP 상점정보에 등록된 현금영수증 정보를 입력하세요.</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">발급여부</td>
					<td width="35%" class="t_value">
						<input type="text" name="id_info" value="<?=$order_info->id_info?>" class="input">
					</td>
					<td width="15%" class="t_name">국세청 승인여부</td>
					<td width="35%" class="t_value">
						<input type="radio" name="bill_yn" value="Y" <? if(!strcmp($order_info->bill_yn, "Y")) { ?> checked <? } ?>> 승인
						<input type="radio" name="bill_yn" value="N" <? if(!strcmp($order_info->bill_yn, "N")) { ?> checked <? } ?>> 미승인
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">승인번호</td>
					<td width="85%" class="t_value" colspan="3">
						<input type="text" name="authno" value="<?=$order_info->authno?>" class="input">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? } ?>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="33%"></td>
		<td width="33%" align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='order_list.php?<?=$param?>'">&nbsp;
			<? if($order_info->pay_method == "KK" && !empty($order_info->kakao_nonRepToken) && $order_info->status == "OY"){ //카카오결제일때만 ?>
			<input type="button" value="카카오페이 결제취소" class="base_btn yellow" onClick="Kakaopay_Cancel('<?=$order_info->tid?>');">
			<? } ?>
		</td>
		<td width="33%" align="right"><input type="button" value="인 쇄" class="base_btn blue" onClick="orderPrint()"></td>
	</tr>
</table>
</form>

<? include "../foot.php"; ?>