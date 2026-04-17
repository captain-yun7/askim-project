<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php include_once "../../inc/site_info.php"; ?>
<?php
if($mode == "" || $mode == "insert"){

	$sql = "delete from wiz_forminfo where code = ''";
	query($sql);

	$sql = "insert into wiz_forminfo(idx,code,title,skin,rece_sms,rece_email,rece_bbs) values('','$code','$title','','Y','Y','Y' )";
	query($sql);

	$sql = "select max(idx) as idx from wiz_forminfo";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$idx = $row['idx'];
	$mode = "update";
}

if($mode == "update"){
	$sql = "select * from wiz_forminfo where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$form_info = sql_fetch_arr($result);

	if(empty($form_info['code'])) $fmode = "insert";
	else $fmode = "update";
}

include "../head.php";

?>

<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

	if(frm.code.value == ""){
		alert('폼메일 코드를 입력하세요.');
		frm.code.focus();
		return false;
	} else if(!check_Char(frm.code.value)) {
		alert('코드는 특수문자를 사용할 수 없습니다.');
		frm.code.focus();
		return false;
	}

	if(frm.title.value == ""){
		alert('폼메일명을 입력하세요.');
		frm.title.focus();
		return false;
	}

}

function deleteForm(idx){
	if(confirm('선택한 폼을 삭제하시겠습니까?')){
		document.location = 'form_save.php?mode=form&sub_mode=delete&code=<?=$code?>&idx=' + idx;
	}
}

function resizeIframe(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">폼메일</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">폼메일을 생성/항목을 추가/수정/삭제 관리합니다.</td>
	</tr>
</table>
<br>
<form name="frm" action="form_save.php" method="post" onSubmit="return inputCheck(this);" style="margin:0;">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">폼메일코드(영문) <font color=red>*</font></td>
					<td width="35%" class="t_value">
						<input name="code" type="text" value="<?=$form_info['code']?>" class="input">
					</td>
					<td width="15%" class="t_name">폼메일명 <font color=red>*</font></td>
					<td width="35%" class="t_value">
						<input name="title" type="text" value="<?=$form_info['title']?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">스킨</td>
					<td class="t_value">
						<select name="skin" class="select">
						<?php
						$dh = opendir("../../form/skin");
						while(($file = readdir($dh)) !== false){
							if($file != "." && $file != ".."){
								$file_list[] = $file;
							}
						}
						sort ($file_list); reset ($file_list);
						for($ii=0;$ii<count($file_list);$ii++){
						?>
							<option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
						<?php
						}
						?>
						</select>
						<script language="javascript">
						<!--
						skin = document.frm.skin;
						for(ii=0; ii<skin.length; ii++){
							if(skin.options[ii].value == "<?=$form_info['skin']?>")
							skin.options[ii].selected = true;
						}
						-->
						</script>
					</td>
					<td class="t_name">스팸글체크</td>
					<td class="t_value">항목추가 시 항목속성을 "스팸글체크"를 선택하면<br>사용할 수 있습니다. </td>
				</tr>
				<tr>
					<td class="t_name">발신설정</td>
					<td colspan="3" class="t_value">
						<?=$site_info['site_name']?>&lt;<?=$site_info['site_email']?>>
						<span class="tit_alt" style="color:#FF0000">기본설정 > 사이트정보 > "사이트명"과  "관리자 이메일"에 입력된 값으로 발송됩니다.</span>
					</td>
				</tr>
				<tr>
					<td width="15%" height="10" align="left" class="t_name">브라우저 타이틀</td>
					<td width="85%" class="t_value" colspan="3"><input name="browser_title" type="text" size="60" value="<?=$form_info['browser_title']?>" class="input"></td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(Description)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?=$form_info['searchkey_de']?></textarea>
						<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
						<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
					</td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(Classification)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?=$form_info['searchkey_cl']?></textarea>
						<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
					</td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(keywords)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey" rows="3" cols="120" class="textarea"><?=$form_info['searchkey']?></textarea>
						<div class="sub_tit_alt_red">※ 해당 항목은 네이버 검색에만 적용되며, 구글에선 참고용으로만 활용합니다.</div>
						<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 활용해 검색된다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며, 일부 반영이 안될 수 있습니다.</div>
						<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
						<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
					</td>
				</tr>
				<tr>
					<td class="t_name">약관설정</td>
					<td colspan="3" class="t_value">
						<table width="100%" border="0" cellspacing="2" cellpadding="1">
							<tr>
								<td><span style="vertical-align: middle"><input name="agree_use" type="checkbox" value="Y" <? if($form_info['agree_use'] == "Y") echo "checked"; ?>></span>사용함</td>
							</tr>
							<tr>
								<td>
									<textarea name="agree_text" rows="10" cols="100" class="textarea"><?=$form_info['agree_text']?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">수신설정</td>
					<td colspan="3" class="t_value">
						<table width="100%" border="0" cellspacing="2" cellpadding="1">
							<tr>
								<td width="100"><span style="vertical-align: middle"><input name="rece_bbs" type="checkbox" value="Y" <? if($form_info['rece_bbs'] == "Y") echo "checked"; ?> onClick="this.checked=true;"></span>게시판 수신</td>
								<td><font color=red>게시판 수신은 필수입니다.</font></td>
							</tr>
							<tr>
								<td><span style="vertical-align: middle"><input name="rece_email" type="checkbox" value="Y" <? if($form_info['rece_email'] == "Y") echo "checked"; ?>></span>email 수신</td>
								<td>
									<input type="text" name="email_list" value="<?=$form_info['email_list']?>" size="40" style="width:90%" class="input" placeholder="예) test@test.com,aaa@aaa.com">
								</td>
							</tr>

							<? if($site_info['sms_use'] == "Y"){ ?>
							<tr>
								<td><span style="vertical-align: middle"><input name="rece_sms" type="checkbox" value="Y" <? if($form_info['rece_sms'] == "Y") echo "checked"; ?>></span>SMS 수신</td>
								<td>
									<input type="text" name="sms_list" value="<?=$form_info['sms_list']?>" size="40" style="width:90%" class="input" placeholder="예) 011-1234-5678,010-321-6547">
								</td>
							</tr>
						<? } ?>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="6">
				<tr>
					<td>
						<font color=red>
						- 이메일, sms수신은 여러명이 동시에 수신할 수 있습니다. 수신할 이메일 sms를 콤마(,)로 구분하여 입력합니다.
						</font>
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
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='form_config.php';">
		</td>
	</tr>
</table>
</form>

<br><br>

<iframe src="form_field.php?fidx=<?=$idx?>&code=<?=$form_info['code']?>&fmode=<?=$fmode?>" width="100%" frameborder="0" marginwidth="0" onload="resizeIframe(this)"></iframe>

<?php include "../foot.php"; ?>