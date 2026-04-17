<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

//웹 취약점 대비 - 파라미터 필터링 2022-06-30 정나혜
foreach($_REQUEST as $k => $v){
	${$k} = xss_clean($v);
}

$ptype_array = array("", "input", "list", "view", "passwd", "save");
$ptype = isset($ptype) ? $ptype : ''; 
if(!in_array($ptype, $ptype_array)) {
	error('정상적으로 값이 넘어오지 않았습니다.');
	exit;
}

$mode_array = array("", "insert", "modify", "view", "reply", "delete", "delbbs", "comment", "comment_e", "delco", "recom", "prino", "pribbs","statuschg");
$mode = isset($mode) ? $mode : ''; 
if(!in_array($mode, $mode_array)) {
	error('정상적으로 값이 넘어오지 않았습니다.');
	exit;
}

if($ptype == "" || $ptype == "list") include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/list.php";
else if($ptype == "view")            include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/view.php";
else if($ptype == "input")           include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/input.php";
else if($ptype == "passwd")          include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/passwd.php";
else if($ptype == "save")            include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/save.php";
?>