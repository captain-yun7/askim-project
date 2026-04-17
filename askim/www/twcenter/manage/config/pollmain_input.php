<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<?
$sql = "select * from wiz_pollinfo where code = '$code'";
$result = query($sql) or error("sql_error");
$total = sql_fetch_row($result);
$pollmain_info = sql_fetch_arr($result);
$pollmain_info['mainskin'] = stripslashes($pollmain_info['mainskin']);

$basic_ex = "<table width=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\">\\n  <tr>\\n    <td height=\\\"20\\\" align=\\\"left\\\"><b>{SUBJECT}</b></td>\\n  </tr>\\n  <tr><td height=\\\"5\\\"></td></tr>\\n  <tr><td bgcolor=\\\"f3f3f3\\\" style=\\\"padding:4px\\\" align=\\\"left\\\">{CONTENT}</td></tr>\\n  <tr><td height=\\\"7\\\"></td></tr>\\n\\n  [LOOP]\\n  <tr><td align=\\\"left\\\"><img src=\\\"{SKIN_DIR}/image/point.gif\\\" align=\\\"absmiddle\\\"> {QUESTION}</td></tr>\\n\\n  [LOOP2]\\n  <tr><td>{ANSWER}</td></tr>\\n  [/LOOP2]\\n\\n  [/LOOP]\\n\\n  <tr><td height=\\\"10\\\"></td></tr>\\n  <tr>\\n    <td align=\\\"right\\\">{VOTE_BTN} {VIEW_BTN}</td>\\n  </tr>\\n</table>";
?>
      <script language="javascript">
      <!--
      function inputCheck(frm){

      	if(frm.mainskin.value == ""){
      		alert("스킨을 입력하세요");
      		frm.mainskin.focus();
      		return false;
      	}

      }

      function setSkin(frm, skin) {

      	if(skin == "DB") frm.mainskin.value = frm.tmp_skin.value;
      	if(skin == "BASIG") frm.mainskin.value = "<?=$basic_ex?>";

      }
      -->
      </script>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메인설문</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">메인설문 추출 상세설정을 합니다.</td>
        </tr>
      </table>

	  <br><br>
      <form name="frm" action="pollmain_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="code" value="<?=$code?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">설문코드</td>
                <td width="85%" class="t_value" colspan="3"><?=$code?></td>
              </tr>
              <tr>
                <td class="t_name">연결페이지</td>
                <td class="t_value" colspan="3">http://<?=$HTTP_HOST?>/<input name="purl" type="text" size="30" value="<?=$pollmain_info['purl']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">예제소스적용</td>
                <td class="t_value" colspan="3">
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="DB" onClick="setSkin(this.form, this.value)" checked></span>기존스킨
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="BASIG" onClick="setSkin(this.form, this.value)"></span>일반스킨
                </td>
              </tr>
              <tr>
                <td class="t_name">설문조사스킨</td>
                <td class="t_value" colspan="3">
                  <textarea name="mainskin" rows="12" cols="60" class="textarea" style="width:100%"><?=$pollmain_info['mainskin']?></textarea><br><br>
                  <textarea name="tmp_skin" rows="12" cols="60" class="textarea" style="width:100%;display:none"><?=$pollmain_info['mainskin']?></textarea>
                  <font color="red">반복되는 부분을 [LOOP]와 [/LOOP]사이에 위치하게 합니다.</font><br><br>
                  <font color="red">반복되는 답변 부분을 [LOOP2]와 [/LOOP2]사이에 위치하게 합니다.</font><br><br>
                  <table width="100%" border="0" cellpadding="5" cellspacing="3" class="e_style">
                    <tr>
                      <td bgcolor="#FFFFFF">
                        <table width="100%" border="0">
                        <tr><td><b>{SUBJECT}</b> 설문조사 제목 &nbsp; <b>{CONTENT}</b> 설문조사 내용&nbsp;  <b>{QUESTION}</b> 질문 &nbsp; <b>{ANSWER}</b> 답변</td></tr>
                        <tr><td><b>{VOTE_BTN}</b> 투표하기 버튼 &nbsp; <b>{VIEW_BTN}</b> 결과보기 버튼&nbsp;</td></tr>
                        </table>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table></td>
        </tr>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='pollmain_config.php';">
          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>