<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script>
	$(function() {
		setLayoutByPopupForm();
		$("form[name='frm']").validate({
			rules : {
				language : {required : true},
				title : {
                    required : {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    }, 
                    rangelength : [2, 25]},
				type : {required : true},
				content : {editorRequired : {depends : function() {return !getSmartEditor("content")}}},
				open : {required : true},

				recongnition_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				recongnition_tablet : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				recongnition_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},

				toppx_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				toppx_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				toppx_responsive_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				toppx_responsive_tablet : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				toppx_responsive_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},

				leftpx_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				leftpx_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				leftpx_responsive_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				leftpx_responsive_tablet : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				leftpx_responsive_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},

				width_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				width_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "fixed" ? true : false);}}, number : true},
				width_responsive_pc : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				width_responsive_tablet : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},
				width_responsive_mobile : {required : {depends : function(){return ($("[name=popupform]:checked").val() == "responsive" ? true : false);}}, number : true},

				height_pc : {number : true},
				//height_mobile : {number : true},
				height_responsive_pc : {number : true},
				height_responsive_tablet : {number : true},
				//height_responsive_mobile : {number : true},
				sdate : {required : true, dateValid : true},
				edate : {required : true, dateValid : true}
			}, messages : {
				language : {required : "언어를 선택해주세요."},
				title : {required : "제목을 입력해주세요.", rangelength : $.validator.format("제목은 {0}~{1}자입니다.")},
				type : {required : "형태를 선택해주세요."},
				content : {editorRequired : "내용을 입력해주세요."},
				open : {required : "공개여부를 선택해주세요."},

				recongnition_pc : {required : "반응형 pc 인식 넓이를 입력해주세요.", number : "숫자만 입력가능합니다."},
				recongnition_tablet : {required : "반응형 테블릿 인식 넓이를 입력해주세요.", number : "숫자만 입력가능합니다."},
				recongnition_mobile : {required : "반응형 모바일 인식 넓이를 입력해주세요.", number : "숫자만 입력가능합니다."},

				toppx_pc : {required : "pc 상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				toppx_mobile : {required : "모바일 상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				toppx_responsive_pc : {required : "반응형 pc 상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				toppx_responsive_tablet : {required : "반응형 테블릿 상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				toppx_responsive_mobile : {required : "반응형 모바일 상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},

				leftpx_pc : {required : "pc 좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				leftpx_mobile : {required : "모바일 좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				leftpx_responsive_pc : {required : "반응형 pc 좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				leftpx_responsive_tablet : {required : "반응형 테블릿 좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				leftpx_responsive_mobile : {required : "반응형 모바일 좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},

				width_pc : {required : "pc 너비를 입력해주세요.", number : "숫자만 입력가능합니다."},
				width_mobile : {required : "모바일 너비를 입력해주세요.", number : "숫자만 입력가능합니다."},
				width_responsive_pc : {required : "반응형 pc 너비를 입력해주세요.", number : "숫자만 입력가능합니다."},
				width_responsive_tablet : {required : "반응형 테블릿 너비를 입력해주세요.", number : "숫자만 입력가능합니다."},
				width_responsive_mobile : {required : "반응형 모바일 너비를 입력해주세요.", number : "숫자만 입력가능합니다."},

				height_pc : {number : "숫자만 입력가능합니다."},
				//height_mobile : {number : "숫자만 입력가능합니다."},
				height_responsive_pc : {number : "숫자만 입력가능합니다."},
				height_responsive_tablet : {number : "숫자만 입력가능합니다."},
				//height_responsive_mobile : {number : "숫자만 입력가능합니다."},

				sdate : {required : "팝업 시작일을 입력해주세요.", dateValid : "날짜를 제대로 입력해주세요. (YYYY-mm-dd)"},
				edate : {required : "팝업 종료일을 입력해주세요.", dateValid : "날짜를 제대로 입력해주세요. (YYYY-mm-dd)"}
			}
		});

		$("[name=popupform]").on("click change", function(){
			setLayoutByPopupForm();
		});
	});

	function setLayoutByPopupForm()
	{
		if($("[name=popupform]:checked").length == 0){
			$("[name=popupform][value=fixed]").prop("checked", true);
		}
		var popupform = $("[name=popupform]:checked").val();

		if(popupform == "fixed") {
			$(".responsive_tr").addClass("hide");
			$(".fixed_tr").removeClass("hide");
		}else if(popupform == "responsive") {
			$(".fixed_tr").addClass("hide");
			$(".responsive_tr").removeClass("hide");
		}
	}



	function popup_save(form) {
		var frm = $(form)
		if(!frm.valid()) {
			return false;
		}
		if ($("[name=popupform]:checked").val() == "fixed"){
			if ($("[name=width_mobile]").val() == 0){
				alert("모바일 너비를 잘못 입력하셨습니다.");
				return false;
			}
			if ($("[name=width_pc]").val() == 0){
				alert("pc 너비를 잘못 입력하셨습니다.");
				return false;
			}
		}else{
			if ($("[name=width_responsive_mobile]").val() == 0){
				alert("모바일 너비를 잘못 입력하셨습니다.");
				return false;
			}
			if ($("[name=width_responsive_pc]").val() == 0){
				alert("pc 너비를 잘못 입력하셨습니다.");
				return false;
			}
		}
		frm.prop("action", "");
		frm.submit();
	}

	function popup_delete(form) {
		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}
		$(form).prop("action", "/admin/popup/popup_delete");
		form.submit();
	}

	$('#leftmenu >ul > li').addClass('on');
	<? if($mode == "modify") { ?>
		$('#leftmenu >ul > li:nth-of-type(1)').find('ul li:nth-of-type(2) a').text('팝업 수정');
	<? } else { ?>
	<? } ?>
</script>
<div id="contents">
<?=form_open("", array("name" => "frm"));?>
<input type="hidden" name="mode" value="<?=$mode?>" />
<input type="hidden" name="no" value="<?=$this->input->get("no", true)?>" />
<input type="hidden" name="ref" value="<?=$ref?>">
	<div class="main_tit">
		<h2>팝업 <? if($mode == "register") : echo "등록"; else : echo "수정"; endif; ?></h2>
		<div class="btn_right btn_num2">
			<? if($this->input->get("no", true)) : ?><a href="javascript://" onclick="popup_delete(document.frm);" class="btn gray sel_minus">삭제</a><? endif ?>
			<a href="popup_list?<?=$ref?>" class="btn gray">목록</a>
			<a href="javascript://" onclick="popup_save(document.frm);" class="btn point">저장</a>
		</div><!--btn_center-->
	</div><!--main_tit-->
		
		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="">
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
				<tr>
					<th class="ta_left" scope="row">사이트 형식</th>
					<td>
						<input type="radio" class="tbB-input2" id="popupform_fixed" name="popupform" value="fixed" <?=($popup_view["popupform"] == "fixed" ? "checked" : "")?>/><label for="popupform_fixed">일반형 홈페이지</label>
						<input type="radio" class="tbB-input2" id="popupform_responsive" name="popupform" value="responsive" <?=($popup_view["popupform"] == "responsive" ? "checked" : "")?>/><label for="popupform_responsive">반응형 홈페이지</label>
					</td>
				</tr>
				<tr class="fixed_tr">
					<th class="ta_left" scope="row">팝업 크기</th>
					<td>
						<div class="number_box">
							<span class="box_title">PC 팝업 너비*높이 기입</span>
							<input type="text" name="width_pc" placeholder="너비" value="<?=$popup_view["width_pc"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="height_pc" placeholder="높이" value="<?=$popup_view["height_pc"] == 0 ? '' : $popup_view["height_pc"]?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">모바일 팝업 너비 기입</span>
							<input type="text" name="width_mobile" placeholder="너비" value="<?=$popup_view["width_mobile"]?>" />
							<span>%</span><span> (모바일 높이값은 가로값에 맞춰 유동적으로 적용됩니다.) </span>
							<!--input type="text" name="height_mobile" placeholder="높이" value="<?=$popup_view["height_mobile"] == 0 ? '' : $popup_view["height_mobile"]?>" />
							<span>pixel</span-->
						</div>
					</td>
				</tr>
				<tr class="fixed_tr">
					<th class="ta_left" scope="row">팝업 위치</th>
					<td>
						<div class="number_box">
							<span class="box_title">PC 팝업 노출 위치</span>
							<input type="text" name="toppx_pc" placeholder="상단 위치" value="<?=$popup_view["toppx_pc"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="leftpx_pc" placeholder="좌측 위치" value="<?=$popup_view["leftpx_pc"]?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">모바일 팝업 노출 위치</span>
							<input type="text" name="toppx_mobile" placeholder="상단 위치" value="<?=$popup_view["toppx_mobile"]?>" />
							<span>pixel</span><span> (모바일 팝업은 화면 가로값의 가운데로 노출 위치값이 고정됩니다.) </span>
							<!--input type="text" name="leftpx_mobile" placeholder="좌측 위치" value="<?=$popup_view["leftpx_mobile"]?>" />
							<span>pixel</span-->
						</div>
					</td>
				</tr>
				
				<!--반응형 설정-->
				<tr class="responsive_tr">
					<th class="ta_left" scope="row">팝업 인식 넓이</th>
					<td>
						<div class="number_box">
							<span class="box_title">PC로 인식되는 넓이</span>
							<input type="text" name="recognition_pc" placeholder="인식 화면 너비" value="<?=$popup_view["recognition_pc"]?>" />
							<span>pixel</span><span> (해당 사이즈보다 크면 PC 설정값이 적용됩니다.)</span>
						</div>
						<div class="number_box">
							<span class="box_title">태블릿으로 인식되는 넓이</span>
							<input type="text" name="recognition_tablet" placeholder="인식 화면 너비" value="<?=$popup_view["recognition_tablet"]?>" />
							<span>pixel</span><span> (해당 사이즈보다 크면 태블릿 설정값이 적용됩니다.)</span>
						</div>
					</td>
				</tr>
				<tr class="responsive_tr">
					<th class="ta_left" scope="row">팝업 크기</th>
					<td>
						<div class="number_box">
							<span class="box_title">PC 팝업 너비*높이 기입</span>
							<input type="text" name="width_responsive_pc" placeholder="너비" value="<?=$popup_view["width_responsive_pc"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="height_responsive_pc" placeholder="높이" value="<?=$popup_view["height_responsive_pc"] == 0 ? '' : $popup_view["height_responsive_pc"] ?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">태블릿 팝업 너비*높이 기입</span>
							<input type="text" name="width_responsive_tablet" placeholder="너비" value="<?=$popup_view["width_responsive_tablet"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="height_responsive_tablet" placeholder="높이" value="<?=$popup_view["height_responsive_tablet"] == 0 ? '' : $popup_view["height_responsive_tablet"]?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">모바일 팝업 너비 기입</span>
							<input type="text" name="width_responsive_mobile" placeholder="너비" value="<?=$popup_view["width_responsive_mobile"]?>" />
							<span>%</span><span> (모바일 높이값은 가로값에 맞춰 유동적으로 적용됩니다.) </span>
							<!--input type="text" name="height_responsive_mobile" placeholder="높이" value="<?=$popup_view["height_responsive_mobile"] == 0 ? '' : $popup_view["height_responsive_mobile"]?>" />
							<span>pixel</span-->
						</div>
					</td>
				</tr>
				<tr class="responsive_tr">
					<th class="ta_left" scope="row">팝업 위치</th>
					<td>
						<div class="number_box">
							<span class="box_title">PC 팝업 노출 위치</span>
							<input type="text" name="toppx_responsive_pc" placeholder="상단 위치" value="<?=$popup_view["toppx_responsive_pc"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="leftpx_responsive_pc" placeholder="좌측 위치" value="<?=$popup_view["leftpx_responsive_pc"]?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">태블릿 팝업 노출 위치</span>
							<input type="text" name="toppx_responsive_tablet" placeholder="상단 위치" value="<?=$popup_view["toppx_responsive_tablet"]?>" />
							<span>pixel</span><span> * </span>
							<input type="text" name="leftpx_responsive_tablet" placeholder="좌측 위치" value="<?=$popup_view["leftpx_responsive_tablet"]?>" />
							<span>pixel</span>
						</div>
						<div class="number_box">
							<span class="box_title">모바일 팝업 노출 위치</span>
							<input type="text" name="toppx_responsive_mobile" placeholder="상단 위치" value="<?=$popup_view["toppx_responsive_mobile"]?>" />
							<span>pixel</span><span> (모바일 팝업은 화면 가로값의 가운데로 노출 위치값이 고정됩니다.) </span>
							<!--input type="text" name="leftpx_responsive_mobile" placeholder="좌측 위치" value="<?=$popup_view["leftpx_responsive_mobile"]?>" />
							<span>pixel</span-->
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="">
				<colgroup>
					<col width="10%" />
					<col width="19.5%" />
					<col width="8.5%" />
					<col width="15.5%" />
					<col width="8.5%" />
					<col width="15.5%" />
					<col width="8.5%" />
					<col width="*" />
				</colgroup>	
				<tr>
					<th class="ta_left" scope="row">팝업 형태</th>
					<td>
						<input type="radio" class="tbB-input2" id="type-1" name="type" value="1" <?if($popup_view["type"] == "1"):?>checked<?endif?> /> <label for="type-1">레이어</label>
						<input type="radio" class="tbB-input2" id="type-2" name="type" value="2" <?if($popup_view["type"] == "2"):?>checked<?endif?> /> <label for="type-2">윈도우</label>
					</td>
					<th class="ta_left" scope="row">공개 여부</th>
					<td>
						<input type="radio" class="tbB-input2" id="open-y" name="open" value="y" <?if($popup_view["open"] == "y"):?>checked<?endif?> /> <label for="open-y">공개</label>
						<input type="radio" class="tbB-input2" id="open-n" name="open" value="n" <?if($popup_view["open"] == "n"):?>checked<?endif?> /> <label for="open-n">비공개</label>
					</td>

					<th class="ta_left" scope="row">노출 시작일</th>
					<td><input type="text" name="sdate" class="startdate" maxlength="10" value="<?=$popup_view["sdate_date"]?>" /></td>
					<th class="ta_left" scope="row">노출 종료일</th>
					<td><input type="text" name="edate" class="enddate" maxlength="10" value="<?=$popup_view["edate_date"]?>" /></td>
				</tr>

				<tr>
					<?php if($this->_site_language["multilingual"]) : ?>
					<th class="ta_left">언어</th>
					<td>
						<select name="language">
							<?php foreach($this->_site_language["set_language"] as $key => $value) :?>
								<option value="<?=$key?>" <?=$popup_view["language"] == $key || (!$popup_view["language"] && $this->_site_language['defalult'] == $key) ? "selected" : ""?>><?=$value?></option>
							<?php endforeach ?>
						</select>
					</td>
					<?php else :?>
					<input type="hidden" name="language"  value="<?=$this->_site_language["default"]?>" />
					<?php endif ?>
					<th class="ta_left" scope="row">제목</th>
					<td colspan="<?php if($this->_site_language["multilingual"]) : ?>5<?php else :?>7<?php endif ?>"><input type="text" name="title" value="<?=$popup_view["title"]?>"/></td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">내용</th>
					<td colspan="7">
						<div class="editor-box">
							<textarea name="content" id="content" class="editor"><?=$popup_view["content"]?></textarea>
						</div>
						<script>attachSmartEditor("content", "popup");</script>
					</td>
				</tr>
				<? if($this->input->get("no", true)) : ?>
				<tr>
					<th class="ta_left" scope="row">등록일</th>
					<td><?=$popup_view["regdt"]?></td>
					<th class="ta_left" scope="row">작성자</th>
					<td><?=$popup_view["regname"]?></td>
					<th class="ta_left" scope="row">수정일</th>
					<td><?=$popup_view["updatedt"]?></td>
					<th class="ta_left" scope="row">수정자</th>
					<td><?=$popup_view["updatename"]?></td>
				</tr>
				<? endif ?>
			</table>
		</div><!--table_write-->
	<?=form_close()?>
	<div class="pop_info"><img src="/lib/admin/images/bnr_admin_popup.jpg" alt=""/></div>
	<div class="terms_privecy_box">
		<dl>
			<dt>- 레이어 팝업과 윈도우 팝업의 차이점이 무엇인가요?</dt>
			<dd>레이어 팝업은 새창이 뜨지 않고, 현재 보이는 창 내에서 팝업을 제공하는 형태입니다. 윈도우 팝업은 새창으로 팝업이 뜨는 일반적인 팝업입니다.<br><br></dd>
		</dl>
		<dl>
			<dt>- 팝업은 어디에서 뜨나요?</dt>
			<dd>팝업관리에서 설정하시는 팝업은 홈페이지의 메인페이지에서만 뜨게끔 되어있습니다. <br>* 다른 서브페이지에서 팝업이 뜨길 원하시는 경우, 별도서비스로 고객센터로 문의 시 관련 안내를 받으실 수 있습니다.<br><br></dd>
		</dl>
		<dl>
			<dt>- 공개여부는 어떤 기능인가요?</dt>
			<dd>팝업을 미리 만들어 놓고, 잠시 감춰두고 싶으실 때 "비공개" 설정을 통해 활용하실 수 있는 기능입니다.<br><br></dd>
		</dl>
		<dl>
			<dt>- 상단간격 / 좌측간격은 무엇인가요?</dt>
			<dd>홈페이지 상단과 좌측을 기준으로, 팝업이 뜨는 위치를 잡아주는 것으로, 설정하신 간격만큼 띄워져서 팝업이 뜨게 됩니다.<br><br></dd>
		</dl>
		<dl>
			<dt>- 너비 / 높이는 무엇인가요?</dt>
			<dd>너비와 높이는 팝업의 전체 크기입니다. 제작하고자 하시는 팝업의 크기를 확인하시어, 기입해주세요.<br><br></dd>
		</dl>
		<dl>
			<dt>- 시작일 / 종료일은 어떤 기능인가요?</dt>
			<dd>팝업이 뜨는 날짜와 팝업이 더이상 뜨지 않는 날짜를 설정하여 관리할 수 있는 기능입니다.<br>시작일에 팝업이 뜨기 시작하는 날짜를, 종료일에 팝업이 뜨는 마지막 날짜를 설정해주시면, 별도의 수정없이 자동으로 해당 날짜 0시부터 팝업이 뜨고, 삭제됩니다.<br><br></dd>
		</dl>
	</div>
</div><!-- // contents -->