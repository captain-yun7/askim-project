<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

echo "<script Language=\"JavaScript\" src=\"/twcenter/js/lib2.js\"></script>".PHP_EOL;
echo "<link href=\"/twcenter/bbs/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;

//웹 취약점 대비 - 파라미터 필터링 2022-06-30 정나혜
foreach($_REQUEST as $k => $v){
	${$k} = xss_clean($v);
}

//웹 취약점 대비 - 태그 삭제 2022-06-30 정나혜
if(!isset($page)) $page = '';
$page = strip_tags($page);

if(!isset($code)) $code = '';
$code = strip_tags($code);

if(!isset($pos)) $pos = '';
$pos = strip_tags($pos);

if(!isset($code_page)) $code_page = '';
$code_page = strip_tags($code_page);

//공격대비 일부 파라미터 무조건 숫자로 2024-08-13 
if($idx) $idx              = intval($idx);
if($page) $page             = intval($page);
if($category) $category         = intval($category);


// 검색 파라미터
$category         = sqlSearchfilter($category);
$catcode          = sqlSearchfilter($catcode);
$searchkey        = sqlSearchfilter($searchkey);
$searchopt        = sqlSearchfilter($searchopt);
$sido             = sqlSearchfilter($sido);
$gugun            = sqlSearchfilter($gugun);
$sub_category_val = sqlSearchfilter($sub_category_val);
$process_val      = sqlSearchfilter($process_val);
$post             = sqlSearchfilter($post);
$status           = sqlSearchfilter($status);
$search_name      = sqlSearchfilter($search_name);
$email_1          = sqlSearchfilter($email_1);
$email_2          = sqlSearchfilter($email_2);

$param = "code=$code";
if($category != "")           $param .= "&category=$category";
if($catcode != "")           $param .= "&catcode=$catcode";
if($searchkey != "")          $param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($sido != "")               $param .= "&sido=$sido";
if($gugun != "")              $param .= "&gugun=$gugun";
if($sub_category_val != "")   $param .= "&sub_category_val=$sub_category_val";
if($process_val != "")        $param .= "&process_val=$process_val";
if($pos       != "")          $param .= "&pos=$pos&code_page=$code_page";
if($status != "")             $param .= "&status=$status";
if($search_name != "")        $param .= "&search_name=$search_name";
if($email_1 != "")            $param .= "&email_1=$email_1";
if($email_2 != "")            $param .= "&email_2=$email_2";

if($pos       != "")          $p_param = "pos=$pos&code_page=$code_page";

$line = $bbs_info['line'];
$bbsadmin_ids = explode(",", $bbs_info['bbsadmin']);

// 목록보기 권한체크
if($lpermi < $mem_level) {
	//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10 
	$perurl = $bbs_info['perurl'];
	if(mobile_check() ==  true && $bbs_info['perurl_m']) $perurl = $bbs_info['perurl_m'];

	error($bbs_info['permsg'], $perurl);
}


// 버튼설정
$list_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=list&code=$code&$p_param' class='btn_w'>리스트</a>";
if($wpermi >= $mem_level) {

	if($mem_level != "0" && $bbs_info['write_point'] < 0 && empty($wiz_session['id'])) {
		$write_btn = "<input type='button' value='글쓰기' class='btn_b' onClick=\"alert('글 작성 시 포인트가 소모됩니다. 로그인 후 이용해주세요.');\" style='cursor:pointer' alt='글쓰기' />";
	} else if(!check_point($wiz_session['id'], $bbs_info['write_point'])) {
		$write_btn = "<input type='button' value='글쓰기' class='btn_b' onClick=\"alert('".$bbs_info['point_msg']."');\" style='cursor:pointer' alt='글쓰기' />";
	} else {
		$write_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=input&mode=insert&code=".$code."&pos=".$pos."&code_page=".$code_page."' class='btn_b'>글쓰기</a>";
	}

} else {

	if(!strcmp($bbs_info['btn_view'], "Y")) {
		//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10 
		if(!empty($bbs_info['perurl']) && mobile_check() == false) {
			$perurl = " document.location='".$bbs_info['perurl']."'; ";
		}else if(mobile_check() == true && $bbs_info['perurl_m']) {
			$perurl = " document.location='".$bbs_info['perurl_m']."'; ";
		}
		$write_btn = "<input type='button' value='글쓰기' class='btn_b' onClick=\"alert('".$bbs_info['permsg']."'); $perurl \" style='cursor:pointer' alt='글쓰기' >";
	}

}

