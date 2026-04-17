$(function(){
	var tree_menu	= $('#tree_menu');
	var TREE_OPEN	= '/admin/manage/image/ic_plus.gif';
	var TREE_CLOSE	= '/admin/manage/image/ic_minus.gif';

	var FOLDER_OPEN	   = '<img src="/admin/manage/image/ic_folder_o.gif" style="vertical-align:middle">';
	var FOLDER_CLOSE   = '<img src="/admin/manage/image/ic_folder_c.gif" style="vertical-align:middle">';

	tree_menu.find('li:has("ul")').prepend('<a href="#" class="controller"><img src="' + TREE_CLOSE + '" /></a> ');
	tree_menu.find('li:last-child').addClass('end');

	$('.controller').click(function(){
		var temp_el = $(this).parent().find('>ul');
		var temp_el_vv = $(this).parent().find('span').attr('id');

		if (temp_el.css('display') == 'none'){
			temp_el.slideDown(100);
			$(this).find('img').attr('src', TREE_CLOSE);
			$("span#"+temp_el_vv).html(FOLDER_OPEN);
			return false;
		} else {
			temp_el.slideUp(100);
			$(this).find('img').attr('src', TREE_OPEN);
			$("span#"+temp_el_vv).html(FOLDER_CLOSE);
			return false;
		}
	});

	function tree_init(status){
		if (status == 'close'){
			tree_menu.find('ul').hide();
			$('a.controller').find('img').attr('src', TREE_OPEN);
		} else if (status == 'open'){
			tree_menu.find('ul').show();
			$('a.controller').find('img').attr('src', TREE_CLOSE);
		}
	}
	tree_init('open');

});

function moveCode(catcode){
	$.ajax({
		type:"post"
		, async: false
		, url:  "/admin/manage/product/prd_category.php"
		, data : {"catcode":catcode}
		, success: function(data) {
			$("#PrdCategory").html(data);
		}	
		, error: function(){
		}
	});
	return false;
}

function moveCategory(mode,posi,catcode,depthno){
	$.ajax({
		type:"post"
		, async: false
		, url:  "/admin/manage/product/category_save.php"
		, data : {"mode":mode,"posi":posi,"catcode":catcode,"depthno":depthno}
		, success: function(data) {
			var result = data.split("|");
			if(result[0] == "ok"){
				document.location.href = "/admin/manage/product/prd_category.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3];
			}
		}	
		, error: function(){
		}
	});
	return false;
}
