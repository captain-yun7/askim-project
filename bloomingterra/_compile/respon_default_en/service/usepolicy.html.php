<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/service/usepolicy.html 000000830 */ ?>
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