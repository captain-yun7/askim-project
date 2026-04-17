<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$code_page  = sqlfilter($code_page);
$_GET['po'] = sqlfilter($pos);

include_once $_SERVER['DOCUMENT_ROOT'].'/head.php';
?>

<?$code=$code; include $_SERVER['DOCUMENT_ROOT'].'/twcenter/module/bbs.php'; // 게시판 ?>


<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/foot.php";
?>