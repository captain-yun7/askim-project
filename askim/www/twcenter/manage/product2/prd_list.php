<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";

$tmp_param = array();
/*
작업자명	: 이상민
작업일시	: 2020-04-08
작업내용	: param에 페이지번호가 입력되어 페이지 이동불가현상 수정
*/
//if(isset($page)       && $page)       $tmp_param[] = "page=".$page;
if(isset($dep_code)   && $dep_code)   $tmp_param[] = "dep_code=".$dep_code;
if(isset($dep2_code)  && $dep2_code)  $tmp_param[] = "dep2_code=".$dep2_code;
if(isset($dep3_code)  && $dep3_code)  $tmp_param[] = "dep3_code=".$dep3_code;
if(isset($dep4_code)  && $dep4_code)  $tmp_param[] = "dep4_code=".$dep4_code;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;
if(isset($tmp_rows)   && $tmp_rows)   $tmp_param[] = "tmp_rows=".$tmp_rows;
if(isset($s_showset)  && $s_showset)  $tmp_param[] = "s_showset=".$s_showset;
if(isset($recom)      && $recom)      $tmp_param[] = "recom=".$recom;
if(isset($srh_prev)   && $srh_prev)   $tmp_param[] = "srh_prev=".$srh_prev;
if(isset($srh_next)   && $srh_next)   $tmp_param[] = "srh_next=".$srh_next;
if(isset($srh_date)   && $srh_date)   $tmp_param[] = "srh_date=".$srh_date;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

$sql = "select count(prdcode) as all_total from wiz_product2";
$result = query($sql);
$row = sql_fetch_obj($result);
$all_total = $row->all_total;

$prev_period = $srh_prev;
$next_period = $srh_next." 23:59:59";

$where = array();

if(isset($dep_code)   && $dep_code)   $where[] = " wc.catcode LIKE '$dep_code$dep2_code$dep3_code$dep4_code%' ";
if(isset($searchkey)  && $searchkey)  $where[] = " wp.$searchopt LIKE '%$searchkey%' ";
if(isset($recom)      && $recom)      $where[] = " wp.recom = 'Y' ";
if(isset($s_showset)  && $s_showset)  $where[] = " wp.showset = '$s_showset' ";
if(isset($srh_prev) && $srh_prev) {
	$srh_field = ($srh_date == '' || $srh_date == 'wdate') ? 'wp.wdate' : 'wp.mdate';
	$where[] = $srh_field." >= '$prev_period' and ".$srh_field." <= '$next_period'";
}

$search_query   = ($where) ? " AND ".implode(" AND ", $where) : "";

$sql = "
	select distinct wp.prior
		 , wp.prdcode
		 , wp.prdnum
		 , wp.prdname
		 , wp.prdimg_R
	  from wiz_product2 wp
	     , wiz_cprelation2 wc
	 where wp.prdcode != '' 
	   and wc.prdcode = wp.prdcode
	   $search_query
	   order by prior desc, prdcode desc
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

function delConfirm(prdcode){
	if(confirm("삭제하시겠습니까?")){
		document.location = "prd_save.php?mode=delete&prdcode=" + prdcode + "&<?=$param?>";
	}
}

// 체크박스 전체선택
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

