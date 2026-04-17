<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/mem_info.php"; ?>
<? include "../head.php"; ?>
<script language="javascript">
	function inputCheck(frm){

		var site_name = "<?php echo $site_info['site_name'] ?>"; 

		if(frm.templateCode.value==""){
			alert('템플릿 코드를 입력해주세요');
			return false;
		}

		if(frm.templateName.value==""){
			alert('템플릿 이름을 입력해주세요');
			return false;
		}

		if(frm.templateContent.value==""){
			alert('템플릿 내용을 입력해주세요');
			return false;
		}

		if(site_name==""){
			alert('기본설정에서 사이트명을 입력해주세요.');
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

				if(type == "cust") cutText(type, frm.templateContent.value);
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

		if(type == "cust") document.frm.templateContent.value = tmpStr;
		else  document.frm.sms_opermsg.value = tmpStr;

		calByte(type, tmpStr);
	}

	function checkSmsmsg(type){

		var tmpStr;
		if(type == "cust" && document.frm.templateContent != null){
			tmpStr = document.frm.templateContent.value;
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

<?
$sql = "select templateCode from wiz_talk ";
$res = query($sql);
while($trow = sql_fetch_arr($res)) {
	$t_templateCode = explode("_", $trow['templateCode']);
	if(trim($tcode) == trim($t_templateCode[1])) {
		error("해당코드는 알림톡 템플릿이 생성된 코드입니다.");
	}
}

$sql_siteinfo = "select *from wiz_siteinfo";
$result_siteinfo = query($sql_siteinfo);
$row_siteinfo = sql_fetch_arr($result_siteinfo);
$talk_length = 19-strlen($row_siteinfo['talk_id']);

$sql_mail = "select subject,alim_msg from wiz_mailsms where code='".$tcode."' ";
$row_mail = sql_fetch($sql_mail);

if(strpos($row_mail['subject'], ']') !== false) {
	$string_sub = explode("]", $row_mail['subject']);
	$mail_sub = trim($string_sub[1]);
} else {
	$mail_sub = trim($row_mail['subject']);
}
$code_sub = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $mail_sub);
$alim_msg = trim($row_mail['alim_msg']);

?>

<form name="frm" action="talk_template_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="senderKey" value="<?=$senderKey?>">
<input type="hidden" name="_alimtalk_id" value="<?=$_alimtalk_id?>">

	 <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
		<tr>
			<td width="15%" class="t_name">템플릿 코드</td>
			<td width="85%" class="t_value">
				<?php echo $_alimtalk_id?>_ <input name="templateCode" type="text" value="<?php echo $tcode ?>" class="input" maxlength="<?=$talk_length?>">
				<b><font color="blue">ex) 사이트코드_템플릿코드값_넘버 => <?php echo $_alimtalk_id?>_edit_001</font></b>
			</td>
		</tr>
		<tr>
			<td width="15%" class="t_name">템플릿 이름</td>
			<td width="85%" class="t_value">
				[<?php echo $site_info['site_name'] ?>] <input name="templateName" type="text" value="<?php echo $code_sub ?>" class="input">
				<b><font color="blue">ex) [사이트이름] 템플릿이름 => [사이트이름을 입력하세요] 유지관리-접수안내
			</td>
		</tr>
		<tr>
			<td width="15%" class="t_name">템플릿 내용</td>
			<td width="85%" class="t_value">
				<textarea name="templateContent" type="text" value="" class="textarea" rows="20" style="width:98%;" onKeyUP="checkSmsmsg('cust');"><?php echo $alim_msg ?></textarea>
				<input type="text" name="sms_custbyte" size="15" style="height:20px; border: 1px solid #7862ce; font-size:9pt; color:#fff; font-family:NanumGothic, 나눔고딕, NG; background-color:#7862ce; text-align:center;" value="0/1000 Bytes" onfocus="this.blur()">

				<table width="98%" border="0" cellpadding="5" cellspacing="3" align="center" class="e_style">
					<tr>
					  <td bgcolor="#FFFFFF">
					  <table>
					  <tr>
					  <td><b>#{UID}</b> 회원아이디 &nbsp;</td>
					  <td><b>#{MEM_PW}</b> 회원비밀번호 &nbsp;</td>
					  <td><b>#{NAME}</b> 회원이름 &nbsp;</td>
					  <td><b>#{HPHONE}</b> 회원휴대폰번호 &nbsp;</td>
					  <td><b>#{TPHONE}</b> 회원전화번호 &nbsp;</td>
					  </tr>
					  <tr>
					  <td><b>#{상풍명}</b> 상풍명</td>
					  <td><b>#{수량}</b> 수량 &nbsp;</td>
					  <td><b>#{택배사명}</b> 택배사명</td>
					  <td><b>#{운송장번호}</b> 운송장번호</td>
					  <td></td>
					  </tr>
					  <tr>
					  <td><b>#{SHOPNAME}</b> 쇼핑몰명 &nbsp;</td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  </tr>
					  </table>
					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td class="t_name">버튼 추가</td>
			<td>
				<table cellpadding="2" cellspacing="0">
					<tr>
						<th class="t_name">버튼명</th>
						<td class="t_value"><input type="text" name="btn_name" class="input" value=""></td>
					</tr>
					<tr>
						<th class="t_name">모바일링크</th>
						<td class="t_value"><input type="text" name="linkMo" class="input" value="" size="60"> 전체링크 입력 : (ex) http://<?=$_SERVER['HOST_NAME']?>/#{PAGELINK}</td>
					</tr>
					<tr>
						<th class="t_name">PC링크</th>
						<td class="t_value"><input type="text" name="linkPc" class="input" value="" size="60"></td>
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
