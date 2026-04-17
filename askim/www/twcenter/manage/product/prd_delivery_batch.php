<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php
if($mode != "batch"){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>상품배송비 일괄변경</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../../js/lib.js?ver=<?php echo VERSION ?>"></script>
<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.del_type.value == "") {
		alert('일괄적용할 상품배송비를 선택해주세요.');
		return false;
	} else {
		if(frm.del_type.value == 'DC' && frm.del_price.value == "") {
			alert('상품별 배송비를 입력해주세요.');
			return false;
		}

		if(!confirm("상품배송비를 일괄적용하시겠습니까?")) {
			return false;
		} else {
			frm.mode.value = "batch";
		}
	}

}

function delType(v) {

	if(v == 'DC') {
		$("input[name=del_price]").prop("disabled", false);
	} else {
		$("input[name=del_price]").prop("disabled", true);
	}

}

-->
</script>
<body>


<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">상품배송비 일괄변경</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td align="center">

			<form name="frm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="selvalue" value="<?=$selvalue?>">
			<input type="hidden" name="menucode" value="<?=$menucode?>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="25%" align="center" class="t_name">상품배송비 선택</td>
			          <td width="75%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DA" onclick="delType('DA')"></span>기본 배송정책
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DB" onclick="delType('DB')"></span>무료배송
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DC" onclick="delType('DC')"></span>상품별 배송비
						<input name="del_price" type="text" class="input Onum" size="10" disabled>원
						<span style="vertical-align: middle"><input type="radio" name="del_type" value="DD" onclick="delType('DD')"></span>수신자부담(착불)
			          </td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center" style="padding:15px">
						<input type="submit" value=" 일괄변경 " class="base_btn reg">
						<input type="button" value=" 닫기 " class="base_btn gray" onClick="self.close();">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>
<?php
} else {

	$selarr = explode("|",$selvalue);
	for($ii=count($selarr); $ii>=0; $ii--){

		if($selarr[$ii] != "") {

			$prdcode = $selarr[$ii];

			$sql_com = "";
			$sql_com .= " del_type              = '$del_type'         ";
			$sql_com .= " , del_price           = '$del_price'        ";

			$sql = "update wiz_product set {$sql_com} where prdcode='".$prdcode."' ";
			query($sql);

		}
	}

	echo "<script>alert('상품배송비 조건이 일괄변경되었습니다.');opener.document.location='prd_list.php?menucodeParam';self.close();</script>";

}
?>