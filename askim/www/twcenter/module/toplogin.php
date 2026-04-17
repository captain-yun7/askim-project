<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$login_menu = "<span class='material-symbols-outlined'>login</span>로그인"; $logout_menu = "<span class='material-symbols-outlined'>logout</span>로그아웃";
if(!empty($mem_info['login_img'])) $login_menu = "<img src='/".$mem_info['login_img']."' border='0'>";
if(!empty($mem_info['logout_img'])) $logout_menu = "<img src='/".$mem_info['logout_img']."' border='0'>";

/*
if($wiz_session['id'] == "")
	echo "<a href='".$login_url."?prev=".$PHP_SELF."'>".$login_menu."</a>";
else
	echo "<a href='".$logout_url."'>".$logout_menu."</a>";
*/

if(trim($wiz_session['id']) == ""){
	if($PHP_SELF == "/comm/auto_bbs.php") {
		echo "<a href='".$login_url."?prev=".urlencode($PHP_SELF."?".$_SERVER['REQUEST_URI'])."'>".$login_menu."</a>";		
	} else if($_SERVER['QUERY_STRING']) {
		$_SERVER['QUERY_STRING'] = urldecode($_SERVER['QUERY_STRING']);

		if(preg_match("/member\/login\.php/i", $PHP_SELF)){
			$trans_query_string = $_GET['prev'];
			echo "<li class='login'><a href='".$login_url."?".urlencode($trans_query_string ?? "")."'>".$login_menu."</a></li>";
		} else {
			echo "<li class='login'><a href='".$login_url."?prev=".urlencode($PHP_SELF."?".$_SERVER['QUERY_STRING'])."'>".$login_menu."</a></li>";
		}
	} else {
		echo "<a href='".$login_url."?prev=".urlencode($PHP_SELF)."'>".$login_menu."</a>";		
	}
}else{
	echo "<a href='".$logout_url."'>".$logout_menu."</a>";
}
?>