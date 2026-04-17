<script>
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				theme_name : {required : true},
				theme_description : {required : true},
				skin_type : {required : true}
			}, messages : {
				theme_name : {required : "진열명을 입력해주세요."},
				theme_description : {required : "진열설명을 입력해주세요."},
				skin_type : {required : "상품진열 스킨을 선택해주세요."}
			}
		});

		$("#checkRegistered, #checkAllGoods").on("click", function() {
			$(this).closest("table").find("input.goods").trigger("click");
		});

		$("#saveGoods").on("click", function(e) {
			e.preventDefault();
			$("#frm").submit();
		});

		$(".btn_remove").on("click", function(e) {
			e.preventDefault();
			if($("#registered input.goods:checked").length > 0) {
				$("#frm").attr({
					"action": "/admin/auth/remove_display_from_main",
					"target": ""
				}).submit();
			} else {
				alert("메인 상품 진열에서 삭제할 상품을 선택해 주세요.");
				return false;
			}
		});

		$(".btn_add").on("click", function(e) {
			e.preventDefault();
			if($("#all input.goods:checked").length > 0) {
				$("#frm").attr({
					"action": "/admin/auth/add_display",
					"target": ""
				}).submit();
			} else {
				alert("추가할 상품을 선택해 주세요.");
				return false;
			}
		});

		$(".goods-name").on("click", function() {
			$(this).siblings("td").find("input.goods").trigger("click");
		});
/*
		$("#mainGoods").droppable({
			drop: function(e, ui) {
				if($("td.nothing").length > 0) $("td.nothing").parent("tr").remove();
				var no = ui.draggable.children("td:eq(0)").data("no");
				if(no > 0) {
					ui.draggable.children("td:eq(0)").html("<input type='checkbox' name='goodsNo[]' class='goods' value='" + no + "'>");
				}
			}
		});
*/
		var reg = $("#registered input.goods").map(function() {
			return $(this).val();
		}).get();

		$("#all input.goods").each(function() {
			if($.inArray($(this).val(), reg) > -1) {
				$(this).attr("disabled", true);
			}
		});

		$("#mainGoods").sortable({
			stop: function() {
				var chks = $(this).find("input.goods").map(function() {
					return $(this).val() + ":" + $(this).data("n");
				}).get().join("|");
				$("#orderBy").val(chks);
				$("#frm").attr({
					"action": "/admin/auth/sort_display",
					"target": "ifr_processor"
				}).submit();
			}
		});

		$("#category").on("change", function() {
			$(location).attr("href", "display_main_reg?no=" + $("#no").val() + "&category=" + $(this).val());
		});
	});

	$('#leftmenu >ul > li:nth-of-type(3)').addClass('on').find('ul li:nth-of-type(2)').addClass('active');
