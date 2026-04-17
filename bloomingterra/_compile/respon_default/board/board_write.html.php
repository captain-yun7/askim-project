<?php /* Template_ 2.2.8 2024/03/15 15:41:26 /home/bloomingterra/www/data/skin/respon_default/board/board_write.html 000000782 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<div class="sub_content">
	<div class="sub_board">
		<div class="board_write_wrap">
<?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='modify'||$TPL_VAR["board_info"]['mode']=='answer'){?>
		<?php echo include_('board_write','board/_form_board_write.html')?>

<?php }elseif($TPL_VAR["board_info"]['mode']=='answer_write'||$TPL_VAR["board_info"]['mode']=='answer_modify'){?>
		<?php echo include_('board_answer_write','board/_form_board_answer_write.html')?>

<?php }?>
		</div>
	</div><!-- .sub_cont -->
</div><!--content_sub-->
<?php $this->print_("footer",$TPL_SCP,1);?>