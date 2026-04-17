<?php

	if(strpos($t_optcode5,"&&") !== false){
		$opt5_val = explode("&&",$t_optcode5);
		for($i=0; $i<count($opt5_val)-1; $i++){
			$exp = $opt5_val[$i];
			list($optcode5_v,$t_optcode5_v2,$t_optcode5_v3,$t_optcode5_v4) = explode("^",$exp);
			$optcode5_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode5_v2)."원 / ".$t_optcode5_v4."개)</span>";
			$opt5 .= $t_opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";
		}
	} else {
		list($optcode5_v,$t_optcode5_v2) = explode("/",$t_optcode5);
		$optcode5_v2 = "";
		$opt5 = $t_opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";

	}

	if($t_opttitle5 != '' && $t_optcode5 != '')  $optcode .= $opt5;


	if(strpos($t_optcode6,"&&") !== false){
		$opt6_val = explode("&&",$t_optcode6);
		for($i=0; $i<count($opt6_val)-1; $i++){
			$exp = $opt6_val[$i];
			list($optcode6_v,$t_optcode6_v2,$t_optcode6_v3,$t_optcode6_v4) = explode("^",$exp);
			$optcode6_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode6_v2)."원 / ".$t_optcode6_v4."개)</span>";
			$opt6 .= $t_opttitle6." : ".$optcode6_v." ".$optcode6_v2."<br>";
		}
	} else {
		list($optcode6_v,$t_optcode6_v2) = explode("/",$t_optcode6);
		$optcode6_v2 = "";
		$opt6 = $t_opttitle6." : ".$optcode6_v." ".$optcode6_v2.",";
	}

	if($t_opttitle6 != '' && $t_optcode6 != '')  $optcode .= $opt6;

	if(strpos($t_optcode7,"&&") !== false){
		$opt7_val = explode("&&",$t_optcode7);
		for($i=0; $i<count($opt7_val)-1; $i++){
			$exp = $opt7_val[$i];
			list($optcode7_v,$t_optcode7_v2,$t_optcode7_v3,$t_optcode7_v4) = explode("^",$exp);
			$optcode7_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode7_v2)."원 / ".$t_optcode7_v4."개)</span>";
			$opt7 .= $t_opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
		}
	} else {
		list($optcode7_v,$t_optcode7_v2) = explode("/",$t_optcode7);
		$optcode7_v2 = "";
		$opt7 = $t_opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
	}

	if($t_opttitle7 != '' && $t_optcode7 != '')  $optcode .= $opt7;



	if(strpos($t_optcode3,"&&") !== false){
		$opt3_val = explode("&&",$t_optcode3);
		for($i=0; $i<count($opt3_val)-1; $i++){
			$exp = $opt3_val[$i];
			list($optcode3_v,$t_optcode3_v2,$t_optcode3_v3,$t_optcode3_v4) = explode("^",$exp);
			$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원 / ".$t_optcode3_v4."개)</span>";
			$opt3 .= $t_opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br>";
		}
	} else {
		list($optcode3_v,$t_optcode3_v2) = explode("/",$t_optcode3);
		$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2 ?? 0)."원)</span>";
		$opt3 = $t_opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br>";
	}

	if(strpos($t_optcode4,"&&") !== false){
		$opt4_val = explode("&&",$t_optcode4);
		for($i=0; $i<count($opt4_val)-1; $i++){
			$exp = $opt4_val[$i];
			list($optcode4_v,$t_optcode4_v2,$t_optcode4_v3,$t_optcode4_v4) = explode("^",$exp);
			$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원 / ".$t_optcode4_v4."개)</span>";
			$opt4 .= $t_opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br>";
		}
	} else {
		list($optcode4_v,$t_optcode4_v2) = explode("/",$t_optcode4);
		$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2 ?? 0)."원)</span>";
		$opt4 = $t_opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br>";
	}

	if(strpos($t_optcode8,"&&") !== false){
		$opt8_val = explode("&&",$t_optcode8);
		for($i=0; $i<count($opt8_val)-1; $i++){
			$exp = $opt8_val[$i];
			list($optcode8_v,$t_optcode8_v2,$t_optcode8_v3,$t_optcode8_v4) = explode("^",$exp);
			$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원 / ".$t_optcode8_v4."개)</span>";
			$opt8 .= $t_opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br>";
		}
	} else {
		list($optcode8_v,$t_optcode8_v2) = explode("/",$t_optcode8);
		$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2 ?? 0)."원)</span>";
		$opt8 = $t_opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br>";
	}

	if(strpos($t_optcode9,"&&") !== false){
		$opt9_val = explode("&&",$t_optcode9);
		for($i=0; $i<count($opt9_val)-1; $i++){
			$exp = $opt9_val[$i];
			list($optcode9_v,$t_optcode9_v2,$t_optcode9_v3,$t_optcode9_v4) = explode("^",$exp);
			$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원 / ".$t_optcode9_v4."개)</span>";
			$opt9 .= $t_opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br>";
		}
	} else {
		list($optcode9_v,$t_optcode9_v2) = explode("/",$t_optcode9);
		$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2 ?? 0)."원)</span>";
		$opt9 = $t_opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br>";
	}

	if(strpos($t_optcode10,"&&") !== false){
		$opt10_val = explode("&&",$t_optcode10);
		for($i=0; $i<count($opt10_val)-1; $i++){
			$exp = $opt10_val[$i];
			list($optcode10_v,$t_optcode10_v2,$t_optcode10_v3,$t_optcode10_v4) = explode("^",$exp);
			$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원 / ".$t_optcode10_v4."개)</span>";
			$opt10 .= $t_opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br>";
		}
	} else {
		list($optcode10_v,$t_optcode10_v2) = explode("/",$t_optcode10);
		$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2 ?? 0)."원)</span>";
		$opt10 = $t_opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br>";
	}

	if(strpos($t_optcode11,"&&") !== false){
		$opt11_val = explode("&&",$t_optcode11);
		for($i=0; $i<count($opt11_val)-1; $i++){
			$exp = $opt11_val[$i];
			list($optcode11_v,$t_optcode11_v2,$t_optcode11_v3,$t_optcode11_v4) = explode("^",$exp);
			$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원 / ".$t_optcode11_v4."개)</span>";
			$opt11 .= $t_opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br>";
		}
	} else {
		list($optcode11_v,$t_optcode11_v2) = explode("/",$t_optcode11);
		$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2 ?? 0)."원)</span>";
		$opt11 = $t_opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br>";
	}
	
	if(
		 strpos($t_optcode12 ?? '', "&&") !== false &&
		strpos($t_optcode13 ?? '', "&&") !== false
	){
		$opt12_val = explode("&&",$t_optcode12);
		$opt13_val = explode("&&",$t_optcode13);
		for($i=0; $i<count($opt12_val)-1; $i++){
			$exp2 = $opt12_val[$i];
			$exp3 = $opt13_val[$i];
			list($optcode12_v,$t_optcode12_v2,$t_optcode12_v3,$t_optcode12_v4) = explode("^",$exp2);
			list($optcode13_v,$t_optcode13_v2,$t_optcode13_v3,$t_optcode13_v4) = explode("^",$exp3);
			//if($t_optcode12_v2 !='' || $t_optcode12_v2 !=0){
				$optcode12_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode12_v2)."원 / ".$t_optcode12_v4."개)</span>";
			//}
			//if($t_optcode13_v2 !='' || $t_optcode13_v2 !=0){
				$optcode13_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode13_v2)."원 / ".$t_optcode13_v4."개)</span>";
			//}
			$opt12 .= $t_opttitle12." : ".$optcode12_v.", ".$t_opttitle13." : ".$optcode13_v." ".$optcode12_v2.$optcode13_v2."<br>";
		}
	} else {
		list($optcode12_v,$t_optcode12_v2) = explode("/",$t_optcode12 ?? '');
		$optcode12_v2 = "";
		$opt12 = $t_opttitle12." : ".$optcode12_v." ".$optcode12_v2."<br>";
	}
	if($t_opttitle12 != '' && $t_optcode12 != '')  $optcode .= $opt12;

	if(strpos($t_optcode13 ?? '', "&&") !== false){
		/*$opt13_val = explode("&&",$t_optcode13);
		for($i=0; $i<count($opt13_val)-1; $i++){
			$exp = $opt13_val[$i];
			list($optcode13_v,$t_optcode13_v2,$t_optcode13_v3,$t_optcode13_v4) = explode("^",$exp);
			if($t_optcode13_v2 !=""){//2021-01-11
				$optcode13_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode13_v2)."원 / ".$t_optcode13_v4."개)</span>";
			}
			$opt13 .= $t_opttitle13." : ".$optcode13_v." ".$optcode13_v2."<br>";
		}*/
	} else {
		list($optcode13_v,$t_optcode13_v2) = explode("/",$t_optcode13 ?? '');
		$optcode13_v2 = "";
		$opt13 = $t_opttitle13." : ".$optcode13_v." ".$optcode13_v2."<br>";
	}
	if($t_opttitle13 != '' && $t_optcode13 != '')  $optcode .= $opt13;


	if($t_opttitle3 != '' && $t_optcode3 != '')  $optcode .= $opt3;
	if($t_opttitle4 != '' && $t_optcode4 != '')  $optcode .= $opt4;
	if($t_opttitle8 != '' && $t_optcode8 != '')  $optcode .= $opt8;
	if($t_opttitle9 != '' && $t_optcode9 != '')  $optcode .= $opt9;
	if($t_opttitle10 != '' && $t_optcode10 != '') $optcode .= $opt10;
	if($t_opttitle11 != '' && $t_optcode11 != '') $optcode .= $opt11;

	if(strpos($t_optcode,"&&") !== false){
		$opt_val = explode("&&",$t_optcode);
		for($i=0; $i<count($opt_val)-1; $i++){
			$exp = $opt_val[$i];
			list($optcode_v,$t_optcode_v2,$t_optcode_v3,$t_optcode_v4) = explode("^",$exp);
			$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원 / ".$t_optcode_v4."개)</span>";

			if($t_opttitle != '') $topttitle = $t_opttitle;
			if($t_opttitle != '' && $t_opttitle2 != '') $topttitle .= "/";
			if($t_opttitle2 != '') $topttitle .= $t_opttitle2;

			$opt .= $topttitle." : ".$optcode_v." ".$optcode_v2."<br>";
		}
	} else {
		list($optcode_v,$t_optcode_v2) = explode("^",$t_optcode);
		if($t_optcode_v2 != 0){
			$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
		} else {
			$optcode_v2 = "";
		}

		if($t_opttitle != '') $topttitle = $t_opttitle;
		if($t_opttitle != '' && $t_opttitle2 != '') $topttitle .= "/";
		if($t_opttitle2 != '') $topttitle .= $t_opttitle2;

		$opt .= $topttitle." : ".$optcode_v." ".$optcode_v2."<br>";
	}

	if($t_opttitle != '' || $t_opttitle2 != '') $optcode .= $opt;

	$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
	$optcode = "<span class='pay_add_tit'>".$optcode."</span>";


?>