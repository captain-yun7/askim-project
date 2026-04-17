<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "myout") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/myout.php";
else if($ptype == "save")  include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/myout_save.php";
?>