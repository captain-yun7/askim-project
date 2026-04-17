(function($){

	$(function() {

		$("input[type=file]").bind('change', function (e) {

			//var max_size = $("#max_size").val();

			if( !$(this).val() ) return;
			 
			var f = this.files[0];

			var size = f.size || f.fileSize;
			//console.log(size);
			var limit = 20000000;

			if( size > limit )
			{
				alert( '파일은 20MB 이하 파일만 업로드 가능합니다.' );
				$(this).val('');
				return;
			}
						 
			$(this).parent().find('input[type=text]').val($(this).val());

		});

	});

})(jQuery);
