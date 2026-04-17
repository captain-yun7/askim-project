<script>
	function getList(page_name, per_page) {
		$.ajax({
			url:'./main/get_list',
			type:'post',
			data: '&page_name=' + page_name + '&per_page=' + per_page,
			dataType:'json',
			success:function(data) {
				switch(page_name) {
					case 'member':
						var addHtml = "";
						if(data['member_list']) {
							for(var i = 0; i < data['member_list'].length; i++) {
								addHtml += '	 <tr>';
								addHtml += '		<td>' + data['member_list'][i]['userid'] + '</td>';
								addHtml += '		<td>' + data['member_list'][i]['name'] + '</td>';
								addHtml += '		<td>' + data['member_list'][i]['mobile'] + '</td>';
								addHtml += '		<td>' + data['member_list'][i]['regdt'] + '</td>';
								addHtml += '		<td>' + data['member_list'][i]['last_login'] + '</td>';
								addHtml += '		<td><a href="./member/member_reg?userid=' + data['member_list'][i]['userid'] + '" class="btn_mini">보기</a></td>';
								addHtml += '	 </tr>'
							}
							$('#member').html(addHtml);

							var paging_num = parseInt(((per_page-1)/10));
							addHtml = '';

							if(paging_num > 0) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'member\', \'' + (String)(paging_num * 10) + '\');"><</a></span>';
							}

							for(var i = 10 * paging_num + 1; i <= 10 * paging_num + 10; i++) {
								if(i <= data['max_page']) {
									if(i == per_page) {
										addHtml += '<span><a class="page_num on" href="javascript://" onclick="getList(\'member\', \'' + i + '\');">' + i + '</a></span>';
									} else {
										addHtml += '<span><a class="page_num"href="javascript://" onclick="getList(\'member\', \'' + i + '\');">' + i + '</a></span>';
									}
								}
							}

							if(paging_num < parseInt((parseInt(data['max_page'])-1)/10)) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'member\', \'' + (String)((paging_num+1) * 10 + 1) + '\');">></span>';
							}
							$('#paging_member').html(addHtml);

						} else {
							addHtml = "<tr><td colspan='6'>회원이 없습니다.</td></tr>";
							$('#member').html(addHtml);
						}
						break;
					case 'popup':
						var addHtml = "";
						if(data['popup_list']) {
							for(var i = 0; i < data['popup_list'].length; i++) {

								var sdate = moment(data['popup_list'][i]['sdate']).isValid() ? moment(data['popup_list'][i]['sdate']).format("YYYY-MM-DD") : "미지정";
								var edate = moment(data['popup_list'][i]['edate']).isValid() ? moment(data['popup_list'][i]['edate']).format("YYYY-MM-DD") : "미지정";

								addHtml += '<tr>';
								addHtml += '	<td>' + data['popup_list'][i]['no'] + '</td>';
								addHtml += '	<td>' + data['popup_list'][i]['title'] + '</td>';
								addHtml += '	<td>' + sdate + ' ~ ' + edate + '</td>';
								//addHtml += '	<td><input type="checkbox" ' + (data['popup_list'][i]['open'] == 'y' ? 'checked' : '') + ' disabled /></td>';
								addHtml += '	<td><a href="./popup/popup_reg?no=' + data['popup_list'][i]['no'] + '" class="btn_mini">보기</a></td>';
								addHtml += '</tr>'
							}
							$('#popup').html(addHtml);

							var paging_num = parseInt(((per_page-1)/10));
							addHtml = '';

							if(paging_num > 0) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'popup\', \'' + (String)(paging_num * 10) + '\');"><</span>';
							}

							for(var i = 10 * paging_num + 1; i <= 10 * paging_num + 10; i++) {
								if(i <= data['max_page']) {
									if(i == per_page) {
										addHtml += '<span><a class="page_num on" href="javascript://" onclick="getList(\'popup\', \'' + i + '\');">' + i + '</a></span>';
									} else {
										addHtml += '<span><a class="page_num" href="javascript://" onclick="getList(\'popup\', \'' + i + '\');">' + i + '</a></span>';
									}
								}
							}

							if(paging_num < parseInt((parseInt(data['max_page'])-1)/10)) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'popup\', \'' + (String)((paging_num+1) * 10 + 1) + '\');">></span>';
							}

							$('#paging_popup').html(addHtml);
						} else {
							addHtml = "<tr><td colspan='5'>팝업이 없습니다.</td></tr>";
							$('#popup').html(addHtml);
						}
						break;
					case 'board':
						var addHtml = "";
						if(data['board_list']) {
							for(var i = 0; i < data['board_list'].length; i++) {
								addHtml += '<tr>';
								addHtml += '	<td>' + data['board_list'][i]['code'] + '</td>';
								addHtml += '	<td>' + data['board_list'][i]['title'] + '</td>';
								addHtml += '	<td>' + data['board_list'][i]['name'] + '</td>';
								addHtml += '	<td>' + data['board_list'][i]['regdt'] + '</td>';
								addHtml += '</tr>'
							}
							$('#board').html(addHtml);

							var paging_num = parseInt(((per_page-1)/10));
							addHtml = '';

							if(paging_num > 0) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'board\', \'' + (String)(paging_num * 10) + '\');"><</span>';
							}

							for(var i = 10 * paging_num + 1; i <= 10 * paging_num + 10; i++) {
								if(i <= data['max_page']) {
									if(i == per_page) {
										addHtml += '<span><a class="page_num on" href="javascript://" onclick="getList(\'board\', \'' + i + '\');">' + i + '</a></span>';
									} else {
										addHtml += '<span><a class="page_num" href="javascript://" onclick="getList(\'board\', \'' + i + '\');">' + i + '</a></span>';
									}
								}
							}

							if(paging_num < parseInt((parseInt(data['max_page'])-1)/10)) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'board\', \'' + (String)((paging_num+1) * 10 + 1) + '\');">></span>';
							}
							$('#paging_board').html(addHtml);
						} else {
							addHtml = "<tr><td colspan='4'>게시글이 없습니다.</td></tr>";
							$('#board').html(addHtml);
						}
						break;
					case 'goods':
						var addHtml = "";

						if(data['goods_list']) {
							for(var i = 0; i < data['goods_list'].length; i++) {
								addHtml += '<tr>';
								addHtml += '	<td>' + data['goods_list'][i]['no'] + '</td>';
								addHtml += '	<td>' + data['goods_list'][i]['name'] + '</td>';
								addHtml += '	<td>' + data['goods_list'][i]['regdt'] + '</td>';
								addHtml += '	<td>' + data['goods_list'][i]['moddt'] + '</td>';
								addHtml += '	<td><a href="./goods/goods_reg?no=' + data['goods_list'][i]['no'] + '" class="btn_mini">보기</a></td>';
								addHtml += '</tr>'
							}
							$('#goods').html(addHtml);
							var paging_num = parseInt(((per_page-1)/10));
							addHtml = '';

							if(paging_num > 0) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'goods\', \'' + (String)(paging_num * 10) + '\');"><</span>';
							}

							for(var i = 10 * paging_num + 1; i <= 10 * paging_num + 10; i++) {
								if(i <= data['max_page']) {
									if(i == per_page) {
										addHtml += '<span><a class="page_num on" href="javascript://" onclick="getList(\'goods\', \'' + i + '\');">' + i + '</a></span>';
									} else {
										addHtml += '<span><a class="page_num" href="javascript://" onclick="getList(\'goods\', \'' + i + '\');">' + i + '</a></span>';
									}
								}
							}

							if(paging_num < parseInt((parseInt(data['max_page'])-1)/10)) {
								addHtml += '<span><a href="javascript://" onclick="getList(\'goods\', \'' + (String)((paging_num+1) * 10 + 1) + '\');">></span>';
							}
							$('#paging_goods').html(addHtml);
						} else {
							addHtml = "<tr><td colspan='5'>상품이 없습니다.</td></tr>";
							$('#goods').html(addHtml);
						}
						break;

				}

			},
			error:function(jqXHR, textStatus, errorThrown){
            alert(textStatus + ' : ' + errorThrown);
			}
		})
		//페이징 숫자 리로드
		//1인지 검사
		//마지막 페이지 검사
		//에이작스 구현
	}
	<?php if(isset($main_notice_auth)) : ?>
		<?php if($main_notice_auth['member']) : ?>
			getList('member', 1);
		<?php endif?>

		<?php if($main_notice_auth['board']) : ?>
			getList('board', 1);
		<?php endif?>

		<?php if($main_notice_auth['goods']) : ?>
			getList('goods', 1);
		<?php endif?>

		<?php if($main_notice_auth['popup']) : ?>
			getList('popup', 1);
		<?php endif?>
	<?php endif?>
