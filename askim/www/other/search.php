<?php
$code_page='other';
$code = 'search';
$ThisPageNum = 2;
include_once $_SERVER["DOCUMENT_ROOT"]."/head.php";
?>



<div class="search_con basic_cont" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400" data-aos-once="true">
	<? $stype = 'bbs'; include $_SERVER['DOCUMENT_ROOT'].'/twcenter/module/search_list.php'; // 전체검색 페이지 ?>
</div>



<? include $_SERVER['DOCUMENT_ROOT']."/foot.php" ?>
