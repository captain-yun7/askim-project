<script>
	$(function() {
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	function select_init(ele, field) {
		var select_value = $(ele).find("option:selected").val();

		//파일일 경우 셀렉트 박스 추가
		if(select_value == "file") {
			$("select[name='optionField["+site_language+"]["+field+"][file_type]']").prop("disabled", false).removeClass("hide");
			$("input[name='optionField["+site_language+"]["+field+"][width]'], input[name='optionField["+site_language+"]["+field+"][height]']").prop("disabled", false).removeClass("hide disabled");
		} else {
			$("select[name='optionField["+site_language+"]["+field+"][file_type]']").prop("disabled", true).addClass("hide");
			$("input[name='optionField["+site_language+"]["+field+"][width]'], input[name='optionField["+site_language+"]["+field+"][height]']").prop("disabled", true).addClass("hide disabled");
		}

		if(!select_value || select_value == "editor" || select_value == "file") {
			$(".box_option_"+site_language+"_"+ field +", #btn_option_"+site_language+"_"+ field).addClass("hide");
			$(".box_option_"+site_language+"_"+ field).find(":text").prop("disabled", true).addClass("disabled hide");
		} else {
			$(".box_option_"+site_language+"_"+ field +", #btn_option_"+site_language+"_"+ field).removeClass("hide");
			$(".box_option_"+site_language+"_"+ field).find(":text").prop("disabled", false).removeClass("disabled hide");
		}
	}

	function select_file_type(ele, field) {
		var select_value = $(ele).find("option:selected").val();
		//이미지일 경우 width 와 height 추가
		if(select_value == "image") {
			$("input[name='optionField["+site_language+"]["+field+"][width]'], input[name='optionField["+site_language+"]["+field+"][height]']").prop("disabled", false).removeClass("hide disabled");
		} else {
			$("input[name='optionField["+site_language+"]["+field+"][width]'], input[name='optionField["+site_language+"]["+field+"][height]']").prop("disabled", true).addClass("hide disabled");
		}
	}

	function remove_option(field,  optionNo) {
		$("#box_option_"+ site_language +"_"+ field +"_"+ optionNo).remove();
	}

	function add_option(field) {
		var last_option_no = Number($(".option_"+ site_language +"_"+ field +":last").val()) + 1 || 1;
		var str = '';
		str += '	<div id="box_option_'+ site_language +'_'+ field +'_'+ last_option_no +'" class="box_option_'+ site_language +'_'+ field +' box_option_'+ field +'">';
		str += '		<input type="hidden" class="option_'+ site_language +'_'+ field +'" value="'+ last_option_no +'" />';
		str += '		<input type="hidden" name="optionField['+ site_language +']['+ field +'][itemName][]" value="'+ field +"-"+ last_option_no +'" style="width:35%;" />';
		str += '		<input type="text" name="optionField['+ site_language +']['+ field +'][itemValue][]" style="width:35%;" />';
		str += '		<a href="javascript://" class="btn_mini" onclick="remove_option(\''+ field +'\', \''+ last_option_no +'\');">삭제</a>';
		str += '	</div>';
		$(str).appendTo("#option_"+ field +"_td:last");
	}

    function language_change(language,obj) {
        site_language = language;

        if(obj){
            $(".lang_tab").find("li").each(function(i,e){
            if($(e).hasClass("on")){
                $(e).removeClass("on");
                }
            });

            $(obj).closest("li").addClass("on");
            var select_language = $(obj).attr("data-language");
            $("[name='language']").val(select_language);
        }

		<?php // 컬럼명 선택언어 토글셋팅 ?>
		$("[name*='nameField").addClass("hide");
		$("[name*='nameField["+ site_language +"]']").removeClass("hide");

		<?php // 옵션 선택언어 토글셋팅 ?>
		$("[class*='box_option").addClass("hide");
		$("[class*='box_option_"+ site_language +"']").removeClass("hide");

		<?php // 사용 선택언어 토글셋팅 ?>
		$("[name*='useField']:not(:disabled)").addClass("hide");
		$("[name*='useField["+site_language+"]']").removeClass("hide");

		<?php // 필수 선택언어 토글셋팅 ?>
		$("[name*='reqField']:not(:disabled").addClass("hide");
		$("[name*='reqField["+site_language+"]']").removeClass("hide");

		$("[name*='optionField']").addClass("hide");
		$("[class*='file_type']").addClass("hide");
		$("[id*='btn_option']").addClass("hide");
		$("[class*='item_type']").addClass("hide");

		$("[class*='item_type_"+site_language+"']").removeClass("hide");

		$("[class*='item_type_"+site_language+"']").each(function(idx, item){
			select_init(item, $(item).data("itemname"));
		});

		$("[class*='file_type_"+site_language+"']").each(function(idx, item){
			select_file_type(item, $(item).data("itemname"));
		});
	}

	function field_save(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}
		var is_submit = true;

		<?php if($this->_site_language["multilingual"]) : ?>
			<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
				$(frm).find(":text:not('.disabled')").filter("[name*='<?=$key?>']").not("#image_width, #image_height").each(function() {
					if(this.value == "" && "<?=$key?>" == "<?=$this->_site_language['default']?>") {
						alert("<?=$value?>에 입력하지 않는 곳이 있습니다.");
						this.focus();
						is_submit = false;
						return false;
					}
				});
			<?php endforeach ?>
		<?php else :?>
			$(frm).find(":text:not('.disabled')").filter("[name*='<?=$this->_site_language["default"]?>']").not("#image_width, #image_height").each(function() {
				if(this.value == "") {
					alert("<?=$this->_site_language["support_language"][$this->_site_language["default"]]?>에 입력하지 않는 곳이 있습니다.");
					this.focus();
					is_submit = false;
					return false;
				}
			});
		<?php endif ?>

		if(is_submit) {
			frm.submit();
		}
	}

</script>
<div id="contents">
	<div class="main_tit">
		<h2>상품필드셋팅<em>* 필수 항목은 사용 체크가 되어있어야 합니다.</em></h2>
		<div class="btn_right">
			<a href="javascript://" onclick="field_save(document.frm);" class="btn point">저장</a>
		</div>
		<div class="location">
			<h4><button type="button" class="info_bullet">URL</button> <?=base_url()?>goods/goods_view <a class="btn-sm" href="<?=base_url()?>goods/goods_view" target="_blank">바로가기</a></h4>
			<h4><button type="button" class="info_bullet">SKIN</button> /data/skin/<?=$this->_skin?>/goods/goods_view</h4>
		</div>
	</div>
	<div class="lang_tab">
		<?php if($this->_site_language["multilingual"]) : ?>
			<ul>
				<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
					<li class="<?=$this->_site_language['default'] == $key ? "on" : ""?>" style="width:<?=(100/count($this->_site_language["support_language"]))?>%">
                        <a onclick="javascript:language_change('<?=$key?>',this);" data-language = "<?=$key?>"><?=$value?></a>
                    </li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
    </div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="register" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="6%">
					<col width="6%">
					<col width="19%">
					<col>
					<col width="20%">
					<col width="21.5%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">사용</th>
						<th scope="col">필수</th>
						<th scope="col">필드명</th>
						<th scope="col">변수명</th>
						<th scope="col">형식</th>
						<th scope="col">옵션</th>
					</tr>
				</head>
				<tbody id='divList'>
					<?php foreach($fieldset['default'] as $value) :?>
						<tr>
							<td align="center"><label><input type="checkbox" name="useField[<?=$value?>]" checked disabled /> 사용</label></td>
							<td align="center"><label><input type="checkbox" name="reqField[<?=$value?>]" checked disabled /> 필수</label></td>
							<td class="ta_left field_td">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor") : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$goodsField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>
							<td align="left"><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
							<td align="left"></td>
							<td align="center"></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['hidden'] as $value) :?>
						<tr>
							<td align="center"><label><input type="checkbox" checked disabled /> 사용</label></td>
							<td align="center"><label><input type="checkbox" checked disabled /> 필수</label></td>
							<td class="ta_left field_td">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor") : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$goodsField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>
							<td align="left"><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
							<td align="left"></td>
							<td align="center"></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['etc'] as $value) :?>
						<tr>
							<td align="center">
								<label>
									<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
										<input type="checkbox" name="useField[<?=$language_key?>][<?=$value?>]" value="checked"
										<?
											if(isset($goodsField["use"][$value]) || isset($goodsField["use"][$language_key][$value])):
												echo "checked";
											endif
										?>
										/>
									<?php endforeach ?>
									사용
								</label>
							</td>
							<td align="center">
								<label>
									<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
										<input type="checkbox" name="reqField[<?=$language_key?>][<?=$value?>]" value="checked"
										<?
											if(isset($goodsField["require"][$value]) || isset($goodsField["require"][$language_key][$value])):
												echo "checked";
											endif
										?>
										/>
									<?php endforeach ?>
									필수
								</label>
							</td>
							<td class="ta_left field_td">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor" && !in_array($value, $arr_ex)) : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$goodsField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$goodsField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>

							<td align="left"><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
							<td align="left">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(in_array($value, $readonly)) : // 수정불가 필드처리 ?>
										<select class="item_type_<?=$language_key?>" data-itemname="<?=$value?>" name="optionField[<?=$language_key?>][<?=$value?>][type]" onfocus="this.initialSelect=this.selectedIndex;" onchange="this.selectedIndex=this.initialSelect;">
											<option value="">텍스트</option>
											<option value="radio"
												<?=($goodsField["option"][$language_key][$value]["type"] == "radio" || $goodsField["option"][$language_key][$value]["type"] == "radio") ? "selected" : ""?>
											>라디오</option>
											<option value="select"
												<?=($goodsField["option"][$language_key][$value]["type"] == "select" || $goodsField["option"][$language_key][$value]["type"] == "select") ? "selected" : ""?>
											>셀렉트</option>
											<option value="editor"
												<?=($goodsField["option"][$language_key][$value]["type"] == "editor" || $goodsField["option"][$language_key][$value]["type"] == "editor") ? "selected" : ""?>
											>에디터</option>
											<option value="file"
												<?=($goodsField["option"][$language_key][$value]["type"] == "file" || $goodsField["option"][$language_key][$value]["type"] == "file") ? "selected" : ""?>
											>파일</option>
										</select>

										<select class="file_type_<?=$language_key?> <?=!in_array($goodsField["option"][$language_key][$value]["type"], array("file")) ? "hide" : ""?>" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" <?php if($value == 'upload_fname') : ?>onchange="select_file_type(this, '<?=$value?>');"<?php else : ?>onfocus="this.initialSelect=this.selectedIndex;" onchange="this.selectedIndex=this.initialSelect;"<?php endif?> data-itemname="<?=$value?>">
											<?php foreach($extension as $itemValue) : ?>
												<option value="<?=$itemValue?>"
												<?=($goodsField["option"][$language_key][$value]["file_type"] == $itemValue || $goodsField["option"][$language_key][$value]["file_type"] == $itemValue) ? "selected" : ""?>><?=$itemValue?>
												</option>
											<?php endforeach; ?>
										</select>
									<?php else : ?>
										<select class="item_type_<?=$language_key?>" data-itemname="<?=$value?>" name="optionField[<?=$language_key?>][<?=$value?>][type]" onchange="select_init(this, '<?=$value?>');" >
											<option value="">텍스트</option>
											<option value="radio"
												<?=($goodsField["option"][$value]["type"] == "radio" || $goodsField["option"][$language_key][$value]["type"] == "radio") ? "selected" : ""?>
											>라디오</option>
											<option value="select"
												<?=($goodsField["option"][$value]["type"] == "select" || $goodsField["option"][$language_key][$value]["type"] == "select") ? "selected" : ""?>
											>셀렉트</option>
											<option value="editor"
												<?=($goodsField["option"][$value]["type"] == "editor" || $goodsField["option"][$language_key][$value]["type"] == "editor") ? "selected" : ""?>
											>에디터</option>
											<option value="file"
												<?=($goodsField["option"][$value]["type"] == "file" || $goodsField["option"][$language_key][$value]["type"] == "file") ? "selected" : ""?>
											>파일</option>
										</select>

										<a href="javascript://" id="btn_option_<?=$language_key?>_<?=$value?>" class="btn_mini <?=!isset($goodsField["option"][$language_key][$value]["type"]) || (isset($goodsField["option"][$language_key][$value]["type"]) && !in_array($goodsField["option"][$language_key][$value]["type"], array("checkbox", "radio", "select"))) ?"hide" : ""?>" onclick="add_option('<?=$value?>');" >추가</a>
										<select class="file_type_<?=$language_key?> <?=!isset($goodsField["option"][$language_key][$value]["type"]) || (isset($goodsField["option"][$language_key][$value]["type"]) && in_array($goodsField["option"][$language_key][$value]["type"], array("checkbox", "radio", "select", "editor"))) ? "hide disabled" : ""?>" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" onchange="select_file_type(this, '<?=$value?>');" <?=!isset($goodsField["option"][$language_key][$value]["type"]) || (isset($goodsField["option"][$language_key][$value]["type"]) && in_array($goodsField["option"][$language_key][$value]["type"], array("checkbox", "radio", "select", "editor"))) ? "disabled" : ""?> data-itemname="<?=$value?>">
											<?php foreach($extension as $itemValue) : ?>
												<option value="<?=$itemValue?>"
												<?=($goodsField["option"][$language_key][$value]["file_type"] == $itemValue || $goodsField["option"][$language_key][$value]["file_type"] == $itemValue) ? "selected" : ""?>><?=$itemValue?>
												</option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								<?php endforeach ?>
							</td>

							<td id="option_<?=$value?>_td" align="center">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(isset($goodsField["option"][$language_key][$value]["item"])): ?>
										<?php $i = 0; ?>
										<?php foreach($goodsField["option"][$language_key][$value]["item"] as $itemName => $itemValue) : ?>
											<?php $i++; ?>
											<div id="box_option_<?=$language_key?>_<?=$value?>_<?=$i?>" class="box_option_<?=$language_key?>_<?=$value?> box_option_<?=$value?>">
												<input type="hidden" class="option_<?=$language_key?>_<?=$value?>" value="<?=$i?>" />
												<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][itemName][]" value="<?=$itemName?>" style="width:35%;" <?php if(in_array($value, $readonly)) : echo "readonly"; endif; ?> />
												<input type="text" name="optionField[<?=$language_key?>][<?=$value?>][itemValue][]" value="<?=$itemValue?>" style="width:35%;" />
												<?php if(!in_array($value, $readonly)) : ?><a href="javascript://" class="btn_mini" onclick="remove_option('<?=$value?>', '<?=$i?>');">삭제</a><? endif; ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
									<input type="text" id="image_width" name="optionField[<?=$language_key?>][<?=$value?>][width]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" style="width:40%;" placeholder="가로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["width"]) ? $goodsField["option"][$language_key][$value]["width"] : ""?>" <?= $goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />

									<input type="text" id="image_height" name="optionField[<?=$language_key?>][<?=$value?>][height]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" style="width:40%;" placeholder="세로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["height"]) ? $goodsField["option"][$language_key][$value]["height"] : ""?>" d<?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />
								<?php endforeach ?>
								<?php
								 if ( $value == img1 || $value == img2 ) {
									echo "<p class='bbs_txt'>세로값을 비우면, <b>원본 이미지 비율을 유지</b>하고 리사이징됩니다.</p>";
								 }
								 ?>
							</td>
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>