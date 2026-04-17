<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "myinfo") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/myinfo.php";
else if($ptype == "save")  include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/myinfo_save.php";
//update_page("MEM_INFO");
?>