<? include_once "../common.php"; ?>
<?

$fp = fopen("all_product.txt","w");
if(!$fp){
	die("error");
}

$db_Select = "
				select * 
				from 
					wiz_product wp, 
					wiz_cprelation wc,
					wiz_category wt
				where 
					wp.showset != 'N' and 
					wp.shortage != 'Y' and 
					wt.catuse != 'N' and
					wc.prdcode = wp.prdcode and
					wt.catcode = wc.catcode 
				order by wp.prdcode desc";

$db_Query = mysql_query($db_Select);

$str = "";
	
while($db_rows = mysql_fetch_array($db_Query)){		

	$imgpath_M	= $_SERVER["HTTP_HOST"]."/twcenter/data/prdimg/$db_rows['prdimg_M1']";
	
	//-- 상품단가 기준에 의한 배송료 정책
	if($db_rows['del_type'] == 'DA'){
		if($db_rows['sellprice'] > $oper_info->del_staprice) $del_pay = 0;
		else $del_pay = $oper_info->del_staprice3;
	} else if($db_rows['del_type'] == 'DB'){
		$del_pay = 0;
	} else if($db_rows['del_type'] == 'DC'){
		$del_pay = $db_rows['del_price'];
	} else if($db_rows['del_type'] == 'DD'){
		$del_pay = '-l';
	}

	$pgUrl	 = $_SERVER["HTTP_HOST"]."/product/view.php?prdcode=$db_rows['prdcode']&catcode=$db_rows['catcode']";

	$catcode01 = substr($db_rows['catcode'],0,2)."000000";
	$catcode02 = substr($db_rows['catcode'],0,4)."0000";
	$catcode03 = substr($db_rows['catcode'],0,6)."00";
	$catcode04 = substr($db_rows['catcode'],0,8);


	$sql = "select * from wiz_category where (depthno = '1' and catcode = '$catcode01') or (depthno = '2' and catcode = '$catcode02') or (depthno = '3' and catcode = '$catcode03') or (depthno = '4' and catcode = '$catcode04') order by depthno asc";
	$result = mysql_query($sql) or error(mysql_error());
	while($cat_row = mysql_fetch_array($result)) {
		if($cat_row['depthno'] == "1") $catname1 = $cat_row['catname'];
		if($cat_row['depthno'] == "2") $catname2 = $cat_row['catname'];
		if($cat_row['depthno'] == "3") $catname3 = $cat_row['catname'];
		if($cat_row['depthno'] == "4") $catname4 = $cat_row['catname'];
	}

	$prd_Qry_4 = "SELECT * FROM wiz_brand WHERE idx='$db_rows['brand']'";		//-- '브랜드 불러오기'
	$prd_Res_4 = mysql_query($prd_Qry_4) or error(mysql_error());
	$prd_Row_4 = mysql_fetch_object($prd_Res_4);
	
	$sql = "select wb.idx from wiz_bbs as wb, wiz_product as wp where wb.code='review' and wb.prdcode = wp.prdcode and wb.prdcode='$db_rows['prdcode']'";	
	$result = mysql_query($sql) or error(mysql_error());
	$total = mysql_num_rows($result);
	//$prdname = str_replace(" ","",$db_rows['prdname']);													//-- '상품명'	
	$prdname = strip_tags($db_rows['prdname']);

	$str .= "<<<begin>>>\n";
	$str .= "<<<mapid>>>$db_rows['prdcode']\n";
	$str .= "<<<pname>>>$prdname\n";
	$str .= "<<<price>>>$db_rows['sellprice']\n";
	$str .= "<<<pgurl>>>http://$pgUrl\n";
	$str .= "<<<igurl>>>http://$imgpath_M\n";
	$str .= "<<<cate1>>>$catname1\n";
	$str .= "<<<cate2>>>$catname2\n";
	$str .= "<<<cate3>>>$catname3\n";
	$str .= "<<<cate4>>>$catname4\n";
	$str .= "<<<caid1>>>$catcode01\n";
	$str .= "<<<caid2>>>$catcode02\n";
	$str .= "<<<caid3>>>$catcode03\n";
	$str .= "<<<caid4>>>$catcode04\n";
	$str .= "<<<deliv>>>$del_pay\n";
	$str .= "<<<point>>>$db_rows['reserve']\n";
	$str .= "<<<revct>>>$total\n";
	$str .= "<<<brand>>>$prd_Row_4->brdname\n";
	$str .= "<<<maker>>>$db_rows['prdcom']\n";
	$str .= "<<<ftend>>>\n";
	
}

fwrite($fp,$str);
fclose($fp);
?>

<script>
	alert('전체상품DB가 업데이트 되었습니다.');
	history.go(-1);
</script>