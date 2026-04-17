<script>
	function delete_display_theme(){
		if($(":checkbox[name='no[]']:checked").length < 1) {
			alert("선택된 상품진열이 없습니다.");
			return false;
		}

		if(!confirm("선택된 상품진열을 삭제하시겠습니까?")) {
			return false;
		}

		document.frm.action = "delete_display_theme";
		document.frm.submit();
	}

	$('#leftmenu >ul > li:nth-of-type(3)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>메인 상품진열</h2>
		<div class="btn_right btn_num2">
			<a href="javascript://" onclick="delete_display_theme()" class="btn gray sel_minus">선택 삭제</a>
			<a href="display_main_reg" class="btn point new_plus">+ 상품진열 등록</a>
		</div><!--btn_right-->
		
	</div>
	<div class="main_search">
			<fieldset>
				<?=form_open("", array("name" => "search_frm", "method" => "GET"))?>
					<select name="search_type">
						<option value="" <?=set_select("search_type", "")?>>전체</option>
						<option value="theme_name" <?=set_select("search_type", "theme_name")?>>진열이름</option>
						<option value="theme_description" <?=set_select("search_type", "theme_description")?>>진열설명</option>
						<option value="no" <?=set_select("search_type", "no")?>>치환코드 넘버</option>
					</select>
					<input type="text" class="searchBox" name='search' value="<?=$this->input->get("search", true)?>" />
					<button class="floatL">검색</button>
				<?=form_close()?>
			</fieldset>
		</div>
	<?=form_open("", array("name" => "frm"))?>
		<div class="table_list">
		  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableA">
			<colgroup>
			  <col width="3%" />
			  <col width="7%" />
			  <col width="15%" />
			  <col width="22%" />
			  <col width="30%" />
			  <col width="13%" />
			  <col width="10%" />
			</colgroup>
			<thead>
			  <tr>
				<th scope="col"><input type="checkbox" onchange="checkToggle(this, 'no[]');" /></th>
				<th scope="col">No.</th>
				<th scope="col">진열명</th>
				<th scope="col">진열설명</th>
				<th scope="col">치환코드</th>
				<th scope="col">등록일</th>
				<th scope="col">관리</th>
			  </tr>
			</thead>
			<tbody id='divList'>
			<?php if(isset($display_main_list)) : ?>
				<?php foreach($display_main_list as $key => $value) : ?>
					<tr>
						<td><input type="checkbox" name="no[]" value="<?=$value["no"]?>" /></td>
						<td><?=$total_rows - $key - $offset?></td>
						<td align="left"><?=$value["theme_name"]?></td>
						<td align="left"><?=$value["theme_description"]?></td>
						<td>{=include_display_main('display_main', '<?=$value['no']?>')}</td>
						<td><?=$value['regdt']?></td>
						<td>
							<a href="display_main_reg?no=<?=$value["no"]?>" class="btn_mini on">수정</a>
						</td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
			</tbody>
		  </table>
		</div><!--table_list-->
	
	<?=form_close()?>
	<div class="btn_paging">
		<?=$pagination?>
	</div><!--btn_paging-->

	<div class="terms_privecy_box">
		<dl>
			<dt>- 메인 진열 상품을 프론트페이지(홈페이지)에서 보이게하려면?</dt>
			<dd>
			메인 진열 상품을 생성하고나면, <em class="point">"치환코드"</em> 항목이 보이실겁니다. <br>
			해당 치환코드를 보여주고싶은 <em class="point">홈페이지 스킨명/index.html</em> 안에 <em>html 소스작업</em>을 하여 넣어주셔야 합니다.<br>
			예 ) {=include_display_main('display_main', '1')} 치환태그를 메인 진열 상품이 나왔으면 하는 곳에 넣어주세요.<br><br>
			</dd>
		</dl>
		<dl>
			<dt>- 메인 진열 상품이 나오는 곳 디자인을 바꾸고 싶어요.</dt>
			<dd>
			메인 진열 상품 치환태그는, <em class="point">홈페이지 스킨명/layout/display/메인진열상품설정에서 선택한 스킨리스트.html</em>을 메인으로 불러오게끔 하는 역할을 합니다.<br/>
			즉, 메인 진열 상품에 적용되는 html 태그는 모두 <em>layout/display</em>안에 html 파일 형태로 있는 거지요.<br>
			새로운 디자인을 추가하려면, 해당 폴더에 새로운 html 파일을 추가하고, 메인 진열 상품 설정하는 곳에서 스킨리스트를 변경해주시면 됩니다.<br><br>
			</dd>
		</dl>
	</div>
</div>