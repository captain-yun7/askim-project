

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