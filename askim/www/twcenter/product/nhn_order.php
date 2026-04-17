<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

//item data를 생성한다.
class ItemStack {
	var $id;
	var $name;
	var $tprice;
	var $uprice;
	var $option;
	var $count;

	//option이 여러 종류라면, 선택된 옵션을 슬래시(/)로 구분해서 표시하는 것을 권장한다.
	function ItemStack($_id, $_name, $_tprice, $_uprice, $_option, $_count) {
		$this->id = $_id;
		$this->name = $_name;
		$this->tprice = $_tprice;
		$this->uprice = $_uprice;
		$this->option = $_option;
		$this->count = $_count;
	}

	function makeQueryString() {
		$ret .= 'ITEM_ID=' . urlencode($this->id);
		$ret .= '&ITEM_NAME=' . urlencode($this->name);
		$ret .= '&ITEM_COUNT=' . $this->count;
		$ret .= '&ITEM_OPTION=' . urlencode($this->option);
		$ret .= '&ITEM_TPRICE=' . $this->tprice;
		$ret .= '&ITEM_UPRICE=' . $this->uprice;

		return $ret;
	}
};

$shopId   = $oper_info['nhn_shopid'];
$certiKey = $oper_info['nhn_certikey'];

#-----------------------------------------------------------------------------------------------------------
#   배송비 정책가져오기																				
#-----------------------------------------------------------------------------------------------------------
$t_prdprice = 0;

# 바로구매의 경우 해당상품만 가져오기.
if($_SESSION['basketidx'] != ""){
	if(strpos($_SESSION['basketidx'],"|")){
		$bidxs = explode("|",$_SESSION['basketidx']);
		foreach($bidxs as $k => $v){
			if($v != ""){
				if($basket_idxs != "") $basket_idxs .= ",";
				$basket_idxs .= "'".$v."'";
			}
		}
		$basket_sql .= " AND wb.idx IN(".$basket_idxs.")";
	}else{
		$basket_sql .= " AND wb.idx = '".$_SESSION['basketidx']."' ";
	}
	unset($_SESSION['basketidx']);
} else {
	$basket_sql .= " AND wb.direct = 'basket' ";
}

unset($_SESSION['basketidx']);

$sql_del = "
	SELECT wb.*
		 , wp.del_type
		 , wp.del_price 
	  FROM wiz_basket_tmp as wb 
	  left join wiz_product as wp 
		on wb.prdcode = wp.prdcode 
	 WHERE wb.uniq_id='".$_uniq_id."' 
	   $basket_sql ;
";
$result_del = query($sql_del) or error("sql error");
$prd_delprice = 0;
while($drow = sql_fetch_obj($result_del)){
	$prdprice += $drow->prdprice * $drow->amount;
	if($drow->del_type == 'DC' && $drow->del_price > 0) $prd_delprice += $drow->del_price;
}
$t_prdprice = (int)$prdprice;

$deliver_price = deliver_price($t_prdprice, $oper_info);
$deliver_price += $prd_delprice;

$s_Type = (($t_prdprice < $oper_info['del_staprice']) && $deliver_price > 0) ? "PAYED" : "FREE";
$shippingType = $s_Type;

if ($shippingType == $s_Type) {
	$shippingPrice = $deliver_price;
} else {
	$shippingPrice = 0;
}


if(mobile_check() == true){
	if($prdcode != "") $backUrl = WAY_HOST."/m/sub/prdview.php?prdcode=$prdcode";	## 상품상세보기
	else $backUrl = WAY_HOST."/m/sub/cart.php";									## 장바구니
}else{
	if($prdcode != "") $backUrl = WAY_HOST."/shop/shop.php?ptype=view&prdcode=$prdcode";	## 상품상세보기
	else $backUrl = WAY_HOST."/shop/basket.php";											## 장바구니
}



$queryString = 'SHOP_ID='.urlencode($shopId);
$queryString .= '&CERTI_KEY='.urlencode($certiKey);
$queryString .= '&SHIPPING_TYPE='.$shippingType;
$queryString .= '&SHIPPING_PRICE='.$shippingPrice;
$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5=';
$queryString .= '&BACK_URL='.urlencode($backUrl);
$queryString .= '&SA_CLICK_ID='.$_COOKIE["NVADID"]; //CTS

