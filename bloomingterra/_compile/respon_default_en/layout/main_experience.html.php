<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/layout/main_experience.html 000001096 */ ?>
<?php if($TPL_VAR["experience_info"]['mainview']=='y'){?>
<ul class="list">
<?php if(isset($TPL_VAR["experience_list"]['board_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["experience_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li class="">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view" alt="<?php echo $TPL_V1["title"]?>"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>" alt="<?php echo $TPL_V1["title"]?>"><?php }?>
		<div class="thumb">
			<img src="<?php echo _UPLOAD?>/board/<?php echo $TPL_V1["upload_path"]?>/<?php echo $TPL_V1["board_file"]["thumbnail"][ 0]['fname']?>" alt="<?php echo $TPL_V1["title"]?>" onerror="this.src='/data/skin/images/common/noimg.gif'">
		</div>
		</a>
	</li>
<?php }}?>
<?php }else{?>
	<li>There is no post</li>
<?php }?>
</ul>
<?php }?>