<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include "../head.php";

if(empty($mode)) $mode = "insert";

if($mode == "update"){
	$sql = "select * from wiz_brand where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$brd_info = sql_fetch_arr($result);
}
?>
<script type="text/javascript">
function showBrdsub(gubun){

	brd_sub.style.display = 'none';
	brd_sub2.style.display = 'none';

	if(gubun == "NON") brd_sub.style.display = 'none';
	else if(gubun == "FIL") brd_sub.style.display = '';
	else if(gubun == "HTM") brd_sub2.style.display = '';

}

$(function() {
	$("#categoryShow").load("brand_list.php");
});

</script>
<div id="detailcategoryList">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td><img src="../image/ic_tit.gif"></td>
			<td valign="bottom" class="tit">브랜드관리</td>
			<td width="2"></td>
			<td valign="bottom" class="tit_alt">브랜드를 설정합니다.</td>
		</tr>
	</table>

	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="40%" valign="top">
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td valign="top">
							<table width="100%" height="400" border="0" cellspacing="2" cellpadding="6" bgcolor="E1E1E1">
								<tr>
									<td valign="top" bgcolor="#ffffff">
									<div id="categoryList">
										<div id="subCateList">
											<div id="categoryShow"></div>
										</div>
									</div>
									</td>
								</tr>
							</table>
						</td>
						<td width="10"></td>


					</tr>
				</table>
			</td>
			<td width="60%" height="400" valign="top">
				<div id="categoryContent">
					<div id="categoryInput">
					<?php include "./ajax_brd_category.php"; ?>
					</div>
				</div>
				<div class="clear"></div>
			</td>

		</tr>
	</table>
</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/foot.php"; ?>
