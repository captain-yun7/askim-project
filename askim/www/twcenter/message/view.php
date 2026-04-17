<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 검색 파라미터
$param = "mode=$mode";
if($idx != "") $param .= "&idx=$idx";
if($page != "") $param .= "&page=$page";
if($category != "") $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";


// 게시물 정보
$sql = "select * from wiz_message where idx = '$idx'";
$result = query($sql) or error("sql_error");
$msg_row = sql_fetch_arr($result);

$se_id		= $msg_row['se_id'];
$se_name	= $msg_row['se_name'];
$re_id		= $msg_row['re_id'];
$re_name	= $msg_row['re_name'];
$subject	= $msg_row['subject'];
$content	= $msg_row['content'];
$upfile		= $msg_row['upfile'];
$stauts		= $msg_row['stauts'];
$wdate 		= str_replace("-",".",substr($msg_row['wdate'], 0, 10));

if($msg_row['ctype'] != "H"){
	$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);
}

// 첨부파일 이미지인경우 보여주기
if(img_type(WIZHOME_PATH."/data/message/$se_id/".$msg_row['upfile'])) {
	$upimg = "<a href=javascript:viewImg('$msg_row['upfile']');><img src='/twcenter/data/message/$se_id/".$msg_row['upfile']."' border='0' name='wiz_target_resize'></a><br>";
	$_ResizeCheck = true;
}

// 이미지 리사이즈를 위해서 처리하는 부분
if(strpos(strtolower($content), "<img") !== false || $_ResizeCheck == true) {
	$content = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $content);
	$content = "<table border=0 cellspacing=0 cellpadding=0 style='width:".$img_width."px;height:0px;' id='wiz_get_table_width'>
							<col width=100%></col>
							<tr>
								<td><img src='' border='0' name='wiz_target_resize' width='0' height='0'></td>
							</tr>
						</table>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<col width=100%></col>
							<tr><td valign=top>".$content."</td></tr>
						</table>";
	$_ResizeCheck = true;
}

if(!strcmp($mode, "receive") && !strcmp($wiz_session['id'], $re_id)) {
	// 쪽지 수신
	$sql = "update wiz_message set status = 'Y' where idx = '$idx'";
	$result = query($sql) or error("sql_error");
}


// 버튼설정
$delete_btn = "<a href='$PHP_SELF?ptype=passwd&$param'><image src='$skin_dir/image/btn_delete.gif' border='0'></a>";
$close_btn = "<img src='$skin_dir/image/btn_close.gif' border='0' onClick='opener.document.location.reload();self.close()' style='cursor:hand'>";

if(!strcmp($mode, "receive") || !strcmp($re_id, $wiz_session['id'])) {
	if(!check_point($wiz_session['id'], $site_info['msg_point'])) {
		$reply_btn = "<image src='$skin_dir/image/btn_reply.gif' border=0 onClick=\"alert('$site_info['point_msg']')\" style='cursor:pointer'>";
	} else {
		$reply_btn = "<a href='$PHP_SELF?ptype=input&submode=reply&$param'><image src='$skin_dir/image/btn_reply.gif' border=0></a>";
	}

}

// 첨부파일
if($msg_row['upfile'] != "") $upfile = "<a href='/twcenter/message/down.php?se_id=$se_id&idx=$idx&no=1'>$msg_row['upfile_name']</a>";

if(!strcmp($mode, "receive")) $id_sql = " and re_id = '$wiz_session['id']' and re_status != 'N' ";
else if(!strcmp($mode, 'send')) $id_sql = " and se_id = '$wiz_session['id']' and se_status != 'N' ";

//이전 쪽지
$sql = "select idx from wiz_message where idx > '$idx' $id_sql order by idx asc limit 1";
$result = query($sql) or error("sql_error");
$row = sql_fetch_arr($result);

if(!empty($row['idx'])) $pre_msg = "<a href='$PHP_SELF?mode=$mode&idx=$row['idx']'>이전쪽지</a>";	
else $pre_msg = "";

//다음 쪽지
$sql = "select idx from wiz_message where idx < '$idx' $id_sql order by idx desc limit 1";
$result = query($sql) or error("sql_error");
$row = sql_fetch_arr($result);

if(!empty($row['idx'])) $next_msg = "<a href='$PHP_SELF?mode=$mode&idx=$row['idx']'>다음쪽지</a>";
else $next_msg = "";

// 뷰스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/view_head.php';
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/view_foot.php';

view_img_resize();

?>