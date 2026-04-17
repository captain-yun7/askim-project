<script>
	$(function(){
		$(".btn_save").click(function(){
			var frm = $("[name='frm']");
			$(frm).attr("action","goods_list_save");
			frm.submit();
		});

		$("#setCategory").on("click", function(e) {
			e.preventDefault();
			if($(".goods-no:checked").length > 0) {
				var goods = $(".goods-no:checked").map(function() {
					return $(this).val();
				}).get().join("|");
				window.open($(this).attr("href") + "?goods=" + goods, "set_category", "width=810, height=400, scrollbars=no");
			} else {
				alert("카테고리를 변경할 상품을 선택해 주세요.");
			}
		});

		$("#copyGoods").on("click", function(e) {
			e.preventDefault();
			if($(".goods-no:checked").length > 0) {
				var goods = $(".goods-no:checked").map(function() {
					return $(this).val();
				}).get().join("|");
				$(location).attr("href", "copy_goods?no=" + goods);
			} else {
				alert("복사할 상품을 선택해 주세요.");
			}
		});
	});
	function goods_state_change(no, yn_state) {
		var set_data = {
			no : no,
			yn_state : yn_state
		};

		$.ajax({
			url : "/admin/goods/goods_state_change",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert("변경되었습니다.");
							location.reload();
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

	function goods_delete(){
		if($(":checkbox[name='no[]']:checked").length < 1) {
			alert("선택된 상품이 없습니다.");
			return false;
		}

		if(!confirm("선택된 상품을 삭제하시겠습니까?")) {
			return false;
		}

		document.frm.action = "goods_delete";
		document.frm.submit();
	}
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>상품 리스트</h2>
		<div class="btn_right btn_num3">
			<a href="goods_reg" class="btn point new_plus">+ 신규 상품 등록</a>
		</div><!--btn_right-->
	</div>
	<div class="clear">
		<div class="btn_left fl">
			<a href="category_pop" id="setCategory" class="btn btnline1">선택 카테고리 관리</a>
			<a class="btn btnline1 btn_save">노출 설정 저장</a>
			<a href="#" id="copyGoods" class="btn btnline1">선택 복사</a>
			<a href="javascript://" onclick="goods_delete()" class="btn btnline1">선택 삭제</a>
		</div>
		<div class="main_search fr">
			<fieldset>
				<?=form_open("", array("name" => "search_frm", "method" => "GET"))?>
					<select name="search_type">
					<option value="Gm.name" <?=set_select("search_type", "Gm.name")?>><?=$goodsField["name"]["kor"]["name"]?></option>
						<!-- 추가영역 -->
						<option value="Cm.categoryNm" <?=set_select("search_type", "Cm.categoryNm")?>>카테고리</option>
						<!-- 추가영역 -->
						<option value="Go.no" <?=set_select("search_type", "Go.no")?>><?=$goodsField["name"]["kor"]["no"]?></option>
					</select>
					<select name="sort_type">
						<option value="" <?=set_select("sort_type", "")?>>기본 정렬</option>
						<option value="Go.regDt" <?=set_select("sort_type", "Go.regDt")?>>등록일별 정렬</option>
						<option value="hitCnt" <?=set_select("sort_type", "hitCnt")?>>조회순별 정렬</option>
					</select>
					<input type="text" class="searchBox" name='search' value="<?=$search?>" />
					<button class="floatL">검색</button>
				<?=form_close()?>
			</fieldset>
		</div>
	</div>
	<?=form_open("", array("name" => "frm"))?>
		<div class="table_list">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="4%" /><!-- 체크박스 -->
					<col width="5%" /><!-- No -->
					<col width="7%" /><!-- 상품No -->
					<col width="10%"><!--썸네일-->
					<col width="*"/><!-- 상품명 -->
					<col width="7%" /><!-- 노출유무 -->
					<col width="7%" /><!-- 순서 -->
					<col width="11%" /><!-- 관리 -->
					<col width="7%"/><!--등록일-->
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="all" id="all" value=0 onchange="checkToggle(this, 'no[]');"></th>
						<th scope="col">No.</th>
						<th scope="col">상품번호</th>
						<th scope="col"></th>
						<th scope="col"><?=$goodsField["name"]["kor"]["name"]?></th>
						<th scope="col" class=""><?=$goodsField["name"]["kor"]["yn_state"]?></th>
						<!-- 변경됨 --><th scope="col" class="">노출 순서</th><!-- 변경됨 -->
						<th scope="col">관리</th>
						<th scope="col">등록일</th>
					</tr>
				</thead>
				<tbody>
				<? if(isset($goods_list)) : ?>
					<? foreach($goods_list as $key => $value) : ?>
						<input type="hidden" name = "goodsNo[]" value = "<?=$value["no"]?>">
						<tr>
							<td><input type="checkbox" name="no[]" class="goods-no" value="<?=$value["no"]?>" /></td>
							<td><?=$total_rows - $key - $offset?></td>
							<td><?=$value["no"]?></td>
							<td>
							<? if(!empty($value["img2"])){ ?>
								<img src = "<?=_UPLOAD?>/goods/img2/<?=$value["img2"]?>"/>
							<? } ?>
							</td>
							<td class="left"><?=(!empty($value["multi_name"]) ? $value["multi_name"] : $value["name"])?></td>
							<td>
								<label><input type="checkBox" name = "yn_state[]" value = "<?=$value["no"]?>"<? if($value["yn_state"] == "y") : ?>checked<? endif ?>> 노출</label>
							</td>
							<td><!-- 순서는 숫자로 기입할 수 있으며 기본값은 빈값입니다. --><input type="text" name="sortNum[]" class="inq_w30" value="<?=$value["sortNum"]?>" maxlength="3"></td>
							<td>
								<a href="/goods/goods_view?no=<?=$value["no"]?>" class="btn_mini" target="_blank">보기</a>
								<a href="goods_reg?no=<?=$value["no"]?>&per_page=<?=$this->input->get("per_page", true)?>&search_type=<?=$this->input->get("search_type", true)?>&sort_type=<?=$this->input->get("sort_type", true)?>&search=<?=urlencode($this->input->get("search", true))?>" class="btn_mini on">수정</a>
							</td>
							<td><span class="bbs_tag"><?=$value["regdt"]?></span></td>
						</tr>
					<? endforeach; ?>
				<? else : ?>
					<tr>
						<td colspan="8">등록된 상품이 없습니다</td>
					</tr>
				<? endif; ?>
				</tbody>
			</table>
		</div>
	<?=form_close()?>
	<div class="btn_paging">
		<?=$pagination?>
	</div><!--btn_paging-->
	<div class="terms_privecy_box">
		<dl>
			<dt>- "카테고리 노출유무"는 어떤 기능인가요? </dt>
			<dd>해당 상품이 담겨있는 카테고리 접속 시, 해당 상품 노출 유무를 설정하는 메뉴입니다. </dd>
		</dl>
		<dl class="mt20">
			<dt>- "카테고리 노출순서"는 어떤 기능인가요?</dt>
			<dd>카테고리 접속 시, 노출되는 상품 리스트 순서를 변경하고 싶을 때 활용 가능한 메뉴입니다. </dd>
		</dl>
	</div>
</div>