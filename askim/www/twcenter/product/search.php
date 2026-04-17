<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$catcode    = sqlSearchfilter(trim($catcode));
$brand      = sqlSearchfilter(trim($brand));
$searchopt  = sqlSearchfilter(trim($searchopt));
$searchkey  = sqlSearchfilter(trim($searchkey));
$prdname    = sqlSearchfilter(trim($prdname));
$sellprice  = sqlSearchfilter(trim($sellprice));
$sellprice2 = sqlSearchfilter(trim($sellprice2));
$prdcom     = sqlSearchfilter(trim($prdcom));
$reserve    = sqlSearchfilter(trim($reserve));
$reserve2   = sqlSearchfilter(trim($reserve2));

$tmp_param = array();
if(isset($catcode)    && $catcode)    $tmp_param[] = "catcode=".$catcode;
if(isset($grp)        && $grp)        $tmp_param[] = "grp=".$grp;
if(isset($brand)      && $brand)      $tmp_param[] = "brand=".$brand;
if(isset($orderby)    && $orderby)    $tmp_param[] = "orderby=".$orderby;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;
if(isset($prdname)    && $prdname)    $tmp_param[] = "prdname=".$prdname;
if(isset($sellprice)  && $sellprice)  $tmp_param[] = "sellprice=".$sellprice;
if(isset($sellprice2) && $sellprice2) $tmp_param[] = "sellprice2=".$sellprice2;
if(isset($prdcom)     && $prdcom)     $tmp_param[] = "prdcom=".$prdcom;
if(isset($reserve)    && $reserve)    $tmp_param[] = "reserve=".$reserve;
if(isset($reserve2)   && $reserve2)   $tmp_param[] = "reserve2=".$reserve2;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";

include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/cat_info.php"; 		// 카테고리정보

// 이동하지 말것(추천상품 상품이미지 사이트때문)
if($cat_info['prd_width'] == "")  $cat_info['prd_width'] = "215";
if($cat_info['prd_height'] == "") $cat_info['prd_height'] = "215";
if($cat_info['prd_num'] == "" || $cat_info['prd_num'] <= 0) $cat_info['prd_num'] = 20;

$prd_width  = $cat_info['prd_width'];
$prd_height = $cat_info['prd_height'];

$line = 5;
$prdname_len = 100;

$where = array();

if($catcode != ""){
	$catcode01 = str_replace("00","",substr($catcode,0,2));
	$catcode02 = str_replace("00","",substr($catcode,2,2));
	$catcode03 = str_replace("00","",substr($catcode,4,2));
	$tmpcode = $catcode01.$catcode02.$catcode03;
}

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

// 카테고리별 찾기
if(!empty($catcode)){
	$catcode01 = substr($catcode,0,2);
	$catcode02 = substr($catcode,2,2);
	$catcode03 = substr($catcode,4,2);
	if($catcode01 == "00") $catcode01 = "";
	if($catcode02 == "00") $catcode02 = "";
	if($catcode03 == "00") $catcode03 = "";
	$tmpcode = $catcode01.$catcode02.$catcode03;
	$where[] = " wc.catcode like '$tmpcode%' ";
}

// 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
if($grp != "")          $where[] = " wp.$grp = 'Y' ";
if($brand != "")        $where[] = " wp.brand = '$brand' ";
if(!empty($searchkey))  $where[] = " wp.$searchopt like '%".$searchkey."%' ";

if(empty($prdname))     $prdname = $searchkey;
if(!empty($prdname))    $where[] = " wp.prdname like '%$prdname%' ";
if(!empty($sellprice))  $where[] = " wp.sellprice >= $sellprice ";
if(!empty($sellprice2)) $where[] = " wp.sellprice <= $sellprice2 ";
if(!empty($prdcom))     $where[] = " wp.prdcom like '%$prdcom%' ";
if(!empty($reserve))    $where[] = " wp.reserve >= '$reserve' ";
if(!empty($reserve2))   $where[] = " wp.reserve <= '$reserve2' ";

$sql_search   = ($where) ? " and ".implode(" and ", $where) : "";

$sql = "
	select distinct wp.prdcode 
	  from wiz_cprelation wc
	     , wiz_product wp
		 , wiz_category wy 
	 where wy.catuse != 'N' 
	   and wc.catcode = wy.catcode 
	   and wc.prdcode = wp.prdcode 
	   and wp.showset != 'N' 
	   $sql_search
	   $order_sql
";
$result = query($sql);
$total = sql_fetch_row($result);
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
					document.location="/<?=$prd_info['order_url']?>";
				}
			}
			, error: function(){
				alert("연동페이지를 확인하시기 바랍니다.");
			}
		});

	}
}

