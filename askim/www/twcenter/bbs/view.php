<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

if(($ptype == "" || $ptype != "view" || $code == "" || $idx == "") || SQLInjectXssForward($code) == 1) {
	error('정상적으로 값이 넘어오지 않았습니다.');
	exit;
}

echo "<link href=\"/twcenter/bbs/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;

$bbsadmin_ids = explode(",", $bbs_info['bbsadmin']);

//공격대비 게시글번호/페이지번호/카테고리 파라미터 무조건 숫자로 2024-08-13 
$idx              = sqlfilter(intval($idx));
$page             = sqlfilter(intval($page));
$category         = sqlfilter(intval($category));
$searchkey        = sqlfilter($searchkey);
$searchopt        = sqlfilter($searchopt);

// 검색 파라미터
$param = "code=$code";
if($idx != "")       $param .= "&idx=$idx";
if($page != "")      $param .= "&page=$page";
if($category != "")  $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($pos != "")       $param .= "&pos=$pos&code_page=$code_page";
if($search_name != "")  $param .= "&search_name=$search_name";
if($email_1 != "")  $param .= "&email_1=$email_1";
if($email_2 != "")  $param .= "&email_2=$email_2";

if(empty($bbs_info['datetype_view'])) $bbs_info['datetype_view'] = "%Y-%m-%d";

// 게시물 번호 ($no)
$no = get_bbs_no($_GET);

// 게시물 정보
$sql = "
	select wb.*
		 , from_unixtime(wb.wdate, '".$bbs_info['datetype_view']."') as wdate
		 , wc.catname, wc.caticon
	  from wiz_bbs as wb 
	  left join wiz_bbscat as wc 
	    on wb.category = wc.idx
	 where wb.idx = '$idx'
";
$result = query($sql);
$total = sql_fetch_row($result);
if($total <= 0) error("해당 게시물이 없습니다.");
$bbs_row = sql_fetch_arr($result);

$memid          = $bbs_row['memid'];
$name           = xss_check($bbs_row['name']);
$nick           = $bbs_row['nick'];
$email          = xss_check($bbs_row['email']);
$tphone         = xss_check($bbs_row['tphone']);
$hphone         = xss_check($bbs_row['hphone']);
$zipcode        = xss_check($bbs_row['zipcode']);
$address        = xss_check($bbs_row['address']);
$subject        = xss_check($bbs_row['subject']);
$content        = xss_check($bbs_row['content']);
$status         = $bbs_row['status'];
$reply          = xss_check($bbs_row['reply']);
$wdate          = $bbs_row['wdate'];
$count          = $bbs_row['count'];
$recom          = $bbs_row['recom'];
$ip             = $bbs_row['ip'];
$star           = $bbs_row['star'];
$latitude       = $bbs_row['latitude'];
$longitude      = $bbs_row['longitude'];

$addinfo1       = xss_check($bbs_row['addinfo1']);
$addinfo2       = xss_check($bbs_row['addinfo2']);
$addinfo3       = xss_check($bbs_row['addinfo3']);
$addinfo4       = xss_check($bbs_row['addinfo4']);
$addinfo5       = xss_check($bbs_row['addinfo5']);

$addinfo6       = xss_check($bbs_row['addinfo6']);
$addinfo7       = xss_check($bbs_row['addinfo7']);
$addinfo8       = xss_check($bbs_row['addinfo8']);
$addinfo9       = xss_check($bbs_row['addinfo9']);
$addinfo10      = xss_check($bbs_row['addinfo10']);

$addinfo11       = xss_check($bbs_row['addinfo11']);
$addinfo12       = xss_check($bbs_row['addinfo12']);
$addinfo13       = xss_check($bbs_row['addinfo13']);
$addinfo14       = xss_check($bbs_row['addinfo14']);
$addinfo15       = xss_check($bbs_row['addinfo15']);

$addinfo16       = xss_check($bbs_row['addinfo16']);
$addinfo17       = xss_check($bbs_row['addinfo17']);
$addinfo18       = xss_check($bbs_row['addinfo18']);
$addinfo19       = xss_check($bbs_row['addinfo19']);
$addinfo20      = xss_check($bbs_row['addinfo20']);


