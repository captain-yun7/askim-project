<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script>
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				language : {required : true},
				title : {required : true, rangelength : [2, 25]},
				type : {required : true},
				content : {editorRequired : {depends : function() {return !getSmartEditor("content")}}},
				open : {required : true},
				toppx : {required : true, number : true},
				leftpx : {required : true, number : true},
				width : {required : true, number : true},
				height : {required : true, number : true},
				sdate : {required : true, dateValid : true},
				edate : {required : true, dateValid : true}
			}, messages : {
				language : {required : "언어를 선택해주세요."},
				title : {required : "제목을 입력해주세요.", rangelength : $.validator.format("제목은 {0}~{1}자입니다.")},
				type : {required : "형태를 선택해주세요."},
				content : {editorRequired : "내용을 입력해주세요."},
				open : {required : "공개여부를 선택해주세요."},
				toppx : {required : "상단간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				leftpx : {required : "좌측간격을 입력해주세요.", number : "숫자만 입력가능합니다."},
				width : {required : "너비를 입력해주세요.", number : "숫자만 입력가능합니다."},
				height : {required : "높이를 입력해주세요.", number : "숫자만 입력가능합니다."},
				sdate : {required : "팝업 시작일을 입력해주세요.", dateValid : "날짜를 제대로 입력해주세요. (YYYY-mm-dd)"},
				edate : {required : "팝업 종료일을 입력해주세요.", dateValid : "날짜를 제대로 입력해주세요. (YYYY-mm-dd)"}
			}
		});
	});

	function popup_save(form) {
		var frm = $(form)
		if(!frm.valid()) {
			return false;
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
</script>
<div id="contents">
<?=form_open("", array("name" => "frm"));?>
<input type="hidden" name="mode" value="<?=$mode?>" />
<input type="hidden" name="no" value="<?=$this->input->get("no", true)?>" />
	<div class="main_tit">
		<h2>팝업 등록/수정</h2>
		<div class="btn_right btn_num2">
			<a href="javascript://" onclick="popup_save(document.frm);" class="btn point">저장</a>
			<? if($this->input->get("no", true)) : ?><a href="javascript://" onclick="popup_delete(document.frm);" class="btn black">삭제</a><? endif ?>
			<a href="popup_list" class="btn gray">목록</a>
		</div><!--btn_center-->
	</div><!--main_tit-->

		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableB">
				<colgroup>
					<col width="8.5%" />
					<col width="19.5%" />
					<col width="8.5%" />
					<col width="15.5%" />
					<col width="8.5%" />
					<col width="15.5%" />
					<col width="8.5%" />
					<col width="*" />
				</colgroup>
				<tr>
					<th class="ta_left" scope="row">형태</th>
					<td>
						<input type="radio" id="type-1" name="type" value="1" <?if($popup_view["type"] == "1"):?>checked<?endif?> /> <label for="type-1">레이어 팝업</label>
						<input type="radio" id="type-2" name="type" value="2" <?if($popup_view["type"] == "2"):?>checked<?endif?> /> <label for="type-2">윈도우 팝업</label>
					</td>
					<th class="ta_left" scope="row">공개</th>
					<td>
						<input type="radio" id="open-y" name="open" value="y" <?if($popup_view["open"] == "y"):?>checked<?endif?> /> <label for="open-y">공개</label>
						<input type="radio" id="open-n" name="open" value="n" <?if($popup_view["open"] == "n"):?>checked<?endif?> /> <label for="open-n">비공개</label>
					</td>
					<th class="ta_left" scope="row">시작일</th>
					<td><input type="text" name="sdate" class="startdate" maxlength="10" style="width:200px;" value="<?=$popup_view["sdate_date"]?>" /></td>
					<th class="ta_left" scope="row">종료일</th>
					<td><input type="text" name="edate" class="enddate" maxlength="10" style="width:200px;" value="<?=$popup_view["edate_date"]?>" /></td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">상단간격</th>
					<td><input type="text" name="toppx" style="width:100px;" placeholder="숫자만입력" value="<?=$popup_view["toppx"]?>" /> px</td>
					<th class="ta_left" scope="row">좌측간격</th>
					<td><input type="text" name="leftpx" style="width:100px;" placeholder="숫자만입력" value="<?=$popup_view["leftpx"]?>" /> px</td>
					<th class="ta_left" scope="row">너비</th>
					<td><input type="text" name="width" style="width:100px;" placeholder="숫자만입력" value="<?=$popup_view["width"]?>" /> px</td>
					<th class="ta_left" scope="row">높이</th>
					<td><input type="text" name="height" style="width:100px;" placeholder="숫자만입력" value="<?=$popup_view["height"]?>" /> px</td>
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
					<td colspan="<?php if($this->_site_language["multilingual"]) : ?>5<?php else :?>7<?php endif ?>"><input type="text" name="title" value="<?=$popup_view["title"]?>" style="max-width:100%;width:100%;"/></td>
				</tr>
				<tr>
					<th class="ta_left" scope="row">내용</th>
					<td colspan="7">
						<div class="editor-box" style="max-width:100%;">
							<textarea name="content" id="content" class="editor" style="width:1016px; height:400px;"><?=$popup_view["content"]?></textarea>
						</div>
						<script>attachSmartEditor("content", "popup");</script>
					</td>
				</tr>
				<? if($this->input->get("no", true)) : ?>
				<tr>
					<th class="ta_left" scope="row">등록일</th>
					<td style="vertical-align:middle;"><?=$popup_view["regdt"]?></td>
					<th class="ta_left" scope="row">작성자</th>
					<td style="vertical-align:middle;"><?=$popup_view["regname"]?></td>
					<th class="ta_left" scope="row">수정일</th>
					<td style="vertical-align:middle;"><?=$popup_view["updatedt"]?></td>
					<th class="ta_left" scope="row">수정자</th>
					<td style="vertical-align:middle;"><?=$popup_view["updatename"]?></td>
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