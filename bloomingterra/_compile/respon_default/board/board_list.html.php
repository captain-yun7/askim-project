<?php /* Template_ 2.2.8 2024/03/19 18:14:00 /home/bloomingterra/www/data/skin/respon_default/board/board_list.html 000002644 */ 
$TPL_preface_1=empty($TPL_VAR["preface"])||!is_array($TPL_VAR["preface"])?0:count($TPL_VAR["preface"]);?>
<?php $this->print_("header",$TPL_SCP,1);?>

<style>
.current-tag { background: #f00 !important; }
</style>
<script type="text/javascript">
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				search : {required : true}
			}, messages : {
				search : {required : "검색어를 입력해주세요."}
			}
		});
	});
</script>
<div class="gallery_list_bg"></div>
<div class="sub_cont w_custom">
	<div class="sub_board board_list">
		<div class="clear">
			<?php echo form_open('',$TPL_VAR["form_attribute"])?>

			<div class="search_wrap">
				<fieldset>
				<legend>게시글 검색</legend>
					<input type="hidden" name="code" value="<?php echo $TPL_VAR["board_info"]['code']?>" />
					<div class="board_search_sel dn">
<?php if(count($TPL_VAR["preface"])> 0){?>
						<select name="category">
							<option value="">말머리</option>
<?php if($TPL_preface_1){foreach($TPL_VAR["preface"] as $TPL_V1){?>
							<option value="<?php echo $TPL_V1?>"<?php echo set_select('category',$TPL_V1)?>><?php echo $TPL_V1?></option>
<?php }}?>
						</select>
<?php }?>
						<select name="search_type" id="search_type" title="검색할 항목을 선택하세요." class="select">
							<option value="title" selected="selected">제목</option>
							<option value="content" <?php echo set_select('search_type','content')?>>내용</option>
							<option value="name" <?php echo set_select('search_type','name')?>>작성자</option>
							<option value="userid" <?php echo set_select('search_type','userid')?>>아이디</option>
						</select>
					</div><!--/ board_search_sel -->
					<label for="search" class="dn">검색어를 입력하세요.</label>
					<input type="text" name="search" id="search" value="<?php echo set_value('search')?>" class="input_text" placeholder="Search..."/>
					<input type="submit" class="search_btn" value="검색" />
				</fieldset>
			</div><!--board_search-->
			<?php echo form_close()?>

		</div>
<?php $this->print_("board_display",$TPL_SCP,1);?>

		<div class="view_btn clear">
			<?php echo $TPL_VAR["board_list"]['pagination']?>

<?php if($TPL_VAR["board_info"]['is_write']){?>
			<div class="btn_wrap ta_right"><a href="/board/board_write?code=<?php echo $TPL_VAR["board_info"]['code']?>" class="btn btn_point">글쓰기</a></div>
<?php }?>
		</div>
	</div><!-- .sub_board -->
</div><!-- .sub_content -->

<?php $this->print_("footer",$TPL_SCP,1);?>