</script>
<div id="contents">
	<?=form_open("", array("name" => "frm", "id" => "frm"));?>
	<input type="hidden" name="mode" value="<?=$mode?>" />
	<input type="hidden" name="no" id="no" value="<?=$display_main['no']?>" />
	<input type="hidden" name="theme_no" value="<?=$this->input->get("no", true)?>">
	<input type="hidden" name="orderby" id="orderBy" value="">
		<div class="main_tit">
		<h2>메인 상품진열 - <?=$mode == "register" ? "등록" : "수정"?></h2>
		<div class="btn_right btn_num2">
			<button><a href="#" id="saveGoods" class="btn point">저장하기</a></button>
			<button type="button" onclick="javascript:location.href='display_main_list'"><a href="display_main_list" class="btn gray">목록보기</a></button>
		</div><!--btn_center-->
	</div><!--main_tit-->
		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableB">
				<colgroup>
					<col width="14%" />
					<col width="36%" />
					<col width="14%" />
					<col width="*" />
				</colgroup>
				<tr>
					<th scope="row">진열명</th>
					<td colspan="3"><input type="text" name="theme_name" value="<?=$display_main["theme_name"]?>" /></td>
				</tr>
				<tr>
					<th scope="row">진열설명</th>
					<td colspan="3"><input type="text" name="theme_description" value="<?=$display_main["theme_description"]?>"></td>
				</tr>
				<tr>
					<th scope="row">스킨 리스트</th>
					<td colspan="3">
						<select name="skin_type">
							<?php foreach($skin_files as $key => $value) : ?>
								<option value="<?=$value?>" <?if($display_main["skin_type"] == $value):?>selected<?endif?>><?=$value?></option>
							<?php endforeach ?>
						</select>
						<button type="button" class="info_bullet">SKIN위치</button> /data/skin/<?=$this->_skin?>/layout/display<br/>
						<p class="bbs_cuation">스킨추가방법 : FTP 에 위 경로대로 접속한 뒤, html 파일을 업로드하면 html 파일명으로 스킨리스트가 노출됩니다. (단, html 파일 안에 소스작업이 되어있어야 함.)</p>
					</td>
				</tr>
				<tr>
					<th scope="row">등록일</th>
					<td><?=$display_main["regdt"]?></td>
					<th scope="row">최종 업데이트</th>
					<td><?=$display_main["moddt"]?></td>
				</tr>
			</table>
		</div><!--table_write-->
		<?php if($display_main["no"]) : ?>
			<div class="table_list main_goods_add">
				<div class="main-display">
					<h3>등록 상품 리스트</h3>
					<div>
						<table cellpadding="0" cellspacing="0" border="0" id="registered">
							<colgroup>
								<col width="10%" /><!-- No -->
								<col width="12%"><!--썸네일-->
								<col /><!-- 상품명 -->
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="checkRegistered"></th>
									<th scope="col" colspan="2">상품명</th>
								</tr>
							</thead>
							<tbody<?=count($display_main_list) > 0 ? " id='mainGoods'" : ""?>>
							<?php
							if(count($display_main_list) < 1) :
								echo "<tr><td colspan='3' class='nothing'>등록된 상품이 없습니다.</td></tr>";
							else :
								foreach($display_main_list as $key => $value) :
							?>
								<tr>
									<td><input type="checkbox" name="goodsNo[]" class="goods" value="<?=$value["no"]?>" data-n="<?=$value['n']?>"></td>
									<td>
									<? if(!empty($value["img2"])){ ?>
										<img src = "<?=_UPLOAD?>/goods/img2/<?=$value["img2"]?>"/>
									<? } ?>
									</td>
									<td class="goods-name"><?=$value["name"]?></td>
								</tr>
							<?php
								endforeach;
							endif;
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="add_control">
					<a href="#" class="btn_add"><span></span>추가</a>
					<a href="#" class="btn_remove"><span></span>삭제</a>
				</div>
				<div class="goods-list">
					<h3>
						상품 선택
						<select name="category" id="category">
							<option value="">카테고리</option>
							<?php
							foreach($categories as $key => $value) :
								$selected = $value['category'] == $this->input->get("category", true) ? " selected" : "";
								echo "<option value='".$value['category']."'".$selected.">".$value['categorynm']."</option>";
							endforeach;
							?>
						</select>
					</h3>
					<div>
						<table cellpadding="0" cellspacing="0" border="0" id="all">
							<colgroup>
								<col width="10%" /><!-- No -->
								<col width="12%"><!--썸네일-->
								<col /><!-- 상품명 -->
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="checkAllGoods"></th>
									<th scope="col" colspan="2">상품명</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($goods['goods_list'] as $key => $value) :
							?>
								<tr>
									<td><input type="checkbox" name="goods_no[]" class="goods" value="<?=$value['no']?>"></td>
									<td><img src="/upload/goods/img2/<?=$value['img2']?>"></td>
									<td class="goods-name"><?=$value['name']?></td>
								</tr>
							<?php
							endforeach;
							?>
							</tbody>
						</table>
						<?php echo $goods['pagination']; ?>
					</div>
				</div>
			</div>
		<?php endif ?>
	<?=form_close();?>
</div><!-- // contents -->