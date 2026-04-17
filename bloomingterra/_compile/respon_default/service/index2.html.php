<?php /* Template_ 2.2.8 2024/03/18 16:40:20 /home/bloomingterra/www/data/skin/respon_default/service/index2.html 000005732 */ ?>
<?php $this->print_("header",$TPL_SCP,1);?>

<?php $this->print_("popup_open",$TPL_SCP,1);?>


<style>
#contents_wrap {width:100%;padding-left:0 !important;padding-right:0 !important;}
#contents_wrap #contents_box {padding:0;}
#wrap {padding-top:0;}
/* #header{display: none;} */
.sec01_iframe{width:100%;height:100vh;position: absolute;top:0;left:0;z-index:1;}
.sec01_iframe iframe{width:100%;height:117%;position: absolute;top:0;left:0;}
.sec01 .layout .word p{z-index:3;}
.sec01 .layout{background:none;}
/* .sec01 .layout+.lay02{background:none;} */
.sec01 .layout+.lay03{background:none;}

@media only screen and (max-width:860px){
	#smooth-wrapper:before{width:100%;height:100vh;content:"";background:url('/data/skin/respon_default/service/images/skin/section_01_bg.jpg')no-repeat center;background-size:cover;position: fixed;top:0;left:0;z-index:3;}
}
</style>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/ScrollSmoother.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/ScrollToPlugin.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/SplitText.min.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/custom.js"></script>
<script src="https://player.vimeo.com/api/player.js"></script>
<div id="smooth-wrapper">
    <div id="smooth-content">
		<!-- 메인비주얼 -->
		<div class="sec01 ver_pc">
			<div class="layout lay01">
				<!-- <div class="img">
					<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
				</div> -->
				<div class="sec01_iframe">
					<iframe src="https://player.vimeo.com/video/924512237?autoplay=1&loop=1&autopause=0&muted=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" title="bloomingterra"></iframe>
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
					<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
				</div> -->
				<div class="word">
					<p>ACHIEVE</p>
					<p>SUCCESS</p>
				</div>
			</div>
			<div class="layout lay03">
				<!-- <div class="img">
					<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
				</div> -->
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
					<div class="word">
						<p>BEYOND</p>
						<p>SPACE</p>
					</div>
					<!-- <div class="img">
						<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
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
						<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
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
						<img src="/data/skin/respon_default/service/images/skin/obj_globe.png" alt="globe">
					</div> -->
				</div>
			</div>
			<div class="btn_scroll">
				<span class="scroll">SCROLL</span>
			</div>
		</div>
		<!--section1-->
<?php $this->print_("footer",$TPL_SCP,1);?>

	</div>
</div>


<script type="text/javascript">
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? true : false;

    const setVH = ()=>{
        if(isMobile){
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty("--vh", `$<?php echo $TPL_VAR["vh"]?>px`);
        }
    };
    window.addEventListener('resize',setVH);
    setVH();

    gsap.registerPlugin(ScrollTrigger, ScrollSmoother, SplitText, ScrollToPlugin);
    
    let scroller = null;

    if(!isMobile){
        
        scroller = ScrollSmoother.create({
            smooth: 1.2,
            effects: true,
            // normalizeScroll : true
        });

    }

//새로고침
/*window.onload = function() {
	setTimeout (function(){
		scrollTo(0,0);
	}, 100);
}*/
$(document).ready(function(){
	

	$(window).resize(function(){

	});

});

</script>