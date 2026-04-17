<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql) or error("sql error");
$order_info = sql_fetch_arr($result);

// 주문취소 버튼
get_cancel_btn();

// 교환요청 버튼
get_exchange_btn();

// 에스크로 버튼
//get_escrow_btn();

// 세금계산서 버튼
get_tax_btn();

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
<!DOCTYPE html>
<html lang="ko">
<head>
<title>:: 주문내역 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo $skin_dir ?>/style.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="0" leftmargin="0" <? if($print == "ok"){ ?>onLoad="window.print();" <? } ?>>
<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_info.php";			// 주문정보
?>
<table width="100%" cellpadding=6 cellspacing=0>
  <tr>
    <td>

      <table width="100%">
        <tr><td height="10"></td></tr>
        <tr><td><?=$ordinfo?></td></tr>
	      <tr><td height="10"></td></tr>
	      <? if($print != "ok"){ ?>
	      <tr>
		      <td align="center">
		      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		      		<tr>
		      			<!-- <td width="30%"><a href="javascript:window.print()"><img src="/twcenter/product/image/btn_print.gif" border="0" /></a></td>
		      			<td align="center"><?//=$cancel_btn?>&nbsp;<?//=$exchange_btn?>&nbsp;<?//=$escrow_btn?></td>
		      			<td width="30%" align="right"><?//=$tax_btn?></td> -->

						<td width="30%"><a href="javascript:window.print()" class="btn_type2">프린트 하기</a></td>
		      			<td align="center"><input onClick="javascript:orderCancel('<?=$orderid?>','');" type="button" value="주문 취소 요청" class="btn_type6">&nbsp;<input onClick="javascript:orderexchange('<?=$orderid?>','');" type="button" value="교환요청" class="btn_type7">&nbsp;<?=$escrow_btn?></td>
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