<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql) or error("sql error");
$order_info = sql_fetch_arr($result);

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
<html>
<head>
<title>:: 주문내역 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="/comm/css/common.css" />
<link rel="stylesheet" type="text/css" href="/comm/css/shop_main.css" />
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css" />
</head>
<body onLoad="window.print();">
<table width="100%" cellpadding=20 cellspacing=0>
  <tr>
    <td>

			<? include "./order_info.php"; ?>

    </td>
  </tr>
</table>
</body>
</html>