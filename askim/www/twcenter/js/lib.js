// 가격에 원단위 컴마를 찍는다.
function set_WonComma(price)
{

	if(price != null){
		var pricelen = price.length;
		var ii = pricelen%3;
		var wonprice = price.substring(0,ii);
		for(;ii<pricelen;ii+=3){
			wonprice += "," + price.substring(ii,ii+3);
		}
		if((pricelen%3) == 0)
		wonprice = wonprice.substring(1,wonprice.length);
		return wonprice;
	}

}

// 인풋내 콤마 제거
function removeComma(str) {
	if(typeof(str) == 'string') str = str.replace(/,/g, '');
	return str;
}

function fnValidationCheck(obj,type,msg) {

	if (typeof(obj) == 'undefined') return false;
	var obj_value = obj.val().replace(/(^\s*)|(\s*$)/gi, ''); //앞뒤공백제거

	var Reg_pattern = '';
	if(type == 'S') {
		Reg_pattern = /[\{\}\[\]\/?,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
	} else if(type == 'H') {
		Reg_pattern = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
	}

	if(type == 'N') {
		if(obj_value == '') {
			alert(msg);
			obj.val('');
			try { obj.focus(); } catch (e) { }
			return false;
		}
	} else {

		if (Reg_pattern.test(obj_value) == true) {
			alert(msg);
			obj.val('');
			try { obj.focus(); } catch (e) { }
			return false;
		}
	}

	return true;

}

function fnInputVal(obj, msg) {
	
	if (typeof(obj) == 'undefined') return false;
	var objVal = obj.val().replace(/(^\s*)|(\s*$)/gi, ''); //앞뒤공백제거
	if(objVal == '') {
		alert(msg);
		obj.val('');
		try { obj.focus(); } catch (e) { }
		return false;
	}

	return true;

}

function fnEmailVal(obj, obj2, msg) {

	if (typeof(obj) === 'undefined') return false;
	var objVal  = obj.val().replace(/(^\s*)|(\s*$)/gi, '');

	if (typeof(obj2) === 'undefined') return false;
	var objVal2  = obj2.val().replace(/(^\s*)|(\s*$)/gi, '');

	var EmailType = objVal + "@" + objVal2;
	var email_Reg_pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (email_Reg_pattern.test(EmailType) == false) {
		alert(msg);
		obj.val('');
		return false;
	}

	return true;

}

function fnSpecialVal(obj, msg) {

	if (typeof(obj) == 'undefined') return false;
	var objVal  = obj.val().replace(/(^\s*)|(\s*$)/gi, '');

	var special_pattern = /[`~!@#$%^&*|\\\'\";:\/?]/gi;
	if (special_pattern.test(objVal) == true) {
		alert(msg);
		obj.val('');
		try { obj.focus(); } catch (e) { }
		return false;
	}

	return true;

}

function fnHangulVal(obj, msg) {

	if (typeof(obj) == 'undefined') return false;
	var objVal  = obj.val().replace(/(^\s*)|(\s*$)/gi, '');

	var hangul_pattern = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
	if (hangul_pattern.test(objVal) == true) {
		alert(msg);
		obj.val('');
		try { obj.focus(); } catch (e) { }
		return false;
	}

	return true;

}

function checkAll(v){
	
	if($("#chkAll").prop("checked")) {
		$("." + v).prop("checked", true);
	} else {
		$("." + v).prop("checked", false);
	}
}

function isYYYYMMDD(y, m, d) {
	switch (m) {
		case 2:     // 2월의 경우
			if (d > 29) return false;
			if (d == 29) {
			     // 2월 29의 경우 당해가 윤년인지를 확인
			        if ((y % 4 != 0) || (y % 100 == 0) && (y % 400 != 0))
			                return false;
			}
		break;
		case 4:     // 작은 달의 경우
		case 6:
		case 9:
		case 11:
		if (d == 31) return false;
	}

	// 큰 달의 경우
	return true;
}
function isNumeric(s) {
	for (i=0; i<s.length; i++) {
		c = s.substr(i, 1);
		if (c < "0" || c > "9") return false;
	}
	return true;
}
function isLeapYear(y) {
	if (y < 100)
		y = y + 1900;
	if ( (y % 4 == 0) && (y % 100 != 0) || (y % 400 == 0) ) {
		return true;
	} else {
		return false;
	}
}
function getNumberOfDate(yy, mm) {
	month = new Array(29,31,28,31,30,31,30,31,31,30,31,30,31);
	if (mm == 2 && isLeapYear(yy)) mm = 0;
	return month[mm];
}
function isSSN(s1, s2) {
	n = 2;
	sum = 0;
	for (i=0; i<s1.length; i++)
		sum += parseInt(s1.substr(i, 1)) * n++;
	for (i=0; i<s2.length-1; i++) {
		sum += parseInt(s2.substr(i, 1)) * n++;
		if (n == 10) n = 2;
	}
	c = 11 - sum % 11;
	if (c == 11) c = 1;
	if (c == 10) c = 0;
	if (c != parseInt(s2.substr(6, 1))) return false;
	else return true;
}

// 주민 등록 번호 체크
function check_ResidentNO_old(str_f_num, str_l_num)
{

	var i3=0
	for (var i=0;i<str_f_num.length;i++)
	{
		var ch1 = str_f_num.substring(i,i+1);
		if (ch1<'0' || ch1>'9') { i3=i3+1 }
	}
	if ((str_f_num == '') || ( i3 != 0 ))
	{
		return (false);
	}

	var i4=0
	for (var i=0;i<str_l_num.length;i++)
	{
		var ch1 = str_l_num.substring(i,i+1);
		if (ch1<'0' || ch1>'9') { i4=i4+1 }
	}
	if ((str_l_num == '') || ( i4 != 0 ))
	{
		return (false);
	}

	if(str_f_num.substring(0,1) < 0)
	{
		return (false);
	}

	if(str_l_num.substring(0,1) > 2)
	{
		return (false);
	}

	if((str_f_num.length > 7) || (str_l_num.length > 8))
	{
		return (false);
	}

	if ((str_f_num == '72') || ( str_l_num == '18'))
	{
		return (false);
	}

	var f1=str_f_num.substring(0,1)
	var f2=str_f_num.substring(1,2)
	var f3=str_f_num.substring(2,3)
	var f4=str_f_num.substring(3,4)
	var f5=str_f_num.substring(4,5)
	var f6=str_f_num.substring(5,6)
	var hap=f1*2+f2*3+f3*4+f4*5+f5*6+f6*7
	var l1=str_l_num.substring(0,1)
	var l2=str_l_num.substring(1,2)
	var l3=str_l_num.substring(2,3)
	var l4=str_l_num.substring(3,4)
	var l5=str_l_num.substring(4,5)
	var l6=str_l_num.substring(5,6)
	var l7=str_l_num.substring(6,7)
	hap=hap+l1*8+l2*9+l3*2+l4*3+l5*4+l6*5
	hap=hap%11
	hap=11-hap
	hap=hap%10
	if (hap != l7)
	{
		return (false);
	}

	return true;

}


/* 게시판 목록 체크박스 */

// 체크박스 전체선택
function selectAllBbs(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

// 체크박스 선택해제
function selectCancelBbs(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].idx != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverseBbs(form){
	if(form.select_tmp.checked){
		selectAllBbs();
	}else{
		selectCancelBbs();
	}
}

// 체크박스 선택리스트
function selectValueBbs(){
	var i;
	var selbbs = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selbbs = selbbs + document.forms[i].idx.value + "|";
				}
			}
	}
	return selbbs;
}

//선택게시물 삭제
function delBbs(purl, param){

	selbbs = selectValueBbs();

	if(selbbs == ""){
		alert("삭제할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 게시물을 정말 삭제하시겠습니까?")){
			document.location = purl + "?ptype=save&mode=delbbs&selbbs=" + selbbs + "&" + param;
		}
	}
}

//게시물이동
function moveBbs(code){
	selbbs = selectValueBbs();
	if(selbbs == ""){
		alert("이동할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "/twcenter/bbs/move.php?code=" + code + "&selbbs=" + selbbs;
		window.open(uri,"moveBbs","width=400,height=150");
	}
}

// 게시물복사
function copyBbs(code){
	selbbs = selectValueBbs();
	if(selbbs == ""){
		alert("복사할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "/twcenter/bbs/copy.php?code=" + code + "&selbbs=" + selbbs;
		window.open(uri,"copyBbs","width=400,height=150");
	}
}

// 게시물 순서변경
function orderBbs(code) {
	var uri = "/twcenter/bbs/order.php?code=" + code;
	window.open(uri,"orderBbs","width=600,height=350,resizable=yes,scrollbars=yes");
}

// 상품 카테고리 레이어
function disableLay(getno){
	if(document.all.displayer != null){
		if(document.all.displayer.length==null) document.all.displayer.style.display='none';
		else document.all.displayer[getno].style.display='none';
	}
}

function displayLay(getno){

	if(document.all.displayer != null){
	  for(i=0; i<document.all.displayer.length; i++){
	          document.all.displayer[i].style.display='none';
	  }
	  if(document.all.displayer.length==null) document.all.displayer.style.display='block';
	  else document.all.displayer[getno].style.display='block';
	}

}

function check_all(f)
{
	var chk = document.getElementsByName("checks[]");
	for (i=0; i<chk.length; i++)
	chk[i].checked = f.chkall.checked;
}

function check_val(act)
{
	var f = document.pgfrm;
	var act_url = "/twcenter/manage/as/as_save.php";

	if (act == "update") // 선택수정
	{
		var sel=[];

		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');
		f.idx.value = check_id;
		f.action = act_url;
		str = "수정";
	}
	else if (act == "delete") // 선택삭제
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url;
		str = "삭제";
	}
	else if (act == "copy") // 선택복사
	{
		var sel=[];

		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');
		f.idx.value = check_id;
		f.action = act_url;
		str = "복사";
	}
	else
		return;


	var chk = document.getElementsByName("checks[]");
	var bchk = false;

	for (i=0; i<chk.length; i++)
	{
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk) 
	{
		alert(str + "할 AS리스트를 선택하세요.");
		return;
	}

	if (act == "delete")
	{
		if (!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
			return;
	}

	f.mode.value = act;
	f.submit();
}

function check_val2(num)
{
	var chk = document.getElementsByName("checks[]");
	chk[num].checked = true;
}

function check_down(act)
{
	var f = document.pgfrm;
	var act_url = "/twcenter/manage/as/sel_download.php";
	var act_url2 = "/twcenter/manage/as/sel_print.php";

	if (act == "download")
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url;
		str = "다운로드";
	}
	else if (act == "print")
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url2;
		str = "송장출력";
	}
	else
		return;


	var chk = document.getElementsByName("checks[]");
	var bchk = false;

	for (i=0; i<chk.length; i++)
	{
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk) 
	{
		alert(str + "할 AS리스트를 선택하세요.");
		return;
	}

	f.submit();

}

function servicechg(idx,num)
{
	var url = "as_service.php?idx="+idx+"&srv_idx="+num;
	var win = "servicechg";
	
	window.open(url, win, "fullscreen=0,toolbar=0,scrollbars=0,location=0,status=0,menubar=0,resizable=0,width=400,height=300,top=0,left=0");
}

function asUpdate(check_id)
{
	var f = document.pgfrm;
	var act_url = "/twcenter/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "update";
	f.date_check.value = "date_check";
	f.action = act_url;
	f.submit();
}

function asDefer(check_id)
{
	var f = document.pgfrm;
	var act_url = "/twcenter/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "update";
	f.date_check.value = "date_defer";
	f.action = act_url;
	f.submit();
}

function asDelete(check_id)
{
	var f = document.pgfrm;
	var act_url = "/twcenter/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "delete";
	f.action = act_url;

	q = confirm("선택한 자료를 정말 삭제하시겠습니까?")
	if (q == true)
	{
		f.submit();
	}
	else
	{
		f.mode.value = "no_delete";
		return false;
	}
	
}

function btnchange(href)
{
	if(confirm("처리결과를 변경하시겠습니까?")) 
		document.location.href = href;
}

function csv_submit()
{
	var f = document.csvfrm;
	f.action = "./as_file_update.php";
	f.submit();
}

function csv_submit_out()
{
	var f = document.csvfrm_out;
	f.action = "./as_file_update_out.php";
	f.submit();
}

function stats_submit(act) 
{
	var f = document.searchForm;
	f.action = act;
	f.submit();
}

function sms_submit(href) 
{
	if(confirm("문자를 발송하시겠습니까?")) 
		document.location.href = href;
}


function sms_submit_t(href,date_idx,dilnum_idx) 
{

	//출고송장 비어있는지 확인
	var dilnum = document.getElementById(dilnum_idx).value;
	//alert(dilnum);
	if(dilnum==""){
		alert("출고송장이 비어있습니다.");
		return false;
	}
	

	var date = document.getElementById(date_idx).value;

	
	//출고일 날짜 형식 
	var outdate = date;
	var outdate_split = outdate.split('-');

	var outdate_split_1=outdate_split[0];
	var outdate_split_2=outdate_split[1];
	var outdate_split_3=outdate_split[2];

	if(outdate.length=='10'){
		if(outdate_split_1.length=='4'){

			if(outdate_split_2.length=='2'){

				if(outdate_split_2<13 && outdate_split_2>0){

					if(outdate_split_3.length=='2'){
						if(outdate_split_3<32 && outdate_split_3>0){
							if(confirm("문자를 발송하시겠습니까?")) 
								document.location.href = href;
						}else{
							alert("출고일이 잘못 표기되었습니다");
							return false;
						}
					}else{
						alert("출고일이 잘못 표기되었습니다");
						return false;
					}

				}else{
					alert("출고일이 잘못 표기되었습니다");
					return false;
				}

			}else{
				alert("출고일이 잘못 표기되었습니다");
				return false;
			}

		}else{
		  alert("출고일이 잘못 표기되었습니다");
		  return false;
		}
	}else if(outdate.length=='0'){
		alert("출고일이 비어있습니다.");
		return false;
	}else{
	  alert("출고일이 잘못 표기되었습니다");
	  return false;
	}
}

/* 쇼핑몰 > 카테고리설정 */
function moveCode(catcode,depthno,prior,menucode){
	var params = "mode=update&catcode="+catcode+"&depthno="+depthno+"&prior="+prior+"&menucode="+menucode;
	$.get("/twcenter/manage/product/ajax_prd_category.php?" + params, function(data){
		var href = "/twcenter/manage/product/ajax_prd_category.php?" + params;
		$("#categoryInput").load(href);
	});
	$("span").removeClass("clickover");
}

function moveCode2(catcode,menucode){
	var href = "/twcenter/manage/product/ajax_prd_category.php?catcode="+catcode+"&menucode="+menucode;
	$("#categoryInput").load(href);
}

function moveCategory(mode,posi,catcode,depthno,menucode){

	if(catcode == '00000000'){
		alert('최상위 카테고리는 정렬을 할수없습니다.');
		return false;
	}

	$.ajax({
		type:"post"
		, url: "/twcenter/manage/product/category_save.php"
		, data : {"mode":mode,"posi":posi,"catcode":catcode,"depthno":depthno}
		, cache: false
//		, processData: false
//		, contentType: false
		, success: function(data) {
			var result = data.split('|');
			if(result[0] == "ok"){
				var href = "/twcenter/manage/product/category_list.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3]+"&menucode="+menucode;
				//$("#subCateList").load(href);
				alert("정렬 순서가 변경되었습니다.");
				$("#categoryList").load(href);
			} else if(result[0] == 'error' && result[1] != '') {
				alert(result[1]);
			}
		}
		, error: function(){
		}
	});
}


/*** 상품관리 > 카테고리설정 */
function SmoveCode(catcode,depthno,prior,menucode,mode){
	var params = "mode=update&catcode="+catcode+"&depthno="+depthno+"&prior="+prior+"&menucode="+menucode;
	$.get("/twcenter/manage/product2/ajax_prd_category.php?" + params, function(data){
		var href = "/twcenter/manage/product2/ajax_prd_category.php?" + params;
		$("#categoryInput").load(href);
	});
	$("span").removeClass("clickover");
}

function SmoveCode2(catcode,menucode){
	var href = "/twcenter/manage/product2/ajax_prd_category.php?catcode="+catcode+"&menucode="+menucode;
	$("#categoryInput").load(href);
}

function SmoveCategory(mode,posi,catcode,depthno,menucode){

	$.ajax({
		type:"post"
		, url: "/twcenter/manage/product2/cat_save.php"
		, data : {"mode":mode,"posi":posi,"catcode":catcode,"depthno":depthno}
		, cache: false
		, success: function(data) {
			var result = data.split('|');
			if(result[0] == "ok"){
				var href = "/twcenter/manage/product2/ajax_sub_category.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3]+"&menucode="+menucode;
				//$("#subCateList").load(href);
				alert("정렬 순서가 변경되었습니다.");
				$("#categoryList").load(href);
			}
		}
		, error: function(){
		}
	});

}


function brandCode(idx,menucode){
	var params = "mode=update&idx="+idx+"&menucode="+menucode;
	$.get("/twcenter/manage/product/prd_brand.php?" + params, function(data){
		var href = "/twcenter/manage/product/ajax_brd_category.php?" + params;
		$("#categoryInput").load(href);
	});
	$("span").removeClass("clickover");
}

function brandCode2(menucode){
	var href = "/twcenter/manage/product/ajax_brd_category.php?menucode="+menucode;
	$("#categoryInput").load(href);
}

function BrandmoveCategory(mode,posi,idx,menucode){

	var params = "mode="+mode+"&posi="+posi+"&idx="+idx+"&menucode="+menucode;
	$.get("/twcenter/manage/product/brand_save.php?" + params, function(data){
		if(data){
			var result = data.split("|");
			var href = "/twcenter/manage/product/brand_list.php?mode="+result[1]+"&idx="+result[2]+"&menucode="+menucode;
			$("#categoryShow").load(href);
		}
	});

}

function getDateStr(DateStr){

	function dateLength(date){
		date = date + '';
		return date.length < 2 ? '0' + date : date;
	}
	return DateStr.getFullYear() + '-' + dateLength(DateStr.getMonth() + 1) + '-' + dateLength(DateStr.getDate());
}

$(function(){

	$(".btn_period, .btn_period2").each(function () {

		$(this).on("click", function () {

			$(this).addClass("period_2");
			$(this).siblings().removeClass("period_2");
			$(this).addClass("period_4");
			$(this).siblings().removeClass("period_4");

			var get_frm_id      = $("form").prop("id");
			var btn_id = $(this).attr("id");

			var num_ck  = btn_id.replace(/[^0-9]/g,"");
			var num_ck2 = btn_id.replace(/[0-9]/g,"");

			if(num_ck > 0)  var eqnum = num_ck;
			else            var eqnum = 0;

			var data_property_s  = $("#" + get_frm_id + " *[data-date-s]");
			var data_property_e  = $("#" + get_frm_id + " *[data-date-e]");

			var fileter_s       = data_property_s.filter(':eq("'+eqnum+'")');
			var target_s        = fileter_s.attr('data-date-s');

			var fileter_e       = data_property_e.filter(':eq("'+eqnum+'")');
			var target_e        = fileter_e.attr('data-date-e');

			var frmel   = this.form;
			var frmObj  = $(frmel);

			if(num_ck > 0) {
				var setperiod2 = num_ck2+"1";
				if(typeof frmel.setperiod2 === "undefined") frmObj.prepend('<input type="hidden" name="setperiod2" value="' + setperiod2 + '">');
				frmObj.find("input[name=setperiod2]").val(setperiod2);
			} else {
				var setperiod = num_ck2;
				if(typeof frmel.setperiod === "undefined") frmObj.prepend('<input type="hidden" name="setperiod" value="' + setperiod + '">');
				frmObj.find("input[name=setperiod]").val(setperiod);
			}

			var d = new Date();
			var todate = getDateStr(d);
			var today, yesday, weekday, fifday, monthday, yearday;

			if (btn_id == 'today' || btn_id == 'today1') {
				today = todate;
				$("#"+target_s).val(today);
				$("#"+target_e).val(todate);

			} else if (btn_id == 'yesday' || btn_id == 'yesday1') {
				var yesterday = d.getDate();
				d.setDate(yesterday - 1);
				yesday = getDateStr(d);
				$("#"+target_s).val(yesday);
				$("#"+target_e).val(todate);

			} else if (btn_id == 'week' || btn_id == 'week1') {
				var lastweek = d.getDate();
				d.setDate(lastweek - 7);
				weekday = getDateStr(d);
				$("#"+target_s).val(weekday);
				$("#"+target_e).val(todate);

			} else if (btn_id == 'fifteen' || btn_id == 'fifteen1') {
				var lastfif = d.getDate();
				d.setDate(lastfif - 15);
				fifday = getDateStr(d);
				$("#"+target_s).val(fifday);
				$("#"+target_e).val(todate);

			} else if (btn_id == 'month' || btn_id == 'month1') {
				var lastmonth = d.getMonth();
				d.setMonth(lastmonth - 1);
				monthday = getDateStr(d);
				$("#"+target_s).val(monthday);
				$("#"+target_e).val(todate);
			} else if (btn_id == 'thmonth' || btn_id == 'thmonth1') {
				var lastmonth = d.getMonth();
				d.setMonth(lastmonth - 3);
				monthday = getDateStr(d);
				$("#"+target_s).val(monthday);
				$("#"+target_e).val(todate);


			} else if (btn_id == 'year' || btn_id == 'year1') {
				var lastyear = d.getFullYear();
				d.setYear(lastyear - 1);
				yearday = getDateStr(d);
				$("#"+target_s).val(yearday);
				$("#"+target_e).val(todate);

			} else if (btn_id == 'all') {
				$("#"+target_s).val('');
				$("#"+target_e).val('');
			}

		});
	});

});

//좌측메뉴 관련 제이쿼리
$(function () {
	if($.cookie("leftMenu") == "close"){
		$('#contents_wrap').addClass('navi_close');
	} else {
		$('#contents_wrap').removeClass('navi_close');
	}
});

function navigation_btn() {
	$('#contents_wrap').toggleClass('navi_close');
	if ($('#contents_wrap').hasClass('navi_close')) {
		$.cookie('leftMenu', 'close', { expires: 1, path: '/', domain: location.host, secure: false });
	}
	else {
		$.cookie('leftMenu', 'open', { expires: 1, path: '/', domain: location.host, secure: false });
	}
}

// 실시간 입금내역 검색
var manual_match = function(sort_data) {
	
	var params = "";
	params += "page=" + $("[name=page]").val();
	params += "&s_status=" + $("[name=s_status]").val();
	params += "&s_bank=" + $("[name=s_bank]").val();
	params += "&srh_prev=" + $("[name=srh_prev]").val();
	params += "&srh_next=" + $("[name=srh_next]").val();
	params += "&searchopt=" + $("[name=searchopt]").val();
	params += "&searchkey=" + $("[name=searchkey]").val();
	if(sort_data != '') params += "&sorter="+sort_data;
	else				params += "&sorter="+$("[id=searchkey]").val();

	$.ajax({
		type:"post"
		, cache: false
		, async: false
		, url: "/twcenter/manage/product/bankda_ajax_manual_match.php"
		, data: params
		, dataType: "json"
		, success: function(data) {
//			console.log(data);
			var objData = String(JSON.stringify(data));
			var parsedJson = JSON.parse(objData);
			
			$("#manual_match_list").empty();
			
			if(parsedJson.list != null) {

				var len = parsedJson.list.length;
				var html = "";

				for(var i=0; i<len; i++){

					var num					= parsedJson.list[i].num;
					var bkdate				= parsedJson.list[i].bkdate;
					var actnumber			= parsedJson.list[i].actnumber;
					var bkname				= parsedJson.list[i].bkname;
					var bkinput				= parsedJson.list[i].bkinput;
					var bkjukyo				= parsedJson.list[i].bkjukyo;
					var match_result		= parsedJson.list[i].match_result;
					var match_date			= parsedJson.list[i].match_date;
					var orderid				= parsedJson.list[i].orderid;
					var mresult_code		= parsedJson.list[i].mresult_code;

					var page                = parsedJson.list[i].page;
					var lists               = parsedJson.list[i].lists;
					var page_count          = parsedJson.list[i].page_count;
					var total               = parsedJson.list[i].total;

					if(parsedJson.list[i].sorter != undefined){
						var sorter			= parsedJson.list[i].sorter;
					}
					
					$("[id=sorter]").val(sorter);

					html += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					html += '	<tr>';
					html += '		<td width="10%" align="center" class="t_line" height="38">' + num +'</td>';
					html += '		<td width="18%" align="center" class="t_line">' + bkdate + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + actnumber + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + bkinput + '원</td>';
					html += '		<td width="18%" align="center" class="t_line">' + bkjukyo + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + match_result + '</td>';
					html += '	</tr>';
					html += '</table>';
					
					$("#manual_match_list").html(html);
				}

				pagenavi(page, lists, total, page_count, sorter);

			} else {

			}
		
		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});

};


var pagenavi = function(page, lists, total, page_count, sorter) {

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
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList('page', '1', '" + sorter + "')\">처음</a></li>";
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList('page', '" + ppage + "', '" + sorter + "')\">이전</a></li>";

		for(i = spage; i <= epage; i++){
			if(page == i) page_navi += "<li><a href=\"javascript:;\" class=\"active\">" + i + "</a></li>";
			else		  page_navi += "<li><a href=\"javascript:;\" onClick=\"searchList('page', '" + i + "', '" + sorter + "')\">" + i + "</a></li>";
		}

		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList('page', '" + npage + "', '" + sorter + "')\">다음</a></li>";
		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList('page', '" + page_count + "', '" + sorter + "')\">맨끝</a></li>";
		page_navi += "</ul></div>";
	}

	$("#paging").html(page_navi);
};

var searchList = function(stype, value, sorter) {

	var frm = document.frm;
	if(stype == "page"){
		$("[name=page]").val(value);
	}
	if(sorter != ""){
		$("[id=sorter]").val(sorter);
	}

	manual_match(sorter);
	return false;
};

var sortBy = function(sort) {

	var sorting = sort.split(".");
	var usort = "";

	(sorting[1] == "ASC") ? usort = "DESC" : usort = "ASC";
	var sort_result = sorting[0]+"."+usort;
	document.getElementById(sort).id = sort_result;

	if(usort == "DESC") {
		$("." + sorting[0]).text('▲');
	} else {
		$("." + sorting[0]).text('▼');
	}

	manual_match(sort_result);

};

// 입금대기(주문접수) 검색
var order_match = function(sort_data) {

	var params = "";
	params += "page=" + $("[name=page]").val();
	params += "&sdate=" + $("[name=sdate]").val();
	params += "&edate=" + $("[name=edate]").val();
	params += "&searchopt2=" + $("[name=searchopt2]").val();
	params += "&searchkey2=" + $("[name=searchkey2]").val();
	params += "&price1=" + $("[name=price1]").val();
	params += "&price2=" + $("[name=price2]").val();
	if(sort_data != '') params += "&sorter2="+sort_data;
	else				params += "&sorter2="+$("[id=searchkey2]").val();
	//console.log(params);	

	$.ajax({
		type:"post"
		, cache: false
		, async: false
		, url: "/twcenter/manage/product/bankda_ajax_order_match.php"
		, data: params
		, dataType: "json"
		, success: function(data) {
	//		console.log(data);
			var objData = String(JSON.stringify(data));
			var parsedJson = JSON.parse(objData);
			
			$("#order_match_list").empty();
			
			if(parsedJson.list != null) {

				var len = parsedJson.list.length;
				var html = "";

				for(var i=0; i<len; i++){

					var ordnum				= parsedJson.list[i].ordnum;
					var order_date			= parsedJson.list[i].order_date;
					var orderid				= parsedJson.list[i].orderid;
					var total_price			= parsedJson.list[i].total_price;
					var account_name		= parsedJson.list[i].account_name;

					var page                = parsedJson.list[i].page;
					var lists               = parsedJson.list[i].lists;
					var page_count          = parsedJson.list[i].page_count;
					var total               = parsedJson.list[i].total;

					if(parsedJson.list[i].sorter != undefined){
						var sorter			= parsedJson.list[i].sorter;
					}
					
					$("[id=sorter2]").val(sorter);

					html += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					html += '	<tr>';
					html += '		<td width="10%" align="center" class="t_line" height="38">' + ordnum + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + order_date + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + orderid + '</td>';
					html += '		<td width="18%" align="center" class="t_line">' + total_price + '원</td>';
					html += '		<td width="18%" align="center" class="t_line">' + account_name + '</td>';
					html += '		<td width="18%" align="center" class="t_line"></td>';
					html += '	</tr>';
					html += '</table>';
					
					$("#order_match_list").html(html);
				}

				pagenavi2(page, lists, total, page_count, sorter2);

			} else {

			}
		
		}
		,error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});

};


var pagenavi2 = function(page, lists, total, page_count, sorter) {

	var page_navi = "";
	var spage;
	var epage;
	var tmp;
	var ppage;
	var npage;

	if(page_count == 0){ $("#paging2").html(''); return; }

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
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList2('page', '1', '" + sorter + "')\">처음</a></li>";
		page_navi += "  <li><a href=\"javascript:;\" onClick=\"searchList2('page', '" + ppage + "', '" + sorter + "')\">이전</a></li>";

		for(i = spage; i <= epage; i++){
			if(page == i) page_navi += "<li><a href=\"javascript:;\" class=\"active\">" + i + "</a></li>";
			else		  page_navi += "<li><a href=\"javascript:;\" onClick=\"searchList2('page', '" + i + "', '" + sorter + "')\">" + i + "</a></li>";
		}

		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList2('page', '" + npage + "', '" + sorter + "')\">다음</a></li>";
		page_navi += "  <li><li><a href=\"javascript:;\" onClick=\"searchList2('page', '" + page_count + "', '" + sorter + "')\">맨끝</a></li>";
		page_navi += "</ul></div>";
	}

	$("#paging2").html(page_navi);
};

var searchList2 = function(stype, value, sorter) {

	var frm = document.frm;
	if(stype == "page"){
		$("[name=page]").val(value);
	}
	if(sorter != ""){
		$("[id=sorter2]").val(sorter);
	}

	order_match(sorter);
	return false;
};

// 실시간 입금조회 체크
function AccountLoad() {

	if(bankda_service != 'Y') {
		alert("실시간 입금조회 서비스신청이 필요합니다.");
		return false;
	}

	var _params = "";
	_params += "sdate=";
	_params += "&edate=";

	$.ajax({
		type: 'POST'
		,url: '/twcenter/manage/product/bankda_transaction_ajaxload.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		document.location.reload();
	}).fail(function(request, status, error){
	});
	
}


//채용정보 게시물 일괄변경
function update(code){
	selbbs = selectValueBbs();
	var url = "/twcenter/bbs/list_all_up.php?code="+code+"&selbbs="+selbbs;
	
	if(selbbs == ""){
		alert("변경할 게시물을 선택하세요.");
		return false;
	}else{
		window.open(url,"updateBbs","width=300,height=150,resizable=yes,scrollbars=yes");
	}
}


//온라인 예약 게시물 일괄변경
function update2(code){
	selbbs = selectValueBbs();
	var url = "/twcenter/bbs/list_all_up2.php?code="+code+"&selbbs="+selbbs;
	
	if(selbbs == ""){
		alert("변경할 게시물을 선택하세요.");
		return false;
	}else{
		window.open(url,"updateBbs","width=300,height=150,resizable=yes,scrollbars=yes");
	}
}

//견적문의 게시물 일괄변경
function update3(code){
	selbbs = selectValueBbs();
	var url = "/twcenter/bbs/list_all_up3.php?code="+code+"&selbbs="+selbbs;
	
	if(selbbs == ""){
		alert("변경할 게시물을 선택하세요.");
		return false;
	}else{
		window.open(url,"updateBbs","width=300,height=150,resizable=yes,scrollbars=yes");
	}
}
function number_format(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).on("keyup keydown blur", ".numCk", function(){
	$(this).val($(this).val().replace(/[^0-9]/g,""));
});

$(document).on("keyup keydown blur", ".num_alphaCk", function(){
	$(this).val($(this).val().replace(/[^A-Za-z0-9]/g,""));
});