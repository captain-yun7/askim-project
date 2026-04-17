<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if(!empty($wiz_session['id'])){

	session_unset();
	session_destroy();

	makeCookie("uniq_id","");
	set_cookie('w_id', '', 0);
	set_cookie('w_auto', '', 0);
}

$go_url = "/";
if($mem_info['out_url'] != "") $go_url = "/".$mem_info['out_url'];
Header("Location: $go_url");

?>
<script>
<? if($site_info['autologin_use'] == 'Y'){?>
	localStorage.setItem("id", "");
	localStorage.setItem("passwd", "");
	localStorage.setItem("name", "");
	localStorage.setItem("email", "");
	localStorage.setItem("hphone", "");
	localStorage.setItem("tphone", "");
	localStorage.setItem("level", "");
	localStorage.setItem("level_value", "");
	localStorage.setItem("auto_login", "N");
<? } ?>
location.href="<?=$go_url?>";
</script>