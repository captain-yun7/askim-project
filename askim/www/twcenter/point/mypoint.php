<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/point_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

// 전체포인트
$my_point = number_format(get_point($wiz_session['id']));

// 상단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_head.php';

$sql = "select wp.idx
				from wiz_point as wp left join wiz_bbs as wb on wp.bidx = wb.idx
				where wp.memid = '$wiz_session['id']' $ptype_sql $mode_sql $search_sql
				order by wp.wdate desc";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$rows = 20;
$lists = 5;
if(!$page) $page = 1;
$page_count = ceil($total/$rows);
$start = ($page-1)*$rows;
$no = $total-$start;

$sql = "select wp.*, wb.code, wb.subject
				from wiz_point as wp left join wiz_bbs as wb on wp.bidx = wb.idx
				where wp.memid = '$wiz_session['id']' $ptype_sql $mode_sql $search_sql
				order by wp.wdate desc limit $start, $rows";
$result = query($sql) or error("sql error");

while(($row = sql_fetch_arr($result))){
	
	$memo = $row['memo'];
	$point = number_format($row['point']);
	$wdate = $row['wdate'];

	if(!empty($row['subject'])) {
		if(strlen($row['subject']) > 30) $subject = cut_str($row['subject'], 30)."...";
		else $subject = $row['subject'];
		$memo .= "&nbsp;(".$subject.")";
	}
	
	// 글목록파일
	@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_body.php';

	$no--;
	$idx++;
}

// 하단파일
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_foot.php';

?>