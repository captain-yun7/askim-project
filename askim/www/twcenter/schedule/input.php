<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/sch_info.php';

// 자동등록글체크
get_spam_check();

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 검색 파라미터
$param = "code=$code";
if($page != "") $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

// 버튼설정
$list_btn = "<a href=$PHP_SELF?ptype=list&$param class='btn_w'>리스트</a>";
$confirm_btn = "<input type='submit' value='확인' class='btn_b' alt='확인' />";
$cancel_btn = "<input type='button' value='취소' class='btn_w'  onClick='history.go(-1)' alt='취소' />";


// 게시물 정보
$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where idx = '$idx'";
$result = query($sql) or error("sql_error");
$bbs_row = sql_fetch_arr($result);

// 글 작성
if($mode == "") $mode = "insert";
if($mode == "insert"){

	if($wpermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

	$name = $wiz_session['name'];
	$email = $wiz_session['email'];
	$wdate = date('Y-m-d');
	if($sch_info['privacy'] == "Y") $privacy_checked = "checked";

	// 비밀번호 숨김
	if($wiz_session['id'] != ""){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}




// 글 수정
}else if($mode == "modify"){

	$name = $bbs_row['name'];
	$email = $bbs_row['email'];
	$wdate = $bbs_row['wdate'];
	$subject = $bbs_row['subject'];
	$content = $bbs_row['content'];

	for($ii = 1; $ii <= $upfile_max; $ii++) {
		if(!empty($bbs_row[upfile.$ii])) {
			${upfile.$ii} = "<input type='checkbox' name='delupfile[]' value='upfile".$ii."'> 삭제 (".$bbs_row[upfile.$ii._name].")";
		}
	}
	if(!empty($bbs_row['movie1'])) {
		$movie1 = "<input type='checkbox' name='delupfile[]' value='movie1'> 삭제 ($bbs_row['movie1'])";
	}
	$movie2 = $bbs_row['movie2'];
	$movie3 = $bbs_row['movie3'];

	// 비밀번호 숨김
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid'])														// 자신에글
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

	if($bbs_row['ctype'] == "H") $ctype_checked = "checked";
	if($bbs_row['privacy'] == "Y") $privacy_checked = "checked";
	if($bbs_row['notice'] == "Y") $notice_checked = "checked";

// 글 답변
}else if($mode == "reply"){

	$sql = "select category,subject,content,privacy,passwd from wiz_bbs where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$bbs_row = sql_fetch_arr($result);


	$category = $bbs_row['category'];
	$subject = $bbs_row['subject'];
	$content = $bbs_row['content']."\n\n==================== 답 변 ====================\n\n";
	$name = $wiz_session['name'];
	$email = $wiz_session['email'];
	$wdate = date('Y-m-d');

	if($sch_info['privacy'] == "Y" || $bbs_row['privacy'] == "Y") $privacy_checked = "checked";

	// 비밀번호 숨김
	if($wiz_session['id'] != ""){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

}


// 카테고리
if($sch_info['category'] != ""){

	$catarr = explode(",",$sch_info['category']);
	$catlist = "<select name='category' title='카테고리 선택'>";
	$catlist .= "<option value=''>분류</option>";
	for($ii=0;$ii<count($catarr);$ii++){
		if($bbs_row['category'] == $catarr[$ii]) $selected = " selected";
		else $selected = "";
		$catlist .= "<option value='".$catarr[$ii]."'".$selected.">".$catarr[$ii]."</option>";
	}
	$catlist .= "</select>";

}

// 첨부파일 사용여부
if($sch_info['upfile'] < 5) { $hide_upfile5_start = "<!--"; $hide_upfile5_end = "-->"; }
if($sch_info['upfile'] < 4) { $hide_upfile4_start = "<!--"; $hide_upfile4_end = "-->"; }
if($sch_info['upfile'] < 3) { $hide_upfile3_start = "<!--"; $hide_upfile3_end = "-->"; }
if($sch_info['upfile'] < 2) { $hide_upfile2_start = "<!--"; $hide_upfile2_end = "-->"; }
if($sch_info['upfile'] < 1) { $hide_upfile1_start = "<!--"; $hide_upfile1_end = "-->"; }

// 동영상 사용여부
if($sch_info['movie'] < 3) { $hide_movie3_start = "<!--"; $hide_movie3_end = "-->"; }
if($sch_info['movie'] < 2) { $hide_movie2_start = "<!--"; $hide_movie2_end = "-->"; }
if($sch_info['movie'] < 1) { $hide_movie1_start = "<!--"; $hide_movie1_end = "-->"; }

// 스팸글체크기능 사용여부
if(!strcmp($sch_info['spam_check'], "N") || strcmp($mode, "insert")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

// 입력스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/input.php';

?>