<?php
if($bbs_info['comment'] == "Y"){

// 검색 파라미터
$c_param = "code=$code";
if($page != "")      $c_param .= "&page=$page";
if($category != "")  $c_param .= "&category=$category";
if($searchkey != "") $c_param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($pos != "")       $c_param .= "&pos=$pos&code_page=$code_page";

get_spam_check();

?>
<script language="javascript">
<!--
function delComment(idx){
	var url = "save.php?mode=delco&code=<?=$code?>&bbs_idx=<?=$idx?>&idx=" + idx;
	window.open(url, "delComment", "height=175, width=300, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=250, top=250");
}

function memberCheck(){
<?php
	if($cpermi < $mem_level){
		echo "alert('작성권한이 없습니다.');";
		if(empty($hide_passwd_start)) {
			echo "document.comment.name.value=''; comment.name.blur(); if(comment.passwd != undefined) comment.passwd.blur();";
		}
		echo "comment.content.blur(); return false;";
	}else {

		if($mem_level != "0" && $bbs_info['comment_point'] < 0 && empty($wiz_session['id'])) {
			echo "alert('코멘트 작성 시 포인트가 소모됩니다. 로그인 후 이용해주세요.'); document.comment.name.value=''; comment.name.blur(); if(comment.passwd != undefined) comment.passwd.blur(); comment.content.blur(); return false;";
		} else if(!check_point($wiz_session['id'], $bbs_info['comment_point'])) {
			echo "alert('".$bbs_info['point_msg']."'); document.comment.name.value=''; comment.name.blur(); if(comment.passwd != undefined) comment.passwd.blur(); comment.content.blur(); return false;";
		}

		echo "return true;";
	}
?>
}

function commentCheck(frm){

	if(memberCheck()){
		if(frm.name != null && frm.name.value == ""){
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

function comment_toggle(comment)
{
	$.comment_idx   = "#comment_"+comment;
	$(".comment_close").hide();
	$($.comment_idx).show();
}

function comment_e_toggle(comment)
{
	$.comment_e_idx = "#comment_e_"+comment;
	$(".comment_close").hide();
	$($.comment_e_idx).show();
}

/* jQuery를 이용하지 않고 파일업로드시 이미지 미리보기 해줄때 (쓰고싶은사람은 쓸것, 노출될부분에 id 잡아줘라! Made by Cheon) */
function previewIMG(img, previewFile){

	var isIE = (navigator.appName == "Microsoft Internet Explorer");
	var path = img.value;
	var ext = path.substring(path.lastIndexOf('.') + 1).toLowerCase();

	if(ext == "gif" || ext == "jpeg" || ext == "jpg" ||  ext == "png" )
	{
		if(isIE) {
			$('#'+ previewFile).attr('src', path);
		} else {
			if(img.files[0])
			{
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#'+ previewFile).attr('src', e.target.result);
				}
				reader.readAsDataURL(img.files[0]);
			}
		}

	} else {

	}
}

-->
</script>
<?php

	$writer = $wiz_session['name'];
	$sql = "
		SELECT count(*) as total 
		  FROM wiz_comment 
		 WHERE cidx = '".$idx."' 
		 ORDER BY sortby ASC
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$total = $row['total'];

	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/com_head.php";

	$sql = "
		SELECT nick 
		  FROM wiz_member 
		 WHERE id = '".$wiz_session['id']."'
	";
	$result = query($sql);
	$mem_info = sql_fetch_arr($result);

	$nick = $mem_info['nick'];

	if(!strcmp($bbs_info['name_type'], "NICK") && !empty($nick)) $writer = $nick;

	if($rows == "") $rows = "12";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$cpage || $cpage > $page_count) $cpage = 1;
	$start = ($cpage-1)*$rows;
	$no = $total-$start;

	$sql = "
		SELECT * 
		  FROM wiz_comment 
		 WHERE cidx='".$idx."' 
		 ORDER BY cast(sortby as unsigned) ASC, (case when depno = 0 then prino end) desc, (case when depno > 0 then sortby end) asc
		 LIMIT $start, $rows
	";
	$result = query($sql);

	while($com_row = sql_fetch_arr($result)){

		$name  = $com_row['name'];
		$nick  = $com_row['nick'];
		$memid = $com_row['memid'];

		if($com_row['upfile1'] != "") {
			if(img_type($upfile_comm_path."/M".$com_row["upfile1"])) {
				$upfile1 = "첨부파일 : <a href=javascript:viewImg_comment('".$com_row['idx']."');>".$com_row['upfile1_name']."</a>";
				$upfile_del = "<input type='checkbox' name='delupfile' value='upfile1'> 삭제 (".$com_row['upfile1_name'].")";
			} else {
				$upfile1 = "첨부파일 : <a href=\"/twcenter/bbs/down_comment.php?idx=".$com_row['idx']."\">".$com_row['upfile1_name']."</a>";
				$upfile_del = "<input type='checkbox' name='delupfile' value='upfile1'> 삭제 (".$com_row['upfile1_name'].")";
			}
		} else {
			$upfile1    = "";
			$upfile_del = "";
		}

		if(img_type(WIZHOME_PATH."/data/member/".$memid."_icon.gif")) $icon = "<img src='/twcenter/data/member/".$memid."_icon.gif' align='absmiddle' width='20' height='20' alt='' />";
		else if(img_type(WIZHOME_PATH."/data/member/".$memid."_icon.jpg")) $icon = "<img src='/twcenter/data/member/".$memid."_icon.jpg' align='absmiddle' width='20' height='20' alt='' />";
		else $icon = "";

		$re_space = "";
		for($ii=0; $ii<$com_row['depno']; $ii++) $re_space .= "";

		if($com_row['depno'] > 0) $re_reply = "<div class='reply_icon' style='margin-left:".($com_row['depno']*15)."px;'></div>";
		else					  $re_reply = "";

		if(!strcmp($bbs_info['name_type'], "name")) $name = $name;
		else if(!strcmp($bbs_info['name_type'], "nick") && !empty($nick)) $name = $nick;
		else if(!strcmp($bbs_info['name_type'], "icon") && !empty($icon)) $name = $icon;
		else if(!strcmp($bbs_info['name_type'], "iname")) $name = $icon." ".$name;
		else if(!strcmp($bbs_info['name_type'], "inick")) {
			if(!empty($nick)) $name = $icon." ".$nick;
			else $name = $icon." ".$name;
		}

		$ip = $com_row['ip'];
		$wdate = $com_row['wdate'];
		$content = str_replace("\n", "<br>", $com_row['content']);

		// 버튼설정
		$codel_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=passwd&mode=delco&cidx=".$idx."&idx=".$com_row['idx']."&".$c_param."'>삭제</a>";

		include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/com_body.php";

	}

	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/com_foot.php";

}
?>