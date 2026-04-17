<script>
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				'country_info[mark][]' : {required : true},
				'country_info[code][]' : {required : true},
				<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
					<?php if($language_key == 'kor'): continue; endif; ?>
					'country_info[name][<?=$language_key?>][]' : {required : true},
				<?php endforeach; ?>
			}, messages : {
				'country_info[mark][]' : {required : "표기명을 등록해주세요."},
				'country_info[code][]' : {required : "국가코드를 등록해주세요."},
				<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
					<?php if($language_key == 'kor'): continue; endif; ?>
					'country_info[name][<?=$language_key?>][]' : {required : "국가명(<?=$language_value?>)을 등록해주세요."},
				<?php endforeach; ?>
			},invalidHandler: function(form, validator){
				var errors = validator.numberOfInvalids();
				if (errors) {
					validator.errorList[0].element.focus();
				}
			},
		});
	});

	$.validator.prototype.checkForm = function() {
		this.prepareForm();
		for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
			if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
				for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
					this.check( this.findByName( elements[i].name )[cnt] );
				}
			} else {
				this.check( elements[i] );
			}
		}
		return this.valid();
	};

	function fieldSave(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}

		if(!$(frm).valid()){
			return false;
		}

		frm.submit();
	}

	function addCountry(){
		let lastNo = Number($('#divList').find('tr:last').attr('name').split('_')[1]) + 1;
		let htmlStr = '';
		htmlStr += '<tr name="tr_'+lastNo+'">';
		htmlStr += '	<td align="center">';
		htmlStr +=			lastNo+1;
		htmlStr += '	</td>';
		htmlStr += '	<td align="center" class="input_w33p">';
		<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
			<?php if($language_key == 'kor'): continue; endif; ?>
			htmlStr += '		<input type="text" name="country_info[name][<?=$language_key?>][]" value="" placeholder="<?=$language_value?> 국가명 입력" />';
		<?php endforeach; ?>
		htmlStr += '	</td>';
		htmlStr += '	<td align="center" class="input_w100p">';
		htmlStr += '		<input type="text" name="country_info[mark][]" value="" placeholder="국가의 표기명 입력" />';
		htmlStr += '	</td>';
		htmlStr += '	<td align="center" class="input_w100p">';
		htmlStr += '		<input type="text" name="country_info[code][]" value="" placeholder="국가 코드 입력" />';
		htmlStr += '	</td>';
		htmlStr += '	<td align="right">';
		htmlStr += '		<a href="javascript://" name="deleteBtn" class="btn_mini" onclick="deleteCountry('+lastNo+');">삭제</a>';
		htmlStr += '	</td>';
		htmlStr += '</tr>';

		$('#divList').append(htmlStr);
		$('input[name="country_info[name][eng][]"]:last').focus();
	}

	function deleteCountry(index){
		$('tr[name="tr_'+index+'"]').remove();
	}
	
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="main_tit">
			<h2>국가 정보 설정</h2>
			<div class="btn_right">
				<?php if($key == 0) :?>
				<a href="javascript://" name="addBtn" class="btn point new_plus" onclick="addCountry();">+ 국가 추가</a>
				<?php endif; ?>
				<a href="javascript://" onclick="fieldSave(document.frm);" class="btn point">저장</a>
			</div>
		</div>

		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col style="width:36px;">
					<col style="width:*">
					<col style="width:200px;">
					<col style="width:110px;">
					<col style="width:140px;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">번호</th>
						<th scope="col">국가명(
						<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
							<?php if($language_key == 'kor'): continue; endif; ?>
							<?=trim($language_value)?><?php if($language_key != 'jpn'): ?>,<?php endif; ?>
						<?php endforeach; ?>
						)</th>
						<th scope="col">표기명</th>
						<th scope="col">국가코드</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody id='divList'>
					<?php foreach($default_country_Info as $key => $value) : ?>
						<tr name="tr_<?=$key?>">
							<td align="center">
								<?=$key+1?>
							</td>
							<td align="center" class="input_w33p" name="nameField_<?=$key?>">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
									<?php if($language_key == 'kor'): continue; endif; ?>
									<input type="text" name="default_country_Info[name][<?=$language_key?>][]" value="<?=$value['name'][$language_key]?>"readonly/>
								<?php endforeach; ?>
							</td>
							<td align="center" class="input_w100p" name="markField_<?=$key?>">
								<input type="text" name="default_country_Info[mark][]" value="<?=$value['mark']?>" readonly/>
							</td>
							<td align="center" class="input_w100p" name="codeField_<?=$key?>">
								<input type="text" name="default_country_Info[code][]" value="<?=$value['code']?>" readonly/>
							</td>
							<td align="right" class="btn_no_m" name="btnField_<?=$key?>">
							</td>
						</tr>
					<?php endforeach; ?>
					<?php foreach($country_info as $key => $value) : ?>
						<tr name="tr_<?=count($default_country_Info)+$key?>">
							<td align="center">
								<?=count($default_country_Info)+$key+1?>
							</td>
							<td align="center" class="input_w33p" name="nameField_<?=count($default_country_Info)+$key?>">
								<?php foreach($this->_site_language["support_language"] as $language_key => $language_value) : ?>
									<?php if($language_key == 'kor'): continue; endif; ?>
									<input type="text" name="country_info[name][<?=$language_key?>][]" value="<?=$value['name'][$language_key]?>" placeholder="국가명을 입력해주세요(<?=$language_value?>)" readonly/>
								<?php endforeach; ?>
							</td>
							<td align="center" class="input_w100p" name="markField_<?=count($default_country_Info)+$key?>">
								<input type="text" name="country_info[mark][]" value="<?=$value['mark']?>" placeholder="국가의 표기명을 입력해주세요" readonly/>
							</td>
							<td align="center" class="input_w100p" name="codeField_<?=count($default_country_Info)+$key?>">
								<input type="text" name="country_info[code][]" value="<?=$value['code']?>" placeholder="국가의 코드를 입력해주세요" readonly/>
							</td>
							<td align="right" class="btn_no_m" name="btnField_<?=count($default_country_Info)+$key?>">
								<a href="javascript://" name="deleteBtn" class="btn_mini" onclick="deleteCountry(<?=count($default_country_Info)+$key?>);">삭제</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>