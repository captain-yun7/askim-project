<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/sch_info.php';

$bbs_info = $sch_info;

// 검색 파라미터
$param = "code=$code";
if($page != "") $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";


// 글작성
if($mode == "insert"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($sch_info['spam_check'], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($wpermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.inc";

	// 입력데이터
	$memid = $wiz_session['id'];
	$memgrp = $wiz_session['id'];

	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_session['passwd'];
	if($sch_info['editor'] == "Y") $ctype = "H";


	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = query($sql) or error("sql_error");
	if($row = sql_fetch_arr($result)){
		$prino = $row['prino'] + 1;
	}
	$grpno = $prino;

	$sql = "insert into wiz_bbs(idx,code,prino,grpno,depno,notice,category,memid,memgrp,name,nick,email,tphone,hphone,zipcode,address,subject,content,reply,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate,status)
					values('','$code','$prino','$grpno','$depno','$notice','$category','$memid','$memgrp','$name','$nick','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$reply','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name',
					'$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('".$wdate."'), '$status')";

	query($sql) or error("sql_error");

	echo "<script>document.location='$PHP_SELF?ptype=list&$param';</script>";

// 게시물 수정
}else if($mode == "modify"){

	$sql = "select memid,passwd from wiz_bbs where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$bbs_row = sql_fetch_arr($result);

	// 수정권한 체크
	if(
		$mem_level == "0" || 																																				// 전체관리자
		($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)  ||		// 게시판관리자
		($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 													// 자신의글
		($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd)																			// 비밀번호일치
	){
	}else{
		error("비밀번호가 일치하지 않습니다.");
	}


	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.inc";


	// 입력데이터
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($sch_info['editor'] == "Y") $ctype = "H";

	if(!empty($wdate)) $wdate_sql = ", wdate=unix_timestamp('".$wdate."') ";
	
	$sql = "update wiz_bbs set notice='$notice',category='$category',name='$name',nick='$nick',email='$email',tphone='$tphone',hphone='$hphone',zipcode='$zipcode',address='$address',subject='$subject',content='$content',addinfo1='$addinfo1',addinfo2='$addinfo2',addinfo3='$addinfo3',addinfo4='$addinfo4',addinfo5='$addinfo5',ctype='$ctype',privacy='$privacy' $upfile_sql $movie1_sql ,movie2='$movie2',movie3='$movie3' $reply_sql $wdate_sql $count_sql where idx = '$idx'";
	query($sql) or error("sql_error");

	if($privacy == "Y" && ($bbs_row['memid'] == "" || $wiz_session['id'] == "")) $param .= "&passwd=$passwd";

	alert("수정되었습니다.","$PHP_SELF?ptype=view&idx=$idx&$param");

// 답글작성
}else if($mode == "reply"){

	// 작성권한 체크
	if($apermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 첨부파일 업로드
	include WIZHOME_PATH."/bbs/upfile.inc";

	$sql = "select idx,grpno,prino,depno,memid,memgrp,name,email from wiz_bbs where idx='$idx'";
	$result = query($sql) or error("sql_error");
	$row = sql_fetch_arr($result);
	$re_name = $row['name'];
	$re_email = $row['email'];

	$grpno = $row['grpno'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	// 입력데이타
	$memid = $wiz_session['id'];
	$memgrp = $row['memgrp'].",".$memid;
	if($privacy == "Y") $memid = $row['memid'];
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_session['passwd'];
	if($sch_info['editor'] == "Y") $ctype = "H";

	$sql = "update wiz_bbs set prino = prino+1 where code = '$code' and prino >= '$prino'";
	$result = query($sql) or error("sql_error");

	$sql = "insert into wiz_bbs(idx,code,grpno,prino,depno,notice,category,memid,memgrp,name,nick,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate)
					values('','$code','$grpno','$prino','$depno','$notice','$category','$memid','$memgrp','$name','$nick','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name',
					'$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('".$wdate."'))";

	query($sql) or error("sql_error");


	// 답글 메일발송
	if($sch_info['remail'] == "Y"){

		include_once $_SERVER['DOCUMENT_ROOT'].\"/twcenter/inc/site_info.php\";

		$mail_info = get_table("wiz_mailsms", "code = 'bbs'");

		$content = str_replace("\n","<br>",$content);
		$content = "<table width=100% cellpadding=2><tr><td bgcolor=#efefef>&nbsp; <b>제목 : $subject</b></td></tr><tr><td><br></td></tr><tr><td>$content</td></tr></table>";

		$email_subj = "[".$site_info['site_name']."] 문의하신 게시물 답변입니다.";
		$email_msg = str_replace("{MESSAGE}",$content,$mail_info['email_msg']);
		$email_msg = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $email_msg);

		send_mail($site_info['site_name'], $site_info['site_email'], $re_name, $re_email, $email_subj, $email_msg);

	}

	echo "<script>document.location='$PHP_SELF?ptype=list&$param';</script>";

// 게시물 삭제
}else if($mode == "delete"){

	$sql = "select memid,passwd,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,movie1 from wiz_bbs where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$bbs_row = sql_fetch_arr($result);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) || 												// 자신의글
	($bbs_row['passwd'] != "" && $bbs_row['passwd'] == $passwd)																		// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}


	for($ii = 1; $ii <= $upfile_max; $ii++) {

		if($bbs_row[upfile.$ii] != ""){
			@unlink($upfile_path."/".$bbs_row[upfile.$ii]);
			@unlink($upfile_path."/S".$bbs_row[upfile.$ii]);
			@unlink($upfile_path."/M".$bbs_row[upfile.$ii]);
		}

	}

	if($bbs_row['movie1'] != ""){
		@unlink($upfile_path."/".$bbs_row['movie1']);
	}

	$sql = "delete from wiz_bbs where idx = '$idx'";
	query($sql) or error("sql_error");

	alert("삭제되었습니다.","$PHP_SELF?ptype=list&$param");

// 코멘트 입력
}else if($mode == "comment"){

	$ctype = "BBS";
	
	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($sch_info['spam_check'], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($cpermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

	// 욕설체크
	check_abuse($name); check_abuse($content);

	if($name == "") $name = $wiz_session['name'];
	if($passwd == "") $passwd = $wiz_session['passwd'];

	$sql = "insert into wiz_comment(idx,ctype,cidx,star,memid,name,content,passwd,wdate,ip)
					 values('', '$ctype', '$cidx', '$star', '$wiz_session['id']', '$name', '$content', '$passwd', now(), '$_SERVER['REMOTE_ADDR']')";

  $result = query($sql) or error("sql_error");
	$point_cidx = mysqli_insert_id($connect);

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where ctype='$ctype' and cidx='$cidx'";
	$result = query($sql) or error("sql_error");
	$comment = sql_fetch_row($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql) or error("sql_error");

	alert("댓글을 작성하였습니다.", "$PHP_SELF?ptype=view&code=$code&idx=$cidx&$param");

// 코멘트 삭제
}else if($mode == "delco"){

	$sql = "select memid,passwd from wiz_comment where idx='$idx'";
	$result = query($sql) or error("sql_error");
	$row = sql_fetch_arr($result);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																																			// 전체관리자
	($sch_info['bbsadmin'] != "" && strpos($sch_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($row['memid'] != "" && $row['memid'] == $wiz_session['id']) || 																// 자신의글
	($row['passwd'] != "" && $row['passwd'] == $passwd)																						// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}

	$sql = "delete from wiz_comment where idx='$idx'";
  $result = query($sql) or error("sql_error");

  // 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = query($sql) or error("sql_error");
	$comment = sql_fetch_row($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql) or error("sql_error");


	alert("삭제되었습니다.","$PHP_SELF?ptype=view&idx=$cidx&$param");



// 추천하기
}else if($mode == "recom"){

	if(strlen($HTTP_COOKIE_VARS["bbs_recom".$idx])==0){
		
		$sql = "select memid from wiz_bbs where idx = '$idx'";
		$result = query($sql) or error("sql_error");
		$row = sql_fetch_arr($result);
		
		$memid = $row['memid'];

		$sql = "update wiz_bbs set recom = recom + 1 where idx='$idx'";
		$result = query($sql) or error("sql_error");

		setcookie("bbs_recom".$idx, $idx, time()+60*60*24*365);
		
		echo "<script>alert('추천 되었습니다.');document.location='$prev?ptype=view&recom=ok&idx=$idx&$param';</script>";

	}else{

		echo "<script>alert('한번만 추천가능합니다.');document.location='$prev?ptype=view&idx=$idx&$param';</script>";

	}

}

?>