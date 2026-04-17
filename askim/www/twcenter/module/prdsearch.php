<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/search.php";
else if($ptype == "view" && $viewType == "M") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view.php";
else if($ptype == "view" && $viewType == "I") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/view_v.php";

?>