<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

if(empty($mode)) $mode = "insert";

if($mode == "update"){
	$sql = "select * from wiz_brand where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$brd_info = sql_fetch_arr($result);
}

?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="arrow_padd">
						<a href="javascript:;" onclick="BrandmoveCategory('updateprior','up','<?=$idx?>','<?=$menucode?>')"><img src="../image/cat_up.gif" border="0"></a><br><br><br>
						<a href="javascript:;" onclick="BrandmoveCategory('updateprior','down','<?=$idx?>','<?=$menucode?>')"><img src="../image/cat_down.gif" border="0"></a>
					</td>
					<td>
					<?
					if($mode == "") $mode = "insert";
					?>
						<form name="frm" id="frm" method="post" enctype="multipart/form-data">
						<input type="hidden" name="tmp">
						<input type="hidden" name="mode" value="<?=$mode?>">
						<input type="hidden" name="idx" value="<?=$idx?>">
						<input type="hidden" name="mencode" value="<?=$menucode?>">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"  valign="top">
							<tr>
								<td bgcolor="D5D3D3">
									<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;위치</td>
											<td width="300" class="t_value">
											<?
											$brdname = "최상위";
											if($idx != ""){
												$sql = "select * from wiz_brand where idx = '$idx'";
												$result = query($sql) or error("sql error");

												while($prow = sql_fetch_obj($result)){
													$catname .= " &gt; <a href=prd_brand.php?mode=update&idx=$prow->idx&$menucodeParam>$prow->brdname</a>";
												}
											}
											echo $brdname;
											?>
											</td>
										</tr>
										<?
										if($idx != ""){
										?>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;링크주소</td>
											<td width="300" class="t_value">?brand=<?=$idx?></td>
										</tr>
										<?
										}
										?>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;브랜드명</td>
											<td width="300" class="t_value">
												<input name="brdname" id="brdname" value="<?=$brd_info['brdname']?>" size="30" type="text" class="input">&nbsp;
												<span style="vertical-align: middle"><input type="checkbox" name="brduse" value="N" <? if($brd_info['brduse'] == "N") echo "checked"; ?>></span>브랜드숨김
											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;서브상단</td>
											<td width="300" class="t_value">
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showBrdsub('NON');" value="NON" <? if($brd_info['subimg_type'] == "NON" || $brd_info['subimg_type'] == "") echo "checked"; ?>></span>없음
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showBrdsub('FIL');" value="FIL" <? if($brd_info['subimg_type'] == "FIL") echo "checked"; ?>></span>이미지
												<span style="vertical-align: middle"><input type="radio" name="subimg_type" onClick="showBrdsub('HTM');" value="HTM" <? if($brd_info['subimg_type'] == "HTM") echo "checked"; ?>></span>HTML

												<div id='brd_sub' style="display:<? if($brd_info['subimg_type'] == "FIL") echo "show"; else echo "none"; ?>">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="300" class="t_value">
														<?
														if(is_file("../../data/subimg/".$brd_info['subimg'])){
															$img_ext = getFileExt($brd_info['subimg']);
															echo "<img src='/twcenter/data/subimg/".$brd_info['subimg']."' width='290' height='50'> <a href=brand_save.php?mode=delsubimg&idx=$idx&$menucodeParam><font color=red>[삭제]</font></a>";
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

												<div id='brd_sub2' style="display:<? if($brd_info['subimg_type'] == "HTM") echo "show"; else echo "none"; ?>">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="300" class="t_value">
															<textarea name="subimg02" cols="45" rows="5" class="textarea"><?=$brd_info['subimg']?></textarea>
														</td>
													</tr>
												</table>
												</div>

											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;상품크기</td>
											<td width="300" class="t_value">
												가로 <input type="text" name="prd_width" value="<?=$brd_info['prd_width']?>" size="5" class="input"> px&nbsp;
												세로 <input type="text" name="prd_height" value="<?=$brd_info['prd_height']?>" size="5" class="input"> px
											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;상품진열수</td>
											<td width="300" class="t_value">
												<input type="text" name="prd_num" value="<? if($brd_info['prd_num']=="") echo "20"; else echo $brd_info['prd_num']; ?>" size="5" class="input"> 개&nbsp;
											</td>
										</tr>
										<tr>
											<td width="100" height="25" align="left" class="t_name">&nbsp;&nbsp;추천상품 진열</td>
											<td width="300" class="t_value">
												<span style="vertical-align: middle"><input type="radio" name="recom_use" value="Y" <? if($brd_info['recom_use'] == "Y") echo "checked";?>></span>사용
												<span style="vertical-align: middle"><input type="radio" name="recom_use" value="N" <? if($brd_info['recom_use'] == "N" || $brd_info['recom_use'] == "" ) echo "checked";?>></span>사용안함<br>
												상품목록 페이지 상단에 추천상품 진열
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
								<td align="center"><input type="button" value="등 록" id="modify_btn" class="base_btn reg"></td>
							</tr>
						<?
						}else if($mode == "update"){
						?>
							<tr>
								<td  align="center">
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
							- 브랜드 정보 수정시 브랜드 클릭후 오른쪽에서 변경합니다.<br>
							- 브랜드 순서 변경시 클릭후 위아래 화살표를 이용합니다.<br>
							- 상품 가로, 세로 사이즈를 입력하면 임의로 사이즈 변경이 가능합니다. &nbsp;&nbsp;임의 변경시 이미지가 깨질 수 있습니다.
							</div>
							</div>
						</div>



					</td>
				</tr>
			</table>
			</div>
			<div class="clear"></div>
		</td>

	</tr>
</table>
<script language="JavaScript" type="text/javascript">
<!--
$(function(){

	$("#modify_btn").click(function(){
		
		var brdname = $("#brdname").val();
		if(brdname == ""){
			alert("분류명을 입력하세요.");
			$("#brdname").focus();
			return false;
		}

		$('#frm').ajaxForm({
			type:"post"
			, url:  "brand_save.php"
			, data : {mode:"<?=$mode?>"}
			, cache: false
			, processData: false
			, contentType: false
			, success: function(result) {
				var result = result.split('|');
				if(result[0] == "ok"){
					if(result[1] == "update"){
						alert("브랜드정보를 수정하였습니다.");
						var href = "prd_brand.php?mode="+result[1]+"&idx="+result[2]+"&<?=$menucodeParam?>";
					} else if(result[1] == "insert") {
						alert("브랜드를 추가하였습니다.");
						var href = "prd_brand.php?"+"<?=$menucodeParam?>";
					}
				}
				if(href) {
					//$("#detailcategoryList").load(href);
					document.location = href;
				}
			}
			, error: function(){
			}

		});
		$('#frm').submit();
	});

	$("#delete_btn").click(function(){

		var conf = confirm('브랜드를 삭제 하시겠습니까?');
		if(conf){
			$.ajax({
				type:"post"
				, async: false
				, url:  "brand_save.php"
				, data : {"mode":"delete","idx":"<?=$idx?>"}
				, success: function(data) {
					var result = data.split("|");
					var href = "prd_brand.php?mode="+result[1]+"&idx="+result[2]+"&<?=$menucodeParam?>";

					if(result[0] == "ok"){
						alert("선택하신 브랜드를 삭제하였습니다.");
						//$("#detailcategoryList").load("prd_brand.php?<?=$menucodeParam?>");
						document.location = "prd_brand.php?<?=$menucodeParam?>";
					} else if(result[0] == "1"){
						alert("현재 브랜드에 상품이 존재합니다. 삭제하실 수 없습니다.");
						//$("#detailcategoryList").load(href);
						document.location = href;
					}

				}
				, error: function(){
				}
			});
			return false;
		}

	});

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

});

</script>