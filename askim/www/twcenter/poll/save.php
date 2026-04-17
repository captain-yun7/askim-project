<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/poll_info.php';

$idx = $_REQUEST["idx"];


// 설문참여
if($mode == ""){
	
	$poll_list = explode("/",$checkValue);
	
	for($ii=0;$ii<count($poll_list)-1;$ii++){
		$check_list = explode(":",$poll_list[$ii]);
		$sql = "update wiz_polldata set ".$check_list[1]." = ".$check_list[1]."+1 where idx = '".$check_list[0]."'";
		query($sql);
	}
	
	$sql = "update wiz_poll set cnt = cnt + 1 where idx = '$pidx'";
	query($sql);
	
	alert("참여하신 내용이 반영되었습니다.","$PHP_SELF?ptype=view&idx=$pidx");  
	

// 코멘트 입력
}else if($mode == "comment"){
	
	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($poll_info['spam_check'], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	if($name == "") $name = $wiz_session['name'];
	if($passwd == "") $passwd = $wiz_session['passwd'];
	
	// 욕설체크
	check_abuse($name); check_abuse($content);
	
	$ctype = "POLL";
	$sql = "insert into wiz_comment(idx,ctype,cidx,star,memid,name,content,passwd,wdate,ip)
					 values('', '$ctype', '$cidx', '$star', '".$wiz_session['id']."', '$name', '$content', '$passwd', now(), '".$_SERVER['REMOTE_ADDR']."')";
   $result = query($sql) or error("sql_error");

	echo "<script>document.location='$PHP_SELF?ptype=view&idx=$cidx';</script>";
	


// 코멘트 삭제
}else if($mode == "delco"){
	
	$sql = "select memid, passwd from wiz_comment where idx='$idx'";
	$result = query($sql) or error("sql_error");
	$row = sql_fetch_arr($result);
	
	// 삭제권한 체크
	if(
	$mem_level == "0" ||																																			// 전체관리자
	($row['memid'] != "" && $row['memid'] == $wiz_session['id']) || 																// 자신의글
	($row['passwd'] != "" && $row['passwd'] == $passwd)																						// 비밀번호일치
	){
		$passwd = $row['passwd'];
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}
	
	$sql = "delete from wiz_comment where idx='$idx' and passwd = '$passwd'";
	$result = query($sql) or error("sql_error");
  
  alert("댓글이 삭제되었습니다.","$PHP_SELF?ptype=view&idx=$cidx");  


}
?>