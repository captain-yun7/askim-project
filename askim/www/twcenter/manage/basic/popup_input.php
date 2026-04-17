<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
$page_name = "팝업관리";
$page_desc = "팝업내용을 설정 합니다.";
$navi_name = " 기본설정 > 팝업관리";
?>
<? include "../head.php"; ?>

<?
if($mode == "") $mode = "insert";
if($mode == "update"){
	$sql = "select * from wiz_popup where idx='$idx'";
	$result = query($sql) or error("sql error");
	$popup_info = sql_fetch_obj($result);
}
if(!empty($popup_info)) {
	if($popup_info->sdate == "") $popup_info->sdate = date('Y-m-d');
	if($popup_info->edate == "") $popup_info->edate = date('Y-m-d');

	if($popup_info->posi_x == "") $popup_info->posi_x = "100";
	if($popup_info->posi_y == "") $popup_info->posi_y = "100";

	if($popup_info->size_x == "") $popup_info->size_x = "340";
	if($popup_info->size_y == "") $popup_info->size_y = "400";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){

   if(frm.title.value == ""){
      alert("제목을 입력하세요");
      frm.title.focus();
      return false;
   }

	if(frm.posi_x.value != "" && !check_Num(frm.posi_x.value)) {
		alert("위치는 숫자만 입력하세요.");
		frm.posi_x.focus();
		return false;
	}
	if(frm.posi_y.value != "" && !check_Num(frm.posi_y.value)) {
		alert("위치는 숫자만 입력하세요.");
		frm.posi_y.focus();
		return false;
	}

	if(frm.size_x.value != "" && !check_Num(frm.size_x.value)) {
		alert("크기는 숫자만 입력하세요.");
		frm.size_x.focus();
		return false;
	}
	if(frm.size_y.value != "" && !check_Num(frm.size_y.value)) {
		alert("크기는 숫자만 입력하세요.");
		frm.size_y.focus();
		return false;
	}

  if(content.outputBodyHTML() == ""){
		alert("팝업내용을 입력하세요");
		return false;
  }

}

$(function() {

/*
	$('#sdate').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#edate').datepicker({
		language: 'kr',
		autoClose: true
	});
*/
});

//-->
</script>

<!-- datepicker_lib.php-->
<link href="/comm/jquery-ui/jquery-ui-1.11.3.min.css?ver='.MWS_VERSION.'" rel="stylesheet" type="text/css" >
<link href="/comm/jquery-ui/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/comm/jquery-ui/jquery-ui-1.11.3.min.js"></script>
<script type="text/javascript" src="/twcenter/js/datepicker.js"></script>

</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">팝업관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">팝업을 추가/수정/삭제 합니다.</td>
        </tr>
      </table>
	  <br />
<div class="helpTip box">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 팝업을 추가/수정/삭제할 수 있습니다.<br>
        - PC 또는 모바일 페이지가 별도로 제작된 경우, 사이트 전체/PC/모바일을 선택하여 팝업을 적용할 수 있습니다.<br />
        - 모바일에서는 위치 및 크기 조절이 불가합니다.<br />
		- 모바일에서 레이어 팝업 적용시, 모바일 최소 해상도(320px)에 맞게 크기가 작게 조정되어 표시되며, 일반 팝업에서는 크기 조절이 불가합니다.
	  </div>
	</div>
