<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

/*
작업자명	: 이상민
작업일시	: 2024-04-05
작업내용	: 모듈의 코드를 변경
*/

// 폼메일정보
$sql = "select count(*) as total from wiz_forminfo where code = '$form_code'";
$row = sql_fetch($sql);

// 생성되지 않은 폼메일인경우
if($row['total'] <= 0){
	$msg = "<font color=red><b>".$form_code."</b></font> 폼메일은 아직 생성되지 않았습니다.";
	echo "<table align=center><tr><td height=25>&nbsp;&nbsp;".$msg."&nbsp;&nbsp;</td></tr></table>";
} else {
	if($ptype == "" || $ptype == "form") include $_SERVER['DOCUMENT_ROOT']."/twcenter/form/input.php";
	else if($ptype == "save") include $_SERVER['DOCUMENT_ROOT']."/twcenter/form/save.php";
}
?>