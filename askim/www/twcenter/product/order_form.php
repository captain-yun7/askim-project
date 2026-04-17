<?
	//장바구니수량 재고 재확인
	basketCheckAmount($_uniq_id, $product_idx);
?>
<script>
	if (self.name != 'reload') {
		self.name = 'reload';
		self.location.reload(true);
	}else{
		self.name = '';
	}
</script>

<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if(!empty($wiz_session['id'])) {

	// 회원정보로드(sns)------------------------
	if($wiz_session['login_Type'] == "sns"){
		$where  = " id='{$wiz_session['id']}' ";
	} else {
		$where = " id='{$wiz_session['id']}' ";
	}

	$sql = "select * from wiz_member where $where";
	$mem_info = sql_fetch($sql);

	$mem_id       = $mem_info['id'];


	if(!isset($mem_info['tphone'])) $mem_info['tphone'] ='';
	$mem_tphone   = explode("-", $mem_info['tphone']);
	
	if(!isset($mem_info['hphone'])) $mem_info['hphone'] ='';
	$mem_hphone   = explode("-", $mem_info['hphone']);
	
	if(!isset($mem_info['fax'])) $mem_info['fax'] ='';
	$mem_fax      = explode("-", $mem_info['fax']);
	
	if(!isset($mem_info['post'])) $mem_info['post'] ='';
	$mem_post     = explode("-", $mem_info['post']);

	if(!isset($mem_info['email'])) $mem_info['email'] ='';
	$mem_email    = explode("@", $mem_info['email']);

	if(!isset($mem_info['com_post'])) $mem_info['com_post'] ='';
	$mem_com_post = explode("-", $mem_info['com_post']);
	
	if(!isset($mem_info['birthday'])) $mem_info['birthday'] ='';
	$mem_birthday = explode("-", $mem_info['birthday']);
	
	if(!isset($mem_info['memorial'])) $mem_info['memorial'] ='';
	$mem_memorial = explode("-", $mem_info['memorial']);

}

// 회원적립금 가져오기
if($oper_info['reserve_use'] == "Y" && $wiz_session['id'] != ""){

	$sql = "select sum(reserve) as reserve from wiz_reserve where memid = '$mem_id'";
	$row = sql_fetch($sql);

	if($row['reserve'] == "") $mem_info['reserve'] = 0;
	else $mem_info['reserve'] = $row['reserve'];

}else{
	$mem_info['reserve'] = 0;
}
?>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="JavaScript">
<!--
function sameCheck(frm){

	if(frm.same_check.checked == true){

		frm.rece_name.value    = frm.send_name.value;

		frm.rece_tphone.value   = frm.send_tphone.value;
		frm.rece_tphone2.value  = frm.send_tphone2.value;
		frm.rece_tphone3.value  = frm.send_tphone3.value;

		frm.rece_hphone.value   = frm.send_hphone.value;
		frm.rece_hphone2.value  = frm.send_hphone2.value;
		frm.rece_hphone3.value  = frm.send_hphone3.value;

		frm.rece_post.value     = frm.send_post.value;
		frm.rece_address1.value = frm.send_address1.value;
		frm.rece_address2.value = frm.send_address2.value;

	}else{

		frm.rece_name.value     = "";
		frm.rece_tphone.value   = "";
		frm.rece_tphone2.value  = "";
		frm.rece_tphone3.value  = "";
		frm.rece_hphone.value   = "";
		frm.rece_hphone2.value  = "";
		frm.rece_hphone3.value  = "";
		frm.rece_post.value     = "";
		frm.rece_address1.value = "";
		frm.rece_address2.value = "";

	}

}