// CPA 스크립트 가이드 설치 업체는 해당 값 전달
$queryString .= '&CPA_INFLOW_CODE='.urlencode($_COOKIE["CPAValidator"]);
$queryString .= '&NAVER_INFLOW_CODE='.urlencode($_COOKIE["NA_CO"]);

$totalMoney = 0;

#-----------------------------------------------------------------------------------------------------------
#   DB와 장바구니에서 상품 정보를 얻어 온다		
#-----------------------------------------------------------------------------------------------------------
//$search_sql = "";
//if($prdcode != "") $search_sql .= " and wb.prdcode = '$prdcode' ";
//if($basket_idx != "") $search_sql .= " and wb.idx = '$basket_idx' ";

$sql = "
	SELECT wb.*
		 , wp.del_type
		 , wp.del_price
		 , wp.shortage
	  FROM wiz_basket_tmp as wb 
	  LEFT JOIN wiz_product as wp 
	    ON wb.prdcode = wp.prdcode
	 WHERE wb.uniq_id='".$_uniq_id."' 
	   $search_sql 
	   $basket_sql 
	 ORDER BY wb.prdcode DESC
";
$btresult = query($sql) or error("sql error");
while($btrow = sql_fetch_obj($btresult)){

		$basket_exist = true;
		$optcode      = "";
		$prdimg       = "";
		$del_type     = "";
		$prd_price   += ($btrow->prdprice * $btrow->amount);

		$opt3 = "";
		$opt4 = "";
		$opt5 = "";
		$opt6 = "";
		$opt7 = "";
		$opt8 = "";
		$opt9 = "";
		$opt10 = "";
		$opt11 = "";
		
		if(strpos($btrow->optcode5,"&&") !== false){
			$opt5_val = explode("&&",$btrow->optcode5);
			for($i=0; $i<count($opt5_val)-1; $i++){
				$exp = $opt5_val[$i];
				list($optcode5_v,$t_optcode5_v2,$t_optcode5_v3,$t_optcode5_v4) = explode("^",$exp);
				$optcode5_v2 = "(".number_format($t_optcode5_v2)."원 / ".$t_optcode5_v4."개)";
				$opt5 .= "".$btrow->opttitle5." : ".$optcode5_v." ".$optcode5_v2;
			}
		} else {
			list($optcode5_v,$t_optcode5_v2) = explode("/",$btrow->optcode5);
			$optcode5_v2 = "(".number_format($t_optcode5_v2)."원)";
			$opt5 = "".$btrow->opttitle5." : ".$optcode5_v." ".$optcode5_v2;

		}

		if($btrow->opttitle5 != '' && $btrow->optcode5 != '')  $optcode .= $opt5;


		if(strpos($btrow->optcode6,"&&") !== false){
			$opt6_val = explode("&&",$btrow->optcode6);
			for($i=0; $i<count($opt6_val)-1; $i++){
				$exp = $opt6_val[$i];
				list($optcode6_v,$t_optcode6_v2,$t_optcode6_v3,$t_optcode6_v4) = explode("^",$exp);
				$optcode6_v2 = "(".number_format($t_optcode6_v2)."원 / ".$t_optcode6_v4."개)";
				$opt6 .= "".$btrow->opttitle6." : ".$optcode6_v." ".$optcode6_v2;
			}
		} else {
			list($optcode6_v,$t_optcode6_v2) = explode("/",$btrow->optcode6);
			$optcode6_v2 = "(".number_format($t_optcode6_v2)."원)";
			$opt6 = "".$btrow->opttitle6." : ".$optcode6_v." ".$optcode6_v2.",";
		}

		if($btrow->opttitle6 != '' && $btrow->optcode6 != '')  $optcode .= $opt6;


		if(strpos($btrow->optcode7,"&&") !== false){
			$opt7_val = explode("&&",$btrow->optcode7);
			for($i=0; $i<count($opt7_val)-1; $i++){
				$exp = $opt7_val[$i];
				list($optcode7_v,$t_optcode7_v2,$t_optcode7_v3,$t_optcode7_v4) = explode("^",$exp);
				$optcode7_v2 = "(".number_format($t_optcode7_v2)."원 / ".$t_optcode7_v4."개)";
				$opt7 .= "".$btrow->opttitle7." : ".$optcode7_v." ".$optcode7_v2;
			}
		} else {
			list($optcode7_v,$t_optcode7_v2) = explode("/",$btrow->optcode7);
			$optcode7_v2 = "(".number_format($t_optcode7_v2)."원)";
			$opt7 = "".$btrow->opttitle7." : ".$optcode7_v." ".$optcode7_v2;
		}

		if($btrow->opttitle7 != '' && $btrow->optcode7 != '')  $optcode .= $opt7;



		if(strpos($btrow->optcode3,"&&") !== false){
			$opt3_val = explode("&&",$btrow->optcode3);
			for($i=0; $i<count($opt3_val)-1; $i++){
				$exp = $opt3_val[$i];
				list($optcode3_v,$t_optcode3_v2,$t_optcode3_v3,$t_optcode3_v4) = explode("^",$exp);
				$optcode3_v2 = "(".number_format($t_optcode3_v2)."원 / ".$t_optcode3_v4."개)";
				$opt3 .= "".$btrow->opttitle3." : ".$optcode3_v." ".$optcode3_v2;
			}
		} else {
			list($optcode3_v,$t_optcode3_v2) = explode("/",$btrow->optcode3);
			$optcode3_v2 = "(".number_format($t_optcode3_v2)."원)";
			$opt3 = "".$btrow->opttitle3." : ".$optcode3_v." ".$optcode3_v2;
		}

		if(strpos($btrow->optcode4,"&&") !== false){
			$opt4_val = explode("&&",$btrow->optcode4);
			for($i=0; $i<count($opt4_val)-1; $i++){
				$exp = $opt4_val[$i];
				list($optcode4_v,$t_optcode4_v2,$t_optcode4_v3,$t_optcode4_v4) = explode("^",$exp);
				$optcode4_v2 = "(".number_format($t_optcode4_v2)."원 / ".$t_optcode4_v4."개)";
				$opt4 .= "".$btrow->opttitle4." : ".$optcode4_v." ".$optcode4_v2;
			}
		} else {
			list($optcode4_v,$t_optcode4_v2) = explode("/",$btrow->optcode4);
			$optcode4_v2 = "(".number_format($t_optcode4_v2)."원)";
			$opt4 = "".$btrow->opttitle4." : ".$optcode4_v." ".$optcode4_v2;
		}

		if(strpos($btrow->optcode8,"&&") !== false){
			$opt8_val = explode("&&",$btrow->optcode8);
			for($i=0; $i<count($opt8_val)-1; $i++){
				$exp = $opt8_val[$i];
				list($optcode8_v,$t_optcode8_v2,$t_optcode8_v3,$t_optcode8_v4) = explode("^",$exp);
				$optcode8_v2 = "(".number_format($t_optcode8_v2)."원 / ".$t_optcode8_v4."개)";
				$opt8 .= "- ".$btrow->opttitle8." : ".$optcode8_v." ".$optcode8_v2;
			}
		} else {
			list($optcode8_v,$t_optcode8_v2) = explode("/",$btrow->optcode8);
			$optcode8_v2 = "(".number_format($t_optcode8_v2)."원)";
			$opt8 = "".$btrow->opttitle8." : ".$optcode8_v." ".$optcode8_v2;
		}

		if(strpos($btrow->optcode9,"&&") !== false){
			$opt9_val = explode("&&",$btrow->optcode9);
			for($i=0; $i<count($opt9_val)-1; $i++){
				$exp = $opt9_val[$i];
				list($optcode9_v,$t_optcode9_v2,$t_optcode9_v3,$t_optcode9_v4) = explode("^",$exp);
				$optcode9_v2 = "(".number_format($t_optcode9_v2)."원 / ".$t_optcode9_v4."개)";
				$opt9 .= "".$btrow->opttitle9." : ".$optcode9_v." ".$optcode9_v2;
			}
		} else {
			list($optcode9_v,$t_optcode9_v2) = explode("/",$btrow->optcode9);
			$optcode9_v2 = "(".number_format($t_optcode9_v2)."원)";
			$opt9 = "".$btrow->opttitle9." : ".$optcode9_v." ".$optcode9_v2;
		}

		if(strpos($btrow->optcode10,"&&") !== false){
			$opt10_val = explode("&&",$btrow->optcode10);
			for($i=0; $i<count($opt10_val)-1; $i++){
				$exp = $opt10_val[$i];
				list($optcode10_v,$t_optcode10_v2,$t_optcode10_v3,$t_optcode10_v4) = explode("^",$exp);
				$optcode10_v2 = "(".number_format($t_optcode10_v2)."원 / ".$t_optcode10_v4."개)";
				$opt10 .= "".$btrow->opttitle10." : ".$optcode10_v." ".$optcode10_v2;
			}
		} else {
			list($optcode10_v,$t_optcode10_v2) = explode("/",$btrow->optcode10);
			$optcode10_v2 = "(".number_format($t_optcode10_v2)."원)";
			$opt10 = "".$btrow->opttitle10." : ".$optcode10_v." ".$optcode10_v2;
		}

		if(strpos($btrow->optcode11,"&&") !== false){
			$opt11_val = explode("&&",$btrow->optcode11);
			for($i=0; $i<count($opt11_val)-1; $i++){
				$exp = $opt11_val[$i];
				list($optcode11_v,$t_optcode11_v2,$t_optcode11_v3,$t_optcode11_v4) = explode("^",$exp);
				$optcode11_v2 = "(".number_format($t_optcode11_v2)."원 / ".$t_optcode11_v4."개)";
				$opt11 .= "".$btrow->opttitle11." : ".$optcode11_v." ".$optcode11_v2;
			}
		} else {
			list($optcode11_v,$t_optcode11_v2) = explode("/",$btrow->optcode11);
			$optcode11_v2 = "(".number_format($t_optcode11_v2)."원)";
			$opt11 = "".$btrow->opttitle11." : ".$optcode11_v." ".$optcode11_v2;
		}


		if($btrow->opttitle3 != '' && $btrow->optcode3 != '')  $optcode .= $opt3;
		if($btrow->opttitle4 != '' && $btrow->optcode4 != '')  $optcode .= $opt4;
		if($btrow->opttitle8 != '' && $btrow->optcode8 != '')  $optcode .= $opt8;
		if($btrow->opttitle9 != '' && $btrow->optcode9 != '')  $optcode .= $opt9;
		if($btrow->opttitle10 != '' && $btrow->optcode10 != '') $optcode .= $opt10;
		if($btrow->opttitle11 != '' && $btrow->optcode11 != '') $optcode .= $opt11;

		if(strpos($btrow->optcode,"&&") !== false){
			$opt_val = explode("&&",$btrow->optcode);
			for($i=0; $i<count($opt_val)-1; $i++){
				$exp = $opt_val[$i];
				list($optcode_v,$t_optcode_v2,$t_optcode_v3,$t_optcode_v4) = explode("^",$exp);
				$optcode_v2 = "(".number_format($t_optcode_v2)."원 / ".$t_optcode_v4."개)";

				if($btrow->opttitle != '') $topttitle = $btrow->opttitle;
				if($btrow->opttitle != '' && $btrow->opttitle2 != '') $topttitle .= "/";
				if($btrow->opttitle2 != '') $topttitle .= $btrow->opttitle2;

				$opt .= "".$topttitle." : ".$optcode_v." ".$optcode_v2;
			}
		} else {
			list($optcode_v,$t_optcode_v2) = explode("^",$btrow->optcode);
			if($t_optcode_v2 != 0){
				$optcode_v2 = "(".number_format($t_optcode_v2)."원)";
			} else {
				$optcode_v2 = "";
			}

			if($btrow->opttitle != '') $topttitle = $btrow->opttitle;
			if($btrow->opttitle != '' && $btrow->opttitle2 != '') $topttitle .= "/";
			if($btrow->opttitle2 != '') $topttitle .= $btrow->opttitle2;

			$opt = "".$topttitle." : ".$optcode_v." ".$optcode_v2;
		}

		if($btrow->opttitle != '' || $btrow->opttitle2 != '') $optcode .= $opt;

		$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;

		$id          = $btrow->prdcode;
		$name        = strip_tags($btrow->prdname);
		$uprice      = $btrow->prdprice;
		$count       = $btrow->amount;
		$tprice      = $uprice * $count;
		$option      = $optcode;

		if($id == "") {
			error("상품 정보가 전달되지 않았습니다.");
			exit;
		}

		$item        = new ItemStack($id, $name, $tprice, $uprice, $option, $count);
		$totalMoney += $tprice;
		$queryString .= '&EC_MALL_PID='.$btrow->prdcode;			//-- '지식쇼핑 mapid와 동일(상품ID)
		$queryString .= '&'.$item->makeQueryString();

}

