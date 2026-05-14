<style>
.categories { width: 200px; height: 110px !important; padding: 5px !important; }
#btnSelected { padding: 5px 20px; background: #000; color: #fff; }
.btn-remove-category { background: red; color: white; font-size: 10px; padding: 2px 5px; vertical-align: top; margin-left: 10px; }
/* 2026-05-14: 에디터 토글 UI */
.editor-toggle-bar { padding: 10px 12px; margin-bottom: 8px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; gap: 16px; font-size: 13px; }
.editor-toggle-bar label { cursor: pointer; }
.editor-toggle-bar .badge-new { display: inline-block; padding: 2px 6px; background: #0A2C51; color: #fff; border-radius: 3px; font-size: 11px; margin-left: 4px; }
.editor-toggle-bar .note { color: #888; margin-left: auto; font-size: 12px; }
/* Toast UI Editor 영역 */
.tui-editor-wrap { min-height: 500px; }
</style>
<script type="text/javascript" src="/lib/admin/js/admin_goods.js" charset="utf-8"></script>
<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>

<?php
// 2026-05-14: 이 view는 Toast UI 모드 전용 (controller가 $_use_tui=true일 때 호출)
// OPCache 우회를 위해 별도 file로 분리 (신규 path → 옛 캐시 영향 없음)
$_use_tui = true;
$_editor_choice = 'tui';
?>

<?php if ($_use_tui): ?>
<!-- Toast UI Editor 4.x (2026-05-14 시범 도입) -->
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<script src="https://uicdn.toast.com/editor/latest/i18n/ko-kr.js"></script>
<?php endif; ?>
<script>
	var fieldOption = <?=json_encode($fieldOption["option"])?>;
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				<? if($this->_site_language["multilingual"]){ ?>
					<? foreach($this->_site_language["set_language"] as $key => $value){ ?>
						name_<?=$key?>  : {required : true, maxlength : 50},
					<? } ?>
				<? }else{ ?>
					name  : {required : true, maxlength : 50},
				<? } ?>
				category1 : {required : true},
				category2 : {required : false},
				category3 : {required : false},
				category4 : {required : false},
				category5 : {required : false},
				//다국어 처리
				<? if($this->_site_language["multilingual"]){ ?>
						<? foreach($this->_site_language["set_language"] as $key => $value){ ?>
							info_<?=$key?> : {editorRequired : {depends : function(){
							var result = true;
							<? if($goodsField["require"][$key]["info"]): ?>
								result = !getColumnValue("info_<?=$key?>");
							<? else: ?>
								getColumnValue("info_<?=$key?>");
								result = false;
							<? endif; ?>
							return result;
							}}},
						<? } ?>
				<? }else{ ?>
					info : {editorRequired : {depends : function(){
						var result = true;
						<? if($goodsField["require"][$this->_site_language["default"]]["info"]): ?>
							result = !getColumnValue("info");
						<? else: ?>
							getColumnValue("info")
							result = false;
						<? endif; ?>
						return result;
					}}},
				<? } ?>
				upload_path : {editorRequired : {depends : function(){return <?if($goodsField["require"][$this->_site_language["default"]]["upload_fname"]) : ?>!(getColumnValue("upload_path"))<? else : ?>false<? endif; ?>}}},
				upload_fname : {editorRequired : {depends : function(){return <?if($goodsField["require"][$this->_site_language["default"]]["upload_fname"]) : ?>!getColumnValue("upload_fname")<? else : ?>false<? endif; ?>}}},
				'detail_fname[]' : {editorRequired : {depends : function(){return <?if($goodsField["require"][$this->_site_language["default"]]["detail_img"]) : ?>!getColumnValue("detail_fname[]")<? else : ?>false<? endif; ?>}}},
				yn_state : {required : true},
				img1 : {editorRequired : {depends : function(){return <?if($goodsField["require"][$this->_site_language["default"]]["img1"]) : ?>!(getColumnValue("img1"))<? else : ?>false<? endif; ?>}}},
				img2 : {editorRequired : {depends : function(){return <?if($goodsField["require"][$this->_site_language["default"]]["img2"]) : ?>!getColumnValue("img2")<? else : ?>false<? endif; ?>}}},
				<? for($idx = 1; $idx<=20; $idx++){ ?>
					<? if(!empty($goodsField["multi"]["ex".$idx]) && $this->_site_language["multilingual"]): ?>
						<? foreach($this->_site_language["set_language"] as $key => $value): ?>
							ex<?=$idx?>_<?=$key?> : {editorRequired : {
								depends : function(){
									<? if($goodsField['require'][$key]['ex'.$idx]): ?>
										return !getColumnValue('ex<?=$idx?>_<?=$key?>');
									<? else : ?>
										getColumnValue('ex<?=$idx?>_<?=$key?>');
										return false;
									<? endif; ?>
								}
							}},
						<? endforeach ?>
					<? else: ?>
						ex<?=$idx?> : {editorRequired : {
							depends : function(){
								<? if($goodsField['require'][$this->_site_language["default"]]['ex'.$idx]): ?>
									return !getColumnValue('ex<?=$idx?>');
								<? else : ?>
									getColumnValue('ex<?=$idx?>');
									return false;
								<? endif; ?>
							}
						}},
					<? endif ?>

				<? } ?>
			}, messages : {
				<? if($this->_site_language["multilingual"]){ ?>
					<? foreach($this->_site_language["set_language"] as $key => $value){ ?>
						name_<?=$key?>  : {required : "<?=$goodsField["name"][$site_language]["name"]?>를 입력해주세요.", maxlength : $.validator.format("<?=$goodsField["name"][$site_language]["name"]?>은 {0}자 이하입니다.")},
					<? } ?>
				<? }else{ ?>
					name : {required : "<?=$goodsField["name"][$site_language]["name"]?>를 입력해주세요.", maxlength : $.validator.format("<?=$goodsField["name"][$site_language]["name"]?>은 {0}자 이하입니다.")},
				<? } ?>
				category1 : {required : "<?=$goodsField["name"][$site_language]["category"]?>를 등록해주세요."},
				category2 : {required : false},
				category3 : {required : false},
				category4 : {required : false},
				category5 : {required : false},
				//다국어 처리
				<? if($this->_site_language["multilingual"]){ ?>
						<? foreach($this->_site_language["set_language"] as $key => $value){ ?>
							info_<?=$key?> : {editorRequired : "<?=$goodsField["name"][$site_language]["info"]?>(<?=$value?>)를 입력해주세요."},
						<? } ?>
				<? }else{ ?>
					info : {editorRequired : "<?=$goodsField["name"][$site_language]["info"]?>를 입력해주세요."},
				<? } ?>
				img1 : {editorRequired : "<?=$goodsField["name"][$site_language]["img1"]?>를 등록해주세요."},
				img2 : {editorRequired : "<?=$goodsField["name"][$site_language]["img2"]?>를 등록해주세요."},
				yn_state : {required : "<?=$goodsField["name"][$site_language]["yn_state"]?>를 선택해주세요."},
				upload_path : {required : "<?=$goodsField["name"][$site_language]["upload_fname"]?>를 등록해주세요."},
				upload_fname : {required : "<?=$goodsField["name"][$site_language]["upload_fname"]?>를 등록해주세요."},
				'detail_fname[]' : {editorRequired : "상세 이미지를 등록해주세요."},
				<? for($idx = 1; $idx<=20; $idx++){ ?>
					<? if(!empty($goodsField["multi"]["ex".$idx]) && $this->_site_language["multilingual"]): ?>
						<? foreach($this->_site_language["set_language"] as $key => $value): ?>
							ex<?=$idx?>_<?=$key?> : {editorRequired : "<?=$goodsField["name"][$site_language]["ex".$idx]?>(<?=$value?>)를 <?=in_array($goodsField["option"]["ex".$idx]["type"], array("select", "radio", "checkbox")) ? "선택" : $goodsField["option"]["ex".$dix]["type"] == "file" ? "등록" : "입력"?>해주세요."},
						<? endforeach ?>
					<? else: ?>
						ex<?=$idx?> : {editorRequired : "<?=$goodsField["name"][$site_language]["ex".$idx]?>를 <?=in_array($goodsField["option"]["ex".$idx]["type"], array("select", "radio", "checkbox")) ? "선택" : $goodsField["option"]["ex".$dix]["type"] == "file" ? "등록" : "입력"?>해주세요."},
					<? endif ?>

				<? } ?>
			}
		});

		uploadForm.init(document.frm);

		//$.get("")

		$(document).on("click", ".categories option", function() {
			var v = $(this).val(), len = $(this).parent().data("len"), lang = "kor", idx = $(this).parent().index();

			for(var i = idx; i < 5; i++) {
				$("#gCategory" + (i + 2)).html("");
			}

			$.ajax({
				url: "get_next_categories",
				type: "post",
				data: {
					v: v,
					length: len,
					language: lang
				},
				dataType: "json",
				success: function(res) {
					if(res.length > 0) {
						var opts = [];
						$.each(res, function(i) {
							opts.push("<option value='" + res[i].category + "'>" + res[i].categorynm + "</option>");
						});
						$("#gCategory" + (len + 1)).html(opts.join(""));
					}
				}
			});
		});

		$("#btnSelected").on("click", function() {
			var name = [], category, temp = "";
			for(var i = 1; i < 6; i++) {
				if($("#gCategory" + i).val()) {
					name.push($("#gCategory" + i + " option:selected").text());
					category = $("#gCategory" + i).val();
				}
			}

			temp = "<div class='selected'><input type='hidden' name='category[]' value='" + category + "'><span>" + name.join(" > ") + "</span><button type='button' class='btn-remove-category'>삭제</button></div>";
			$("#selectedCategory").html(temp);
		});
	});

	/**
	 *@date 2018-10-10
	 *
	 *@author James
	 *
	 *상세이미지 동적 추가 함수
	 *
	 */
	function add_detail_img(event){
		var len = $(".detail_tr").length;
		if(len > 0){

			if(len >= 10){
				alert("상세 이미지는 최대 10개까지만 등록 가능합니다.");
				return false;
			}
			var addHtml = "";
			addHtml += "<tr class = 'detail_tr'>";
			addHtml += "	<th align='left' class = 'detail_th'>상세이미지 "+(len + 1)+"</th>";
			addHtml += "	<td>";
			addHtml += "		<input type='file' name='detail"+(len+1)+"'/>";
			addHtml += "		<input type ='hidden' name = 'detail_oname[]'/>";
			addHtml += "		<input type ='hidden' name='detail_fname[]' value=''/>";
			addHtml += "		<input type='hidden' name='detail"+(len+1)+"_type' value='image'/>";
			addHtml += "		<input type='hidden' name='detail"+(len+1)+"_folder' value='/upload/goods/detail_img'/>";
			addHtml += "		<input type='hidden' name='detail"+(len+1)+"_size' value='2'>";

			<? if(!empty($goodsField["option"]["kor"]["detail_img"]["width"])){ ?>
				addHtml += "		<input type='hidden' name='detail"+(len+1)+"_width' value='<?=$goodsField["option"]["kor"]["detail_img"]["width"]?>'>";
			<? } ?>
			<? if(!empty($goodsField["option"]["kor"]["detail_img"]["height"])){ ?>
				addHtml += "		<input type='hidden' name='detail"+(len+1)+"_height' value='<?=$goodsField["option"]["kor"]["detail_img"]["height"]?>'>";
			<? } ?>

			addHtml += "		<a onclick = 'javascript:remove_detail_img(this);' class='btn_del_file'>-&nbsp;삭제</a>";
			addHtml += "		<div>";
			addHtml += "			<span id = 'detail"+(len+1)+"_filezone'></span>";
			addHtml += "		</div>";
			addHtml += "	</td>";
			addHtml += "</tr>";

			$(".detail_tr").last().after(addHtml);

			uploadForm.init(document.frm);
		}
	}

	function remove_detail_img(event){
		$(event).closest(".detail_tr").remove();

		//상세이미지 이름 재정렬
		var len = $(".detail_th").length;

		if(len > 1){
			var cnt = 2;
			$(".detail_th").each(function(i,e){

				if(i == 0){
					return true;
				}
				$(e).html("상세이미지 "+cnt);
				cnt++;
			});
		}
	}

	function goods_save() {
		var frm = $("form[name='frm']");

		if(!frm.valid()){
			return false;
		}

		frm.submit();
	}

	function getColumnValue(name) {
		var value = $("[name='"+ name +"']").val();
		var arrLanguage = ["kor", "eng", "jpn", "chn"];
		var languageKey = "kor";
		var columnKey = name;
		<? if($this->_site_language["multilingual"]) { ?>
			$(arrLanguage).each(function(idx, language){
				if(name.indexOf(language) !== -1){
					languageKey = language;
					columnKey = columnKey.replace("_" + languageKey, "");
					return false;
				}
			});
		<? } ?>

		if(fieldOption[languageKey][columnKey] && fieldOption[languageKey][columnKey]["type"]) {
			if(fieldOption[languageKey][columnKey]["type"] == "file") {
				value = $("input[name='"+ name +"_fname']").val();
			} else if(fieldOption[languageKey][columnKey]["type"] == "editor") {
				// 2026-05-14: Toast UI Editor 사용 중이면 그쪽에서 추출
				if (window['__tuiEditor_' + name]) {
					value = window['__tuiEditor_' + name].getHTML();
					if (value === '<p><br></p>' || value === '<p></p>') value = '';
				} else {
					value = getSmartEditor(name);
				}
			} else if(fieldOption[languageKey][columnKey]["type"] == "checkbox" || fieldOption[languageKey][columnKey]["type"] == "radio") {
				value = $("[name='"+ name +"']:checked").val();
			} else if(fieldOption[languageKey][columnKey]["type"] == "select") {
				value = $("[name='"+ name +"'] option:selected").val();
			}
		}
		return value;
	}

	function goods_delete(){
		if(!confirm("해당 상품을 삭제하시겠습니까?")) {
			return false;
		}
		document.frm.action = "goods_delete";
		document.frm.submit();
	}

    function translate_google(language){
        //https://translate.google.co.kr/?hl=ko
        var text = $("input[name='name_" + language + "']").val();
        switch (language) {
            case 'chn' :
                language = 'zh-CN';
                break;
            case 'jpn' :
                language = 'jp';
                break;
            default :
                language = 'en';
                break;
        }
        url = 'https://translate.google.com/#auto/' + language + '/' + text;
        win = window.open(url, "", "width=860, height=600, scrolbars=1, resizable=1");
        win.focus();
        //return win;
    }

	function fnMove(language){
        var offset = $("#" + language + "_link").offset();
		var test = $('#header').height() + $('div.main_tit').outerHeight(true);
        $('html, body').animate({scrollTop : offset.top - test}, 400);
    }
	
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<!-- 2026-05-14: 에디터 토글 (운영자 선택). 기본은 SmartEditor 유지, 신규 글부터 Toast UI 권장. -->
	<div class="editor-toggle-bar">
		<strong>본문 에디터:</strong>
		<label><input type="radio" name="editor_choice" value="smart"<?= !$_use_tui ? ' checked' : '' ?>> SmartEditor (기존, 안정)</label>
		<label><input type="radio" name="editor_choice" value="tui"<?= $_use_tui ? ' checked' : '' ?>> Toast UI Editor<span class="badge-new">NEW</span></label>
		<span class="note">변경 시 페이지 새로고침. 기존 글은 SmartEditor 권장. 신규 글은 Toast UI 권장 (이미지+텍스트 인터리브 쉬움).</span>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelectorAll('input[name="editor_choice"]').forEach(function(el){
			el.addEventListener('change', function(){
				document.cookie = 'preferred_editor=' + this.value + '; max-age=31536000; path=/';
				var u = new URL(location.href);
				u.searchParams.set('editor', this.value);
				if (confirm('에디터를 변경하려면 페이지를 새로고침합니다. 작성 중인 내용이 사라질 수 있습니다. 계속하시겠습니까?')) {
					location.href = u.toString();
				} else {
					// 원복
					document.querySelector('input[name="editor_choice"][value="<?= $_use_tui ? 'tui' : 'smart' ?>"]').checked = true;
				}
			});
		});
	});
	</script>

	<?=form_open("", array("name" => "frm", "id" => "frm"))?>
		<input type="hidden" name="admin_page_flag" value="y">
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<input type="hidden" name="ref" value="<?=$ref?>">
		<? if($this->_site_language["multilingual"]){ ?>
			<input type="hidden" name="multi" value="y"/>
		<? } ?>
		<input type="hidden" name="upload_path" value="<?=$goods_view["upload_path"]?>" />
		<div class="main_tit">
			<h2>상품 <? if($mode == "register") : echo "등록"; else : echo "수정 "; endif; ?><!--a target='_blank' class='btn-sm' href='<?=base_url()?>goods/goods_view?no=<?=$this->input->get('no', true)?>'>화면 보기</a--></h2>
			<? if($this->_site_language["multilingual"]) { // 다국어 지원할때 ?>
			<div class="gd_top">
				상세정보입력 바로가기 <? foreach($this->_site_language["set_language"] as $languageKey => $languageVal){ // 다국어 사용체크 된 언어들 ?>
					<a href="javascript://" onclick="fnMove('<?=$languageKey?>');" class="<? if($languageKey == 'kor') { ?>ko<? } ?><? if($languageKey == 'jpn') { ?>jp<? } ?><? if($languageKey == 'eng') { ?>en<? } ?><? if($languageKey == 'chn') { ?>ch<? } ?>"><?=$languageVal?></a>
				<? } ?>
			</div>
			<? } ?>
			<div class="btn_right btn_num2 <? if($mode == "modify") : ?>btn_num3<? endif ?>">
				<?php
				if($goods_view['no']) :
					echo '<a href="/goods/goods_view?no='.$goods_view['no'].'" class="btn gray" target="_blank">보기</a>';
					echo '<a href="javascript://" onclick="goods_delete();" class="btn gray sel_minus">삭제</a>';
				endif;
				?>
				<a href="goods_list?<?=$ref?>" class="btn gray">취소</a>
				<a href="javascript://" onclick="goods_save();" class="btn point">저장</a>
			</div><!--btn_right-->
		</div>
		<div class="table_write_info">* 체크는 필수입력사항입니다.</div>
		<div id="divList">
			<div class="sub_tit"><h3>상품노출 카테고리 설정</h3></div>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup><col width="150px" /><col width="*" /></colgroup>
				<tr>
					<th class="ta_left"><em>*</em> <?=$goodsField["name"][$site_language]["category"]?></th>
					<td>
						<select name="category1" id="category1" class="categorySelect" onchange="select_category(this.value, 1);"></select>
						<select name="category2" id="category2" class="categorySelect" onchange="select_category(this.value, 2);"></select>
						<select name="category3" id="category3" class="categorySelect" onchange="select_category(this.value, 3);"></select>
						<select name="category4" id="category4" class="categorySelect" onchange="select_category(this.value, 4);"></select>
						<select name="category5" id="category5" class="categorySelect"></select>
						<script>select_category("<?=$goods_view["category"]?>", 1);</script>
					</td>
				</tr>
				</table>
			</div>
			<div class="table_write dn">
				<table>
					<colgroup>
						<col style="width: 150px;">
						<col>
					</colgroup>
					<tr>
						<th>카테고리 설정</th>
						<td>
							<?php
							for($i = 1; $i < 6; $i++) :
								echo '<select name="_category'.$i.'" id="gCategory'.$i.'" class="categories" size="5" data-len="'.$i.'">';
									if($i == 1) :
										foreach($categories['kor'] as $key => $value) :
											if(strlen($value['code']) == 3) :
												echo '<option value="'.$value['code'].'">'.$value['name'].'</option>';
											endif;
										endforeach;
									endif;
								echo '</select>';
							endfor;
							?>
							<button type="button" id="btnSelected">선택</button>
						</td>
					</tr>
					<tr>
						<th><em>*</em> 선택된 카테고리</th>
						<td id="selectedCategory">
						</td>
					</tr>
				</table>
			</div>
			<div class="sub_tit"><h3>상품노출 설정</h3></div>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup><col width="150px" /><col width="*" /></colgroup>
				<tr>
					<th class="ta_left"><em>*</em> <?=$goodsField["name"][$site_language]["yn_state"]?></th>
					<td>
						<select name="yn_state">
							<option value="y" <? if($goods_view["yn_state"] == "y") : ?>selected<? endif ?>>노출</option>
							<option value="n" <? if($goods_view["yn_state"] == "n") : ?>selected<? endif ?>>노출안함</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="ta_left">메인페이지 진열</th>
					<td>
						<?php if(ib_isset($display_main_list)) : ?>
							<?php foreach($display_main_list as $value) : ?>
								<label for="display_theme_no-<?=$value['no']?>"><input type="checkbox" id="display_theme_no-<?=$value['no']?>" name="display_theme_no[]" value="<?=$value['no']?>" <?=in_array($value['no'], $goods_view['display_theme']) ? 'checked' : ''?>> <?=$value['theme_name']?></label>
							<?php endforeach?>
						<?php endif?>
					</td>
				</tr>
				</table>
			</div>

			<div class="sub_tit"><h3>기본정보 설정</h3></div>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup><col width="150px" /><col width="*" /></colgroup>
				<? if($this->_site_language["multilingual"]){ ?>
					<?foreach($this->_site_language["set_language"] as $key => $value){ ?>
						<tr>
							<th class="ta_left"><em>*</em> <?=$goodsField["name"][$site_language]["name"]?>(<?=$value?>)</th>
							<td><input type="text" name="name_<?=$key?>" value="<?=$goods_view["multi"]["name"][$key]?>" /><a href="javascript:translate_google('<?=$key?>')" class="btn gray btn_h32">구글 번역</a></td>
						</tr>
					<? } ?>
				<? }else{ ?>
				<tr>
					<th class="ta_left"><em>*</em> <?=$goodsField["name"][$site_language]["name"]?>(국문)</th>
					<td><input type="text" name="name" value="<?=$goods_view["name"]?>" /></td>
				</tr>
				<? } ?>
				<tr>
					<th class="ta_left">URL Slug<br><small style="color:#888;font-weight:normal;">/service/&lt;slug&gt;</small></th>
					<td>
						<input type="text" name="slug" value="<?=htmlspecialchars($goods_view["slug"] ?? '', ENT_QUOTES)?>" placeholder="예: tirtir-pop-up-store-seongsu" maxlength="150" style="width: 400px;" pattern="[a-zA-Z0-9가-힣\-]+">
						<span style="color:#888;font-size:12px;margin-left:8px;">영문/한글/숫자/하이픈만. 비워두면 기존 query URL 사용. 한 번 저장 후 변경 시 SEO 색인 손실 위험.</span>
					</td>
				</tr>
				<? if($mode == "modify") : ?>
					<tr>
						<th class="ta_left">고유 <?=$goodsField["name"][$site_language]["no"]?></th>
						<td><input type="text" name="no" value="<?=$this->input->get("no")?>" readonly class="input_readonly" /></td>
					</tr>
				<? endif ?>
				<? foreach($goodsField["name"][$site_language] as $key => $value) : ?>
					<? if($goodsField["use"][$this->_site_language["default"]][$key] == "checked") : ?>
						<? if($key == "img1" || $key == "img2") :  ?>
						<!-- 대표이미지 -->
						<tr>
							<th class="ta_left"><? if($goodsField["require"][$this->_site_language["default"]][$key] == "checked") : echo "<em>*</em> "; endif; ?><?=$value?></th>
							<td>
								<? if($goodsField["option"][$site_language][$key]) : ?>
									<? if(in_array($goodsField["option"][$site_language][$key]["type"], array("checkbox", "radio"))) :?>
										<? foreach($goodsField["option"][$site_language][$key]["item"][$site_language] as $subKey => $subValue) : ?>
											<input type="<?=$goodsField["option"][$site_language][$key]["type"]?>" id="<?=$key?>-<?=$subKey?>" name="<?=$key?>" value="<?=$subKey?>" <? if($goods_view[$key] == $subKey ) : ?>checked<? endif ?>>
											<label for="<?=$key?>-<?=$subKey?>"><?=$subValue?></label>
										<? endforeach; ?>
									<? elseif($goodsField["option"][$site_language][$key]["type"] == "select") : ?>
										<select name="<?=$key?>">
											<? foreach($goodsField["option"][$site_language][$key]["item"][$site_language] as $subKey => $subValue) : ?>
												<option value="<?=$subKey?>" <? if($goods_view[$key] == $subKey ) : ?>selected<? endif ?>><?=$subValue?></option>
											<? endforeach; ?>
										<select>
									<? elseif($goodsField["option"][$site_language][$key]["type"] == "editor") : ?>
										<!-- DBG: key=<?=$key?> use_tui=<?=isset($_use_tui)?($_use_tui?'1':'0'):'undef'?> editor_choice=<?=isset($_editor_choice)?$_editor_choice:'undef'?> get=<?=isset($_GET['editor'])?$_GET['editor']:'noget'?> cookie=<?=isset($_COOKIE['preferred_editor'])?$_COOKIE['preferred_editor']:'nocookie'?> -->
										<? if ($_use_tui && $key === 'info'): ?>
											<!-- 2026-05-14 Toast UI Editor (info 필드 한정 시범 도입) -->
											<textarea name="<?=$key?>" id="<?=$key?>" class="editor" style="display:none"><?=$goods_view[$key]?></textarea>
											<div id="<?=$key?>_tui" class="tui-editor-wrap"></div>
											<script>
											(function(){
												var initialValue = document.getElementById('<?=$key?>').value || '';
												var editor = new toastui.Editor({
													el: document.querySelector('#<?=$key?>_tui'),
													height: '550px',
													initialEditType: 'wysiwyg',
													previewStyle: 'tab',
													language: 'ko-KR',
													initialValue: initialValue,
													hooks: {
														addImageBlobHook: function(blob, callback){
															var fd = new FormData();
															fd.append('image', blob, blob.name || ('image_' + Date.now() + '.png'));
															fetch('/admin/goods/editor_image_upload', { method:'POST', body:fd, credentials:'same-origin' })
																.then(function(r){ return r.json(); })
																.then(function(d){
																	if (d.url) { callback(d.url, ''); }
																	else { alert('이미지 업로드 실패: ' + (d.error || 'unknown')); }
																})
																.catch(function(e){ alert('이미지 업로드 오류: ' + e.message); });
														}
													}
												});
												// submit 직전 본문 추출 → textarea로 반영
												document.forms['frm'].addEventListener('submit', function(){
													document.getElementById('<?=$key?>').value = editor.getHTML();
												});
												// 외부 노출 (검증 호환용)
												window['__tuiEditor_<?=$key?>'] = editor;
											})();
											</script>
										<? else: ?>
											<div class="editor-box"><textarea name="<?=$key?>" id="<?=$key?>" class="editor"><?=$goods_view[$key]?></textarea></div>
											<script>attachSmartEditor("<?=$key?>", "goods");</script>
										<? endif; ?>
									<? elseif($goodsField["option"][$site_language][$key]["type"] == "file" && $key != "detail_img") : ?>
										<input type="file" name="<?=$key?>"/>
										<input type="hidden" name="<?=$key?>_oname" value="<?=$goods_view[$key.'_oname']?>"/>
										<input type="hidden" name="<?=$key?>_fname" value="<?=$goods_view[$key]?>" />
										<? if($goodsField["option"][$site_language][$key]["type"] == "file") : ?>
											<input type="hidden" name="<?=$key?>_type" value="<?=$goodsField["option"][$site_language][$key]["file_type"]?>" />
										<? else : ?>
											<input type="hidden" name="<?=$key?>_type" value="document" />
										<? endif ?>
										<?php if($key == "upload_fname") : ?>
											<input type="hidden" name="<?=$key?>_folder" value="<?=_UPLOAD?>/goods/file" />
										<?php else : ?>
											<input type="hidden" name="<?=$key?>_folder" value="<?=_UPLOAD?>/goods/<?=$key?>" />
										<?php endif?>
										<? if($goodsField["option"][$site_language][$key]["file_type"] == "image") : ?>
										<!--이미지 리사이즈용 width, height 추가-->
										<?php if(empty($goodsField['option'][$site_language][$key]['width']) && empty($goodsField['option'][$site_language][$key]['height'])) : ?>
											<p>* 자동으로 리사이징되어 등록됩니다. ( 자동 )</p>
											<? if(!empty($max_file_uploads)){ ?>
											<p>* 최대 업로드 가능 이미지 용량 : <?=$max_file_uploads?>mb</p>
											<? } ?>
										<?php else : ?>
											<p>
											* 권장 사이즈 값으로 리사이징되어 등록됩니다. ( 권장사이즈 : 가로 <?=$goodsField["option"][$site_language][$key]["width"]?>px X 세로 <?=$goodsField["option"][$site_language][$key]["height"]?>px )
											</p>
											<? if(!empty($max_file_uploads)){ ?>
											<p>* 최대 업로드 가능 이미지 용량 : <?=$max_file_uploads?>mb</p>
											<? } ?>
											<input type="hidden" name="<?=$key?>_width" value="<?=$goodsField["option"][$site_language][$key]["width"]?>">
											<input type="hidden" name="<?=$key?>_height" value="<?=$goodsField["option"][$site_language][$key]["height"]?>">
										<?php endIf ?>

										<!-- 가로 세로 사이즈 중, 입력된 값이 없을 시, "자동" 으로 노출되게끔 구분 처리 부탁드립니다. -->
										<!--input type="text" readonly name="<?=$key?>_pixel" value="<?=$goodsField["option"][$key]["width"]?>"  />
										<input type="text" readonly name="<?=$key?>_pixel" value="<?=$goodsField["option"][$key]["height"]?>" /-->
										<? endif; ?>
										<span id="<?=$key?>_filezone"  class="file_down_span">
											<a href="/fileRequest/download?file=<?=urlencode("/goods/". ($key == "upload_fname" ? "file" : $key) ."/". $goods_view[$key])?>" target="_blank">
												<?=$key == 'upload_fname' ? $goods_view['upload_oname'] : $goods_view[$key.'_oname'] ? $goods_view[$key.'_oname'] : $goods_view[$key]?>
											</a>
											<? if($goods_view[$key]) : ?><a href="javascript://" onclick="uploadForm.uploadRemove('<?=$key?>')" class="file_no"><img src="/lib/admin/images/btn_close.gif"></a><? endif ?>
										</span>
									<? endif; ?>
								<? else : ?>
									<input type="text" name="<?=$key?>" value="<?=$goods_view[$key]?>" />
								<? endif; ?>
							</td>
						</tr>
						<? endif; ?>
					<? endif; ?>
				<? endforeach; ?>
				</table>
			</div>

			<?php if($goodsField["use"][$this->_site_language["default"]]['detail_img'] == "checked") : ?>
			<div class="sub_tit"><h3>상세이미지 등록</h3></div>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup><col width="150px" /><col width="*" /></colgroup>
				<? foreach($goodsField["name"][$site_language] as $key => $value) : ?>
					<? if($goodsField["use"][$this->_site_language["default"]][$key] == "checked") : ?>
						<? if($key == "detail_img") :  ?>
							<? if(!empty($goods_view["detail_img"])){ ?>
								<!--기존 이미지 존재-->
								<!--foreach Start-->
								<? foreach($goods_view["detail_img"] as $fkey => $fval){ ?>
									<? if(!empty($fval)){ ?>
									<tr class = "detail_tr">
										<th class="ta_left" class = "detail_th">
											<? if($goodsField["require"]["kor"]["detail_img"] == "checked") { echo "<em>*</em> "; } ?>
											상세이미지 <?=($fkey + 1)?>
										</th>
										<td>
											<input type="file" name="detail<?=$fkey + 1?>"/>
											<input type ="hidden" name = "detail_oname[]" value = "<?=$goods_view['detail_img_oname'][$fkey]?>"/>
											<input type ="hidden" name="detail_fname[]" value="<?=$fval?>"/>
											<input type="hidden" name="detail<?=$fkey + 1?>_type" value="image"/>
											<input type="hidden" name="detail<?=$fkey + 1?>_folder" value="/upload/goods/detail_img"/>
											<input type="hidden" name="detail<?=$fkey + 1?>_size" value="2">
											<?

											if(!empty($goodsField["option"]["kor"]["detail_img"]["width"])){ ?>

											<input type="hidden" name="detail<?=$fkey + 1?>_width" value="<?=$goodsField["option"]["kor"]["detail_img"]["width"]?>"/>
											<? } ?>

											<? if(!empty($goodsField["option"]["kor"]["detail_img"]["height"])){ ?>
											<input type="hidden" name="detail<?=$fkey + 1?>_height" value="<?=$goodsField["option"]["kor"]["detail_img"]["height"]?>"/>
											<? } ?>
											<? if($fkey == 0){ ?>
												<a onclick = "javascript:add_detail_img(this);" class="btn_more_file">+&nbsp;이미지 추가</a>
											<? }else{ ?>
												<a onclick = "javascript:remove_detail_img(this);" class="btn_del_file">-&nbsp;삭제</a>
											<? } ?>
											<div>
												<span id="detail<?=$fkey + 1?>_filezone" class="file_down_span">
													<a href="/fileRequest/download?file=<?=urlencode("/goods/detail_img/". $fval)?>" target="_blank">
														<?=$goods_view["detail_img_oname"][$fkey]?>
													</a>
													<? if($fval) : ?><a href="javascript://" onclick="uploadForm.uploadRemove('detail<?=$fkey + 1?>')" class="file_no"><img src="/lib/admin/images/btn_close.gif"></a><? endif ?>
												</span>
											</div>
										</td>
									</tr>
									<? } ?>
								<? } ?>
								<!--foreach End-->
							<? }else{ ?>
							<!--기존 이미지 없음-->
							<tr class = "detail_tr">
								<th class="ta_left">
								<? if($goodsField["require"][$this->_site_language["default"]]["detail_img"] == "checked") { echo "<em>*</em> "; } ?>
								상세이미지 1
								</th>
								<td>
									<input type="file" name="detail1"/>
									<input type ="hidden" name = "detail_oname[]"/>
									<input type ="hidden" name="detail_fname[]" value=""/>
									<input type="hidden" name="detail1_type" value="image"/>
									<input type="hidden" name="detail1_folder" value="/upload/goods/detail_img"/>
									<input type="hidden" name="detail1_size" value="2">
									<? if(!empty($goodsField["option"]["kor"]["detail_img"]["width"])){ ?>
									<input type="hidden" name="detail1_width" value="<?=$goodsField["option"]["kor"]["detail_img"]["width"]?>"/>
									<? } ?>

									<? if(!empty($goodsField["option"]["kor"]["detail_img"]["height"])){ ?>
									<input type="hidden" name="detail1_height" value="<?=$goodsField["option"]["kor"]["detail_img"]["height"]?>"/>
									<? } ?>
									<a onclick = "javascript:add_detail_img(this);" class="btn_more_file">+&nbsp;이미지 추가</a>
									<div>
										<span id="detail1_filezone" class="file_down_span">
											<a href="/fileRequest/download?file=<?=urlencode("/goods/detail_img/")?>" target="_blank">
											</a>
										</span>
									</div>
								</td>
							</tr>
							<? } ?>
						<? endif; ?>
					<? endif; ?>
				<? endforeach; ?>
				</table>
			</div>
			<?php endif; ?>
			<!--@author James 수정중-->
			<?php if($goodsField["use"][$this->_site_language["default"]]['upload_fname'] == "checked") : ?>
			<div class="sub_tit"><h3>첨부파일</h3></div>
			<?php endif; ?>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="150px" />
						<col width="*" />
					</colgroup>
					<tbody id='divList'>
						<?
							$exceptKey = array(
								"img2",
								"img1",
								"detail_img",
							);
						?>
						<? if($this->_site_language["multilingual"]) { // 다국어 지원할때 ?>
							<? foreach($goodsField['name'][$site_language] as $columnKey => $columnVal){ // 전체 필드들 ?>
								<? if(!array_key_exists($columnKey, $goodsField["multi"])) { // 다국어를 지원하지 않는 필드들 ?>
									<? if(in_array($columnKey, $exceptKey)): continue; endif; ?>
									<? if($goodsField["use"][$site_language][$columnKey] != "checked") continue; // 해당 필드가 사용 체크 되지 않았을 때 ?>
									<tr>
									<th class="ta_left">
										<? if($goodsField["require"][$site_language][$columnKey] == "checked") { echo "<em>*</em> "; } ?><?=$goodsField["name"][$site_language][$columnKey]?>
									</th>
									<td>
										<?
										if($goodsField["option"][$site_language][$columnKey]){
											$goodsOption = $goodsField["option"][$site_language][$columnKey];
										?>
										<?
											$htmlStr = "";
											switch($goodsOption["type"]){
												case "checkbox":
												case "radio":
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$checkFl = $goods_view[$columnKey] == $itemNm ? "checked" : "";
														$htmlStr .= "<input type='".$goodsOption["type"]."' id='".$columnKey."-".$itemNm."' name='".$columnKey."' value='".$itemNm."' ".$checkFl.">";
														$htmlStr .= "<label for = '".$columnKey."-".$itemNm."'>".$itemVal."</label>";
													}
													break;

												case "select":
													$htmlStr .= "<select name = '".$columnKey."'>";
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$selectFl = $goods_view[$columnKey] == $itemNm ? "selected" : "";
														$htmlStr .= "<option value = '".$itemNm."' ".$selectFl.">".$itemVal."</option>";
													}
													$htmlStr .= "</select>";
													break;
												case "editor":
													$htmlStr .= "<div class = 'editor-box'>";
													$htmlStr .= "<textarea name = '".$columnKey."' id = '".$columnKey."' class = 'editor'>";
													$htmlStr .= $goods_view[$columnKey];
													$htmlStr .= "</textarea>";
													$htmlStr .= "</div>";
													$htmlStr .= "<script>attachSmartEditor('".$columnKey."', 'goods');</script>";
													break;
												case "file":
													$htmlStr .= "<input type = 'file' name = '".$columnKey."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_oname' value = '".$goods_view['upload_oname']."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_fname' value = '".$goods_view[$columnKey]."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_type' value = '".$goodsField["option"][$site_language][$columnKey]["file_type"]."'>";

													if($columnKey == "upload_fname") {
														$uploadPath = _UPLOAD."/goods/file";
													}else{
														$uploadPath = _UPLOAD."/goods/".$columnKey;
													}
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_folder' value = '".$uploadPath."'>";

													if($goodsOption["file_type"] == "image") {
														if(!empty($goodsOption["width"]) && !empty($goodsOption["height"])) {
															$htmlStr .= "<p>* 자동으로 리사이징되어 등록됩니다. ( 자동 )</p>";
															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."</p>";
															}
														}else {
															$htmlStr .= "<p>* 권장 사이즈 값으로 리사이징되어 등록됩니다. (권장사이즈 : 가로 ".$goodsOption["width"]."px X 세로 ".$goodsOption["height"]."px )</p>";
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_width' value = '".$goodsOption["width"]."'>";
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_height' value = '".$goodsOption["height"]."'>";
															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."mb</p>";
															}
														}
													}

													$htmlStr .= "<span id = '".$columnKey."_filezone'  class='file_down_span'>";
													$htmlStr .= "<a href = '/fileRequest/download?file=".urlencode("/goods/".($columnKey == "upload_fname" ? "file" : $columnKey) . "/" . $goods_view[$columnKey])."' target = '_blank' >";
													$htmlStr .= ($columnKey == "upload_fname" ? $goods_view["upload_oname"] : $goods_view[$columnKey]);
													$htmlStr .= "</a>";
													if($goods_view[$columnKey]) {
														$htmlStr .= "<a href = 'javascript://' onclick='uploadForm.uploadRemove("."\"".$columnKey."\"".")' class = 'file_no'>";
														$htmlStr .= "<img src='/lib/admin/images/btn_close.gif'>";
														$htmlStr .= "</a>";
													}
													$htmlStr .= "</span>";

													break;
												default: // text
													$htmlStr .= "<input type = 'text' name = '".$columnKey."' value = '".$goods_view[$columnKey]."'>";
													break;
											}

											echo $htmlStr;
										?>
										<? }else { ?>
											<input type = "text" name = "<?=$columnKey?>" value = "<?=$goods_view[$columnKey]?>">

										<? } ?>
									</td>
								</tr>
								<? } ?>
							<? } ?>

							<?php
							foreach($this->_site_language["set_language"] as $languageKey => $languageVal) {
							// 다국어 사용체크 된 언어들
								$cnt = 0;
								foreach($goodsField["use"][$languageKey] as $k => $v) {
									if(!in_array($k, ['img1', 'img2', 'detail_img', 'upload_fname'])) {
										$cnt++;
									}
								}
								if($cnt > 0) : // 현재 다국어에서 사용 중인 여분 필드 개수가 몇 개인지 체크
							?>
								<tr id="<?=$languageKey?>_link"><td class="lang_title" colspan="2"><?=$languageVal?> 추가정보</td></tr>
								<?php endif; ?>
								<? foreach($goodsField["multi"] as $columnKey => $columnVal){ // 다국어를 지원하는 필드들 ?>
									<? if($goodsField["use"][$languageKey][$columnKey] != "checked") continue;// 해당 필드가 사용되지 않았을 때 ?>

									<tr>
									<th class="ta_left">
										<? if($goodsField["require"][$languageKey][$columnKey] == "checked") { echo "<em>*</em> "; } ?><!--(<#?=$languageVal?>)--><?=$goodsField["name"][$languageKey][$columnKey]?>
									</th>
									<td>
										<?
										if($goodsField["option"][$languageKey][$columnKey]){
											$goodsOption = $goodsField["option"][$languageKey][$columnKey];

											$htmlStr = "";
											switch($goodsOption["type"]){
												case "checkbox":
												case "radio":
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$checkFl = $goods_view["multi"][$columnKey][$languageKey] == $itemVal ? "checked" : "";
														$htmlStr .= "<input type='".$goodsOption["type"]."' id='".$columnKey."-".$languageKey."-".$itemNm."' name='".$columnKey."_".$languageKey."' value='".$itemVal."' ".$checkFl.">";
														$htmlStr .= "<label for = '".$columnKey."-".$languageKey."-".$itemNm."'>".$itemVal."</label>";
													}
													break;

												case "select":
													$htmlStr .= "<select name = '".$columnKey."_".$languageKey."'>";
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$selectFl = $goods_view["multi"][$columnKey][$languageKey] == $itemVal ? "selected" : "";
														$htmlStr .= "<option value = '".$itemVal."' ".$selectFl.">".$itemVal."</option>";
													}
													$htmlStr .= "</select>";
													break;
												case "editor":
													$htmlStr .= "<div class = 'editor-box'>";
													$htmlStr .= "<textarea name = '".$columnKey."_".$languageKey."' id = '".$columnKey."_".$languageKey."' class = 'editor'>";
													$htmlStr .= $goods_view["multi"][$columnKey][$languageKey];
													$htmlStr .= "</textarea>";
													$htmlStr .= "</div>";
													$htmlStr .= "<script>attachSmartEditor('".$columnKey."_".$languageKey."', 'goods');</script>";
													break;
												case "file":
													$htmlStr .= "<input type = 'file' name = '".$columnKey."_".$languageKey."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_oname' value = '".$goods_view[$columnKey.'_'.$languageKey.'_oname']."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_fname' value = '".$goods_view["multi"][$columnKey][$languageKey]."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_type' value = '".$goodsField["option"][$languageKey][$columnKey]["file_type"]."'>";

													if($columnKey == "upload_fname") {
														$uploadPath = _UPLOAD."/goods/file";
													}else{
														$uploadPath = _UPLOAD."/goods/".$columnKey;
													}
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_folder' value = '".$uploadPath."'>";

													if($goodsOption["file_type"] == "image") {
														if(!empty($goodsOption["width"]) && !empty($goodsOption["height"])) {
															$htmlStr .= "<p>* 자동으로 리사이징되어 등록됩니다. ( 자동 )</p>";
															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."</p>";
															}
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_width' value = '".$goodsOption["width"]."'>";
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_height' value = '".$goodsOption["height"]."'>";
														}else {
															$htmlStr .= "<p>* 권장 사이즈 값으로 리사이징되어 등록됩니다. (권장사이즈 : 가로 ".$goodsOption["width"]."px X 세로 ".$goodsOption["height"]."px )</p>";
															if(!empty($goodsOption['width'])){
																$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_width' value = '".$goodsOption["width"]."'>";
															}
															if(!empty($goodsOption['height'])){
																$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_".$languageKey."_height' value = '".$goodsOption["height"]."'>";
															}

															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."mb</p>";
															}
														}
													}   
													$_tmpOname = ($columnKey == "upload_fname" ? $goods_view["upload_oname"] : $goods_view[$columnKey.'_'.$languageKey.'_oname'] ? $goods_view[$columnKey.'_'.$languageKey.'_oname'] : $goods_view["multi"][$columnKey][$languageKey]);

													$htmlStr .= "<span id = '".$columnKey."_".$languageKey."_filezone'  class='file_down_span'>";
													$htmlStr .= "<a href = '/fileRequest/download?file=".urlencode("/goods/".($columnKey == "upload_fname" ? "file" : $columnKey) . "/" . $goods_view["multi"][$columnKey][$languageKey])."&save=".$_tmpOname."' target = '_blank' >";
													$htmlStr .= $_tmpOname;
													$htmlStr .= "</a>";
													if($goods_view["multi"][$columnKey][$languageKey]) {
														$htmlStr .= "<a href = 'javascript://' onclick='uploadForm.uploadRemove("."\"".$columnKey."_".$languageKey."\"".")' class = 'file_no'>";
														$htmlStr .= "<img src='/lib/admin/images/btn_close.gif'>";
														$htmlStr .= "</a>";
													}
													$htmlStr .= "</span>";

													break;
												default: // text
													$htmlStr .= "<input type = 'text' name = '".$columnKey."_".$languageKey."' value = '".$goods_view["multi"][$columnKey][$languageKey]."'>";
													break;
											}
											echo $htmlStr;
										}else {
										?>
											<input type = "text" name = "<?=$columnKey?>_<?=$languageKey?>" value = "<?=$goods_view["multi"][$columnKey][$languageKey]?>">
										<? } ?>
									</td>
								</tr>
								<? } ?>
							<? } ?>
						<? }else{ // 다국어를 지원하지 않을 때 ?>
							<? foreach($goodsField['name'][$site_language] as $columnKey => $columnVal){ // 전체 필드에서 ?>
								<? if(in_array($columnKey, $exceptKey)): continue; endif; ?>
								<? if($columnKey == 'info') { ?>
									<tr id="<?=$site_language?>_link">
										<td class="lang_title" colspan="2">
												<?=$this->_site_language["support_language"][$site_language]?> 추가정보
										</td>
									</tr>
								<? } ?>
								<? if($goodsField["use"][$site_language][$columnKey] != "checked") continue;?>
								<tr>
									<th class="ta_left">
										<? if($goodsField["require"][$site_language][$columnKey] == "checked") { echo "<em>*</em> "; } ?><?=$goodsField["name"][$site_language][$columnKey]?>
									</th>
									<td>
										<?
										if($goodsField["option"][$site_language][$columnKey]){
											$goodsOption = $goodsField["option"][$site_language][$columnKey];
										?>
										<?
											$htmlStr = "";
											switch($goodsOption["type"]){
												case "checkbox":
												case "radio":
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$checkFl = $goods_view[$columnKey] == $itemVal ? "checked" : "";
														$htmlStr .= "<input type='".$goodsOption["type"]."' id='".$columnKey."-".$itemNm."' name='".$columnKey."' value='".$itemVal."' ".$checkFl.">";
														$htmlStr .= "<label for = '".$columnKey."-".$itemNm."'>".$itemVal."</label>";
													}
													break;

												case "select":
													$htmlStr .= "<select name = '".$columnKey."'>";
													foreach($goodsOption["item"] as $itemNm => $itemVal){
														$selectFl = $goods_view[$columnKey] == $itemVal ? "selected" : "";
														$htmlStr .= "<option value = '".$itemVal."' ".$selectFl.">".$itemVal."</option>";
													}
													$htmlStr .= "</select>";
													break;
												case "editor":
													$htmlStr .= "<div class = 'editor-box'>";
													$htmlStr .= "<textarea name = '".$columnKey."' id = '".$columnKey."' class = 'editor'>";
													$htmlStr .= $goods_view[$columnKey];
													$htmlStr .= "</textarea>";
													$htmlStr .= "</div>";
													$htmlStr .= "<script>attachSmartEditor('".$columnKey."', 'goods');</script>";
													break;
												case "file":
													$htmlStr .= "<input type = 'file' name = '".$columnKey."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_oname' value = '".$goods_view['upload_oname']."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_fname' value = '".$goods_view[$columnKey]."'>";
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_type' value = '".$goodsField["option"][$site_language][$columnKey]["file_type"]."'>";

													if($columnKey == "upload_fname") {
														$uploadPath = _UPLOAD."/goods/file";
													}else{
														$uploadPath = _UPLOAD."/goods/".$columnKey;
													}
													$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_folder' value = '".$uploadPath."'>";

													if($goodsOption["file_type"] == "image") {
														if(!empty($goodsOption["width"]) && !empty($goodsOption["height"])) {
															$htmlStr .= "<p>* 자동으로 리사이징되어 등록됩니다. ( 자동 )</p>";
															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."</p>";
															}
														}else {
															$htmlStr .= "<p>* 권장 사이즈 값으로 리사이징되어 등록됩니다. (권장사이즈 : 가로 ".$goodsOption["width"]."px X 세로 ".$goodsOption["height"]."px )</p>";
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_width' value = '".$goodsOption["width"]."'>";
															$htmlStr .= "<input type = 'hidden' name = '".$columnKey."_height' value = '".$goodsOption["height"]."'>";
															if(!empty($max_file_uploads)) {
																$htmlStr .= "<p>* 최대 업로드 가능 이미지 용량 : ".$max_file_uploads."mb</p>";
															}
														}
													}
													$_tmpOname = ($columnKey == "upload_fname" ? $goods_view["upload_oname"] : $goods_view[$columnKey.'_oname']);
													$htmlStr .= "<span id = '".$columnKey."_filezone'  class='file_down_span'>";
													$htmlStr .= "<a href = '/fileRequest/download?file=".urlencode("/goods/".($columnKey == "upload_fname" ? "file" : $columnKey) . "/" . $goods_view[$columnKey])."&save=".$_tmpOname."' target = '_blank'>";
													$htmlStr .= $_tmpOname;
													$htmlStr .= "</a>";
													if($goods_view[$columnKey]) {
														$htmlStr .= "<a href = 'javascript://' onclick='uploadForm.uploadRemove("."\"".$columnKey."\"".")' class = 'file_no'>";
														$htmlStr .= "<img src='/lib/admin/images/btn_close.gif'>";
														$htmlStr .= "</a>";
													}
													$htmlStr .= "</span>";

													break;
												default: // text
													$htmlStr .= "<input type = 'text' name = '".$columnKey."' value = '".$goods_view[$columnKey]."'>";
													break;
											}

											echo $htmlStr;
										?>
										<? }else { ?>
											<input type = "text" name = "<?=$columnKey?>" value = "<?=$goods_view[$columnKey]?>">

										<? } ?>
									</td>
								</tr>
							<? } ?>
						<? } ?>
					</tbody>
				</table>
			</div>
			<div class="sub_tit dn"><h3>상품 개별 SEO 설정</h3></div>
			<div class="table_write dn">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="150px" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>개별 설정 사용</th>
							<td>
								<input type="radio" name="use_seo" id="useSeoY" value="y"<?=$goods_view['use_seo'] == 'y' ? ' checked' : ''?>><label for="useSeoY">예</label>
								<input type="radio" name="use_seo" id="useSeoN" value="n"<?=$goods_view['use_seo'] == 'n' ? ' checked' : ''?>><label for="useSeoN">아니오</label>
							</td>
						</tr>
						<?php
						$flag = ['kor' => 'ko', 'eng' => 'us', 'chn' => 'ch', 'jpn' => 'jp'];
						foreach($flag as $key => $value) :
						?>
						<tr>
							<th><img src="/lib/admin/images/icon_<?=$value?>.gif">타이틀(Title)</th>
							<td><input type="text" name="<?=$key?>_title" value="<?=$seo[$key]['title']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>작성자(Author)</th>
							<td><input type="text" name="<?=$key?>_author" value="<?=$seo[$key]['author']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>설명(Description)</th>
							<td><input type="text" name="<?=$key?>_description" value="<?=$seo[$key]['description']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>키워드(Keywords)</th>
							<td><input type="text" name="<?=$key?>_keywords" value="<?=$seo[$key]['keywords']?>" style="width: 100%;"></td>
						</tr>
						<?php
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>
	<?=form_close()?>
</div>
