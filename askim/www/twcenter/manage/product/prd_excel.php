<?
include "../../common.php";
include "../../inc/admin_check.php";

if($exceldown != "ok"){
?>
<html>
<head>
<title>:: 상품정보 다운로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function selBasic(frm){
	frm.c_prdgroup.checked = true;
	frm.c_prdname.checked = true;
	frm.c_prdcom.checked = true;
	frm.c_origin.checked = false;
	frm.c_showset.checked = false;
	frm.c_shortage.checked = false;
	frm.c_stock.checked = false;
	frm.c_category.checked = true;
	frm.c_brand.checked = true;

	frm.c_sellprice.checked = true;
	frm.c_conprice.checked = true;
	frm.c_reserve.checked = false;
	frm.c_option.checked = true;

	frm.c_image.checked = true;
	frm.c_stortexp.checked = false;
	frm.c_content.checked = true;
}

function selAll(frm){
	frm.c_prdgroup.checked = true;
	frm.c_prdname.checked = true;
	frm.c_prdcom.checked = true;
	frm.c_category.checked = true;
	frm.c_brand.checked = true;
	frm.c_origin.checked = true;
	frm.c_showset.checked = true;
	frm.c_shortage.checked = true;
	frm.c_stock.checked = true;

	frm.c_sellprice.checked = true;
	frm.c_conprice.checked = true;
	frm.c_reserve.checked = true;
	frm.c_option.checked = true;

	frm.c_image.checked = true;
	frm.c_stortexp.checked = true;
	frm.c_content.checked = true;
}
//-->
</script>
</head>

<body leftmargin="5" topmargin="5">
<table><tr><td height="4"></td></table>
<form name="frm" action="" method="post">
<input type="hidden" name="exceldown" value="ok">

<input type="hidden" name="dep_code" value="<?=$dep_code?>">
<input type="hidden" name="dep2_code" value="<?=$dep2_code?>">
<input type="hidden" name="dep3_code" value="<?=$dep3_code?>">
<input type="hidden" name="special" value="<?=$special?>">
<input type="hidden" name="showset" value="<?=$showset?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="6" class="t_style">
  <tr>
    <td bgcolor="ffffff">
    <table><tr><td></td></tr></table>
     <table cellspacing="2" cellpadding="0" border="0">
       <tr>
        <td><font color="2369C9"><b>선택구분</b></font></td>
        <td><input type="radio" name="sel_gubun" onClick="selBasic(this.form);" checked><font color="red"><b>기본선택</b></font></td>
		  <td><input type="radio" name="sel_gubun" onClick="selAll(this.form);"><font color="red"><b>전체선택</b></font></td>
		  <td></td>
		  <td></td>
		</tr>
		<tr><td height="6"></td></tr>
      <tr>
        <td width="80"><font color="2369C9"><b>기본정보</b></font></td>
        <td width="100"><input type="checkbox" name="c_prdcode" value="Y" checked>상품코드</td>
		  <td width="100"><input type="checkbox" name="c_prdname" value="Y" checked>상품명</td>
		  <td width="100"><input type="checkbox" name="c_prdgroup" value="Y" checked>상품그룹</td>
		  <td width="100"><input type="checkbox" name="c_prdcom" value="Y" checked>제조사</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="c_category" value="Y" checked>카테고리</td>
		  <td><input type="checkbox" name="c_brand" value="Y" checked>브랜드</td>
		  <td><input type="checkbox" name="c_origin" value="Y">원산지</td>
		  <td><input type="checkbox" name="c_showset" value="Y">상품진열</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="c_shortage" value="Y">품절여부</td>
		  <td><input type="checkbox" name="c_stock" value="Y">재고량</td>
		  <td></td>
		  <td></td>
		</tr>
	   <tr><td height="6"></td></tr>
		<tr>
		   <td><font color="2369C9"><b>가격및옵션</b></font></td>
			<td><input type="checkbox" name="c_sellprice" value="Y" checked>판매가</td>
			<td><input type="checkbox" name="c_conprice" value="Y" checked>정가</td>
			<td><input type="checkbox" name="c_reserve" value="Y">적립금</td>
			<td><input type="checkbox" name="c_option" value="Y" checked>옵션</td>
		</tr>
		<tr><td height="6"></td></tr>
		<tr>
		   <td><font color="2369C9"><b>상품사진/설명</b></font></td>
			<td><input type="checkbox" name="c_image" value="Y" checked>상품사진</td>
			<td><input type="checkbox" name="c_stortexp" value="Y">관리자주석</td>
			<td><input type="checkbox" name="c_content" value="Y" checked>상세설명</td>
			<td></td>
		</tr>
    </table>
   </td>
 </tr>
</table>
<table align="center">
  <tr><td height="5"></td></tr>
  <tr>
    <td>
    	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
    	<img src="../image/btn_close_l.gif" style="cursor:hand" onClick="self.close();">
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?
}else{

	$filename = "prd_info[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	echo "<style>\n";
	echo ".xl40\n";
	echo "        {mso-style-parent:style0;\n";
	echo "        mso-number-format:'0_ ';\n";
	echo "        text-align:center;\n";
	echo "        border:.5pt solid black;\n";
	echo "        background:white;\n";
	echo "        mso-pattern:auto none;\n";
	echo "        white-space:normal;}\n";
	echo "-->\n";
	echo "</style>\n";

	if(!empty($dep_code))   $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' and ";
	if(!empty($special))    $special_sql = "wp.$special = 'Y' and ";
	if(!empty($display))    $display_sql = "wp.showset = '$display' and ";
	if(!empty($mobileShow)) $mobileShow_sql = "wp.mobileShow = '$mobileShow' and ";
	if(!empty($searchopt))  $search_sql  = "wp.$searchopt like '%$searchkey%' and ";
	if(!empty($coupon_use)) $coupon_sql  = "wp.coupon_use = '$coupon_use' and ";
	if(!empty($brand))      $brand_sql   = "wp.brand = '$brand' and ";
	if(!empty($shortage)) {
		if($shortage ==  "N") $shortage_sql = " (wp.shortage = '$shortage' or wp.shortage = '') and ";
		else $shortage_sql = " wp.shortage = '$shortage' and ";
	}
	if($shortage ==  "S") $stock_sql = " wp.stock <= '$stock' and ";

	if(isset($srh_prev) && $srh_prev) {

		$prev_period = $srh_prev;
		$next_period = $srh_next." 23:59:59";

		$srh_field = ($srh_date == '' || $srh_date == 'wdate') ? 'wp.wdate' : 'wp.mdate';
		$date_sql = $srh_field." >= '$prev_period' and ".$srh_field." <= '$next_period' and ";
	}
	
	
	if (isset($sdel_type) && $sdel_type) {

		$join_array = implode("/", $sdel_type);
		$join_val   = explode("/", $join_array);

		foreach($join_val as $key => $value){
			if(isset($value)) $join_path .= " or wp.del_type = '$value'";
		}
		$join_path      = substr($join_path,3);
		$del_sql        = "and ({$join_path})";
	}

	echo "<table border=1>\n";
	echo "  <tr align=center style=font-weight:bold>\n";
	if($c_prdcode == "Y") echo "<td bgcolor=#C0C0C0>상품코드</td>\n";
	if($c_prdname == "Y") echo "<td bgcolor=#C0C0C0>상품명</td>\n";
	if($c_category == "Y") echo "<td bgcolor=#C0C0C0>상품카테고리</td>\n";
	if($c_prdgroup == "Y") echo "<td bgcolor=#C0C0C0>상품그룹</td>\n";
	if($c_brand == "Y") echo "<td bgcolor=#C0C0C0>브랜드</td>\n";
	if($c_prdcom == "Y") echo "<td bgcolor=#C0C0C0>제조사</td>\n";
	if($c_origin == "Y") echo "<td bgcolor=#C0C0C0>원산지</td>\n";
	if($c_showset == "Y") echo "<td bgcolor=#C0C0C0>상품진열</td>\n";
	if($c_shortage == "Y") echo "<td bgcolor=#C0C0C0>품절여부</td>\n";
	if($c_stock == "Y") echo "<td bgcolor=#C0C0C0>재고량</td>\n";
	if($c_sellprice == "Y") echo "<td bgcolor=#C0C0C0>판매가</td>\n";
	if($c_conprice == "Y") echo "<td bgcolor=#C0C0C0>정가</td>\n";
	if($c_reserve == "Y") echo "<td bgcolor=#C0C0C0>적립금</td>\n";
	if($c_option == "Y") {
		echo "<td bgcolor=#C0C0C0>일반 옵션1</td>\n";
		echo "<td bgcolor=#C0C0C0>일반 옵션2</td>\n";
		echo "<td bgcolor=#C0C0C0>일반 옵션3</td>\n";
		echo "<td bgcolor=#C0C0C0>가격추가 옵션1</td>\n";
		echo "<td bgcolor=#C0C0C0>가격추가 옵션2</td>\n";
		echo "<td bgcolor=#C0C0C0>가격/재고 옵션1</td>\n";
		echo "<td bgcolor=#C0C0C0>가격/재고 옵션2</td>\n";
		echo "<td bgcolor=#C0C0C0>가격/재고 옵션값</td>\n";
	}
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품대표사진</td>\n";
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품사진1</td>\n";
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품사진2</td>\n";
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품사진3</td>\n";
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품사진4</td>\n";
	if($c_image == "Y") echo "<td bgcolor=#C0C0C0>상품사진5</td>\n";
	if($c_stortexp == "Y") echo "<td bgcolor=#C0C0C0>관리자주석</td>\n";
	if($c_content == "Y") echo "<td bgcolor=#C0C0C0>상세설명</td>\n";
	echo "   </tr>";


	$array_prdselect = explode('|',$prdselect ?? "");
	if(count($array_prdselect)-1 > 0){

		$tmp_prdselect = "";
		foreach($array_prdselect as $key => $value){
			if(!empty($value)) $tmp_prdselect .= " or wp.prdcode = '{$value}'";
		}
		$tmp_prdselect = substr($tmp_prdselect,3);
		$selprd_sql = " ({$tmp_prdselect})";
	} else {
		$selprd_sql = "wp.prdcode != '' ";
	}


	$sql = "
	
		select 
		
			wp.*, 
			wy.catname,
			wb.brdname
			
		from
		
			wiz_product as wp left join wiz_cprelation as wc on wp.prdcode = wc.prdcode
			left join wiz_category as wy on wc.catcode = wy.catcode
			left join wiz_brand as wb on wp.brand = wb.idx
			
		where
		
			$catcode_sql
			$special_sql
			$display_sql
			$mobileShow_sql
			$search_sql
			$coupon_sql
			$brand_sql
			$shortage_sql
			$stock_sql
			$date_sql
			$selprd_sql
			$del_sql
			
			order by wp.prior desc, wp.prdcode desc
			
		";

	//echo "<pre>".$sql."</pre>";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	while($row = sql_fetch_obj($result)){

		if($row->new == "Y") $row->prdgroup .= "/신상품";
		if($row->popular == "Y") $row->prdgroup .= "/인기상품";
		if($row->recom == "Y") $row->prdgroup .= "/추천상품";
		if($row->sale == "Y") $row->prdgroup .= "/세일상품";
		if($row->best == "Y") $row->prdgroup .= "/베스트상품";

		echo "<tr>\n";
		if($c_prdcode == "Y") echo "<td>$row->prdcode</td>\n";
		if($c_prdname == "Y") echo "<td>$row->prdname</td>\n";
		if($c_category == "Y") echo "<td>$row->catname</td>\n";
		if($c_prdgroup == "Y") echo "<td>$row->prdgroup</td>\n";
		if($c_brand == "Y") echo "<td>$row->brdname</td>\n";
		if($c_prdcom == "Y") echo "<td>$row->prdcom</td>\n";
		if($c_origin == "Y") echo "<td>$row->origin</td>\n";
		if($c_showset == "Y") echo "<td>$row->showset</td>\n";
		if($c_shortage == "Y") echo "<td>$row->shortage</td>\n";
		if($c_stock == "Y") echo "<td>$row->stock</td>\n";
		if($c_sellprice == "Y") echo "<td>$row->sellprice</td>\n";
		if($c_conprice == "Y") echo "<td>$row->conprice</td>\n";
		if($c_reserve == "Y") echo "<td>$row->reserve</td>\n";
		if($c_option == "Y") {
			echo "<td>$row->opttitle5::$row->optcode5</td>\n";
			echo "<td>$row->opttitle6::$row->optcode6</td>\n";
			echo "<td>$row->opttitle7::$row->optcode7</td>\n";
			echo "<td>$row->opttitle3::$row->optcode3</td>\n";
			echo "<td>$row->opttitle4::$row->optcode4</td>\n";
			echo "<td>$row->opttitle::$row->optcode</td>\n";
			echo "<td>$row->opttitle2::$row->optcode2</td>\n";
			echo "<td>$row->optvalue</td>\n";
		}
		if($c_image == "Y") echo "<td>$row->prdimg_R</td>\n";
		if($c_image == "Y") echo "<td>/$row->prdimg_L1/$row->prdimg_M1/$row->prdimg_S1/</td>\n";
		if($c_image == "Y") echo "<td>/$row->prdimg_L2/$row->prdimg_M2/$row->prdimg_S2/</td>\n";
		if($c_image == "Y") echo "<td>/$row->prdimg_L3/$row->prdimg_M3/$row->prdimg_S3/</td>\n";
		if($c_image == "Y") echo "<td>/$row->prdimg_L4/$row->prdimg_M4/$row->prdimg_S4/</td>\n";
		if($c_image == "Y") echo "<td>/$row->prdimg_L5/$row->prdimg_M5/$row->prdimg_S5/</td>\n";
		if($c_stortexp == "Y") echo "<td>$row->stortexp</td>\n";
		if($c_content == "Y") echo "<td>".htmlspecialchars($row->content)."</td>\n";
		echo "   </tr>";

	}

	echo "</table>\n";

}
?>