$content = str_replace("fr|om","from",$content);

$addinfo6 = str_replace("&#034;","\"", $addinfo6);


if(img_type(WIZHOME_PATH."/data/member/".$memid."_icon.gif"))      $icon = "<img src='/twcenter/data/member/".$memid."_icon.gif' align='absmiddle' alt=''>";
else if(img_type(WIZHOME_PATH."/data/member/".$memid."_icon.jpg")) $icon = "<img src='/twcenter/data/member/".$memid."_icon.jpg' align='absmiddle' alt=''>";
else $icon = "";

if(!strcmp($bbs_info['name_type'], "name"))                       $name = $name;
else if(!strcmp($bbs_info['name_type'], "nick") && !empty($nick)) $name = $nick;
else if(!strcmp($bbs_info['name_type'], "icon") && !empty($icon)) $name = $icon;
else if(!strcmp($bbs_info['name_type'], "iname"))                 $name = $icon." ".$name;
else if(!strcmp($bbs_info['name_type'], "inick")) {
	if(!empty($nick)) $name = $icon." ".$nick;
	else $name = $icon." ".$name;
}

if($bbs_row['caticon'] != "")      $catname = "<img src='/twcenter/data/category/".$code."/".$bbs_row['caticon']."' align='absmiddle' alt='' > ";
else if($bbs_row['catname'] != "") $catname = "[".$bbs_row['catname']."] ";

if($bbs_row['ctype'] != "H"){
	$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);

	$reply = htmlspecialchars($reply);
	$reply = str_replace("\n", "<br>", $reply);
}

$_ResizeCheck = false;
// 첨부파일 이미지인경우 보여주기
if(strcmp($bbs_info['imgview'], "Y")) {

	for($ii = 1; $ii <= 12; $ii++) {
		if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row['upfile'.$ii])) {
			${'upimg'.$ii} = "<div align='".$bbs_info['img_align']."'><a href=javascript:bbsviewImg('".$code."','".$bbs_row['upfile'.$ii]."','img'); title='확대보기'><img src='/twcenter/data/bbs/$code/M".$bbs_row['upfile'.$ii]."' border='0' name='wiz_target_resize' alt='첨부 이미지'/></a></div>";
			$_ResizeCheck = true;
		}
	}

}

if($addinfo1 !="usemap"){ //2021-03-11 리사이징시 usemap 단어 제외되는 문제
	// 이미지 리사이즈를 위해서 처리하는 부분
	if(strpos(strtolower($content), "<img") !== false) {
		$content = ContentImgResizeCheck($content,$code);
	}
}

if($code == "portfolio"){ //2025-09-04 포트폴리오 게시판에만 이미지 확대 추가
	if(strpos(strtolower($content), "<img") !== false) {
		$addinfo6 = ContentImgResizeCheck($addinfo6, $code);
	}
}

// 글보기 권한체크
if($rpermi < $mem_level){
	//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10 
	$perurl = $bbs_info['perurl'];
	if(mobile_check() ==  true && $bbs_info['perurl_m']) $perurl = $bbs_info['perurl_m'];
	
	error($bbs_info['permsg'], $perurl);
}

$passwd = md5($passwd ?? ' ');
// 비밀글인경우 체크
if($bbs_row['privacy'] == "Y"){
	
	$sql = "
		select idx 
		  from wiz_bbs 
		 where code = '$code' 
		   and grpno = '".$bbs_row['grpno']."' 
		   and passwd = '$passwd' 
		   and passwd != ''
	";
	$result = query($sql);
	$grp_passwd = sql_fetch_row($result);
	$memgrps = explode(",", $bbs_row['memgrp']);
	
//	var_dump($mem_level);
//	exit;
	if(
	$mem_level == 0 ||																					// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||		// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 							// 자신의글
	($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd) ||										// 비밀번호일치
	($wiz_session['id'] != "" && in_array($wiz_session['id'], $memgrps)) ||			// 그룹의글
	($grp_passwd > 0)	|| 																				// 그룹비번
	($bbs_row['notice'] == 'Y' ) 
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.","");
		else  error("권한이 없습니다.","?ptype=passwd&mode=view&$param");
	}

}
// 포인트 차감 체크
if($mem_level != "0" && $bbs_info['view_point'] < 0 && empty($wiz_session['id'])) {
	error("게시글 열람 시 포인트가 소모됩니다. 로그인 후 이용해주세요.");
} else if (!check_point($wiz_session['id'], $bbs_info['view_point'])) {
	error($bbs_info['point_msg']);
}
save_point("BBS", $wiz_session['id'], "view", $idx);

