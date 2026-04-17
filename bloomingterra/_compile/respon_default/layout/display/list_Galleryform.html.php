<?php /* Template_ 2.2.8 2024/03/11 13:59:34 /home/bloomingterra/www/data/skin/respon_default/layout/display/list_Galleryform.html 000001137 */ ?>
<?php if(isset($TPL_VAR["display_main_data"]['display_main_list'])){?>
<!-- 리스트타입 -->
진열 명 : <?php echo $TPL_VAR["display_main_data"]["display_main"]["theme_name"]?><br/>
진열 설명 : <?php echo $TPL_VAR["display_main_data"]["display_main"]["theme_description"]?>

<div class="list_cont list_Galleryform">
	<ul>
<?php if(is_array($TPL_R1=$TPL_VAR["display_main_data"]['display_main_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
		<li>
			<a href="/goods/goods_view?no=<?php echo $TPL_V1["no"]?>">
				<div class="thumb"><img src="/upload/goods/img2/<?php echo $TPL_V1["img2"]?>" onerror="this.src='/data/skin/respon_default/images/common/noimg.gif'" alt="<?php echo $TPL_V1["name"]?>"></div>
				<div class="info">
					<p class="name"><?php echo $TPL_V1["name"]?></em>
				</div>
			</a>
		</li>
<?php }}?>
	</ul>
</div><!-- .list_Galleryform -->
<?php }else{?>
<div class="list_no"><div class="no_data">검색된 결과가 없습니다.</div></div>
<?php }?>