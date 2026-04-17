<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$param = "send_name=$send_name&orderid=$orderid&order_guest=$order_guest&page=$page";

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql) or error("sql error");
$order_info = sql_fetch_arr($result);

$status = $order_info['status'];

// 주문취소 버튼
get_cancel_btn();

// 교환요청 버튼
get_exchange_btn();

// 에스크로 버튼
//get_escrow_btn();

// 세금계산서 버튼
get_tax_btn();

include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/order_info_p.php';			// 주문정보

if(!empty($HTTP_REFERER)) {
	$pos = strpos($HTTP_REFERER, SSL.$HTTP_HOST);
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
</head>
<body topmargin="0" leftmargin="0" <? if($print == "ok"){ ?>onLoad="window.print();" <? } ?>>
<table width="100%" cellpadding=0 cellspacing=0>
  <tr>
    <td>

      <table width="100%">
        <tr><td><?=$ordinfo?></td></tr>
	      <tr><td height="10"></td></tr>
	      <? if($print != "ok"){ ?>
	      <tr>
		      <td align="center">
		      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		      		<tr>
		      			<td width="30%" style="text-align:left;"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?ptype=list&<?php echo $param ?>"><img src="/twcenter/product/image/btn_list.gif" border="0"></a></td>
		      			<td align="center"><?php echo $cancel_btn ?><!-- &nbsp;<?$exchange_btn?>&nbsp;<?=$escrow_btn?> --></td>
		      			<td width="30%" align="right"><?//=$tax_btn?></td>
		      		</tr>
		      	</table>
		      </td>
	      </tr>
	    	<? } ?>
      </table>

    </td>
  </tr>
</table>
</body>
</html>