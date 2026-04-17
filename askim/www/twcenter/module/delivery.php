<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(mobile_check()){
	if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/delivery_m.php";
	if($ptype == "input") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/delivery_input_m.php";
} else {
	if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/delivery.php";
	if($ptype == "input") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/delivery_input.php";
}

if($ptype == "save") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/delivery_save.php";
?>