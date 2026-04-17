<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/msg_info.php';

// 검색 파라미터
$param = "mode=$mode";
if($page != "") $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

// 쪽지작성
if($submode == "insert"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 입력데이터
	$se_id = $wiz_session['id'];
	$se_name = $wiz_session['name'];
	$se_name = str_replace("\"","&quot;",$se_name);
	$re_name = str_replace("\"","&quot;",$re_name);
	$subject = str_replace("\"","&quot;",$subject);

	// 첨부파일 업로드
	include WIZHOME_PATH."/message/upfile.inc";

	if(empty($re_id) && !empty($seluser)) {

		$sellist = explode("|", $seluser);

		for($ii = 0; $ii < count($sellist); $ii++) {
			$id = $sellist[$ii];
			if(!empty($id)) {
				$sql = "select name from wiz_member where id = '$id'";
				$result = query($sql) or error("sql_error");
				$row = sql_fetch_arr($result);

				$re_name = $row['name'];
				$re_id = $id;

				$sql = "insert into wiz_message (idx,se_id,se_name,re_id,re_name,subject,content,upfile,upfile_name,addinfo1,addinfo2,addinfo3,wdate,status,re_status,se_status)
								values('','$se_id','$se_name','$re_id','$re_name','$subject','$content','$upfile_tmp','$upfile_name','$addinfo1','$addinfo2','$addinfo3',now(),'N','Y','Y')";
				query($sql) or error("sql_error");
				$midx = mysqli_insert_id($connect);

				save_point("MSG", $se_id, "", "", "", $midx);
			}
		}

	} else if(!empty($re_id)) {
		$sql = "insert into wiz_message (idx,se_id,se_name,re_id,re_name,subject,content,upfile,upfile_name,addinfo1,addinfo2,addinfo3,wdate,status,re_status,se_status)
						values('','$se_id','$se_name','$re_id','$re_name','$subject','$content','$upfile_tmp','$upfile_name','$addinfo1','$addinfo2','$addinfo3',now(),'N','Y','Y')";
		query($sql) or error("sql_error");
		$midx = mysqli_insert_id($connect);
		save_point("MSG", $se_id, "", "", "", $midx);
	}


	echo "<script>alert('쪽지가 발송되었습니다.'); self.close(); //document.location='$PHP_SELF?ptype=member&$param';</script>";

// 쪽지 수정
}else if($submode == "modify"){

// 답글작성
}else if($submode == "reply"){

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	// 입력데이터
	$se_id = $wiz_session['id'];
	$se_name = $wiz_session['name'];
	$se_name = str_replace("\"","&quot;",$se_name);
	$re_name = str_replace("\"","&quot;",$re_name);
	$subject = str_replace("\"","&quot;",$subject);

	// 첨부파일 업로드
	include WIZHOME_PATH."/message/upfile.inc";

	$sql = "insert into wiz_message (idx,se_id,se_name,re_id,re_name,subject,content,upfile,upfile_name,addinfo1,addinfo2,addinfo3,wdate,status,re_status,se_status)
					values('','$se_id','$se_name','$re_id','$re_name','$subject','$content','$upfile_tmp','$upfile_name','$addinfo1','$addinfo2','$addinfo3',now(),'N','Y','Y')";
	query($sql) or error("sql_error");
	$midx = mysqli_insert_id($connect);
	save_point("MSG", $se_id, "", "", "", $midx);

	echo "<script>window.opener.document.location.href = window.opener.document.URL; document.location='$PHP_SELF?ptype=view&idx=$idx&$param';</script>";

// 게시물 삭제
}else if($submode == "delete"){

	$sql = "select se_id, upfile, re_status, se_status from wiz_message where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$msg_row = sql_fetch_arr($result);

	$upfile_path = WIZHOME_PATH."/data/message/".$msg_row['se_id'];		// 업로드파일 위치

	if(!strcmp($mode, "receive")) {

		if(!strcmp($msg_row['se_status'], "N")) {

			if(!empty($msg_row['upfile'])) @unlink($upfile_path."/".$msg_row['upfile']);
			$sql = "delete from wiz_message where idx = '$idx'";
			query($sql) or error("sql_error");

		} else {

			$sql = "update wiz_message set re_status = 'N' where idx = '$idx'";
			query($sql) or error("sql_error");

		}

		$sql = "select max(idx) as maxidx from wiz_message where re_id = '$wiz_session['id']' and re_status != 'N'";
		$result = query($sql) or error("sql_error");
		$row = sql_fetch_arr($result);
		$maxidx = $row['maxidx'];

	} else if(!strcmp($mode, "send")) {

		if(!strcmp($msg_row['re_status'], "N")) {

			if(!empty($msg_row['upfile'])) @unlink($upfile_path."/".$msg_row['upfile']);
			$sql = "delete from wiz_message where idx = '$idx'";
			query($sql) or error("sql_error");

		} else {

			$sql = "update wiz_message set se_status = 'N' where idx = '$idx'";
			query($sql) or error("sql_error");

		}

		delete_point("MSG", $wiz_session['id'], "", "", "", $idx);

		$sql = "select max(idx) as maxidx from wiz_message where se_id = '$wiz_session['id']' and se_status != 'N'";
		$result = query($sql) or error("sql_error");
		$row = sql_fetch_arr($result);
		$maxidx = $row['maxidx'];

	}

	if(!empty($maxidx)){
		echo "<script>alert('삭제 되었습니다.'); window.opener.document.location.href = window.opener.document.URL; document.location='$PHP_SELF?ptype=view&idx=$maxidx&$param';</script>";
	} else {
		echo "<script>alert('삭제 되었습니다.'); window.opener.document.location.reload(); self.close();</script>";
	}

// 친구추가
}else if($submode == "addfriend"){

	$user = explode("|", $seluser);

	for($ii = 0; $ii < count($user); $ii++) {
		$sql = "select idx from wiz_friend where myid = '$wiz_session['id']' and frdid = '$user[$ii]'";
		$result = query($sql) or error("sql_error");
		$total = sql_fetch_row($result);

		if($total < 1 && !empty($user[$ii])) {
			$sql = "insert into wiz_friend(idx, myid, frdid, wdate) values('', '$wiz_session['id']', '$user[$ii]', now())";
			query($sql) or error("sql_error");
		}
	}

	alert("등록되었습니다.", $prev);


// 친구삭제
}else if($submode == "delfriend"){

	$idx = explode("|", $seluser);

	for($ii = 0; $ii < count($idx); $ii++) {
		$sql = "delete from wiz_friend where idx = '$idx[$ii]'";
		query($sql) or error("sql_error");
	}

	alert("삭제되었습니다.", $prev);

}
?>