<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/poll_info.php";

// 검색 파라미터
//$param = "poll_code=$poll_code";
$param = "code=$code";
if($idx != "") $param .= "&idx=$idx";
if($page != "") $param .= "&page=$page";
if($category != "") $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

// 글보기 권한체크
$permsg = $poll_info['permsg'];
$perurl = $poll_info['perurl'];
if($rpermi < $mem_level) error($permsg,$perurl);

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$idx = $_REQUEST["idx"];

if(empty($poll_info['datetype_view'])) $poll_info['datetype_view'] = "%Y-%m-%d";

$sql = "select *, date_format(sdate, '".$poll_info['datetype_view']."') as tmp_sdate, date_format(edate, '".$poll_info['datetype_view']."') as tmp_edate from wiz_poll where idx = '$idx'";
$result = query($sql) or error("sql_error");
$poll_row = sql_fetch_arr($result);

$tdate = date('Y-m-d');
$subject = $poll_row['subject'];
$content = str_replace("\n", "<br>", $poll_row['content']);
$sdate = $poll_row['tmp_sdate'];
$edate = $poll_row['tmp_edate'];
$wdate = $poll_row['wdate'];
$cnt = $poll_row['cnt'];

$list_btn = "<a href='$PHP_SELF?ptype=list' class='btn_w'>리스트</a>";
$polluse = $poll_row['polluse'];

if($poll_info['apermi'] == "M" && $wiz_session['id'] == "") $polluse = "N";
if($poll_row['sdate'] > $tdate || $poll_row['edate'] < $tdate) $polluse = "N";

// 댓글 작성 비밀번호 숨김
if($wiz_session['id'] != ""){
	$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
}

// 스팸글체크기능 사용여부
if(!strcmp($poll_info['spam_check'], "N")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

?>

<script language="JavaScript" type="text/javascript">
<!--

function readCookie(cookiename){
 var Found = false;

 cookiedata = document.cookie;
 if ( cookiedata.indexOf(cookiename) >= 0 ){
   Found = true;
 }
 return Found;
}

function setCookie( name, value, expiredays ){
 var todayDate = new Date();
 todayDate.setDate( todayDate.getDate() + expiredays );
 document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function vote(){
<?php
if($apermi < $mem_level) {
?>
	alert("<?=$permsg?>"); return;
<?php
} else {
?>
	if(!readCookie("wiz_vote<?=$idx?>")){

	var frm = document.voteForm;
	var checkValue = "";
	<?
	for($ii=0;$ii<30;$ii++){
	?>
		if(frm.answer<?=$ii?> != null){
			var voteCheck = false;
			if(frm.answer<?=$ii?>.length == undefined){ //답변개수 1개인 경우, 배열이 아니기때문에 예외처리
				if(frm.answer<?=$ii?>.checked == true){
					 voteCheck = true;
					 checkValue = checkValue + frm.qidx<?=$ii?>.value + ":" + frm.answer<?=$ii?>.value + "/";
				}
			}else{
				for(var i=0; i < frm.answer<?=$ii?>.length; i++){
				  if(frm.answer<?=$ii?>[i].checked == true){
					 voteCheck = true;
					 checkValue = checkValue + frm.qidx<?=$ii?>.value + ":" + frm.answer<?=$ii?>[i].value + "/";
				  }
				}
			}

			if(voteCheck == false){
			  alert('질문에 대한 답변을 선택하세요.'); return;
			}
		}
	<?
	}
	?>
		var url = "<?=$PHP_SELF?>?ptype=save&pidx=<?=$idx?>&checkValue=" + checkValue;
		document.location = url;
	  setCookie("wiz_vote<?=$idx?>", "true", 1);

	}else{
		alert('이미 설문에 참여하셨습니다.');
		return;
	}
<?php
}
?>
}

function delComment(idx){
	var url = "save.php?mode=delco&code=<?=$code?>&bbs_idx=<?=$idx?>&idx=" + idx;
	window.open(url, "delComment", "height=175, width=300, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=250, top=250");
}

function memberCheck(){
<?
	if($cpermi < $mem_level){
		echo "alert('$permsg');";
		if(empty($hide_passwd_start)) {
			echo "document.comment.name.value=''; comment.name.blur(); if(comment.passwd != undefined) comment.passwd.blur();";
		}
		echo "comment.content.blur(); return false;";
	}else {
		echo "return true;";
	}
?>
}

function commentCheck(frm){

	if(memberCheck()){
	   if(frm.name.value == ""){
	      alert("이름을 입력하세요");
	      frm.name.focus();
	      return false;
	   }
	   if(frm.passwd != null && frm.passwd.value == ""){
	      alert("비밀번호를 입력하세요");
	      frm.passwd.focus();
	      return false;
	   }
		if(frm.content.value == ""){
	      alert("내용을 입력하세요");
	      frm.content.focus();
	      return false;
	   }
	  if (frm.vcode != undefined && (hex_md5(frm.vcode.value) != md5_norobot_key)) {
	  	alert("자동등록방지코드를 정확히 입력해주세요.");
	    frm.vcode.focus();
	    return false;
		}

	}else{
		return false;
	}
}

-->
</script>
<?

// 스킨파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_head.php";

if(!strcmp($poll_info['comment'], "Y")) {

	// 검색 파라미터
	$c_param = "code=$code";
	if($page != "") $c_param .= "&page=$page";
	if($category != "") $c_param .= "&category=$category";
	if($searchkey != "") $c_param .= "&searchopt=$searchopt&searchkey=$searchkey";

	get_spam_check(); // 자동등록글체크

	$writer = $wiz_session['name'];

	$sql = "SELECT * FROM wiz_comment WHERE cidx='$idx' order by idx desc";
	$result = query($sql) or error("sql_error");
	$total = sql_fetch_row($result);

	if($rows == "") $rows = "12";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$cpage || $cpage > $page_count) $cpage = 1;
	$start = ($cpage-1)*$rows;
	$no = $total-$start;

	$sql = "SELECT * FROM wiz_comment WHERE cidx='$idx' order by idx desc limit $start, $rows";
	$result = query($sql) or error("sql_error");

	while($com_row = sql_fetch_arr($result)){

	  $name = $com_row['name'];
	  $ip = $com_row['ip'];
	  $wdate = $com_row['wdate'];
		$content = str_replace("\n", "<br>", $com_row['content']);

		// 버튼설정
		$codel_btn = "<a href='$PHP_SELF?ptype=passwd&mode=delco&cidx=$idx&idx=".$com_row['idx']."&$c_param'><image src='$skin_dir/image/ic_del.gif' border='0' alt='삭제' /></a>";

		include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/com_body.php';
	}

	// 스킨파일
	include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/com_foot.php';
}

// 스킨파일
include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/view_foot.php';
?>