<script type="text/javascript" src="/lib/admin/js/admin_board.js"></script>
<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script>
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on').find('ul li:nth-of-type(1)').addClass('active');
</script>
<script>
	var Common_Board = new common_board({
		code : "<?=$board_info["code"]?>",
		no : "<?=$this->input->get("no", true)?>"
	});

	$(function() {
		$("form[name='frm']").validate({
			rules : {
				answer_title : {required : true},
				answer_content : {editorRequired : {depends : function(){return !getSmartEditor("content")}}},
			}, messages : {
				answer_title : {required : "답변제목을 입력해주세요."},
				answer_content : {editorRequired : "답변내용을 입력해주세요."},
			}
		});

		attachSmartEditor("content", "board");
	});
</script>
<div id="contents">
	<div class="main_tit">
		<h2 class="board-name">게시글 관리 <em><?=$board_info["name"]?></em></h2>
		<div class="btn_right btn_num2">
			<a class="btn gray" href="board_list?code=<?=$board_info["code"]?>">목록</a>
			<a class="btn point" href="javascript://" onclick="Common_Board.board_write(document.frm);">저장</a>
		</div><!--btn_right-->
	</div>
	<?=form_open("", $form_attribute)?>
		<input type="hidden" name="write_userid" value="<?=$board_view["userid"]?>" />
		<input type="hidden" name="code" value="<?=$board_info["code"]?>" />
		<input type="hidden" name="mode" value="<?=$board_info["mode"]?>" />
		<input type="hidden" name="no" value="<?=$this->input->get("no", true)?>" />
		<input type="hidden" name="language" value="<?=$board_view["language"]?>">
		<input type="hidden" name="ref" value="<?=$ref?>">
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="12%">
					<col width="*">
				</colgroup>
				<tr>
					<th class="ta_left">답변 제목</th>
					<td><input type="text" name="answer_title" value="<?=$board_view["answer_title"]?>" /></td>
				</tr>
				<tr style="height:150px;">
					<th class="ta_left">답변 내용</th>
					<td><textarea id="content" name="answer_content" style="display:none;"><?=$board_view["answer_content"]?></textarea></td>
				</tr>
			</table>
		</div>
	<?=form_close()?>
</div><!-- //contents END -->