// 관리자인 경우에만 볼수있는 컨텐츠 설정(전체관리자||게시판관리자)
if($mem_level == "0" || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))) {

} else {
	$hide_admin_start = "<!--"; $hide_admin_end = "-->";
}

// 추천기능 사용여부
if($bbs_info['recom'] != "Y"){
	$hide_recom_start = "<!--"; $hide_recom_end = "-->";
}

// 게시물 분류
$sql = "
	select idx
		 , gubun
		 , catname
		 , catimg
		 , catimg_over 
	  from wiz_bbscat where code = '".$code."' 
	 order by gubun desc,prior asc,idx asc
";
$result = query($sql);
$total = sql_fetch_row($result);
if($total > 0) {
	$ii = 0;
	while($row = sql_fetch_arr($result)) {

		if($total < 2 && !strcmp($row['gubun'], "A")) {

		} else {

			if(empty($row['catimg_over']))                      $row['catimg_over'] = $row['catimg'];
			if(empty($category) && !strcmp($row['gubun'], "A")) $row['catimg']        = $row['catimg_over'];
			if(!empty($row['catimg'])) 
				$catname = "<img src='/twcenter/data/category/".$code."/".$row['catimg']."' name='c_".$ii."' border=0 id='c_".$ii."' onMouseOver=WIZ_swapImage('c_".$ii."','','/twcenter/data/category/".$code."/".$row['catimg_over']."',1) onMouseOut=WIZ_swapImgRestore()>";
			else $catname = $row['catname'];

			if($category == $row['idx']) {
				if(!empty($row['catimg'])) $catname = "<img src='/twcenter/data/category/".$code."/".$row['catimg_over']."' name='c_".$ii."' border=0 id='c_".$ii."' onMouseOver=WIZ_swapImage('c_".$ii."','','/twcenter/data/category/".$code."/".$row['catimg_over']."',1) onMouseOut=WIZ_swapImgRestore()>";
				//else $catname = "<span>".$catname."</span>";
				else $catname = $catname;
			}

			$search_param = "";
			if($search_name){
				$search_param .= "&search_name=".$search_name;
			}
			if($email_1 && $email_2){
				$search_param .= "&email_1=".$email_1."&email_2=".$email_2;
			}

			if(!strcmp($row['gubun'], "A")) {
				if($category=="" || $category=="0"){
					$catlist .= "<li class='swiper-slide hover'><a href='".$_SERVER['PHP_SELF']."?ptype=list&code=".$code."&code_page=".$code_page."&pos=".$pos.$search_param."#contents' >".$catname."</a></li>";
				}else{
					$catlist .= "<li class='swiper-slide'><a href='".$_SERVER['PHP_SELF']."?ptype=list&code=".$code."&code_page=".$code_page."&pos=".$pos.$search_param."#contents'>".$catname."</a></li>";
				}
			} else {

				//190709 추가 : 카테고리 선택 효과를 a 태그 뒤의 hover로 변경
				if($category == $row['idx']) {		//카테고리 고유값이 있고 내가 클릭한 카테고리값가 일치하는 경우.
					$catlist .= "<li class='swiper-slide hover'><a href='".$_SERVER['PHP_SELF']."?ptype=list&code=".$code."&category=".$row['idx']."&code_page=".$code_page."&pos=".$pos.$search_param."#contents'>".$catname."</a></li>";		//마우스 오버값 표시
				} else {
					$catlist .= "<li class='swiper-slide'><a href='".$_SERVER['PHP_SELF']."?ptype=list&code=".$code."&category=".$row['idx']."&code_page=".$code_page."&pos=".$pos.$search_param."#contents'>".$catname."</a></li>";
				}

		    	//$catlist .= "<li><a href='".$_SERVER['PHP_SELF']."?ptype=list&code=".$code."&category=".$row['idx']."&code_page=".$code_page."&pos=".$pos."'>".$catname."</a></li>";
			}

			if(empty($row['catimg']))  if($ii < $total-1) $catlist .= "";
			
			$ii++;

		}

		$catname = "";
	}


}

