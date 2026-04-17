<style>
/*.right { text-align: right; }
.center { text-align: center; }
.d0 { width: 100% !important; }
.d2 { width: 90% !important; }
.d4 { width: 80% !important; }
.d6 { width: 70% !important; }*/
#tmp { display: none; }
</style>
<script>
	$(function() {
		//language_change("<?=$this->_site_language['default']?>");

		$("#divList input").on("blur", function() {
			if($(this).val() != $(this).data("ori")) {
				var no = $(this).closest("li").find("input[name='use']").data("no");
				var col = $(this).data("col");
				var str = $(this).val();
				$.ajax({
					url: "put_menu",
					data: {
						no: no,
						col: col,
						str: str
					},
					success: function(res) {}
				});
			}
		});

		$(".switch").on("click", function() {
			$.ajax({
				url: "change_use",
				data: {
					no: $(this).data("no"),
					yn: $(this).is(":checked")
				},
				success: function() {}
			});
		});

		$("#addMenu").on("click", function(e) {
			e.preventDefault();
			var n = 0;
			$("input.codes").each(function() {
				if($(this).val().length == 2) {
					n = parseInt($(this).val());
				}
			});

			var nn = n + 1;
			nn = nn.toString().padStart(2, "0");
			$("#divList .adding").remove();
			var html = "<li class='adding'>" + $("#tmp").html() + "</li>";
			if($("#divList").find("li").length > 0) {
				$("#divList li.deps_0:last").append(html);
			} else {
				$("#divList ul").append(html);
			}
			$("#divList .adding").show().find("input[name='code']").val(nn);
			$("input[name='name']").focus();
		});

		$(".add").on("click", function(e) {
			e.preventDefault();
			var n = parseInt($(this).data("code")) * 100, nn = 0, result = "", len = $(this).data("code").length;

			$("input.codes").each(function() {
				if(n < parseInt($(this).val()) && (n + 100) > parseInt($(this).val())) {
					nn = parseInt($(this).val());
				}
			});

			result = nn ? (nn + 1) : (n + 1);
			nn = result.toString().padStart((result.toString().length + (result.toString().length % 2)), "0");
			$("#divList .adding").remove();
			if($(this).closest("li").find("ul").length > 0) {
				$(this).closest("li").find("ul").prepend("<li class='adding'>" + $("#tmp").html() + "</li>");
			} else {
				$(this).closest("li").after("<li class='adding'>" + $("#tmp").html() + "</li>");
			}
			$("#divList .adding").show().find("input[name='code']").val(nn);
			$("input[name='name']").focus();
		});

		$(".remove").on("click", function(e) {
			e.preventDefault();
			var code = parseInt($(this).data("code")) * 100, n, no = $(this).data("no");
			$("input.codes").each(function() {
				if(code < parseInt($(this).val()) && (code + 100) > parseInt($(this).val())) {
					n = $(this).val();
				}
			});

			if(n) {
				if(confirm("하위 메뉴가 존재합니다. 삭제할 경우 복구가 불가능합니다.\n삭제하시겠습니까?")) {
					$(location).attr("href", "remove_menu?lang=" + $("#lang").val() + "&code=" + $(this).data("code"));
				}
			} else {
				if(confirm("삭제할 경우 복구가 불가능합니다.\n삭제하시겠습니까?")) {
					$(location).attr("href", "remove_menu?no=" + no + "&lang=" + $("#lang").val());
				}
			}
		});

		$(document).on("click", ".remove-this", function(e) {
			e.preventDefault();
			$(this).closest("li.adding").remove();
		});
	});

	var site_language = "<?=$this->_site_language['default']?>";
