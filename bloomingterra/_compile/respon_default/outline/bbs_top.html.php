<?php /* Template_ 2.2.8 2024/03/11 13:59:03 /home/bloomingterra/www/data/skin/respon_default/outline/bbs_top.html 000000575 */ ?>
<?php if(isset($TPL_VAR["board_nav"]['board_manage_list'])){?>
<ul id="sub_nav" class="submenu dn">
<?php if(is_array($TPL_R1=$TPL_VAR["board_nav"]['board_manage_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li><a href="/board/board_list?code=<?php echo $TPL_V1["code"]?>" <?php if($TPL_VAR["board_info"]['code']==$TPL_V1["code"]){?>class="on"<?php }?>><?php echo $TPL_V1["name"]?></a></li>
<?php }}?>
</ul><!--submenu-->
<?php }?>