<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$param = "code=$code&title=$title&page=$page";

if(empty($mode)) $mode = "catinsert";

if(!strcmp($mode, "catupdate")) {
	$sql = "select * from wiz_bbscat where code = '$code' and idx = '$idx'";
	$result = query($sql) or error("sql error");
	$cat_info = sql_fetch_arr($result);
}
?>
<html>
<head>
<title>:: 카테고리관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){

	if(frm.catname.value == ""){
		alert("분류명을 입력하세요.");
		frm.catname.focus();
		return false;
	}
}
//-->
</script>
</head>
<body>
<table width="100%" border="0" cellpadding=10 cellspacing=0>
<tr>
<td>

<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">카테고리관리  : <?=$bbs_info['title']?></td>
  </tr>
</table>
<form name="frm" action="bbs_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="title" value="<?=$title?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td width="15%" class="t_name">분류</td>
    <td width="85%" class="t_value">
    	<span style="vertical-align: middle"><input type="checkbox" name="gubun" value="A" <? if(!strcmp($cat_info['gubun'], "A")) { ?> checked <? } ?>></span> 전체분류<br>
    	※전체분류는 게시판의 특정 분류값이 아닌 "전체" 게시물을 보여주는 값입니다.
    </td>
  </tr>
  <tr>
    <td class="t_name">우선순위</td>
    <td class="t_value">
    	<select name="prior">
    		<? for($ii = 1; $ii < 21; $ii++) { ?>
    		<option value="<?=$ii?>" <? if(!strcmp($ii, $cat_info['prior'])) echo "selected"; ?>><?=$ii?></option>
    		<? } ?>
    	</select> (작을수록 순위가 높음, "전체분류"는 우선순위에 상관없이 가장 순위가 높습니다.)<br>
    </td>
  </tr>
  <tr>
    <td class="t_name">분류명</td>
    <td class="t_value">
    	<input type="text" name="catname" value="<?=$cat_info['catname']?>" class="input">
    </td>
  </tr>
  <tr>
    <td class="t_name">분류이미지</td>
    <td class="t_value">
    	<input type="file" name="catimg" class="input">
<?
if(!empty($cat_info['catimg'])) {
?>
			<br> <img src="/twcenter/data/category/<?=$code?>/<?=$cat_info['catimg']?>">
			<input type="checkbox" name="delfile[]" value="catimg"> 삭제
<?
}
?>
    </td>
  </tr>
  <tr>
    <td class="t_name">롤오버이미지</td>
    <td class="t_value">
    	<input type="file" name="catimg_over" class="input">
<?
if(!empty($cat_info['catimg_over'])) {
?>
			<br> <img src="/twcenter/data/category/<?=$code?>/<?=$cat_info['catimg_over']?>">
			<input type="checkbox" name="delfile[]" value="catimg_over"> 삭제
<?
}
?>
    </td>
  </tr>
  <tr>
    <td class="t_name">분류아이콘</td>
    <td class="t_value">
    	<input type="file" name="caticon" class="input">
<?
if(!empty($cat_info['caticon'])) {
?>
			<br> <img src="/twcenter/data/category/<?=$code?>/<?=$cat_info['caticon']?>">
			<input type="checkbox" name="delfile[]" value="caticon"> 삭제
<?
}
?>
    </td>
  </tr>
<?
if(!strcmp($mode, "catupdate")) {
?>
  <tr>
    <td width="15%" class="t_name">링크값</td>
    <td class="t_value">
    	파일명?category=<?=$cat_info['idx']?>
    </td>
  </tr>
<?
}
?>
</table>
<br>
<table width="100%" border="0" cellpadding=0 cellspacing=0>
  <tr>
    <td align="center">
		<input type="submit" value="확인" class="base_btn reg">
		<input type="button" value="목록" class="base_btn gray" onClick="document.location='category.php?<?=$param?>'">
    </td>
  </tr>
</table>
</form>
</td>
</tr>
</table>
</body>
</html>