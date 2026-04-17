<?php /* Template_ 2.2.8 2024/03/11 13:59:03 /home/bloomingterra/www/data/skin/respon_default/layout/main_notice.html 000001062 */ ?>
<?php if($TPL_VAR["notice_info"]['mainview']=='y'){?>
<ul class="board_ul">
<?php if(isset($TPL_VAR["notice_list"]['board_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["notice_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li class="board_li">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view" alt="<?php echo $TPL_V1["title"]?>"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>" alt="<?php echo $TPL_V1["title"]?>"><?php }?></a>
		<div class="txt">
			<strong><?php if($TPL_V1["preface"]){?><em><?php echo $TPL_V1["preface"]?></em><?php }?><?php echo $TPL_V1["title"]?></strong>
			<p><?php echo date("d.m.Y",strtotime($TPL_V1["regdt_date"]))?> </p>
		</div>
	</li>
<?php }}?>
<?php }else{?>
	<li>게시글이 없습니다.</li>
<?php }?>
</ul>
<?php }?>