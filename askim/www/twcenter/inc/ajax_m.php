<?
include "../common.php";		// DB컨넥션, 접속자 파악
//include "./prd_info.inc";	// 운영정보
include "./oper_info.inc";	// 운영정보
// 재고량 체크

function checkAmount($prdcode, $amount, $optcode){

	global $prd_info;

	global $optcode3;
	global $optcode4;

	$sql = "select prdname, prdimg_R as prdimg, opttitle, optcode, opttitle2, optcode2, opttitle3, optcode3, opttitle4, optcode4, optvalue, stock, sellprice, reserve, shortage, opt_use from wiz_product where prdcode = '$prdcode'";
	$result = query($sql) or error("sql error");
	$prd_info = sql_fetch_obj($result);
   
	if(!empty($prd_info->optcode3)) {

		$opt3_arr = explode("^^", $prd_info->optcode3);

		for($ii = 0; $ii < count($opt3_arr); $ii++) {

			list($opt, $price, $reserve) = explode("^", $opt3_arr[$ii]);

			if(!strcmp($opt, $optcode3)) {

				$prd_info->sellprice = $prd_info->sellprice + $price;
				$prd_info->reserve = $prd_info->reserve + $reserve;

			}
		}
	}
	if(!empty($prd_info->optcode4)) {

		$opt4_arr = explode("^^", $prd_info->optcode4);

		for($ii = 0; $ii < count($opt4_arr); $ii++) {

			list($opt, $price, $reserve) = explode("^", $opt4_arr[$ii]);

			if(!strcmp($opt, $optcode4)) {

				$prd_info->sellprice = $prd_info->sellprice + $price;
				$prd_info->reserve = $prd_info->reserve + $reserve;

			}
		}

	}
	
	if(!strcmp($prd_info->opt_use, "Y")){
		$opt1_arr = explode("^", $prd_info->optcode);
		$opt2_arr = explode("^", $prd_info->optcode2);
		$opt_tmp = explode("^^", $prd_info->optvalue);

		list($optcode1, $optcode2) = explode("/", $optcode);

		$no = 0;
		for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
			for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
				list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

				if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
					$prd_info->sellprice = $prd_info->sellprice + $price;
					$prd_info->reserve = $prd_info->reserve + $reserve;
					if($stock < $amount){
						error("주문수량이 재고량(".$stock."개)보다 많습니다.");
					}
				}

				$no++;
			}
		}

		/*
		$tmp_short = 0;
		$opt_tmp = explode("^^",$prd_info->optcode);
		for($ii=0; $ii<count($opt_tmp)-1; $ii++){
			$opt_sub_tmp = explode("^",$opt_tmp[$ii]);
			if($opt_sub_tmp[0] == $optcode){
				$prd_info->sellprice = $opt_sub_tmp[1];
				if($opt_sub_tmp[2] < $amount){
					error("주문수량이 재고량(".$opt_sub_tmp[2]."개)보다 많습니다.");
				}
			}
		}
		*/

	}else{
		
		if(!strcmp($prd_info->shortage, "S")) {

	   	if($amount > $prd_info->stock){
	   		error("주문수량이 재고량(".$prd_info->stock."개)보다 많습니다.");
	   	}
	   	
	  } else if(!strcmp($prd_info->shortage, "Y")) {
	  	
	  	error("품절된 상품입니다.");
	  	
	  }

	}

}

$optcode=iconv("UTF-8","EUC-KR",$optcode);
$optcode2=iconv("UTF-8","EUC-KR",$optcode2);
$optcode3=iconv("UTF-8","EUC-KR",$optcode3);
$optcode4=iconv("UTF-8","EUC-KR",$optcode4);
$optcode5=iconv("UTF-8","EUC-KR",$optcode5);
$optcode6=iconv("UTF-8","EUC-KR",$optcode6);
$optcode7=iconv("UTF-8","EUC-KR",$optcode7);
$opttitle=iconv("UTF-8","EUC-KR",$opttitle);
$opttitle2=iconv("UTF-8","EUC-KR",$opttitle2);
$opttitle3=iconv("UTF-8","EUC-KR",$opttitle3);
$opttitle4=iconv("UTF-8","EUC-KR",$opttitle4);
$opttitle5=iconv("UTF-8","EUC-KR",$opttitle5);
$opttitle6=iconv("UTF-8","EUC-KR",$opttitle6);
$opttitle7=iconv("UTF-8","EUC-KR",$opttitle7);



$optlist = explode("^",$optcode);
$optcode = $optlist[0];

$optlist = explode("^",$optcode2);
$optcode2 = $optlist[0];

$optlist = explode("^",$optcode3);
$optcode3 = $optlist[0];

$optlist = explode("^",$optcode4);
$optcode4 = $optlist[0];

$optlist = explode("^",$optcode5);
$optcode5 = $optlist[0];

$optlist = explode("^",$optcode6);
$optcode6 = $optlist[0];

$optlist = explode("^",$optcode7);
$optcode7 = $optlist[0];



//$optcode_cut = "";
//$optcode_cut = explode('/',$optcode);
//$optcode = $optcode_cut[0];
//$optcode2 = $optcode_cut[1];



// 같은상품에 같은 옵션을 선택했는지
$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."'";
$bresult = query($bsql) or error("sql error");
while($brow = sql_fetch_arr($bresult)){
	if($brow['prdcode'] == $prdcode &&
		$brow['optcode'] == $optcode){
		$upidx = $brow['idx'];
		$basket_exist = true;
		break;
	}
}
checkAmount($prdcode, $amount, $optcode);
// 적립금 사용여부
if($oper_info->reserve_use != "Y") $prd_info->reserve = 0;
// 중복된 상품에 옵션이 없다면 신규생성

if(!$basket_exist){	
	$sellprice = $tmp_sellprice + $opt_price1 + $opt_price2 + $opt_price3;
	$reserve = $opt_reserve + $tmp_reserve + $opt_reserve1 + $opt_reserve2 + $opt_reserve3;	
	if($prdcode!=""){
		$insert_sql = "INSERT INTO wiz_basket_tmp (
		idx,uniq_id,prdcode,prdname,prdimg,prdprice,prdreserve,opttitle,optcode,opttitle2,optcode2,
		opttitle3,optcode3,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,amount,wdate
		)VALUES(
		'','".$_uniq_id."','$prdcode','$prd_info->prdname','$prd_info->prdimg','$sellprice','$reserve','$opttitle','$optcode','$opttitle2','$optcode2',
		'$opttitle3','$optcode3','$opttitle4','$optcode4','$opttitle5','$optcode5','$opttitle6','$optcode6','$opttitle7','$optcode7','$amount',now())";
		query($insert_sql) or error("sql error");
		// 장바구니수 증가
		$sql = "update wiz_product set basketcnt = basketcnt + 1 where prdcode='$prdcode'";
		@query($sql);
	}
}else{
//	$update_sql = "update wiz_basket_tmp set amount='$amount' where idx='$upidx'";
	$update_sql = "update wiz_basket_tmp set amount=amount+'$amount' where idx='$upidx'";
	query($update_sql);
}

?>
