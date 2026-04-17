<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>

<!--                ### 팝업 시작 ###            -->
<script language="javascript">
<!--
function readCookie(cookiename)
{
 var Found = false;

 cookiedata = document.cookie;
 if ( cookiedata.indexOf(cookiename) >= 0 ){
   Found = true;
 }

 return Found;
}

function setCookie( name, value, expiredays )
{
 var todayDate = new Date();
 todayDate.setDate( todayDate.getDate() + expiredays );
 document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
//-->
</script>
<?

/*
$today = date('Y-m-d');
$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content from wiz_popup where isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
*/


$check_language = $_SERVER['REQUEST_URI'];
$cut_branch = explode('/',$check_language);

$today = date('Y-m-d');
$sql="";
if($cut_branch[1]=="chi"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='china' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="eng"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='usa' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="jp"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='japan' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="m" && $cut_branch[2] == ""){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='m_korea' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="m" && $cut_branch[2] == "chi"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='m_china' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="m" && $cut_branch[2] == "eng"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='m_usa' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="m" && $cut_branch[2] == "jp"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='m_japan' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else if($cut_branch[1]=="m"){
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='m_korea' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}else{
	$sql = "select idx,scroll,posi_x,posi_y,size_x,size_y,popup_type,linkurl,content,branch from wiz_popup where (branch='korea' or branch='all' ) and isuse = 'Y' and sdate <= '$today' and edate >= '$today'";
}
$result = query($sql);
while($popup_info = sql_fetch_arr($result)){

	// 스크롤
	if($popup_info['scroll'] == "Y") $popup_info['scroll'] = "yes";
	else $popup_info['scroll'] = "no";
	
	// 일반팝업
	if($popup_info['popup_type'] == "" || $popup_info['popup_type'] == "W"){
 
?>



<?php if($cut_branch[1]=="eng") {?>

<script language="javascript">
<!--
if(!readCookie("popupDayClose<?=$popup_info['idx']?>")){
	window.open("/popup/popup_eng.php?idx=<?=$popup_info['idx']?>","popup<?=$popup_info['idx']?>","height=<?=$popup_info['size_y']?>, width=<?=$popup_info['size_x']?>, menubar=no, scrollbars=<?=$popup_info['scroll']?>, resizable=no, toolbar=no, status=no, top=<?=$popup_info['posi_y']?>, left=<?=$popup_info['posi_x']?>");
}
-->
</script>

<?php }else if($cut_branch[1]=="chi") {?>
<script language="javascript">
<!--
if(!readCookie("popupDayClose<?=$popup_info['idx']?>")){
	window.open("/popup/popup_chi.php?idx=<?=$popup_info['idx']?>","popup<?=$popup_info['idx']?>","height=<?=$popup_info['size_y']?>, width=<?=$popup_info['size_x']?>, menubar=no, scrollbars=<?=$popup_info['scroll']?>, resizable=no, toolbar=no, status=no, top=<?=$popup_info['posi_y']?>, left=<?=$popup_info['posi_x']?>");
}
-->
</script>

<?php }else{?>

<script language="javascript">
<!--
if(!readCookie("popupDayClose<?=$popup_info['idx']?>")){
	window.open("/popup/popup.php?idx=<?=$popup_info['idx']?>","popup<?=$popup_info['idx']?>","height=<?=$popup_info['size_y']?>, width=<?=$popup_info['size_x']?>, menubar=no, scrollbars=<?=$popup_info['scroll']?>, resizable=no, toolbar=no, status=no, top=<?=$popup_info['posi_y']?>, left=<?=$popup_info['posi_x']?>");
}
-->
</script>
<?php }?>

<?

	// 레이어팝업
	}else{
	
	  if($cut_branch[1]=="eng"){
				if(!${"popupDayClose".$popup_info['idx']}) include $_SERVER['DOCUMENT_ROOT']."/popup/popup_layer_eng.php";
		
		} else if($cut_branch[1]=="chi"){
				if(!${"popupDayClose".$popup_info['idx']}) include $_SERVER['DOCUMENT_ROOT']."/popup/popup_layer_chi.php";
		
		} else{
				if(!${"popupDayClose".$popup_info['idx']}) include $_SERVER['DOCUMENT_ROOT']."/popup/popup_layer.php";

		}


	}
   
}

?>
<!--                ### 팝업 끝 ###            -->