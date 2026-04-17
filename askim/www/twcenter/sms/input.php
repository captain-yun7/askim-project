<link href="<?=$skin_dir?>/style.css" rel="stylesheet" type="text/css">
<script language="Javascript" src="/twcenter/js/lib.js"></script>
<script Language="Javascript">
<!--
function smsCheck(frm) {

	if(!frm.message.value) {
		alert("내용을 입력하세요.");
		frm.message.focus();
		return false;
	}

	if(!frm.se_num1.value) { alert("연락처를 입력하세요."); frm.se_num1.focus(); return false;
	} else if(!check_Num(frm.se_num1.value)) { alert("연락처는 숫자만 입력하세요."); frm.se_num1.focus(); return false; }

	if(!frm.se_num2.value) { alert("연락처를 입력하세요."); frm.se_num2.focus(); return false;
	} else if(!check_Num(frm.se_num2.value)) { alert("연락처는 숫자만 입력하세요."); frm.se_num2.focus(); return false; }

	if(!frm.se_num3.value) { alert("연락처를 입력하세요."); frm.se_num3.focus(); return false;
	} else if(!check_Num(frm.se_num3.value)) { alert("연락처는 숫자만 입력하세요."); frm.se_num3.focus(); return false; }

	if(!frm.se_name.value) {
		alert("이름을 입력하세요.");
		frm.se_name.focus();
		return false;
	}

}

function calByte(aquery){

	var frm = document.smsFrm;

	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;;

	var tmpName;
	var tmpNameLeg = 0;

	tmpName = new String("[" + frm.se_name.value + "]");
	temp = tmpName.length;
	for(t=0; t<temp; t++){
		onechar = tmpName.charAt(t);
		if(escape(onechar).length > 4) {
			tmpNameLeg += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tmpNameLeg++;
		}
	}

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}

		frm.sms_byte.value = tcount+"/80 bytes";

		if(tcount > (80 - tmpNameLeg)) {
			alert("메시지내용은 이름을 포함하여 80 바이트 이상 전송할 수 없습니다.");

			cutText(frm.message.value);

			return;
		}
	}
	if ( temp == 0 ) {

		frm.sms_byte.value = "0/80 bytes";

	}
}

function cutText(aquery) {

	var tmpStr;
	var temp=0;
	var onechar;
	var tcount = 0;

	var frm = document.smsFrm;

	var tmpName;
	var tmpNameLeg = 0;

	tmpName = new String("[" + frm.se_name.value + "]");
	temp = tmpName.length;
	for(t=0; t<temp; t++){
		onechar = tmpName.charAt(t);
		if(escape(onechar).length > 4) {
			tmpNameLeg += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tmpNameLeg++;
		}
	}

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(t=0; t<temp; t++){
		onechar = tmpStr.charAt(t);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}
		if(tcount > (80 - tmpNameLeg)) {
			tmpStr = tmpStr.substring(0, t);
			break;
		}
	}

	frm.message.value = tmpStr;

	calByte(tmpStr);
}

function checkSmsmsg(mode){
	var frm = document.smsFrm;
	var tmpStr = frm.message.value;
	calByte(tmpStr);
}
//-->
</script>
<form name="smsFrm" method="post" action="<?=$PHP_SELF?>" onsubmit="return smsCheck(this)">
<table border="0" cellpadding="0" cellspacing="0">
<input type="hidden" name="stype" value="send">
	<tr>
		<td><img src="<?=$skin_dir?>/image/m_message_top.gif"></td>
	</tr>
	<tr>
		<td><img src="<?=$skin_dir?>/image/m_message_in_top.gif"></td>
	</tr>
	<tr>
		<td valign="top" style="height:129px;background:url(<?=$skin_dir?>/image/m_message_bg.gif) no-repeat;padding-left:10px;">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<textarea name="message" class="sms_msg" rows="3" onKeyUp="checkSmsmsg('msg');"></textarea></td>
								</tr>
								<tr>
									<td style="text-align:right;"><input type="text" name="sms_byte" size="10" style="height:14px; border: 0px solid #FFFFFF; ; font-size:8pt; font-family:돋움; background-color:#999ea4; color:#FFFFFF;" value="0/80 bytes" onFocus="this.blur()"></td>
								</tr>
					  	</table>
					</td>
				</tr>
				<tr>
					<td height="12"></td>
				</tr>
				<tr>
					<td align="left">
						<table border="0" cellpadding="0" cellspacing="0">
						  <tr>
							<td align="left" width="50"><b>연락처</b></td>
							<td align="left">
								<input type="text" name="se_num1" style="width:30px" class="input">-<input type="text" name="se_num2" style="width:30px" class="input">-<input type="text" name="se_num3" style="width:30px" class="input">
							</td>
						  </tr>
						  <tr><td height="5"></td></tr>
						  <tr>
							<td align="left"><b>이름</b></td>
							<td align="left">
								<input type="text" name="se_name" style="width:67px" class="input" onKeyUp="checkSmsmsg('name');">
							  <input type="image" src="<?=$skin_dir?>/image/btn_send.gif" align="absmiddle">
							 </td>
						  </tr>
						</table>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
</form>