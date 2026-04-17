<?php /* Template_ 2.2.8 2026/02/19 16:57:43 /gcsd33_bloomingterra/www/data/skin/respon_default_en/goods/goods_view.html 000007891 */ 
$TPL_extraFieldData_1=empty($TPL_VAR["extraFieldData"])||!is_array($TPL_VAR["extraFieldData"])?0:count($TPL_VAR["extraFieldData"]);?>
<?php $this->print_("header",$TPL_SCP,1);?>

<div class="sub_content">
	<div class="sub_view">
		<div class="view_visual">
			<div class="view_thumb">
<?php if(is_array($TPL_R1=$TPL_VAR["goods_view"]['goods_view']['detail_img'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
					<img src="<?php echo _UPLOAD?>/goods/detail_img/<?php echo $TPL_V1?>" onerror="this.src='/data/skin/respon_default_en/images/common/noimg.gif'">
<?php }}?>
			</div>
			<h3 class="subject"><?php echo $TPL_VAR["goods_view"]['goods_view']['name']?></h3>
			<div class="info_wrap">
				<dl class="info desc">
					<dt>Client</dt>
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
<?php if($TPL_V1["name"]=='Client'){?>
								<dd><?php echo $TPL_V1["value"]?></dd>
<?php }?>
<?php }}?>
<?php }?>
					
				</dl>
				<dl class="info desc">
					<dt>Category</dt>
<?php if(isset($TPL_VAR["category_list"]['top_category_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["category_list"]['top_category_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
<?php if($TPL_VAR["category_info"]['category']==$TPL_V1["category"]||substr($TPL_VAR["category_info"]['category'], 0, 3)==substr($TPL_V1["category"], 0, 3)||substr($TPL_VAR["category_info"]['category'], 0, 3)==substr($TPL_V1["category"], 0, 3)){?>
					<dd><?php echo $TPL_V1["categorynm"]?></dd>
<?php }?>
<?php }}?>
<?php }?>
				</dl>
				<dl class="info desc">
					<dt>Date</dt>
					<dd><?php echo date('Y.m.d',strtotime($TPL_VAR["goods_view"]["goods_view"]["regdt"]))?></dd>
				</dl>
			</div>
			<a href="" class="share_btn"><img src="/data/skin/respon_default_en/images/sub/share_btn.png" alt="공유버튼"></a>
		</div>
<?php if($TPL_VAR["CI"]->input->ip_address()==="210.121.177.87"){?>
<?php }?>
<?php if($TPL_VAR["goods_view"]['goods_view']['info']){?>
		<div class="view_text">
			<div class="cont">
				<?php echo htmlspecialchars_decode($TPL_VAR["goods_view"]['goods_view']['info'])?>

			</div>
		</div>
<?php }?>
		<div class="view_add">
			<div class="prodSwiper">
				<ul class="swiper-wrapper">
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
<?php if($TPL_V1["name"]=='슬라이드 이미지 01'||$TPL_V1["name"]=='슬라이드 이미지 02'||$TPL_V1["name"]=='슬라이드 이미지 03'){?>
								<li class="swiper-slide"><?php echo $TPL_V1["value"]?></li>
<?php }?>
<?php }}?>
<?php }?>
				</ul>
				<div class="swiper-pagination"></div>
			</div>
<?php if($TPL_VAR["goods_view"]['goods_view']['ex4']&&$TPL_VAR["goods_view"]['goods_view']['ex5']&&$TPL_VAR["goods_view"]['goods_view']['ex7']){?>
			<div class="youtube">
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
<?php if($TPL_V1["name"]=='유튜브 썸네일'){?>
				<div class="youtube_thumb"><?php echo $TPL_V1["value"]?></div>
<?php }?>
<?php }}?>
<?php }?>
				<dl>
					<dt>
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
<?php if($TPL_V1["name"]=='유튜브 링크'){?>
									<a href="<?php echo $TPL_V1["value"]?>" class="link" target="_blank"></a>
<?php }?>
<?php }}?>
<?php }else{?>
							<a href="https://www.youtube.com/@askimstudio" class="link" target="_blank"></a>
<?php }?>
						<img src="../data/skin/respon_default/images/sub/yotube_icon.svg" alt="유튜브">
					</dt>
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
<?php if($TPL_V1["name"]=='유튜브 텍스트'){?>
								<dd><?php echo $TPL_V1["value"]?></dd>
<?php }?>
<?php }}?>
<?php }?>
					
				</dl>
			</div>
<?php }?>
		</div>
		<div class="view_contact">
			<div class="cont">
				<h2>CONTACT</h2>
				<h5>Blooming Terra connects space and people, <br>providing the most reasonable solutions.</h5>
				<div class="btn_send">
					<a href="../board/board_write?code=inquiry"><span>Contact Us</span></a>
				</div>
			</div>
		</div>
		<div class="view_button">
			<div class="w_custom">
