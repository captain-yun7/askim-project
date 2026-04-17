<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$goUrl = "prd_cat.php";
if(empty($catcode)) $catcode = "00000000";
?>
<script>
$(function () {

	$("#subCategory").load("ajax_sub_category.php?catcode=<?php echo $catcode ?>", function(data) {
		//console.log(data);
	});

	var tree_menu	= $('#tree_menu');
	var tree_plus	= '/twcenter/manage/image/ic_plus.gif';
	var tree_minus	= '/twcenter/manage/image/ic_minus.gif';
	var folder_open	   = '<img src="/twcenter/manage/image/ic_folder_o.gif" style="vertical-align:middle; padding:0 0 0 8px;">';
	var folder_close   = '<img src="/twcenter/manage/image/ic_folder_c.gif" style="vertical-align:middle; padding:0 0 0 8px;">';
	var status = 'close';
	
	tree_menu.find('li:has("ul")').prepend('<a href="#" class="controller"><img src="' + tree_plus + '" style="padding:0 0 0 5px">22</a>');
	tree_menu.find('li:last-child').addClass('end');

	$('.controller').click(function(){

		var temp_el = $(this).parent().find('>ul');
		var catcode = $(this).parent().find('span').attr('id');


		if (temp_el.css('display') == 'none'){

			temp_el.slideDown(100);
			$(this).find('img').attr('src', tree_minus);
			$("span#"+catcode).html(folder_open);
			return false;


		} else {

			temp_el.slideUp(100);
			$(this).find('img').attr('src', tree_plus);
			$("span#"+catcode).html(folder_close);
			return false;

		}

	});

	function tree_init(status){
		if (status == 'close'){
			tree_menu.find('ul').hide();
			$('a.controller').find('img').attr('src', tree_plus);
		} else if (status == 'open'){
			tree_menu.find('ul').show();
			$('a.controller').find('img').attr('src', tree_minus);
		}
	}
	tree_init(status);

});

</script>
<div class="manage_sort_left">
	<div class="manage_sort_tree">
		<div id="subCategory"></div>
	</div>
</div>

