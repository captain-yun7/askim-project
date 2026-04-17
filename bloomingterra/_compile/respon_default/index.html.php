<?php /* Template_ 2.2.8 2024/05/02 12:09:51 /home/bloomingterra/www/data/skin/respon_default/index.html 000031571 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<?php $this->print_("popup_open",$TPL_SCP,1);?>


<style>
#contents_wrap {width:100%;padding-left:0 !important;padding-right:0 !important;}
#contents_wrap #contents_box {padding:0;}
#wrap {padding-top:0;}

@media only screen and (max-width:860px){
	#smooth-wrapper:before{width:100%;height:100vh;content:"";background:url('/data/skin/respon_default/images/skin/section_01_bg.jpg')no-repeat center;background-size:cover;position: fixed;top:0;left:0;z-index:0;}
}
</style>
<script src="<?php echo $TPL_VAR["js"]?>/js/gsap.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/ScrollTrigger.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/ScrollSmoother.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/ScrollToPlugin.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/SplitText.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/custom.js"></script>

<script type="text/javascript">
	var confData = JSON.parse('<?php echo json_encode($TPL_VAR["main_image_slide"]["pc"][$TPL_VAR["lang"]])?>');
		confData.files = confData.files || {};
	$(window).resize(function() {
		// 팝업 이벤트 호출
<?php if(isset($TPL_VAR["popup_list"]['popup_list'])){?>
			var winWidth = $(window).width();
<?php if(is_array($TPL_R1=$TPL_VAR["popup_list"]['popup_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
<?php if(get_cookie('popup_'.$TPL_V1["no"],true)!='y'){?>
					var popupWidth = 0, popupHeight = 0, popupToppx = 0, popupLeftpx = 0;
					var isMobile = false;
					var agentInitial = '';
<?php if($TPL_V1["popupform"]=="responsive"){?>
						if(winWidth >= Number(<?php echo $TPL_V1["recognition_pc"]?>)){
							popupWidth = Number(<?php echo $TPL_V1["width_responsive_pc"]?>);
							popupHeight = Number(<?php echo $TPL_V1["height_responsive_pc"]?>);
							popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_pc"]?>);
							popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_pc"]?>);
							agentInitial = 'pc';
						} else if(winWidth >= Number(<?php echo $TPL_V1["recognition_tablet"]?>)){
							popupWidth = Number(<?php echo $TPL_V1["width_responsive_tablet"]?>);
							popupHeight = Number(<?php echo $TPL_V1["height_responsive_tablet"]?>);
							popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_tablet"]?>);
							popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_tablet"]?>);
							agentInitial = 't';
						}else {
							popupWidth = Number(<?php echo $TPL_V1["width_responsive_mobile"]?>);
							popupHeight = Number(<?php echo $TPL_V1["height_responsive_mobile"]?>);
							popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_mobile"]?>);
							popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_mobile"]?>);
							isMobile = true;
							agentInitial = 'm';
						}

<?php if($TPL_V1["type"]=='1'){?>
							if($('#popup_<?php echo $TPL_V1["no"]?>').length > 0){
								var popupObj = $('#popup_<?php echo $TPL_V1["no"]?>');

								if (isMobile){
									if(popupObj.css('width') != popupWidth+'px'){
										popupObj.css('width', popupWidth + '%');
									}
								}else{
									if(popupObj.css('width') != popupWidth+'px'){
										popupObj.css('width', popupWidth + 'px');
									}
								}

								/*if (isMobile){	
									if(popupObj.css('height') != popupHeight+'px'){
									}
								}else{
									if(popupObj.css('height') != popupHeight+'px'){
										popupObj.css('height', popupHeight + 'px');
									}
								}*/

								if(popupObj.css('top') != popupToppx+'px'){
									popupObj.css('top', popupToppx + 'px');
								}

								if (isMobile){
									popupObj.css('left', '50%');
									popupObj.css('height', 'auto');
								}else{
									if(popupObj.css('left') != popupLeftpx+'px'){
										popupObj.css('left', popupLeftpx + 'px');
									}
								}

								if (popupObj.hasClass('layer_pc')){
									popupObj.removeClass('layer_pc');
								}else if(popupObj.hasClass('layer_t')){
									popupObj.removeClass('layer_t');
								}else if(popupObj.hasClass('layer_m')){
									popupObj.removeClass('layer_m');
								}
								popupObj.addClass('layer_'+agentInitial);
							}
