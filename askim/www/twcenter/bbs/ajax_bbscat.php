<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php"; ?>
<?
	$sql_cat = "select idx, catname from wiz_bbscat where code='$code' and gubun!='A' order by prior, idx";
	$res_cat = query($sql_cat);
	$list = array();
	while($row_cat = sql_fetch_arr($res_cat)) {
		echo "<option value='".$row_cat['idx']."'>".$row_cat['catname']."</option>";
	}
?>