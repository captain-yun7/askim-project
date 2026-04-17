<? if($sch_info['comment'] == "Y"){ ?>
<?php

// 검색 파라미터
$c_param = "code=$code";
if($page != "") $c_param .= "&page=$page";
if($category != "") $c_param .= "&category=$category";
if($searchkey != "") $c_param .= "&searchopt=$searchopt&searchkey=$searchkey";

?>
<? get_spam_check(); // 자동등록글체크 ?>
<script language="javascript">
<!--
function delComment(idx){
	var url = "save.php?mode=delco&code=<?=$code?>&bbs_idx=<?=$idx?>&idx=" + idx;
	window.open(url, "delComment", "height=175, width=300, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=250, top=250");
}

function memberCheck(){
<?
	if($cpermi < $mem_level){
		echo "alert('작성권한이 없습니다.');";
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

	$writer = $wiz_session['name'];

	$sql = "select nick from wiz_member where id = '$wiz_session['id']'";
	$result = query($sql) or error("sql_error");
	$mem_info = sql_fetch_arr($result);
	
	$nick = $mem_info['nick'];
	
	if(!strcmp($bbs_info['name_type'], "NICK") && !empty($nick)) $writer = $nick;
	
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
		$codel_btn = "<a href='$PHP_SELF?ptype=passwd&mode=delco&cidx=$idx&idx=$com_row['idx']&$c_param'><image src='$skin_dir/image/ic_del.gif' border='0' alt='삭제' /></a>";

		include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/com_body.php';

	}

	include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/com_foot.php';

}