<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";

echo "<link href=\"/twcenter/bbs/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 게시물정보
$sql = "select memid from wiz_bbs where idx = '$idx'";
$result = query($sql) or error("sql error");
$bbs_row = sql_fetch_arr($result);

// 코멘트정보
if($mode == "delco"){
	$co_sql = "select memid from wiz_comment where idx = '$idx'";
	$co_result = query($co_sql) or error("sql error");
	$co_row = sql_fetch_arr($co_result);
}

// 버튼설정
$confirm_btn = "<input type=submit value=확인 class='btn_b' border='0' alt='확인' />";

if($mode == "view")
	$cancel_btn = "<input type=button value=취소 class='btn_w' onClick=document.location='?ptype=list&code=$code&pos=$pos&code_page=$code_page' style='cursor:pointer' alt='취소' />";
else
	$cancel_btn = "<input type=button value=취소 class='btn_w' onClick='history.go(-1);' style='cursor:pointer' alt='취소' />";


$bbsadmin_ids = explode(",", $bbs_info['bbsadmin']);

// 관리자이거나 자기글인경우
if(
	$mem_level == "0" ||																																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) ||													// 자신의글
	($co_row['memid'] != "" && $co_row['memid'] == $wiz_session['id'])															// 자신의코멘드
){
	$input_passwd = "<p style='display:inline-block; margin-right:10px; line-height:35px;'>글을 삭제하시겠습니까?</p>";
}else{
	
	// 상황별 메세지
	if($mode == "view" &&  $mobile_key !== "M") $mode_msg = "이 글은 비밀글입니다. 비밀번호를 입력하여 주십시요.";
	else if($mode == "view" &&  $mobile_key == "M") $mode_msg = "이 글은 비밀글입니다.";
	else if($mode == "delete") $mode_msg = "글을 삭제합니다. 비밀번호를 입력하여 주십시요.";
	else if($mode == "delco") $mode_msg = "댓글을 삭제합니다. 비밀번호를 입력하여 주십시요.";
	if($mobile_key == "M"){
		$input_passwd = "<input type='password' name='passwd' size='20' class='input input_s' title='비밀번호 입력' placeholder='비밀번호를 입력하세요' autocomplete='off'/>";
	}else{
		$input_passwd = "<input type='password' name='passwd' size='20' class='input input_s' title='비밀번호 입력' autocomplete='off' />";
	}
}

if($mode == "view") $ptype = "view";
else if($mode == "delete" || $mode == "delco") $ptype = "save";


@include $_SERVER['DOCUMENT_ROOT']."/$skin_dir/passwd.php";
?>