// 조회수 증가
$count_id = "bbs_view_".$code."_".$idx;
if(!$_SESSION[$count_id]) {
	$sql = "
		update wiz_bbs 
		   set count = count+1 
		 where idx = '$idx'
	";
	$result = query($sql);
	$_SESSION[$count_id] = true;
	$count++;
}

// 버튼설정
$list_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=list&$param' class='btn_w'>리스트</a>";
$list_btn_ver2 = "<a href='".$_SERVER['PHP_SELF']."?ptype=list&$param' class='btn_w_ver2 more_btn'>목록으로</a>";
if($wpermi >= $mem_level){

	if($mem_level != "0" && $bbs_info['write_point'] < 0 && empty($wiz_session['id'])) {
		$write_btn = "<input type='button' value='글쓰기' class='btn_b' onClick=\"alert('글 작성 시 포인트가 소모됩니다. 로그인 후 이용해주세요.');\" style='cursor:pointer' alt='글쓰기' />";
	} else if(!check_point($wiz_session['id'], $bbs_info['write_point'])) {
		$write_btn = "<input type='button' value='글쓰기' onClick=\"alert('".$bbs_info['point_msg']."');\" style='cursor:pointer' class='btn_b' />";
	} else {
		$write_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=input&mode=insert&$param' class='btn_b'>글쓰기</a>";
	}

	$modify_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=input&mode=modify&$param' class='btn_w'>수정</a>";
	$delete_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=delete&$param' class='btn_w'>삭제</a>";
} else {

	if(!strcmp($bbs_info['btn_view'], "Y")) {
		//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10
		if(!empty($bbs_info['perurl']) && mobile_check() == false) {
			$perurl = " document.location='".$bbs_info['perurl']."'; ";
		}else if(mobile_check() == true && $bbs_info['perurl_m']) {
			$perurl = " document.location='".$bbs_info['perurl_m']."'; ";
		}
		$write_btn = "<input type='button' value='글쓰기' border='0' onClick=\"alert('".$bbs_info['permsg']."'); $perurl \" style='cursor:pointer' />";
	}

	// 구매회원 체크
	if(!strcmp($wpermi, "-1")) {

		$prdcode = $bbs_row['prdcode'];

		$sql = "select count(idx) as cnt 
			      from wiz_basket as wb 
				  left join wiz_order as wo 
				    on wb.orderid = wo.orderid
				 where wb.prdcode = '$prdcode' 
				   and wo.status = 'DC'
		";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row['cnt'] > 0) {
			$modify_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=input&mode=modify&$param' class='btn_w'>수정</a>";
			$delete_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=delete&$param' class='btn_w'>삭제</a>";
		}

	}

}

if($bbs_info['skin'] == "answerBasic" && ($status == "Y"|| $reply != "")) {
	$reply_btn_str = "답글 수정";
} else {
	$reply_btn_str = "답글";
}

if($apermi >= $mem_level){

	if($mem_level != "0" && $bbs_info['write_point'] < 0 && empty($wiz_session['id'])) {
		$reply_btn = "<a href=\"javascript:alert('".$bbs_info['point_msg']."');\" class='btn_w'>".$reply_btn_str."</a>";
	} else if(!check_point($wiz_session['id'], $bbs_info['write_point'])) {
		$reply_btn = "<a href=\"javascript:alert('".$bbs_info['point_msg']."');\" class='btn_w'>".$reply_btn_str."</a>";
	} else {
		$reply_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=input&mode=reply&$param' class='btn_w'>".$reply_btn_str."</a>";
	}

} else {

	if(!strcmp($bbs_info['btn_view'], "Y")) {
		//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10 
		if(!empty($bbs_info['perurl']) && mobile_check() == false) {
			$perurl = " document.location='".$bbs_info['perurl']."'; ";
		}else if(mobile_check() == true && $bbs_info['perurl_m']) {
			$perurl = " document.location='".$bbs_info['perurl_m']."'; ";
		}
		$reply_btn = "<input type='button' value='".$reply_btn_str."' onClick=\"alert('".$bbs_info['permsg']."'); $perurl \" style='cursor:pointer' alt='".$reply_btn_str."'>";
	}

}

