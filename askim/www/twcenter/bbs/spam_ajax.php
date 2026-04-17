<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$sql_spam = "select * from wiz_siteinfo";
$result_spam = query($sql_spam);
$row_spam = sql_fetch_arr($result_spam);

$bbs_spam_value = md5($row_spam['site_name']);

echo $bbs_spam_value;

?>