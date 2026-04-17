<? 
include_once "../../common.php"; 

$sql="update wiz_product_inquiry set priorno='$priorno'where idx='$idx'";
query($sql) or error("sql error");
complete("진열순서를 변경하였습니다.","product_inquiry.php?page=$page");
?>