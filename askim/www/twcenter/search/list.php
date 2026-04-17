<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if($mobile_key == "M")
	$skin_dir = "/twcenter/search/skin/searchBasic_m";
else
	$skin_dir = "/twcenter/search/skin/".$site_info['search_skin'];

$total_searchkey = sqlSearchfilter(trim($total_searchkey));

$search_bg = "var(--main)";			// 검색어 배경
$search_color = "var(--white)";		// 검색어 색상

$bbs_rows = "10";				// 게시판검색 페이지출력수
$bbs_lists = "5";				// 게시판검색 리스트출력수

$prd_rows = "10";				// 상품검색 페이지출력수
$prd_lists = "5";				// 상품검색 리스트출력수

$page_rows = "10";				// 페이지검색 페이지출력수
$page_lists = "5";				// 페이지검색 리스트출력수

$param = "total_searchkey=".$total_searchkey;


// 게시판
if(!strcmp($stype, "bbs")) {

	if(!empty($prd_page))  $param .= "&prd_page=".$prd_page;
	if(!empty($page_page)) $param .= "&page_page=".$page_page;

	// 제목, 내용
	if(empty($total_searchkey)) $search_sql = " wb.idx = '' ";
	else $search_sql = "(instr(subject, '$total_searchkey') > 0 or instr(content, '$total_searchkey') > 0)";

	$code_sql = "and (code != 'download')";

	$sql = "
		select count(idx) as cnt 
		  from wiz_bbs as wb 
		 where 
		   $search_sql $code_sql
		 order by prino desc
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$total = $row['cnt'];
	
	// 상단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_head.php";

	$idx = 0;
	$rows = $bbs_rows;
	$lists = $bbs_lists;
	if($rows == "") $rows = "10";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$bbs_page || $bbs_page > $page_count) $bbs_page = 1;
	$start = ($bbs_page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select wb.idx
			 , wb.code
			 , wb.subject
			 , wb.content
			 , wb.wdate as wtime
			 , wb.privacy
			 , from_unixtime(wb.wdate, '%Y.%m.%d') as wdate
			 , wi.page_url
			 , wi.page_url_m
			 , wi.title
			 , wi.subject_len
			 , wi.down_point
			 , wi.point_msg
			 , wi.bbsadmin
			 , wi.newc
			 , wi.hotc
			 , wi.name_type
			 , wc.catname
			 , wc.caticon
			 , wi.pageurl
		  from (select subject
					 , content
					 , wdate
					 , code
					 , category
					 , idx
					 , privacy
				  from wiz_bbs
				 where $search_sql $code_sql
				 order by prino desc, idx desc
				 limit $start, $rows
		  ) wb
		  left join wiz_bbsinfo as wi 
		    on wb.code = wi.code
		  left join wiz_bbscat as wc 
		    on wb.category = wc.idx
	";
	$result = query($sql);

	$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));

	while($row = sql_fetch_arr($result)) {

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

		$home_icon 		= "";
		$status    		= "";

		$code 			= $row['code'];
		$name 			= $row['name'];
		$nick 			= $row['nick'];
		$email 			= $row['email'];
		$count 			= $row['count'];
		$comment 		= $row['comment'];
		$recom 			= $row['recom'];
		$wdate 			= $row['wdate'];
		$address 		= $row['address'];
		$ip				= $row['ip'];

		$addinfo1		= $row['addinfo1'];
		$addinfo2		= $row['addinfo2'];
		$addinfo3		= $row['addinfo3'];
		$addinfo4		= $row['addinfo4'];
		$addinfo5		= $row['addinfo5'];

		if($row['subject_len'] > 0) $row['subject'] = cut_str($row['subject'], $row['subject_len']);
		$row['content'] = cut_str(strip_tags($row['content']), 300);

		$row['subject'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['subject']);
		$row['content'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['content']);

		$content		= $row['content'];
		if(mobile_check()) {
			$page_url = "/".$row["page_url_m"];
		}else{
			if($row['page_url']!=''){
				$page_url = "/".$row['page_url'];
			}else{
				$page_url = "/".$row['pageurl'];
			}
		}

		if($mobile_key == "M"){
			if($row['title'] != "") $title = "<span>".$row['title']."</span>";
			else $title = "";
		} else {
			if($row['title'] != "") $title = "[".$row['title']."]";
			else $title = "";
		}

		if($row['caticon'] != "") $catname = "<img src='/twcenter/data/category/".$code."/".$row['caticon']."' align='absmiddle'>";
		else if($row['catname'] != "") $catname = "[".$row['catname']."]";

		if(!empty($row['upfile1'])) {
			if(!check_point($wiz_session['id'], $row['down_point'])) {
				$file_icon = "<a href=\"javascript:alert('".$row['point_msg']."')\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle'></a>";
			} else if($rpermi < $mem_level) {
				if($row['code'] == 'faq'){
					$file_icon = "<a href=\"".$page_url."?ptype=list&page=".$page."&code=".$code."&".$param."\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle'></a>";
				}else{
					$file_icon = "<a href=\"".$page_url."?ptype=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param."\"><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle'></a>";
				}
			} else {
				$file_icon = "<a href='/twcenter/bbs/down.php?code=".$code."&idx=".$row['idx']."&no=1'><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle'></a>";
			}

		}
		
		if($row['code'] == 'faq'){
			$subject = "<a href='".$page_url."?ptype=list&page=".$page."&code=".$code."&".$param."'>".$row['subject']."</a>";
		}else{
			$subject = "<a href='".$page_url."?ptype=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param."'>".$row['subject']."</a>";
		}
		$viewBbs = $page_url."?ptype=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param;
		if($row['privacy'] == "Y"){
			if(
				($mem_level == "0") ||																	// 전체관리자
				($row['bbsadmin'] != "" && strpos($row['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
				($row['memid'] != "" && $row['memid'] == $wiz_session['id']) ||							// 자신의글
				($wiz_session['id'] != "" && strpos($row['memgrp'],$wiz_session['id']) !== false)		// 그룹의글
			){
			}else{
				$subject = "<a href='".$page_url."?ptype=passwd&mode=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param."'>".$row['subject']."</a>";
				$viewBbs = $page_url."?ptype=passwd&mode=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param;
				if(!empty($file_icon)) $file_icon = "<a href='".$page_url."?ptype=passwd&mode=view&idx=".$row['idx']."&page=".$page."&code=".$code."&".$param."'><img src='".$skin_dir."/image/file.gif' border='0' align='absmiddle'></a>";

				$content = "비밀글입니다.";
			}
			$lock_icon = "<img src='".$skin_dir."/image/lock.gif' border='0' align='absmiddle'>";
		}

		$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$wtime = $row['wtime'];
		if(($ttime-$wtime)/86400 <= $row['newc']) 	$new_icon = "<img src='".$skin_dir."/image/new.gif' border='0' align='absmiddle'>";
		if($row['count'] > $row['hotc']) 				$hot_icon = "<img src='".$skin_dir."/image/hot.gif' border='0' align='absmiddle'>";
		if($row['depno'] != 0) 						$re_icon  = "<img src='".$skin_dir."/image/re.gif' border='0' align='absmiddle' class='re_icon'>";

		for($ii=0; $ii < $row['depno']; $ii++) 		$re_space .= "&nbsp;&nbsp;";

		$upimg_l = $row['upfile1'];
		if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/S".$row['upfile1'])) 
			$upimg_s = "/twcenter/data/bbs/".$code."/S".$row['upfile1'];
		else 
			$upimg_s = $skin_dir."/image/noimg.gif";

		if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/M".$row['upfile1'])) 
			$upimg_m = "/twcenter/data/bbs/".$code."/M".$row['upfile1'];
		else 
			$upimg_m = $skin_dir."/image/noimg.gif";

		$viewImg = "javascript:viewImg('".$upimg_l."')";

		if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.gif")) 
			$icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.gif' align='absmiddle'>";
		else if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.jpg")) 
			$icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.jpg' align='absmiddle'>";
		else $icon = "";

		if(!strcmp($row['name_type'], "name"))						 $name = $name;
		else if(!strcmp($row['name_type'], "nick") && !empty($nick)) $name = $nick;
		else if(!strcmp($row['name_type'], "icon") && !empty($icon)) $name = $icon;
		else if(!strcmp($row['name_type'], "iname"))				 $name = $icon." ".$name;
		else if(!strcmp($row['name_type'], "inick")) {
			if(!empty($nick)) $name = $icon." ".$nick;
			else			  $name = $icon." ".$name;
		}

		// 홈페이지
		if(!empty($row['address'])) {
			$home_icon = "<a href='http://".$row['address']."' target='_blank'><img src='".$skin_dir."/image/ic_home.gif' border='0'></a>";
		}

		// 처리상태
		if(!strcmp($row['status'], "Y")) $status = "<img src='".$skin_dir."/image/bt_end.gif'>";
		else $status = "<img src='".$skin_dir."/image/bt_ing.gif'>";

		// 예약신청일
		if(!empty($addinfo1)) {
			$rdate = $addinfo1."년".$addinfo2."월".$addinfo3."일".$addinfo4."시~".$addinfo5."시";
		}

		// 목록파일
		@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_body.php";

		$no--;

	}

	if($total <= 0) {
?>
			<tr>
				<td height="50" align="center" colspan="20">검색된 게시물이 없습니다.</td>
			</tr>

<?php
	}

	// 하단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_foot.php";

// 상품
} else if(!strcmp($stype, "prd")) {

	if(!empty($bbs_page))  $param .= "&bbs_page=".$bbs_page;
	if(!empty($page_page)) $param .= "&page_page=".$page_page;

	// 상품명, 상품간단설명, 상품상세정보
	if(empty($total_searchkey)){
		$search_sql = " and wp.prdcode = '' ";
	}else{
		//$search_sql = " and (wp.prdname like '%$total_searchkey%' or wp.stortexp like '%$total_searchkey%' or wp.content like '%$total_searchkey%') ";
		$total_searchkey_conv = str_replace(" ","",$total_searchkey);
		$search_sql = " and (instr(prdname, '$total_searchkey') > 0  
							 or instr(stortexp, '$total_searchkey') > 0
							 or instr(content, '$total_searchkey') > 0
							 or instr(prdname, '$total_searchkey_conv') > 0
							 or instr(stortexp, '$total_searchkey_conv') > 0
							 or instr(content, '$total_searchkey_conv') > 0
							 or instr(replace(prdname,' ',''), '$total_searchkey') > 0
							 or instr(replace(stortexp,' ',''), '$total_searchkey') > 0
							 or instr(replace(content,' ',''), '$total_searchkey') > 0
							 or instr(replace(prdname,' ',''), '$total_searchkey_conv') > 0
							 or instr(replace(stortexp,' ',''), '$total_searchkey_conv') > 0
							 or instr(replace(content,' ',''), '$total_searchkey_conv') > 0
							 ) ";
	}

	$sql = "
		select count(wp.prdcode) as cnt
		  from wiz_product as wp 
		  left join wiz_cprelation as wcp 
		    on wp.prdcode = wcp.prdcode
		  left join wiz_category as wcat 
		    on wcp.catcode = wcat.catcode
		 where wp.showset != 'N' 
		   and wcat.catuse != 'N' 
		   $search_sql
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$total = $row['cnt'];

	// 상단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_head.php";

	$idx = 0;
	$rows = $prd_rows;
	$lists = $prd_lists;
	if($rows == "") $rows = "10";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$prd_page || $prd_page > $page_count) $prd_page = 1;
	$start = ($prd_page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select wp.prdcode
			 , wp.prdname
			 , wp.stortexp
			 , wp.content
			 , wp.prdimg_R
			 , wc.catname
			 , wc.purl
			 , wc.catcode
		  from (select prdcode
					 , prdname
					 , stortexp
					 , content
					 , prdimg_R
				  from wiz_product
				 where showset != 'N' 
					$search_sql
				 order by prior desc
				 limit $start, $rows
		  ) as wp
		  left join wiz_cprelation as wcp 
		    on wp.prdcode = wcp.prdcode
		  left join wiz_category as wc 
		    on wcp.catcode = wc.catcode
		   and wc.catuse != 'N' 
		   group by prdcode
	";
	//echo "<xmp>".$sql."</xmp>";
	$result = query($sql);
	while($row = sql_fetch_arr($result)) {

		$purl = "/".$row['purl'];

		$row['content']  = cut_str(strip_tags($row['content']), 300);
		$row['stortexp'] = cut_str(strip_tags($row['stortexp']), 300);

		$row['prdname'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['prdname']);
		$row['content'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['content']);
		$row['stortexp'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['stortexp']);

		if(!empty($row['catname'])) $catname = "[".$row['catname']."]";
		else $catname = "";

		$prdurl = $purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $catcode ."&page=".$page."&".$param;
		$content = str_replace("\n","",$row['content']);
		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row['prdimg_R'])) $prdimg = "/twcenter/images/noimg_R.gif";
		else $prdimg = "/twcenter/data/prdimg/".$row['prdimg_R'];
		//$prdimg = "/twcenter/data/prdimg/".$row['prdimg_R'];
		$prdprice = number_format((float)($row['prdprice'] ?? 0));
		$prdname = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."'>".$row['prdname']."</a>";
		$stortexp = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."'>".$row['stortexp']."</a>";
		$viewimg = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."'><img src='$skin_dir/image/bt_view.gif' border='0'></a>";

		// 목록파일
		@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_body.php";

		$no--;

	}

	if($total <= 0) {
?>
			<tr>
				<td height="50" align="center" colspan="20">검색된 상품이 없습니다.</td>
			</tr>

<?php
	}

	// 하단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_foot.php";