</script>


<form name="prdSearch" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return prdsearch(this);">

<div class="prd_search">
	<h4 class="search_tit">찾으시는 상품의 검색조건을 정확하게 기입하여 주시기 바랍니다.</h4>
	<ul>
		<li class="one">
		<select name="catcode">
			<option value="">상품 분류를 선택하세요</option>
			<?php
			$sql = "
				select catcode
					 , catname 
				  from wiz_category 
				 where depthno = 1 
				   and catuse != 'N' 
				 order by priorno01 asc
			";
			$result = query($sql);
			while($row = sql_fetch_obj($result)){
			?>
			<option value="<?php echo $row->catcode ?>" <? if($row->catcode == $catcode) echo "selected"; ?>>
			<?php echo $row->catname ?>
			</option>
			<?php
			}
			?>
		</select>
		</li>
		<li><input type="text" name="prdname" value="<?=$prdname?>" class="input" placeholder="상품명" /></li>
		<li><input type="text" name="prdcom" value="<?=$prdcom?>" placeholder="브랜드검색"  class="input"  /></li>
		<li>
			<dl>
				<dt>가격</dt>
				<dd><input type="text" name="sellprice" value="<?=$sellprice?>" class="input input_price" Onlynum="true"><span>~</span><input type="text" name="sellprice2" value="<?=$sellprice2?>" class="input input_price" Onlynum="true"></dd>
			</dl>
		</li>
		<li>
			<dl>
				<dt>적립금</dt>
				<dd><input type="text" name="reserve" value="<?=$reserve?>" class="input input_price" /><span>~</span><input type="text" name="reserve2" value="<?=$reserve2?>" class="input input_price" /></dd>
			</dl>
		</li>
		<li class="one"><input class="search_btn" type="submit" value="검색하기" /></li>
	</ul>
</div>
</form>


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


<div class="prd_list">
<?php
$no = 0;
$rows = $cat_info['prd_num'];
$lists = 4;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;

$sql = "
	select distinct wp.prdcode
		 , wp.*
		 , wy.purl
	  from wiz_cprelation wc
		 , wiz_product wp
		 , wiz_category wy
	 where wc.catcode = wy.catcode
	   and wc.prdcode = wp.prdcode
	   and wp.showset != 'N'
	   $sql_search
	 $order_sql
	 limit $start, $rows
";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	// 상품아이콘
	$sp_img = "";
	if($row['popular'] == "Y")		$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
	if($row['recom'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
	if($row['new'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
	if($row['sale'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";
	if($row['best'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
	if($row['shortage'] == "Y" || ($row['shortage'] == "S" && $row['stock'] <= 0)) 
		$sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

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
		$sellprice = "<span>".$row['strprice']."</span>";;
	}

	$prdurl = $PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param;
	$prdname = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'>".cut_str($row['prdname'], 30)."</a>";
	$shortexp = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'>".cut_str($row['shortexp'],120)."</a>";
	$viewimg = "<a href='".$PHP_SELF."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param."'><img src='$skin_dir/image/bt_view.gif' border='0'></a>";

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row['prdimg_R'])) $prdimg = "/twcenter/images/noimg_R.gif";
	else $prdimg = "/twcenter/data/prdimg/".$row['prdimg_R'];

	if($row['opttitle']  != "" || $row['opttitle2']  != "" || $row['opttitle3']  != "" || $row['opttitle4'] != "" ||
	   $row['opttitle5'] != "" || $row['opttitle6']  != "" || $row['opttitle7']  != "" || $row['opttitle8'] != "" ||
	   $row['opttitle9'] != "" || $row['opttitle10'] != "" || $row['opttitle11'] != ""){

		$ubasket = "sel";

	} else {
		$ubasket = "buy";
	}

?>
<?php if($no%$line == 0) echo ""; ?>

<dl>
<p class="cart"><img src='/twcenter/product/image/icon_buy.gif' style="cursor:pointer" alt="바로구매" title="바로구매" onclick="view_go('<?php echo $row['prdcode']?>','<?php echo $row['purl']?>','<?php echo $ubasket?>')"  class='icon_buy'></p>

<a href="<?php echo $prdurl?>" class="link">
	<dt><img src="/twcenter/product/image/blank_img.png" style="background-image:url('<?php echo $prdimg?>');" alt="<?php echo $row['prdname'] ?>" /><p class="icon"><?php echo $coupon_img?><?php echo $sp_img?></p></dt>
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
if($total <= 0){
	echo "<div class='none'>등록된 상품이 없습니다.</div>";
}
?>
</div>

<div class="tpad_35"><?php echo print_pagelist($page, $lists, $page_count, $param); ?></div>

