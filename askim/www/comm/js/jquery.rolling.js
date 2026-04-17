/*** 롤링 기능 ***/

jQuery.fn.extend({

	
	/*** 일반 갤러리형 롤링 ***/
	/*** 좌측으로 자동이동
	     화살표를 통해 좌/우측 이동 가능
		 마우스 올리면 자동롤링 중지
		 마우스 올리면 li에 over class 추가
		 ***/
	gallery_rolling:function(p_dispNumber, p_speed, p_dispTimeout)
	{
		return this.each(function(){
			var dispNumber  = p_dispNumber==null ? 4: p_dispNumber;      // 보여지는 갯수
			var speed       = p_speed==null ? 800 : p_speed;              // 한번 움직이는 시간
			var dispTimeout = p_dispTimeout==null ? 3000 : p_dispTimeout; // 한번 쉬는 시간

			function gallery_moveTo(i)
			{
				current = i;
				$gallery_ul.animate({"left":-current*li_width}, {duration:speed, queue:false});
			}

			function gallery_prev(is_auto)
			{
				// 마우스 올려져있으면 자동롤링 안함
				if(is_auto!=false && is_over)  return;

				var prev = 0 >= current ? li_count-1 : current-1;
				gallery_moveTo(prev);
			}

			function gallery_next(is_auto)
			{
				// 마우스 올려져있으면 자동롤링 안함
				if(is_auto!=false && is_over)  return;

				var next = (li_count-dispNumber) <= current ? 0 : current+1;
				gallery_moveTo(next);
			}

			var current = 0; // 현재 표시된 위치
			var is_over = false; // 마우스 올려져있는지 여부

			var $gallery      = $(this);
			var $carrousel_box= $gallery.find("> .carrousel_box");
			var $gallery_ul   = $carrousel_box.find(" > ul");
			var $gallery_li   = $gallery_ul.find(" > li").css({"float":"left"});

			var li_width = $gallery_li.eq(0).width();
			var li_height= $gallery_li.eq(0).height();
			var li_count = $gallery_li.size();

			// 배너박스
			$gallery.hover(function(){ is_over = true; }
						, function(){ is_over = false;});

			// 화살표
			$gallery.find("> .arrow_prev").click(function(){ gallery_prev(false); }).end()
					.find("> .arrow_next").click(function(){ gallery_next(false); }).end();

			// CSS
			$carrousel_box.css({"position":"absolute", "width":li_width*dispNumber, "height":li_height, "overflow":"hidden"});
			$gallery_ul.css({"position":"absolute", "top":0, "left":0, "width":li_width * li_count, "height":li_height});
			$gallery_li.hover(function(){$(this).addClass("over")}, function(){$(this).removeClass("over")});

			// Play
			window.setInterval(gallery_next, dispTimeout);
		});
	}

	
	
	/*** 앨범형 롤링 ***/
	/*** 상단으로 이동
	     화살표를 통해 상/하 이동 가능
		 마우스 올리면 자동롤링 중지
		 LI가 활성화되면 div.main_img에 이미지 보여줌
		 ***/
	, album_rolling:function(p_dispNumber, p_speed, p_dispTimeout)
	{
		return this.each(function(){
			var dispNumber  = p_dispNumber==null ? 3 : p_dispNumber;      // 보여지는 갯수
			var speed       = p_speed==null ? 400 : p_speed;              // 한번 움직이는 시간
			var dispTimeout = p_dispTimeout==null ? 3000 : p_dispTimeout; // 한번 쉬는 시간

			function album_moveTo(i)
			{
				current = i;
				if(i <= Math.floor(dispNumber/2))
					var album_pos = 0;
				else if((li_count-dispNumber) < i)
					var album_pos = li_count-dispNumber;
				else
					var album_pos = i - Math.floor(dispNumber/2);

				$album_ul
					.animate({"top":-album_pos*li_height}, {duration:speed, queue:false})
					.find("> li:eq("+i+")")
					.trigger("active");
			}

			function album_prev(is_auto)
			{
				if(is_play==false) return;

				// 마우스 올려져있으면 자동롤링 안함
				if(is_auto!=false && is_over)  return;

				var prev = 0 >= current ? li_count-1 : current-1;
				album_moveTo(prev);
			}

			function album_next(is_auto)
			{
				if(is_play==false) return;

				// 마우스 올려져있으면 자동롤링 안함
				if(is_auto!=false && is_over)  return;

				var next = (li_count-1) <= current ? 0 : current+1;
				album_moveTo(next);
			}

			var _timer	= null;
			var current	= 0; // 현재 표시된 위치
			var is_play	= false; // 롤링 여부
			var is_over	= false; // 마우스 올려져있는지 여부

			var $album		= $(this);
			var $main_img	= $album.find("div.main_img");
			var $carrousel_box= $album.find("> div.carrousel_box");
			var $album_ul	= $carrousel_box.find(" > ul");

			var li_width = $album_ul.find("> li:eq(0)").width();
			var li_height= $album_ul.find("> li:eq(0)").height();
			var li_count = $album_ul.find("> li").size();

			// 배너박스
			$album
				.bind("play", function(){
					is_play = true;
					album_moveTo(0);
					if(_timer != null)	window.clearInterval(_timer);
					_timer = window.setInterval(album_next, dispTimeout);
				})
				.bind("stop", function(){
					is_play = false;
					window.clearInterval(_timer);
					_timer = null;
				})
				.hover(function(){ is_over = true; }
					, function(){ is_over = false;})
			;

			// 기본 CSS 설정
			$carrousel_box.css({"position":"absolute", "width":li_width, "height":li_height*dispNumber, "overflow":"hidden"});
			$album_ul.css({"position":"absolute", "top":0, "left":-10000, "width":li_width, "height":li_height * li_count});
			$album_ul.css({"left":0});

			// 화살표
			$album
				.find("> .arrow_prev").click(function(){ album_prev(false); }).end()
				.find("> .arrow_next").click(function(){ album_next(false); }).end();

			// 활성화되었을 때 반응
			$album_ul.find("> li")
			.bind("active", function(){
				$this = $(this);
				$old_img = $main_img.children("div.img_box");
				title = $this.attr("title");
				if(title.length && title.length > 0)
				{
					$new_img = $('<div class="img_box"><img src="'+$this.attr("image_src")+'" /><div class="comment">'+$this.attr("title")+'</div></div>');
					$new_img.find("> div.comment").css("opacity", 0.45);
				}
				else
					$new_img = $('<div class="img_box"><img src="'+$this.attr("image_src")+'" /></div>');
				$new_img.css("opacity", 0);
				$main_img.append($new_img);

				// FadeIn 효과
				$new_img.animate({"opacity":1}, 1000);
				$old_img.animate({"opacity":0}, 1000, function(){$(this).remove();});

				// 현재 선택된거 표시
				$this.addClass("on") // 지금꺼
					 .siblings("li")
						.removeClass("on")
						.end()
				;
			})
			// 클릭했을 때
			.each(function(i){
				$(this).click(function(){
					current = i;
					$(this).trigger("active");
				});
			});
		});
	}
});
