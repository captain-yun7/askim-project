<script>
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				level : {required : true, number : true},
				gradenm : {required : true, rangelength: [1, 10]}
			}, messages : {
				level : {required : "레벨을 입력해주세요.", number : "숫자만 입력가능합니다."},
				gradenm : {required : "등급명을 입력해주세요.", rangelength: $.validator.format("등급명은 {0}~{1}자까지 입력가능합니다.")}
			}
		});
	});

	function authDelete() {
		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}
		frm = $("form[name='frm']");

		frm.prop("action", "member_auth_delete");
		frm.submit();
	}

	function authSave() {
		var frm = $("form[name='frm']");

		if(!frm.valid()) {
			return false;
		}

		if($(":checkbox[name^='nav[']:checked", frm).length < 1) {
			alert("최소 한개이상 하위메뉴가 선택되있어야 합니다.");
			return false;
		}

		frm.prop("action", "");
		frm.submit();
	}

	function navChange(nav) {
		$(".left-tr").addClass("hide").filter("#left-"+ nav +"-tr").removeClass("hide");
	}

	$('#leftmenu >ul > li:nth-of-type(2)').addClass('on');
</script>
<? if($this->input->get("level", true)) : ?>
<script>
	$('#leftmenu >ul > li:nth-of-type(2)').find('ul li:nth-of-type(2) a').text('관리자 등급 수정');
</script>
<? endif; ?>
<div id="contents">
	<div class="main_tit">
		<h2>관리자 등급 <? if($this->input->get("level", true)) : echo "수정"; else : echo "등록"; endif; ?></h2>
		<div class="btn_right btn_num3">
			<? if($this->input->get("level", true)) : ?><a href="javascript://" onclick="authDelete();" class="btn gray sel_minus">삭제</a><? endif; ?>
			<a href="member_auth" class="btn gray">목록</a>
			<a href="javascript://" onclick="authSave(document.frm);" class="btn point"><? if($this->input->get("level", true)) : echo "수정"; else : echo "저장"; endif; ?></a>
		</div>
	</div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="14%" />
					<col width="38%" />
					<col width="14%" />
					<col width="38%" />
				</colgroup>
				<tbody>
					<tr>
						<th scope="col">레벨</th>
						<td><input type="text" class="inq_w50p" name="level" placeholder="레벨은 숫자만 기입가능합니다" value="<?if(isset($member_grade_view["level"])) :?><?=$member_grade_view["level"]?><?endif?>" <? if($this->input->get("level", true)) : echo "readonly"; endif; ?> /></td>
						<th scope="col">등급명</th>
						<td><input type="text" class="inq_w50p" name="gradenm" placeholder="등급명을 기입해주세요" value="<?if(isset($member_grade_view["gradenm"])) :?><?=$member_grade_view["gradenm"]?><?endif?>" /></td>
					</tr>
					<tr>
						<th>메뉴</th>
						<td colspan="3">
							<div class="relative">
							<? unset($this->_adm_menu["auth"]) ?>
							<?php
							foreach($this->_adm_menu as $key => $value) :
								if($key == "menu" && $this->session->admin_member['userid'] != "superman") continue;
							?>
								<input type="radio" id="r-<?=$key?>" name="nav" value="<?=$key?>" onchange="navChange('<?=$key?>');" class="grad_radio"/><label for="r-<?=$key?>" class="grad_radio_label"><?=$value["name"]?></label>
							<?php
							endforeach;
							?>
							</div>
						</td>
					<tr>
					<? foreach($this->_adm_menu as $key => $value) : ?>
						<tr id="left-<?=$key?>-tr" class="left-tr hide">
							<th><?=$value["name"]?> 하위메뉴</th>
							<td colspan="3">
								<? foreach($this->_adm_menu[$key]["low_menu"] as $subValue) : ?>
									<input type="checkbox" id="r-<?=$key?>-<?=$subValue["segment"]?>" name="nav[<?=$key?>][]" value="<?=$subValue["segment"]?>" <? if(isset($this->_adm_auth[$this->input->get("level", true)][$key]) && in_array($subValue["segment"], $this->_adm_auth[$this->input->get("level", true)][$key])) : echo "checked"; endif; ?> /><label for="r-<?=$key?>-<?=$subValue["segment"]?>"><?=$subValue["name"]?></label>
								<? endforeach; ?>
							</td>
						</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
	<div class="terms_privecy_box">
		<dl>
			<dt>- "게시글 관리" 권한 설정시 주의사항</dt>
			<dd>
			<em class="point">"게시글관리"</em> 에 권한을 주면 <em>일괄적으로 모든 게시판에</em> 게시글 쓰기, 게시글 보기, 게시글에 대한 답변, 댓글 및 이동·삭제·복사 권한이 주어집니다.<br/><br/>
			</dd>
		</dl>
		<dl>
			<dt>- "레벨" 주의사항</dt>
			<dd>
			<em class="point">"레벨"</em> 은 <em>80부터 98이하의 숫자</em>만 사용 가능합니다.<br/><br/>
			</dd>
		</dl>
	</div>
</div>