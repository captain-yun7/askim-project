<script>
	$(function() {
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	function menuSave(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}
		obj = $("form[name='frm']").serializeObject();
		frm.submit();	
	}

	function addMenu() {
		var first = (Number($(":input[name='menu["+  site_language +"]']:last").val()) || 0) + 1
		var str = '';
		str += '	<tr id="menu_'+ site_language +'_'+ first +'" class="menu_dep1" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'\')">';
		str += '	 	<td align="center">'+ first +'</th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][name]" style="width:90%;" /></th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][url]" style="width:90%;" /></th>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][sort]" value="'+ first +'" style="width:90%;" /></th>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][use]" style="width:90%;">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']" value="'+ first +'" />';
		str += '	 	</th>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="addSecondMenu(\''+ first +'\');">하위메뉴</a>';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'\')">삭제</a>';
		str += '	 	</th>';
		str += '	 </tr>';
		
		$(str).appendTo("#divList");
	} 

	function addSecondMenu(first) {
		var second = (Number($(":input[name='menu["+ site_language +"]["+ first +"]']:last").val()) || 0) + 1
		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'" class="menu_dep2 menu_'+ site_language +'_'+ first +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'</th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][name]" value="<?=$secondValue["name"]?>" style="width:90%;" /></th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][url]" value="<?=$secondValue["url"]?>" style="width:90%;" /></th>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][sort]" value="'+ second +'" style="width:90%;" /></th>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][use]" style="width:90%;">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']" value="'+ second +'" />';
		str += '	 	</th>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="addThirdMenu(\''+ first +'\', \''+ second +'\');">하위메뉴</a>';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'\')">삭제</a>';
		str += '	 	</th>';
		str += '	 </tr>';
			
		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +", .menu_"+ site_language +"_"+ first).last());
	}

	function addThirdMenu(first, second) {
		var third = (Number($(":input[name='menu["+ site_language +"]["+ first +"]["+ second +"]']:last").val()) || 0) + 1
		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'" class="menu_dep3 menu_'+ site_language +'_'+ first +'_'+ second +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'-'+ third +'</th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][name]" style="width:90%;" /></th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][url]" style="width:90%;" /></th>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][sort]" value="'+ third +'" style="width:90%;" /></th>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][use]" style="width:90%;">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']['+ second +']" value="'+ third +'" />';
		str += '	 	</th>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="addFourthMenu(\''+ first +'\', \''+ second +'\', \''+ third +'\');">하위메뉴</a>';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'\')">삭제</a>';
		str += '	 	</th>';
		str += '	 </tr>';

		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +"_"+ second +", .menu_"+ site_language +"_"+ first +"_"+ second).last());
	}

	function addFourthMenu(first, second, third) {
		var fourth = (Number($(":input[name='menu["+ site_language +"]["+ first +"]["+ second +"]["+ third +"]']:last").val()) || 0) + 1
		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ fourth +'" class="menu_dep4 menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ third +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'-'+ third +'-'+ fourth +'</th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][name]" value="<?=$fourthValue["name"]?>" style="width:90%;" /></th>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][url]" value="<?=$fourthValue["url"]?>" style="width:90%;" /></th>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][sort]" value="'+ fourth +'" style="width:90%;" /></th>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][use]" style="width:90%;">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']['+ second +']['+ third +']" value="'+ fourth +'" />';
		str += '	 	</th>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_mini" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ fourth +'\')">삭제</a>';
		str += '	 	</th>';
		str += '	 </tr>';

		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +"_"+ second +"_"+ third +", .menu_"+ site_language +"_"+ first +"_"+ second +"_"+ third).last());
	}

	function removeMenu(id) {
		$("#"+ id +", ."+ id).remove();
	}

	function menuToggle(id) {
		//$("."+ id).toggle();
	}

	function language_change(language) {
		site_language = language;
		<?php // 컬럼명 선택언어 토글셋팅 ?>
		$("[id*='menu_").addClass("hide");
		$("[id*='menu_"+ language).removeClass("hide");
	}
