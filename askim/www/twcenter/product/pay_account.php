<!-- 무통장 입금 결제 -->
<script language="javascript">
<!--
function inputCheck(frm){
	if(frm.account.value == ""){
		alert("계좌번호를 선택하세요.");
		return false;
	}
	if(frm.account_name.value == ""){
		alert("입금자명 입력하세요.");
		return false;
	}
}
-->
</script>

<form action="/twcenter/product/order_update.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">
<input type="hidden" name="as_b" value="<?=$as_b?>">
<input type="hidden" name="product_idx" value="<?=$product_idx?>">

<? if(strpos($PHP_SELF, "/m/") !== false) { ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td style="padding:15px 0px 10px 0px">

			<!-- 무통장 입금일 때 : 아래 필드가 보이게 해 주세요 -->
			
			<div class="order_table">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
				<tr>
					<th width="80">결제방법</th>
					<td>무통장입금</td>
				</tr>
				<tr>
					<th>입금계좌번호</th>
					<td>
						<select name="account" class="select2">
						<?php
						$sql = "select * from bank_account";
						$res = query($sql);
						while($row = sql_fetch_arr($res)) {
							$account = trim($row['bkname']." ".$row['bkacctno2']." ".$row['bkacctholer']);
							echo "<option value='$account'>$account</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<th>입금자명</th>
					<td>
						<input type=text name="account_name" value="<?=$wiz_session['name']?>" size=25 class="input_style">
					</td>
				</tr>
			</table>
			</div>

	  </td>
	</tr>
	<tr>
	  <td style="padding:10px;">
	    <input type="submit" class="btn_grat_big" value="결제하기" style="margin-bottom:2px" />
		<a href="/m/"><input type="button" class="btn_etc" value="취소하기" onClick="" /></a>
	  </td>
	</tr>
</table>

<? } else { ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td style="padding:15px 0px 10px 0px">

			<!-- 무통장 입금일 때 : 아래 필드가 보이게 해 주세요 -->
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
				<tr><td colspan="2" bgcolor="#333333" height="1"></td></tr>
				<tr>
					<td width="20%" class="table_tit2">결재방법</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">무통장입금</td>
				</tr>
				<tr>
					<td class="table_tit2">입금계좌번호</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">
						<select name="account">
						<?php
						$sql = "select * from bank_account";
						$res = query($sql);
						while($row = sql_fetch_arr($res)) {
							$account = trim($row['bkname']." ".$row['bkacctno2']." ".$row['bkacctholer']);
							echo "<option value='$account'>$account</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="table_tit2">입금자명</td>
					<td style="border-bottom:1px solid #d7d7d7; padding:5px 10px;">
						<input type=text name="account_name" value="<?=$wiz_session['name']?>" size=25 class="input">
					</td>
				</tr>
			</table>

	  </td>
	</tr>
	<tr>
	  <td height="60" align="center" valign="bottom">
		<input type="submit" value="결제하기" class="btn_style2">
		<a href="/"><input type="button" value="취소하기" class="btn_style1"></a>
	  </td>
	</tr>
</table>

<? } ?>


</form>