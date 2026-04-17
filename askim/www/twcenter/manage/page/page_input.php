<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<?
if($mode == "") $mode = "insert";
if($mode == "update"){
	$sql = "select * from wiz_page where idx='$idx'";
	$result = query($sql) or error("sql error");
	$page_info = sql_fetch_obj($result);
	$page_info->content = stripslashes($page_info->content);
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
function inputCheck(frm){

	if(frm.code.value == ""){
		alert("코드를 입력하세요.");
		frm.code.focus();
		return false;
	} else if(!check_Char(frm.code.value)) {
 		alert('코드는 특수문자를 사용할 수 없습니다.');
		frm.code.focus();
 		return false;
   }
   if(frm.menu.value == ""){
      alert("그룹메뉴를 선택하세요");
      frm.menu.focus();
      return false;
   }
   if(frm.title.value == ""){
      alert("페이지명을 입력하세요");
      frm.title.focus();
      return false;
   }
   if(content.outputBodyHTML() == ""){
      alert("페이지내용을 입력하세요");
      return false;
   }

}
function popGrp() {
	var url = "group.php";
	window.open(url,"PAGEGroup","height=300, width=500, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
function grp_reload() {
	let obj = $("select[name=menu]");
	let old_grp = obj.find("option:selected").val();
	$.get("./page_save.php", "mode=get_grplist", function(res) {
		obj.find("option").not("[value='']").remove();
		for(let i = 0; i < res.length; i++) {
			selected = (old_grp == res[i]['idx']) ? " selected" : "";
			obj.append("<option value='"+res[i]['idx']+"'"+selected+">"+res[i]['grpname']+"</option>");
		}
	}, "json");
}
//-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">페이지관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">페이지를 추가/수정/삭제 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="page_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
        <tr>
          <td width="15%" class="t_name">페이지코드 <font color="red">*</font></td>
          <td width="85%" class="t_value" colspan="3">
          <input type="text" name="code" value="<?=$page_info->code?>" size="30" class="input" maxlength="30" <? if($mode == "update") echo "readonly"; ?>> 영문입력
          </td>
        </tr>
        <tr>
          <td height="10" align="left" class="t_name">메뉴 <font color="red">*</font></td>
          <td class="t_value" colspan="3">
          	<?php
			$grp_list = get_grplist("page");
          	?>
          	<select name="menu" id="page_grp" class="select">
          		<option value="">:: 페이지그룹 ::</option>
          		<? foreach($grp_list as $grp) { ?>
          		<option value="<?=$grp['idx']?>" <? if($page_info->menu == $grp['idx']) echo "selected" ?>><?=$grp['grpname']?></option>
          		<? } ?>
          	</select>
			<input type="button" value="그룹관리" class="base_btm reg" onclick="popGrp()">&nbsp;
          	우선순위
          	<select name="prior" class="select">
          		<? for($ii = 1; $ii < 11; $ii++) { ?>
          		<option value="<?=$ii?>" <? if($ii == $page_info->prior) echo "selected"; ?>><?=$ii?></option>
          		<? } ?>
          	</select> (그룹내에서 페이지 우선순위,작을수록 순위가 높음)<br>
          	페이지그룹은 페이지가 많은 경우 페이지를 그룹별로 효과적으로 관리하기 위한 기능입니다.
          </td>
        </tr>
        <tr>
          <td class="t_name">페이지명 <font color="red">*</font></td>
          <td class="t_value" colspan="3">
          <input type="text" name="title" value="<?=$page_info->title?>" size="80" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">페이지url</td>
          <td class="t_value" colspan="3">
          http://<?=$HTTP_HOST?>/<input type="text" name="url" value="<?=$page_info->url?>" size="52" class="input">
          </td>
        </tr>
		<tr>
			<td width="15%" align="left" class="t_name">페이지 브라우저타이틀</td>
			<td class="t_value padd" colspan="3">
				<input type="text" name="browser_title" value="<?=$page_info->browser_title?>" size="80" class="input">
			</td>
		</tr>

		<tr>
			<td width="15%" align="left" class="t_name">공통 메타네임(Description)</td>
			<td class="t_value padd" colspan="3">
				<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?php echo $page_info->searchkey_de ?></textarea>
				<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
				<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
			</td>
		</tr>
		<tr>
			<td width="15%" align="left" class="t_name">공통 메타네임(Classification)</td>
			<td class="t_value padd" colspan="3">
				<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?php echo $page_info->searchkey_cl ?></textarea>
				<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
			</td>
		</tr>

		<tr>
			<td width="15%" align="left" class="t_name">공통 메타네임(keywords)</td>
			<td class="t_value padd" colspan="3">
				<textarea name="searchkey" rows="3" cols="120" class="textarea"><?php echo $page_info->searchkey ?></textarea>
				<div class="sub_tit_alt_red">※ 해당 항목은 네이버 및 구글에선 참고용으로만 활용합니다.</div>
				<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 사이트의 접근성을 향상에 활용한다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며 일부 반영이 안될 수 있습니다.</div>
				<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
				<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
			</td>
		</tr>

        <tr>
          <td class="t_value" colspan="4">
		  <dl class="page_crome">
			<dt>본문수정 시 <span>크롬 브라우저 사용</span>을 권장해 드립니다.</dt>
			<dd>사용되어지고 있는 편집기의 특성상 인터넷 익스플로러에서 선택이 잘 되지않는 현상이 발생되어 수정에 어려움이  발생되고 있습니다.<br>위와 같은 현상이 크롬브라우저에서는 나타나지 않으니 크롬 브라우저를 이용하여 수정하시면 더 편리합니다.</dd>
			<a href="https://www.google.com/chrome/" target="_blank">다운로드LINK</a>
		  </dl>
          <?
          $edit_height = "500";
          $edit_content = $page_info->content;
          include "../../webedit/WIZEditor.html";
          ?>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onclick="document.location='page_list.php?page=<?=$page?>&<?=$menucodeParam?>';">
          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>