function inputCheck(frm){

	<? if(!strcmp((string)$order_guest, "true")){ ?>
		if(frm.agree2[0].checked==false){
			alert('개인정보 수집·이용 안내에 대한 동의여부를 체크해주시기 바랍니다.');
			frm.agree2[1].checked=true;
			return false;
		}
	<? } ?>

	if(!frm.basket_exist.value) {
		alert("주문할 상품이 없습니다.");
		return false;
	}

	if(frm.send_name.value == ""){
		alert("주문하시는분 성명을 입력하세요");
		frm.send_name.focus();
		return false;
	}else{
		if(!check_nonChar(frm.send_name.value)){
			alert("주문자 성명에는 특수문자가 들어갈 수 없습니다");
			frm.send_name.focus();
			return false;
		}
	}

/*	if(frm.send_tphone.value == ""){
		alert("주문하시는분 전화번호를 입력하세요.");
		frm.send_tphone.focus();
		return false;
	}

	if(frm.send_tphone2.value == ""){
		alert("주문하시는분 전화번호를 입력하세요.");
		frm.send_tphone2.focus();
		return false;
	}

	if(frm.send_tphone3.value == ""){
		alert("주문하시는분 전화번호를 입력하세요.");
		frm.send_tphone3.focus();
		return false;
	} */

	if(frm.send_hphone.value == ""){
		alert("주문하시는분 휴대전화번호를 입력하세요.");
		frm.send_hphone.focus();
		return false;
	}

	if(frm.send_hphone2.value == ""){
		alert("주문하시는분 휴대전화번호를 입력하세요.");
		frm.send_hphone2.focus();
		return false;
	}

	if(frm.send_hphone3.value == ""){
		alert("주문하시는분 휴대전화번호를 입력하세요.");
		frm.send_hphone3.focus();
		return false;
	}

	if(frm.email_1.value == "" || frm.email_2.value == ""){
		alert("주문하시는분 이메일을 입력하세요.");
		frm.email_1.focus();
		return false;
	}
	
	if(frm.email_1.value != "" && frm.email_2.value != ""){
		var o_email = frm.email_1.value+"@"+frm.email_2.value;
		var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
		if(regex.test(o_email) === false) {
			alert("이메일형식이 잘못되었습니다.");
			return false;
		}
	}
	
	if(frm.send_address1.value == ""){
		alert("주문하시는분 주소를 입력하세요");
		frm.send_address1.focus();
		return false;
	}
	if(frm.send_address2.value == ""){
		alert("주문하시는분 상세주소를 입력하세요");
		frm.send_address2.focus();
		return false;
	}

	if(frm.rece_name.value == ""){
		alert("받으시는분 성명을 입력하세요");
		frm.rece_name.focus();
		return false;
	}else{
		if(!check_nonChar(frm.rece_name.value)){
			alert("받으시는분 성명에는 특수문자가 들어갈 수 없습니다");
			frm.rece_name.focus();
			return false;
		}
	}

	if(frm.rece_hphone.value == ""){
		alert("받으시는분 휴대전화번호를 입력하세요.");
		frm.rece_hphone.focus();
		return false;
	}
	if(frm.rece_hphone2.value == ""){
		alert("받으시는분 휴대전화번호를 입력하세요.");
		frm.rece_hphone2.focus();
		return false;
	}
	if(frm.rece_hphone3.value == ""){
		alert("받으시는분 휴대전화번호를 입력하세요.");
		frm.rece_hphone3.focus();
		return false;
	}

	if(frm.rece_address1.value == ""){
		alert("받으시는분 주소를 입력하세요");
		frm.rece_address1.focus();
		return false;
	}
	if(frm.rece_address2.value == ""){
		alert("받으시는분 상세주소를 입력하세요");
		frm.rece_address2.focus();
		return false;
	}

	var pay_checked = false;
	var pay_checked_val = "";
	for(ii=0;ii<frm.pay_method.length;ii++){
		if(frm.pay_method[ii].checked == true){
			pay_checked = true;
			pay_checked_val = frm.pay_method[ii].value;
		}
	}

	if(pay_checked == false){
		alert("일반결제를 선택하세요");
		return false;
	}

	<? if(!strcmp($oper_info['tax_use'], "Y")) { ?>

	if(pay_checked_val == "PC" && frm.tax_type[0].checked != true) {
		alert("신용카드 결제 시 세금계산서 및 현금영수증 발급이 불가능합니다.");
		frm.tax_type[0].checked = true;
		qclick("");
		return false;
	}

	// 세금계산서
	if(frm.tax_type[1].checked == true) {

		if(frm.com_num.value == ""){
			alert("사업자 번호를 입력하세요");
			frm.com_num.focus();
			return false;
		}
		if(frm.com_name.value == ""){
			alert("상호를 입력하세요");
			frm.com_name.focus();
			return false;
		}
		if(frm.com_owner.value == ""){
			alert("대표자를 입력하세요");
			frm.com_owner.focus();
			return false;
		}
		if(frm.com_address1.value == ""){
			alert("사업장 소재지를 입력하세요");
			frm.com_address1.focus();
			return false;
		}
		if(frm.com_address2.value == ""){
			alert("사업장 소재지 상세주소를 입력하세요");
			frm.com_address2.focus();
			return false;
		}
		if(frm.com_kind.value == ""){
			alert("업태를 입력하세요");
			frm.com_kind.focus();
			return false;
		}
		if(frm.com_class.value == ""){
			alert("종목을 입력하세요");
			frm.com_class.focus();
			return false;
		}
		if(frm.com_tel.value == ""){
			alert("전화번호를 입력하세요");
			frm.com_tel.focus();
			return false;
		}
		if(frm.com_email.value == ""){
			alert("이메일을 입력하세요");
			frm.com_email.focus();
			return false;
		}

	}

	// 현금영수증
	if(frm.tax_type[2].checked == true) {

		var cash_type_check = false;
		for(ii = 0; ii < frm.cash_type.length; ii++) {
			if(frm.cash_type[ii].checked == true) {
				cash_type_check = true;
				break;
			}
		}
		if(cash_type_check == false) {
			alert("발급사유를 선택하세요.");
			return false;
		}

		var cash_type2_check = false;
		for(ii = 0; ii < frm.cash_type2.length; ii++) {
			if(frm.cash_type2[ii].checked == true) {
				cash_type2_check = true;
				break;
			}
		}
		if(cash_type2_check == false) {
			alert("신청정보를 선택하세요.");
			return false;
		}

		var cash_type2 = frm.cash_type2.value;
		
		if(cash_type2 == 'CARDNUM') {
			for(ii = 0; ii < document.forms["frm"].elements["cash_info[]"].length; ii++) {
				if(document.forms["frm"].elements["cash_info[]"][ii].value == "") {
					alert("현금영수증 카드번호를 입력하세요.");
					document.forms["frm"].elements["cash_info[]"][ii].focus();
					return false;
				}
			}
		} else if(cash_type2 == 'HPHONE') {
			for(ii = 0; ii < document.forms["frm"].elements["cash_info3[]"].length; ii++) {
				if(document.forms["frm"].elements["cash_info3[]"][ii].value == "") {
					alert("휴대전화번호를 입력하세요.");
					document.forms["frm"].elements["cash_info3[]"][ii].focus();
					return false;
				}
			}
		} else if(cash_type2 == 'COMNUM') {
			for(ii = 0; ii < document.forms["frm"].elements["cash_info2[]"].length; ii++) {
				if(document.forms["frm"].elements["cash_info2[]"][ii].value == "") {
					alert("사업자등록번호를 입력하세요.");
					document.forms["frm"].elements["cash_info2[]"][ii].focus();
					return false;
				}
			}
		}

		if(frm.cash_name.value == "") {
			alert("신청자명을 입력하세요.");
			frm.cash_name.focus();
			return false;
		}

	}

	<? } ?>
	<?
	if($oper_info['unLimited'] != 'Y'){
	?>
		if(!reserveUse(frm)){
			return false;
		}
	<?
	}
	?>

}

