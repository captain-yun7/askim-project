<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/prd_info.php";

## 페이지 파라메터 (검색조건이 변하지 않도록)
##--------------------------------------------------------------------------------------------------
$tmp_param = array();
if(isset($page)       && $page)       $tmp_param[] = "page=".$page;
if(isset($dep_code)   && $dep_code)   $tmp_param[] = "dep_code=".$dep_code;
if(isset($dep2_code)  && $dep2_code)  $tmp_param[] = "dep2_code=".$dep2_code;
if(isset($dep3_code)  && $dep3_code)  $tmp_param[] = "dep3_code=".$dep3_code;
if(isset($dep4_code)  && $dep4_code)  $tmp_param[] = "dep4_code=".$dep4_code;
if(isset($special)    && $special)    $tmp_param[] = "special=".$special;
if(isset($display)    && $display)    $tmp_param[] = "display=".$display;
if(isset($searchopt)  && $searchopt)  $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey)  && $searchkey)  $tmp_param[] = "searchkey=".$searchkey;
if(isset($stock_opt)  && $stock_opt)  $tmp_param[] = "stock_opt=".$stock_opt;
if(isset($shortpage)  && $shortpage)  $tmp_param[] = "shortpage=".$shortpage;
if(isset($prdcode)    && $prdcode)    $tmp_param[] = "prdcode=".$prdcode;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;
##--------------------------------------------------------------------------------------------------

$prdimg_path = "../../data/prdimg";
$prdicon_path = "../../data/prdicon";

/*
색상/사이즈옵션

if($color_use == "Y"){
	for($i=0;$i<sizeof($colorName);$i++){
		if($colorName[$i] == "") error ("색상옵션을 정확히 입력하시기 바랍니다.");
		if($i > 0) $optcode12 .= ",";
		$optcode12 .= $colorName[$i]."^".$colorCode[$i];
	}
}*/

/* 2021-03-15 완전한 기능이 아니므로 주석처리
switch($timesale_use){
	case "Y":
		$timesale_sql = " , timesale_use = '".$timesale_use."', timelimit = '".$timelimit_date." ".$timelimit_hour.":".$timelimit_min.":00'";
		break;
	case "N":
		$timesale_sql = " , timesale_use = '".$timesale_use."', timelimit = '' ";
		break;
}*/

/**
 * 상품등록
 **/

/*
작업자	: 정나혜 
작업일시	: 2024-01-05
작업내용	: 입력시 db에 영향을 줄 수 있는 어퍼스트로피나 쌍따옴표 같은 입력 치환 (입력, 수정, 복사 모두 확인 할 것)
subject, content, mcontent, addinfo1~10, info_name1~10, info_value1~10, stortexp
*/

