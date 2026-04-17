<style>
/*.right { text-align: right; }
.center { text-align: center; }
.d0 { width: 100% !important; }
.d2 { width: 90% !important; }
.d4 { width: 80% !important; }
.d6 { width: 70% !important; }*/
</style>
<script>
	function lpad(n, width) {
		n = n + "";
		return n.length >= width ? n : new Array(width - n.length + 1).join("0") + n;
	}

	$(function() {
		//language_change("<?=$this->_site_language['default']?>");

		$("#divList input").on("blur", function() {
			if($(this).val() != $(this).data("ori")) {
				$(location).attr("href", "?lang=" + $("#lang").val());
				console.log($(this).val());
			}
		});

		$(".add").on("click", function(e) {
			e.preventDefault();
			var n = parseInt($(this).data("code")) * 100, nn = 0, result = "", len = $(this).data("code").length;

			$("input[name='code']").each(function() {
				if(n < parseInt($(this).val()) && (n + 100) > parseInt($(this).val())) {
					nn = parseInt($(this).val());
				}
			});

			result = nn ? (nn + 1) : (n + 1);
			result = result + "";
			result = result.length >= (len + 2) ? result : new Array((len + 2) - result.length + 1).join("0") + result;
			console.log(result);
		});

		$(".remove").on("click", function(e) {
			e.preventDefault();
			var code = parseInt($(this).data("code")) * 100, n;
			$("input[name='code']").each(function() {
				if(code < parseInt($(this).val()) && (code + 100) > parseInt($(this).val())) {
					n = $(this).val();
				}
			});

			if(n) {
				if(confirm("하위 메뉴가 존재합니다. 삭제할 경우 복구가 불가능합니다.\n삭제하시겠습니까?")) {
					$(location).attr("href", "remove_menu?code=" + code + "&t=all");
				}
			} else {
				if(confirm("삭제할 경우 복구가 불가능합니다.\n삭제하시겠습니까?")) {
					$(location).attr("href", "remove_menu?code=" + code + "&t=me");
				}
			}
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
			<a href="javascript://" onclick="addMenu();" class="btn point new_plus">+ 1차 메뉴 추가</a>
			<!-- a href="javascript://" onclick="history.back();" class="btn gray">취소</a>
			<a href="javascript://" onclick="menuSave(document.frm);"  class="btn point">저장</a -->
		</div>
	</div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<input type="hidden" name="lang" id="lang" value="<?=$this->input->get("lang", true)?>">
		<div class="menu_dep_wrapper">
			<div class="menu_dep_title">
				<ul>
					<li class="dep_used">사용</li>
					<li class="dep_code">고유코드</li>
					<li class="dep_name">메뉴명</li>
					<li class="dep_link">연결 URL</li>
				</ul>
			</div>
			<div class="menu_dep_list" id='divList'>
				<!-- 하드코딩 미리보기 -->
				<ul>
					<li class='deps_0'>
						<div class="clear">
							<div class="dep_used"><span class='switch__container'><input type='checkbox' name='use' id='use0_y' value='y' class='switch switch--shadow' checked><label for='use0_y'>사용</label></span></div>
							<div class="dep_code"><input type='text' name='code' class='"' value=''></div>
							<div class="dep_name"><input type='text' name='name' class='"' value='1차 메뉴 예제'></div>
							<div class="dep_link"><input type='text' name='url' class='' value=''><a href='#' class='btn_dep remove'>삭제</a><a href='#' class='btn_dep add'>하위 메뉴 추가</a></div>
						</div>
						<ul>
							<li class='deps_2'>
								<div class="clear">
									<div class="dep_used"><span class='switch__container'><input type='checkbox' name='use' id='use2_y' value='y' class='switch switch--shadow' checked><label for='use2_y'>사용</label></span></div>
									<div class="dep_code"><input type='text' name='code' class='"' value=''></div>
									<div class="dep_name"><input type='text' name='name' class='"' value='1차의 하위 2차 메뉴 예제'></div>
									<div class="dep_link"><input type='text' name='url' class='' value=''><a href='#' class='btn_dep remove'>삭제</a><a href='#' class='btn_dep add'>하위 메뉴 추가</a></div>
								</div>
								<ul>
									<li class='deps_4'>
										<div class="clear">
											<div class="dep_used"><span class='switch__container'><input type='checkbox' name='use' id='use4_y' value='y' class='switch switch--shadow' checked><label for='use4_y'>사용</label></span></div>
											<div class="dep_code"><input type='text' name='code' class='"' value=''></div>
											<div class="dep_name"><input type='text' name='name' class='"' value='1차의 2차의 하위 3차 메뉴 예제'></div>
											<div class="dep_link"><input type='text' name='url' class='' value=''><a href='#' class='btn_dep remove'>삭제</a><a href='#' class='btn_dep add'>하위 메뉴 추가</a></div>
										</div>
										<ul>
											<li class='deps_6'>
												<div class="clear">
													<div class="dep_used"><span class='switch__container'><input type='checkbox' name='use' id='use6_y' value='y' class='switch switch--shadow' checked><label for='use6_y'>사용</label></span></div>
													<div class="dep_code"><input type='text' name='code' class='"' value=''></div>
													<div class="dep_name"><input type='text' name='name' class='"' value='1차의 2차 3차의 하위 4차 메뉴 예제'></div>
													<div class="dep_link"><input type='text' name='url' class='' value=''><a href='#' class='btn_dep remove'>삭제</a><a href='#' class='btn_dep add'>하위 메뉴 추가</a></div>
												</div>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
				<!-- 하드코딩 미리보기 -->
				<ul>
				<?php
				foreach($menus as $key => $value) :
					$checked = $value['use'] == "y" ? " checked" : "";
					$add = strlen($value['code']) < 8 ? "<a href='#' class='btn_dep add' data-code='".$value['code']."'>하위 메뉴 추가</a>" : "";
					echo "
						<li class='deps_0'>
							<div class='clear'>
								<div class='dep_used'>
								<span class='switch__container'><input type='checkbox' name='use' id='use".$value['code']."_y' value='y' class='switch switch--shadow' '".$checked."><label for='use".$value['code']."_y'>사용</label></span></div>
								<div class='dep_code'><input type='text' name='code' value='".$value['code']."' data-ori='".$value['code']."'></div>
								<div class='dep_name'><input type='text' name='name' value='".$value['name']."' data-ori='".$value['name']."'></div>
								<div class='dep_link'><input type='text' name='url' value='".$value['url']."' data-ori='".$value['url']."'><a href='#' class='btn_dep remove' data-code='".$value['code']."'>삭제</a>".$add."</div>
							</div>";
					if(count($value['sub']) > 0) :
						echo "<ul>";
						foreach($value['sub'] as $k => $v) :
							$cn = strlen($v['code']) - 2;
							$addSub = strlen($v['code']) < 8 ? "<a href='#' class='btn_dep add' data-code='".$v['code']."'>하위 메뉴 추가</a>" : "";
							echo "
								<li class='deps_".$cn."'>
									<div class='clear'>
										<div class='dep_used'><span class='switch__container'><input type='checkbox' name='use' id='use".$cn."_y' value='y' class='switch switch--shadow' checked><label for='use".$cn."_y'>사용</label></span></div>
										<div class='dep_code'><input type='text' name='code' class='' value='".$v['code']."'></div>
										<div class='dep_name'><input type='text' name='name' class='' value='".$v['name']."'></div>
										<div class='dep_link'><input type='text' name='url' class='' value='".$v['url']."'><a href='#' class='btn_dep remove' data-code='".$v['code']."'>삭제</a>".$addSub."</div>
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
	<?=form_close();?>
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