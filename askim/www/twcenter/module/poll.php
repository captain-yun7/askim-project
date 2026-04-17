<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/poll/list.php";
else if($ptype == "view") include $_SERVER['DOCUMENT_ROOT']."/twcenter/poll/view.php";
else if($ptype == "save") include $_SERVER['DOCUMENT_ROOT']."/twcenter/poll/save.php";
else if($ptype == "passwd") include $_SERVER['DOCUMENT_ROOT']."/twcenter/poll/passwd.php";

update_page("POLL");
?>