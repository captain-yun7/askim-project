$(function(){
	//드롭다운 메뉴 설치 (상위 리스트, 하위 리스트)
	dropMenuNav($(".lnb_nav"), $(".drop_menu_wrap"));
	
	//해당 페이지에 일치하는 상위 메뉴 리스트 Tap 유치
	var nav = location.href;
	nav = nav.split("/");
	nav = nav.reverse();
	$(".lnb_nav li").each(function(){
		var href = $(this).find("a").attr("href");
		href = href.split("/");
		href = href[(href.length) - 2];
		if(href && href === nav[2]){
			$(this).addClass("tap_page");
		}
	});
});

/* 드롭다운 메뉴  */
var dropMenuNav = function(parent, elem){ //(상위 리스트, 하위 리스트)
	
	//메뉴 숨기기
	elem.hide(); 
	
	//이벤트 핸들러
	parent.hover(function(){elem.stop().slideDown("fast");}, function(){elem.stop().slideUp("fast");}); //상위 오버 시 유치
	elem.hover(function(){elem.stop().slideDown("fast");}, function(){elem.stop().slideUp("fast");}); //하위 오버 시 유치
	
	//상위 리스트 오버시 "tap" 클래스 추가
	parent.find("li").hover(function(){
		$(this).addClass("tap");
	}, function(){
		$(this).parent().find("li").removeClass("tap");
	});
	
	//하위 리스트 오버시 "tap"추가 + 상위 리스트  "tap" 유지
	$(".depth").hover(function(){
		var parentIndext = $(this).parent().index(); //해당 하위 리스트위 상위 리스트 인덱스 가져오기
		parent.find("li").eq(parentIndext).addClass("tap"); //tap 클래스 추가
	}, function(){
		parent.find("li").removeClass("tap"); //모두 제거
	});
	
}