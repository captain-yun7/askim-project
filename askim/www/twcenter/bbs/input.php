<?php
ini_set('memory_limit','-1');
set_time_limit(0);
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

if($site_info['recaptcha_sitekey'] && $site_info['recaptcha_secretkey']){
	echo "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
}else{
	// 자동등록글체크
	get_spam_check();
}

if(strcmp($mode, "modify")) {
	if(!check_point($wiz_session['id'], $bbs_info['write_point'])) {
		error($bbs_info['point_msg']);
	}
}

echo "<link href=\"/twcenter/bbs/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;

/* 
	작업자 : 김나연
	작업일 : 2021-10-29
	작업내용 : 파일 드래그앤드롭 업로드 기능 추가
*/
?>


<script language="javascript">
var fileList = new Array();
$(function() {
	$(".fileDrop").on("dragenter", function(e){
        e.preventDefault();
        e.stopPropagation();
    }).on("dragover", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).css("background", "#e0f0fe");
    }).on("dragleave", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).css("background", "#f5f5f5");
    }).on("drop", function(e){
        e.preventDefault();
		var filemax = $(this).data("maxlength");
		var fileCnt = $("input[name='upfiles[]']", $(this)).length + $("input[name='oldfiles[]']", $(this)).length + $("input[name='oldfiles_m[]']", $(this)).length;
		if(fileCnt >= filemax) {
			alert("파일은 "+filemax+"개 까지 업로드 가능합니다.");
		} else {
			var files = e.originalEvent.dataTransfer.files;
			$(this).css("background", "#fff");
			fileUpload(files, $(this));
		}
    }).on("dblclick", function() {
		$("input[name=upfile_tmp]").data("filetype", $(this).data("filetype"));
		$("input[name=upfile_tmp]").trigger("click");
	});

	fileDropReset();

});
$(document).on("click", ".add_file", function() {
	var cnt = $("#upfileDrop").find("input[name='oldfiles[]']").length;
	var filemax = $("#upfileDrop").data("maxlength");

	if(cnt >= filemax){
		alert("파일은 "+filemax+"개 까지 업로드 가능합니다.");
		return;
	} else {
		let filetype = $(this).siblings(".fileDrop").data("filetype");
		$("input[name=upfile_tmp]").data("filetype", filetype);
		$("input[name=upfile_tmp]").trigger("click");
	}
});
function sel_upfile(element) {
	var obj = event.target;
	filetype = $(obj).data("filetype");
	var upfileObj = $(".fileDrop[data-filetype='"+filetype+"']");
	if(element.value) {
		fileUpload(element.files, upfileObj);
		element.value = "";
	}	
}

$(document).on("click", ".fileDel", function() {
	var sessUpload = $(this).data("sessid");
	var obj = $(this);

	$.ajax({
		type : 'POST',
		data : {
			mode : "fileDel",
			sessUpload : sessUpload
		},
		url : '/twcenter/bbs/ajax_upload.php',
		success : function(data) {
			if(data == "ok") {
				obj.parent().remove();
			}
			fileDropReset();
		},
		error : function(jqXHR, textStatus, errorThrown) {
			console.log(jqXHR);
		},
		complete : function(jqXHR, textStatus) {
		}
	});
});

function fileDropReset() {	
	$(".fileDrop").each(function(){
		$(this).css("background", "");
		if($(this).find("p").length < 1) {
			$(this).removeClass("file");
			$(this).addClass("nofile");
		} else {
			$(this).addClass("file");
			$(this).removeClass("nofile");			
		}
	});
}

$(document).on("click", ".old_fileDel", function() {
	if($(this).hasClass("movie")) {
		$("#del_movie").val("Y");
		$(this).parent().remove();
	} else {
		$(this).parent().remove();
		var delfiles = $("#delfiles").val()+$(this).data("fileno")+"|";
		$("#delfiles").val(delfiles);
	}
	
	fileDropReset();
});

