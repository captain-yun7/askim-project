<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

fileExtCheck();

/*
작업자명	: 이상민
작업일시	: 2021-10-06
작업내용	: vimeo 업로드 컴포넌트 로드
*/
require($_SERVER['DOCUMENT_ROOT']."/comm/API/vimeo/vendor/autoload.php");

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;

if($movie1['size'] > 0 && $use_vimeo) {
	$lib = new Vimeo(VIMEO_CLIENT_ID, VIMEO_CLIENT_SECRET, VIMEO_ACCESS_TOKEN);

	// argv 대신 업로드할 파일경로의 배열을 넣으면 된다.
	$files = $movie1;

	array_shift($files);

	// 업로드된 트랙을 체크하는 배열
	$uploaded = array();

	// 업로드 로직 시작
	foreach ($files as $file_name) {
		// 업로드 시작 로깅
		//print 'Uploading ' . $file_name . "\n";
		try {
			// Vimeo 서버에 업로드하고 해당 파일의 uri을 받는다.
			$uri = $lib->upload($file_name);
			// 해당 파일의 정보를 요청한다.
			$video_data = $lib->request($uri);
			// 업로드가 성공한 파일의 링크를 받는다.
			$link = '';
			if($video_data['status'] == 200) {
				$link = $video_data['body']['link'];
			}
			// 로깅을 위해 저장
			$uploaded[] = array('file' => $file_name, 'api_video_uri' => $uri, 'link' => $link);
		}
		catch (VimeoUploadException $e) {
			//  업로드 오류 발생시 예외처리
			//print 'Error uploading ' . $file_name . "\n";
			//print 'Server reported: ' . $e->getMessage() . "\n";
		}
	}
	// 결과 로깅 및 파일 링크 표시
	//print 'Uploaded ' . count($uploaded) . " files.\n\n";
	foreach ($uploaded as $site_video) {
		//echo print_r($site_video, true)."\n";
		extract($site_video);
		//print "$file is at $link.\n";
	}
	if($link) {
		$movie1_sql = " , movie1='$link' ";
	}
//	echo "</xmp>";
//		exit;
}

// 검색 파라미터
$tmp_param = array();
$tmp_param[] = "code=".$code;
if(isset($page)      && $page)      $tmp_param[] = "page=".$page;
if(isset($searchopt) && $searchopt) $tmp_param[] = "searchopt=".$searchopt;
if(isset($searchkey) && $searchkey) $tmp_param[] = "searchkey=".$searchkey;
if(isset($pos)       && $pos)       $tmp_param[] = "pos=".$pos;
if(isset($code_page) && $code_page) $tmp_param[] = "code_page=".$code_page;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";

//SQL 입력값 문자열 필터
$name     = xss_clean($_POST["name"],true,true);
if($code == "online"){ 
	$email    = sql_filter($_POST["email_1"]."@".$_POST["email_2"]);
	$hphone = $hphone1."-".$hphone2."-".$hphone3;
} else {
	$email    = xss_clean($_POST["email"],true,true);
	$hphone   = (!empty($_POST["hphone"])) ? $_POST["hphone"] : $wiz_session["hphone"];
}
$tphone   = xss_clean($_POST["tphone"]);
$zipcode  = xss_clean($_POST["zipcode"],true,true);
$address  = xss_clean($_POST["address"],true,true);
$subject  = xss_clean($_POST["subject"],true,true);
$content  = xss_clean($_POST["content"],true,true);
//$content  = xss_clean($_POST["content"]);
$reply    = xss_clean($_POST["reply"],true,true);
if($code == "history") {
	$addinfo1 = $_POST["addinfo1"];
	$addinfo2 = $_POST["addinfo2"];
} else {
	$addinfo1 = xss_clean($_POST["addinfo1"],true,true);
	$addinfo2 = xss_clean($_POST["addinfo2"],true,true);
}
$addinfo3 = xss_clean($_POST["addinfo3"],true,true);
$addinfo4 = xss_clean($_POST["addinfo4"],true,true);
$addinfo5 = xss_clean($_POST["addinfo5"],true,true);

$addinfo6  = xss_clean($_POST["addinfo6"],true,true);
$addinfo7  = xss_clean($_POST["addinfo7"],true,true);
$addinfo8  = xss_clean($_POST["addinfo8"],true,true);
$addinfo9  = xss_clean($_POST["addinfo9"],true,true);
$addinfo10 = xss_clean($_POST["addinfo10"],true,true);

if($api_info['daum_map_key'] && $api_info['daum_map_api_key'] && (!$latitude || !$longitude)) {

	$app_key = $api_info['daum_map_api_key'];
	$param_str = urlencode($address);
	$ch = curl_init();
	$headers = array(
		"authorization: KakaoAK $app_key"
	);

	$url = "https://dapi.kakao.com/v2/local/search/address.json?query=".$param_str;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$res =curl_exec($ch);
	curl_close($ch);

	$conv_res = json_decode($res,1);

	$latitude  = $conv_res['documents'][0]['x'];	//-- 위도
	$longitude = $conv_res['documents'][0]['y'];	//-- 경도

}

