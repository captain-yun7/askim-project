<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

// 재고량 체크
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
							error("주문수량이 재고량(".$stock."개)보다 많습니다.");
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
						error("주문수량이 재고량(".$stock."개)보다 많습니다.");
					}
				}
				$no++;
			}

		}

	} else {

		if(!strcmp($prd_row->shortage, "S")) {
			if($amount > $prd_row->stock){

				if($direct == "checkout") { echo "<script>alert('주문수량이 재고량(".$prd_row->stock."개)보다 많습니다.');self.close();</script>"; }
				else { error("주문수량이 재고량(".$prd_row->stock."개)보다 많습니다."); }
			}
		} else if(!strcmp($prd_row->shortage, "Y")) {

			if($direct == "checkout") { echo "<script>alert('품절된 상품입니다.');self.close();</script>"; }
			else { error("품절된 상품입니다."); }

		}

	}

}


// 상품장바구니에 저장
if($mode == "insert"){
	/* -- ----------------------------------------------------- -- *\
	 * view페이지에서 넘어올때
	\* -- ----------------------------------------------------- -- */
	if(empty($idx) && empty($selprd)) {

		$optlist = explode("^",$optcode);
		$optcode = $optlist[0];

		$optlist = explode("^",$optcode2);
		$optcode2 = $optlist[0];

		$optlist = explode("^",$optcode3);
		$optcode3 = $optlist[0];

		$optlist = explode("^",$optcode4);
		$optcode4 = $optlist[0];

		$optcode5 = $optcode5;
		$optcode6 = $optcode6;
		$optcode7 = $optcode7;

		$optlist = explode("^",$optcode8);
		$optcode8 = $optlist[0];

		$optlist = explode("^",$optcode9);
		$optcode9 = $optlist[0];

		$optlist = explode("^",$optcode10);
		$optcode10 = $optlist[0];

		$optlist = explode("^",$optcode11);
		$optcode11 = $optlist[0];

		// 같은상품에 같은 옵션을 선택했는지
		$bsql = "
			select * 
			  from wiz_basket_tmp 
			 where uniq_id = '".$_uniq_id."'
			   and prdcode = '".$prdcode."'
			   and direct = '".$direct."'
		";
		$bresult = query($bsql);
		while($result = sql_fetch_arr($bresult)){
			$o_amount = $result['amount'];
			$basket_exist=false;
			if($result['prdcode'] == $prdcode && $result['optcode'] == $optcode && $result['optcode2'] == $optcode2 &&
			   $result['optcode3'] == $optcode3 && $result['optcode4'] == $optcode4 && $result['optcode5'] == $optcode5 &&
			   $result['optcode6'] == $optcode6 && $result['optcode7'] == $optcode7 && $result['optcode8'] == $optcode8 &&
			   $result['optcode9'] == $optcode9 && $result['optcode10'] == $optcode10 && $result['optcode11'] == $optcode11) {
					$result['amount'] = $amount;
					$basket_exist = true;
					$basket_idx = $result['idx'];
					break;
			}
		}

		// 재고 체크
		checkAmount($prdcode, $amount, $optcode);

		// 적립금 사용여부
		if($oper_info['reserve_use'] != "Y") $prd_row->reserve = 0;

		// 중복된 상품에 옵션이 없다면 신규생성
		if(!$basket_exist && $direct == "basket") {

			$sellprice = (int)$tmp_sellprice + (int)$opt_price1 + (int)$opt_price2 + (int)$opt_price3 + (int)$opt_price8 + (int)$opt_price9 + (int)$opt_price10 + (int)$opt_price11;
			$reserve   = (int)$tmp_reserve + (int)$opt_reserve1 + (int)$opt_reserve2 + (int)$opt_reserve3 + (int)$opt_reserve8 + (int)$opt_reserve9 + (int)$opt_reserve10 + (int)$opt_reserve11;

			$basket_id = $_uniq_id;

			if($wiz_session['id']) $memid = $wiz_session['id'];
			else                   $memid = "비회원";

			$sql_com = "";
			$sql_com .= " uniq_id           = '".$_uniq_id."'	                  ";
			$sql_com .= " , memid	        = '".$memid."'	                      ";
			$sql_com .= " , prdcode         = '".$prdcode."'	                  ";
			$sql_com .= " , prdname         = '".$prd_row->prdname."'	          ";
			$sql_com .= " , prdimg          = '".$prd_row->prdimg."'	          ";
			$sql_com .= " , prdprice        = '".$sellprice."'	                  ";
			$sql_com .= " , prdreserve      = '".$reserve."'	                  ";
			$sql_com .= " , opttitle        = '".$opttitle."'	                  ";
			$sql_com .= " , optcode         = '".$optcode."'	                  ";
			$sql_com .= " , opttitle2       = '".$opttitle2."'	                  ";
			$sql_com .= " , optcode2        = '".$optcode2."'	                  ";
			$sql_com .= " , opttitle3       = '".$opttitle3."'	                  ";
			$sql_com .= " , optcode3        = '".$optcode3."'	                  ";
			$sql_com .= " , opttitle4       = '".$opttitle4."'	                  ";
			$sql_com .= " , optcode4        = '".$optcode4."'	                  ";
			$sql_com .= " , opttitle5       = '".$opttitle5."'	                  ";
			$sql_com .= " , optcode5        = '".$optcode5."'	                  ";
			$sql_com .= " , opttitle6       = '".$opttitle6."'	                  ";
			$sql_com .= " , optcode6        = '".$optcode6."'	                  ";
			$sql_com .= " , opttitle7       = '".$opttitle7."'	                  ";
			$sql_com .= " , optcode7        = '".$optcode7."'	                  ";
			$sql_com .= " , opttitle8       = '".$opttitle8."'	                  ";
			$sql_com .= " , optcode8        = '".$optcode8."'	                  ";
			$sql_com .= " , opttitle9       = '".$opttitle9."'	                  ";
			$sql_com .= " , optcode9        = '".$optcode9."'	                  ";
			$sql_com .= " , opttitle10      = '".$opttitle10."'	                  ";
			$sql_com .= " , optcode10       = '".$optcode10."'	                  ";
			$sql_com .= " , opttitle11      = '".$opttitle11."'	                  ";
			$sql_com .= " , optcode11       = '".$optcode11."'	                  ";
			$sql_com .= " , amount          = '".$amount."'	                      ";
			$sql_com .= " , wdate           = now()                               ";
			$sql_com .= " , direct          = '".$direct."'	                      ";

			$insert_sql = "insert into wiz_basket_tmp set {$sql_com} ";
			query($insert_sql);

			$basket_idx = mysqli_insert_id($connect);
			$_SESSION['basketidx'] = $basket_idx;

			// 장바구니수 증가
			$sql = "
				update wiz_product 
				   set basketcnt = if(basketcnt is null, 1, basketcnt + 1)
				 where prdcode='".$prdcode."'
			";
			query($sql);

		} else {

			if($direct == "buy" || $direct == "checkout") {

				$sellprice = (int)$tmp_sellprice + (int)$opt_price1 + (int)$opt_price2 + (int)$opt_price3 + (int)$opt_price8 + (int)$opt_price9 + (int)$opt_price10 + (int)$opt_price11;
				$reserve   = (int)$tmp_reserve + (int)$opt_reserve1 + (int)$opt_reserve2 + (int)$opt_reserve3 + (int)$opt_reserve8 + (int)$opt_reserve9 + (int)$opt_reserve10 + (int)$opt_reserve11;

				$basket_id = $_uniq_id;

				if($wiz_session['id']) $memid = $wiz_session['id'];
				else                   $memid = "비회원";

				$sql_com = "";
				$sql_com .= " uniq_id           = '".$_uniq_id."'	                  ";
				$sql_com .= " , memid	        = '".$memid."'	                      ";
				$sql_com .= " , prdcode         = '".$prdcode."'	                  ";
				$sql_com .= " , prdname         = '".$prd_row->prdname."'	          ";
				$sql_com .= " , prdimg          = '".$prd_row->prdimg."'	          ";
				$sql_com .= " , prdprice        = '".$sellprice."'	                  ";
				$sql_com .= " , prdreserve      = '".$reserve."'	                  ";
				$sql_com .= " , opttitle        = '".$opttitle."'	                  ";
				$sql_com .= " , optcode         = '".$optcode."'	                  ";
				$sql_com .= " , opttitle2       = '".$opttitle2."'	                  ";
				$sql_com .= " , optcode2        = '".$optcode2."'	                  ";
				$sql_com .= " , opttitle3       = '".$opttitle3."'	                  ";
				$sql_com .= " , optcode3        = '".$optcode3."'	                  ";
				$sql_com .= " , opttitle4       = '".$opttitle4."'	                  ";
				$sql_com .= " , optcode4        = '".$optcode4."'	                  ";
				$sql_com .= " , opttitle5       = '".$opttitle5."'	                  ";
				$sql_com .= " , optcode5        = '".$optcode5."'	                  ";
				$sql_com .= " , opttitle6       = '".$opttitle6."'	                  ";
				$sql_com .= " , optcode6        = '".$optcode6."'	                  ";
				$sql_com .= " , opttitle7       = '".$opttitle7."'	                  ";
				$sql_com .= " , optcode7        = '".$optcode7."'	                  ";
				$sql_com .= " , opttitle8       = '".$opttitle8."'	                  ";
				$sql_com .= " , optcode8        = '".$optcode8."'	                  ";
				$sql_com .= " , opttitle9       = '".$opttitle9."'	                  ";
				$sql_com .= " , optcode9        = '".$optcode9."'	                  ";
				$sql_com .= " , opttitle10      = '".$opttitle10."'	                  ";
				$sql_com .= " , optcode10       = '".$optcode10."'	                  ";
				$sql_com .= " , opttitle11      = '".$opttitle11."'	                  ";
				$sql_com .= " , optcode11       = '".$optcode11."'	                  ";
				$sql_com .= " , amount          = '".$amount."'	                      ";
				$sql_com .= " , wdate           = now()                               ";
				$sql_com .= " , direct          = '".$direct."'	                      ";

				$insert_sql = "insert into wiz_basket_tmp set {$sql_com} ";
				query($insert_sql);

				$basket_idx = mysqli_insert_id($connect);
				$_SESSION['basketidx'] = $basket_idx;

				// 장바구니수 증가
				$sql = "
					update wiz_product 
					   set basketcnt = if(basketcnt is null, 1, basketcnt + 1 )
					 where prdcode='".$prdcode."'
				";
				query($sql);

			} else {
				/* 
					작업자		: 김나연
					작업일시	: 2020-12-10
					작업내용	: 장바구니에 있는 상품을 추가시 재고수량 재체크, 기존에 장바구니에 추가한 수량+추가수량이 재고수량 이상일 경우 추가되지 않음
				*/
				checkAmount($prdcode, $o_amount+$amount, $optcode);

				$update_sql = "
					update wiz_basket_tmp 
					   set amount=amount+'$amount' 
					 where idx='".$basket_idx."'
				";
				query($update_sql);

			}

		}

	/* -- ----------------------------------------------------- -- *\
	 * 위시리스트에서 선택후 장바구니 담길때
	\* -- ----------------------------------------------------- -- */
	} else {

		if(!empty($idx)) {
			$selprd = $idx;
		}

		$tmp_prd = explode("|", $selprd);
		foreach($tmp_prd as $pkey => $pvalue){
			if(!empty($pvalue)) $tmpq .= " OR idx='$pvalue'";
		}
		$tmpq = substr($tmpq,3);

		$sql = "select * from wiz_wishlist where memid = '".$wiz_session['id']."' and ".$tmpq;
		$results = query($sql);
		while($row = sql_fetch_arr($results)){

			$prdcode      = $row['prdcode'];

			$opttitle     = $row['opttitle'];
			$optcode      = $row['optcode'];
			
			$opttitle2    = $row['opttitle2'];
			$optcode2     = $row['optcode2'];
			
			$opttitle3    = $row['opttitle3'];
			$optcode3     = $row['optcode3'];
			
			$opttitle4    = $row['opttitle4'];
			$optcode4     = $row['optcode4'];
			
			$opttitle5    = $row['opttitle5'];
			$optcode5     = $row['optcode5'];
			
			$opttitle6    = $row['opttitle6'];
			$optcode6     = $row['optcode6'];
			
			$opttitle7    = $row['opttitle7'];
			$optcode7     = $row['optcode7'];
			
			$opttitle8    = $row['opttitle8'];
			$optcode8     = $row['optcode8'];
			
			$opttitle9    = $row['opttitle9'];
			$optcode9     = $row['optcode9'];
			
			$opttitle10   = $row['opttitle10'];
			$optcode10    = $row['optcode10'];
			
			$opttitle11   = $row['opttitle11'];
			$optcode11    = $row['optcode11'];

			$opttitle12   = $row['opttitle12'];
			$optcode12    = $row['optcode12'];

			$opttitle13   = $row['opttitle13'];
			$optcode13    = $row['optcode13'];

			$amount       = $row['amount'];
			$memid        = $row['memid'];
			$price        = $row['price'];

			// 같은상품에 같은 옵션을 선택했는지
    		$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."'";
    		$bresult = query($bsql) or error("sql error");
    		while($result = sql_fetch_arr($bresult)){
				$basket_exist=false;
				if($result['prdcode'] == $prdcode && $result['optcode'] == $optcode && $result['optcode2'] == $optcode2 &&
				   $result['optcode3'] == $optcode3 && $result['optcode4'] == $optcode4 && $result['optcode5'] == $optcode5 &&
				   $result['optcode6'] == $optcode6 && $result['optcode7'] == $optcode7 && $result['optcode8'] == $optcode8 &&
				   $result['optcode9'] == $optcode9 && $result['optcode10'] == $optcode10 && $result['optcode11'] == $optcode11 &&
				   $result['optcode12'] == $optcode12 && $result['optcode13'] == $optcode13) {

					$result['amount'] = $amount;
					$basket_exist = true;
					$basket_idx = $result['idx'];

    				break;

    			}
    		}
			// 재고 체크
			checkAmount($prdcode, $amount, $optcode);

			// 적립금 사용여부
			if($oper_info['reserve_use'] != "Y") $prd_row->reserve = 0;
			else $prd_row->reserve = $prd_row->reserve * $amount;

    		// 중복된 상품에 옵션이 없다면 신규생성
    		if(!$basket_exist){
				$sql_com = "";
				$sql_com .= " uniq_id           = '".$_uniq_id."'	                  ";
				$sql_com .= " , memid           = '".$memid."'	                      ";
				$sql_com .= " , prdcode         = '".$prdcode."'	                  ";
				$sql_com .= " , prdname         = '".$prd_row->prdname."'	          ";
				$sql_com .= " , prdimg          = '".$prd_row->prdimg."'	          ";
				$sql_com .= " , prdprice        = '".$price."'	                      ";
				$sql_com .= " , prdreserve      = '".$prd_row->reserve."'	          ";
				$sql_com .= " , opttitle        = '".$opttitle."'	                  ";
				$sql_com .= " , optcode         = '".$optcode."'	                  ";
				$sql_com .= " , opttitle2       = '".$opttitle2."'	                  ";
				$sql_com .= " , optcode2        = '".$optcode2."'	                  ";
				$sql_com .= " , opttitle3       = '".$opttitle3."'	                  ";
				$sql_com .= " , optcode3        = '".$optcode3."'	                  ";
				$sql_com .= " , opttitle4       = '".$opttitle4."'	                  ";
				$sql_com .= " , optcode4        = '".$optcode4."'	                  ";
				$sql_com .= " , opttitle5       = '".$opttitle5."'	                  ";
				$sql_com .= " , optcode5        = '".$optcode5."'	                  ";
				$sql_com .= " , opttitle6       = '".$opttitle6."'	                  ";
				$sql_com .= " , optcode6        = '".$optcode6."'	                  ";
				$sql_com .= " , opttitle7       = '".$opttitle7."'	                  ";
				$sql_com .= " , optcode7        = '".$optcode7."'	                  ";
				$sql_com .= " , opttitle8       = '".$opttitle8."'	                  ";
				$sql_com .= " , optcode8        = '".$optcode8."'	                  ";
				$sql_com .= " , opttitle9       = '".$opttitle9."'	                  ";
				$sql_com .= " , optcode9        = '".$optcode9."'	                  ";
				$sql_com .= " , opttitle10      = '".$opttitle10."'	                  ";
				$sql_com .= " , optcode10       = '".$optcode10."'	                  ";
				$sql_com .= " , opttitle11      = '".$opttitle11."'	                  ";
				$sql_com .= " , optcode11       = '".$optcode11."'	                  ";
				$sql_com .= " , opttitle12      = '".$opttitle12."'	                  ";
				$sql_com .= " , optcode12       = '".$optcode12."'	                  ";
				$sql_com .= " , opttitle13      = '".$opttitle13."'	                  ";
				$sql_com .= " , optcode13       = '".$optcode13."'	                  ";
				$sql_com .= " , amount          = '".$amount."'	                      ";
				$sql_com .= " , wdate           = now()	                              ";
				$sql_com .= " , direct          = 'basket'	                          ";

				$insert_sql = "insert into wiz_basket_tmp set {$sql_com} ";
				query($insert_sql);

				$basket_idx = mysqli_insert_id($connect);

 				// 장바구니수 증가
 				$sql = "
					update wiz_product 
					   set basketcnt = if(basketcnt is null, 1, basketcnt + 1 )
					 where prdcode='".$prdcode."'
				";
 				query($sql);

			}else{
				/* 
					작업자		: 김나연
					작업일시	: 2020-12-10
					작업내용	: 장바구니에 있는 상품을 추가시 재고수량 재체크, 기존에 장바구니에 추가한 수량+추가수량이 재고수량 이상일 경우 추가되지 않음
				*/
				checkAmount($prdcode, $o_amount+$amount, $optcode);

				$update_sql = "
					update wiz_basket_tmp 
					   set amount=amount+'$amount' 
					 where idx='".$basket_idx."'
				";
				query($update_sql);
			}

		}
	}

	if($direct == "buy" || $direct == "checkout"){
		//$product_idx = $basket_idx."|";
		//$product_idx = (substr($product_idx, -1) == '|') ? substr_replace($product_idx, '', -1) : $product_idx;
		//$get_param = "?product_idx=$product_idx";
		$product_idx = $basket_idx;
		$get_param = "?product_idx=$product_idx";

	} else {
		$get_param = "";
	}

	if(mobile_check() == true) {
		if($direct == "basket" || empty($direct)) {
			$go_url = $mobile_path."/sub/cart.php";
			header("Location: /".$go_url);
		} else if($direct == "buy") {
			$go_url = $mobile_path."/sub/order_form.php".$get_param;
			header("Location: /".$go_url);
		} else if($direct == "checkout") {
			//$go_url = $mobile_path."/nhn/?prdcode=$prdcode&basket_idx=$basket_idx";
			$go_url = "twcenter/product/nhn_order.php?prdcode=$prdcode&basket_idx=$basket_idx";
			header("Location: /".$go_url);
		}
	} else {
		if($direct == "basket" || empty($direct)) {
			$go_url = $prd_info['basket_url'];
			header("Location: /".$go_url);
		} else if($direct == "buy") {
			$go_url = $prd_info['order_url'].$get_param;
			header("Location: /".$go_url);
		} else if($direct == "checkout") {
			$go_url = "twcenter/product/nhn_order.php?prdcode=$prdcode&basket_idx=$basket_idx";
			header("Location: /".$go_url);
		}
	}

// 장바구니 수정
}else if($mode == "update"){

	$idx    = $_POST['idx'];
	$amount = $_POST['amount'];
	$bkinfo= sql_fetch("select * from wiz_basket_tmp where uniq_id='".$_uniq_id."' and idx='".$idx."'");

	// 재고 체크
	$optcode = explode("^", $bkinfo['optcode']);
	checkAmount($bkinfo['prdcode'], $amount, $optcode[0]);
//	checkAmount($bkinfo['prdcode'], $amount, $bkinfo['optcode']);

	@query("update wiz_basket_tmp set amount = '$amount' where uniq_id='".$_uniq_id."' and idx='".$idx."'");

	//setcookie('member_guest','guest',time()+60*60*24,'/');

	if(mobile_check() == true) {
		if($ptype == "form" && strpos($pagetype,'order') !== false){
			$go_url = $mobile_path."/sub/order_form.php?product_idx=$product_idx";
			if($order_guest) $go_url .= "&order_guest=$order_guest";
		} else {
			$go_url = $mobile_path."/sub/cart.php";
		}
	} else {
		if($ptype == "form" && strpos($pagetype,'order')){
			$go_url = $prd_info['order_url']."?product_idx=$product_idx";
		} else {
			$go_url = $prd_info['basket_url'];
		}
	}

	header("Location: /".$go_url);

// 장바구니 삭제
}else if($mode == "delete"){

	$idx      = $_GET['idx'];
	$selected = $_GET['selected'];

	// 개별삭제
	if($idx != "") {
		@query("delete from wiz_basket_tmp where uniq_id='".$_uniq_id."' and idx='".$idx."'");
	}

	// 선택삭제
	if($selected != "") {

		$array_selected = explode("|",$selected);
		$i=0;
		while($array_selected[$i]){

			$tmp_idx = $array_selected[$i];

			@query("delete from wiz_basket_tmp where uniq_id='".$_uniq_id."' and idx='".$tmp_idx."'");

			$i++;
		}

	}

	if(mobile_check() == true) {
		$sql_del_m = "select * from wiz_basket_tmp where uniq_id='".$_uniq_id."'";
		$result_del_m = query($sql_del_m);
		$num_del_m = sql_fetch_row($result_del_m);

		if($ppage == "order"){
			if($num_del_m>0){
				$go_url = $mobile_path."/sub/order_form.php";
			}else{
				$go_url = $mobile_path."/sub/cart.php";
			}
		}else{
			$go_url = $mobile_path."/sub/cart.php";
		}
	} else {
		if($ptype == "form" && strpos($pagetype,'order')){
			$go_url = $prd_info['order_url'];
		} else {
			$go_url = $prd_info['basket_url'];
		}
	}

	header("Location: /".$go_url);


// 장바구니 전체삭제
}else if($mode == "delall"){
	@query("delete from wiz_basket_tmp where uniq_id='".$_uniq_id."'");

	if(mobile_check() == true) {
		$go_url = $mobile_path."/sub/cart.php";
	} else {
		$go_url = $prd_info['basket_url'];
	}

	header("Location: /".$go_url);

// 관심상품 추가
}else if($mode == "my_wish"){

	if(empty($wiz_session['id'])) {
		error("로그인 후 이용해주세요.");
		exit;
	}

	if(!empty($prdcode)) {

		$sql = "select * from wiz_product where prdcode='".$prdcode."' ";
		$row = sql_fetch($sql);

		$prdcode     = $row['prdcode'];
		$prdprice    = $row['sellprice'];
		$prdreserve  = $row['reserve'];

		$optcode     = $row['optcode'];
		$opttitle    = $row['opttitle'];
		$optcode2    = $row['optcode2'];
		$opttitle2   = $row['opttitle2'];
		$optcode3    = $row['optcode3'];
		$opttitle3   = $row['opttitle3'];
		$optcode4    = $row['optcode4'];
		$opttitle4   = $row['opttitle4'];
		$optcode5    = $row['optcode5'];
		$opttitle5   = $row['opttitle5'];
		$optcode6    = $row['optcode6'];
		$opttitle6   = $row['opttitle6'];
		$optcode7    = $row['optcode7'];
		$opttitle7   = $row['opttitle7'];

		$optcode8    = $row['optcode8'];
		$opttitle8   = $row['opttitle8'];
		$optcode9    = $row['optcode9'];
		$opttitle9   = $row['opttitle9'];
		$optcode10   = $row['optcode10'];
		$opttitle10  = $row['opttitle10'];
		$optcode11   = $row['optcode11'];
		$opttitle11  = $row['opttitle11'];

	} else if(!empty($idx)) {

		$sql = "select * from wiz_basket_tmp where idx='".$idx."' ";
		$row = sql_fetch($sql);

		$prdcode     = $row['prdcode'];
		$prdprice    = $row['prdprice'];
		$prdreserve  = $row['prdreserve'];

		$optcode     = $row['optcode'];
		$opttitle    = $row['opttitle'];
		$optcode2    = $row['optcode2'];
		$opttitle2   = $row['opttitle2'];
		$optcode3    = $row['optcode3'];
		$opttitle3   = $row['opttitle3'];
		$optcode4    = $row['optcode4'];
		$opttitle4   = $row['opttitle4'];
		$optcode5    = $row['optcode5'];
		$opttitle5   = $row['opttitle5'];
		$optcode6    = $row['optcode6'];
		$opttitle6   = $row['opttitle6'];
		$optcode7    = $row['optcode7'];
		$opttitle7   = $row['opttitle7'];

		$optcode8    = $row['optcode8'];
		$opttitle8   = $row['opttitle8'];
		$optcode9    = $row['optcode9'];
		$opttitle9   = $row['opttitle9'];
		$optcode10   = $row['optcode10'];
		$opttitle10  = $row['opttitle10'];
		$optcode11   = $row['optcode11'];
		$opttitle11  = $row['opttitle11'];
		$amount      = $row['amount'];

	}

	$sql = "select * from wiz_wishlist where memid = '".$wiz_session['id']."' and prdcode = '$prdcode'";
	$total = sql_fetch_rows($sql);

	if($total > 0 ) error("이미 등록한 관심상품 입니다.");

	$sql_com = "";
	$sql_com .= " memid             = '".$wiz_session['id']."'            ";
	$sql_com .= " , prdcode         = '".$prdcode."'	                  ";
	$sql_com .= " , opttitle        = '".$opttitle."'	                  ";
	$sql_com .= " , optcode         = '".$optcode."'	                  ";
	$sql_com .= " , opttitle2       = '".$opttitle2."'	                  ";
	$sql_com .= " , optcode2        = '".$optcode2."'	                  ";
	$sql_com .= " , opttitle3       = '".$opttitle3."'	                  ";
	$sql_com .= " , optcode3        = '".$optcode3."'	                  ";
	$sql_com .= " , opttitle4       = '".$opttitle4."'	                  ";
	$sql_com .= " , optcode4        = '".$optcode4."'	                  ";
	$sql_com .= " , opttitle5       = '".$opttitle5."'	                  ";
	$sql_com .= " , optcode5        = '".$optcode5."'	                  ";
	$sql_com .= " , opttitle6       = '".$opttitle6."'	                  ";
	$sql_com .= " , optcode6        = '".$optcode6."'	                  ";
	$sql_com .= " , opttitle7       = '".$opttitle7."'	                  ";
	$sql_com .= " , optcode7        = '".$optcode7."'	                  ";
	$sql_com .= " , amount          = '".$amount."'	                      ";
	$sql_com .= " , wdate           = now()	                              ";
	$sql_com .= " , opttitle8       = '".$opttitle8."'	                  ";
	$sql_com .= " , optcode8        = '".$optcode8."'	                  ";
	$sql_com .= " , opttitle9       = '".$opttitle9."'	                  ";
	$sql_com .= " , optcode9        = '".$optcode9."'	                  ";
	$sql_com .= " , opttitle10      = '".$opttitle10."'	                  ";
	$sql_com .= " , optcode10       = '".$optcode10."'	                  ";
	$sql_com .= " , opttitle11      = '".$opttitle11."'	                  ";
	$sql_com .= " , optcode11       = '".$optcode11."'	                  ";
	$sql_com .= " , price           = '".$prdprice."'	                  ";
	$sql_com .= " , reserve         = '".$prdreserve."'	                  ";

	$sql = "insert into wiz_wishlist set {$sql_com} ";
	query($sql);

	alert("관심상품에 추가하였습니다.", "");

// 관심상품 삭제
}else if($mode == "my_wishdel"){

	if(!empty($selprd)) {

		$tmp_prd = explode("|", $selprd);

		for($ii = 0; $ii < count($tmp_prd); $ii++) {
			$idx = $tmp_prd[$ii];
			$sql = "delete from wiz_wishlist where memid = '".$wiz_session['id']."' and idx = '".$idx."'";
			query($sql);
		}

	} else {

		$sql = "delete from wiz_wishlist where memid = '".$wiz_session['id']."' and idx = '".$idx."'";
		query($sql);

	}

	alert("관심상품에 삭제되었습니다.", $prev);

}else if($mode == "delete_form"){
	$idx = $_REQUEST['idx'];
	$product_idx = $_REQUEST['product_idx'];

	$sql_del = "delete from wiz_basket_tmp where idx='".$idx."'";
	query($sql_del);


	if(mobile_check() == true) {
		$go_url = $mobile_path."/sub/order_form.php";
	} else {
		$product_idx_new = str_replace($idx."|","",$product_idx);
		if($product_idx_new==""){
			$go_url = $prd_info['basket_url'];
		}else{
			$go_url = $prd_info['order_url']."?ptype=form&product_idx=".$product_idx_new;
		}
	}

	echo "<script>alert('삭제되었습니다.');location.href='/".$go_url."'</script>";
}
?>