$totalPrice = (int)$totalMoney + (int)$shippingPrice;
$queryString .= '&TOTAL_PRICE='.$totalPrice;

//echo($queryString."<br>\n");

if($oper_info['nhn_host_chk'] == "T") $chk_url = "test-".$oper_info['nhn_host'];
else                                  $chk_url = $oper_info['nhn_host'];

$req_addr = 'ssl://'.$chk_url;
$req_url = 'POST /customer/api/order.nhn HTTP/1.1'; // utf-8
//$req_url = 'POST /customer/api/CP949/order.nhn HTTP/1.1'; // euc-kr
$req_host = $chk_url;
$req_port = 443;

$fp = fopen($_SERVER['DOCUMENT_ROOT']."/twcenter/data/nc_order_log.txt", "a+");
ob_start();
echo "\n====================================================================================\n";
echo date('Y-m-d His'). "-" .$_SERVER['HTTP_USER_AGENT'];
echo "req_addr - ".$req_addr."\n";
echo "req_port - ".$req_port."\n";
echo "errno - ".$errno."\n";
echo "errstr - ".$errstr."\n";
echo "queryString - ".$queryString."\n";
echo "\n====================================================================================\n";
$msg = ob_get_contents();
ob_end_clean();
fwrite($fp, $msg);
fclose($fp);

