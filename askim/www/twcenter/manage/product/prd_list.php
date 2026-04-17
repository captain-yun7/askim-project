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
if(isset($dep_code)   && $dep_code)   $tmp_param[] = "dep_code=".$dep_code;
if(isset($dep2_code)  && $dep2_code)  $tmp_param[] = "dep2_code=".$dep2_code;
if(isset($dep3_code)  && $dep3_code)  $tmp_param[] = "dep3_code=".$dep3_code;
if(isset($dep4_code)  && $dep4_code)  $tmp_param[] = "dep4_code=".$dep4_code;
if(isset($special)    && $special)    $tmp_param[] = "special=".$special;
if(isset($display)    && $display)    $tmp_param[] = "display=".$display;
if(isset($coupon_use) && $coupon_use) $tmp_param[] = "coupon_use=".$coupon_use;
if(isset($coupon_use) && $coupon_use) $tmp_param[] = "coupon_use=".$coupon_use;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;
if(isset($brand)      && $brand)      $tmp_param[] = "brand=".$brand;
if(isset($shortage)   && $shortage)   $tmp_param[] = "shortage=".$shortage;
if(isset($stock)      && $stock)      $tmp_param[] = "stock=".$stock;
if(isset($tmp_rows)   && $tmp_rows)   $tmp_param[] = "tmp_rows=".$tmp_rows;
if(isset($mobileShow) && $mobileShow) $tmp_param[] = "mobileShow=".$mobileShow;
if(isset($noCate)     && $noCate)     $tmp_param[] = "noCate=".$noCate;
if(isset($srh_prev)   && $srh_prev)   $tmp_param[] = "srh_prev=".$srh_prev;
if(isset($srh_next)   && $srh_next)   $tmp_param[] = "srh_next=".$srh_next;
if(isset($srh_date)   && $srh_date)   $tmp_param[] = "srh_date=".$srh_date;

/*if(isset($timesale)   && $timesale)   $tmp_param[] = "timesale=".$timesale;*/

if(isset($sdel_type) && $sdel_type) {
	for($i=0; $i<count($sdel_type); $i++) {
		$deltype_encode = urlencode('sdel_type[]');
		$tmp_param[] = $deltype_encode.'='.$sdel_type[$i];
	}
}

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

//--------------------------------------------------------------------------------------------------

$sql = "select count(prdcode) as all_total from wiz_product";
$result = query($sql);
$row = sql_fetch_obj($result);
$all_total = $row->all_total;

$prev_period = $srh_prev;
$next_period = $srh_next." 23:59:59";

$where = array();

if(isset($dep_code)   && $dep_code)   $where[] = " wc.catcode LIKE '$dep_code$dep2_code$dep3_code$dep4_code%' ";
if(isset($special)    && $special)    $where[] = " wp.$special = 'Y' ";
if(isset($display)    && $display)    $where[] = " wp.showset = '$display' ";
if(isset($mobileShow) && $mobileShow) $where[] = " wp.mobileShow = '$mobileShow' ";
if(isset($searchopt)  && $searchopt)  $where[] = " wp.$searchopt LIKE '%$searchkey%' ";
if(isset($coupon_use) && $coupon_use) $where[] = " wp.coupon_use = '$coupon_use' ";
if(isset($brand)      && $brand)      $where[] = " wp.brand = '$brand' ";
if(isset($noCate)     && $noCate)     $where[] = " wc.catcode = '00000000' ";
if(isset($shortage)   && $shortage) {
	if(!strcmp($shortage, "N")) $where[] = " (wp.shortage = '$shortage' or wp.shortage = '') ";
	else $where[] = " wp.shortage = '$shortage' ";
}
if(!isset($shortage)) $shortage = '';
if(!strcmp($shortage, "S")) $where[] = " wp.stock <= '$stock' ";
if(isset($srh_prev) && $srh_prev) {
	$srh_field = ($srh_date == '' || $srh_date == 'wdate') ? 'wp.wdate' : 'wp.mdate';
	$where[] = $srh_field." >= '$prev_period' and ".$srh_field." <= '$next_period'";
}
//if($oper_info['del_batch_use'] == 'Y') {

	if (isset($sdel_type) && $sdel_type) {

		$join_array = implode("/", $sdel_type);
		$join_val   = explode("/", $join_array);

		foreach($join_val as $key => $value){
			if(isset($value)) $join_path .= " or wp.del_type = '$value'";
		}
		$join_path      = substr($join_path,3);
		$where[]        = " ({$join_path})";
	}