// 글작성
if($mode == "insert"){

	if(strpos($content,"usemap=")){ //2021-03-11 usemap사용시 뷰페이지에서 리사이징 함수 작동되지 않도록 구분지을 값.
		$addinfo1="usemap";
	}

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");

	if($mem_level != "0" && !strcmp($bbs_info['spam_check'], "Y")) {
		if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
			error("자동등록방지 코드가 존재하지 않습니다.");
		} else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
			error("자동등록방지 코드가 일치하지 않습니다.");
		}
	}

	// 작성권한 체크
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
			";
			$result = query($sql);
			$row = sql_fetch_arr($result);

			if($row['cnt'] <= 0) {
				error($bbs_info['permsg'],$bbs_info['perurl']);
			}

		} else {
			error($bbs_info['permsg'],$bbs_info['perurl']);
		}
	}

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 온라인신청 정원 재확인 (동시접수 방지)
	if($code == "online"){ 
		$sql_sch = "select * from wiz_bbs where code='inquiry' and idx='$addinfo1'";
		$row_sch = sql_fetch($sql_sch);
		$max_person = $row_sch['addinfo4'];
		$sql_chk = "select sum(addinfo4) as total from wiz_bbs where code='$code' and addinfo1='$addinfo1' and addinfo2='$addinfo2' and addinfo3='$addinfo3' and status !='3'";
		$row_chk = sql_fetch($sql_chk);
		if($max_person < $row_chk['total'] + $addinfo4) {
			error("예약 가능 인원을 초과하였습니다.");
		}
	}

	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.php";


	// 입력데이터
	if($memid == "") $memid = $wiz_session['id'];
	$memgrp = $memid;

	$sql = "select nick from wiz_member where id = '".$memid."'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$nick = $row['nick'];

	$name    = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_session['passwd'];
	if($bbs_info['editor'] == "Y") $ctype = "H";

	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = query($sql);
	if($row = sql_fetch_arr($result)){
		$prino = $row['prino'] + 1;
	}
	$grpno = $prino;

	if(empty($wdate)) $wdate = date('Y-m-d H:i:s');

	$upfile_sql     = "";
	$upfie_name_sql = "";

	for($i=1; $i<=12; $i++) {
		$upfile_sql     .= " , upfile".$i."         = '".${'upfile'.$i.'_tmp'}."'          ";
		$upfie_name_sql .= " , upfile".$i."_name    = '".${'upfile'.$i.'_name'}."'         ";
	}

	$sql_com = "";
	$sql_com .= " prdcode               = '$prdcode'                       ";
	$sql_com .= " , code                = '$code'                          ";
	$sql_com .= " , prino               = '$prino'                         ";
	$sql_com .= " , grpno               = '$grpno'                         ";
	$sql_com .= " , depno               = '$depno'                         ";
	$sql_com .= " , star                = '$star'                          ";
	$sql_com .= " , notice              = '$notice'                        ";
	$sql_com .= " , category            = '$category'                      ";
	$sql_com .= " , memid               = '$memid'                         ";
	$sql_com .= " , memgrp              = '$memgrp'                        ";
	$sql_com .= " , name                = '$name'                          ";
	$sql_com .= " , nick                = '$nick'                          ";
	$sql_com .= " , email               = '$email'                         ";
	$sql_com .= " , tphone              = '$tphone'                        ";
	$sql_com .= " , hphone              = '$hphone'                        ";
	$sql_com .= " , zipcode             = '$zipcode'                       ";
	$sql_com .= " , address             = '$address'                       ";
	$sql_com .= " , subject             = '$subject'                       ";
	$sql_com .= " , content             = '$content'                       ";
	$sql_com .= " , reply               = '$reply'                         ";
	$sql_com .= " , addinfo1            = '$addinfo1'                      ";
	$sql_com .= " , addinfo2            = '$addinfo2'                      ";
	$sql_com .= " , addinfo3            = '$addinfo3'                      ";
	$sql_com .= " , addinfo4            = '$addinfo4'                      ";
	$sql_com .= " , addinfo5            = '$addinfo5'                      ";
	$sql_com .= " , addinfo6            = '$addinfo6'                      ";
	$sql_com .= " , addinfo7            = '$addinfo7'                      ";
	$sql_com .= " , addinfo8            = '$addinfo8'                      ";
	$sql_com .= " , addinfo9            = '$addinfo9'                      ";
	$sql_com .= " , addinfo10           = '$addinfo10'                     ";
	$sql_com .= " , ctype               = '$ctype'                         ";
	$sql_com .= " , privacy             = '$privacy'                       ";
	if($upfile_sql_new) {
		$sql_com .= $upfile_sql_new;
	} else {
		$sql_com .= " {$upfile_sql}                                            ";
		$sql_com .= " {$upfie_name_sql}                                        ";
	}
	$sql_com .= " {$movie1_sql}                                       ";
	$sql_com .= " , movie2              = '$movie2'                        ";
	$sql_com .= " , movie3              = '$movie3'                        ";
	$sql_com .= " , passwd              = '$passwd'                        ";
	$sql_com .= " , count               = '$count'                         ";
	$sql_com .= " , recom               = '$recom'                         ";
	$sql_com .= " , comment             = '$comment'                       ";
	$sql_com .= " , ip                  = '$REMOTE_ADDR'                   ";
	$sql_com .= " , wdate               = unix_timestamp('".$wdate."')     ";
	$sql_com .= " , status              = '$status'                        ";
	$sql_com .= " , latitude            = '$latitude'                      ";
	$sql_com .= " , longitude           = '$longitude'                     ";
	/*
if($use_vimeo) {
	echo $sql_com;
	exit;
}*/
	$sql = "INSERT INTO wiz_bbs SET {$sql_com} ";
	query($sql);

	$bidx = mysqli_insert_id($connect);

	if($code=="history" || $code=="history_en" ){
		$addinfo1 = $_POST['addinfo1'];
		$addinfo2 = $_POST['addinfo2'];
		if     (count($addinfo1) > ($addinfo2)) { $cnt = count($addinfo1); }
		else if(count($addinfo1) < ($addinfo2)) { $cnt = count($addinfo2); }
		else									{ $cnt = count($addinfo1); }
		for($ai=0; $ai < $cnt; $ai++){
			$history_sql = "insert into wiz_history(bidx,month,content) values('".$bidx."','".get_text("input", $addinfo1[$ai])."','".get_text("input", $addinfo2[$ai])."')";
			query($history_sql);
		}
		
	}

	// 관리자에게 SMS 발송
	if(!strcmp($bbs_info['sms'], "Y")) {
		include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

		# 글작성시 작성자이름입력란이 있을경우 없을경우
		if($name != "") $se_name = $name;
		else if($wiz_session['id'] != "") $se_name = $wiz_session['id'];
		else $se_name = $site_info['site_name'];

		$se_num  = $site_info['site_tel'];
		$re_num  = $site_info['site_hand'];

		$message = "[".$bbs_info['title']."] ".$se_name."님의 글이 등록되었습니다";
		$massage_iconv = str_conv($message, "euc-kr");
		if($se_num && $re_num && $site_info['sms_use'] == "Y") {
			send_sms($se_num, $re_num, $massage_iconv, $se_name);
		}
	}


	// 관리자에게 EMAIL 발송
	if(!strcmp($bbs_info['email'], "Y")) {
		
		## 보내는이, 발송메일 관리자걸로.
		$send_email = $site_info['site_email'];
		$se_name = $site_info['site_name'];
		
		include "bbs_email.php";
		$bbs_emailSub = "[".$bbs_info['title']."] ".$se_name."님의 글이 등록되었습니다";
		$email_content = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $email_content);
		$email_content = stripslashes($email_content);
		
		$email = explode(",",$site_info['bbs_email']);
		for($ei=0; $ei < sizeof($email); $ei++){
		send_mail($se_name, $send_email, $site_info['site_name'], $email[$ei], $bbs_emailSub, $email_content);
		}
	}
	

	save_point("BBS", $memid, "write", $bidx);
	if(!empty($webeditImg) && sizeof($webeditImg) > 0) {
		for($i=0;$i<sizeof($webeditImg);$i++){
			$file_url_ar = explode("/",$webeditImg[$i]);
			$file_source = $file_url_ar[sizeof($file_url_ar) - 1];

			$fsql = " select idx from wiz_webedit_file where bbstable='wiz_bbs' and code = '".$code."' and tmp_token = '".$tmp_token."' and file_source = '".$file_source."' and bidx = '' ";
			$frow = sql_fetch($fsql);
			if($frow["idx"] != ""){
				$isql = " update wiz_webedit_file set bidx = '".$bidx."' where idx = '".$frow["idx"]."' ";
				$iresult = query($isql);
			}
		}

		$isql = $iresult = $fsql = $frow = "";

		unset($isql);
		unset($iresult);
		unset($fsql);
		unset($frow);
	}
	switch($code){
		case "online":
			if($mem_level != "0"){
				if($wiz_session["id"] != ""){
					echo "<script>alert('접수가 완료되었습니다.');document.location='/member/inquiry.php';</script>";
				} else {
					echo "<script>alert('접수가 완료되었습니다.');document.location='/online/inquiry_search.php';</script>";
				}
			} else {
				echo "<script>document.location='$PHP_SELF?ptype=list&$param';</script>";
			}
			break;
		default:
			echo "<script>document.location='$PHP_SELF?ptype=list&$param';</script>";
	}