if($bbs_info['recom'] == "Y"){

	if($mem_level != "0" && $bbs_info['recom_point'] < 0 && empty($wiz_session['id'])) {
		$recom_btn = "<input type='button' value='추천' onClick=\"alert('추천 시 포인트가 소모됩니다. 로그인 후 이용해주세요.');\" style='cursor:pointer' alt='추천'>";
	} else if(!check_point($wiz_session['id'], $bbs_info['recom_point'])) {
		$recom_btn = "<input type='button' value='추천' onClick=\"alert('".$bbs_info['point_msg']."');\" style='cursor:pointer' alt='추천'>";
	} else {
		$recom_btn = "<a href='/twcenter/bbs/save.php?mode=recom&prev=".$_SERVER['PHP_SELF']."&$param' class='btn_w'>추천</a>";
	}

}

// 첨부파일
if($mem_level != "0" && $bbs_info['down_point'] < 0 && empty($wiz_session['id'])) {
	for($ii = 1; $ii <= 12; $ii++) {
		if($bbs_row['upfile'.$ii] != "") ${'upfile'.$ii}  = "<a href=\"javascript:alert('다운로드 시 포인트가 소모됩니다. 로그인 후 이용해주세요.')\">".$bbs_row['upfile'.$ii.'_name']."</a>";
	}
} else if(!check_point($wiz_session['id'], $bbs_info['down_point'])) {
	for($ii = 1; $ii <= 12; $ii++) {
		if($bbs_row['upfile'.$ii] != "") ${'upfile'.$ii}  = "<a href=\"javascript:alert('".$bbs_info['point_msg']."')\"><span class='material-symbols-outlined file'>attach_file</span>".$bbs_row['upfile'.$ii.'_name']."</a>";
	}
} else {
	for($ii = 1; $ii <= 12; $ii++) {
		if($bbs_row['upfile'.$ii] != ""){ 
		${'upfile'.$ii}  = "<a href='/twcenter/bbs/down.php?code=$code&idx=$idx&no=".$ii."'><span class='material-symbols-outlined file'>attach_file</span>".$bbs_row['upfile'.$ii.'_name']."</a>"; 
		}
	}
}

## 비디오바로보기
for($mi=1; $mi <= 3; $mi++){
	if($bbs_row['movie'.$mi] != "") {
		## video 태그에서 지원하지 않는 확장자일경우. (익스 재생 O, 크롬 재생 x -> 크롬은 video태그에서 지원하는 동영상확장자만 재생 가능)
		if ($use_vimeo && strpos($bbs_row['movie'.$mi], "vimeo") !== false) {			// 링크(비메오 등) 일 경우			
			$vimeo_link = explode("/", $bbs_row['movie'.$mi]);
			$vimeo_code = end($vimeo_link);
			${'movie'.$mi} = '<iframe src="https://player.vimeo.com/video/'.$vimeo_code.'?autoplay=1&amp;loop=1&amp;autopause=0" width="100%" height="480" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen=""></iframe>';
		} else if(!strpos($bbs_row['movie'.$mi],'.mp4') && !strpos($bbs_row['movie'.$mi],'.webm') && !strpos($bbs_row['movie'.$mi],'.ogg') && !strpos($bbs_row['movie'.$mi],'.ogv') && !strpos($bbs_row['movie'.$mi],'.mp3')){
			${'movie'.$mi} = "<br><br><embed type='*' src='/twcenter/data/bbs/$code/".$bbs_row['movie'.$mi]."' autostart='true'></embed><br>";
		}
		## 오디오 확장자일경우
		else if(strpos($bbs_row['movie'.$mi],'.mp3')){
			${'movie'.$mi} = "<br><br><iframe src='/twcenter/bbs/audio.php?src=".$bbs_row['movie'.$mi]."&code=$code' style='width:310px; height:60px;'></iframe>";
			
		}
		## video 태그에서 지원하는 확장자일경우
		else if(strpos($bbs_row['movie'.$mi],'.mp4') || strpos($bbs_row['movie'.$mi],'.webm') || strpos($bbs_row['movie'.$mi],'.ogg') || strpos($bbs_row['movie'.$mi],'.ogv')){
			${'movie'.$mi} = "<br><br><video controls loop autoplay src='/twcenter/data/bbs/$code/".$bbs_row['movie'.$mi]."'></video>";
		}
	}
}

