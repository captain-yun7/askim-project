<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$param = "send_name=$send_name&orderid=$orderid&order_guest=$order_guest&page=$page";

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = query($sql);
$order_info = sql_fetch_arr($result);

$status = $order_info['status'];

if(!isset($order_info['del_com'])) $order_info['del_com'] = '';
list($del_com,$del_code) = explode("|",$order_info['del_com']);
if(!empty($order_info['del_com'])){

	$query = "select * from wiz_delivery_company where idx='{$del_code}' ";
	$qresult = query($query);
	$_delivery = sql_fetch_arr($qresult);

	$del_trace = $_delivery['del_trace'];
	$del_com   = $_delivery['del_com'];

} else {

	$del_trace = $oper_info['del_trace'];
	$del_com   = $oper_info['del_com'];
}

// 주문취소 버튼
get_cancel_btn();

// 교환요청 버튼
get_exchange_btn();

// 에스크로 버튼
//get_escrow_btn();

// 세금계산서 버튼
get_tax_btn();

include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_info.php";			// 주문정보

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
<body topmargin="0" leftmargin="0" <?php if($print == "ok"){ ?>onLoad="window.print();" <?php } ?>>
<table width="100%" cellpadding=0 cellspacing=0>
  <tr>
    <td>

      <table width="100%">
        <tr><td><?php echo $ordinfo ?></td></tr>
	      <tr><td height="30"></td></tr>
	      <?php if($print != "ok"){ ?>
	      <tr>
		      <td align="center">
		      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		      		<tr>
		      			<td width="30%" style="text-align:left;">
						 <input type="button" onClick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>?ptype=list&<?php echo $param ?>'" value="리스트" class="btn_type2"></td>
		      			<!-- <td align="center"><?=$cancel_btn?>&nbsp;<?=$exchange_btn?>&nbsp; <?=$escrow_btn?></td>-->
						<?php
								if($order_info['status'] =="DC"){ 
							?>
								<td align="center"><input type="button" onclick="document.location='/customer/qna.php';" value="반품/교환/환불신청" class="btn_type3"></td>
							<? } else { ?>
								<td align="center"><?php echo $cancel_btn ?></td>
							<?php } ?>
		      			<td width="30%" align="right"><!-- <?php echo $tax_btn ?> --></td>
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