if($mode == "insert") {

	## 상품넘버 만들기
	$sql = "select max(prdcode) as prdcode, max(prior) as prior from wiz_product";
	$result = query($sql);
	if($row = sql_fetch_obj($result)){

		$datenum = substr($row->prdcode,0,6);
		$prdnum = substr($row->prdcode,6,4);
		$prdnum = substr("000".(++$prdnum),-4);

		if($datenum == date('ymd')) $prdcode = $datenum.$prdnum;
		else $prdcode = date('ymd')."0001";

		## 상품진열 순서
 		$prior = $row->prior + 1;

	}else{
		$prdcode = date('ymd')."0001";

		## 상품진열 순서
		$prior = date(ymdHis);

	}

	## 상품아이콘
	for($ii=0; ($prdicon && $ii<count($prdicon)); $ii++){
		$prdicon_list .= $prdicon[$ii]."/";
	}

	## 상품이미지 저장
	include "./prd_imgup.php";

	$prdname = str_replace("'","′",$prdname);

	## 상품 옵션 1
	for($ii = 0; ($tmp_optcode && $ii < count($tmp_optcode)); $ii++) {
		if(!empty($tmp_optcode[$ii])) $optcode .= $tmp_optcode[$ii]."^";
	}

	## 상품 옵션 2
	for($ii = 0; ($tmp_optcode2 && $ii < count($tmp_optcode2)); $ii++) {
		if($ii == 0) $optcode2 = "";
		if(!empty($tmp_optcode2[$ii])) $optcode2 .= $tmp_optcode2[$ii]."^";
	}

	## 상품 옵션 - 가격/적립금/재고
	for($ii = 0; ($tmp_opt['sellprice'] && $ii < count($tmp_opt['sellprice'])); $ii++) {

		if(empty($tmp_opt['sellprice'][$ii])) $tmp_opt['sellprice'][$ii] = 0;
		if(empty($tmp_opt['reserve'][$ii])) $tmp_opt['reserve'][$ii] = 0;
		if(empty($tmp_opt['stock'][$ii])) $tmp_opt['stock'][$ii] = 0;

		$optvalue .= $tmp_opt['sellprice'][$ii]."^".$tmp_opt['reserve'][$ii]."^".$tmp_opt['stock'][$ii]."^^";
	}

	## 가격추가옵션 PART1
	for($jj = 3; $jj <= 4; $jj++){
		for($ii = 0; (${'optcode'.$jj.'_opt'} && $ii < count(${'optcode'.$jj.'_opt'})); $ii++) {
			if(strcmp(${'optcode'.$jj.'_opt'}[$ii]."^".${'optcode'.$jj.'_pri'}[$ii]."^".${'optcode'.$jj.'_res'}[$ii]."^^", "^^^^")) {

				if(empty(${'optcode'.$jj.'_pri'}[$ii])) ${'optcode'.$jj.'_pri'}[$ii] = 0;
				if(empty(${'optcode'.$jj.'_res'}[$ii])) ${'optcode'.$jj.'_res'}[$ii] = 0;

				${'optcode'.$jj} .= ${'optcode'.$jj.'_opt'}[$ii]."^".${'optcode'.$jj.'_pri'}[$ii]."^".${'optcode'.$jj.'_res'}[$ii]."^^";
			}
		}
	}

	## 가격추가옵션 PART2
	for($kk = 8; $kk <= 11; $kk++){
		for($ii = 0; (${'optcode'.$kk.'_opt'} && $ii < count(${'optcode'.$kk.'_opt'})); $ii++) {
			if(strcmp(${'optcode'.$kk.'_opt'}[$ii]."^".${'optcode'.$kk.'_pri'}[$ii]."^".${'optcode'.$kk.'_res'}[$ii]."^^", "^^^^")) {

				if(empty(${'optcode'.$kk.'_pri'}[$ii])) ${'optcode'.$kk.'_pri'}[$ii] = 0;
				if(empty(${'optcode'.$kk.'_res'}[$ii])) ${'optcode'.$kk.'_res'}[$ii] = 0;

				${'optcode'.$kk} .= ${'optcode'.$kk.'_opt'}[$ii]."^".${'optcode'.$kk.'_pri'}[$ii]."^".${'optcode'.$kk.'_res'}[$ii]."^^";
			}
		}
	}

	$opt_title_sql  = "";
	$opt_code_sql   = "";

	for($i=2; $i<=13; $i++) {
		$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i}."'                      ";
		$opt_code_sql   .= " , optcode".$i."          = '".${'optcode'.$i}."'                       ";
	}

	$opt_req_sql  = "";

	for($i=3; $i<=13; $i++) {
		$opt_req_sql  .= " , opt".$i."_req            = '".${'opt'.$i.'_req'}."'                      ";
	}

	$info_name_sql  = "";
	$info_value_sql = "";

	for($i=1; $i<=6; $i++) {
		$info_name_sql  .= " , info_name".$i."          = '".get_text("input", ${'info_name'.$i})."'                      ";
		$info_value_sql .= " , info_value".$i."         = '".get_text("input", ${'info_value'.$i})."'                     ";
	}

	$prdimg_L_sql  = "";
	$prdimg_M_sql = "";
	$prdimg_S_sql = "";

	for($i=1; $i<=5; $i++) {
		$prdimg_L_sql .= " , prdimg_L".$i."         = '".${'prdimg_L'.$i.'_name'}."'                   ";
		$prdimg_M_sql .= " , prdimg_M".$i."         = '".${'prdimg_M'.$i.'_name'}."'                   ";
		$prdimg_S_sql .= " , prdimg_S".$i."         = '".${'prdimg_S'.$i.'_name'}."'                   ";
	}

	$prdname = get_text("input", $prdname);
	$stortexp = get_text("textarea", $stortexp);
	$content = get_text("textarea", $content);
	$mcontent = get_text("textarea", $mcontent);


	## 상품정보 저장
	$sql_com = "";
	$sql_com .= " prdcode                  = '$prdcode'                  ";
	$sql_com .= " , prdname                = '$prdname'                  ";
	$sql_com .= " , prdcom                 = '$prdcom'                   ";
	$sql_com .= " , origin                 = '$origin'                   ";
	$sql_com .= " , showset                = '$showset'                  ";
	$sql_com .= " , stock                  = '$stock'                    ";
	$sql_com .= " , savestock              = '$savestock'                ";
	$sql_com .= " , prior                  = '$prior'                    ";
	$sql_com .= " , viewcnt                = '$viewcnt'                  ";
	$sql_com .= " , deimgcnt               = '$deimgcnt'                 ";
	$sql_com .= " , basketcnt              = '$basketcnt'                ";
	$sql_com .= " , ordercnt               = '$ordercnt'                 ";
	$sql_com .= " , cancelcnt              = '$cancelcnt'                ";
	$sql_com .= " , comcnt                 = '$comcnt'                   ";
	$sql_com .= " , sellprice              = '$sellprice'                ";
	$sql_com .= " , conprice               = '$conprice'                 ";
	$sql_com .= " , reserve                = '$reserve'                  ";
	$sql_com .= " , strprice               = '$strprice'                 ";
	$sql_com .= " , new                    = '$new'                      ";
	$sql_com .= " , best                   = '$best'                     ";
	$sql_com .= " , popular                = '$popular'                  ";
	$sql_com .= " , recom                  = '$recom'                    ";
	$sql_com .= " , sale                   = '$sale'                     ";
	$sql_com .= " , shortage               = '$shortage'                 ";
	$sql_com .= " , coupon_use             = '$coupon_use'               ";
	$sql_com .= " , coupon_dis             = '$coupon_dis'               ";
	$sql_com .= " , coupon_type            = '$coupon_type'              ";
	$sql_com .= " , coupon_amount          = '$coupon_amount'            ";
	$sql_com .= " , coupon_limit           = '$coupon_limit'             ";
	$sql_com .= " , coupon_sdate           = '$coupon_sdate'             ";
	$sql_com .= " , coupon_edate           = '$coupon_edate'             ";
	$sql_com .= " , del_type               = '$del_type'                 ";
	$sql_com .= " , del_price              = '$del_price'                ";
	$sql_com .= " , prdicon                = '$prdicon_list'             ";
	$sql_com .= " , prefer                 = '$prefer'                   ";
	$sql_com .= " , brand                  = '$brand'                    ";
	$sql_com .= " , info_use               = '$info_use'                 ";
	$sql_com .= " {$info_name_sql}                                       ";
	$sql_com .= " {$info_value_sql}                                      ";
	$sql_com .= " , opt_use                = '$opt_use'                  ";
	$sql_com .= " , color_use              = '$color_use'                ";
	$sql_com .= " , opttitle               = '$opttitle'                 ";
	$sql_com .= " , optcode                = '$optcode'                  ";
	$sql_com .= " {$opt_title_sql}                                       ";
	$sql_com .= " {$opt_code_sql}                                        ";
	$sql_com .= " {$opt_req_sql}                                         ";
	$sql_com .= " , optvalue               = '$optvalue'                 ";
	$sql_com .= " , prdimg_R               = '$prdimg_R_name'            ";
	$sql_com .= " {$prdimg_L_sql}                                        ";
	$sql_com .= " {$prdimg_M_sql}                                        ";
	$sql_com .= " {$prdimg_S_sql}                                        ";
	$sql_com .= " , searchkey              = '$searchkey'                ";
	$sql_com .= " , stortexp               = '$stortexp'                 ";
	$sql_com .= " , content                = '$content'                  ";
	$sql_com .= " , wdate                  = now()                       ";
	$sql_com .= " , mdate                  = now()                       ";
	$sql_com .= " , mobileShow             = '$mobileShow'               ";
	$sql_com .= " , mcontent               = '$mcontent'                 ";
	$sql_com .= " , eventcouponuse         = '$eventcouponuse'           ";
	$sql_com .= " , eventcouponlink        = '$eventcouponlink'          ";
	$sql_com .= " , eventcouponidx         = '$eventcouponidx'           ";
	$sql_com .= " , prd_seo_use            = '$prd_seo_use'              ";
	$sql_com .= " , prd_br_title           = '$prd_br_title'             ";
	$sql_com .= " , prd_descript           = '$prd_descript'             ";
	$sql_com .= " , prd_classification     = '$prd_classification'       ";
	$sql_com .= " , prd_keywords           = '$prd_keywords'             ";

	/*$sql_com .= $timesale_sql;*/

	$sql = "INSERT INTO wiz_product SET {$sql_com} ";
	query($sql);

	## 카테고리정보 저장
	if(!empty($class04)) {
		$catcode = $class04;
	} else {
		if(!empty($class03)) $catcode = $class03."00";
		else if(!empty($class02)) $catcode = $class02."0000";
		else {
			if(empty($class01)) $class01 = "0000";
			$catcode = $class01."000000";
		}
	}
	$sql = "insert into wiz_cprelation(idx,prdcode,catcode) values('', '$prdcode', '$catcode')";
	$result = query($sql);

	complete("상품이 입력되었습니다.","prd_input.php?mode=update&prdcode=$prdcode&$param");