$nc_sock = @fsockopen($req_addr, $req_port, $errno, $errstr);

if ($nc_sock) {
	fwrite($nc_sock, $req_url."\r\n" );
	fwrite($nc_sock, "Host: ".$req_host.":".$req_port."\r\n" );
	fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n");
	fwrite($nc_sock, "Content-length: ".strlen($queryString)."\r\n");
	fwrite($nc_sock, "Accept: */*\r\n");
	fwrite($nc_sock, "\r\n");
	fwrite($nc_sock, $queryString."\r\n");
	fwrite($nc_sock, "\r\n");

	// get header
	while(!feof($nc_sock)){
		$header=fgets($nc_sock,4096);
		if($header=="\r\n"){
			break;
		} else {
			$headers .= $header;
		}
	}

	// get body
	while(!feof($nc_sock)){
		$bodys.=@fgets($nc_sock,4096);
	}

	fclose($nc_sock);

	$resultCode = substr($headers,9,3);
		
	if ($resultCode == 200) {
		// success
		$orderId = $bodys;
	} else {
		// fail
		echo $bodys;
	}
} else {
	echo "$errstr ($errno)<br>\n";
	exit(-1);
	// 에러처리
}

//리턴받은 order_id로 주문서 page를 호출한다.
//echo ($orderId."<br>\n");
if(mobile_check() == true) {
	if($oper_info['nhn_host_chk'] == "T") $chk_url = "test-m.".$oper_info['nhn_host'];
	else                                  $chk_url = "m.".$oper_info['nhn_host'];
	$orderUrl = "https://".$chk_url."/mobile/customer/order.nhn";
}else{
	$orderUrl = "https://".$chk_url."/customer/order.nhn";
}
?>

<html> 
<body>
	<form name="frm" method="get" action="<?=$orderUrl?>">
	<input type="hidden" name="ORDER_ID" value="<?=$orderId?>">
	<input type="hidden" name="SHOP_ID" value="<?=$shopId?>">
	<input type="hidden" name="TOTAL_PRICE" value="<?=$totalPrice?>">
	</form>
</body>
<script>

<?php
	if($resultCode == 200){
?>
	document.frm.target = "_top";
	document.frm.submit();
<?php } ?>
</script>
</html>
