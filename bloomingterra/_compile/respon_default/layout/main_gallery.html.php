<?php /* Template_ 2.2.8 2024/03/11 13:59:02 /home/bloomingterra/www/data/skin/respon_default/layout/main_gallery.html 000001546 */ ?>
<?php if($TPL_VAR["gallery_info"]['mainview']=='y'){?>
<ul class="board_ul" data-aos="fade-up" data-aos-offset="100" data-aos-easing="ease-in-quart" data-aos-delay="100" data-aos-duration="800">
<?php if(isset($TPL_VAR["gallery_list"]['board_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["gallery_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li class="board_li">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view" alt="<?php echo $TPL_V1["title"]?>"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>" alt="<?php echo $TPL_V1["title"]?>"><?php }?></a>
		<div class="thumb">
			<img src="<?php echo _UPLOAD?>/board/<?php echo $TPL_V1["upload_path"]?>/<?php echo $TPL_V1["board_file"]["thumbnail"][ 0]['fname']?>" alt="<?php echo $TPL_V1["title"]?>" onerror="this.src='/data/skin/respon_default/images/common/noimg.gif'">
		</div>
		<div class="txt">
			<strong><?php if($TPL_V1["preface"]){?>[<?php echo $TPL_V1["preface"]?>] <?php }?><?php echo $TPL_V1["title"]?></strong>
			<p><?php echo strip_tags(htmlspecialchars_decode($TPL_V1["content"]))?></p>
			<p><?php echo strip_tags($TPL_VAR["gallery_extra"][$TPL_V1["no"]]['ex1'])?></p>
		</div>
	</li>
<?php }}?>
<?php }else{?>
	<li>게시글이 없습니다.</li>
<?php }?>
</ul>
<?php }?>