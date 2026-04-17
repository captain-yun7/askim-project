<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/mem_info.php';

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/myout.php';
?>