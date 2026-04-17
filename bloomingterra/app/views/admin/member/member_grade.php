<script>
	function gradeDelete(form) {
		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}

		form.submit();
	}
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>회원 등급관리</h2>
		<div class="btn_right">
			<a href="member_grade_reg" class="btn point new_plus">+ 신규 회원등급 생성</a>
		</div>
	</div>
	

	<div class="table_write">
		<table cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col width="7%" />
				<col width="9%" />
				<col width="*" />
				<col width="10%" />
				<col width="20%" />
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
						<a href="member_grade_reg?level=<?=$value["level"]?>" class="btn_mini on">관리</a>
						<form name="form_<?=$value["level"]?>"action="member_grade_delete" method="post">
							<input type="hidden" name="level" value="<?=$value["level"]?>">
							<a href="javascript://" onclick="gradeDelete(document.form_<?=$value["level"]?>)" class="btn_mini">삭제</a>
						</form>
					</td>
				</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