// 게시물 수정
}else if($mode == "modify"){

	$sql = "select memid,passwd from wiz_bbs where idx = '$idx'";
	$result = query($sql);
	$bbs_row = sql_fetch_arr($result);

	// 수정권한 체크
	if(
		$mem_level == "0" || 																																				// 전체관리자
		($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||		// 게시판관리자
		($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 													// 자신의글
		($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd)																			// 비밀번호일치
	){
	}else{
		error("비밀번호가 일치하지 않습니다.");
	}

	$sql = "select nick from wiz_member where id = '".$bbs_row['memid']."'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$nick = $row['nick'];

	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.php";

	// 입력데이터
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($bbs_info['editor'] == "Y") $ctype = "H";

	// 답변권한이 있을때 답변(reply), 처리상태(status) 수정
	if($apermi >= $mem_level) {
		$reply_sql = ", reply='$reply', status='$status' ";
	}

	if(!empty($wdate)) $wdate_sql = ", wdate=unix_timestamp('".$wdate."') ";
	if(!empty($count)) $count_sql = ", count='$count' ";

	$sql_com = "";
	$sql_com .= " star                 = '$star'                      ";
	$sql_com .= " , notice             = '$notice'                    ";
	$sql_com .= " , category           = '$category'                  ";
	$sql_com .= " , name               = '$name'                      ";
	$sql_com .= " , nick               = '$nick'                      ";
	$sql_com .= " , email              = '$email'                     ";
	$sql_com .= " , tphone             = '$tphone'                    ";
	$sql_com .= " , hphone             = '$hphone'                    ";
	$sql_com .= " , zipcode            = '$zipcode'                   ";
	$sql_com .= " , address            = '$address'                   ";
	$sql_com .= " , subject            = '$subject'                   ";
	$sql_com .= " , content            = '$content'                   ";
	$sql_com .= " , addinfo1           = '$addinfo1'                  ";
	$sql_com .= " , addinfo2           = '$addinfo2'                  ";
	$sql_com .= " , addinfo3           = '$addinfo3'                  ";
	$sql_com .= " , addinfo4           = '$addinfo4'                  ";
	$sql_com .= " , addinfo5           = '$addinfo5'                  ";
	$sql_com .= " , addinfo6           = '$addinfo6'                  ";
	$sql_com .= " , addinfo7           = '$addinfo7'                  ";
	$sql_com .= " , addinfo8           = '$addinfo8'                  ";
	$sql_com .= " , addinfo9           = '$addinfo9'                  ";
	$sql_com .= " , addinfo10          = '$addinfo10'                 ";
	$sql_com .= " , ctype              = '$ctype'                     ";
	$sql_com .= " , privacy            = '$privacy'                   ";
	$sql_com .= " , latitude           = '$latitude'                  ";
	$sql_com .= " , longitude          = '$longitude'                 ";
	if($upfile_sql_new) {
		$sql_com .= $upfile_sql_new;
	} else {
		$sql_com .= " {$upfile_sql}                                            ";
	}
	$sql_com .= " {$movie1_sql}                                       ";
	$sql_com .= " , movie2             = '$movie2'                    ";
	$sql_com .= " , movie3             = '$movie3'                    ";
	$sql_com .= " {$reply_sql}                                        ";
	$sql_com .= " {$wdate_sql}                                        ";
	$sql_com .= " {$count_sql}                                        ";

	$sql = "UPDATE wiz_bbs SET {$sql_com} WHERE idx = '$idx' ";
	query($sql);

	if($code=="history" || $code=="history_en"){
		$addinfo1 = $_POST['addinfo1'];
		$addinfo2 = $_POST['addinfo2'];
		if     (count($addinfo1) > ($addinfo2)) { $cnt = count($addinfo1); }
		else if(count($addinfo1) < ($addinfo2)) { $cnt = count($addinfo2); }
		else									{ $cnt = count($addinfo1); }

		query("delete from wiz_history where bidx='".$idx."'");

		for($ai=0; $ai < $cnt; $ai++){
			$history_sql = "insert into wiz_history(bidx,month,content) values('".$idx."','".get_text("input", $addinfo1[$ai])."','".get_text("input", $addinfo2[$ai])."')";
			query($history_sql);
		}
		
	}

	if($privacy == "Y" && ($bbs_row['memid'] == "" || $wiz_session['id'] == "")) $param .= "&passwd=$passwd";

	if(!empty($webeditImg) && sizeof($webeditImg) > 0){
		for($i=0;$i<sizeof($webeditImg);$i++){
			$file_url_ar = explode("/",$webeditImg[$i]);
			$file_source = $file_url_ar[sizeof($file_url_ar) - 1];

			$fsql = " select idx from wiz_webedit_file where bbstable='wiz_bbs' and code = '".$code."' and tmp_token = '".$tmp_token."' and file_source = '".$file_source."' and bidx = ''";
			$frow = sql_fetch($fsql);
			if($frow["idx"] != ""){
				$isql = " update wiz_webedit_file set bidx = '".$idx."' where idx = '".$frow["idx"]."' ";
				$iresult = query($isql);
			}
		}

		$isql = $iresult = $fsql = $frow = "";

		unset($isql);
		unset($iresult);
		unset($fsql);
		unset($frow);
	}

	switch($code){
		case "online":
			if($mem_level != "0"){
				alert("수정 되었습니다.","/online/inquiry_search.php");
			} else {
				alert("수정 되었습니다.","$PHP_SELF?ptype=view&idx=$idx&$param");
			}
			break;
		default:
			alert("수정 되었습니다.","$PHP_SELF?ptype=view&idx=$idx&$param");
	}

// 답글작성
}else if($mode == "reply"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if($mem_level != "0" && !strcmp($bbs_info['spam_check'], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($apermi < $mem_level) error($bbs_info['permsg'],$bbs_info['perurl']);

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.php";

	$sql = "select idx,grpno,prino,depno,memid,memgrp,name,email from wiz_bbs where idx='$idx'";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$re_name  = $row['name'];
	$re_email = $row['email'];

	$grpno = $row['grpno'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	// 입력데이타
	if($memid == "") $memid = $wiz_session['id'];
	$memgrp = $row['memgrp'].",".$memid;

	$sql = "select nick from wiz_member where id = '$memid'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$nick = $row['nick'];

	if($privacy == "Y") $memid = $row['memid'];

	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_session['passwd'];
	if($bbs_info['editor'] == "Y") $ctype = "H";

	$sql = "update wiz_bbs set prino = prino+1 where code = '$code' and prino >= '$prino'";
	$result = query($sql);

	if(empty($wdate)) $wdate = date('Y-m-d H:i:s');

	$upfile_sql     = "";
	$upfie_name_sql = "";

	for($i=1; $i<=12; $i++) {
		$upfile_sql     .= " , upfile".$i."         = '".${'upfile'.$i.'_tmp'}."'          ";
		$upfie_name_sql .= " , upfile".$i."_name    = '".${'upfile'.$i.'_name'}."'         ";
	}

	$sql_com = "";
	$sql_com .= " prdcode                    = '$prdcode'                          ";
	$sql_com .= " , code                     = '$code'                             ";
	$sql_com .= " , grpno                    = '$grpno'                            ";
	$sql_com .= " , prino                    = '$prino'                            ";
	$sql_com .= " , depno                    = '$depno'                            ";
	$sql_com .= " , star                     = '$star'                             ";
	$sql_com .= " , notice                   = '$notice'                           ";
	$sql_com .= " , category                 = '$category'                         ";
	$sql_com .= " , memid                    = '$memid'                            ";
	$sql_com .= " , memgrp                   = '$memgrp'                           ";
	$sql_com .= " , name                     = '$name'                             ";
	$sql_com .= " , nick                     = '$nick'                             ";
	$sql_com .= " , email                    = '$email'                            ";
	$sql_com .= " , tphone                   = '$tphone'                           ";
	$sql_com .= " , hphone                   = '$hphone'                           ";
	$sql_com .= " , zipcode                  = '$zipcode'                          ";
	$sql_com .= " , address                  = '$address'                          ";
	$sql_com .= " , subject                  = '$subject'                          ";
	$sql_com .= " , content                  = '$content'                          ";
	$sql_com .= " , addinfo1                 = '$addinfo1'                         ";
	$sql_com .= " , addinfo2                 = '$addinfo2'                         ";
	$sql_com .= " , addinfo3                 = '$addinfo3'                         ";
	$sql_com .= " , addinfo4                 = '$addinfo4'                         ";
	$sql_com .= " , addinfo5                 = '$addinfo5'                         ";
	$sql_com .= " , addinfo6                 = '$addinfo6'                         ";
	$sql_com .= " , addinfo7                 = '$addinfo7'                         ";
	$sql_com .= " , addinfo8                 = '$addinfo8'                         ";
	$sql_com .= " , addinfo9                 = '$addinfo9'                         ";
	$sql_com .= " , addinfo10                = '$addinfo10'                        ";
	$sql_com .= " , ctype                    = '$ctype'                            ";
	$sql_com .= " , privacy                  = '$privacy'                          ";
	$sql_com .= " {$upfile_sql}                                                    ";
	$sql_com .= " {$upfie_name_sql}                                                ";
	$sql_com .= " , movie1                   = '$movie1_tmp'                       ";
	$sql_com .= " , movie2                   = '$movie2'                           ";
	$sql_com .= " , movie3                   = '$movie3'                           ";
	$sql_com .= " , passwd                   = '$passwd'                           ";
	$sql_com .= " , count                    = '$count'                            ";
	$sql_com .= " , recom                    = '$recom'                            ";
	$sql_com .= " , comment                  = '$comment'                          ";
	$sql_com .= " , ip                       = '$REMOTE_ADDR'                      ";
	$sql_com .= " , wdate                    = unix_timestamp('".$wdate."')        ";

	$sql = "INSERT INTO wiz_bbs SET {$sql_com} ";
	query($sql);

	// 관리자에게 SMS 발송
	if(!strcmp($bbs_info['sms'], "Y")) {
		include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

		$se_name = $name;
		//$se_num  = $hphone;
		$se_num  = $site_info['site_tel'];
		$re_num  = $site_info['site_hand'];
		$message = "[".$bbs_info['title']."] ".$se_name."님의 글이 등록되었습니다";

		$massage_iconv = str_conv($message, "euc-kr");
		if($se_num && $re_num && $site_info['sms_use'] == "Y") {
			send_sms($se_num, $re_num, $massage_iconv, $se_name);
		}
	}

	// 답글 메일발송
	if($bbs_info['remail'] == "Y"){

		include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

		$mail_info = get_table("wiz_mailsms", "code = 'bbs'");

		$content = str_replace("\n","<br>",$content);
		$content = "<table width=100% cellpadding=2><tr><td bgcolor=#efefef>&nbsp; <b>제목 : $subject</b></td></tr><tr><td><br></td></tr><tr><td>$content</td></tr></table>";

		$email_subj = "[".$site_info['site_name']."] 문의하신 게시물 답변입니다.";
		$email_msg = str_replace("{MESSAGE}",$content,$mail_info['email_msg']);
		$email_msg = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $email_msg);

		send_mail($site_info['site_name'], $site_info['site_email'], $re_name, $re_email, $email_subj, $email_msg);

	}

	if(sizeof($webeditImg) > 0){
		for($i=0;$i<sizeof($webeditImg);$i++){
			$file_url_ar = explode("/",$webeditImg[$i]);
			$file_source = $file_url_ar[sizeof($file_url_ar) - 1];

			$fsql = " select idx from wiz_webedit_file where bbstable='wiz_bbs' and code = '".$code."' and tmp_token = '".$tmp_token."' and file_source = '".$file_source."' and bidx = ''";
			$frow = sql_fetch($fsql);
			if($frow["idx"] != ""){
				$isql = " update wiz_webedit_file set bidx = '".$bidx."' where idx = '".$frow["idx"]."' ";
				$iresult = query($isql);
			}
		}

		$isql = $iresult = $fsql = $frow = "";

		unset($isql);
		unset($iresult);
		unset($fsql);
		unset($frow);
	}

	alert("답글이 작성되었습니다.","$PHP_SELF?ptype=list&$param");


// 게시물 삭제
}else if($mode == "delete"){

	$sql = "
		select memid
			 , passwd
			 , upfile1
			 , upfile2
			 , upfile3
			 , upfile4
			 , upfile5
			 , upfile6
			 , upfile7
			 , upfile8
			 , upfile9
			 , upfile10
			 , upfile11
			 , upfile12
			 , movie1
			 , code
		  from wiz_bbs
		 where idx = '$idx'
	";
	$result = query($sql);
	$bbs_row = sql_fetch_arr($result);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																		// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 							// 자신의글
	($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd)										// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}


	for($ii = 1; $ii <= $upfile_max; $ii++) {

		if($bbs_row['upfile'.$ii] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/S".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/M".$bbs_row['upfile'.$ii]);
		}

	}

	if($bbs_row['movie1'] != ""){
		@unlink($upfile_path."/".$bbs_row['movie1']);
	}

	$sql = "delete from wiz_bbs where idx = '$idx'";
	query($sql);

	delete_point("BBS", $bbs_row['memid'], "write", $idx);

	$sql = " select count(*) as cnt from wiz_webedit_file where bbstable = 'wiz_bbs' and code = '".$bbs_row["code"]."' and bidx = '".$idx."' ";
	$row = sql_fetch($sql);
	if($row["cnt"] > 0){
		$sql2 = " select * from wiz_webedit_file where bbstable = 'wiz_bbs' and code = '".$bbs_row["code"]."' and bidx = '".$idx."' order by idx asc ";
		$result2 = query($sql2);
		for($i=0;$row2 = sql_fetch_arr($result2);$i++){
			$file_del = @unlink($_SERVER["DOCUMENT_ROOT"].$row2["UploadDir"].$row2["file_source"]);
			if($file_del){
				$sql3 = " delete from wiz_webedit_file where idx = '".$row2["idx"]."' ";
				$result3 = query($sql3);
			}
		}
	}

	$sql = $row = $sql2 = $result2 = $row2 = $sql3 = $result3 = "";
	unset($sql);
	unset($row);
	unset($sql2);
	unset($result2);
	unset($row2);
	unset($sql3);
	unset($result3);

	alert("삭제 되었습니다.","$PHP_SELF?ptype=list&$param");



// 다중삭제
}else if($mode == "delbbs"){

	$array_selbbs = explode("|",$selbbs);
	for($ii=0;$ii<count($array_selbbs);$ii++){

		$idx = $array_selbbs[$ii];
		$sql = "
			select memid
				 , passwd
				 , upfile1
				 , upfile2
				 , upfile3
				 , upfile4
				 , upfile5
				 , upfile6
				 , upfile7
				 , upfile8
				 , upfile9
				 , upfile10
				 , upfile11
				 , upfile12
				 , movie1
				 , code
			  from wiz_bbs
			 where idx = '$idx'
		";
		$result = query($sql);
		$bbs_row = sql_fetch_arr($result);

		// 삭제권한 체크
		if(
		$mem_level == "0" ||																																			// 전체관리자
		($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
		($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 												// 자신의글
		($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd)																		// 비밀번호일치
		){
		}else{
			if($passwd) error("비밀번호가 일치하지 않습니다.");
			else error("권한이 없습니다.");
		}


		for($jj = 1; $jj <= $upfile_max; $jj++) {

			if($bbs_row['upfile'.$jj] != ""){
				@unlink($upfile_path."/".$bbs_row['upfile'.$jj]);
				@unlink($upfile_path."/S".$bbs_row['upfile'.$jj]);
				@unlink($upfile_path."/M".$bbs_row['upfile'.$jj]);
			}

		}

		if($bbs_row['movie1'] != ""){
			@unlink($upfile_path."/".$bbs_row['movie1']);
		}

		$sql = "delete from wiz_bbs where idx = '$idx'";
		query($sql);

		delete_point("BBS", $bbs_row['memid'], "write", $idx);

		$sql = " select count(*) as cnt from wiz_webedit_file where bbstable = 'wiz_bbs' and code = '".$bbs_row["code"]."' and bidx = '".$idx."' ";
		$row = sql_fetch($sql);
		if($row["cnt"] > 0){
			$sql2 = " select * from wiz_webedit_file where bbstable = 'wiz_bbs' and code = '".$bbs_row["code"]."' and bidx = '".$idx."' order by idx asc ";
			$result2 = query($sql2);
			for($i=0;$row2 = sql_fetch_arr($result2);$i++){
				$file_del = @unlink($_SERVER["DOCUMENT_ROOT"].$row2["UploadDir"].$row2["file_source"]);
				if($file_del){
					$sql3 = " delete from wiz_webedit_file where idx = '".$row2["idx"]."' ";
					$result3 = query($sql3);
				}
			}
		}

		$sql = $row = $sql2 = $result2 = $row2 = $sql3 = $result3 = "";
		unset($sql);
		unset($row);
		unset($sql2);
		unset($result2);
		unset($row2);
		unset($sql3);
		unset($result3);

	}

	alert("게시물이 삭제되었습니다.","$PHP_SELF?ptype=list&$param");



// 코멘트 입력
}else if($mode == "comment"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");

	if($mem_level != "0" && !strcmp($bbs_info['spam_check'], "Y")) {

		// 자동등록방지 코드 검사
		if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
			error("자동등록방지 코드가 존재하지 않습니다.");
		} else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
			error("자동등록방지 코드가 일치하지 않습니다.");
		}

	}

	// 작성권한 체크
	if($cpermi < $mem_level) error($bbs_info['permsg'],$bbs_info['perurl']);

	if($name == "") $name = $wiz_session['name'];
	if($passwd == "") $passwd = $wiz_session['passwd'];

	$sql = "select nick from wiz_member where id = '".$wiz_session['id']."'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$nick = $row['nick'];

	// 욕설체크
	check_abuse($name); check_abuse($content);

	$ctype = "BBS";

	if($comment_idx){	// 댓글에 댓글

		$sql = "select idx,grpno,prino,depno from wiz_comment where idx='$comment_idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);
		$grpno = $row['grpno'];
		$prino = $row['prino'];
		$depno = ++$row['depno'];

		$sql = "update wiz_comment set prino = prino+1 where cidx = '$cidx' and prino >= '$prino'";
		$result = query($sql);
	}
	else{			// 일반댓글

		$sql = "select max(prino) as prino from wiz_comment where cidx = '$cidx'";
		$result = query($sql);
		if($row = sql_fetch_arr($result)){
			$prino = $row['prino'] + 1;
		}
		$grpno = $prino;
		$depno = 0;
	}

	//comment에 자동링걸기
	function autoLink($contents)
	{
		$pattern = '/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[_0-9a-z-]+)+(:[0-9]{2,4})?\/?';
		$pattern .= '([\.~_0-9a-z-]+\/?)*';
		$pattern .= '(\S+\.[_0-9a-z]+)?';
		$pattern .= '(\?[_0-9a-z#%&=\-\+]+)*/i';
		$replacement = '<a href="\\0" target="_blank">\\0</a>';
		
		return preg_replace($pattern, $replacement, $contents, -1);
	}

	$content = autoLink($content);

	if(!empty($passwd)) $passwd = md5($passwd);

	include WIZHOME_PATH."/bbs/upfile_comment.php";

	$sql_com = "";
	$sql_com .= " ctype                      = '$ctype'                            ";
	$sql_com .= " , cidx                     = '$cidx'                             ";
	$sql_com .= " , prino                    = '$prino'                            ";
	$sql_com .= " , grpno                    = '$grpno'                            ";
	$sql_com .= " , depno                    = '$depno'                            ";
	$sql_com .= " , star                     = '$star'                             ";
	$sql_com .= " , memid                    = '".$wiz_session['id']."'                  ";
	$sql_com .= " , name                     = '$name'                             ";
	$sql_com .= " , nick                     = '$nick'                             ";
	$sql_com .= " , content                  = '$content'                          ";
	$sql_com .= " , passwd                   = '$passwd'                           ";
	$sql_com .= " , wdate                    = now()                               ";
	$sql_com .= " , ip                       = '".$_SERVER['REMOTE_ADDR']."'             ";
	$sql_com .= " , upfile1                  = '$upfile1_tmp'                      ";
	$sql_com .= " , upfile1_name             = '$upfile1_name'                     ";

	$sql = "INSERT INTO wiz_comment SET {$sql_com} ";
	query($sql);

	$point_cidx = mysqli_insert_id($connect);

	if($comment_idx){

		$sql = "SELECT * FROM wiz_comment WHERE idx='$comment_idx'";
		$comment_data = sql_fetch("SELECT * FROM wiz_comment WHERE idx='$comment_idx'");

		$sortby = $comment_data['sortby']."/".$point_cidx;
		query("UPDATE wiz_comment SET sortby='$sortby' WHERE idx='$point_cidx' ");

	} else {

		$sortby = $point_cidx;
		query("UPDATE wiz_comment SET sortby='$sortby' WHERE idx='$point_cidx' ");

	}

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = query($sql);
	$comment = sql_fetch_row($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql);

	save_point("COMMENT", $wiz_session['id'], "", $cidx, $point_cidx);

	echo "<script>document.location='$PHP_SELF?ptype=view&idx=$cidx&$param';</script>";

}else if($mode == "comment_e"){

	$sql = "select memid,passwd,upfile1,upfile1_name from wiz_comment where idx='$comment_idx'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	if(!empty($passwd)){
		$passwd = md5($passwd);
	}

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");

	if($mem_level != "0" && !strcmp($bbs_info['spam_check'], "Y")) {

		// 자동등록방지 코드 검사
		if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
			error("자동등록방지 코드가 존재하지 않습니다.");
		} else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
			error("자동등록방지 코드가 일치하지 않습니다.");
		}

	}

	//권한체크
	if(
	$mem_level == "0" ||																		// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($row['memid'] != "" && $row['memid'] == $wiz_session['id']) || 									// 자신의글
	($row['passwd'] != "" && $row['passwd'] == $passwd)												// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}

	if($name == "")   $name   = $wiz_session['name'];
	if($passwd == "") $passwd = $wiz_session['passwd'];

	$nick = $row['nick'];

	// 욕설체크
	check_abuse($name); check_abuse($content);

	$ctype = "BBS";

	//comment에 자동링걸기
	function autoLink($contents)
	{
		$pattern = '/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[_0-9a-z-]+)+(:[0-9]{2,4})?\/?';
		$pattern .= '([\.~_0-9a-z-]+\/?)*';
		$pattern .= '(\S+\.[_0-9a-z]+)?';
		$pattern .= '(\?[_0-9a-z#%&=\-\+]+)*/i';
		$replacement = '<a href="\\0" target="_blank">\\0</a>';
		
		return preg_replace($pattern, $replacement, $contents, -1);
	}

	$content = autoLink($content);

	include WIZHOME_PATH."/bbs/upfile_comment.php";

	query("UPDATE wiz_comment SET content='$content' $upfile_sql WHERE idx='$comment_idx' ");

	echo "<script>document.location='$PHP_SELF?ptype=view&idx=$cidx&$param';</script>";

// 코멘트 삭제
}else if($mode == "delco"){

	$sql = "select memid,passwd,upfile1,upfile1_name from wiz_comment where idx='$comment_idx'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$passwd = md5($passwd);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																		// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($row['memid'] != "" && $row['memid'] == $wiz_session['id']) || 									// 자신의글
	($row['passwd'] != "" && $row['passwd'] == $passwd)												// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}

	if($row['upfile1'] != ""){
		@unlink($upfile_comm_path."/".$row['upfile1']);
		@unlink($upfile_comm_path."/S".$row['upfile1']);
		@unlink($upfile_comm_path."/M".$row['upfile1']);
	}

	$sql = "delete from wiz_comment where idx='$idx'";
	$result = query($sql);

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = query($sql);
	$comment = sql_fetch_row($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql);

	delete_point("COMMENT", $row['memid'], "", $cidx, $idx);

	alert("삭제 되었습니다.","$PHP_SELF?ptype=view&idx=$cidx&$param");



// 추천하기
}else if($mode == "recom"){

	if(strlen($HTTP_COOKIE_VARS["bbs_recom".$idx])==0){

		$sql = "select memid from wiz_bbs where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$memid = $row['memid'];

		$sql = "update wiz_bbs set recom = recom + 1 where idx='$idx'";
		$result = query($sql);

		setcookie("bbs_recom".$idx, $idx, time()+60*60*24*365, false, true);

		save_point("BBS", $memid, "recom", $idx);
		if(strpos($prev, 'http') !== false && strpos(str_replace('www', '', $prev), str_replace('www', '', $HTTP_HOST)) === false) {
			$prev = "/";
		}

		echo "<script>alert('추천 되었습니다.');document.location='$prev?ptype=view&recom=ok&idx=$idx&$param';</script>";

	}else{

		echo "<script>alert('한번만 추천가능합니다.');document.location='$prev?ptype=view&idx=$idx&$param';</script>";

	}



// 진열순서 변경
} else if(!strcmp($mode, "prino")) {

	$sql = "update wiz_bbs set prino = '$prino' where code = '$code' and idx = '$idx'";
	query($sql);

	echo "<script>window.opener.document.location.href = window.opener.document.URL; document.location='order.php?$param';</script>";



// 진열순서 변경
} else if(!strcmp($mode, "pribbs")) {

	$array_selbbs = explode("|",$selbbs);
	$array_selpri = explode("|",$selpri);
	for($ii=0;$ii<count($array_selbbs);$ii++){

	$idx = $array_selbbs[$ii];
	$prino = $array_selpri[$ii];

	$sql = "update wiz_bbs set prino = '$prino' where code = '$code' and idx = '$idx'";
	query($sql);

	}

	echo "<script>window.opener.document.location.href = window.opener.document.URL; document.location='order.php?$param';</script>";

// 접수상황 변경
} else if($mode == "statuschg"){
	
	$sql = "update wiz_bbs set status = '$status' where code = '$code' and idx = '$idx'";
	query($sql) or error("상태 변경 중 오류가 발생했습니다");
	alert("진행상태가 변경되었습니다.","$PHP_SELF?ptype=list&$param");
}

?>