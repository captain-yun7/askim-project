<?
//ptype : 목록, 보기, 쓰기, 저장
//mode 	: 받은 쪽지, 보낸 쪽지
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/msg_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if(empty($wiz_session['id'])) error("로그인 후 이용해주세요.", "/");

include $_SERVER['DOCUMENT_ROOT']."/twcenter/message/member.php";

?>