$prev = "";
$next = "";

// 현재 카테고리의 글일경우 카테고리의 다음글 이전글만 보이도록.
//2024-08-28 카테고리 파라미터 intval 처리로 0인 경우는 넘어오지 않은 것으로 제외
if($category != "" && $category != 0) $cat_sql = "and category='$category'";
else $cat_sql = "";

// 자신이 쓴 글 또는 자신의 글에 달린 답변글
if($mybbs) $my_sql = " and (memid='".$wiz_session['id']."' or memgrp like '".$wiz_session['id'].",%')";

// 이전글
$sql = "
	select idx
		 , subject
		 , privacy
		 , memid
		 , memgrp
		 , category
	  from wiz_bbs 
	 where code = '$code' 
	   and prino > '".$bbs_row['prino']."' 
	   $my_sql $cat_sql 
	 order by prino asc limit 1
";
$result = query($sql);
if($row = sql_fetch_arr($result)) {

	$row['subject'] = xss_check($row['subject']);

	if($mobile_key == "M"){
		$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&code=$code&idx=".$row['idx']."&category=$category'>←&nbsp;&nbsp;이전글</a>";
	}else{
		$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&code=$code&idx=".$row['idx']."&category=$category'>".$row['subject']."</a>";
	}

	if($row['privacy'] == "Y"){
		$memgrps = explode(",", $row['memgrp']);
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle' alt='lock'>";
		if(
			($mem_level == "0") ||																			// 전체관리자
			($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))  ||	// 게시판관리자
			($row['memid'] != "" && $row['memid'] == $wiz_session['id']) ||									// 자신의글
			($wiz_session['id'] != "" && in_array($wiz_session['id'], $memgrps))				// 그룹의글
		){
		}else{
			if($mobile_key == "M"){
				$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=view&code=$code&idx=".$row['idx']."&category=$category'>←&nbsp;&nbsp;이전글</a>".$lock_icon;
			}else{
				$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=view&code=$code&idx=".$row['idx']."&category=$category'>".$row['subject']."</a> ".$lock_icon;
			}

		}
	}
}

// 다음글
$sql = "
	select idx
		 , subject
		 , privacy
		 , memid
		 , memgrp
	  from wiz_bbs 
	 where code = '$code' 
	   and prino < '".$bbs_row['prino']."' 
	   $my_sql $cat_sql 
	 order by prino desc limit 1
