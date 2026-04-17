<?php
$code_page = 'portfolio';
$ThisPageNum = 1;
$page_code = "portfolio";
$code = "portfolio";
$page_type = "bbs";
include_once $_SERVER['DOCUMENT_ROOT'].'/head.php';
?>


<div class="portfolio_cont" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400" data-aos-once="true">
	<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/bbs.php"; // 게시판 ?>
</div>



<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/foot.php";
?>
