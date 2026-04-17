<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/site_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';
?>
<link href="<?=$skin_dir?>/style.css" rel="stylesheet" type="text/css">
<script Language="JavaScript">
<!--

//쪽지보내기 팝업
function popMessage( re_id, re_name ) {
<?php
if(!check_point($wiz_session['id'], $site_info['msg_point'])) {
?>
	alert("<?=$site_info['point_msg']?>");
<?php
} else {
?>
   var url = "/twcenter/message/pop_message.php?ptype=input&mode=<?=$mode?>&submode=insert&re_id=" + re_id + "&re_name=" + re_name;
   window.open(url, "PopMessage", "width=500, height=350, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
<?php
}
?>
}

//쪽지보내기 팝업
function popMessage2( seluser ) {
<?php
if(!check_point($wiz_session['id'], $site_info['msg_point'])) {
?>
	alert("<?=$site_info['point_msg']?>");
<?php
} else {
?>
   var url = "/twcenter/message/pop_message.php?ptype=input&mode=<?=$mode?>&submode=insert&seluser=" + seluser;
   window.open(url, "PopMessage", "width=500, height=350, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
<?php
}
?>
}
//-->
</script>

<?php
// 검색 파라미터
$param = "mode=$mode";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($ptype != "") $param .= "&ptype=$ptype";

// 상단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/friend_head.php';

// 게시물 쿼리
if($searchopt) $search_sql = " and $searchopt like '%$searchkey%' ";

$sql = "select wf.idx, wm.id, wm.name, wm.wdate from wiz_friend as wf left join wiz_member as wm on wf.frdid=wm.id where wf.myid = '$wiz_session['id']' and wm.id != '' $search_sql order by wf.idx desc";
$result = query($sql) or error("sql_error");
$total = sql_fetch_row($result);

$idx = 0;
if($rows == "") $rows = "20";
if($lists == "") $lists = "5";

$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$sql = "select wf.idx, wm.id, wm.name, wm.wdate from wiz_friend as wf left join wiz_member as wm on wf.frdid=wm.id where wf.myid = '$wiz_session['id']' and wm.id != ''  $search_sql order by wf.idx desc limit $start, $rows";
$result = query($sql) or error("sql_error");

while($row = sql_fetch_arr($result)){

	$new_icon="";
	
	$wdate = str_replace("-",".",substr($row['wdate'], 0, 10));
	
	$name = $row['name'];
	$id = $row['id'];
	//$message_btn = "<a href='$PHP_SELF?mode=member&ptype=input&submode=insert&re_name=$name&re_id=$id'>쪽지보내기</a>";
	$message_btn = "<span onClick=\"popMessage( '$id', '$name' )\" style='cursor:pointer'>쪽지보내기</span>";
	
	// 글목록파일
	@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/friend_body.php';

	$no--;
	$idx++;

}

if($total <= 0){
	echo "<tr><td height='25' align='center' colspan='20'>등록된 친구가 없습니다.</td></tr>";
}

$grp_btn = "<img src='".$skin_dir."/image/btn_msg.gif' onClick='grpMessage()' style='cursor:hand'>";
$del_btn = "<img src='".$skin_dir."/image/btn_fdel.gif' onClick='delFriend()' style='cursor:hand'>";

// 하단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/friend_foot.php';

?>