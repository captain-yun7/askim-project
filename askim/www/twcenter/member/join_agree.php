<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

if(mobile_check() == true){
	$reurl = "/m/";
}else{
	$reurl = "/";
}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if($site_info['namecheck_use'] != "Y"){
	$namecheck_start = "<!--";
	$namecheck_end = "-->";
} else {
	if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
		error("메뉴를 통하여 접근해주시기 바랍니다.", $reurl);
	}
}
$login_Type = $_POST['login_Type'];

if($login_Type == "sns"){
	sns_Login($login_Type);
}


include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/join_agree.php";
?>