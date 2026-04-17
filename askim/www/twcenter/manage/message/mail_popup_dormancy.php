<?php
include_once "../../common.php";
include "../../inc/twcenter_check.php";
include "../../inc/site_info.php";
include "../../inc/mem_info.php";

$mode = $mode ? $mode : "sendmail";

// 메일발송
if($smode == "mailok"){

	$tmp_subject = $subject;
	$tmp_content = $content;

	$user_list = explode(",",$seluser);
	$send_ok = false;

	for($ii=0; $ii < count($user_list); $ii++){

		$mrow = sql_fetch("select id,name,email,passwd from wiz_member where email='".$user_list[$ii]."' ");

		$re_info = "";

		$re_name   = $mrow['name'];
		$re_email  = $user_list[$ii];

		$re_info['id']     = $mrow['id'];
		$re_info['name']   = $mrow['name'];

		$subject = info_replace($site_info, $re_info, $tmp_subject);
		$content = info_replace($site_info, $re_info, $tmp_content);
		$content = stripslashes($content);	// 자동역슬래쉬 제거(역슬래쉬있으면 이미지 깨짐)

		if($re_email != "") {
			$send_ok = true;
			send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content);
		}

	}

	if($send_ok) {
		echo "<script>alert('이메일 발송을 완료하였습니다.');self.close();</script>";
		exit;
	} else {
		echo "<script>alert('이메일 발송시 오류가 발생했습니다.');self.close();</script>";
		exit;
	}

} 


// 메일스킨
$sql = "select * from wiz_mailsms where code = 'mem_dormancy'";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);

$edit_subject = stripslashes($row->email_subj);
$edit_subject = info_replace($site_info, $re_info, $edit_subject);

?>
<html>
<head>
<title>:: 휴면처리공지 이메일발송 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){

	if(frm.seluser.value == ""){
		alert("받는이가 없습니다");
		frm.seluser.focus();
		return false;
	}

	if(frm.subject.value == ""){
		alert("제목을 입력하세요");
		frm.subject.focus();
		return false;
	}

	try{ content.outputBodyHTML(); } catch(e){ }
	if(frm.content.value == ""){
		alert("내용을 입력하세요.");
		return false;
	}
}
//-->
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">휴면처리공지 이메일발송</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>
<form name="frm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<input type="hidden" name="smode" value="mailok">
<input type="hidden" name="se_name" value="<?php echo $site_info['site_name'] ?>">
<input type="hidden" name="se_email" value="<?php echo $site_info['site_email'] ?>">
<input type="hidden" name="seluser_info" value="<?php echo $seluser_info ?>">
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td height="30" width="20%" class="t_name">보내는이</td>
    <td class="t_value"><?php echo $site_info['site_name'] ?>(<?php echo $site_info['site_email'] ?>)</td>
  </tr>
  <tr>
    <td height="30" class="t_name">받는이</td>
    <td class="t_value">
      <textarea rows="3" cols="50" name="seluser" class="textarea" style="width:90%"><?php echo $seluser ?></textarea>
	  <?php if($mode == "DirectSend") { ?>
      <table><tr><td>형식) 발송메일구분자는 공백없이 콤마(,)로 구분해주세요.</td></tr></table>
	  <?php } else { ?>
      <table><tr><td>형식) 발송메일구분자는 공백없이 콤마(,)로 구분해주세요.</td></tr></table>
	  <?php } ?>
    </td>
  </tr>
  <tr>
    <td height="30" class="t_name">메일제목</td>
    <td class="t_value"><input type="text" name="subject" size="55" value="<?php echo $edit_subject ?>" class="input" style="width:90%"></td>
  </tr>
  <tr>
    <td colspan="2" class="t_value">
    <?php
    $edit_content = stripslashes($row->email_msg);
	$mrow = sql_fetch("select id,name,email,passwd from wiz_member where email='".$seluser."' ");
	$re_info['name'] = $mrow['name'];
    $edit_content = info_replace($site_info, $re_info, $edit_content);
    include "../../webedit/WIZEditor.html";
    ?>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding=0 cellspacing=0>
  <tr>
    <td align="center" colspan="2">
	  <input type="submit" class="base_btn5 sms" value="발송하기">
    </td>
  </tr>
</table>
</form>
</td>
</tr>
</table>
</body>
</html>