<script type="text/javascript" src="/lib/admin/js/admin_member.js?v=20200427"></script>
<script>
	var Common_Member = new common_member({
		<? if($this->input->get("userid", true)) : ?>userid : "<?=$this->input->get("userid", true)?>"<? endif ?>
	});

	$(function() {
		$("form[name='frm']").validate({});
		<? if($this->input->get("userid", true)) : ?>
			language_change("<?=$this->input->get('registLanguage', true)?>");
		<? else : ?>
			language_change("<?=$this->_site_language['default']?>");
		<? endif; ?>

		$("input[id^='changePassword']").on("click", function() {
			var f = $(this).is(":checked") == true ? false : true;
			if(f == false) alert("관리자 페이지에서 비밀번호를 변경할 경우, 회원이 로그인을 할 수 없을 수 있습니다.\n회원의 비밀번호 분실로 인한 요청 시에만 비밀번호를 변경하시길 권장드립니다.");
			$(".change-password").attr("readonly", f);
		});
	});
	var site_language = "<?=$this->_site_language['default']?>";
    var member_require_field = JSON.parse('<?= json_encode($memberField["require"]); ?>'); // 언어별 필수 설정 필드
	function memberSave() {
		var frm = $("form[name='frm']");

		<? foreach($this->_site_language["set_language"] as $setLanguageKey => $setLanguageValue) : ?>
			<? foreach($memberField["name"][$setLanguageKey] as $key => $value) : ?>
				<? if($memberField["type"][$setLanguageKey][$key] == "email") : ?>
					<? if ($key != 'email') : ?>
						$("[name='<?=$key?>_<?=$setLanguageKey?>']").val($("[name='<?=$key?>_<?=$setLanguageKey?>_id']").val() +"@"+ $("[name='<?=$key?>_<?=$setLanguageKey?>_domain']").val());
					<? endif; ?>
				<? endif; ?>
			<? endforeach; ?>
		<? endforeach; ?>

        let emailKey = "input[name='email_duplicate_"+site_language+"']";
        if(document.querySelector(emailKey).value != "y") {
            alert("이메일 중복확인을 해주세요.");
            return false;
        }

		if(!frm.valid()){
			return false;
		}
        frm.submit();
	}

	function language_change(language, obj) {
		if (obj){
			site_language = $(obj).val();
		}else{
			site_language = language;
		}

		$(".table_write").each(function(i,e){

			if ($(e).hasClass(site_language)){
				if($(e).hasClass("hide")){
					$(e).removeClass("hide");
				}
				$(e).find('input,select').prop('disabled', false);
			}else{
				if (!$(e).hasClass("hide")){
					$(e).addClass("hide");
				}
				$(e).find('input,select').prop('disabled', true);
			}
		});

		$('select[name="language"]').val(site_language);


		$('input[name="userid_'+site_language+'"]').rules('add', {required : true, rangelength: [4, 14], onlyNumEngValid : true, messages : {required : "<?=$memberField["name"][kor]["userid"]?>를 입력해주세요.", rangelength: $.validator.format("<?=$memberField["name"][kor]["userid"]?>는 {0}~{1}자입니다."), onlyNumEngValid : "<?=$memberField["name"][kor]["userid"]?>는 영어, 숫자만 사용 가능합니다."  }
		});

		$('input[name="userid_duplicate_'+site_language+'"]').rules('add', {required : function(ele){return !(ele.value == "y");}, messages : {required : "<?=$memberField["name"][kor]["userid"]?> 중복확인을 해주세요."}
		});

		$('input[name="name_'+site_language+'"]').rules('add', {required : true, maxlength : 10, messages : {required : "<?=$memberField["name"][kor]["name"]?>를 입력해주세요.",maxlength: $.validator.format("<?=$memberField["name"][kor]["name"]?>은 {0}자 이하입니다.")}
		});

		$('input[name="password_'+site_language+'"]').rules(
        'add', 
        {
        <? if($mode == "modify") { ?>
            required : false, 
        <? } else { ?>
            required : true,
        <? } ?>
            rangelength : [10, 16], 
            equalTo : "#password2-"+site_language, 
            passwordValid : {
                depends : function() {return $("[name='password_kor']").val()}
            }, 
            messages : {
                required : "<?=$memberField["name"][kor]["password"]?>를 입력해주세요.", 
                rangelength: $.validator.format("<?=$memberField["name"][kor]["password"]?>는 {0}~{1}자입니다."), 
                equalTo : "비밀번호가 일치하지 않습니다.",
                passwordValid : "<?=$memberField["name"][kor]["password"]?>는 영어/숫자/특수문자를 2종류 이상 혼용하여 사용해야 합니다."
            }
		});

		$('input[name="email_duplicate_'+site_language+'"]').rules('add', {required : true, messages : {required : "<?=$memberField["name"][kor]["email"]?> 중복확인을 해주세요."}
		});

		$('input[name="email_'+site_language+'"]').rules('add', {required : true, messages : {required : "<?=$memberField["name"][kor]["email"]?>를 입력해주세요."}
		});

        //if(member_require_field[site_language]['birth']) {
		$("input[name='birth_" + site_language + "']").rules("add", {
			required: true,
			rangelength: [8, 8],
			number: true,
			man14Valid: true,
			messages: {
				required: "생년월일을 입력해 주세요.",
				rangelength: $.validator.format("생년월일을 입력해주세요. ex)19900101"),
				number: "숫자만 입력가능합니다.",
				man14Valid: "14세 미만 회원가입 시 법정대리인의 동의가 필요합니다. 고객센터로 문의해주세요."
			}
		});
        //}

		var phoneValidFlag = false;
		var numHypenValidFlag = true;

		if (site_language == 'kor'){
			phoneValidFlag = true;
			numHypenValidFlag = false;
		}

/*
		if(member_require_field[site_language]['mobile']) {
			$("input[name='mobile_" + site_language + "']").rules("add", {
				required: true,
				phoneValid: phoneValidFlag,
				onlyNumHyphenValid: numHypenValidFlag,
				messages: {
					required: "<?=$memberField["name"]['kor']["mobile"]?>를 입력해주세요.",
					phoneValid: "올바른 <?=$memberField["name"]['kor']["mobile"]?>를 입력해주세요. ex)000-0000-0000)",
					onlyNumHyphenValid: "<?=$memberField["name"]['kor']["mobile"]?>는 숫자와 하이픈(-)만 입력 하실 수 있습니다."
				}
			});
		}

        if(member_require_field[site_language]['fax']) {
			$("input[name='fax_" + site_language + "']").rules("add", {
				required: true,
				phoneValid: phoneValidFlag,
				onlyNumHyphenValid: numHypenValidFlag,
				messages: {
					required: "<?=$memberField["name"]['kor']["fax"]?>를 입력해주세요.",
					phoneValid: "올바른 <?=$memberField["name"]['kor']["fax"]?>를 입력해주세요. ex)000-0000-0000)",
					onlyNumHyphenValid : "<?=$memberField["name"]['kor']["fax"]?>는 숫자와 하이픈(-)만 입력 하실 수 있습니다."
				}
			});
        }
*/
		/*if (site_language == 'kor'){
			$('a[name="kor_zip"]').removeClass("hide");
		}else{
			$('a[name="kor_zip"]').addClass("hide");
		}*/

		let optionStr = '';

		<? if(!$this->_admin_member["super"] && !in_array("member_auth", $this->_adm_auth[$this->_admin_member["level"]]["member"]) && array_key_exists($tmp_member_level, $member_view['level']) && isset($member_view['level'])){ ?>
			optionStr = '<option value="<?=$member_view["level"]?>" selected><?=$tmp_member_level[$member_view["level"]]?></option>';
		<? }else{ ?>
			if (site_language == 'kor'){
				<? if(in_array("member_auth", $this->_adm_auth[$this->_admin_member["level"]]["member"])){ ?>
					<? foreach($admin_grade_list as $value){ ?>
						optionStr += '<option value="<?=$value["level"]?>" <? if(isset($member_view["level"]) && $member_view["level"] == $value["level"]) : ?>selected<? endif; ?> >[관리자]<?=$value["gradenm"]?></option>';
					<? } ?>
					optionStr += '<option disabled>--------------------------------</option>';
				<? } ?>
				<? foreach($member_grade_list as $value) :?>
					optionStr += '<option value="<?=$value["level"]?>" <? if(isset($member_view["level"]) && $member_view["level"] == $value["level"]) : ?>selected<? endif; ?> ><?=$value["gradenm"]?></option>';
				<? endforeach; ?>
			}else{
				<? foreach($member_grade_list as $value) :?>
					optionStr += '<option value="<?=$value["level"]?>" <? if(isset($member_view["level"]) && $member_view["level"] == $value["level"]) : ?>selected<? endif; ?> ><?=$value["gradenm"]?></option>';
				<? endforeach; ?>
			}
		<? } ?>
		if($("#mode").val() != "modify") $('select[name="level_'+site_language+'"]').html(optionStr);
	}

	/**
	 * 이메일 도메인 변경
	 */
	function domain_select_change(language ,email) {
		var regex = /^((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

		if(regex.test($("[name='"+ email + "_" + language + "_domain_select'] option:selected").val()) === true) {
			$("[name='"+ email + "_" + language + "_domain']").hide();
			$("[name='"+ email + "_" + language + "_domain']").val($("[name='"+ email + "_" + language + "_domain_select'] option:selected").val());
		} else {
			$("[name='"+ email + "_" + language + "_domain']").val("");
			$("[name='"+ email + "_" + language + "_domain']").show();
		}
	}

	/**
	 * 이메일 도메인 변경
	 */
	function extra_domain_select_change(language ,email) {
		$("[name='"+ email + "_" + language + "_domain']").val($("[name='"+ email + "_" + language + "_domain_select'] option:selected").val());
	}
	
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');

	<? if($mode == "modify") { ?>
		$('#leftmenu >ul > li:nth-of-type(1)').find('ul li:nth-of-type(2) a').text('회원 정보수정');
	<? } else { ?>
	<? } ?>
</script>
<div id="contents">
	<div class="main_tit">
		<h2>회원 <? if($this->input->get("userid", true)) : echo "정보수정"; else : echo "등록"; endif; ?></h2>
		<div class="btn_right btn_num2">
			<!-- a href="javascript://" onclick="history.back();" class="btn gray">취소</a -->
			<a href="member_list?<?=$ref?>" class="btn gray">목록</a>
			<a href="javascript://" onclick="memberSave()" class="btn point">저장</a>
		</div><!--btn_right-->
	</div>
	<?=form_open("", array("name"=>"frm", "target"=>"ifr_processor"));?>
	<input type="hidden" name="mode" id="mode" value="<?=$mode?>" />
	<input type="hidden" name="ref" value="<?=$ref?>">
	<? foreach($this->_site_language["set_language"] as $setLanguageKey => $setLanguageValue) : ?>
	<div class="table_write <?=$setLanguageKey?> <? if($setLanguageKey != $this->_site_language['default']) : echo "hide"; endif; ?> " >
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col width="10%" />
				<col width="40%" />
				<col width="10%" />
				<col width="40%" />
			</colgroup>
			<tbody id='divList_<?=$setLanguageKey?>'>
				<tr>
					<th align="left"><?=$memberField["name"][$setLanguageKey]["userid"]?>(필수)</th>
					<td>
						<input type="text" name="userid_<?=$setLanguageKey?>" <? if($this->input->get("userid", true)) : echo "readonly"; else : echo 'onkeyup="Common_Member.duplicate_init(this.form.userid_duplicate_'.$setLanguageKey.');"'; endif; ?> value="<? if(isset($member_view["userid"])) : ?><?=$member_view["userid"]?><? endif; ?>" />
						<!-- <label for="userid" class="dn">{memberField['name'][cfg_site['language']]['userid']}</label> -->
						<input type="hidden" name="userid_duplicate_<?=$setLanguageKey?>" value="<? if ($this->input->get("userid", true)) : echo 'y'; endif; ?>"/>

                        
                        <? if($mode != "modify") { //정보 수정일 경우 아이디 중복확인 감춤 2020-06-16 ?>
						<a href="javascript://" onclick="Common_Member.userid_duplicate_check(document.frm.userid_<?=$setLanguageKey?>, '<?=$setLanguageKey?>');" class="btn_mini gray">아이디 중복확인</a>
					    <? } ?>
                    </td>
					<th align="left"><?=$memberField["name"][$setLanguageKey]["name"]?></th>
					<td><input type="text" name="name_<?=$setLanguageKey?>" value="<? if(isset($member_view["name"])) : ?><?=$member_view["name"]?><? endif; ?>" /></td>
				</tr>
				<tr>
                    <? if($this->_site_language["multilingual"]) : ?>
					<th align="left">언어구분</th>
					<td>
						<select name="language" <?if (!$this->input->get("userid", true)) : echo "onchange='language_change(".'""'.", this);'"; else: echo("onFocus='this.initialSelect = this.selectedIndex;'onChange='this.selectedIndex = this.initialSelect;'"); endif; ?> >
                            <? foreach($this->_site_language["set_language"] as $key => $value) : ?>
                                <!-- <option value="<?=$key?>" <? if($member_view["language"] == $key) : ?> selected <? endif ?> > <?=$value?></option> -->
                                <option value="<?=$key?>"><?=$value?></option>
                            <? endforeach; ?>
						</select>
						* 관리자등급의 경우 "한국어"만 가능합니다.
					</td>
                    <? else : ?>
                    <!-- 기본언어 설정 -->
                    <input type="hidden" name="language" value="<?=$setLanguageKey?>"/>
                    <? endif ?>
					<?
						$tmp_member_level = array_column($member_grade_list, 'gradenm','member_level');
					?>
					<th align="left"><?=$memberField["name"][$setLanguageKey]['level']?></th>
					<td <? if(!$this->_site_language["multilingual"]) : ?>colspan="3"<? endif ?>>
						<select name="level_<?=$setLanguageKey?>">
						<?php
						if($this->input->get("userid", true)) :
							$grade = array_merge($admin_grade_list, $member_grade_list);
							foreach($grade as $v) :
								if($this->session->userdata['admin_member']['level'] >= $v['level']) :
									$selected = $member_view['level'] == $v['level'] ? " selected" : "";
									echo "<option value='".$v['level']."'".$selected.">".$v['gradenm']."</option>";
								endif;
							endforeach;
						endif;
						?>
						</select>
					</td>
				</tr>
				<tr>
					<th align="left"><?=$memberField["name"][$setLanguageKey]["password"]?></th>
					<td>
						<?php if($this->input->get("userid", true)) : ?>
						<input type="password" name="password_<?=$setLanguageKey?>" id="password_<?=$setLanguageKey?>" class="change-password" readonly />
						<input type="checkbox" id="changePassword_<?=$setLanguageKey?>" data-lang="<?=$setLanguageKey?>">
						<label for="changePassword_<?=$setLanguageKey?>">비밀번호 변경</label>
						<?php else : ?>
						<input type="password" name="password_<?=$setLanguageKey?>" id="password_<?=$setLanguageKey?>" class="change-password" />
						<?php endif; ?>
					</td>
					<!-- 비밀번호 확인 추가됨. -->
					<? if($setLanguageKey == 'kor'){ ?>
						<th align="left"><?=$memberField["name"][$setLanguageKey]["password"]?> 확인</th>
					<? }elseif($setLanguageKey == 'eng'){ ?>
						<th align="left"><?=$memberField["name"][$setLanguageKey]["password"]?> Confirm</th>
					<? }elseif($setLanguageKey == 'chn'){ ?>
						<th align="left"><?=$memberField["name"][$setLanguageKey]["password"]?> 确认</th>
					<? }elseif($setLanguageKey == 'jpn'){ ?>
						<th align="left"><?=$memberField["name"][$setLanguageKey]["password"]?> 確認</th>
					<? } ?>
                    <td>
						<?php if($this->input->get("userid", true)) : ?>
						<input type="password" name="password2_<?=$setLanguageKey?>" id="password2-<?=$setLanguageKey?>" class="change-password" readonly />
						<?php else : ?>
						<input type="password" name="password2_<?=$setLanguageKey?>" id="password2-<?=$setLanguageKey?>" class="change-password" />
						<?php endif; ?>
					</td>
					<!-- 비밀번호 확인 추가됨. -->
				</tr>
				<? $i = 0;?>
				<? foreach($memberField["name"][$setLanguageKey] as $key => $value) : ?>
					<? if ($key == 'auto_regist_prevention_text') :  continue; endif;?>
					<? if(isset($memberField["use"][$setLanguageKey][$key]) && $memberField["use"][$setLanguageKey][$key] == "checked") : ?>
						<? if($i % 4 == 0) : ?><tr><? endif; ?>
							<? if($key == 'zip'){ ?>
								<? if($setLanguageKey == 'kor'){ ?>
									<th align="left">우편번호</th>
								<? }elseif($setLanguageKey == 'eng'){ ?>
									<th align="left">POSTAL_CODE</th>
								<? }elseif($setLanguageKey == 'chn'){ ?>
									<th align="left">邮政编码</th>
								<? }elseif($setLanguageKey == 'jpn'){ ?>
									<th align="left">郵便番号</th>
								<? } ?>
							<? }elseif($key =='country'){ ?>
								<? if($setLanguageKey == 'eng'){ ?>
									<th align="left">COUNTRY</th>
								<? }elseif($setLanguageKey == 'chn'){ ?>
									<th align="left">国家</th>
								<? }elseif($setLanguageKey == 'jpn'){ ?>
									<th align="left">配送国</th>
								<? } ?>
							<? }elseif($key =='city'){ ?>
								<? if($setLanguageKey == 'eng'){ ?>
									<th align="left">CITY</th>
								<? }elseif($setLanguageKey == 'chn'){ ?>
									<th align="left">城市</th>
								<? }elseif($setLanguageKey == 'jpn'){ ?>
									<th align="left">都市</th>
								<? } ?>
							<? }elseif($key =='state_province_region'){ ?>
								<? if($setLanguageKey == 'eng'){ ?>
									<th align="left">STATE_PROVINCE_REGION</th>
								<? }elseif($setLanguageKey == 'chn'){ ?>
									<th align="left">州/省/地区</th>
								<? }elseif($setLanguageKey == 'jpn'){ ?>
									<th align="left">州/県/地域</th>
								<? } ?>
							<? }else{ ?>
								<th align="left"><?=$value?></th>
							<? } ?>
							<td class="input_mail_add">
								<? if(in_array($memberField["type"][$setLanguageKey][$key], array("checkbox", "radio"))) :?>
									<? foreach($memberField["option"][$key]["item"][$setLanguageKey] as $subKey => $subValue) : ?>
										<input type="<?=$memberField["type"][$setLanguageKey][$key]?>" id="<?=$key?>-<?=$subKey?>-<?=$setLanguageKey?>" name="<?=$key?>_<?=$setLanguageKey?>" value="<?=$subKey?>" <? if(isset($member_view[$key]) && $member_view[$key] == $subKey) : ?>checked<? endif; ?>>
										<label for="<?=$key?>-<?=$subKey?>-<?=$setLanguageKey?>"><?=$subValue?></label>
									<? endforeach; ?>
                                <? elseif($memberField["type"][$setLanguageKey][$key] == "email") :?>
									<? if($key == "email") : ?>
										<input type="hidden" name="email_duplicate_<?=$setLanguageKey?>" <? if(isset($member_view[$key]) && $member_view[$key]) : ?>value="y"<? endif ?> />
										<?php $email = explode("@", $member_view[$key]);?>
										<input type="hidden" name="<?=$key?>_<?=$setLanguageKey?>" value="<? if(isset($member_view[$key])) : ?><?=$member_view[$key]?><? endif; ?>" />
										<input type="text" class="mail_id" name="<?=$key?>_<?=$setLanguageKey?>_id" value="<? if(isset($member_view[$key])) : ?><?=isset($email[0]) ? $email[0] : ""?><? endif; ?>" onkeyup="Common_Member.duplicate_init(this.form.email_duplicate_<?=$setLanguageKey?>, this.form.email_<?=$setLanguageKey?>);" /> @
										<input type="text" class="mail_domain" name="<?=$key?>_<?=$setLanguageKey?>_domain" value="<? if(isset($member_view[$key])) : ?><?=isset($email[1]) ? $email[1] : ""?><? endif; ?>" onchange="Common_Member.duplicate_init(this.form.email_duplicate_<?=$setLanguageKey?>, this.form.email_<?=$setLanguageKey?>);" />
										<select name="<?=$key?>_<?=$setLanguageKey?>_domain_select" onchange="domain_select_change('<?=$setLanguageKey?>','<?=$key?>'); Common_Member.duplicate_init(this.form.email_duplicate_<?=$setLanguageKey?>, this.form.email_<?=$setLanguageKey?>);">
											<? foreach($memberField["option"][$key]["item"][$setLanguageKey] as $subKey => $subValue) : ?>
												<option value="<?=$subValue?>"><?=$subValue?></option>
											<? endforeach; ?>
										<select>
										<a href="javascript://" onclick="Common_Member.email_duplicate_check(document.frm.email_<?=$setLanguageKey?>, '<?=$setLanguageKey?>');" class="btn_mini gray">중복확인</a>
									<? else : ?>
										<?php $email = explode("@", $member_view[$key]);?>
										<input type="hidden" name="<?=$key?>_<?=$setLanguageKey?>" value="<? if(isset($member_view[$key])) : ?><?=$member_view[$key]?><? endif; ?>" />
										<input type="text" class="mail_id" name="<?=$key?>_<?=$setLanguageKey?>_id" value="<? if(isset($member_view[$key])) : ?><?=isset($email[0]) ? $email[0] : ""?><? endif; ?>" /> @
										<input type="text" class="mail_domain" name="<?=$key?>_<?=$setLanguageKey?>_domain" value="<? if(isset($member_view[$key])) : ?><?=isset($email[1]) ? $email[1] : ""?><? endif; ?>" />
										<select name="<?=$key?>_<?=$setLanguageKey?>_domain_select" onchange="extra_domain_select_change('<?=$setLanguageKey?>','<?=$key?>');">
											<? foreach($memberField["option"][$key]["item"][$setLanguageKey] as $subKey => $subValue) : ?>
												<option value="<?=$subValue?>"><?=$subValue?></option>
											<? endforeach; ?>
										<select>
									<? endif; ?>
								<? elseif($memberField["type"][$setLanguageKey][$key] == "select") :?>
									<? if($key == 'country' && $setLanguageKey != 'kor') : ?>
										<select name="country_<?=$setLanguageKey?>" >
											<? foreach($country_info as $ckey => $cval): ?>
												<option value="<?=$cval['name'][$setLanguageKey]?><?=$cval['mark']?>" <? if(isset($member_view[$key]) && $member_view[$key] == $cval['name'][$setLanguageKey].$cval['mark']) : ?>selected<? endif; ?>><?=$cval['name'][$setLanguageKey]?><?=$cval['mark']?></option>
											<? endforeach; ?>
										</select>
									<? else: ?>
										<select name="<?=$key?>_<?=$setLanguageKey?>">
											<? foreach($memberField["option"][$key]["item"][$setLanguageKey] as $subKey => $subValue) : ?>
												<option value="<?=$subKey?>" <? if(isset($member_view[$key]) && $member_view[$key] == $subKey) : ?>selected<? endif; ?>><?=$subValue?></option>
											<? endforeach; ?>
										<select>
									<? endif; ?>
								<? else : ?>
									<? if($key == 'mobile' && $setLanguageKey != 'kor') : ?>
										<select name="mobile_country_code_<?=$setLanguageKey?>">
											<? foreach($country_info as $ckey => $cval): ?>
												<option value="<?=$cval['name'][$setLanguageKey]?><?=$cval['code']?>" <? if(isset($member_view['mobile_country_code']) && $member_view['mobile_country_code'] == $cval['name'][$setLanguageKey].$cval['code']) : ?>selected<? endif; ?>><?=$cval['name'][$setLanguageKey]?><?=$cval['code']?></option>
											<? endforeach; ?>
										</select>
									<? endif; ?>
									<input type="text" name="<?=$key?>_<?=$setLanguageKey?>" <? if(in_array($key, array("zip","address")) && $setLanguageKey == 'kor') : echo "readonly"; endif; ?> <? if($key == "birth") : ?> placeholder="ex) 19990101" <? elseif(in_array($key, array("mobile", "fax"))) : ?> placeholder="ex) 010-1234-5678" <? endif; ?>value="<? if(isset($member_view[$key])) : ?><?=$member_view[$key]?><? endif; ?>" />
									<? if($key == "address" && $setLanguageKey == 'kor') : ?><a name="kor_zip" href="javascript://" onclick="searchAddress(document.frm.zip_<?=$setLanguageKey?>, document.frm.address_<?=$setLanguageKey?>, document.frm.address2_<?=$setLanguageKey?>)"  class="btn_mini point">주소검색</a><? endif; ?>
								<? endif; ?>
							</td>
						<?if($i % 4 == 1 || $i == count($memberField["name"][$setLanguageKey]) - 1) : ?></tr><? endif; ?>
						<? $i++; ?>
					<? endif; ?>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
	<? endforeach; ?>
	<div class="terms_privecy_box">
		<? if($this->_site_language["multilingual"]) : ?>
		<dl>
			<dt>- 다국어 사용시 주의사항</dt>
			<dd>
			회원 등록시, <em class="point">관리자 등급</em>의 경우, 언어는 <em class="point">"한국어"</em>만 지원됩니다. <br>
			이미 등록한 회원의 정보 수정시에는 <em class="point">언어 변경이 불가능</em>합니다.<br><br>
			</dd>
		</dl>
		<? endif ?>
		<dl>
			<dt>- 회원정보 수정시 주의사항</dt>
			<dd>
			<em class="point">비밀번호</em>의 경우, 수정을 임의로 진행하면, 차후 회원이 기입했던 비밀번호와 맞지않는 문제가 발생할 수 있으니 주의하시기 바랍니다.<br>
			<em class="point">이메일</em>의 경우, 수정을 임으로 진행하면, 차후 회원이 id/pw 를 분실하거나, 문의글 작성 후 메일을 회신 받을 때 문제될 수 있으니 주의하시기 바랍니다.<br><br>
			</dd>
		</dl>
	</div>
	<div class="btn_paging">
		<div class="paging"></div>
	</div><!--btn_paging-->
	<?=form_close();?>
</div>
