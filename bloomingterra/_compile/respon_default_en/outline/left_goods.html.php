<?php /* Template_ 2.2.8 2024/03/11 13:59:07 /home/bloomingterra/www/data/skin/respon_default_en/outline/left_goods.html 000001200 */ ?>
<div class="sub_menu">
    <!--leftmenu영역시작-->
	<dl>
		<dt>PRODUCT</dt>
<?php if(isset($TPL_VAR["category_list"]['top_category_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["category_list"]['top_category_list'])&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
			<dd class="menu0<?php echo $TPL_I1+ 1?> <?php if($TPL_VAR["category_info"]['category']==$TPL_V1["category"]||substr($TPL_VAR["category_info"]['category'], 0, 3)==substr($TPL_V1["category"], 0, 3)||substr($TPL_VAR["category_info"]['category'], 0, 3)==substr($TPL_V1["category"], 0, 3)){?>on<?php }?>">
				<a href="/goods/goods_list?cate=<?php echo $TPL_V1["category"]?>&display_type=<?php echo $TPL_VAR["display_type"]?>">
<?php if($TPL_V1["multi_category"]){?>
				<?php echo $TPL_V1["multi_category"]?>

<?php }else{?>
				<?php echo $TPL_V1["categorynm"]?>

<?php }?>
				</a>
			</dd>
<?php }}?>
<?php }else{?>
			<dd>There are no categories.</dd>
<?php }?>
	</dl>
    <!--leftmenu영역끝-->
<?php $this->print_("left_bnr",$TPL_SCP,1);?>

</div><!-- .sub_menu -->