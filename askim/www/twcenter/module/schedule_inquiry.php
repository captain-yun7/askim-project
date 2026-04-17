<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/list_inquiry.php";
else if($ptype == "view") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/view_inquiry.php";

update_page("SCH");
?>