<?php
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/twcenter_check.php';

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

	frm.orderid.checked       = true;
	frm.orderprd.checked      = true;
	frm.total_price.checked   = true;
	frm.pay_method.checked    = true;
	frm.order_date.checked    = true;
	frm.account.checked       = true;
	frm.deliver_com.checked   = true;
	frm.deliver_num.checked   = true;
	frm.account_name.checked  = false;
	frm.ostatus.checked       = true;
	frm.descript.checked      = false;

	frm.send_name.checked     = true;
	frm.send_email.checked    = false;
	frm.send_tphone.checked   = true;
	frm.send_hphone.checked   = false;
	frm.send_post.checked     = false;
	frm.send_address.checked  = false;

	frm.rece_name.checked     = true;
	frm.rece_tphone.checked   = true;
	frm.rece_hphone.checked   = false;
	frm.rece_post.checked     = true;
	frm.rece_address.checked  = true;
	frm.demand.checked        = false;
	frm.cancelmsg.checked     = false;

	frm.deliver_price.checked = false;

}

function selAll(frm){

	frm.orderid.checked       = true;
	frm.orderprd.checked      = true;
	frm.total_price.checked   = true;
	frm.pay_method.checked    = true;
	frm.order_date.checked    = true;
	frm.account.checked       = true;
	frm.deliver_com.checked   = true;
	frm.deliver_num.checked   = true;
	frm.account_name.checked  = true;
	frm.ostatus.checked       = true;
	frm.descript.checked      = true;

	frm.send_name.checked     = true;
	frm.send_email.checked    = true;
	frm.send_tphone.checked   = true;
	frm.send_hphone.checked   = true;
	frm.send_post.checked     = true;
	frm.send_address.checked  = true;

	frm.rece_name.checked     = true;
	frm.rece_tphone.checked   = true;
	frm.rece_hphone.checked   = true;
	frm.rece_post.checked     = true;
	frm.rece_address.checked  = true;
	frm.demand.checked        = true;
	frm.cancelmsg.checked     = true;

	frm.deliver_price.checked = true;

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
		  <td><input type="checkbox" name="deliver_com" value="Y" checked>배송업체</td>
		  <td><input type="checkbox" name="deliver_num" value="Y" checked>운송장번호</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="account_name" value="Y" checked>입금인</td>
		  <td><input type="checkbox" name="ostatus" value="Y" checked>처리상태</td>
		  <td><input type="checkbox" name="descript" value="Y">관리자메모</td>
		  <td><input type="checkbox" name="deliver_price" value="Y">배송료포함</td>
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

if($wiz_admin['id'] != "" && strpos($_SERVER['HTTP_REFERER'], 'google') === false){

	$filename = "order_info[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> "; 

?>
<style>
.TextFormat {mso-number-format:"0_ "; text-align:"center"; font-size:12px;}
.TextFormat2 { font-size:12px; font-family:NanumGothic, 나눔고딕, NG, Tahoma, Geneva, sans-serif; text-align:"center"; }
.TextFormat3 {mso-number-format:"\@"; text-align:"center"; font-size:12px;}
</style>
<table border='1'>
	<tr bgcolor="#cccccc">
		<? if($orderid		== "Y"){?><td class="TextFormat2">주문번호</td>				<?} else { ?>	<? } ?>
		<? if($orderprd		== "Y"){?><td class="TextFormat2">주문상품</td>				<?} else { ?>	<? } ?>
		<? if($orderprd		== "Y"){?><td class="TextFormat2">주문옵션</td>				<?} else { ?>	<? } ?>
		<? if($orderprd		== "Y"){?><td class="TextFormat2">갯수</td>					<?} else { ?>	<? } ?>
		<? if($total_price	== "Y"){?><td class="TextFormat2">총결제금액</td>				<?} else { ?>	<? } ?>
		<? if($pay_method	== "Y"){?><td class="TextFormat2">결제방법</td>				<?} else { ?>	<? } ?>
		<? if($order_date	== "Y"){?><td class="TextFormat2">주문일자</td>				<?} else { ?>	<? } ?>
		<? if($account		== "Y"){?><td class="TextFormat2">결제계좌</td>				<?} else { ?>	<? } ?>
		<? if($deliver_com	== "Y"){?><td class="TextFormat2">배송업체</td>				<?} else { ?>	<? } ?>
		<? if($deliver_num	== "Y"){?><td class="TextFormat2">운송장번호</td>				<?} else { ?>	<? } ?>
		<? if($account_name	== "Y"){?><td class="TextFormat2">입금인</td>					<?} else { ?>	<? } ?>

		<? if($send_name	== "Y"){?><td class="TextFormat2">주문자명</td>				<?} else { ?>	<? } ?>
		<? if($send_email	== "Y"){?><td class="TextFormat2">주문자 이메일</td>			<?} else { ?>	<? } ?>
		<? if($send_tphone	== "Y"){?><td class="TextFormat2">주문자 전화번호</td>			<?} else { ?>	<? } ?>
		<? if($send_hphone	== "Y"){?><td class="TextFormat2">주문자 휴대폰</td>			<?} else { ?>	<? } ?>
		<? if($send_post	== "Y"){?><td class="TextFormat2">주문자 우편번호</td>			<?} else { ?>	<? } ?>
		<? if($send_address	== "Y"){?><td class="TextFormat2">주문자 주소</td>				<?} else { ?>	<? } ?>

		<? if($rece_name	== "Y"){?><td class="TextFormat2">수취인명</td>				<?} else { ?>	<? } ?>
		<? if($rece_tphone	== "Y"){?><td class="TextFormat2">수취인 전화번호</td>			<?} else { ?>	<? } ?>
		<? if($rece_hphone	== "Y"){?><td class="TextFormat2">수취인 휴대폰</td>			<?} else { ?>	<? } ?>
		<? if($rece_post	== "Y"){?><td class="TextFormat2">수취인 우편번호</td>			<?} else { ?>	<? } ?>
		<? if($rece_address	== "Y"){?><td class="TextFormat2">수취인 주소</td>				<?} else { ?>	<? } ?>
		<? if($demand		== "Y"){?><td class="TextFormat2">요청사항</td>				<?} else { ?>	<? } ?>

		<? if($ostatus		== "Y"){?><td class="TextFormat2">처리상태</td>				<?} else { ?>	<? } ?>
		<? if($cancelmsg	== "Y"){?><td class="TextFormat2">주문취소사유</td>				<?} else { ?>	<? } ?>
		<? if($descript		== "Y"){?><td class="TextFormat2">관리자메모</td>				<?} else { ?>	<? } ?>

	</tr>

<?

	$prev_period = $srh_prev;
	$next_period = $srh_next." 23:59:59";

	$where = array();

	/*if(($s_status == "ALL" || empty($s_status)) && !empty($srh_prev)){
		$where[]  = "wo.order_date >= '$prev_period' and order_date <= '$next_period'";

	} else if(!empty($s_status)) {*/
	if(!empty($s_status)) {
		if($s_status == 'OR')        $ordsearch_sql = "order_date";
		else if($s_status == 'OY')   $ordsearch_sql = "pay_date";
		else if($s_status == 'DC')   $ordsearch_sql = "send_date";
		else if($s_status == 'DR')   $ordsearch_sql = "send_pre_date";
		else if($s_status == 'DI')   $ordsearch_sql = "send_pro_date";
		else if($s_status == 'OC')   $ordsearch_sql = "cancel_date";
		else if($s_status == 'RD')   $ordsearch_sql = "cancel_request_date";
		else if($s_status == 'RC')   $ordsearch_sql = "cancel_date";
		else if($s_status == 'CD')   $ordsearch_sql = "ex_request_date";
		else if($s_status == 'CC')   $ordsearch_sql = "exchange_date";
		else if($s_status == 'MI')   $ordsearch_sql = "order_date";
	}
	if(isset($srh_prev) && $srh_prev) {
		if($s_status == 'ALL' || $s_status == ''){
			$where[] = "wo.order_date >= '$prev_period' and wo.order_date <= '$next_period'";
		} else {
			$where[]  = "wo.$ordsearch_sql >= '$prev_period' and wo.$ordsearch_sql <= '$next_period' ";
		}
	} 

/*		if($s_status == 'ALL'){
			$where[]  = "";
		} else {
			$where[]  = "wo.$ordsearch_sql >= '$prev_period' and wo.$ordsearch_sql <= '$next_period' ";
		}

	} else if($s_status == "ALL" || empty($s_status)){
		$where[]  = "";
	} else {
		$where[]  = "wo.order_date >= '$prev_period' and order_date <= '$next_period'";
	}
*/
	if($s_status == "" || $s_status == 'ALL') $where[] = "wo.status != ''";
	else if($s_status == "MI")                $where[] = "wo.status = ''";
	else                                      $where[] = "wo.status = '$s_status'";

	/*if($s_status2 != "") $where[] = "wo.status2 = '$s_status2'";
	if($_GET['pay_method'] != "") $where[] = "wo.pay_method = '$_GET['pay_method']'";*/

	if($searchopt && $searchkey) $where[] = "wo.$searchopt like '%$searchkey%'";

//추가
	if(!empty($t_pay_method)){
		$_arr = implode("/",$t_pay_method);
		$_val = explode("/", $_arr);

		foreach($_val as $key => $value){
			if(!empty($value)) $tmp_paymethod .= " OR pay_method='$value'";
		}
		$tmp_paymethod  = substr($tmp_paymethod,3);
		$where[]        = "({$tmp_paymethod})";
	}
//--추가
	$array_selorder = explode('|',$selorder ?? '');
	if(count($array_selorder)-1 > 0){

		$tmp_selorder = "";
		foreach($array_selorder as $key => $value){
			if(!empty($value)) $tmp_selorder .= " or wo.orderid = '{$value}'";
		}
		$tmp_selorder = substr($tmp_selorder,3);
		$where[] = " ({$tmp_selorder})";
	}

	if(!empty($t_conn)) $where[] = "connect_type='$t_conn'";

	$search_query   = ($where) ? " and ".implode(" and ", $where) : "";

	$sql = "
		SELECT wo.*	
			 , wb.prdname
			 , wb.prdprice
			 , wb.amount
			 , wb.opttitle3
			 , wb.optcode3
			 , wb.opttitle4
			 , wb.optcode4
			 , wb.opttitle5
			 , wb.optcode5
			 , wb.opttitle6
			 , wb.optcode6
			 , wb.opttitle7
			 , wb.optcode7
			 , wb.opttitle8
			 , wb.optcode8
			 , wb.opttitle9
			 , wb.optcode9
			 , wb.opttitle10
			 , wb.optcode10
			 , wb.opttitle11
			 , wb.optcode11
			 , wb.opttitle
			 , wb.optcode
			 , wb.opttitle2
			 , wb.optcode2
			 , wb.del_com as wbdelcom
			 , wb.del_num as wbdelnum
			 , wb.del_type
			 , wb.del_price
		FROM wiz_order AS wo
		LEFT JOIN wiz_basket AS wb 
		  ON wo.orderid = wb.orderid
		WHERE wo.orderid != ''
			$search_query
			order by wo.order_date desc
		";


	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	while($row = sql_fetch_obj($result)){

		if($deliver_price == "Y"){
			$deli_price    = ($row->deliver_price > 0) ? $row->deliver_price : '0';
			$deliver_msg   = ($row->deliver_price > 0) ? "(배송료 포함)" : "";

			/* 2020-10-16 배송정책에 따라 배송료 부여*/
			if($row->del_type=="DC" && ($row->del_price > $row->deliver_price)){ //상품별 배송 | 상품별 배송비가 기본배송료보다 큰경우
				$prd_price	= ($row->prdprice * $row->amount) + $deli_price;
			}else if($row->del_type=="DA" && ($row->del_price < $row->deliver_price)){ //기본배송 | 상품별 배송비보다 큰경우
				$prd_price	= ($row->prdprice * $row->amount) + $deli_price;
			}else{
				$prd_price	= ($row->prdprice * $row->amount);
			}
			
		} else {
			$prd_price	= $row->prdprice * $row->amount;
		}


		list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3);
		list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4);
		list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8);
		list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9);
		list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10);
		list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11);

		/*
		작업자	: 정나혜 
		작업일시	: 2022-09-22
		작업내용	: 선택옵션이 여러 개 존재하는 경우 줄바꿈 태그를 이용해 줄바꿈을 출력하게 되면 한줄씩 셀이 추가되어 변환 
				=> 엑셀에서 필터링 진행시 정상적으로 필터링 되지 않는 오류 수정
		*/

		if($row->opttitle5 != '')  $optcode = $row->opttitle5." : ".$row->optcode5 ; else $optcode = "";
		if($row->opttitle6 != '')  $optcode .= ",<br/> ".$row->opttitle6." : ".$row->optcode6; else $optcode .= "";
		if($row->opttitle7 != '')  $optcode .= ",<br/> ".$row->opttitle7." : ".$row->optcode7.",<br/> "; else $optcode .= "";

		if($row->opttitle3 != '')  $optcode .= ",<br/> ".$row->opttitle3." : ".$optcode3_v; else $optcode .= "";
		if($row->opttitle4 != '')  $optcode .= ",<br/> ".$row->opttitle4." : ".$optcode4_v; else $optcode .= "";
		if($row->opttitle8 != '')  $optcode .= ",<br/> ".$row->opttitle8." : ".$optcode8_v; else $optcode .= "";
		if($row->opttitle9 != '')  $optcode .= ",<br/> ".$row->opttitle9." : ".$optcode9_v; else $optcode .= "";
		if($row->opttitle10 != '') $optcode .= ",<br/> ".$row->opttitle10." : ".$optcode10_v; else $optcode .= "";
		if($row->opttitle11 != '') $optcode .= ",<br/> ".$row->opttitle11." : ".$optcode11_v; else $optcode .= "";

		list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
		if($row->opttitle != '')   $optcode .= $row->opttitle;
		if($row->opttitle != '' && $row->opttitle2 != '') $optcode .= "/";
		if($row->opttitle2 != '')  $optcode .= $row->opttitle2;
		if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= " : ".$optcode_v."";

		$row->deliver_num = (!empty($row->wbdelnum)) ? $row->wbdelnum : $row->deliver_num;
		list($del_com,$del_idx) = explode("|",$row->wbdelcom ?? '');
		$row->deliver_com = (!empty($row->wbdelcom)) ? $del_com : $row->deliver_com;

