<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if($type == "edit") {
	$img = explode("/" ,urldecode(base64_decode($img)));
	$img_path = "../../twcenter/data/webedit/".$img[4]."/".$img[5];
} else {
	$img_path = "../../twcenter/data/bbs/".$code."/".$img;
}
?>
<html>
<head>
<title>이미지 확대</title>
<link rel="shortcut icon" href="/img/favicon.ico" />
<style>
body {
	margin:0;
	padding:0;
	overflow:hidden;
	width:100%;
	height:100%;
}
.dragme{position:relative;}
</style>
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script language='javascript'>
<!--
/*function winResize()
{
	var Dwidth = parseInt(document.body.scrollWidth);
	var Dheight = parseInt(document.body.scrollHeight);
	var divEl = document.createElement('div');
	divEl.style.position = 'absolute';
	divEl.style.left     = '0px';
	divEl.style.top      = '0px';
	divEl.style.width    = '100%';
	divEl.style.height   = '100%';

	document.body.appendChild(divEl);
	window.resizeBy(Dwidth-divEl.offsetWidth, Dheight-divEl.offsetHeight);
	document.body.removeChild(divEl);
}

function selfClose(){
	window.close();
}*/
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

//-->
</script>
</head>

<body>
<center>
	<img src="<?php echo $img_path ?>" name="bbsimg" id="container" onClick="window.close();" style="cursor:pointer" class="dragme">
</center>
</body>
</html>