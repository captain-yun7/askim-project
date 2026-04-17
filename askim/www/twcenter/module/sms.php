<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

$skin_dir = "/twcenter/sms";

if(empty($stype) || !strcmp($stype, "input")) include $_SERVER['DOCUMENT_ROOT']."/twcenter/sms/input.php";
else if(!strcmp($stype, "send")) include $_SERVER['DOCUMENT_ROOT']."/twcenter/sms/send.php";

?>