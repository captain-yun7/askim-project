$(document).ready(function(){

	function init(){
		wrapHeight();// ***** wrap 높이지정 스크립트 *****
		naviScroll();// ***** 네비 스크롤 스크립트 *****
		//naviClick();// ***** 메인 네비 클릭 방지 *****
		naviSlide();// ***** 네비 슬라이드 스크립트 *****
	}

	function wrapHeight(){
		var windowHeight = $(window).height();
		var innerHeight = $(".wrap_inner").height();

		if(innerHeight < windowHeight)$(".wrap").css("height",windowHeight);

		$(window).bind("resize", function(){
			var windowHeight = $(window).height();
			var innerHeight = $(".wrap_inner").height();

			if(innerHeight < windowHeight)
			{
				$(".wrap").css("height",windowHeight);
			} else{
				$(".wrap").css("height","auto");
			}

		});
	}



	function naviScroll(){
		$("#left").mCustomScrollbar({
			autoHideScrollbar:true,
			theme:"light-thin"
		});
	}


	
	function naviClick(){
		$(".hd_lnb").find("a").bind("click", function(e){
			e.preventDefault();
		});
	}



	function naviSlide(){
		var autoTimer;
		var counter = 0;
		var counterTimer = 300;
		var mainMenu = $(".hd_lnb > li.has_child");
		var realMenu = $(".hd_all_sub");
		var bg = $(".hd_all_bg");


		//처음에 안보이기
		realMenu.css("left","0px");
		//$(".hd_all_sub > ul").css("display","none")
		//bg.css("left", "0");
		bg.css({
			"left":"0",
			"display":"none"
		});

		menuSetting();
		subOver();

		function menuSetting()
		{
			//menu
			mainMenu.bind({
				mouseover : function(){
					var _this = $(this).index();
					clearTimeOutHandler();
					buttonOver(_this);
					bgOpen();
				},
				focusin : function(){
					var _this = $(this).index();
					clearTimeOutHandler();
					buttonOver(_this);
					bgOpen();
				},
				focusout : function(){
					setTimeOutHandler()
				},
				mouseout : function(){
					setTimeOutHandler();
				},
				click : function(){
					
				}
			});

			//서브메뉴		
			bg.bind({
				mouseover : function(){
					clearTimeOutHandler();
					bgOpen();
				},
				mouseout : function(){
					setTimeOutHandler();
				},
				click : function(){

				}
			});

			//키보드 접근
			$(".hd_all_sub").find("ul li:first-child a").bind("focus", function(){
				bg.css("display","block").css("left","230px");
				//realMenu.css("display","block").css("left","230px");
			});

			$(".hd_all_sub").find("ul li:last-child a").bind("blur", function(){
				bg.css("left","0px").css("display","none");
				//realMenu.css("left","0px").css("display","none");
			});

			$(".hd_all_sub > li").each(function(){
				var sub_num = $(this).index();
				
					$(".hd_all_sub > li:eq("+sub_num+")").find("ul li:first a").bind("focus", function(){
						if(realMenu.position().left == -16){ //포커스 & 키보드 중복 방지
							$(".hd_all_sub > li:eq("+sub_num+")").css("left","230px");
						}
					});

					$(".hd_all_sub > li:eq("+sub_num+")").find("ul li:last a").bind("blur", function(){
						$(".hd_all_sub > li:eq("+sub_num+")").css("left","80px")
					});
			});
		};

		function buttonOver(idx)
		{
			$(".hd_lnb > li > a").removeClass("on");
			$(".hd_lnb > li:eq("+idx+")").find(".dep1_a").addClass("on");

			//서브메뉴 다 가림
			$(".hd_all_sub").css("display","none")

			//선택한 메뉴만 보임
			$(".hd_all_sub:eq("+idx+")").css("display","block")

			//서브메뉴 이동
			realMenu.stop().animate({"left":"230px"}, 300, function(){
				realMenu.css("z-index","1010");
			});
		};

		function buttonOut()
		{
			realMenu.css("z-index","990").stop().animate({"left":"-16px"}, 300, function(){ //서브 닫힌 후 오버효과 사라지기
				mainMenu.find("a").removeClass("on");
				realMenu.find("a").removeClass("on");
				$(".hd_all_sub > ul").css("display","block");
			});
		};

		//서브배경 한번만 나오기
		function bgOpen()
		{
			if(bg.css("display") == "none")
			{
				bg.css("display","block");
				bg.stop().animate({"left":"230px"}, 300);
			};
		};

		function bgOut()
		{
			if(bg.css("display") == "block")
			{
				bg.stop().animate({"left":"0px"}, 300,function(){
					bg.css("display","none");
				});
			};
		};
		
		//서브메뉴 오버효과
		function subOver()
		{
			realMenu.find("a").bind("mouseover focus", function(){
				realMenu.find("a").removeClass("on");
				$(this).addClass("on");
			});
		}

		function timeHandler()
		{
			buttonOut();
			bgOut();
		};

		function clearTimeOutHandler()
		{
			clearTimeout(autoTimer);
		};

		function setTimeOutHandler()
		{
			autoTimer = setTimeout(timeHandler,counterTimer);
		};
	}



	init();

});
