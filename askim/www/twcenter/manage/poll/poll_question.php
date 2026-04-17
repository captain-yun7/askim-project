<? include_once "../../common.php"; ?>
<?
if($smode == "") $smode = "insert";
if($smode == "update"){
	$sql = "select * from wiz_polldata where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:: 설문내용 등록 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
//-->
</script>
<style type="text/css">
<!--
.style1 {
	color: #DF4400;
	font-weight: bold;
}
.style2 {color: #FFFFFF}
-->
</style>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(form){

   if(form.question.value == ""){
      alert('질문을 입력하세요.');
      form.question.focus();
      return false;
   }
   if(form.answer01.value == ""){
      alert('답변1을 입력하세요.');
      form.answer01.focus();
      return false;
   }

}
-->
</script>
</head>

<body>

<table width="100%" border="0" cellspacing="3" cellpadding="3">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 설문내용</td>
        </tr>
      </table>
      <form name="frm" action="poll_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="question">
      <input type="hidden" name="smode" value="<?=$smode?>">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="pidx" value="<?=$pidx?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>

            <table align="center" width="98%" border="0" cellspacing="1" cellpadding="2" class="t_style">
            <tr>
            	<td class="t_name">질문</td>
            	<td class="t_value"> <input type="text" name="question" value="<?=$row['question']?>" size="50" class="input"></td>
				<td class="t_name"> 참여수</td></tr>
            <tr>
            	<td width="15%" class="t_name">답변 1</td>
            	<td class="t_value"> <input type="text" name="answer01" value="<?=$row['answer01']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count01" value="<?=$row['count01']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 2</td>
            	<td class="t_value"> <input type="text" name="answer02" value="<?=$row['answer02']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count02" value="<?=$row['count02']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 3</td>
            	<td class="t_value"> <input type="text" name="answer03" value="<?=$row['answer03']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count03" value="<?=$row['count03']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 4</td>
            	<td class="t_value"> <input type="text" name="answer04" value="<?=$row['answer04']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count04" value="<?=$row['count04']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 5</td>
            	<td class="t_value"> <input type="text" name="answer05" value="<?=$row['answer05']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count05" value="<?=$row['count05']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 6</td>
            	<td class="t_value"> <input type="text" name="answer06" value="<?=$row['answer06']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count06" value="<?=$row['count06']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 7</td>
            	<td class="t_value"> <input type="text" name="answer07" value="<?=$row['answer07']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count07" value="<?=$row['count07']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 8</td>
            	<td class="t_value"> <input type="text" name="answer08" value="<?=$row['answer08']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count08" value="<?=$row['count08']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 9</td>
            	<td class="t_value"> <input type="text" name="answer09" value="<?=$row['answer09']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count09" value="<?=$row['count09']?>" size="6" class="input"></td>
            </tr>
            <tr>
            	<td class="t_name">답변 10</td>
            	<td class="t_value"> <input type="text" name="answer10" value="<?=$row['answer10']?>" size="50" class="input"></td>
            	<td class="t_value"> <input type="text" name="count10" value="<?=$row['count10']?>" size="6" class="input"></td>
            </tr>
            </table>

          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg" >&nbsp;
			<input type="button" value="닫기" class="base_btn gray"  onClick="self.close()">
          </td>
        </tr>
      </table>
	  </form>

    </td>
  </tr>
</table>

</body>
</html>