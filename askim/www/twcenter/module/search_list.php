<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if(empty($ptype) || !strcmp($ptype, "list")) include $_SERVER['DOCUMENT_ROOT']."/twcenter/search/list.php";

update_page("SEARCH");
?>