<?php /* Template_ 2.2.8 2026/02/19 16:57:50 /gcsd33_bloomingterra/www/data/skin/respon_default_en/layout/goods/list.html 000001446 */ ?>
<?php if(isset($TPL_VAR["goods_list"]['goods_list'])){?>
<!-- 리스트타입 -->
<ul class="gallery_list">
<?php if(is_array($TPL_R1=$TPL_VAR["goods_list"]['goods_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
	<li>
		<div class="cont">
			<a href="/goods/goods_view?no=<?php echo $TPL_V1["no"]?>&cate=<?php echo $TPL_VAR["req"]["cate"]?>" class="link"></a>
			<span class="arw"><img src="../data/skin/respon_default/images/sub/gallery_arw.svg" alt=""></span>
			<div class="thumb">
				<img src="/upload/goods/img2/<?php echo $TPL_V1["img2"]?>" onerror="this.src='/data/skin/respon_default_en/images/common/noimg.gif'" alt="<?php echo $TPL_V1["name"]?>">
			</div>
			<div class="info">
				<dl>
					<dt><?php echo $TPL_V1["name"]?></dt>
					<!-- <?php if($TPL_V1["ex1"]){?>
					<dd><?php echo $TPL_V1["ex1"]?></dd>
<?php }?>
				</dl>
				<span><?php echo date_format(date_create($TPL_V1["regdt_date"]),'20y.m.d')?></span> -->
			</div>
		</div>
	</li>
<?php }}?>
</ul>
<?php }else{?>
<p class="nodate"><?php if($TPL_VAR["category_info"]['category']){?>제품이 없습니다.<?php }else{?>검색된 결과가 없습니다.<?php }?></p>
<?php }?>
<script src="<?php echo $TPL_VAR["js"]?>/js/masonry.pkgd.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/gallery_list.js"></script>