//}

if(isset($timesale)  && $timesale)   $where[] = " wp.timesale_use = '".$timesale."' ";

$search_query   = ($where) ? " and ".implode(" and ", $where) : "";

$sql = "
	select distinct wp.prdcode
	  from wiz_product wp
	  left join wiz_cprelation wc on wp.prdcode = wc.prdcode
	 where wp.prdcode!=''
	   $search_query
";
$result = query($sql);
$total = sql_fetch_row($result);

if(!empty($tmp_rows)) $tmp_rows = $tmp_rows; else $tmp_rows = 20;
$rows = $tmp_rows;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

?>

<script language="JavaScript" type="text/javascript">
<!--

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
		if(document.forms[i].prdcode != null){
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
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function prdDelete(){

	var i;
	var selected = "";

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selected = selected + document.forms[i].prdcode.value + "|";
				}
			}
	}

	if(selected == ""){
		alert("삭제할 상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 상품을 정말 삭제하시겠습니까?")){
			document.location = "prd_save.php?mode=delete&page=<?=$page?>&<?=$param?>&selected=" + selected;
		}else{
			selectEmpty();
			return;
		}
	}
	return;

}

// 카테고리 변경
function catChange(form, idx){
	if(idx == "1"){
		form.dep2_code.options[0].selected = true;
		form.dep3_code.options[0].selected = true;
		form.dep4_code.options[0].selected = true;
	}else if(idx == "2"){
		form.dep3_code.options[0].selected = true;
		form.dep4_code.options[0].selected = true;
	}else if(idx == "3"){
		form.dep4_code.options[0].selected = true;
	}
	form.page.value = 1;
	form.submit();
}

// 상품복사
function prdCopy(prdcode){
	if(confirm("동일한 상품을 하나 더 자동등록합니다.")){
		document.location = "prd_save.php?mode=prdcopy&prdcode=" + prdcode + "&<?=$menucodeParam?>";
	}
}

// 상품 진열여부
function prdShow(prdcode){
	if(confirm("진열여부를 수정하시겠습니까?") == true){
		var show_id;
		var m_show_id;

		show_id   = ($('input[id=prd_show_'+prdcode+']').is(":checked")) ? "Y" : "N";

		<?//2024-03-25 [모바일 진열] 사용시에만 모바일진열 값으로 저장하기 
		if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use']=="Y"){ ?>
			m_show_id = ($('input[id=m_prd_show_'+prdcode+']').is(":checked")) ? "Y" : "N";
		<? }else{ ?>
			m_show_id = '';
		<? } ?>

		var result_show_chk = show_id+"/"+m_show_id;
		document.location = "prd_save.php?mode=show&prdcode=" + prdcode+"&result_show_chk="+result_show_chk+"&<?=$param?>";
	} else {
		return;
	}
}

// 상품가격/적립금 업데이트
function prdUpdate(prdcode){
	var sellprice = $("#tmp_sellprice_"+prdcode).val();
	var reserve   = $("#tmp_reserve_"+prdcode).val();

	$.get("/twcenter/manage/product/prd_save.php?mode=prdReserve&prdcode="+prdcode+"&sellprice="+sellprice+"&reserve="+reserve, function(data){
		if(data == 'ok'){
			alert("수정되었습니다.");
			var href = "/twcenter/manage/product/prd_list.php?<?=$param?>";
			document.location.href = href;
		}
	});
}

