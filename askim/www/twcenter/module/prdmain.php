<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prdmain_info.php";

$mainskin = str_replace("[/LOOP]","[LOOP]",$mainskin);
list($header,$body,$footer) = explode("[LOOP]",$mainskin);

echo $header;

$idx = 0;

$catcode01 = "";
$catcode02 = "";
$catcode03 = "";

$pcategory_sql = "";
$maintype_sql = "";

// 카테고리별 찾기
if(!empty($pcategory)){
	$catcode01 = substr($pcategory,0,2);
	$catcode02 = substr($pcategory,2,2);
	$catcode03 = substr($pcategory,4,2);
	if($catcode01 == "00") $catcode01 = "";
	if($catcode02 == "00") $catcode02 = "";
	if($catcode03 == "00") $catcode03 = "";
	$tmpcode = $catcode01.$catcode02.$catcode03;
	$pcategory_sql = " and wc.catcode like '$tmpcode%' ";
}
if($maintype == "recom") $maintype_sql = " and wp.recom='Y' ";
if($maintype == "popular") $maintype_sql = " and wp.popular='Y' ";
if($maintype == "best") $maintype_sql = " and wp.best='Y' ";
if($maintype == "sale") $maintype_sql = " and wp.sale='Y' ";
if($maintype == "new") $maintype_sql = " and wp.new='Y' ";

$sql = "select wp.*, wc.purl, wc.catcode
				from wiz_product as wp left join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
				left join wiz_category as wc on wcp.catcode = wc.catcode
				where wp.showset != 'N' and wc.catuse != 'N' $maintype_sql $pcategory_sql
				group by wcp.prdcode
				order by wp.prior desc, wp.prdcode desc
				limit ".$prdcnt;
$result = query($sql);

while($row = sql_fetch_arr($result)){

	$body_tmp = stripslashes($body);

	if($prdline!=0 && ($idx%$prdline)==0) echo "<tr>";

	$purl = $row['purl'];
	if($purl == "") {
		$p_sql = "select purl from wiz_category where catuse != 'N' and purl != '' order by catcode asc limit 1";
		$p_result = query($p_sql) or error("sql error");
		$p_row = sql_fetch_arr($p_result);
		$purl = $p_row['purl'];
	}

	$conprice = "";
	if($row['conprice'] > $row['sellprice']){
		$conprice = "<s>".number_format($row['conprice'])."원</s> → ";
	}

	if(!empty($row['strprice'])) {
		$conprice = "";
		$sellprice = $row['strprice'];
	} else {
		$sellprice = number_format($row['sellprice'])."원";
	}

	$prdprice = $conprice.$sellprice;

	// 상품아이콘
	$sp_img = "";
	if($row['popular'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
	if($row['recom'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
	if($row['new'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
	if($row['sale'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";
	if($row['best'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
	if($row['shortage'] == "Y" || ($row['shortage'] == "S" && $row['stock'] <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

	$prdicon_list = explode("/",$row['prdicon']);
	for($ii=0; $ii<count($prdicon_list)-1; $ii++){
		$sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
	}

	// 쿠폰아이콘
	$coupon_img = "";
	if(
	$row['coupon_use'] == "Y" &&
	$row['coupon_sdate'] <= date('Y-m-d') &&
	$row['coupon_edate'] >= date('Y-m-d') &&
	($row['coupon_limit'] == "N" || ($row['coupon_limit'] == "" && $row['coupon_amount'] > 0))
	){

		$coupon_img = "<font class=coupon>".$row['coupon_dis'].$row['coupon_type']."</font> <img src='/twcenter/images/icon_coupon.gif' align='absmiddle'>";
	}

	if(file_exists(WIZHOME_PATH."/data/prdimg/".$row['prdimg_R']) && !empty($row['prdimg_R'])) $prdimg = "/twcenter/data/prdimg/".$row['prdimg_R'];		// img
	else $prdimg = "/twcenter/images/noimg_R.gif";

	if($purl != "") $prdlink = "/".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=".$row['catcode'];
	else $prdlink = "javascript:alert('상품 페이지가 제대로 설정되있지 않습니다.');";
	$prdname = cut_str($row['prdname'],$prdname_len);
	$prdcode = $row['prdcode'];
    $stortexp = cut_str($row['stortexp'],$prdname_len);

	$body_tmp = str_replace("{PRDNAME}",$prdname ?? "",$body_tmp);
	$body_tmp = str_replace("{PRDPRICE}",$prdprice ?? "",$body_tmp);
	$body_tmp = str_replace("{PRDCODE}",$prdnum ?? "",$body_tmp);
	$body_tmp = str_replace("{PRDIMG}",$prdimg ?? "",$body_tmp);
	$body_tmp = str_replace("{PRDLINK}",$prdlink ?? "",$body_tmp);
	$body_tmp = str_replace("{SPIMG}",$sp_img ?? "",$body_tmp);
	$body_tmp = str_replace("{COUPON}",$coupon_img ?? "",$body_tmp);
	$body_tmp = str_replace("{CODE}",$prdcode ?? "",$body_tmp);
	$body_tmp = str_replace("{stortexp}",$stortexp ?? "",$body_tmp);
	

	echo $body_tmp;
	$idx++;
}

echo $footer;

$pcategory = "";
?>