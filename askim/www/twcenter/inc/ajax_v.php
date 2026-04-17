<?
include "../common.php";
include "../inc/oper_info.php";

function checkAmount($prdcode, $amount, $optcode, $optcode3, $optcode4, $optcode5, $optcode6, $optcode7, $optcode8, $optcode9, $optcode10, $optcode11){

	global $prd_info;

	global $optcode3;
	global $optcode4;
	global $optcode5;
	global $optcode6;
	global $optcode7;
	global $optcode8;
	global $optcode9;
	global $optcode10;
	global $optcode11;

	$sql = "
	
		select 
		
			prdname
			,prdimg_R as prdimg
			,opttitle
			,optcode
			,opttitle2
			,optcode2
			,opttitle3
			,optcode3
			,opttitle4
			,optcode4
			,opttitle5
			,optcode5
			,opttitle6
			,optcode6
			,opttitle7
			,optcode7
			,opttitle8
			,optcode8
			,opttitle9
			,optcode9
			,opttitle10
			,optcode10
			,opttitle11
			,optcode11
			,optvalue
			,stock
			,sellprice
			,reserve
			,shortage
			,opt_use
			
		from
		
			wiz_product
			
		where
		
			prdcode = '$prdcode'
			
	";
	$result = query($sql) or error("sql error");
	$prd_info = sql_fetch_obj($result);
   
	if(!empty($prd_info->optcode3)) {

		$opt3_arr = explode("^^", $prd_info->optcode3);
		for($ii = 0; $ii < count($opt3_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt3_arr[$ii]);
			if(!strcmp($opt, $optcode3)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}
	if(!empty($prd_info->optcode4)) {

		$opt4_arr = explode("^^", $prd_info->optcode4);
		for($ii = 0; $ii < count($opt4_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt4_arr[$ii]);
			if(!strcmp($opt, $optcode4)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}

	if(!empty($prd_info->optcode8)) {
		$opt8_arr = explode("^^", $prd_info->optcode8);
		for($ii = 0; $ii < count($opt8_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt8_arr[$ii]);
			if(!strcmp($opt, $optcode8)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}

	if(!empty($prd_info->optcode9)) {
		$opt9_arr = explode("^^", $prd_info->optcode9);
		for($ii = 0; $ii < count($opt9_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt9_arr[$ii]);
			if(!strcmp($opt, $optcode9)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}

	if(!empty($prd_info->optcode10)) {
		$opt10_arr = explode("^^", $prd_info->optcode10);
		for($ii = 0; $ii < count($opt10_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt10_arr[$ii]);
			if(!strcmp($opt, $optcode10)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}

	if(!empty($prd_info->optcode11)) {
		$opt11_arr = explode("^^", $prd_info->optcode11);
		for($ii = 0; $ii < count($opt11_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt11_arr[$ii]);
			if(!strcmp($opt, $optcode11)) {
				$prd_info->sellprice = $price;
				$prd_info->reserve   = $reserve;
			}
		}
	}


	if(!strcmp($prd_info->opt_use, "Y")){

		if($prd_info->optcode2 != ""){

			$opt1_arr = explode("^", $prd_info->optcode);
			$opt2_arr = explode("^", $prd_info->optcode2);
			$opt_tmp = explode("^^", $prd_info->optvalue);

			list($optcode1, $optcode2) = explode("/", $optcode);

			$no = 0;
			for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
				for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

					## String에 대한 공백제거
					$opt1_arr[$ii] = preg_replace("/\s+/", "", $opt1_arr[$ii]);
					$opt2_arr[$jj] = preg_replace("/\s+/", "", $opt2_arr[$jj]);

					if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
						$prd_info->sellprice = $prd_info->sellprice + $price;
						$prd_info->reserve   = $prd_info->reserve + $reserve;
						if($stock < $amount){
							error("주문수량이 재고량(".$stock."개)보다 많습니다.");
						}
					}

					$no++;
				}
			}

		} else {

			$opt1_arr = explode("^", $prd_info->optcode);
			$opt_tmp = explode("^^", $prd_info->optvalue);

			$no = 0;

			for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
				list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

				## String에 대한 공백제거
				$opt1_arr[$ii] = preg_replace("/\s+/", "", $opt1_arr[$ii]);
				if(!strcmp($optcode, $opt1_arr[$ii])) {
					$prd_info->sellprice = $prd_info->sellprice + $price;
					$prd_info->reserve = $prd_info->reserve + $reserve;
					if($stock < $amount){
						error("주문수량이 재고량(".$stock."개)보다 많습니다.");
					}
				}
				$no++;
			}

		}

	} else {

		if(!strcmp($prd_info->shortage, "S")) {
			if($amount > $prd_info->stock){

				if($direct == "checkout") { echo "<script>alert('주문수량이 재고량(".$prd_info->stock."개)보다 많습니다.');self.close();</script>"; }
				else { error("주문수량이 재고량(".$prd_info->stock."개)보다 많습니다."); }
			}
		} else if(!strcmp($prd_info->shortage, "Y")) {

			if($direct == "checkout") { echo "<script>alert('품절된 상품입니다.');self.close();</script>"; }
			else { error("품절된 상품입니다."); }

		}

	}

}

$optlist   = explode("^",$optcode);
$optcode   = $optlist[0];

$optlist   = explode("^",$optcode2);
$optcode2  = $optlist[0];

$optlist   = explode("^",$optcode3);
$optcode3  = $optlist[0];

$optlist   = explode("^",$optcode4);
$optcode4  = $optlist[0];

$optcode5 = $optcode5;
$optcode6 = $optcode6;
$optcode7 = $optcode7;

$optlist   = explode("^",$optcode8);
$optcode8  = $optlist[0];

$optlist   = explode("^",$optcode9);
$optcode9  = $optlist[0];

$optlist   = explode("^",$optcode10);
$optcode10 = $optlist[0];

$optlist   = explode("^",$optcode11);
$optcode11 = $optlist[0];

// 같은상품에 같은 옵션을 선택했는지
$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."'";
$bresult = query($bsql) or error("sql error");
while($brow = sql_fetch_arr($bresult)){
	if($brow['prdcode'] == $prdcode &&
		$brow['optcode'] == $optcode &&
		$brow['optcode2'] == $optcode2 &&
		$brow['optcode3'] == $optcode3 &&
		$brow['optcode4'] == $optcode4 &&
		$brow['optcode5'] == $optcode5 &&
		$brow['optcode6'] == $optcode6 &&
		$brow['optcode7'] == $optcode7 &&
		$brow['optcode8'] == $optcode8 &&
		$brow['optcode9'] == $optcode9 &&
		$brow['optcode10'] == $optcode10 &&
		$brow['optcode11'] == $optcode11){

		$brow['amount'] = $amount;
		$upidx        = $brow['idx'];
		$basket_exist = true;
		break;
	}
}

checkAmount($prdcode, $amount, $optcode, $optcode3, $optcode4, $optcode5, $optcode6, $optcode7, $optcode8, $optcode9, $optcode10, $optcode11);
if($oper_info['reserve_use'] != "Y") $prd_info->reserve = 0;

$product_idx = "";
if(!$basket_exist){

	$sellprice = $tmp_sellprice + $opt_price1 + $opt_price2 + $opt_price3 + $opt_price8 + $opt_price9 + $opt_price10+ $opt_price11;
	$reserve   = $tmp_reserve + $opt_reserve1 + $opt_reserve2 + $opt_reserve3 + $opt_reserve8 + $opt_reserve9 + $opt_reserve10 + $opt_reserve11;

	if($wiz_session['id']) $memid = $wiz_session['id'];
	else                 $memid = "비회원";

	if($prdcode!=""){

		$insert_sql = "INSERT INTO wiz_basket_tmp ";
		$insert_sql .= "( ";
		$insert_sql .= "idx,uniq_id,memid,prdcode,prdname,prdimg,prdprice,prdreserve, ";
		$insert_sql .= "opttitle,optcode,opttitle2,optcode2,";
		$insert_sql .= "opttitle3,optcode3,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7, ";
		$insert_sql .= "opttitle8,optcode8,opttitle9,optcode9,opttitle10,optcode10,opttitle11,optcode11,amount,wdate ";
		$insert_sql .= ") VALUES ( ";
		$insert_sql .= "'','".$_uniq_id."','$memid','$prdcode','$prd_info->prdname','$prd_info->prdimg','$sellprice','$reserve', ";
		$insert_sql .= "'$opttitle','$optcode','$opttitle2','$optcode2','$opttitle3','$optcode3','$opttitle4','$optcode4', ";
		$insert_sql .= "'$opttitle5','$optcode5','$opttitle6','$optcode6','$opttitle7','$optcode7','$opttitle8','$optcode8', ";
		$insert_sql .= "'$opttitle9','$optcode9','$opttitle10','$optcode10','$opttitle11','$optcode11','$amount', now())";
		//echo $insert_sql;
		query($insert_sql);

		// 장바구니수 증가
		$sql = "update wiz_product set basketcnt = basketcnt + 1 where prdcode='$prdcode'";
		query($sql);

	}

	if($direct == "buy"){
		$product_idx = "";
		$sel_qry = "select * from wiz_basket_tmp where uniq_id='".$_uniq_id."' and prdcode='$prdcode' and optsortnum='$sort_num'";
		$sel_result = query($sel_qry);
		while($sel_row = sql_fetch_obj($sel_result)){
			$product_idx .= $sel_row->idx."|";
		}
	} else {
		$product_idx = "";
		$sel_qry = "select * from wiz_basket_tmp where uniq_id='".$_uniq_id."'";
		$sel_result = query($sel_qry);
		while($sel_row = sql_fetch_obj($sel_result)){
			$product_idx .= $sel_row->idx."|";
		}
	}

}else{
	$update_sql = "update wiz_basket_tmp set amount=amount+'$amount' where idx='$upidx'";
	query($update_sql);

	$product_idx = $upidx."|";

}

echo $product_idx."^".$direct;

?>
