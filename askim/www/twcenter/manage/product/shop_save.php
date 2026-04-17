<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

// 운영정보설정
if($mode == "oper_info"){
	if($pay_method) {
		for($ii=0; $ii<count($pay_method); $ii++){
			$pay_tmp .= $pay_method[$ii]."/";
		}
	}
	$easy_pay_tmp = array();
	if($easy_pay_method) {
		for($ii=0; $ii<count($easy_pay_method); $ii++){
			$easy_pay_tmp[] = $easy_pay_method[$ii];
		}
	}
	$easy_pay = implode("/", $easy_pay_tmp);

	if($pay_agent == "DACOM") {
		$card_quotabase = (is_array($card_quotabase)) ? "0:".implode(":",$card_quotabase) : "0:";
	} else if($pay_agent == "KCP") {
		$card_quotabase = trim($card_quotabase2);
	} else if($pay_agent == "INICIS") {
		$card_quotabase = (is_array($card_quotabase3)) ? implode(":",$card_quotabase3) : "";
	}
	//echo $card_quotabase;
	if($card_quota_use == 'N') $card_quotabase = "";

	$sql = "";
	$sql .= " SET pay_method            = '" . $pay_tmp . "'                   ";
	$sql .= ", easy_pay_method          = '" . $easy_pay . "'                  ";
	$sql .= ", pay_id                   = '" . $pay_id . "'                    ";
	$sql .= ", pay_key                  = '" . $pay_key . "'                   ";
	$sql .= ", pay_agent                = '" . $pay_agent . "'                 ";
	$sql .= ", pay_escrow               = '" . $pay_escrow . "'                ";
	$sql .= ", pay_test                 = '" . $pay_test . "'                  ";
	$sql .= ", del_com                  = '" . $del_com . "'                   ";
	$sql .= ", del_trace                = '" . $del_trace . "'                 ";
	$sql .= ", del_prd                  = '" . $del_prd . "'                   ";
	$sql .= ", del_prd2                 = '" . $del_prd2 . "'                  ";
	$sql .= ", del_method               = '" . $del_method . "'                ";
	$sql .= ", del_fixprice             = '" . $del_fixprice . "'              ";
	$sql .= ", del_staprice             = '" . $del_staprice . "'              ";
	$sql .= ", del_staprice2            = '" . $del_staprice2 . "'             ";
	$sql .= ", del_staprice3            = '" . $del_staprice3 . "'             ";
	$sql .= ", del_extrapost1           = '" . $del_extrapost1 . "'            ";
	$sql .= ", del_extrapost12          = '" . $del_extrapost12 . "'           ";
	$sql .= ", del_extraprice1          = '" . $del_extraprice1 . "'           ";
	$sql .= ", del_extrapost2           = '" . $del_extrapost2 . "'            ";
	$sql .= ", del_extrapost22          = '" . $del_extrapost22 . "'           ";
	$sql .= ", del_extraprice2          = '" . $del_extraprice2 . "'           ";
	$sql .= ", del_extrapost3           = '" . $del_extrapost3 . "'            ";
	$sql .= ", del_extrapost32          = '" . $del_extrapost32 . "'           ";
	$sql .= ", del_extraprice3          = '" . $del_extraprice3 . "'           ";
	$sql .= ", reserve_use              = '" . $reserve_use . "'               ";
	$sql .= ", reserve_join             = '" . $reserve_join . "'              ";
	$sql .= ", recom_use                = '" . $recom_use . "'                 ";
	$sql .= ", reserve_recom            = '" . $reserve_recom . "'             ";
	$sql .= ", reserve_min              = '" . $reserve_min . "'               ";
	$sql .= ", reserve_max              = '" . $reserve_max . "'               ";
	$sql .= ", reserve_buy              = '" . $reserve_buy . "'               ";
	$sql .= ", reserve_per              = '" . $reserve_per . "'               ";
	$sql .= ", review_use               = '" . $review_use . "'                ";
	$sql .= ", review_level             = '" . $review_level . "'              ";
	$sql .= ", tax_use                  = '" . $tax_use . "'                   ";
	$sql .= ", tax_status               = '" . $tax_status . "'                ";
	$sql .= ", tax_api                  = '" . $tax_api . "'                   ";
	$sql .= ", tax_id                   = '" . $tax_id . "'                    ";
	$sql .= ", tax_passwd               = '" . $tax_passwd . "'                ";
	$sql .= ", tax_type                 = '" . $tax_type . "'                  ";			// 세금계산서 연동방법
	$sql .= ", tax_certkey              = '" . $tax_certkey . "'               ";			// 바로빌 연동인증키
	$sql .= ", prdrel_use               = '" . $prdrel_use . "'                ";
	$sql .= ", chk_connect_type         = '" . $chk_connect_type . "'          ";
	$sql .= ", chk_prdshow              = '" . $chk_prdshow . "'               ";
	$sql .= ", del_extrapost1_addr      = '" . $del_extrapost1_addr . "'       ";
	$sql .= ", del_extrapost12_addr     = '" . $del_extrapost12_addr . "'      ";
	$sql .= ", del_extrapost2_addr      = '" . $del_extrapost2_addr . "'       ";
	$sql .= ", del_extrapost22_addr     = '" . $del_extrapost22_addr . "'      ";
	$sql .= ", del_extrapost3_addr      = '" . $del_extrapost3_addr . "'       ";
	$sql .= ", del_extrapost32_addr     = '" . $del_extrapost32_addr . "'      ";
	$sql .= ", chk_readglass            = '" . $chk_readglass . "'             ";
	$sql .= ", unLimited                = '" . $unLimited . "'                 ";
	$sql .= ", pay_id_escrow            = '" . $pay_id_escrow . "'             ";
	$sql .= ", pay_key_escrow           = '" . $pay_key_escrow . "'            ";
	$sql .= ", card_quota_use           = '" . $card_quota_use . "'            ";
	$sql .= ", card_quotabase           = '" . $card_quotabase . "'            ";
	$sql .= ", del_batch_use            = '" . $del_batch_use . "'             ";
	$sql .= ", cash_receipts_use        = '" . $cash_receipts_use . "'         ";
	$sql .= ", receipts_issue           = '" . $receipts_issue . "'            ";
	$sql .= ", ord_cancel_type          = '" . $ord_cancel_type . "'           ";

	query(" UPDATE wiz_operinfo $sql ");


	$code = "review";
	$sql = "UPDATE wiz_bbsinfo SET usetype = '$review_usetype', wpermi = '$review_wpermi' where code = '$code'";
	query($sql);

	$code = "qna";
	$sql = "UPDATE wiz_bbsinfo SET usetype = '$qna_usetype', wpermi = '$qna_wpermi' where code = '$code'";
	query($sql);

	$sql = "UPDATE wiz_siteinfo SET estimate_bigo='$estimate_bigo'";
	query($sql);


	complete("운영정보 설정이 저장되었습니다.","shop_oper.php");

// 쿠폰관리
}else if($mode == "shop_coupon"){

	$couponimg_path = "../../data/coupon";
	if(!is_dir($couponimg_path)) mkdir($couponimg_path, 0707);	// 업로드 디렉토리 생성

	$sql = "select * from wiz_coupon where idx='$idx'";
	$result = query($sql) or error("sql error");
	$coupon_info = sql_fetch_arr($result);

	if($sub_mode == "insert"){

		if($coupon_img['size'] > 0){
			file_check($coupon_img['name']);
			copy($coupon_img['tmp_name'], $couponimg_path."/".$coupon_img['name']);
			@chmod($couponimg_path."/".$coupon_img['name'], 0606);
		}

		$sql = "";
		$sql .= " SET idx                   = ''                                  ";
		$sql .= ", coupon_name              = '" . $coupon_name . "'              ";
		$sql .= ", coupon_img               = '" . $coupon_img['name'] . "'         ";
		$sql .= ", coupon_sdate             = '" . $coupon_sdate . "'             ";
		$sql .= ", coupon_edate             = '" . $coupon_edate . "'             ";
		$sql .= ", coupon_amount            = '" . $coupon_amount . "'            ";
		$sql .= ", coupon_limit             = '" . $coupon_limit . "'             ";
		$sql .= ", coupon_dis               = '" . $coupon_dis . "'               ";
		$sql .= ", coupon_type              = '" . $coupon_type . "'              ";
		$sql .= ", coupon_useE_type              = '" . $coupon_useE_type . "'              ";
		$sql .= ", coupon_use_edate              = '" . $coupon_use_edate . "'              ";
		$sql .= ", coupon_use_eday              = '" . $coupon_use_eday . "'              ";
		$sql .= ", coupon_price_limit              = '" . $coupon_price_limit . "'              ";
		$sql .= ", wdate                    = now()                               ";

		query(" INSERT INTO wiz_coupon $sql ");

		complete("쿠폰이 생성되었습니다.","shop_coupon.php");

	}else if($sub_mode == "update"){

		if($coupon_img['size'] > 0){
			file_check($coupon_img['name']);
			@unlink($couponimg_path."/".$coupon_info['coupon_img']);
			copy($coupon_img['tmp_name'], $couponimg_path."/".$coupon_img['name']);
			chmod($couponimg_path."/".$coupon_img['name'], 0606);
			
			$coupon_img_sql = " , coupon_img = '{$coupon_img['name']}' ";
		}

		$sql = "";
		$sql .= " SET coupon_name           = '" . $coupon_name . "'              ";
		$sql .= " " . $coupon_img_sql . "                                         ";
		$sql .= ", coupon_sdate             = '" . $coupon_sdate . "'             ";
		$sql .= ", coupon_edate             = '" . $coupon_edate . "'             ";
		$sql .= ", coupon_amount            = '" . $coupon_amount . "'            ";
		$sql .= ", coupon_limit             = '" . $coupon_limit . "'             ";
		$sql .= ", coupon_dis               = '" . $coupon_dis . "'               ";
		$sql .= ", coupon_type              = '" . $coupon_type . "'              ";
		$sql .= ", coupon_useE_type              = '" . $coupon_useE_type . "'              ";
		$sql .= ", coupon_use_edate              = '" . $coupon_use_edate . "'              ";
		$sql .= ", coupon_use_eday              = '" . $coupon_use_eday . "'              ";
		$sql .= ", coupon_price_limit              = '" . $coupon_price_limit . "'              ";
		$sql .= " WHERE idx                 = '" . $idx . "'                      ";

		query(" UPDATE wiz_coupon $sql ");

		complete("쿠폰이 수정되었습니다.","shop_coupon_input.php?sub_mode=update&idx=$idx");

	}else if($sub_mode == "delete"){

		@unlink($couponimg_path."/".$coupon_info['coupon_img']);
		$sql = "delete from wiz_coupon where idx = '$idx'";
		query($sql);

		complete("쿠폰이 삭제되었습니다.","shop_coupon.php");

	}else if($sub_mode == "coupon_img_del"){

		@unlink($couponimg_path."/".$coupon_info['coupon_img']);

		$sql = "update wiz_coupon set coupon_img  = '' where idx = '$idx'";
		query($sql);

		complete("쿠폰이미지가 삭제되었습니다.","shop_coupon_input.php?sub_mode=update&idx=$idx");

	}

// 회원발급쿠폰 삭제
}else if(!strcmp($mode, "delmycoupon")) {

	$sql = "delete from wiz_mycoupon where idx = '$idx'";
		query($sql);
	
	/*
	작업자명	: 이상민
	작업일시	: 2020-11-27
	작업내용	: 쿠폰삭제 시 수량복원안되는 오류 수정
	*/
	$sql = "select * from wiz_product where prdcode='$prdcode'";
	$result = query($sql) or error("sql error");
	$prd_info = sql_fetch_obj($result);

	if($prd_info->coupon_limit != "N"){
		$sql = "update wiz_product set coupon_amount = coupon_amount + 1 where prdcode='$prdcode'";
		query($sql) or error("sql error");
	}

	complete("쿠폰이 삭제되었습니다.","shop_mycoupon.php?prdcode=$prdcode");

// 쿠폰사용여부 설정
}else if($mode == "coupon_use"){

	$sql = "update wiz_operinfo set coupon_use ='$coupon_use'";
	query($sql);

	complete("쿠폰사용여부 설정이 저장되었습니다.","shop_coupon.php");

// 적립금 일괄적용
}else if($mode == "setreserve"){

	$percent = $reserve_per/100;

	$sql = "update wiz_product set reserve = sellprice * $percent";
	query($sql);

	$sql = "update wiz_operinfo set reserve_per ='$reserve_per'";
	query($sql);

	complete("적립금 일괄적용 되었습니다.","shop_oper.php");

// 뱅크다 아이디 및 비번
}else if($mode == "bankda"){

	$sql = "
		update wiz_operinfo 
		   set bankda_id = '$user_id'
			 , bankda_pw = '$user_pw'
	";
	query($sql);


}

?>