// 페이지
} else if(!strcmp($stype, "prd2")) {

	if(!empty($bbs_page))  $param .= "&bbs_page=".$bbs_page;
	if(!empty($page_page)) $param .= "&page_page=".$page_page;

	//$showset_sql = " and showset != 'N' ";

	// 상품명, 상품간단설명, 상품상세정보
	if(empty($total_searchkey)){
		$search_sql = " and wp.prdcode = '' ";
	}else{
		$total_searchkey_conv = str_replace(" ","",$total_searchkey);
		$search_sql = " and (instr(prdname, '$total_searchkey') > 0  
							 or instr(shortexp, '$total_searchkey') > 0
							 or instr(content, '$total_searchkey') > 0
							 or instr(prdname, '$total_searchkey_conv') > 0
							 or instr(shortexp, '$total_searchkey_conv') > 0
							 or instr(content, '$total_searchkey_conv') > 0
							 or instr(replace(prdname,' ',''), '$total_searchkey') > 0
							 or instr(replace(shortexp,' ',''), '$total_searchkey') > 0
							 or instr(replace(content,' ',''), '$total_searchkey') > 0
							 or instr(replace(prdname,' ',''), '$total_searchkey_conv') > 0
							 or instr(replace(shortexp,' ',''), '$total_searchkey_conv') > 0
							 or instr(replace(content,' ',''), '$total_searchkey_conv') > 0
							 ) ";
	}

	$sql = "
		select count(wp.prdcode) as cnt
		  from wiz_product2 as wp 
		  left join wiz_cprelation2 as wcp 
		    on wp.prdcode = wcp.prdcode
		  left join wiz_category2 as wcat 
		    on wcp.catcode = wcat.catcode
		 where wp.showset != 'N' 
		   and wcat.catuse != 'N' 
		   $search_sql
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$total = $row['cnt'];

	// 상단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_head.php";

	$idx = 0;
	$rows = $prd_rows;
	$lists = $prd_lists;
	if($rows == "") $rows = "10";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$prd2_page || $prd2_page > $page_count) $prd2_page = 1;
	$start = ($prd2_page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select wp.prdcode
			 , wp.prdname
			 , wp.shortexp
			 , wp.content
			 , wp.prdimg_R
			 , wc.catname
			 , wc.purl
			 , wc.catcode
		  from (select prdcode
					 , prdname
					 , shortexp
					 , content
					 , prdimg_R
				  from wiz_product2
				 where showset != 'N' 
					$search_sql
				 order by prior desc
				 limit $start, $rows
		  ) as wp
		  left join wiz_cprelation2 as wcp 
		    on wp.prdcode = wcp.prdcode
		  left join wiz_category2 as wc 
		    on wcp.catcode = wc.catcode
		   and wc.catuse != 'N' 
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)) {

		$purl = "/".$row['purl'];

		$row['content']  = cut_str(strip_tags($row['content']), 300);
		$row['shortexp'] = cut_str(strip_tags($row['shortexp']), 300);

		$row['prdname'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['prdname']);
		$row['content'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['content']);
		$row['shortexp'] = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['shortexp']);

	if($mobile_key == "M"){
		if(!empty($row['catname'])) $catname = $row['catname'];
		else $catname = "";
	} else {
		if(!empty($row['catname'])) $catname = "[".$row['catname']."]";
		else $catname = "";
	}


		$prdurl = $purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param;
		$content = str_replace("\n","",$row['content']);
		//$prdimg = "/twcenter/data/product2/".$row['prdimg_R'];
    /** 제품 이미지 없을 때 noimg 나오게 하기 **/
		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".$row['prdimg_R'])) $prdimg = $skin_dir."/image/noimg_R.gif";
		else $prdimg = "/twcenter/data/product2/".$row['prdimg_R'];
		/** 제품 이미지 없을 때 noimg 나오게 하기 끝 **/
		$prdprice = number_format((float)($row['prdprice'] ?? 0));
		$prdname = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."'>".$row['prdname']."</a>";
		$shortexp = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."'>".$row['shortexp']."</a>";
		$viewimg = "<a href='".$purl."?ptype=view&prdcode=".$row['prdcode']."&catcode=". $row['catcode'] ."&page=".$page."&".$param."' class='viewmore'>VIEW</a>";

		// 목록파일
		@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_body.php";

		$no--;

	}

	if($total <= 0) {
?>
			<tr>
				<td height="50" align="center" colspan="20">검색된 제품이 없습니다.</td>
			</tr>
<?php
	}

	// 하단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_foot.php";

