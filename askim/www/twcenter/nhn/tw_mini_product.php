<? include_once "../common.php"; ?>
<?

$fp = fopen("mini_product.txt","w") or die("error");

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

$db_Query = query($db_Select);
$str = "";
while($db_rows = sql_fetch_arr($db_Query)){
	$prdname = strip_tags($db_rows['prdname']);

	$sql = "select wb.idx from wiz_bbs as wb, wiz_product as wp where wb.code='review' and wb.prdcode = wp.prdcode and wb.prdcode='$db_rows['prdcode']'";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);


	if($db_rows['wdate'] != $db_rows['mdate']) $show_class = "U";
	else $show_class = "I";
	
	$str .= "<<<begin>>>\n";
	$str .= "<<<mapid>>>$db_rows['prdcode']\n";
	$str .= "<<<pname>>>$prdname\n";
	$str .= "<<<price>>>$db_rows['sellprice']\n";
	$str .= "<<<class>>>$show_class\n";
	$str .= "<<<utime>>>$db_rows['wdate']\n";
	$str .= "<<<revct>>>$total\n";
	$str .= "<<<ftend>>>\n";
}
fwrite($fp,$str);
fclose($fp);
?>
<script>
	alert('요약상품DB가 업데이트 되었습니다.');
	history.go(-1);
</script>
