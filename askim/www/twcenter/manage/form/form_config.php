<?php
include "../../common.php";
include "../../inc/admin_check.php";
include_once "../../inc/site_info.php";

if($save == 'ok') {
	$sql = "update wiz_forminfo set
		rece_sms           = '$rece_sms'
		, rece_email         = '$rece_email'
		, rece_bbs           = '$rece_bbs'
		, sms_list           = '$sms_list'
		, email_list         = '$email_list'
		where code='$code'";
	if(query($sql)) {
		complete("폼메일 수신설정이 변경되었습니다.", $_SERVER['HTTP_REFERER']);
	} else {
		error("오류가 발생했습니다.");
	}
} else {
	$sql = "select * from wiz_forminfo where code='$code'";
	$form_info = sql_fetch($sql);
?>
<html>
<head>
<title>:: 폼메일 수신설정 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>

</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">폼메일 수신설정</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$form_info['title']?></td>
  </tr>
</table>
<span class="tip_br5"></span>
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
<input type="hidden" name="save" value="ok">
<input type="hidden" name="code" value="<?=$code?>">
<table width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="center">
			<table width="99%" align="center" border="0" cellpadding="1" cellspacing="1" class="t_style">
				<tr>
					<td class="t_name">발신설정</td>
					<td colspan="3" class="t_value">
						<?=$site_info['site_name']?>&lt;<?=$site_info['site_email']?>>
						<br><span style="font-weight:400;color:#FF0000">- 기본설정 > 사이트정보 > "사이트명"과  "관리자 이메일"에 입력된 값으로 발송됩니다.</span>
					</td>
				</tr>
				<tr>
					<td class="t_name">수신설정</td>
					<td colspan="3" class="t_value">
						<table width="100%" border="0" cellspacing="2" cellpadding="1">
							<tr>
								<td width="100"><span style="vertical-align: middle"><input name="rece_bbs" type="checkbox" value="Y" <? if($form_info['rece_bbs'] == "Y") echo "checked"; ?> onClick="this.checked=true;"></span> 게시판 수신</td>
								<td><font color=red>게시판 수신은 필수입니다.</font></td>
							</tr>
							<tr>
								<td><span style="vertical-align: middle"><input name="rece_email" type="checkbox" value="Y" <? if($form_info['rece_email'] == "Y") echo "checked"; ?>></span> email 수신</td>
								<td>
									<input type="text" name="email_list" value="<?=$form_info['email_list']?>" size="40" style="width:90%" class="input" placeholder="예) test@test.com,aaa@aaa.com">
								</td>
							</tr>

							<? if($site_info['sms_use'] == "Y"){ ?>
							<tr>
								<td><span style="vertical-align: middle"><input name="rece_sms" type="checkbox" value="Y" <? if($form_info['rece_sms'] == "Y") echo "checked"; ?>></span> SMS 수신</td>
								<td>
									<input type="text" name="sms_list" value="<?=$form_info['sms_list']?>" size="40" style="width:90%" class="input" placeholder="예) 011-1234-5678,010-321-6547">
								</td>
							</tr>
						<? } ?>
						</table>
						<span style="font-weight:400;color:#FF0000">
						- 이메일, sms수신은 여러명이 동시에 수신할 수 있습니다. 수신할 이메일 sms를 콤마(,)로 구분하여 입력합니다.
						</span>
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
			<input type="submit" value="확인" class="base_btn reg">
			<input type="button" value="닫기" class="base_btn gray" onClick="self.close();">
		</td>
	</tr>
</table>
</form>
<? } ?>