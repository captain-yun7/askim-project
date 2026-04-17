<?
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/dbcon.php';
$connect = @mysql_connect($db_host, $db_user, $db_pass) or error("DB 접속시 에러가 발생했습니다.");
@mysql_select_db($db_name, $connect) or error("DB Select 에러가 발생했습니다");
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/site_info.php';
?>
<html>
<head>
<title>:: 라이센스키 입력 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="./manage/wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){
   if(frm.site_key.value == ""){
      alert("라이센스키를 입력하세요");
      frm.site_key.focus();
      return false;
   }
}

-->
</script>
</head>

<body onLoad="document.frm.site_key.focus();">
<table width="100%"  height="11" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  bgcolor="#3bc1c1">&nbsp;</td>
    <td width="222" bgcolor="#1c86cc"></td>
    <td width="2" bgcolor="#ffffff"></td>
    <td width="75" bgcolor="#AEAEAE"></td>
  </tr>
</table>
<table><tr><td height="200"></td></tr></table>
<table width="510" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit"><img src="./manage/image/ic_tit.gif" align="absmiddle">라이센스키</td>
  </tr>
  <tr><td height="5"></td></tr>
</table>
<form name="frm" action="/twcenter/install_ok.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="keyupdate">
<table width="510" align="center" border="0" cellspacing="1" cellpadding="0" class="t_style">
	<tr align="left" valign="middle">
		<td class="t_name">라이센스키</td>
		<td class="t_value" style="padding: 5px">
			<textarea name="site_key" rows="2" cols="50" class="textarea"><?=$site_info['site_key']?></textarea>
			<input type="image" src="./manage/image/btn_confirm_s.gif" align="absmiddle">
		</td>
	</tr>
</table>
</form>
<table width="510" align="center" cellspacing="1" cellpadding="0">
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<font color="red">
			- 라이센스키가 없거나 올바르지 않습니다. 정확한 라이센스키를 입력하세요.<br>
			</font>
        	- 라이센스키가 정확하지 않을 경우 설치 2주일 후 부터 관리자 기능을 사용할 수 없습니다.<br>
        	- 도메인이 변경될 경우 라이센스 키를 다시 발급받아야 합니다.<br>
        	- 도메인이 여러개인경우 한라인에 하나씩 추가할 수 있습니다.
		</td>
	</tr>
</table>

</body>
</html>