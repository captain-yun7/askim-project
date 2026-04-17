$(document).ready(function(){
	setTimeout(function(){
		var gallMasonry = $(".gallery_list").masonry({
			itemSelector: ".gallery_list > li",
			columnWidth: ".gallery_list > li",
			isAnimated: true,
			horizontalOrder: true,
			resize: true,
			transitionDuration: "0.4s",
		});
	},100);
	$(window).resize(function(){
		setTimeout(function(){
			var gallMasonry = $(".gallery_list").masonry({
				itemSelector: ".gallery_list > li",
				columnWidth: ".gallery_list > li",
				isAnimated: true,
				horizontalOrder: true,
				resize: true,
				transitionDuration: "0.4s",
			});
		},100);
	});
	$(".gallery_list > li:lt(9)").addClass("on");
	var tirgger = true;
	$(window).scroll(function(){
		var gallHei = $(window).height();
		var gallBtm = $(".gallery_list").get(0).getBoundingClientRect().bottom;
		var onLenght = $(".gallery_list > li.on").length;

		if(`${gallHei - gallBtm}` > 0 && tirgger === true){
			tirgger = false;
			$(".gallery_list > li").slice(`${onLenght}`,`${onLenght+9}`).addClass("on");
			setTimeout(function(){
				var gallMasonry = $(".gallery_list").masonry({
					itemSelector: ".gallery_list > li",
					columnWidth: ".gallery_list > li",
					isAnimated: true,
					horizontalOrder: true,
					resize: true,
					transitionDuration: "0.4s",
				});
			},100);
		} else {
			setTimeout(function(){
				tirgger = true;
			},2000);
		}
	});
});