<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "") {
	if(empty($wiz_session['id']) && $_COOKIE['member_guest'] != "guest") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_check.php";
	else include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_form.php";
}
else if($ptype == "form")       include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_form.php";
else if(!strcmp($ptype, "pay")) include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_pay.php";
else if(!strcmp($ptype, "ok"))  include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_ok.php";

?>