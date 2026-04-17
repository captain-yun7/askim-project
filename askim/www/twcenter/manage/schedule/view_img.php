<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
?>
<html>
<head>
<title></title>
<script language="javascript">
<!--
function resize() 
{ 
	var p_height, p_width; 
	p_width = document.prdimg.width+27; 
	p_height = document.prdimg.height+52; 
	self.resizeTo(p_width, p_height); 
} 
-->
</script>
</head>

<body topmargin="0" leftmargin="0" onLoad="resize()">
<img src="../../data/bbs/<?=$code?>/<?=$img?>" name="prdimg" border="0" onClick="self.close()" style="cursor:hand"></td>
</body>
</html>