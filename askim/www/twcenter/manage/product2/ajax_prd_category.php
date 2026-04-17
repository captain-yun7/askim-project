<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

if($mode == "") $mode = "insert";
if($mode == "update"){
	$sql = "select * from wiz_category2 where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	$cat_info = sql_fetch_obj($result);
}
?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="arrow_padd">
						<a href="javascript:SmoveCategory('updateprior','up','<?=$catcode?>','<?=$depthno?>','<?=$menucode?>')"><img src="../image/cat_up.gif" border="0"></a><br><br><br>
						<a href="javascript:SmoveCategory('updateprior','down','<?=$catcode?>','<?=$depthno?>','<?=$menucode?>')"><img src="../image/cat_down.gif" border="0"></a>
					</td>
					<td width="10"></td>
					<td width="10"></td>
					<td valign="top">
						<form name="frm" id="frm" method="post" enctype="multipart/form-data">
						<input type="hidden" name="tmp">
						<input type="hidden" name="mode" value="<?=$mode?>">
						<input type="hidden" name="catcode" value="<?=$catcode?>">
						<input type="hidden" name="depthno" value="<?=$depthno?>">
						<input type="hidden" name="org_catuse" value="<?=$cat_info->catuse?>">
						<input type="hidden" name="menucode" value="<?=$menucode?>">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"  valign="top">
							<tr>
								<td bgcolor="D5D3D3">
									<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
										<tr>
											<td width="20%" class="t_name">위치</td>
											<td width="80%" class="t_value">
											<?php
											$catname = "최상위";
											if($catcode != ""){
		
												$catcode1 = substr($catcode,0,2);
												$catcode2 = substr($catcode,0,4);
												$catcode3 = substr($catcode,0,6);
												$catcode4 = substr($catcode,0,8);

												$sql = "
													SELECT *
													  FROM wiz_category2
													 WHERE (catcode like '$catcode1%' and depthno = 1)
														OR (catcode like '$catcode2%' and depthno = 2)
														OR (catcode like '$catcode3%' and depthno = 3)
														OR (catcode like '$catcode4%' and depthno = 4)
														OR (catcode = '$catcode')
													 ORDER BY depthno ASC
												";
												$result = query($sql) or error("sql error");
											
												while($prow = sql_fetch_obj($result)){
													//$catname .= " &gt; <a href=prd_category.php?mode=update&catcode=$prow->catcode>$prow->catname</a>";
													$catname .= " &gt; $prow->catname";
												}
											
											}
											echo $catname;
											?>
											</td>
										</tr>
										<? if($catcode != "") { ?>
										<tr>
											<td class="t_name">분류코드</td>
											<td class="t_value"><?=$catcode?></td>
										</tr>
										<? } ?>
										<tr>
											<td class="t_name">분류명</td>
											<td class="t_value">
												<input name="catname" id="catname" value="<?=$cat_info->catname?>" size="30" type="text" class="input">&nbsp;
												<span style="vertical-align: middle"><input type="checkbox" name="catuse" value="N" <? if($cat_info->catuse == "N") echo "checked"; ?>></span>분류숨김

											</td>
										</tr>

										<tr>
											<td class="t_name">분류이미지</td>
											<td class="t_value">
											<?
											if(is_file(WIZHOME_PATH."/data/product/".$cat_info->catimg)) echo "<span class='img_border'><img src='/twcenter/data/product/$cat_info->catimg' width='54' height='54'></span><br>";
											?>
												<div class="filebox preview-image">
												<input class="input upload-name" value="파일선택" disabled="disabled">
												<label for="input-file2">파일 업로드</label>
												<input type="file" name="catimg" id="input-file2" class="upload-hidden">
												</div>
												<span style="vertical-align: middle"><input type="checkbox" name="delimg" value="<?=$cat_info->catimg?>"></span>이미지삭제
											</td>
										</tr>

										<tr>
											<td class="t_name">상품진열수</td>
											<td class="t_value">
												<input type="text" name="prd_num" value="<? if($cat_info->prd_num=="") echo "20"; else echo $cat_info->prd_num; ?>" size="5" class="input"> 개&nbsp;
											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">연결페이지</td>
											<td class="t_value">
												http://<?=$HTTP_HOST?>/<input type="text" name="purl" value="<?=$cat_info->purl?>" class="input" size="35">&nbsp;
											</td>
										</tr>

										<tr>
											<td class="t_name">스킨</td>
											<td class="t_value">
												<select name="prd_skin" class="select">
													<?
													$dh = opendir("../../product2/skin");
													while(($file = readdir($dh)) !== false){
													if($file != "." && $file != ".."){
														$file_list[] = $file;
													}
													}
													sort ($file_list); reset ($file_list);
													for($ii=0;$ii<count($file_list);$ii++){
													?>
													<option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
													<?
													}
													?>
												</select>
												<script language="javascript">
												<!--
												skin = document.frm.prd_skin;
												for(ii=0; ii<skin.length; ii++){
													if(skin.options[ii].value == "<?=$cat_info->prd_skin?>")
													skin.options[ii].selected = true;
												}
												-->
												</script>
											</td>
										</tr>
										<tr>
											<td align="left" class="t_name">브라우저타이틀</td>
											<td class="t_value padd">
												<input type="text" name="browser_title" value="<?=$cat_info->browser_title?>" size="80" class="input">
											</td>
										</tr>

										<tr>
											<td align="left" class="t_name">메타네임(Description)</td>
											<td class="t_value padd">
												<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?php echo $cat_info->searchkey_de ?></textarea>
												<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
												<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
											</td>
										</tr>
										<tr>
											<td align="left" class="t_name">메타네임(Classification)</td>
											<td class="t_value padd">
												<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?php echo $cat_info->searchkey_cl ?></textarea>
												<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
											</td>
										</tr>

										<tr>
											<td align="left" class="t_name">메타네임(keywords)</td>
											<td class="t_value padd">
												<textarea name="searchkey" rows="3" cols="120" class="textarea"><?php echo $cat_info->searchkey ?></textarea>
												<div class="sub_tit_alt_red">※ 해당 항목은 네이버 및 구글에선 참고용으로만 활용합니다.</div>
												<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 사이트의 접근성을 향상에 활용한다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며 일부 반영이 안될 수 있습니다.</div>
												<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
												<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table width="10" height="10" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td></td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
							<?
							if($mode == "insert"){
							?>
							<tr>
								<td align="center">
									<img src="../image/btn_insert_l.gif" id="modify_btn"  style="cursor:pointer">
								</td>
							</tr>
							<?
							}else if($mode == "update"){
							?>
							<tr>
								<td width="33%">
									<?
									if($depthno != 4){
									?>
									<input type="button" value="하위분류등록" class="base_btn blue" onClick="document.location='prd_cat.php?mode=insert&catcode=<?=$catcode?>&depthno=<?=$depthno?>&<?=$menucodeParam?>';">
									<?
									}
									?>
								</td>
								<td width="66%">
									<input type="button" value="수 정" id="modify_btn" class="base_btn reg">&nbsp;
									<input type="button" value="삭 제" id="delete_btn" class="base_btn gray">
								</td>
							</tr>
							<?
							}
							?>
						</table>
						</form>
						<br>
						<div class="helpTip">
							<h4>체크사항</h4>
							<div class="content">
								<div class="explain">
									- 카테고리 정보 수정시 카테고리 클릭후 오른쪽에서 변경합니다.<br>
									- 카테고리 순서 변경시 클릭후 위아래 화살표를 이용합니다.<br>
									- 상품 가로, 세로 사이즈를 입력하면 임의로 사이즈 변경이 가능합니다.&nbsp;&nbsp;임의 변경시 이미지가 깨질 수 있습니다.
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
<script language="JavaScript" type="text/javascript">
<!--
$(function(){

	$("#modify_btn").click(function(){
		
		var catname = $("#catname").val();
		if(catname == ""){
			alert("분류명을 입력하세요.");
			$("#catname").focus();
			return false;
		}

		$('#frm').ajaxForm({
			type:"post"
			, url:  "cat_save.php"
			, data : {mode:"<?=$mode?>"}
			, cache: false
			, processData: false
			, contentType: false
			, success: function(data) {
				//console.log(data);
				//return;
				var result = data.split('|');
				if(result[0] == "ok"){
					if(result[1] == "update"){
						alert("분류정보를 수정하였습니다.");
					} else if(result[1] == "insert") {
						alert("상품분류를 추가하였습니다.");
					}
				}
				var href = "prd_cat.php?mode="+result[1]+"&catcode="+result[3]+"&depthno="+result[2]+"&<?=$menucodeParam?>";
				//$("#detailcategoryList").load(href);
				document.location.href = href;
			}
			, error: function(){
			}

		});
		$('#frm').submit();
	});

	$("#delete_btn").click(function(){
		$.ajax({
			type:"post"
			, async: false
			, url:  "cat_save.php"
			, data : {"mode":"delete","catcode":"<?=$catcode?>","depthno":"<?=$depthno?>"}
			, success: function(data) {
				var result = data.split("|");
				var href = "prd_cat.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3]+"&<?=$menucodeParam?>";

				if(result[0] == "ok"){
					alert("선택하신 분류를 삭제하였습니다.");
					document.location.href = href;
					//$("#detailcategoryList").load(href);
				} else if(result[0] == "1"){
					alert("하위분류가 존재합니다. 삭제하실 수 없습니다.");
					document.location.href = href;
					//$("#detailcategoryList").load(href);
				} else if(result[0] == "2"){
					alert("현재분류에 상품이 존재합니다. 삭제하실 수 없습니다.");
					document.location.href = href;
					//$("#detailcategoryList").load(href);
				}
			}
			, error: function(){
			}
		});
		return false;

	});

	var depthno = $("[name=depthno]").val();
	if(depthno >= 1) {

		var imgTarget = $('.preview-image .upload-hidden');

		imgTarget.on('change', function(){
			var parent = $(this).parent();
			parent.children('.upload-display').remove();

			if(window.FileReader){
				if (!$(this)[0].files[0].type.match(/image\//)) return;

				var reader = new FileReader();
				reader.onload = function(e){
					var src = e.target.result;
					parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img src="'+src+'" class="upload-thumb"></div></div>');
				}
				reader.readAsDataURL($(this)[0].files[0]);

				var filename = $(this)[0].files[0].name;
			}
			else {
				$(this)[0].select();
				$(this)[0].blur();
				var imgSrc = document.selection.createRange().text;
				parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img class="upload-thumb"></div></div>');

				var img = $(this).siblings('.upload-display').find('img');
				img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";
			
				var filename = $(this).val().split('/').pop().split('\\').pop();
			}

			$(this).siblings('.upload-name').val(filename);

		});

	}

});

</script>