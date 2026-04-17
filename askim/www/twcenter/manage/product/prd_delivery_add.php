<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

$j_query = "select * from wiz_basket where orderid='{$orderid}' and idx={$basIdx}";
$j_result = query($j_query);
$j_row = sql_fetch_arr($j_result);

if(!empty($j_row['deliver_date'])){
	$j_row['deliver_date'] = $j_row['deliver_date'];
} else {
	$j_row['deliver_date'] = date("YmdHi");
}

echo "<link href=\"../wiz_style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<script type=\"text/javascript\" src=\"../../js/jquery-1.11.3.min.js\"></script>".PHP_EOL;

include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

 ?>
<script type="text/javascript">
function delivery_push(mode){

	var del_com      = $("#del_com option:selected").val();
	var del_num      = $("#del_num").val();
	var deliver_date = $("#deliver_date").val();
	
	if(del_com == ""){
		alert("배송업체를 선택하세요.");
		return;
	}

	if(del_num == ""){
		alert("운송장번호를 입력하세요.");
		return;
	}

	if(deliver_date == ""){
		alert("발송일자를 입력하세요.");
		return;
	}

	$.ajax({
		type:"post"
		, async: false
		, url:  "order_save.php"
		, data: {mode:"deliverySel", del_com:del_com, del_num:del_num, deliver_date:deliver_date, basIdx:"<?php echo $basIdx ?>", orderid:"<?php echo $orderid ?>"}
		, success: function(data) {
			var res = data.trim(); // 2023-12-28 저장 관련 수정
			if(res == "ok"){
				alert("선택상품의 배송업체가 선택되었습니다");
				window.opener.location.reload();
				window.close();
			}
		}
		, error: function(){
			alert("서버와의 연결이 원활하지 않습니다.");
		}
	});

}

</script>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="80%"><img src="../image/ics_tit.gif" align="absmiddle"> 상품명 : <?php echo $j_row['prdname'] ?> [<?php echo $orderid ?>]</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>	
<form name="delFrm" method="post">
<table width="99%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center">
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="25%" class="t_name">배송업체</td>
					<td width="75%" class="t_value">
						<select name="del_com" class="select" id="del_com">
							<option value="">배송업체선택</option>
						<?php
							$query = "select * from wiz_delivery_company where del_com2 = 'Y'";
							$result = query($query);
							while($_delivery = sql_fetch_arr($result)){
								$del_code = $_delivery['del_com']."|".$_delivery['idx'];
						 ?>
							<option value="<?php echo $del_code ?>" <? if($del_code == $j_row['del_com']) echo "selected" ?>><?php echo $_delivery['del_com'] ?></option>
						<?php
							}
						 ?>
						</script>
					</td>
				</tr>
				<tr>
					<td width="25%" class="t_name">운송장번호</td>
					<td width="75%" class="t_value"><input type="text" name="del_num" id="del_num" value="<?php echo $j_row['del_num'] ?>" class="input"></td>
				</tr>
				<tr>
					<td width="25%" class="t_name">발송일자</td>
					<td width="75%" class="t_value"><input type="text" name="deliver_date" id="deliver_date"  value="<?php echo $j_row['deliver_date'] ?>" class="input delivery">
					<br><b>발송일자 입력형식(년월일시분)</b>
					예) <?php echo date('Y') ?>년 <?php echo date('m') ?>월 <?php echo date('d') ?>일 <?php echo date('H') ?>시 <?php echo date('i') ?>분 =
					<?php echo date('Y').date('m').date('d').date('H').date('i') ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="99%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="" cellpadding="0">
				<tr>
					<td align="center">
						<a href="javascript:delivery_push('delPush');"><input type="button" value="확인" class="base_btn reg"></a>&nbsp;
						<input type="button" value="닫기" class="base_btn gray" onclick="self.close();">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>
</form>
