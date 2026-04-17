<script>
	var site_language = "<?=$this->_site_language['default']?>";

	$(function() {
		language_change("<?=$this->_site_language['default']?>");

		$("[name*=useField]:enabled").on("click", function(){
			if($(this).prop("checked") == false){
				var reqField = $(this).closest("tr").find("[name*='reqField["+site_language+"]']");

				if(reqField.length){
					reqField.prop("checked", false);
				}
			}
		});

		$("[name*=reqField]:enabled").on("click", function(){
			if($(this).prop("checked") == true){
				var useField = $(this).closest("tr").find("[name*='useField["+site_language+"]']");

				if(useField.length){
					useField.prop("checked", true);
				}
			}
		});
	});



	function select_init(ele, field) {
		var select_value = $(ele).find("option:selected").val();

		//파일일 경우 셀렉트 박스 추가
		if(select_value == "file") {
			$("select[name='optionField["+site_language+"]["+field+"][file_type]']").prop("disabled", false).removeClass("hide");
			if($("select[name='optionField["+site_language+"]["+field+"][file_type]']:selected").val() == "image"){
				$("input[name='optionField["+site_language+"]["+field+"][width]'], input[name='optionField["+site_language+"]["+field+"][height]']").prop("disabled", false).removeClass("hide disabled");
			}
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
		//2020-02-26 Inbet Matthew 형식을 라디오나 셀렉트 선택 시 옵션이 없다면 추가 하는 로직 추가
		var itemValueCnt = $('input[name="optionField['+site_language+']['+field+'][itemValue][]"]').length;
		if ((select_value == "radio" || select_value == "select") && itemValueCnt == 0){
			add_option(field);
		}
		//Matthew End
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
		str += '		<input type="hidden" name="optionField['+ site_language +']['+ field +'][itemName][]" value="'+ field +"-"+ last_option_no +'" />';
		str += '		<input type="text" class="inq_w35p" name="optionField['+ site_language +']['+ field +'][itemValue][]" />';
		str += '		<a href="javascript://" class="btn_mini" onclick="remove_option(\''+ field +'\', \''+ last_option_no +'\');">삭제</a>';
		str += '	</div>';
		$(str).appendTo("#option_"+ field +"_td:last");
	}

    function language_change(language,obj) {
        site_language = language;
		if(site_language == "kor"){
			$(".except_tr").removeClass("hide");
		}else{
			$('.except_tr').addClass("hide");
		}
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

		//2020-02-26 Inbet Matthew 썸네일, 대표이미지, 상세이미지, 첨부파일의 경우 추가 클래스 언어에 따른 감춤 처리 해제
		$("[class*='item_type_span_"+site_language+"']").removeClass("hide");
		$("[class*='file_type_span_"+site_language+"']").removeClass("hide");
		var fixedColumn = new Array('img1','img2','detail_img','upload_fname');
		for (var i = 0; i < fixedColumn.length; i++){
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][width]']").removeClass("hide");
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][height]']").removeClass("hide");
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][width]']").removeClass("disabled");
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][height]']").removeClass("disabled");
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][width]']").prop("disabled", false);
			$("input[name='optionField["+site_language+"]["+fixedColumn[i]+"][height]']").prop("disabled", false);
		}
		//Matthew End
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
<script type="text/javascript">
$(document).ready(function(){
	$('.tag_view').click(function() {
		var thisa = $(this).parent('.tag_wrap');
		var prts = $('.tag_wrap');
		if( (thisa.is('.on')) ) {
			thisa.removeClass("on");
		} else {
			prts.removeClass("on");
			thisa.addClass("on");
		}
	});
	$(document).click(function(e){
		if (!$(e.target).is('.tag_view, .bbs_tag') ){
			$('.tag_wrap').removeClass('on');
		}
	});
});	
</script>
<div id="contents">
	<div class="main_tit">
		<h2>상품 필드 세팅</h2>
		
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
			<a href="javascript://" onclick="field_save(document.frm);" class="btn point">저장</a>
		</div>
	</div>
	<div class="table_write_info">* 필수 항목은 사용 체크가 되어있어야 합니다.</div>
	
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="register" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="6%">
					<col width="6%">
					<col width="*">
					<col width="20%">
					<col width="31%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">사용</th>
						<th scope="col">필수</th>
						<th scope="col">필드명</th>
						<th scope="col">형식</th>
						<th scope="col">옵션</th>
						<th scope="col">변수</th>
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
							<td align="left"></td>
							<td align="left"></td>
							<td><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['hidden'] as $value) :?>
						<? if(in_array($value, array('img1', 'img2', 'detail_img'))){ ?>
						<tr class="except_tr">
						<? }else{ ?>
						<tr>
						<? } ?>
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
							<td align="left"></td>
							<td align="left"></td>
							<td><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['etc2'] as $value) :?>
						<? if(in_array($value, array('img1', 'img2', 'detail_img'))){ ?>
						<tr class="except_tr">
						<? }else{ ?>
						<tr>
						<? } ?>
							<td align="center">
							<?php foreach($this->_site_language["support_language"] as $language_key => $language_value){ ?>
								<input type="hidden" value="checked" name="useField[<?=$language_key?>][<?=$value?>]"/>
								<input type="hidden" value="checked" name="reqField[<?=$language_key?>][<?=$value?>]"/>
							<? } ?>
								<label>
									<input type="checkbox" value="checked" checked disabled/>
									사용
								</label>
							</td>
							<td align="center">
								<label>
									<input type="checkbox" value="checked" checked disabled/>
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
							<td align="left">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(in_array($value, $readonly)) : // 수정불가 필드처리 ?>
										<?php //2020-02-26 Inbet Matthew 썸네일 대표이미지, 상세이미지 옵션의 경우 텍스트로만 표시하도록 변경 ?>
										<?php if($value == 'detail_img' || $value == 'img1' || $value == 'img2'){ ?>
											<span class="item_type_span_<?=$language_key?>">파일</span>
											<span class="file_type_span_<?=$language_key?>">image</span>
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][type]" value="file">
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" value="image">
										<?php }else if($value == 'upload_fname'){ ?>
											<span class="item_type_span_<?=$language_key?>">파일</span>
											<span class="file_type_span_<?=$language_key?>">document</span>
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][type]" value="file">
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" value="document">
										<?php }else{ ?>
										<?php  //Matthew End ?>
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
										<?php } ?>
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

							<td id="option_<?=$value?>_td" align="left">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(isset($goodsField["option"][$language_key][$value]["item"])): ?>
										<?php $i = 0; ?>
										<?php foreach($goodsField["option"][$language_key][$value]["item"] as $itemName => $itemValue) : ?>
											<?php $i++; ?>
											<div id="box_option_<?=$language_key?>_<?=$value?>_<?=$i?>" class="box_option_<?=$language_key?>_<?=$value?> box_option_<?=$value?>">
												<input type="hidden" class="option_<?=$language_key?>_<?=$value?>" value="<?=$i?>" />
												<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][itemName][]" value="<?=$itemName?>"  <?php if(in_array($value, $readonly)) : echo "readonly"; endif; ?> />
												<input type="text" name="optionField[<?=$language_key?>][<?=$value?>][itemValue][]" value="<?=$itemValue?>" class="inq_w35p" />
												<?php if(!in_array($value, $readonly)) : ?><a href="javascript://" class="btn_mini" onclick="remove_option('<?=$value?>', '<?=$i?>');">삭제</a><? endif; ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
									<? if(!in_array($value, array("upload_fname"))){ ?>
									<input type="text" id="image_width" name="optionField[<?=$language_key?>][<?=$value?>][width]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" class="inq_w40p"placeholder="가로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["width"]) ? $goodsField["option"][$language_key][$value]["width"] : ""?>" <?= $goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />

									<input type="text" id="image_height" name="optionField[<?=$language_key?>][<?=$value?>][height]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" class="inq_w40p" placeholder="세로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["height"]) ? $goodsField["option"][$language_key][$value]["height"] : ""?>" d<?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />
									<? } ?>
								<?php endforeach ?>
								<?php
								 if ( $value == img1 || $value == img2 ) {
									echo "<p class='bbs_txt'>세로값을 비우면, <b>원본 이미지 비율을 유지</b>하고 리사이징됩니다.</p>";
								 }
								 ?>
							</td>
							<td><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>

					<?php endforeach; ?>
					<?php foreach($fieldset['etc'] as $value) :?>
						<? if(in_array($value, array('img1', 'img2', 'detail_img'))){ ?>
						<tr class="except_tr">
						<? }else{ ?>
						<tr>
						<? } ?>
							<td align="center">
								<label>
									<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
										<input type="checkbox" name="useField[<?=$language_key?>][<?=$value?>]" value="checked"
										<?
											if(isset($goodsField["use"][$value]) || isset($goodsField["use"][$language_key][$value])){
												echo "checked ";
											}
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
							<td align="left">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(in_array($value, $readonly)) : // 수정불가 필드처리 ?>
										<?php //2020-02-26 Inbet Matthew 썸네일 대표이미지, 상세이미지 옵션의 경우 텍스트로만 표시하도록 변경 ?>
										<?php if($value == 'detail_img' || $value == 'img1' || $value == 'img2'){ ?>
											<span class="item_type_span_<?=$language_key?>">파일</span>
											<span class="file_type_span_<?=$language_key?>">image</span>
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][type]" value="file">
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" value="image">
										<?php }else if($value == 'upload_fname'){ ?>
											<span class="item_type_span_<?=$language_key?>">파일</span>
											<span class="file_type_span_<?=$language_key?>">document</span>
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][type]" value="file">
											<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][file_type]" value="document">
										<?php }else{ ?>
										<?php  //Matthew End ?>
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
										<?php } ?>
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
							<td id="option_<?=$value?>_td" align="left">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(isset($goodsField["option"][$language_key][$value]["item"])): ?>
										<?php $i = 0; ?>
										<?php foreach($goodsField["option"][$language_key][$value]["item"] as $itemName => $itemValue) : ?>
											<?php $i++; ?>
											<div id="box_option_<?=$language_key?>_<?=$value?>_<?=$i?>" class="box_option_<?=$language_key?>_<?=$value?> box_option_<?=$value?>">
												<input type="hidden" class="option_<?=$language_key?>_<?=$value?>" value="<?=$i?>" />
												<?php if(!in_array($value, $arr_ex)){ ?>
												<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][itemName][]" value="<?=$itemName?>" <?php if(in_array($value, $readonly)) : echo "readonly"; endif; ?> />
												<?php }else { ?>
												<input type="hidden" name="optionField[<?=$language_key?>][<?=$value?>][itemName][]" value="<?=$value?>-<?=$i?>" <?php if(in_array($value, $readonly)) : echo "readonly"; endif; ?> />
												<?php } ?>
												<input type="text" name="optionField[<?=$language_key?>][<?=$value?>][itemValue][]" value="<?=$itemValue?>" class="inq_w35p" />
												<?php if(!in_array($value, $readonly)) : ?><a href="javascript://" class="btn_mini" onclick="remove_option('<?=$value?>', '<?=$i?>');">삭제</a><? endif; ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
									<? if(!in_array($value, array("upload_fname"))){ ?>
									<input type="text" id="image_width" name="optionField[<?=$language_key?>][<?=$value?>][width]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" class="inq_w40p" placeholder="가로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["width"]) ? $goodsField["option"][$language_key][$value]["width"] : ""?>" <?= $goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />

									<input type="text" id="image_height" name="optionField[<?=$language_key?>][<?=$value?>][height]" class="set_image_<?=$value?> <?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled hide"?>" class="inq_w40p" placeholder="세로값 입력" value="<?=isset($goodsField["option"][$language_key][$value]["height"]) ? $goodsField["option"][$language_key][$value]["height"] : ""?>" d<?=$goodsField["option"][$language_key][$value]["file_type"] == "image" ? "" : "disabled"?> />
									<? } ?>
								<?php endforeach ?>
								<?php
								 if ( $value == img1 || $value == img2 ) {
									echo "<p class='bbs_txt'>세로값을 비우면, <b>원본 이미지 비율을 유지</b>하고 리사이징됩니다.</p>";
								 }
								 ?>
							</td>
							<td><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{goodsField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>

					<?php endforeach; ?>

				</tbody>
			</table>
		</div>
	<?=form_close();?>
	<div class="terms_privecy_box">
		<dl>
			<dt>- 허용 첨부파일 확장자 리스트</dt>
			<dd>
			Document : doc, docx, hwp, txt, pdf, ppt, pptx, show, zip, 7z<br/>Excel : xls, xlsx, cell<br>Video : mp4, mpeg4<br>Image : rtf, jpeg, jpg, png, gif, bmp
			</dd>
		</dl>
	</div>
</div>