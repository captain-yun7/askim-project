<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/layout/main_service.html 000001015 */ ?>
<?php if($TPL_VAR["service_info"]['mainview']=='y'){?>
<ul class="ibx">
<?php if(isset($TPL_VAR["service_list"]['board_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["service_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li>
		<a href="<?php echo $TPL_VAR["service_extra"][$TPL_V1["no"]]['ex1']?>"></a>
		<div class="thumb"><img src="<?php echo _UPLOAD?>/board/<?php echo $TPL_V1["upload_path"]?>/<?php echo $TPL_V1["board_file"]["thumbnail"][ 0]['fname']?>" alt="<?php echo $TPL_V1["title"]?>" onerror="this.src='/data/skin/images/common/noimg.gif'"></div>
		<div class="txt_box">
			<div class="ov_box"></div>
			<dl>
				<dt><?php echo $TPL_V1["title"]?></dt>
				<dd><?php echo strip_tags(htmlspecialchars_decode($TPL_V1["content"]))?></dd>
			</dl>
		</div>
	</li>
<?php }}?>
<?php }else{?>
	<li>There is no post</li>
<?php }?>
</ul>
<?php }?>