";
$result = query($sql);
if($row = sql_fetch_arr($result)) {

	$row['subject'] = xss_check($row['subject']);

	if($mobile_key == "M"){
		$next = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&code=$code&idx=".$row['idx']."&category=$category'>다음글&nbsp;&nbsp;→</a>";
	}else{
		$next = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&code=$code&idx=".$row['idx']."&category=$category'>".$row['subject']."</a>";
	}
	if($row['privacy'] == "Y"){
		$memgrps = explode(",", $row['memgrp']);
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle' alt='lock'>";
		if(
			($mem_level == "0") ||																				// 전체관리자
			($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||		// 게시판관리자
			($row['memid'] != "" && $row['memid'] == $wiz_session['id']) ||										// 자신의글
			($wiz_session['id'] != "" && in_array($wiz_session['id'], $memgrps))					// 그룹의글
		){
		}else{
			if($mobile_key == "M"){
				$next = "".$lock_icon."<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=view&code=$code&idx=".$row['idx']."&category=$category'>다음글&nbsp;&nbsp;→</a> ";
			}else{
				$next = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=view&code=$code&idx=".$row['idx']."&category=$category'>".$row['subject']."</a> ".$lock_icon;
			}
			
		}
	}
}

// 댓글 작성 비밀번호 숨김
if($wiz_session['id'] != ""){
	$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
}

// 첨부파일 사용여부
if($bbs_info['upfile'] < 1){
	$hide_upfile_start = "<!--"; $hide_upfile_end = "-->";
}

// 추천기능 사용여부
if($bbs_info['recom'] != "Y"){
	$hide_recom_start = "<!--"; $hide_recom_end = "-->";
}

// 답글기능 사용여부
if($bbs_info['reply'] != "Y"){
	$hide_bbsreply_start = "<!--"; $hide_bbsreply_end = "-->";
}

// 스팸글체크기능 사용여부
if($mem_level == "0" || !strcmp($bbs_info['spam_check'], "N")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

// 답변내용이 없는 경우 숨김
if(empty($reply) || strcmp($status, "Y")) {
	$hide_reply_start = "<!--"; $hide_reply_end = "-->";
}

// 선호도 숨김
if(strcmp($code, "review")) {
	$hide_star_start = "<!--"; $hide_star_end = "-->";
}

if($bbs_row['prdcode'] != ""){
	$prd_sql = "
		select wp.prdcode
			 , wp.prdname
			 , wp.sellprice
			 , wp.strprice
			 , wp.prdimg_R
			 , wc.purl
		  from wiz_product as wp 
		  left join wiz_cprelation as wcp 
		    on wp.prdcode = wcp.prdcode
		  left join wiz_category as wc 
		    on wc.catcode = wcp.catcode
		 where wp.prdcode='".$bbs_row['prdcode']."' ";
	$prd_result = query($prd_sql);
	$prd_info = sql_fetch_obj($prd_result);

	if(!$prd_info) $prd_info = (object)[];

	if(!empty($prd_info->strprice)) $prd_info->sellprice = $prd_info->strprice;
	else                            $prd_info->sellprice = number_format($prd_info->sellprice)."원";

	if(mobile_check() == true || strpos($_SERVER['PHP_SELF'], "/m/") !== false) {
		$prd_info->purl = "m/sub/prdview.php";
	} else {
		$prd_info->purl = $prd_info->purl;
	}

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_info->prdimg_R)) $prd_info->prdimg_R = "/twcenter/images/noimg_M.gif";
	else $prd_info->prdimg_R = "/twcenter/data/prdimg/".$prd_info->prdimg_R;
?>
	
<div class="prd_review_box">
		<dl>
				<dt><img src="/twcenter/product2/skin/prdBasic/image/blank_img.png" style="background-image:url('<?php echo $prd_info->prdimg_R ?>'); "alt="<?php echo $prd_info->prdname ?>"/></dt>
				<dd>
					<h4 class="prdname"><?php echo $prd_info->prdname ?></h4>
					<small class="price"><?php echo $prd_info->sellprice ?></small>
					<a class="prd_view_btn" href="/<?php echo $prd_info->purl ?>?ptype=view&prdcode=<?php echo $prd_info->prdcode ?>"<?php if(($mem_level == "0") || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))){echo "target='_blank'";}?>>상품보기</a>
				</dd>
		</dl>
</div>
	
<br />
<?php
}

// 관리자인 경우에만 볼수있는 컨텐츠 설정 (전체관리자 / 게시판관리자)
if($mem_level == "0" || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))) {

} else {
	$hide_admin_start = "<!--"; $hide_admin_end = "-->";
}

// 뷰스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_head.php";
@include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/comment.php";
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_foot.php";

view_img_resize();

if(!strcmp($bbs_info['view_list'], "Y")) {
	$view_idx = $idx;
	echo "<table width='100%' height='10'><tr><td></td></tr></table>";
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/bbs/list.php";
}
?>