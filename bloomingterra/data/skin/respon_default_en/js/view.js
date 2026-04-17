$(document).ready(function(){
	$(".view_visual .view_thumb").addClass("on");

	$(".view_visual .share_btn").on("click", function(){
		var url = $(location).attr("href");
		var textarea = document.createElement("textarea");
		document.body.appendChild(textarea);
		url = window.document.location.href;
		textarea.value = url;
		textarea.select();
		document.execCommand("copy");
		document.body.removeChild(textarea);
		alert("Link has been copied.")
		return false;
	});
	var prodSwiper = new Swiper(".prodSwiper", {
		loop : true,
		loopAdditionalSlides : 1,
		autoplay : {
		  delay : 3000,   // 시간 설정
		  disableOnInteraction : false,  // false로 설정하면 스와이프 후 자동 재생이 비활성화 되지 않음
		},
		speed:700,
		pagination: {
			el: ".prodSwiper .swiper-pagination",
			clickable: true,
        },
		
	})
	$(window).resize(function(){
		setTimeout(function(){
			prodSwiper.update();
		},100)
	});
});