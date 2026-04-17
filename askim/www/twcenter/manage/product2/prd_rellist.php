<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "prdcode=$prdcode&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";
//--------------------------------------------------------------------------------------------------

?>
<!DOCTYPE html>
<html>
<head>
<title>:: 관련상품 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.8.3.min.js"></script>
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
function addReation(){

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
		alert("상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("관련상품으로 등록하시겠습니까?")){
			$.ajax({
				type: "post",
				url: "prd_rellist_ajax.php",
				data: {selected: selected, prdcode: '<?=$prdcode?>'},
				success: function (data) {
					data = data.trim();
					if(data == 'ok'){
						alert("관련상품이 등록되었습니다.");
						opener.parent.location.reload();
						window.close();
					}
				},
				error: function (data, status, err) {
					alert("서버와의 통신이 실패했습니다.");
					alert(err)
					return;
				}
			});
			//document.location = "prd_save.php?mode=reladd&page=<?=$page?>&<?=$param?>&selected=" + selected;
		}else{
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

//-->
</script>
</head>
<body>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">관련상품등록</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span><br />

<table width="99%" border="0" cellspacing="0" cellpadding="6" align="center">
	<tr>
		<td align="center">
			<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
			<input type="hidden" name="page" value="<?=$page?>">
			<input type="hidden" name="prdcode" value="<?=$prdcode?>">
			<table width="99%" border="0" cellpadding="0" cellspacing="0">
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
									$result = query($sql) or error("sql error");
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
									<?
									if($dep_code != ''){
										$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category2 where depthno = 2 and catcode like '$dep_code%' order by priorno02 asc";
										$result = query($sql) or error("sql error");
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
									<?
									if($dep_code != '' && $dep2_code != ''){
										$sql = "select substring(catcode,5,2) as catcode, catname from wiz_category2 where depthno = 3 and catcode like '$dep_code$dep2_code%' order by  priorno03 asc";
										$result = query($sql) or error("sql error");
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
									<?
									if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){
										$sql = "select substring(catcode,7,2) as catcode, catname from wiz_category2 where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%' order by  priorno04 asc";
										$result = query($sql) or error("sql error");
										while($row = sql_fetch_obj($result)){
											if($row->catcode == $dep4_code)
												echo "<option value='$row->catcode' selected>$row->catname";
											else
												echo "<option value='$row->catcode'>$row->catname";
										}
									}
									?>
									</select>
									<input type="checkbox" name="recom" value="Y" <? if($recom == "Y") echo "checked"; ?>>추천상품
								</td>
							</tr>
							<tr>
								<td class="t_name">조건검색</td>
								<td class="t_value">
									<table border="0" cellspacing="0" cellpadding="1">
										<tr>
											<td>
												<select name="searchopt" class="select">
													<option value="prdnum" <? if($searchopt == "prdnum") echo "selected"; ?>>상품코드
													<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
												</select>&nbsp;
											</td>
											<td>
												<input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
											</td>
											<!-- <td>
											<input type="image" src="../image/btn_search.gif" align="absmiddle">
											</td> -->
										</tr>
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
						</table>
						<br>
						<table width="100%" cellspacing="1" cellpadding="3" border="0">
							<tr>
								<td align="center">
									<input type="submit" value="검색" class="search_btn2">&nbsp;
									<input type="button" value="초기화" class="search_default" onclick="location.href='<?=$PHP_SELF?>'">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
			<br>
			<?
			$sql = "select count(prdcode) as all_total from wiz_product2";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_obj($result);
			$all_total = $row->all_total;

			if($dep_code != "") $catcode_sql    = " and wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' ";
			if($searchkey != "") $searchkey_sql = " and wp.$searchopt like '%$searchkey%' ";
								 $showset_sql   = " and wp.showset != 'N' ";

			if($recom != "") $recom_sql = " and wp.recom = 'Y' ";
			$sql = "

				select 
				
					distinct wp.prior, wp.prdcode, wp.prdnum, wp.prdname, wp.prdimg_R, wp.showset
					
				from 
				
					wiz_product2 wp, 
					wiz_cprelation2 wc 
					
				where 
				
					wp.prdcode != '' 
					$catcode_sql 
					$searchkey_sql 
					$showset_sql 
					$recom_sql and 
					wc.prdcode = wp.prdcode 
					order by prior desc, prdcode desc
					
			";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows  = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

			?>
			<table width="99%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="25">총 상품수 : <b><?=$all_total?></b> , 검색 상품수 : <b><?=$total?></b></td>
					<td align="right">
						<input type="button" value="관련상품등록" class="base_btm reg" onclick="addReation()">
						<span class="tip_br2"></span>					
					</td>
				</tr>
			</table>
			<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<form>
				<tr><td class="t_rd" colspan="20"></td></tr>
					<tr class="t_th">
					<th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
					<th width="12%">상품코드</th>
					<th width="10%">이미지</th>
					<th>상품명</th>
				</tr>
				<tr><td class="t_rd" colspan="20"></td></tr>
				</form>
				<?
				if($dep_code != "")  $catcode_sql   = " and wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' ";
				if($searchkey != "") $searchkey_sql = " and wp.$searchopt like '%$searchkey%' ";
									 $showset_sql   = " and wp.showset != 'N' ";

				if($recom != "") $recom_sql = " and wp.recom = 'Y' ";

				$sql = "

					select 
					
						distinct wp.prior, wp.prdcode, wp.prdnum, wp.prdname, wp.prdimg_R, wp.prdprice, wp.showset 
						
					from 
					
						wiz_product2 wp, 
						wiz_cprelation2 wc 
						
					where 
					
						wp.prdcode != '' 
						$catcode_sql 
						$searchkey_sql 
						$showset_sql 
						$recom_sql and 
						wc.prdcode = wp.prdcode 
						order by prior desc, prdcode desc limit $start, $rows
						
				";
				$result = query($sql) or error("sql error");

				while($row = sql_fetch_obj($result)){
					if($row->content != ""){
						$row->content = str_replace("\n","",$row->content);
					}
					if($row->prdimg_R == "") $row->prdimg_R = "/twcenter/images/noimage.gif";
					else $row->prdimg_R = "/twcenter/data/product2/$row->prdimg_R";
				?>
				<form name="<?=$row->prdcode?>" action="product_save.php">
				<input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
				<tr>
					<td align="center" height="55"><input type="checkbox" name="select_checkbox"></td>
					<td align="center"><?=$row->prdcode?></td>
					<td align="center"><img src="<?=$row->prdimg_R?>" width="50" height="50" border="0"></td>
					<td><?=$row->prdname?></td>
				</tr>
				<tr><td colspan="20" class="t_line"></td></tr>
				</form>
				<?
					$no--;
				}

				if($total <= 0){
				?>
				<tr><td height='30' colspan=7 align=center>등록된 상품이 없습니다.</td></tr>
				<tr><td colspan="20" class="t_line"></td></tr>
				<?
				}
				?>
			</table>

			<br>
			<table width="99%" height="10" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>

<div class="helpTip2" style="padding: 0 0 0 5px">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 상품진열에 <font color="#000000"><strong>'진열함'</strong></font> 으로 체크된 상품만 관련상품리스트에 노출됩니다.
	  </div>
	</div>
</div>

</body>
</html>