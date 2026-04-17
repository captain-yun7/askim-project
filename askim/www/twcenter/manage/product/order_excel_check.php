<?php
include "../../common.php";
include "../../inc/twcenter_check.php";


if($exceldown != "ok"){
?>
<html>
<head>
<title>:: 주문정보 다운로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function selBasic(frm){

	frm.orderid.checked = true;
	frm.orderprd.checked = true;
	frm.total_price.checked = true;
	frm.pay_method.checked = true;
	frm.order_date.checked = true;
	frm.account.checked = true;
	frm.deliver_num.checked = false;
	frm.account_name.checked = false;
	frm.ostatus.checked = true;

	frm.send_name.checked = true;
	frm.send_email.checked = false;
	frm.send_tphone.checked = true;
	frm.send_hphone.checked = false;
	frm.send_post.checked = false;
	frm.send_address.checked = false;

	frm.rece_name.checked = true;
	frm.rece_tphone.checked = true;
	frm.rece_hphone.checked = false;
	frm.rece_post.checked = true;
	frm.rece_address.checked = true;
	frm.demand.checked = false;

}

function selAll(frm){

	frm.orderid.checked = true;
	frm.orderprd.checked = true;
	frm.total_price.checked = true;
	frm.pay_method.checked = true;
	frm.order_date.checked = true;
	frm.account.checked = true;
	frm.deliver_num.checked = true;
	frm.account_name.checked = true;
	frm.ostatus.checked = true;
	frm.descript.checked = true;

	frm.send_name.checked = true;
	frm.send_email.checked = true;
	frm.send_tphone.checked = true;
	frm.send_hphone.checked = true;
	frm.send_post.checked = true;
	frm.send_address.checked = true;

	frm.rece_name.checked = true;
	frm.rece_tphone.checked = true;
	frm.rece_hphone.checked = true;
	frm.rece_post.checked = true;
	frm.rece_address.checked = true;
	frm.demand.checked = true;
	frm.cancelmsg.checked = true;

}
//-->
</script>
</head>

<body leftmargin="5" topmargin="5" scroll="yes">
<table><tr><td height="5"></td></tr></table>
<table width="98%" border="0" cellpadding="3" cellspacing="6" class="t_style" align="center">
<form name="frm" action="" method="post">
<input type="hidden" name="exceldown" value="ok">
<input type="hidden" name="status" value="<?=$status?>">
<input type="hidden" name="prev_year" value="<?=$prev_year?>">
<input type="hidden" name="prev_month" value="<?=$prev_month?>">
<input type="hidden" name="prev_day" value="<?=$prev_day?>">
<input type="hidden" name="next_year" value="<?=$next_year?>">
<input type="hidden" name="next_month" value="<?=$next_month?>">
<input type="hidden" name="next_day" value="<?=$next_day?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
  <tr>
    <td bgcolor="ffffff">
    <table><tr></td></tr></table>
     <table width="100%" cellspacing="2" cellpadding="0" border="0">
     <tr>
      <td><font color="2369C9"><b>선택구분</b></font></td>
      <td><input type="radio" name="sel_gubun" onClick="selBasic(this.form);" checked><font color="red"><b>기본선택</b></font></td>
		  <td><input type="radio" name="sel_gubun" onClick="selAll(this.form);"><font color="red"><b>전체선택</b></font></td>
		  <td></td>
		  <td></td>
		</tr>
		<tr><td height="3"></td></tr>
    <tr>
      <td><font color="2369C9"><b>주문정보</b></font></td>
      <td><input type="checkbox" name="orderid" value="Y" checked>주문번호</td>
		  <td><input type="checkbox" name="orderprd" value="Y" checked>주문상품</td>
		  <td><input type="checkbox" name="total_price" value="Y" checked>총결제금액</td>
		  <td><input type="checkbox" name="pay_method" value="Y" checked>결제방법</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="order_date" value="Y" checked>주문일자</td>
		  <td><input type="checkbox" name="account" value="Y" checked>결제계좌</td>
		  <td><input type="checkbox" name="deliver_num" value="Y">운송장번호</td>
		  <td><input type="checkbox" name="account_name" value="Y" checked>입금인</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="ostatus" value="Y" checked>처리상태</td>
		  <td><input type="checkbox" name="descript" value="Y">관리자메모</td>
		  <td></td>
		  <td></td>
	   </tr>
	   <tr><td height="3"></td></tr>
		<tr>
		  <td><font color="2369C9"><b>주문자정보</b></font></td>
			<td><input type="checkbox" name="send_name" value="Y" checked>주문자명</td>
			<td><input type="checkbox" name="send_email" value="Y">주문자 이메일</td>
			<td><input type="checkbox" name="send_tphone" value="Y" checked>주문자 전화번호</td>
			<td><input type="checkbox" name="send_hphone" value="Y">주문자 휴대폰</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="send_post" value="Y">주문자 우편번호</td>
		  <td><input type="checkbox" name="send_address" value="Y">주문자 주소</td>
		  <td></td>
		  <td></td>
	   </tr>
	   <tr><td height="6"></td></tr>
		<tr>
		  <td><font color="2369C9"><b>수취인정보</b></font></td>
			<td><input type="checkbox" name="rece_name" value="Y" checked>수취인명</td>
			<td><input type="checkbox" name="rece_tphone" value="Y" checked>수취인 전화번호</td>
			<td><input type="checkbox" name="rece_hphone" value="Y">수취인 휴대폰</td>
			<td><input type="checkbox" name="rece_post" value="Y" checked>수취인 우편번호</td>
    </tr>
    <tr>
      <td></td>
      <td><input type="checkbox" name="rece_address" value="Y" checked>수취인주소</td>
		  <td><input type="checkbox" name="demand" value="Y">수취인요청사항</td>
		  <td><input type="checkbox" name="cancelmsg" value="Y">주문취소사유</td>
      <td></td>
      <td></td>
      </tr>
    </table>
   </td>
 </tr>
</table>
<br>
<table align="center">
  <tr>
    <td>
    	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
    	<img src="../image/btn_close_l.gif" style="cursor:hand" onClick="self.close();">
    </td>
  </tr>
</form>
</table>
</body>
</html>
<?php
}else{

	$filename = "order_info[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

?>
<style>
.TextFormat
	{
	mso-number-format:"\@";
	}
</style>
	<table border='1'>
		<tr bgcolor="#cccccc">
			<? if($orderid		== "Y"){?><td align="center"><strong>주문번호</strong></td>				<?} else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td align="center"><strong>주문상품</strong></td>				<?} else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td align="center"><strong>주문옵션</strong></td>				<?} else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td align="center"><strong>사은품</strong></td>				<?} else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td align="center"><strong>갯수</strong></td>					<?} else { ?>	<? } ?>
			<? if($total_price	== "Y"){?><td align="center"><strong>총결제금액</strong></td>			<?} else { ?>	<? } ?>
			<? if($pay_method	== "Y"){?><td align="center"><strong>결제방법</strong></td>				<?} else { ?>	<? } ?>
			<? if($order_date	== "Y"){?><td align="center"><strong>주문일자</strong></td>				<?} else { ?>	<? } ?>
			<? if($account		== "Y"){?><td align="center"><strong>결제계좌</strong></td>				<?} else { ?>	<? } ?>
			<? if($deliver_num	== "Y"){?><td align="center"><strong>운송장번호</strong></td>			<?} else { ?>	<? } ?>
			<? if($account_name	== "Y"){?><td align="center"><strong>입금인</strong></td>				<?} else { ?>	<? } ?>

			<? if($send_name	== "Y"){?><td align="center"><strong>주문자명</strong></td>				<?} else { ?>	<? } ?>
			<? if($send_email	== "Y"){?><td align="center"><strong>주문자 이메일</strong></td>		<?} else { ?>	<? } ?>
			<? if($send_tphone	== "Y"){?><td align="center"><strong>주문자 전화번호</strong></td>		<?} else { ?>	<? } ?>
			<? if($send_hphone	== "Y"){?><td align="center"><strong>주문자 휴대폰</strong></td>		<?} else { ?>	<? } ?>
			<? if($send_post	== "Y"){?><td align="center"><strong>주문자 우편번호</strong></td>		<?} else { ?>	<? } ?>
			<? if($send_address	== "Y"){?><td align="center"><strong>주문자 주소</strong></td>			<?} else { ?>	<? } ?>

			<? if($rece_name	== "Y"){?><td align="center"><strong>수취인명</strong></td>				<?} else { ?>	<? } ?>
			<? if($rece_tphone	== "Y"){?><td align="center"><strong>수취인 전화번호</strong></td>		<?} else { ?>	<? } ?>
			<? if($rece_hphone	== "Y"){?><td align="center"><strong>수취인 휴대폰</strong></td>		<?} else { ?>	<? } ?>
			<? if($rece_post	== "Y"){?><td align="center"><strong>수취인 우편번호</strong></td>		<?} else { ?>	<? } ?>
			<? if($rece_address	== "Y"){?><td align="center"><strong>수취인 주소</strong></td>			<?} else { ?>	<? } ?>
			<? if($demand		== "Y"){?><td align="center"><strong>요청사항</strong></td>				<?} else { ?>	<? } ?>

			<? if($ostatus		== "Y"){?><td align="center"><strong>처리상태</strong></td>				<?} else { ?>	<? } ?>
			<? if($cancelmsg	== "Y"){?><td align="center"><strong>주문취소사유</strong></td>			<?} else { ?>	<? } ?>
			<? if($descript		== "Y"){?><td align="center"><strong>관리자메모</strong></td>			<?} else { ?>	<? } ?>

		</tr>

