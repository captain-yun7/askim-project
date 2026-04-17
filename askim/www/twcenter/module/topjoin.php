<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$join_menu = "<span class='material-symbols-outlined'>person</span>회원가입"; $myinfo_menu = "<span class='material-symbols-outlined'>person</span>마이페이지";
if(!empty($mem_info['join_img'])) $join_menu = "<img src='/".$mem_info['join_img']."' border='0'>";
if(!empty($mem_info['myinfo_img'])) $myinfo_menu = "<img src='/".$mem_info['myinfo_img']."' border='0'>";

if($wiz_session['id'] == "")
   echo "<a href='".$join_url."'>".$join_menu."</a>";
else
   echo "<a href='/member/mypage.php'>".$myinfo_menu."</a>";
?>