</script>
<style>
#leftmenu {display:none;}
#container {padding-left:0;}
</style>
<div id="contents" class="main_index">
	<div class="main_tit dn">
		<h2>메인관리</h2>
		<div class="blind main_search">
			<fieldset>
			<form onsubmit="return searchModule(this);">
				<select name="searchType">
					<option value="title">제목</option>
				</select>
				<input type="text" class="searchBox" name="searchContent">
				<button><img src="/cms/images/new/btn_search.gif" alt="검색" style="cursor:hand"></button>
			</form>
			</fieldset>
		</div><!--main_search-->
	</div>
	<div class="clear">
		<?php if(isset($main_notice_auth)) : ?>
			<?php if($main_notice_auth['member']) : ?>
				<dl class="main_box box01 fl">
					<dt>신규 회원 현황<a href="/admin/member/member_list" class="more_go">더보기</a></dt>
					<dd>
						<table cellpadding="0" cellspacing="0" border="0">
							<colgroup>
								<col width="14%">
								<col width="12%">
								<col width="18%">
								<col width="23%">
								<col width="23%">
								<col >
							</colgroup>
							<thead>
								<tr>
									<th>아이디</th>
									<th>이름</th>
									<th>연락처</th>
									<th>가입일</th>
									<th>최종접속일</th>
									<th>보기</th>
								</tr>
							</thead>
							<tbody id="member"></tbody>
						</table>
						<div class="paging" id="paging_member"></div>
					</dd>
				</dl>
			<?php endif?>
			<?php if($main_notice_auth['board']) : ?>
				<dl class="main_box box02 fl">
					<dt>신규 게시글 현황<a href="/admin/board/board_list" class="more_go">더보기</a></dt>
					<dd>
						<table cellpadding="0" cellspacing="0" border="0">
							<colgroup>
								<col width="18%">
								<col >
								<col width="14%">
								<col width="23%">
							</colgroup>
							<thead>
								<tr>
									<th>게시판명</th>
									<th>제목</th>
									<th>작성자</th>
									<th>등록일</th>
								</tr>
							</thead>
							<tbody id="board">

							</tbody>
						</table>
						<div class="paging" id="paging_board"></div>
					</dd>
				</dl>
			<?php endif?>
			<?php if($main_notice_auth['goods']) : ?>
				<dl class="main_box box03 fl">
					<dt>신규 상품등록 현황<a href="/admin/goods/goods_list" class="more_go">더보기</a></dt>
					<dd>
						<table cellpadding="0" cellspacing="0" border="0">
							<colgroup>
								<col width="10%">
								<col width="39%">
								<col width="21%">
								<col width="21%">
								<col >
							</colgroup>
							<thead>
								<tr>
									<th>상품번호</th>
									<th>상품명</th>
									<th>등록일</th>
									<th>수정일</th>
									<th>보기</th>
								</tr>
							</thead>
							<tbody id="goods">

							</tbody>
						</table>
						<div class="paging" id="paging_goods">
						</div>
					</dd>
				</dl>
			<?php endif?>
			<?php if($main_notice_auth['popup']) : ?>
				<dl class="main_box box04 fl">
					<dt>팝업 현황<a href="/admin/popup/popup_list" class="more_go">더보기</a></dt>
					<dd>
						<table cellpadding="0" cellspacing="0" border="0">
							<colgroup>
								<col width="9%">
								<col width="44%">
								<col width="24%">
								<col width="14%">
								<!-- <col > -->
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>제목</th>
									<th>기간</th>
									<!-- <th>공개여부</th> -->
									<th>보기</th>
								</tr>
							</thead>
							<tbody id="popup"></tbody>
						</table>
						<div class="paging" id="paging_popup"></div>
					</dd>
				</dl>
			<?php endif?>
		<?php endif?>
	</div>
</div><!-- // contents -->