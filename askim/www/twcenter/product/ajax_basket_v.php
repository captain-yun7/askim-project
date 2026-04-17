<?php
include "../common.php";
include "../inc/oper_info.php";

$product_idx = array();

// 같은상품에 같은 옵션을 선택했는지
$optcode_split = explode("&&", $optcode_val);
$optcode_join  = "^".$optcode_real_value."^".$tmp_reserve."^".$amount[0]."&&";

$optcode_v   = "";
$optcode_v2  = $optcode2_val;
$optcode_v3  = "";
$optcode_v4  = "";
$optcode_v5  = "";
$optcode_v6  = "";
$optcode_v7  = "";
$optcode_v8  = "";
$optcode_v9  = "";
$optcode_v10 = "";
$optcode_v11 = "";

for($i=0; $i<count($optcode_split)-1; $i++) {
	$optcode_v   .= (isset($optcode)   && $optcode   && $optcode   != "undefined") ? $optcode_split[$i].$optcode_join   : "";
}
for($i=3; $i<=11; $i++) {

	if($tmp_sellprice == 0) {
		${'optcode'.$i.'_split'} = explode("||", ${'optcode'.$i.'_val'});
		for($j=0; $j<count(${'optcode'.$i.'_split'})-1; $j++) {
			${'optcode'.$i.'_join'} .= str_replace("/", "^", ${'optcode'.$i.'_split'}[$j])."^".${'amount'.$i}[0]."&&";
		}
	} else {
		${'optcode'.$i.'_split'} = explode("||", ${'optcode'.$i.'_val'});
		for($j=0; $j<count(${'optcode'.$i.'_split'})-1; $j++) {
			list(${'t'.$i}, ${'p'.$i}, ${'r'.$i}) = explode("/", ${'optcode'.$i.'_split'}[$j]);
			${'optcode'.$i.'_join'} .= ${'t'.$i}."^".$tmp_sellprice."^".${'r'.$i}."^".${'amount'.$i}[0]."&&";
		}

	}
}

$optcode_v3  = (isset($optcode3_val)  && $optcode3_val  && $optcode3_val  != "undefined") ? $optcode3_join  : "";
$optcode_v4  = (isset($optcode4_val)  && $optcode4_val  && $optcode4_val  != "undefined") ? $optcode4_join  : "";
$optcode_v5  = (isset($optcode5_val)  && $optcode5_val  && $optcode5_val  != "undefined") ? $optcode5_join  : "";
$optcode_v6  = (isset($optcode6_val)  && $optcode6_val  && $optcode6_val  != "undefined") ? $optcode6_join  : "";
$optcode_v7  = (isset($optcode7_val)  && $optcode7_val  && $optcode7_val  != "undefined") ? $optcode7_join  : "";
$optcode_v8  = (isset($optcode8_val)  && $optcode8_val  && $optcode8_val  != "undefined") ? $optcode8_join  : "";
$optcode_v9  = (isset($optcode9_val)  && $optcode9_val  && $optcode9_val  != "undefined") ? $optcode9_join  : "";
$optcode_v10 = (isset($optcode10_val) && $optcode10_val && $optcode10_val != "undefined") ? $optcode10_join : "";
$optcode_v11 = (isset($optcode11_val) && $optcode11_val && $optcode11_val != "undefined") ? $optcode11_join : "";

$bsql = "
	select * 
	  from wiz_basket_tmp 
	 where uniq_id='".$_uniq_id."' 
	   and opttitle = '".$opttitle."' 
	   and optcode = '".$optcode_v."'
	   and opttitle2 = '".$opttitle2."' 
	   and optcode2 = '".$optcode_v2."'
	   and opttitle3 = '".$opttitle3."' 
	   and optcode3 = '".$optcode_v3."'
	   and opttitle4 = '".$opttitle4."' 
	   and optcode4 = '".$optcode_v4."'
	   and opttitle5 = '".$opttitle5."' 
	   and optcode5 = '".$optcode_v5."'
	   and opttitle6 = '".$opttitle6."' 
	   and optcode6 = '".$optcode_v6."'
	   and opttitle7 = '".$opttitle7."' 
	   and optcode7 = '".$optcode_v7."'
	   and opttitle8 = '".$opttitle8."' 
	   and optcode8 = '".$optcode_v8."'
	   and opttitle9 = '".$opttitle9."' 
	   and optcode9 = '".$optcode_v9."'
	   and opttitle10 = '".$opttitle10."' 
	   and optcode10 = '".$optcode_v10."'
	   and opttitle11 = '".$opttitle11."' 
	   and optcode11 = '".$optcode_v11."'
	   and prdcode = '".$prdcode."'
	   and direct = '".$direct."'
