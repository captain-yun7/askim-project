/***********************************************
	@ Ready
***********************************************/
$(function(){
	// @ Category 애니메이션
	$('.sitemap').click(function(){
		$('#wrapper').css('position' , 'fixed').stop().animate({ 'left' : '200px' }, 200 ,'easeOutExpo');
		$('#navCategory').stop().animate({ 'width' : '200px' }, 200 ,'easeOutExpo' );		
		$('#blockLay').show().fadeTo('fast',0);
		mobileBottomBar(true);
	});
	$('#blockLay').click(function(){
		$('#wrapper').css('position' , 'static').stop().animate({ 'left' : '0px' }, 200 ,'easeOutExpo');
		$('#navCategory').stop().animate({ 'width' : '0px' }, 200 ,'easeOutExpo');		
		$('#blockLay').hide();
		$('#footMenu').stop(true).slideUp(200);
		mobileBottomBar(false);
	});

	$('#navCategory > ul > li > a').click(function(){
		$('#navCategory > ul > li').removeClass('on').find('> ul').slideUp();
		$(this).parent().addClass('on').find('ul').slideDown();
	});	
});
