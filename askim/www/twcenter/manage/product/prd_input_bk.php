<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/prd_info.php";
include "../head.php";
include "../../lib/datepicker_lib.php";

$colspan = ($reserve_use == "Y") ? "" : " colspan=\"3\"";

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code";
$param .= "&special=$special&display=$display&searchopt=$searchopt&searchkey=$searchkey&page=$page&shortpage=$shortpage&$menucodeParam";
//--------------------------------------------------------------------------------------------------
$count = 0;
if($shortpage == "Y") $listpage_url = "prd_shortage.php";
else $listpage_url = "prd_list.php";

$imgpath = WIZHOME_PATH."/data/prdimg";

if(empty($mode)) $mode = "insert";

if($mode == "insert"){

	$catcode01 = $dep_code;
	$catcode02 = $dep_code.$dep2_code;
	$catcode03 = $dep_code.$dep2_code.$dep3_code;
	$catcode04 = $dep_code.$dep2_code.$dep3_code.$dep4_code;
	$prd_stock = 100;
//	$prd_row->stock = 100;

// 상품정보를 가져온다.
}else if($mode == "update"){

	$sql = "SELECT wp.*, wc.idx, wc.catcode 
		FROM wiz_product wp 
		left join wiz_cprelation wc on wp.prdcode = wc.prdcode
		WHERE wp.prdcode = '{$prdcode}'";
	$result = query($sql) or error("sql error");
	$prd_row = sql_fetch_obj($result);

	$relidx = $prd_row->idx;
	$catcode01 = substr($prd_row->catcode,0,2);
	$catcode02 = substr($prd_row->catcode,0,4);
	$catcode03 = substr($prd_row->catcode,0,6);
	$catcode04 = substr($prd_row->catcode,0,8);

	$optuse = $prd_row->opt_use;

	$prd_stock = $prd_row->stock;

}

if($prd_row->timelimit != ""){
	$timelimit = $prd_row->timelimit;

	$timelimit_date = date("Y-m-d", strtotime($timelimit));
	$timelimit_hour = date("H", strtotime($timelimit));
	$timelimit_min = date("i", strtotime($timelimit));
}

// 적립금 사용여부와 적용률을 가저온다.
$sql = "select reserve_use, reserve_buy from wiz_operinfo";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);
$reserve_use = $row->reserve_use;
$reserve_buy = $row->reserve_buy;

function print_position($catcode){

	global $prdcode;

	$catcode1 = substr($catcode,0,2);
	$catcode2 = substr($catcode,0,4);
	$catcode3 = substr($catcode,0,6);
	$catcode4 = substr($catcode,0,8);

	$c_sql = "
	
		SELECT
		
			*
			
		FROM
		
			wiz_category
			
		WHERE
		
			catuse != 'N' AND
			(catcode like '$catcode1%' AND depthno = 1) OR
			(catcode like '$catcode2%' AND depthno = 2) OR
			(catcode like '$catcode3%' AND depthno = 3) OR
			(catcode like '$catcode4%' AND depthno = 4) OR
			(catcode = '$catcode')
			
	";
	$c_result = query($c_sql) or error("sql error");
	$now_position = " &nbsp; Home";
	while($c_row = sql_fetch_obj($c_result)){
		$now_position .= " &gt; $c_row->catname";
	}

	$now_position .= " <a href=prd_save.php?mode=catlist&submode=delete&prdcode=$prdcode&catcode=$catcode><font color=red>[삭제]</font></a>";

	return $now_position;
}

?>
<script language="JavaScript" type="text/javascript">
<!--
	var loding = false;
	var prd_class = new Array();

<?
	$no = 0;
	$sql = "SELECT catcode, catname, depthno FROM wiz_category ORDER BY priorno01, priorno02, priorno03, priorno04 ASC";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	while($row = sql_fetch_obj($result)){

		$code01 = substr($row->catcode,0,2);
		$code02 = substr($row->catcode,0,4);
		$code03 = substr($row->catcode,0,6);
		$code04 = substr($row->catcode,0,8);

		if($row->depthno == 1){ $catcode = $code01; $parent = 0; }
		if($row->depthno == 2){ $catcode = $code02; $parent = $code01; }
		if($row->depthno == 3){ $catcode = $code03; $parent = $code02; }
		if($row->depthno == 4){ $catcode = $code04; $parent = $code03; }
?>

	prd_class[<?=$no?>] = new Array();
	prd_class[<?=$no?>][0] = "<?=$catcode?>";
	prd_class[<?=$no?>][1] = "<?=$row->catname?>";
	prd_class[<?=$no?>][2] = "<?=$parent?>";
	prd_class[<?=$no?>][3] = "<?=$row->depthno?>";

<?
	$no++;
	}
?>
var tno = <?=$total?>;

function setClass01(){

	var arrayClass = eval("document.frm.class01");
	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	arrayClass.options[0]  = new Option(":: 1차분류 ::","");
	arrayClass1.options[0] = new Option(":: 2차분류 ::","");
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='1'){
			arrayClass.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}
}

function changeClass01(){

	var arrayClass = eval("document.frm.class01");
	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass.selectedIndex;
	var selvalue = arrayClass.options[selidx].value;

	arrayClass1.options.length=0;
	arrayClass2.options.length=0;
	arrayClass3.options.length=0;
	arrayClass1.options[0] = new Option(":: 2차분류 ::","");
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='2' && prd_class[no][2]==selvalue){
			arrayClass1.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass02(){

	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass1.selectedIndex;
	var selvalue = arrayClass1.options[selidx].value;

	arrayClass2.options.length=0;
	arrayClass3.options.length=0;
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='3' && prd_class[no][2]==selvalue){
			arrayClass2.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}
}

