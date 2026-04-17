<?php /* Template_ 2.2.8 2024/03/20 11:40:32 /home/bloomingterra/www/data/skin/respon_default/layout/board/list_gallery_01.html 000004465 */ ?>
<?php if(isset($TPL_VAR["board_list"]['notice_list'])){?>
<?php if(count($TPL_VAR["board_list"]['notice_list'])> 0){?>
<ul class="gallery_notice">
<?php if(is_array($TPL_R1=$TPL_VAR["board_list"]['notice_list'])&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
	<li class="<?php if((($TPL_I1% 3)+ 1)=='3'){?>last<?php }?> ">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view" alt="<?php echo $TPL_V1["title"]?>"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>" alt="<?php echo $TPL_V1["title"]?>"><?php }?></a>
		<div class="thumb">
			<img src="<?php echo _UPLOAD?>/board/<?php echo $TPL_V1["upload_path"]?>/<?php echo $TPL_V1["board_file"]["thumbnail"][ 0]['fname']?>" alt="<?php echo $TPL_V1["title"]?>" onerror="this.src='/data/skin/respon_default/images/common/noimg.gif'">
		</div>
		<div class="txt_info">
			<b>공지</b>
			<div class="board_tit">
				<div class="board_ico">
<?php if($TPL_V1["display_read"]=='s'){?><img src="/data/skin/respon_default/images/common/icon_secret.gif" alt="비밀글"><?php }?>
<?php if(count($TPL_V1["board_file"]['file'])> 0){?><img src="/lib/images/icon_attach_file.png" alt="첨부파일"><?php }?>
				</div>
				<h3>
					<strong><?php if($TPL_V1["preface"]){?>[<?php echo $TPL_V1["preface"]?>]<?php }?> <?php echo mb_strimwidth(($TPL_V1["title"]), 0, 150,"...")?></strong>
<?php if($TPL_VAR["board_info"]['comment']=='y'){?>
<?php if($TPL_V1["comment"]>'0'){?>[<?php echo $TPL_V1["comment"]?>]<?php }?>
<?php }?>
				</h3>
			</div>
			<p><?php echo $TPL_V1["name"]?><span></span><?php echo $TPL_V1["regdt_date"]?></p>
<?php if($TPL_VAR["extra"][$TPL_V1["no"]]['ex1']){?>
			<p><?php echo $TPL_VAR["extra"][$TPL_V1["no"]]['ex1']?></p>
<?php }?>
		</div>
	</li>
<?php }}?>
</ul>
<?php }?>
<?php }?>

<?php if(isset($TPL_VAR["board_list"]['board_list'])){?>
<div class="masonry_gutter"></div>
<ul class="gallery_list">
<?php if(is_array($TPL_R1=$TPL_VAR["board_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li>
		<div class="cont">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view" alt="<?php echo $TPL_V1["title"]?>" class="link"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?><?php if($TPL_VAR["req"]["tag"]){?>&tag=<?php echo $TPL_VAR["req"]["tag"]?><?php }?><?php if($TPL_VAR["req"]["search"]){?>&search_type=<?php echo $TPL_VAR["req"]["search_type"]?>&search=<?php echo $TPL_VAR["req"]["search"]?><?php }?>" alt="<?php echo $TPL_V1["title"]?>" class="link"><?php }?></a>
			<span class="arw"><img src="../data/skin/respon_default/images/sub/gallery_arw.svg" alt=""></span>
			<div class="thumb">
				<img src="<?php echo _UPLOAD?>/board/<?php echo $TPL_V1["upload_path"]?>/<?php echo $TPL_V1["board_file"]["thumbnail"][ 0]['fname']?>" alt="<?php echo $TPL_V1["title"]?>" onerror="this.src='/data/skin/respon_default/images/common/noimg.gif'">
			</div>
			<div class="info">
				<dl>
					<dt><?php if($TPL_V1["preface"]){?>[<?php echo $TPL_V1["preface"]?>]<?php }?> <?php echo mb_strimwidth(($TPL_V1["title"]), 0, 70,"...")?></dt>
<?php if($TPL_VAR["extra"][$TPL_V1["no"]]['ex1']){?>
					<dd><?php echo $TPL_VAR["extra"][$TPL_V1["no"]]['ex1']?></dd>
<?php }?>
				</dl>
				<span><?php echo date_format(date_create($TPL_V1["regdt_date"]),'20y.m.d')?></span>
			</div>
		</div>
<?php if(count($TPL_VAR["tags"][$TPL_V1["no"]])> 0){?>
		<ul class="tag_list">
<?php if(is_array($TPL_R2=$TPL_VAR["tags"][$TPL_V1["no"]])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
			<li><a href="../board/board_list?code=<?php echo $TPL_V1["code"]?>&tag=<?php echo $TPL_V2?>"<?php if($TPL_VAR["req"]["tag"]===$TPL_V2){?> class="current-tag"<?php }?>>#<?php echo $TPL_V2?></a></li>
<?php }}?>
		</ul>
<?php }?>
	</li>
<?php }}?>
</ul><!--gallery_list-->
<?php }else{?>
<?php if(count($TPL_VAR["board_list"]['notice_list'])< 1){?>
<p class="nodate">게시글이 없습니다.</p>
<?php }?>
<?php }?>
<script src="<?php echo $TPL_VAR["js"]?>/js/masonry.pkgd.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/gallery_list.js"></script>