<?
	if($prev_year){
	   $prev_period = $prev_year."-".$prev_month."-".$prev_day;
	   $next_period = $next_year."-".$next_month."-".$next_day." 23:59:59";
	   $period_sql = " and wo.order_date >= '$prev_period' and wo.order_date <= '$next_period'";
	}
//	if($s_status == "") $status_sql = "and wo.status != ''";
//	else if($s_status == "MI") $status_sql = "and wo.status = ''";
//	else $status_sql = "and wo.status = '$s_status'";

	if($s_status == "") $status_sql = "and wo.status != ''";
	else $status_sql = "and wo.status = '$s_status'";

	if($s_status2 != "") $status2_sql = "and wo.status2 = '$s_status2'";
	if($_GET['pay_method'] != "") $paymethod_sql = " and wo.pay_method = '$_GET['pay_method']'";

	if($searchopt && $searchkey) $searchopt_sql = " and wo.$searchopt like '%$searchkey%'";

	$selorder_cut = explode('|',$selorder);
	$selorder_cut_cnt = count($selorder_cut);
	$selorder_sql = "";
	for($ii=0; $ii<$selorder_cut_cnt-1; $ii++){
		if($ii==$selorder_cut_cnt-2)
			$selorder_sql .= "wo.orderid='$selorder_cut[$ii]' ";
		else
			$selorder_sql .= "wo.orderid='$selorder_cut[$ii]' or ";
	}