// 상단 체크박스(전체관리자||게시판관리자)
if($mem_level == "0" || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))) {

	$checkbox_head  = "<form style='margin:0;'><input type='checkbox' id=select_tmp name='select_tmp' onClick='selectReverseBbs(this.form)'></form>";
	$sel_delete_btn = "<input type='button' value='선택삭제' onClick=\"delBbs('".$_SERVER['PHP_SELF']."', '".$param."');\" style='cursor:pointer' alt='선택삭제' class='btn_w' />";
	$sel_copy_btn   = "<input type='button' value='선택복사' onClick=\"copyBbs('".$code."');\" style='cursor:pointer' alt='선택복사' class='btn_w' />";
	$sel_move_btn   = "<input type='button' value='선택이동' onClick=\"moveBbs('".$code."');\" style='cursor:pointer' alt='선택이동' class='btn_w' />";
	$order_btn      = "<input type='button' value='순서변경' onClick=\"orderBbs('".$code."');\" style='cursor:pointer' alt='순서변경' class='btn_w' />";
	$sel_update_btn = "<input type='button' value='선택일괄변경' onClick=\"update('".$code."');\" style='cursor:pointer'  class='btn_w long' />";
	$sel_update_btn2 = "<input type='button' value='선택일괄변경' onClick=\"update2('".$code."');\" style='cursor:pointer'  class='btn_w long' />";
	$sel_update_btn3 = "<input type='button' value='선택일괄변경' onClick=\"update3('".$code."');\" style='cursor:pointer'  class='btn_w long' />";
}

// 상단파일
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_head.php";

if(empty($bbs_info['datetype_list'])) $bbs_info['datetype_list'] = "%Y-%m-%d";

$idx = 0;
$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));

// 공지사항
$sql = "
	select wb.* 
		 , wb.wdate as wtime
		 , from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate
	  from wiz_bbs as wb 
	  left join wiz_bbscat as wc 
	    on wb.category = wc.idx
	 where wb.code = '$code' 
	   and wb.notice = 'Y' 
	 order by wb.prino desc
";
$result = query($sql);


// 반응형 : 모바일사이즈일 때 공지 표시 노출되기
$mobile_msg = "<span class=\"mobile_show\"><div class='m_notice'>공지</div></span>";


