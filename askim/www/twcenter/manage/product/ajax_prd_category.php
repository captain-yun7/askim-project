<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?php
	if($mode == "") $mode = "insert";
	if($mode == "update"){
		$sql = "select * from wiz_category where catcode = '$catcode'";
		$result = query($sql) or error("sql error");
		$cat_info = sql_fetch_obj($result);
	}
?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="arrow_padd">
						<a href="javascript:;" onclick="moveCategory('updateprior','up','<?=$catcode?>','<?=$depthno?>','<?=$menucode?>')"><img src="../image/cat_up.gif" border="0"></a><br><br><br>
						<a href="javascript:;" onclick="moveCategory('updateprior','down','<?=$catcode?>','<?=$depthno?>','<?=$menucode?>')"><img src="../image/cat_down.gif" border="0"></a>
					</td>
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
											<?
											$catname = "최상위";
											if($catcode != ""){
		
												$catcode1 = substr($catcode,0,2);
												$catcode2 = substr($catcode,0,4);
												$catcode3 = substr($catcode,0,6);
												$catcode4 = substr($catcode,0,8);

												$sql = "
													SELECT *
													  FROM wiz_category
													 WHERE (catcode like '$catcode1%' and depthno = 1)
														OR (catcode like '$catcode2%' and depthno = 2)
														OR (catcode like '$catcode3%' and depthno = 3)
														OR (catcode like '$catcode4%' and depthno = 4)
														OR (catcode = '$catcode')
													 ORDER BY depthno ASC
												";
												$result = query($sql) or error("sql error");
											
												while($prow = sql_fetch_obj($result)){
													$catname .= " &gt; <a href=prd_category.php?mode=update&catcode=$prow->catcode>$prow->catname</a>";
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
										<!-- <tr>
											<td class="t_name">노출여부</td>
											<td class="t_value">
												<span style="vertical-align: middle"><input type="checkbox" name="mainView" value="Y" <? if($cat_info->mainView =='Y') echo " checked";?>></span>메인 > Featured Categories 노출
											</td>
										</tr> -->
										<tr>
											<td class="t_name" style="line-height:140%;">메뉴이미지</td>
											<td class="t_value">
											<?
											if(is_file("../../data/catimg/$cat_info->catimg")) echo "<span class='img_border'><img src='/twcenter/data/catimg/$cat_info->catimg' width='54' align='absmiddle'></span> <a href=category_save.php?mode=delcatimg&catcode=$catcode&depthno=$depthno&$menucodeParam><font color=red>[삭제]</font></a><br>";
											?>
												<div class="filebox preview-image">
													<input class="input upload-name" value="파일선택" disabled="disabled">
													<label for="input-file">파일 업로드</label>
													<input type="file" name="catimg" id="input-file" class="upload-hidden">
												</div>
											</td>
										</tr>
										<tr>
											<td class="t_name">롤오버이미지</td>
											<td class="t_value">
											<?
											if(is_file("../../data/catimg/$cat_info->catimg_over")) echo "<span class='img_border'><img src='/twcenter/data/catimg/$cat_info->catimg_over' width='54' align='absmiddle'></span> <a href=category_save.php?mode=delcatimg_over&catcode=$catcode&depthno=$depthno&$menucodeParam><font color=red>[삭제]</font></a><br>";
											?>
												<div class="filebox preview-image">
												<input class="input upload-name" value="파일선택" disabled="disabled">
												<label for="input-file2">파일 업로드</label>
												<input type="file" name="catimg_over" id="input-file2" class="upload-hidden">
												</div>

											</td>
										</tr>
										<tr>
											<td class="t_name">서브상단</td>
											<td class="t_value">
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showCatsub('NON');" value="NON" <? if($cat_info->subimg_type == "NON" || $cat_info->subimg_type == "") echo "checked"; ?>></span>없음
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showCatsub('FIL');" value="FIL" <? if($cat_info->subimg_type == "FIL") echo "checked"; ?>></span>이미지
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showCatsub('HTM');" value="HTM" <? if($cat_info->subimg_type == "HTM") echo "checked"; ?>></span>HTML
	
												<div id='cat_sub' style="display:<? if($cat_info->subimg_type == "FIL") echo "show"; else echo "none"; ?>">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="t_value">
															<?
															if(is_file("../../data/subimg/$cat_info->subimg")){
																$img_ext = substr($cat_info->subimg,-3);
																echo "<img src='/twcenter/data/subimg/$cat_info->subimg' width='290' height='50'> <a href=category_save.php?mode=delsubimg&catcode=$catcode&depthno=$depthno&$menucodeParam><font color=red>[삭제]</font></a>";
															}
															?>
															<div class="filebox preview-image">
															<input class="input upload-name" value="파일선택" disabled="disabled">
															<label for="input-file3">파일 업로드</label>
															<input type="file" name="subimg" id="input-file3" class="upload-hidden">
															</div>

														</td>
													</tr>
												</table>
												</div>
	
												<div id='cat_sub2' style="display:<? if($cat_info->subimg_type == "HTM") echo "show"; else echo "none"; ?>">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="t_value">
															<textarea name="subimg02" cols="45" rows="5" class="textarea"><?=$cat_info->subimg?></textarea>
														</td>
													</tr>
												</table>
												</div>
	
											</td>
										</tr>
										<!-- <tr>
											<td class="t_name">상품크기</td>
											<td class="t_value">
												가로 <input type="text" name="prd_width" value="<?=$cat_info->prd_width?>" size="5" class="input"> px&nbsp;
												세로 <input type="text" name="prd_height" value="<?=$cat_info->prd_height?>" size="5" class="input"> px
												<div class="msg_txt_t">
													※ 상품리스트에 해당 크기로 제품이미지가 표시됩니다.
												</div>
											</td>
										</tr> -->
										<tr>
											<td class="t_name">상품진열수</td>
											<td class="t_value">
												<input type="text" name="prd_num" value="<? if($cat_info->prd_num=="") echo "20"; else echo $cat_info->prd_num; ?>" size="5" class="input"> 개&nbsp;
											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">연결페이지</td>
											<td class="t_value">
												<?=SSL.$HTTP_HOST?>/<input type="text" name="purl" id="purl" value="<?=$cat_info->purl?>" class="input" size="35">&nbsp;
											</td>
										</tr>
										<tr>
											<td class="t_name">추천상품 진열</td>
											<td class="t_value">
												<span style="vertical-align: middle"><input type="radio" name="recom_use" value="Y" <? if($cat_info->recom_use == "Y") echo "checked";?>></span>사용
												<span style="vertical-align: middle"><input type="radio" name="recom_use" value="N" <? if($cat_info->recom_use == "N" || $cat_info->recom_use == "" ) echo "checked";?>></span>사용안함<br>
												상품목록 페이지 상단에 추천상품 진열
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
									<!-- <img src="../image/btn_insert_l.gif" id="modify_btn"  style="cursor:pointer"> -->
									<input type="button" value="등 록" id="modify_btn" class="base_btn reg">
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
									<input type="button" value="하위분류등록" class="base_btn blue" onClick="document.location='prd_category.php?mode=insert&catcode=<?=$catcode?>&depthno=<?=$depthno?>&<?=$menucodeParam?>';">
									<?
									}
									?>
								</td>
								<td width="66%">
									<input type="button" value="수 정" id="modify_btn" class="base_btn reg">&nbsp;
									<input type="button" value="삭 제" id="delete_btn" class="base_btn gray">
								</td>
								<!-- <td width="33%" align="right">
									<input type="button" value="카테고리 엑셀업로드" class="btnExcel" onclick="excelUp();">
								</td> -->
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
		
		var catname    = $.trim($("#catname").val());
		var cnpclassid = $.trim($("#cnpclassid").val());
		var brandcode  = $.trim($("#brandcode").val());
		var purl       = $.trim($("#purl").val());

		if(catname == ""){
			alert("분류명을 입력하세요.");
			$("#catname").focus();
			return false;
		}

		/*
		작업자명	: 이상민
		작업일시	: 2020-03-18
		작업내용	: 내부프로젝트게시판 3/4요청에 의거 상품분류 등록시 연결페이지항목 필수처리
		*/
		if(purl == ""){
			alert("연결페이지를 입력하세요");
			$("#purl").focus();
			return false;
		}

		$('#frm').ajaxForm({
			type:"post"
			, url:  "category_save.php"
			, data : {mode:"<?=$mode?>"}
			, cache: false
			, processData: false
			, contentType: false
			, success: function(result) {
			//console.log(result);
			//return false;
				var result = result.split('|');
				if(result[0] == "ok"){
					if(result[1] == "update"){
						alert("분류정보를 수정하였습니다.");
					} else if(result[1] == "insert") {
						alert("상품분류를 추가하였습니다.");
					}
				}
				var href = "prd_category.php?mode="+result[1]+"&catcode="+result[3]+"&depthno="+result[2]+"&<?=$menucodeParam?>";
				//$("#detailcategoryList").load(href);
				document.location.href = href;
				
			}
			, error: function(){
			}

		});
		$('#frm').submit();
	});

	$("#delete_btn").click(function(){

		if(confirm("삭제후에는 복구될 수 없습니다.\n정말 삭제하시겠습니까?")) {

			$.ajax({
				type:"post"
				, async: false
				, url:  "category_save.php"
				, data : {"mode":"delete","catcode":"<?=$catcode?>","depthno":"<?=$depthno?>", "priorno" : "<?=$cat_info->{'priorno0'.$depthno}?>"}
				, success: function(data) {
					var result = data.split("|");
					var href = "prd_category.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3]+"&<?=$menucodeParam?>";
					
					if(result[0] == "ok"){
						alert("선택하신 분류를 삭제하였습니다.");
						//$("#detailcategoryList").load(href);
						document.location.href = href;
					} else if(result[0] == "1"){
						alert("하위분류가 존재합니다. 삭제하실 수 없습니다.");
						//$("#detailcategoryList").load(href);
						document.location.href = href;
					} else if(result[0] == "2"){
						alert("현재분류에 상품이 존재합니다. 삭제하실 수 없습니다.");
						//$("#detailcategoryList").load(href);
						document.location.href = href;
					}
				}
				, error: function(){
				}
			});
		}
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

function excelUp() {

	var w = 560;
	var h = 520;
	var window_left = (screen.availWidth - w) / 2;
	var window_top  = (screen.availHeight - h) / 2;
	var url = "./prd_category_excel_up.php";
	window.open(url, "popup_window", "width=" + w +", height=" + h + ", scrollbars=yes,top=" + window_top + ",left=" + window_left);
}

</script>