function fileUpload(files, obj) {
	if(files != null && files != undefined){
		var file = files[0];
		var filename = file.name;

		/* 첨부파일 확장자 체크 */
		var ext_config = "<?=strtolower($site_info['ext_config'])?>";
		var use_ext = new Array();
		var tmp_arr = filename.split(".");
		var file_ext = tmp_arr[tmp_arr.length-1];

		if(obj.data("filetype") == "movie") {		//동영상 업로드 형식 제한
			ext_config = "U";
			use_ext.push('avi');
			use_ext.push('mp4');
			use_ext.push('wmv');
		} else {
<?
	$use_ext = explode("\n", str_replace("\r", "", $site_info['use_ext']));
	foreach($use_ext as $ext) {
		echo "		use_ext.push('".$ext."'); \n";
	}
?>
		}
		if( (ext_config == "U" && use_ext.indexOf(file_ext.toLowerCase()) < 0) || (ext_config == "D" && use_ext.indexOf(file_ext.toLowerCase()) >= 0) ) {
			alert("허용되지 않은 확장자입니다.");
			fileDropReset();
			return;
		}
		var fileSize = file.size;
		if(fileSize < 1) {
			alert("빈 파일은 업로드 하실 수 없습니다.");
			return false;
		}
		var fileSize_unit = new Array("B", "KB", "MB");
		var loopcnt = 0;
		while(fileSize > 1024) {
			fileSize = fileSize / 1024;
			loopcnt++;
		}
		fileSize = Math.floor(fileSize*10)/10;
		var filesize_str = fileSize + fileSize_unit[loopcnt];
		
		var reader = new FileReader();
		reader.onload = function(e) {
			uploadMaskView();
			var base64data = reader.result;
			var data = base64data.split(',')[1];
			var sendsize = 1024 *100;			//100kb씩 나눠서 전송
			var filelength = data.length;
			var pos = 0;
			// 업로드 세션을 기록하기 위한 변수할당 (이어붙이기+업로드 중 중단 시 삭제할 수 있도록, 임시파일의 파일명이 됨)
			var sessUpload = "<?=md5($_SERVER['REMOTE_ADDR'].date('His'))?>"+Math.floor(Math.random()*1000);
			var filetype = obj.data("filetype");
			var sno = 1;
			var upload = function() {
				var sendData = encodeURI(data.substr(pos, sendsize));
					$.ajax({
						type : 'POST',
						data : {
							mode : "upload",
							sessUpload : sessUpload, 
							filename : filename,
							filelength : sendData.length,
							sno:sno,
							data : sendData
						},
						contentType: "application/x-www-form-urlencoded",
						url : '/twcenter/bbs/ajax_upload.php',
						success : function(data) {
							// 전체가 전송될 때까지
							var result = data.split("|");
							if (result[0] == "error") {
								alert("파일 전송 중 오류가 발생했습니다.");
								uploadMaskHide();
							} else if (pos < filelength) {
								setTimeout(upload, 1);
							} else {
								obj.append("<p>" + " "+ filename + " ("+filesize_str +") <input type='hidden' name='upfiles[]' value='"+filetype+"?"+sessUpload+"?"+filename+"'> <input type='button' class='fileDel' data-sessid='"+sessUpload+"' value='삭제'></p>");
								setTimeout(uploadMaskHide, 100);	
								fileDropReset();
							}
							pos = pos + sendsize;
							if (pos > filelength) {
								pos = filelength;
							}
							var percent = Math.floor(pos/filelength*100);
							$('#progress').text(percent+"% / 100%");
							sno++;
						},
						error : function(jqXHR, textStatus, errorThrown) {
							console.log(jqXHR);
						},
						complete : function(jqXHR, textStatus) {
						}
					});
			};
			upload();
		}
		// base64로 넘깁니다.
		reader.readAsDataURL(file);
	
	}
}

function uploadMaskView() {
	var maskHeight = $(document).height();
	var maskWidth = $(document).width();

	var mask = "<div id='file_mask'></div>";

	var progress = '';

	progress += "<div id='progress' class='file_pro'></div>";

	$('body').append(mask).append(progress);

	$('#file_mask').css({
		'width' : '100%',
		'height': '100%',
		'opacity' : '0.8'
	});

	$('#file_mask').show();
	$('#progress').show();
}

function uploadMaskHide() {
	$('#file_mask').remove();
	$('#progress').remove();
}
</script>
<?
/*========= 파일 드래그앤드롭 업로드 기능 END =========*/ 

