<?php
include "../common.php";
include "../inc/oper_info.php";
function checkAmount($prdcode, $amount, $optcode){

	global $prd_row, $optcode3, $optcode4, $optcode8, $optcode9, $optcode10, $optcode11;

	$sql = "
		SELECT prdname
			 , prdimg_R as prdimg
			 , opttitle
			 , optcode
			 , opttitle2
			 , optcode2
			 , opttitle3
			 , optcode3
			 , opttitle4
			 , optcode4
			 , opttitle8
			 , optcode8
			 , opttitle9
			 , optcode9
			 , opttitle10
			 , optcode10
			 , opttitle11
			 , optcode11
			 , optvalue
			 , stock
			 , sellprice
			 , reserve
			 , shortage
			 , opt_use
		  FROM wiz_product
		 WHERE prdcode = '$prdcode'
	";
	$prd_row = sql_fetch_object($sql);

	if(!empty($prd_row->optcode3)) {
		$opt3_arr = explode("^^", $prd_row->optcode3);
		for($ii = 0; $ii < count($opt3_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt3_arr[$ii]);
			if(!strcmp($opt, $optcode3)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!empty($prd_row->optcode4)) {
		$opt4_arr = explode("^^", $prd_row->optcode4);
		for($ii = 0; $ii < count($opt4_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt4_arr[$ii]);
			if(!strcmp($opt, $optcode4)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!empty($prd_row->optcode8)) {
		$opt8_arr = explode("^^", $prd_row->optcode8);
		for($ii = 0; $ii < count($opt8_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt8_arr[$ii]);
			if(!strcmp($opt, $optcode8)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!empty($prd_row->optcode9)) {
		$opt9_arr = explode("^^", $prd_row->optcode9);
		for($ii = 0; $ii < count($opt9_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt9_arr[$ii]);
			if(!strcmp($opt, $optcode9)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!empty($prd_row->optcode10)) {
		$opt10_arr = explode("^^", $prd_row->optcode10);
		for($ii = 0; $ii < count($opt10_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt10_arr[$ii]);
			if(!strcmp($opt, $optcode10)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!empty($prd_row->optcode11)) {
		$opt11_arr = explode("^^", $prd_row->optcode11);
		for($ii = 0; $ii < count($opt11_arr); $ii++) {
			list($opt, $price, $reserve) = explode("^", $opt11_arr[$ii]);
			if(!strcmp($opt, $optcode11)) {
				$prd_row->sellprice = $prd_row->sellprice + $price;
				$prd_row->reserve = $prd_row->reserve + $reserve;
			}
		}
	}

	if(!strcmp($prd_row->opt_use, "Y")){

		if($prd_row->optcode2 != ""){

			$opt1_arr = explode("^", $prd_row->optcode);
			$opt2_arr = explode("^", $prd_row->optcode2);
			$opt_tmp = explode("^^", $prd_row->optvalue);

			list($optcode1, $optcode2) = explode("/", $optcode);

			$no = 0;
			for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
				for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

					## String에 대한 공백제거
					$opt1_arr[$ii] = preg_replace("/\s+/", "", $opt1_arr[$ii]);
					$opt2_arr[$jj] = preg_replace("/\s+/", "", $opt2_arr[$jj]);

					if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
						$prd_row->sellprice = $prd_row->sellprice + $price;
						$prd_row->reserve   = $prd_row->reserve + $reserve;
						if($stock < $amount){
							//error("주문수량이 재고량(".$stock."개)보다 많습니다.");
							echo "error";
							exit;
						}
					}

					$no++;
				}
			}

		} else {

			$opt1_arr = explode("^", $prd_row->optcode);
			$opt_tmp = explode("^^", $prd_row->optvalue);

			$no = 0;

			for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
				list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

				## String에 대한 공백제거
				$opt1_arr[$ii] = preg_replace("/\s+/", "", $opt1_arr[$ii]);
				if(!strcmp($optcode, $opt1_arr[$ii])) {
					$prd_row->sellprice = $prd_row->sellprice + $price;
					$prd_row->reserve = $prd_row->reserve + $reserve;
					if($stock < $amount){
						//error("주문수량이 재고량(".$stock."개)보다 많습니다.");
						echo "error";
						exit;
					}
				}
				$no++;
			}

		}

	} else {

		if(!strcmp($prd_row->shortage, "S")) {
			if($amount > $prd_row->stock){

				if($direct == "checkout") { echo "<script>alert('주문수량이 재고량(".$prd_row->stock."개)보다 많습니다.');self.close();</script>"; }
				else { 
					echo "error";
					exit;
					//error("주문수량이 재고량(".$prd_row->stock."개)보다 많습니다."); 
				}
			}
		} else if(!strcmp($prd_row->shortage, "Y")) {

			if($direct == "checkout") { echo "<script>alert('품절된 상품입니다.');self.close();</script>"; }
			else {
				echo "error";
				exit;
				// error("품절된 상품입니다."); 
			}

		}

	}

}

$product_idx = array();
// 같은상품에 같은 옵션을 선택했는지

for($i=1; $i<=13; $i++) {
	$j = ($i == 1) ? "" : $i;
	$t_sql .= " and opttitle".$j." = '".${'opttitle'.$j.'_opt'}."' ";
	$q_sql .= " and optcode".$j." = '".${'optcode'.$j.'_opt'}."' ";
}

$bsql = "
	select * 
	  from wiz_basket_tmp 
	 where uniq_id = '".$_uniq_id."' 
	   $t_sql
	   $q_sql
	   and direct = '".$direct."'
	   and prdcode = '".$prdcode."'
";
$bresult = query($bsql);
$num_brow = sql_fetch_row($bresult);

$basket_exist = "";
if($num_brow != "0"){
	$brow = sql_fetch_arr($bresult);
	$upidx=$brow['idx'];
	$basket_exist = "Y";
}else{
	$basket_exist = "N";
}

if($basket_exist == "N" && $direct == "basket"){

	// 적립금 사용여부
	if($oper_info['reserve_use'] != "Y") $tmp_reserve_opt = 0;
	
	$sql_prdinfo = "select * from wiz_product where prdcode='".$prdcode."'";
	$result_prdinfo = query($sql_prdinfo);
	$row_prdinfo = sql_fetch_arr($result_prdinfo);

	if($wiz_session['id']) $memid = $wiz_session['id'];
	else                   $memid = "비회원";

	if($prdcode != ""){

		$opt_title_sql  = "";
		$opt_code_sql   = "";

		for($i=2; $i<=13; $i++) {
			$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i."_opt"}."'                      ";
			$opt_code_sql   .= " , optcode".$i."          = '".${'optcode'.$i."_opt"}."'                       ";
		}

		$sql_com .= " uniq_id            = '".$_uniq_id."'          	";
		$sql_com .= " , memid            = '".$memid."'          	        ";
		$sql_com .= " , prdcode          = '".$prdcode."'                   ";
		$sql_com .= " , prdname          = '".$row_prdinfo['prdname']."'      ";
		$sql_com .= " , prdimg           = '".$row_prdinfo['prdimg_R']."'     ";
		$sql_com .= " , prdprice         = '".$tmp_sellprice_opt."'         ";
		$sql_com .= " , prdreserve       = '".$tmp_reserve_opt."'          	";
		$sql_com .= " , opttitle         = '".$opttitle_opt."'          	";
		$sql_com .= " , optcode          = '".$optcode_opt."'               ";
		$sql_com .= " ".$opt_title_sql."                                  ";
		$sql_com .= " ".$opt_code_sql."                                   ";
		$sql_com .= " , amount           = '".$amount_opt."'                ";
		$sql_com .= " , optsortnum       = 'Y'                          ";
		$sql_com .= " , wdate            = now()                        ";
		$sql_com .= " , direct           = '".$direct."'                ";

		$insert_sql = "insert into wiz_basket_tmp set ".$sql_com." ";
		query($insert_sql);

		$basketidx = mysqli_insert_id($connect);
		$tmp_basket .= $basketidx."|";
		$_SESSION['basketidx'] = (substr($tmp_basket, -1) == '|') ? substr_replace($tmp_basket, '', -1) : $tmp_basket;

		// 장바구니수 증가
		$sql = "
			update wiz_product 
			   set basketcnt = basketcnt + 1 
			 where prdcode='".$prdcode."'
		";
		query($sql);

	}

	$sel_qry = "
		select * 
		  from wiz_basket_tmp 
		 where uniq_id = '".$_uniq_id."'
		   and direct = 'basket'
	";
	$sel_result = query($sel_qry);
	while($sel_row = sql_fetch_obj($sel_result)){
		$product_idx[] = $sel_row->idx;
	}

} else {		//장바구니에 있을때, 바로구매일 경우 바로구매, 장바구니일 경우 재고체크 후 주문수량추가

	if($direct == "buy" || $direct == "checkout") {			///바로구매

		// 적립금 사용여부
		if($oper_info['reserve_use'] != "Y") $tmp_reserve_opt = 0;
		
		$sql_prdinfo = "select * from wiz_product where prdcode='".$prdcode."'";
		$result_prdinfo = query($sql_prdinfo);
		$row_prdinfo = sql_fetch_arr($result_prdinfo);

		if($wiz_session['id']) $memid = $wiz_session['id'];
		else                   $memid = "비회원";

		if($prdcode != ""){

			$opt_title_sql  = "";
			$opt_code_sql   = "";

			for($i=2; $i<=13; $i++) {
				$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i."_opt"}."'                      ";
				$opt_code_sql   .= " , optcode".$i."          = '".${'optcode'.$i."_opt"}."'                       ";
			}

			$sql_com .= " uniq_id            = '".$_uniq_id."'          	";
			$sql_com .= " , memid            = '".$memid."'          	        ";
			$sql_com .= " , prdcode          = '".$prdcode."'                   ";
			$sql_com .= " , prdname          = '".$row_prdinfo['prdname']."'      ";
			$sql_com .= " , prdimg           = '".$row_prdinfo['prdimg_R']."'     ";
			$sql_com .= " , prdprice         = '".$tmp_sellprice_opt."'         ";
			$sql_com .= " , prdreserve       = '".$tmp_reserve_opt."'          	";
			$sql_com .= " , opttitle         = '".$opttitle_opt."'          	";
			$sql_com .= " , optcode          = '".$optcode_opt."'               ";
			$sql_com .= " ".$opt_title_sql."                                  ";
			$sql_com .= " ".$opt_code_sql."                                   ";
			$sql_com .= " , amount           = '".$amount_opt."'                ";
			$sql_com .= " , optsortnum       = 'Y'                          ";
			$sql_com .= " , wdate            = now()                        ";
			$sql_com .= " , direct           = '".$direct."'                ";

			$insert_sql = "insert into wiz_basket_tmp set ".$sql_com." ";
			query($insert_sql);

			$basketidx = mysqli_insert_id($connect);
			$tmp_basket .= $basketidx;
			/*
			작업자		: 이상민
			작업일시	: 2019-08-13
			작업내용	: 네이버페이 구매시 세션변수에 장바구니 idx 추가
			*/
			if($direct == "checkout" && $_SESSION["basketidx"] != ""){
				$_SESSION["basketidx"] = $_SESSION["basketidx"]."|".$tmp_basket;
			} else {
				$_SESSION['basketidx'] = $tmp_basket;
			}

			// 장바구니수 증가
			$sql = "
				update wiz_product 
				   set basketcnt = basketcnt + 1 
				 where prdcode='".$prdcode."'
			";
			query($sql);

		}

		$product_idx[] = $basketidx;

	} else {		// 재고체크 후 수량추가

		//재고체크
		$select_sql = "select * from wiz_basket_tmp where idx='".$upidx."'";
		$bkinfo = sql_fetch($select_sql);
		$optcode = explode("^", $bkinfo['optcode']);

		$total_amount = $bkinfo['amount']+$amount_opt;
		checkAmount($bkinfo['prdcode'], $bkinfo['amount']+$amount_opt, $optcode);

		$update_sql = "
			update wiz_basket_tmp 
			   set amount = amount + '".$amount_opt."' 
			 where idx = '".$upidx."'
		";
		query($update_sql);

		$product_idx[] = $upidx;

	}

}

$product_idx = implode("|", $product_idx);
echo $product_idx."^".$direct;

?>
