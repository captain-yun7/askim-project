$(document).ready(function(){
			
	// 메인비주얼 영상
	$('.jarallax').jarallax({
		speed:0,
		videoStartTime:0,
		videoVolume:0,
		videoLoop:true,
		videoPlayOnlyVisible:false,
		videoLazyLoading:false,
	});

	$('.visual_bg').slick({
		infinite: true,
		speed: 500,
		arrows: false,
		fade: true,
		dots: true,
		draggable: true,
		appendDots : $('.custom-dots'),
		cssEase: 'linear',       
		pauseOnFocus: false,
		pauseOnHover: false,         
		autoplay: true,
		autoplaySpeed: 6000,
	});	
		
	$('.visual_bg .slick-active').addClass('action'); 
	$('.visual_bg').on('afterChange', function(event, slick, currentSlide, nextSlide){
		$('.slick-slide').removeClass('action');
		setTimeout(function (){ 
			$('.slick-active').addClass('action'); 
		});
	});
	$('.visual_bg').on('init', function(event,slick){
		setTimeout(function(){ 
			$('.slick-active').addClass('action'); 
		});
	});								
	$('.v_play').click(function(){
		$('.visual_bg').slick('slickPause');
		$('.visual_bg').addClass('pause');
	}); 
	$('.v_stop').click(function(){
		$('.visual_bg').slick('slickPlay');
		$('.visual_bg').removeClass('pause');
	});					



});


$(window).on('load', function () {
	setFlowBanner('.brand_wrap1', '.brand_roll1', 60, 'flowRolling');
	setFlowBanner('.brand_wrap2', '.brand_roll2', 60, 'flowRolling2');
	setFlowBanner('.brand_wrap3', '.brand_roll3', 60, 'flowRolling');
});

function setFlowBanner(wrapSelector, listSelector, speed, animationName) {
const $wrap = $(wrapSelector);
let $list = $(listSelector);
let $clone = $list.clone();
let wrapWidth = '';
let listWidth = '';

$wrap.append($clone);
initBanner();

// 반응형 대응
let oldWChk = getDeviceType();
$(window).on('resize', function () {
	let newWChk = getDeviceType();
	if (newWChk !== oldWChk) {
		oldWChk = newWChk;
		initBanner();
	}
});

// 배너 롤링 실행 함수
function initBanner() {
	if (wrapWidth !== '') {
		$wrap.find(listSelector).css('animation', 'none');
		$wrap.find(listSelector).slice(2).remove();
	}
	wrapWidth = $wrap.width();
	listWidth = $list.width();

	if (listWidth < wrapWidth) {
		const listCount = Math.ceil(wrapWidth * 2 / listWidth);
		for (let i = 2; i < listCount; i++) {
			let $cloned = $clone.clone();
			$wrap.append($cloned);
		}
	}

	$wrap.find(listSelector).css({
		'animation': `${listWidth / speed}s linear infinite ${animationName}`
	});
}

// 일시정지 / 재생
$wrap
	.on('mouseleave', function () {
		$wrap.find(listSelector).css('animation-play-state', 'running');
	});
}

// 현재 디바이스 타입 반환
function getDeviceType() {
	const w = window.innerWidth;
	if (w > 1501) return 'pc';
	else if (w > 681) return 'ta';
	else return 'mo';
}








let odometerTriggered = false; // 한 번만 실행하도록 플래그

$(window).scroll(function() {
    const odometers = $(".odometer");

    if($("#indicator").hasClass("action")) {
        if (!odometerTriggered) {
            odometers.each(function(index) {
                $(this).text($("#odometer" + (index + 1)).data("val"));
            });
            $(".plus").addClass("active");
            odometerTriggered = true;
        }
    } else {
        if (odometerTriggered) {
            odometers.text(0);
            $(".plus").removeClass("active");
            odometerTriggered = false;
        }
    }
});



(function($){
	$(window).on('scroll', function() {
		$('.ani').each(function(index, elem) {
			if ($(window).scrollTop() > $(elem).offset().top - ($(window).height() / 2)) {
				var $this = $(this);
				$this.addClass("action");
			}	
			if ($(window).scrollTop() > $(elem).offset().top - ($(window).height() / 2)) {
				var $this = $(this);
				$this.addClass("action");
			}else{
				var $this = $(this);
				$this.removeClass("action");
			}	
		});
	});
})(jQuery);








