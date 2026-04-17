<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if(!empty($send_name)) $param = "send_name=$send_name&orderid=$orderid&order_guest=$order_guest";

if($oper_info['deliveryType'] == "P"){
	include_once "order_list_p.php";
} else {
	include_once "order_list_om.php";
}
?>