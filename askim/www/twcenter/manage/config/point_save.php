<?
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$sql = "update wiz_siteinfo set point_skin='$point_skin', point_url = '$point_url'";
query($sql) or error("sql_error");

complete("설정이 저장되었습니다.","point_config.php");

?>