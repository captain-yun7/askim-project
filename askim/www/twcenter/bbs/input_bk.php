<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

// 자동등록글체크
get_spam_check();

if(strcmp($mode, "modify")) {
	if(!check_point($wiz_session['id'], $bbs_info['write_point'])) {
		error($bbs_info['point_msg']);
	}
}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
if($bbs_info['upfile'] > 0) {
//echo "<script type=\"text/javascript\" src=\"/comm/js/upload_file_limit.js\"></script>".PHP_EOL;
}

// 검색 파라미터
$param = "code=$code";
if($page != "")      $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($pos != "")       $param .= "&pos=$pos&code_page=$code_page";

if(SQLInjectXssForward($code) == 1 || SQLInjectXssForward($param) == 1) {
	error('정상적으로 값이 넘어오지 않았습니다.');
	exit;
}


// 버튼설정
$list_btn = "<a href='$PHP_SELF?ptype=list&$param' class='btn_w'>리스트</a>";
$confirm_btn = "<input type='submit' value='확인' class='btn_b' />";
$cancel_btn = "<input type='button' value='취소' onClick='history.go(-1)' style='cursor:pointer;' class='btn_w'>";


// 게시물 정보
if($mode != "insert"){
	$sql = "
		select *
		     , FROM_UNIXTIME(wdate) as wdate 
		  from wiz_bbs 
		 where idx = '$idx'
	";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);
}

if(empty($prdcode)) $prdcode = $bbs_row['prdcode'];

// 글 작성
if($mode == "") $mode = "insert";
if($mode == "insert"){

	if($code == 'history'){
	$content="
	<table class='his_table' summary='연혁에 대한 상세 내용을 월별로 보여줍니다'>
		<caption style='display:none; font-size:0; line-height:0;'>연혁 상세목록</caption>
		<tbody>
		  <tr>
			<th>01</th>
			<td>연혁을 넣으시면 됩니다.</td>
		  </tr>
		</tbody>
	</table>
	";}

	if($wpermi < $mem_level) {
		// 구매회원 체크
		if(!strcmp($wpermi, "-1")) {

			$sql = "
				select count(idx) as cnt 
				  from wiz_basket as wb 
				  left join wiz_order as wo 
				    on wb.orderid = wo.orderid
				 where wb.prdcode = '$prdcode' 
				   and wo.status = 'DC' 
				   and wo.send_id = '".$wiz_session['id']."'
			";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_arr($result);

			if($row['cnt'] <= 0) {
				error($bbs_info['permsg'],$bbs_info['perurl']);
			}

		} else {
			error($bbs_info['permsg'],$bbs_info['perurl']);
		}
	}

	$name  = $wiz_session['name'];
	$email = $wiz_session['email'];

	$bbs_row['wdate'] = date('Y-m-d H:i:s');

	$sql = "
		select nick 
		  from wiz_member 
		 where id = '".$wiz_session['id']."'
	";
	$result = query($sql) or error("sql error");
	$mem_info = sql_fetch_arr($result);

	$nick = $mem_info['nick'];

	if((!strcmp($bbs_info['name_type'], "nick") || !strcmp($bbs_info['name_type'], "inick")) && !empty($nick)) $name = $nick;

	if($bbs_info['privacy'] == "Y") $privacy_checked = "checked";

	// 비밀번호숨김
	if(
	$mem_level == "0" || 																				// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false) ||		// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||								// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 개인정보취급방침 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)		// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

	// 답변권한이 없을 때 숨김
	if($apermi < $mem_level) {
		$hide_reply_start = "<!--"; $hide_reply_end = "-->";
	}


	switch($code){
		case "online":
			if($sch_idx != "") $addinfo1 = $sch_idx;
			if($sch_date != "") $addinfo2 = $sch_date;
			if($sch_time != "") $addinfo3 = $sch_time;
			break;
	}
// 글 수정
}else if($mode == "modify"){

	$name       = $bbs_row['name'];
	$email      = $bbs_row['email'];
	$subject    = $bbs_row['subject'];
	$content    = $bbs_row['content'];
	$reply      = $bbs_row['reply'];
	$star       = $bbs_row['star'];

	$tphone     = $bbs_row['tphone'];
	$hphone     = $bbs_row['hphone'];
	$zipcode    = $bbs_row['zipcode'];
	$address    = $bbs_row['address'];

	$addinfo1   = $bbs_row['addinfo1'];
	$addinfo2   = $bbs_row['addinfo2'];
	$addinfo3   = $bbs_row['addinfo3'];
	$addinfo4   = $bbs_row['addinfo4'];
	$addinfo5   = $bbs_row['addinfo5'];

	$addinfo6   = $bbs_row['addinfo6'];
	$addinfo7   = $bbs_row['addinfo7'];
	$addinfo8   = $bbs_row['addinfo8'];
	$addinfo9   = $bbs_row['addinfo9'];
	$addinfo10  = $bbs_row['addinfo10'];
	
	$addinfo11   = $bbs_row['addinfo11'];
	$addinfo12   = $bbs_row['addinfo12'];
	$addinfo13   = $bbs_row['addinfo13'];
	$addinfo14   = $bbs_row['addinfo14'];
	$addinfo15  = $bbs_row['addinfo15'];
	
	$addinfo16   = $bbs_row['addinfo16'];
	$addinfo17   = $bbs_row['addinfo17'];
	$addinfo18   = $bbs_row['addinfo18'];
	$addinfo19   = $bbs_row['addinfo19'];
	$addinfo20  = $bbs_row['addinfo20'];

	$latitude   = $bbs_row['latitude'];
	$longitude  = $bbs_row['longitude'];
	$status	 = $bbs_row['status'];

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

	$latitude   = xss_check($latitude);
	$longitude  = xss_check($longitude); 
	$status   = xss_check($status);

	$content = str_replace("fr|om","from",$content);

	if($bbs_row['ctype'] == "T" && $bbs_info['editor'] == "Y" && mobile_check() == false) {
		$content = str_replace("\n", "<br>", $content);
	}

	for($ii = 1; $ii <= $upfile_max; $ii++) {
		if(!empty($bbs_row['upfile'.$ii])) {
			${'upfile'.$ii} = "<input type='checkbox' name='delupfile[]' value='upfile".$ii."'> 삭제 (".$bbs_row['upfile'.$ii.'_name'].")";
		}
	}
	if(!empty($bbs_row['movie1'])) {
		$movie1 = "<input type='checkbox' name='delupfile[]' value='movie1'> 삭제 ($bbs_row['movie1'])";
	}

	$movie2 = $bbs_row['movie2'];
	$movie3 = $bbs_row['movie3'];

	// 비밀번호 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid'])								// 자신에글
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	} 

	// 개인정보취급방침 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
	}

	// 평점
	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row['star'])) ${"star".$ii."_checked"} = "checked";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)		// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

	if($bbs_row['ctype'] == "H")   $ctype_checked   = "checked";
	if($bbs_row['privacy'] == "Y") $privacy_checked = "checked";
	if($bbs_row['notice'] == "Y")  $notice_checked  = "checked";
	if($bbs_row['status'] == "Y")  $status_checked  = "checked";

	// 답변권한이 없을 때 숨김
	if($apermi < $mem_level) {
		$hide_reply_start = "<!--"; $hide_reply_end = "-->";
	}

