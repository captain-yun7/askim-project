<? 
include_once "../../common.php"; 

$sql_delete="delete from wiz_product_inquiry where idx='$idx'";
query($sql_delete) or error("sql error");

?>
<script>
location.href="./product_inquiry.php";
</script>