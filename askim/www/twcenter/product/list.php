<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$param = "";
if($catcode   != "") $param .= "catcode=$catcode";
if($grp       != "") $param .= "&grp=$grp";
if($brand     != "") $param .= "&brand=$brand";
if($orderby   != "") $param .= "&orderby=$orderby";
if($searchopt != "") $param .= "&searchopt=$searchopt";
if($searchkey != "") $param .= "&searchkey=$searchkey";

include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/cat_info.php"; 		// 카테고리정보
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/brd_info.php";			// 브랜드정보

// 이동하지 말것(추천상품 상품이미지 사이트때문)
$prd_width  = $cat_info['prd_width'];
$prd_height = $cat_info['prd_height'];
$prd_num = $cat_info['prd_num'];
if($prd_width == "")  $prd_width  = "215";
if($prd_height == "") $prd_height = "215";
if($prd_num == "" || $prd_num <= 0) $prd_num = 16;

?>
<script type="text/javascript">
function numCheck(frm){
	var szNum1 = frm.sellprice.value;
	var szNum2 = frm.sellprice2.value;

	var num_check=/^[0-9]*$/;

	if(num_check.test(szNum1)){
	}else{
		alert ( "숫자만 입력할 수 있습니다." );
		return false;
	}

	if(num_check.test(szNum2)){
	}else{
		alert ( "숫자만 입력할 수 있습니다." );
		return false;
	}

}

function view_go(prdcode,purl,ubasket){

	if(ubasket == "sel"){
		alert("옵션항목이 있는 상품입니다.\n옵션선택 페이지로 이동합니다.");
		location.href = "/"+purl+"?ptype=view&prdcode="+prdcode;
	} else if(ubasket == "buy") {

		var mode = "insert";
		$.ajax({
			type:"post"
			, async: false
			, url:  "/twcenter/product/ajax_basket_buy.php"
			, data: {prdcode: prdcode, mode: mode}
			, success: function(data) {
				if(data == "1" || data == "2") {
					alert("주문수량이 재고량보다 많습니다.");
					return false;
				} else if(data == "3" || data == "4") {
					alert("품절된 상품입니다.");
					return false;
				} else if(data == "ok") {
					alert("바로구매 페이지로 이동합니다.");
					document.location="/<?php echo $prd_info['order_url']?>";
				}
			}
			, error: function(){
				alert("연동페이지를 확인하시기 바랍니다.");
			}
		});

	}
}

</script>


<!--<form action="<?php echo $PHP_SELF?>" method="get" onSubmit="return numCheck(this);">
		<div class="price_search">
			<h5>가격</h5>
			<span class="input_area">
			<input type="text" id="sellprice" name="sellprice" value="<?php echo $sellprice?>" style="width:120px;text-align:right;" class="prd_input"/>
			~
			<input type="text" id="sellprice2" name="sellprice2" value="<?php echo $sellprice2?>" style="width:120px;text-align:right;" class="prd_input"/>
			( 컴마(,) 제외하고 입력 바랍니다. ex. 100000~200000 )</span>
			<p class="btn">
			<a href="<?php echo $PHP_SELF?>"><img src="/twcenter/product/image/btn_init.gif"></a>
			<input type="image" src="/twcenter/product/image/btn_search02.gif">
			</p>
		</div>
		</form> -->

	<?php

	// 상품 쿼리
	if($catcode != ""){
		$catcode01 = str_replace("00","",substr($catcode,0,2));
		$catcode02 = str_replace("00","",substr($catcode,2,2));
		$catcode03 = str_replace("00","",substr($catcode,4,2));
		$catcode04 = str_replace("00","",substr($catcode,6,2));
		$tmpcode = $catcode01.$catcode02.$catcode03.$catcode04;
	}

	$upcat = substr($tmpcode, 0, strlen($tmpcode)-2);
	while(strlen($upcat) < 8) {
		$upcat .= "00";
	}

	$catlist_all = "<a href='$purl?ptype=list&catcode=".$upcat."'>"."전체보기"."</a>";
	if($brand) {
		$category_all = "<a href='$purl?ptype=list&catcode='00000000'><font color=#ffffff>"."All Brand"."</font></a>";
		include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/category_brand.php";			// 브랜드 카테고리
	} else {
		$category_all = "<a href='$purl?ptype=list&catcode='00000000'><font color=#ffffff>"."전체카테고리"."</font></a>";
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/category.php";					// 카테고리
	}

	include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_recom.php";					// 추천상품

	// 정렬순서
	if($orderby == ""){
		$order_sql = "order by wp.prior desc, prdcode desc";
	}else if($orderby == "pop"){
		$order_sql = "order by wp.ordercnt desc, wp.prior desc, prdcode desc";
	}else if($orderby == "new"){
		$order_sql = "order by wp.prior desc, prdcode desc";
	}else if($orderby == "bottom"){
		$order_sql = "order by wp.sellprice asc, prdcode desc";
	}else if($orderby == "height"){
		$order_sql = "order by wp.sellprice desc, prdcode desc";
	}else{
		$order_sql = "order by $orderby";
	}

	$price_sql = "";
	if(!empty($sellprice))  $price_sql .= " and sellprice >= '$sellprice' ";
	if(!empty($sellprice2)) $price_sql .= " and sellprice <= '$sellprice2' ";

	// 카테고리별 찾기
	if(!empty($catcode)){
		$catcode01 = substr($catcode,0,2);
		$catcode02 = substr($catcode,2,2);
		$catcode03 = substr($catcode,4,2);
		$catcode04 = substr($catcode,6,2);
		if($catcode01 == "00") $catcode01 = "";
		if($catcode02 == "00") $catcode02 = "";
		if($catcode03 == "00") $catcode03 = "";
		if($catcode04 == "00") $catcode04 = "";
		$tmpcode = $catcode01.$catcode02.$catcode03.$catcode04;
		$catcode_sql = " wc.catcode like '$tmpcode%' and ";
	}

	// 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
	if($grp != "") $grp_sql = " wp.$grp = 'Y' and ";

	// 브랜드별 찾기
	if($brand != "") $brand_sql = " wp.brand = '$brand' and ";
	if(!empty($searchkey)){
		//$search_sql = " wp.$searchopt like '%".$searchkey."%' and ";
		$total_searchkey_conv = str_replace(" ","",$searchkey);
		$search_sql = " (wp.$searchopt like '%".$searchkey."%' or wp.$searchopt like '%".$total_searchkey_conv."%' or REPLACE(wp.$searchopt,' ','') like '%".$searchkey."%' or REPLACE(wp.$searchopt,' ','') like '%".$total_searchkey_conv."%' ) and";
	}

	$sql = "
		SELECT distinct wp.prdcode
		  FROM wiz_cprelation wc
			 , wiz_product wp
			 , wiz_category wy

		WHERE $catcode_sql $grp_sql $brand_sql $search_sql wy.catuse != 'N' 
		  AND wc.catcode = wy.catcode 
		  AND wc.prdcode = wp.prdcode 
		  AND wp.showset != 'N'
		  $order_sql
	";
	$result = query($sql);
	$total = sql_fetch_row($result);

	// 상단파일
	?>

