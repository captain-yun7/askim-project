<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$user_Id    = $_POST['user_Id'];
$user_Name  = $_POST['user_Name'];
$user_Email = $_POST['user_Email'];
$sns_Login  = $_POST['sns_Login'];

$nh_sql = "select * from wiz_member where sns_id='{$user_Id}' and sns_login='{$sns_Login}' ";
$nh_row = sql_fetch($nh_sql);

if($nh_row['id']){
	echo "ok|".$nh_row['id'];
}
?>