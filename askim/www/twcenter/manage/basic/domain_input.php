<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?
if($submode == ""){

   $submode = "insert";

}else if($submode == "update"){

   $sql = "select * from wiz_otherinfo where idx = '$idx'";
   $result = query($sql) or error("sql error");
   $row = sql_fetch_arr($result);

}
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>도메인 정보</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
   if(frm.info01.value == ""){
      alert("도메인을 입력하세요");
      frm.info01.focus();
      return false;
   }
}
//-->
</script>
</head>

<body>
<table width="100%"cellpadding=10 cellspacing=0><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 도메인 정보</td>
  </tr>
</table>
<form name="frm" method="post" action="site_save.php" onSubmit="return inputCheck(this)">
<input type="hidden" name="mode" value="domain">
<input type="hidden" name="submode" value="<?=$submode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%"cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td width="100" class="t_name">도메인</td>
    <td class="t_value"><input type="text" name="info01" value="<?=$row['info01']?>" class="input" size="25"></td>
  </tr>
  <tr>
    <td class="t_name">구입사이트</td>
    <td class="t_value"><input type="text" name="info02" value="<?=$row['info02']?>" class="input" size="25"></td>
  </tr>
  <tr>
    <td class="t_name">아이디</td>
    <td class="t_value"><input type="text" name="info03" value="<?=$row['info03']?>" class="input" size="25"></td>
  </tr>
  <tr>
    <td class="t_name">비밀번호</td>
    <td class="t_value"><input type="password" name="info04" value="" class="input" size="25"></td>
  </tr>
  <tr>
    <td class="t_name">만료일</td>
    <td class="t_value"><input type="text" name="info05" value="<?=$row['info05']?>" class="input" size="25"></td>
  </tr>
</table>
<table align="center">
  <tr><td height="5"></td></tr>
  <tr>
    <td>
    	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
    	<img src="../image/btn_close_l.gif" style="cursor:hand" onClick="self.close();">
    </td>
  </tr>
</table>
</form>
</td></tr></table>
</body>
</html>