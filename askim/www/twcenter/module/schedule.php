<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/list.php";
else if($ptype == "view") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/view.php";
else if($ptype == "input") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/input.php";
else if($ptype == "passwd") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/passwd.php";
else if($ptype == "save") include $_SERVER['DOCUMENT_ROOT']."/twcenter/schedule/save.php";

update_page("SCH");
?>