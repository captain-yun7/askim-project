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
			
			var ox_use = row.yn_use == 'y' ? 'o' : 'x';
			var ox_state = row.yn_state == 'y' ? 'o' : 'x';

			str += '<li id="r-'+ row.category +'">';
			//str += '<span class="'+ nodeClass +'" onclick="set_category(\''+ row.category +'\', this)">'+ row.categorynm +'<span>사(' + row.yn_use.toUpperCase() + ') 활(' + row.yn_state.toUpperCase() + ')</span></span>';
			str += '<span class="'+ nodeClass +'" onclick="set_category(\''+ row.category +'\', this)">'+ row.categorynm +'<div class="cate_tree_used"><span class="tree_used use_' + ox_use + '"><em>사용</em>(' + ox_use.toUpperCase() + ')</span><span></span><span class="tree_active use_' + ox_state + '"><em>활성</em>(' + ox_state.toUpperCase() + ')</span></span></div></span>';
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
				category1 : {required : true},
				category2 : {required : false},
				category3 : {required : false},
				category4 : {required : false},
				<? if($this->_site_language["multilingual"]){ ?>
					<? foreach($this->_site_language["set_language"] as $key => $val){ ?>
						categorynm_<?=$key?>  : {required : true, maxlength : 20},
					<? } ?>
				<? }else{ ?>
					categorynm  : {required : true, maxlength : 20},
				<? } ?>
				yn_use : {required : false},
				yn_state : {required : false},
				sort : {required : true, number : true},
			}, messages : {
				category1 : {required : "카테고리를 선택해주세요."},
				category2 : {required : "카테고리를 선택해주세요."},
				category3 : {required : "카테고리를 선택해주세요."},
				category4 : {required : "카테고리를 선택해주세요."},
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
							$("#categoryNo").text(result.data.category);
							input_fill(result.data);
							select_category(result.data.category, 1, 'category_list');
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

	function category_preview(language) {
		var obj = $("form[name='frm']").serializeObject();
		var category = obj.category5 || obj.category4 || obj.category3 || obj.category2 || obj.category1;

		if(category) {
			window.open("/goods/goods_list?cate="+category+"&language="+language);
		}else{
			window.open("/?"+"language="+language);
		}
	}

	function category_save() {
		var frm = $("form[name='frm']");

		if(!frm.valid()){
			return false;
		}

		frm.submit();
	}

	function category_delete(){

		var obj = $("form[name='frm']").serializeObject();
		var category = obj.category5 || obj.category4 || obj.category3 || obj.category2 || obj.category1;

		if(!category) {
			alert("선택된 카테고리가 없습니다.");
			return false;
		}

		var set_data = {
			category : category
		};

		$.ajax({
			url : "/admin/goods/category_goods_empty_check",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.data.cnt > 0) {
								alert("해당 카테고리 안에 상품이 있습니다.\n상품이 있으면 삭제가 되지 않습니다.");
								return false;
							}

							if(!confirm("하위카테고리까지 삭제가 됩니다.\n삭제하시겠습니까?")) {
								return false;
							}

							$("[name='frm']").prop("action", "category_delete").submit();
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

	$('#leftmenu >ul > li:nth-of-type(2)').addClass('on');
</script>
<div id="contents">
	<?=form_open("", array("name" => "frm"))?>
			<input type="hidden" name="mode" value="<?=$mode?>" />
	<div class="main_tit">
		<h2>카테고리 수정</h2>
		<div class="btn_right btn_num2">
			<a href="javascript://" onclick="category_delete();" class="btn gray sel_minus">삭제</a>
			<a href="javascript://" onclick="category_save();" class="btn point">저장</a>
		</div>
	</div>
	<div class="cate_tit_wrap">
		<strong>카테고리 수정</strong>
		<div class="table_write_info">수정하고자하는 카테고리를 선택 후, 선택 카테고리 정보에서 수정하실 수 있습니다.</div>

	</div>
	<div class="cate_box_wrapper clear">
		<div class="table_list table_cate">
			<div class="clear">
				<!--a href="../goods/category_reg"  class="btn point new_plus">+ 신규 카테고리 생성</a-->
				<h3>카테고리 목록</h3>
			</div>
			<div class="menu_tree_box">
				<ul class="menu_tree menu_tree_top"></ul>
			</div>
		</div>

		<div class="cate_select_box">
			<div class="cate_step_wrap">
				<h3>선택 카테고리 정보</h3>
				<select name="category1" id="category1" class="categorySelect" onfocus="this.initialSelect = this.selectedIndex;" onchange="this.selectedIndex = this.initialSelect;"></select>
				<select name="category2" id="category2" class="categorySelect" onfocus="this.initialSelect = this.selectedIndex;" onchange="this.selectedIndex = this.initialSelect;"></select>
				<select name="category3" id="category3" class="categorySelect" onfocus="this.initialSelect = this.selectedIndex;" onchange="this.selectedIndex = this.initialSelect;"></select>
				<!-- <select name="category4" id="category4" class="categorySelect" onfocus="this.initialSelect = this.selectedIndex;" onchange="this.selectedIndex = this.initialSelect;"></select>
				<select name="category5" id="category5" class="categorySelect" onfocus="this.initialSelect = this.selectedIndex;" onchange="this.selectedIndex = this.initialSelect;"></select> -->
				<script>select_category("", "1", "category_list");</script>
			</div>
			<div class="table_write">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="21%" />
						<col width="19%" />
						<col width="11%" />
						<col width="19%" />
						<col width="11.5%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="col" class="ta_left">카테고리번호</th>
							<td colspan="5"><span id="categoryNo"></span></td>
						</tr>
						<? if($this->_site_language["multilingual"]){ ?>
							<? foreach($this->_site_language["set_language"] as $key => $val){ ?>
								<tr>
									<th scope="col" class="ta_left">카테고리명 <span>(<?=$val?>)</span></th>
									<td colspan="5">
										<input type="text" name="categorynm_<?=$key?>" class="cate_name_input" placeholder="최대 20글자까지 입력하실 수 있습니다." />
										<a href="javascript://" onclick="category_preview('<?=$key?>');" class="btn_mini">미리보기</a>
									</td>
								</tr>
							<? } ?>
						<? }else{ ?>
						<tr>
							<th scope="col" class="ta_left">카테고리명 <span>(국문)</span></th>
							<td colspan="5">
								<input type="text" name="categorynm" class="cate_name_input" placeholder="최대 20글자까지 입력하실 수 있습니다." />
								<a href="javascript://" onclick="category_preview('kor');" class="btn_mini">미리보기</a>
							</td>
						</tr>
						<? } ?>

						<!-- 다국어 사용함 체크시, 사용하는 언어별로 생성되게 요청드림. -->

						<!-- 다국어 사용함 체크시, 사용하는 언어별로 생성되게 요청드림. -->
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
						<tr>
							<th scope="col" class="ta_left">마지막 수정일</th>
							<td colspan="3"><input type="text" name="moddt" class="input_readonly" readonly /></td>
							<th scope="col" class="ta_left">최초생성일</th>
							<td><input type="text" name="regdt" class="input_readonly" readonly /></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="terms_privecy_box">
				<dl>
					<dt>- 카테고리를 새로 생성하고 싶어요.</dt>
					<dd>
					신규 카테고리 생성은, 좌측에서 "신규 카테고리 등록" 메뉴를 눌러 이동하신 후 수정 가능합니다.<br><br>
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
