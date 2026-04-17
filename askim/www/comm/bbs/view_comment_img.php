<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$img_data = sql_fetch("SELECT * FROM wiz_comment WHERE idx='{$comment_idx}' ");
$img_path = "../../twcenter/data/comment/M".$img_data['upfile1'];
?>
<html>
<head>
<title>:: 이미지 확대 ::</title>
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
$(function(){

	$(window).load(function() {

		var strWidth;
		var strHeight;

		if(window.innerWidth && window.innerHeight && window.outerWidth && window.outerHeight) {
			strWidth = $('#container').outerWidth() + (window.outerWidth - window.innerWidth);
			strHeight = $('#container').outerHeight() + (window.outerHeight - window.innerHeight);
		} else {
			/* ie8이하에서 outerWidth속성을 지원하지 않음 */
			var strDocumentWidth = $(document).outerWidth();
			var strDocumentHeight = $(document).outerHeight();

			window.resizeTo (strDocumentWidth, strDocumentHeight);

			var strMenuWidth = strDocumentWidth - $(window).width();
			var strMenuHeight = strDocumentHeight - $(window).height();

			strWidth = $('#container').$(document).outerWidth() + strMenuWidth;
			strHeight = $('#container').$(document).outerHeight() + strMenuHeight;
		}

		window.resizeTo(strWidth, strHeight);

	});

});
</script>
</head>
<body topmargin="0" leftmargin="0">
	<img src="<?php echo $img_path ?>" id="container" name="bbsimg" onClick="self.close();" style="cursor:pointer">
</body>
</html>