<form action="<?php echo $PHP_SELF?>" method="get">
	<input type="hidden" name="catcode" value="<?php echo $catcode?>">
	<input type="hidden" name="grp" value="<?php echo $grp?>">
	<input type="hidden" name="brand" value="<?php echo $brand?>">

<dl class="sort_area">
	<dd>
	<a href="<?php echo $PHP_SELF."?orderby=pop&catcode=$catcode&grp=$grp"?>" class="bgnone">인기순</a>
	<a href="<?php echo $PHP_SELF."?orderby=new&catcode=$catcode&grp=$grp"?>">최신순</a>
	<a href="<?php echo $PHP_SELF."?orderby=bottom&catcode=$catcode&grp=$grp"?>">최저가순</a>
	<a href="<?php echo $PHP_SELF."?orderby=height&catcode=$catcode&grp=$grp"?>">최고가순</a>
	</dd>
	<dt>총 <strong><?php echo $total?></strong>개 의 상품이 등록 되었습니다.</dt>
</dl>
</form>
<?php
		// 2018-06-15 상품리스트 사이에 쇼핑몰 서브상단 있을시 표시
		$cate_sql = "select subimg_type,subimg from wiz_category WHERE catcode = '".$catcode."'";
		$cate_result = query($cate_sql);
		$cate_row = sql_fetch_arr($cate_result);

		if($cate_row['subimg_type'] == "FIL"){ //이미지
			if(is_file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/subimg/".$cate_row['subimg'])){
				$img_ext = substr($cate_row['subimg'],-3);
				$cate_view = "<div class='bpad_10'><a href='$purl?ptype=list&catcode=".$catcode."'><img src='/twcenter/data/subimg/".$cate_row['subimg']."' ></a></div>";
			}
		}else if($cate_row['subimg_type'] == "HTM"){ // HTML
			$cate_view = "<div class='bpad_10'><a href='$purl?ptype=list&catcode=".$catcode."'>".$cate_row['subimg']."</a></div>";
		}
?>

<?php echo  $cate_view ?>

<div class="prd_list">
<?php
$no           = 0;
$rows         = $prd_num;		// 상품수
$lists        = 10;				// 페이징 갯수
$line         = 4;				// 라인당 상품수
$prdname_len  = 30;				// 상품명 길이
$shortexp_len = 100;			// 상품설명 길이

$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;


