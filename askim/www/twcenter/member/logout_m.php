<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/mem_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/site_info.php';

if(!empty($wiz_session['id'])){
	session_unset();
	session_destroy();
	makeCookie("uniq_id","");
}

$go_url = "/m/main.php";
if($mem_info['out_url'] != "") $go_url = "/m/main.php";

?>
<script>
<? if($site_inf['autologin_use'] == 'Y'){?>
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