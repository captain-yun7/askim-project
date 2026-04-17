<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql);
$order_info = sql_fetch_arr($result);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:: 주문내역 ::</title>
<?
if(!empty($HTTP_REFERER)) {
	$pos = strpos($HTTP_REFERER, "http://".$HTTP_HOST);
	if($pos === false) {
?>
<script Language="Javascript">
<!--
		alert("잘못된 경로 입니다.");
		self.close();
//-->
</script>
<?php
		exit;
	}
}
?>
<link rel="stylesheet" type="text/css" href="/twcenter/product/style.css">
<link rel="stylesheet" type="text/css" href="/comm/css/common.css" />
<link rel="stylesheet" type="text/css" href="/comm/css/shop_main.css" />
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css" />
<script type="text/javascript">
$(document).bind("contextmenu", function(e){
	return false;
});

/* F1~F12 기본키 막기 */
$(function() {

	document.onkeydown = function(e) {
		e = e || window.event;
		var nKeyCode = e.keyCode;

		try {
			if(nKeyCode >= 112 && nKeyCode <= 123) {
				if(!+"\v1") {	// IE일 경우
					e.keyCode = e.returnValue = 0;
				} else {		// IE가 아닌 경우
					e.preventDefault();
				}
			}
		} catch(err) {}
	};	
	
});
</script>
</head>
<body onLoad="window.print();">
<table width="100%" cellpadding=20 cellspacing=0>
  <tr>
    <td>

			<? include "../../twcenter/product/order_info.php"; ?>

    </td>
  </tr>
</table>
</body>
</html>