$sql = "
	SELECT DISTINCT wp.prdcode
		 , wp.prdname
		 , wp.stortexp
		 , wp.prdcom
		 , wp.reserve
		 , wp.sellprice
		 , wp.strprice
		 , wp.prdimg_R
		 , wp.popular
		 , wp.recom
		 , wp.new as newc
		 , wp.best
		 , wp.sale
		 , wp.shortage
		 , wp.prdicon
		 , wp.stock
		 , wp.conprice
		 , wp.coupon_use
		 , wp.coupon_type
		 , wp.coupon_dis
		 , wp.coupon_amount
		 , wp.coupon_limit
		 , wp.coupon_edate
		 , wy.purl
		 , wp.opttitle
		 , wp.opttitle2
		 , wp.opttitle3
		 , wp.opttitle4
		 , wp.opttitle5
		 , wp.opttitle6
		 , wp.opttitle7
		 , wp.opttitle8
		 , wp.opttitle9
		 , wp.opttitle10
		 , wp.opttitle11
	  FROM wiz_cprelation wc
		 , wiz_product wp
		 , wiz_category wy
	 WHERE $catcode_sql $grp_sql $brand_sql $search_sql wy.catuse != 'N' 
	   AND wc.catcode = wy.catcode 
	   AND wc.prdcode = wp.prdcode
	   AND wp.showset != 'N' $price_sql $order_sql
	 LIMIT $start, $rows
	";
//echo "<xmp>".$sql."</xmp>";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	// 상품아이콘
	$sp_img = "";
	if($row['popular']  == "Y") $sp_img .= "<img src='/twcenter/images/icon_hit.gif' alt='hit'/>&nbsp;";
	if($row['recom']    == "Y") $sp_img .= "<img src='/twcenter/images/icon_rec.gif' alt='recom'/>&nbsp;";
	if($row['newc']     == "Y") $sp_img .= "<img src='/twcenter/images/icon_new.gif' alt='new'/>&nbsp;";
	if($row['sale']     == "Y") $sp_img .= "<img src='/twcenter/images/icon_sale.gif' alt='sale'/>&nbsp;";
	if($row['best']     == "Y") $sp_img .= "<img src='/twcenter/images/icon_best.gif' alt='best'/>&nbsp;";
	if($row['shortage'] == "Y" || ($row['shortage'] == "S" && $row['stock'] <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

	$prdicon_list = explode("/",$row['prdicon']);
	for($ii=0; $ii<count($prdicon_list)-1; $ii++){
		$sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
	}

	// 쿠폰아이콘
	$coupon_img = "";
	if(
	$row['coupon_use'] == "Y" &&
	$row['coupon_edate'] >= date('Y-m-d') &&
	($row['coupon_limit'] == "N" || ($row['coupon_limit'] == "" && $row['coupon_amount'] > 0))
	){

		$coupon_img = "<font class=coupon>".number_format($row['coupon_dis']).$row['coupon_type']."</font> <img src='/twcenter/images/icon_coupon.gif'>&nbsp;";
	}

	// 정상가(판매가보다 높을경우 할인표시)
	$conprice = "";
	if($row['conprice'] > $row['sellprice']){
		$conprice = "<s>".number_format($row['conprice'])."원</s>";
	}

	$sellprice = "<span>".number_format($row['sellprice'])."</span>원";

	if(!empty($row['strprice'])) {
		$conprice = "";
		$sellprice = "<span>".$row['strprice']."</span>";
	}

	$prdurl = $PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param;
	$prdname = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'>".cut_str($row['prdname'], $prdname_len)."</a>";
	$shortexp = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'>".cut_str($row['shortexp'], $shortexp_len)."</a>";
	$stortexp ="<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'>".cut_str($row['stortexp'], $stortexp_len)."</a>";
	$viewimg = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'><img src='$skin_dir/image/bt_view.gif' border='0'></a>";

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row['prdimg_R'])) $prdimg = "/twcenter/images/noimg_R.gif";
	else $prdimg = "/twcenter/data/prdimg/".$row['prdimg_R'];

	if($no%$line == 0) echo "";

	if($row['opttitle']  != "" || $row['opttitle2']  != "" || $row['opttitle3']  != "" || $row['opttitle4'] != "" ||
	   $row['opttitle5'] != "" || $row['opttitle6']  != "" || $row['opttitle7']  != "" || $row['opttitle8'] != "" ||
	   $row['opttitle9'] != "" || $row['opttitle10'] != "" || $row['opttitle11'] != ""){

		$ubasket = "sel";

	} else {
		$ubasket = "buy";
	}

?>
<dl>
<p class="cart"><img src='/twcenter/product/image/icon_buy.gif' style="cursor:pointer" alt="바로구매" title="바로구매" onclick="view_go('<?php echo $row['prdcode']?>','<?php echo $row['purl']?>','<?php echo $ubasket?>')"  class='icon_buy'></p>

<a href="<?php echo $prdurl?>" class="link">
	<dt><img src="<?php echo $prdimg?>" alt="<?php echo $row['prdname'] ?>" /><p class="icon"><?php echo $coupon_img?><?php echo $sp_img?></p></dt>
	<dd>
		<p class="tit"><?php echo $row['prdname'] ?></p>
		<p class="stxt"><?php echo $row['stortexp']?></p>
		<p class="price"><?php echo $conprice?><?php echo $sellprice?></p>
	</dd>
</a>
</dl>

<?php

	$rows--;
	$no++;
}
if($total <= 0) echo "<div class='none'>등록된 상품이 없습니다.</div>";

// 하단파일
?>
</div>


<div class="tpad_35"><?php echo print_pagelist($page, $lists, $page_count, $param); ?></div>

