<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "prdcode=$prdcode&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code";
$param .= "&special=$special&showset=$showset&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&brand=$brand&shortage=$shortage&stock=$stock&$menucodeParam";
//--------------------------------------------------------------------------------------------------

?>
<!DOCTYPE html>
<html>
<head>
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

//선택상품 등록
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
					if(data == 'ok'){
						alert("관련상품이 등록되었습니다.");
						//opener.parent.location.reload();
						opener.parent.$("#related").load("/twcenter/manage/product/ajax_relate_goods.php?prdcode=<? echo $prdcode; ?>");
						window.close();
					}
				},
				error: function (data, status, err) {
					alert("서버와의 통신이 실패했습니다.");
					alert(err)
					return;
				}
			});
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
<span class="tip_br5"></span>

<table width="100%" border="0" cellspacing="0" cellpadding="6" align="center">
	<tr>
		<td align="center">

			<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
			<input type="hidden" name="page" value="<?=$page?>">
			<input type="hidden" name="prdcode" value="<?=$prdcode?>">
			<table width="99%" cellspacing="1" cellpadding="3" border="0" class="t_style">
				<tr>
					<td width="15%" class="t_name">&nbsp; 상품분류</td>
					<td width="85%" colspan="3" class="t_value">
						<select name="dep_code" onChange="catChange(this.form,'1');" class="select">
							<option value=''>:: 1차분류 ::
							<?
							$sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1 order by priorno01 asc";
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
								$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%' order by priorno02 asc";
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
								$sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%' order by  priorno03 asc";
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
								$sql = "select substring(catcode,7,2) as catcode, catname from wiz_category where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%' order by  priorno04 asc";
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

					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">&nbsp; 검색조건</td>
					<td class="t_value" colspan="3">
						<select name="searchopt" onChange="this.form.page.value=1;" class="select">
							<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
							<option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
							<option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
						</select>
						<input type="text" size="18" name="searchkey" value="<?=$searchkey?>" class="input">
						<input type="submit" value="검색" class="base_btn4 gray">
					</td>
				</tr>
				<tr>
					<td class="t_name">&nbsp; 재고여부</td>
					<td class="t_value" colspan="3">
						<select name="shortage" onChange="chgShortage(this.form)" class="select">
							<option value="">:: 재고여부 ::
							<option value="Y" <? if($shortage == "Y") echo "selected"; ?>>품절상품</option>
							<option value="N" <? if($shortage == "N") echo "selected"; ?>>무제한</option>
							<option value="S" <? if($shortage == "S") echo "selected"; ?>>수량</option>
						</select>
						<input type="text" size="5" name="stock" value="<?=$stock?>" class="input" <? if($shortage != "S") echo "disabled" ?>>개 이하
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">&nbsp; 브랜드</td>
					<td class="t_value">
						<select name="brand" onChange="this.form.page.value=1;this.form.submit();" class="select">
							<option value="">:: 브랜드선택 ::
							<?
							$sql = "select idx, brdname from wiz_brand where brduse != 'N' order by priorno asc";
							$result = query($sql) or error("sql error");
							while($row = sql_fetch_arr($result)) {
							?>
							<option value="<?=$row['idx']?>" <? if($brand == $row['idx']) echo "selected"; ?>><?=$row['brdname']?></option>
							<?
							}
							?>
						</select>
					</td>
					<td width="15%" class="t_name">&nbsp; 쿠폰적용</td>
					<td class="t_value">
						<select name="coupon_use" onChange="this.form.page.value=1;this.form.submit();" class="select">
							<option value="">:: 선택 ::
							<option value="Y" <? if($coupon_use == "Y") echo "selected"; ?>>예
							<option value="N" <? if($coupon_use == "N") echo "selected"; ?>>아니오
						</select>
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">&nbsp; 그룹</td>
					<td width="35%" class="t_value">
						<select name="special" onChange="this.form.page.value=1;this.form.submit();" class="select">
							<option value="">:: 그룹선택 ::
							<option value="new" <? if($special == "new") echo "selected"; ?>>신상품
							<option value="best" <? if($special == "best") echo "selected"; ?>>베스트상품
							<option value="popular" <? if($special == "popular") echo "selected"; ?>>인기상품
							<option value="recom" <? if($special == "recom") echo "selected"; ?>>추천상품
							<option value="sale" <? if($special == "sale") echo "selected"; ?>>세일상품
							<option value="stock" <? if($special == "stock") echo "selected"; ?>>품절상품
						</select>
					</td>
					<td width="15%" class="t_name">&nbsp; 진열여부</td>
					<td width="35%" class="t_value">
						<select name="showset" onChange="this.form.page.value=1;this.form.submit();" class="select">
							<option value="">:: 선택 ::
							<option value="Y" <? if($showset == "Y") echo "selected"; ?>>진열함
							<option value="N" <? if($showset == "N") echo "selected"; ?>>진열안함
						</select>
					</td>
				</tr>
			</table>
			</form>
			<br>
			<?
			$sql = "select prdcode from wiz_product";
			$result = query($sql) or error("sql error");
			$all_total = sql_fetch_row($result);

			if(!empty($dep_code))    $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' and ";
			if(!empty($special))     $special_sql = "wp.$special = 'Y' and ";
			if(!empty($showset))     $showset_sql = "wp.showset = '$showset' and ";
			if(!empty($searchopt) && !empty($searchkey))   $search_sql  = "wp.$searchopt like '%$searchkey%' and ";
			if(!empty($coupon_use))  $coupon_sql  = "wp.coupon_use = '$coupon_use' and ";
			if(!empty($brand))       $brand_sql   = "wp.brand = '$brand' and ";
			if(!empty($shortage)) {
				if(!strcmp($shortage, "N")) $shortage_sql = " (wp.shortage = '$shortage' or wp.shortage = '') and ";
				else                        $shortage_sql = " wp.shortage = '$shortage' and ";
			}
			if(!strcmp($shortage, "S")) $stock_sql = " wp.stock <= '$stock' and ";

			$sql = "
			
				select 
				
					distinct wp.prdcode 
				
				from 
				
					wiz_product wp, 
					wiz_cprelation wc
					
				where 
					wp.prdcode != '$prdcode' and
					$catcode_sql 
					$special_sql 
					$showset_sql 
					$search_sql 
					$coupon_sql 
					$brand_sql 
					$shortage_sql 
					$stock_sql 
					wc.prdcode = wp.prdcode 
					order by wp.prior desc, wp.prdcode desc
					
			";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 16;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			?>
			<table width="99%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="25">총 상품수 : <b><?=$all_total?></b> , 검색 상품수 : <b><?=$total?></b></td>
					<td align="right"><input type="button" value="관련상품등록" class="base_btm blue" onclick="addReation()"></td>
				</tr>
			</table>
				<table width="99%" border="0" cellspacing="0" cellpadding="0">
					<form>
					<tr><td class="t_rd" colspan="20"></td></tr>
					<tr class="t_th">
					<th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
						<th width="15%">상품코드</th>
						<th width="13%">이미지</th>
						<th>상품명</th>
						<th width="15%">상품가격</th>
						<th width="15%">적립금</th>
						<th width="10%">진열</th>
					</tr>
					<tr><td class="t_rd" colspan="20"></td></tr>
					</form>
					<?
					$sql = "
					
						select 
						
							distinct wp.prdcode, wp.prdimg_R, wp.prdname, wp.sellprice, wp.prior, wp.stock ,wp.reserve, wp.showset
							
						from 
						
							wiz_product wp, 
							wiz_cprelation wc
						
						where 
							wp.prdcode != '$prdcode' and
							$catcode_sql 
							$special_sql 
							$showset_sql 
							$search_sql 
							$coupon_sql 
							$brand_sql 
							$shortage_sql 
							$stock_sql 
							wc.prdcode = wp.prdcode 
							order by wp.prior desc, wp.prdcode desc limit $start, $rows
							
					";
					$result = query($sql) or error("sql error");

					while(($row = sql_fetch_obj($result)) && $rows){
					if($row->prdimg_R == "") $row->prdimg_R = "/twcenter/images/noimage.gif";
					else $row->prdimg_R = "/twcenter/data/prdimg/$row->prdimg_R";

					$showset = ($row->showset == 'Y') ? "<font color='red'>상품진열</font>" : "미진열";

					?>
					<form name="<?=$row->prdcode?>" action="product_save.php">
					<input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
					<tr>
						<td align="center" height="55"><input type="checkbox" name="select_checkbox"></td>
						<td align="center"><?=$row->prdcode?></td>
						<td align="center"><img src="<?=$row->prdimg_R?>" width="50" height="50" border="0"></td>
						<td><?=$row->prdname?></td>
						<td align="right" style="padding:0 10px 0 0"><?=number_format($row->sellprice)?>원</td>
						<td align="right" style="padding:0 10px 0 0"><?=number_format($row->reserve)?>원</td>
						<td align="center"><?=$showset?></td>
					</tr>
					<tr><td colspan="20" class="t_line"></td></tr>
					</form>
					<?
						$no--;
						$rows--;
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

			</td>
		</tr>
	</table>
</body>
</html>