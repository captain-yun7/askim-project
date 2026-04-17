<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';
?>
<link href="<?=$skin_dir?>/style.css" rel="stylesheet" type="text/css">
<script Language="JavaScript">
<!--

//쪽지보기 팝업
function popMessage( idx ) {
	
   var url = "/twcenter/message/pop_message.php?mode=<?=$mode?>&idx=" + idx;
   window.open(url, "PopMessage", "width=700, height=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");

}

//-->
</script>

<?php
// 검색 파라미터
$param = "mode=$mode";
if($category != "") $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

if(!strcmp($mode, "send")) {
	$date_title = "보낸날짜";
	$name_title = "받은이";
	$search_opt = "re_";
	$search_opt2 = "받은이";
	$status_sql = " and se_status != 'N' ";
} else {
	$date_title = "받은날짜";
	$name_title = "보낸이";
	$search_opt = "se_";
	$search_opt2 = "보낸이";
	$status_sql = " and re_status != 'N' ";
}
// 상단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_head.php';

// 게시물 쿼리

if($searchopt) $search_sql = " and $searchopt like '%$searchkey%' ";

if(!strcmp($mode, "send"))
	$sql = "select * from wiz_message where se_id = '$wiz_session['id']' $search_sql $status_sql order by idx desc";
else 
	$sql = "select * from wiz_message where re_id = '$wiz_session['id']' $search_sql $status_sql order by idx desc";

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

if(!strcmp($mode, "send"))
	$sql = "select * from wiz_message where se_id = '$wiz_session['id']' $search_sql $status_sql order by idx desc limit $start, $rows";
else 
	$sql = "select * from wiz_message where re_id = '$wiz_session['id']' $search_sql $status_sql order by idx desc limit $start, $rows";

$result = query($sql) or error("sql_error");

while($row = sql_fetch_arr($result)){

	$new_icon="";
	
	if(!strcmp($mode, "send")) $name = $row['re_name'];
	else $name = $row['se_name'];
	$wdate = str_replace("-",".",substr($row['wdate'], 0, 10));
	
	$content = $row['content'];
	
	if(!strcmp($row['status'], "Y")) {
		$status = "읽음";
		$new_icon = "";
	}	else {
		$status = "읽지않음";
		$new_icon = "<img src='$skin_dir/image/new.gif' border='0' align='absmiddle'>";				// new
	}

	//$subject = "<a href='$PHP_SELF?ptype=view&idx=$row['idx']&page=$page&$param'>$row['subject']</a>";		// subject
	$subject = "<span onClick=\"popMessage('$row['idx']')\" style='cursor:pointer'>$row['subject']</span>";		// subject
	
	if(empty($new_icon)) {
		$wdate_list = explode(".",$wdate);
		$wtime = mktime(0,0,0,$wdate_list[1],$wdate_list[2],$wdate_list[0]);
		if(($ttime-$wtime)/86400 <= $bbs_info['newc']) 	$new_icon = "<img src='$skin_dir/image/new.gif' border='0' align='absmiddle'>";				// new
	}

	if(file_exists(WIZHOME_PATH."/data/message/".$row['se_id']."/".$row['upfile']) && !empty($row['upfile'])) $upfile = "○";
	else $upfile = "×";
	//$upfile = "/twcenter/data/message/".$row['se_id']."/".$row['upfile'];							// img

	// 글목록파일
	@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_body.php';

	$no--;
	$idx++;

}

if($total <= 0){
	echo "<tr><td height='25' align='center' colspan='20'>등록된 쪽지가 없습니다.</td></tr>";
}

// 하단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_foot.php';

?>