//공격대비 게시글번호/페이지번호/카테고리 파라미터 무조건 숫자로 2024-08-13 
$idx              = intval($idx);
$page             = intval($page);
$category         = intval($category);

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

$bbsadmin_ids = explode(",", $bbs_info['bbsadmin']);

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
		
		//권한 체크관련 - 모바일에서 권한으로 인한 경고 후 이동시 모바일 이동페이지가 입력되어있으면 모바일 이동페이지로 이동 2021-11-10
		$perurl = $bbs_info['perurl'];
		if(mobile_check() ==  true && $bbs_info['perurl_m']) $perurl = $bbs_info['perurl_m'];
		
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
				error($bbs_info['permsg'],$perurl);
			}

		} else {
			error($bbs_info['permsg'],$perurl);
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
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||		// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||								// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 개인정보취급방침 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
		$hide_privacy_script_start = "/*"; $hide_privacy_script_end = "*/";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))		// 게시판관리자
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
	
	$addinfo11   = xss_check($addinfo11);
	$addinfo12   = xss_check($addinfo12);
	$addinfo13   = xss_check($addinfo13);
	$addinfo14   = xss_check($addinfo14);
	$addinfo15   = xss_check($addinfo15);

	$addinfo16   = xss_check($addinfo16);
	$addinfo17   = xss_check($addinfo17);
	$addinfo18   = xss_check($addinfo18);
	$addinfo19   = xss_check($addinfo19);
	$addinfo20  = xss_check($addinfo20);

	$latitude   = xss_check($latitude);
	$longitude  = xss_check($longitude); 
	$status   = xss_check($status);

	$content = str_replace("fr|om","from",$content);

	if($bbs_row['ctype'] == "T" && $bbs_info['editor'] == "Y" && mobile_check() == false) {
		$content = str_replace("\n", "<br>", $content);
	}

	/* 파일 드래그앤드롭, 기존파일 리스트 */
	if($bbs_info['use_drag'] == "Y") {
		$upfiles = "<input type='hidden' name='delfiles' id='delfiles' value=''>";		
		for($ii = 1; $ii <= $upfile_max; $ii++) {
			if(!empty($bbs_row['upfile'.$ii]) && @file($upfile_path."/".$bbs_row['upfile'.$ii])) {
				${'upfile'.$ii} = "<input type='checkbox' name='delupfile[]' value='upfile".$ii."'> 삭제 (".$bbs_row['upfile'.$ii.'_name'].")";
				if($bbs_info['use_drag'] == "Y") {
					$fsize = filesize($upfile_path."/".$bbs_row['upfile'.$ii]);
					$filesize_unit = array("B", "KB", "MB");
					$loopcnt = 0;
					while($fsize > 1024) {
						$fsize = $fsize / 1024;
						$loopcnt++;
					}
					$fsize = floor($fsize *10)/10;
					$filesize_str = $fsize.$filesize_unit[$loopcnt];
					$upfiles .= "<p>".$bbs_row['upfile'.$ii.'_name']." (".$filesize_str.") <input type='hidden' name='oldfiles[]' value='".$bbs_row['upfile'.$ii]."?".$bbs_row['upfile'.$ii.'_name']."'><input type='button' class='old_fileDel' value='삭제' data-fileno='".$ii."'></p>";
				}
			}
		}
		$movies = "<input type='hidden' name='del_movie' id='del_movie' value=''>";
		if($bbs_row['movie1']) {
			$movies .= "<p>".$bbs_row['movie1']."<input type='hidden' name='oldfiles_m[]' value='".$bbs_row['movie1']."?movie1'><input type='button' class='old_fileDel movie' value='삭제'></p>";
		}

	}else{
		for($ii = 1; $ii <= $upfile_max; $ii++) {
			if(!empty($bbs_row['upfile'.$ii])) {
				${'upfile'.$ii} = "<input type='checkbox' name='delupfile[]' value='upfile".$ii."'> 삭제 (".$bbs_row['upfile'.$ii.'_name'].")";
			}
		}
	}

	if(!empty($bbs_row['movie1'])) {
		$movie1 = "<input type='checkbox' name='delupfile[]' value='movie1'> 삭제 (".$bbs_row['movie1'].")";
	}

	$movie2 = $bbs_row['movie2'];
	$movie3 = $bbs_row['movie3'];

	// 비밀번호 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid'])								// 자신에글
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	} 

	// 개인정보취급방침 숨김
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids)) ||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
		$hide_privacy_script_start = "/*"; $hide_privacy_script_end = "*/";
	}

	// 평점
	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row['star'])) ${"star".$ii."_checked"} = "checked";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))		// 게시판관리자
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
			 , addinfo11
			 , addinfo12
			 , addinfo13
			 , addinfo14
			 , addinfo15
			 , addinfo16
			 , addinfo17
			 , addinfo18
			 , addinfo19
			 , addinfo20
			 , prdcode 
		  from wiz_bbs 
		 where idx = '$idx'
	";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);

	$category  = $bbs_row['category'];
	$subject   = $bbs_row['subject'];
	if($bbs_info['editor'] == "Y") {
		$content   = $bbs_row['content']."<br/>\n\n==================== 답 변 ====================\n\n<br/>";
	} else {
		$content = $bbs_row['content']."\n\n==================== 답 변 ====================\n\n";
	}
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
	
	$addinfo11  = $bbs_row['addinfo11'];
	$addinfo12  = $bbs_row['addinfo12'];
	$addinfo13  = $bbs_row['addinfo13'];
	$addinfo14  = $bbs_row['addinfo14'];
	$addinfo15  = $bbs_row['addinfo15'];

	$addinfo16  = $bbs_row['addinfo16'];
	$addinfo17  = $bbs_row['addinfo17'];
	$addinfo18  = $bbs_row['addinfo18'];
	$addinfo19  = $bbs_row['addinfo19'];
	$addinfo20 = $bbs_row['addinfo20'];

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
	
	$addinfo11  = xss_check($addinfo11);
	$addinfo12  = xss_check($addinfo12);
	$addinfo13  = xss_check($addinfo13);
	$addinfo14  = xss_check($addinfo14);
	$addinfo15  = xss_check($addinfo15);

	$addinfo16  = xss_check($addinfo16);
	$addinfo17  = xss_check($addinfo17);
	$addinfo18  = xss_check($addinfo18);
	$addinfo19  = xss_check($addinfo19);
	$addinfo20 = xss_check($addinfo20);

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
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))	||	// 게시판관리자
	($bbs_row['memid'] != "" && $wiz_session['id'] == $bbs_row['memid']) ||							// 자신에글
	($wiz_session['id'] != "")
	){
		$hide_privacy_start = "<!--"; $hide_privacy_end = "-->";
		$hide_privacy_script_start = "/*"; $hide_privacy_script_end = "*/";
	}

	// 평점
	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row['star'])) ${"star".$ii."_checked"} = "checked";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))		// 게시판관리자
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
($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))		// 게시판관리자
){
} else {
	$hide_admin_start = "<!--"; $hide_admin_end = "-->";
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
	$catlist = "<label for='category' class='blind'>게시물 분류</label><select id=\"category\" name=\"category\" class=\"select\" title='게시물 분류를 선택해주세요'  style='width:165px; height:35px;'>";
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
		  <td align="right"><a href="<?=$purl?>?ptype=view&prdcode=<?=$prd_info->prdcode?>"<?if(($mem_level == "0") || ($bbs_info['bbsadmin'] != "" && in_array($wiz_session['id'], $bbsadmin_ids))){echo "target='_blank'";}?>><img src="<?=$skin_dir?>/image/btn_prdview.gif" border="0" alt='자세히 보기' ></a></td>
		</tr>
	</table>
</div>
<?
}

// 입력스킨 인클루드
@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/input.php";
?>
<label for="upfile_tmp" class="blind">file</label>
<input id="upfile_tmp" type="file" name="upfile_tmp" onchange="sel_upfile(this)" data-filetype="" style="display:none">
<script>
$(document).on("ready", function() {
	$(".fileDrop").after("<input type='button' class='btn_b add_file' style='margin-top:2px;width:100%' value='파일추가'>");
});
</script>