/**
 * 상품수정
 **/
} else if($mode == "update") {

	## 상품이미지 삭제
	for($ii=0; ($delimg && $ii<count($delimg)); $ii++){
		if($delimg[$ii] != "") @unlink($prdimg_path."/".$delimg[$ii]);
	}

	## 상품이미지 저장
	include "./prd_imgup.php";

	$prdname = str_replace("'","′",$prdname);

	## 상품아이콘
	for($ii=0; ($prdicon && $ii<count($prdicon)); $ii++){
		$prdicon_list .= $prdicon[$ii]."/";
	}

	## 상품 옵션 1
	for($ii = 0; ($tmp_optcode && $ii < count($tmp_optcode)); $ii++) {
		if(!empty($tmp_optcode[$ii])) $optcode .= $tmp_optcode[$ii]."^";
	}

	## 상품 옵션 2
	for($ii = 0; ($tmp_optcode2 && $ii < count($tmp_optcode2)); $ii++) {
		if($ii == 0) $optcode2 = "";
		if(!empty($tmp_optcode2[$ii])) $optcode2 .= $tmp_optcode2[$ii]."^";
	}

	## 상품 옵션 - 가격/적립금/재고
	for($ii = 0; ($tmp_opt['sellprice'] && $ii < count($tmp_opt['sellprice'])); $ii++) {

		if(empty($tmp_opt['sellprice'][$ii])) $tmp_opt['sellprice'][$ii] = 0;
		if(empty($tmp_opt['reserve'][$ii])) $tmp_opt['reserve'][$ii] = 0;
		if(empty($tmp_opt['stock'][$ii])) $tmp_opt['stock'][$ii] = 0;

		$optvalue .= $tmp_opt['sellprice'][$ii]."^".$tmp_opt['reserve'][$ii]."^".$tmp_opt['stock'][$ii]."^^";
	}


	## 가격추가옵션 PART1
	for($jj = 3; $jj <= 4; $jj++){
		for($ii = 0; (${'optcode'.$jj.'_opt'} && $ii < count(${'optcode'.$jj.'_opt'})); $ii++) {
			if(strcmp(${'optcode'.$jj.'_opt'}[$ii]."^".${'optcode'.$jj.'_pri'}[$ii]."^".${'optcode'.$jj.'_res'}[$ii]."^^", "^^^^")) {

				if(empty(${'optcode'.$jj.'_pri'}[$ii])) ${'optcode'.$jj.'_pri'}[$ii] = 0;
				if(empty(${'optcode'.$jj.'_res'}[$ii])) ${'optcode'.$jj.'_res'}[$ii] = 0;

				${'optcode'.$jj} .= ${'optcode'.$jj.'_opt'}[$ii]."^".${'optcode'.$jj.'_pri'}[$ii]."^".${'optcode'.$jj.'_res'}[$ii]."^^";
			}
		}
	}

	## 가격추가옵션 PART2
	for($kk = 8; $kk <= 11; $kk++){
		for($ii = 0; (${'optcode'.$kk.'_opt'} && $ii < count(${'optcode'.$kk.'_opt'})); $ii++) {
			if(strcmp(${'optcode'.$kk.'_opt'}[$ii]."^".${'optcode'.$kk.'_pri'}[$ii]."^".${'optcode'.$kk.'_res'}[$ii]."^^", "^^^^")) {

				if(empty(${'optcode'.$kk.'_pri'}[$ii])) ${'optcode'.$kk.'_pri'}[$ii] = 0;
				if(empty(${'optcode'.$kk.'_res'}[$ii])) ${'optcode'.$kk.'_res'}[$ii] = 0;

				${'optcode'.$kk} .= ${'optcode'.$kk.'_opt'}[$ii]."^".${'optcode'.$kk.'_pri'}[$ii]."^".${'optcode'.$kk.'_res'}[$ii]."^^";
			}
		}
	}

	if($opt_use != 'Y'){
		$opttitle  = "";
		$optcode   = "";
		$optvalue  = "";
	} else {
		$opttitle  = $opttitle;
		$optcode   = $optcode;
		$optvalue  = $optvalue;
	}

	$opt_title_sql  = "";
	$opt_code_sql   = "";

	for($i=2; $i<=13; $i++) {
		$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i}."'                      ";
		$opt_code_sql   .= " , optcode".$i."          = '".${'optcode'.$i}."'                       ";
	}

	$info_name_sql  = "";
	$info_value_sql = "";

	for($i=1; $i<=6; $i++) {
		$info_name_sql  .= " , info_name".$i."          = '".get_text("input", ${'info_name'.$i})."'                      ";
		$info_value_sql .= " , info_value".$i."         = '".get_text("input", ${'info_value'.$i})."'                     ";
	}
	
	$prdimg_L_sql  = "";
	$prdimg_M_sql = "";
	$prdimg_S_sql = "";

	for($i=1; $i<=5; $i++) {
		$prdimg_L_sql .= " , prdimg_L".$i."         = '".${'prdimg_L'.$i.'_name'}."'                   ";
		$prdimg_M_sql .= " , prdimg_M".$i."         = '".${'prdimg_M'.$i.'_name'}."'                   ";
		$prdimg_S_sql .= " , prdimg_S".$i."         = '".${'prdimg_S'.$i.'_name'}."'                   ";
	}

	$opt_req_sql  = "";

	for($i=3; $i<=13; $i++) {
		$opt_req_sql  .= " , opt".$i."_req            = '".${'opt'.$i.'_req'}."'                      ";
	}

	$prdname = get_text("input", $prdname);
	$stortexp = get_text("textarea", $stortexp);
	$content = get_text("textarea", $content);
	$mcontent = get_text("textarea", $mcontent);

	## 상품정보 저장
	$sql_com = "";
	$sql_com .= " prdcode                  = '$prdcode'                  ";
	$sql_com .= " , prdname                = '$prdname'                  ";
	$sql_com .= " , prdcom                 = '$prdcom'                   ";
	$sql_com .= " , origin                 = '$origin'                   ";
	$sql_com .= " , showset                = '$showset'                  ";
	$sql_com .= " , shortage               = '$shortage'                 ";
	$sql_com .= " , coupon_use             = '$coupon_use'               ";
	$sql_com .= " , coupon_dis             = '$coupon_dis'               ";
	$sql_com .= " , coupon_type            = '$coupon_type'              ";
	$sql_com .= " , coupon_amount          = '$coupon_amount'            ";
	$sql_com .= " , coupon_limit           = '$coupon_limit'             ";
	$sql_com .= " , coupon_sdate           = '$coupon_sdate'             ";
	$sql_com .= " , coupon_edate           = '$coupon_edate'             ";
	$sql_com .= " , del_type               = '$del_type'                 ";
	$sql_com .= " , del_price              = '$del_price'                ";
	$sql_com .= " , prdicon                = '$prdicon_list'             ";
	$sql_com .= " , prefer                 = '$prefer'                   ";
	$sql_com .= " , brand                  = '$brand'                    ";
	$sql_com .= " , stock                  = '$stock'                    ";
	$sql_com .= " , prior                  = '$prior'                    ";
	$sql_com .= " , sellprice              = '$sellprice'                ";
	$sql_com .= " , conprice               = '$conprice'                 ";
	$sql_com .= " , reserve                = '$reserve'                  ";
	$sql_com .= " , strprice               = '$strprice'                 ";
	$sql_com .= " , new                    = '$new'                      ";
	$sql_com .= " , best                   = '$best'                     ";
	$sql_com .= " , popular                = '$popular'                  ";
	$sql_com .= " , recom                  = '$recom'                    ";
	$sql_com .= " , sale                   = '$sale'                     ";
	$sql_com .= " , info_use               = '$info_use'                 ";
	$sql_com .= " {$info_name_sql}                                       ";
	$sql_com .= " {$info_value_sql}                                      ";
	$sql_com .= " , opt_use                = '$opt_use'                  ";
	$sql_com .= " , color_use              = '$color_use'                ";
	$sql_com .= " , opttitle               = '$opttitle'                 ";
	$sql_com .= " , optcode                = '$optcode'                  ";
	$sql_com .= " {$opt_title_sql}                                       ";
	$sql_com .= " {$opt_code_sql}                                        ";
	$sql_com .= " {$opt_req_sql}                                         ";
	$sql_com .= " , optvalue               = '$optvalue'                 ";
	$sql_com .= " , prdimg_R               = '$prdimg_R_name'            ";
	$sql_com .= " {$prdimg_L_sql}                                        ";
	$sql_com .= " {$prdimg_M_sql}                                        ";
	$sql_com .= " {$prdimg_S_sql}                                        ";
	$sql_com .= " , searchkey              = '$searchkey'                ";
	$sql_com .= " , stortexp               = '$stortexp'                 ";
	$sql_com .= " , content                = '$content'                  ";
	$sql_com .= " , mdate                  = now()                       ";
	$sql_com .= " , mobileShow             = '$mobileShow'               ";
	$sql_com .= " , mcontent               = '$mcontent'                 ";
	$sql_com .= " , eventcouponuse         = '$eventcouponuse'           ";
	$sql_com .= " , eventcouponlink        = '$eventcouponlink'          ";
	$sql_com .= " , eventcouponidx         = '$eventcouponidx'           ";
	$sql_com .= " , prd_seo_use            = '$prd_seo_use'              ";
	$sql_com .= " , prd_br_title           = '$prd_br_title'             ";
	$sql_com .= " , prd_descript           = '$prd_descript'             ";
	$sql_com .= " , prd_classification     = '$prd_classification'       ";
	$sql_com .= " , prd_keywords           = '$prd_keywords'             ";

	/*$sql_com .= $timesale_sql;*/

	$sql = "UPDATE wiz_product SET {$sql_com} WHERE prdcode = '$prdcode' ";
	query($sql);

	## 카테고리 정보 저장
	if(!empty($class04)) {
		$catcode = $class04;
	} else {
		if(!empty($class03)) $catcode = $class03."00";
		else if(!empty($class02)) $catcode = $class02."0000";
		else {
			if(empty($class01)) $class01 = "0000";
			$catcode = $class01."000000";
		}
	}

	$sql = "update wiz_cprelation set catcode = '$catcode' where prdcode = '$prdcode' and idx = '$relidx'";
	$result = query($sql);

	complete("상품정보가 수정되었습니다.","prd_input.php?mode=update&prdcode=$prdcode&$param");

