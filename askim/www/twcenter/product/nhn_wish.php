<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

## item data를 생성
class ItemStack {
	var $id;
	var $name;
	var $uprice;
	var $image;
	var $thumb;
	var $url;

	function ItemStack($_id, $_name, $_uprice, $_image, $_thumb, $_url) {
		$this->id     = $_id;
		$this->name   = $_name;
		$this->uprice = $_uprice;
		$this->image  = $_image;
		$this->thumb  = $_thumb;
		$this->url    = $_url;
	}

	function makeQueryString() {
		$ret .= 'ITEM_ID=' . urlencode($this->id);
		$ret .= '&ITEM_NAME=' . urlencode($this->name);
		$ret .= '&ITEM_UPRICE=' . $this->uprice;
		$ret .= '&ITEM_IMAGE=' . urlencode($this->image);
		$ret .= '&ITEM_THUMB=' . urlencode($this->thumb);
		$ret .= '&ITEM_URL=' . urlencode($this->url);
		return $ret;
	}
};

$shopId   = $oper_info['nhn_shopid'];
$certiKey = $oper_info['nhn_certikey'];

$queryString = 'SHOP_ID='.urlencode($shopId);
$queryString .= '&CERTI_KEY='.urlencode($certiKey);
$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5=';

## DB에서 상품 정보를 얻어옴/옵션가격 가공처리

$sql = "select * from wiz_product wp, wiz_cprelation wc where wp.prdcode='$prdcode' and wc.prdcode = wp.prdcode";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);
$prd_row = sql_fetch_arr($result);
if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");

	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row["prdimg_L1"])) $prd_row["prdimg_L1"] = "/images/noimg_L.gif";
	else $prd_row["prdimg_L1"] = "/twcenter/data/prdimg/".$prd_row["prdimg_L1"];

	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row["prdimg_S1"])) $prd_row["prdimg_S1"] = "/images/noimg_S.gif";
	else $prd_row["prdimg_S1"] = "/twcenter/data/prdimg/".$prd_row["prdimg_S1"];

	$uid    = $prd_row['prdcode'];
	$name   = $prd_row['prdname'];
	$uprice = $prd_row['sellprice'];
	$image  = WAY_HOST.$prd_row["prdimg_L1"];
	$thumb  = WAY_HOST.$prd_row["prdimg_S1"];
	//$url    = WAY_HOST."/twcenter/product/view.php?prdcode=".$prd_row['prdcode'];
	$url    = WAY_HOST."/shop/shop.php?ptype=view&prdcode=$prdcode";
	$item   = new ItemStack($uid, $name, $uprice, $image, $thumb, $url);
	$queryString .= '&'.$item->makeQueryString();

//echo($queryString."<br>\n");

if($oper_info['nhn_host_chk'] == "T") $nhn_host = "test-".$oper_info['nhn_host'];
else                                  $nhn_host = $oper_info['nhn_host'];

$req_addr = 'ssl://'.$nhn_host;

$req_url = 'POST /customer/api/wishlist.nhn HTTP/1.1'; // utf-8

//$req_url = 'POST /customer/api/CP949/wishlist.nhn HTTP/1.1'; // euc-kr
$req_host = $nhn_host;
$req_port = 443;
$nc_sock = @fsockopen($req_addr, $req_port, $errno, $errstr);
if ($nc_sock) {
	fwrite($nc_sock, $req_url."\r\n" );
	fwrite($nc_sock, "Host: ".$req_host.":".$req_port."\r\n" );
	fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n"); // euc-kr
	fwrite($nc_sock, "Content-length: ".strlen($queryString)."\r\n");
	fwrite($nc_sock, "Accept: */*\r\n");
	fwrite($nc_sock, "\r\n");
	fwrite($nc_sock, $queryString."\r\n");
	fwrite($nc_sock, "\r\n");

	// get header
	while(!feof($nc_sock)){
		$header=@fgets($nc_sock,4096);
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
		// 한개일경우
		$itemId = $bodys;

		// 여러개일경우
		//$itemIds = trim($bodys);
		//$itemIdList = split(",",$itemIds);
	} else {
		// fail
		echo $bodys;
	}

} else {
 echo "$errstr ($errno)<br>\n";
 exit(-1);
 //에러처리
}

//리턴받은 itemId로 주문서 page를 호출한다.
//echo ($itemId."<br>\n");

if(mobile_check() == true) {
	if($oper_info['nhn_host_chk'] == "T") $nhn_host = "test-m.".$oper_info['nhn_host'];
	else                                  $nhn_host = "m.".$oper_info['nhn_host'];
	$wishlistPopupUrl = "//".$nhn_host."/mobile/customer/wishList.nhn";
}else{
	$wishlistPopupUrl = "//".$nhn_host."/customer/wishlistPopup.nhn";
}

?>
<html>
<body>
<form name="frm" method="get" action="<?=$wishlistPopupUrl?>">
<input type="hidden" name="SHOP_ID" value="<?=$shopId?>">

<!-- 한 개일 경우 -->
<input type="hidden" name="ITEM_ID" value="<?=$itemId?>">

<!-- 여러 개일 경우
<? for($i=0; $i < count($itemIdList); $i++) { ?>
<input type="hidden" name="ITEM_ID" value="<?=$itemIdList[$i]?>">
<? } ?>
-->
</form>
</body>
<script>

<? if ($resultCode == 200) { ?>
document.frm.target = "_top";
document.frm.submit();
<? } ?>
</script>
</html>
