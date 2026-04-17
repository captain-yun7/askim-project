<script>
	function authDelete(form) {
		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}

		form.submit();
	}
</script>
<div id="contents">
	<div class="main_tit">
		<h2>관리자 등급 리스트</h2>
		<div class="btn_right">
			<? if ( $this->_admin_member["level"] >= 98 ) : ?><a href="member_auth_reg" class="btn point new_plus">+ 관리자 등급 등록</a><? endif; ?>
		</div>
	</div>
	
	<div class="table_write_info">* 관리자 최고 레벨인 99레벨 이상은 만들 수 없고, 99레벨은 삭제가 불가능한 등급입니다.</div>
	<div class="table_write">
		<table cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col width="7%" />
				<col width="10%" />
				<col width="42%" />
				<col width="15%" />
				<col width="13%" />
				<col width="13%" />
			</colgroup>
			<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col">레벨</th>
					<th scope="col">등급명</th>
					<th scope="col">인원수</th>
					<th scope="col">변경일</th>
					<th scope="col">관리</th>
				</tr>
			</head>
			<tbody id='divList'>
				<? foreach($member_grade_list as $key => $value) : ?>
				<tr>
					<td align="center"><?=count($member_grade_list) - $key?></td>
					<td align="center"><?=$value["level"]?></td>
					<td align="left"><?=$value["gradenm"]?></td>
					<td align="center"><?=intval($value["cnt"])?></td>
					<td align="center"><?=$value["moddt"]?></td>
					<td align="center">
						<? if(($this->_admin_member["level"] - 1) < $value['level']) : ?>
						<? else:?>
						<a href="member_auth_reg?level=<?=$value["level"]?>" class="btn_mini on">관리</a>
						<form name="form_<?=$value["level"]?>"action="member_auth_delete" method="post">
							<input type="hidden" name="level" value="<?=$value["level"]?>">
							<a href="javascript://" onclick="authDelete(document.form_<?=$value["level"]?>)" class="btn_mini">삭제</a>
						</form>
						<? endif; ?>
					</td>
				</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
</div>