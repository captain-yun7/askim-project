<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$sql = "select title,linkurl,content from wiz_popup where idx = '$idx'";
$popup_info = sql_fetch($sql);

if($popup_info['linkurl']) {
	$popup_info['linkurl'] = "onclick=self.close();opener.window.open('".$popup_info['linkurl']."'); style='cursor:pointer'";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no,maximum-scale=1.0,minimum-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$popup_info['title']?></title>
<style>
@import url('/comm/css/font.css');
@import url('/comm/css/root.css');


body,p {padding:0; margin:0; font-family:var(--kor);}
table, tr, td {color:rgba(34,34,34,0.7); margin:0; padding:0; font-family:var(--kor); font-size:16px; line-height:155%;}
.logo_cont {width:100%; text-align:center; padding:10px 0;}
.title {color:var(--white); font-size:17px; padding:14px 0; font-family:var(--kor); font-weight:600; text-align:center;  background:var(--main); letter-spacing: -0.5px;}
.content {padding:20px 15px 15px 15px; vertical-align:top; color:rgba(34,34,34,0.7); letter-spacing: -0.5px;}
.content * {letter-spacing: -0.5px;}
.close {color:#f0f0f0; text-align:center; background:#222; line-height:100%; font-size:12px; padding:12px 3px; line-height: 1;  letter-spacing: -0.5px; width:35%; cursor:pointer;}
.oneDay {border-right:1px solid rgba(255,255,255,0.1); width: 65%;}
.close input {vertical-align:top; margin-left:10px;}
.close input[type='image'] {vertical-align: middle; margin:-2px 0 0 7px;}


.logo {display:flex; flex-wrap:wrap; align-items:center; justify-content:center;}
.logo img{width: 100px; height: auto;}


</style>
<script language="javascript">
<!--
function popupClose(){
  setCookie("popupDayClose<?=$idx?>", "true", 1);
  self.close();
}

function popupClose2(){
  self.close();
}

function setCookie( name, value, expiredays )
{
  var todayDate = new Date();
  todayDate.setDate( todayDate.getDate() + expiredays );
  document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
//-->
</script>
</head>
<body topmargin="0" leftmargin="0">

<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr><td class="logo_cont" colspan="2">
<div class="logo"><img src="/img/logo.png" alt="에스킴컴퍼니"></div>
</td></tr>
<tr><td class="title" colspan="2"><?=$popup_info['title']?></td></tr>
<tr><td class="content" colspan="2" height="100%" <?=$popup_info['linkurl']?>><?=$popup_info['content']?></td></tr>
	<tr>
		<td class="close oneDay" onClick="popupClose();">24시간동안 보지않음.<input type="image" src="popup_close.gif" ></td>
		<td class="close " onClick="popupClose2();">닫기<input type="image" src="popup_close.gif" ></td>
	</tr>
</table>

</body>
</html>