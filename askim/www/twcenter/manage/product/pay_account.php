<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/oper_info.php";

if($save == ""){

	if(empty($mode)) $mode = "insert";
	if(!strcmp($mode, "update")) {

		$sql = "
			select bkname
				 , bkacctno
				 , bkacctno2
				 , bkacctholer
			  from bank_account
			 where idx = '".$no."' 
		";
		$account_tmp = sql_fetch($sql);

		$bank = $account_tmp['bkname'];
		$account = $account_tmp['bkacctno2'];
		$name = $account_tmp['bkacctholer'];

	}

?>
<html>
<head>
<title>무통장 입금 은행 등록</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.bank.value == ""){
		alert("은행명을 입력하세요");
		frm.bank.focus();
		return false;
	}
	if(frm.account.value == ""){
		alert("계좌번호를를 입력하세요");
		frm.account.focus();
		return false;
	}
	if(frm.name.value == ""){
		alert("예금주를 입력하세요");
		frm.name.focus();
		return false;
	}
}
//-->
</script>
</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">무통장 입금 은행 등록</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>


<form name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return inputCheck(this);">
<input type="hidden" name="save" value="true">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="no" value="<?=$no?>">
<table width="99%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">
	<tr>
		<td width=30% height=25 class="t_name">&nbsp; 은행명</td>
		<td width=70% class="t_value"><input type="text" name="bank" value="<?=$bank?>" class="input"></td>
	</tr>
	<tr>
		<td height=25 class="t_name">&nbsp; 계좌번호</td>
		<td class="t_value"><input type="text" name="account" value="<?=$account?>" class="input"> * 하이픈('-') 포함입력</td>
	</tr>
	<tr>
		<td height=25 class="t_name">&nbsp; 예금주</td>
		<td class="t_value"><input type="text" name="name" value="<?=$name?>" class="input"></td>
	</tr>
</table>
</center>

<br>
<table width="100%" border=0 cellpadding=0 cellspacing=0 align=center>
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;<input type="button" value="닫기" class="base_btn gray" onclick="self.close();">
		</td>
	</tr>
</table>
</form>

<?php
} else {

	if(!strcmp($mode, "insert")) {

		$account2 = str_replace("-", "", $account);
		$sql_com = "";
		$sql_com .= " bkname             = '".$bank."'                          ";
		$sql_com .= " , bkacctholer      = '".$name."'                          ";
		$sql_com .= " , bkacctno         = '".$account2."'                       ";
		$sql_com .= " , bkacctno2        = '".$account."'                      ";
		$sql_com .= " , wdate            = now()                                ";

		$sql = "insert into bank_account set {$sql_com} ";
		query($sql);

		echo "<script>alert('저장되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "update")) {

		$account2 = str_replace("-", "", $account);
		$sql_com = "";
		$sql_com .= " bkname             = '".$bank."'                          ";
		$sql_com .= " , bkacctholer      = '".$name."'                          ";
		$sql_com .= " , bkacctno         = '".$account2."'                      ";
		$sql_com .= " , bkacctno2        = '".$account."'                       ";
		$sql_com .= " , mdate            = now()                                ";

		$sql = "update bank_account set {$sql_com} where idx = '".$no."' ";
		query($sql);

		echo "<script>alert('수정되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "delete")) {

		$sql = "delete from bank_account where idx = '".$no."' ";
		query($sql);

		echo "<script>alert('삭제되었습니다.');document.location='shop_oper.php';</script>";

	}

}
?>