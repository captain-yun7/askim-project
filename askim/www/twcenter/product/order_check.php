<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

//$prev = WAY_GURL;
$prev = WAY_GURL."?product_idx=$product_idx";

// 로그인페이지 
$order = "true"; 
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/login.php";

?>