// 체크박스 선택해제
function selectCancel(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].prdcode != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverse(form){
		
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectCancel();
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

//선택회원 삭제
function prdDelete(){

	selvalue = selectValue();

	if(selvalue == ""){
		alert("삭제할 상품을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 상품을 정말 삭제하시겠습니까?")){
			document.location = "prd_save.php?mode=delete&<?=$menucodeParam?>&selvalue=" + selvalue;
		}
	}

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

//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">상품관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">상품 검색/추가/수정/삭제 관리합니다.</td>
	</tr>
</table>
<br>
<form name="searchForm" id="searchForm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="setperiod" value="<?php echo $setperiod ?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td bgcolor="ffffff">
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">상품분류</td>
					<td width="85%" class="t_value">
						<select name="dep_code" onChange="catChange(this.form,'1');" class="select">
						<option value=''>:: 1차분류 :: 
						<?
						$sql = "select substring(catcode,1,2) as catcode, catname from wiz_category2 where depthno = 1 order by priorno01 asc";
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
						<option value=''> :: 2차분류 :: 
						<?
						if($dep_code != ''){
							$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category2 where depthno = 2 and catcode like '$dep_code%' order by priorno02 asc";
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
						<option value=''> :: 3차분류 :: 
						<?
						if($dep_code != '' && $dep2_code != ''){
							$sql = "select substring(catcode,5,2) as catcode, catname from wiz_category2 where depthno = 3 and catcode like '$dep_code$dep2_code%' order by  priorno03 asc";
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
						<option value=''> :: 4차분류 :: 
						<?
						if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){
							$sql = "select substring(catcode,7,2) as catcode, catname from wiz_category2 where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%' order by priorno04 asc";
							$result = query($sql);
							while($row = sql_fetch_obj($result)){
								if($row->catcode == $dep4_code)
									echo "<option value='$row->catcode' selected>$row->catname";
								else
									echo "<option value='$row->catcode'>$row->catname";
							}
						}
						?>
						</select>&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" name="recom" value="Y" <? if($recom == "Y") echo "checked"; ?>></span>추천상품
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
					<td class="t_name">조건검색</td>
					<td class="t_value">
						
						<table border="0" cellspacing="0" cellpadding="1">
							<tr>
								<td>
									<select name="searchopt" class="select">
										<option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
										<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
									</select>&nbsp;
								</td>
								<td>
									<input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
								</td>
						</table>
						<script language="javascript">
						searchopt = document.searchForm.searchopt;
						for(ii=0; ii<searchopt.length; ii++){
							if(searchopt.options[ii].value == "<?=$searchopt?>")
							searchopt.options[ii].selected = true;
						}
						</script>
					</td>
				</tr>
				<tr>
					<td class="t_name">상품진열 여부</td>
					<td class="t_value">
						<span style='vertical-align: middle;'><input type="radio" name="s_showset" value="Y" <? if($s_showset == "Y") echo "checked";?>></span>진열&nbsp;
						<span style='vertical-align: middle;'><input type="radio" name="s_showset" value="N"  <? if($s_showset == "N") echo "checked";?>></span>미진열
					</td>
				</tr>

			</table>
		</td>
	</tr>
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

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table> 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 상품수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 상품수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
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
			<!-- <img src="../image/btn_prdadd.gif" style="cursor:hand" onClick="document.location='prd_input.php?mode=insert';" align="absmiddle"> -->
			<input type="button" value="상품등록" class="btnListchk3" onClick="document.location='prd_input.php?mode=insert&<?=$menucodeParam?>';">
			</form>
		</td>
	</tr>
	<tr><td height="3"></td></tr>
</table> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td class="t_rd" colspan=20></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
		<th width="5%">번호</th>
		<th width="10%">상품코드</th>
		<th width="5%">이미지</th>
		<th>상품명</th>
		<th width="25%">상품분류</th>
		<th width="7%">진열순서</th>
		<th width="10%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan=20></td></tr>
	</form>
	<?php
	$sql = "
		select wp.prdcode
			 , wp.prdnum
			 , wp.prdname
			 , wp.prdimg_R
			 , wp.prior
			 , wc.catcode
		  from wiz_product2 wp
			 , wiz_cprelation2 wc
		 where wp.prdcode != '' 
		   and wc.prdcode = wp.prdcode 
		   $search_query
		 order by prior desc, prdcode desc 
		 limit $start, $rows
	";
	$result = query($sql);
	
	while($row = sql_fetch_obj($result)){
		
		if(!isset($row->content)) $row->content = array();
		$row->content = str_replace("\n","",$row->content);

		$catname = "HOME";

		$t_catcode1 = substr($row->catcode,0,2);
		$t_catcode2 = substr($row->catcode,0,4);
		$t_catcode3 = substr($row->catcode,0,6);
		$t_catcode4 = substr($row->catcode,0,8);

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".$row->prdimg_R)){
			$prdimg = "/twcenter/images/noimg_R.gif";
		}else{
			$prdimg = "/twcenter/data/product2/".$row->prdimg_R;
		}

		$sql_cate = "
			select *
			  from wiz_category2
			 where (catcode like '$t_catcode1%' and depthno = 1) 
			    or (catcode like '$t_catcode2%' and depthno = 2) 
				or (catcode like '$t_catcode3%' and depthno = 3) 
				or (catcode like '$t_catcode4%' and depthno = 4) 
				or (catcode = '$row->catcode')
			 order by depthno asc
		";
		$result_cate = query($sql_cate);

		while($prow_cate = sql_fetch_obj($result_cate)){
			$catname .= " &gt; $prow_cate->catname";
		}

	?>
	<form name="frm<?=$no?>">
	<input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
	<input type="hidden" name="menucode" value="<?=$menucode?>">
	<tr> 
		<td height="85" align="center"><input type="checkbox" name="select_checkbox"></td>
		<td align="center"><?=$no?></td>
		<td align="center"><?=$row->prdcode?></td>
		<td align="center">
			<span class="img_border2">
				<!--
				<img src="../../data/product2/<?=$row->prdimg_R?>" align="absmiddle" width="65" height="65" class="thumbnail">
				-->
				<img src="<?=$prdimg?>" align="absmiddle" width="65" height="65" class="thumbnail">
				<?=$tt?>
			</span>
		</td>
		<td style="padding-left:20px">
			<a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><?=$row->prdname?></a>
		</td>
		<td style="padding:0 0 0 10px"><?=$catname?></td>
		<td align="center">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="prd_save.php?mode=prior&posi=upup&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/upup_icon.gif" border="0" title="10단계 위로"></a></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=up&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/up_icon.gif" border="0" title="1단계 위로"></a></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=down&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/down_icon.gif" border="0" title="1단계 아래로"></a></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=downdown&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/downdown_icon.gif" border="0" title="10단계 아래로"></a> </td>
				</tr>
				<tr>
					<td></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=maximum&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/btn_prev2.gif" height="20px" border="0" title="최상위"></a></td>
					<td width="2"></td>
					<td><a href="prd_save.php?mode=prior&posi=lowest&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/btn_next2.gif" height="20px border="0" title="최하위"></a></td>
					<td width="2"></td>
				</tr>
			</table>
		</td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="document.location='prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>'">
			<img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="delConfirm('<?=$row->prdcode?>');">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	</form>
	<?
		$no--;
	}

	if($total <= 0){
	?>
	<tr><td height=30 colspan=10 align=center>등록된 상품이 없습니다.</td></tr>
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
		<td width="33%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
		<td width="33%" align="right"></td>
	</tr>
</table>

<? include "../foot.php"; ?>
