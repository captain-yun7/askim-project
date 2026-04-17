<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($oper_info['sns_login_use'] == "Y") {

	//if($ptype == "" || $ptype == "agree") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_agree.php";
	if($ptype == "" && $sns_Login == "")      include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_list.php";
	else if($ptype == "" && $sns_Login != "") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_agree.php";
	else if($ptype == "agree")                include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_agree.php";
	else if($ptype == "input")                include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_input.php";
	else if($ptype == "save")                 include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_save.php";
	else if($ptype == "ok")                   include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_ok.php";
	//update_page("MEM_JOIN");

} else {

	if($ptype == "" || $ptype == "agree") include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_agree.php";
	else if($ptype == "input")            include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_input.php";
	else if($ptype == "save")             include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_save.php";
	else if($ptype == "ok")               include $_SERVER['DOCUMENT_ROOT']."/twcenter/member/join_ok.php";
	//update_page("MEM_JOIN");

}
?>