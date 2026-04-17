<script>
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>휴면회원 관리</h2>
	</div>
	<div class="table_write_info">* 휴면회원은 마지막 접속일로부터 1년이 지난 회원이 자동으로 전환되며, 관리자가 임의로 수정·변경 관리할 수 없습니다.</div>
	<div class="main_search">
			<fieldset>
				<?=form_open("", array("method"=>"GET"));?>
				<select name="search_type">
					<option value="userid" <?=set_select("search_type", "userid")?>><?=$memberField["name"]["kor"]["userid"]?></option>
					<option value="name" <?=set_select("search_type", "name")?>><?=$memberField["name"]["kor"]["name"]?></option>
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
				<col width="7%" />
				<col width="13%" />
				<col width="11%" />
				<col width="11%" />
				<col width="24%" />
				<col width="13%" />
				<col width="13%" />
				<col width="8%" />
			</colgroup>
			<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col"><?=$memberField["name"]["kor"]["userid"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["name"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["mobile"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["email"]?></th>
					<th scope="col"><?=$memberField["name"]["kor"]["regdt"]?></th>
					<th scope="col">휴면회원 전환일</th>
					<th scope="col">보기</th>
				</tr>
			</thead>
			<? if(isset($member_list)) : ?>
				<tbody id='divList'>
					<? foreach($member_list as $key => $value) : ?>
						<tr>
							<td><?=$total_rows - $key - $offset?></td>
							<td class="left"><span class="icon-<?=substr($value['language'], 0, 1)?>"><?=$value["userid"]?></span></td>
							<td><?=$value["name"]?></td>
							<td><?=$value["mobile"]?></td>
							<td><?=$value["email"]?></td>
							<td><?=$value["regdt"]?></td>
							<td><?=$value["dormant_dt"]?></td>
							<td><a href="member_dormant_view?userid=<?=$value["userid"]?>" class="btn_mini">보기</a></td>
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
	<div class="terms_privecy_box">
		<dl>
			<dt>- 휴면 회원과 개인정보 유효기간제</dt>
			<dd>
			* 2015년 8월 18일부터 시행된 개인정보유효기간제 시행에 따라 1년 이상 사이트 이용 기록이 없는 고객들은 자동으로 휴면회원 처리가 되며 개인정보가 분리 보관됩니다.<br>
			* 관련 법령인 정보통신망 이용촉진 및 정보보호등에 관한 법률 제29조 2항 시행령 제16조)에 따라 개인정보 유효기간 만료 전에 메일발송으로 고객에게 사전 안내가 되도록 되어있습니다.<br>
			* 휴면회원 전환을 이행하지 않을 경우 3000만원 이하의 과태료가 부과됩니다.<br><br>
			</dd>
		</dl>
		<dl>
			<dt>- 휴면 회원의 관리</dt>
			<dd>
			* 휴면회원은 이용 내역이 없어 '일시적으로 정지'된 회원을 의미하며 운영자가 임의로 휴면회원을 탈퇴시킬 수 없습니다.<br>
			* 휴면회원에게 메일과 SMS를 발송할 경우 관련 법령에 따라 법적인 불이익을 받을 수 있습니다.<br>
			* 휴면회원의 개인정보는 분리, 보관되어 '회원관리 > 휴면관리'에서 별도로 조회 가능합니다. (휴면회원이 로그인 후, 휴면해제를 선택하면 휴면해제가 되며, 휴면관리 리스트에서 회원관리 리스트로 이전 됩니다.)<br><br>
			</dd>
		</dl>
	</div>
</div>