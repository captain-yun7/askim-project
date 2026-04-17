<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/poll_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$sql = "select count(idx) as all_total from wiz_poll where code = '$poll_code'";
$result = query($sql) or error("sql_error");
$row = sql_fetch_obj($result);
$all_total = $row->all_total;

// 목록보기 권한체크
if($lpermi < $mem_level) error($poll_info['permsg'],$poll_info['perurl']);

// 상단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_head.php";

if(empty($poll_info['datetype_list'])) $poll_info['datetype_list'] = "%Y-%m-%d";

if($searchopt != "") $search_sql .= " and $searchopt like '%$searchkey%' ";
$sql = "select * from wiz_poll where code = '$code' $search_sql order by idx desc";
$result = query($sql) or error("sql_error");
$total = sql_fetch_row($result);

$rows = 20;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$sql = "select *, date_format(sdate, '".$poll_info['datetype_list']."') as tmp_sdate, date_format(edate, '".$poll_info['datetype_list']."') as tmp_edate from wiz_poll where code = '$code' $search_sql order by idx desc limit $start, $rows";
$result = query($sql) or error("sql_error");

while($row = sql_fetch_arr($result)){
	
	if($poll_info['subject_len'] > 0) $row['subject'] = cut_str($row['subject'], $poll_info['subject_len']);

	$subject = "<a href=".$PHP_SELF."?ptype=view&idx=".$row['idx'].">".$row['subject']."</a>";
	if($row['polluse'] == "Y" && $row['edate'] >= date('Y-m-d')) $status = "진행중"; else $status = "진행종료";
	$wdate = $row['wdate'];
	$sdate = $row['tmp_sdate'];
	$edate = $row['tmp_edate'];
	$cnt = $row['cnt'];
	
	// 글목록파일
	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_body.php";

	$no--;
}

// 하단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_foot.php";

?>
