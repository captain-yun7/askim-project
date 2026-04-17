<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<?

/*
작업자명	: 이상민
작업일시	: 2024-04-05
작업내용	: 모듈의 코드를 변경
*/

$sql = "select * from wiz_page where code='$page_code'";
$row = sql_fetch($sql);

echo $row['content'];
?>