while($row = sql_fetch_arr($result)){

	$catname		= "";
	$upimg_s		= "";
	$upimg_m		= "";
	$home_icon		= "";
	$status 		= "";
	$ip 			= "";
	$lock_icon		= "";
	$re_icon		= "";
	$new_icon		= "";
	$hot_icon		= "";
	$file_icon		= "";

	/* 아이콘 표시 START */
	$wtime = $row['wtime'];
	if(($ttime-$wtime)/86400 <= $bbs_info['newc']) $new_icon = "<img src='$skin_dir/image/new.gif' border='0' align='absmiddle' alt='new'>";
	if($row['count'] > $bbs_info['hotc']) 		   $hot_icon = "<img src='$skin_dir/image/hot.gif' border='0' align='absmiddle' alt='hot'>";
	if($row['depno'] != 0) 						   $re_icon  = "<img src='$skin_dir/image/re.gif' border='0' align='absmiddle' alt='re' class='re_icon'>";
	for($ii=0; $ii < $row['depno']; $ii++) 		   $re_space .= "&nbsp;&nbsp;";

	//첨부파일
	if(!empty($row['upfile1'])) {
		
		if($mem_level != "0" && $bbs_info['down_point'] < 0 && empty($wiz_session['id'])) {
			$file_icon = "<a href=\"javascript:alert('파일다운로드 시 포인트가 소모됩니다. 로그인 후 이용해주세요.')\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle' alt='file' ></a>";
		} else if(!check_point($wiz_session['id'], $bbs_info['down_point'])) {
			$file_icon = "<a href=\"javascript:alert('".$bbs_info['point_msg']."')\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle' alt='file' ></a>";
		} else if($rpermi < $mem_level) {
			$file_icon = "<a href=\"".$_SERVER['PHP_SELF']."?ptype=view&idx=".$row['idx']."&page=".$page."&".$param."\"><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file'></a>";
		} else {
			$file_icon = "<a href='/twcenter/bbs/down.php?code=".$code."&idx=".$row['idx']."&no=1'><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file'></a>";
		}
	}
	/* 아이콘 표시 END */

	if($mobile_key == "M"){
		if($row['caticon'] != "")      $catname = "<img src='/twcenter/data/category/".$code."/".$row['caticon']."' align='absmiddle'>";  // category
		else if($row['catname'] != "") $catname = "[".$row['catname']."]&nbsp;&nbsp;";
	} else {
		if($row['caticon'] != "")      $catname = "<img src='/twcenter/data/category/".$code."/".$row['caticon']."' align='absmiddle'>";  // category
		else if($row['catname'] != "") $catname = "[".$row['catname']."]";
	}


	if($bbs_info['subject_len'] > 0) $row['subject'] = cut_str($row['subject'], $bbs_info['subject_len']);
		if($mobile_key == "M"){
			$no    = "<span class='notice'>공지</span>";
			$name  = $row['name'];
			$nick  = $row['nick'];
			$wdate = $row['wdate'];
			$count = $row['count'];
		} else {
			$no    = "<div class='notice' title='공지'><span class='material-symbols-outlined'>brand_awareness</span></div>";
			$name  = $row['name'];
			$nick  = $row['nick'];
			$wdate = $row['wdate'];
			$count = $row['count'];
		}

	if($mobile_key !== "M" && $row['comment'] > 0)      $comment = "(".number_format($row['comment']).")";
	else if ($mobile_key == "M" && $row['comment'] > 0) $comment = "<span class='commentBg'>".number_format($row['comment'])."</span>";
	else                                                $comment = "";

	$subject = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&idx=".$row['idx']."&page=".$page."&".$param."'>".$row['subject']."</a>";
	$viewBbs = "".$_SERVER['PHP_SELF']."?ptype=view&idx=".$row['idx']."&page=".$page."&".$param;

	$recom = $row['recom'];

	$upimg_l = $row['upfile1'];

	if(file_exists(WAY_DATA_PATH."/bbs/".$code."/S".$row['upfile1'])) $upimg_s = WAY_DATA_DIR2."/bbs/".$code."/S".$row['upfile1'];
	else $upimg_s = $skin_dir."/image/noimg.gif";

	if(file_exists(WAY_DATA_PATH."/bbs/".$code."/M".$row['upfile1'])) $upimg_m = WAY_DATA_DIR2."/bbs/".$code."/M".$row['upfile1'];
	else $upimg_m = $skin_dir."/image/noimg.gif";

	$viewImg = "javascript:viewImg('".$upimg_l."')";

	$content = $row['content'];
	$content = str_replace("fr|om","from",$content);
	if($row['ctype'] != "H"){
		$content = str_replace("\n", "<br>", $content);
	}

	// 목록 체크박스(전체관리자||게시판관리자)
	if($mem_level == "0" || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))) {
		$checkbox_body = "<form style='margin:0;'><input type='hidden' name='idx' value='".$row['idx']."'><input type='checkbox' name='select_checkbox'></form>";
	}

	$ip = $row['ip'];

	if(img_type(WAY_DATA_PATH."/member/".$row['memid']."_icon.gif")) {
		$icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.gif' align='absmiddle' alt=''>";
	} else if(img_type(WAY_DATA_PATH."/member/".$row['memid']."_icon.jpg")) {
		$icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.jpg' align='absmiddle' alt=''>";
	} else {
		$icon = "";
	}

	if(!strcmp($bbs_info['name_type'], "name")) $name = $name;
	else if(!strcmp($bbs_info['name_type'], "nick") && !empty($nick)) $name = $nick;
	else if(!strcmp($bbs_info['name_type'], "icon") && !empty($icon)) $name = $icon;
	else if(!strcmp($bbs_info['name_type'], "iname")) $name = $icon." ".$name;
	else if(!strcmp($bbs_info['name_type'], "inick")) {
		if(!empty($nick)) $name = $icon." ".$nick;
		else $name = $icon." ".$name;
	}

	if(!empty($row['prdcode'])) {

		// 상품정보
		$prd_sql = "
			select wp.prdcode
				 , wp.prdname
				 , wp.prdimg_S1
				 , wc.purl
			  from wiz_product as wp 
			  left join wiz_cprelation as wcp 
			    on wp.prdcode = wcp.prdcode
			  left join wiz_category as wc 
			    on wcp.catcode = wc.catcode
			 where wp.prdcode = '".$row['prdcode']."'
		";
		$prd_row = sql_fetch($prd_sql);

		// 상품 이미지
		if(!@file(WAY_DATA_PATH."/prdimg/".$prd_row['prdimg_S1'])) $prd_row['prdimg_S1'] = "/twcenter/images/noimg_S.gif";
		else $prd_row['prdimg_S1'] = WAY_DATA_DIR2."/prdimg/".$prd_row['prdimg_S1'];

		$prdimg = "<a href='/".$prd_row['purl']."?ptype=view&prdcode=".$prd_row['prdcode']."'><img src='".$prd_row['prdimg_S1']."' width='50' height='50' border='0'></a>";
		$prdname = cut_str($prd_row['prdname'],30);

	} else {

		$prdimg = "";
		$prdname = "";

	}

	$name     = xss_check($name);
	$email    = xss_check($email);
	$tphone   = xss_check($tphone);
	$hphone   = xss_check($hphone);
	$zipcode  = xss_check($zipcode);
	$address  = xss_check($address);
	$subject  = xss_check($subject);
	$content  = xss_check($content);
	$reply    = xss_check($reply);

	$addinfo1 = xss_check($addinfo1);
	$addinfo2 = xss_check($addinfo2);
	$addinfo3 = xss_check($addinfo3);
	$addinfo4 = xss_check($addinfo4);
	$addinfo5 = xss_check($addinfo5);

	$addinfo6 = xss_check($addinfo6);
	$addinfo7 = xss_check($addinfo7);
	$addinfo8 = xss_check($addinfo8);
	$addinfo9 = xss_check($addinfo9);
	$addinfo10 = xss_check($addinfo10);
	
	$addinfo11 = xss_check($addinfo11);
	$addinfo12 = xss_check($addinfo12);
	$addinfo13 = xss_check($addinfo13);
	$addinfo14 = xss_check($addinfo14);
	$addinfo15 = xss_check($addinfo15);

	$addinfo16 = xss_check($addinfo16);
	$addinfo17 = xss_check($addinfo17);
	$addinfo18 = xss_check($addinfo18);
	$addinfo19 = xss_check($addinfo19);
	$addinfo20 = xss_check($addinfo20);



	// 글목록파일
	@include WAY_PATH."/".$skin_dir."/list_body.php";

	$idx++;

}
$mobile_msg = "";		//반응형일 때 공지표시 관련