/*	$sql = "
		SELECT
			wo.*,
			wb.prdname
		FROM
			wiz_order AS wo
			LEFT JOIN wiz_basket AS wb ON wo.orderid = wb.orderid
		WHERE
			wo.orderid != ''
			$status_sql
			$searchopt_sql
			$period_sql
			group by wo.orderid
			order by wo.orderid desc
		";
*/
/*
	$sql = "
		SELECT
			wo.*				,
			wb.prdname			,
			wb.prdprice			,
			wb.amount			,
			wb.opttitle3		,
			wb.optcode3			
		FROM
			wiz_order AS wo
			LEFT JOIN wiz_basket AS wb ON wo.orderid = wb.orderid
		WHERE
			wo.orderid != ''and
			($selorder_sql)
			$status_sql
			$status2_sql
			$searchopt_sql
			$period_sql
			$paymethod_sql
			order by wo.order_date desc
		";
*/
/*
다운로드시 orderid 를 group by 로 묶어서 처리 
*/
	$sql = "
		SELECT
			wo.*				,
			wb.prdname			,
			wb.prdprice			,
			wb.amount			,
			wb.opttitle3		,
			wb.optcode3			,
			wb.opttitle			,
			wb.optcode			,
			wb.opttitle2		,
			wb.optcode2			,
			SUM(amount) AS total_amount, 
			SUM(prdprice) AS total_prdprice, 
			group_concat(prdname separator '/') AS total_prdname ,
			group_concat(opttitle,',',opttitle2 , ':', replace(optcode,'/',','), '(', convert(amount,char), ')'  separator '/') AS total_option2,
			group_concat(opttitle3, ':', optcode3, '(', convert(amount,char), ')'  separator '/') AS total_option3,
			group_concat(convert(prdprice,char) separator '/') AS total_prdprice_text, 
			group_concat(convert(amount,char) separator '/') AS total_amount_text
		FROM
			wiz_order AS wo
			LEFT JOIN wiz_basket AS wb ON wo.orderid = wb.orderid
		WHERE
			wo.orderid != ''and
			($selorder_sql)
			$status_sql
			$status2_sql
			$searchopt_sql
			$period_sql
			$paymethod_sql
		GROUP BY wo.orderid
		ORDER BY wo.order_date desc
		";