</div>
<br />		
      <form name="frm" action="popup_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
        <tr>
          <td class="t_name">제목 <font color="red">*</font></td>
          <td class="t_value" colspan="3">
          <input type="text" name="title" value="<?=$popup_info->title?>" size="80" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">게시기간 <font color="red">*</font></td>
          <td class="t_value" colspan="3">
          	<input type="text" name="sdate" id="sdate" value="<?=$popup_info->sdate?>" size="15" class="datepicker-here input2">
             ~
            <input type="text" name="edate" id="edate" value="<?=$popup_info->edate?>" size="15" class="datepicker-here input2">
            <? if($popup_info->edate < date('Y-m-d')) echo "<font color=red>[종료일지남]</font>"; ?>&nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">팝업형태</td>
          <td class="t_value">
            <span style="vertical-align: middle"><input name="popup_type" type="radio" value="W" size="6" class="input" <? if($popup_info->popup_type == "W" || $popup_info->popup_type == "") echo "checked"; ?>></span> 일반팝업 &nbsp;
            <span style="vertical-align: middle"><input name="popup_type" type="radio" value="L" size="6" class="input" <? if($popup_info->popup_type == "L") echo "checked"; ?>></span> 레이어팝업<div style="margin-top:5px; color:#ff0000;">※ 일반팝업은 하나만 사용하시길 권장드립니다. </div>
          </td>
          <td class="t_name">구분</td>
          <td class="t_value">
				<select name="branch" class="select">
					<option value="all" <? if($popup_info->branch == "all") echo "selected"; ?>>전체</option>
				<!-- 	<option value="korea" <? if($popup_info->branch == "korea") echo "selected"; ?>>국문</option>
					<option value="usa" <? if($popup_info->branch == "usa") echo "selected"; ?>>영문</option>
					<option value="china" <? if($popup_info->branch == "china") echo "selected"; ?>>중문</option> -->
					<!--
					<option value="all" <? if($popup_info->branch == "all") echo "selected"; ?>>PC 전체</option>
					<option value="korea" <? if($popup_info->branch == "korea") echo "selected"; ?>>PC 국문</option>
					<option value="china" <? if($popup_info->branch == "china") echo "selected"; ?>>PC 중문</option>
					<option value="usa" <? if($popup_info->branch == "usa") echo "selected"; ?>>PC 영문</option>
					<option value="japan" <? if($popup_info->branch == "japan") echo "selected"; ?>>PC 일문</option>
					<option value="m_all" <? if($popup_info->branch == "m_all") echo "selected"; ?>>Mobile 전체</option>
					<option value="m_korea" <? if($popup_info->branch == "m_korea") echo "selected"; ?>>Mobile 국문</option>
					<option value="m_china" <? if($popup_info->branch == "m_china") echo "selected"; ?>>Mobile 중문</option>
					<option value="usa" <? if($popup_info->branch == "m_usa") echo "selected"; ?>>Mobile 영문</option>
					<option value="japan" <? if($popup_info->branch == "m_japan") echo "selected"; ?>>Mobile 일문</option>
					-->
				</select>
		  </td>
        </tr>
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
            <span style="vertical-align: middle"><input name="isuse" type="radio" value="Y" size="6" class="input" <? if($popup_info->isuse == "Y" || $popup_info->isuse == "") echo "checked"; ?>></span> 사용함 &nbsp;
            <span style="vertical-align: middle"><input name="isuse" type="radio" value="N" size="6" class="input" <? if($popup_info->isuse == "N") echo "checked"; ?>></span> 사용안함
          </td>
          <td width="15%" class="t_name">스크롤여부</td>
          <td width="35%" class="t_value">&nbsp;
            <span style="vertical-align: middle"><input name="scroll" type="radio" value="Y" size="6" class="input" <? if($popup_info->scroll == "Y") echo "checked"; ?>></span> 허용함&nbsp;
            <span style="vertical-align: middle"><input name="scroll" type="radio" value="N" size="6" class="input" <? if($popup_info->scroll == "N" || $popup_info->scroll == "") echo "checked"; ?>></span> 허용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">위치 <font color="red">*</font></td>
          <td class="t_value">&nbsp;
            X : <input name="posi_x" type="text" value="<?=$popup_info->posi_x?>" size="6" class="input"> &nbsp;
            Y : <input name="posi_y" type="text" value="<?=$popup_info->posi_y?>" size="6" class="input">
			<div style="margin-top:5px; color:#ff0000;">※ PC에서 적용할 때만 사용가능(모바일에서는 적용불가) </div>
          </td>
          <td class="t_name">크기</td>
          <td class="t_value">&nbsp;
            가로 : <input name="size_x" type="text" value="<?=$popup_info->size_x?>" size="6" class="input"> &nbsp;
            세로 : <input name="size_y" type="text" value="<?=$popup_info->size_y?>" size="6" class="input">
			<div style="margin-top:5px; color:#ff0000;">※ PC에서 적용할 때만 사용가능(모바일에서는 적용불가) </div>
          </td>
        </tr>
        <tr>
          <td class="t_name">링크주소</td>
          <td class="t_value" colspan="3"><input type="text" name="linkurl" value="<?=$popup_info->linkurl?>" size="80" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">팝업내용</td>
          <td class="t_value" colspan="3" height="300">

          	<?
	          $edit_content = $popup_info->content;
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
			<input type="button" value="목록" class="base_btn gray" onclick="document.location='popup_list.php?page=<?=$page?>&menucode=<?=$menucode?>';">          </td>
        </tr>
      </table>
	  </form>

	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			  - 팝업형태에서 레이어팝업을 이용하여 팝업창이 아닌 레이어로 공지할 수 있습니다.<br>
			  - 팝업을 생성하였으나 뜨지않을 경우 세가지를 체크해보세요. 게시기간, 사용여부, 브라우저>도구>인터넷옵션>쿠키삭제
		  </div>
		</div>
	  </div>

<? include "../foot.php"; ?>