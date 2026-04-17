<script>
	var site_language = "<?=$this->_site_language['default']?>";

	$(function() {
		language_change("<?=$this->_site_language['default']?>");
		//2020-02-24 Inbet Matthew 최초 게시판 만들 시에 자주 사용하는 항목 자동 체크
		<?php if(!$this->input->get("code", true)) : ?>
			$('#board_type-1').prop('checked', true);
			$('#yn_display_list-y').prop('checked', true);
			$('#tree-y').prop('checked', true);
			$('#comment-y').prop('checked', true);
			$('#files-n').prop('checked', true);
			$('#yn_email-n').prop('checked', true);
			$('#yn_mobile-n').prop('checked', true);
			$('#secret-2').prop('checked', true);
			$('[name=extraFl][value="n"]').prop('checked', true);
			$('[name=read_auth]').val(0).prop('selected', true);
			$('[name=write_auth]').val(1).prop('selected', true);
			$('[name=anwrite_auth]').val(1).prop('selected', true);
			$('[name=comment_auth]').val(1).prop('selected', true);
			$('#preface-2').prop('checked', true);
			$('#thumbnail-n').prop('checked', true);
			$('#yn_video-n').prop('checked', true);
			$('[name=roundpage]').val(12);

		<?php endif ?>
		//Matthew End

		if($('[name=extraFl]:checked').val() == 'n') {
			$('.extra_tr, .extra_p').addClass('hide');
		}else{
			$('.extra_tr, .extra_p').removeClass('hide');
		}

		set_change_yn($("[name='board_type']:checked").length ? $("[name='board_type']:checked") : $("[name='board_type']:first"));
		set_change_yn($("[name='yn_send_mail']:checked").length ? $("[name='yn_send_mail']:checked") : $("[name='yn_send_mail']:last"));
		set_change_yn($("[name='tree']:checked").length ? $("[name='tree']:checked") : $("[name='tree']:last"));
		set_change_yn($("[name='comment']:checked").length ? $("[name='comment']:checked") : $("[name='comment']:last"));
		set_change_yn($("[name='files']:checked").length ? $("[name='files']:checked") : $("[name='files']:last"));
		set_change_yn($("[name='thumbnail']:checked").length ? $("[name='thumbnail']:checked") : $("[name='thumbnail']:last"));

        // @TODO 이후 입력값 수정 필요
        set_change_yn($("[name='yn_preface']:checked").length ? $("[name='yn_preface']:checked") : $("[name='yn_preface']:last"));

		$('[name=extraFl]').on('click change', function(){
			if($('[name=extraFl]:checked').val() == 'n') {
				$('.extra_tr, .extra_p').addClass('hide');
			}else{
				$('.extra_tr, .extra_p').removeClass('hide');
			}
		});

		$('[name*=useField]').on('click', function(){
			if($(this).prop("checked") == false){
				$(this).closest('tr').find('[name*="reqField[' + site_language + ']"]').prop('checked', false);
			}
		});

        // 추가필드 필수 선택시 자동으로 사용 체크박스도 체크 처리 20200424 Jonas
		$('[name*=reqField]').on('click', function(){
			if($(this).prop("checked") == true){
				$(this).closest('tr').find('[name*="useField[' + site_language + ']"]').prop('checked', true);
			}
		});

		$("form[name='frm']").validate({
			submitHandler : function() {
				if($("[name=extraFl]:checked").val() == 'y') { // 추가필드 사용 시
					var chkField = true;
					var extra_file_type_field_cnt = 0;
					<? if($this->_site_language["multilingual"]){ ?>
						<? foreach($this->_site_language["support_language"] as $languageKey => $languageVal){ ?>
							$("form[name='frm'] .extra_tr").find(":text:not('.disabled, #image_width, #image_height')").filter("[name*='<?=$languageKey?>']").each(function(){
								if($(this).val() == "" && $(this).closest("tr").find(".<?=$languageKey?>_td [name*=useField]").prop("checked") == true){
									alert("<?=$languageVal?>에 입력하지 않은 곳이 있습니다.");
									chkField = false;
									return false;
								}
								if ($(this).closest("tr").find(".<?=$languageKey?>_td [name*=useField]").prop("checked") == true && $(this).closest("tr").find(".<?=$languageKey?>_td [name*=type]").eq(0).val() == 'file'){
									extra_file_type_field_cnt++;
								}
							});

						<? } ?>
					<? }else{ ?>
						$("form[name='frm'] .extra_tr").find(":text:not('.disabled, #image_width, #image_height')").filter("[name*='kor']").each(function(){
							if($(this).val() == "" && $(this).closest("tr").find(".kor_td [name*=useField]").prop("checked") == true){
								alert("<?=$this->_site_language["support_language"]["kor"]?>에 입력하지 않은 곳이 있습니다.");
								chkField = false;
								return false;
							}
							if ($(this).closest("tr").find(".kor_td [name*=useField]").prop("checked") == true && $(this).closest("tr").find(".kor_td [name*=type]").eq(0).val() == 'file'){
								extra_file_type_field_cnt++;
							}
						});
					<? } ?>

					if (extra_file_type_field_cnt >= 1){
						if ($("#extra_file_size").val() == '' || $("#extra_file_size").val() == undefined || $("#extra_file_size").val() == 0){
							alert("파일 형식의 추가필드 사용이 체크가 되있을 시 반드시 업로드 용량 제한을 입력하셔야 합니다.");
							$("#extra_file_size").focus();
							return false;
						}else{
							var regexp = /^[0-9]*$/
							if( !regexp.test($("#extra_file_size").val()) ) {
								alert("추가 필드 업로드 용량 제한은 숫자만 입력하실 수 있습니다.");
								$("#extra_file_size").focus();
								return false;
							}
						}
					}

					if(chkField == false){
						return false;
					}
				}

				if ($('input[name="code"]').val() == 'file' || $('input[name="code"]').val() == 'comment' || $('input[name="code"]').val() == 'manage' || $('input[name="code"]').val() == 'global'){
					alert("게시판 아이디로 사용이 불가능한 문자열(manage, global, file, comment)을 사용하셨습니다. 다른 문자열로 게시판 아이디를 생성해주세요.");
					return false;
				}
				return true;
			},
			rules : {
				code : {required : true, rangelength : [2, 20], onlyNumEngValid : true},
                <?php if($this->_site_language["multilingual"]) : ?>
                    <?php foreach($this->_site_language["set_language"] as $key => $value) :?>
				        'name[<?=$key?>]' : {required : true, rangelength : [2, 50]},
                    <?php endforeach ?>
                <?php else : ?>
				    'name[kor]' : {required : true, rangelength : [2, 50]},
                <?php endif ?>
				board_type : {required : true},
				yn_send_mail : {required : {depends : function(){return $("[name='board_type'][value='2']").is(":checked")}}},
				mail_form : {required : {depends : function(){return $("[name='yn_send_mail'][value='y']").is(":checked")}}},
				yn_display_list : {required : true},
				yn_preface : {required : true},
				preface : {required : {depends : function(){return $("[name='yn_preface'][value='y']").is(":checked")}}},
				read_auth : {required : true, number : true},
				write_auth : {required : true, number : true},
				tree : {required : true},
				anwrite_auth : {required : {depends : function(){return $("[name='tree'][value='y']").is(":checked")}}, number : true},
				comment : {required : true},
				comment_auth : {required : {depends : function(){return $("[name='comment'][value='y']").is(":checked")}}, number : true},
				files : {required : true},
				file_count : {required : {depends : function(){return $("[name='files'][value='y']").is(":checked")}}},
				filesize : {required : {depends : function(){return $("[name='files'][value='y']").is(":checked")}}, number : true},
				yn_email : {required : true},
				yn_mobile : {required : true},
				yn_video : {required : true},
				roundpage : {required : true, number : true},
				sort_type : {required : true},
				skin_type : {required : true},
				thumbnail : {required : true},
				thumbnail_count : {required : {depends : function(){return $("[name='thumbnail'][value='y']").is(":checked")}}},
				secret : {required : true},
				extraFieldInfo : {required : true}
			}, messages : {
				code : {required : "게시판코드를 입력해주세요.", rangelength : $.validator.format("게시판코드는 {0}~{1}자입니다."), onlyNumEngValid : "게시판코드는 영어, 숫자만 사용 가능합니다."},
                <?php if($this->_site_language["multilingual"]) : ?>
                    <?php foreach($this->_site_language["set_language"] as $key => $value) :?>
                        'name[<?=$key?>]' : {required : "<?=$value?> 게시판명을 입력해주세요.", rangelength : $.validator.format("게시판명은 {0}~{1}자입니다.")},
                    <?php endforeach ?>
                <?php else : ?>
                    'name[kor]' : {required : "국문 게시판명을 입력해주세요.", rangelength : $.validator.format("게시판명은 {0}~{1}자입니다.")},
                <?php endif ?>
				board_type : {required : "게시판 유형을 선택해주세요."},
				yn_send_mail : {required : "답변 메일발송을 선택해주세요."},
				mail_form : {required : "메일폼을 선택해주세요."},
				yn_display_list : {required : "리스트 노출을 선택해주세요"},
				yn_preface : {required : "말머리 사용 여부를 선택해주세요"},
				preface : {required : "말머리를 입력해주세요."},
				read_auth : {required : "글읽기 권한을 선택해주세요.", number : "숫자만 입력가능합니다."},
				write_auth : {required : "글쓰기 권한을 선택해주세요.", number : "숫자만 입력가능합니다."},
				tree : {required : "답변쓰기 여부를 선택해주세요."},
				anwrite_auth : {required : "답변쓰기 권한을 선택해주세요.", number : "숫자만 입력가능합니다."},
				comment : {required : "댓글쓰기 여부를 선택해주세요."},
				comment_auth : {required : "댓글쓰기 권한을 선택해주세요.", number : "숫자만 입력가능합니다."},
				files : {required : "첨부파일 등록여부를 선택해주세요."},
				file_count : {required : "첨부파일 수량을 선택해주세요.", number : "숫자만 입력가능합니다."},
				filesize : {required : "첨부파일 용량제한을 입력해주세요.", number : "숫자만 입력가능합니다."},
				yn_email : {required : "이메일 작성을 선택해주세요."},
				yn_mobile : {required : "휴대폰 작성을 선택해주세요."},
				yn_video : {required : "동영상주소 작성을 선택해주세요."},
				roundpage : {required : "페이지당 글 갯수를 입력해주세요.", number : "숫자만 입력가능합니다."},
				sort_type : {required : "게시판 정렬을 선택해주세요."},
				skin_type : {required : "게시판 스킨을 선택해주세요."},
				thumbnail : {required : "썸네일 등록여부를 선택해주세요."},
				thumbnail_count : {required : "썸네일 수량을 선택해주세요.", number : "숫자만 입력가능합니다."},
				secret : {required : "게시판 비밀글설정을 선택해주세요."}
			}
		});

		$("select[class^='item_type_']").on("change blur", function() {
			var nm = $(this).closest("td").prev("td").children("input[name^='nameField']").val().split("(");
			if($(this).val() == "file") {
				$(this).closest("td").prev("td").children("input[name^='nameField']").val(nm[0] + "(" + $(this).siblings("select[class^='file_type_']").val() + ")");
			} else {
				$(this).closest("td").prev("td").children("input[name^='nameField']").val(nm[0]);
			}
		});

		$("select[class^='file_type_']").on("change", function() {
			var nm = $(this).closest("td").prev("td").children("input[name^='nameField']").val().split("(");
			$(this).closest("td").prev("td").children("input[name^='nameField']").val(nm[0] + "(" + $(this).val() + ")");
		});
	});

	function set_change_yn(element) {
		if($(element).data("active")) {
			$("."+ $(element).data("target-class")).removeClass("hide").find("input, select").prop("disabled", false).trigger('change');;
		} else {
			$("."+ $(element).data("target-class")).addClass("hide").find("input, select").prop({"disabled" : true, "checked" : false}).trigger('change');
		}
	}

	function sorttype_validate(form) {
		var tree = $(":radio[name='tree']:checked", form).val();
		var sort_type = $(":radio[name='tree']:checked", form).data("value");

		//조회수가 아닐경우 답글하기 사용안함로 변경
		//$("select[name='sort_type'] option[value='"+ sort_type +"']", form).prop("selected", true);
	}

	function mail_validate(form) {
		var yn_send_mail = $(":radio[name='yn_send_mail']:checked", form).val();
		var yn_email = $(":radio[name='yn_send_mail']:checked", form).data("value");

		$(":radio[name='yn_email'][value='"+ yn_email +"']", form).prop("checked", true);
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
			if($("select[name='optionField["+site_language+"]["+field+"][file_type]']:selected").val() == 'image'){
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

		var itemValueCnt = $('input[name="optionField['+site_language+']['+field+'][itemValue][]"]').length;
		if ((select_value == "radio" || select_value == "select") && itemValueCnt == 0){
			add_option(field);
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
		// 동적으로 추가되는 input과 페이지 로드시에 input이 상이하여 오동작...
        // 더 상위 셀렉터로 잡은 뒤 카운트하여 사용
        // @deprecated 20200424 INBET JONAS var last_option_no = Number($(".option_"+ site_language +"_"+ field +":last").val()) + 1 || 1;
		var last_option_no = Number($(".box_option_"+ site_language +"_"+ field).length) + 1 || 1;

        var str = '';
		str += '	<div id="box_option_'+ site_language +'_'+ field +'_'+ last_option_no +'" class="box_option_'+ site_language +'_'+ field +' box_option_'+ field +'">';
		str += '		<input type="hidden" class="option_'+ site_language +'_'+ field +'" value="'+ last_option_no +'" />';
		str += '		<input type="hidden" name="optionField['+ site_language +']['+ field +'][itemName][]" value="'+ field +"-"+ last_option_no +'" class="inq_w70p" />';
		str += '		<input type="text" name="optionField['+ site_language +']['+ field +'][itemValue][]" class="inq_w70p" />';
		str += '		<a href="javascript://" class="btn_mini" onclick="remove_option(\''+ field +'\', \''+ last_option_no +'\');">삭제</a>';
		str += '	</div>';
		$(str).appendTo("."+site_language+"_td#option_"+ field +"_td:last");
	}

	$('#leftmenu >ul > li:nth-of-type(4)').addClass('on').find('ul li:nth-of-type(2)').addClass('active');
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
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
	<div class="main_tit">
		<h2>게시판 관리</h2>
		<div class="btn_right btn_num2">
			<?php if($this->input->get("code", true)) : ?>
			<!-- <button><a href="javascript://" onclick="if(confirm('게시판을 삭제하시겠습니까?nn주의) 게시판에 작성된 글이 모두 삭제됩니다.')) location.href='board_manage_delete?code=<?=$this->input->get("code", true)?>';" class="btn gray">삭제하기</a></button> -->
			<?php endif ?>
			<button type="button" onclick="javascript:location.href='board_manage'"><a href="board_manage" class="btn gray">목록</a></button>
			<button><a class="btn point">저장</a></button>
		</div><!--btn_center-->
	</div><!--main_tit-->

		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableB">
				<colgroup>
					<col width="14%" />
					<col width="86%" />
				</colgroup>
				<tr>
					<th class="ta_left" scope="row">게시판 유형</th>
					<td>
						<input type="radio" class="tbB-input2" name="board_type" id="board_type-1" value="1" data-target-class="board_type-2" data-active="false" <?php if($board_manage["board_type"] == "1"):?>checked<?php endif?> onchange="set_change_yn(this); mail_validate(this.form);" /> <label for="board_type-1">일반</label>
						<input type="radio" class="tbB-input2" name="board_type" id="board_type-2" value="2" data-target-class="board_type-2" data-active="true" <?php if($board_manage["board_type"] == "2"):?>checked<?php endif?> onchange="set_change_yn(this); mail_validate(this.form);" /> <label for="board_type-2">문의</label>
					</td>
				</tr>
				<tr class="board_type-2">
					<th class="ta_left" scope="row">답변 메일 발송<?php if($this->_site_language["multilingual"]) : ?><?php endif ?></th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_send_mail" id="yn_send_mail-y" value="y" data-target-class="yn_send_mail-y" data-active="true" data-value="y" <?php if($board_manage["yn_send_mail"] == "y"):?>checked<?php endif?> onchange="mail_validate(this.form); set_change_yn(this); " /> <label for="yn_send_mail-y">사용</label>
						<input type="radio" class="tbB-input2" name="yn_send_mail" id="yn_send_mail-n" value="n" data-target-class="yn_send_mail-y" data-active="false" <?php if($board_manage["yn_send_mail"] == "n"):?>checked<?php endif?> onchange="mail_validate(this.form); set_change_yn(this); " /> <label for="yn_send_mail-n">사용안함</label>
						<!--해당내용 정확히 확인되지 않아 주석처리p class="bbs_txt"><em>답변메일 발송 사용 선택시, "이메일 작성" 기능을 설정하실 수 없습니다.</em></p><br/-->
						<p class="bbs_txt">메일 발송시 답변 타이틀이 메일 제목이 되며, <em>국/영문이 함께 쓰인 메일 폼</em>으로 발송됩니다.</p>
					</td>
				</tr>
				<tr class="yn_send_mail-y">
					<th class="ta_left" scope="row">메일폼 디자인</th>
					<td>
						<select name="mail_form">
							<?php foreach($mail_form as $key => $value) : ?>
								<option value="<?=$key?>" <?php if($board_manage["mail_form"] == $key):?>selected<?php endif?>><?=$value?></option>
							<?php endforeach ?>
						</select>
						<button type="button" class="info_bullet">메일폼 위치</button> /data/mail_form
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">목록페이지 사용</th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_display_list" id="yn_display_list-y" value="y" <?php if($board_manage["yn_display_list"] == "y"):?>checked<?php endif?> onchange="mail_validate(this.form); set_change_yn(this); " /> <label for="yn_display_list-y">사용함</label>
						<input type="radio" class="tbB-input2" name="yn_display_list" id="yn_display_list-n" value="n" <?php if($board_manage["yn_display_list"] == "n"):?>checked<?php endif?> onchange="mail_validate(this.form); set_change_yn(this); " /> <label for="yn_display_list-n">사용안함</label>
						<p class="bbs_txt board_type-2">"문의" 선택시 "사용안함"을 선택해야하며, 사용안함 선택시 '홈페이지'에서 게시판 목록 페이지로 접근이 불가능합니다.</em></p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">목록페이지 스킨 선택</th>
					<td>
						<select name="skin_type">
							<?php foreach($skin_files as $key => $value) : ?>
								<option value="<?=$value?>" <?if($board_manage["skin_type"] == $value):?>selected<?endif?>><?=$value?></option>
							<?php endforeach ?>
						</select>
						<button type="button" class="info_bullet">SKIN위치</button> /data/skin/<?=$this->_skin?>/layout/board
					</td>
				</tr>
				<tr>
					<th scope="row">관리자에게 메일 발송</th>
					<td>
						<input type="radio" name="yn_admin_email" id="ynAdminEmailY" value="y" checked><label for="ynAdminEmailY">사용</label>
						<input type="radio" name="yn_admin_email" id="ynAdminEmailN" value="n"<?=$board_manage['yn_admin_email'] == 'n' ? ' checked' : ''?>><label for="ynAdminEmailN">사용안함</label>
						<p class="bbs_txt">게시판에 글이 등록될 경우 관리자 이메일(<a href="conf_reg"><b><?=$this->_cfg_site['kor']['adminEmail']?></b></a>)로 메일이 발송됩니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">게시판 아이디</th>
					<td><input type="text" name="code" class="inq_w200" value="<?=$board_manage["code"]?>" <?php if(isset($board_manage["code"])):?>readonly<?php endif?>/><br/>
					<p class="bbs_txt">영문자와 숫자로만 입력해주세요</p><p class="bbs_txt"><em><b>manage</b>, <b>global</b>, <b>file</b>, <b>comment</b> 문자열 단독 사용불가. <b>이미 생성후 사용중이라면, 해당 게시판은 절대 삭제하시면 안됩니다.</b></em></p></td>
				</tr>
                <!-- 원본 게시판명 유지 -->
				<tr>
					<th class="ta_left" scope="row">게시판명</th>
					<td><input type="text" name="name" class="inq_w200" value="<?=$board_manage["name"]?>"></td>
				</tr>
                <!-- 다국어 설정 유무에 따른 게시판명 변환 -->
                <?php if($this->_site_language["multilingual"]) : ?>
                    <?php foreach($this->_site_language["set_language"] as $key => $value) :?>
                        <tr>
                            <th class="ta_left" scope="row">노출명 [<?=$value?>]</th>
                            <td><input type="text" name="name_<?=$key?>" class="inq_w200" value="<?=$board_manage["global"][$key]["name"]?>"></td>
                        </tr>
                    <?php endforeach ?>
                <?php else : ?>
                    <tr>
                        <th class="ta_left" scope="row">게시판명 (<?=$this->_site_language["support_language"]["kor"]?>)</th>
                        <td><input type="text" name="name_kor" class="inq_w200" value="<?=$board_manage["global"]["kor"]["name"]?>"></td>
                    </tr>
                <?php endif ?>
				<tr>
					<th class="ta_left" scope="row">글읽기 권한</th>
					<td>
						<select name="read_auth" class="auth">
							<?php foreach($admin_grade_read_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["read_auth"] == $value["level"]):?>selected<?php endif?>>[관리자]<?=$value["gradenm"]?></option>
							<?php endforeach ?>
							<option disabled>--------------------------------</option>
							<?php foreach($member_grade_read_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["read_auth"] == $value["level"]):?>selected<?php endif?>><?=$value["gradenm"]?></option>
							<?php endforeach ?>
							<option disabled>--------------------------------</option>
							<option value="0" <?php if($board_manage["read_auth"] == "0"):?>selected<?php endif?>>비회원</option>
						</select>
						<p class="bbs_txt">글읽기 권한은 글쓰기 권한과 같거나 더 높아야 합니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">글쓰기 권한</th>
					<td>
						<select name="write_auth" class="auth">
							<?php foreach($admin_grade_write_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["write_auth"] == $value["level"]):?>selected<?php endif?>>[관리자]<?=$value["gradenm"]?></option>
							<?php endforeach ?>
							<option disabled>--------------------------------</option>
							<?php foreach($member_grade_write_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["write_auth"] == $value["level"]):?>selected<?php endif?>><?=$value["gradenm"]?></option>
							<?php endforeach ?>
							<option disabled>--------------------------------</option>
							<option value="0" <?php if($board_manage["write_auth"] == "0"):?>selected<?php endif?>>비회원</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">말머리<?php if($this->_site_language["multilingual"]) : ?> (국문 전용)<?php endif ?></th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_preface" id="preface-2" value="n" data-target-class="preface-2" data-active="false" <?php if($board_manage["yn_preface"] == "n"):?>checked<?php endif?> onclick="set_change_yn(this)" /> <label for="preface-2">사용안함</label>
						<input type="radio" class="tbB-input2" name="yn_preface" id="preface-1" value="y" data-target-class="preface-2" data-active="true" <?php if($board_manage["yn_preface"] == "y"):?>checked<?php endif?> onclick="set_change_yn(this)" /> <label for="preface-1">사용</label>
						<div class="preface-2">
							<input type="text" name="preface" class="inq_w400" placeholder="말머리 구분은 콤마( , ) 입니다." value="<?=$board_manage["preface"]?>">
							<p class="bbs_txt"><em>말머리는 <b>최대 5개</b>까지 등록가능합니다.</em></p>
						</div>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">답글</th>
					<td>
						<input type="radio" class="tbB-input2" name="tree" id="tree-y" value="y" data-value="regdt" <?php if($board_manage["tree"] == "y"):?>checked<?php endif?> data-target-class="tree-y" data-active="true" onchange="set_change_yn(this); sorttype_validate(this.form);" /> <label for="tree-y">사용</label>
						<input type="radio" class="tbB-input2" name="tree" id="tree-n" value="n" <?php if($board_manage["tree"] == "n"):?>checked<?php endif?> data-target-class="tree-y" data-active="false" onchange="set_change_yn(this); sorttype_validate(this.form);" /> <label for="tree-n">사용안함</label>
						<span class="tree-y">
							답글 쓰기 권한 ( 글쓰기 권한에서 설정하신 등급과 같거나 높아야 합니다. )
							<select name="anwrite_auth" class="auth">
								<?php foreach($admin_grade_write_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["anwrite_auth"] == $value["level"]):?>selected<?php endif?>>[관리자]<?=$value["gradenm"]?></option>
								<?php endforeach ?>
								<option disabled>--------------------------------</option>
								<?php foreach($member_grade_write_list as $key => $value) :?>
									<option value="<?=$value["level"]?>" <?php if($board_manage["anwrite_auth"] == $value["level"]):?>selected<?php endif?>><?=$value["gradenm"]?></option>
								<?php endforeach ?>
								<option disabled>--------------------------------</option>
								<option value="0" <?php if($board_manage["anwrite_auth"] == "0"):?>selected<?php endif?>>비회원</option>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">댓글</th>
					<td>
						<input type="radio" class="tbB-input2" name="comment" id="comment-y" value="y" <?php if($board_manage["comment"] == "y"):?>checked<?php endif?> data-target-class="comment-y" data-active="true" onchange="set_change_yn(this);" /> <label for="comment-y">사용</label>
						<input type="radio" class="tbB-input2" name="comment" id="comment-n" value="n" <?php if($board_manage["comment"] == "n"):?>checked<?php endif?> data-target-class="comment-y" data-active="false" onchange="set_change_yn(this);" /> <label for="comment-n">사용안함</label>
						<span class="comment-y">
							댓글 쓰기 권한
							<select name="comment_auth" class="auth">
								<?php foreach($admin_grade_write_list as $key => $value) :?>
								<option value="<?=$value["level"]?>" <?php if($board_manage["comment_auth"] == $value["level"]):?>selected<?php endif?>>[관리자]<?=$value["gradenm"]?></option>
								<?php endforeach ?>
								<option disabled>--------------------------------</option>
								<?php foreach($member_grade_write_list as $key => $value) :?>
									<option value="<?=$value["level"]?>" <?php if($board_manage["comment_auth"] == $value["level"]):?>selected<?php endif?>><?=$value["gradenm"]?></option>
								<?php endforeach ?>
								<option disabled>--------------------------------</option>
								<option value="0" <?if($board_manage["comment_auth"] == "0"):?>selected<?endif?>>비회원</option>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">첨부파일</th>
					<td>
						<input type="radio" class="tbB-input2" name="files" id="files-y" value="y" data-target-class="files-y" data-active="true" <?php if($board_manage["files"] == "y"):?>checked<?php endif?> onchange="set_change_yn(this);" /> <label for="files-y">사용</label>
						<input type="radio" class="tbB-input2" name="files" id="files-n" value="n" data-target-class="files-y" data-active="false" <?php if($board_manage["files"] == "n"):?>checked<?php endif?> onchange="set_change_yn(this);" /> <label for="files-n">사용안함</label>
						<p class="files-y">
							<?=form_dropdown("file_count", array(""=>"첨부파일 갯수 선택", 1 => 1, 2 => 2, 3 => 3), $board_manage["file_count"])?>
							각 첨부파일 당 용량을 <input type="text" name="filesize" value="<?=$board_manage["filesize"]?>" class="inq_w40">MB로 제한합니다. (숫자만 입력 가능)
						</p>
						<p class="bbs_txt">첨부파일은 보안을위해 정해진 확장자만 등록이 가능합니다.</p>
						<p class="bbs_txt">등록 가능 확장자 : 문서 ( 'doc','docx','hwp','txt','pdf','xls','xlsx','cell','ppt','pptx','show','rtf','zip','7z' ) / 이미지 ( 'jpeg','jpg','png','bmp','gif' ) / 엑셀 ( 'xls','xlsx','cell' ) / 동영상 ( 'mp4','mpeg4' )</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">이메일</th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_email" id="yn_email-y" value="y" <?php if($board_manage["yn_email"] == "y"):?>checked<?php endif?> onchange="mail_validate(this.form);" /> <label for="yn_email-y">사용</label>
						<input type="radio" class="tbB-input2" name="yn_email" id="yn_email-n" value="n" <?php if($board_manage["yn_email"] == "n"):?>checked<?php endif?> onchange="mail_validate(this.form);" /> <label for="yn_email-n">사용안함</label>
						<p class="bbs_txt">회원의 개인정보 보호에 의거하여 관리자페이지 '게시글관리' 에서만 확인할 수 있습니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">휴대폰</th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_mobile" id="yn_mobile-y" value="y" <?php if($board_manage["yn_mobile"] == "y"):?>checked<?php endif?> /> <label for="yn_mobile-y">사용</label>
						<input type="radio" class="tbB-input2" name="yn_mobile" id="yn_mobile-n" value="n" <?php if($board_manage["yn_mobile"] == "n"):?>checked<?php endif?> /> <label for="yn_mobile-n">사용안함</label>
						<p class="bbs_txt">회원의 개인정보 보호에 의거하여 관리자페이지 '게시글관리' 에서만 확인할 수 있습니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">비밀글 작성 사용</th>
					<td>
						<input type="radio" name="secret" id="secret-0" class="tbB-input2" value="0" <?php if($board_manage["secret"] == "0"):?>checked<?php endif?> /><label for="secret-0">일반글만 사용</label>
						<input type="radio" name="secret" id="secret-1" class="tbB-input2" value="1" <?php if($board_manage["secret"] == "1"):?>checked<?php endif?> /><label for="secret-1">비밀글만 사용</label>
						<input type="radio" name="secret" id="secret-2" class="tbB-input2" value="2" <?php if($board_manage["secret"] == "2"):?>checked<?php endif?> /><label for="secret-2">둘다 사용</label>
						<p class="bbs_txt">게시판 유형 "문의"타입은 "비밀글만 사용"을 선택하셔야 합니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">페이지 노출 갯수</th>
					<td>
						<input type="text" name="roundpage" class="inq_w105" value="<?=$board_manage["roundpage"]?>" placeholder="숫자만 입력"><p class="bbs_txt">숫자만 입력가능하며, 한 페이지에 너무 많은 게시글을 노출할 경우 속도 저하를 야기할 수 있습니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row"><em>갤러리형 게시판</em><br/>목록페이지 썸네일</th>
					<td>
						<input type="radio" class="tbB-input2" name="thumbnail" id="thumbnail-y" value="y" data-target-class="thumbnail-y" data-active="true" <?php if($board_manage["thumbnail"] == "y"):?>checked<?php endif?> onchange="set_change_yn(this);" /> <label for="thumbnail-y">사용</label>
						<input type="radio" class="tbB-input2" name="thumbnail" id="thumbnail-n" value="n" data-target-class="thumbnail-y" data-active="false" <?php if($board_manage["thumbnail"] == "n"):?>checked<?php endif?> onchange="set_change_yn(this);" /> <label for="thumbnail-n">사용안함</label>
						<span class="thumbnail-y">
							<?=form_dropdown("thumbnail_count", array(""=>"이미지 파일 갯수 선택", 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5), $board_manage["thumbnail_count"])?>
						</span>
						<p class="bbs_txt">대표이미지로 설정한 썸네일만 목록페이지에서 노출되며, 썸네일이 없는경우, 첨부파일 중 이미지를 목록페이지에서 보여줍니다.</p><p class="bbs_txt">등록 가능 확장자 : jpg, gif, png</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row"><em>동영상 게시판</em><br/>영상 Url</th>
					<td>
						<input type="radio" class="tbB-input2" name="yn_video" id="yn_video-y" value="y" <?php if($board_manage["yn_video"] == "y"):?>checked<?php endif?> /> <label for="yn_video-y">동영상게시판 사용</label>
						<input type="radio" class="tbB-input2" name="yn_video" id="yn_video-n" value="n" <?php if($board_manage["yn_video"] == "n"):?>checked<?php endif?> /> <label for="yn_video-n">동영상게시판 사용안함</label>
						<p class="bbs_txt">동영상 게시판일 경우에만 사용함 체크해주세요.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row"><em>일반형 게시판</em><br/>게시글 정렬</th>
					<td>
						<select name="sort_type" onchange="sorttype_validate(this.form);">
							<option value="regdt">등록일순(기준) </option>
							<option value="hit">조회수</option>
						</select>
						<p class="bbs_txt">조회수 정렬은 게시판 유형이 "일반"이고, 목록페이지 스킨이 "일반형"이어야 사용가능하며, 사용시 "답글하기" 기능은 사용이 불가합니다.</p>
						<p class="bbs_txt">조회수 정렬 사용 설정시 일부 사용자 리스트페이지에서 오류가 발생할 수 있으므로, 사용을 권장하지 않습니다.</p>
					</td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">CAPTCHA</th>
					<td>
						<input type="radio" name="use_captcha" id="captchaY" value="y" checked>
						<label for="captchaY">사용</label>
						<input type="radio" name="use_captcha" id="captchaN" value="n"<?php if($board_manage['use_captcha'] == "n") echo " checked"?>>
						<label for="captchaN">사용안함</label>
					</td>
				</tr>
				<!--예비컬럼 기능 Start-->
				<tr>
					<th class="ta_left" scope="row" rowspan="2" style="border-bottom:0;">
						게시판 추가 필드 기능
					</th>
					<td style="border-bottom:0;">
						<label><input type="radio" name="extraFl" value="n" <?=($board_manage['extraFl'] == 'n' ? "checked" : "")?>/>사용안함</label>
						<label><input type="radio" name="extraFl" value="y" <?=($board_manage['extraFl'] == 'y' ? "checked" : "")?>/>사용함</label>
						<p class="bbs_file_size extra_p">* 파일 형식의 업로드 용량을 <input type="text" id="extra_file_size" name="extra_file_size" class="inq_w40" value="<?=($board_manage['extra_file_size'] == 0 ? "" : $board_manage['extra_file_size'])?>">MB로 제한합니다.</p>
					</td>
				</tr>
				<tr class="extra_tr">
					<td>
						<div style="max-width:100%;">
							<div class="lang_icon_tab">
								<?php if($this->_site_language["multilingual"]) : ?>
								<ul>
								<?php
								foreach($this->_site_language["support_language"] as $languageKey => $languageVal) :
									foreach($this->_site_language['set_language'] as $k => $v) :
										if($k == $languageKey) :
											$on = $this->_site_language['default'] == $languageKey ? "on" : "";
											echo "<li class='".$on." lang_".$languageKey."'><a onclick='javascript:language_change(\"".$languageKey."\", this);' data-language='".$languageKey."'>".$languageVal."</a></li>";
										endif;
									endforeach;
								endforeach;
								?>
								</ul>
								<?php endif ?>
							</div>
							<div class="table_write">
								<table cellpadding="0" cellspacing="0" border="0">
									<colgroup>
										<col width="60px">
										<col width="60px">
										<col width="210px">
										<col width="24%">
										<col width="*">
										<col width="9%">
									</colgroup>
									<thead>
										<tr>
											<th scope="col" align="center">사용</th>
											<th scope="col" align="center">필수</th>
											<th scope="col" align="center">필드명</th>
											<th scope="col" align="center">형식</th>
											<th scope="col" align="center">옵션</th>
											<th scope="col" align="center">변수명</th>
										</tr>
									</thead>
									<tbody id='divList'>
										<tr>
											<?
											$extraFieldInfo = $board_manage['extraFieldInfo'];
											foreach($extraField as $columnKey){

											?>
												<tr>
													<?
													foreach($this->_site_language["support_language"] as $languageKey => $languageVal){
														$extraOption = $extraFieldInfo['option'][$languageKey][$columnKey];
													?>
														<!--사용-->
														<td align="center" class = "<?=$languageKey?>_td">
															<label>
																<input type = "checkbox" name = "useField[<?=$languageKey?>][<?=$columnKey?>]" value = "checked" <?=(isset($extraFieldInfo["use"][$languageKey][$columnKey]) ? "checked" : "")?>/>사용
															</label>
														</td>
														<!--필수-->
														<td align="center" class = "<?=$languageKey?>_td">
															<label>
																<input type = "checkbox" name = "reqField[<?=$languageKey?>][<?=$columnKey?>]" value = "checked" <?=(isset($extraFieldInfo["require"][$languageKey][$columnKey]) ? "checked" : "")?>/>필수
															</label>
														</td>
														<!--필드명-->
														<td align="ta_left field_td" class = "<?=$languageKey?>_td">
															<input type = "text" name = "nameField[<?=$languageKey?>][<?=$columnKey?>]" value="<?=$extraFieldInfo["name"][$languageKey][$columnKey]?>" />
															<p><?=$extraFieldInfo["name"][$languageKey][$columnKey]?></p>
														</td>
														<!--형식-->
														<td align="left" class = "<?=$languageKey?>_td">
															<select class = "item_type_<?=$languageKey?>" data-itemname = "<?=$columnKey?>" name = "optionField[<?=$languageKey?>][<?=$columnKey?>][type]" onchange = "select_init(this, '<?=$columnKey?>');">
																<option value = "">
																	텍스트
																</option>
																<option value = "radio" <?=$extraOption["type"] == "radio" ? "selected" : "" ?>>
																	라디오
																</option>
																<option value = "select" <?=$extraOption["type"] == "select" ? "selected" : "" ?>>
																	셀렉트
																</option>
																<option value = "editor" <?=$extraOption["type"] == "editor" ? "selected" : "" ?>>
																	에디터
																</option>
																<option value = "file" <?=$extraOption["type"] == "file" ? "selected" : "" ?>>
																	파일
																</option>
																<option value = "checkbox" <?=$extraOption["type"] == "checkbox" ? "selected" : "" ?>>
																	체크박스
																</option>
															</select>

															<a href = "javascript://" id = "btn_option_<?=$languageKey?>_<?=$columnKey?>" class = "btn_mini <?=(in_array($extraOption["type"], array("checkbox", "radio", "select")))  ? "" : "hide"?>" onclick="add_option('<?=$columnKey?>');">
																추가
															</a>
															<select
																class = "file_type_<?=$languageKey?> <?=( (!isset($extraOption["type"]) || in_array($extraOption["type"], array("checkbox", "radio", "select", "editor")))? "hide disabled" : "")?>" data-itemname = "<?=$columnKey?>"
																name = "optionField[<?=$languageKey?>][<?=$columnKey?>][file_type]" onchange = "select_file_type(this, '<?=$columnKey?>');"
																<?=( (!isset($extraOption["type"]) || in_array($extraOption["type"], array("checkbox", "radio", "select", "editor")))? "hide disabled" : "")?>>
																<? foreach($extension as $itemValue){ ?>
																	<option value="<?=$itemValue?>" <?=($extraOption["file_type"] == $itemValue ? "selected" : "")?>>
																		<?=$itemValue?>
																	</option>
																<? } ?>
															</select>
														</td>
														<!--옵션-->
														<td id = "option_<?=$columnKey?>_td" align = "left" class = "<?=$languageKey?>_td">
															<? if(isset($extraOption["item"])) { ?>
																<? $i = 0; ?>
																<? foreach($extraOption["item"] as $itemName => $itemValue){ ?>
																	<? $i++; ?>
																	<div id = "box_option_<?=$languageKey?>_<?=$columnKey?>_<?=$i?>" class = "box_option_<?=$languageKey?> box_option_<?=$languageKey?>_<?=$columnKey?> box_option_<?=$columnKey?>">
																		<input type="hidden" class="option_<?=$languageKey?>_<?=$columnKey?>" value="<?=$i?>">

                                                                        <input
																		type="hidden" name="optionField[<?=$languageKey?>][<?=$columnKey?>][itemName][]" value="<?=$itemName?>"
																		/>

																		<input
																		type = "text" name="optionField[<?=$languageKey?>][<?=$columnKey?>][itemValue][]" value = "<?=$itemValue?>"
																		class = "inq_w70p" />

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
															class="set_image_<?=$columnKey?> <?=$extraOption["file_type"] == "image" ? "" : "disabled hide"?> inq_w40p"
															placeholder="가로값 입력"
															value="<?=$extraOption["width"]?>"
															/>
															<input
															type="text"
															id="image_height"
															class="inq_w40p"
															placeholder="세로값 입력"
															name="optionField[<?=$languageKey?>][<?=$columnKey?>][height]" value="<?=$extraOption["height"]?>"/>
														</td>
														<!--변수명(치환태그)-->
														<td align="center" class = "<?=$languageKey?>_td">
															<div class="tag_wrap"><span class="tag_view">태그보기</span><span class="bbs_tag">{extraFieldData['<?=$columnKey?>']}</span></div>
														</td>
													<? } // foreach language ?>
												</tr>
											<? } // foreach extraField ?>
									</tbody>
								</table>
							</div>
							<p class="bbs_txt">등록 가능 확장자<br>
							문서 ( 'doc','docx','hwp','txt','pdf','xls','xlsx','cell','ppt','pptx','show','rtf','zip','7z' ) / 이미지 ( 'jpeg','jpg','png','bmp','gif' ) / 엑셀 ( 'xls','xlsx','cell' ) / 동영상 ( 'mp4','mpeg4' )</p>
						</div>
						<!-- <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableB"> -->
							<!-- <colgroup> -->
								<!-- <col width="16%" /> -->
								<!-- <col width="84%" /> -->
							<!-- </colgr	oup> -->
							<!-- <tr> -->
								<!-- <th class="ta_left" scope="row"> -->

								<!-- </th> -->
								<!-- <td> -->

								<!-- </td> -->
							<!-- </tr> -->
						<!-- </table> -->
					</td>
				</tr>
				<!--예비컬럼 기능 End-->
			</table>
		</div><!--table_write-->
		<div class="table_write_info">* 게시판 최초 생성일 : <?=$board_manage["regdt"]?><br/>* 게시판 마지막 수정일 : <?=$board_manage["updatedt"]?></div>
	<?=form_close();?>
	<div class="terms_privecy_box">
		<dl>
			<dt>- 권한설정 안내사항</dt>
			<dd>
			슈퍼관리자 제외한 <em>모든 관리자등급</em>에 속하는 관리자는 게시판 프론트페이지에서 <em>"글읽기 / 글쓰기 / 답글 / 댓글"</em> 권한이 주어집니다. <br>
			<em>게시글 이동·복사·삭제</em>의 권한을 원하시는 경우, 해당 관리자 등급에 <em class="point">게시글 관리</em>권한을 부여해주세요.<br><br>
			</dd>
		</dl>
	</div>
</div><!-- // contents -->