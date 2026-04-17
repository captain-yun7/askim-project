<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/outline/nav.html 000006099 */ 
$TPL_cfg_menu_1=empty($TPL_VAR["cfg_menu"])||!is_array($TPL_VAR["cfg_menu"])?0:count($TPL_VAR["cfg_menu"]);?>
<div id="header" class="">
	<div class="header_cont">
		<h1 class="hd_logo"><a href="/" title="Logo"><?php echo $TPL_VAR["cfg_site"]["compName"]?></a></h1>
<?php if(isset($TPL_VAR["cfg_menu"])){?>
		<div class="hd_right">
			<ul class="hd_lnb">
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="index_"){?>
				<li><a href="" id="about_btn">ABOUT</a></li>
<?php }else{?>
				<li><a href="/" id="about_btn">ABOUT</a></li>
<?php }?>
<?php if($TPL_cfg_menu_1){foreach($TPL_VAR["cfg_menu"] as $TPL_V1){?>
<?php if($TPL_V1["use"]=='y'){?>
				<li class="<?php if($TPL_VAR["on"]!=''){?><?php if($TPL_VAR["on"]==$TPL_V1["name"]){?>on <?php }?><?php }else{?><?php if($TPL_VAR["lm"]["name"]==$TPL_V1["name"]){?>on <?php }?><?php }?><?php if(strpos($TPL_V1["url"],'/goods/')> - 1){?><?php if(strpos($TPL_V1["url"],substr($TPL_VAR["req"]["cate"], 0, 3))> - 1){?> on<?php }?><?php }?>">
					<a href="<?php echo $TPL_V1["url"]?>"><?php echo $TPL_V1["name"]?></a>
<?php }else{?>
					<li class="hd_lnb_dep1_li"><a href="<?php echo $TPL_V1["url"]?>" class="dep1_a"><?php echo $TPL_V1["name"]?></a><!-- 하위메뉴 없을 때 -->
<?php }?>
				</li>
<?php }}?>
<?php }?>
			</ul>
			<dl class="multi_lang dn">
				<dt><span>EN</span></dt>
				<dd>
<?php if($TPL_VAR["multilingual"]){?>
					<ul class="hd_lang">
<?php if(is_array($TPL_R1=$TPL_VAR["cfg_site"]['languagelink'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<li><a href="/?language=<?php echo $TPL_K1?>" onclick=""><?php if($TPL_K1=='kor'){?>KR<?php }else{?>EN<?php }?></a></li>
<?php }}?>
					</ul>
<?php }?>
				</dd>
			</dl>
			<a class="menu-trigger" href="#"><span></span><span></span><span></span></a>
		</div>
	</div>
</div>
<aside id="aside">
	<div class="aside_box">
<?php if(isset($TPL_VAR["cfg_menu"])){?>
		<ul class="slide menu slidemenu">
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="index_"){?>
			<li><a href="" id="about_btn">ABOUT</a></li>
<?php }else{?>
			<li><a href="/" id="about_btn">ABOUT</a></li>
<?php }?>
<?php if($TPL_cfg_menu_1){foreach($TPL_VAR["cfg_menu"] as $TPL_V1){?>
<?php if($TPL_V1["use"]=='y'){?>
			<li class="depth1_li"><a href="<?php echo $TPL_V1["url"]?>" class="depth1_a"><?php echo $TPL_V1["name"]?></a></li>
<?php }?>
<?php }}?>
		</ul>
<?php }?>
	</div>
</aside><!-- #aside 상단영역 -->
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="index_"){?>
<script>
$(function() {
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? true : false;
		if(!isMobile){
			scroller = ScrollSmoother.create({
				smooth: 2,
				//effects: true,
				//normalizeScroll : true
			});
		}
});
</script>
<?php }?>
<script>
$(function() {
	//lang
	$('.multi_lang dt').click(function(){
		$(this).toggleClass('on').next('dd').stop().slideToggle('slow');
	});
	$(document).mouseup(function (e){
		//닫기
		var langDD = $('.multi_lang dt');
		if(langDD.has(e.target).length === 0){
			langDD.removeClass("on").next("dd").stop().slideUp();
		}
	});

	var itval;
	// aside close
	function aside_close() {
		$('#aside').removeClass('on');
		clearTimeout(itval);
		$('#header').removeClass('hd_aside');
		itval = setTimeout(function () {
			$('body').css({'overflow':'inherit','height':'auto'});
			
		}, 800);
	}
	
	// aside open
	function aside_open() {
		$('#aside').addClass('on');
		$('#header').addClass('hd_aside');
		clearTimeout(itval);
		$('body').css({'overflow':'hidden','height':'100%'});
	}
	
	// aside on/off
	$('.menu-trigger, .btn_aside_close').each(function(e){
		$(this).on('click', function(e){
			e.preventDefault();
			$('.menu-trigger').toggleClass('active-1');
			if ($('#aside').hasClass('on')) {
				aside_close();
			} else {
				aside_open();
			}
		});
		$('.aside_bg').on('click', function(){
			$('.menu-trigger').removeClass('active-1');
			aside_close();
		});
	});
	
	//카테고리 메뉴
	$("li.group_tit .icons").on("click", function(e) {
		//e.preventDefault();
		$(this).parent().siblings().children().next().next().slideUp(function() {
			$(this).siblings("a").removeClass("on");
		});
		$(this).parent().addClass("on");
		$(this).siblings("ul").slideToggle(function() {
			if($(this).is(":visible") == false) $(this).parent("li").removeClass("on");
		});
		if($(this).parent().closest('li').find('ul').children().length == 0) {
			return true;	
		} else {
			return false;
		}
	});
});
</script>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!="index_"){?>
<script>
$(document)	.ready(function(){
	// header on
	if ( $(this).scrollTop() > 1 ) {
		$('#header').addClass('on');
	} else {
		$('#header').removeClass('on');
	}
	$(window).scroll(function(){
		if ( $(this).scrollTop() > 1 ) {
			$('#header').addClass('on');
		} else {
			$('#header').removeClass('on');
		}
	});
});
</script>
<?php }else{?>
<script>
$(document)	.ready(function(){
	var winW = $(window).width();
	if(winW < 860){
		// header on
		if ( $(this).scrollTop() > 1 ) {
			$('#header').addClass('on');
		} else {
			$('#header').removeClass('on');
		}

		$(window).scroll(function(){
			if ( $(this).scrollTop() > 1 ) {
				$('#header').addClass('on');
			} else {
				$('#header').removeClass('on');
			}
		});
	}else{
		$('#header').removeClass('on');
	}

	$(window).resize(function(){
		var winW = $(window).width();
		if(winW < 860){
			// header on
			if ( $(this).scrollTop() > 1 ) {
				$('#header').addClass('on');
			} else {
				$('#header').removeClass('on');
			}
			$(window).scroll(function(){
				if ( $(this).scrollTop() > 1 ) {
					$('#header').addClass('on');
				} else {
					$('#header').removeClass('on');
				}
			});
		}else if (winW > 860){
			$('#header').removeClass('on');
			$(window).scroll(function(){
				if ( $(this).scrollTop() > 1 ) {
					$('#header').removeClass('on');
				} else {
					$('#header').removeClass('on');
				}
			});
		}
	});
	
});
</script>
<?php }?>