<?php }else{?>
							var popup_options = 'width='+popupWidth+',height='+popupHeight+',top='+popupToppx+',left='+popupLeftpx+',status=no,resizable=no,scrollbars=yes'; 
							var popup_html = '<?php echo addslashes(htmlspecialchars_decode($TPL_V1["content"]))?>';
							popup_html += '<div style="float:right; margin-bottom:5px; margin-right:15px;">';
							popup_html += '	<label><input type="checkbox" value="<?php echo $TPL_V1["no"]?>" onChange="opener.noShow(this);">하루 동안 보지않기</label> <a href="javascript:this.close();">닫기</a>';
							popup_html += '</div>';
							if(typeof(popup_<?php echo $TPL_V1["no"]?>) == 'object') {
								var addressHeight = popup_<?php echo $TPL_V1["no"]?>.outerHeight - popup_<?php echo $TPL_V1["no"]?>.innerHeight;//주소 표시줄 높이
								popup_<?php echo $TPL_V1["no"]?>.resizeTo(popupWidth+(popup_<?php echo $TPL_V1["no"]?>.outerWidth - popup_<?php echo $TPL_V1["no"]?>.innerWidth), popupHeight+addressHeight);
								popup_<?php echo $TPL_V1["no"]?>.moveTo(popupToppx, popupLeftpx);
								console.log(popup_options);
							}
<?php }?>
<?php }?>
<?php }?>
<?php }}?>
<?php }?>
	});
	
</script>

