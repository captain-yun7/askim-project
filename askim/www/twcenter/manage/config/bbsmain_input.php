<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');

$bbs_ex = "<table width=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\">\\n[LOOP]\\n<tr>\\n  <td width=\\\"5\\\" height=\\\"20\\\"><img src=\\\"/twcenter/bbsmain/image/point.gif\\\" width=\\\"3\\\" height=\\\"3\\\"></td>\\n  <td width=\\\"5\\\"></td>\\n  <td  align=\\\"left\\\"><a href=\\\"{LINK}\\\">{SUBJECT}</a>{NEW}</td>\\n  <td align=\\\"right\\\">{DATE}</td>\\n</tr>\\n[/LOOP]\\n</table>";
$photo_ex = "<table width=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\">\\n[LOOP]\\n<td>\\n  <table>\\n  <tr><td><a href=\\\"{LINK}\\\"><img src=\\\"{PHOTO}\\\" width=\\\"70\\\" border=\\\"0\\\"></a></td></tr>\\n  <tr><td><a href=\\\"{LINK}\\\">{SUBJECT}</a></td></tr>\\n  </table>\\n</td>\\n[/LOOP]\\n</table>";
$thumb_ex = "<table width=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\">\\n[LOOP]\\n<tr>\\n  <td>\\n    <table>\\n    <tr>\\n    <td rowspan=\\\"2\\\"><a href=\\\"{LINK}\\\"><img src=\\\"{PHOTO}\\\" width=\\\"70\\\" border=\\\"0\\\"></a></td>\\n    <td><a href=\\\"{LINK}\\\">{SUBJECT}</a></td>\\n    </tr>\\n    <tr><td>{CONTENT}</td></tr>\\n    </table>\\n  </td>\\n</tr>\\n[/LOOP]\\n</table>";

