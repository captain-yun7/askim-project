<? 
include_once "../../common.php"; 

$sql="select *from wiz_product_inquiry order by priorno desc limit 1";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);

$priorno = $row['priorno']+10;

$sql_insert="insert into wiz_product_inquiry (prdname,priorno) values ('$inquiry_product','$priorno')";
query($sql_insert) or error("sql error");
?>
<script>
/*
window.opener.location.reload();
window.close();
*/
location.href="./product_inquiry.php";
</script>