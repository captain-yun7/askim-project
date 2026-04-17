<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?
if($save != "true"){

// 분석할 파라메터 가져오기
$sql = "select con_parameter from wiz_siteinfo";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);

?>
<html>
<head>
<title>:: 분석파라미터 설정 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
</head>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">분석파라미터 설정</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>	
<center>
<form name="frm" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="save" value="true">
<table width="99%"cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td class="t_name" align="center" height=35 width=100>분석파라미터</td>
    <td class="t_value">&nbsp;
      <input type="text" name="parameter" value="<?=$row->con_parameter?>" size="55" class="input">
	  <input type="submit" value="확인" class="base_btm reg">

	</td>
  </tr>
</table>
</form>
</center>
<div class="helpTip2">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 각 검색엔진 별로 분석해야할 파라미터 명이 다릅니다.<br>
		&nbsp;&nbsp;ex) 네이버에서 "아디다스"로 검색한경우 상단 주소는 다음과 같습니다.<br>
		&nbsp;&nbsp;http://search.naver.com/search.naver?where=nexearch&<font color='red'><b>query</b></font>=%BE%C6%B5%F0%B4%D9%BD%BA&frm=t1<br>
		- 이 경우 분석해야할 파라메터는 <font color='red'><b>query</b></font>가 됩니다.<br>
		- 위의 분석파라메터에 각 파라메터를 컴마로 구분하여 저정하시면 됩니다.<br>
	  </div>
	</div>
</div>
</body>
</html>
<?
}else{

	$sql = "update wiz_siteinfo set con_parameter = '$parameter'";
	$result = query($sql) or error("sql error");

	complete("적용되었습니다.","$PHP_SELF");

}
?>