//		echo "<pre>".$sql."</pre>";

	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	while($row = sql_fetch_obj($result)){

		$prd_price	= $row->prdprice * $row->amount;												//-- '개별상품 금액'

		$tot_price  = "";																			//-- '총 상품 금액'

		$prd_pri_cut = explode('/',$row->total_prdprice_text);
		$prd_amo_cut = explode('/',$row->total_amount_text);

		for($jj=0; $jj<=count($prd_pri_cut); $jj++){
				$tot_price += ($prd_pri_cut[$jj]*$prd_amo_cut[$jj]);
				
		}


		$R_prd = $row->optcode3;

		$jung = explode("(", $R_prd);

		if(strpos($row->optcode3, "증정없") !== false) $optcode3_val = "";
		else $optcode3_val =  $row->opttitle3 ." : ". $jung[0];

		if($row->opttitle3 != "") $prd_opt	= $optcode3_val;										//-- '개별선택 옵션'
		else $prd_opt = "";

		//------------------ 사은품출력부분 ----------------------------------------------------------------------------

		$gift_val = explode("+", $R_prd);
		$gift_val_2 = str_replace(")", "", $gift_val[1]);

		if(strpos($gift_prd, "증정없") !== false || strpos($gift_prd, "포당") !== false) $gift_set = "";
		else if($gift_val_2 != "") $gift_set = $row->prdname ." - ".$gift_val_2;
		else $gift_set = "";

		//------------------ 사은품출력부분 ----------------------------------------------------------------------------

?>
		<tr>
			<? if($orderid		== "Y"){
				if($row->f_orderid==""){?>
					<td class="TextFormat"><?=$row->orderid?></td>	
				<?}else{?>
					<td class="TextFormat"><?=$row->f_orderid?></td>	
			<? }}else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td><?=$row->total_prdname?></td>					<?} else { ?>	<? } ?>
			<!--
			<? if($orderprd		== "Y" && (preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $row->total_option2))){?><td><?=$row->total_option2?></td>					<?} else { ?> <? } ?>
			<? if($orderprd		== "Y" && (preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $row->total_option3))){?><td><?=$row->total_option3?></td>					<?} else { ?> <? } ?>
			-->
			<? if($orderprd		== "Y" && $row->optcode3==""){?><td><?=$row->total_option2?></td>					<?} else { ?> <? } ?>
			<? if($orderprd		== "Y" && $row->optcode3!=""){?><td><?=$row->total_option3?></td>					<?} else { ?> <? } ?>
			<? if($orderprd		== "Y"){?><td><?=$gift_set?></td>							<?} else { ?>	<? } ?>
			<? if($orderprd		== "Y"){?><td><?=$row->total_amount?></td>					<?} else { ?>	<? } ?>
			<? if($total_price	== "Y"){?><td><?=number_format($tot_price)?></td>			<?} else { ?>	<? } ?>
			<? if($pay_method	== "Y"){?><td><?=pay_method($row->pay_method)?></td>		<?} else { ?>	<? } ?>
			<? if($order_date	== "Y"){?><td><?=$row->order_date?></td>					<?} else { ?>	<? } ?>
			<? if($account		== "Y"){?><td><?=$row->account?></td>						<?} else { ?>	<? } ?>
			<? if($deliver_num	== "Y"){?><td><?=$row->deliver_num?></td>					<?} else { ?>	<? } ?>
			<? if($account_name	== "Y"){?><td><?=$row->account_name?></td>					<?} else { ?>	<? } ?>

			<? if($send_name	== "Y"){?><td><?=$row->send_name?></td>						<?} else { ?>	<? } ?>
			<? if($send_email	== "Y"){?><td><?=$row->send_email?></td>					<?} else { ?>	<? } ?>
			<? if($send_tphone	== "Y"){?><td class="TextFormat"><?=$row->send_tphone?></td><?} else { ?>	<? } ?>
			<? if($send_hphone	== "Y"){?><td class="TextFormat"><?=$row->send_hphone?></td><?} else { ?>	<? } ?>
			<? if($send_post	== "Y"){?><td><?=$row->send_post?></td>						<?} else { ?>	<? } ?>
			<? if($send_address	== "Y"){?><td><?=$row->send_address?></td>					<?} else { ?>	<? } ?>

			<? if($rece_name	== "Y"){?><td><?=$row->rece_name?></td>						<?} else { ?>	<? } ?>
			<? if($rece_tphone	== "Y"){?><td class="TextFormat"><?=$row->rece_tphone?></td><?} else { ?>	<? } ?>
			<? if($rece_hphone	== "Y"){?><td class="TextFormat"><?=$row->rece_hphone?></td><?} else { ?>	<? } ?>
			<? if($rece_post	== "Y"){?><td><?=$row->rece_post?></td>						<?} else { ?>	<? } ?>
			<? if($rece_address	== "Y"){?><td><?=$row->rece_address?></td>					<?} else { ?>	<? } ?>
			<? if($demand		== "Y"){?><td><?=$row->demand?></td>						<?} else { ?>	<? } ?>

			<? if($ostatus		== "Y"){?><td><?=order_status($row->status)?></td>			<?} else { ?>	<? } ?>
			<? if($cancelmsg	== "Y"){?><td><?=$row->cancelmsg?></td>						<?} else { ?>	<? } ?>
			<? if($descript		== "Y"){?><td><?=$row->descript?></td>						<?} else { ?>	<? } ?>

		</tr>
<? 
	}
?>
	</table>
<?
}
?>