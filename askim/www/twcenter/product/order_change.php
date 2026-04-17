<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($exchange == "true"){

	$sql = "update wiz_order set exchangemsg='$exchangemsg', status='CD', ex_request_date=now() where orderid='$orderid'";
	query($sql);

	echo "<script>alert('교환요청신청이 정상적으로 처리되었습니다.');self.close();opener.document.location.reload();</script>";
	exit;

}

$o_sql = "SELECT * FROM wiz_basket WHERE orderid='{$orderid}' ";
$o_result = query($o_sql);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>:: 교환요청 ::</title>
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $skin_dir ?>/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.exchangemsg.value == ""){
		alert(" 교환요청 사유를 작성해주세요");
		frm.exchangemsg.focus();
		return false;
	}
}
//-->
</script>
</head>

<body topmargin="0" leftmargin="0">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:4px solid #979797;">
	<tr>
		<td width="351" height="54" class="pop_tit">교환요청</td>
		<td width="60"><a href="javascript:window.close();"><img src="<?=$skin_dir?>/image/id_check_close.gif" width="21" height="21" border="0"></a></td>
	</tr>
	<tr>
		<td colspan="2" align="center" valign="top">
			<form name="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
			<input type="hidden" name="exchange" value="true">
			<input type="hidden" name="orderid" value="<?=$orderid?>">
			<table border=0 cellpadding=0 cellspacing=0  width="90%" class="order_form">
				<tr><td colspan="2" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td class="tit" height=40 width="20%">주문번호</td>
					<td class="val" width="70%"><?=$orderid?></td>
				</tr>
				<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
				<tr>
					<td class="tit" height=40 width="20%">교환옵션</td>
					<td class="val" width="70%">
						<select name="opt_name">
							<option value="">옵션선택</option>
							<?php
							$optname = "";
							while($o_row = sql_fetch_arr($o_result)){
								if(!empty($o_row['opttitle'])) {
									$optname .= $o_row['opttitle'];
								} else if(!empty($o_row['opttitle'])) {
									$optname .= $o_row['opttitle'];
								}
							?>
							<option value=""></option>
							<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
				<tr>
					<td class="tit">교환사유</td>
					<td class="val" style="padding:8px"><textarea rows="6" cols="10" name="exchangemsg" class="input" style="width:100%"></textarea></td>
				</tr>
				<tr><td height="1" colspan="4" bgcolor=#d7d7d7></td></tr>
				<tr>
					<td colspan=2 align=center height="50">
						<input type="image" src="<?=$skin_dir?>/image/btn_confirm.gif"> <img src="<?=$skin_dir?>/image/btn_cancel.gif" onClick="self.close()" style="cursor:pointer">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>