<?php if($TPL_VAR["button"]["prev"]> 0){?>
				<a href="?no=<?php echo $TPL_VAR["button"]["prev"]?>&cate=<?php echo $TPL_VAR["req"]["cate"]?>" class="page_btn">
<?php }else{?>
				<a href="#" class="page_btn" onclick="alert('더 이상 없습니다.'); return false;">
<?php }?>
					<dl>
						<dt><img src="/data/skin/respon_default/images/sub/page_btn.svg" alt="이전"></dt>
						<dd>PREV</dd>
					</dl>
				</a>
				<a href="goods_list?cate=<?php echo $TPL_VAR["req"]["cate"]?>" class="list_btn"><span></span><span></span><span></span></a>
<?php if($TPL_VAR["button"]["next"]> 0){?>
				<a href="?no=<?php echo $TPL_VAR["button"]["next"]?>&cate=<?php echo $TPL_VAR["req"]["cate"]?>" class="page_btn next">
<?php }else{?>
				<a href="#" class="page_btn next" onclick="alert('더 이상 없습니다.'); return false;">
<?php }?>
					<dl>
						<dt><img src="/data/skin/respon_default/images/sub/page_btn.svg" alt="이전"></dt>
						<dd>NEXT</dd>
					</dl>
				</a>
			</div>
		</div>
		<div class="dn">
			<div class="info">
				<dl>
					<dt class="thumb_box"><img src="<?php echo _UPLOAD?>/goods/img1/<?php echo $TPL_VAR["goods_view"]['goods_view']['img1']?>" onerror="this.src='/data/skin/respon_default_en/images/common/noimg.gif'" alt="<?php echo $TPL_VAR["goods_view"]['goods_view']['name']?>"></dt>
					<dd class="goods_tit"><?php echo $TPL_VAR["goods_view"]['goods_view']['name']?></dd>
				</dl>
			</div><!-- .info -->
			<div class="info_wrap">
<?php if($TPL_VAR["goods_view"]['goods_view']['upload_fname']){?>
				<dl class="info_li">
					<dt class="info_tit"><?php echo $TPL_VAR["goodsField"]['name'][$TPL_VAR["cfg_site"]['language']]['upload_fname']?></dt>
					<dd class="info_cont"><a href="/fileRequest/download?file=<?php echo urlencode('/goods/file'.'/'.$TPL_VAR["goods_view"]['goods_view']['upload_fname'])?>&save=<?php echo urlencode($TPL_VAR["goods_view"]['goods_view']['upload_oname'])?>" target="_blank" style="color:cornflowerblue;" download><?php echo $TPL_VAR["goods_view"]['goods_view']['upload_oname']?></a></dd>
				</dl>
<?php }?>
<?php if($TPL_VAR["goods_view"]['goods_view']['info']){?>
				<dl class="info_li">
					<dt class="info_tit"><?php echo $TPL_VAR["goodsField"]['name'][$TPL_VAR["cfg_site"]['language']]['info']?></dt>
					<dd class="info_cont"><?php echo htmlspecialchars_decode($TPL_VAR["goods_view"]['goods_view']['info'])?></dd>
				</dl>
<?php }?>
				<!--예비필드 노출-->
<?php if($TPL_VAR["extraFieldData"]){?>
<?php if($TPL_extraFieldData_1){foreach($TPL_VAR["extraFieldData"] as $TPL_V1){?>
					<dl class="info_li">
						<dt class="info_tit"><?php echo $TPL_V1["name"]?></dt>
						<dd class="info_cont"><?php echo $TPL_V1["value"]?></dd>
					</dl>
<?php }}?>
<?php }?>
				<dl class="info_li">
					<dt class="info_tit"><?php echo $TPL_VAR["goodsField"]['name'][$TPL_VAR["cfg_site"]['language']]['detail_img']?></h4>
					<dd class="info_cont">
						<ul>
<?php if(is_array($TPL_R1=$TPL_VAR["goods_view"]['goods_view']['detail_img'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
							<li>
								<img src="<?php echo _UPLOAD?>/goods/detail_img/<?php echo $TPL_V1?>" onerror="this.src='/data/skin/respon_default_en/images/common/noimg.gif'">
							</li>
<?php }}?>
						</ul>
					</dd>
				</dl>
			</div>
		</div>
	</div><!--/ info_wrap -->
</div><!-- .sub_cont -->
<script src="<?php echo $TPL_VAR["js"]?>/js/view.js"></script>
<?php $this->print_("footer",$TPL_SCP,1);?>