<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php include_once "../../inc/site_info.php"; ?>
<?php
if($mode == "update"){
	$sql = "select * from wiz_mailsms where code = '$code'";
	$result = query($sql) or error("sql error");
	$mailsms_info = sql_fetch_obj($result);
	$mailsms_info->email_msg = stripslashes($mailsms_info->email_msg);
}

include "../head.php";


?>

<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.code.value == ""){
		alert("코드명을 입력하세요");
		frm.code.focus();
		return false;
	} else if(!check_Char(frm.code.value)) {
   		alert('코드명은 특수문자를 사용할 수 없습니다.');
      frm.code.focus();
   		return false;
	}
	if(frm.subject.value == ""){
		alert("분류명을 입력하세요");
		frm.subject.focus();
		return false;
	}
	content.outputBodyHTML();
}

function calByte(type, aquery, gubun){

	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}

		if(gubun == 'SMS') {

			if(type == "cust") frm.sms_custbyte.value = tcount+"/80 Bytes";
			else  frm.sms_operbyte.value = tcount+"/80 Bytes";

			if(tcount > 80) {
				alert("메시지내용은 80 바이트 이상 전송할 수 없습니다.");

				if(type == "cust") cutText(type, frm.sms_msg.value, gubun);
				else cutText(type, frm.sms_opermsg.value, gubun);


				return;
			}

		} else if(gubun == 'ALIMTALK') {

			if(type == "alimtalk") frm.alim_custbyte.value = tcount+"/1000 Bytes";

			if(tcount > 1000) {
				alert("메시지내용은 1000 바이트 이상 전송할 수 없습니다.");

				if(type == "alimtalk") cutText(type, frm.alim_msg.value, gubun);
				return;
			}

		}

	}
	if ( temp == 0 ) {

		if(gubun == 'SMS') {
			if(type == "cust") frm.sms_custbyte.value = "0/80 Bytes";
			else  frm.sms_operbyte.value = "0/80 Bytes";
		} else {
			if(type == "alimtalk") frm.alim_custbyte.value = "0/1000 Bytes";
		}

	}
}

function cutText(type, aquery, gubun) {

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

		var bytecount = (type == 'cust') ? 80 : 1000;
			if(tcount > bytecount) {
				tmpStr = tmpStr.substring(0, t);
				break;
			}
	}

	if(gubun == 'SMS') {
		if(type == "cust") document.frm.sms_msg.value = tmpStr;
		else  document.frm.sms_opermsg.value = tmpStr;
	} else {
		if(type == "alimtalk") document.frm.alim_msg.value = tmpStr;
	}

	calByte(type, tmpStr, gubun);
}

function checkSmsmsg(type){

	var tmpStr;
	if(type == "cust" && document.frm.sms_msg != null){
		tmpStr = document.frm.sms_msg.value;
		calByte(type, tmpStr, 'SMS');
	} else if(type == "alimtalk" && document.frm.alim_msg != null){
		tmpStr = document.frm.alim_msg.value;
		calByte(type, tmpStr, 'ALIMTALK');
	}
}

