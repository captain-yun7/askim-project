<script>

	function fieldSave(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}
		var isSubmit = true;

		if(isSubmit) {
			frm.submit();	
		}
	}

</script>
<div id="contents">
	<div class="main_tit">
		<h2>개발자모드</h2>
		<div class="btn_right">
			<a href="javascript://" onclick="fieldSave(document.frm);" class="btn point">저장</a>
		</div>
	</div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="12%">
					<col width="12%">
					<col width="*">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">항목</th>
						<th scope="col">선택</th>
						<th scope="col">설명</th>
					</tr>
				</head>
				<tbody id='divList'>
						<tr>
							<td align="center">개발자 모드</td>
							<td align="center"><input type="checkbox" name="debug_mode" id="debug_mode" <?=($debug_mode) ? 'checked' : '' ?> value='1' /><label for="debug_mode">사용</label></td>
							<td>개발자 모드를 사용하면 프론트 페이지에서 모든 변수값을 확인할 수 있습니다. 단, 납품시에는 반드시 개발자 모드를 비활성화 하십시오.</td>
						</tr>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
</div>
