<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(empty($wiz_session['id']) && empty($order_guest)) include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_list_check.php";
else {
	if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_list.php";
	else if(!strcmp($ptype, "view"))     include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_detail.php";
	else if(!strcmp($ptype, "pview"))    include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_detail_p.php";
}

?>