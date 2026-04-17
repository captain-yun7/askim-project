<?php
if(is_array($code)) $code = $code[0];
$code = sqlfilter($code);
if($code != ""){

	$upfile_max = 12;	// 파일첨부 : 최대12까지가능, 사용자페이지 skin에는 적용되지 않음

	// 게시판정보
	$sql = "select * from wiz_bbsinfo where code = '$code'";
	$result = query($sql);
	$total = sql_fetch_row($result);
	$bbs_info = sql_fetch_arr($result);

	if($bbs_info['perurl'] != "") $bbs_info['perurl'] .= "?prev=".urlencode($_SERVER['REQUEST_URI']);
	if($bbs_info['perurl_m'] != "") $bbs_info['perurl_m'] .= "?prev=".urlencode($_SERVER['REQUEST_URI']);

	// 생성되지 않은 게시판인경우
	if($total <= 0){
		//$msg = "<font color=red><b>".$code."</b></font> 게시판은 아직 생성되지 않았습니다.";
		//echo "<table align=center><tr><td height=25>&nbsp;&nbsp;".$msg."&nbsp;&nbsp;</td></tr></table>";
		error("생성되지 않은 게시판입니다.");
	}

	// 스킨위치
	if(isset($mobile_key) && $mobile_key == "M"){
		$skin_d = "skin_m/".$bbs_info['skin_m'];
	} else {
		$skin_d = "skin/".$bbs_info['skin'];
	}

	if($code){
		$skin_dir = "/twcenter/bbs/".$skin_d;
	}

	// 게시판 접근권한
	$level_info = level_info();
//	$mem_level = isset($wiz_session['level'], $level_info[$wiz_session['level']]['level']) ? $level_info[$wiz_session['level']]['level'] : '';
	$mem_level = $level_info[$wiz_session['level']]['level'];

	$lpermi = $level_info[$bbs_info['lpermi']]['level'];
	$rpermi = $level_info[$bbs_info['rpermi']]['level'];
	$wpermi = $level_info[$bbs_info['wpermi']]['level'];
	$apermi = $level_info[$bbs_info['apermi']]['level'];
	$cpermi = $level_info[$bbs_info['cpermi']]['level'];

	// 게시판 파일업로드 설정
	$upfile_path = WIZHOME_PATH."/data/bbs/".$code;				// 업로드파일 위치
	$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
	$imgsize_s = $bbs_info['simgsize'];
	$imgsize_m = $bbs_info['mimgsize'];

	if($imgsize_s == 0) $imgsize_s = 120;
	if($imgsize_m == 0) $imgsize_m = 500;

	// 게시판 위에서 해당 변수명을 쓸경우 에러 발생 방지
	$idx = isset($_REQUEST['idx']) ? $_REQUEST['idx'] : ''; 
	$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
	$searchopt = isset($_REQUEST['searchopt']) ? $_REQUEST['searchopt'] : '';
	$searchkey = isset($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';

	// 게시판관리자 체크
	$bbsadmin_list = explode(",", $bbs_info['bbsadmin']);
	for($ii = 0; $ii < count($bbsadmin_list); $ii++) {
		if(!empty($wiz_session['id']) && !strcmp($bbsadmin_list[$ii], $wiz_session['id'])) {
			$mem_level = 0; break;
		}
	}

	// 코멘트 파일업로드 설정
	$upfile_comm_path = WIZHOME_PATH."/data/comment";

}
/*
작업자명	: 이상민
작업일시	: 2021-10-06
작업내용	: vimeo API 키값 선언

-- 수정
작업자명	: 김나연
작업일시	: 2021-10-22
작업내용	: vimeo API 키 연동 site info 에서 획득하도록 수정

*/
if(empty($site_info)){
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
}

if(!defined('VIMEO_CLIENT_ID')) {
	define('VIMEO_CLIENT_ID', $site_info['vimeo_client_id']);
}
if(!defined('VIMEO_CLIENT_SECRET')) {
	define('VIMEO_CLIENT_SECRET', $site_info['vimeo_client_secret']);
}
if(!defined('VIMEO_ACCESS_TOKEN')) {
	define('VIMEO_ACCESS_TOKEN', $site_info['vimeo_access_token']);
}


if($bbs_info['movie'] > 0 && $bbs_info['use_vimeo'] == "Y" && VIMEO_CLIENT_ID && VIMEO_CLIENT_SECRET && VIMEO_ACCESS_TOKEN) {
	$use_vimeo = true;
}
?>