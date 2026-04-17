<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<html>
<head>
<title>:: 게시판그룹관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/comm/js/script.js"></script>
<script language="JavaScript">
<!--
function grpDelete(idx) {
	if(confirm("삭제하시겠습니까?")) {
		document.location = "bbs_save.php?mode=grpdelete&idx=" + idx;
	}
}
function set_prior() {
	let params = new Array();
	$("form[name*=frm]").each(function() {
		idx = $(this).find("input[name=idx]").val();
		prior = $(this).find("input[name=prior]").val();
		params.push([idx,prior]);
	});
	frm = $("<form></form>");
	frm.attr("method", "post");
	frm.attr("action", "./bbs_save.php");
	frm.append("<input type='hidden' name='mode' value='set_grp_prior'>");
	frm.append("<input type='hidden' name='json_data' value='"+JSON.stringify(params)+"'>");
	frm.appendTo("body");
	frm.submit();
    
}
//-->
</script>
</head>
<body>

<br>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 게시판그룹관리</td>
    <td align="right"><img src="../image/btn_bbscat.gif" style="cursor:hand" onclick="document.location='group_input.php?mode=grpinsert'" class="sbtn"></td></td>
  </tr>
</table>


<table align="center" width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="15%">번호</th>
		<th>분류명</th>
		<th width="80">우선순위</th>
		<th width="30%">기능</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
<?php
$grplist = get_grplist("bbs");
if(sizeof($grplist) > 0) {
	foreach($grplist as $k=>$grp) {
?>
	<tr>
		<td align="center" height="30"><?=$k+1?></td>
		<td align="center"><?=$grp['grpname']?></td>
		<td align="center">
	<form class="grp_frm" name="frm<?=$grp['idx']?>" method="post" action="./bbs_save.php">
	<input type="hidden" name="mode" value="grp_prior">
	<input type="hidden" name="idx" value="<?=$grp['idx']?>"><input type="text" class="input" name="prior" size="2" value="<?=$grp['prior']?>"> <input type="submit" value="변경" align="absmiddle" title="우선순위 변경" class="base_btm blue2">
	</form></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="document.location='group_input.php?idx=<?=$grp['idx']?>&mode=grpupdate'" class="gbtn">
			<img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="grpDelete('<?=$grp['idx']?>')" class="gbtn">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
	}
?>
	<tr><td colspan="3"></td><td height="50" align="center"><input type="button" value="순서일괄변경" class="btnListchk" onclick="set_prior()"></td></tr>
<?
} else {
?>
	<tr>
		<td height="30" class="t_value" align="center" colspan="7">등록된 그룹이 없습니다.</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
}
?>
</table>

</body>
</html>