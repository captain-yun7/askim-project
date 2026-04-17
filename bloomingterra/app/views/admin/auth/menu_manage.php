<script>
	$(function() {
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	function menuSave(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}

		var is_submit = true;

		$(frm).find(":text:not('.disabled')").filter("[name*='"+site_language+"'][name*='name']").each(function() {
			if(this.value == "") {
				alert("입력하지 않는 곳이 있습니다.");
				this.focus();
				is_submit = false;
				return false;
			}
		});

		if(is_submit) {
			frm.submit();
		}

		obj = $("form[name='frm']").serializeObject();

	}

	function addMenu() {

		//var first = (Number($(":input[name='menu["+  site_language +"]']:last").val()) || 0) + 1
		//var first = (Number($(":input[name='menu["+  site_language +"]']").length) || 0) + 1;

		var inputData = new Array();
		for (var i = 0 ; i < $('tr.menu_dep1.menu_'+site_language).length; i++){
			var key = $('tr.menu_dep1.menu_'+site_language).eq(i).attr('id').split('_')[2];
			inputData[i] = Number($('tr.menu_dep1.menu_'+site_language).find('input[name="front_menu['+ site_language +']['+ key +'][sort]"]').val());
		}
		var first = inputData.length > 0 ? Math.max.apply(null, inputData)+1 : 0;

		var str = '';
		str += '	<tr id="menu_'+ site_language +'_'+ first +'" class="menu_dep1 menu_'+ site_language+'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'\')">';
		str += '	 	<td align="center">'+ first +'</td>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][use]">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']" value="'+ first +'" />';
		str += '	 	</td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][name]" class="cnm"/></td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][url]"/></td>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][sort]" value="'+ first +'"/></td>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_dep remove" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'\')">삭제</a>';
		str += '	 		<a href="javascript://" class="btn_dep add" onclick="addSecondMenu(\''+ first +'\');">하위메뉴추가</a>';
		str += '	 	</td>';
		str += '	 </tr>';

		$(str).appendTo("#divList").find("input.cnm").focus();
	}

	function addSecondMenu(first) {
		//var second = (Number($(":input[name='menu["+ site_language +"]["+ first +"]']:last").val()) || 0) + 1;

		var inputData = new Array();
		for (var i = 0 ; i < $('tr.menu_dep2.menu_'+site_language+'_'+first).length; i++){
			var key = $('tr.menu_dep2.menu_'+site_language+'_'+first).eq(i).attr('id').split('_')[3];
			inputData[i] = Number($('tr.menu_dep2.menu_'+site_language+'_'+first).find('input[name="front_menu['+ site_language +']['+ first +'][menu]['+key+'][sort]"]').val());
		}
		var second = inputData.length > 0 ? Math.max.apply(null, inputData)+1 : 1;

		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'" class="menu_dep2 menu_'+ site_language +'_'+ first +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'</td>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][use]">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']" value="'+ second +'" />';
		str += '	 	</td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][name]" class="cnm" value="<?=$secondValue["name"]?>"/></td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][url]" value="<?=$secondValue["url"]?>"/></td>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][sort]" value="'+ second +'"/></td>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_dep remove" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'\')">삭제</a>';
		str += '	 		<a href="javascript://" class="btn_dep add" onclick="addThirdMenu(\''+ first +'\', \''+ second +'\');">하위메뉴추가</a>';
		str += '	 	</td>';
		str += '	 </tr>';

		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +", .menu_"+ site_language +"_"+ first).last()).find("input.cnm").focus();
	}

	function addThirdMenu(first, second) {
		//var third = (Number($(":input[name='menu["+ site_language +"]["+ first +"]["+ second +"]']:last").val()) || 0) + 1;

		var inputData = new Array();
		for (var i = 0 ; i < $('tr.menu_dep3.menu_'+site_language+'_'+first+'_'+second).length; i++){
			var key = $('tr.menu_dep3.menu_'+site_language+'_'+first+'_'+second).eq(i).attr('id').split('_')[4];
			inputData[i] = Number($('tr.menu_dep3.menu_'+site_language+'_'+first+'_'+second).find('input[name="front_menu['+ site_language +']['+ first +'][menu]['+second+'][menu]['+key+'][sort]"]').val());
		}
		var third = inputData.length > 0 ? Math.max.apply(null, inputData)+1 : 1;

		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'" class="menu_dep3 menu_'+ site_language +'_'+ first +'_'+ second +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'-'+ third +'</td>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][use]">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']['+ second +']" value="'+ third +'" />';
		str += '	 	</td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][name]" class="cnm"/></td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][url]"/></td>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][sort]" value="'+ third +'"/></td>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_dep remove" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'\')">삭제</a>';
		str += '	 		<a href="javascript://" class="btn_dep add" onclick="addFourthMenu(\''+ first +'\', \''+ second +'\', \''+ third +'\');">하위메뉴추가</a>';
		str += '	 	</td>';
		str += '	 </tr>';

		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +"_"+ second +", .menu_"+ site_language +"_"+ first +"_"+ second).last()).find("input.cnm").focus();
	}

	function addFourthMenu(first, second, third) {
		//var fourth = (Number($(":input[name='menu["+ site_language +"]["+ first +"]["+ second +"]["+ third +"]']:last").val()) || 0) + 1;

		var inputData = new Array();
		for (var i = 0 ; i < $('tr.menu_dep4.menu_'+site_language+'_'+first+'_'+second+'_'+third).length; i++){
			var key = $('tr.menu_dep4.menu_'+site_language+'_'+first+'_'+second+'_'+third).eq(i).attr('id').split('_')[5];
			inputData[i] = Number($('tr.menu_dep4.menu_'+site_language+'_'+first+'_'+second+'_'+third).find('input[name="front_menu['+ site_language +']['+ first +'][menu]['+second+'][menu]['+third+'][menu]['+key+'][sort]"]').val());
		}
		var fourth = inputData.length > 0 ? Math.max.apply(null, inputData)+1 : 1;

		var str = '';
		str += '	 <tr id="menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ fourth +'" class="menu_dep4 menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'" onclick="menuToggle(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ third +'\')">';
		str += '	 	<td align="center">'+ first +'-'+ second +'-'+ third +'-'+ fourth +'</td>';
		str += '	 	<td align="center">';
		str += '	 		<select name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][use]">';
		str += '	 			<option value="y">사용</option>';
		str += '	 			<option value="n">사용안함</option>';
		str += '	 		</select>';
		str += '	 		<input type="hidden" name="menu['+ site_language +']['+ first +']['+ second +']['+ third +']" value="'+ fourth +'" />';
		str += '	 	</td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][name]" class="cnm" value="<?=$fourthValue["name"]?>"/></td>';
		str += '	 	<td align="center" style="padding-left:10px"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][url]" value="<?=$fourthValue["url"]?>"/></td>';
		str += '	 	<td align="center"><input type="text" name="front_menu['+ site_language +']['+ first +'][menu]['+ second +'][menu]['+ third +'][menu]['+ fourth +'][sort]" value="'+ fourth +'"/></td>';
		str += '	 	<td align="center">';
		str += '	 		<a href="javascript://" class="btn_dep remove" onclick="removeMenu(\'menu_'+ site_language +'_'+ first +'_'+ second +'_'+ third +'_'+ fourth +'\')">삭제</a>';
		str += '	 	</td>';
		str += '	 </tr>';

		$(str).insertAfter($("#menu_"+ site_language +"_"+ first +"_"+ second +"_"+ third +", .menu_"+ site_language +"_"+ first +"_"+ second +"_"+ third).last()).find("input.cnm").focus();
	}

	function removeMenu(id) {
		var menuDep = $("#"+id).attr("class").split(' ')[0];
		var menuDep2Id = new Array();
		var menuDep3Id = new Array();
		var menuDep4Id = new Array();

		switch (menuDep){
			case 'menu_dep1':
				for (let i = 0; i < $('.menu_dep2.' + id).length; i++){
					menuDep2Id.push($('.menu_dep2.' + id).eq(i).attr('id'));
					for (let j = 0; j < $('.menu_dep3.' + menuDep2Id[i]).length; j++){
						menuDep3Id.push($('.menu_dep3.' + menuDep2Id).eq(j).attr('id'));
						for (let k = 0; k < $('.menu_dep4.' + menuDep3Id[j]).length; k++){
							menuDep4Id.push($('.menu_dep4.' + menuDep3Id).eq(k).attr('id'));
						}
					}
				}
			break;

			case 'menu_dep2':
				for (let i = 0; i < $('.menu_dep3.' + id).length; i++){
					menuDep3Id.push($('.menu_dep3.' + id).eq(i).attr('id'));
					for (let j = 0; j < $('.menu_dep4.' + menuDep3Id[i]).length; j++){
						menuDep4Id.push($('.menu_dep4.' + menuDep3Id).eq(j).attr('id'));
					}
				}
			break;

			case 'menu_dep3':
				for (let i = 0; i < $('.menu_dep4.' + id).length; i++){
					menuDep4Id.push($('.menu_dep4.' + id).eq(i).attr('id'));
				}
			break;
		}

		if (menuDep2Id.length > 0 || menuDep3Id.length > 0 || menuDep4Id.length > 0){

			if (!confirm("삭제하시려는 메뉴는 하위메뉴추가가 존재하며 삭제 진행시 하위 메뉴가 모두 삭제됩니다. 삭제하시겠습니까?")){
				return false;
			}

			if (menuDep2Id.length > 0){
				for (let i = 0; i < menuDep2Id.length; i++){
					$('#'+menuDep2Id[i]).remove();
				}
			}
			if (menuDep3Id.length > 0){
				for (let i = 0; i < menuDep3Id.length; i++){
					$('#'+menuDep3Id[i]).remove();
				}
			}
			if (menuDep4Id.length > 0){
				for (let i = 0; i < menuDep4Id.length; i++){
					$('#'+menuDep4Id[i]).remove();
				}
			}

			$("#"+ id).remove();

		}else{
			$("#"+ id).remove();
		}

		//$("#"+ id +", ."+ id).remove();
	}

	function menuToggle(id) {
		//$("."+ id).toggle();
	}

    function language_change(language,obj) {
        site_language = language;

        if(obj){
            $(".lang_icon_tab").find("li").each(function(i,e){
            if($(e).hasClass("on")){
                $(e).removeClass("on");
                }
            });

            $(obj).closest("li").addClass("on");
            var select_language = $(obj).attr("data-language");
            $("[name='language']").val(select_language);
        }

		<?php // 컬럼명 선택언어 토글셋팅 ?>
		$("[id*='menu_").addClass("hide");
		$("[id*='menu_"+ language).removeClass("hide");
	}
