<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";

$mode = $mode ? $mode : "sendmail";
// 메일발송
if($smode == "mailok"){

	$tmp_subject = $subject;
	$tmp_content = $content;

	$user_list = explode(",",$seluser);
	$user_info_list = explode(",",$seluser_info);

	for($ii=0; $ii < count($user_list); $ii++){

		$mrow = sql_fetch("select id,name,email,passwd from wiz_member where email='".$user_list[$ii]."' ");

		$re_info = "";

		$re_id = $re_passwd = $re_name = $re_email = '';

		if ($mode == "sendmail") {
		    $user_list = explode(",", $_POST['seluser'] ?? '');
		    $user_info_list = explode(",", $_POST['seluser_info'] ?? '');  // 비어있으면 빈 배열처럼 처리됨

		    if (isset($user_list[$ii])) {
		        $tmp = explode(":", $user_list[$ii]);
		        $re_name  = $tmp[0] ?? '';
		        $re_email = $tmp[1] ?? '';
		    }

		    if (isset($user_info_list[$ii])) {
		        $tmp2 = explode(":", $user_info_list[$ii]);
		        $re_id     = $tmp2[0] ?? '';
		        $re_passwd = $tmp2[1] ?? '';
		    }
		} else {
		    $re_id     = $mrow['id'] ?? '';
		    $re_passwd = $mrow['passwd'] ?? '';
		    $re_name   = $mrow['name'] ?? '';
		    $re_email  = $mrow['email'] ?? '';
		}

		// 이후 안전하게 처리 가능
		$re_info = [];

		if ($re_id !== '')     $re_info['id'] = $re_id;
		if ($re_passwd !== '') $re_info['passwd'] = $re_passwd;
		if ($re_name !== '')   $re_info['name'] = $re_name;
		if ($re_email !== '')  $re_info['email'] = $re_email;

		$subject = info_replace($site_info, $re_info, $tmp_subject);
		$content = info_replace($site_info, $re_info, $tmp_content);
		$content = stripslashes($content);	// 자동역슬래쉬 제거(역슬래쉬있으면 이미지 깨짐)

		if($re_name != "") send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content);

	}

	echo "<script>alert('이메일 발송을 완료하였습니다.');self.close();</script>";
	exit;

} 


// 메일스킨
$sql = "select * from wiz_mailsms where code = 'basic'";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);

?>
<html>
<head>
<title>:: 메일발송 ::</title>
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
		<td width="50%">이메일발송</td>
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
      <textarea rows="3" cols="50" name="seluser" class="textarea" style="width:100%"><?php echo $seluser ?></textarea>
	  <?php if($mode == "DirectSend") { ?>
      <table><tr><td><span class="sub_tit_alt2">- 발송메일구분자는 콤마(,)로 구분해주세요</span></td></tr></table>
	  <?php } else { ?>
      <table><tr><td><span class="sub_tit_alt2">- 발송메일구분자는 콤마(,)로 구분해주세요. 형식) 홍길동:test@test.com,</span></td></tr></table>
	  <?php } ?>
    </td>
  </tr>
  <tr>
    <td height="30" class="t_name">메일제목</td>
    <td class="t_value"><input type="text" name="subject" size="55" class="input" style="width:100%"></td>
  </tr>
  <tr>
    <td colspan="2" class="t_value">
    <?php
    $edit_content = $row->email_msg;
    $edit_content = info_replace($site_info, $re_info, $edit_content);
    include "../../webedit/WIZEditor.html";
    ?>
     <table width="98%" border="0" cellpadding="5" cellspacing="3" align="center" class="e_style">
        <tr>
          <td bgcolor="#FFFFFF">
          <table>
          <tr>
          <td><b>{DATE}</b> 오늘날짜 &nbsp;</td>
          <td><b>{MEM_ID}</b> 회원아이디 &nbsp;</td>
          <!--td><b>{MEM_PW}</b> 회원비밀번호 &nbsp;</td-->
          <td><b>{MEM_NAME}</b> 회원이름</td>
          </tr>
          <tr>
          <td><b>{SITE_NAME}</b> 사이트명 &nbsp;</td>
          <td><b>{SITE_EMAIL}</b> 사이트 이메일</td>
          </tr>
          <tr>
          <td><b>{SITE_TEL}</b> 사이트 전화번호 &nbsp;</td>
          <td colspan=2><b>{SITE_URL}</b> 사이트 주소로 변경되어 발송됩니다.</td>
          </tr>
          </table>
        </tr>
      </table>
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