// 페이지
} else if(!strcmp($stype, "page")) {

	if(!empty($bbs_page)) $param .= "&bbs_page=".$bbs_page;
	if(!empty($prd_page)) $param .= "&prd_page=".$prd_page;


	// 페이지내용
	if(empty($total_searchkey)) $search_sql = " idx = '' ";
	else $search_sql = " (instr(content, '$total_searchkey') > 0) ";

	$sql = "
		select count(idx) as cnt 
		  from wiz_page 
		 where 
			$search_sql
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$total = $row['cnt'];

	// 상단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_head.php";

	$idx = 0;
	$rows = $page_rows;
	$lists = $page_lists;
	if($rows == "") $rows = "10";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$page_page || $page_page > $page_count) $page_page = 1;
	$start = ($page_page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select url
			 , title
			 , content
		  from wiz_page 
		 where 
			$search_sql 
		 order by idx desc 
		 limit $start, $rows
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)) {

		$purl     = "/".$row['url'];
		$pagename = $row['title'];

		$row['content'] = cut_str(strip_tags($row['content']), 300);

		$content = str_replace($total_searchkey, "<span style='background-color:".$search_bg."; color:".$search_color.";'>".$total_searchkey."</span>", $row['content']);

		// 목록파일
		@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_body.php";

		$no--;

	}

	if($total <= 0) {
?>
			<tr>
				<td height="30" align="center" colspan="20">검색된 페이지가 없습니다.</td>
			</tr>

<?php
	}

	// 하단파일
	@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/".$stype."_list_foot.php";

}

?>