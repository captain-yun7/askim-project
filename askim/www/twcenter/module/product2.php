<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

//웹 취약점 대비 - 파라미터 필터링 2022-06-30 정나혜
foreach($_REQUEST as $k => $v){
	${$k} = xss_clean($v);
}

$f_path = "/twcenter/product2/";

if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product2/list.php";
else if($ptype == "view") include $_SERVER['DOCUMENT_ROOT']."/twcenter/product2/view.php";

//update_page("PRD");
?>