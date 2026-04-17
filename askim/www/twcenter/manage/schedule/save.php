<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>

<?
$upfile_path = "../../data/bbs/".$code;						// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
$S_width = 120; $S_height = 120;									// 스몰섬네일 크기
$M_width = 600; $M_height = 600;									// 중간섬네일 크기

// 검색 파라미터
$param = "code=$code&idx=$idx&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";

/*
작업자명	: 이상민
작업일시	: 2020-03-23
작업내용	: inquiry일때 addinfo3 배열 implode
*/
switch($code){
	case "inquiry":
		$addinfo3 = is_array($addinfo3) ? implode("/", $addinfo3) : $addinfo3;
		$addinfo6 = is_array($addinfo6) ? implode("/", $addinfo6) : $addinfo6; 
		$addinfo9 = $able_sDay." ".$able_sTime.":00:00";
		$addinfo10 = $able_eDay." ".$able_eTime.":00:00";
		break;
}

if(empty($wdate)) $wdate = date('Y-m-d H:i:s');


// 게시물 입력
if($mode == "insert"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_arr($result)){
		$prino = $row['prino'] + 1;
	}
	$grpno = $prino;
	
	include "./upfile.php";
	
	$memid = $wiz_admin['id'];
	$memgrp = $wiz_admin['id'];
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($bbs_info['editor'] == "Y") $ctype = "H";
	$content = get_text("textarea", $content);
	
	$sql = "insert into wiz_bbs(idx,code,prino,grpno,depno,notice,category,memid,memgrp,name,nick,email,tphone,hphone,zipcode,address,subject,content,reply,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,addinfo6,addinfo7,addinfo8,addinfo9,addinfo10,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate,status)
					values('','$code','$prino','$grpno','$depno','$notice','$category','$memid','$memgrp','$name','$nick','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$reply','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$addinfo6','$addinfo7','$addinfo8','$addinfo9','$addinfo10','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name',
					'$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('".$wdate."'), '$status')";
	query($sql) or error("sql error");
   
	complete("게시물이 작성되었습니다.","list.php?code=$code&$menucodeParam");

// 게시물 수정
}else if($mode == "update"){

	switch($code){
		case "inquiry":
			$sql = " select count(*) as cnt from wiz_bbs where code = 'online' and addinfo1 = '".$idx."' and addinfo2 < '".$addinfo1."' ";
			$row = sql_fetch($sql);
			if($row["cnt"] > 0){
				complete("예약가능일 이전에 예약내역이 존재하여 일정을 변경할 수 없습니다.","list.php?code=$code&page=$page&$menucodeParam");
				exit;
			}
			break;
	}

	include "./upfile.php";
	
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($bbs_info['editor'] == "Y") $ctype = "H";
	$content = get_text("textarea", $content);
	
	$sql = "update wiz_bbs set notice='$notice',category='$category',name='$name',nick='$nick',email='$email',tphone='$tphone',hphone='$hphone',zipcode='$zipcode',address='$address',subject='$subject',content='$content',addinfo1='$addinfo1',addinfo2='$addinfo2',addinfo3='$addinfo3',addinfo4='$addinfo4',addinfo5='$addinfo5',addinfo6='$addinfo6',addinfo7='$addinfo7',addinfo8='$addinfo8',addinfo9='$addinfo9',addinfo10='$addinfo10',ctype='$ctype',privacy='$privacy' $upfile_sql $movie1_sql,movie2='$movie2',movie3='$movie3',count='$count',wdate=unix_timestamp('$wdate') where idx = '$idx'";
	
	query($sql) or error("sql error");
   
	complete("게시물이 수정되었습니다.","view.php?$param");




// 답글작성
}else if($mode == "reply"){

	$sql = "select idx,grpno,prino,depno,memid,memgrp,name,email from wiz_bbs where idx='$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);
	$re_name = $row['name'];
	$re_email = $row['email'];

	$grpno = $row['grpno'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	include "./upfile.php";
	
	$memid = $wiz_admin['id'];
	$memgrp = $row['memgrp'].",".$memid;
	if($privacy == "Y") $memid = $row['memid'];
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($bbs_info['editor'] == "Y") $ctype = "H";
	$content = addslashes($content);
	
	$sql = "update wiz_bbs set prino = prino+1 where code = '$code' and prino >= '$prino'";
	$result = query($sql) or error("sql error");

	$sql = "insert into wiz_bbs(idx,code,prino,grpno,depno,notice,category,memid,memgrp,name,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile1_name,upfile2_name,upfile3_name,passwd,count,recom,comment,ip,wdate) 
					values('','$code','$prino','$grpno','$depno','$notice','$category','$memid','$memgrp','$name','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1','$upfile2','$upfile3','$upfile1_name','$upfile2_name','$upfile3_name','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('$wdate'))"; 

	query($sql) or error("sql error");


	// 답글 메일발송
	if($bbs_info['remail'] == "Y"){
		
		include "../../inc/bbs_info.php";
		$mail_info = get_table("wiz_mailsms", "code = 'bbs'");
		
		$content = str_replace("\n","<br>",$content);
		$content = "<table width=100% cellpadding=2><tr><td bgcolor=#efefef>&nbsp; <b>제목 : $subject</b></td></tr><tr><td><br></td></tr><tr><td>$content</td></tr></table>";

		$email_subj = "[".$site_info['site_name']."] 문의하신 게시물 답변입니다.";
		$email_msg = str_replace("{MESSAGE}",$content,$mail_info['email_msg']);
		$email_msg = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $email_msg);
		
		send_mail($site_info['site_name'], $site_info['site_email'], $re_name, $re_email, $email_subj, $email_msg);
		
	}
	
	complete("답글이 작성되었습니다.","list.php?code=$code&page=$page&$menucodeParam");



// 글삭제
}else if($mode == "delete"){
	switch($code){
		case "inquiry":
			$sql = " select count(*) as cnt from wiz_bbs where code = 'online' and addinfo1 = '".$idx."' ";
			$row = sql_fetch($sql);
			if($row["cnt"] > 0){
				complete("예약내역이 존재하여 일정을 삭제할 수 없습니다.","list.php?code=$code&page=$page&$menucodeParam");
				exit;
			}
			break;
	}

	$sql = "select upfile1,upfile2,upfile3 from wiz_bbs where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);

  if($bbs_row['upfile1'] != ""){
		@unlink($upfile_path."/".$bbs_row['upfile1']);
		@unlink($upfile_path."/S".$bbs_row['upfile1']);
		@unlink($upfile_path."/M".$bbs_row['upfile1']);
	}
	if($bbs_row['upfile2'] != ""){
		@unlink($upfile_path."/".$bbs_row['upfile2']);
		@unlink($upfile_path."/S".$bbs_row['upfile2']);
		@unlink($upfile_path."/M".$bbs_row['upfile2']);
	}
	if($bbs_row['upfile3'] != ""){
		@unlink($upfile_path."/".$bbs_row['upfile3']);
		@unlink($upfile_path."/S".$bbs_row['upfile3']);
		@unlink($upfile_path."/M".$bbs_row['upfile3']);
	}
	
	$sql = "delete from wiz_bbs where idx = '$idx'";
	query($sql) or error("sql error");
	
	complete("게시물이 삭제되었습니다.","list.php?$param");
	


// 다중삭제
}else if($mode == "delbbs"){

	$array_selbbs = explode("|",$selbbs);
	for($ii=0;$ii<count($array_selbbs);$ii++){
		
		$idx = $array_selbbs[$ii];
		$sql = "select upfile1,upfile2,upfile3 from wiz_bbs where idx = '$idx'";
		$result = query($sql) or error("sql error");
		$bbs_row = sql_fetch_arr($result);

		if($bbs_row['upfile1'] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile1']);
			@unlink($upfile_path."/S".$bbs_row['upfile1']);
			@unlink($upfile_path."/M".$bbs_row['upfile1']);
		}
		if($bbs_row['upfile2'] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile2']);
			@unlink($upfile_path."/S".$bbs_row['upfile2']);
			@unlink($upfile_path."/M".$bbs_row['upfile2']);
		}
		if($bbs_row['upfile3'] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile3']);
			@unlink($upfile_path."/S".$bbs_row['upfile3']);
			@unlink($upfile_path."/M".$bbs_row['upfile3']);
		}
		
		$sql = "delete from wiz_bbs where idx='$idx'";
		query($sql) or error("sql error");

	}

	complete("게시물이 삭제되었습니다.","list.php?$param");




// 첨부파일 삭제
}else if($mode == "delfile"){

	$sql = "select upfile1,upfile2,upfile3 from wiz_bbs where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);

	if($file == "upfile1"){
		$upfile_sql = " upfile1 = '', upfile1_name = ''";
		@unlink($upfile_path."/".$bbs_row['upfile1']);
		@unlink($upfile_path."/S".$bbs_row['upfile1']);
		@unlink($upfile_path."/M".$bbs_row['upfile1']);
	}else if($file == "upfile2"){
		$upfile_sql = " upfile2 = '', upfile2_name = ''";
		@unlink($upfile_path."/".$bbs_row['upfile2']);
		@unlink($upfile_path."/S".$bbs_row['upfile2']);
		@unlink($upfile_path."/M".$bbs_row['upfile2']);
	}else if($file == "upfile3"){
		$upfile_sql = " upfile3 = '', upfile3_name = ''";
		@unlink($upfile_path."/".$bbs_row['upfile3']);
		@unlink($upfile_path."/S".$bbs_row['upfile3']);
		@unlink($upfile_path."/M".$bbs_row['upfile3']);
	}
	
	$sql = "update wiz_bbs set $upfile_sql where idx='$idx'";
	$result = query($sql) or error("sql error");
	
	complete("파일이 삭제되었습니다.","input.php?mode=update&$param");
	
	
// 코멘트 입력
}else if($mode == "comment"){
	
	$ctype = "BBS";
	if(!get_magic_quotes_gpc()) $content = addslashes($content);
	$sql = "insert into wiz_comment(idx,ctype,cidx,star,memid,name,content,passwd,wdate,ip)
					 values('', '$ctype', '$cidx', '$star', '$memid', '$name', '$content', '$passwd', now(), '".$_SERVER['REMOTE_ADDR']."')";
  $result = query($sql) or error("sql error");
	
	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = query($sql) or error("sql error");
	$comment = sql_fetch_row($result);
	
	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql) or error("sql error");
	
	complete("댓글이 작성되었습니다.","view.php?code=$code&idx=$cidx&$menucodeParam");


// 코멘트 삭제
}else if($mode == "delcomment"){
	
	$sql = "delete from wiz_comment where idx='$idx'";
  $result = query($sql) or error("sql error");
  
  // 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = query($sql) or error("sql error");
	$comment = sql_fetch_row($result);
	
	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	query($sql) or error("sql error");
	
  complete("댓글이 삭제되었습니다.","view.php?code=$code&idx=$cidx&$menucodeParam");

}
?>