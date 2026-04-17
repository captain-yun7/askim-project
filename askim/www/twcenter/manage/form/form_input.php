<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "code=".$code."&searchopt=".$searchopt."&searchkey=".$searchkey."&searchstatus=".$searchstatus."&".$menucodeParam;

$sql = "select * from wiz_form where idx = '$idx'";
$result = query($sql) or error("sql error");
$form_info = sql_fetch_arr($result);
$form_info['reply'] = stripslashes($form_info['reply']);

$sql = "select idx from wiz_forminfo where code = '".$form_info['code']."'";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$fidx = $row['idx'];

if(strcmp(substr($form_info['content'], 0, 3), "|^|")) $content = $form_info['content'];
else {

	$form_content = explode("|^|", $form_info['content']);

	for($ii = 0; $ii < count($form_content); $ii++) {
		list($frm_idx, $frm_data) = explode("||", $form_content[$ii]);
		$form_data[$frm_idx] = $frm_data;
	}

	$no = 1;
	$file_exist = false;
	$content = "<table width=100% border=0 cellspacing=1 cellpadding=3 style=\"font-size:12px; border :solid #b8d9e1 1px; border-bottom:none; \">";
	$sql = "
		select * 
		  from wiz_formfield 
		 where fidx = '$fidx' 
		   and ftype != 'spamcheck' 
		 order by fprior asc, idx asc
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){

		$form_ftype = $row['ftype'];

		if(strpos($form_ftype, "file") !== false) {
			$file_exist = true;
		}

		$content .= "<tr>";
		$content .= "<td width=80 style=\"color: #11809f; background: #e8f3f7; line-height: 15px; padding-left:10px; height:30px; border-bottom:1px solid #b8d9e1;\">".$row['fname']."</td>";
		$content .= "<td style=\"color: #555555; background: #ffffff; line-height: 20px; padding-left:10px; border-bottom:1px solid #b8d9e1;\">".$form_data[$row['idx']]."&nbsp;</td>";
		$content .= "</tr>";
		$no++;
	}
	$content .= "</table>";

}

?>
<? include "../head.php"; ?>

      <script language="JavaScript" type="text/javascript">
			<!--
			function inputCheck(frm){
				content.outputBodyHTML();
			}
			//-->
			</script>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">폼메일관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">작성된 폼메일을 관리 합니다.</td>
        </tr>
      </table>

      <br>
      <form action="form_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="searchopt" value="<?=$searchopt?>">
      <input type="hidden" name="searchkey" value="<?=$searchkey?>">
      <input type="hidden" name="searchstatus" value="<?=$searchstatus?>">
      <input type="hidden" name="mode" value="update">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">제목</td>
                <td colspan="3" class="t_value"><input type="text" name="subject" value="<?=$form_info['subject']?>" size="60" class="input"></td>
              </tr>
			  <?php if($file_exist) { ?>
              <tr>
                <td class="t_name">첨부파일</td>
                <td colspan="3" class="t_value">
                	<a href="down.php?idx=<?=$idx?>&no=1"><?=$form_info['upfile1_name']?></a>
                	<a href="down.php?idx=<?=$idx?>&no=2"><?=$form_info['upfile2_name']?></a>
                	<a href="down.php?idx=<?=$idx?>&no=3"><?=$form_info['upfile3_name']?></a>
					<a href="down.php?idx=<?=$idx?>&no=4"><?=$form_info['upfile4_name']?></a>
					<a href="down.php?idx=<?=$idx?>&no=5"><?=$form_info['upfile5_name']?></a>
                </td>
              </tr>
			  <?php } ?>
              <tr>
                <td width="15%" class="t_name">이름</td>
                <td width="35%" class="t_value">
                	<input type="text" name="name" value="<?=$form_info['name']?>" class="input">
                </td>
                <td width="15%" class="t_name">연락처</td>
                <td width="35%" class="t_value">
                	<input type="text" name="phone" value="<?=$form_info['phone']?>" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">이메일</td>
                <td class="t_value">
                	<input type="text" name="email" value="<?=$form_info['email']?>" class="input">
                </td>
                <td class="t_name">처리상태</td>
                <td class="t_value">
                  <select name="status" class="select">
                  <option value="">- 선택 -</option>
                	<option value="대기중" <? if($form_info['status'] == "대기중") echo "selected"; ?>>대기중</option>
                	<option value="처리중" <? if($form_info['status'] == "처리중") echo "selected"; ?>>처리중</option>
                	<option value="처리완료" <? if($form_info['status'] == "처리완료") echo "selected"; ?>>처리완료</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="t_name">작성내용</td>
                <td colspan="3" class="t_value">
                	<?=$content?>
                </td>
              </tr>
              <tr>
                <td class="t_name">답변내용</td>
                <td colspan="3" class="t_value">
                	<?
                	$edit_content = $form_info['reply'];
									include "../../webedit/WIZEditor.html";
									?>
									&nbsp; <input type="checkbox" name="smail" value="Y">답변 메일로 보내기 (체크시 작성자 이메일로 답변을 보냅니다.)
                </td>
              </tr>
            </table></td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr>
      		<td align="center">
				<input type="submit" value="확인" class="base_btn reg">&nbsp;<input type="button" value="목록" class="base_btn gray" onClick="document.location='form_list.php?<?=$param?>&page=<?=$page?>'";>
      		</td>
      	</tr>
      </table>
	  </form>


<? include "../foot.php"; ?>