$(function(){
	var paymethod = $(":input:radio[name=pay_method]:checked").val();
	if(paymethod != 'PC'){
		$("#kakao").hide();
	}
});

function pay_kakao(){

	var paymethod = $(":input:radio[name=pay_method]:checked").val();
	if(paymethod == "PC"){
		$("#kakao").show();
	} else {
		$("#kakao").hide();
	}

}

// 우편번호
function zipSearch(kind){

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
			eval('frm.'+kind+'address1').value = fullAddr;

			if(eval('frm.'+kind+'address1') != null)
				eval('frm.'+kind+'address2').focus();
		}
	}).open();

}

// 적립금 사용
function reserveUse(frm){

	if(frm.reserve_use != null){

		var reserve_use = frm.reserve_use.value;
		var total_price = frm.total_price.value;
		var unLimited = "<?=$oper_info['unLimited']?>";

		if(reserve_use != ""){

			if(reserve_use != "" && !check_Num(reserve_use)){
				alert("적립금은 숫자만 가능합니다.");
				frm.reserve_use.value = "";
				frm.reserve_use.focus();
				return false;
			}else{
				reserve_use = eval(reserve_use);
				total_price = eval(total_price);
			}

			if(reserve_use > <?=$mem_info['reserve']?>){
				alert("사용가능액 보다 많습니다.");
				frm.reserve_use.value = "";
				frm.reserve_use.focus();
				return false;
			}else if(reserve_use > total_price){
				alert("주문금액 보다 많습니다.");
				frm.reserve_use.value = "";
				frm.reserve_use.focus();
				return false;
			}else if(reserve_use < <?=$oper_info['reserve_min']?>){
				alert("최소사용 적립금 보다 작습니다. <?=number_format($oper_info['reserve_min'])?>원 이상 사용가능합니다.");
				frm.reserve_use.value = "";
				return false;
			}else if(unLimited != 'Y' && reserve_use > <?=$oper_info['reserve_max']?>){
				alert("최대사용 적립금 보다 큽니다. <?=number_format($oper_info['reserve_max'])?>원 이하 사용가능합니다.");
				frm.reserve_use.value = "";
				return false;
			}

		}

	}

	return true;

}

/*
작업자명	: 이상민
작업일시	: 2020-04-09
작업내용	: 적립금 일괄사용버튼 추가
*/
$(document).on("click", "#use_max_reserve", function(){
	var total_price = $("input[name='total_price']").val() - $("#basket_deliver_price").val();
	var mem_reserve = <?php echo intval($mem_info["reserve"]); ?>;
	var use_reserve = 0;
	var oper_reserve_min = <?php echo intval($oper_info["reserve_min"]); ?>;
	var oper_reserve_max = <?php echo intval($oper_info["reserve_max"]); ?>;	
	var unLimited = "<?=$oper_info['unLimited']?>";

	if($(this).prop("checked") == true){
		// 보유적립금이 총 금액보다 많을 때
		if(mem_reserve > total_price){
			use_reserve = total_price;
		} else {
			// 보유적립금이 총 금액보다 적을 때
			use_reserve = mem_reserve;
		}
		
		if(oper_reserve_min > 0 && oper_reserve_min > use_reserve){
			use_reserve = 0;
		}

		if(unLimited != 'Y' && oper_reserve_max > 0 && use_reserve > oper_reserve_max){
			use_reserve = oper_reserve_max;
		}
	}

	$("input[name='reserve_use']").val(use_reserve);
	reserveUse(document.frm);
});

var couponWin;

// 쿠폰사용
//function couponUse(){
//
//	if(couponWin != null) couponWin.close();
//
//	var url = "/twcenter/product/coupon_list.php?prdcode=<?=$cp_prdcode?>";
//	couponWin = window.open(url, "couponUse", "height=450, width=650, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
//}

function cuponClose() {

	if(couponWin != null) couponWin.close();
}

// 세금계산서발행
function qclick(idnum) {

	tax01.style.display = 'none';
	tax02.style.display = 'none';

	if(idnum != ""){
		tax=eval("tax"+idnum+".style");
		tax.display = '';

		$("#tax_view_1").show();
	} else {
		$("#tax_view_1").hide();
	}

}

// 현금영수증발행 - 발급사유
function qclick2(idnum) {

	var type1 = "<input type=\"radio\" name=\"cash_type2\" value=\"CARDNUM\" onclick=\"qclick3('01');qclick4('CARDNUM');\"> 현금영수증 카드번호";
	var type2 = "<input type=\"radio\" name=\"cash_type2\" value=\"COMNUM\" onclick=\"qclick3('02');qclick4('COMNUM');\"> 사업자 등록번호";
	var type3 = "<input type=\"radio\" name=\"cash_type2\" value=\"HPHONE\" onclick=\"qclick3('03');qclick4('HPHONE');\"> 휴대전화번호";
	//var type4 = "<input type=\"radio\" name=\"cash_type2\" value=\"RESNO\" onclick=\"qclick3('04');qclick4('RESNO');\"> 주민등록번호";

	if(idnum) {
		$("#cash_info_id").show();
	} else {
		$("#cash_info_id").hide();
	}

	// 사업자 지출증빙용
	if(idnum == "01") {
		document.getElementById("cash_info01").innerHTML = type1 + " " + type2;
	// 개인소득 공제용
	} else if(idnum == "02") {
		document.getElementById("cash_info01").innerHTML = type1 + " " + type3 + " ";
	} else if(idnum == "03"){
		document.getElementById("cash_info01").innerHTML = "";
	}

	document.getElementById("cash_info02").innerHTML = "";

	var tmp_cash_type2 = $("input[name=tmp_cash_type2]").val();
	if(tmp_cash_type2 == '') {
		$("#cash_info_id02").hide();
	}

}