if(!strcmp($mode, "insert")) {
	$bbsmain_info['cnt'] = 5;
	$bbsmain_info['line'] = 0;
	$bbsmain_info['subject_len'] = 30;
	$bbsmain_info['content_len'] = 80;
	$bbsmain_info['skin'] = str_replace("\\n", "\n", $bbs_ex);
} else {

	$mode = "update";

	$sql = "select * from wiz_bbsmain where code = '$code' and idx = '$idx'";
	$result = query($sql);
	$total = sql_fetch_row($result);
	$bbsmain_info = sql_fetch_arr($result);
	$bbsmain_info['skin'] = stripslashes($bbsmain_info['skin']);
}

 ?>
      <script language="javascript">
      <!--
      function inputCheck(frm){

      	if(frm.code.value == ""){
      		alert("게시판 코드를 선택하세요");
      		frm.code.focus();
      		return false;
      	}
      	if(frm.subject_len.value == ""){
      		alert("제목 글자수를 입력하세요");
      		frm.subject_len.focus();
      		return false;
      	} else if(!check_Num(frm.subject_len.value)) {
      		alert("제목 글자수는 숫자로 입력하세요");
      		frm.subject_len.focus();
      		return false;
      	}

				if(frm.content_len.value != "" && !check_Num(frm.content_len.value)) {
					alert("내용 글자수는 숫자만 입력하세요.");
					frm.content_len.focus();
					return false;
				}

      	if(frm.cnt.value == ""){
      		alert("게시물 수를 입력하세요");
      		frm.cnt.focus();
      		return false;
      	} else if(!check_Num(frm.cnt.value)) {
      		alert("게시물 수는 숫자로 입력하세요");
      		frm.cnt.focus();
      		return false;
      	}

				if(frm.line.value != "" && !check_Num(frm.line.value)) {
					alert("줄바꿈 게시물수는 숫자만 입력하세요.");
					frm.line.focus();
					return false;
				}

      	if(frm.skin.value == ""){
      		alert("스킨을 입력하세요");
      		frm.skin.focus();
      		return false;
      	}

      }

      function setSkin(frm, skin) {

      	if(skin == "DB") frm.skin.value = frm.tmp_skin.value;
      	if(skin == "BBS") frm.skin.value = "<?php echo $bbs_ex ?>";
      	if(skin == "PHOTO") frm.skin.value = "<?php echo $photo_ex ?>";
      	if(skin == "THUMB") frm.skin.value = "<?php echo $thumb_ex ?>";

      }
      -->
      </script>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메인게시물</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">메인게시물 추출 상세설정을 합니다.</td>
        </tr>
      </table>

			<br>
      <form name="frm" action="bbsmain_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="<?php echo $mode ?>">
      <input type="hidden" name="idx" value="<?php echo $idx ?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">게시판코드</td>
                <td class="t_value" colspan="3">
					<?php
					if(!strcmp($mode, "insert")) {
					 ?>
					<select name="code" class="input">
						<option value="">::게시판선택::</option>
						<?php
							$sql = "select code, title from wiz_bbsinfo where type = 'BBS' order by code asc";
							$result = query($sql);
							while($row = sql_fetch_arr($result)) {
						 ?>
						<option value="<?php echo $row['code'] ?>"><?php echo $row['title'] ?></option>
						<?
							}
						 ?>
					</select>
					<?php
					} else {
					 ?>
					<input type="hidden" name="code" value="<?php echo $code ?>">
                	<?php echo $code ?>
					<?php
					}
					 ?>
                </td>
              </tr>
              <tr>
                <td class="t_name">연결페이지</td>
                <td class="t_value" colspan="3">http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input name="purl" type="text" size="30" value="<?php echo $bbsmain_info['purl'] ?>" class="input"></td>
              </tr>
              <tr>
                <td width="15%" class="t_name">제목 글자수</td>
                <td width="35%" class="t_value">
                  <input name="subject_len" type="text" value="<?php echo $bbsmain_info['subject_len'] ?>" class="input">
                </td>
                <td width="15%" class="t_name">내용 글자수</td>
                <td width="35%" class="t_value">
                  <input name="content_len" type="text" value="<?php echo $bbsmain_info['content_len'] ?>" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">게시물수</td>
                <td class="t_value">
                  <input name="cnt" type="text" value="<?php echo $bbsmain_info['cnt'] ?>" class="input">
                </td>
                <td class="t_name">줄바꿈 게시물수</td>
                <td class="t_value">
                  <input name="line" type="text" value="<?php echo $bbsmain_info['line'] ?>" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">예제소스적용</td>
                <td class="t_value" colspan="3">
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="DB" onClick="setSkin(this.form, this.value)" checked></span> 기존스킨
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="BBS" onClick="setSkin(this.form, this.value)"></span> 일반스킨(목록형)
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="PHOTO" onClick="setSkin(this.form, this.value)"></span> 포토스킨
                	<span style="vertical-align: middle"><input type="radio" name="skin_ex" value="THUMB" onClick="setSkin(this.form, this.value)"></span> 썸네일스킨
                </td>
              </tr>
              <tr>
                <td class="t_name">게시물스킨</td>
                <td class="t_value" colspan="3">
                  <textarea name="skin" rows="12" cols="60" class="textarea" style="width:100%"><?php echo $bbsmain_info['skin'] ?></textarea><br><br>
                  <textarea name="tmp_skin" rows="12" cols="60" class="textarea" style="width:100%;display:none"><?php echo $bbsmain_info['skin'] ?></textarea>
                  <font color=red>반복되는 부분을 [LOOP]와 [/LOOP]사이에 위치하게 합니다.</font><br>
                  <font color=red>스타일은 상단에 입력한 스타일(css, &lt;style&gt;)이 적용됩니다.</font><br>
                  <table width="100%" border="0" cellpadding="5" cellspacing="3" class="e_style">
                    <tr>
                      <td bgcolor="#FFFFFF">
                        <table width="100%" border="0">
                        <tr><td><b>{SUBJECT}</b> 제목 &nbsp; <b>{CONTENT}</b> 내용&nbsp;  <b>{DATE}</b> 작성일 &nbsp; <b>{NEW}</b> New아이콘 &nbsp; <b>{PHOTO}</b> 사진</td>
                        	<tr><td><b>{CATEGORY}</b> 카테고리 <b>{LINK}</b> 링크 <td>
                        <tr><td height="5"></td></tr>
                        <tr><td><b>{NEW}</b> : 적성한글에 하루동안 new 아이콘을 표시합니다. 아이콘 변경은 이미지 경로 확인후 덮어쓰세요.</td></tr>
                        <tr><td><b>{PHOTO}</b> : &lt;img src="{PHOTO}" width="100" height="100"&gt; 태그를 이용해 이미지 사이즈를 조정하세요.</td></tr>
                        <tr><td><b>{LINK}</b> : &lt;a href="{LINK}"&gt;&lt;img src="{PHOTO}" border="0"&gt;&lt;/a&gt; 해당 게시물로 이동</td></tr>
                        <tr><td>&nbsp; &lt;a href="{LINK}"&gt;{SUBJECT}&lt;/a&gt; 해당 게시물로 이동</td></tr>
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
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='bbsmain_config.php';">
         	</td>
        </tr>
      </table>
	  </form>

<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
