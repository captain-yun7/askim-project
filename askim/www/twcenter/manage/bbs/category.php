<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$param = "code=$code";
?>
<html>
<head>
<title>:: 카테고리관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function catDelete(idx) {
	if(confirm("삭제하시겠습니까?")) {
		document.location = "bbs_save.php?mode=catdelete&<?=$param?>&idx=" + idx;
	}
}
//-->
</script>
</head>
<body>

<br>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="20"><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">카테고리관리 : <?=$bbs_info['title']?></td>
    <td align="right">
	<input type="button" value="분류등록" class="base_btm blue" onclick="document.location='category_input.php?mode=catinsert&<?=$param?>'"></td></td>
  </tr>
</table>

<?php
	$sql = "select * from wiz_bbscat where code='$code' order by gubun desc, prior asc, idx asc";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	$rows = 10;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

?>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="8%">번호</th>
		<th>분류명</th>
		<th>분류이미지</th>
		<th>롤오버이미지</th>
		<th>분류아이콘</th>
		<th>링크값</th>
		<th width="10%">우선순위</th>
		<th width="12%">기능</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
<?php
	$sql = "select * from wiz_bbscat where code='$code' order by gubun desc, prior asc, idx asc limit $start, $rows";
	$result = query($sql) or error("sql error");

	while($row = sql_fetch_arr($result)){
?>
	<tr>
		<td align="center" height="30"><?=$no?></td>
		<td align="center"><?=$row['catname']?></td>
		<td align="center">
			<?php if(!empty($row['catimg'])) { ?> <img src="/twcenter/data/category/<?=$code?>/<?=$row['catimg']?>"><?php } ?>
		</td>
		<td align="center">
			<?php if(!empty($row['catimg_over'])) { ?> <img src="/twcenter/data/category/<?=$code?>/<?=$row['catimg_over']?>"><?php } ?>
		</td>
		<td align="center">
			<?php if(!empty($row['caticon'])) { ?> <img src="/twcenter/data/category/<?=$code?>/<?=$row['caticon']?>"><?php } ?>
		</td>
		<td align="center">파일명?category=<?=$row['idx']?></td>
		<td align="center"><?=$row['prior']?></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="document.location='category_input.php?idx=<?=$row['idx']?>&mode=catupdate&<?=$param?>'" class="gbtn">
			<img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="catDelete('<?=$row['idx']?>')" class="gbtn">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
  	$no--;
	}

	if($total <= 0) {
?>
	<tr>
		<td height="30" class="t_value" align="center" colspan="7">등록된 분류가 없습니다.</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
	}
?>
</table>

<br>
<table width="100%" align="center" border="0" cellpadding=0 cellspacing=0>
	<tr>
		<td align="center" ><? print_pagelist($page, $lists, $page_count, $param); ?></td>
	</tr>
</table>

</body>
</html>