<script>
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>탈퇴회원 리스트</h2>
		
	</div>
	<div class="main_search">
			<fieldset>
				<?=form_open("", array("method"=>"GET"));?>
				<select name="search_type">
					<option value="userid" <?=set_select("search_type", "userid")?>>아이디</option>
					<option value="name" <?=set_select("search_type", "name")?>>이름</option>
				</select>
				<input type="text" class="searchBox" name='search' value="<?=set_value("search")?>" />
				<button class="floatL">검색</button>
				<?=form_close()?>
			</fieldset>
		</div>
	<div class="table_list">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<!-- <col width="5%" /> -->
				<col width="8%" />
				<col width="14%" />
				<col width="13%" />
				<col width="15%" />
				<col width="50%" />
			</colgroup>
			<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col">아이디</th>
					<th scope="col">이름</th>
					<th scope="col">탈퇴일</th>
					<th scope="col">탈퇴 내용</th>
				</tr>
			</thead>
			<? if(isset($member_list)) : ?>
				<tbody id='divList'>
					<? foreach($member_list as $key => $value) : ?>
						<tr>
							<td><?=$total_rows - $key - $offset?></td>
							<td class="left"><span class="icon-<?=substr($value['language'], 0, 1)?>"><?=$value["userid"]?></span></td>
							<td><?=$value["name"]?></td>
							<td><?=$value["withdrawal_dt"]?></td>
							<td class="left"><?=$value["withdrawal_reason"]?></td>
						</tr>
					<? endforeach; ?>
				</tbody>
			<? else : ?>
				<tr><td colspan="10">회원정보가 없습니다.</td></tr>
			<? endif; ?>
		</table>
	</div>

	<div class="btn_paging">
		<?=$pagination?>
	</div><!--btn_paging-->
</div>