<? include_once "../../common.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
if($mode == "mailsend"){

	$subject = "메일발송 테스트입니다.";
	$mail_body = "
	-------------------------------------------------
	[메일테스트]
	이 내용이 제대로 보이면 이메일이 수신된것 입니다.
	".date('Y-m-d h:i:s')."
	-------------------------------------------------
	";

	$mail = explode(",",$mailtmp);
	for($ii=0;$ii<count($mail);$ii++){
		//mail($mail[$ii], $subject, $mail_body, $header);
		$re_name  = $mail[$ii];
		$re_email  = $mail[$ii];
		send_mail($site_info['site_name'], $site_info['site_email'], $re_name, $re_email, $subject, $mail_body);
	}

	echo "<script>alert('메일을 발송하였습니다.');history.go(-1);</script>";

}
?>
<? include "../head.php"; ?>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메일발송테스트</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">서버에서 메일이 잘 발송되는지 테스트합니다.</td>
        </tr>
      </table>

      <br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="font-size: 10pt; color: #000000; line-height: 20px;">
          사이트에서 메일을 발송했음에도 불구하고 메일을 수신 하지 못하는경우<br>
					프로그램상의 문제이기 보다는 대부분 서버 또는 발송이메일의 문제입니다.<br>
					<b>사용하고 계신 프로그램은 많은 사이트 개발에 이용된 프로그램으로 메일발송 프로그램에는 문제가 없습니다.</b><br><br>
					각 포털과 여러메일로 메일을 발송해서 아무곳도 수신이 안되는지 일부만 되는지 체크해보시기 바랍니다.<br>
					아래내용과 같이 수신됩니다.<br>
          -------------------------------------------------------------------------------<br>
					[메일테스트]<br>
					이 내용이 제대로 보이면 이메일이 수신된것 입니다.<br>
					2009-10-01 12:12:12<br>
					-------------------------------------------------------------------------------<br>
          </td>
        </tr>
      </table>
			<br>
      <form name="frm" action="<?=$PHP_SELF?>" method="post">
      <input type="hidden" name="mode" value="mailsend">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" align="left" class="t_name">받는메일주소</td>
                <td width="85%" class="t_value" colspan="3">
                	<table border="0" cellspacing="0" cellpadding="0">
                		<tr>
                			<td><input name="mailtmp" value="" size="60" type="text" class="input"></td>
                			<td>&nbsp;<input type="image" src="../image/btn_sendmail.gif"></td>
                		</tr>
                			<td colspan="2">메일을 ,로 구분해서 여러개 입력 (aaa@hanmail.net,bbb@naver.com)</td>
                		</tr>
                	</table>
                </td>
              </tr>
            </table></td>
        </tr>
      </table>
	  </form>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="b_title02">메일수신이 안되는경우</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="font-size: 10pt; color: #000000; line-height: 20px;">

          프로그램상에서 처리해드릴 수 있는 부분이 없습니다.<br>
					호스팅회사나 서버관리자에게 메일발송테스트 한 결과를 설명하고 해결요청을 하시기 바랍니다.<br><br>

					호스팅회사나 서버관리자가 화이트도메인 등록이 필요하다고 요청한경우<br>
				  한국정보보호진흥원 KISA(<a href="http://www.kisarbl.or.kr" target="_blank">http://www.kisarbl.or.kr</a>) 사이트에서 등록합니다.<br>
					등록후 SPF Record 레코드값을 호스팅 화사나 서버관리자에게 전달하고 적용을 요청합니다.<br>

          </td>
        </tr>
      </table>

<? include "../foot.php"; ?>