-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메세지설정</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">상황별 메일/SMS발송 내용을 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="mail_save.php" onSubmit="return inputCheck(this)" method="post">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">

	  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="t_style">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr>
                <td width="15%" class="t_name">코드 <font color=red>*</font></td>
                <td width="85%" class="t_value">
                <input type="text" name="code" value="<?=$mailsms_info->code?>" size="20" class="input" <? if($mode == "update") echo "readonly"; ?>>
                </td>
              </tr>
              <tr>
                <td class="t_name">분류명 <font color=red>*</font></td>
                <td class="t_value">
                <input type="text" name="subject" value="<?=$mailsms_info->subject?>" size="60" class="input">
                </td>
              </tr>

              <? if (
				    (isset($code) && (strpos($code, "mem") !== false || strpos($code, "ord") !== false)) 
				    && isset($site_info['sms_use']) && $site_info['sms_use'] === "Y"
				) { ?>
              <tr>
                <td class="t_name">SMS발송</td>
                <td class="t_value">
                  <input type="checkbox" name="sms_send" value="Y" <? if($mailsms_info->sms_send == 'Y') echo "checked"; ?>>고객수신 &nbsp;
                  <input type="checkbox" name="sms_oper" value="Y" <? if($mailsms_info->sms_oper == 'Y') echo "checked"; ?>>관리자수신<br>
                  <textarea cols="35" rows="5" name="sms_msg" onKeyDown="checkSmsmsg('cust');" class="textarea"><?=$mailsms_info->sms_msg?></textarea>
                  <input type="text" name="sms_custbyte" size="11" style="height:20px; border: 1px solid #7862ce; font-size:9pt; color:#fff; font-family:NanumGothic, 나눔고딕, NG; background-color:#7862ce; text-align:center;" value="0/80 Bytes" onfocus="this.blur()">
                </td>
              </tr>
            	<? } ?>

              <?php if (
				(isset($code) && (strpos($code, "mem") !== false || strpos($code, "ord") !== false)) 
				|| (isset($mailsms_info->type) && ($mailsms_info->type === "MEM" || strcmp($mailsms_info->type, "ORD") === 0))
				) { ?>
              <tr>
                <td class="t_name">메일발송</td>
                <td class="t_value">
                <input type="checkbox" name="email_send" value="Y" <? if($mailsms_info->email_send == 'Y') echo "checked"; ?>>고객수신 &nbsp; &nbsp;
                <input type="checkbox" name="email_oper" value="Y" <? if($mailsms_info->email_oper == 'Y') echo "checked"; ?>>관리자수신
                </td>
              </tr>
              <tr>
                <td class="t_name">메일제목</td>
                <td class="t_value">
                <input type="text" name="email_subj" value="<?=$mailsms_info->email_subj?>" size="80" class="input">
                </td>
              </tr>
              <?php } else if($mailsms_info->type == "FRM") { ?>
              <tr>
                <td class="t_name">메일발송</td>
                <td class="t_value">
                <input type="checkbox" name="email_send" value="Y" <? if($mailsms_info->email_send == 'Y') echo "checked"; ?>>고객수신 &nbsp; &nbsp;
                <input type="checkbox" name="email_oper" value="Y" <? if($mailsms_info->email_oper == 'Y') echo "checked"; ?>>관리자수신
                </td>
              </tr>
			  <?php } ?>
              <tr>
                <td colspan="3" class="t_value">
                <?
                $edit_content = $mailsms_info->email_msg;
				if(!isset($edit_content)) $edit_content = '';

                $edit_content = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $edit_content);
                include "../../webedit/WIZEditor.html";
                ?>

                 <table width="98%" border="0" cellpadding="5" cellspacing="3" align="center" class="e_style">
                    <tr>
                      <td bgcolor="#FFFFFF">
                      <table>
                      <tr>
                      <td><b>{DATE}</b> 오늘날짜 &nbsp;</td>
                      <td><b>{MEM_ID}</b> 회원아이디 &nbsp;</td>
                      <td><b>{MEM_PW}</b> 회원비밀번호 &nbsp;</td>
                      </tr>
                      <tr>
                      <td><b>{MEM_NAME}</b> 회원이름</td>
                      <td><b>{SITE_NAME}</b> 사이트명 &nbsp;</td>
                      <td><b>{SITE_EMAIL}</b> 사이트 이메일</td>
                      </tr>
                      <tr>
                      <td><b>{SITE_TEL}</b> 사이트 전화번호 &nbsp;</td>
                      <td><b>{SITE_TEL}</b> 사이트 팩스번호 &nbsp;</td>
                      <td><b>{SITE_URL}</b> 사이트 주소로 변경되어 발송됩니다.</td>
                      </tr>
                      <tr>
                      <td><b>{SITE_OWNER}</b> 대표 &nbsp;</td>
                      <td><b>{SITE_NUM}</b> 사업자번호</td>
                      <td><b>{SITE_ADDRESS}</b> 업체 주소</td>
                      </tr>
                      </table>
                    </tr>
                  </table>
                </td>
              </tr>
			</table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onclick="document.location='mail_list.php?page=<?=$page?>&<?=$menucodeParam?>';">
          </td>
        </tr>
      </table>
	  </form>

<?php include "../foot.php"; ?>