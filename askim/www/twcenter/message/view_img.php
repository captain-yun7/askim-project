<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
?>
<html>
<head>
<title>:: 이미지 확대 ::</title>
<script language="javascript">
<!--

var margin_width = 12;
var margin_height = 38;

if(new RegExp(/MSIE/).test(navigator.userAgent)){

  if(navigator.appName.charAt(0) == "M" &&
  	navigator.appVersion.charAt(0) == 4)
  {
  		if(navigator.appVersion.indexOf("MSIE 7") != -1) {				// IE 7
	 		margin_height = margin_height + 44;
  		} else if(navigator.appVersion.indexOf("MSIE 8") != -1) {	// IE 8
	 		margin_height = margin_height + 44;
  		} else {
  		}
	}
}

function resize() 
{ 
	var p_height, p_width; 
	p_width = document.prdimg.width+margin_width; 
	p_height = document.prdimg.height+margin_height; 
	self.resizeTo(p_width, p_height); 
} 
-->
</script>
</head>

<body topmargin="0" leftmargin="0" onLoad="resize();">
<img src="../data/message/<?=$se_id?>/<?=$img?>" name="prdimg" border="0" onClick="self.close()" style="cursor:hand"></td>
</body>
</html>