// 현금영수증발행 - 신청정보
function qclick3(idnum) {

	if(idnum) {
		$("#cash_info_id02").show();
	} else {
		$("#cash_info_id02").hide();
	}
	var cash_info_id02_tit;
	switch(idnum) {
	  case '01':
		cash_info_id02_tit = "현금영수증 카드번호";
		break;
	  case '02':
		cash_info_id02_tit = "사업자 등록번호";
		break;
	  case '03':
		cash_info_id02_tit = "휴대전화번호";
		break;
	}

	$("#cash_info_id02_tit").text(cash_info_id02_tit);

	var hphone_num = "";
	    hphone_num += "<select name=\"cash_info3[]\" class=\"input\">";
		hphone_num += "  <option value='010'>010</option>";
		hphone_num += "  <option value='011'>011</option>";
		hphone_num += "  <option value='016'>016</option>";
		hphone_num += "  <option value='017'>017</option>";
		hphone_num += "  <option value='018'>018</option>";
		hphone_num += "  <option value='019'>019</option>";
		hphone_num += "</select>";

	/** 현금영수증 카드번호 **/
	var cash_info01  = "<input type=\"text\"     name=\"cash_info[]\" size=\"4\" maxlength=\"4\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
	    cash_info01 += "<input type=\"text\"     name=\"cash_info[]\" size=\"4\" maxlength=\"4\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
		cash_info01 += "<input type=\"password\" name=\"cash_info[]\" size=\"4\" maxlength=\"4\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
		cash_info01 += "<input type=\"password\" name=\"cash_info[]\" size=\"7\" maxlength=\"7\" class=\"input\" onkeyup='qclick5();' Onlynum=true>";

	/** 사업자등록번호 **/
	var cash_info02  = "<input type=\"text\"     name=\"cash_info2[]\" size=\"3\" maxlength=\"3\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
	    cash_info02 += "<input type=\"text\"     name=\"cash_info2[]\" size=\"2\" maxlength=\"2\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
		cash_info02 += "<input type=\"text\"     name=\"cash_info2[]\" size=\"7\" maxlength=\"7\" class=\"input\" onkeyup='qclick5();' Onlynum=true>";

	/** 휴대전화번호 **/
	var cash_info03  = hphone_num + " - ";
	    cash_info03 += "<input type=\"text\"     name=\"cash_info3[]\" size=\"4\" maxlength=\"4\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
		cash_info03 += "<input type=\"text\"     name=\"cash_info3[]\" size=\"4\" maxlength=\"4\" class=\"input\" onkeyup='qclick5();' Onlynum=true>";

	/** 주민등록번호 **/
	var cash_info04  = "<input type=\"text\"     name=\"cash_info4[]\" size=\"6\" maxlength=\"6\" class=\"input\" onkeyup='qclick5();' Onlynum=true> - ";
	    cash_info04 += "<input type=\"password\" name=\"cash_info4[]\" size=\"7\" maxlength=\"7\" class=\"input\" onkeyup='qclick5();' Onlynum=true>";

	var cash_info = eval("cash_info"+idnum);
	document.getElementById("cash_info02").innerHTML = cash_info;

	var tmp_cash_type2 = $("input[name=tmp_cash_type2]:checked").val();
	$("#tmp_cash_type2").val(tmp_cash_type2);

}

function qclick4(val){
	var cash_type2_tmp =  $(':radio[name="cash_type2"]:checked').val();
	$("#cash_type2_tmp").val(cash_type2_tmp);
}

function qclick5(){

	var cash_info = "";
	for(ii = 0; ii < document.getElementsByName("cash_info_arr[]").length; ii++) {
		cash_info += document.getElementsByName("cash_info_arr[]")[ii].value + "-";
	}
	$("#cash_info_tmp").val(cash_info);
}

function tax_check(){
	var vist_check   = document.getElementsByName('pay_method');
	var vist_checked = "";
	for(var i=0; i<vist_check.length; i++){
		if(vist_check[i].checked==true){
			vist_checked = vist_check[i].value;
		}
	}
	if(vist_checked=="PC" || vist_checked=="PH" || vist_checked=="KK"){
		var tax_view             = document.getElementById("tax_view");
		var tax_view_1           = document.getElementById("tax_view_1");
		tax_view.style.display   = "none"; 
		tax_view_1.style.display = "none";
		document.getElementsByName('tax_type')[0].checked = true;
	}else{
		var tax_view             = document.getElementById("tax_view");
		var tax_view_1           = document.getElementById("tax_view_1");
		tax_view.style.display   = ""; 
		tax_view_1.style.display = "";
		qclick('');
		document.getElementsByName('tax_type')[0].checked = true;

		document.getElementById("com_num").value           = "";
		document.getElementById("com_name").value          = "";
		document.getElementById("com_owner").value         = "";
		document.getElementById("com_address1").value      = "";
		document.getElementById("com_address2").value      = "";
		document.getElementById("com_kind").value          = "";
		document.getElementById("com_class").value         = "";
		document.getElementById("cash_type").value         = "";
		document.getElementsByName('cash_type')[0].checked = "";
		document.getElementsByName('cash_type')[1].checked = "";
		document.getElementById("cash_name").value         = "";
	}
}

