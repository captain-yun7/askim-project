<?
include "../common.php";
include "./oper_info.php";

$optlist = explode("^",$optcode_opt);
$optcode = $optlist[0];

$optlist = explode("^",$optcode2_opt);
$optcode2 = $optlist[0];

$optlist = explode("^",$optcode3_opt);
$optcode3 = $optlist[0];

$optlist = explode("^",$optcode4_opt);
$optcode4 = $optlist[0];

$optlist = explode("^",$optcode5_opt);
$optcode5 = $optlist[0];

$optlist = explode("^",$optcode6_opt);
$optcode6 = $optlist[0];

$optlist = explode("^",$optcode7_opt);
$optcode7 = $optlist[0];

$optlist = explode("^",$optcode8_opt);
$optcode8 = $optlist[0];

$optlist = explode("^",$optcode9_opt);
$optcode9 = $optlist[0];

$optlist = explode("^",$optcode10_opt);
$optcode10 = $optlist[0];

$optlist = explode("^",$optcode11_opt);
$optcode11 = $optlist[0];

$bsql = "SELECT * FROM wiz_wishlist WHERE memid='".$wiz_session['id']."'";
$bresult = query($bsql) or error("sql error");
while($brow = sql_fetch_arr($bresult)){
	if($brow['prdcode'] == $prdcode &&
		$result['optcode'] == $optcode &&
		$result['optcode2'] == $optcode2 &&
		$result['optcode3'] == $optcode3 &&
		$result['optcode4'] == $optcode4 &&
		$result['optcode5'] == $optcode5 &&
		$result['optcode6'] == $optcode6 &&
		$result['optcode7'] == $optcode7 &&
		$result['optcode8'] == $optcode8 &&
		$result['optcode9'] == $optcode9 &&
		$result['optcode10'] == $optcode10 &&
		$result['optcode11'] == $optcode11){

		$upidx = $brow['idx'];
		$basket_exist = true;
		break;

	}
}

if(!$basket_exist){	
	$sellprice = $tmp_sellprice_opt + $opt_price1 + $opt_price2 + $opt_price3 + $opt_price8 + $opt_price9 + $opt_price10 + $opt_price11;
	$reserve   = $tmp_reserve_opt + $opt_reserve1 + $opt_reserve2 + $opt_reserve3 + $opt_reserve8 + $opt_reserve9 + $opt_reserve10 + $opt_reserve11;

	if($prdcode!=""){

		$insert_sql = "insert into wiz_wishlist
						(idx,memid,prdcode,
						opttitle,optcode,opttitle2,optcode2,opttitle3,optcode3,
						opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,opttitle8,optcode8,opttitle9,optcode9,opttitle10,optcode10,opttitle11,optcode11,amount,wdate,price)
						values
						('','$wiz_session['id']','$prdcode',
						'$opttitle_opt','$optcode_opt','$opttitle2_opt','$optcode2_opt','$opttitle3_opt','$optcode3_opt',
						'$opttitle4_opt','$optcode4_opt','$opttitle5_opt','$optcode5_opt','$opttitle6_opt','$optcode6_opt','$opttitle7_opt','$optcode7_opt','$opttitle8_opt','$optcode8_opt','$opttitle9_opt','$optcode9_opt','$opttitle10_opt','$optcode10_opt','$opttitle11_opt','$optcode11_opt','$amount_opt', now(),'$sellprice')";
		query($insert_sql) or error("sql error");
		
	}
}
?>