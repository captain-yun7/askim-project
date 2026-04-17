<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
// 회원정보
if($mode == "") $mode = "insert";
if($mode == "update"){
	$sql = "select * from wiz_message where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$msg_info = sql_fetch_arr($result);
}
?>
<? include_once "../../inc/mem_info.php"; ?>
<?
$param = "slevel=".$slevel."&searchopt=".$searchopt."&searchkey=".$searchkey."&page=".$page."&".$menucodeParam;
?>
<? include "../head.php"; ?>

<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.subject.value == ""){
		alert("제목을 입력하세요");
		frm.subject.focus();
		return false;
	}
/*	if(frm.content.value == ""){
		alert("내용을 입력하세요");
		frm.content.focus();
		return false;
	}*/
	try{ content.outputBodyHTML(); } catch(e){ }
	if(frm.content.value == ""){
		alert("내용을 입력하세요.");
		return false;
	}

}
-->
</script>
</head>

<table border="0" cellspacing="0" cellpadding="2">
<tr>
  <td><img src="../image/ic_tit.gif"></td>
  <td valign="bottom" class="tit">쪽지목록</td>
  <td width="2"></td>
  <td valign="bottom" class="tit_alt">주고받은 쪽지를 관리합니다.</td>
</tr>
</table>

<br>
<form name="frm" action="message_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td>
		<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
		  <tr>
			<td width="15%" class="t_name">발송일</td>
			<td width="85%" class="t_value" colspan="3"><?=$msg_info['wdate']?></td>
		  </tr>
		  <tr>
			<td width="15%" class="t_name">보낸사람(이름)</td>
			<td width="35%" class="t_value"><input name="re_name" type="text" value="<?=$msg_info['se_name']?>" class="input" <? if($mode == "update") echo "readonly"; ?>></td>
			<td width="15%" class="t_name">보낸사람(아이디)</td>
			<td width="35%" class="t_value"><input name="re_id" type="text" value="<?=$msg_info['se_id']?>" class="input" <? if($mode == "update") echo "readonly"; ?>></td>
		  </tr>
		  <tr>
			<td class="t_name">받은사람(이름)</td>
			<td class="t_value"><input name="se_name" type="text" value="<?=$msg_info['re_name']?>" class="input" <? if($mode == "update") echo "readonly"; ?>></td>
			<td class="t_name">받은사람(아이디)</td>
			<td class="t_value"><input name="se_id" type="text" value="<?=$msg_info['re_id']?>" class="input" <? if($mode == "update") echo "readonly"; ?>></td>
		  </tr>
		  <tr>
			<td class="t_name">제목</td>
			<td class="t_value" colspan=3>
				<input name="subject" type="text" value="<?=$msg_info['subject']?>" class="input" size=90>
			</td>
		  </tr>
		  <tr>
			<td height="25" class="t_name">내용</td>
			<td class="t_value" colspan="3">
				<?
				if($site_info['msg_editor_use'] == "Y"){
					$edit_name	  = "content";
					$edit_content = $msg_info['content'];
					include WIZHOME_PATH."/webedit/WIZEditor.html";
				}else{
				?>
				<textarea name="content" cols="85" rows="13" class="textarea" style="width:98%;word-break:break-all;"><?=$msg_info['content']?></textarea>
				<?
				}
				?>

			</td>
		  </tr>
		  <tr>
			<td class="t_name">첨부파일</td>
			<td class="t_value" colspan=3>
				<div class="filebox preview-image">
					<input class="input upload-name" value="파일선택" disabled="disabled">
					<label for="input-file">이미지 업로드</label>
					<input type="file" name="upfile" id="input-file" class="upload-hidden">
				</div>
			<? if($msg_info['upfile'] != ""){ ?>
				&nbsp;<a href="message_save.php?mode=delfile&idx=<?=$idx?>&file=<?=$msg_info['upfile']?>&se_id=<?=$msg_info['se_id']?>">
					<button type="button" class="small_btn">삭제</button>
					</a>&nbsp;
				<a href='../../data/message/<?=$msg_info['se_id']?>/<?=$msg_info['upfile']?>' target='_blank'><?=$msg_info['upfile_name']?></a>
			<? } ?>
			</td>
		  </tr>
		  <tr>
			<td class="t_name">수신여부</td>
			<td class="t_value" colspan=3>
				<input name="status" type="radio" value="Y" <? if(!strcmp($msg_info['status'], 'Y')) echo 'checked' ?>> 읽음
				<input name="status" type="radio" value="N" <? if(!strcmp($msg_info['status'], 'N') || empty($msg_info['status'])) echo 'checked' ?>> 읽지않음
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
		<input type="button" value="목록" class="base_btn gray" onClick="document.location='message_list.php?<?=$param?>';">
	  </td>
	</tr>
</table>
</form>

<? include "../foot.php"; ?>