function changeClass03(){

	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass2.selectedIndex;
	var selvalue = arrayClass2.options[selidx].value;

	arrayClass3.options.length=0;
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='4' && prd_class[no][2]==selvalue){
			arrayClass3.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass04(){
}

//-- 상품카테고리 설정
function setCategory(){

	var arrayClass01 = eval("document.frm.class01");
	var arrayClass02 = eval("document.frm.class02");
	var arrayClass03 = eval("document.frm.class03");
	var arrayClass04 = eval("document.frm.class04");

	for(no=1; no < arrayClass01.length; no++){
		if(arrayClass01.options[no].value == '<?=$catcode01?>'){
			arrayClass01.options[no].selected = true;
			changeClass01();
		}
	}

	for(no=1; no < arrayClass02.length; no++){
		if(arrayClass02.options[no].value == '<?=$catcode02?>'){
			arrayClass02.options[no].selected = true;
			changeClass02();
		}
	}

	for(no=1; no < arrayClass03.length; no++){
		if(arrayClass03.options[no].value == '<?=$catcode03?>'){
			arrayClass03.options[no].selected = true;
			changeClass03();
		}
	}

	for(no=1; no < arrayClass04.length; no++){
		if(arrayClass04.options[no].value == '<?=$catcode04?>')
		arrayClass04.options[no].selected = true;
	}

}

function inputCheck(frm){

	var optuse = '<?=$optuse?>';
	if(optuse=="Y"){
		var id_ch = "#tmp_opt2_";
		for(var i=0;i<50;i++){
			var id_ch_sum = id_ch+i;

			if ( $(id_ch_sum).length > 0 ) {
				if($(id_ch_sum).val()==""){
					alert("옵션값이 비어있습니다.");
					return false;
				}
			}
		}

		var id_ch_b = "#optcode2_";
		for(var i=1;i<50;i++){
			var id_ch_sum_b = id_ch_b+i;

			if ( $(id_ch_sum_b).length > 0 ) {
				if($(id_ch_sum_b).val()==""){
					alert("옵션값이 비어있습니다.");
					return false;
				}
			}
		}
	}

	if(loding == false){
		alert("상품정보를 가져오고 있습니다. 잠시후 재시도 하세요");
		return false;
	}

	if(frm.prdname.value == ""){
		alert("상품명을 입력하세요");
		frm.prdname.focus();
		return false;
	}

	if(frm.sellprice.value == ""){
		alert("판매가를 입력하세요");
		frm.sellprice.focus();
		return false;
	}

	/*
	작업자명	: 이상민
	작업일시	: 2020-03-18
	작업내용	: 내부프로젝트게시판 3/4요청에 의거 상품 등록시 상품분류 필수처리
	*/
	var cat1 = $("select[name='class01']").val();
	var cat2 = $("select[name='class02']").val();
	var cat3 = $("select[name='class03']").val();
	var cat4 = $("select[name='class04']").val();

	if(cat1 == "" || cat1 == null){
		alert("상품분류를 선택하시기 바랍니다.");
		return false;
	}

	content.outputBodyHTML();
	mcontent.outputBodyHTML();

	/* 2021-03-15 완전한 기능이 아니므로 주석처리
	if($("#timesale_use1").prop("checked") == true){
		if($("#timesale_date").val() == ""){
			alert("타임세일 종료일시를 선택하시기 바랍니다.");
			$("#timesale_date").focus();
			return false;
		}
		if($("#timesale_hour").val() == ""){
			alert("타임세일 종료일시를 선택하시기 바랍니다.");
			$("#timesale_hour").focus();
			return false;
		}
		if($("#timesale_min").val() == ""){
			alert("타임세일 종료일시를 선택하시기 바랍니다.");
			$("#timesale_min").focus();
			return false;
		}
	}
	*/

/*
	var optvalue = "";
	var length = frm.optcode_tmp.length;
	for(ii = 0; ii < length; ii++){ optvalue += frm.optcode_tmp.options[ii].value+"^^"; }
	frm.optcode.value = optvalue;
*/
}

//해당 이미지를 삭제한다.
function deleteImage(prdcode, prdimg, imgpath){
	if(imgpath == ""){
		alert("삭제할 이미지가 없습니다.");
		return;
	}else{
	if(confirm("이미지를 삭제하시겠습니까?"))
		document.location = "prd_save.php?mode=delete_image&prdcode="+prdcode+"&prdimg="+prdimg+"&imgpath="+imgpath;
	}
	return;
}

function appendOption(){

	var frm = document.frm;
	var length = frm.optcode_tmp.length;

	var optcode_01 = frm.optcode_01.value;
	var optcode_02 = frm.optcode_02.value;
	var optcode_03 = frm.optcode_03.value;

	if(optcode_01 == ""){
		alert("옵션을 입력하세요.");
		frm.optcode_01.focus();
		return;
	}
	if(optcode_02 == "") optcode_02 = "0";
	if(optcode_03 == "") optcode_03 = "0";

	if(!check_Num(optcode_02)){
		alert("가격은 숫자만 가능합니다.");
		frm.optcode_02.focus();
		return;
	}
	if(!check_Num(optcode_03)){
		alert("재고는 숫자만 가능합니다.");
		frm.optcode_03.focus();
		return;
	}

	var opttxt = optcode_01+" - "+optcode_02+"원 : "+optcode_03+"개";
	var optvalue = optcode_01+"^"+optcode_02+"^"+optcode_03;

	var option1 = new Option(opttxt, optvalue, true);
	frm.optcode_tmp.options[length] = option1;

	frm.optcode_01.value = "";
	frm.optcode_02.value = "";
	frm.optcode_03.value = "";

}

function deleteOption(){

	var frm = document.frm;
	var index = frm.optcode_tmp.selectedIndex;

	if(index >= 0){
		frm.optcode_tmp.options[index] = null;
	}
	frm.optcode_01.value = "";
	frm.optcode_02.value = "";
	frm.optcode_03.value = "";
}

function editOption(){


	var frm = document.frm;
	var length = frm.optcode_tmp.length;
	var idx = document.frm.optcode_tmp.selectedIndex;

	if(idx < 0) return;

	var optcode_01 = frm.optcode_01.value;
	var optcode_02 = frm.optcode_02.value;
	var optcode_03 = frm.optcode_03.value;

	if(optcode_01 == ""){
		alert("옵션을 입력하세요.");
		frm.optcode_01.focus();
		return;
	}
	if(optcode_02 == "") optcode_02 = "0";
	if(optcode_03 == "") optcode_03 = "0";

	if(!check_Num(optcode_02)){
		alert("가격은 숫자만 가능합니다.");
		frm.optcode_02.focus();
		return;
	}
	if(!check_Num(optcode_03)){
		alert("재고는 숫자만 가능합니다.");
		frm.optcode_03.focus();
		return;
	}

	var opttxt = optcode_01+" - "+optcode_02+"원 : "+optcode_03+"개";
	var optvalue = optcode_01+"^"+optcode_02+"^"+optcode_03;

	document.frm.optcode_tmp.options['idx'].text = opttxt;
	document.frm.optcode_tmp.options['idx'].value = optvalue;

}

function openOption(optno){

	var url = "option_list.php?optno=" + optno;
  window.open(url,"","height=350, width=350, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}


function chgOption(){

	var tmp_optcode = document.frm.optlist.value.split("^^");
	var tmp_opttext = document.frm.opttext.value.split("^^");
   var length = document.frm.optcode_tmp.length;
	for(ii=0; ii < (tmp_optcode.length-1); ii++){
		var option1 = new Option(tmp_opttext[ii], tmp_optcode[ii], true);
		document.frm.optcode_tmp.options[length] = option1;

		length++;
	}

	return false;
}

function setOption(){
	var idx = document.frm.optcode_tmp.selectedIndex;
	if(idx >= 0){
		var tmp_optcode = document.frm.optcode_tmp.options['idx'].value.split("^");
		frm.optcode_01.value = tmp_optcode[0];
		frm.optcode_02.value = tmp_optcode[1];
		frm.optcode_03.value = tmp_optcode[2];
	}

}


function optUp(){

	var frm = document.frm;
	var sel_idx = frm.optcode_tmp.selectedIndex;

	if(sel_idx > 0){

		chg_idx = sel_idx - 1;

		var sel_txt = frm.optcode_tmp.options[sel_idx].text;
		var sel_val = frm.optcode_tmp.options[sel_idx].value;
		var chg_txt = frm.optcode_tmp.options[chg_idx].text;
		var chg_val = frm.optcode_tmp.options[chg_idx].value;


		frm.optcode_tmp.options[chg_idx].text = sel_txt;
		frm.optcode_tmp.options[chg_idx].value = sel_val;
		frm.optcode_tmp.options[sel_idx].text = chg_txt;
		frm.optcode_tmp.options[sel_idx].value = chg_val;

		frm.optcode_tmp.options[chg_idx].selected = true;

	}



}

function optDown(){

	var frm = document.frm;
	var sel_idx = frm.optcode_tmp.selectedIndex;
	var opt_len = document.frm.optcode_tmp.length;

	if(-1 < sel_idx && sel_idx < (opt_len-1)){

		var chg_idx = sel_idx + 1;

		var sel_txt = frm.optcode_tmp.options[sel_idx].text;
		var sel_val = frm.optcode_tmp.options[sel_idx].value;
		var chg_txt = frm.optcode_tmp.options[chg_idx].text;
		var chg_val = frm.optcode_tmp.options[chg_idx].value;


		frm.optcode_tmp.options[chg_idx].text = sel_txt;
		frm.optcode_tmp.options[chg_idx].value = sel_val;
		frm.optcode_tmp.options[sel_idx].text = chg_txt;
		frm.optcode_tmp.options[sel_idx].value = chg_val;

		frm.optcode_tmp.options[chg_idx].selected = true;

	}

}

function prdlayCheck(){

	<?
	/*
	작업자		: 이상민
	작업일시	: 2019-08-19
	작업내용	: 추가이미지의 S, M, L 사이즈 중 한개라도 등록되어있으면 나타나도록 수정
	*/
	for($ii = 2; $ii <= $prdimg_max; $ii++) {
		if(
			@file($imgpath."/".$prd_row->{"prdimg_S".$ii}) ||
			@file($imgpath."/".$prd_row->{"prdimg_M".$ii}) ||
			@file($imgpath."/".$prd_row->{"prdimg_L".$ii})
		){
			echo "document.frm.prdlay_check".$ii.".checked = true; prdlay".$ii.".style.display='';";
		}
	}
	//if($prd_row->opttitle != "" || $prd_row->optcode != "") echo "document.frm.opt_use.checked = true; prdopt.style.display='';";
	if($prd_row->opt_use == "Y") echo "document.frm.opt_use.checked = true; prdopt.style.display='';";
	if($prd_row->color_use == "Y") echo "document.frm.color_use.checked = true; prd_cs_opt.style.display='';";
	?>
}

// 상품가격에 따른 적립금 적용 퍼센트에따라 적립금 적용
function setReserve(frm){

   if(frm.reserve != null){
   	var sellprice = frm.sellprice.value;
   	if(!check_Num(sellprice)){
			alert("판매가는 숫자이어야 합니다.");
			frm.sellprice.value = "";
			frm.sellprice.focus();
		}else{
	      var reserve = "" + sellprice*(<?=$reserve_buy?>/100)
	      reserve = reserve.split('.');
	      frm.reserve.value = reserve[0];
	   }
   }
}

function lodingComplete(){
	loding = true;
}

function prdCategory(){
  var url = "prd_catlist.php?prdcode=<?=$prdcode?>";
  window.open(url, "prdCategory", "height=330, width=800, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
}

function prdIcon(){
	var url = "prd_icon.php";
	window.open(url, "prdIcon", "height=250, width=450, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}

function setImgsize(){
	var url = "prd_imgsize.php";
   window.open(url, "setImgsize", "height=320, width=300, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}


function addopt(optid) {

	var frm = document.frm;
	var tbl = document.getElementById('opt');

	if(optid == 'opt1') {

		var row = tbl.insertRow(-1);
		var idx = tbl.rows.length - 2;

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(-1);
			switch (i){
				case 0: cell.innerHTML = "<input type=\"text\" class=\"input\" size=\"20\" name=\"tmp_optcode[]\" > <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\" style=\"vertical-align:middle\">";break;
				default:
					cell.innerHTML = "<input type=\"text\" name=\"tmp_opt[sellprice][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[reserve][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[stock][]\" class=\"input\" size=\"7\">";
					break;
			}
		}

	} else if(optid == 'opt2') {
		var bb_count = document.getElementById("bb_count").value;
		bb_count++;
		document.getElementById("bb_count").value = bb_count;

		for (i=0;i<tbl.rows.length;i++){

			cell = tbl.rows[i].insertCell(-1);
			switch (i){
				case 0: cell.innerHTML = "<input type=\"text\" class=\"input\"  id=\"tmp_opt2_"+bb_count+"\" style=\"width:95%\" name=\"tmp_optcode2[]\" >";break;
				default:
					cell.innerHTML = "<input type=\"text\" name=\"tmp_opt[sellprice][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[reserve][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[stock][]\" class=\"input\" size=\"7\">";
					break;
			}
		}

	} else if(optid == 'opt3') {

		var tbl = document.getElementById('opt3');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode3_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	} else if(optid == 'opt4') {

		var tbl = document.getElementById('opt4');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode4_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	} else if(optid == 'opt8') {

		var tbl = document.getElementById('opt8');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode8_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode8_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode8_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	} else if(optid == 'opt9') {

		var tbl = document.getElementById('opt9');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode9_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode9_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode9_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	} else if(optid == 'opt10') {

		var tbl = document.getElementById('opt10');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode10_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode10_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode10_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	} else if(optid == 'opt11') {

		var tbl = document.getElementById('opt11');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode11_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode11_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode11_res[]\">";
			cell.innerHTML += " <input type=\"button\" name=\"del_tr\" value=\"삭제\" class='suboptdel' onclick=\"delRow(this)\">";
		}

	}
}
function delRow(obj) {
	$(obj).parent().parent("tr").remove();
}
function delopt(optid) {
	var tbl = document.getElementById('opt');

	if(optid == 'opt1') {

		if (tbl.rows.length > 2) opt.deleteRow(-1);

	} else if(optid == 'opt2') {

		if (tbl.rows[0].cells.length < 3) return;
		for (i=0;i<tbl.rows.length;i++){
			tbl.rows[i].deleteCell(-1);
		}

	} else if(optid == 'opt3') {
		var tbl = document.getElementById('opt3');
		if (tbl.rows.length > 1) opt3.deleteRow(-1);
	} else if(optid == 'opt4') {
		var tbl = document.getElementById('opt4');
		if (tbl.rows.length > 1) opt4.deleteRow(-1);
	} else if(optid == 'opt8') {
		var tbl = document.getElementById('opt8');
		if (tbl.rows.length > 1) opt8.deleteRow(-1);
	} else if(optid == 'opt9') {
		var tbl = document.getElementById('opt9');
		if (tbl.rows.length > 1) opt9.deleteRow(-1);
	} else if(optid == 'opt10') {
		var tbl = document.getElementById('opt10');
		if (tbl.rows.length > 1) opt10.deleteRow(-1);
	} else if(optid == 'opt11') {
		var tbl = document.getElementById('opt11');
		if (tbl.rows.length > 1) opt11.deleteRow(-1);
	}
}

function hidden_addopt(optid,num){
	if(optid == 'opt1') {

		var tr = $(num).parent().parent();
		var class_val = tr.attr("class");

		var add_row = tr.clone();
		add_row.find("td:eq("+num+")").remove();
		add_row.insertAfter($("#opt ."+class_val+":last"));

	}
}


function hidden_addopt_t(optid,num,num2){
	var chk = Number(num2)+1;
	if(optid == 'opt1_'+chk) {

		var tr = $(num).parent().parent();
		var class_val = tr.attr("class");
		var add_row = tr.clone();
		add_row.find("td:eq("+num+")").remove();
		add_row.insertAfter($("#opt ."+class_val+":last"));

	}
}

function hidden_delopt_t(optid,num,num2){
	var chk = Number(num2)+1;
	if(optid == 'opt1_'+chk) {
		var tr = $(num).parent().parent();
		tr.remove();
	}
}

// 상품별쿠폰 발급회원
function popMycoupon(prdcode){
	var url = "shop_mycoupon.php?prdcode=" + prdcode;
	window.open(url,"MyCouponList","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 이벤트할인쿠폰
function popEventcoupon(){
	var url = "shop_eventcoupon.php";
	window.open(url,"EventCouponList","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function popEventcoupon2(idx){
	if(idx == ""){
		alert("상품등록이후 이용가능합니다.");
	} else {
		var url = "shop_eventcoupon2.php?idx="+idx;
		window.open(url,"EventCouponList2","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
}

//관련상품등록
function addReation(){
	<? if($mode == "insert"){ ?>
		alert("상품등록 후 관련상품을 등록하세요.");
	<? }else{ ?>
		var url = "prd_rellist.php?prdcode=<?=$prdcode?>";
		window.open(url, "addReation", "height=900, width=800, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
	<? } ?>
}

//관련상품삭제
function relDel(n){
	if(confirm("선택하신 관련상품을 삭제하시겠습니까?\n삭제이후 복구할수 없습니다.")){

		var mode = "reldel";
		$.ajax({
			type: "post",
			url: "prd_save.php",
			data : {mode:mode,idx:n},
			success: function (data) {
				if(data == "delok"){
					alert("관련상품이 삭제되었습니다.");
					var refresh_url = "./ajax_relate_goods.php?prdcode=<?php echo $prdcode ?>";
					refresh(refresh_url);
				}
			},
			error: function (data, status, err) {
				alert("서버와의 통신이 실패했습니다.");
				return;
			}
		});
		return false;

	} else {
		return false;
	}
}

function selectAll(){

	if($("#checkAll").prop("checked")) {
		$("input[name=select_checkbox]:checkbox").prop("checked", true);
	} else {
		$("input[name=select_checkbox]:checkbox").prop("checked", false);
	}
}

function prdDelete(){

	if($('input[name=select_checkbox]:checked').length == 0){
		alert("삭제할 관련상품을 선택하세요.");
		return false;

	} else {

		var select_checkbox = [];

		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		if(confirm("관련상품을 삭제하시겠습니까?\n삭제이후 복구할수 없습니다.")){

			var chkval = select_checkbox.join(',');
			var mode = "multireldel";
			$.ajax({
				type: "post",
				url: "prd_save.php",
				data : {mode:mode,chkval:chkval},
				success: function (data) {
					if(data == "delok"){
						alert("관련상품이 삭제되었습니다.");
						var refresh_url = "./ajax_relate_goods.php?prdcode=<?php echo $prdcode ?>";
						refresh(refresh_url);
					}
				},
				error: function (data, status, err) {
					alert("서버와의 통신이 실패했습니다.");
					return;
				}
			});
			return false;

		} else {
			return false;
		}
	
	}

}

function refresh(href){
	$("#related").load(href);
}

//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">상품등록</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">상품 상세정보를 설정합니다.</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 기본정보</td>
	</tr>
</table>
<form name="frm" action="prd_save.php?<?=$param?>" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode"    value="<?=$mode?>">
<input type="hidden" name="optlist" value="">
<input type="hidden" name="opttext" value="">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<input type="hidden" name="relidx"  value="<?=$relidx?>">
<input type="hidden" name="optcode" value="<?=$optcode?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="bb_count" id="bb_count" value="0">
<input type="hidden" name="eventcouponidx" id="eventcouponidx">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style" id="example">
				<!-- <tr>
					<td class="t_name">분류상태</td>
					<td class="t_value" colspan="3">
						<table>
						<?
						$sql = "SELECT * FROM wiz_cprelation WHERE prdcode='$prdcode'";
						$result = query($sql);
						while($row = sql_fetch_obj($result)){
							if($row->catcode != '00000000'){
								echo "<tr><td>".print_position($row->catcode)."</td></tr>";
							}
						}
						?>
						</table>
					</td>
				</tr> -->
				<tr class="item1">
					<td class="t_name" width="15%">상품분류 <!-- <a href="javascript:prdCategory()"><img src="../image/btn_catadd.gif" align="absmiddle" border="0"></a> --></td>
					<td class="t_value" colspan="3">
						<select name="class01" onChange="changeClass01();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class02" onChange="changeClass02();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class03" onChange="changeClass03();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class04" onChange="changeClass04();" class="mul_select" style="width:220px;" size="4" multiple></select>&nbsp;
						<?// if($mode == "update"){ ?>
						<a href="javascript:prdCategory()"><input type="button" value="상품분류추가" class="btn_mn"></a>
						<?// } ?>
					</td>
				</tr>
			</table>
			<br>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td class="t_name">상품그룹</td>
					<td class="t_value" colspan="3">
						<input type="checkbox" name="new" value="Y" <? if($prd_row->new == "Y") echo "checked"; ?>><img src="/twcenter/images/icon_new.gif" border="0"> &nbsp;
						<input type="checkbox" name="best" value="Y" <? if($prd_row->best == "Y") echo "checked"; ?>><img src="/twcenter/images/icon_best.gif" border="0"> &nbsp;
						<input type="checkbox" name="popular" value="Y" <? if($prd_row->popular == "Y") echo "checked"; ?>><img src="/twcenter/images/icon_hit.gif" border="0"> &nbsp;
						<input type="checkbox" name="recom" value="Y" <? if($prd_row->recom == "Y") echo "checked"; ?>><img src="/twcenter/images/icon_rec.gif" border="0">  &nbsp;
						<input type="checkbox" name="sale" value="Y" <? if($prd_row->sale == "Y") echo "checked"; ?>><img src="/twcenter/images/icon_sale.gif" border="0"> &nbsp;
					</td>
				</tr>
				<tr>
					<td class="t_name">
						상품아이콘 
						<a href="javascript:prdIcon()"><input type="button" value="아이콘관리" class="base_btm reg tmar_5"></a>
					</td>
					<td class="t_value" colspan="3">
						<table cellspacing=0 cellpadding=0>
							<tr>
								<td>
									<table cellspacing=0 cellpadding=0>
									<?
									$prdicon= explode("/",$prd_row->prdicon);
									for($ii=0; $ii<count($prdicon); $ii++){
										$prdicon_list[$prdicon[$ii]] = true;
									}

									$no = 0;

									// 업로드 디렉토리 생성
									if(!is_dir('../../data/prdicon')) mkdir('../../data/prdicon', 0755);

									if($handle = opendir('../../data/prdicon')){
										while(false !== ($file_name = readdir($handle))){
											if($file_name != "." && $file_name != ".."){
												if($no%7 == 0) echo "<tr>";
									?>
											<td><input type="checkbox" name="prdicon[]" value="<?=$file_name?>" <? if($prdicon_list["$file_name"]==true) echo "checked";?>></td>
											<td><img src="/twcenter/data/prdicon/<?=$file_name?>" border="0"></td>
									<?
												$no++;
											}
										}
										closedir($handle);
									}
									?>
									</table>
								</td>
								
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">상품명 <font color="red">*</font></td>
					<td class="t_value" colspan="3">
						<input type="text" name="prdname" value="<?=$prd_row->prdname?>" size="60" class="input input_color">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">제조사</td>
					<td width="35%" class="t_value">
						<input name="prdcom" type="text" value="<?=$prd_row->prdcom?>" class="input">
						<select onChange="this.form.prdcom.value = this.value" class="select">
						<option value="">::선택::</option>
						<?
						$sql = "select distinct prdcom from wiz_product where prdcom != '' order by prdcom asc";
						$result = query($sql);
						while($row = sql_fetch_obj($result)){
						?>
						<option value="<?=$row->prdcom?>"><?=$row->prdcom?></option>
						<?
						}
						?>
						<select>
					</td>
					<td width="15%" class="t_name">원산지</td>
					<td width="35%" class="t_value">
						<input name="origin" type="text" value="<?=$prd_row->origin?>" class="input">
						<select onChange="this.form.origin.value = this.value" class="select">
						<option value="">::선택::</option>
						<?
						$sql = "select distinct origin from wiz_product where origin != '' order by origin asc";
						$result = query($sql);
						while($row = sql_fetch_obj($result)){
						?>
						<option value="<?=$row->origin?>"><?=$row->origin?></option>
						<?
						}
						?>
						<select>
					</td>
					</tr>
					<tr>
						<td class="t_name">브랜드</td>
						<td class="t_value">
							<select name="brand" style="width:130px" class="select">
							<option value="">::선택::</option>
							<?
							$sql = "select idx, brdname from wiz_brand where brduse != 'N' order by priorno asc";
							$result = query($sql);
							while($row = sql_fetch_obj($result)){
							?>
							<option value="<?=$row->idx?>" <? if(!strcmp($row->idx, $prd_row->brand)) echo "selected" ?>><?=$row->brdname?></option>
							<?
							}
							?>
							<select>
						</td>
						<td class="t_name">상품진열</td>
						<td class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="showset" value="Y" <? if($prd_row->showset == "Y" || empty($prd_row->showset)) echo "checked"; ?>></span>진열함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="showset" value="N" <? if($prd_row->showset == "N") echo "checked"; ?>></span>진열안함&nbsp;&nbsp;
						<? if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use']=="Y"){ ?>
						<span style="vertical-align: middle"><input type="checkbox" name="mobileShow" value="Y" <? if($prd_row->mobileShow == "Y") echo "checked"; ?>></span><font color='red'>모바일 진열</font>
						<? }else{ ?>
							<input type="hidden" name="mobileShow" value="<?=$prd_row->mobileShow?>">
						<?}?>
						</td>
					</tr>
					<input type="hidden" name="prior" value="<? if(empty($prd_row->prior)) echo date(ymdHis); else echo $prd_row->prior; ?>" maxlength="12" class="input">
					<!--tr>
						<td class="t_name">우선순위</td>
						<td class="t_value">
						<input type="text" name="prior" value="<? if(empty($prd_row->prior)) echo date(ymdHis); else echo $prd_row->prior; ?>" maxlength="12" class="input">
						</td>
						<td class="t_name"></td>
						<td class="t_value">

						</td-->
						<!--td class="t_name">선호도</td>
						<td class="t_value">
						<select name="prefer">
						<option value="1" <? if($prd_row->prefer == "1") echo "selected"; ?>>별1
						<option value="2" <? if($prd_row->prefer == "2") echo "selected"; ?>>별2
						<option value="3" <? if($prd_row->prefer == "3" || $prd_row->prefer == "") echo "selected"; ?>>별3
						<option value="4" <? if($prd_row->prefer == "4") echo "selected"; ?>>별4
						<option value="5" <? if($prd_row->prefer == "5") echo "selected"; ?>>별5
						</select>
						</td//-->
					<!--/tr-->
			</table>
		</td>
	</tr>
</table>
<!--table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td height="2"></td></tr>
	<tr>
		<td width="17%"></td>
		<td>(숫자가 클수록 진열시 앞에 나옵니다. 최대 12자리) </td>
	</tr>
</table-->

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품정보</td>
		<td>
			<span style="vertical-align: middle"><input type="radio" name="info_use" onClick="if(this.checked==true) addinfo.style.display='none';" value="N" <? if($prd_row->info_use == "" || $prd_row->info_use == "N") echo "checked"; ?>></span>사용안함
			<span style="vertical-align: middle"><input type="radio" name="info_use" onClick="if(this.checked==true) addinfo.style.display='';" value="Y" <? if($prd_row->info_use == "Y") echo "checked"; ?>></span>사용함
		</td>
	</tr>
</table>
<div id="addinfo" style=display:<? if($prd_row->info_use == "" || $prd_row->info_use == "N") echo "none"; else echo "show"; ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">상품정보</td>
					<td width="85%" class="t_value">

						<table border="0" cellspacing="5" cellpadding="0">
							<tr>
								<td></td>
								<td>상품가격</td>
								<td>1,000원 (예시)</td>
							</tr>
							<tr>
								<td>1.</td>
								<td><input name="info_name1" type="text" value="<?=$prd_row->info_name1?>" size="20" class="input"></td>
								<td><input name="info_value1" type="text" value="<?=$prd_row->info_value1?>" size="45" class="input"></td>
								<td width="60" align="right">4.</td>
								<td><input name="info_name4" type="text" value="<?=$prd_row->info_name4?>" size="20" class="input"></td>
								<td><input name="info_value4" type="text" value="<?=$prd_row->info_value4?>" size="45" class="input"></td>
							</tr>
							<tr>
								<td>2.</td>
								<td><input name="info_name2" type="text" value="<?=$prd_row->info_name2?>" size="20" class="input"></td>
								<td><input name="info_value2" type="text" value="<?=$prd_row->info_value2?>" size="45" class="input"></td>
								<td align="right">5.</td>
								<td><input name="info_name5" type="text" value="<?=$prd_row->info_name5?>" size="20" class="input"></td>
								<td><input name="info_value5" type="text" value="<?=$prd_row->info_value5?>" size="45" class="input"></td>
							</tr>
							<tr>
								<td>3.</td>
								<td><input name="info_name3" type="text" value="<?=$prd_row->info_name3?>" size="20" class="input"></td>
								<td><input name="info_value3" type="text" value="<?=$prd_row->info_value3?>" size="45" class="input"></td>
								<td align="right">6.</td>
								<td><input name="info_name6" type="text" value="<?=$prd_row->info_name6?>" size="20" class="input"></td>
								<td><input name="info_value6" type="text" value="<?=$prd_row->info_value6?>" size="45" class="input"></td>
							</tr>
						</table>

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
</div>
<?
if($site_info['event_coupon_use'] == "Y"){
?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 이벤트할인쿠폰</td>
		<td>
			<span style="vertical-align: middle"><input type="radio" name="eventcouponuse" onClick="if(this.checked==true) evt_coupon.style.display='none';" value="N" <? if($prd_row->eventcouponuse == "" || $prd_row->eventcouponuse == "N") echo "checked"; ?>></span>사용안함
			<span style="vertical-align: middle"><input type="radio" name="eventcouponuse" onClick="if(this.checked==true) evt_coupon.style.display='';" value="Y" <? if($prd_row->eventcouponuse == "Y") echo "checked"; ?>></span>사용함
		</td>
	</tr>
</table>
<div id="evt_coupon" style=display:<? if($prd_row->eventcouponuse == "" || $prd_row->eventcouponuse == "N") echo "none"; else echo "show"; ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">링크주소</td>
					<td width="85%" class="t_value">
						<input type="text" name="eventcouponlink" id="eventcouponlink" value="<?=$prd_row->eventcouponlink?>" class="input" size="60">&nbsp;&nbsp;
						<input type="button" value="쿠폰적용" class="base_btm blue2" onclick="popEventcoupon()">
						<input type="button" value="쿠폰발급회원" class="base_btm reg" onclick="popEventcoupon2('<?=$prd_row->eventcouponidx?>')">

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
</div>
<? } ?>


<? if($oper_info['coupon_use'] == "Y"){ ?>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품할인쿠폰</td>
		<td>
			<span style="vertical-align: middle"><input type="radio" name="coupon_use" onClick="if(this.checked==true) coupon.style.display='none';" value="N" <? if($prd_row->coupon_use == "" || $prd_row->coupon_use == "N") echo "checked"; ?>></span>사용안함
			<span style="vertical-align: middle"><input type="radio" name="coupon_use" onClick="if(this.checked==true) coupon.style.display='';" value="Y" <? if($prd_row->coupon_use == "Y") echo "checked"; ?>></span>사용함
		</td>
	</tr>
</table>
<div id="coupon" style=display:<? if($prd_row->coupon_use == "" || $prd_row->coupon_use == "N") echo "none"; else echo "show"; ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td class="t_name">쿠폰금액/할인율</td>
					<td colspan="3" class="t_value">
						<input name="coupon_dis" type="text" value="<?=$prd_row->coupon_dis?>" class="input">
						<span style="vertical-align: middle"><input type="radio" name="coupon_type" value="%" <? if($prd_row->coupon_type == "" || $prd_row->coupon_type == "%") echo "checked"; ?>></span>% 퍼센트
						<span style="vertical-align: middle"><input type="radio" name="coupon_type" value="원" <? if($prd_row->coupon_type == "원") echo "checked"; ?>></span>원
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">쿠폰수량</td>
					<td width="35%" class="t_value">
						<input name="coupon_amount" type="text" value="<?=$prd_row->coupon_amount?>" class="input"  <? if($prd_row->coupon_limit == "N") echo "disabled"; ?>>
						<span style="vertical-align: middle"><input type="checkbox" name="coupon_limit" value="N" <? if($prd_row->coupon_limit == "N") echo "checked"; ?> onClick="if(this.checked==true) this.form.coupon_amount.disabled = true; else this.form.coupon_amount.disabled = false;"></span>수량제한없음
					</td>
					<td width="15%" class="t_name">쿠폰종료일</td>
					<td width="35%" class="t_value">

					  <span class="calendar">
						  <input name="coupon_sdate" id="coupon_sdate" size="15" type="text" value="<?=$prd_row->coupon_sdate?>"  class="datepicker-here input2">
						   ~
						  <input name="coupon_edate" id="coupon_edate" size="15" type="text" value="<?=$prd_row->coupon_edate?>"  class="datepicker-here input2">
					  </span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right" height="30"><input type="button" value="쿠폰발급회원" class="base_btm reg" onclick="popMycoupon('<?=$prdcode?>')"></td>
	</tr>
</table>
<br>
</div>

<? } ?>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 가격 및 재고</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">판매가 <font color="red">*</font></td>
					<td width="35%" class="t_value"><input name="sellprice" type="text" value="<?=$prd_row->sellprice?>" class="input input_color" <?php  if($oper_info['reserve_use'] == "Y") { ?> onchange="setReserve(this.form);" <?php } ?>></td>
					<td width="15%" class="t_name">정가</td>
					<td width="35%" class="t_value"><input name="conprice" type="text" value="<?=$prd_row->conprice?>" class="input"><div class="sub_tit_alt2 tmar_5"> <s>5,000</s>로 표기됨, 0 입력시 표기안됨</div> </td>
				</tr>
				<tr>
					<?php
					if($reserve_use == "Y") {
					?>
					<td class="t_name">적립금<br><a href="shop_oper.php#res"><? if($reserve_use == "Y") echo "(판매가 ".$reserve_buy." %)"; ?></a></td>
					<td class="t_value"><input name="reserve" type="text" value="<?=$prd_row->reserve?>" class="input"></td>
					<?php } ?>
					<td class="t_name">재고량</td>
					<td class="t_value" <?php echo $colspan ?>>
						<span style="vertical-align: middle"><input type="radio" name="shortage" value="Y" <? if($prd_row->shortage == "Y") echo "checked"; ?>></span><img src="/twcenter/images/icon_not.gif" border="0" align="absmiddle"> &nbsp;
						<span style="vertical-align: middle"><input type="radio" name="shortage" value="N" <? if($prd_row->shortage == "N" || empty($prd_row->shortage)) echo "checked"; ?>></span>무제한
						<span style="vertical-align: middle"><input type="radio" name="shortage" value="S" <? if($prd_row->shortage == "S") echo "checked"; ?>></span>수량
						<input name="stock" type="text" size="5" value="<?=$prd_stock?>" class="input">개&nbsp;
						<div class="sub_tit_alt2 tmar_5"> 수량을 지정하면 재고가 없을시 판매중지</div>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격대체문구</td>
					<td class="t_value" colspan='3'>
						<input name="strprice" type="text" value="<?=$prd_row->strprice?>" class="input">
						<div class="sub_tit_alt2 tmar_5"> 가격대체문구를 입력하면 가격대신 입력한 문구가 보이며 구매가 불가능합니다.</div>
					</td>
					<!--td class="t_name">타임세일</td> <?/*2021-03-15 완전한 기능이 아니므로 주석처리, 가격 대체문구 colspan='3' 추가*/?>
					<td class="t_value ">
						<span style="vertical-align: middle"><input type="radio" id="timesale_use1" name="timesale_use" value="Y" <? if($prd_row->timesale_use == "Y") echo "checked"; ?>></span>사용함 &nbsp;
						<span class="calendar"><input id="timelimit_date" name="timelimit_date" type="text" size="8" value="<?=$timelimit_date?>" class="input2 datepicker" autocomplete="off"></span>&nbsp;
						<select id="timelimit_hour" name="timelimit_hour" class="input">
							<?php
							for($i=0;$i<=23;$i++){
								if($i == $timelimit_hour) $hsel = "selected";
								else $hsel = "";

								if($i < 10) $t = "0".$i;
								else $t = $i;

								echo "<option value='".$i."' ".$hsel.">".$t."시</option>";
							}
							?>
						</select>&nbsp;
						<select id="timelimit_min" name="timelimit_min" class="input">
							<?php
							for($i=0;$i<=59;$i++){
								if($i == $timelimit_min) $msel = "selected";
								else $msel = "";

								if($i < 10) $t = "0".$i;
								else $t = $i;

								echo "<option value='".$i."' ".$msel.">".$t."분</option>";
							}
							?>
						</select>&nbsp;&nbsp;
						<span style="vertical-align: middle"><input type="radio" id="timesale_use2" name="timesale_use" value="N" <? if($prd_row->timesale_use == "N" || empty($prd_row->timesale_use)) echo "checked"; ?>></span>사용안함&nbsp;
						<div class="sub_tit_alt2 tmar_5"> 타임세일 종료일시가 입력되어 있어도 "사용함"에 체크되지 않으면 반영되지 않습니다.</div>
					</td -->
				</tr>
			</table>
		</td>
	</tr>
</table>


<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 배송비</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">배송비</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DA" <? if(!strcmp($prd_row->del_type, "DA") || empty($prd_row->del_type)) echo "checked" ?>></span>기본 배송정책
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DB" <? if(!strcmp($prd_row->del_type, "DB")) echo "checked" ?>></span>무료배송
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DC" <? if(!strcmp($prd_row->del_type, "DC")) echo "checked" ?>></span>상품별 배송비
						<input name="del_price" type="text" value="<?=$prd_row->del_price?>" class="input" size="10">원
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DD" <? if(!strcmp($prd_row->del_type, "DD")) echo "checked" ?>></span>수신자부담(착불)
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품별 검색엔진 최적화(SEO)</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">노출여부</td>
					<td width="85%" class="t_value">
						<label><span style="vertical-align: middle"><input type="radio" name="prd_seo_use" value="Y" <?php if($prd_row->prd_seo_use == 'Y') echo "checked" ?>></span>노출</label>
						<label><span style="vertical-align: middle"><input type="radio" name="prd_seo_use" value="N" <?php if($prd_row->prd_seo_use == 'N') echo "checked" ?>></span>미노출</label>
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">브라우저 타이틀설정</td>
					<td width="85%" class="t_value">
						<input name="prd_br_title" type="text" value="<?=$prd_row->prd_br_title?>" class="input" size="100">
						<div class="sub_tit_alt2 tmar_5"> 해당상품에 대해 간단명료하게 입력하세요. 특수기호는 사용을 자제해주시기 바랍니다.</div>
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">상품 메타태그 설명 (Description)</td>
					<td width="85%" class="t_value">
						<input name="prd_descript" type="text" value="<?=$prd_row->prd_descript?>" class="input" size="100">
						<div class="sub_tit_alt2 tmar_5"> 메타태그 설명은 70~160자를 유지하는게 좋습니다.</div>
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">상품 메타태그 키워드 (Keywords)</td>
					<td width="85%" class="t_value">
						<input name="prd_keywords" type="text" value="<?=$prd_row->prd_keywords?>" class="input" size="100">
						<div class="sub_tit_alt2 tmar_5"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
						<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품옵션</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<?php 
			// 색상/사이즈 옵션 시작
			?>
			<script src="/comm/colorpicker/jquery.minicolors.js"></script>
			<link rel="stylesheet" href="/comm/colorpicker/jquery.minicolors.css">
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<!-- <tr>
					<td width="15%" class="t_name">색상/사이즈옵션</td>
					<td width="85%" class="t_value" colspan="3">
						<img src="../image/tip_icon.png"> 색상 및 사이즈옵션을 관리할 수 있습니다.
						<input type="checkbox" name="color_use" value="Y" onClick="if(this.checked==true) prd_cs_opt.style.display=''; else prd_cs_opt.style.display='none';"><font color="red">설정하기</font><br>
						<div id="prd_cs_opt" style="display:none;">
							<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
								<tr>
									<td width="15%" class="t_name">색상옵션</td>
									<td width="85%" class="t_value" colspan="3">
										<input type="hidden" name="opttitle12" value="색상">
										<input type="hidden" name="optcode12" value="<?php echo $prd_row->optcode12; ?>">
										<?php 
										$colorAr = explode(",", $prd_row->optcode12);
										for($i=0;$i<sizeof($colorAr);$i++){
											list($colorName, $colorCode) = explode("^", $colorAr[$i]);
										?>
										<p class="colorCode_wrap" style="margin: 5px 0px;">
											<input type="text" name="colorName[]" value="<?php echo $colorName; ?>" class="input init" placeholder="색상명을 입력하세요">
											<input type="hidden" name="colorCode[]" value="<?php echo $colorCode; ?>" class="input init colorCode">
											<input type="button" value="항목추가" class="optadd codeadd">
											<input type="button" value="항목삭제" class="optdel codedel">
										</p>
										<?php
										}
										?>
										<div class="sub_tit_alt2 tmar_5">색상명을 입력하고, 우측 네모를 눌러 색상코드를 선택하세요</div>
									</td>
								</tr>
								<tr>
									<td class="t_name">사이즈 옵션</td>
									<td class="t_value" colspan="3">
										<input type="hidden" name="opttitle13" value="사이즈" class="input">
										<input type="text" name="optcode13" value="<?=$prd_row->optcode13?>" size="80" class="input">
										<div class="sub_tit_alt2 tmar_5">옵션은 컴마(,)로 구분하여 입력하세요</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr> -->
			</table>
			<table><tr><td></td></tr></table>
			<script>
			$(document).on("click", ".codeadd", function(){
				var parent = $(this).parent(".colorCode_wrap");
				var clone = parent.clone();

				clone.find(".init").val("");

				clone.find(".colorCode").minicolors("destroy").minicolors({
					control: 'hue',
					defaultValue: '#fff',
					format: 'hex',
					keywords: '',
					inline: '',
					letterCase: 'lowercase',
					position: 'bottom left',
					swatches: [],
					change: function(value, opacity) {
						if( !value ) return;
						if( opacity ) value += ', ' + opacity;
						if( typeof console === 'object' ) {
							console.log(value);
						}
					},
					theme: 'bootstrap'
				});

				parent.after(clone);
			});

			$(document).on("click", ".codedel", function(){
				var parent = $(this).parent(".colorCode_wrap");

				if($(".colorCode_wrap").length > 1){
					if(confirm("삭제하시겠습니까?")){
						parent.remove();
					}
				} else {
					alert("더이상 삭제할 수 없습니다.");
				}
			});

			$(".colorCode").minicolors({
				control: 'hue',
				defaultValue: '#fff',
				format: 'hex',
				keywords: '',
				inline: '',
				letterCase: 'lowercase',
				position: 'bottom left',
				swatches: [],
				change: function(value, opacity) {
					if( !value ) return;
					if( opacity ) value += ', ' + opacity;
					if( typeof console === 'object' ) {
						console.log(value);
					}
				},
				theme: 'bootstrap'
			});
			</script>
			<?php
			// 종료
			?>
		
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">일반 옵션1
					<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt5_req" value="Y" <?php if($prd_row->opt5_req == 'Y') echo "checked" ?>> 필수<?}?>
					</td>
					<td width="85%" class="t_value" colspan="3">
					옵션명 : <input type="text" name="opttitle5" value="<?=$prd_row->opttitle5?>" class="input">
					&nbsp; 옵션 : <input type="text" name="optcode5" value="<?=$prd_row->optcode5?>" size="60" class="input">
					<input type="button" value="불러오기" class='optfold' onClick="openOption('opt5');"> 
					<div class="sub_tit_alt2 tmar_5">옵션은 컴마(,)로 구분</div>
					</td>
				</tr>
				<tr>
					<td class="t_name">일반 옵션2
					<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt6_req" value="Y" <?php if($prd_row->opt6_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
					옵션명 : <input type="text" name="opttitle6" value="<?=$prd_row->opttitle6?>" class="input">
					&nbsp; 옵션 : <input type="text" name="optcode6" value="<?=$prd_row->optcode6?>" size="60" class="input">
					<input type="button" value="불러오기" class='optfold' onClick="openOption('opt6');"> 
					<div class="sub_tit_alt2 tmar_5">ex(95,100,105...)</div>
					</td>
				</tr>
				<tr>
					<td class="t_name">일반 옵션3
					<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt7_req" value="Y" <?php if($prd_row->opt7_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
					옵션명 : <input type="text" name="opttitle7" value="<?=$prd_row->opttitle7?>" class="input">
					&nbsp; 옵션 : <input type="text" name="optcode7" value="<?=$prd_row->optcode7?>" size="60" class="input">
					<input type="button" value="불러오기" class='optfold' onClick="openOption('opt7');">
					</td>
				</tr>
			</table>

			<table><tr><td></td></tr></table>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">가격추가 옵션1
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt3_req" value="Y" <?php if($prd_row->opt3_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td width="85%" class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle3" value="<?=$prd_row->opttitle3?>" class="input">
						<input type="button" value="불러오기" class='optfold' onclick="openOption('opt3');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt3')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt3')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt3">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode3_arr = explode("^^", $prd_row->optcode3);
							for($ii = 0; $ii < count($optcode3_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode3_arr[$ii]);
							?>
							<tr>
								<td>
									&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode3_opt[]" value="<?=$opt?>">
									추가가격 : <input type="text" class="input" name="optcode3_pri[]" value="<?=$price?>" size="10">
									추가적립금 : <input type="text" class="input" name="optcode3_res[]" value="<?=$reserve?>" size="10">
									<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격추가 옵션2
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt4_req" value="Y" <?php if($prd_row->opt4_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle4" value="<?=$prd_row->opttitle4?>" class="input">
						<input type="button" value="불러오기" class='optfold' onClick="openOption('opt4');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt4')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt4')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt4">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode4_arr = explode("^^", $prd_row->optcode4);
							for($ii = 0; $ii < count($optcode4_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode4_arr[$ii]);
							?>
							<tr>
								<td>
								&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode4_opt[]" value="<?=$opt?>">
								추가가격 : <input type="text" class="input" name="optcode4_pri[]" value="<?=$price?>" size="10">
								추가적립금 : <input type="text" class="input" name="optcode4_res[]" value="<?=$reserve?>" size="10">
								<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
								}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격추가 옵션3
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt8_req" value="Y" <?php if($prd_row->opt8_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle8" value="<?=$prd_row->opttitle8?>" class="input">
						<input type="button" value="불러오기" class='optfold' onClick="openOption('opt8');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt8')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt8')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt8">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode8_arr = explode("^^", $prd_row->optcode8);
							for($ii = 0; $ii < count($optcode8_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode8_arr[$ii]);
							?>
							<tr>
								<td>
								&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode8_opt[]" value="<?=$opt?>">
								추가가격 : <input type="text" class="input" name="optcode8_pri[]" value="<?=$price?>" size="10">
								추가적립금 : <input type="text" class="input" name="optcode8_res[]" value="<?=$reserve?>" size="10">
								<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격추가 옵션4
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt9_req" value="Y" <?php if($prd_row->opt9_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle9" value="<?=$prd_row->opttitle9?>" class="input">
						<input type="button" value="불러오기" class='optfold' onClick="openOption('opt9');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt9')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt9')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt9">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode9_arr = explode("^^", $prd_row->optcode9);
							for($ii = 0; $ii < count($optcode9_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode9_arr[$ii]);
							?>
							<tr>
								<td>
								&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode9_opt[]" value="<?=$opt?>">
								추가가격 : <input type="text" class="input" name="optcode9_pri[]" value="<?=$price?>" size="10">
								추가적립금 : <input type="text" class="input" name="optcode9_res[]" value="<?=$reserve?>" size="10">
								<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격추가 옵션5
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt10_req" value="Y" <?php if($prd_row->opt10_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle10" value="<?=$prd_row->opttitle10?>" class="input">
						<input type="button" value="불러오기" class='optfold' onClick="openOption('opt10');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt10')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt10')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt10">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode10_arr = explode("^^", $prd_row->optcode10);
							for($ii = 0; $ii < count($optcode10_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode10_arr[$ii]);
							?>
							<tr>
								<td>
								&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode10_opt[]" value="<?=$opt?>">
								추가가격 : <input type="text" class="input" name="optcode10_pri[]" value="<?=$price?>" size="10">
								추가적립금 : <input type="text" class="input" name="optcode10_res[]" value="<?=$reserve?>" size="10">
								<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">가격추가 옵션6
						<?if($site_info['viewType']!='M'){?><input type="checkbox" name="opt11_req" value="Y" <?php if($prd_row->opt11_req == 'Y') echo "checked" ?>> 필수 <?}?>
					</td>
					<td class="t_value" colspan="3">
						옵션명 : <input type="text" name="opttitle11" value="<?=$prd_row->opttitle11?>" class="input">
						<input type="button" value="불러오기" class='optfold' onClick="openOption('opt11');">
						<input type="button" value="항목추가" class='optadd' onclick="addopt('opt11')">
						<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt11')">
						<br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt11">
							<tr>
								<td></td>
							</tr>
							<?
							$optcode11_arr = explode("^^", $prd_row->optcode11);
							for($ii = 0; $ii < count($optcode11_arr) - 1; $ii++) {
								list($opt, $price, $reserve) = explode("^", $optcode11_arr[$ii]);
							?>
							<tr>
								<td>
								&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode11_opt[]" value="<?=$opt?>">
								추가가격 : <input type="text" class="input" name="optcode11_pri[]" value="<?=$price?>" size="10">
								추가적립금 : <input type="text" class="input" name="optcode11_res[]" value="<?=$reserve?>" size="10">
								<input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="delRow(this)">
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
			</table>

			<table><tr><td></td></tr></table>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">가격/재고옵션</td>
					<td width="85%" class="t_value" colspan="3">
					<img src="../image/tip_icon.png"> 하나 또는 두개의 옵션을 조합하여 가격/적립금추가,재고관리가 가능합니다.
					<input type="checkbox" name="opt_use" value="Y" onClick="if(this.checked==true) prdopt.style.display=''; else prdopt.style.display='none';"><font color="red">설정하기</font><br>
					<div id="prdopt" style="display:none">

						<table border="0" cellpadding="0" cellspacing="0">
							<tr height="30">
								<td>
									옵션 1 : <input type="text" name="opttitle" value="<?=$prd_row->opttitle?>" class="input">
									<input type="button" value="항목추가" class='optadd' onclick="addopt('opt1')">
									<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt1')">
								</td>
								<td width="5"></td>
								<td>
									옵션 2 : <input type="text" name="opttitle2" value="<?=$prd_row->opttitle2?>" class="input">
									<input type="button" value="항목추가" class='optadd' onclick="addopt('opt2')">
									<input type="button" value="항목삭제" class='optdel' onclick="delopt('opt2')">
								</td>
							</tr>
						</table>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr height="30">
								<td>입력형식 : <input type="text" size="9" class="input" value="추가금액" readonly style="text-align:center">:<input type="text" size="10" class="input" value="추가적립금" readonly style="text-align:center">:<input type="text" size="8" class="input" value="재고" readonly style="text-align:center">
								</td>
							</tr>
						</table>
						<?
						$optcode_list = explode("^",$prd_row->optcode);
						$optcode2_list = explode("^",$prd_row->optcode2);

						$opt_list = explode("^^",$prd_row->optvalue);
						for($ii=0; $ii < count($opt_list)-1; $ii++){
							$optvalue[$ii] = explode("^",$opt_list[$ii]);
						}

						$no = 0;
						?>
						<table id="opt" border="0" cellpadding="3" cellspacing="1" class="t_style">

							<tr>
								<td class="t_name" align="center">옵션명</td>
								<td id="opt2_1"><input type="text" name="tmp_optcode2[]" id="optcode2_0" class="input" value="<?=$optcode2_list[0]?>" style="width:95%"></td>
								<?
								for($ii = 1; $ii < count($optcode2_list) - 1; $ii++) {
								?>
								<td id="opt2_<? echo $ii + 1 ?>" class="t_value"><input type="text" name="tmp_optcode2[]" id="optcode2_<?=$ii?>" class="input" value="<?=$optcode2_list[$ii]?>" style="width:100%"></td>
								<?
								}
								?>
							</tr>
							<tr id="opt1" class="opt1">
								<td nowrap align="center"><input type="text" name="tmp_optcode[]" value="<?=$optcode_list[0]?>" size="20" class="input" > <input type="button" value="추가" class='suboptadd' onclick="hidden_addopt('opt1',this)" style="vertical-align:middle"> <input type="button" value="삭제" class='suboptdel' onclick="delRow(this)" style="vertical-align:middle"></td>
								<td align="center">
									<input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input">
								</td>
								<?
								for($ii = 1; $ii < count($optcode2_list) - 1; $ii++) {
									$no++;
								?>
								<td id="opt2_<? echo $ii + 1 ?>" class="t_value"><input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input"></td>
								<?
								}
								?>
							</tr>
							<?
							for($ii = 1; $ii < count($optcode_list) - 1; $ii++) {
								$no++;
							?>
							<tr id="opt1_<? echo $ii+1 ?>" class="opt1_<? echo $ii+1 ?>">
								<td nowrap align="center"><input type="text" name="tmp_optcode[]" value="<?=$optcode_list[$ii]?>" size="20" class="input"> <input type="button" value="추가" class='suboptadd' onclick="hidden_addopt_t('opt1_<? echo $ii+1 ?>',this,'<?=$ii?>')" style="vertical-align:middle"> <input type="button" name="del_tr[]" value="삭제" class='suboptdel' onclick="hidden_delopt_t('opt1_<? echo $ii+1 ?>',this,'<?=$ii?>')" style="vertical-align:middle"></td>
								<td align="center">
									<input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input">
								</td>
								<?
									for($jj = 1; $jj < count($optcode2_list) - 1; $jj++) {
										$no++;
								?>
								<td class="t_value" id="opt2_<? echo $jj + 1 ?>"><input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input"></td>
								<?
									}
								?>
							</tr>
							<?
							}

							?>
						</table>
					</div>

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품사진</td>
		<td>
		<?
		for($j=2; $j<=$oper_info['prd_cnt']; $j++){
		?>
			<span style="vertical-align: middle"><input type="checkbox" class="chk_class<?=$j?>" name="prdlay_check<?=$j?>" onClick="if(this.checked==true) prdlay<?=$j?>.style.display=''; else prdlay<?=$j?>.style.display='none';"></span><font color="red">이미지추가<?=$j?></font>
		<? } ?>&nbsp; &nbsp;
			<a href="javascript:setImgsize();"><img src="../image/btn_imgsize.gif" align="absmiddle" border="0"></a>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="75%">
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="20%" class="t_name">원본 이미지</td>
					<td width="80%" class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file">이미지 업로드</label>
							<input type="file" name="realimg" id="input-file" class="upload-hidden"> <div class="sub_tit_alt2 tmar_5"> GIF, JPG, JPEG, PNG 파일만 업로드하세요.</div>
						</div>
					</td>
				</tr>
				<tr>
					<td height="40" class="t_name">
					상품목록 이미지 <font color="red">*</font>
					<span class="sub_tit_alt">사이즈 (<?=$oper_info['prdimg_R']?> x <?=$oper_info['prdimg_R']?>)</span></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_R" class="input">
						<? if( @file($imgpath."/".$prd_row->prdimg_R) ){ ?>
						<input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_R?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->prdimg_R?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_R?>';"><?=$prd_row->prdimg_R?></a>)
						<? } else { ?>
						원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
				<tr>
					<td height="40" class="t_name">
					축소이미지 이미지1
					<span class="sub_tit_alt">사이즈 (<?=$oper_info['prdimg_S']?> x <?=$oper_info['prdimg_S']?>)</span></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_S1" class="input">
						<? if( @file($imgpath."/".$prd_row->prdimg_S1) ){ ?>
						<input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S1?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->prdimg_S1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_S1?>';"><?=$prd_row->prdimg_S1?></a>)
						<? } else { ?>
						원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
				<tr>
					<td height="40" class="t_name">
					제품상세 이미지1 <font color="red">*</font>
					<span class="sub_tit_alt">사이즈 (<?=$oper_info['prdimg_M']?> x <?=$oper_info['prdimg_M']?>)</span></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_M1" class="input">
						<? if( @file($imgpath."/".$prd_row->prdimg_M1) ){ ?>
						<input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M1?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->prdimg_M1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_M1?>';"><?=$prd_row->prdimg_M1?></a>)
						<? } else { ?>
						원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
				<tr>
					<td height="40" class="t_name">
					확대 이미지1 <font color="red">*</font>
					<span class="sub_tit_alt">사이즈 (<?=$oper_info['prdimg_L']?> x <?=$oper_info['prdimg_L']?>)</span></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_L1" class="input">
						<? if( @file($imgpath."/".$prd_row->prdimg_L1) ){ ?>
						<input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L1?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->prdimg_L1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_L1?>';"><?=$prd_row->prdimg_L1?></a>)
						<? } else { ?>
						원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
			</table>
		</td>
		<td width="25%" height="100%">
			<table width="100%" height="100%" cellspacing="0" cellpadding="0" class="t_style">
				<tr>
					<td align="center" bgcolor="#ffffff">
					<?
					if(@file($imgpath."/".$prd_row->prdimg_R))
						echo "<img src='../../data/prdimg/$prd_row->prdimg_R' name='prdimg1' width='100'>";
					else
						echo "<img src='../image/noimg.gif' width='100'>";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
for($k=2; $k<=$oper_info['prd_cnt']; $k++){
?>
<div id="prdlay<?=$k?>" style="display:none">
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="75%">
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="20%" class="t_name">원본 이미지</td>
					<td width="80%" class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file<?=$k?>">이미지 업로드</label>
							<input type="file" name="realimg<?=$k?>" id="input-file<?=$k?>" class="upload-hidden">
						</div>					
					</td>
				</tr>
				<tr>
					<td class="t_name">축소 이미지<?=$k?></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_S<?=$k?>" class="input">
						<? if( @file($imgpath."/".$prd_row->{prdimg_S.$k}) ){ ?>
							<input type="checkbox" name="delimg[]" value="<?=$prd_row->{prdimg_S.$k}?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->{prdimg_S.$k}?>" target="_blank" onMouseOver="document.prdimg<?=$k?>.src='../../data/prdimg/<?=$prd_row->{prdimg_S.$k}?>';"><?=$prd_row->{prdimg_S.$k}?></a>)
						<? } else { ?>
							원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
				<tr>
					<td class="t_name">상세 이미지<?=$k?></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_M<?=$k?>" class="input">
						<? if( @file($imgpath."/".$prd_row->{prdimg_M.$k}) ){ ?>
							<input type="checkbox" name="delimg[]" value="<?=$prd_row->{prdimg_M.$k}?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->{prdimg_M.$k}?>" target="_blank" onMouseOver="document.prdimg<?=$k?>.src='../../data/prdimg/<?=$prd_row->{prdimg_M.$k}?>';"><?=$prd_row->{prdimg_M.$k}?></a>)
						<? } else { ?>
							원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
				<tr>
					<td class="t_name">확대 이미지<?=$k?></td>
					<td class="t_value" colspan="3">
						<input type="file" name="prdimg_L<?=$k?>" class="input">
						<? if( @file($imgpath."/".$prd_row->{prdimg_L.$k}) ){ ?>
							<input type="checkbox" name="delimg[]" value="<?=$prd_row->{prdimg_L.$k}?>">삭제 (<a href="/twcenter/data/prdimg/<?=$prd_row->{prdimg_L.$k}?>" target="_blank" onMouseOver="document.prdimg<?=$k?>.src='../../data/prdimg/<?=$prd_row->{prdimg_L.$k}?>';"><?=$prd_row->{prdimg_L.$k}?></a>)
						<? } else { ?>
							원본 이미지를 등록하시면 해당 이미지가 자동으로 생성됩니다.
						<? } ?>
					</td>
				</tr>
			</table>
		</td>
		<td width="25%" height="100%">
			<table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td align="center" bgcolor="#ffffff">
					<?
					if(@file($imgpath."/".$prd_row->{prdimg_M.$k}))
						echo "<img src='../../data/prdimg/".$prd_row->{prdimg_M.$k}."' name='prdimg2' width='100'>";
					else
						echo "No<br>Image";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<? 
}
?>

<? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 관련상품</td>
	</tr>
</table>
<div id="related">
	<? include  "./ajax_relate_goods.php"; ?>
</div>
<? } ?>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품설명</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" class="t_name">상품간단설명</td>
					<td width="85%" class="t_value">
					<textarea name="stortexp" rows="5" cols="50" style="width:99%" class="textarea"><?=$prd_row->stortexp?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="t_name">상세설명</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
					<?
					$edit_name	  = "content";
					$edit_content = $prd_row->content;
					include "../../webedit/WIZEditor.html";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? if($site_info['mobile_use'] == 'Y'){ ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 모바일 상품설명</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td colspan="3" class="t_name">모바일 상세설명</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
					<?
					$edit_name	  = "mcontent";
					$edit_content = $prd_row->mcontent;
					include "../../webedit/WIZEditor.html";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? } ?>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td></td>
	</tr>
</table>
<div id="gotop">
<table>
	<tr><td><input type="image" src="../image/btn_prdsave.gif"></td></tr>
	<tr><td><a href='<?=$listpage_url?>?<?=$param?>''><img src="../image/btn_prdlist.gif" border="0"></a></td></tr>
</table>
</div>
<br>
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onclick="document.location='<?=$listpage_url?>?<?=$param?>';">
		</td>
	</tr>
</table>
</form>
<script type="text/javascript">
function initMoving(target, position, topLimit, btmLimit) {
	if (!target) return false;

	var obj = target;
	var initTop = position;
	var bottomLimit = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight) - btmLimit - obj.offsetHeight;
	var top = initTop;


	obj.style.position = 'absolute';

	var getTop = function() {
		var browserTop = 0;
		if (typeof(window.pageYOffset) == 'number') {
			browserTop = window.pageYOffset;
	} else if (typeof(document.documentElement.scrollTop) == 'number') {
			browserTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		}
		return browserTop;
	}

	var getSize = function() {
		var myWidth = 0, myHeight = 0;
		if( typeof( window.innerWidth ) == 'number' ) {
			//-- 익스외
			myWidth = window.innerWidth;
			myHeight = window.innerHeight;
		} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			//-- 익스6+
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			//-- 익스6-
			myWidth = document.body.clientWidth;
			myHeight = document.body.clientHeight;
		}
		return [myWidth, myHeight];
	}
 
	function move() {
		var sizeWH = getSize();
		var sizeW = sizeWH[0];
		var sizeH = sizeWH[1];
		 
		if (initTop > 0) {
			pos = getTop() + initTop;
		} else {
			pos = getTop() + sizeH + initTop;
		}

		if (pos > bottomLimit)
			pos = bottomLimit;
		if (pos < topLimit)
			pos = topLimit;
     
 
		interval = top - pos;
		top = top - interval / 5;
		obj.style.top = top + 'px';
		obj.style.left = sizeW - 90 + 'px';

		window.setTimeout(function () {
			move();
		}, 25);
	}
 
	function addEvent(obj, type, fn) {
		if (obj.addEventListener) {
			obj.addEventListener(type, fn, false);
		} else if (obj.attachEvent) {
			obj['e' + type + fn] = fn;
			obj[type + fn] = function() {
				obj['e' + type + fn](window.event);
			}
			obj.attachEvent('on' + type, obj[type + fn]);
		}
	}
 
	addEvent(window, 'load', function () {
		move();
	});
}
</script>
<style type="text/css">
#gotop {
	position: fixed;
	right: 40%;
	top: 280px;
	width: 50px;
	height: 200px;
}
</style>
<script type="text/javascript">initMoving(document.getElementById("gotop"), 200, 100, -300);</script>
<script>setClass01();setCategory();prdlayCheck();lodingComplete();</script>

<? include "../foot.php"; ?>