// 게시물 쿼리
if($category) $category_sql = " and category = '$category' ";
if($searchopt) {
	if(!strcmp($searchopt, "subcon")) $search_sql = " and (subject like '%$searchkey%' or content like '%$searchkey%') ";
	else $search_sql = " and $searchopt like '%$searchkey%' ";
}

// 자신이 쓴 글 또는 자신의 글에 달린 답변글
if($mybbs) $my_sql      = " and (memid='".$wiz_session['id']."' or memgrp like '".$wiz_session['id'].",%')";
if($sido)  $address_sql = " and address like '".$sido."%' ";
if($gugun) $address_sql .= " and CONCAT(' ', address, ' ') LIKE '% $gugun %' ";

if($is_inqury){
	$search_email = $email_1."@".$email_2;
	$search_sql .= " and name = '".$search_name."' and email = '".$search_email."' ";
}

if($mem_level == 0){
	switch($code){
		case "online":
			if($status) $search_sql .= " and status = '".$status."' ";
			break;
	}
}

$sql = "
	select idx 
	  from wiz_bbs 
	 where code = '".$code."' 
	   and notice != 'Y' 
	   $my_sql 
	   $category_sql 
	   $search_sql 
	   $address_sql 
	   $sub_category_sql 
	   $process_sql 
	 order by prino desc