</script>
<div id="contents">
	<div class="main_tit">
		<h2>메뉴 설정</h2>
		<?php if(!$this->_site_language["multilingual"]) : ?>
			<input type="hidden" name="language" value="<?=$this->_site_language[""]?>" />
		<?php endif ?>
		<div class="lang_icon_tab">
			<?php if($this->_site_language["multilingual"]) : ?>
			<ul>
			<?php
			foreach($this->_site_language["support_language"] as $key => $value) :
				foreach($this->_site_language['set_language'] as $k => $v) :
					if($k == $key) :
						$on = $this->_site_language['default'] == $key ? "on" : "";
						echo "<li class='".$on." lang_".$key."'><a onclick='javascript:language_change(\"".$key."\", this);' data-language='".$key."'>".$value."</a></li>";
					endif;
				endforeach;
			endforeach;
			?>
			</ul>
			<?php endif ?>
		</div>
		<div class="btn_right">
			<a href="javascript://" onclick="addMenu();" class="btn point new_plus">+ 메뉴 추가</a>
			<a href="javascript://" onclick="history.back();" class="btn gray">취소</a>
			<a href="javascript://" onclick="menuSave(document.frm);"  class="btn point">저장</a>
		</div>
	</div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="table_write table_menu">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="10%" />
					<col width="10%" />
					<col width="25%" />
					<col width="" />
					<col width="5%" />
					<col width="15%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col"></th>
						<th scope="col">사용</th>
						<th scope="col">메뉴명</th>
						<th scope="col">URL</th>
						<th scope="col">순서</th>
						<th scope="col">보기</th>
					</tr>
				</head>
				<tbody id='divList'>
					<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
						<?php if(isset($cfg_menu[$language_key])) : ?>
							<?php foreach($cfg_menu[$language_key] as $firstKey => $firstValue) : // 상위메뉴 ?>
								<tr id="menu_<?=$language_key?>_<?=$firstKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>')" class="menu_dep1 menu_<?=$language_key?>">
									<td align="left"><span><?=$firstKey?></span></td>
									<td align="center">
										<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][use]">
											<option value="y" <?= $firstValue["use"] == "y" ? "selected" : ""?>>사용</option>
											<option value="n" <?=$firstValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
										</select>
										<input type="hidden" name="menu[<?=$language_key?>]" value="<?=$firstKey?>" />
									</td>
									<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][name]" value="<?=$firstValue["name"]?>"/></td>
									<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][url]" value="<?=$firstValue["url"]?>"/></td>
									<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][sort]" value="<?=$firstKey?>"/></td>
									<td align="center">
										<a href="javascript://" class="btn_dep remove" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>')">삭제</a>
										<a href="javascript://" class="btn_dep add" onclick="addSecondMenu('<?=$firstKey?>');">하위메뉴추가</a>
									</td>
								</tr>


								<?php if(isset($firstValue["menu"])) : ?>
									<?php foreach($firstValue["menu"] as $secondKey => $secondValue) : // 2 메뉴 ?>
										<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>" class="menu_dep2 menu_<?=$language_key?>_<?=$firstKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>')">
											<td align="center"><span><?=$firstKey?>-<?=$secondKey?></span></td>
											<td align="center">
												<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][use]">
													<option value="y" <?=$secondValue["use"] == "y" ? "selected" : ""?>>사용</option>
													<option value="n" <?=$secondValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
												</select>
												<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>]" value="<?=$secondKey?>" />
											</td>
											<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][name]" value="<?=$secondValue["name"]?>"/></td>
											<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][url]" value="<?=$secondValue["url"]?>"/></td>
											<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][sort]" value="<?=$secondKey?>"/></td>
											<td align="center">
												<a href="javascript://" class="btn_dep remove" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>')">삭제</a>
												<a href="javascript://" class="btn_dep add" onclick="addThirdMenu('<?=$firstKey?>', '<?=$secondKey?>');">하위메뉴추가</a>
											</td>
										</tr>


										<?php if(isset($secondValue["menu"])) : ?>
											<?php foreach($secondValue["menu"] as $thirdKey => $thirdValue) : // 3 메뉴 ?>
												<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>" class="menu_dep3 menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>')">
													<td align="center"><span><?=$firstKey?>-<?=$secondKey?>-<?=$thirdKey?></span></td>
													<td align="center">
														<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][use]">
															<option value="y" <?=$thirdValue["use"] == "y" ? "selected" : ""?>>사용</option>
															<option value="n" <?=$thirdValue["use"] == "n" ? "selected" : ""?>>사용안함</option>
														</select>
														<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>][<?=$secondKey?>]" value="<?=$thirdKey?>" />
													</td>
													<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][name]" value="<?=$thirdValue["name"]?>"/></td>
													<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][url]" value="<?=$thirdValue["url"]?>"/></td>
													<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][sort]" value="<?=$thirdKey?>" /></td>
													<td align="center">
														<a href="javascript://" class="btn_dep remove" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>')">삭제</a>
														<a href="javascript://" class="btn_dep add" onclick="addFourthMenu('<?=$firstKey?>', '<?=$secondKey?>', '<?=$thirdKey?>');">하위메뉴추가</a>
													</td>
												</tr>


												<?php if(isset($thirdValue["menu"])) : ?>
													<?php foreach($thirdValue["menu"] as $fourthKey => $fourthValue) : // 4 메뉴 ?>
														<tr id="menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$fourthKey?>" class="menu_dep4 menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>" onclick="menuToggle('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$thirdKey?>')">
															<td align="center"><span><?=$firstKey?>-<?=$secondKey?>-<?=$thirdKey?>-<?=$fourthKey?></span></td>
															<td align="center">
																<select name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][use]">
																	<option value="y" <?=$fourthValue["use"] == "y" ? "selected" : ""?>>사용</option>
																	<option value="n" <?=$fourthValue["use"] == "n"? "selected" : ""?>>사용안함</option>
																</select>
																<input type="hidden" name="menu[<?=$language_key?>][<?=$firstKey?>][<?=$secondKey?>][<?=$thirdKey?>]" value="<?=$fourthKey?>" />
															</td>
															<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][name]" value="<?=$fourthValue["name"]?>"/></td>
															<td align="center" style="padding-left:10px"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][url]" value="<?=$fourthValue["url"]?>"/></td>
															<td align="center"><input type="text" name="front_menu[<?=$language_key?>][<?=$firstKey?>][menu][<?=$secondKey?>][menu][<?=$thirdKey?>][menu][<?=$fourthKey?>][sort]" value="<?=$fourthKey?>"/></td>
															<td align="center">
																<!-- <a href="javascript://" class="btn_mini" onclick="addFourthMenu('<?=$firstKey?>', '<?=$secondKey?>', '<?=$thirdKey?>', '<?=$fourthKey?>');">하위메뉴추가</a> -->
																<a href="javascript://" class="btn_dep remove" onclick="removeMenu('menu_<?=$language_key?>_<?=$firstKey?>_<?=$secondKey?>_<?=$thirdKey?>_<?=$fourthKey?>')">삭제</a>
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
	<div class="terms_privecy_box">
		<dl>
			<dt>- 상품 카테고리를 메뉴관리에서 사용할 경우 주의사항</dt>
			<dd>
			사용자 페이지(홈페이지) 상단 메뉴 활성화 기능으로 1차 메뉴의 하위 메뉴에 접속하게 되면 해당 1차 메뉴가 활성화 됩니다. <br>
			"상품관리 > 카테고리관리" 에서 등록한 상품 카테고리를 메뉴관리에 등록할 경우, <em class="point">하위 카테고리가 있는 1차 카테고리 한 개</em>만 등록이 가능합니다. <br><br>

			*** 1차 메뉴에 하위 카테고리가 없는 1차 상품 카테고리를 2개 이상 사용해야 할 경우, 소스상에서 별도로 상단메뉴 활성화 처리를 script로 진행해주셔야 합니다.<br><br>
			</dd>
		</dl>
	</div>
</div>
