<?
if($cat_info['depthno'] == 1) $tmp_catcode = substr($catcode,0,2);
else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,4);
else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,6);
else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,8);
if($cat_info['depthno'] < 4) $cat_info['depthno']++;

$upcat = substr($tmpcode, 0, strlen($tmpcode)-2);
while(strlen($upcat) < 8) {
	$upcat .= "00";
}
$purl = "/m/sub/prdlist.php";
$catlist_all = "<a href='$purl?catcode=".$upcat."'>"."전체보기"."</a>";

$ii = 0;
$sql = "
	select

		catcode,
		catname,
		depthno

	from

		wiz_category

	where

		$brand_sql
		catuse != 'N' and
		catcode like '$tmp_catcode%' and
		depthno = '".$cat_info['depthno']."'
		order by priorno01, priorno02, priorno03, priorno04 asc
";
$result = query($sql) or error("sql error");
while($row = sql_fetch_arr($result)){

	## 현재카테고리에 몇개의 상품이 있는지
	$t_catcode = $row['catcode'];
	if($row['depthno'] == 1)		$t_catcode = substr($t_catcode,0,2);
	else if($row['depthno'] == 2)	$t_catcode = substr($t_catcode,0,4);
	else if($row['depthno'] == 3)	$t_catcode = substr($t_catcode,0,6);
	else if($row['depthno'] == 4)	$t_catcode = substr($t_catcode,0,8);

	## 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
	if($grp != "") $grp_sql = " $grp = 'Y' and ";

	## 브랜드별 찾기
	if($brand != "") $brand_sql = " wp.brand = '$brand' and ";

	$t_sql = "select count(idx) as prd_count from wiz_cprelation wcp left join wiz_product wp on wcp.prdcode = wp.prdcode where $grp_sql $brand_sql wcp.catcode like '$t_catcode%' and wp.showset = 'Y' ";
	$t_result = query($t_sql) or error("sql error");
	$t_row = sql_fetch_obj($t_result);
	$prd_count = $t_row->prd_count;

	if($catcode == $row['catcode']) $row['catname'] = "<strong>".$row['catname']." (".$prd_count.")</strong>";
	else $row['catname'] = $row['catname']." (".$prd_count.")";

	if(!empty($row['purl'])) $purl = "../".$row['purl'];
	else $purl = $purl;
	$catlist[$ii] = "<a href='$purl?catcode=".$row['catcode'].($grp ? '&grp='.$grp : '').($brand ? '&brand='.$brand : '')."'>".$row['catname']."</a>";
	$ii++;
}
// 하위 카테고리가 없을 경우 현재 카테고리
if($ii <= 0) {

	$cat_info['depthno'] = $cat_info['depthno'] - 1;

	if($cat_info['depthno'] == 1)      $tmp_catcode = substr($catcode,0,0);
	else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);
	else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,6);

	$sql = "
	select

		catcode,
		catname,
		depthno

	from

		wiz_category

	where

		$grp_sql
		$brand_sql
		catuse != 'N' and
		catcode like '$tmp_catcode%' and
		depthno = '".$cat_info['depthno']."'
		order by priorno01, priorno02, priorno03, priorno04 asc

	";
	$result = query($sql) or error("sql error");
	while($row = sql_fetch_arr($result)){

		## 현재카테고리에 몇개의 상품이 있는지
		$t_catcode = $row['catcode'];
		if($row['depthno'] == 1)		$t_catcode = substr($t_catcode,0,2);
		else if($row['depthno'] == 2)	$t_catcode = substr($t_catcode,0,4);
		else if($row['depthno'] == 3)	$t_catcode = substr($t_catcode,0,6);
		else if($row['depthno'] == 4)	$t_catcode = substr($t_catcode,0,8);
		else if($row['depthno'] == 5)	$t_catcode = substr($t_catcode,0,10);

		## 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
		if($grp != "") $grp_sql = " $grp = 'Y' and ";

		## 브랜드별 찾기
		if($brand != "") $brand_sql = " wp.brand = '$brand' and ";

		$t_sql = "select count(idx) as prd_count from wiz_cprelation wcp left join wiz_product wp on wcp.prdcode = wp.prdcode where $grp_sql $brand_sql wcp.catcode like '$t_catcode%' and wp.showset = 'Y' ";

		$t_result = query($t_sql) or error("sql error");
		$t_row = sql_fetch_obj($t_result);
		$prd_count = $t_row->prd_count;

		if($catcode == $row['catcode']) $row['catname'] = "<strong>".$row['catname']." (".$prd_count.")</strong>";
		else $row['catname'] = $row['catname']." (".$prd_count.")";

		if(!empty($row['purl'])) $purl = "../".$row['purl'];
		else $purl = $purl;

		$catlist[$ii] = "<a href='$purl?catcode=".$row['catcode'].($grp ? '&grp='.$grp : '').($brand ? '&brand='.$brand : '')."'>".$row['catname']."</a>";
		$ii++;
	}
}
?>





<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3" class="cate_list_con">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cate_list">
				<?array_unshift($catlist, $catlist_all);?>
				<?
				for($ii=0;$ii<count($catlist);$ii++){
					if($ii%3 == 0) echo "<tr>";
				?>
					<td valign="top"><?=$catlist[$ii]?></td>
				<? } ?>
			</table>

</td>
</tr>
</table>
