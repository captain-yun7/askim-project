<script>
	$(function() {
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	function select_init(ele, field) {
		var select_value = $(ele).find("option:selected").val();
		if(!select_value || select_value == "text") {
			$(".box_option_" + site_language + "_" +field +", #btn_option_" + site_language + "_"+ field).addClass("hide");
			$(".box_option_" + site_language + "_" + field).find(":text").prop("disabled", true).addClass("disabled");
		} else {
			$(".box_option_" + site_language + "_" +field +", #btn_option_" + site_language + "_"+ field).removeClass("hide");
			$(".box_option_" + site_language + "_" + field).find(":text").prop("disabled", false).removeClass("disabled");
		}
	}

	function remove_option(field,  optionNo) {
		$("#box_option_"+ site_language +"_"+ field +"_"+ optionNo).remove();
	}

	function add_option(field) {
		var last_option_no = Number($(".option_"+ site_language +"_"+ field +":last").val()) + 1 || 1;
		var str = '';
		str += '	<div id="box_option_'+ site_language +'_'+ field +'_'+ last_option_no +'" class="box_option_'+ site_language +'_'+ field +' box_option_'+ field +'">';
		str += '		<input type="hidden" class="option_'+ field +'_'+ site_language +'" value="'+ last_option_no +'" />';
		str += '		<input type="hidden" name="optionField['+ field +']['+ site_language +'][itemName][]" value="'+ field +"-"+ last_option_no +'"  />';
		str += '		<input type="text" name="optionField['+ field +']['+ site_language +'][itemValue][]"  />';
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
		$("[name*='nameField["+ language +"]']").removeClass("hide");

		<?php // 옵션 선택언어 토글셋팅 ?>
		$("[class*='box_option").addClass("hide");
		$("[class*='box_option_"+ language +"']").removeClass("hide");
    }

	function field_save(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}
		var is_submit = true;

		$(frm).find(":text:not('.disabled')").filter("[name*='<?=$this->_site_language["default"]?>']").each(function() {
			if(this.value == "") {
				alert("<?=$this->_site_language["support_language"][$this->_site_language["default"]]?>에 입력하지 않는 곳이 있습니다.");
				this.focus();
				is_submit = false;
				return false;
			}
		});

		if(is_submit) {
			frm.submit();
		}
	}
</script>
<div id="contents">
	<div class="main_tit">
		<div><h2>회원필드셋팅<em>* 필수 항목은 사용 체크가 되어있어야 합니다.<?php if($this->_site_language["multilingual"]) : ?><br/>* 다국어 사용시 각 나라별 법령에 기준하여 위배되지 않게 운영하셔야 합니다.<?php endif ?></em></h2></div>
		<div class="btn_right">
			<a href="javascript://" onclick="field_save(document.frm);" class="btn point">저장</a>
		</div>
		<div class="location" style="margin-left:20px;">
			<h4><button type="button" class="info_bullet">URL</button> <?=base_url()?>member/join <a class="btn-sm" href="<?=base_url()?>member/join" target="_blank">바로가기</a></h4>
			<h4><button type="button" class="info_bullet">SKIN</button> /data/skin/<?=$this->_skin?>/member/join.html</h4>
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
					<col width="19%">
					<col width="25%">
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
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>
							<td align="left"><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
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
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>
							<td align="left"><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
							<td align="left"></td>
							<td align="center"></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['etc'] as $value) :?>
						<tr>
							<td align="center">
								<?php if($value == "birth"): ?>
									<label><input type="checkbox" name="useField[<?=$value?>]" value="checked" checked disabled /> 사용</label>
									<input type="hidden" name="useField[<?=$value?>]" value="checked" />
								<?php else : ?>
									<label><input type="checkbox" name="useField[<?=$value?>]" value="checked" <?=isset($memberField["use"][$value]) ? $memberField["use"][$value] : "" ?> /> 사용</label>
								<?php endif; ?>
							</td>
							<td align="center">
								<?php if($value == "birth"): ?>
									<label><input type="checkbox" name="reqField[<?=$value?>]" value="checked" checked disabled /> 필수</label>
									<input type="hidden" name="reqField[<?=$value?>]" value="checked" />
								<?php else : ?>
									<label><input type="checkbox" name="reqField[<?=$value?>]" value="checked" <?=isset($memberField["require"][$value]) ? $memberField["require"][$value] : "" ?> /> 필수</label>
								<?php endif; ?>
							</td>
							<td class="ta_left field_td">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor" && !in_array($value, $arr_ex)) : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?>(<?=$value?>)</p>
							</td>
							<td align="left"><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></td>
							<td align="left">
								<?php if(in_array($value, $readonly)) : // 수정불가 필드처리 ?>
									<select class="item_type" name="typeField[<?=$value?>]" onfocus="this.initialSelect=this.selectedIndex;" onchange="this.selectedIndex=this.initialSelect;">
										<option value="text" <?=$memberField["type"][$value] == "text" ? "selected": ""?>>텍스트</option>
										<!-- <option value="checkbox" <?=$memberField["type"][$value] == "checkbox" ? "selected" : ""?>>체크박스</option> -->
										<option value="radio" <?=$memberField["type"][$value] == "radio" || isset($radio[$value])? "selected" : ""?>>라디오</option>
										<option value="select" <?=$memberField["type"][$value] == "select" ?"selected" : ""?>>셀렉트</option>
										<option value="email" <?=$memberField["type"][$value] == "email" ? "selected" : ""?>>이메일</option>
									</select>
								<?php else : ?>
									<select class="item_type" name="typeField[<?=$value?>]" onchange="select_init(this, '<?=$value?>');" >
										<option value="text" <?=$memberField["type"][$value] == "text" ? "selected": ""?>>텍스트</option>
										<!-- <option value="checkbox" <?=$memberField["type"][$value] == "checkbox" ? "selected" : ""?>>체크박스</option> -->
										<option value="radio" <?=$memberField["type"][$value] == "radio" || isset($radio[$value]) ? "selected" : ""?>>라디오</option>
										<option value="select" <?=$memberField["type"][$value] == "select" ?"selected" : ""?>>셀렉트</option>
										<option value="email" <?=$memberField["type"][$value] == "email" ? "selected" : ""?>>이메일</option>
									</select>
									<a href="javascript://" id="btn_option_<?=$value?>" class="btn_mini <?if(!in_array($memberField["type"][$value], array("checkbox", "radio", "select", "email"))) : echo "hide"; endif; ?>" onclick="add_option('<?=$value?>');" >추가</a>
								<?php endif; ?>
								<?php if($value == "sex"):?><p class="bbs_cuation"><em><b><?=$memberField["name"]["kor"][$value]?></b>은 <b>라디오</b>타입만 사용 가능</em></p><?php endif; ?>
							</td>
							<td id="option_<?=$value?>_td" align="center">
								<?php if(isset($memberField["option"][$value]["item"]) && $memberField["type"][$value] != "text"): ?>
									<?php foreach($memberField["option"][$value]["item"] as $language_key => $language_value) : ?>
										<?php $i = 0; ?>
										<?php foreach($language_value as $itemName => $itemValue) :?>
											<?php $i++; ?>
											<div id="box_option_<?=$language_key?>_<?=$value?>_<?=$i?>" class="box_option_<?=$language_key?>_<?=$value?> box_option_<?=$value?>" style="<?php if($value == "yn_mailling" || $value == "yn_sms" ):?>display:inline-block;width:auto;<?php else :?><?php endif?>">
												<input type="hidden" class="option_<?=$language_key?>_<?=$value?>" value="<?=$i?>" /><input type="hidden" name="optionField[<?=$value?>][<?=$language_key?>][itemName][]" value="<?=$itemName?>" <?=in_array($value, $readonly) ? "readonly" : ""?> /><input type="text" name="optionField[<?=$value?>][<?=$language_key?>][itemValue][]" value="<?=$itemValue?>" style="<?php if($itemValue == "수신동의"):?>width:75px<?php endif?><?php if($itemValue == "수신거부"):?>width:75px<?php endif?>"/>
												<?php if(!in_array($value, $readonly)) : ?><a href="javascript://" class="btn_mini" onclick="remove_option('<?=$value?>', '<?=$i?>');">삭제</a><? endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>