function LayView(n){
	var lay1    = document.getElementById("lay_con1");
	var lay2    = document.getElementById("lay_con2");
	var lay3    = document.getElementById("lay_con3");
	var layimg1 = document.getElementById("lay_img1");
	var layimg2 = document.getElementById("lay_img2");  
	var layimg3 = document.getElementById("lay_img3");
	
	if(n == 1){
		lay1.style.display       = "";
		lay2.style.display       = "none";
		lay3.style.display       = "none";
		layimg1.style.background = "#888";
		layimg1.style.color      = "#fff";
		layimg2.style.background = "#ececec";
		layimg2.style.color      = "#666";
		layimg3.style.background = "#ececec";
		layimg3.style.color      = "#666";
	}else if(n == 2){
		lay1.style.display       = "none";
		lay2.style.display       = "";
		lay3.style.display       = "none";
		layimg1.style.background = "#ececec";
		layimg1.style.color      = "#666";
		layimg2.style.background = "#888";
		layimg2.style.color      = "#fff";
		layimg3.style.background = "#ececec";
		layimg3.style.color      = "#666";
	}else {
		lay1.style.display       = "none";
		lay2.style.display       = "none";
		lay3.style.display       = "";
		layimg1.style.background = "#ececec";
		layimg1.style.color      = "#666";
		layimg2.style.background = "#ececec";
		layimg2.style.color      = "#666";
		layimg3.style.background = "#888";
		layimg3.style.color      = "#fff";
	}
}

var basic_del_chk = function(obj) {

	$.ajax({
		type: "post"
		,url: "/twcenter/member/delivery_proc.php"
		,cache: false
		,async: false
		,data: {
				"basicChk" : obj
				, "gType" : "L"
		}
		,dataType: "json"
		,success: function (data) {

			var objData = String(JSON.stringify(data));
			var parsedJson = JSON.parse(objData);

			if(parsedJson.list != null) {

				var len = parsedJson.list.length;
				for(var i=0; i<len; i++){

					$("#Frmaddr").show();
					$("input[name='rece_name']").val(parsedJson.list[i].re_name);
					$("input[name='rece_post']").val(parsedJson.list[i].re_post);
					$("input[name='rece_address1']").val(parsedJson.list[i].re_addr);
					$("input[name='rece_address2']").val(parsedJson.list[i].re_addr2);
					$("select[name='rece_hphone']").val(parsedJson.list[i].hp1);
					$("input[name='rece_hphone2']").val(parsedJson.list[i].hp2);
					$("input[name='rece_hphone3']").val(parsedJson.list[i].hp3);
					$("input[name='rece_tphone']").val(parsedJson.list[i].tp1);
					$("input[name='rece_tphone2']").val(parsedJson.list[i].tp2);
					$("input[name='rece_tphone3']").val(parsedJson.list[i].tp3);				
				}

			} else {
				
				alert("선택하신 정보를 가져올수 없습니다.\n다시 시도해주세요.");
				self.close();

			}
		
		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}

	});

};

var basic_del_chk2 = function(obj) {

	$.ajax({
		type: "post"
		,url: "/twcenter/member/delivery_proc.php"
		,cache: false
		,async: false
		,data: {basicChk: obj, "gType" : "P"}
		,dataType: "json"
		,success: function (data) {

			var objData = String(JSON.stringify(data));
			var parsedJson = JSON.parse(objData);
			if(parsedJson.list != null) {

				var len = parsedJson.list.length;
				for(var i=0; i<len; i++){
					if(obj == 'V') {
						$("#Frmaddr").hide();
						$("input[name='rece_post']").val('');
						$("input[name='rece_address1']").val('');
						$("input[name='rece_address2']").val('');
					} else {
						$("#Frmaddr").show();

						$("input[name='rece_name']").val(parsedJson.list[i].rece_name);
						$("select[name='rece_hphone']").val(parsedJson.list[i].hp1);
						$("input[name='rece_hphone2']").val(parsedJson.list[i].hp2);
						$("input[name='rece_hphone3']").val(parsedJson.list[i].hp3);
						$("input[name='rece_tphone']").val(parsedJson.list[i].tp1);
						$("input[name='rece_tphone2']").val(parsedJson.list[i].tp2);
						$("input[name='rece_tphone3']").val(parsedJson.list[i].tp3);
						$("input[name='rece_post']").val(parsedJson.list[i].rece_post);
						$("input[name='rece_address1']").val(parsedJson.list[i].rece_address);
						$("input[name='rece_address2']").val(parsedJson.list[i].rece_address2);
					}
				}

			} else {
				alert("선택하신 정보를 가져올수 없습니다.\n다시 시도해주세요.");
			}
		
		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}

	});

};

var del_chg = function() {
 
	var obj = $('input:radio[name=address_change]:checked').val();
	if(obj == 'C' || obj == 'V' || obj == 'D') {
		alert("기본배송지 체크후 배송지변경을 해주세요.");
	} else {
		var url = "/twcenter/module/delivery_pop.php?ctl=p";
		window.open(url,'','height=500, width=700, scrollbars=no');
	}
};


$(function() {
	$("#Frmaddr").show();
	<?php 
	if($com_member == true){
		echo '$(".com_noshow").hide();';
	}
	?>
});

//-->
</script>

<body onUnload="cuponClose();">


