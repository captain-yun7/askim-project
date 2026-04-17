<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(empty($ptype) || !strcmp($ptype, 'view')) include $_SERVER['DOCUMENT_ROOT'].'/twcenter/message/view.php';
else if(!strcmp($ptype, 'input')) include $_SERVER['DOCUMENT_ROOT'].'/twcenter/message/input.php';
else if(!strcmp($ptype, 'passwd')) include $_SERVER['DOCUMENT_ROOT'].'/twcenter/message/passwd.php';
else if(!strcmp($ptype, 'save')) include $_SERVER['DOCUMENT_ROOT'].'/twcenter/message/save.php';

?>