<?php /* Template_ 2.2.8 2026/02/19 16:57:43 /gcsd33_bloomingterra/www/data/skin/respon_default_en/board/board_list.html 000002113 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<script type="text/javascript">
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				search : {required : true}
			}, messages : {
				search : {required : "Please enter the keyword."}
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
				<legend>Search</legend>
					<input type="hidden" name="code" value="<?php echo $TPL_VAR["board_info"]['code']?>" />
					<div class="board_search_sel dn">
						<select name="search_type" id="search_type" title="Please select an item to search." class="select">
							<option value="title" selected="selected">Title</option>
							<option value="content" <?php echo set_select('search_type','content')?>>Contents</option>
							<option value="name" <?php echo set_select('search_type','name')?>>Writer</option>
							<option value="userid" <?php echo set_select('search_type','userid')?>>ID</option>
						</select>
					</div><!--/ board_search_sel -->
					<label for="search" class="dn">Please enter the keyword.</label>
					<input type="text" name="search" id="search" value="<?php echo set_value('search')?>" class="input_text" placeholder="Search..."/>
					<input type="submit" class="search_btn" value="Search" />
				</fieldset>
			</div><!--board_search-->
			<?php echo form_close()?>

		</div>
<?php $this->print_("board_display",$TPL_SCP,1);?>

		<div class="view_btn clear">
			<?php echo $TPL_VAR["board_list"]['pagination']?>

<?php if($TPL_VAR["board_info"]['is_write']){?>
			<div class="btn_wrap ta_right"><a href="/board/board_write?code=<?php echo $TPL_VAR["board_info"]['code']?>" class="btn btn_point">Compose a Post</a></div>
<?php }?>
		</div>
	</div><!-- .sub_board -->
</div><!-- .sub_content -->
<?php $this->print_("footer",$TPL_SCP,1);?>