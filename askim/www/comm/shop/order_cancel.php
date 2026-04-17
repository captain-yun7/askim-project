<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($cancel == "true"){

	$sql = "
		update wiz_order 
		   set cancelmsg='$cancelmsg'
		     , status='RD'
			 , cancel_request_date=now() 
		 where orderid='$orderid'
	";
	query($sql);

	echo "<script>alert('취소요청이 정상적으로 처리되었습니다.');self.close();opener.document.location.reload();</script>";
	exit;

}
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>:: 주문취소 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width" />
<link href="<?php echo $skin_dir ?>style.css" rel="stylesheet" type="text/css">
<link href="/comm/css/sub.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.cancelmsg.value == ""){
		alert("취소사유를 작성해주세요");
		frm.cancelmsg.focus();
		return false;
	}
}
//-->
</script>
</head>

<body topmargin="0" leftmargin="0">
<div class="order_cancel">
	<h5>주문취소<a href="javascript:window.close();" class="close"><img src="<?php echo $skin_dir ?>image/id_check_close.gif" width="21" height="21" border="0"></a></h5>

	<form name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return inputCheck(this);">
	    <input type="hidden" name="cancel" value="true">
	    <input type="hidden" name="orderid" value="<?php echo $orderid ?>">
		<table border=0 cellpadding=0 cellspacing=0  width="95%" class="order_form">
			<tr>
				<td class="tit"  width="30%" style="padding:13px 10px">주문번호</td>
				<td class="val" width="70%"><?php echo $orderid ?></td>
			</tr>
			<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
			<tr>
				<td class="tit"  width="30%" style="padding:13px 10px">취소사유</td>
				<td class="val" style="padding:8px"><textarea rows="6" cols="10" name="cancelmsg" class="input" style="width:100%"></textarea></td>
			</tr>
			<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
			<tr>
				<td colspan=2 align=center height="50">
					<input type="image" src="<?php echo $skin_dir ?>image/btn_confirm.gif"> <img src="<?php echo $skin_dir ?>image/btn_cancel.gif" onClick="self.close()" style="cursor:pointer">
				</td>
			</tr>
		</table>
		</form>

</div>


<!-- <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:5px solid #979797;">
  <tr>
    <td width="351" height="54" class="pop_tit">주문취소</td>
    <td width="60"><a href="javascript:window.close();"><img src="<?=$skin_dir?>/image/id_check_close.gif" width="21" height="21" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top">

		<form name="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
	    <input type="hidden" name="cancel" value="true">
	    <input type="hidden" name="orderid" value="<?=$orderid?>">
		<table border=0 cellpadding=0 cellspacing=0  width="90%" class="order_form">
	    <tr><td colspan="2" bgcolor="#333333" height="1"></td></tr>
			<tr>
				<td class="tit" height=40 width="30%">주문번호</td>
				<td class="val" width="70%"><?=$orderid?></td>
			</tr>
			<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
			<tr>
				<td class="tit">취소사유</td>
				<td class="val" style="padding:8px"><textarea rows="6" cols="10" name="cancelmsg" class="input" style="width:100%"></textarea></td>
			</tr>
			<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
			<tr>
				<td colspan=2 align=center height="50">
					<input type="image" src="<?=$skin_dir?>/image/btn_confirm.gif"> <img src="<?=$skin_dir?>/image/btn_cancel.gif" onClick="self.close()" style="cursor:hand">
				</td>
			</tr>
		</table>
		</form>

    </td>
  </tr>
</table> -->
</body>
</html>