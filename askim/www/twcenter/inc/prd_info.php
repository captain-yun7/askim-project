<?php
$sql = "select * from wiz_prdinfo";
$prd_info = sql_fetch($sql);

if(!empty($f_path) && strpos($f_path, "/twcenter/product2/") !== false) {
	$skin_dir = "/twcenter/product2/";
} else { 
	$skin_dir = "/twcenter/product/";
}

$prdimg_max = 10; // 상품사진 : 최대12까지가능
$prdfile_max = 5; // 첨부파일 : 최대5까지가능

?>