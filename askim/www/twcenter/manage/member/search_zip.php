<? include_once "../../common.php"; ?>
<?
$list = get_zipcode_list($address); $search_count = count($list);
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>우편번호 검색</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--

function setAddr( zipcode1, zipcode2 , addr )
{
	opener.frm.<?=$kind?>post1.value = zipcode1;
	opener.frm.<?=$kind?>post2.value = zipcode2;
	opener.frm.<?=$kind?>address1.value = addr;

	if(opener.frm.<?=$kind?>address2 != null)
		opener.frm.<?=$kind?>address2.focus();

	self.close();
}
//-->
</script>
</head>

<body onLoad="document.frm.address.focus();">

<table width="100%" cellpadding=10 cellspacing=0><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 우편번호 검색</td>
  </tr>
</table>
<form name="frm" method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="kind" value="<?=$kind?>">
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td width="100" class="t_name">지역명</td>
    <td class="t_value">
    	<input type="text" name="address" class="input" size="20" style="ime-mode:active">
      <input type="image" src="../image/btn_search.gif"  align="absmiddle">
    </td>
  </tr>
</table>
</form>
<br>
<?
if( $address != ""){
?>
<table border=0 cellpadding=2 cellspacing=0 width=100% bgcolor=#ffffff align=center>
<?
	for ($i=0; $i<count($list); $i++) {
?>
	<tr>
	  <td width=70><font color=#2088CD><?=$list[$i][zip1]?>-<?=$list[$i][zip2]?></font></td>
	  <td><a href="" onClick="setAddr( '<? echo $list[$i][zip1] ?>' , '<? echo $list[$i][zip2] ?>' , '<? echo $list[$i][set_addr] ?>' )"><? echo $list[$i][addr] ?></a></td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor=#f0f0f0></td></tr>
<?
	}

	if($address != "" && $search_count <= 0){
?>
	<tr><td colspan=2 height=1 bgcolor=#f0f0f0></td></tr>
	<tr>
	  <td colspan="2" align="center">- 찾으시는 주소가 없습니다. 다시 입력하세요.</td>
	</tr>
	<tr><td height="1" colspan="10" background="../img/dot.gif"></td></tr>
<?
	}
?>
</table>
<?
}
?>

</td></tr></table>
</body>
</html>