function prdShowCheck(){

	if(confirm("진열여부를 일괄 수정하시겠습니까?") == true){

		var prdcode    = "";
		var show_id    = "";
		var m_show_id  = "";
		var mode       = "show_check";

		for(i=0;i<document.forms.length;i++){
			if(document.forms[i].elements['prdcode'] != null){

				prdcode        = prdcode + document.forms[i].elements['prdcode'].value+"|";

				a_prdcode      = document.forms[i].elements['prdcode'].value;
				show_id_val    = ($('input[id=prd_show_'+a_prdcode+']').is(":checked"))   ? "Y" : "N";

				<? //2024-03-25 [모바일 진열] 사용시에만 모바일진열 값으로 저장하기 
				if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use']=="Y"){ ?>
				m_show_id_val  = ($('input[id=m_prd_show_'+a_prdcode+']').is(":checked")) ? "Y" : "N";
				<? }else{ ?>
				m_show_id_val = '';
				<? } ?>
				
				show_id        = show_id + show_id_val+"|";
				m_show_id      = m_show_id + m_show_id_val+"|";

			}
		}
		var postData = { "prdcode": prdcode, "show_id": show_id, "m_show_id":m_show_id, "mode":mode, "menucode":"<?=$menucode?>"};

		$.ajax({
			url:'prd_save.php',
			type:'post',
			data:postData,
			traditional: true,
			success:function(data){
				if(data == 'ok'){
					alert("진열여부가 일괄변경되었습니다.");
					location.reload();
				}
			},
			error:function(){
			}
		});
		return false;
	} else {
		return;
	}

}

function prdDeliCheck(){

	var i;
	var selected = "";

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selected = selected + document.forms[i].prdcode.value + "|";
				}
			}
	}

	if(selected == ""){
		alert("상품배송비 조건을 변경할 상품을 선택하지 않았습니다.");
		return;
	}else{
		var uri = "prd_delivery_batch.php?selvalue=" + selected + "&<?=$menucodeParam?>";
		window.open(uri,"batch","width=750,height=200,resizable=yes");
	}
	return;


}

function prdPriceCheck(){

	if(confirm("가격 및 적립금을 일괄 수정하시겠습니까?") == true){

		var prdcode     = "";
		var price_id    = "";
		var reserve_id  = "";
		var mode        = "priceAllCheck";

		for(i=0;i<document.forms.length;i++){
			if(document.forms[i].elements['prdcode'] != null){

				prdcode        = prdcode + document.forms[i].elements['prdcode'].value+"|";

				a_prdcode      = document.forms[i].elements['prdcode'].value;
				price_id_val   = $("input[id=tmp_sellprice_"+a_prdcode+"]").val();
				reserve_id_val = $("input[id=tmp_reserve_"+a_prdcode+"]").val();

				price_id       = price_id + price_id_val+"|";
				reserve_id     = reserve_id + reserve_id_val+"|";

			}
		}
		var postData = { "prdcode": prdcode, "price_id": price_id, "reserve_id":reserve_id, "mode":mode, "menucode":"<?=$menucode?>"};

		$.ajax({
			url:'prd_save.php',
			type:'post',
			data:postData,
			traditional: true,
			success:function(data){
				if(data == 'ok'){
					alert("가격 및 적립금이 일괄 변경되었습니다.");
					var href = "/twcenter/manage/product/prd_list.php?<?=$param?>";
					document.location.href = href;
				}
			},
			error:function(){
			}
		});
		return false;
	} else {
		return;
	}

}
// 상품정보 엑셀다운
function excelDown(){

	var i;
	var prdselect = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					prdselect = prdselect + document.forms[i].prdcode.value + "|";
				}
			}
	}

	if(prdselect != ''){
		var url = "prd_excel.php?prdselect="+prdselect+"&<?=$param?>";
	} else {
		var url = "prd_excel.php?<?=$param?>";
	}
	window.open(url,"excelDown","height=240, width=560, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 상품정보 엑셀입력
function excelUp() {
	var url = "prd_excel_up.php";
	window.open(url,"excelUp","height=550, width=600, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 재고여부
function chgShortage(frm) {

	frm.page.value = 1;

	if(frm.shortage.value == "S") {
		frm.stock.disabled = false;
		frm.stock.focus();
	} else {
		frm.stock.disabled = true;
		frm.submit();
	}

}

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selvalue = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selvalue = selvalue + document.forms[i].prdcode.value + "|";
				}
			}
	}
	return selvalue;
}

