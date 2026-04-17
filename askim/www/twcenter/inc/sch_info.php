<?
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = query($sql);
$total = sql_fetch_row($result);
$sch_info = sql_fetch_arr($result);

if($sch_info['perurl'] != "") $sch_info['perurl'] .= "?prev=".urlencode($_SERVER['REQUEST_URI']);

$upfile_max = 3;	// 파일첨부 : 최대12까지가능, 사용자페이지 skin에는 적용되지 않음
	
// 생성되지 않은 게시판인경우
if($total <= 0){
	$msg = "<font color=red><b>".$code."</b></font> 게시판은 아직 생성되지 않았습니다.";
	echo "<table align=center><tr><td height=25>&nbsp;&nbsp;".$msg."&nbsp;&nbsp;</td></tr></table>";
}

// 스킨위치
$skin_dir = "/twcenter/schedule/skin/".$sch_info['skin'];

// 게시판 접근권한
$level_info = level_info();
$mem_level = $level_info[$wiz_session['level']]['level'];

$lpermi = $level_info[$sch_info['lpermi']]['level'];
$rpermi = $level_info[$sch_info['rpermi']]['level'];
$wpermi = $level_info[$sch_info['wpermi']]['level'];
$apermi = $level_info[$sch_info['apermi']]['level'];
$cpermi = $level_info[$sch_info['cpermi']]['level'];

// 파일업로드 설정
$upfile_path = WIZHOME_PATH."/data/bbs/".$code;		// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
$imgsize_s = $sch_info['simgsize'];
$imgsize_m = $sch_info['mimgsize'];

if($imgsize_s == 0) $imgsize_s = 120;
if($imgsize_m == 0) $imgsize_m = 500;

// 게시판 위에서 해당 변수명을 쓸경우 에러 발생 방지
$idx = $_REQUEST['idx'];
$category = $_REQUEST['category'];
$searchopt = $_REQUEST['searchopt'];
$searchkey = $_REQUEST['searchkey'];

?>