/**
 * 상품삭제
 **/
} else if($mode == "delete") {

	if($prdcode) {

		## 카테고리 연관 삭제
		$sql = "delete from wiz_cprelation where prdcode = '$prdcode'";
		$result = query($sql);

		## 관련련상품 연관 삭제
		$sql = "delete from wiz_prdrelation where prdcode = '$prdcode' || relcode = '$prdcode'";
		$result = query($sql);

		## 상품데이타 삭제
		foreach (glob($prdimg_path."/".$prdcode."*") as $filename) {
   		@unlink($filename);
		}

		## 상품평 삭제
		$sql = "delete from wiz_comment where prdcode = '$prdcode'";
		query($sql);

		$sql = "delete from wiz_product where prdcode = '$prdcode'";
		$result = query($sql);

	} else {

		$array_selected = explode("|",$selected);
		$i=0;
		while($array_selected[$i]){

			$tmp_prdcode = $array_selected[$i];

			## 카테고리 연관 삭제
			$sql = "delete from wiz_cprelation where prdcode = '$tmp_prdcode'";
			query($sql);

			## 관련련상품 연관 삭제
			$sql = "delete from wiz_prdrelation where prdcode = '$tmp_prdcode' || relcode = '$tmp_prdcode'";
			query($sql);

			##상품데이타 삭제
			foreach (glob($prdimg_path."/".$tmp_prdcode."*") as $filename) {
	   		@unlink($filename);

			}

			## 상품평 삭제
			$sql = "delete from wiz_comment where prdcode = '$tmp_prdcode'";
			query($sql);

			$sql = "delete from wiz_product where prdcode = '$tmp_prdcode'";
			query($sql);

			$i++;
		}

	}

	complete("선택한 상품을 삭제하였습니다.","prd_list.php?page=$page&$param");

/**
 * 상품복사
 **/
} else if($mode == "prdcopy") {

	## 기존상품 정보
	$sql = "select * from wiz_product where prdcode='$prdcode'";
	$result = query($sql);
	$prd_info = sql_fetch_obj($result);

	## 상품넘버 만들기
	$sql = "select max(prdcode) as prdcode, max(prior) as prior from wiz_product";
	$result = query($sql);
	if($row = sql_fetch_obj($result)) {

		$datenum = substr($row->prdcode,0,6);
		$prdnum = substr($row->prdcode,6,4);
		$prdnum = substr("000".(++$prdnum),-4);

		if($datenum == date('ymd')) $prdcode = $datenum.$prdnum;
		else $prdcode = date('ymd')."0001";

		## 상품진열 순서
 		$prior = $row->prior + 1;

	}else{
		$prdcode = date('ymd')."0001";

		## 상품진열 순서
		$prior = date(ymdHis);

	}

	## 상품이미지
	$prdimg_path = "../../data/prdimg";
	$prdimg_R_name = $prdcode."_R.".pathinfo($prd_info->{'prdimg_R'}, PATHINFO_EXTENSION);
	if(@file($prdimg_path."/".$prd_info->prdimg_R)) copy($prdimg_path."/".$prd_info->prdimg_R, $prdimg_path."/".$prdimg_R_name);

	$prdimg_L_sql = "";
	$prdimg_M_sql = "";
	$prdimg_S_sql = "";

	for($j=1; $j<=5; $j++){

		$L_img_info = pathinfo($prd_info->{'prdimg_L'.$j}, PATHINFO_EXTENSION);
		$M_img_info = pathinfo($prd_info->{'prdimg_M'.$j}, PATHINFO_EXTENSION);
		$S_img_info = pathinfo($prd_info->{'prdimg_S'.$j}, PATHINFO_EXTENSION);

		${'prdimg_L'.$j."_name"} = $prdcode."_L".$j.".".$L_img_info;
		${'prdimg_M'.$j."_name"} = $prdcode."_M".$j.".".$M_img_info;
		${'prdimg_S'.$j."_name"} = $prdcode."_S".$j.".".$S_img_info;

		if(@file($prdimg_path."/".$prd_info->{'prdimg_L'.$j})) 
			copy($prdimg_path."/".$prd_info->{'prdimg_L'.$j}, $prdimg_path."/".${'prdimg_L'.$j."_name"});
		if(@file($prdimg_path."/".$prd_info->{'prdimg_M'.$j})) 
			copy($prdimg_path."/".$prd_info->{'prdimg_M'.$j}, $prdimg_path."/".${'prdimg_M'.$j."_name"});
		if(@file($prdimg_path."/".$prd_info->{'prdimg_S'.$j})) 
			copy($prdimg_path."/".$prd_info->{'prdimg_S'.$j}, $prdimg_path."/".${'prdimg_S'.$j."_name"});

		if($L_img_info) $prdimg_L_sql .= ", prdimg_L".$j." = '".${'prdimg_L'.$j."_name"}."'       ";
		if($M_img_info) $prdimg_M_sql .= ", prdimg_M".$j." = '".${'prdimg_M'.$j."_name"}."'       ";
		if($S_img_info) $prdimg_S_sql .= ", prdimg_S".$j." = '".${'prdimg_S'.$j."_name"}."'       ";

	}

	$prd_info->content = addslashes($prd_info->content);
	$prd_info->prior = $prior;

	$opt_title_sql  = "";
	$opt_code_sql   = "";
	for($i=2; $i<=13; $i++) {
		$opt_title_sql  .= " , opttitle".$i."         = '".$prd_info->{'opttitle'.$i}."'            ";
		$opt_code_sql   .= " , optcode".$i."          = '".$prd_info->{'optcode'.$i}."'             ";
	}

	$info_name_sql  = "";
	$info_value_sql = "";
	for($i=1; $i<=6; $i++) {
		$info_name_sql  .= " , info_name".$i."        = '".get_text("input", $prd_info->{'info_name'.$i})."'            ";
		$info_value_sql .= " , info_value".$i."       = '".get_text("input", $prd_info->{'info_value'.$i})."'           ";
	}

	$opt_req_sql  = "";
	for($i=3; $i<=13; $i++) {
		$opt_req_sql  .= " , opt".$i."_req            = '".$prd_info->{'opt'.$i.'_req'}."'           ";
	}

	$prd_info->prdname = get_text("input", $prd_info->prdname);
	$prd_info->stortexp = get_text("textarea", $prd_info->stortexp);
	$prd_info->content = get_text("textarea", $prd_info->content);
	$prd_info->mcontent = get_text("textarea", $prd_info->mcontent);

	## 상품정보 저장
	$sql_com = "";
	$sql_com .= " prdcode               = '$prdcode'                    ";
	$sql_com .= " , prdname             = '$prd_info->prdname'          ";
	$sql_com .= " , prdcom              = '$prd_info->prdcom'           ";
	$sql_com .= " , origin              = '$prd_info->origin'           ";
	$sql_com .= " , showset             = '$prd_info->showset'          ";
	$sql_com .= " , stock               = '$prd_info->stock'            ";
	$sql_com .= " , savestock           = '$prd_info->savestock'        ";
	$sql_com .= " , prior               = '$prd_info->prior'            ";
	$sql_com .= " , viewcnt             = '0'                           ";
	$sql_com .= " , deimgcnt            = '0'                           ";
	$sql_com .= " , basketcnt           = '0'                           ";
	$sql_com .= " , ordercnt            = '0'                           ";
	$sql_com .= " , cancelcnt           = '0'                           ";
	$sql_com .= " , comcnt              = '0'                           ";
	$sql_com .= " , sellprice           = '$prd_info->sellprice'        ";
	$sql_com .= " , conprice            = '$prd_info->conprice'         ";
	$sql_com .= " , reserve             = '$prd_info->reserve'          ";
	$sql_com .= " , strprice            = '$prd_info->strprice'         ";
	$sql_com .= " , new                 = '$prd_info->new'              ";
	$sql_com .= " , best                = '$prd_info->best'             ";
	$sql_com .= " , popular             = '$prd_info->popular'          ";
	$sql_com .= " , recom               = '$prd_info->recom'            ";
	$sql_com .= " , sale                = '$prd_info->sale'             ";
	$sql_com .= " , shortage            = '$prd_info->shortage'         ";
	$sql_com .= " , del_type            = '$prd_info->del_type'         ";
	$sql_com .= " , del_price           = '$prd_info->del_price'        ";
	$sql_com .= " , prdicon             = '$prd_info->prdicon_list'     ";
	$sql_com .= " , prefer              = '$prd_info->prefer'           ";
	$sql_com .= " , brand               = '$prd_info->brand'            ";
	$sql_com .= " , info_use            = '$prd_info->info_use'         ";
	$sql_com .= " {$info_name_sql}                                      ";
	$sql_com .= " {$info_value_sql}                                     ";
	$sql_com .= " , opt_use             = '$prd_info->opt_use'          ";
	$sql_com .= " , color_use           = '$prd_info->color_use'        ";
	$sql_com .= " , opttitle            = '$prd_info->opttitle'         ";
	$sql_com .= " , optcode             = '$prd_info->optcode'          ";
	$sql_com .= " {$opt_title_sql}                                      ";
	$sql_com .= " {$opt_code_sql}                                       ";
	$sql_com .= " {$opt_req_sql}                                        ";
	$sql_com .= " , optvalue            = '$prd_info->optvalue'         ";
	$sql_com .= " , prdimg_R            = '$prdimg_R_name'              ";
	$sql_com .= " {$prdimg_L_sql}                                       ";
	$sql_com .= " {$prdimg_M_sql}                                       ";
	$sql_com .= " {$prdimg_S_sql}                                       ";
	$sql_com .= " , searchkey           = '$prd_info->searchkey'        ";
	$sql_com .= " , stortexp            = '$prd_info->stortexp'         ";
	$sql_com .= " , content             = '$prd_info->content'          ";
	$sql_com .= " , wdate               = now()                         ";
	$sql_com .= " , mdate               = now()                         ";
	$sql_com .= " , mobileShow          = '$prd_info->mobileShow'       ";
	$sql_com .= " , mcontent            = '$prd_info->mcontent'         ";
	$sql_com .= " , eventcouponuse      = '$prd_info->eventcouponuse'   ";
	$sql_com .= " , eventcouponlink     = '$prd_info->eventcouponlink'  ";
	$sql_com .= " , eventcouponidx      = '$prd_info->eventcouponidx'   ";
	$sql_com .= " , prd_seo_use         = '$prd_info->prd_seo_use'      ";
	$sql_com .= " , prd_br_title        = '$prd_info->prd_br_title'     ";
	$sql_com .= " , prd_descript        = '$prd_info->prd_descript'     ";
	$sql_com .= " , prd_classification  = '$prd_info->prd_classification'";
	$sql_com .= " , prd_keywords        = '$prd_info->prd_keywords'     ";

	$sql = "INSERT INTO wiz_product SET {$sql_com} ";
	query($sql);

	## 카테고리정보 저장
	$sql = "select * from wiz_cprelation where prdcode='$prd_info->prdcode'";
	$result = query($sql);
	while($row = sql_fetch_obj($result)) {

		$sql = "insert into wiz_cprelation(idx,prdcode,catcode) values('', '$prdcode', '$row->catcode')";
		query($sql);

	}

	complete("복사되었습니다.","prd_list.php?$param");

/**
 * 상품진열순서
 **/
} else if($mode == "prior") {

	$where = array();

	if(!empty($dep_code))		$where[] = "wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' ";
	if(!empty($special))		$where[] = "wp.$special = 'Y' ";
	if(!empty($display))		$where[] = "wp.showset = '$display' ";
	if(!empty($searchopt))		$where[] = "wp.$searchopt like '%$searchkey%' ";
	if(!empty($coupon_use))		$where[] = "wp.coupon_use = '$coupon_use' ";
	if(!empty($brand))			$where[] = "wp.brand = '$brand' ";
	if(!empty($shortage)) {
		if(!strcmp($shortage, "N"))	$where[]= " (wp.shortage = '$shortage' or wp.shortage = '') ";
		else						$where[]= " wp.shortage = '$shortage' ";
	}
	if(!strcmp($shortage, "S"))	$where[] = " wp.stock <= '$stock' ";

	$sql_search   = ($where) ? " AND ".implode(" AND ", $where) : "";

	$sql = "
		SELECT DISTINCT wp.prdcode
			 , wp.prdname
			 , wp.prior
		  FROM wiz_product wp
			 , wiz_cprelation wc
		 WHERE wc.prdcode = wp.prdcode
			$sql_search
	";

	// 1단계위로
	if($posi == "up"){

		$sql .= " AND wp.prior >= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior ASC limit 1";
		$result = query($sql);

		if($row = sql_fetch_obj($result)) {

			$change_prior = $row->prior;
			$change_prdcode = $row->prdcode;

			$prior_sql_1 = "UPDATE wiz_product SET prior=(prior+1) WHERE prior>'".$change_prior."'";
			$prior_result = query($prior_sql_1);

			$prior_sql_2 = "UPDATE wiz_product SET prior = '".($change_prior+1)."' WHERE prdcode = '".$prdcode."'";
			$prior_result_2 = query($prior_sql_2);

		}

	// 1단계아래로
	} else if($posi == "down") {

		$sql .= " AND wp.prior <= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior DESC limit 1";
		$result = query($sql);

		if($row = sql_fetch_obj($result)) {

			$change_prior = $row->prior;
			$change_prdcode = $row->prdcode;

			$prior_sql_1 = "UPDATE wiz_product SET prior=(prior-1) WHERE prior<'".$change_prior."'";
			$prior_result_1 = query($prior_sql_1);

			$prior_sql_2 = "UPDATE wiz_product SET prior = '".($change_prior-1)."' WHERE prdcode = '".$prdcode."'";
			$prior_result_2 = query($prior_sql_2);
		}

	// 10단계위로
	}else if($posi == "upup"){

		$count=1;

		$sql .= " AND wp.prior >= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior ASC limit 10";
		$result = query($sql);
		$total = sql_fetch_row($result);

		while($row = sql_fetch_obj($result)) {

			if($total == $count){
				$change_prior = $row->prior;
				$change_prdcode = $row->prdcode;
				$change_name = $row->prdname;
			}
			$count++;
		}

		if($total > 0){

			$prior_sql_1 = "UPDATE wiz_product SET prior=(prior+1) WHERE prior>'".$change_prior."'";
			$prior_result_1 = query($prior_sql_1);

			$prior_sql_2 = "UPDATE wiz_product SET prior = '".($change_prior+1)."' WHERE prdcode = '".$prdcode."'";
			$prior_result_2 = query($prior_sql_2);

		}

	// 10단계아래로
	}else if($posi == "downdown"){

		$count=1;

		$sql .= " AND wp.prior <= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior DESC limit 10";
		$result = query($sql);
		$total = sql_fetch_row($result);

		while($row = sql_fetch_obj($result)) {

			if($total==$count){
				$change_prior = $row->prior;
				$change_prdcode = $row->prdcode;
				$change_name = $row->prdname;
			}
			$count++;
		}

		if($total > 0){

			$prior_sql_1 = "UPDATE wiz_product SET prior=(prior-1) WHERE prior<'".$change_prior."'";
			$prior_result_1 = query($prior_sql_1);

			$prior_sql_2 = "UPDATE wiz_product SET prior = '".($change_prior-1)."' WHERE prdcode = '".$prdcode."'";
			$prior_result_2 = query($prior_sql_2);

		}
	}

	complete("진열순서를 변경하였습니다.","prd_list.php?$param");

/**
 * 상품평삭제
 **/
} else if($mode == "delesti") {

	## 1개 상품평 삭제
	if($estiidx){

		$sql = "delete from wiz_bbs where idx = '$estiidx'";
		$result = query($sql);

	## 선택 상품평 삭제
	}else{

		$array_selected = explode("|",$selected);
		$i=0;
		while($array_selected[$i]){

			$tmp_estiidx = $array_selected[$i];

			$sql = "delete from wiz_bbs where idx = '$tmp_estiidx'";
			$result = query($sql);

			$i++;
		}

	}

	complete("선택한 상품평을 삭제하였습니다.","prd_estimate.php?page=$page&$menucodeParam");

/**
 * 재고관리
 **/
} else if($mode == "stock") {

	$sql = "update wiz_product set stock='$stock', savestock='$savestock' where prdcode='$prdcode'";
	$result = query($sql);

	complete("선택한 상품재고를 수정하였습니다.","prd_shortage.php?$param");

/**
 * 옵션수정
 **/
} else if($mode == "optedit") {

	if(!empty($prdcode)){

		$sql = "update wiz_product set optvalue = '$optvalue' where prdcode = '$prdcode'";
		$result = query($sql);
		echo "<script>alert('옵션항목이 적용되었습니다.');opener.document.location.reload();self.close();</script>";

	}else{
		echo "<script>alert('상품코드가 없습니다.');self.close();</script>";
	}

/**
 * 카테고리분류추가
 **/
} else if($mode == "catlist") {

	if($submode == "insert"){

		if(!empty($class04)) {
			$catcode = $class04;
		} else {
			if(!empty($class03)) $catcode = $class03."00";
			else if(!empty($class02)) $catcode = $class02."0000";
			else {
				if(empty($class01)) $class01 = "0000";
				$catcode = $class01."000000";
			}
		}

		$sql = "select * from wiz_cprelation where prdcode = '$prdcode' and catcode = '$catcode'";
		$result = query($sql);

		if($row = sql_fetch_obj($result)) {
			error('이미등록된 분류입니다.');
		}else{
			$sql = "insert into wiz_cprelation(idx,prdcode,catcode) values('', '$prdcode', '$catcode')";
			$result = query($sql);

			complete('분류를 추가하였습니다.','prd_catlist.php?prdcode='.$prdcode);

		}

	}else if($submode == "delete"){

		$sql = "delete from wiz_cprelation where prdcode = '$prdcode' and catcode = '$catcode'";
		$result = query($sql);

		complete('선택한 분류를 삭제하였습니다.','prd_catlist.php?prdcode='.$prdcode."&".$menucodeParam);

	}

/**
 * 상품아이콘 등록
 **/
} else if($mode == "prdicon") {

	if($upfile['size'] > 0){
		file_check($upfile['name']);
		copy($upfile['tmp_name'], $prdicon_path."/".$upfile['name']);
		chmod($prdicon_path."/".$upfile['name'], 0606);
	}

	complete('등록되었습니다.','prd_icon.php');

/**
 * 상품아이콘 삭제
 **/
} else if($mode == "icondel") {

	@unlink($prdicon_path."/".$prdicon);
	complete('삭제되었습니다.','prd_icon.php');

/**
 * 관련상품 등록
 **/
} else if($mode == "reladd") {

	$array_selected = explode("|",$selected);
	$i=0;
	while($array_selected[$i]){

		$tmp_prdcode = $array_selected[$i];

		$sql = "insert into wiz_prdrelation(idx,prdcode,relcode) values('','$prdcode','$tmp_prdcode')";
		query($sql);

		$i++;
	}

	echo "<script>opener.document.location.reload();</script>";
	complete("등록되었습니다.","prd_rellist.php?$param");

/**
 * 관련상품 삭제
 **/
} else if($mode == "reldel") {

	$sql = "delete from wiz_prdrelation where idx = '".$idx."'";
	query($sql);

	print "delok";

/**
 * 관련상품 (다중체크)
 **/
} else if($mode == "multireldel") {

	$relIdx = explode(",",$chkval);
	for($ii=0; ($relIdx && $ii<count($relIdx)); $ii++){
		$sql = "delete from wiz_prdrelation where idx = '".$relIdx[$ii]."'";
		query($sql);
	}

	print "delok";

/**
 * 진열여부체크
 **/
} else if($mode=="show") {

	list($showset,$mobileShow) = explode("/",$result_show_chk);
	$sql="update wiz_product set showset='$showset',mobileShow='$mobileShow' where prdcode='$prdcode'";
	query($sql);

	complete("진열여부를 변경하였습니다.","prd_list.php?$param");

/**
 * 진열여부 일괄체크
 **/
} else if($mode=="show_check") {

	$aprdcode   = explode('|',$prdcode);
	$show_id    = explode('|',$show_id);
	$m_show_id  = explode('|',$m_show_id);
	$prd_cnt    = $aprdcode ? count($aprdcode) : 0;

	for($ii=0; $ii<$prd_cnt-1; $ii++){
		$prdcode     = $aprdcode[$ii];
		$showset     = $show_id[$ii];
		$mobileShow  = $m_show_id[$ii];

		if($site_info['mobile_use']=="Y"){
			$mobileShow_sql = " ,mobileShow='$mobileShow'";
		}

		$sql = "update wiz_product set showset='$showset' $mobileShow_sql where prdcode='$prdcode'";
		query($sql);

	}

	echo "ok";

/**
 * 상품적립금적용
 **/
} else if($mode=="prdReserve") {

	$sellprice = str_replace(",","",$sellprice);
	$reserve   = str_replace(",","",$reserve);

	$sql = "update wiz_product set sellprice='$sellprice',reserve='$reserve' where prdcode='$prdcode'";
	query($sql);

	echo "ok";

/**
 * 상품적립금 일괄적용
 **/
} else if($mode=="priceAllCheck") {

	$aprdcode   = explode('|',$prdcode);
	$price_id   = explode('|',$price_id);
	$reserve_id = explode('|',$reserve_id);
	$prd_cnt    = $aprdcode ? count($aprdcode) : 0;

	for($ii=0; $ii<$prd_cnt-1; $ii++){
		$prdcode     = $aprdcode[$ii];
		$sellprice   = str_replace(",","",$price_id[$ii]);
		$reserve     = str_replace(",","",$reserve_id[$ii]);

		$sql = "update wiz_product set sellprice='$sellprice',reserve='$reserve' where prdcode='$prdcode'";
		query($sql);

	}

	echo "ok";

/**
 * 장바구니 삭제
 **/
} else if(!strcmp($mode, "basketDel")) {

	$idx_list = explode("|", $selorder);
	for($ii = 0; ($idx_list && $ii < count($idx_list)); $ii++) {
		$idx = $idx_list[$ii];
		$sql = "delete from wiz_basket_tmp where idx = '$idx'";
		query($sql);
	}

	complete("삭제되었습니다.","_total_cart.php?page=$page&$param");


/**
 * 위시리스트 삭제
 **/
} else if(!strcmp($mode, "wishlistDel")) {

	$idx_list = explode("|", $selorder);
	for($ii = 0; ($idx_list && $ii < count($idx_list)); $ii++) {
		$idx = $idx_list[$ii];
		$sql = "delete from wiz_wishlist where idx = '$idx'";
		query($sql);
	}

	complete("삭제되었습니다.","_total_wishlist.php?page=$page&$param");


} else if($mode == "set_common_seo"){
	// 공통 SEO 등록
	$str = array();
	$browser_title = get_text("input", $_POST['browser_title']);
	$searchkey_de = get_text("textarea", $_POST['searchkey_de']);
	$searchkey_cl = get_text("textarea", $_POST['searchkey_cl']);
	$searchkey = get_text("textarea", $_POST['searchkey']);

	$sql = " 
			update 
				wiz_siteinfo 
			set 
				prd_browser_title = '".$browser_title."'
				, prd_searchkey_de = '".$searchkey_de."'
				, prd_searchkey_cl= '".$searchkey_cl."'
				, prd_searchkey = '".$searchkey."'
	";
	$result = query($sql);

	if(!$result){
		$str['result'] = "100";
		$str['msg'] = "DB저장 오류";
	} else {
		$str['result'] = "000";
		$str['msg'] = "상품 공통 SEO정보가 저장되었습니다.";
	}

	echo json_encode($str);
}
?>