//상품이동
function movePrd(){
	selvalue = selectValue();

	if(selvalue == ""){
		alert("이동할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_move.php?selvalue=" + selvalue + "&<?=$menucodeParam?>";
		window.open(uri,"movePrd","width=750,height=350");
	}
}

// 상품복사
function copyPrd(){
	selvalue = selectValue();
	if(selvalue == ""){
		alert("복사할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_copy.php?selvalue=" + selvalue + "&<?=$menucodeParam?>";
		window.open(uri,"copyPrd","width=750,height=350,resizable=yes");
	}
}

function naver_all_up(){
	document.location = "/twcenter/nhn/tw_all_product.php";
}

function naver_mini_up(){
	document.location = "/twcenter/nhn/tw_mini_product.php";
}

$(function(){

	$("#pcshowall").click(function(){
		if($("#pcshowall").prop("checked")) {
			$("input[name=prd_show]").prop("checked",true);
		} else {
			$("input[name=prd_show]").prop("checked",false);
		}
	})

	$("#mobileshowall").click(function(){
		if($("#mobileshowall").prop("checked")) {
			$("input[name=m_prd_show]").prop("checked",true);
		} else {
			$("input[name=m_prd_show]").prop("checked",false);
		}
	})

	$('.inputComma').bind({
		'focus' : function() {
			this.value = removeComma(this.value);
			if($(this).attr('data-decimal') && !parseInt($(this).attr('data-decimal'))) this.value = this.value;
		},
		'blur' : function() {
			this.value = won_Comma(this.value);
			if($(this).attr('data-decimal') && !parseInt($(this).attr('data-decimal'))) this.value = this.value;
		},
		'keyup' : function() {
			if($(this).attr('data-decimal') && !parseInt($(this).attr('data-decimal'))) this.value = this.value;

		}
	}).each(function() {
		if($(this).attr('data-decimal') && !parseInt($(this).attr('data-decimal'))) this.value = Math.floor(removeComma(this.value));
		this.value = won_Comma(this.value);
	});

});

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

/** 원본이미지 미리보여주기 **/
$(function() {

	var xOffset = 10;
	var yOffset = 30;

	$(document).on("mouseover",".thumbnail",function(e){
		 
		$("body").append("<p id='preview'><img src='"+ $(this).attr("src") +"'></p>");
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast"); //미리보기 화면 설정 셋팅
	});
	 
	$(document).on("mousemove",".thumbnail",function(e){
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});
	 
	$(document).on("mouseout",".thumbnail",function(){
		$("#preview").remove();
	});
	
});

//-->
</script>
<style>
/** 미리보기 스타일 **/
#preview{
	z-index: 9999;
	position:absolute;
	border:1px solid #ccc;
	background:#fff;
	padding:5px 0 0 0 ;
	display:none;
	color:#fff;
}
</style>
</head>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">상품목록</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">전체상품 목록 및 검색합니다.</td>
	</tr>
</table>

<br>
<form name="searchForm" id="searchForm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="setperiod" value="<?php echo $setperiod ?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">상품분류</td>
		<td width="85%" colspan="3" class="t_value">
			<select name="dep_code" onChange="catChange(this.form,'1');" class="select">
				<option value=''>:: 1차분류 ::
				<?php
				$sql = "
					SELECT substring(catcode,1,2) AS catcode
						 , catname
					  FROM wiz_category
					 WHERE depthno = 1 
					 ORDER BY priorno01 ASC
				";
				$result = query($sql);
				while($row = sql_fetch_obj($result)){
					if($row->catcode == $dep_code)
						echo "<option value='$row->catcode' selected>$row->catname";
					else
						echo "<option value='$row->catcode'>$row->catname";
				}
				?>
			</select>
			<select name="dep2_code" onChange="catChange(this.form,'2');" class="select">
				<option value=''>:: 2차분류 ::
				<?php
				if($dep_code != ''){

					$sql = "
						SELECT substring(catcode,3,2) AS catcode
							 , catname
						  FROM wiz_category
						 WHERE depthno = 2 
						   AND catcode LIKE '$dep_code%'
						 ORDER BY priorno02 ASC
					";
				$result = query($sql);
				while($row = sql_fetch_obj($result)){
					if($row->catcode == $dep2_code)
						echo "<option value='$row->catcode' selected>$row->catname";
					else
						echo "<option value='$row->catcode'>$row->catname";
					}
				}
				?>
			</select>
			<select name="dep3_code" onChange="catChange(this.form,'3');" class="select">
				<option value=''>:: 3차분류 ::
				<?php
				if($dep_code != '' && $dep2_code != ''){

					$sql = "
						SELECT substring(catcode,5,2) as catcode
							 , catname
						  FROM wiz_category
						 WHERE depthno = 3 
						   AND catcode LIKE '$dep_code$dep2_code%'
						 ORDER BY  priorno03 ASC
					";
					$result = query($sql);
					while($row = sql_fetch_obj($result)){
						if($row->catcode == $dep3_code)
							echo "<option value='$row->catcode' selected>$row->catname";
						else
							echo "<option value='$row->catcode'>$row->catname";
						}
					}
				?>
			</select>
			<select name="dep4_code" onChange="catChange(this.form,'4');" class="select">
				<option value=''>:: 4차분류 ::
				<?php
				if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){

					$sql = "
						SELECT substring(catcode,7,2) as catcode
							 , catname
						  FROM wiz_category
						 WHERE depthno = 4 
						   AND catcode LIKE '$dep_code$dep2_code$dep3_code%'
						 ORDER BY  priorno04 ASC
					";
					$result = query($sql);
					while($row = sql_fetch_obj($result)){
						if($row->catcode == $dep4_code)
							echo "<option value='$row->catcode' selected>$row->catname";
						else
							echo "<option value='$row->catcode'>$row->catname";
						}
					}
				?>
			</select> 
			<input type="checkbox" name="noCate" value="N" <?php if($noCate == 'N') echo "checked" ?>> 카테고리 미지정 상품
		</td>
	</tr>
	<tr>
		<td width="15%" class="t_name">기간검색</td>
		<td width="85%" class="t_value" colspan="3">
			<select name="srh_date" class="select">
				<option value="wdate" <? if($srh_date == "wdate") echo "selected"; ?>>등록일
				<option value="mdate" <? if($srh_date == "mdate") echo "selected"; ?>>수정일
			</select>
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
		<td width="15%" class="t_name">검색조건</td>
		<td width="35%" class="t_value">
			<select name="searchopt" onChange="this.form.page.value=1;" class="select">
				<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
				<option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
				<option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
			</select>
			<input type="text" size="25" name="searchkey" value="<?=$searchkey?>" class="input">
		</td>
		<td width="15%" class="t_name">쿠폰적용</td>
		<td width="35%" class="t_value">
			<select name="coupon_use" onChange="this.form.page.value=1;this.form.submit();" class="select">
				<option value="">:: 선택 ::
				<option value="Y" <? if($coupon_use == "Y") echo "selected"; ?>>예
				<option value="N" <? if($coupon_use == "N") echo "selected"; ?>>아니오
			</select>
		</td>
	</tr>
	<tr>
		<td class="t_name">재고여부</td>
		<td class="t_value">
			<select name="shortage" onChange="chgShortage(this.form)" class="select">
				<option value="">:: 재고여부 ::
				<option value="Y" <? if($shortage == "Y") echo "selected"; ?>>품절상품</option>
				<option value="N" <? if($shortage == "N") echo "selected"; ?>>무제한</option>
				<option value="S" <? if($shortage == "S") echo "selected"; ?>>수량</option>
			</select>
			<input type="text" size="5" name="stock" value="<?=$stock?>" class="input" <? if($shortage != "S") echo "disabled" ?>>개 이하
		</td>
		<td class="t_name">진열여부</td>
		<td class="t_value">
			<select name="display" onChange="this.form.page.value=1;this.form.submit();" class="select">
				<option value="">:: 선택 ::
				<option value="Y" <? if($display == "Y") echo "selected"; ?>>진열함
				<option value="N" <? if($display == "N") echo "selected"; ?>>진열안함
			</select>&nbsp;
			<?
