<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 검색 파라미터
$param = "mode=$mode";
if($idx != "")       $param .= "&idx=$idx";
if($page != "")      $param .= "&page=$page";
if($category != "")  $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

if(!strcmp($re_id, $wiz_session['id'])) error("자기자신에게는 쪽지를 보낼 수 없습니다.");

// 버튼설정
$list_btn    = "<a href=$PHP_SELF?ptype=list&$param><img src='$skin_dir/image/btn_list.gif' border='0'></a>";
$confirm_btn = "<input type='image' src='$skin_dir/image/btn_confirm.gif' border='0'>";
$cancel_btn  = "<img src='$skin_dir/image/btn_cancel.gif' border='0' onClick='history.go(-1)' style='cursor:hand'>";
$close_btn   = "<img src='$skin_dir/image/btn_close.gif' border='0' onClick='self.close()' style='cursor:hand'>";

// 글 작성
$se_id = $wiz_session['id'];
$se_name = $wiz_session['name'];

if(!strcmp($submode, "reply")) {
	$sql = "select * from wiz_message where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$msg_info2 = sql_fetch_arr($result);
	$re_id   = $msg_info2['se_id'];
	$re_name = $msg_info2['se_name'];
}	

if(empty($re_id) && !empty($seluser)) {
	
	$sellist = explode("|", $seluser);
	
	for($ii = 0; $ii < count($sellist); $ii++) {
		$id = $sellist[$ii];
		if(!empty($id)) {
			$sql = "select name from wiz_member where id = '$id'";
			$result = query($sql) or error("sql_error");
			$row = sql_fetch_arr($result);
			
			$re_user .= $row['name']."(".$id.")";
			if($ii < count($sellist) - 2) $re_user .= ", ";
		}
	}
		
} else if(!empty($re_id)) {
	$re_user = $re_name."(".$re_id.")";
}

// 입력스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/input.php';

?>