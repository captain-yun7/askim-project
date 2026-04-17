<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$sql = "
	select wcat.prd_skin
	  from wiz_product2 as wp 
	  left join wiz_cprelation2 as wc 
	    on wp.prdcode = wc.prdcode
	  left join wiz_category2 as wcat 
	    on wc.catcode = wcat.catcode
	 where wp.prdcode='".$prdcode."'
";
$result = query($sql);
$row = sql_fetch_arr($result);

if(!empty($row['prd_skin'])) $skin_dir = "/twcenter/product2/skin/".$row['prd_skin'];

if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".$prdimg)) $prdimg = $skin_dir."/image/noimg_L.gif";
else $prdimg = "/twcenter/data/product2/".$prdimg;

?>
<html>
<head>
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<title>:: 제품 확대 ::</title>
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
	<img src="<?php echo $prdimg ?>" name="prdimg" id="container" onClick="window.close();" style="cursor:pointer" class="dragme">
</center>
</body>
</html>
