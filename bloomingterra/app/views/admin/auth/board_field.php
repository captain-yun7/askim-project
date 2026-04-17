<script>
$(function() {
	language_change("<?=$this->_site_language['default']?>");
});

var site_language = "<?=$this->_site_language['default']?>";

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

	$("[class*='_td']").addClass("hide");
	$("[class*='"+site_language+"_td']").removeClass("hide");

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
		<h2>게시판필드셋팅<em>* 필수 항목은 사용 체크가 되어있어야 합니다.</em></h2>
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
				<?php foreach($this->_site_language["support_language"] as $languageKey => $languageVal) :?>
					<li class="<?=$this->_site_language['default'] == $languageKey ? "on" : ""?>" style="width:<?=(100/count($this->_site_language["support_language"]))?>%">
                        <a onclick="javascript:language_change('<?=$languageKey?>',this);" data-language = "<?=$languageKey?>"><?=$languageVal?></a>
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
					<? foreach($fieldSet as $columnKey){ ?>

						<tr>
							<? foreach($this->_site_language["support_language"] as $languageKey => $languageVal){
								$boardOption = $boardField["option"][$languageKey][$columnKey];
							?>
								<!--사용-->
								<td align="center" class = "<?=$languageKey?>_td">
									<label>
										<input type = "checkbox" name = "useField[<?=$languageKey?>][<?=$columnKey?>]" value = "checked" <?=(isset($boardField["use"][$languageKey][$columnKey]) ? "checked" : "")?>/>사용
									</label>
								</td>
								<!--필수-->
								<td align="center" class = "<?=$languageKey?>_td">
									<label>
										<input type = "checkbox" name = "reqField[<?=$languageKey?>][<?=$columnKey?>]" value = "checked" <?=(isset($boardField["require"][$languageKey][$columnKey]) ? "checked" : "")?>/>사용
									</label>
								</td>
								<!--필드명-->
								<td align="ta_left field_td" class = "<?=$languageKey?>_td">
									<input type = "text" name = "nameField[<?=$languageKey?>][<?=$columnKey?>]" value="<?=$boardField["name"][$languageKey][$columnKey]?>" />
									<p><?=$boardField["name"][$languageKey][$columnKey]?></p>
								</td>
								<!--변수명(치환태그)-->
								<td align="left" class = "<?=$languageKey?>_td">
									<span class="bbs_tag">

									</span>
								</td>
								<!--형식-->
								<td align="left" class = "<?=$languageKey?>_td">
									<select class = "item_type_<?=$languageKey?>" data-itemname = "<?=$columnKey?>" name = "optionField[<?=$languageKey?>][<?=$columnKey?>][type]" onchange = "select_init(this, '<?=$columnKey?>');">
										<option value = "">
											텍스트
										</option>
										<option value = "radio" <?=$boardOption["type"] == "radio" ? "selected" : "" ?>>
											라디오
										</option>
										<option value = "select" <?=$boardOption["type"] == "select" ? "selected" : "" ?>>
											셀렉트
										</option>
										<option value = "editor" <?=$boardOption["type"] == "editor" ? "selected" : "" ?>>
											에디터
										</option>
										<option value = "file" <?=$boardOption["type"] == "file" ? "selected" : "" ?>>
											파일
										</option>
									</select>

									<a href = "javascript://" id = "btn_option_<?=$languageKey?>_<?=$columnKey?>" class = "btn_mini <?=(in_array($boardOption["type"], array("checkbox", "radio", "select")))  ? "" : "hide"?>" onclick="add_option('<?=$columnKey?>');">
										추가
									</a>
									<select
										class = "file_type_<?=$languageKey?> <?=( (!isset($boardOption["type"]) || in_array($boardOption["type"], array("checkbox", "radio", "select", "editor")))? "hide disabled" : "")?>" data-itemname = "<?=$columnKey?>"
										name = "optionField[<?=$languageKey?>][<?=$columnKey?>][file_type]" onchange = "select_file_type(this, '<?=$columnKey?>');"
										<?=( (!isset($boardOption["type"]) || in_array($boardOption["type"], array("checkbox", "radio", "select", "editor")))? "hide disabled" : "")?>>
										<? foreach($extension as $itemValue){ ?>
											<option value="<?=$itemValue?>" <?=($boardOption["file_type"] == $itemValue ? "selected" : "")?>>
												<?=$itemValue?>
											</option>
										<? } ?>
									</select>
								</td>
								<!--옵션-->
								<td id = "option_<?=$columnKey?>_td" align = "center" class = "<?=$languageKey?>_td">
									<? if(isset($boardOption["item"])) { ?>
										<? $i = 0; ?>
										<? foreach($boardOption["item"] as $itemName => $itemValue){ ?>
											<? $i++; ?>
											<div id = "box_option_<?=$languageKey?>_<?=$columnKey?>_<?=$i?>" class = "box_option_<?=$languageKey?> box_option_<?=$languageKey?>_<?=$columnKey?> box_option_<?=$columnKey?>">
												<input
												type="hidden" name="optionField[<?=$languageKey?>][<?=$columnKey?>][itemName][]" value="<?=$itemName?>"
												style="width:35%;"/>

												<input
												type = "text" name="optionField[<?=$languageKey?>][<?=$columnKey?>][itemValue][]" value = "<?=$itemValue?>"
												style = "width:35%;" />

												<a href = "javascript://"
												class = "btn_mini"
												onclick = "remove_option('<?=$columnKey?>', '<?=$i?>');">
													삭제
												</a>
											</div>
										<? } ?>
									<? } ?>
									<input
									type="text"
									id="image_width"
									name="optionField[<?=$languageKey?>][<?=$columnKey?>][width]"
									class="set_image_<?=$columnKey?> <?=$boardOption["file_type"] == "image" ? "" : "disabled hide"?>"
									style="width:40%;"
									placeholder="가로값 입력"
									value="<?=isset($boardOption["width"])?>"
									/>
									<input
									type="text"
									id="image_height"
									style="width:40%;"
									placeholder="세로값 입력"
									name="optionField[<?=$languageKey?>][<?=$columnKey?>][height]"/>
								</td>
							<? } ?>
						</tr>
					<? } // foreach fieldSet ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>

</div>