</script>
<div id="contents">
	<div class="main_tit">
		<h2>메뉴 설정</h2>
		<?php if(!$this->_site_language["multilingual"]) : ?>
			<input type="hidden" name="language" value="<?=$this->_site_language[""]?>" />
		<?php endif ?>
		<div class="lang_icon_tab">
			<?php if($this->_site_language["multilingual"]) : ?>
			<ul>
			<?php
			foreach($this->_site_language["support_language"] as $key => $value) :
				foreach($this->_site_language['set_language'] as $k => $v) :
					if($k == $key) :
						$on = $this->input->get("lang", true) == $key ? "on" : "";
						echo "<li class='".$on." lang_".$key."'><a href='?lang=".$key."'>".$value."</a></li>";
					endif;
				endforeach;
			endforeach;
			?>
			</ul>
			<?php endif ?>
		</div>
		<div class="btn_right">
			<a href="#" id="addMenu" class="btn point new_plus" data-code="1">+ 1차 메뉴 추가</a>
			<!-- a href="javascript://" onclick="history.back();" class="btn gray">취소</a>
			<a href="javascript://" onclick="menuSave(document.frm);"  class="btn point">저장</a -->
		</div>
	</div>
		<div id="tmp">
			<form method="post" action="add_menu">
				<input type="hidden" name="mode" value="<?=$mode?>" />
				<input type="hidden" name="lang" id="lang" value="<?=$this->input->get("lang", true)?>">
				<input type="text" name="code" required>
				<input type="text" name="name" required>
				<input type="text" name="url" required>
				<input type="submit" value="submit">
				<a href="#" class="remove-this">[삭제]</a>
			</form>
		</div>
		<div class="menu_dep_wrapper">
			<div class="menu_dep_title">
				<ul>
					<li class="dep_used">사용</li>
					<li class="dep_code">고유코드</li>
					<li class="dep_name">메뉴명</li>
					<li class="dep_link">연결 URL</li>
				</ul>
			</div>
			<div class="menu_dep_list" id="divList">
				<ul>
				<?php
				foreach($menus as $key => $value) :
					$mc = $value['use'] == "y" ? " checked" : "";
					$add = strlen($value['code']) < 8 ? "<a href='#' class='btn_dep add' data-code='".$value['code']."'>하위 메뉴 추가</a>" : "";
					echo "
						<li class='deps_0'>
							<div class='clear'>
								<div class='dep_used'>
								<span class='switch__container'><input type='checkbox' name='use' id='use".$value['code']."_y' value='".$value['use']."' data-no='".$value['no']."' class='switch switch--shadow'".$mc."><label for='use".$value['code']."_y'>사용</label></span></div>
								<div class='dep_code'><input type='text' class='codes' value='".$value['code']."' data-ori='".$value['code']."' data-col='code'></div>
								<div class='dep_name'><input type='text' value='".$value['name']."' data-ori='".$value['name']."' data-col='name'></div>
								<div class='dep_link'><input type='text' value='".$value['url']."' data-ori='".$value['url']."' data-col='url'><a href='#' class='btn_dep remove' data-code='".$value['code']."' data-no='".$value['no']."'>삭제</a>".$add."</div>
							</div>";
					if(count($value['sub']) > 0) :
						echo "<ul>";
						foreach($value['sub'] as $k => $v) :
							$cn = strlen($v['code']) - 2;
							$sc = $v['use'] == "y" ? " checked" : "";
							$addSub = strlen($v['code']) < 8 ? "<a href='#' class='btn_dep add' data-code='".$v['code']."'>하위 메뉴 추가</a>" : "";
							echo "
								<li class='deps_".$cn."'>
									<div class='clear'>
										<div class='dep_used'><span class='switch__container'><input type='checkbox' name='use' id='use".$v['code']."_y' value='".$v['use']."' data-no='".$v['no']."' class='switch switch--shadow'".$sc."><label for='use".$v['code']."_y'>사용</label></span></div>
										<div class='dep_code'><input type='text' class='codes' value='".$v['code']."' data-ori='".$v['code']."' data-col='code'></div>
										<div class='dep_name'><input type='text' value='".$v['name']."' data-ori='".$v['name']."' data-col='name'></div>
										<div class='dep_link'><input type='text' value='".$v['url']."' data-ori='".$v['url']."' data-col='url'><a href='#' class='btn_dep remove' data-code='".$v['code']."' data-no='".$v['no']."'>삭제</a>".$addSub."</div>
									</div>
								</li>";
						endforeach;
						echo "</ul>";
					endif;
					echo "</li>";
				endforeach;
				?>
				</ul>
			</div>
		</div>
	<div class="terms_privecy_box">
		<dl>
			<dt>- 상품 카테고리를 메뉴관리에서 사용할 경우 주의사항</dt>
			<dd>
			사용자 페이지(홈페이지) 상단 메뉴 활성화 기능으로 1차 메뉴의 하위 메뉴에 접속하게 되면 해당 1차 메뉴가 활성화 됩니다. <br>
			"상품관리 > 카테고리관리" 에서 등록한 상품 카테고리를 메뉴관리에 등록할 경우, <em class="point">하위 카테고리가 있는 1차 카테고리 한 개</em>만 등록이 가능합니다. <br><br>

			*** 1차 메뉴에 하위 카테고리가 없는 1차 상품 카테고리를 2개 이상 사용해야 할 경우, 소스상에서 별도로 상단메뉴 활성화 처리를 script로 진행해주셔야 합니다.<br><br>
			</dd>
		</dl>
	</div>
</div>
