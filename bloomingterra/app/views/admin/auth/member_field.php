<script>
	$(function() {
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	function select_init(ele, field) {
		var select_value = $(ele).find("option:selected").val();
		if(!select_value || select_value == "text") {
			$(".box_option_" + site_language + "_" +field+"_email_type").addClass("hide");
			$(".box_option_" + site_language + "_" +field+"_email_type").find("input").prop("disabled", true).addClass("disabled");
			$(".box_option_" + site_language + "_" +field +", #btn_option_" + site_language + "_"+ field).addClass("hide");
			$(".box_option_" + site_language + "_" + field).find(":text").prop("disabled", true).addClass("disabled");
		} else {
			if (select_value == "email"){
				$(".box_option_" + site_language + "_" +field+"_email_type").remove();
				$(".box_option_" + site_language + "_" +field +", #btn_option_" + site_language + "_"+ field).addClass("hide");
				$(".box_option_" + site_language + "_" + field).find("input").prop("disabled", true).addClass("disabled");
				add_email_address_option(field);
			}else{
				$(".box_option_" + site_language + "_" +field+"_email_type").addClass("hide");
				$(".box_option_" + site_language + "_" +field+"_email_type").find("input").prop("disabled", true).addClass("disabled");
				$(".box_option_" + site_language + "_" +field +", #btn_option_" + site_language + "_"+ field).removeClass("hide");
				$(".box_option_" + site_language + "_" + field).find("input").prop("disabled", false).removeClass("disabled");
			}
		}

		//2020-02-26 Inbet Matthew 형식을 라디오나 셀렉트 선택 시 옵션이 없다면 추가 하는 로직 추가
			var itemValueCnt = $(".box_option_" + site_language + "_" +field).find($('input[name="optionField['+field+']['+site_language+'][itemValue][]"]')).length;
			if ((select_value == "radio" || select_value == "select" || select_value == "checkbox") && itemValueCnt == 0){
				add_option(field);
			}
		//Matthew End
	}

	function remove_option(field,  optionNo) {
		$("#box_option_"+ site_language +"_"+ field +"_"+ optionNo).remove();
	}

	function add_email_address_option(field) {
		//var last_option_no = Number($(".option_"+ site_language +"_"+ field +"_email_type:last").val()) + 1 || 1;
		var str = '';
		var hideFl = '';

		if($('[name="optionField['+ field +']['+ site_language +'][itemValue][]"]').length == 0){
			hideFl = 'invisible';
		}
		<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
			<?php foreach($emailDefaultAddr[$language_key] as $key => $value) :?>
				if (site_language == '<?=$language_key?>'){
					str += '	<div id="box_option_'+ site_language +'_'+ field +'_'+ (Number("<?=$key?>")+1) +'" class="box_options box_option_'+ site_language +'_'+ field +'_email_type box_option_'+ field +' box_option_email">';
					str += '		<input type="hidden" class="option_'+ site_language +'_'+ field +'_email_type" value="'+ (Number("<?=$key?>")+1) +'" />';
					str += '		<input type="hidden" name="optionField['+ field +']['+ site_language +'][itemName][]" value="'+ field +"-"+ (Number("<?=$key?>")+1) +'"  />';
					str += '		<input type="text" name="optionField['+ field +']['+ site_language +'][itemValue][]"  value="<?=$value?>" readonly /> ';
					//str += '		<a href="javascript://" class="btn_mini  '+hideFl+'" onclick="remove_option(\''+ field +'\', \''+ (Number("<?=$key?>")+1) +'\');">삭제</a>';
					str += '	</div>';
				}
			<? endforeach; ?>
		<? endforeach; ?>
		$(str).appendTo("#option_"+ field +"_td:last");
	}

	function add_option(field) {
		var last_option_no = Number($(".option_"+ site_language +"_"+ field +":last").val()) + 1 || 1;
		var str = '';
		var hideFl = '';

		if($('[name="optionField['+ field +']['+ site_language +'][itemValue][]"]').length == 0){
			hideFl = 'invisible';
		}

		str += '	<div id="box_option_'+ site_language +'_'+ field +'_'+ last_option_no +'" class="box_options box_option_'+ site_language +'_'+ field +' box_option_'+ field +'">';
		str += '		<input type="hidden" class="option_'+ site_language +'_'+ field +'" value="'+ last_option_no +'" />';
		str += '		<input type="hidden" name="optionField['+ field +']['+ site_language +'][itemName][]" value="'+ field +"-"+ last_option_no +'"  />';
		str += '		<input type="text" name="optionField['+ field +']['+ site_language +'][itemValue][]"  />';
		str += '		<a href="javascript://" class="btn_mini  '+hideFl+'" onclick="remove_option(\''+ field +'\', \''+ last_option_no +'\');">삭제</a>';
		str += '	</div>';
		$(str).appendTo("#option_"+ field +"_td:last");
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
		$("[name*='nameField").addClass("hide");
		$("[name*='nameField["+ language +"]']").removeClass("hide");

		<?php // 옵션 선택언어 토글셋팅 ?>
		$("[class*='box_option").addClass("hide");
		$("[class*='box_option_"+ language +"']").removeClass("hide");

		<?php // 사용 선택언어 토글셋팅 ?>
		$("[class*='useField").addClass("hide");
		$("[class*='useField["+ language +"]']").removeClass("hide");

		<?php // 필수 선택언어 토글셋팅 ?>
		$("[class*='reqField").addClass("hide");
		$("[class*='reqField["+ language +"]']").removeClass("hide");

		<?php // 형식 선택언어 토글셋팅 ?>
		$("[name*='typeField").addClass("hide");
		$("[name*='typeField["+ language +"]']").removeClass("hide");

		<?php // 버튼 선택언어 토글셋팅 ?>
		$("[id*='btn_option']").addClass("hide");
		$("[name*='typeField["+language+"]']").each(function(idx, item){
			select_init(item, $(item).data("itemname"));
		});

		//2020-02-19 Inbet Matthew 필수 체크박스 클릭시 왼쪽 사용함 체크박스도 동시에 체크되도록 변경
			$("[name*='reqField").each(function(i){
				$(this).change(function(e){
					if($(this).is(":checked")){
						var reqFieldName = $(this).attr('name');
						$('input[name="'+reqFieldName.replace('reqField','useField')+'"]').prop('checked', true);
					}
				});
			});
		//2020-02-20 Inbet Matthew 자동가입방지문구 사용함 변화에 따라 필수 체크박스 동기화
			$('input[name="useField['+site_language+'][auto_regist_prevention_text]"]').change(function(e){
				if($('input[name="useField['+site_language+'][auto_regist_prevention_text]"]').is(":checked")){
					var useFieldName = $(this).attr('name');
					$('input[name="'+useFieldName.replace('useField','reqField')+'"]').prop('checked', true);
				}else{
					var useFieldName = $(this).attr('name');
					$('input[name="'+useFieldName.replace('useField','reqField')+'"]').prop('checked', false);
				}
			});
		//Matthew End
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
		<div><h2>회원 필드세팅<em></em></h2></div>
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
	<div class="table_write_info"><?php if($this->_site_language["multilingual"]) : ?>* 다국어 사용시 각 나라별 법령에 기준하여 위배되지 않게 운영하셔야 합니다.<?php endif ?></div>

	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="register" />
		<div class="table_write table_va_top">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="6%">
					<col width="6%">
					<col width="35%">
					<col width="18%">
					<col width="25%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">사용</th>
						<th scope="col">필수</th>
						<th scope="col">필드명</th>
						<th scope="col">형식</th>
						<th scope="col">옵션</th>
						<th scope="col">변수명</th>
					</tr>
				</head>
				<tbody id='divList'>
					<?php foreach($fieldset['default'] as $value) :?>
						<tr>
							<td align="center" class="td_pa_none"><label><input type="checkbox" name="useField[<?=$value?>]" checked disabled /> 사용</label></td>
							<td align="center" class="td_pa_none"><label><input type="checkbox" name="reqField[<?=$value?>]" checked disabled /> 필수</label></td>
							<td class="ta_left field_td member_field">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor") : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?><!-- (<?=$value?>)--></p>
							</td>
							<td align="left"></td>
							<td align="left"></td>
							<td align="left"><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>
					<?php endforeach; ?>

					<?php foreach($fieldset['hidden'] as $value) :?>
						<tr>
							<td align="center" class="td_pa_none"><label><input type="checkbox" checked disabled /> 사용</label></td>
							<td align="center" class="td_pa_none"><label><input type="checkbox" checked disabled /> 필수</label></td>
							<td class="ta_left field_td member_field">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor") : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?><!-- (<?=$value?>)--></p>
							</td>
							<td align="left"></td>
							<td align="left"></td>
							<td align="left"><div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></div></td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($fieldset['etc'] as $value) :?>
						<tr>
							<?php foreach($this->_site_language["support_language"] as $language_key => $language_value): ?>
								<td align="center" class="td_pa_none useField[<?=$language_key?>]">
									<?php if($value == "birth" || $value == "email"): ?>
										<label><input type="checkbox" name="useField[<?=$language_key?>][<?=$value?>]" value="checked" checked disabled /> 사용</label>
										<input type="hidden" name="useField[<?=$language_key?>][<?=$value?>]" value="checked" />
									<?php else : ?>
										<label><input type="checkbox" name="useField[<?=$language_key?>][<?=$value?>]" value="checked"
										<? if(isset($memberField["use"][$language_key][$value])){ ?>
											<?=$memberField["use"][$language_key][$value]?>
										<? }else if(isset($memberField["require"][$language_key][$value])){ ?>
											<?=$memberField["require"][$language_key][$value]?>
										<? } ?>
										/> 사용</label>
									<?php endif; ?>
								</td>
								<td align="center" class="td_pa_none reqField[<?=$language_key?>]">
									<?php if($value == "birth" || $value == "email"): ?>
										<label><input type="checkbox" name="reqField[<?=$language_key?>][<?=$value?>]" value="checked" checked disabled /> 필수</label>
										<input type="hidden" name="reqField[<?=$language_key?>][<?=$value?>]" value="checked" />
									<?php else : ?>
										<label><input type="checkbox" name="reqField[<?=$language_key?>][<?=$value?>]" value="checked"
										<? if(isset($memberField["require"][$language_key][$value])){ ?>
											<?=$memberField["require"][$language_key][$value]?>
										<? }else if(isset($memberField["require"][$value])){ ?>
											<?=$memberField["require"][$value]?>
										<? } ?>
										<?php if($value == 'auto_regist_prevention_text'){ echo ('disabled');/*2020-02-20 Inbet Matthes 자동가입방지문구는 무조건 사용하면 필수이도록 함*/ }?>
										/> 필수</label>
									<?php endif; ?>
								</td>
							<?php endforeach ?>
							<td class="ta_left field_td member_field">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if($language_key == "kor" && !in_array($value, $arr_ex)) : // 한글은 수정안되도록 (수정되도 상관은업음)?>
										<input type="hidden" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php else :?>
										<input type="text" name="nameField[<?=$language_key?>][<?=$value?>]" value="<?=$memberField["name"][$language_key][$value]?>" />
									<?php endif ?>
								<?php endforeach ?>
								<p><?=$memberField["name"]["kor"][$value]?><!--(<?=$value?>)--></p>
							</td>
							<td align="left">
								<?php if($value == "sex" || $value == "birth" || $value == "email" || $value == "zip" || $value == "mobile" || $value == "fax" || $value == "address" || $value == "address2" || $value == "yn_sms" || $value == "yn_mailling" ):?>
								<div class="td_pinup_select">
								<?php endif; ?>
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) :?>
									<?php if(in_array($value, $readonly)) : // 수정불가 필드처리 ?>
										<select class="item_type" name="typeField[<?=$language_key?>][<?=$value?>]" onfocus="this.initialSelect=this.selectedIndex;" onchange="this.selectedIndex=this.initialSelect;" data-itemname="<?=$value?>">
											<option value="text" <?=$memberField["type"][$language_key][$value] == "text" ? "selected": ""?>>텍스트</option>
											<option value="radio" <?=$memberField["type"][$language_key][$value] == "radio" || isset($radio[$value])? "selected" : ""?>>라디오</option>
											<option value="select" <?=$memberField["type"][$language_key][$value] == "select" ?"selected" : ""?>>셀렉트</option>
											<option value="email" <?=$memberField["type"][$language_key][$value] == "email" ? "selected" : ""?>>이메일</option>
										</select>
									<?php else : ?>
										<?php if($value == "auto_regist_prevention_text"){ continue; } //2020-02-20 Inbet Matthew 자동가입장지문구 사용 여부는 필드 타입 설정이 필요없기에 제외?>
										<select class="item_type" name="typeField[<?=$language_key?>][<?=$value?>]" onchange="select_init(this, '<?=$value?>');" data-itemname="<?=$value?>">
											<option value="text" <?=$memberField["type"][$language_key][$value] == "text" ? "selected": ""?>>텍스트</option>
											<option value="radio" <?=$memberField["type"][$language_key][$value] == "radio" || isset($radio[$value]) ? "selected" : ""?>>라디오</option>
											<option value="select" <?=$memberField["type"][$language_key][$value] == "select" ?"selected" : ""?>>셀렉트</option>
											<option value="email" <?=$memberField["type"][$language_key][$value] == "email" ? "selected" : ""?>>이메일</option>
										</select>
										<a href="javascript://" id="btn_option_<?=$language_key?>_<?=$value?>" class="btn_mini <?if(!in_array($memberField["type"][$value], array("checkbox", "radio", "select", "email")) || $this->_site_language['default'] != $language_key) : echo "hide"; endif; ?>" onclick="add_option('<?=$value?>');" >옵션 추가</a>
									<?php endif; ?>
								<? endforeach ?>
								<?php if($value == "sex" || $value == "birth" || $value == "email" || $value == "zip" || $value == "mobile" || $value == "fax" || $value == "address" || $value == "address2" || $value == "yn_sms" || $value == "yn_mailling" ):?>
								</div>
								<?php endif; ?>
							</td>
							<td class="options <?php if($value == "email"):?>option_mails<?php endif; ?>" id="option_<?=$value?>_td" align="left">
								<?php if(isset($memberField["option"][$value]["item"]) && $memberField["type"][$value] != "text"): ?>
									<?php foreach($memberField["option"][$value]["item"] as $language_key => $language_value) : ?>
										<?php $i = 0; ?>
										<?php foreach($language_value as $itemName => $itemValue) :?>
											<?php $i++; ?>
											<div id="box_option_<?=$language_key?>_<?=$value?>_<?=$i?>" class="box_options box_option_<?=$language_key?>_<?=$value?><?php if($memberField["type"][$language_key][$value] == 'email'): echo"_email_type"; endif; ?> box_option_<?=$value?>" style="<?php if($value == "yn_mailling" || $value == "yn_sms" ):?>display:inline-block;width:auto;<?php else :?><?php endif?>">
												<input type="hidden" class="option_<?=$language_key?>_<?=$value?>" value="<?=$i?>" />
												<?php if(!in_array($value, $arr_ex)){ ?>
												<input type="hidden" name="optionField[<?=$value?>][<?=$language_key?>][itemName][]" value="<?=$itemName?>" <?=in_array($value, $readonly) ? "readonly" : ""?> />
												<?php }else { ?>
												<input type="hidden" name="optionField[<?=$value?>][<?=$language_key?>][itemName][]" value="<?=$value?>-<?=$i?>" <?=in_array($value, $readonly) ? "readonly" : ""?> />
												<?php } ?>
												<input type="text" name="optionField[<?=$value?>][<?=$language_key?>][itemValue][]" value="<?=$itemValue?>" style="<?php if($itemValue == "수신동의"):?>width:75px<?php endif?><?php if($itemValue == "수신거부"):?>width:75px<?php endif?>" <?=$value == 'email' ? "readonly" : ""?> />
												<?
												if(COUNT($language_value) > 1 && array_keys($language_value)[0] != $itemName){
													$hideFl = '';
												}else {
													$hideFl = 'invisible';
												}
												?>
												<?php if(!in_array($value, $readonly)) { ?>
													<a href="javascript://" class="btn_mini <?=$hideFl?>" onclick="remove_option('<?=$value?>', '<?=$i?>');">삭제</a>
												<? } ?>
											</div>
										<?php endforeach; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if($value == "sex" || $value == "yn_sms" || $value == "yn_mailling" ):?><p class="bbs_cuation"><em><b>라디오</b>타입만 사용 가능</em></p><?php endif; ?>
								<?php if($value == "birth" || $value == "zip" || $value == "address" || $value == "address2" || $value == "mobile" || $value == "fax"):?><p class="bbs_cuation"><em><b>텍스트</b>타입만 사용 가능</em></p><?php endif; ?>
								<?php if($value == "email"):?><p class="bbs_cuation"><em><b>이메일</b>타입만 사용 가능</em></p><?php endif; ?>
							</td>
							<td align="left">
								<div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{memberField['name'][cfg_site['language']]['<?=$value?>']}</span></div>
							</td>
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>