//			if($site_info['mobile_use'] == 'Y'){
			if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use']=="Y"){
			?>
				<input type="checkbox" name="mobileShow" value="Y" <? if($mobileShow == "Y") echo "checked"; ?> onChange="this.form.page.value=1;this.form.submit();"> 모바일진열
			<? } ?>
		</td>
	</tr>
	<tr>
		<td class="t_name">그룹</td>
		<td class="t_value">
			<select name="special" onChange="this.form.page.value=1;this.form.submit();" class="select">
				<option value="">:: 그룹선택 ::
				<option value="new" <? if($special == "new") echo "selected"; ?>>신상품
				<option value="best" <? if($special == "best") echo "selected"; ?>>베스트상품
				<option value="popular" <? if($special == "popular") echo "selected"; ?>>인기상품
				<option value="recom" <? if($special == "recom") echo "selected"; ?>>추천상품
				<option value="sale" <? if($special == "sale") echo "selected"; ?>>세일상품
			</select>
		</td>
		<td class="t_name">브랜드</td>
		<td class="t_value">
			<select name="brand" onChange="this.form.page.value=1;this.form.submit();" class="select">
				<option value="">:: 브랜드선택 ::
				<?
				$sql = "select idx, brdname from wiz_brand where brduse != 'N' order by priorno asc";
				$result = query($sql);
				while($row = sql_fetch_arr($result)) {
				?>
				<option value="<?=$row['idx']?>" <? if($brand == $row['idx']) echo "selected"; ?>><?=$row['brdname']?></option>
				<?
				}
				?>
			</select>
		</td>
	</tr>
	<?php
	//if($oper_info['del_batch_use'] == 'Y') {
		$del_name  = array('전체','기본 배송정책','무료배송','상품별 배송비','수신자부담(착불)');
		$del_value = array('AA','DA','DB','DC','DD');
	?>
	<tr>
		<td class="t_name">배송비 조건</td>
		<td class="t_value" colspan='3'>
			<?php echo checkSearch($del_value, $del_name, $join_array, 'sdel_type'); ?>
		</td>
		<!--td class="t_name">타임세일 여부</td> <?/*2021-03-15 완전한 기능이 아니므로 주석처리, 배송비 조건 colspan='3' 추가*/?>
		<td class="t_value">
			<select name="timesale" class="select">
				<option value="">:: 선택 ::
				<option value="Y" <? if($timesale == "Y") echo "selected"; ?>>사용함</option>
				<option value="N" <? if($timesale == "N") echo "selected"; ?>>사용안함</option>
			</select>&nbsp;
		</td-->
	</tr>
	<?// } ?>
	<?php
	if($oper_info['naver_use']=="Y"){
	?>
	<!-- <tr>
		<td width="15%" class="t_name">네이버 지식쇼핑<img src="../image/naver_icon.gif" style="vertical-align:middle; margin:0 0 1px 5px"></td>
		<td width="85%" colspan="3" class="t_value">
			<input type="button" value="전체상품업데이트" onclick="naver_all_up()" class="btn_nhn">
			<input type="button" value="요약상품업데이트" onclick="naver_mini_up()" class="btn_nhn2">
		</td>
	</tr> -->
	<?php } ?>

