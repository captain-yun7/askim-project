<script>
	function member_delete(){
		if($(":checkbox[name='userid[]']:checked").length < 1) {
			alert("선택된 회원이 없습니다.");
			return false;
		}

		if(!confirm("선택된 회원을 삭제하시겠습니까?")) {
			return false;
		}

		document.frm.action = "member_delete";
		document.frm.submit();
	}
</script>

<div id="contents">
	<div class="main_tit">
		<h2>
			회원 리스트
		</h2>
		<div class="btn_right btn_num2">
			<a href="javascript://" onclick="member_delete()" class="btn gray sel_minus">선택 삭제</a>
			<a href="member_reg" class="btn point new_plus">+ 회원 등록</a>
		</div><!--btn_right-->
	</div>
	
	<div class="table_write_info">* 화이트 도메인 미등록시 포털사에서 스팸으로 분류하여 메일이 발송이 되지 않습니다.</div>
	<div class="table_write_info">* 화이트 도메인 등록은 각 포털사 고객센터에 문의하세요.</div>
	<div class="main_search">
		<fieldset>
			<?=form_open("", array("method"=>"GET"));?>
			<select name="search_type">
				<option value="userid" <?=set_select("search_type", "userid")?>><?=$memberField["name"]["kor"]["userid"]?></option>
				<option value="name" <?=set_select("search_type", "name")?>><?=$memberField["name"]["kor"]["name"]?></option>
			</select>
			<select name="search_lang">
				<option value="">언어별(전체)</option>
                <? foreach($this->_site_language["set_language"] as $key => $value) : ?>
                    <option value="<?=$key?>" <?=set_select("search_lang", $key)?>><?=$value?></option>
                <? endforeach; ?>
			</select>
			<select name="search_level">
				<option value="">등급별(전체)</option>
                <? foreach($member_grade_list as $value) :?>
                    <option value="<?=$value["level"]?>" <?=set_select("search_level", $value["level"])?>><?=$value["gradenm"]?></option>
                <? endforeach; ?>
			</select>
			<input type="text" class="searchBox" name='search' value="<?=set_value("search")?>" />
			<button class="floatL">검색</button>
			<?=form_close()?>
		</fieldset>
	</div>
	<div class="table_list">
        <?=form_open("", array("name" => "frm"));?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col width="3%" /><!-- 선택 -->
				<col width="8%" /><!-- 번호 -->
				<col width="8%" /><!-- 언어구분 -->
				<col width="12%" /><!-- 아이디 -->
				<col width="12%" /><!-- 이름 -->
				<col width="11%" /><!-- 회원등급 -->
				<col width="18%" /><!-- 주소 -->
				<col width="11%" /><!-- 연락처 -->
				<col width="9%" /><!-- 가입일 -->
				<col width="8%" /><!-- 정보수정 -->
			</colgroup>
			<thead>
				<tr>
					<th scope="col"><input type="checkbox" name="all" id="all" value=0 onchange="checkToggle(this, 'userid[]');"></th>
					<th scope="col">번호</th>
					<th scope="col">언어</th>
					<th scope="col"><?=$memberField["name"]["kor"]["userid"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["name"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["level"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["email"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["mobile"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["regdt"]?></th>
					<!--th scope="col"><?=$memberField["name"]["kor"]["last_login"]?></th-->
					<th scope="col">정보수정</th>
				</tr>
			</thead>
			<? if(isset($member_list)) : ?>
				<tbody id='divList'>
					<? foreach($member_list as $key => $value) : ?>
						<tr>
							<td><input type="checkbox" name="userid[]" value="<?=$value["userid"]?>" /></td>
							<td><?=$total_rows - $key - $offset?></td>
							<td><?=$this->_site_language["support_language"][$value["language"]]?></td>
							<td><?=$value["userid"]?></td>
							<td><?=$value["name"]?></td>
							<td><?=$member_grade_text[$value["level"]]?></td>
							<td><?=$value["email"]?></td>
							<td><?=$value["mobile"]?></td>
							<td><?=substr($value["regdt"], 0, 10)?></td>
							<!--td><?=$value["last_login"]?></td-->
							<td>
								<? if($this->_admin_member["super"] || $this->_admin_member["level"] >= $value["level"] ) : ?>
									<a href="member_reg?userid=<?=$value["userid"]?>&registLanguage=<?=$value["language"]?>&per_page=<?=$this->input->get("per_page", true)?>&search_type=<?=$this->input->get("search_type", true)?>&search=<?=urlencode($this->input->get("search", true))?>&search_lang=<?=$this->input->get("search_lang", true)?>&search_level=<?=$this->input->get("search_level", true)?>" class="btn_mini">관리</a>
								<? else:?>
								<? endif; ?>
							</td>
						</tr>
					<? endforeach; ?>
				</tbody>
			<? else : ?>
				<tr><td colspan="10">회원정보가 없습니다.</td></tr>
			<? endif; ?>
		</table>
        <?=form_close()?>
	</div>

	<div class="btn_paging">
		<?=$pagination?>
	</div><!--btn_paging-->
</div>