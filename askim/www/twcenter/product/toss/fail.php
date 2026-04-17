<?
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";


	if(mobile_check() == true) {
		$go_url = "/m/sub/order_ok.php";
	} else {
		$go_url = "/".$prd_info['order_url'];
	}
	$rescode = $_GET['code'];
	$resmsg = $_GET['message'];
?>

<form name="frm" action="<?=$go_url?>" method="post" target="_blank">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="rescode" value="<?=$rescode?>">
<input type="hidden" name="resmsg" value="<?=$resmsg?>">
<input type="hidden" name="pay_method" value="<?=$order_info->pay_method?>">
<input type="hidden" name="ptype" value="ok">
</form>
<script>document.frm.submit();</script> 

?>