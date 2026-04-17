<script>
	function set_display_yn(no, flag) {
		if(flag == "n") {
			flag = "y"
		} else {
			flag = "n"
		}
		var set_data = {
			"no" : no,
			"open" : flag
		};
		$.ajax({
			url : "/admin/popup/popup_display",
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

	function set_sort(no, sort) {

		var set_data = {
			"no" : no,
			"sort" : sort
		};
		$.ajax({
			url : "/admin/popup/popup_sort",
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

	function popup_delete(form) {
		if(!$(":checkbox[name='no[]']").is(":checked")) {
			alert("삭제할 항목을 선택해주세요.");
			return false;
		}

		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}

		form.submit();
	}
</script>
<div id="contents">
	<div class="main_tit">
		<h2>팝업 리스트</h2>
		<div class="btn_right btn_num2">
			<a href="javascript://" onclick="popup_delete(document.frm);" class="btn gray sel_minus">선택 삭제</a>
			<a href="popup_reg" class="btn point new_plus">+ 팝업 등록</a>
		</div><!--btn_right-->
	</div>
	<div class="table_write_info">* 팝업은 메인페이지에서만 노출되며, PC/모바일 동시 노출됩니다.</div>
	<div class="main_search">
			<fieldset>
				<?=form_open("", array("method"=>"GET"));?>
					<?php if($this->_site_language["multilingual"]) : ?>
						<select name="language" onchange="this.form.submit();">
								<option value="">전체</option>
							<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
								<option value="<?=$key?>" <?=$this->input->get("language", true) == $key ? "selected" : ""?>><?=$value?></option>
							<?php endforeach ?>
						</select>
					<?php endif ?>
					<select name="search_type">
						<option value="title">제목</option>
					</select>
					<input type="text" class="searchBox" name="search">
					<button>검색</button>
				<?=form_close()?>
			</fieldset>
		</div><!--main_search-->
	<div class="table_list">
		<?=form_open("/admin/popup/popup_delete", array("name" => "frm"));?>
			<input type="hidden" name="return_url" value="/<?=urlencode(current_full_url())?>" />
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableA">
				<colgroup>
					<col width="3%" />
					<col width="5%" />
					<col />
					<col width="25%" />
					<col width="7%" />
					<!-- <col width="13%" /> -->
					<col width="10%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" onchange="checkToggle(this, 'no[]')"></th>
						<th scope="col">번호</th>
						<th scope="col">제목</th>
						<th scope="col">노출기간</th>
						<th scope="col">노출유무</th>
						<!-- <th scope="col">순서</th> -->
						<th scope="col">수정</th>
					</tr>
				</thead>
				<tbody id='tbody'>
					<? if(isset($popup_list)) : ?>
						<? foreach($popup_list as $key => $value) : ?>
						<tr<?=strtotime($value['edate']) < time() ? " class='over'" : ""?>>
							<td><input type="checkbox" name="no[]" value="<?=$value["no"]?>"></td>
							<td><?=$total_rows - $key - $offset?></td>
							<td align="left"><?=$value["title"]?></td>
							<td class="td_date"><?=date("Y-m-d", strtotime($value["sdate"]));?>~<?=date("Y-m-d", strtotime($value["edate"]));?></td>
							<td>
								<select onchange="set_display_yn('<?=$value["no"]?>', '<?=$value["open"]?>' )">
									<option value="" <?=$value["open"] == "y" ? "selected" : ""?>>노출</option>
									<option value="" <?=$value["open"] != "y" ? "selected" : ""?>>노출안함</option>
								</select>
							</td>
							<!-- <td>
								<? if($total_rows > 1) : ?>
									<? if($key == "0") : ?>
										<a href="javascript://" onclick="set_sort('<?=$value["no"]?>', '<?=$value["sort"] - 1?>');">↓</a>
									<? elseif(($total_rows) == $key + $offset + 1) : ?>
										<a href="javascript://" onclick="set_sort('<?=$value["no"]?>', '<?=$value["sort"] + 1?>');">↑</a>
									<? else : ?>
										<a href="javascript://" onclick="set_sort('<?=$value["no"]?>', '<?=$value["sort"] + 1?>');">↑</a>
										<a href="javascript://" onclick="set_sort('<?=$value["no"]?>', '<?=$value["sort"] - 1?>');">↓</a>
									<? endif ?>
								<? endif ?>
							</td> -->
							<td><a href="popup_reg?no=<?=$value["no"]?>&per_page<?=$this->input->get("per_page", true)?>&language=<?=$this->input->get("language", true)?>&search_type=<?=$this->input->get("search_type", true)?>&search=<?=urlencode($this->input->get("search", true))?>" class="btn_mini">관리</a></td>
						</tr>
						<? endforeach ?>
					<? else : ?>
						<tr>
							<td colspan="6">팝업이 없습니다.</td>
						</tr>
					<? endif ?>
				</tbody>
			</table>
		<?=form_close()?>
	</div><!--table_list-->
	<div class="btn_paging">
		<div class="paging"><?=$pagination?></div><!--paging-->
	</div><!--btn_paging-->
</div><!-- // contents -->