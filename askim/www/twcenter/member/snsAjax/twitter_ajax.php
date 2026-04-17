<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$user_Id     = $_POST['user_Id'];
$user_Name   = $_POST['user_Name'];
$sns_Login   = $_POST['sns_Login'];
$login_Type  = $_POST['login_Type'];

$tt_sql = "select * from wiz_member where sns_id='{$user_Id}' and sns_login='{$sns_Login}' ";
$tt_cnt = sql_fetch_rows($tt_sql);

if($tt_cnt > 0){
	echo "ok";
}
?>