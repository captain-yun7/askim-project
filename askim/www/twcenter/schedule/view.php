<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/sch_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 검색 파라미터
$param = "code=$code";
if($idx != "") $param .= "&idx=$idx";
if($page != "") $param .= "&page=$page";
if($category != "") $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

if(empty($sch_info['datetype_view'])) $sch_info['datetype_view'] = "%Y-%m-%d";

// 게시물 정보
$sql = "select *, from_unixtime(wdate, '".$sch_info['datetype_view']."') as wdate from wiz_bbs where idx = '$idx'"; 


$result = query($sql) or error("sql_error");
$bbs_row = sql_fetch_arr($result);

$memid 		= $bbs_row['memid'];
$name 		= $bbs_row['name'];
$email 		= $bbs_row['email'];
$tphone 	= $bbs_row['tphone'];
$hphone 	= $bbs_row['hphone'];
$zipcode 	= $bbs_row['zipcode'];
$address 	= $bbs_row['address'];
$subject 	= $bbs_row['subject'];
$content 	= $bbs_row['content'];
$wdate 		= $bbs_row['wdate'];
$count 		= $bbs_row['count'];
$recom 		= $bbs_row['recom'];
$ip 			= $bbs_row['ip'];

$addinfo1 = $bbs_row['addinfo1'];
$addinfo2 = $bbs_row['addinfo2'];
$addinfo3 = $bbs_row['addinfo3'];
$addinfo4 = $bbs_row['addinfo4'];
$addinfo5 = $bbs_row['addinfo5'];

if($bbs_row['category'] != "") $catname = "[".$bbs_row['category']."]";
if($bbs_row['ctype'] != "H"){
	$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);
}

$_ResizeCheck = false;
// 첨부파일 이미지인경우 보여주기
if(strcmp($sch_info['imgview'], "N")) {

	for($ii = 1; $ii <= 12; $ii++) {
		if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row["upfile".$ii])) {
			${upimg.$ii} = "<div align='".$sch_info['img_align']."'><a href=javascript:viewImg('".$bbs_row["upfile".$ii]."');><img src='/twcenter/data/bbs/$code/M".$bbs_row["upfile".$ii]."' border='0' name='wiz_target_resize'></a></div>";
			$_ResizeCheck = true;
		}
	}
}

// 이미지 리사이즈를 위해서 처리하는 부분
$content = stripslashes($content);
if(strpos(strtolower($content), "<img") !== false || $_ResizeCheck == true) {	
	// 이미지 리사이즈를 위해서 처리하는 부분
	$content = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $content);
	$content = "<div style='width:".$sch_info['mimgsize']."px;height:0px;' height=1 id='wiz_get_table_width'>
								<img src='' border='0' name='wiz_target_resize' width='1' height='1' alt='' ></div>
							<div>".$content."</div>";
	$_ResizeCheck = true;	
}

// 글보기 권한체크
if($rpermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

// 비밀글인경우 체크
if($bbs_row['privacy'] == "Y"){

	$sql = "select idx from wiz_bbs where code='$code' and grpno='".$bbs_row['grpno']."' and passwd='$passwd'";
	$result = query($sql) or error("sql_error");
	$grp_passwd = sql_fetch_row($result);

	if(
	$mem_level == 0 ||																																				// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 												// 자신의글
	($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd) ||																// 비밀번호일치
	($wiz_session['id'] != "" && strpos($bbs_row['memgrp'],$wiz_session['id']) !== false) ||				// 그룹의글
	($grp_passwd > 0)																																					// 그룹비번
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.","?ptype=passwd&mode=view&$param");
		else  error("권한이 없습니다.","?ptype=passwd&mode=view&$param");
	}

}

// 조회수 증가
$sql = "update wiz_bbs set count = count+1 where idx = '$idx'";
$result = query($sql) or error("sql_error");

// 버튼설정
if($wpermi >= $mem_level){
	$write_btn = "<a href='$PHP_SELF?ptype=input&mode=insert&$param' class='btn_b'>글쓰기</a>";
	$modify_btn = "<a href='$PHP_SELF?ptype=input&mode=modify&$param' class='btn_w'>수정</a>";
	$delete_btn = "<a href='$PHP_SELF?ptype=passwd&mode=delete&$param' class='btn_w'>삭제</a>";
}
if($apermi >= $mem_level){
	$reply_btn = "<a href='$PHP_SELF?ptype=input&mode=reply&$param' class='btn_w'>답글</a>";
}

$list_btn = "<a href='$PHP_SELF?ptype=list&$param' class='btn_w'>리스트</a>";
if($sch_info['recom'] == "Y"){
	$recom_btn = "<a href='$PHP_SELF?ptype=save&mode=recom&$param' class='btn_w'>추천</a>";
}
// 첨부파일
if(!check_point($wiz_session['id'], $sch_info['down_point'])) {
	for($ii = 1; $ii <= 12; $ii++) {
		if($bbs_row["upfile".$ii] != "") ${"upfile".$ii}  = "<a href=\"javascript:alert('".$sch_info['point_msg']."')\">".$bbs_row["upfile".$ii."_name"]."</a>";
	}
} else {
	for($ii = 1; $ii <= 12; $ii++) {
		if($bbs_row["upfile".$ii] != "") ${"upfile".$ii}  = "<a href='/twcenter/bbs/down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row["upfile".$ii."_name"]."</a>";
	}
}

if($bbs_row['movie1'] != "") $movie1 = "<embed src='/twcenter/data/bbs/$code/".$bbs_row['movie1']."' autostart=false title='동영상1'></embed><br>";
if($bbs_row['movie2'] != "") $movie2 = "<embed src='".$bbs_row['movie2']."' autostart=false title='동영상2'></embed><br>";
if($bbs_row['movie3'] != "") $movie3 = "<embed src='".$bbs_row['movie3']."' autostart=false title='동영상3'></embed><br>";

$prev = "";
$next = "";

// 이전글
$sql = "select idx,subject from wiz_bbs where code = '$code' and prino > ".$bbs_row['prino']." order by prino asc limit 1";
$result = query($sql) or error("sql_error");
if($row = sql_fetch_arr($result)) $prev = "이전글△ <a href='$PHP_SELF?ptype=view&code=$code&idx=".$row['idx']."'>".$row['subject']."</a>";



// 다음글
$sql = "select idx,subject from wiz_bbs where code = '$code' and prino < ".$bbs_row['prino']." order by prino desc limit 1";
$result = query($sql) or error("sql_error");
if($row = sql_fetch_arr($result)) $next = "다음글▽ <a href='$PHP_SELF?ptype=view&code=$code&idx=".$row['idx']."'>".$row['subject']."</a>";



// 댓글 작성 비밀번호 숨김
if($wiz_session['id'] != ""){
	$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
}

// 첨부파일 사용여부
if($sch_info['upfile'] < 1){
	$hide_upfile_start = "<!--"; $hide_upfile_end = "-->";
}

// 추천기능 사용여부
if($sch_info['recom'] != "Y"){
	$hide_recom_start = "<!--"; $hide_recom_end = "-->";
}

// 스팸글체크기능 사용여부
if(!strcmp($sch_info['spam_check'], "N")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

// 뷰스킨 인클루드
$bbs_info = $sch_info;
@include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/view_head.php';
@include $_SERVER['DOCUMENT_ROOT'].'/twcenter/bbs/comment.php';
@include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/view_foot.php';

view_img_resize();
?>