<?php
if ( $code_page != 'main' ) {
?>

	<?php if($ptype == "view") {?>
	<section id="outro" class="compad ani">
		<div class="outro_bg" style="background-image:url('/img/contact_bg.jpg');"></div>
		<div class="outro_txt basic_in">
			<h4 class="fs37" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200" data-aos-once="true">도시를 무대로 <br/>브랜드를 주인공으로 만드는 경험</h4>
			<h3 class="fs65" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300" data-aos-once="true">에스킴컴퍼니와 시작하세요</h3>
			<div class="btn_area" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400" data-aos-once="true">
				<a href="<?php echo $location_info['contact'][1]['src']; ?>" class="more_btn mouse_wheel">상담 신청</a>
			</div>
		</div>
	</section>
	<?php }?>

</main>




<?php if($page_type == "bbs") {?>
<!-- Swiper 제이쿼리 -->
<script type="text/javascript" src="/comm/js/swiper-bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="/comm/css/swiper-bundle.min.css" /> 
<script>
$(document).ready(function () {
	sub_num = 01;

	var cate_tab = undefined;
	cateSwiper();    
	function cateSwiper() {	
		
		var currentItemIndex2 = $('.cate_tab .swiper-slide.hover').index(),
		initialSlideIndex2 = currentItemIndex2 - 1 < 0 ? 0 : currentItemIndex2 - 1;

		cate_tab = new Swiper('.cate_tab', {
			initialSlide: sub_num-1, 
			slidesPerView: 'auto',
			paginationClickable: true,
			spaceBetween: 0,
			loop: false,
			initialSlide: initialSlideIndex2,
		}); 
	} 
	
	$(window).on('resize', function(){
		cateSwiper();              
	});
});
</script>
<?php }?>


<? } else { ?>


</main>

<?php
}
?>   
  
	
 

	<!-- Footer --> 
	<footer class="footer">
		<div class="footer_in basic_in">
			<div class="foot_logo"><img src="/img/logo_w.png" alt="ASKIM"></div>
			<div class="foot_info">
				<ul>
					<li><strong><?=$site_info['com_name']?></strong></li><br/>
					<li class="ls0"><b>사업자등록번호</b><?=$site_info['com_num']?></li>
					<li><b>대표자명</b><?=$site_info['com_owner']?></li><br/>
					<li class="address">(<?=$site_info['com_post']?>) <?=$site_info['com_address']?></li>
					<li class="ls0"><b>Tel.</b><?=$site_info['com_tel']?></li>
					<li class="ls0"><b>E-mail.</b><?=$site_info['site_email']?></li>
				</ul>
				<dl>
					<dd><a href="<?php echo $location_info['other'][1]['src']; ?>">개인정보취급방침</a></dd>
					<dt>© Askim company. All rights reserved. <span>Site by <a href="https://www.web2002.co.kr/" target="_blank" title="새 창 열림">THREEWAY</a></span></dt>
				</dl>
			</div>
		</div>
	</footer>
	<!--//Footer -->








	<!-- 검색레이어팝업 -->
	<div class="mask_search"></div>
	<div class="search_window">
		<div class="search_in">
			<?php include "$_SERVER[DOCUMENT_ROOT]/twcenter/module/search.php";   // 전체검색 ?>
		</div>
		<div class="search_close">닫기</div>
	</div>
	<!--//검색레이어팝업 -->

	<div class="mask"></div>
	<div class="mask_pro"></div>

<script>
AOS.init();

// 검색 레이어 팝업
function wrapWindowByMask(){
// 화면의 높이와 너비를 변수로 만듭니다.
var maskHeight = $(document).height();
var maskWidth = $(window).width();

// fade 애니메이션 : 1초 동안 검게 됐다가 80%의 불투명으로 변합니다.
$('.mask_search').addClass('visible');
$('.search_window').addClass('visible');

// 레이어 팝업을 가운데로 띄우기 위해 화면의 높이와 너비의 가운데 값과 스크롤 값을 더하여 변수로 만듭니다.
var left = ( $(window).scrollLeft() + ( $(window).width() - $('.search_window').width()) / 2 );
var top = ( $(window).scrollTop() + ( $(window).height() - $('.search_window').height()) / 2 );

// css 스타일을 변경합니다.
$('.search_window').css({'left':'50%','top':'50%'});

}

$(document).ready(function(){
	// showMask를 클릭시 작동하며 검은 마스크 배경과 레이어 팝업을 띄웁니다.
	$('.showMask').click(function(e){
		e.preventDefault();
		wrapWindowByMask();
	});

	// 닫기(close)를 눌렀을 때 작동합니다.
	$('.search_window .search_close').click(function (e) {
		e.preventDefault();
		$('.mask_search').removeClass('visible');
		$('.search_window').removeClass('visible');
	}); 

	// 뒤 검은 마스크를 클릭시에도 모두 제거하도록 처리합니다.
	$('.mask_search').click(function () {
		$('.mask_search').removeClass('visible');
		$('.search_window').removeClass('visible');
	});
});

			
// 모니터 세로값 변수 지정
const setVh = () => {
document.documentElement.style.setProperty('--vh', `${window.innerHeight}px`)
};
window.addEventListener('resize', setVh);
setVh();
</script>






</div>
<!-- //wrapper -->

<?php if($oper_info["nhn_common_key"] != ""){ ?>
<script type="text/javascript"> 
wcs_do();
</script>
<?php } ?>

</body>
</html>