<div id="smooth-wrapper">
    <div id="smooth-content">
		<div class="main_content">
			<div class="section_intro"></div>
			<!-- 메인비주얼 -->
			<div class="sec01 ver_pc">
				<div class="layout lay01">
					<!-- <div class="img">
						<img src="/data/skin/respon_default/images/skin/obj_globe.png" alt="globe">
					</div> -->
					<div class="sec01_iframe">
						<iframe src="https://player.vimeo.com/video/924512237?autoplay=1&loop=1&autopause=0&muted=1&controls=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" title="bloomingterra"></iframe>
					</div>
					<div class="word">
						<p>BEYOND</p>
						<p>SPACE</p>
					</div>
					<div class="word">
						<p>ACHIEVE</p>
						<p>SUCCESS</p>
					</div>
				</div>
				<div class="layout lay02">
					<!-- <div class="img">
						<img src="/data/skin/respon_default/images/skin/obj_globe.png" alt="globe">
					</div> -->
					<div class="word">
						<p>ACHIEVE</p>
						<p>SUCCESS</p>
					</div>
				</div>
				<div class="layout lay03" id="mainAbout">
					<div class="sec01_iframe">
						<iframe src="https://player.vimeo.com/video/924512237?autoplay=1&loop=1&autopause=0&muted=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" title="bloomingterra"></iframe>
					</div>
					<div class="word">
						<p>ACHIEVE</p>
						<p>SUCCESS</p>
					</div>
					<div class="wo2">우리는 공간을 통해 트렌드를 만듭니다.</div>
					<dl class="wo3">
						<dt>
							<div>
								<div class="in">WE CREATE TREND<div class="typ"></div></div>
							</div>
							<div>
								<div class="in">WITH CREATIVE SPACE<div class="typ"></div></div>
							</div>
						</dt>
						<dd>
							<div>
								우리는 공간을 통해 트렌드를 만듭니다.
							</div>
						</dd>
					</dl>
				</div>
				<div class="btn_scroll">
					<span class="scroll">SCROLL</span>
				</div>
			</div>
			<div class="sec01 ver_m">
				<div class="layout lay01">
					<div class="layout_flex">
						<div class="sec01_iframe">
							<iframe src="https://player.vimeo.com/video/924512237?autoplay=1&loop=1&autopause=0&muted=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" title="bloomingterra"></iframe>
						</div>
						<div class="word">
							<p>BEYOND</p>
							<p>SPACE</p>
						</div>
						<!-- <div class="img">
							<img src="/data/skin/respon_default/images/skin/obj_globe.png" alt="globe">
						</div> -->
					</div>
				</div>
				<div class="layout lay02">
					<div class="layout_flex">
						<div class="word">
							<p>ACHIEVE</p>
							<p>SUCCESS</p>
						</div>
						<!-- <div class="img">
							<img src="/data/skin/respon_default/images/skin/obj_globe.png" alt="globe">
						</div> -->
					</div>
				</div>
				<div class="layout lay03">
					<div class="layout_flex">
						<dl class="wo3">
							<dt>
								<div>
									<div class="in">WE CREATE TREND<div class="typ"></div></div>
								</div>
								<div>
									<div class="in">WITH CREATIVE SPACE<div class="typ"></div></div>
								</div>
							</dt>
							<dd>
								<div>
									우리는 공간을 통해 트렌드를 만듭니다.
								</div>
							</dd>
						</dl>
						<!-- <div class="img">
							<img src="/data/skin/respon_default/images/skin/obj_globe.png" alt="globe">
						</div> -->
					</div>
				</div>
				<div class="btn_scroll">
					<span class="scroll">SCROLL</span>
				</div>
			</div>
			<!--section1-->
			<div class="sec02" id="sec02">
				<div class="pin">
					<div class="cir">
						<div class="line">
							<div class="bar"></div>
							<div class="li01 lib"></div>
							<div class="li02 lib"></div>
						</div>
					</div>
					<ul class="layout">
						<li class="l1">
							<div class="tbx">
								<div class="cir-fi cir">
									<div class="line">
										<div class="li01 lib"></div>
										<div class="li02 lib"></div>
									</div>
								</div>
								<h3 class="h3">핫플레이스 캠페인</h3>
								<h4 class="h4" data-count="100"><span>0</span><sup>+</sup></h4>
								<p class="p">빠르게 변화하는 핫플 트렌드를 읽고, <br>성수, 신당, 북촌, 이태원, 압구정 등에서<br> 캠페인을 진행하고 있습니다.</p>
							</div>
							<div class="back">
								<div class="img_box">
									<img class="bu01" src="/data/skin/respon_default/images/skin/campaign_bg.png" alt="">
									<img class="bu02" src="/data/skin/respon_default/images/skin/campaign01.png" alt="">
									<img class="bu03" src="/data/skin/respon_default/images/skin/campaign02.png" alt="">
									<img class="bu04" src="/data/skin/respon_default/images/skin/campaign03.png" alt="">
									<img class="bu05" src="/data/skin/respon_default/images/skin/campaign04.png" alt="">
									<img class="bu06" src="/data/skin/respon_default/images/skin/campaign05.png" alt="">
								</div>
								<div class="obj">
									<img class="obj01" src="/data/skin/respon_default/images/skin/camp_obj01.png" alt="">
									<img class="obj02" src="/data/skin/respon_default/images/skin/camp_obj02.png" alt="">
								</div>
								<div class="circle">
									<div class="bu_circle" id="circle01">
										<span>북촌</span>
										<div class="cir-fi cir">
											<div class="line">
												<div class="li01 lib"></div>
												<div class="li02 lib"></div>
											</div>	
										</div>
									</div>
									<div class="bu_circle" id="circle02">
										<span>신당</span>
										<div class="cir-fi cir">
											<div class="line">
												<div class="li01 lib"></div>
												<div class="li02 lib"></div>
											</div>
										</div>
									</div>
									<div class="bu_circle" id="circle03">
										<span>이태원</span>
										<div class="cir-fi cir">
											<div class="line">
												<div class="li01 lib"></div>
												<div class="li02 lib"></div>
											</div>
										</div>
									</div>
									<div class="bu_circle" id="circle04">
										<span>성수</span>
										<div class="cir-fi cir">
											<div class="line">
												<div class="li01 lib"></div>
												<div class="li02 lib"></div>
											</div>
										</div>
									</div>
									<div class="bu_circle" id="circle05">
										<span>압구정</span>
										<div class="cir-fi cir">
											<div class="line">
												<div class="li01 lib"></div>
												<div class="li02 lib"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li class="l2">
							<div class="block">
								<div class="lob">
									<div class="item">
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_01.png" alt="디올"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_02.png" alt="포르쉐"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_03.png" alt="구글"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_04.png" alt="카르티에"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_05.png" alt="나이키"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_01.png" alt="디올"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_02.png" alt="포르쉐"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_03.png" alt="구글"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_04.png" alt="카르티에"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_05.png" alt="나이키"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_01.png" alt="디올"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_02.png" alt="포르쉐"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_03.png" alt="구글"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_04.png" alt="카르티에"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner01_05.png" alt="나이키"></div></div>
										</div>
									</div>
									<div class="item">
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_01.png" alt="버버리"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_02.png" alt="벤츠"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_03.png" alt="보테가"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_04.png" alt="넷플릭스"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_05.png" alt="무신사"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_01.png" alt="버버리"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_02.png" alt="벤츠"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_03.png" alt="보테가"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_04.png" alt="넷플릭스"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_05.png" alt="무신사"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_01.png" alt="버버리"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_02.png" alt="벤츠"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_03.png" alt="보테가"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_04.png" alt="넷플릭스"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner02_05.png" alt="무신사"></div></div>
										</div>
									</div>
									<div class="item">
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_01.png" alt="삼성"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_02.png" alt="예거"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_03.png" alt="올리브영"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_04.png" alt="키엘"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_05.png" alt="프라다"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_01.png" alt="삼성"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_02.png" alt="예거"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_03.png" alt="올리브영"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_04.png" alt="키엘"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_05.png" alt="프라다"></div></div>
										</div>
										<div class="los">
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_01.png" alt="삼성"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_02.png" alt="예거"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_03.png" alt="올리브영"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_04.png" alt="키엘"></div></div>
											<div class="logo"><div><img src="/data/skin/respon_default/images/skin/partner03_05.png" alt="프라다"></div></div>
										</div>
									</div>
								</div>
							</div>
							<div class="tbx">
								<div class="cir-fi cir">
									<div class="line">
										<div class="li01 lib"></div>
										<div class="li02 lib"></div>
									</div>
								</div>
								<h3 class="h3">협업 브랜드</h3>
								<h4 class="h4" data-count="50"><span>0</span><sup>+</sup></h4>
								<p class="p">국내 유명 브랜드부터 세계의 명품 브랜드까지 <br>50여개의 브랜드와 협업했습니다.</p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!--section2-->
			<div class="sec_scr_wrap">
				<div class="sec03" id="sec03">
					<div class="pin">
						<div class="intro">
							<div class="cir">
								<div class="line">
									<div class="li01 lib"></div>
									<div class="li02 lib"></div>
								</div>
							</div>
							<div class="half_line">
								<div class="bar"></div>
								<div class="circle"></div>
							</div>
							<div class="txt_box">
								<div class="sub_txt">
									<p class="sec03_sp">EXPERIENCE</p>
								</div>
								<div class="sub_txt">
									<p class="sec03_camp">VALUE</p>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<!--section3-->
				<div class="sec04 horizon_wrap" id="sec04">
					<div class="title">
						<div class="sub_txt">
							<svg class="loading" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							   width="850px" height="110px" viewBox="0 0 574.558 120" enable-background="new 0 0 574.558 120" xml:space="preserve">
							  <defs>
								<pattern id="water" width=".25" height="1.1" patternContentUnits="objectBoundingBox">
								  <path fill="#42B0FB" d="M0.25,1H0c0,0,0-0.659,0-0.916c0.083-0.303,0.158,0.334,0.25,0C0.25,0.327,0.25,1,0.25,1z"/>
								</pattern>
								
								<text id="text" transform="matrix(1 0 0 1 -8.0684 116.7852)" font-size="160" font-family="Kanit" font-weight="bold" x="50%" text-anchor="middle">EXPERIENCE</text>
								
								<mask id="text_mask">
								  <use x="0" y="0" xlink:href="#text" opacity="1" fill="#42B0FB"/>
								</mask>
							  </defs>
							  <use x="0" y="0" xlink:href="#text" fill="transparent" stroke="#42B0FB" stroke-width="2"/>
							  <rect class="water-fill" mask="url(#text_mask)" fill="url(#water)" x="-200" y="-10" width="2000" height="120"/>
							</svg>
						</div>
					</div>
					<div class="horizon">
						<div class="layer">
							<div class="des">
								<span>EXPERIENCE</span>
								<h3><strong>특별한 경험 제공</strong></h3>
								<p>공간을 통해 브랜드의 메시지를 전달하고, <br>고객의 오감을 자극하는 특별한 경험을 제공합니다. <br>이색 상호작용으로 브랜드와 고객과의 <br>자연스러운 관계 구축을 형성합니다.</p>
							</div>
						</div>
						<div class="layer">
							<ul class="list">
								<li>
									<a href="">
										<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_special_01.jpg" alt="special1"></div>
									</a>
								</li>
								<li>
									<a href="">
										<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_special_02.jpg" alt="special2"></div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--section4-->	
				<div class="sec05 horizon_wrap" id="sec05">
					<div class="title">
						<div class="sub_txt">
							<svg class="loading" version="1.1"  x="0px" y="0px"
							   width="750px" height="110px" viewBox="0 0 574.558 120" enable-background="new 0 0 574.558 120" xml:space="preserve">
							  <defs>
								<pattern id="water2" width=".25" height="1.1" patternContentUnits="objectBoundingBox">
								  <path fill="#42B0FB" d="M0.25,1H0c0,0,0-0.659,0-0.916c0.083-0.303,0.158,0.334,0.25,0C0.25,0.327,0.25,1,0.25,1z"/>
								</pattern>
								
								<text id="text2" transform="matrix(1 0 0 1 -8.0684 116.7852)" font-size="160" font-family="Kanit" font-weight="bold" x="50%" text-anchor="middle">VALUE</text>
								
								<mask id="text_mask2">
								  <use x="0" y="0" xlink:href="#text2" opacity="1" fill="#42B0FB"/>
								</mask>
							  </defs>
							  <use x="0" y="0" xlink:href="#text2" fill="transparent" stroke="#42B0FB" stroke-width="2"/>
							  <rect class="water-fill" mask="url(#text_mask2)" fill="url(#water2)" x="-400" y="-10" width="1600" height="120"/>
							</svg>
						</div>
					</div>
					<div class="horizon">
						<div class="layer">
							<div class="des">
								<span>VALUE</span>
								<h3><strong>성공을 통한 가치창출</strong></h3>
								<p>블루밍테라는 고객과 브랜드에게 모두 <br>가치를 만들어내는 공간입니다. <br>고객에게는 가치 있는 경험을, <br>브랜드에게는 성공적인 캠페인 성과를 제공합니다.</p>
							</div>
						</div>
						<div class="layer">
							<ul class="list">
								<li>
									<a href="">
										<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_campaign_01.jpg" alt="campaign1"></div>
									</a>
								</li>
								<li>
									<a href="">
										<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_campaign_02.jpg" alt="campaign2"></div>
									</a>
								</li>
								<li>
									<a href="">
										<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_campaign_03.jpg" alt="campaign3"></div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--section5-->	
			</div>
			<div class="sec06" id="sec06">
				<div class="sec06_bg"></div>
				<div class="lasy ver_pc">
					<div class="title">
						<h3>SERVICE</h3>
						<p>지금 바로 예약 가능한 공간들을 소개합니다.</p>
					</div>
					 <ul class="ibx">
						<li>
							<a href="/goods/goods_view?no=20"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service01.jpg" alt="service01"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>넷플릭스 신작 [도적, 칼의소리] 홍보 팝업스토어</dt>
									<dd>과거 1920년대 배경으로 일제시대 당시 조선인들의 투쟁사를 그린 넷플릭스의 신작 [도적, 칼의소리] 홍보 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=21"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service02.jpg" alt="service02"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>디즈니 100주년기념 팝업스토어</dt>
									<dd>디즈니 100주년을 기념하여 디즈니의 헤리티지 작품들을 홍보하는 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=22"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service03.jpg" alt="service03"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>거미집 영화 개봉기념, 팝업스토어 </dt>
									<dd>70년대 영화 촬영 현장을 그대로 옮겨놓은 공간으로 다양한 체험존을 구비한 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=23"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service04.jpg" alt="service04"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>삼성 갤럭시 Z 폴드5 출시 기념, 성수동 옥외광고 캠페인</dt>
									<dd>삼성의 갤럭시Z 폴드5 출시를 기념하여, 성수동의 주요 지점 5곳의 옥외광고 캠페인을 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=24"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service05.jpg" alt="service05"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>까르띠에의 [타임 언리미티드 전시] 성수동 옥외광고 캠페인</dt>
									<dd>까르띠에의 전시 홍보를 위해 성수동내 주요지점 5곳의 옥외광고 캠페인을 진행</dd>
								</dl>
							</div>
						</li>
					</ul>
				</div>
				<div class="lasy ver_m">
					<div class="title">
						<h3>SERVICE</h3>
						<p>지금 바로 예약 가능한 공간들을 소개합니다.</p>
					</div>
					 <ul class="ibx">
						<li>
							<a href="/goods/goods_view?no=20"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service01.jpg" alt="service01"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>넷플릭스 신작 [도적, 칼의소리] 홍보 팝업스토어</dt>
									<dd>과거 1920년대 배경으로 일제시대 당시 조선인들의 투쟁사를 그린 넷플릭스의 신작 [도적, 칼의소리] 홍보 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=21"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service02.jpg" alt="service02"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>디즈니 100주년기념 팝업스토어</dt>
									<dd>디즈니 100주년을 기념하여 디즈니의 헤리티지 작품들을 홍보하는 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=22"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service03.jpg" alt="service03"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>거미집 영화 개봉기념, 팝업스토어 </dt>
									<dd>70년대 영화 촬영 현장을 그대로 옮겨놓은 공간으로 다양한 체험존을 구비한 팝업스토어를 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=23"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service04.jpg" alt="service04"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>삼성 갤럭시 Z 폴드5 출시 기념, 성수동 옥외광고 캠페인</dt>
									<dd>삼성의 갤럭시Z 폴드5 출시를 기념하여, 성수동의 주요 지점 5곳의 옥외광고 캠페인을 진행</dd>
								</dl>
							</div>
						</li>
						<li>
							<a href="/goods/goods_view?no=24"></a>
							<div class="thumb"><img src="/data/skin/respon_default/images/skin/main_service05.jpg" alt="service05"></div>
							<div class="txt_box">
								<div class="ov_box"></div>
								<dl>
									<dt>까르띠에의 [타임 언리미티드 전시] 성수동 옥외광고 캠페인</dt>
									<dd>까르띠에의 전시 홍보를 위해 성수동내 주요지점 5곳의 옥외광고 캠페인을 진행</dd>
								</dl>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!--section6-->	
			<div class="sec07">
				<?php echo include_('board_write','board/_form_board_write.html')?>

			</div>
		</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	</div>