// 글 답변
}else if($mode == "reply"){

	$sql = "
		select category
			 , subject
			 , content
			 , privacy
			 , passwd
			 , tphone
			 , address
			 , addinfo1
			 , addinfo2
			 , addinfo3
			 , addinfo4
			 , addinfo5
			 , addinfo6
			 , addinfo7
			 , addinfo8
			 , addinfo9
			 , addinfo10
			 , prdcode 
		  from wiz_bbs 
		 where idx = '$idx'
	";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);

	$category  = $bbs_row['category'];
	$subject   = $bbs_row['subject'];
	$content   = $bbs_row['content']."<br/>\n\n==================== 답 변 ====================\n\n";
	$name      = $wiz_session['name'];
	$email     = $wiz_session['email'];

	$tphone    = $bbs_row['tphone'];
	$hphone    = $bbs_row['hphone'];
	$address   = $bbs_row['address'];

	$addinfo1  = $bbs_row['addinfo1'];
	$addinfo2  = $bbs_row['addinfo2'];
	$addinfo3  = $bbs_row['addinfo3'];
	$addinfo4  = $bbs_row['addinfo4'];
	$addinfo5  = $bbs_row['addinfo5'];

	$addinfo6  = $bbs_row['addinfo6'];
	$addinfo7  = $bbs_row['addinfo7'];
	$addinfo8  = $bbs_row['addinfo8'];
	$addinfo9  = $bbs_row['addinfo9'];
	$addinfo10 = $bbs_row['addinfo10'];

	$tphone    = xss_check($tphone);
	$hphone    = xss_check($hphone);
	$zipcode   = xss_check($zipcode);
	$address   = xss_check($address);
	$subject   = xss_check($subject);
	$content   = xss_check($content);

	$addinfo1  = xss_check($addinfo1);
	$addinfo2  = xss_check($addinfo2);
	$addinfo3  = xss_check($addinfo3);
	$addinfo4  = xss_check($addinfo4);
	$addinfo5  = xss_check($addinfo5);

	$addinfo6  = xss_check($addinfo6);
	$addinfo7  = xss_check($addinfo7);
	$addinfo8  = xss_check($addinfo8);
	$addinfo9  = xss_check($addinfo9);
	$addinfo10 = xss_check($addinfo10);

	$prdcode = $bbs_row['prdcode'];

	$bbs_row['wdate'] = date('Y-m-d H:i:s');

	$sql = "select nick from wiz_member where id = '".$wiz_session['id']."'";
	$result = query($sql) or error("sql error");
	$mem_info = sql_fetch_arr($result);

	$nick = $mem_info['nick'];

	if(!strcmp($bbs_info['name_type'], "NICK") && !empty($nick)) $name = $nick;

	if($bbs_info['privacy'] == "Y" || $bbs_row['privacy'] == "Y") $privacy_checked = "checked";

	// 비밀번호 숨김
	if($wiz_session['id'] != ""){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 개인정보취급방침 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)	||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
	}

	// 평점
	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row['star'])) ${"star".$ii."_checked"} = "checked";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)		// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

}

