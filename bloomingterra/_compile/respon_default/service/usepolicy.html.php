<?php /* Template_ 2.2.8 2024/03/21 10:15:57 /home/bloomingterra/www/data/skin/respon_default/service/usepolicy.html 000000825 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<div class="gallery_list_bg"></div>
<div class="sub_cont w_custom">	
	<div class="board_list">
		<div class="sub_agree">
			<h3><?php echo $TPL_VAR["terms"]['usePolicy']['title']?></h3>
			<div class="agree_box member_agree">
				<div class="agree_box_con"><?php echo nl2br($TPL_VAR["terms"]['usePolicy']['text'])?></div>
			</div>
			<h3><?php echo $TPL_VAR["terms"]['nonMember']['title']?></h3>
			<div class="agree_box member_agree">
				<div class="agree_box_con"><?php echo nl2br($TPL_VAR["terms"]['nonMember']['text'])?></div>
			</div>
		 </div>
	</div><!-- .sub_content -->
</div><!--sub_cont-->
<?php $this->print_("footer",$TPL_SCP,1);?>