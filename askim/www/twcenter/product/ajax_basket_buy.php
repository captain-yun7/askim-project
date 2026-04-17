<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php"; ?>
<?php

if($mode == "insert"){

	// 재고량체크
	$amount = 1;

	$sql = "
	
		SELECT
		
			prdname                 ,
			prdimg_R as prdimg      ,
			stock                   ,
			sellprice               ,
			reserve                 ,
			shortage
			
		FROM
			
			wiz_product
			
		WHERE
		
			prdcode = '$prdcode'
			
	";
	$prd_row = sql_fetch_object($sql);

	if(!strcmp($prd_row->shortage, "S") && $amount > $prd_row->stock) {
		if($direct == "checkout") { $res_m = "1"; }
		else { $res_m = "2"; }
	} else if(!strcmp($prd_row->shortage, "Y")) {

		if($direct == "checkout") { $res_m = "3"; }
		else { $res_m = "4"; }

	} else {

		if(empty($idx) && empty($selprd)) {

			// 같은상품에 같은 옵션을 선택했는지
			$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."'";
			$bresult = query($bsql);
			while($result = sql_fetch_arr($bresult)){
				$basket_exist=false;
				if($result['prdcode'] == $prdcode){
						$result['amount'] = $amount;
						$basket_exist = true;
						$basket_idx = $result['idx'];
						break;
				}
			}

			// 적립금 사용여부
			if($oper_info['reserve_use'] != "Y") $prd_row->reserve = 0;

			// 중복된 상품에 옵션이 없다면 신규생성
			if(!$basket_exist){

				$sellprice = $prd_row->sellprice;
				$reserve   = $prd_row->reserve;

				$basket_id = $_uniq_id;

				if($wiz_session['id']) $memid = $wiz_session['id'];
				else                 $memid = "비회원";

				$insert_sql = "INSERT INTO wiz_basket_tmp ";
				$insert_sql .= "( ";
				$insert_sql .= "idx,uniq_id,memid,prdcode,prdname,prdimg,prdprice,prdreserve, ";
				$insert_sql .= "opttitle,optcode,opttitle2,optcode2,";
				$insert_sql .= "opttitle3,optcode3,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7, ";
				$insert_sql .= "opttitle8,optcode8,opttitle9,optcode9,opttitle10,optcode10,opttitle11,optcode11,amount,wdate ";
				$insert_sql .= ") VALUES ( ";
				$insert_sql .= "'','".$_uniq_id."','$memid','$prdcode','$prd_row->prdname','$prd_row->prdimg','$sellprice','$reserve', ";
				$insert_sql .= "'$opttitle','$optcode','$opttitle2','$optcode2','$opttitle3','$optcode3','$opttitle4','$optcode4', ";
				$insert_sql .= "'$opttitle5','$optcode5','$opttitle6','$optcode6','$opttitle7','$optcode7','$opttitle8','$optcode8', ";
				$insert_sql .= "'$opttitle9','$optcode9','$opttitle10','$optcode10','$opttitle11','$optcode11','$amount', now())";

				query($insert_sql);

				$basket_idx = mysqli_insert_id($connect);

				// 장바구니수 증가
				$sql = "update wiz_product set basketcnt = if(basketcnt is null, 1, basketcnt + 1) where prdcode='$prdcode'";
				query($sql);

			}

			$res_m = "ok";

		}

	}

}

echo $res_m;
?>