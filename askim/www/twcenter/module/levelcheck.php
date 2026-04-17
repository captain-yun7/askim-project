<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if($level == "") $level = 20;
if($msg == "") $msg = "접근권한이 없습니다.";
if(($wiz_session['level_value'] == "" && $wiz_session['level_value'] != "0") || $wiz_session['level_value'] > $level) error($msg,$backurl);
?>