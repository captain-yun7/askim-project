//GNB fixed
	var didScroll; 
	var lastScrollTop = 0; 
	var delta = 5; 
	var navbarHeight = $('header').outerHeight(); 

	$(window).scroll(function(event){ 
		didScroll = true;
	}); 

	setInterval(function() { 
		if (didScroll) { 
			hasScrolled(); 
			didScroll = false; 
		} 
	}, 250); 

	function hasScrolled() { 
		var st = $(this).scrollTop(); 
		// Make sure they scroll more than delta 
		if(Math.abs(lastScrollTop - st) <= delta) return; 
		// If they scrolled down and are past the navbar, add class .nav-up. 
		// This is necessary so you never see what is "behind" the navbar. 
		if (st > lastScrollTop && st > navbarHeight){ 
			// Scroll Down 
			$('.header').removeClass('fixed').addClass('nofixed'); 
		} else if (st < 100){ 
			$('.header').removeClass('fixed').removeClass('nofixed'); 
		} else { 
			// Scroll Up 
			if(st + $(window).height() < $(document).height()) { 
				$('.header').removeClass('nofixed').addClass('fixed'); 
			}
		} 
		lastScrollTop = st;
	}
//GNB fixed


// 스크롤 스피드 
$(window).load(function(){
	$("a.mouse_wheel").mPageScroll2id({
		scrollSpeed:900
	});
});


$(document).ready(function(){
	$('.menuBtn').click(function(){
		$("#menu").fadeIn(300);
		$(".wrapper").addClass("sitemap_show");
		$("body").addClass("noScroll");
	});
	$('.menu_close, #menu ul li a').click(function(){
		$("#menu").fadeOut(300);
		$(".wrapper").removeClass("sitemap_show");
		$("body").removeClass("noScroll");
	});
	
});