<div class="shop_level">
	<ul>
		<li><p class="step">STEP1</p><p class="txt">장바구니</p></li>
		<li class="hover"><p class="step">STEP2</p><p class="txt">주문하기</p></li>
		<li><p class="step">STEP3</p><p class="txt">결제하기</p></li>
		<li><p class="step" >STEP4</p><p class="txt">주문완료</p></li>
	</ul>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
		<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/orderform_list.php"; ?>
		<script>
			// 쿠폰사용
			function couponUse(){

				if(couponWin != null) couponWin.close();
				/*
				작업자명	: 이상민
				작업일시	: 2020-10-14
				작업내용	: 쿠폰적용대상 구매 총 금액계산을 위한 파라미터 추가
				*/
				var url = "/twcenter/product/coupon_list.php?prdcode=<?=$cp_prdcode?>&product_idx=<?php echo $product_idx; ?>";
				couponWin = window.open(url, "couponUse", "height=450, width=650, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
			}
		</script>
	</td>
	</tr>
</table>

<form name="frm" action="<?=$ssl?>/twcenter/product/order_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="total_price" value="<?=$total_price?>">
<input type="hidden" name="coupon_idx" value="">
<input type="hidden" name="basket_exist" value="<?=$basket_exist?>">
<input type="hidden" name="product_idx" value="<?=$product_idx?>">
<input type="hidden" name="tmp_cash_type2" id="tmp_cash_type2">
<?php
/*
작업자명	: 이상민
작업일시	: 2020-04-09
작업내용	: 적립금 일괄사용버튼 추가
*/
?>
<input type="hidden" id="basket_prd_price" value="<?php echo $prd_price; ?>">
<input type="hidden" id="basket_deliver_price" value="<?php echo $deliver_price; ?>">

<?php

if(!isset($order_guest)) $order_guest = '';

		if(!strcmp($order_guest, "true")){ ?>

			<div class="pri_box">

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="shop_tit">개인정보 수집·이용에 대한 동의</td>
							</tr>
							<tr>
								<td class="pri_btn">
									<a style="cursor:pointer; background:#888; color:#fff" name="layimg1" id="lay_img1" onclick="LayView(1);">개인정보의 수집ㆍ이용목적</a>
									<a style="cursor:pointer" name="layimg2" id="lay_img2" onclick="LayView(2);">개인정보수집항목</a>
									<a style="cursor:pointer" name="layimg3" id="lay_img3" onclick="LayView(3);">개인정보의 보유ㆍ이용 기간</a>
								</td>
							</tr>
							<tr id="lay_con1">
								<td style="padding-top:10px;">
									<textarea style="width:97.6%; height:100px;" class="pri_textarea" readonly> "회사"는 회원가입, 원활한 고객상담, 각종 서비스의 제공을 위해 아래와 같은 개인정보를 수집하고 있습니다.

가. 수집항목 : 
보내시는 분 정보(이름 , 전화번호, 휴대전화번호, 주소, 이메일)
받으시는 분 정보(이름 , 전화번호, 휴대전화번호, 주소, 이메일)
결제수단(신용카드 정보, 계좌번호 정보)
증빙수단(사업자등록번호, 사업자 상호, 대표자, 사업장 소재지, 전화번호, 이메일, 현금영수증 카드번호, 휴대전화번호)

나. 개인정보 수집방법 : 상품주문</textarea>
								</td>
							</tr>

							<tr id="lay_con2" style="display:none;">
								<td style="padding-top:10px;">
									<textarea style="width:97.6%; height:100px;" class="pri_textarea" readonly>"회사"는 수집한 개인정보를 다음의 목적을 위해 활용합니다.

가. 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산
구매 및 요금 결제, 물품배송</textarea>
								</td>
							</tr>

							<tr id="lay_con3" style="display:none;">
								<td style="padding-top:10px;">
									<textarea style="width:97.6%; height:100px;" class="pri_textarea" readonly>원칙적으로, 개인정보 수집 및 이용목적이 달성된 후에는 해당 정보를 지체 없이 파기합니다. 단, 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 아래와 같이 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다.

가. 보존 항목 : 이름, 로그인ID, 결제기록, 주문정보, 배송정보
나. 보존 근거 : 서비스 이용의 혼선 방지
다. 보존 기간 : 5년 상법, 전자상거래 등에서의 소비자보호에 관한 법률 등 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다. 이 경우 회사는 보관하는 정보를 그 보관의 목적으로만 이용하며 보존기간은 아래와 같습니다.

라. 계약 또는 청약철회 등에 관한 기록 : 5년 (전자상거래등에서의 소비자보호에 관한 법률)
마. 대금결제 및 재화 등의 공급에 관한 기록 : 5년 (전자상거래등에서의 소비자보호에 관한 법률)
바. 소비자의 불만 또는 분쟁처리에 관한 기록 : 3년 (전자상거래등에서의 소비자보호에 관한 법률)
사. 신용정보의 수집/처리 및 이용 등에 관한 기록 : 3년 (신용정보의 이용 및 보호에 관한 법률)</textarea>
								</td>
							</tr>

							<tr>
								<td style="padding-top:15px;" align="right">개인정보 수집·이용에 대해 동의하시겠습니까?&nbsp;&nbsp;&nbsp;
									<input name="agree2" type="radio" value="Y" class="checkbox">
										동의함&nbsp;
									<input name="agree2" type="radio" value="N" class="checkbox" checked>
										동의안함 
								</td>
							</tr>
						</table>

			</div>

<? } ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding-top:30px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr>
					<td class="shop_tit">주문하시는 분</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="pay_table">
							<tr>
								<td width="20%" class="table_tit2">주문하시는 분 *</td>
								<td width="80%"><input type=text name="send_name" value="<?=$wiz_session['name']?>" size=25 class="input"></td>
							</tr>
							<tr>
								<td class="table_tit2">전화번호</td>
								<td>
									<input type=text name="send_tphone" value="<?=$mem_tphone[0]?>" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="send_tphone2" value="<?=$mem_tphone[1]?>" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="send_tphone3" value="<?=$mem_tphone[2]?>" size=4 class="input" maxlength="4"  Onlynum="true">
								</td>
							</tr>
							<tr>
								<td class="table_tit2">휴대전화번호 *</td>
								<td>
									<select name="send_hphone" class="input">
									<?
									foreach($_hp_array AS $val){
									?>
									<option value="<?=$val[0]?>" <? if($mem_hphone[0] == $val[0]) echo "selected";?>><?=$val[1]?></option>
									<? } ?>
									</select> -
									<input type=text name="send_hphone2" value="<?=$mem_hphone[1]?>" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="send_hphone3" value="<?=$mem_hphone[2]?>" size=4 class="input" maxlength="4"  Onlynum="true">
								</td>
							</tr>
							<tr>
								<td class="table_tit2">이메일 *</td>
								<td><!-- <input type=text name="send_email" value="<?=$mem_info['email']?>" size=30 class="input"> -->
									<input type="text" name="email_1" class="input" style="width:90px;" value="<?=$mem_email[0]?>"> @ 
									<input name="email_2" type="text" class="input" size="12" value="<?=$mem_email[1]?>">
									<select id='email_select' name='' onchange="if(this.value != ''){document.frm.email_2.value=this.value;}else{document.frm.email_2.value='';}" name='email_select' class="input">
									<?
									foreach($_email_array AS $val){
									?>
									<option value="<?=$val[0]?>" <? if($mem_email[1] == $val[0]) echo "selected";?>><?=$val[1]?></option>
									<? } ?>
								</td>
							</tr>
							<tr>
								<td class="table_tit2">주소 *</td>
								<td>
									<input type=text name="send_post" value="<?=$mem_post[0]?>" size=7 class="input"><input type="buttom" value="우편번호 찾기" onClick="javascript:zipSearch('send_');" class="btn_shop1"><br/>

									<input type=text name="send_address1" value="<?=$mem_info['address1']?>" size=70 class="input mar">
									<input type=text name="send_address2" value="<?=$mem_info['address2']?>" size=70 class="input">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:30px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr>
					<td class="shop_tit">상품 받으시는 분</td>
					<td align="right"><input type="checkbox" name="same_check" onClick="sameCheck(this.form);" />&nbsp;주문자 정보와 동일합니다.</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="pay_table">
							<tr>
								<td width="20%" class="table_tit2">받으시는 분 *</td>
								<td width="80%" ><input type=text name="rece_name" size=25 class="input"></td>
							</tr>
							<tr>
								<td class="table_tit2">전화번호</td>
								<td>
									<input type=text name="rece_tphone" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="rece_tphone2" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="rece_tphone3" size=4 class="input" maxlength="4"  Onlynum="true">
								</td>
							</tr>
							<tr>
								<td class="table_tit2">휴대전화번호 *</td>
								<td>
									<select name="rece_hphone" class="input">
									<?
									foreach($_hp_array AS $val){
									?>
									<option value="<?=$val[0]?>"><?=$val[1]?></option>
									<? } ?>
									</select> -
									<input type=text name="rece_hphone2" size=4 class="input" maxlength="4"  Onlynum="true"> -
									<input type=text name="rece_hphone3" size=4 class="input" maxlength="4"  Onlynum="true">
								</td>
							</tr>
							<tr>
								<td class="table_tit2">주소 *</td>
								<td>
									<div class="add_select">
										<label for="adchg_Y"><input type="radio" name="address_change" id="adchg_Y" onclick="basic_del_chk('Y')" value="Y">기본배송지  <input type="button" onclick="del_chg()" value="배송지변경" class="btn_s"></label>				
										<label for="adchg_C"><input type="radio" name="address_change" id="adchg_C" onclick="basic_del_chk2('C')" value="C">최근배송지</label>
										<label for="adchg_D"><input type="radio" name="address_change" id="adchg_D" onclick="basic_del_chk2('D')" value="D">직접입력</label>
									</div>
									<input type=text name="rece_post" size=7 class="input"><input type="buttom" value="우편번호 찾기" onClick="javascript:zipSearch('rece_');" class="btn_shop1"><br/>
									<input type=text name="rece_address1" size=70 class="input mar">
									<input type=text name="rece_address2" size=70 class="input">
								</td>
							</tr>

							<tr>
								<td class="table_tit2">요청사항</td>
								<td><textarea name="demand" cols="70" rows="5" class="input"></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:30px;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="shop_tit">결제수단</td>
				</tr>
				<tr>
					<td colspan="2">

						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="pay_table">
							<? if($oper_info['coupon_use'] == "Y"){ ?>
							<tr>
								<td class="table_tit2">쿠폰사용</td>
								<td>
									<input type="text" name="coupon_use" style="text-align:right" size="15" class="input" readonly>&nbsp;원&nbsp;
									<input type="button" value="쿠폰조회 및 적용" onClick="javascript:couponUse();" class="btn_shop2">
								</td>
							</tr>
							<? } ?>
							<?
							if($oper_info['reserve_use'] == "Y"){
							
								if($oper_info['unLimited'] == "Y"){
									$onchange = "";
									$using_msg = "<font color=red>제한없음</font>";
								} else {
									$onchange = "onchange='reserveUse(this.form);' ";
									$using_msg = "적립금은 ".number_format($oper_info['reserve_min'])."원부터 ".number_format($oper_info['reserve_max'])."원까지 사용이 가능합니다.";
								}
							?>
							<tr>
								<td class="table_tit2">적립금 사용</td>
								<td>
									<input type="text" name="reserve_use" style="text-align:right"  size="15" class="input" <?=$onchange?>>&nbsp;원
									<?php
									/*
									작업자명	: 이상민
									작업일시	: 2020-04-09
									작업내용	: 적립금 일괄사용버튼 추가
									*/
									?>
									&nbsp;&nbsp;<label><input type="checkbox" id="use_max_reserve"> ※ 체크하면 사용가능한 최대 적립금이 적용됩니다.</label>
									<br>
									<span class="form_sub">보유적립금 : <?=number_format($mem_info['reserve'])?> 원</span> (<?=$using_msg?>)
								</td>
							</tr>
							<? } ?>
							<tr>
								<td width=20% class="table_tit2">일반결제</td>
								<td width="80%">
									<input type="radio" name="pay_method" value="" style="display:none">
									<?php
									$pay_method = explode("/",$oper_info['pay_method']);
									for($ii=0; $ii<count($pay_method)-1; $ii++){

										$pay_title = pay_method($pay_method[$ii]);

										if($ii == 0) $checked = "checked";
										else $checked = "";

										if($oper_info['pay_escrow'] == "Y" && ($pay_method[$ii] == "PN" || $pay_method[$ii] == "PV")) $pay_title .= " (에스크로)";

										echo "<input type='radio' name='pay_method' id='pay_method' value='$pay_method[$ii]' $checked onclick='tax_check();'>$pay_title &nbsp;";

									}
									?>
								</td>
							</tr>
							<?php
							if(!strcmp($site_info['easypay_use'], "Y")) {
							?>
							 <tr>
								<td class="table_tit2">간편결제</td>
								<td>
									<?php
										$easy_pay_method = explode("/",$oper_info['easy_pay_method']);
										for($ii=0; $ii<count($easy_pay_method); $ii++){
											$easy_pay_title = pay_method($easy_pay_method[$ii]);

											echo "<input type='radio' name='pay_method' id='pay_method' value='$easy_pay_method[$ii]' $checked onclick='tax_check();'>$easy_pay_title &nbsp;";
										}
									?>
								</td>
							</tr>
							<?php } ?>

							<? if(!strcmp($oper_info['tax_use'], "Y")) { ?>
							<tr id="tax_view">
								<td class="table_tit2">증빙서류</td>
								<td class="checkbox_area">
									<label for="tax_N"><input type="radio" name="tax_type" id="tax_N" value="N" checked onClick="qclick('');">발행안함</label>
									<label for="tax_T"><input type="radio" name="tax_type" id="tax_T" value="T" onClick="qclick('01');">세금계산서 신청</label>
									<label for="tax_C"><input type="radio" name="tax_type" id="tax_C" value="C" onClick="qclick('02');">현금영수증 신청</label>

									<div id="tax_view_1" style="display:none; margin-top:10px;">
										<table id="tax01" style="display:none;" bgcolor="0" width="100%" border="0" cellspacing="1" cellpadding="1" class="pay_table">
											<tr>
												<td class="table_tit2">&nbsp; 사업자 번호</td>
												<td colspan="3" bgcolor="#FFFFFF">
													<input type="text" id="com_num" name="com_num" value="<?=$mem_info['com_num']?>" class="input" size="20">
												</td>
											</tr>
											<tr>
												<td width="20%" class="table_tit2">&nbsp; 상 호</td>
												<td width="30%" bgcolor="#FFFFFF">
													<input type="text" id="com_name" name="com_name" value="<?=$mem_info['com_name']?>" class="input">
												</td>
												<td width="20%" class="table_tit2">&nbsp; 대표자</td>
												<td width="30%" bgcolor="#FFFFFF">
													<input type="text" id="com_owner" name="com_owner" value="<?=$mem_info['com_owner']?>" class="input">
												</td>
											</tr>
											<tr>
												<td class="table_tit2">&nbsp; 사업장 소재지</td>
												<td colspan="3" bgcolor="#FFFFFF">
													<input type=text name="com_post" size=7 class="input"><input type="buttom" value="우편번호 찾기" onClick="javascript:zipSearch('com_');" class="btn_shop1"><br/>

													<input type="text" id="com_address1" name="com_address1" size="70" class="input mar">
													<input type="text" id="com_address2" name="com_address2" size="70" class="input">

												</td>
											</tr>
											<tr>
												<td class="table_tit2">&nbsp; 업 태</td><td bgcolor="#FFFFFF"><input type="text" id="com_kind" name="com_kind" value="<?=$mem_info['com_kind']?>" class="input"></td>
												<td class="table_tit2">&nbsp; 종 목</td><td bgcolor="#FFFFFF"><input type="text" id="com_class" name="com_class" value="<?=$mem_info['com_class']?>" class="input"></td>
											</tr>
											<tr>
												<td class="table_tit2">&nbsp; 전화번호</td><td bgcolor="#FFFFFF"><input type="text" id="com_tel" name="com_tel" value="<?=$mem_info['tphone']?>" class="input"></td>
												<td class="table_tit2">&nbsp; 이메일</td><td bgcolor="#FFFFFF"><input type="text" id="com_email" name="com_email" value="<?=$mem_info['email']?>" class="input"></td>
											</tr>
										</table>

										<table id="tax02" style="display:none;" bgcolor="" width="90%" border="0" cellspacing="1" cellpadding="1" class="pay_table">
											<tr>
												<td width="20%" class="table_tit2">&nbsp; 발급사유</td>
												<td width="80%" bgcolor="#FFFFFF">
													<input type="radio" id="cash_type" name="cash_type" value="C" onClick="qclick2('01');"> 사업자 지출증빙용
													<input type="radio" id="cash_type" name="cash_type" value="P" onClick="qclick2('02');"> 개인소득 공제용
												</td>
											</tr>
											<tr id="cash_info_id" style="display:none">
												<td class="table_tit2" >&nbsp; 신청정보</td>
												<td bgcolor="#FFFFFF">
													<div id="cash_info01"></div>
													<!-- <div id="cash_info02" style="padding:3px;"></div> -->
												</td>
											</tr>
											<tr id="cash_info_id02" style="display:none">
												<td class="table_tit2" >&nbsp; <span id="cash_info_id02_tit"></span></td>
												<td bgcolor="#FFFFFF">
													<div id="cash_info02" style="padding:3px;"></div>
												</td>
											</tr>
											<tr>
												<td class="table_tit2">&nbsp; 신청자명</td>
												<td bgcolor="#FFFFFF">
													<input type="text" id="cash_name" name="cash_name" value="" class="input">
												</td>
											</tr>
										</table>										
									</div>
								</td>
							</tr>
							<? } ?>
						</table>
					</td>
				</tr>
				<tr>
					<td height="60" align="center" valign="bottom">
						<input type="submit" value="주문하기" class="btn_style2">
						<input type="button" value="장바구니 가기" onClick="location.href='/shop/basket.php'"  class="btn_style1">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>