</div>


<script type="text/javascript">
//새로고침
window.onload = function() {
	setTimeout (function(){
		scrollTo(0,0);
	}, 100);
}
$(document).ready(function(){
	var st = $(this).scrollTop();
	var sec03H = $('#header').outerHeight() + $('.sec01').outerHeight() + $('.sec02').outerHeight();

	if(st > sec03H){
		$('.sec03').addClass('on');
	}

	var serviceSwiper = undefined;
	function initSwiper(){
		var winW = $(window).width();
		if(winW < 860 && serviceSwiper == undefined){
			$('.sec06 .lasy.ver_m').addClass('swiper-container');
			$('.sec06 .ver_m .ibx').addClass('swiper-wrapper');
			$('.sec06 .ver_m .ibx li').addClass('swiper-slide');
			var serviceSwiper = new Swiper(".sec06 .lasy.ver_m", {
				slidesPerView : 2.5,
				spaceBetween : 20,
				breakpointsInverse: true,
				breakpoints: {
					500: {
						slidesPerView : 2.5,
						spaceBetween : 20,
					},
					0: {
						slidesPerView : 1.5,
						spaceBetween : 10,
					},
				},
			});
		}
		else if(winW > 861 && serviceSwiper != undefined){
			serviceSwiper.destroy();
			serviceSwiper = undefined;
			$('.sec06 .lasy.ver_m').removeClass('swiper-container');
			$('.sec06 .ver_m .ibx').removeClass('swiper-wrapper');
			$('.sec06 .ver_m .ibx li').removeClass('swiper-slide');
		}
	}
	initSwiper();
	
	$(window).on('resize', function(){
        initSwiper();
    });    
	
	$(window).scroll(function(){
		var st = $(this).scrollTop();
		var sec03H = $('#header').outerHeight() + $('.sec01').outerHeight() + $('.sec02').outerHeight();

		if(st > sec03H){
			$('.sec03').addClass('on');
		}
	});
	
});

</script>