<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 버튼설정
$confirm_btn = "<input type='image' src='$skin_dir/image/btn_confirm.gif' border='0'>";
$cancel_btn = "<img src='$skin_dir/image/btn_cancel.gif' border='0' onClick='history.go(-1);' style='cursor:hand'>";

$input_passwd = "쪽지를 삭제하시겠습니까?";
$ptype = "save";

@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/passwd.php';
?>