";
$bresult = query($bsql);
$num_brow = sql_fetch_row($bresult);

$basket_exist = "";
if($num_brow != "0"){
	$brow = sql_fetch_arr($bresult);
	$upidx = $brow['idx'];
	$basket_exist = "Y";
}else{
	$basket_exist = "N";
}

if($basket_exist == "N" && $direct == "basket"){

	// 적립금 사용여부
	if($oper_info['reserve_use'] != "Y") $tmp_reserve = 0;
	
	$sql_prdinfo = "select * from wiz_product where prdcode='".$prdcode."'";
	$result_prdinfo = query($sql_prdinfo);
	$row_prdinfo = sql_fetch_arr($result_prdinfo);

	if($opttitle != ""){

		$tmp_optcode = explode("&&",$optcode_val);
		for($i=0; $i<count($tmp_optcode)-1; $i++){
			$exp = $tmp_optcode[$i];
			list($tmp_opttitle,$tmp_optprice_db,$tmp_optreserve_db) = explode("^",$exp);

			$tmp_optprice = ($row_prdinfo['sellprice']+$ajax_opt_price[$i])*$amount[$i];
			$tmp_optprice_ch = ($row_prdinfo['sellprice']+$ajax_opt_price[$i]);


		//	if($tmp_optreserve == 0) $tmp_optreserve = $row_prdinfo['reserve']*$amount[$i];
		//	else				     $tmp_optreserve = ($tmp_reserve + $ajax_opt_reserve2[$i])*$amount[$i];

			$tmp_optreserve = ($row_prdinfo['reserve']+$ajax_opt_reserve2[$i])*$amount[$i];

			$optcode_price   += $tmp_optprice;
			$optcode_reserve += $tmp_optreserve;

			$tot_optcode .= $tmp_opttitle."^".$tmp_optprice_ch."^".$tmp_optreserve."^".$amount[$i]."&&";

		}
	}

	if(!empty($opttitle2)){
		$tmp_optcode2 = explode("&&",$optcode2_val);
		for($i=0; $i<count($tmp_optcode2)-1; $i++){
			$exp = $tmp_optcode2[$i];
			list($tmp_opttitle2,$tmp_optprice2,$tmp_optreserve2) = explode("^",$exp);

			if($tmp_optprice2 == 0) $tmp_optprice2 = $row_prdinfo['sellprice']*$amount[$i];
			else				    $tmp_optprice2 = ($tmp_sellprice + $tmp_optprice2)*$amount[$i];

		//	if($tmp_optreserve2 == 0) $tmp_optreserve2 = $row_prdinfo['reserve']*$amount[$i];
		//	else				      $tmp_optreserve2 = ($tmp_reserve + $tmp_optreserve2)*$amount[$i];

			$tmp_optreserve2 = ($row_prdinfo['reserve']+$ajax_opt_reserve2[$i])*$amount[$i];

			$optcode2_price   += $tmp_optprice2;
			$optcode2_reserve += $tmp_optreserve2;
		}
	}

	//가격추가옵션
	if($opttitle3 != ""){
		$tmp_optcode3 = explode("&&",$optcode3_val);
		for($i=0; $i<count($tmp_optcode3)-1; $i++){
			$exp = $tmp_optcode3[$i];
			$tmp_opttitle3 = "";
			$tmp_optprice3 = "";
			$tmp_optreserve3 = "";
			list($tmp_opttitle3,$tmp_optprice3,$tmp_optreserve3) = explode("/",$exp);

			$tmp_optprice3 = ($ajax_opt_price3[$i])*$amount3[$i];
			$tmp_optprice3_noamount = ($ajax_opt_price3[$i]);

			if($tmp_optreserve3 == 0) $tmp_optreserve3 = $row_prdinfo['reserve']*$amount3[$i];
			else				      $tmp_optreserve3 = $tmp_optreserve3*$amount3[$i];

			$optcode3_price   += $tmp_optprice3;
			$optcode3_reserve += $tmp_optreserve3;

			$tot_optcode3 .= $tmp_opttitle3."^".$tmp_optprice3_noamount."^".$tmp_optreserve3."^".$amount3[$i]."&&";

		}
	}

	if($opttitle4 != ""){
		$tmp_optcode4 = explode("&&",$optcode4_val);
		for($i=0; $i<count($tmp_optcode4)-1; $i++){
			$exp = $tmp_optcode4[$i];
			$tmp_opttitle4 = "";
			$tmp_optprice4 = "";
			$tmp_optreserve4 = "";
			list($tmp_opttitle4,$tmp_optprice4,$tmp_optreserve4) = explode("/",$exp);
			
			$tmp_optprice4 = ($ajax_opt_price4[$i])*$amount4[$i];
			$tmp_optprice4_noamount = ($ajax_opt_price4[$i]);

			if($tmp_optreserve4 == 0) $tmp_optreserve4 = $row_prdinfo['reserve']*$amount4[$i];
			else				      $tmp_optreserve4 = $tmp_optreserve4*$amount4[$i];

			$optcode4_price   += $tmp_optprice4;
			$optcode4_reserve += $tmp_optreserve4;

			$tot_optcode4 .= $tmp_opttitle4."^".$tmp_optprice4_noamount."^".$tmp_optreserve4."^".$amount4[$i]."&&";
		}
	}

	if($opttitle8 != ""){
		$tmp_optcode8 = explode("&&",$optcode8_val);
		for($i=0; $i<count($tmp_optcode8)-1; $i++){
			$exp = $tmp_optcode8[$i];
			$tmp_opttitle8 = "";
			$tmp_optprice8 = "";
			$tmp_optreserve8 = "";
			list($tmp_opttitle8,$tmp_optprice8,$tmp_optreserve8) = explode("/",$exp);

			$tmp_optprice8 = ($ajax_opt_price8[$i])*$amount8[$i];
			$tmp_optprice8_noamount = ($ajax_opt_price8[$i]);

			if($tmp_optreserve8 == 0) $tmp_optreserve8 = $row_prdinfo['reserve']*$amount8[$i];
			else				      $tmp_optreserve8 = $tmp_optreserve8*$amount8[$i];

			$optcode8_price   += $tmp_optprice8;
			$optcode8_reserve += $tmp_optreserve8;

			$tot_optcode8 .= $tmp_opttitle8."^".$tmp_optprice8_noamount."^".$tmp_optreserve8."^".$amount8[$i]."&&";
		}
	}
		
	if($opttitle9 != ""){
		$tmp_optcode9 = explode("&&",$optcode9_val);
		for($i=0; $i<count($tmp_optcode9)-1; $i++){
			$exp = $tmp_optcode9[$i];
			$tmp_opttitle9 = "";
			$tmp_optprice9 = "";
			$tmp_optreserve9 = "";
			list($tmp_opttitle9,$tmp_optprice9,$tmp_optreserve9) = explode("/",$exp);

			$tmp_optprice9 = ($ajax_opt_price9[$i])*$amount9[$i];
			$tmp_optprice9_noamount = ($ajax_opt_price9[$i]);

			if($tmp_optreserve9 == 0) $tmp_optreserve9 = $row_prdinfo['reserve']*$amount9[$i];
			else				      $tmp_optreserve9 = $tmp_optreserve9*$amount9[$i];

			$optcode9_price   += $tmp_optprice9;
			$optcode9_reserve += $tmp_optreserve9;

			$tot_optcode9 .= $tmp_opttitle9."^".$tmp_optprice9_noamount."^".$tmp_optreserve9."^".$amount9[$i]."&&";
		}
	}

	if($opttitle10 != ""){
		$tmp_optcode10 = explode("&&",$optcode10_val);
		for($i=0; $i<count($tmp_optcode10)-1; $i++){
			$exp = $tmp_optcode10[$i];
			$tmp_opttitle10 = "";
			$tmp_optprice10 = "";
			$tmp_optreserve10 = "";
			list($tmp_opttitle10,$tmp_optprice10,$tmp_optreserve10) = explode("/",$exp);

			$tmp_optprice10 = ($ajax_opt_price10[$i])*$amount10[$i];
			$tmp_optprice10_noamount = ($ajax_opt_price10[$i]);

			if($tmp_optreserve10 == 0) $tmp_optreserve10 = $row_prdinfo['reserve']*$amount10[$i];
			else				       $tmp_optreserve10 = $tmp_optreserve10*$amount10[$i];

			$optcode10_price   += $tmp_optprice10;
			$optcode10_reserve += $tmp_optreserve10;

			$tot_optcode10 .= $tmp_opttitle10."^".$tmp_optprice10_noamount."^".$tmp_optreserve10."^".$amount10[$i]."&&";
		}
	}

	if($opttitle11 != ""){
		$tmp_optcode11 = explode("&&",$optcode11_val);
		for($i=0; $i<count($tmp_optcode11)-1; $i++){
			$exp = $tmp_optcode11[$i];
			$tmp_opttitle11 = "";
			$tmp_optprice11 = "";
			$tmp_optreserve11 = "";
			list($tmp_opttitle11,$tmp_optprice11,$tmp_optreserve11) = explode("/",$exp);

			$tmp_optprice11 = ($ajax_opt_price11[$i])*$amount11[$i];
			$tmp_optprice11_noamount = ($ajax_opt_price11[$i]);

			if($tmp_optreserve11 == 0) $tmp_optreserve11 = $row_prdinfo['reserve']*$amount11[$i];
			else				       $tmp_optreserve11 = $tmp_optreserve11*$amount11[$i];

			$optcode11_price   += $tmp_optprice11;
			$optcode11_reserve += $tmp_optreserve11;

			$tot_optcode11 .= $tmp_opttitle11."^".$tmp_optprice11_noamount."^".$tmp_optreserve11."^".$amount11[$i]."&&";
		}
	}

	//일반옵션
	if($opttitle5 != ""){
		$tmp_optcode5 = explode("&&",$optcode5_val);

		for($i=0; $i<count($tmp_optcode5)-1; $i++){
			$exp = $tmp_optcode5[$i];
			list($tmp_opttitle5,$tmp_optprice5,$tmp_optreserve5) = explode("/",$exp);

			$optcode5_price   += ($ajax_opt_price5[$i]*$amount5[$i]);
			$optcode5_reserve += ($ajax_opt_reserve5[$i]*$amount5[$i]);

			$tot_optcode5 .= $tmp_opttitle5."^".$ajax_opt_price5[$i]."^0^".$amount5[$i]."&&";
		}
	}


	if($opttitle6 != ""){
		$tmp_optcode6 = explode("&&",$optcode6_val);
		for($i=0; $i<count($tmp_optcode6)-1; $i++){
			$exp = $tmp_optcode6[$i];
			list($tmp_opttitle6,$tmp_optprice6,$tmp_optreserve6) = explode("/",$exp);

			$optcode6_price   += ($ajax_opt_price6[$i]*$amount6[$i]);
			$optcode6_reserve += ($ajax_opt_reserve6[$i]*$amount6[$i]);

			$tot_optcode6 .= $tmp_opttitle6."^".$ajax_opt_price6[$i]."^0^".$amount6[$i]."&&";
		}
	}

	if($opttitle7 != ""){
		$tmp_optcode7 = explode("&&",$optcode7_val);
		for($i=0; $i<count($tmp_optcode7)-1; $i++){
			$exp = $tmp_optcode7[$i];
			list($tmp_opttitle7,$tmp_optprice7,$tmp_optreserve7) = explode("/",$exp);

			$optcode7_price   += ($ajax_opt_price7[$i]*$amount7[$i]);
			$optcode7_reserve += ($ajax_opt_reserve7[$i]*$amount7[$i]);

			$tot_optcode7 .= $tmp_opttitle7."^".$ajax_opt_price7[$i]."^0^".$amount7[$i]."&&";
		}
	}

	if($opttitle != "") {
		$default_price = 0;
	} else {
		//$default_price = $row_prdinfo['sellprice'];
		$default_price = 0;
	}

	$sellprice = $default_price + $optcode_price + $optcode3_price + $optcode4_price + $optcode8_price + $optcode9_price + $optcode10_price + $optcode11_price + $optcode5_price + $optcode6_price + $optcode7_price;

	$reserve   = $optcode_reserve + $optcode3_reserve + $optcode4_reserve + $optcode5_reserve + $optcode6_reserve + $optcode7_reserve + $optcode8_reserve + $optcode9_reserve + $optcode10_reserve + $optcode11_reserve;

	if($wiz_session['id']) $memid = $wiz_session['id'];
	else                   $memid = "비회원";

	$optsortnum = ($direct == "buy") ? "Y" : "";


	if($prdcode != ""){

		$sql_com = "";
		$sql_com .= " uniq_id             = '".$_uniq_id."'                       ";
		$sql_com .= " , memid             = '".$memid."'                          ";
		$sql_com .= " , prdcode           = '".$prdcode."'                        ";
		$sql_com .= " , prdname           = '".$row_prdinfo['prdname']."'         ";
		$sql_com .= " , prdimg            = '".$row_prdinfo['prdimg_R']."'        ";
		$sql_com .= " , prdprice          = '".$sellprice."'                      ";
		$sql_com .= " , prdreserve        = '".$reserve."'                        ";
		$sql_com .= " , opttitle          = '".$opttitle."'                       ";
		$sql_com .= " , optcode           = '".$tot_optcode."'                    ";
		$sql_com .= " , opttitle2         = '".$opttitle2."'                      ";
		$sql_com .= " , optcode2          = '".$optcode2_val."'                   ";
		$sql_com .= " , opttitle3         = '".$opttitle3."'                      ";
		$sql_com .= " , optcode3          = '".$tot_optcode3."'                   ";
		$sql_com .= " , opttitle4         = '".$opttitle4."'                      ";
		$sql_com .= " , optcode4          = '".$tot_optcode4."'                   ";
		$sql_com .= " , opttitle5         = '".$opttitle5."'                      ";
		$sql_com .= " , optcode5          = '".$tot_optcode5."'                   ";
		$sql_com .= " , opttitle6         = '".$opttitle6."'                      ";
		$sql_com .= " , optcode6          = '".$tot_optcode6."'                   ";
		$sql_com .= " , opttitle7         = '".$opttitle7."'                      ";
		$sql_com .= " , optcode7          = '".$tot_optcode7."'                   ";
		$sql_com .= " , opttitle8         = '".$opttitle8."'                      ";
		$sql_com .= " , optcode8          = '".$tot_optcode8."'                   ";
		$sql_com .= " , opttitle9         = '".$opttitle9."'                      ";
		$sql_com .= " , optcode9          = '".$tot_optcode9."'                   ";
		$sql_com .= " , opttitle10        = '".$opttitle10."'                     ";
		$sql_com .= " , optcode10         = '".$tot_optcode10."'                  ";
		$sql_com .= " , opttitle11        = '".$opttitle11."'                     ";
		$sql_com .= " , optcode11         = '".$tot_optcode11."'                  ";
		$sql_com .= " , amount            = '1'                                   ";
		$sql_com .= " , optsortnum        = 'Y'                                   ";
		$sql_com .= " , wdate             = now()                                 ";
		$sql_com .= " , direct            = '".$direct."'                         ";

		$insert_sql = "insert into wiz_basket_tmp set {$sql_com} ";
		query($insert_sql);

		$basketidx = mysqli_insert_id($connect);
		$tmp_basket .= $basketidx."|";
		$_SESSION['basketidx'] = (substr($tmp_basket, -1) == '|') ? substr_replace($tmp_basket, '', -1) : $tmp_basket;

		// 장바구니수 증가
		$sql = "
			update wiz_product 
			   set basketcnt = if(basketcnt is null, 1, basketcnt + 1 )
			 where prdcode = '".$prdcode."'
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

} else {

	if($direct == "buy" || $direct == "checkout") {

		// 적립금 사용여부
		if($oper_info['reserve_use'] != "Y") $tmp_reserve = 0;
		
		$sql_prdinfo = "select * from wiz_product where prdcode='".$prdcode."'";
		$result_prdinfo = query($sql_prdinfo);
		$row_prdinfo = sql_fetch_arr($result_prdinfo);

		if($opttitle != ""){

			$tmp_optcode = explode("&&",$optcode_val);
			for($i=0; $i<count($tmp_optcode)-1; $i++){
				$exp = $tmp_optcode[$i];
				list($tmp_opttitle,$tmp_optprice_db,$tmp_optreserve_db) = explode("^",$exp);

				$tmp_optprice = ($row_prdinfo['sellprice']+$ajax_opt_price[$i])*$amount[$i];
				$tmp_optprice_ch = ($row_prdinfo['sellprice']+$ajax_opt_price[$i]);

			//	if($tmp_optreserve == 0) $tmp_optreserve = $row_prdinfo['reserve']*$amount[$i];
			//	else				     $tmp_optreserve = ($tmp_reserve + $ajax_opt_reserve2[$i])*$amount[$i];

				$tmp_optreserve = ($row_prdinfo['reserve']+$ajax_opt_reserve2[$i])*$amount[$i];

				$optcode_price   += $tmp_optprice;
				$optcode_reserve += $tmp_optreserve;

				$tot_optcode .= $tmp_opttitle."^".$tmp_optprice_ch."^".$tmp_optreserve."^".$amount[$i]."&&";

			}
		}

		if(!empty($opttitle2)){
			$tmp_optcode2 = explode("&&",$optcode2_val);
			for($i=0; $i<count($tmp_optcode2)-1; $i++){
				$exp = $tmp_optcode2[$i];
				list($tmp_opttitle2,$tmp_optprice2,$tmp_optreserve2) = explode("^",$exp);

				if($tmp_optprice2 == 0) $tmp_optprice2 = $row_prdinfo['sellprice']*$amount[$i];
				else				    $tmp_optprice2 = ($tmp_sellprice + $tmp_optprice2)*$amount[$i];

			//	if($tmp_optreserve2 == 0) $tmp_optreserve2 = $row_prdinfo['reserve']*$amount[$i];
			//	else				      $tmp_optreserve2 = ($tmp_reserve + $tmp_optreserve2)*$amount[$i];
				
				$tmp_optreserve2 = ($row_prdinfo['reserve']+$ajax_opt_reserve2[$i])*$amount[$i];

				$optcode2_price   += $tmp_optprice2;
				$optcode2_reserve += $tmp_optreserve2;
			}
		}

		//가격추가옵션
		if($opttitle3 != ""){
			$tmp_optcode3 = explode("&&",$optcode3_val);
			for($i=0; $i<count($tmp_optcode3)-1; $i++){
				$exp = $tmp_optcode3[$i];
				$tmp_opttitle3 = "";
				$tmp_optprice3 = "";
				$tmp_optreserve3 = "";
				list($tmp_opttitle3,$tmp_optprice3,$tmp_optreserve3) = explode("/",$exp);

				$tmp_optprice3 = ($ajax_opt_price3[$i])*$amount3[$i];
				$tmp_optprice3_noamount = ($ajax_opt_price3[$i]);

				if($tmp_optreserve3 == 0) $tmp_optreserve3 = $row_prdinfo['reserve']*$amount3[$i];
				else				      $tmp_optreserve3 = $tmp_optreserve3*$amount3[$i];

				$optcode3_price   += $tmp_optprice3;
				$optcode3_reserve += $tmp_optreserve3;

				$tot_optcode3 .= $tmp_opttitle3."^".$tmp_optprice3_noamount."^".$tmp_optreserve3."^".$amount3[$i]."&&";

			}
		}

		if($opttitle4 != ""){
			$tmp_optcode4 = explode("&&",$optcode4_val);
			for($i=0; $i<count($tmp_optcode4)-1; $i++){
				$exp = $tmp_optcode4[$i];
				$tmp_opttitle4 = "";
				$tmp_optprice4 = "";
				$tmp_optreserve4 = "";
				list($tmp_opttitle4,$tmp_optprice4,$tmp_optreserve4) = explode("/",$exp);
				
				$tmp_optprice4 = ($ajax_opt_price4[$i])*$amount4[$i];
				$tmp_optprice4_noamount = ($ajax_opt_price4[$i]);

				if($tmp_optreserve4 == 0) $tmp_optreserve4 = $row_prdinfo['reserve']*$amount4[$i];
				else				      $tmp_optreserve4 = $tmp_optreserve4*$amount4[$i];

				$optcode4_price   += $tmp_optprice4;
				$optcode4_reserve += $tmp_optreserve4;

				$tot_optcode4 .= $tmp_opttitle4."^".$tmp_optprice4_noamount."^".$tmp_optreserve4."^".$amount4[$i]."&&";
			}
		}

		if($opttitle8 != ""){
			$tmp_optcode8 = explode("&&",$optcode8_val);
			for($i=0; $i<count($tmp_optcode8)-1; $i++){
				$exp = $tmp_optcode8[$i];
				$tmp_opttitle8 = "";
				$tmp_optprice8 = "";
				$tmp_optreserve8 = "";
				list($tmp_opttitle8,$tmp_optprice8,$tmp_optreserve8) = explode("/",$exp);

				$tmp_optprice8 = ($ajax_opt_price8[$i])*$amount8[$i];
				$tmp_optprice8_noamount = ($ajax_opt_price8[$i]);

				if($tmp_optreserve8 == 0) $tmp_optreserve8 = $row_prdinfo['reserve']*$amount8[$i];
				else				      $tmp_optreserve8 = $tmp_optreserve8*$amount8[$i];

				$optcode8_price   += $tmp_optprice8;
				$optcode8_reserve += $tmp_optreserve8;

				$tot_optcode8 .= $tmp_opttitle8."^".$tmp_optprice8_noamount."^".$tmp_optreserve8."^".$amount8[$i]."&&";
			}
		}
			
		if($opttitle9 != ""){
			$tmp_optcode9 = explode("&&",$optcode9_val);
			for($i=0; $i<count($tmp_optcode9)-1; $i++){
				$exp = $tmp_optcode9[$i];
				$tmp_opttitle9 = "";
				$tmp_optprice9 = "";
				$tmp_optreserve9 = "";
				list($tmp_opttitle9,$tmp_optprice9,$tmp_optreserve9) = explode("/",$exp);

				$tmp_optprice9 = ($ajax_opt_price9[$i])*$amount9[$i];
				$tmp_optprice9_noamount = ($ajax_opt_price9[$i]);

				if($tmp_optreserve9 == 0) $tmp_optreserve9 = $row_prdinfo['reserve']*$amount9[$i];
				else				      $tmp_optreserve9 = $tmp_optreserve9*$amount9[$i];

				$optcode9_price   += $tmp_optprice9;
				$optcode9_reserve += $tmp_optreserve9;

				$tot_optcode9 .= $tmp_opttitle9."^".$tmp_optprice9_noamount."^".$tmp_optreserve9."^".$amount9[$i]."&&";
			}
		}

		if($opttitle10 != ""){
			$tmp_optcode10 = explode("&&",$optcode10_val);
			for($i=0; $i<count($tmp_optcode10)-1; $i++){
				$exp = $tmp_optcode10[$i];
				$tmp_opttitle10 = "";
				$tmp_optprice10 = "";
				$tmp_optreserve10 = "";
				list($tmp_opttitle10,$tmp_optprice10,$tmp_optreserve10) = explode("/",$exp);

				$tmp_optprice10 = ($ajax_opt_price10[$i])*$amount10[$i];
				$tmp_optprice10_noamount = ($ajax_opt_price10[$i]);

				if($tmp_optreserve10 == 0) $tmp_optreserve10 = $row_prdinfo['reserve']*$amount10[$i];
				else				       $tmp_optreserve10 = $tmp_optreserve10*$amount10[$i];

				$optcode10_price   += $tmp_optprice10;
				$optcode10_reserve += $tmp_optreserve10;

				$tot_optcode10 .= $tmp_opttitle10."^".$tmp_optprice10_noamount."^".$tmp_optreserve10."^".$amount10[$i]."&&";
			}
		}

		if($opttitle11 != ""){
			$tmp_optcode11 = explode("&&",$optcode11_val);
			for($i=0; $i<count($tmp_optcode11)-1; $i++){
				$exp = $tmp_optcode11[$i];
				$tmp_opttitle11 = "";
				$tmp_optprice11 = "";
				$tmp_optreserve11 = "";
				list($tmp_opttitle11,$tmp_optprice11,$tmp_optreserve11) = explode("/",$exp);

				$tmp_optprice11 = ($ajax_opt_price11[$i])*$amount11[$i];
				$tmp_optprice11_noamount = ($ajax_opt_price11[$i]);

				if($tmp_optreserve11 == 0) $tmp_optreserve11 = $row_prdinfo['reserve']*$amount11[$i];
				else				       $tmp_optreserve11 = $tmp_optreserve11*$amount11[$i];

				$optcode11_price   += $tmp_optprice11;
				$optcode11_reserve += $tmp_optreserve11;

				$tot_optcode11 .= $tmp_opttitle11."^".$tmp_optprice11_noamount."^".$tmp_optreserve11."^".$amount11[$i]."&&";
			}
		}

		//일반옵션
		if($opttitle5 != ""){
			$tmp_optcode5 = explode("&&",$optcode5_val);

			for($i=0; $i<count($tmp_optcode5)-1; $i++){
				$exp = $tmp_optcode5[$i];
				list($tmp_opttitle5,$tmp_optprice5,$tmp_optreserve5) = explode("/",$exp);

				$optcode5_price   += ($ajax_opt_price5[$i]*$amount5[$i]);
				$optcode5_reserve += ($ajax_opt_reserve5[$i]*$amount5[$i]);

				$tot_optcode5 .= $tmp_opttitle5."^".$ajax_opt_price5[$i]."^0^".$amount5[$i]."&&";
			}
		}


		if($opttitle6 != ""){
			$tmp_optcode6 = explode("&&",$optcode6_val);
			for($i=0; $i<count($tmp_optcode6)-1; $i++){
				$exp = $tmp_optcode6[$i];
				list($tmp_opttitle6,$tmp_optprice6,$tmp_optreserve6) = explode("/",$exp);

				$optcode6_price   += ($ajax_opt_price6[$i]*$amount6[$i]);
				$optcode6_reserve += ($ajax_opt_reserve6[$i]*$amount6[$i]);

				$tot_optcode6 .= $tmp_opttitle6."^".$ajax_opt_price6[$i]."^0^".$amount6[$i]."&&";
			}
		}

		if($opttitle7 != ""){
			$tmp_optcode7 = explode("&&",$optcode7_val);
			for($i=0; $i<count($tmp_optcode7)-1; $i++){
				$exp = $tmp_optcode7[$i];
				list($tmp_opttitle7,$tmp_optprice7,$tmp_optreserve7) = explode("/",$exp);

				$optcode7_price   += ($ajax_opt_price7[$i]*$amount7[$i]);
				$optcode7_reserve += ($ajax_opt_reserve7[$i]*$amount7[$i]);

				$tot_optcode7 .= $tmp_opttitle7."^".$ajax_opt_price7[$i]."^0^".$amount7[$i]."&&";
			}
		}

		if($opttitle != "") {
			$default_price = 0;
		} else {
			//$default_price = $row_prdinfo['sellprice'];
			$default_price = 0;
		}

		$sellprice = $default_price + $optcode_price + $optcode3_price + $optcode4_price + $optcode8_price + $optcode9_price + $optcode10_price + $optcode11_price + $optcode5_price + $optcode6_price + $optcode7_price;

		$reserve   = $optcode_reserve + $optcode3_reserve + $optcode4_reserve + $optcode5_reserve + $optcode6_reserve + $optcode7_reserve + $optcode8_reserve + $optcode9_reserve + $optcode10_reserve + $optcode11_reserve;

		if($wiz_session['id']) $memid = $wiz_session['id'];
		else                   $memid = "비회원";

		$optsortnum = ($direct == "buy") ? "Y" : "";


		if($prdcode != ""){

			$sql_com = "";
			$sql_com .= " uniq_id             = '".$_uniq_id."'                       ";
			$sql_com .= " , memid             = '".$memid."'                          ";
			$sql_com .= " , prdcode           = '".$prdcode."'                        ";
			$sql_com .= " , prdname           = '".$row_prdinfo['prdname']."'         ";
			$sql_com .= " , prdimg            = '".$row_prdinfo['prdimg_R']."'        ";
			$sql_com .= " , prdprice          = '".$sellprice."'                      ";
			$sql_com .= " , prdreserve        = '".$reserve."'                        ";
			$sql_com .= " , opttitle          = '".$opttitle."'                       ";
			$sql_com .= " , optcode           = '".$tot_optcode."'                    ";
			$sql_com .= " , opttitle2         = '".$opttitle2."'                      ";
			$sql_com .= " , optcode2          = '".$optcode2_val."'                   ";
			$sql_com .= " , opttitle3         = '".$opttitle3."'                      ";
			$sql_com .= " , optcode3          = '".$tot_optcode3."'                   ";
			$sql_com .= " , opttitle4         = '".$opttitle4."'                      ";
			$sql_com .= " , optcode4          = '".$tot_optcode4."'                   ";
			$sql_com .= " , opttitle5         = '".$opttitle5."'                      ";
			$sql_com .= " , optcode5          = '".$tot_optcode5."'                   ";
			$sql_com .= " , opttitle6         = '".$opttitle6."'                      ";
			$sql_com .= " , optcode6          = '".$tot_optcode6."'                   ";
			$sql_com .= " , opttitle7         = '".$opttitle7."'                      ";
			$sql_com .= " , optcode7          = '".$tot_optcode7."'                   ";
			$sql_com .= " , opttitle8         = '".$opttitle8."'                      ";
			$sql_com .= " , optcode8          = '".$tot_optcode8."'                   ";
			$sql_com .= " , opttitle9         = '".$opttitle9."'                      ";
			$sql_com .= " , optcode9          = '".$tot_optcode9."'                   ";
			$sql_com .= " , opttitle10        = '".$opttitle10."'                     ";
			$sql_com .= " , optcode10         = '".$tot_optcode10."'                  ";
			$sql_com .= " , opttitle11        = '".$opttitle11."'                     ";
			$sql_com .= " , optcode11         = '".$tot_optcode11."'                  ";
			$sql_com .= " , amount            = '1'                                   ";
			$sql_com .= " , optsortnum        = 'Y'                                   ";
			$sql_com .= " , wdate             = now()                                 ";
			$sql_com .= " , direct            = '".$direct."'                         ";

			$insert_sql = "insert into wiz_basket_tmp set {$sql_com} ";
			query($insert_sql);

			$basketidx = mysqli_insert_id($connect);
			$tmp_basket .= $basketidx;
			if($direct == "checkout" && $_SESSION["basketidx"] != ""){
				$_SESSION["basketidx"] = $_SESSION["basketidx"]."|".$tmp_basket;
			} else {
				$_SESSION['basketidx'] = $tmp_basket;
			}

			// 장바구니수 증가
			$sql = "
				update wiz_product 
				   set basketcnt = if(basketcnt is null, 1, basketcnt + 1 )
				 where prdcode = '".$prdcode."'
			";
			query($sql);

		}

		$product_idx[] = $basketidx;

	} else {

		if(
			$optcode_v || $optcode_v2 || $optcode_v3 || $optcode_v4 || $optcode_v5 || $optcode_v6 || $optcode_v7 || $optcode_v8 || $optcode_v9 || $optcode_v10 || $optcode_v11
		) {
			$s_amount = 1;
		} else {
			$s_amount = $amount;
		}

		$update_sql = "
			update wiz_basket_tmp 
			   set amount = amount + '$s_amount' 
			 where idx = '".$upidx."'
		";
		query($update_sql);

		$product_idx[] = $upidx;

	}

}

$product_idx = implode("|", $product_idx);
echo $product_idx."^".$direct;

?>