";
$result = query($sql);
$total = sql_fetch_row($result);

$idx           = 0;
$rows          = $bbs_info['bbs_rows'];
$lists         = $bbs_info['lists'];
if($rows == "")  $rows  = "20";
if($lists == "") $lists = "5";

$page_count = ($total > 0) ? ceil($total/$rows) : 0;
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
if($mobile_key == "M"){
	
	if($code=="faq"){
	$no = $total-$start;
	}else{
	$no = '';
	}

}else{
	$no = $total-$start;
}



$sql = "
	select wb.*
		 , wb.wdate as wtime
		 , from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate
		 , wc.catname
		 , wc.caticon
	  from wiz_bbs as wb 
	  left join wiz_bbscat as wc 
	    on wb.category = wc.idx
	 where wb.code = '$code' 
	   and wb.notice != 'Y' 
	   $category_sql 
	   $search_sql 
	   $my_sql 
	   $address_sql
	 order by wb.prino desc 
	 limit $start, $rows
";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	$catname		= "";
	$lock_icon		= "";
	$re_icon		= "";
	$new_icon		= "";
	$hot_icon		= "";
	$file_icon		= "";
	$re_space		= "";
	$upimg_s		= "";
	$upimg_m		= "";
	$upimg_size		= "";

	$home_icon		= "";
	$status			= "";

	$name 			= $row['name'];
	$nick 			= $row['nick'];
	$email 			= $row['email'];
	$count 			= $row['count'];

	if($mobile_key !== "M" && $row['comment'] > 0) {
		$comment = "(".number_format($row['comment']).")";
	} else if ($mobile_key == "M" && $row['comment'] > 0) {
		$comment = "<span class='commentBg'>".number_format($row['comment'])."</span>";
	} else {
		$comment = "";
	}

	$recom 			= $row['recom'];
	$wdate 			= $row['wdate'];
	$address 		= $row['address'];
	$ip				= $row['ip'];

	$addinfo1		= $row['addinfo1'];
	$addinfo2		= $row['addinfo2'];
	$addinfo3		= $row['addinfo3'];
	$addinfo4		= $row['addinfo4'];
	$addinfo5		= $row['addinfo5'];
	
	$addinfo6		= $row['addinfo6'];
	$addinfo7		= $row['addinfo7'];
	$addinfo8		= $row['addinfo8'];
	$addinfo9		= $row['addinfo9'];
	$addinfo10		= $row['addinfo10'];
	
	$addinfo11		= $row['addinfo11'];
	$addinfo12		= $row['addinfo12'];
	$addinfo13		= $row['addinfo13'];
	$addinfo14		= $row['addinfo14'];
	$addinfo15		= $row['addinfo15'];
	
	$addinfo16		= $row['addinfo16'];
	$addinfo17		= $row['addinfo17'];
	$addinfo18		= $row['addinfo18'];
	$addinfo19		= $row['addinfo19'];
	$addinfo20		= $row['addinfo20'];


	$content		= $row['content'];
	$content = str_replace("fr|om","from",$content);

	if($row['ctype'] != "H"){
		$content = str_replace("\n", "<br>", $content);
	}

	if($row['caticon'] != "") $catname = "<img src='/twcenter/data/category/".$code."/".$row['caticon']."' align='absmiddle' alt=''>";		// category
	else if($row['catname'] != "") $catname = "[".$row['catname']."]";

	if(!empty($bbs_info['subject_len']) && ($bbs_info['subject_len'] > 0)) $row['subject'] = cut_str($row['subject'], $bbs_info['subject_len']);

	//첨부파일
	if(!empty($row['upfile1'])) {
		if($mem_level != "0" && $bbs_info['down_point'] < 0 && empty($wiz_session['id'])) {
			$file_icon = "<a href=\"javascript:alert('파일다운로드 시 포인트가 소모됩니다. 로그인 후 이용해주세요.')\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle' alt='file' ></a>";
		} else if(!check_point($wiz_session['id'], $bbs_info['down_point'])) {
			$file_icon = "<a href=\"javascript:alert('".$bbs_info['point_msg']."')\"><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file' ></a>";
		} else if($rpermi < $mem_level) {
			$file_icon = "<a href=\"$PHP_SELF?ptype=view&idx=".$row['idx']."&page=$page&$param\"><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file'></a>";
		} else {
			$file_icon = "<a href='/twcenter/bbs/down.php?code=$code&idx=".$row['idx']."&no=1'><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file'></a>";
		}
	}

	if($bbs_code == "as_center" && $mem_level != "0"){
		$subject = $row['subject'];
	} else {
		$subject = "<a href='".$PHP_SELF."?ptype=view&idx=".$row['idx']."&page=$page&$param'>".$row['subject']."</a>";
	}

	$viewBbs = $PHP_SELF."?ptype=view&idx=".$row['idx']."&page=$page&$param";
	if($row['privacy'] == "Y"){// privacy
		$memgrps = explode(",", $row['memgrp']);
		if(
			($mem_level == "0") ||																		// 전체관리자
			($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))  ||	// 게시판관리자
			($row['memid'] != "" && $row['memid'] == $wiz_session['id']) ||									// 자신의글
			($wiz_session['id'] != "" && in_array($wiz_session['id'], $memgrps))					// 그룹의글
		){

		}else{
			$subject = "<a href='".$PHP_SELF."?ptype=passwd&mode=view&idx=".$row['idx']."&page=$page&$param'>".$row['subject']."</a>";
			$viewBbs = "$PHP_SELF?ptype=passwd&mode=view&idx=".$row['idx']."&page=$page&$param";
			if(!empty($file_icon)) $file_icon = "<a href='$PHP_SELF?ptype=passwd&mode=view&idx=".$row['idx']."&page=$page&$param'><img src='$skin_dir/image/file.gif' border='0' align='absmiddle' alt='file'></a>";
		}
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle' alt='lock'>";
	}

	$wtime = $row['wtime'];
	if(($ttime-$wtime)/86400 <= $bbs_info['newc']) $new_icon = "<img src='$skin_dir/image/new.gif' border='0' align='absmiddle' alt='new'>";
	if($row['count'] > $bbs_info['hotc']) 			 $hot_icon = "<img src='$skin_dir/image/hot.gif' border='0' align='absmiddle' alt='hot'>";
	if($row['depno'] != 0) 						 $re_icon = "<img src='$skin_dir/image/re.gif' border='0' align='absmiddle' alt='re' class='re_icon'>";
	for($ii=0; $ii < $row['depno']; $ii++) 		 $re_space .= "&nbsp;&nbsp;";

	$upimg_l = $row['upfile1'];
	if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/S".$row['upfile1'])) $upimg_s = "/twcenter/data/bbs/$code/S".$row['upfile1'];
	else $upimg_s = "$skin_dir/image/noimg.gif";

	if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/M".$row['upfile1'])) $upimg_m = "/twcenter/data/bbs/$code/M".$row['upfile1'];
	else $upimg_m = "$skin_dir/image/noimg.gif";

	$viewImg = "javascript:viewImg('".$upimg_l."')";

	if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.gif")) $icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.gif' align='absmiddle' alt=''>";
	else if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.jpg")) $icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.jpg' align='absmiddle' alt=''>";
	else $icon = "";

	if(!strcmp($bbs_info['name_type'], "name")) $name = $name;
	else if(!strcmp($bbs_info['name_type'], "nick") && !empty($nick)) $name = $nick;
	else if(!strcmp($bbs_info['name_type'], "icon") && !empty($icon)) $name = $icon;
	else if(!strcmp($bbs_info['name_type'], "iname")) $name = $icon." ".$name;
	else if(!strcmp($bbs_info['name_type'], "inick")) {
		if(!empty($nick)) $name = $icon." ".$nick;
		else $name = $icon." ".$name;
	}

	// 목록 체크박스
	if(($mem_level == "0") || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))) {
		$checkbox_body = "<form style='margin:0;'><input type='hidden' name='idx' value='".$row['idx']."'><input type='checkbox' name='select_checkbox'></form>";
	}

	if(!empty($row['prdcode'])) {

	 	// 상품정보
	 	$prd_sql = "select wp.prdcode,wp.prdname,wp.prdimg_S1, wc.purl
	 							from wiz_product as wp left join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
	 							left join wiz_category as wc on wcp.catcode = wc.catcode
	 							where wp.prdcode='".$row['prdcode']."'";
	 	$prd_row = sql_fetch($prd_sql);

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row['prdimg_S1'])) $prd_row['prdimg_S1'] = "/twcenter/images/noimg_S.gif";
		else $prd_row['prdimg_S1'] = "/twcenter/data/prdimg/".$prd_row['prdimg_S1'];

		if(($mem_level == "0") || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))){
			$prdimg = "<a href='/".$prd_row['purl']."?ptype=view&prdcode=".$prd_row['prdcode']."' target='_blank'><img src='".$prd_row['prdimg_S1']."' width='50' height='50' border='0'></a>";
		}else{
			$prdimg = "<a href='/".$prd_row['purl']."?ptype=view&prdcode=".$prd_row['prdcode']."'><img src='".$prd_row['prdimg_S1']."' width='50' height='50' border='0'></a>";
		}
		
	 	
	 	$prdname = cut_str($prd_row['prdname'],30);
		$prdname = "<a href='$PHP_SELF?ptype=view&idx=".$row['idx']."&page=$page&$param'>".$prdname."</a>";

	} else {

		$prdimg = "";
		$prdname = "";

	}

	$name       = xss_check($name);
	$email      = xss_check($email);
	$tphone     = xss_check($tphone);
	$hphone     = xss_check($hphone);
	$zipcode    = xss_check($zipcode);
	$address    = xss_check($address);
	$subject    = xss_check($subject);
	$content    = xss_check($content);
	$reply      = xss_check($reply);

	$addinfo1   = xss_check($addinfo1);
	$addinfo2   = xss_check($addinfo2);
	$addinfo3   = xss_check($addinfo3);
	$addinfo4   = xss_check($addinfo4);
	$addinfo5   = xss_check($addinfo5);

	$addinfo6   = xss_check($addinfo6);
	$addinfo7   = xss_check($addinfo7);
	$addinfo8   = xss_check($addinfo8);
	$addinfo9   = xss_check($addinfo9);
	$addinfo10  = xss_check($addinfo10);
	
	$addinfo11   = xss_check($addinfo11);
	$addinfo12   = xss_check($addinfo12);
	$addinfo13   = xss_check($addinfo13);
	$addinfo14   = xss_check($addinfo14);
	$addinfo15   = xss_check($addinfo15);

	$addinfo16   = xss_check($addinfo16);
	$addinfo17   = xss_check($addinfo17);
	$addinfo18   = xss_check($addinfo18);
	$addinfo19   = xss_check($addinfo19);
	$addinfo20  = xss_check($addinfo20);

	// 글목록파일

	@include $_SERVER['DOCUMENT_ROOT']."/$skin_dir/list_body.php";

if($mobile_key == "M"){

	if($code=="faq"){
	$no--;
	}else{
	}

}else{
	$no--;
}
	$idx++;

	if(empty($bbs_idx)) $bbs_idx = $row['idx'];

}

if($total <= 0){
	//echo "<tr><td height='25' align='center' colspan='20'>등록된 글이 없습니다.</td></tr>";
}

if(!empty($view_idx)) $param .= "&idx=$view_idx";

// 하단파일
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_foot.php";

?>