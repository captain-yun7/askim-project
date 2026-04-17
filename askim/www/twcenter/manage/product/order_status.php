<?php
include_once "../../common.php";
?>
<html>
<head>
<title>:: 주문상태 일괄처리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language='javascript'>
<!--
function showdsn(svalue){

	if(svalue=="DI" || svalue=="DC"){
		document.getElementById("showdel").style.display="";
		resizeTo(500,500);
	}else{
		document.getElementById("showdel").style.display="none";
		resizeTo(500,500);
	}
	return;
}

function inputCheck(frm) {

	var cnt = document.forms["frm"].elements["deliveryno[]"].length;

	if(frm.chg_status.value == "DI") {

		for(ii = 0; ii < cnt; ii++) {

			if(!document.forms["frm"].elements["deliveryno[]"][ii].value) {
				alert("송장번호를 입력하세요."); document.forms["frm"].elements["deliveryno[]"][ii].focus(); return false;
			}
			if(!document.forms["frm"].elements["deliver_date[]"][ii].value) {
				alert("발송일자를 입력하세요."); document.forms["frm"].elements["deliver_date[]"][ii].focus(); return false;
			}

		}

	}

}
-->
</script>
</head>
<body onLoad="resizeTo(500,500);" scroll="yes">
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">주문상태 일괄처리</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" cellpadding=0 cellspacing=10>
	<tr>
		<td align="center">

<form action="order_save<?php echo $deli_page ?>.php" name="frm" onsubmit="return inputCheck(this)">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="batchStatus">
<input type="hidden" name="selorder" value="<?=$selorder?>">
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
  <tr align=center>
    <td height="23" class="t_name" width=100><b>상태선택</b></td>
    <td class="t_value">
    <select name="chg_status" onChange="javascript:showdsn(this.value);" style="width:150" class="select2">
		<option value="OR">주문접수
		<option value="OY">결제완료
		<option value="DR">배송준비중
		<option value="DI">배송처리
		<option value="DC">배송완료
		<option value="OC">주문취소
		<option value="">--------------------
		<option value="RD">환불요청
		<option value="RC">환불완료
		<option value="CD">교환요청
		<option value="CC">교환완료
    </select>
    </td>
  </tr>
  <tr align=center id="showdel" style='display:none;'>
    <td colspan=2 align=center bgcolor=#ffffff>
    <div>
    <table cellspacing=1 cellpadding=0 width=100% border=0>
    	<tr>
    		<td align=center><b>주문번호</b></td>
    		<td align=center><b>송장번호</b></td>
    		<td align=center><b>발송일자</b></td>
    	</tr>
    	<?
    	$array_selorder = explode("|",$selorder);
			foreach($array_selorder as $key => $value){
				list($value, $old_status) = explode(":", $value);
				if(trim($value)){

				$sql = "select deliver_num, deliver_date from wiz_order where orderid = '$value'";
				$result = query($sql) or error("sql error");
				$row = sql_fetch_arr($result);

				echo "<tr>
						<td align=center>$value</td>
						<td align=center><input type=text size=12 name=\"deliveryno[]\" value=\"".$row['deliver_num']."\" class=input></td>
						<td align=center>
							<input type=text size=12 name=\"deliver_date[]\" value=\"".$row['deliver_date']."\" onClick=\"if(this.value == '') this.value='".date("Y-m-d H:i:s")."';\" class=\"input\">
						</td>
					</tr>";
				}
			}
    	?>
    	<tr>
    		<td colspan="3" style="padding-top:3px">
    			<b>발송일자 입력형식(년월일시분)</b><br>
    			예) <?=date('Y')?>년 <?=date('m')?>월 <?=date('d')?>일 <?=date('H')?>시 <?=date('i')?>분 =
    		<?=date('Y').date('m').date('d').date('H').date('i')?>
    		</td>
    	</tr>
    </table>
    </div>
    </td>
  </tr>
</table>
			<table width="99%">
				<tr>
					<td>
						<span class="sub_tit_alt3"><img src="../image/tip_icon.png"> <font color="red">'주문취소'</font> 및 <font color="red">'환불완료'</font>된 주문은 상태가 변경되지 않으며</span><span class="tip_br"></span>
						<span class="sub_tit_alt3"><img src="../image/tip_icon.png"> 그 외의 처리상태는 주문상태가 변경됩니다.</span>
					</td>
				</tr>
			</table>
<br>
<table align="center">
  <tr>
    <td><input type="submit" value="확인" class="base_btn reg"></td>
  </tr>
</table>
</form>

</td></tr></table>
</body>
</html>