</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center">
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
		</td>
	</tr>
</table>
</form>

<br>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<form>
	<tr>
		<td><span class="title_msg">총 상품수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 상품수 : <strong id="total_prd_cnt"><?=$total?></strong></span>
		<td align="right">
			<select name="tmp_rows" onchange="location.href='?<?=$param?>&tmp_rows='+this.value" class="select">
				<option value="10"  <? if($tmp_rows==10)  echo "selected";?>>10개씩 출력</option>
				<option value="20"  <? if($tmp_rows==20)  echo "selected";?>>20개씩 출력</option>
				<option value="30"  <? if($tmp_rows==30)  echo "selected";?>>30개씩 출력</option>
				<option value="50"  <? if($tmp_rows==50)  echo "selected";?>>50개씩 출력</option>
				<option value="70"  <? if($tmp_rows==70)  echo "selected";?>>70개씩 출력</option>
				<option value="100" <? if($tmp_rows==100) echo "selected";?>>100개씩 출력</option>
			</select>
			<input type="button" value="엑셀상품등록" class="btnExcel2" onClick="excelUp();">
			<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
			<input type="button" value="상품등록" class="btnListchk3" onClick="document.location='prd_input.php?<?=$param?>'">
		</td>
	</tr>
	</form>
</table>
<div style="height:5px"></div>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
		<th width="10%">상품코드</th>
		<th width="5%"></th>
		<th>상품명</th>
		<th width="6%">상품가격</th>
		<th width="6%">적립금</th>
		<th width="2%"></th>
		<th width="8%">재고량상태</th>
		<th width="8%">진열순서</th>
		<?
		if($oper_info['chk_prdshow']=="Y"){
			if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use'] == 'Y'){
		?>
		<!--
		<th width="12%" valign="middle">
		진열여부(<span style="vertical-align: middle" title="PC버전"><input type="checkbox" id="pcshowall"></span>P/<span style="vertical-align: middle" title="MOBILE버전"><input type="checkbox" id="mobileshowall"></span>M)</th>
		-->
		<?if($site_info['mobile_show_use'] == 'Y'){?>
		<th width="12%" valign="middle">
		진열여부(<span style="vertical-align: middle" title="PC버전"><input type="checkbox" id="pcshowall"></span>P/<span style="vertical-align: middle" title="MOBILE버전"><input type="checkbox" id="mobileshowall"></span>M)</th>
		<?}else{?>
		<th width="12%" valign="middle">
		진열여부(<span style="vertical-align: middle" title="PC버전"><input type="checkbox" id="pcshowall"></span>)</th>
		<?}?>
		<?
			} else {
		?>
		<th width="12%" valign="middle">진열여부&nbsp;<span style="vertical-align: middle"><input type="checkbox" id="pcshowall"></span></th>
		<?
			}
		}
		?>
		<!--th width="6%">타임세일여부</td -->
		<?// if($oper_info['del_batch_use'] == 'Y') { ?>
		<th width="8%">배송비</td>
		<?// } ?>
		<th width="13%">기능</td>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	</form>
		<?php
		$sql = "
			select distinct wp.prdcode 
				 , wp.prdimg_R 
				 , wp.prdname 
				 , wp.sellprice 
				 , wp.reserve 
				 , wp.prior 
				 , wp.stock 
				 , wp.showset 
				 , wp.shortage 
				 , wp.mobileShow
				 , wp.del_type
				 , wp.timesale_use
				 , wp.timelimit
				 , wcat.catname
			  from wiz_product wp
			  left join wiz_cprelation wc on wp.prdcode = wc.prdcode
			  left join wiz_category wcat on wc.catcode = wcat.catcode
			 where wp.prdcode != ''
			  $search_query
			 order by wp.prior desc, wp.prdcode desc 
			 limit $start, $rows
		";

		$result = query($sql);

		while(($row = sql_fetch_obj($result)) && $rows){

			// 상품 이미지
			if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/twcenter/images/noimg_R.gif";
			else $row->prdimg_R = "/twcenter/data/prdimg/".$row->prdimg_R;

			if($row->shortage == "Y")                               $shortage_status = "<font color=red>품절</font>";
			else if($row->shortage == "N" || empty($row->shortage)) $shortage_status = "<font color=#D86771>무제한</font>";
			else if($row->shortage == "S")                          $shortage_status = $row->stock;

			/*switch($row->timesale_use){
				case "Y":
					$timesale_yn = "사용함";
					$timesale_yn .= "<br>".preg_replace("/ /i", "<br>", $row->timelimit)."";
					break;
				case "N":
					$timesale_yn = "사용안함";
					break;
			}*/
		?>
	<form id="frm_<?=$row->prdcode?>" name="<?=$row->prdcode?>" action="product_save.php" onSubmit="return false;">
	<input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
	<tr>
		<td align="center" height="55"><input type="checkbox" name="select_checkbox"></td>
		<td align="center"><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><?=$row->prdcode?></a></td>
		<td align="center">
			<a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><span class="img_border2"><img src="<?=$row->prdimg_R?>" width="48" height="48" border="0" class="thumbnail" style="vertical-align:middle"></span></a>
		</td>
		<td style="padding:0 0 0 10px">
			<? if($row->catname) echo '<p style="color:#bbb">['.$row->catname.']</p>';?>
			<a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><?=$row->prdname?></a></td>
		<td align="center">
			<input type="text" name="tmp_sellprice" id="tmp_sellprice_<?=$row->prdcode?>" class="input inputComma" value='<?=number_format($row->sellprice)?>' size="10" style="text-align:right" data-decimal="0" Onlynum="true">
		</td>
		<td align="center">
			<input type="text" name="tmp_reserve" id="tmp_reserve_<?=$row->prdcode?>" class="input inputComma" value='<?=number_format($row->reserve)?>' size="7" style="text-align:right" data-decimal="0" Onlynum="true">
		</td>
		<td align="center"><input type="button" value="적용" class="base_btm blue2" onclick="prdUpdate('<?=$row->prdcode?>');"/></td>
		<td align="center"><?=$shortage_status?></td>
		<td align="center">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="prd_save.php?mode=prior&posi=upup&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/upup_icon.gif" border="0" alt="10단계 위로" style="vertical-align:middle"></a></td>
					<td width="2"></td>
					<td></td>
				</tr>
				<tr><td height="2"></td></tr>
				<tr>
					<td><a href="prd_save.php?mode=prior&posi=up&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/up_icon.gif" border="0" alt="1단계 위로" style="vertical-align:middle"></a></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=down&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/down_icon.gif" border="0" alt="1단계 아래로" style="vertical-align:middle"></a></td>
				</tr>
				<tr><td height="2"></td></tr>
				<tr>
					<td></td>
					<td width="2"></td>
					<td>
						<a href="prd_save.php?mode=prior&posi=downdown&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/downdown_icon.gif" border="0" alt="10단계 아래로" style="vertical-align:middle"></a>
					</td>
				</tr>
			</table>
		</td>
		 <?if($oper_info['chk_prdshow']=="Y"){?>
		<td align="center">
			<input type="checkbox" name="prd_show" id="prd_show_<?=$row->prdcode?>" <?if($row->showset=="Y") echo 'checked="checked"'?>/>
			<input type="hidden" name="prd_hidden_<?=$row->prdcode?>">&nbsp;
			<? if($site_info['mobile_use']=="Y" && $site_info['mobile_show_use']=="Y"){?>
			<input type="checkbox" name="m_prd_show" id="m_prd_show_<?=$row->prdcode?>" <?if($row->mobileShow=="Y") echo 'checked="checked"'?>/>
			<input type="hidden" name="m_prd_hidden_<?=$row->prdcode?>">&nbsp;

			<? } ?>
			<input type="button" value="적용" class="base_btm blue2" onclick="prdShow('<?=$row->prdcode?>');"/>
		</td>
		<?}?>
		<!--진열여부적용 끝-->
		<!--td align="center"><?php echo $timesale_yn; ?></td-->
		<?// if($oper_info['del_batch_use'] == 'Y') { ?>
		<td align="center"><?php echo deliver_name_prd($row->del_type) ?></td>
		<?// } ?>

		<td align="center">
			<input type="button" value="수정" class="base_btm blue" onclick="document.location='prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>'">
			<input type="button" value="삭제" class="base_btm gray" onclick="selectEmpty();this.form.select_checkbox.checked=true;prdDelete('<?=$row->prdcode?>');">
			<input type="button" value="복사" class="base_btm reg" onclick="prdCopy('<?=$row->prdcode?>');">		
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	</form>
	<?
	$no--;
	$rows--;
	}
	if($total <= 0){
	?>
	<tr><td height='30' colspan=12 align=center>등록된 상품이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="33%">
			<input type="button" value="선택삭제" class="btnListchk gray2" onclick="prdDelete()">
			<input type="button" value="상품이동" class="btnListchk" onclick="movePrd()">
			<input type="button" value="상품복사" class="btnListchk" onclick="copyPrd()">
			
		</td>
		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
		<td width="33%" align="right">
			<input type="button" value="배송조건 일괄변경" class="btnListchk2" onclick="prdDeliCheck()">
			<input type="button" value="진열여부 일괄변경" class="btnListchk2" onclick="prdShowCheck()">
			<input type="button" value="가격/적립금 일괄변경" class="btnListchk2" onclick="prdPriceCheck()">
		</td>
	</tr>
</table>

<? include "../foot.php"; ?>
