<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/list.php";
else if($ptype == "view" && $viewType == "M") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view.php";
else if($ptype == "view" && $viewType == "I") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view_v.php";
else if($ptype == "view_test") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view_test.php";
else if($ptype == "view_ok") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view_ok.php";

//update_page("PRD");
//update_page_order("ORDER");
?>