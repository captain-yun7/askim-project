<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

//웹 취약점 대비 - 파라미터 필터링 2022-06-30 정나혜
foreach($_REQUEST as $k => $v){
	${$k} = xss_clean($v);
}

if(!isset($prdcode)) $prdcode = '';
$prdcode = strip_tags($prdcode);

if(!isset($page)) $page = '';
$page = strip_tags($page);

if(!isset($catcode)) $catcode = '';
$catcode = strip_tags($catcode);

if(!isset($ptype)) $ptype = '';
$ptype = strip_tags($ptype);

if(!isset($searchopt)) $searchopt = '';
$searchopt = strip_tags($searchopt);

if(!isset($searchkey)) $searchkey = '';
$searchkey = strip_tags($searchkey);

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

if($catcode == "") $catcode = "00000000";

$tmp_param = array();
if(isset($catcode)    && $catcode)    $tmp_param[] = "catcode=".$catcode;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";

// 카테고리 정보
$catcode1 = substr($catcode,0,2);
$catcode2 = substr($catcode,0,4);
$catcode3 = substr($catcode,0,6);
$catcode4 = substr($catcode,0,8);

$position = "";
$catname  = "";

$sql = "
	select * 
	  from wiz_category2 
	 where catuse != 'N' 
	   and (
			catcode = '00000000'
			or (
				catcode like '$catcode1%' 
				and depthno = 1
				)
			or (
				catcode like '$catcode2%' 
				and depthno = 2
				)
			or (
				catcode like '$catcode3%' 
				and depthno = 3
			)
			or (
				catcode like '$catcode4%' 
				and depthno = 4
			)
			or (
				catcode = '$catcode'
				)
			) 
	 order by depthno, priorno01, priorno02, priorno03, priorno04 desc
";
$result = query($sql);

while($row = sql_fetch_arr($result)){

	if($catcode == $row['catcode']){
		$cat_info = $row;
		$catname = $row['catname'];
		$parname = $tmp_catname;

		// 스킨위치
		if(mobile_check() == true && $site_info["mobile_use"] == "Y") {
			if(!empty($row['prd_skin'])) $skin_dir = "/twcenter/product2/skin/prdBasic_m";
		} else {
			if(!empty($row['prd_skin'])) $skin_dir = "/twcenter/product2/skin/".$row['prd_skin'];
		}

	}
	if(is_file(WIZHOME_PATH."/data/product/".$row['catimg'])) $catimg = "<img src='/twcenter/data/product/".$row['catimg']."'>";
	$position .= " &gt; <a href='$PHP_SELF?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a>";
	$tmp_catname = $row['catname'];
}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if($cat_info['depthno'] == 1) $tmp_catcode = substr($catcode,0,2);
else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,4);
else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,6);
else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,8);

if($cat_info['depthno'] < 4) $cat_info['depthno']++;

$ii = 0;
$sql = "
	select catcode
		 , catname
		 , depthno
		 , purl
	  from wiz_category2
	 where catuse != 'N' 
	   and catcode like '$tmp_catcode%' 
	   and depthno = '".$cat_info['depthno']."' 
	 order by priorno01, priorno02, priorno03, priorno04 asc
";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	if(!empty($row['purl'])) $purl = "../".$row['purl'];
	else $purl = $PHP_SELF;

	if($catcode == $row['catcode']){
		$catlist[$ii] = "<li class='swiper-slide hover'><a href='$purl?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a></li>";
	} else{
		$catlist[$ii] = "<li class='swiper-slide'><a href='$purl?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a></li>";
	}

	

	$ii++;
}
// 하위 카테고리가 없을 경우 현재 카테고리
if($ii <= 0) {
	
	$cat_info['depthno'] = $cat_info['depthno'] - 1;
	
	if($cat_info['depthno'] == 1) $tmp_catcode = substr($catcode,0,0);
	else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);
	else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,6);

	$sql = "
		select catcode
			 , catname
			 , depthno
			 , purl
		  from wiz_category2 
		 where catuse != 'N' 
		   and catcode like '$tmp_catcode%' 
		   and depthno = '".$cat_info['depthno']."' 
		 order by priorno01, priorno02, priorno03, priorno04 asc
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){
		if(!empty($row['purl'])) $purl = "../".$row['purl'];
		else $purl = $PHP_SELF;

		if($catcode == $row['catcode']){
			$catlist[$ii] = "<li class='swiper-slide hover'><a href='$purl?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a></li>";
		} else{
			$catlist[$ii] = "<li class='swiper-slide'><a href='$purl?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a></li>";
		}


		$ii++;
	}
}
// 하위 카테고리가 없을 경우 현재 카테고리 끝
$catlist_all = "<li class='swiper-slide'><a href='$purl?ptype=list&catcode=".substr($catcode,0,2)."000000'>"."전체보기"."</a></li>";

