<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";


$sql = "select * from wiz_estimate_log where idx='$idx'";
$result = query($sql);
$row = sql_fetch_arr($result);

?>
<html>
<head>
<link href="/twcenter/manage/wiz_style.css" rel="stylesheet" type="text/css">
<link href="/twcenter/bbs/style.css" rel="stylesheet" type="text/css">
<link href="/twcenter/bbs/skin/estimateBasic/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
<script language="JavaScript" src="/twcenter/js/jquery-1.8.3.min.js?ver=<?php echo VERSION ?>"></script>
<script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
<style type="text/css">
.input {
	margin:1px 0;
	border:0px;
	font-size:13px;
	font-family:NanumGothic, 나눔고딕, NG, Tahoma, Geneva, sans-serif;
	color:#545454;
	border:1px solid #ccc;
	border-radius: 2px;
	padding:5px;
}
</style>
<script>
function inputCheck(frm){
	inquiry_memo.outputBodyHTML(); 
	/*
	var developers_status = frm.developers_status;
	if(developers_status.value == "N"){
		alert("진행상태를 입력해주세요.");
		developers_status.focus();
		return false;
	}
	*/
}

$(function() {
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
		'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ko']);

	$( ".datepick" ).datepicker({
		changeMonth: true, 
		changeYear: true,
		showButtonPanel: true,
		yearRange: 'c-99:c+99'
	});
});

</script>
</head>
	<body>
		<table border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td><img src="/twcenter/manage/image/ic_tit.gif"></td>
				<td valign="bottom" class="tit">처리내역수정</td>
			</tr>
		</table>
		<BR>
	<form action='/twcenter/bbs/ajax_estimate_log_save.php' method="post" onSubmit="return inputCheck(this)">
	<input type="hidden" name ="mode" value="update">
	<input type="hidden" name ="bidx" value="<?php echo $row['bidx']?>">
	<input type="hidden" name ="idx" value="<?php echo $idx?>">
	<input type="hidden" name="name" value="<?php echo $row['name']?>">
		<div class="bbs_input">
	<!--
	<dl>
		<dt>작성자</dt>
		<dd><input type="text" id="frm_name" name="name" value="<?php echo $row['name']?>" class="input input_l"></dd>
	</dl>
	-->
	<dl>
		<dt>제목</dt>
		<dd><input type="text" id="frm_subject" name="subject" value="<?php echo $row['subject']?>" class="input input_l"></dd>
	</dl>
	<dl>
		<dt>처리내역</dt>
		<dd>
			<?php
				$edit_content = $row['content'];
				$edit_name = "inquiry_memo";
				include WIZHOME_PATH."/webedit/WIZEditor.html";
			?>
		</dd>
	</dl>
	<br/>
	<div class="btn_area"><input type="submit" value="처리내역저장" class="btn_b"></div>
</div>
		</form>
	</body>
</html>