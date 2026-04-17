<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/wish_list.php";
update_page("WISHLIST");
?>