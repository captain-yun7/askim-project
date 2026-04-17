<?php
$code_page='main';
$_GET['bg'] = 1;
include_once $_SERVER["DOCUMENT_ROOT"]."/head.php";

$sql_mb = "select * from wiz_banner where code='indicator'";
$mbinfo = sql_fetch($sql_mb);

?>
	
<script type="text/javascript" src="/comm/js/gsap.min.js"></script>
<script type="text/javascript" src="/comm/js/ScrollTrigger.min.js"></script>
	<section id="visual">
		<div class="visual_tit basic_in">
			<h2 class="fs80">도시 위에 <br/>브랜드를 새깁니다</h2>
			<div class="v_logo"><img src="/img/logo__txt.png" alt="ASKIM"></div>
		</div>
		<div class="visual_bg">
			<?php
				$banner_code = "visual";
				include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/banner_skin.php"; // 디자인 스킨 적용
			?>
		</div>
		<div class="controler">
			<div class="v_btn">
				<button class="v_play">Play</button>
				<button class="v_stop">Stop</button>
			</div>
			<div class="custom-dots"></div>
		</div>
		
		<a href="#about" class="scroll_down mouse_wheel"><span class="blind">scroll down</span><i></i><?php include $_SERVER['DOCUMENT_ROOT']."/img/scr_icon.svg";?></a>
	</section>
	
	<section id="about" class="compad ani">
		<div class="basic_in">
			<div class="title_area">
				<h3 class="fs65" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">옥외광고, <br/>아직도 이렇게 생각하시나요?</h3>
			</div>
			<ul class="about_list">
				<li data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
					<div class="icon_wrap">
						<span class="icon">
							<img src="/img/about_icon01.svg" alt="유동인구 많은 곳이면 무조건 좋은 거 아닌가?">
						</span>
					</div>
					<p class="fs21">유동인구 많은 곳이면 <br/>무조건 좋은 거 아닌가?</p>
				</li>
				<li data-aos="fade-up" data-aos-duration="600" data-aos-delay="500">
					<div class="icon_wrap">
						<span class="icon">
							<img src="/img/about_icon02.svg" alt="버스, 지하철 광고가 전부 아니야? 다 똑같은 매체인데 뭐가 달라? ">
						</span>
					</div>
					<p class="fs21">버스, 지하철 광고가 <br/>전부 아니야? <br class="brFixed"/>다 똑같은 매체인데 뭐가 달라?</p>
				</li>
				<li data-aos="fade-up" data-aos-duration="600" data-aos-delay="700">
					<div class="icon_wrap">
						<span class="icon">
							<img src="/img/about_icon03.svg" alt="우리 예산으로 이런 광고까지 할 수 있을까? ">
						</span>
					</div>
					<p class="fs21">우리 예산으로 <br/>이런 광고까지 할 수 있을까?</p>
				</li>
			</ul>
		</div>
	</section>
	
	<section id="slogan" class="ani">
		<div class="slogan_in">
			<div class="slogan_bg" style="background-image:url('/img/slogan_bg.jpg');"></div>
			<div class="slogan_txt basic_in">
				<h3 class="fs80 step1">정해진 프레임에 <br/>브랜드를 맞추지 마세요</h3>
			</div>
			<div class="slogan_txt  basic_in">
				<h3 class="fs80 step2">에스킴컴퍼니는 <br/><span class="highlight_wrap"><span class="highlight"></span>고객의 길 위에</span> <br/><span class="highlight_wrap"><span class="highlight"></span>진짜 노출</span>을 만듭니다</h3>
			</div>
			<a href="#usp" class="scroll_down mouse_wheel"><span class="blind">scroll down</span><i></i><?php include $_SERVER['DOCUMENT_ROOT']."/img/scr_icon.svg";?></a>
		</div>
	</section>
	

	<section id="usp" class="compad ani">
		<div class="usp_bg"></div>
		<div class="title_area basic_in">
			<h3 class="fs65">우리는 고객과 브랜드의 <br/>진짜 접점을 설계합니다</h3>
		</div>
		<article>
			<div class="load_line"></div>
			<div class="usp_list basic_in">
				<dl class="one">
					<dt>
						<span class="big_dot"></span>
						<span class="small_dot"></span>
						<span class="line"></span>
						<span class="icon"><img src="/img/usp_icon01.svg" alt="핫플레이스 정보는 기본"/></span>
					</dt>
					<dd class="line_box"></dd>
					<dd>
						<h4 class="fs21">핫플레이스 정보는 기본</h4>
						<p class="fs19">남들보다 한발 앞서 <br/>우리 고객이 진짜 머무는 동선을 포착합니다.</p>
					</dd>
				</dl>
				<dl class="two">
					<dt>
						<span class="big_dot"></span>
						<span class="small_dot"></span>
						<span class="line"></span>
						<span class="icon"><img src="/img/usp_icon02.svg" alt="똑같이 정해진 매체는 이제 그만"/></span>
					</dt>
					<dd class="line_box"></dd>
					<dd>
						<h4 class="fs21">똑같이 정해진 매체는 이제 그만</h4>
						<p class="fs19">브랜드의 감도에 맞춘 <br/>새로운 미디어 매체를 먼저 선점합니다.</p>
					</dd>
				</dl>
				<dl class="three">
					<dt>
						<span class="big_dot"></span>
						<span class="small_dot"></span>
						<span class="line"></span>
						<span class="icon"><img src="/img/usp_icon03.svg" alt="버려진 벽도, 낡은 지붕도 브랜드의 무대로"/></span>
					</dt>
					<dd class="line_box"></dd>
					<dd>
						<h4 class="fs21">버려진 벽도, 낡은 지붕도 <br/>브랜드의 무대로</h4>
						<p class="fs19">아무도 관심 없던 곳에도 <br/>시선이 쏠리기 시작합니다.</p>
					</dd>
				</dl>
			</div>
		</article>
	</section>
	


	<section id="usp_cont" class="compad">
		<div class="usp_in">
			<article class="first usp-item usp-item1">
				<div class="usp_obj">
					<div class="usp_obj_img usp_obj_img1"><div class="people"></div></div>
				</div>
				<div class="txt_area usp-txt">
					<div class="in">
						<h3 class="fs70">POPULAR <br/>SPOT</h3>
						<p class="fs21">하이엔드와 라이징 브랜드 모두 주목하는 바로 그곳!</p>
						<p class="fs19">서울 성수동 내 50개 이상의 매체를 운영하고 있으며, <br/>홍대, 압구정, 한남, 명동, 부산 등 국내 주요 도시와 <br/>할리우드, 선셋 스트립 등 미국 LA 핵심 스트리트 내 매체를 <br/>다량 보유하고 있습니다.</p>
					</div>
				</div>
				<div class="img_area">
					<div class="usp_style">
						<?php
							$banner_code = "popular_spot";
							include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/banner_skin.php"; // 디자인 스킨 적용
						?>
					</div>
				</div>
			</article>
			<article class="change_cont usp-item usp-item2">
				<div class="usp_obj">
					<div class="usp_obj_img usp_obj_img2"><div class="ship"></div></div>
				</div>
				<div class="txt_area usp-txt">
					<div class="in">
						<h3 class="fs70">CUSTOMIZED</h3>
						<p class="fs21">무조건 유명한 장소, <br/>뻔한 전통 매체는 이제 그만!</p>
						<p class="fs19">우리 브랜드에 꼭 맞는 효율적인 매체 전략과 <br/>오직 에스킴컴퍼니에서만 가능한 특수 매체로 특별함을 더하세요.</p>
					</div>
				</div>
				<div class="img_area">
					<div class="usp_style">
						<?php
							$banner_code = "customized";
							include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/banner_skin.php"; // 디자인 스킨 적용
						?>
					</div>
				</div>
			</article>
			<article class="usp-item usp-item3">
				<div class="usp_obj">
					<div class="usp_obj_img usp_obj_img3"><div class="people"></div></div>
				</div>
				<div class="txt_area usp-txt">
					<div class="in">
						<h3 class="fs70">CREATIVITY</h3>
						<p class="fs21">단순히 자극적인 크리에이티브가 아닌,</p>
						<p class="fs19">지역 히스토리와 입지를 똑똑하게 활용한 크리에이티브로 <br/>업계에서 회자되는 임팩트를 경험하세요.</p>
					</div>
				</div>
				<div class="img_area">
					<div class="usp_style">
						<?php
							$banner_code = "creativity";
							include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/banner_skin.php"; // 디자인 스킨 적용
						?>
					</div>
				</div>
			</article>
		</div>
	</section>
	

	<script>
		const uspItems = document.querySelectorAll('.usp_obj');

		window.addEventListener('scroll', () => {
		  const scrollY = window.scrollY;
		  const windowHeight = window.innerHeight;

		  uspItems.forEach(item => {
			const uspObj = item.querySelector('.usp_obj_img');
			const itemRect = item.getBoundingClientRect();
			const start = itemRect.top; // 뷰포트 기준
			const end = itemRect.bottom - uspObj.offsetHeight;

			// 뷰포트 안에 들어오면 translateY 적용
			if (itemRect.top < windowHeight && itemRect.bottom > 0) {
			  // scrollProgress: 0 ~ 1
			  const scrollProgress = (windowHeight - start) / (windowHeight + itemRect.height);
			  // 이동 거리: 0 ~ 최대 100px (원하는 값으로 조정)
			  const translateY = scrollProgress * 200;
			  uspObj.style.transform = `translateY(${translateY}px)`;
			}
		  });
		});
		
	</script>



	<section id="corevalue" class="compad ani">
		<div class="corevalue_bg"></div>
		<div class="basic_in">
			<div class="title_area">
				<h3 class="fs65">우리는 공간에 생명을 불어넣고 <br/>도시의 결을 바꿉니다</h3>
			</div>
			<ul class="corevalue_list">
				<li>
					<div class="in">
						<p class="point_txt">CREATIVITY</p>
						<div class="icon_wrap"><span class="icon"><img src="/img/corevalue_icon01.svg" alt="창의성"/></span></div>
						<p class="fs21">도시와 브랜드를 연결하는</p>
						<h4 class="fs37">창의성</h4>
						<p class="fs19">도시 공간과 브랜드 메시지를 새롭게 연결합니다. <br class="brFixed"/>틀에 박힌 방식이 아닌, <br/>감각적이고 문화적인 방식으로 <br/>사람들의 시선을 사로잡습니다.</p>
					</div>
				</li>
				<li>
					<div class="in">
						<p class="point_txt">PIONEERING</p>
						<div class="icon_wrap"><span class="icon"><img src="/img/corevalue_icon02.svg" alt="개척"/></span></div>
						<p class="fs21">시장에 먼저 진입하는</p>
						<h4 class="fs37">개척</h4>
						<p class="fs19">입지의 가치를 재해석하고, <br/>건물주와 지역을 설득해 <br/>새로운 미디어를 현실화합니다. <br class="brFixed"/>트렌드보다 한발 먼저 움직이며 시장을 설계합니다.</p>
					</div>
				</li>
				<li>
					<div class="in">
						<p class="point_txt">CO-GROWTH</p>
						<div class="icon_wrap"><span class="icon"><img src="/img/corevalue_icon03.svg" alt="동반성장"/></span></div>
						<p class="fs21">함께 성장하는 전략적 파트너십</p>
						<h4 class="fs37">동반성장</h4>
						<p class="fs19">일회성 노출이 아닌, <br/>전략적 목적에 기반한 장기적 성장을 지향합니다. <br class="brFixed"/>브랜드의 맥락을 깊이 이해하고 <br/>공간과 메시지를 정밀하게 연결해 성과를 만듭니다.</p>
					</div>
				</li>
			</ul>
		</div>
	</section>
	
	<section id="brand" class="compad ani">
		<div class="title_area basic_in">
			<h3 class="fs65">에스킴컴퍼니와 함께 <br/>먼저 도심을 사로잡은 <br/>브랜드들을 확인하세요</h3>
		</div>
		<article>
			<div class="brand_wrap brand_wrap1">
				<ul class="brand_roll brand_roll1">
					<!-- <li><p>브랜드명</p><img src="/img/brand_logo01.png" alt=""></li> -->
					
					<?
					$banner_code="main_brand1";
					$banner_sql = "select * from wiz_banner where code = '$banner_code' and isuse != 'N' order by prior, idx asc ";
					$banner_res = query($banner_sql);
					while($banner_row = sql_fetch_arr($banner_res)){
					?>
						<?php if($banner_row['de_img'] != "") {?>
							<li><img src="/twcenter/data/banner/<?=$banner_row['de_img']?>" alt="<?=$banner_row['txt1']?>"></li>
						<?php }else{?>
							<li><p><?=$banner_row['txt1']?></p><img src="/img/brand_noimg.gif" alt="<?=$banner_row['txt1']?>"></li>
						<?php }?>
					<? } ?>
				</ul>
			</div>
			<div class="brand_wrap brand_wrap2">
				<ul class="brand_roll brand_roll2">
					<?
					$banner_code="main_brand2";
					$banner_sql = "select * from wiz_banner where code = '$banner_code' and isuse != 'N' order by prior, idx asc ";
					$banner_res = query($banner_sql);
					while($banner_row = sql_fetch_arr($banner_res)){
					?>
						<?php if($banner_row['de_img'] != "") {?>
							<li><img src="/twcenter/data/banner/<?=$banner_row['de_img']?>" alt="<?=$banner_row['txt1']?>"></li>
						<?php }else{?>
							<li><p><?=$banner_row['txt1']?></p><img src="/img/brand_noimg.gif" alt="<?=$banner_row['txt1']?>"></li>
						<?php }?>
					<? } ?>
				</ul>
			</div>
			<div class="brand_wrap brand_wrap3">
				<ul class="brand_roll brand_roll3">
					<?
					$banner_code="main_brand3";
					$banner_sql = "select * from wiz_banner where code = '$banner_code' and isuse != 'N' order by prior, idx asc ";
					$banner_res = query($banner_sql);
					while($banner_row = sql_fetch_arr($banner_res)){
					?>
						<?php if($banner_row['de_img'] != "") {?>
							<li><img src="/twcenter/data/banner/<?=$banner_row['de_img']?>" alt="<?=$banner_row['txt1']?>"></li>
						<?php }else{?>
							<li><p><?=$banner_row['txt1']?></p><img src="/img/brand_noimg.gif" alt="<?=$banner_row['txt1']?>"></li>
						<?php }?>
					<? } ?>
				</ul>
			</div>
		</article>
		<div class="btn_area basic_in">
			<a href="<?php echo $location_info['portfolio'][1]['src']; ?>" class="more_btn">포트폴리오 보러가기</a>
		</div>
	</section>
	
	<section id="indicator" class="compad ani">
		<div class="indicator_bg"></div>
		<div class="basic_in">
			<div class="title_area">
				<h3 class="fs65">수많은 브랜드가 선택한 이유, <br/>경험으로 증명합니다</h3>
			</div>
			<ul class="indicator_list">
				<li>
					<span class="icon"><img src="/img/indicator_icon01.svg" alt="연간 집행 앰비언트 캠페인"></span>
					<h4 class="fs19">연간 집행 <br/>앰비언트 캠페인</h4>
					<div class="odometer_txt">
						<p id="odometer1" class="odometer fs47" data-val="<?=$mbinfo['txt1']?>">0</p>
						<p class="fs47">건<i class="plus"></i></p>
					</div>
				</li>
				<li>
					<span class="icon"><img src="/img/indicator_icon02.svg" alt="글로벌 앰비언트 전용 매체 보유"></span>
					<h4 class="fs19">글로벌 앰비언트 <br/>전용 매체 보유</h4>
					<div class="odometer_txt">
						<p id="odometer2" class="odometer fs47" data-val="<?=$mbinfo['txt2']?>">0</p>
						<p class="fs47">개<i class="plus"></i></p>
					</div>
				</li>
				<li>
					<span class="icon"><img src="/img/indicator_icon03.svg" alt="누적 캠페인 집행 국가"></span>
					<h4 class="fs19">누적 캠페인 <br/>집행 국가</h4>
					<div class="odometer_txt">
						<p id="odometer3" class="odometer fs47" data-val="<?=$mbinfo['txt3']?>">0</p>
						<p class="fs47">개국<i class="plus"></i></p>
					</div>
				</li>
				<li>
					<span class="icon"><img src="/img/indicator_icon04.svg" alt="중국어, 스페인어 등 글로벌 커뮤니케이션"></span>
					<h4 class="fs19">중국어, 스페인어 등 <br/>글로벌 커뮤니케이션</h4>
					<div class="odometer_txt">
						<p id="odometer4" class="odometer fs47" data-val="<?=$mbinfo['txt4']?>">0</p>
						<p class="fs47">개 언어권</p>
					</div>
				</li>
			</ul>
		</div>
	</section>
	
	<section id="outro" class="ani">
		<div class="outro_in">
			<div class="outro_bg" style="background-image:url('/img/contact_bg.jpg');"></div>
			<div class="outro_txt basic_in">
				<h4 class="fs37">도시를 무대로 <br/>브랜드를 주인공으로 만드는 경험</h4>
				<h3 class="fs65">에스킴컴퍼니와 시작하세요</h3>
				<div class="btn_area">
					<a href="<?php echo $location_info['contact'][1]['src']; ?>" class="more_btn mouse_wheel">상담 신청</a>
				</div>
			</div>
			<a href="#contact" class="scroll_down mouse_wheel"><span class="blind">scroll down</span><i></i><?php include $_SERVER['DOCUMENT_ROOT']."/img/scr_icon.svg";?></a>
		</div>
	</section>
	
	
	<section id="contact" class="compad">
		<article class="basic_in ani">
			<h3 class="fs80 eng ls0">CONTACT US</h3>
			<div class="contact_form">
				<?php $form_code = "contact"; include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/form.php"; // 폼메일 ?>
			</div>
		</article>
		<article class="location_cont basic_in ani">
			<div class="location_logo"><img src="/img/logo.png" alt="ASKIM"></div>
			<ul class="location_info">
				<li class="address"><?=$site_info['com_address']?></li>
				<li class="tel ls0"><?=$site_info['com_tel']?></li>
				<li class="mail ls0"><?=$site_info['site_email']?></li>
			</ul>
			<div class="location_map">
				<!-- * 카카오맵 - 지도퍼가기 -->
				<!-- 1. 지도 노드 -->
				<div id="daumRoughmapContainer1759100917713" class="root_daum_roughmap root_daum_roughmap_landing" style="width: 100%;"></div>

				<!--
					2. 설치 스크립트
					* 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
				-->
				<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

				<!-- 3. 실행 스크립트 -->
				<script charset="UTF-8">
					new daum.roughmap.Lander({
						"timestamp" : "1759100917713",
						"key" : "x4weebiadkp",
					}).render();
				</script>
			</div>
		</article>
	</section>


<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/foot.php";
?>
<? include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/popup.php"; // 팝업관리 ?>    