<?
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$sql = "update wiz_siteinfo set msg_skin='$msg_skin', msg_url = '$msg_url'";
query($sql) or error("sql_error");

complete("쪽지설정이 저장되었습니다.","message_config.php");

?>