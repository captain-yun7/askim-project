<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$param = "code=$code";
?>
<html>
<head>
<title>:: 제품모델관리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script>
function inquiry_sort(idx){

	var sum_id = "priorno_"+idx;
	var result_sum_id = document.getElementById(sum_id).value;
	//alert(result_sum_id);
	location.href="./product_inquiry_sort.php?idx="+idx+"&priorno="+result_sum_id;
}
</script>
</head>
<body>

<br>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="20"><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">제품모델관리 : <?=$bbs_info['title']?></td>
    <td align="right"><img src="../image/btn_product_model2.gif" style="cursor:hand" onclick="document.location='product_inquiry_input.php?<?=$param?>'" class="sbtn"></td></td>
  </tr>
</table>

<?php
	$sql = "select * from wiz_product_inquiry";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	$rows = 200;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

?>
<table align="center" width="98%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="8%">번호</th>
		<th>모델명</th>
		<th width="10%">정렬</th>
		<th width="12%">기능</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
<?php
	$sql_sub = "select * from wiz_product_inquiry order by priorno desc limit $start, $rows";
	$result_sub = query($sql_sub) or error("sql error");

	while($row_sub = sql_fetch_arr($result_sub)){			
?>
	<tr>
		<td align="center" height="30"><?=$no?></td>
		<td align="center"><?=$row_sub['prdname']?></td>
		<td align="center" width="15%">
			<input id="priorno_<?=$row_sub['idx']?>" type="text" value="<?=$row_sub['priorno']?>" class="input" style="width:40px;"> &nbsp;			
			<img src="../image/btn_edit_s.gif" style="cursor:pointer" align="absmiddle" style="margin-top:10px" onclick="inquiry_sort('<?=$row_sub['idx']?>')">
		</td>
		<td align="center">
			<a href="product_inquiry_delete.php?idx=<?=$row_sub['idx']?>">
				<img src="../image/btn_delete_s.gif" style="cursor:pointer">
			</a>		  
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
  	$no--;
	}

	
?>
</table>

<br>
<table align="center" border="0" cellpadding=0 cellspacing=0>
	<tr>
		<td><? print_pagelist($page, $lists, $page_count, $param); ?></td>
	</tr>
</table>
<br><br>
</body>
</html>