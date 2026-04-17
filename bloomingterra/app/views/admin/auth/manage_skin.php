<style>
.skins { width: 100%; }
.skins + .skins {margin-top:10px;}
</style>
<div id="contents">
	<form name="frm" method="post">
		<div class="main_tit">
			<h2>스킨 설정</h2>
			<div class="btn_right">
				<a href="javascript://" onclick="document.frm.submit();" class="btn point">저장</a>
			</div>
		</div>

		<div class="table_write manage_skin_table">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="7%">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
				</colgroup>
				<thead>
					<tr>
						<th scope="col"></th>
						<th scope="col">한국어</th>
						<th scope="col">영어</th>
						<th scope="col">중국어(간체)</th>
						<th scope="col">일어</th>
					</tr>
				</thead>
				<tbody id="divList">
					<th><span>PC</span><span>MOBILE</span></th>
				<?php
				$languages = ['kor', 'eng', 'chn', 'jpn'];
				foreach($languages as $value) {
					echo "<td><select name='".$value."_skin' class='skins'><option value=''>PC</option>";
					foreach($skins as $v) {
						$selected = $current[$value]['skin'] == $v ? " selected" : "";
						echo "<option value='".$v."'".$selected.">".$v."</option>";
					}
					echo "</select>";
					echo "<select name='".$value."_mobile_skin' class='skins'><option value=''>Mobile</option>";
					foreach($skins as $v) {
						$selected = $current[$value]['mobile_skin'] == $v ? " selected" : "";
						echo "<option value='".$v."'".$selected.">".$v."</option>";
					}
					echo "</select></td>";
				}
				?>
				</tbody>
			</table>
		</div>
	</form>
</div>