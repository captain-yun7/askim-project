<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
	if(empty($mode)) $mode = "grpinsert";

	if(!strcmp($mode, "grpupdate") && $idx) {
		$grp = sql_fetch("select * from wiz_banner_grp where idx='".$idx."'");
		$grpname = $grp['grpname'];
		$prior = $grp['prior'];
	} else {
		$row_prior = sql_fetch("select max(prior) as prior from wiz_banner_grp");
		if($row_prior['prior']) $prior = $row_prior['prior']+1;
		else $prior = 1;
	}

?>
<html>
<head>
<title>:: 디자인그룹관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
function inputCheck(frm){

	if(frm.grpname.value == ""){
		alert("그룹명을 입력하세요.");
		frm.grpname.focus();
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
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 디자인그룹관리</td>
  </tr>
</table>
<form name="frm" action="./banner_save.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td width="50" class="t_name">그룹명</td>
    <td class="t_value">
    	<input type="text" name="grpname" value="<?=$grpname?>" class="input">
    </td>
  </tr>
  <tr>
    <td class="t_name">우선순위</td>
    <td class="t_value">
    	<input type="text" name="prior" value="<?=$prior?>" class="input Onum" size='2' maxlength='2'> <font color="red">* 숫자만 입력 (1~99), 동일 우선순위 있을 시 등록순 정렬</font>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding=0 cellspacing=0>
  <tr>
    <td align="center">
      <input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
      <img src="../image/btn_list_l.gif" style="cursor:hand" onClick="document.location='group.php'">
    </td>
  </tr>
</table>
</form>
</td>
</tr>
</table>
</body>
</html>