$wdate = $bbs_row['wdate'];
$count = $bbs_row['count'];

// 관리자인 경우 날짜, 조회수 수정가능
if(
$mem_level == "0" || 																			// 전체관리자
($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)		// 게시판관리자
){
} else {
	$hide_twcenter_start = "<!--"; $hide_twcenter_end = "-->";
}

// 게시물 분류
$sql = "
	select idx
		 , catname
		 , catimg 
	  from wiz_bbscat 
	 where code = '".$code."' 
 	   and gubun != 'A' 
	 order by prior asc, idx asc
";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);
if($total > 0) {
	/* select박스형태 */
	$catlist = "<select name=\"category\" class=\"select\" title='게시물 분류를 선택해주세요'  style='height:35px;'>";
	if ($code == "inquiry") {
		$catlist .= "<option value=\"\">:: 질문유형을 선택해주세요. ::</option>";
	} else {
		$catlist .= "<option value=\"\">:: 전체목록 ::</option>";
	}
	while($row = sql_fetch_arr($result)) {
		$catname = $row['catname'];
		$selected = "";
		if($bbs_row['category'] == $row['idx']) $selected = "selected";
		$catlist .= "<option value=\"".$row['idx']."\" ".$selected.">".$catname."</option>";
	}
	$catlist .= "</select> ";
}

// 첨부파일 사용여부
if($bbs_info['upfile'] < 5) { $hide_upfile5_start = "<!--"; $hide_upfile5_end = "-->"; }
if($bbs_info['upfile'] < 4) { $hide_upfile4_start = "<!--"; $hide_upfile4_end = "-->"; }
if($bbs_info['upfile'] < 3) { $hide_upfile3_start = "<!--"; $hide_upfile3_end = "-->"; }
if($bbs_info['upfile'] < 2) { $hide_upfile2_start = "<!--"; $hide_upfile2_end = "-->"; }
if($bbs_info['upfile'] < 1) { $hide_upfile1_start = "<!--"; $hide_upfile1_end = "-->"; }

// 동영상 사용여부
if($bbs_info['movie'] < 3) { $hide_movie3_start = "<!--"; $hide_movie3_end = "-->"; }
if($bbs_info['movie'] < 2) { $hide_movie2_start = "<!--"; $hide_movie2_end = "-->"; }
if($bbs_info['movie'] < 1) { $hide_movie1_start = "<!--"; $hide_movie1_end = "-->"; }

// 스팸글체크기능 사용여부
if($mem_level == "0" || !strcmp($bbs_info['spam_check'], "N") || !strcmp($mode, "modify")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

// 선호도 숨김
if(strcmp($code, "review")) {
	$hide_star_start = "<!--"; $hide_star_end = "-->";
}

if($prdcode != ""){
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
		 where wp.prdcode = '".$prdcode."'
	";
	$prd_result = query($prd_sql);
	$prd_info = sql_fetch_obj($prd_result);

	if(!empty($prd_info->strprice)) $prd_info->sellprice = $prd_info->strprice;
	else $prd_info->sellprice = number_format($prd_info->sellprice)."원";

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_info->prdimg_R)) $prd_info->prdimg_R = "/twcenter/images/noimg_M.gif";
	else $prd_info->prdimg_R = "/twcenter/data/prdimg/".$prd_info->prdimg_R;

	if(mobile_check()){
		$purl = "/m/sub/prdview.php";
	}else{
		$purl = "/".$prd_info->purl; 
	}
?>
<table><tr><td height="20"></td></table>
 <div style="background:#f7f7f7; padding:15px 24px; border:1px solid #dfdfdf; margin-bottom:20px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="110"><img src="<?=$prd_info->prdimg_R?>" width="100" height="100" alt='' ></td>
		  <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td><?=$prd_info->prdname?><br></td>
			  </tr>
			  <tr>
				<td class="11red_01"><font class="price"><?=$prd_info->sellprice?></font></td>
			  </tr>
			  <!--
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td>평점</td>
					  <td><table border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"></td>
						  </tr>
					  </table></td>
					</tr>
				</table></td>
			  </tr>
			  -->
			</table>
		  </td>
		  <td align="right"><a href="<?=$purl?>?ptype=view&prdcode=<?=$prd_info->prdcode?>"<?if(($mem_level == "0") || ($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)){echo "target='_blank'";}?>><img src="<?=$skin_dir?>/image/btn_prdview.gif" border="0" alt='자세히 보기' ></a></td>
		</tr>
	</table>
</div>
<?
}

// 입력스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/input.php";
?>