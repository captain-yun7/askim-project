<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

/**
 네이버 지식쇼핑 EP 3.0
 프로그램 커스터마이징시 필드가 추가될수있음
 참조 : https://adcenter.shopping.naver.com/main.nhn

Field           Status      Des.
id              필수         상품ID
title           필수         상품명
price_pc        필수         상품가격
price_mobile    권장         모바일상품가격
normal_price    권장         정가
link            필수         상품URL
mobile_link     권장         상품모바일 URL
image_link      필수         이미지URL
category_name1  필수         카테고리명(대분류)
category_name2  권장         카테고리명(중분류)
category_name3  권장         카테고리명(소분류)
category_name4  권장         카테고리명(세분류)
brand           권장         브랜드
maker           권장         제조사
origin          권장         원산지
event_words     권장         이벤트
point           권장         포인트
shipping        필수         배송료
class           필수(요약)    I (신규 상품) / U (업데이트 상품) / D (품절 상품 )
update_time     필수(요약)   상품정보 생성 시각
**/

ob_end_clean();

$tab = "\t";

ob_start();

echo "id{$tab}title{$tab}price_pc{$tab}link{$tab}image_link{$tab}category_name1{$tab}category_name2{$tab}category_name3{$tab}category_name4{$tab}brand{$tab}maker{$tab}origin{$tab}event_words{$tab}point{$tab}shipping{$tab}class{$tab}update_time";

$db_Select = "
	SELECT * 
	  FROM wiz_product wp
		 , wiz_cprelation wc
		 , wiz_category wt
	WHERE wp.showset != 'N'
	  AND wp.shortage != 'Y'
	  AND wt.catuse != 'N'
	  AND wc.prdcode = wp.prdcode
	  AND wt.catcode = wc.catcode 
	ORDER BY wp.prdcode DESC
";

$db_Query = mysql_query($db_Select);

$str = "";
	
while($db_rows = mysql_fetch_array($db_Query)){		

	$imgpath_M	= $_SERVER["HTTP_HOST"]."/twcenter/data/prdimg/$db_rows['prdimg_M1']";
	
	/* 작업자	: 김나연
	   작업일	: 2020-10-22
	   작업내용	: 기본배송정책 적용 상품에 대한 기본배송비 적용 및 착불상품 배송비 값 수정 (착불 : -1)
	 */
	if($db_rows['del_type'] == 'DA') {		
		if($oper_info['del_method'] == 'DA') {		///무료배송
			$db_rows['del_type'] = 'DB';
		} else if ($oper_info['del_method'] == 'DB') {		//착불
			$db_rows['del_type'] = 'DD';
		} else if ($oper_info['del_method'] == 'DC') {		//고정배송비
			$db_rows['del_type'] = 'DC';
			$db_rows['del_price'] = $oper_info['del_fixprice'];
		} else if ($oper_info['del_method'] == 'DD') {		// 구매가격별배송비
			$db_rows['del_type'] = 'DA';
		}
	}
	
	//-- 상품단가 기준에 의한 배송료 정책
	if($db_rows['del_type'] == 'DA'){		
		if($db_rows['sellprice'] > $oper_info['del_staprice']) $del_pay = 0;
		else $del_pay = $oper_info['del_staprice3'];
	} else if($db_rows['del_type'] == 'DB'){
		$del_pay = 0;
	} else if($db_rows['del_type'] == 'DC'){
		$del_pay = $db_rows['del_price'];
	} else if($db_rows['del_type'] == 'DD'){
		$del_pay = -1;
	}

/*
작업자	: 임서연
작업일시	: 2020-03-05
작업내용	: 지식쇼핑 상품 URL 자동연동(수정 반영 작업)
*/

	$catcode01 = substr($db_rows['catcode'],0,2)."000000";
	$catcode02 = substr($db_rows['catcode'],0,4)."0000";
	$catcode03 = substr($db_rows['catcode'],0,6)."00";
	$catcode04 = substr($db_rows['catcode'],0,8);

	$prd_Qry_1 = "SELECT * FROM wiz_category WHERE catcode  = '$catcode01' and depthno=1";				//-- '1차카테고리명 불러오기'
	$prd_Res_1 = mysql_query($prd_Qry_1) or error(mysql_error());
	$prd_Row_1 = mysql_fetch_object($prd_Res_1);

	$prd_Qry_2 = "SELECT catname FROM wiz_category WHERE catcode  = '$catcode02' and depthno=2";		//-- '2차카테고리명 불러오기'
	$prd_Res_2 = mysql_query($prd_Qry_2) or error(mysql_error());
	$prd_Row_2 = mysql_fetch_object($prd_Res_2);

	$prd_Qry_3 = "SELECT catname FROM wiz_category WHERE catcode like '$catcode03%' and depthno=3";		//-- '3차카테고리명 불러오기'
	$prd_Res_3 = mysql_query($prd_Qry_3) or error(mysql_error());
	$prd_Row_3 = mysql_fetch_object($prd_Res_3);

	$prd_Qry_5 = "SELECT catname FROM wiz_category WHERE catcode like '$catcode04%' and depthno=4";		//-- '4차카테고리명 불러오기'
	$prd_Res_5 = mysql_query($prd_Qry_5) or error(mysql_error());
	$prd_Row_5 = mysql_fetch_object($prd_Res_5);

	$prd_Qry_4 = "SELECT brdname FROM wiz_brand WHERE idx='$db_rows['brand']'";		//-- '브랜드 불러오기'
	$prd_Res_4 = mysql_query($prd_Qry_4) or error(mysql_error());
	$prd_Row_4 = mysql_fetch_object($prd_Res_4);

	$prdname = strip_tags($db_rows['prdname']);

	$pgUrl	 = $_SERVER["HTTP_HOST"]."/".$prd_Row_1["purl"]."?ptype=view&prdcode=$db_rows['prdcode']&catcode=$db_rows['catcode']";

	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		$prdUrl  = "https://".$pgUrl;
		$imgUrl  = "https://".$imgpath_M;
	} else {
		$prdUrl  = "http://".$pgUrl;
		$imgUrl  = "http://".$imgpath_M;
	}
	
	$cate_1  = $prd_Row_1->catname;
	$cate_2  = $prd_Row_2->catname;
	$cate_3  = $prd_Row_3->catname;
	$cate_4  = $prd_Row_5->catname;
	$maker   = $db_rows['prdcom'];
	$origin  = $db_rows['origin'];
	$brand   = $prd_Row_4->brdname;
	$reserve = $db_rows['reserve'];

	$wdate   = substr($db_rows['wdate'], 0, 10);

	$show_class = "U";
	if($db_rows['shortage'] == 'Y' || ($db_rows['shortage'] == 'S' && $db_rows['stock'] <= 0)) $show_class = "D";
	else if($wdate == THIS_TIME_YMD && $db_rows['wdate'] == $db_rows['mdate']) $show_class = "I";

	$update_time = $db_rows['mdate'];

	$event_words = "";

	echo "\n{$db_rows['prdcode']}{$tab}{$prdname}{$tab}{$db_rows['sellprice']}{$tab}{$prdUrl}{$tab}{$imgUrl}{$tab}{$cate_1}{$tab}{$cate_2}{$tab}{$cate_3}{$tab}{$cate_4}{$tab}{$brand}{$tab}{$maker}{$tab}{$origin}{$tab}{$event_words}{$tab}{$reserve}{$tab}{$del_pay}{$tab}{$show_class}{$tab}{$update_time}";

}

$content = ob_get_contents();
ob_end_clean();

echo $content;

?>