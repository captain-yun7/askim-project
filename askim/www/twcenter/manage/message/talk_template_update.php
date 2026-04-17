<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

include "../head.php";

$sql_talk = "select * from wiz_talk where idx='$idx' ";
$result_talk = query($sql_talk);
$row_talk = sql_fetch_arr($result_talk);
?>
<script language="javascript">

	$( document ).ready(function() {
		checkSmsmsg('cust');
	});

	function inputCheck(frm){

		if(frm.newTemplateName.value==""){
			alert('템플릿 이름을 입력해주세요');
			return false;
		}

		if(frm.newTemplateContent.value==""){
			alert('템플릿 내용을 입력해주세요');
			return false;
		}
	}


	function calByte(type, aquery){

		var tmpStr;
		var temp = 0;
		var onechar;
		var tcount = 0;;

		tmpStr = new String(aquery);
		temp = tmpStr.length;
		for(k=0; k<temp; k++) {
			onechar = tmpStr.charAt(k);
			if(escape(onechar).length > 4) {
				tcount += 2;
			} else if(onechar != '\n' || onechar != '\r') {
				tcount++;
			}

			if(type == "cust") frm.sms_custbyte.value = tcount+"/1000 Bytes";
			else  frm.sms_operbyte.value = tcount+"/1000 Bytes";

			if(tcount > 1000) {
				alert("메시지내용은 1000 바이트 이상 전송할 수 없습니다.");

				if(type == "cust") cutText(type, frm.newTemplateContent.value);
				else cutText(type, frm.sms_opermsg.value);


				return;
			}
		}
		if ( temp == 0 ) {

			if(type == "cust") frm.sms_custbyte.value = "0/1000 Bytes";
			else  frm.sms_operbyte.value = "0/1000 Bytes";

		}
	}

	function cutText(type, aquery) {

		var tmpStr;
		var temp=0;
		var onechar;
		var tcount = 0;

		tmpStr = new String(aquery);
		temp = tmpStr.length;
		for(t=0; t<temp; t++){
			onechar = tmpStr.charAt(t);
			if(escape(onechar).length > 4) {
				tcount += 2;
			} else if(onechar != '\n' || onechar != '\r') {
				tcount++;
			}
			if(tcount > 1000) {
				tmpStr = tmpStr.substring(0, t);
				break;
			}
		}

		if(type == "cust") document.frm.newTemplateContent.value = tmpStr;
		else  document.frm.sms_opermsg.value = tmpStr;

		calByte(type, tmpStr);
	}

	function checkSmsmsg(type){

		var tmpStr;
		if(type == "cust" && document.frm.newTemplateContent != null){
			tmpStr = document.frm.newTemplateContent.value;
			calByte(type, tmpStr);
		}
	}
</script>

			
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">알림톡 템플릿 관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">템플릿을 등록 관리합니다.</td>
	</tr>
</table>

<br>

<form name="frm" action="talk_template_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="senderKey" value="<?=$senderKey?>">
<input type="hidden" name="templateCode" value="<?=$row_talk['templateCode']?>">		
<input type="hidden" name="newSenderKey" value="<?=$senderKey?>">
<input type="hidden" name="newTemplateCode" value="<?=$row_talk['templateCode']?>">
<!-- <input name="newTemplateName" type="hidden" value="<?=$row_talk['templateName']?>" class="input"> -->
	 <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
		<tr>
			<td width="15%" class="t_name">템플릿 코드</td>
			<td width="85%" class="t_value">
				<?=$row_talk['templateCode']?>
			</td>
		</tr>
		<tr>
			<td width="15%" class="t_name">템플릿 이름</td>
			<td width="85%" class="t_value">
				<input name="newTemplateName" type="text" value="<?=$row_talk['templateName']?>" class="input" size="50">
			</td>
		</tr>
		<tr>
			<td width="15%" class="t_name">템플릿 내용</td>
			<td width="85%" class="t_value">
				<textarea name="newTemplateContent" type="text" value="" class="textarea" rows="20" style="width:98%;"onKeyUP="checkSmsmsg('cust');"><?=$row_talk['templateContent']?></textarea>
				<input type="text" name="sms_custbyte" size="15" style="height:20px; border: 1px solid #7862ce; font-size:9pt; color:#fff; font-family:NanumGothic, 나눔고딕, NG; background-color:#7862ce; text-align:center;" value="0/1000 Bytes" onfocus="this.blur()">
			</td>
		</tr>
		<tr>
			<td class="t_name">버튼 추가</td>
			<td>
				<table cellpadding="2" cellspacing="0">
					<tr>
						<th class="t_name">버튼명</th>
						<td class="t_value"><input type="text" name="btn_name" class="input" value="<?=$row_talk['btn_name']?>"></td>
					</tr>
					<tr>
						<th class="t_name">모바일링크</th>
						<td class="t_value"><input type="text" name="linkMo" class="input" value="<?=$row_talk['linkMo']?>" size="60"> 전체링크 입력 : (ex) http://<?=$_SERVER['HOST_NAME']?>/#{PAGELINK}</td>
					</tr>
					<tr>
						<th class="t_name">PC링크</th>
						<td class="t_value"><input type="text" name="linkPc" class="input" value="<?=$row_talk['linkPc']?>" size="60"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center">
			<input type="image" src="../image/btn_confirm_l.gif"> &nbsp; 
			<img src="../image/btn_list_l.gif" onClick="document.location='talk_list.php';" style="cursor:hand"></td>
		</tr>		
	</table>
</form>

<? include "../foot.php"; ?>
