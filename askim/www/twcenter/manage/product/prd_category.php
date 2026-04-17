<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include "../head.php";

if($mode == "") $mode = "insert";
if($catcode == "") $catcode = "00000000";

if($mode == "update"){
	$sql = "select * from wiz_category where catcode = '$catcode'";
	$result = query($sql);
	$cat_info = sql_fetch_obj($result);
}
?>
<script language="JavaScript" type="text/javascript">
<!--
function showCatsub(gubun){

	cat_sub.style.display = 'none';
	cat_sub2.style.display = 'none';

	if(gubun == "NON") cat_sub.style.display = 'none';
	else if(gubun == "FIL") cat_sub.style.display = '';
	else if(gubun == "HTM") cat_sub2.style.display = '';

}

function set_common_seo(){
	var _params = "";
		_params += "mode=set_common_seo";
		_params += "&browser_title="+$("#browser_title").val();
		_params += "&searchkey_de="+$("#searchkey_de").val();
		_params += "&searchkey_cl="+$("#searchkey_cl").val();
		_params += "&searchkey="+$("#searchkey").val();

	$.ajax({
		type: 'POST'
		,url: 'prd_save.php'
		,cache: false
		,data: _params
		,dataType: 'json'
		,beforeSend:function(){
//			console.log(_params);
//			return false;
		}
	}).done(function(data){
//		console.log(data);
//		return false;
		alert(data.msg);
		switch(data.result){
			case "000":
				location.reload();
				break;
			default:
		}
	});
}

$(function() {
	$("#categoryShow").load("category_list.php?catcode=<?php echo $catcode; ?>");

	$(document).on("click", ".a_cat_item", function(){
		$("#tr_seo").hide();
	});

	$(document).on("click", "#seo_modify_btn", function(){
		set_common_seo();
	});
});
-->
</script>
<div id="detailcategoryList">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td><img src="../image/ic_tit.gif"></td>
			<td valign="bottom" class="tit">상품분류관리</td>
			<td width="2"></td>
			<td valign="bottom" class="tit_alt">상품분류를 설정합니다.</td>
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
					<?php include "./ajax_prd_category.php"; ?>
					</div>
				</div>
				<div class="clear"></div>
			</td>

		</tr>
		<tr id="tr_seo" style="<?php if($catcode != "00000000") echo "display:none;"; ?>">
			<td colspan="2">
				<table border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td><img src="../image/ic_tit.gif"></td>
						<td valign="bottom" class="tit">상품 공통 SEO관리</td>
						<td width="2"></td>
						<td valign="bottom" class="tit_alt">상품페이지의 공통SEO를 설정합니다.</td>
					</tr>
				</table>
				<br>
				<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
					<tr>
						<td width="15%" align="left" class="t_name">브라우저타이틀</td>
						<td class="t_value padd" colspan="3">
							<input type="text" id="browser_title" value="<?php echo $site_info['prd_browser_title']; ?>" size="80" class="input">
						</td>
					</tr>
					<tr>
						<td width="15%" align="left" class="t_name">메타네임(Description)</td>
						<td class="t_value padd" colspan="3">
							<textarea id="searchkey_de" rows="3" cols="120" class="textarea"><?php echo $site_info['prd_searchkey_de']; ?></textarea>
							<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
							<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
						</td>
					</tr>
					<tr>
						<td width="15%" align="left" class="t_name">메타네임(Classification)</td>
						<td class="t_value padd" colspan="3">
							<textarea id="searchkey_cl" rows="3" cols="120" class="textarea"><?php echo $site_info['prd_searchkey_cl']; ?></textarea>
							<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
						</td>
					</tr>
					<tr>
						<td width="15%" align="left" class="t_name">메타네임(keywords)</td>
						<td class="t_value padd" colspan="3">
							<textarea id="searchkey" rows="3" cols="120" class="textarea"><?php echo $site_info['prd_searchkey']; ?></textarea>
							<div class="sub_tit_alt_red">※ 해당 항목은 네이버 및 구글에선 참고용으로만 활용합니다.</div>
							<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 사이트의 접근성을 향상에 활용한다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며 일부 반영이 안될 수 있습니다.</div>
							<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
							<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
						</td>
					</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="2" style="width:100%;">
					<tr>
						<td style="text-align:center;">
							<input type="button" value="수 정" id="seo_modify_btn" class="base_btn reg">
						</td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
	</table>
</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/foot.php"; ?>
