<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";


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
		//echo "aaaaaaa<br>";
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

$q_sql  = "";
for($i=1; $i<=11; $i++) {
	$j = ($i == 1) ? "" : $i;
	$q_sql .= " and optcode".$j." = '".${'optcode_v'.$j}."' \n";
}

$bsql = "
	select * 
	  from wiz_wishlist 
	 where memid = '".$wiz_session['id']."' 
	   $q_sql
	   and prdcode = '".$prdcode."'
";
$bresult = query($bsql);
$num_brow = sql_fetch_row($bresult);

$basket_exist = 0;
if($num_brow != "0"){
	$brow = sql_fetch_arr($bresult);
	$upidx = $brow['idx'];
	++$basket_exist;
}

if($basket_exist <= 0){

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


			if($tmp_optreserve == 0) $tmp_optreserve = $row_prdinfo['reserve']*$amount[$i];
			else				     $tmp_optreserve = ($tmp_reserve + $tmp_optreserve)*$amount[$i];

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

			if($tmp_optreserve2 == 0) $tmp_optreserve2 = $row_prdinfo['reserve']*$amount[$i];
			else				      $tmp_optreserve2 = ($tmp_reserve + $tmp_optreserve2)*$amount[$i];

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
			$optcode5_reserve += 0;

			$tot_optcode5 .= $tmp_opttitle5."^".$ajax_opt_price5[$i]."^0^".$amount5[$i]."&&";
		}
	}


	if($opttitle6 != ""){
		$tmp_optcode6 = explode("&&",$optcode6_val);
		for($i=0; $i<count($tmp_optcode6)-1; $i++){
			$exp = $tmp_optcode6[$i];
			list($tmp_opttitle6,$tmp_optprice6,$tmp_optreserve6) = explode("/",$exp);

			$optcode6_price   += ($ajax_opt_price6[$i]*$amount6[$i]);
			$optcode6_reserve += 0;

			$tot_optcode6 .= $tmp_opttitle6."^".$ajax_opt_price6[$i]."^0^".$amount6[$i]."&&";
		}
	}

	if($opttitle7 != ""){
		$tmp_optcode7 = explode("&&",$optcode7_val);
		for($i=0; $i<count($tmp_optcode7)-1; $i++){
			$exp = $tmp_optcode7[$i];
			list($tmp_opttitle7,$tmp_optprice7,$tmp_optreserve7) = explode("/",$exp);

			$optcode7_price   += ($ajax_opt_price7[$i]*$amount7[$i]);
			$optcode7_reserve += 0;

			$tot_optcode7 .= $tmp_opttitle7."^".$ajax_opt_price7[$i]."^0^".$amount7[$i]."&&";
		}
	}

	if($opttitle != ""){
		$default_price = 0;
	} else if($opttitle == "") {
		if(
			($opttitle5 != "" || $opttitle6 != "" || $opttitle7 != "") && 
			($opttitle3 != "" || $opttitle4 != "" || $opttitle8 != "" || $opttitle9 != "" || $opttitle10 != "" || $opttitle11 != ""))
			{
			$default_price = $row_prdinfo['sellprice'];
		} else if($opttitle5 != "" || $opttitle6 != "" || $opttitle7 != "") {
			$default_price = 0;
		} else {
			$default_price = 0;
		}
	}

	$sellprice = $default_price + $optcode_price + $optcode3_price + $optcode4_price + $optcode8_price + $optcode9_price + $optcode10_price + $optcode11_price + $optcode5_price + $optcode6_price + $optcode7_price;

	$reserve   = $optcode_reserve + $optcode3_reserve + $optcode4_reserve + $optcode8_reserve + $optcode9_reserve + $optcode10_reserve + $optcode11_reserve;

	if($prdcode != "") {

		$opt_title_sql  = "";
		$opt_code_sql   = "";

		for($i=3; $i<=11; $i++) {
			$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i}."'           ";
			$opt_code_sql   .= " , optcode".$i."          = '".${'tot_optcode'.$i}."'        ";
		}

		$sql_com = "";
		$sql_com .= " memid             = '".$wiz_session['id']."'         ";
		$sql_com .= " , prdcode         = '".$prdcode."'                   ";
		$sql_com .= " , opttitle        = '".$opttitle."'                  ";
		$sql_com .= " , optcode         = '".$tot_optcode."'               ";
		$sql_com .= " , opttitle2       = '".$opttitle2."'                 ";
		$sql_com .= " , optcode2        = '".$optcode2_val."'              ";
		$sql_com .= " {$opt_title_sql}                                     ";
		$sql_com .= " {$opt_code_sql}                                      ";
		$sql_com .= " , amount          = '1'                              ";
		$sql_com .= " , wdate           = now()                            ";
		$sql_com .= " , price           = '".$sellprice."'                 ";
		$sql_com .= " , reserve         = '".$reserve."'                   ";

		$sql = "insert into wiz_wishlist set {$sql_com} ";
		query($sql);

	
	}
}

?>