<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code";
$param .= "&searchopt=$searchopt&searchkey=$searchkey&stock_opt=$stock_opt&$menucodeParam";
//--------------------------------------------------------------------------------------------------

?>

<script language="JavaScript" type="text/javascript">
<!--
// 가격변동 옵션 수정
function editOption(prdcode){

	var url = "option_edit.php?prdcode=" + prdcode + "&<?=$menucodeParam?>";
	window.open(url,"editOption","height=400, width=800, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no");

}

// 상품정보 엑셀다운
function excelDown(){
	document.location = "prd_excel_stock.php?<?=$param?>";
}

//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">재고관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">재고를 관리합니다.</td>
	</tr>
</table>

<br>
<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">상품분류</td>
		<td width="85%" class="t_value">
			<select name="dep_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 1차분류 ::
			<?
			$sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1";
			$result = query($sql) or error("sql error");
			while($row = sql_fetch_obj($result)){
				if($row->catcode == $dep_code)
					echo "<option value='$row->catcode' selected>$row->catname";
				else
					echo "<option value='$row->catcode'>$row->catname";
			}
			?>
			</select>
			<select name="dep2_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 2차분류 ::
			<?
			if($dep_code != ''){
			$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%'";
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
			<select name="dep3_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 3차분류 ::
			<?
			if($dep_code != '' && $dep2_code != ''){
			$sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%'";
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
			<select name="dep4_code" onChange="this.form.submit();" class="select">
				<option value=''>:: 4차분류 ::
				<?
				if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){
					$sql = "select substring(catcode,7,2) as catcode, catname from wiz_category where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%'";
					$result = query($sql) or error("sql error");
					while($row = sql_fetch_obj($result)){
						if($row->catcode == $dep4_code) echo "<option value='$row->catcode' selected>$row->catname";
						else echo "<option value='$row->catcode'>$row->catname";
					}
				}
				?>
			</select>
			</td>
			</tr>
			<tr>
			<td class="t_name">재고상태</td>
			<td class="t_value">
				<span style="vertical-align: middle"><input type="radio" name="stock_opt" value="all" onclick="this.form.submit();" <? if($stock_opt=="all" || $stock_opt=="") echo "checked"; ?>></span>전체&nbsp;
				<span style="vertical-align: middle"><input type="radio" name="stock_opt" value="shortage" onclick="this.form.submit();" <? if($stock_opt=="shortage") echo "checked"; ?>></span>품절&nbsp;
				<span style="vertical-align: middle"><input type="radio" name="stock_opt" value="lack" onclick="this.form.submit();" <? if($stock_opt=="lack") echo "checked"; ?>></span>부족&nbsp;
				<span style="vertical-align: middle"><input type="radio" name="stock_opt" value="reserve" onclick="this.form.submit();" <? if($stock_opt=="reserve") echo "checked"; ?>></span>여유&nbsp;
				<span style="vertical-align: middle"><input type="radio" name="stock_opt" value="showset" onclick="this.form.submit();" <? if($stock_opt=="showset") echo "checked"; ?>></span>진열안함
			</td>
			</tr>
			<tr>
			<td class="t_name">검색조건</td>
			<td class="t_value">
				<select name="searchopt" onChange="this.form.page.value=1;" class="select">
				<option value="">:: 선택 ::
				<option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
				<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
				<option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
				</select>
				<input type="text" size="18" name="searchkey" value="<?=$searchkey?>" class="input">
				<!-- <input type="image" src="../image/btn_search.gif" align="absmiddle"> -->
			</td>
			</tr>
			</table>

			<div class="helpTip2">
				<h4>체크사항</h4>
				<div class="content">
				<div class="explain">
				- 재고 수량의 경우 상품등록시 재고량을 수량에 체크한 경우에 노출됩니다.<br />
				- 해당 상품의 재고량 값이 수량에 체크가 된 경우에만 고객이 상품을 구매시 재고량이 카운트됩니다. <br />
				- 재고가 100개일 경우 안전 재고를 10으로 설정하면 여유 값에는 (현재 재고량 - 안전재고) 값이 노출됩니다.
				</div>
				</div>
			</div>


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
			<?
			$sql = "select prdcode from wiz_product";
			$result = query($sql) or error("sql error");
			$all_total = sql_fetch_row($result);

			if(!empty($dep_code))        $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code%' and ";
			if($stock_opt == "shortage") $stock_sql = " (wp.optcode like '%^0^%' or stock <= 0 or shortage ='Y') and ";
			if($stock_opt == "lack")     $stock_sql = " (wp.optcode like '%^1^%' or stock < savestock) and ";
			if($stock_opt == "reserve")  $stock_sql = " (wp.optcode not like '%^0^%' and stock > savestock)  and ";
			if($stock_opt == "showset")  $stock_sql = "wp.showset = 'N' and ";

			if(!empty($searchopt)) $search_sql = "wp.$searchopt like '%$searchkey%' and ";

			$sql = "select distinct wp.prdcode from wiz_product wp, wiz_cprelation wc
			              where $catcode_sql $stock_sql $search_sql wc.prdcode = wp.prdcode order by wp.prior desc, wp.prdcode desc";
			//echo $sql;
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 20;
			$lists = 5;

			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><span class="title_msg">총 상품수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 상품수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
					<td align="right">
						<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
					</td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="t_rd" colspan="20"></td></tr>
				<tr class="t_th">
					<th width="8%">상품코드</th>
					<th width="8%"></th>
					<th>상품명</th>
					<th width="10%">판매가</th>
					<th width="10%">여유</th>
					<th width="10%">재고</th>
					<th width="10%">안전재고</th>
					<th width="10%">기능</th>
				</tr>
				<tr><td class="t_rd" colspan="20"></td></tr>
				<?
				$sql = "
				
				select 
				
					distinct wp.prdcode, wp.optcode, wp.optcode2, wp.optvalue, wp.prdimg_R, wp.prdname, wp.sellprice, wp.prior, wp.stock, wp.savestock, wp.opt_use , wp.shortage
					
				from
				
					wiz_product wp, 
					wiz_cprelation wc

				where 
				
					$catcode_sql
					$stock_sql
					$search_sql
					wc.prdcode = wp.prdcode
					order by wp.prior desc, wp.prdcode desc limit $start, $rows
					
				";
				//echo "<pre>".$sql."</pre>";
				$result = query($sql) or error("sql error");
				while(($row = sql_fetch_obj($result)) && $rows){

					if($row->prdimg_R == "" || !@file(WIZHOME_DATA_DIR."/twcenter/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/twcenter/images/noimage.gif";
					else                     $row->prdimg_R = "/twcenter/data/prdimg/".$row->prdimg_R;

					$reserve = $row->stock - $row->savestock;

					// 옵션별 재고부족상품
					if(!strcmp($row->opt_use, "Y")) {
						$short_list = "<table align=center cellpadding=2 cellspacing=1 width=100% border=0 class='t_style'>";
						$short_list .= "<tr><td class='t_name_main'>옵션명</td><td class='t_name_main'>옵션재고</td></tr>";
						$opt1_arr = explode("^", $row->optcode);
						$opt2_arr = explode("^", $row->optcode2);
						$opt_tmp = explode("^^", $row->optvalue);

						$no = 0;
						if(!empty($row->optcode2)){
							for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
								for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
									list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

									if($stock <= 1) $short_list .= "<tr height='25'><td width=70% bgcolor=#ffffff>&nbsp;<font color='red'>".$opt1_arr[$ii]."/".$opt2_arr[$jj]."</td><td width=30% bgcolor=#ffffff align='center'> ".number_format($stock)." 개</font></td></tr>";
									else $short_list .= "<tr height='25'><td width=70% bgcolor=#ffffff>&nbsp;<font color='#1f83ff'>".$opt1_arr[$ii]."/".$opt2_arr[$jj]."</td><td width=30% bgcolor=#ffffff align='center'> ".number_format($stock)." 개</font></td></tr>";

									$no++;
								}
							}

						} else {
							
							for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {

								list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

								if($stock <= 1) $short_list .= "<tr height='25'><td width=70% bgcolor=#ffffff>&nbsp;<font color='red'>".$opt1_arr[$ii]."</td><td width=30% bgcolor=#ffffff align='center'> ".number_format($stock)." 개</font></td></tr>";
								else $short_list .= "<tr height='25'><td width=70% bgcolor=#ffffff>&nbsp;<font color='#1f83ff'>".$opt1_arr[$ii]."</td><td width=30% bgcolor=#ffffff align='center'> ".number_format($stock)." 개</font></td></tr>";

								$no++;
							}


						}

						$short_list .= "</table>";
					} else {
						$short_list = "";
					}
			?>
				<form action="prd_save.php?<?=$param?>" method="post">
				<input type="hidden" name="tmp">
				<input type="hidden" name="mode" value="stock">
				<input type="hidden" name="page" value="<?=$page?>">
				<input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
				<input type="hidden" name="menucode" value="<?=$menucode?>">
				<tr>
					<td height="85" align="center"><?=$row->prdcode?></td>
					<td>
						<a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&shortpage=Y&<?=$param?>&page=<?=$page?>"><span class="img_border2"><img src="<?=$row->prdimg_R?>" width="65" height="70" border="0"></span></a>
					</td>
					<td style="padding:10 0 10 0">
						<a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&shortpage=Y&<?=$param?>&page=<?=$page?>"><?=$row->prdname?></a></span><table><tr><td></td></tr></table><?=$short_list?>
					</td>
					<td align="center"><?=number_format($row->sellprice)?>원</td>


					<?if($row->shortage=="S"){?>
						<td align="center"><?=$reserve?></td>
						<td align="center"><input type="text" size="6" name="stock" value="<?=$row->stock?>" style="text-align:right" Onlynum="true" <? if($row->stock > 0) echo "class='input'"; else echo "class='inputR'"; ?>></td>
						<td align="center"><input type="text" size="6" name="savestock" value="<?=$row->savestock?>" class="input" style="text-align:right" Onlynum="true"></td>
						<td align="center"><input type="image" src="../image/btn_edit_s.gif"> <img src="../image/btn_option.gif" style="cursor:hand" onClick="editOption('<?=$row->prdcode?>');"></td>
					<?}else if($row->shortage=="N"){?>
						<td align="center">-</td>
						<td align="center"><font color="blue">무제한</font></td>
						<td align="center">-</td>
						<td align="center">-</td>
					<?}else if($row->shortage=="Y"){?>
						<td align="center">-</td>
						<td align="center"><font color="red">품절</font></td>
						<td align="center">-</td>
						<td align="center">-</td>
					<?}?>



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
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td height="5"></td></tr>
				<tr>
					<td width="33%"></td>
					<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
					<td width="33%"></td>
				</tr>
			</table>


<? include "../foot.php"; ?>