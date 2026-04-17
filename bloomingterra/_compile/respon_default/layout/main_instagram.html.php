<?php /* Template_ 2.2.8 2024/03/11 13:59:03 /home/bloomingterra/www/data/skin/respon_default/layout/main_instagram.html 000000604 */ ?>
<?php if(count($TPL_VAR["instagram"]->data)> 0){?>
<h1><a href="<?php echo $TPL_VAR["instagram"]->url?>" target="_blank">Designart 인스타그램 피드</a></h1>
<ul>
<?php if(is_array($TPL_R1=$TPL_VAR["instagram"]->data)&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li style="display: inline-block;"><a href="<?php echo $TPL_V1->permalink?>" target="_blank"><img src="<?php echo $TPL_V1->media_url?>" style="width: 100px;"></a></li>
<?php }}?>
</ul>
<?php }?>