?>
	<tr>
		<? if($orderid		== "Y"){
			if($row->f_orderid==""){?>
				<td class="TextFormat"><?=$row->orderid?></td>	
			<?}else{?>
				<td class="TextFormat"><?=$row->f_orderid?></td>	
		<? }}else { ?>	<? } ?>
		<? if($orderprd		== "Y"){?><td class="TextFormat2"><?=$row->prdname?></td>						<?} else { ?>	<? } ?>
		
		<?php
		/*
		작업자	: 정나혜 
		작업일시	: 2022-09-22
		작업내용	: 선택옵션이 여러 개 존재하는 경우 줄바꿈 태그를 이용해 줄바꿈을 출력하게 되면 한줄씩 셀이 추가되어 변환 
				=> 엑셀에서 필터링 진행시 정상적으로 필터링 되지 않는 오류 수정
		*/
		?>
		<? if($orderprd		== "Y"){?><td><?php echo str_replace("<br/>","<br style='mso-data-placement:same-cell;'>", $optcode); ?></td>
		<?} else { ?>	<? } ?>
		
			<? if($orderprd		== "Y"){?><td class="TextFormat2"><?=$row->amount?></td>						<?} else { ?>	<? } ?>
		<? if($total_price	== "Y"){?><td class="TextFormat2"><?=number_format($prd_price ?? 0).$deliver_msg?></td>			<?} else { ?>	<? } ?>
		<? if($pay_method	== "Y"){?><td class="TextFormat2"><?=pay_method($row->pay_method)?></td>		<?} else { ?>	<? } ?>
		<? if($order_date	== "Y"){?><td class="TextFormat2"><?=$row->order_date?></td>					<?} else { ?>	<? } ?>
		<? if($account		== "Y"){?><td class="TextFormat2"><?=$row->account?></td>						<?} else { ?>	<? } ?>
		<? if($deliver_com	== "Y"){?><td class="TextFormat2"><?=$row->deliver_com?></td>					<?} else { ?>	<? } ?>
		<? if($deliver_num	== "Y"){?><td class="TextFormat3"><?=$row->deliver_num?></td>					<?} else { ?>	<? } ?>
		<? if($account_name	== "Y"){?><td class="TextFormat2"><?=$row->account_name?></td>					<?} else { ?>	<? } ?>

		<? if($send_name	== "Y"){?><td class="TextFormat2"><?=$row->send_name?></td>						<?} else { ?>	<? } ?>
		<? if($send_email	== "Y"){?><td class="TextFormat2"><?=$row->send_email?></td>					<?} else { ?>	<? } ?>
		<? if($send_tphone	== "Y"){?><td class="TextFormat"><?=$row->send_tphone?></td>					<?} else { ?>	<? } ?>
		<? if($send_hphone	== "Y"){?><td class="TextFormat"><?=$row->send_hphone?></td>					<?} else { ?>	<? } ?>
		<? if($send_post	== "Y"){?><td class="TextFormat3"><?=$row->send_post?></td>						<?} else { ?>	<? } ?>
		<? if($send_address	== "Y"){?><td class="TextFormat2"><?=$row->send_address?></td>					<?} else { ?>	<? } ?>

		<? if($rece_name	== "Y"){?><td class="TextFormat2"><?=$row->rece_name?></td>						<?} else { ?>	<? } ?>
		<? if($rece_tphone	== "Y"){?><td class="TextFormat"><?=$row->rece_tphone?></td>					<?} else { ?>	<? } ?>
		<? if($rece_hphone	== "Y"){?><td class="TextFormat"><?=$row->rece_hphone?></td>					<?} else { ?>	<? } ?>
		<? if($rece_post	== "Y"){?><td class="TextFormat3"><?=$row->rece_post?></td>						<?} else { ?>	<? } ?>
		<? if($rece_address	== "Y"){?><td class="TextFormat2"><?=$row->rece_address?></td>					<?} else { ?>	<? } ?>
		<? if($demand		== "Y"){?><td class="TextFormat2"><?=$row->demand?></td>						<?} else { ?>	<? } ?>

		<? if($ostatus		== "Y"){?><td class="TextFormat2"><?=order_status($row->status)?></td>			<?} else { ?>	<? } ?>
		<? if($cancelmsg	== "Y"){?><td class="TextFormat2"><?=$row->cancelmsg?></td>						<?} else { ?>	<? } ?>
		<? if($descript		== "Y"){?><td class="TextFormat2"><?=$row->descript?></td>						<?} else { ?>	<? } ?>

	</tr>
<? 
	}
?>
	</table>
<?
	}

}
?>