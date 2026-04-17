<?
if(!empty($grp)) {
	if($grp == "new")          $catname = "신상품";
	else if($grp == "recom")   $catname = "추천상품";
	else if($grp == "sale")    $catname = "세일상품";
	else if($grp == "popular") $catname = "인기상품";
	else if($grp == "best")    $catname = "베스트상품";
	$position = "&gt; <a href='$PHP_SELF?ptype=list&grp=$grp'>$catname</a>";
} else {
	if(empty($catcode)) $catcode = "00000000";
	// 카테고리 정보
	$catcode1 = substr($catcode,0,2);
	$catcode2 = substr($catcode,0,4);
	$catcode3 = substr($catcode,0,6);
	$position = "";
	$sql = "select * from wiz_category where catuse != 'N' and (
			catcode = '00000000'
			or (catcode like '$catcode1%' and depthno = 1)
			or (catcode like '$catcode2%' and depthno = 2)
			or (catcode like '$catcode3%' and depthno = 3)
			or (catcode = '$catcode'))
							
			order by priorno01 asc, priorno02 asc, priorno03 asc, priorno04 asc";
	$result = query($sql);
	while($cat_row = sql_fetch_arr($result)){
		if($catcode == $cat_row['catcode']){
			$cat_info = $cat_row;
			$catname = $cat_row['catname'];
		}
		if($cat_row['subimg'] != ""){ $subimg = $cat_row['subimg']; $subimg_type = $cat_row['subimg_type']; }
		$position .= " &gt; <a href='$PHP_SELF?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a>";
	}
	if($subimg_type == "FIL"){
		$img_ext = substr($subimg,-3);
		if($img_ext == "swf"){
			$subimg = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\">";
			$subimg .= "<param name=\"movie\" value=\"/twcenter/data/subimg/$subimg\">";
			$subimg .= "<param name=\"quality\" value=\"high\">";
			$subimg .= "<embed src=\"/twcenter/data/subimg/$subimg\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>";
			$subimg .= "</object>";
		}else{
			$subimg = "<img src='/twcenter/data/subimg/$subimg'>";
		}
	}
}
?>