// 카테고리파일
include $_SERVER['DOCUMENT_ROOT']."/$skin_dir/category.php";


// 상품 쿼리
if($catcode != ""){
	$catcode01 = str_replace("00","",substr($catcode,0,2));
	$catcode02 = str_replace("00","",substr($catcode,2,2));
	$catcode03 = str_replace("00","",substr($catcode,4,2));
	$catcode04 = str_replace("00","",substr($catcode,6,2));
	$tmpcode = $catcode01.$catcode02.$catcode03.$catcode04;
}

if(mobile_check() == true && $site_info["mobile_use"] == "Y") {
	$show_sql = " and (wp.mobileShow = 'Y' or wp.mobileShow = '') ";	
} else {
	$show_sql = " and wp.showset != 'N'";
}

if($tmpcode != "") $catcode_sql = " and wc.catcode like '$tmpcode%' ";
if($searchkey != ""){
	$total_searchkey_conv = str_replace(" ","",$searchkey);
	$search_sql = " and (wp.$searchopt like '%".$searchkey."%' or wp.$searchopt like '%".$total_searchkey_conv."%' or REPLACE(wp.$searchopt,' ','') like '%".$searchkey."%' or REPLACE(wp.$searchopt,' ','') like '%".$total_searchkey_conv."%' ) ";
}
$sql = "
	select distinct wp.prdcode
		 , wp.prdnum
		 , wp.prdname
		 , wp.prdprice
		 , wp.shortexp
		 , wp.prdimg_R
		 , wp.showset
		 , wp.mobileShow
	  from wiz_product2 wp
	     , wiz_cprelation2 wc
		 , wiz_category2 wcat
	 where wp.prdcode = wc.prdcode
	   and wcat.catcode = wc.catcode
	   and wcat.catuse != 'N' 
	   $catcode_sql $search_sql $show_sql
";
$result = query($sql);
$total = sql_fetch_row($result);

$idx = 0;

//if(!empty($cat_info['prd_num'])) $rows = $cat_info['prd_num'];
//else $rows = 20;

if(strpos($PHP_SELF, "/m/") !== false) { // 모바일 
	$rows = 10;
}else { 
	$rows =  $cat_info['prd_num']; //PC
}

$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

// 상단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_head.php";

if($new==true){
	$pop_sql = "";
}
if($pop==true){
	$pop_sql = "wp.recom desc ,";
}
$sql = "
	select distinct wp.prdcode
		 , wp.prdnum
		 , wp.prdname
		 , wp.prdprice
		 , wp.shortexp
		 , wp.prdimg_R
		 , wp.showset
		 , wp.mobileShow
	  from wiz_product2 wp
		 , wiz_cprelation2 wc
		 , wiz_category2 wcat
	 where wp.prdcode = wc.prdcode 
	   and wcat.catcode = wc.catcode 
	   and wcat.catuse != 'N' 
	   $catcode_sql $search_sql $show_sql
	 order by $pop_sql wp.prior desc, wp.prdcode desc 
	 limit $start, $rows
";

$result = query($sql);

while($row = sql_fetch_arr($result)){

	$prdurl = $PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&page=".$page."&".$param;
	
	if(!isset($row['content'])) $row['content'] = '';
	$content = str_replace("\n","",$row['content']);	
	$prdnum = $row['prdnum'];
	$prdimg = "/twcenter/data/product2/".$row['prdimg_R'];

	/** 제품 이미지 없을 때 noimg 나오게 하기 **/
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".$row['prdimg_R'])) $prdimg = $skin_dir."/image/noimg_R.gif";
	else $prdimg = "/twcenter/data/product2/".$row['prdimg_R'];
	/** 제품 이미지 없을 때 noimg 나오게 하기 끝 **/

	$prdprice = number_format($row['prdprice']);
	$prdname  = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&page=".$page."&".$param."'>".$row['prdname']."</a>";
	$shortexp = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&page=".$page."&".$param."'>".cut_str($row['shortexp'],120)."</a>";
	$viewimg  = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&page=".$page."&".$param."'><img src='$skin_dir/image/bt_view.gif' border='0' alt='자세히보기' ></a>";
	$shortexp = nl2br($row['shortexp']);

	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_body.php";

	$no--;
	$idx++;
}

// 하단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_foot.php";


?>