</script>
<div id="contents">
	<div class="main_tit">
		<h2>메뉴관리 - 등록</h2>
		<div class="btn_right btn_num3">
			<a href="javascript://" onclick="addMenu();" class="btn point">추가</a>
			<a href="javascript://" onclick="menuSave(document.frm);"  class="btn point">저장</a>
			<a href="javascript://" onclick="history.back();" class="btn gray">취소</a>
		</div>
		<?php if($this->_site_language["multilingual"]) : ?>
			<select name="language" style="margin-left:20px;" onchange="language_change(this.value);">
				<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
					<option value="<?=$key?>" <?=$this->input->get("language", true) == $key ? "selected" : ""?>><?=$value?></option>
				<?php endforeach ?>
			</select>
		<?php else : ?>
			<input type="hidden" name="language" value="<?=$this->_site_language[""]?>" />
		<?php endif ?>
	</div>
	<!-- 국문, 영문... 중 선택한 값이 활성화되면, .lang_tab 안의 li에 on 클래스가 추가되게끔 부탁드립니다.-->
	<div class="lang_tab">
		<?php if($this->_site_language["multilingual"]) : ?>
			<ul>
				<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
					<li class="<?=$this->_site_language['default'] == $key ? "on" : ""?>" style="width:<?=(100/count($this->_site_language["support_language"]))?>%">
                        <a href="javascript:language_change('<?=$key?>');"><?=$value?></a>
                    </li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
    </div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="10%" />
					<col width="20%" />
					<col width="40%" />
					<col width="10%" />
					<col width="10%" />
					<col width="10%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col"></th>
						<th scope="col">이름</th>
						<th scope="col">URL</th>
						<th scope="col">순서</th>
						<th scope="col">사용</th>
						<th scope="col">보기</th>
					</tr>
				</head>
				<tbody id='divList'>
					<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
						<?php if(isset($cfg_menu[$language_key])) : ?>
							<?php foreach($cfg_menu[$language_key] as $firstKey => $firstValue) : // 상위메뉴 ?>
								<tr id="menu_<?=$language_key?>_<?=$firstKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>')">
									<td align="center"><span><?=$firstKey?></span></td>
									<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][name]" value="<?=$firstValue["name"]?>" style="width:90%;" /></td>
									<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][url]" value="<?=$firstValue["url"]?>" style="width:90%;" /></td>
									<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][sort]" value="<?=$firstKey?>" style="width:90%;" /></td>
									<td align="center">
										<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][use]" style="width:90%;">
											<option value="y" <?= $firstValue["use"] == "y" ? "selected" : ""?>>사용</option>
											<option value="n" <?=$firstValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
										</select>
										<input type="hidden" name="menu[<?=$language_key?>]" value="<?=$firstKey?>" />
									</td>
									<td align="center">
										<a href="javascript://" class="btn_mini" onclick="addSecondMenu('<?=$firstKey?>');">하위메뉴</a>
										<a href="javascript://" class="btn_mini" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>')">삭제</a>
									</td>
								</tr>


								<?php if(isset($firstValue["menu"])) : ?>
									<?php foreach($firstValue["menu"] as $secondKey => $secondValue) : // 2 메뉴 ?>
										<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>" class="menu_dep2 menu_<?=$language_key?>_<?=$firstKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>')">
											<td align="center"><span><?=$firstKey?>-<?=$secondKey?></span></td>
											<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][name]" value="<?=$secondValue["name"]?>" style="width:90%;" /></td>
											<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][url]" value="<?=$secondValue["url"]?>" style="width:90%;" /></td>
											<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][sort]" value="<?=$secondKey?>" style="width:90%;" /></td>
											<td align="center">
												<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][use]" style="width:90%;">
													<option value="y" <?=$secondValue["use"] == "y" ? "selected" : ""?>>사용</option>
													<option value="n" <?=$secondValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
												</select>
												<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>]" value="<?=$secondKey?>" />
											</td>
											<td align="center">
												<a href="javascript://" class="btn_mini" onclick="addThirdMenu('<?=$firstKey?>', '<?=$secondKey?>');">하위메뉴</a>
												<a href="javascript://" class="btn_mini" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>')">삭제</a>
											</td>
										</tr>
									

										<?php if(isset($secondValue["menu"])) : ?>
											<?php foreach($secondValue["menu"] as $thirdKey => $thirdValue) : // 3 메뉴 ?>
												<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>" class="menu_dep3 menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>')">
													<td align="center"><span><?=$firstKey?>-<?=$secondKey?>-<?=$thirdKey?></span></td>
													<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][name]" value="<?=$thirdValue["name"]?>" style="width:90%;" /></td>
													<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][url]" value="<?=$thirdValue["url"]?>" style="width:90%;" /></td>
													<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][sort]" value="<?=$thirdKey?>" style="width:90%;" /></td>
													<td align="center">
														<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][use]" style="width:90%;">
															<option value="y" <?=$thirdValue["use"] == "y" ? "selected" : ""?>>사용</option>
															<option value="n" <?=$thirdValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
														</select>
														<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>][<?=$secondKey?>]" value="<?=$thirdKey?>" />
													</td>
													<td align="center">
														<a href="javascript://" class="btn_mini" onclick="addFourthMenu('<?=$firstKey?>', '<?=$secondKey?>', '<?=$thirdKey?>');">하위메뉴</a>
														<a href="javascript://" class="btn_mini" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>')">삭제</a>
													</td>
												</tr>
											

												<?php if(isset($thirdValue["menu"])) : ?>
													<?php foreach($thirdValue["menu"] as $fourthKey => $fourthValue) : // 4 메뉴 ?>
														<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$fourthKey?>" class="menu_dep4 menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$thirdKey?>')">
															<td align="center"><span><?=$firstKey?>-<?=$secondKey?>-<?=$thirdKey?>-<?=$fourthKey?></span></td>
															<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][name]" value="<?=$fourthValue["name"]?>" style="width:90%;" /></td>
															<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][url]" value="<?=$fourthValue["url"]?>" style="width:90%;" /></td>
															<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][sort]" value="<?=$fourthKey?>" style="width:90%;" /></td>
															<td align="center">
																<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][use]" style="width:90%;">
																	<option value="y" <?=$fourthValue["use"] == "y" ? "selected" : ""?>>사용</option>
																	<option value="n" <?=$fourthValue["use"] == "n"? "selected" : ""?>>사용안함</option>
																</select>
																<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>][<?=$secondKey?>][<?=$thirdKey?>]" value="<?=$fourthKey?>" />
															</td>
															<td align="center">
																<!-- <a href="javascript://" class="btn_mini" onclick="addFourthMenu('<?=$firstKey?>', '<?=$secondKey?>', '<?=$thirdKey?>', '<?=$fourthKey?>');">하위메뉴</a> -->
																<a href="javascript://" class="btn_mini" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$fourthKey?>')">삭제</a>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endforeach; ?>	
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>
