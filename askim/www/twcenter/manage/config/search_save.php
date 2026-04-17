<?
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$sql = "update wiz_siteinfo set search_skin='$search_skin', search_url = '$search_url'";
query($sql) or error("sql_error");

complete("통합검색 설정이 저장되었습니다.","search_config.php");

?>