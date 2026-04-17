<script src="/lib/admin/js/admin_goods.js"></script>
<script>
	var get_data = <?=json_encode($category_list)?>;

	$(function() {
		for(var i = 0; i < get_data.length; i++) {
			var nodeClass = 'folder';
			var row = get_data[i];
			var str = '';

			if(row.yn_end == 'y') { // 하위노드가 있는지 체크
				nodeClass = 'file';
			}

			str += '<li id="r-'+ row.category +'">';
			str += '		<span class="'+ nodeClass +'" onclick="set_category(\''+ row.category +'\', this)">'+ row.categorynm +'</span>';
			if(nodeClass == 'folder') {
				var id_name = 'folder-'+ row.category;
				str += '	<ul id="'+ id_name +'" class="menu_tree"></ul>';
			}
			str += '</li>';

			if(row.level == '1') {
				$(".menu_tree_top").append(str);
			} else {
				$("#folder-"+ row.category.substring(0, row.category.length - 3)).append(str)
			}
		}

		$(".menu_tree li span").on('click', function () {
			if ($(this).hasClass("folder")) {
				$(this).toggleClass("close")
				$(this).next().slideToggle("fast");
			}
			$(".menu_tree li span").removeClass("selected");
			$(this).addClass("selected");
		});


		$("form[name='frm']").validate({
			rules : {
				category1 : {required : false},
				category2 : {required : false},
				category3 : {required : false},
				category4 : {required : false, maxlength : {depends : function() {return !!this.value}}},
				<? if($this->_site_language["multilingual"]){ ?>
					<? foreach($this->_site_language["set_language"] as $key => $val){ ?>
						categorynm_<?=$key?>  : {required : true, maxlength : 20},
					<? } ?>
				<? }else{ ?>
					categorynm  : {required : true, maxlength : 20},
				<? } ?>
				yn_use : {required : false},
				yn_state : {required : false},
				sort : {required : false, number : true},
			}, messages : {
				category1 : {required : "카테고리를 선택해주세요."},
				category2 : {required : "카테고리를 선택해주세요."},
				category3 : {required : "카테고리를 선택해주세요."},
				category4 : {required : "카테고리를 선택해주세요.", maxlength : "3차 카테고리까지만 하위등록이 가능합니다."},
				<? if($this->_site_language["multilingual"]){ ?>
					<? foreach($this->_site_language["set_language"] as $key => $val){ ?>
						categorynm_<?=$key?> : {required : "카테고리명(<?=$val?>)을 입력해주세요.", maxlength: $.validator.format("카테고리명은 {0}자 이하입니다.")},
					<? } ?>
				<? }else{ ?>
					categorynm : {required : "카테고리명을 입력해주세요.", maxlength: $.validator.format("카테고리명은 {0}자 이하입니다.")},
				<? } ?>
				yn_use : {required : "사용유무를 선택해주세요."},
				yn_state : {required : "활성유무를 선택해주세요."},
				sort : {required : "순서를 입력해주세요.", number : "숫자만 입력가능합니다."},
			}
		});
	});

	function category_preview() {
		var obj = $("form[name='frm']").serializeObject();
		var category = obj.category5 || obj.category4 || obj.category3 || obj.category2 || obj.category1;
		if(category) {
			window.open("/goods/goods_list?cate="+category+"&language="+language);
		}else{
			window.open("/?"+"language="+language);
		}
	}

	function set_category(category ,ele) {
		$(".btn_mini").removeClass("selected").filter(ele).addClass("selected");

		var set_data = {
			"category" : category
		}

		$.ajax({
			url : "/admin/goods/category_info",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							select_category(result.data.category, 1, 'category_reg');
						} else { //error
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}

	function category_save() {
		var frm = $("form[name='frm']");

		if(!frm.valid()){
			return false;
		}

		frm.submit();
	}

</script>
<div id="contents">
	<?=form_open("", array("name" => "frm"))?>
	<input type="hidden" name="mode" value="<?=$mode?>" />
	<div class="main_tit">
		<h2>카테고리 등록</h2>
		<div class="btn_right">
			<a href="javascript://" onclick="category_save();"  class="btn point">등록</a>
		</div>
	</div>
	<div class="cate_tit_wrap">
		<strong>카테고리 등록</strong>
		<div class="table_write_info">카테고리 정보를 기입 후, 카테고리 등록 버튼을 눌러 신규 카테고리를 등록하실 수 있습니다.</div>
	</div>
	<div class="cate_box_wrapper clear">
		<div class="table_list table_cate">
			<div class="clear">
				<!--a href="./category_list"  class="btn gray sel_minus">카테고리 수정</a-->
				<h3>카테고리 목록</h3>
			</div>
			<div class="menu_tree_box">
				<ul class="menu_tree menu_tree_top"></ul>
			</div>
		</div>

		<div class="cate_select_box">
			<div class="cate_step_wrap">
				<h3>신규 카테고리 정보</h3>
				<select name="category1" id="category1" class="categorySelect" onchange="select_category(this.value, 1, 'category_reg');"></select>
				<select name="category2" id="category2" class="categorySelect" onchange="select_category(this.value, 2, 'category_reg');"></select>
				<select name="category3" id="category3" class="categorySelect" onchange="select_category(this.value, 3, 'category_reg');"></select>
				<!-- <select name="category4" id="category4" class="categorySelect" onchange="select_category(this.value, 4, 'category_reg');"></select> -->
				<script>select_category("", "1", 'category_reg');</script>
			</div>
			<div class="table_write">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="21%" />
						<col width="19%" />
						<col width="11%" />
						<col width="19%" />
						<col width="11%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<? if($this->_site_language["multilingual"]){ ?>
							<? foreach($this->_site_language["set_language"] as $key => $val){ ?>
								<tr>
									<th scope="col" class="ta_left">카테고리명 <span>(<?=$val?>)</span></th>
									<td colspan="5">
										<input type="hidden" name="yn_end" />
										<input type="text" name="categorynm_<?=$key?>" class="cate_name_input" placeholder="최대 20글자까지 입력하실 수 있습니다." />
										<a href="javascript://" onclick="category_preview();" class="btn_mini">미리보기</a>
									</td>
								</tr>
							<? } ?>
						<? }else{ ?>
								<tr>
									<th scope="col" class="ta_left">카테고리명 <span>(국문)</span></th>
									<td colspan="5">
										<input type="hidden" name="yn_end" />
										<input type="text" name="categorynm" class="cate_name_input" placeholder="최대 20글자까지 입력하실 수 있습니다." />
										<a href="javascript://" onclick="category_preview();" class="btn_mini">미리보기</a>
									</td>
								</tr>
						<? } ?>
						<tr>
							<th scope="col" class="ta_left">카테고리 순서</th>
							<td><input type="text" name="sort" class="cate_name_input" placeholder="숫자만 입력"/></td>
							<th scope="col" class="ta_left">사용유무</th>
							<td>
								<!-- select name="yn_use">
									<option value="y">사용</option>
									<option value="n">사용안함</option>
								</select><br/>
								<!-- select[name="yn_use"]을 아래 input 라디오로 바꿔주세요! -->
								<input type="radio" class="tbB-input2" name="yn_use" id="yn_use_y" value="y" checked /> <label for="yn_use_y">사용</label>
								<input type="radio" class="tbB-input2" name="yn_use" id="yn_use_n" value="n" /> <label for="yn_use_n">미사용</label>
							</td>
							<th scope="col" class="ta_left">활성유무</th>
							<td >
								<!-- select name="yn_state">
									<option value="y">활성</option>
									<option value="n">비활성</option>
								</select><br/>
								<!-- select[name="yn_state"]을 아래 input 라디오로 바꿔주세요! -->
								<input type="radio" class="tbB-input2" name="yn_state" id="yn_state_y" value="y" checked /> <label for="yn_state_y">사용</label>
								<input type="radio" class="tbB-input2" name="yn_state" id="yn_state_n" value="n" /> <label for="yn_state_n">미사용</label>
							</td>
						</tr>
						<tr>
							<th scope="col" class="ta_left">접근권한</th>
							<td colspan="5">
								<select name="access_auth">
									<?php foreach($admin_grade_list as $key => $value) :?>
										<option value="<?=$value["level"]?>">[관리자]<?=$value["gradenm"]?></option>
									<?php endforeach ?>
									<option disabled>--------------------------------</option>
									<?php foreach($member_grade_list as $key => $value) :?>
										<option value="<?=$value["level"]?>" ><?=$value["gradenm"]?></option>
									<?php endforeach ?>
									<option disabled>--------------------------------</option>
									<option value="0" >비회원</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="terms_privecy_box">
				<dl>
					<dt>- 카테고리를 수정하고 싶어요.</dt>
					<dd>
					이미 생성한 카테고리를 수정하시려면, 상단에서 "카테고리 관리" 탭을 눌러 이동하신 후 수정 가능합니다.<br><br>
					</dd>
				</dl>
				<dl>
					<dt>- 카테고리 순서를 변경할 수 있나요?</dt>
					<dd>
					수정하고자 하는 카테고리를 선택 후, 우측에서 "카테고리 순서"에 숫자를 수정해주세요.<br>
					* 숫자가 동일한 경우, 마지막 수정일 기준으로 노출 우선순위가 정해집니다.<br><br>
					</dd>
				</dl>
				<dl>
					<dt>- 카테고리 "사용유무"는 어떤 기능인가요?</dt>
					<dd>
					카테고리 사용유무 기능은, 홈페이지에서 카테고리 및 카테고리에 속한 상품 전체를 미노출 시키는 기능입니다.<br>
					아직 홈페이지에서 노출시키고 싶지 않은 카테고리의 경우, "사용유무"를 "사용안함"으로 선택하세요.<br/>
					* 관리자페이지에서 상품등록 및 하위 카테고리 생성, 카테고리 설정값 수정은 가능합니다.<br><br>
					</dd>
				</dl>
				<dl>
					<dt>- 카테고리 "활성유무"는 어떤 기능인가요?</dt>
					<dd>
					카테고리 활성유무 기능은, 홈페이지에서 해당 카테고리에 속한 상품의 상세페이지 진입을 제한하는 기능입니다.<br>
					홈페이지에서 카테고리는 노출되나, 상세페이지만 진입을 금지하고 싶은 경우, "활성유무"를 "활성안함"으로 선택하세요.<br/>
					* 관리자페이지에서 상품등록 및 하위 카테고리 생성, 카테고리 설정값 수정은 가능합니다.<br><br>
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<?=form_close()?>
</div>