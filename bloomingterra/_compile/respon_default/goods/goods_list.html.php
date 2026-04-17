<?php /* Template_ 2.2.8 2024/04/04 15:43:59 /home/bloomingterra/www/data/skin/respon_default/goods/goods_list.html 000005601 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<!-- <script type="text/javascript" src="/lib/js/common_goods.js"></script> -->
<script type="text/javascript" src="<?php echo $TPL_VAR["js"]?>/js/common_goods.js"></script>
<script type="text/javascript">
	var Common_Goods = new common_goods({
		is_login : "<?php echo defined('_IS_LOGIN')?>"
	});
</script>
<div class="gallery_list_bg"></div>
<div class="sub_content">
	<div class="goods_wrap">
		<div class="flex_box">
			<div class="lnb">
				<ul class="lnb_list">
<?php if(is_array($TPL_R1=$TPL_VAR["categories"]["top_category_list"])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
<?php if($TPL_V1["yn_use"]!=="n"){?>
					<li<?php if(substr($TPL_VAR["req"]["cate"], 0, 3)===$TPL_V1["category"]){?> class="on"<?php }?>>
						<a href="?cate=<?php echo $TPL_V1["category"]?>"><?php echo $TPL_V1["categorynm"]?></a>
<?php if(count($TPL_VAR["sub_categories"][$TPL_V1["category"]])> 0){?>
						<ul class="dep02">
<?php if(is_array($TPL_R2=$TPL_VAR["sub_categories"][$TPL_V1["category"]])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
<?php if($TPL_V2["yn_use"]!=="n"){?>
							<li<?php if($TPL_VAR["req"]["cate"]===$TPL_V2["category"]){?> class="on"<?php }?>><a href="?cate=<?php echo $TPL_V2["category"]?>"><?php echo $TPL_V2["categorynm"]?></a></li>
<?php }?>
<?php }}?>
						</ul>
<?php }?>
					</li>
<?php }?>
<?php }}?>
				</ul>
				<ul class="lnb_list dn">
					<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 3)=='001'){?>on<?php }?>">
						<a href="../goods/goods_list?cate=001">성수</a>
						<ul class="dep02">
							<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 6)=='001001'){?>on<?php }?>"><a href="../goods/goods_list?cate=001001">도어 투성수</a></li>
							<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 6)=='001002'){?>on<?php }?>"><a href="../goods/goods_list?cate=001002">오색칠</a></li>
							<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 6)=='001003'){?>on<?php }?>"><a href="../goods/goods_list?cate=001003">2차 카테고리</a></li>
							<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 6)=='001004'){?>on<?php }?>"><a href="../goods/goods_list?cate=001004">2차 카테고리</a></li>
						</ul>
					</li>
					<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 3)=='002'){?>on<?php }?>">
						<a href="../goods/goods_list?cate=002">안국</a>
						<ul class="dep02">
							<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 6)=='002001'){?>on<?php }?>"><a href="../goods/goods_list?cate=002001">도어 투안국</a></li>
						</ul>
					</li>
					<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 3)=='003'){?>on<?php }?>">
						<a href="../goods/goods_list?cate=003">이태원</a>
					</li>
					<li class="<?php if(substr($TPL_VAR["category_info"]['category'], 0, 3)=='004'){?>on<?php }?>">
						<a href="../goods/goods_list?cate=004">신당</a>
					</li>
				</ul>
			</div>
<?php if(isset($TPL_VAR["category_list"]['same_category_list'])||isset($TPL_VAR["category_list"]['low_category_list'])){?>
			<div class="sub_cate_wrap dn">
				<ul class="sub_cate clear lower-category">
<?php if(is_array($TPL_R1=$TPL_VAR["category_list"]['low_category_list'])&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
					<li class="menu0<?php echo $TPL_I1+ 1?> <?php if($TPL_VAR["category_info"]['category']==$TPL_V1["category"]){?>on<?php }?>">
						<a href="/goods/goods_list?cate=<?php echo $TPL_V1["category"]?>&display_type=<?php echo $TPL_VAR["display_type"]?>"><?php if($TPL_V1["multi_category"]){?><?php echo $TPL_V1["multi_category"]?><?php }else{?><?php echo $TPL_V1["categorynm"]?><?php }?></a>
					</li>
<?php }}else{?>
<?php if(is_array($TPL_R1=$TPL_VAR["category_list"]['same_category_list'])&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
					<li class="menu0<?php echo $TPL_I1+ 1?> <?php if($TPL_VAR["category_info"]['category']==$TPL_V1["category"]){?>on<?php }?>">
						<a href="/goods/goods_list?cate=<?php echo $TPL_V1["category"]?>&display_type=<?php echo $TPL_VAR["display_type"]?>"><?php if($TPL_V1["multi_category"]){?><?php echo $TPL_V1["multi_category"]?><?php }else{?><?php echo $TPL_V1["categorynm"]?><?php }?></a>
					</li>
<?php }}?>
<?php }?>
				</ul>
			</div>
<?php }?>
			<div class="goods_cont">
				<div class="sub_list">
					<div class="list_array dn">
						<div class="list_count">총 <span><?php echo $TPL_VAR["goods_list"]['total_rows']?>개</span>의 상품이 있습니다.</div>
						<ul>
							<li class="typeA <?php if($TPL_VAR["display_type"]=='gallery'){?>on<?php }?>" onclick="Common_Goods.goods_list_type('gallery');">갤러리형</li>
							<li class="typeB <?php if(!$TPL_VAR["display_type"]||$TPL_VAR["display_type"]=='list'){?>on<?php }?>" onclick="Common_Goods.goods_list_type('list');">리스트형</li>
						</ul>
						<?php echo form_open($TPL_VAR["form_attribute"]['action'],$TPL_VAR["form_attribute"]['attribute'],$TPL_VAR["form_attribute"]['hidden'])?><?php echo form_close()?>

					</div><!-- .list_array -->
					<div class="list">
<?php $this->print_("goods_display",$TPL_SCP,1);?>

					</div><!-- .list -->
				</div><!--sub_list-->
				<?php echo $TPL_VAR["goods_list"]["pagination"]?>

			</div>
		</div>
	</div>
</div><!-- .sub_content -->
<?php $this->print_("footer",$TPL_SCP,1);?>