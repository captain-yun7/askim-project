<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

ob_end_clean();

$tab = "\t";

ob_start();

echo "id{$tab}title{$tab}price_pc{$tab}link{$tab}image_link{$tab}category_name1{$tab}category_name2{$tab}category_name3{$tab}category_name4{$tab}brand{$tab}maker{$tab}origin{$tab}point{$tab}shipping";

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

$db_Query = query($db_Select);

$str = "";
	
while($db_rows = sql_fetch_arr($db_Query)){		

	$imgpath_M	= $_SERVER["HTTP_HOST"]."/twcenter/data/prdimg/$db_rows['prdimg_M1']";
	
	//-- 상품단가 기준에 의한 배송료 정책
	if($db_rows['del_type'] == 'DA'){
		if($db_rows['sellprice'] > $oper_info['del_staprice']) $del_pay = 0;
		else $del_pay = $oper_info['del_staprice3'];
	} else if($db_rows['del_type'] == 'DB'){
		$del_pay = 0;
	} else if($db_rows['del_type'] == 'DC'){
		$del_pay = $db_rows['del_price'];
	} else if($db_rows['del_type'] == 'DD'){
		$del_pay = '-l';
	}

	$pgUrl	 = $_SERVER["HTTP_HOST"]."/product/view.php?prdcode=$db_rows['prdcode']&catcode=$db_rows['catcode']";

	$catcode01 = substr($db_rows['catcode'],0,2)."000000";
	$catcode02 = substr($db_rows['catcode'],0,4)."0000";
	$catcode03 = substr($db_rows['catcode'],0,6)."00";
	$catcode04 = substr($db_rows['catcode'],0,8);

	$prd_Qry_1 = "SELECT * FROM wiz_category WHERE catcode  = '$catcode01' and depthno=1";				//-- '1차카테고리명 불러오기'
	$prd_Res_1 = query($prd_Qry_1) or error("sql error");
	$prd_Row_1 = sql_fetch_obj($prd_Res_1);

	$prd_Qry_2 = "SELECT catname FROM wiz_category WHERE catcode  = '$catcode02' and depthno=2";		//-- '2차카테고리명 불러오기'
	$prd_Res_2 = query($prd_Qry_2) or error("sql error");
	$prd_Row_2 = sql_fetch_obj($prd_Res_2);

	$prd_Qry_3 = "SELECT catname FROM wiz_category WHERE catcode like '$catcode03%' and depthno=3";		//-- '3차카테고리명 불러오기'
	$prd_Res_3 = query($prd_Qry_3) or error("sql error");
	$prd_Row_3 = sql_fetch_obj($prd_Res_3);

	$prd_Qry_5 = "SELECT catname FROM wiz_category WHERE catcode like '$catcode04%' and depthno=4";		//-- '4차카테고리명 불러오기'
	$prd_Res_5 = query($prd_Qry_5) or error("sql error");
	$prd_Row_5 = sql_fetch_obj($prd_Res_5);

	$prd_Qry_4 = "SELECT brdname FROM wiz_brand WHERE idx='$db_rows['brand']'";		//-- '브랜드 불러오기'
	$prd_Res_4 = query($prd_Qry_4) or error("sql error");
	$prd_Row_4 = sql_fetch_obj($prd_Res_4);

	$prdname = strip_tags($db_rows['prdname']);
	$prdUrl  = "http://".$pgUrl;
	$imgUrl  = "http://".$imgpath_M;
	$cate_1  = $prd_Row_1->catname;
	$cate_2  = $prd_Row_2->catname;
	$cate_3  = $prd_Row_3->catname;
	$cate_4  = $prd_Row_5->catname;
	$maker   = $db_rows['prdcom'];
	$origin  = $db_rows['origin'];
	$brand   = $prd_Row_4->brdname;
	$reserve = $db_rows['reserve'];


	echo "\n{$db_rows['prdcode']}{$tab}{$prdname}{$tab}{$db_rows['sellprice']}{$tab}{$prdUrl}{$tab}{$imgUrl}{$tab}{$cate_1}{$tab}{$cate_2}{$tab}{$cate_3}{$tab}{$cate_4}{$tab}{$brand}{$tab}{$maker}{$tab}{$origin}{$tab}{$reserve}{$tab}{$del_pay}";

}

$content = ob_get_contents();
ob_end_clean();

echo $content;

?>