$(document).ready(function(){
	
	
	gsap.registerPlugin(ScrollTrigger);

	// 타임라인
	let tl = gsap.timeline({
	  scrollTrigger: {
		trigger: ".slogan_in",
		start: "top top",
		end: "+=300%",
		scrub: 2,
		pin: true,
		anticipatePin: 1,
		invalidateOnRefresh: true,
		pinSpacing: true,
		//markers: true
	  }
	});
	tl.fromTo(".slogan_bg",
	  { opacity: 0.4 },
	  { opacity: 1, ease: "power1.out", duration: 1 }
	)
	.fromTo(".step1", 
	  {opacity: 0, y: 60}, 
	  {opacity: 1, y: 0, duration: 1}
	)
		
	.to(".step1", 
	  {opacity: 0, y: -60, duration: 1}
	)
		
	.fromTo(".step2", 
	  {opacity: 0, y: 60}, 
	  {opacity: 1, y: 0, duration: 1}
	)
		
	.fromTo(".step2 .highlight", 
	  {width: "0"}, 
	  {width: "100%", duration: 1, stagger: 0.4, ease: "power2.out"}
	);
		
	
	

	let tl2 = gsap.timeline({
	  scrollTrigger: {
		trigger: ".outro_in",
		start: "top top",
		end: "+=100%",
		scrub: 2,
		pin: true,
		anticipatePin: 1,
		invalidateOnRefresh: true,
		pinSpacing: true,
	  }
	})
	tl2.fromTo(".outro_bg",
	  { opacity: 0.4 },
	  { opacity: 1, ease: "power1.out", duration: 2 }
	)
	.fromTo(".outro_txt .fs37", 
	  {opacity: 0, y: 60}, 
	  {opacity: 1, y: 0, ease: "power1.out", duration: 1.8}
	)
	.fromTo(".outro_txt .fs65", 
	  {opacity: 0, y: 40}, 
	  {opacity: 1, y: 0, ease: "power1.out", duration: 1.6}
	)
	.fromTo(".outro_txt .btn_area", 
	  {opacity: 0, y: 30}, 
	  {opacity: 1, y: 0, ease: "power1.out", duration: 1.4}
	);
	  
	  
	  
	window.addEventListener("resize", () => {
	  ScrollTrigger.refresh();
	});

});








(function($){
  $(window).on('scroll', function() {
    var scrollTop = $(window).scrollTop();
    var windowHeight = $(window).height();

    $('.usp_in').each(function() {
      var $this = $(this);
      var elemTop = $this.offset().top;
      var elemBottom = elemTop + $this.outerHeight();

      // 시작/끝 위치 비율 지정
      var startPoint = scrollTop + windowHeight * 0.2; // 화면 하단
      var endPoint = scrollTop + windowHeight * 0.6;   // 화면 상단

      // startPoint ~ endPoint 범위 안에 있으면 action
      if (startPoint >= elemTop && endPoint <= elemBottom) {
        $this.addClass('action');
      } else {
        $this.removeClass('action');
      }
    });
  });

  // 초기 스크롤 위치 체크
  $(window).trigger('scroll');
})(jQuery);



$(function() {
  // 스크롤 시 USP 아이템 활성화 및 다크모드 처리
  $(window).on('scroll', function() {
    var scrollTop = $(window).scrollTop();
    var windowHeight = $(window).height();
    var darkModeOn = false; // 다크모드 여부 체크

    $('.usp-item').each(function() {
      var $this = $(this);
      var elemTop = $this.offset().top;
      var elemBottom = elemTop + $this.outerHeight();

      // 화면 중앙 기준으로 활성화
      var viewportMiddle = scrollTop + windowHeight / 2;

      if (viewportMiddle >= elemTop && viewportMiddle <= elemBottom) {
        $this.addClass('active');

        // change_cont 클래스가 있으면 다크모드 활성화
        if ($this.hasClass('change_cont')) {
          darkModeOn = true;
        }
      } else {
        $this.removeClass('active');
      }
    });

    // body 다크모드 토글
    $('body').toggleClass('darkmode', darkModeOn);
  });

  // 초기 스크롤 위치에서도 활성화 체크
  $(window).trigger('scroll');
});


$(function() {
  $(window).on('scroll resize', function() {
    var scrollTop = $(window).scrollTop();
    var windowHeight = $(window).height();
    var windowCenter = scrollTop + (windowHeight / 2);

    $('.usp_style > dl').each(function() {
      var $this = $(this);
      var elemTop = $this.offset().top;
      var elemHeight = $this.outerHeight();
      var elemCenter = elemTop + (elemHeight / 2);

      // 요소의 중앙이 화면 중앙 근처에 있으면 addClass
      if (Math.abs(windowCenter - elemCenter) < (elemHeight / 2)) {
        $this.addClass('active');
      } else {
        $this.removeClass('active');
      }
    });
  });

  // 최초 로드시 실행
  $(window).trigger('scroll');
});






