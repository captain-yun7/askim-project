<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<? include "../../lib/datepicker_lib.php"; ?>

<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "status=$status&srh_prev=$srh_prev&srh_next=$srh_next&$menucodeParam";
$param .= "&searchopt=$searchopt&searchkey=$searchkey&tmp_rows=$tmp_rows";
//--------------------------------------------------------------------------------------------------

$to_btn  = "<img src='../image/sicon_today.gif' border='0' align='absmiddle'>";
$ye_btn  = "<img src='../image/sicon_yes.gif' border='0' align='absmiddle'>";
$we_btn  = "<img src='../image/sicon_week.gif' border='0' align='absmiddle'>";
$mo_btn  = "<img src='../image/sicon_month.gif' border='0' align='absmiddle'>";
$tmo_btn = "<img src='../image/sicon_twomonth.gif' border='0' align='absmiddle'>";
$all_btn = "<img src='../image/sicon_all.gif' border='0' align='absmiddle'>";

?>
<script language="JavaScript" type="text/javascript">
<!--
$(function() {

	$('#srh_prev').datepicker({
		language: 'kr',
		autoClose: true
	});
	$('#srh_next').datepicker({
		language: 'kr',
		autoClose: true
	});

});

// 주문상태 변경
function chgStatus(status){

	if(status == 'ALL'){
		$("#srh_prev").val('');
		$("#srh_next").val('');
		document.frm.status.value = status;
		document.frm.submit();
	} else {
		document.frm.status.value = status;
		document.frm.submit();
	}

}

//
function chgReason(reason){
   document.frm.reason.value = reason;
   document.frm.submit();
}

//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
function selectAll(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function basketDelete(){

var i;
var selbasket = "";
for(i=0;i<document.forms.length;i++){
	if(document.forms[i].idx != null){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].select_checkbox.checked)
				selbasket = selbasket + document.forms[i].idx.value + "|";
			}
		}
}

if(selbasket == ""){
	alert("삭제할 주문을 선택하지 않았습니다.");
	return;
}else{
	if(confirm("선택한 주문을 정말 삭제하시겠습니까? \n\n삭제 시 주문상세 내역에서도 삭제됩니다.")){
		document.location = "order_save.php?mode=delete_basket&selbasket=" + selbasket + "&<?=$menucodeParam?>";
	}else{
		return;
	}
}
return;

}

// 선택주문 상태변경
function batchStatus(){

var i;
var selbasket = "";
for(i=0;i<document.forms.length;i++){
	if(document.forms[i].idx != null){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].select_checkbox.checked)
				selbasket = selbasket + document.forms[i].idx.value + "|";
			}
		}
}

if(selbasket == ""){
	alert("변경할 항목을 선택하지 않았습니다.");
	return;
}else{
	var url = "basket_status.php?selbasket=" + selbasket;
	window.open(url,"basketStatus","height=150, width=250, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}
return;

}

// 기간설정
function setPeriod(from,to,start,end,type){

	if(from == '' && to == ''){
		document.frm.s_status.value = 'ALL';
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	} else {
		document.frm.s_type.value = type;
		$("#srh_prev").val(from);
		$("#srh_next").val(to);
	}
}

function viewCancel(idx){

	var ccontent = "#ccontent_"+idx;
		if($(ccontent).css("display") == "none"){
		$(ccontent).show();
	} else {
		$(ccontent).hide();
	}

}

-->
</script>

	<table border="0" cellspacing="0" cellpadding="2">
	  <tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">주문취소목록</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">개별취소 목록 입니다.</td>
	  </tr>
	</table>

	<br>
     <form name="frm" action="<?=$PHP_SELF?>" method="get">
     <input type="hidden" name="page"    value="">
     <input type="hidden" name="status"  value="<?=$status?>">
     <input type="hidden" name="reason"  value="<?=$reason?>">
     <input type="hidden" name="s_type" value="<?=$s_type?>">
     <input type="hidden" name="menucode" value="<?=$menucode?>">

	 <table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
       <tr>
         <td width="15%" class="t_name">진행상태</td>
         <td width="85%" class="t_value">

           <table>
           <tr><td>
           <!-- <input type="button" onClick="chgStatus('ALL');" value="전체목록" <? if($status == "") echo "class=btn_all"; else echo "class=btn_all"; ?>> -->
           <input type="button" onClick="chgStatus('CA');" value="취소신청" <? if($status == "CA") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('CI');" value="처리중" <? if($status == "CI") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('CC');" value="취소완료" <? if($status == "CC") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           </td></tr>
           </table>

         </td>
       </tr>
       <tr>
         <td width="15%" class="t_name">취소사유</td>
         <td width="85%" class="t_value">

           <table>
           <tr><td>
           <!-- <input type="button" onClick="chgReason('');" value="전체목록" <? if($reason == "") echo "class=btn_all"; else echo "class=btn_all"; ?>> -->
           <input type="button" onClick="chgReason('고객변심');" value="고객변심" <? if($reason == "고객변심") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('품절');" value="품절" <? if($reason == "품절") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('배송지연');" value="배송지연" <? if($reason == "배송지연") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('이중주문');" value="이중주문" <? if($reason == "이중주문") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('시스템오류');" value="시스템오류" <? if($reason == "시스템오류") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('누락배송');" value="누락배송" <? if($reason == "누락배송") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('택배분실');" value="택배분실" <? if($reason == "택배분실") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('상품불량');" value="상품불량" <? if($reason == "상품불량") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgReason('기타');" value="기타" <? if($reason == "기타") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           </td></tr>
           </table>

         </td>
       </tr>
       <tr>
         <td class="t_name">기 간</td>
		 <td class="t_value">
			<?
			$yes_day      = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*1));
			$to_day       = date('Y-m-d');
			$week_day     = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*7));
			$month_day    = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*30));
			$twomonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));
			$sixmonth_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*182));
			$prevyear_day = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*365));

			if(!empty($srh_prev)) $week_day2 = $srh_prev; else $week_day2 = $twomonth_day;
			if(!empty($srh_next)) $to_day2   = $srh_next; else $to_day2   = $to_day;

			?>
			<span class="calendar">
			<input type="text" name="srh_prev" id="srh_prev" class="datepicker-here input2" size="15" value="<?=$week_day2?>"> ~ 
			<input type="text" name="srh_next" id="srh_next" class="datepicker-here input2" size="15" value="<?=$to_day2?>">

			<input type="submit" onClick="setPeriod('<?=$to_day?>','<?=$to_day?>','srh_prev','srh_next',1)" value="오늘" <? if($s_type == "1") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$yes_day?>','<?=$to_day?>','srh_prev','srh_next',2)" value="어제" <? if($s_type == "2") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$week_day?>','<?=$to_day?>','srh_prev','srh_next',3)" value="1주일" <? if($s_type == "3") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$month_day?>','<?=$to_day?>','srh_prev','srh_next',4)" value="1개월" <? if($s_type == "4") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$sixmonth_day?>','<?=$to_day?>','srh_prev','srh_next',5)" value="6개월" <? if($s_type == "5") echo "class=sm_per"; else echo "class=m_per"; ?>>
			<input type="submit" onClick="setPeriod('<?=$prevyear_day?>','<?=$to_day?>','srh_prev','srh_next',6)" value="1년" <? if($s_type == "6") echo "class=sm_per"; else echo "class=m_per"; ?>>
			</span>
		 </td>
       </tr>
       <tr>
         <td class="t_name">조건검색</td>
         <td class="t_value">
             <select name="searchopt" class="select2">
             <option value="wb.orderid" <? if($searchopt == "wb.orderid") echo "selected"; ?>>주문번호
             <option value="wo.send_name" <? if($searchopt == "wo.send_name") echo "selected"; ?>>주문자
             <option value="wb.prdname" <? if($searchopt == "wb.prdname") echo "selected"; ?>>상품명
             <option value="wb.prdcode" <? if($searchopt == "wb.prdcode") echo "selected"; ?>>상품코드
             <option value="wb.bank" <? if($searchopt == "wb.bank") echo "selected"; ?>>환불은행
             <option value="wb.account" <? if($searchopt == "wb.account") echo "selected"; ?>>환불계좌명
             <option value="wb.acc_name" <? if($searchopt == "wb.acc_name") echo "selected"; ?>>예금주
             </select>
             <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
         </td>
       </tr>
     </table>
	<br>
	<table width="100%" cellspacing="1" cellpadding="3" border="0">
		<tr>
			<td align="center">
				<input type="submit" value="검색" class="search_btn2">&nbsp;
				<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
			</td>
		</tr>
	</table>

	 </form>

     <br>
			<?
			$sql = "select idx from wiz_basket where status != '' and (status = 'CA' or status = 'CI' or status = 'CC')";
			$all_total = sql_fetch_rows($sql);

			$prev_period = $week_day2;
			$next_period = $to_day2." 23:59:59";

			if(!empty($status)){

				if($status == 'CA')        $cansearch_sql = "wb.ca_date";
				else if($status == 'CI')   $cansearch_sql = "wb.ci_date";
				else if($status == 'CC')   $cansearch_sql = "wb.cc_date";

				
				if($status == 'ALL'){
					$period_sql  = "";
				} else {
					$period_sql  = " and $cansearch_sql >= '$prev_period' and $cansearch_sql <= '$next_period' ";
				}

			} else {
				$period_sql  = " and wb.ca_date >= '$prev_period' and wb.ca_date <= '$next_period'";
			}

			if($status == "" || $status == 'ALL') $status_sql = "and wb.status != ''";
			else $status_sql = "and wb.status = '$status'";

			if($reason != "") $reason_sql = "and wb.reason = '$reason'";

			if($searchopt && $searchkey) $searchopt_sql = " and $searchopt like '%$searchkey%'";

			$sql = "
				
				select
				
					wb.*,
					wo.send_name
					
				from
				
					wiz_basket as wb left join wiz_order as wo on wb.orderid = wo.orderid
					
				where
				
					(wb.status = 'CA' or wb.status = 'CI' or wb.status = 'CC') 
					$status_sql 
					$period_sql 
					$searchopt_sql 
					$reason_sql 
					order by wb.ca_date desc
					
			";

			$result = query($sql);
			$total = sql_fetch_rows($sql);

			if(!empty($tmp_rows)) $tmp_rows = $tmp_rows; else $tmp_rows = 20;
			$rows = $tmp_rows;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if($page < 1 || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			if($start>1) mysqli_data_seek($result,$start);
			?>

	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><span class="title_msg">총 취소건수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색결과 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
			<td align="right">
			<form>
				<select name="tmp_rows" onchange="location.href='?<?=$param?>&tmp_rows='+this.value" class="select">
					<option value="10"  <? if($tmp_rows==10)  echo "selected";?>>10개씩 출력</option>
					<option value="20"  <? if($tmp_rows==20)  echo "selected";?>>20개씩 출력</option>
					<option value="30"  <? if($tmp_rows==30)  echo "selected";?>>30개씩 출력</option>
					<option value="50"  <? if($tmp_rows==50)  echo "selected";?>>50개씩 출력</option>
					<option value="70"  <? if($tmp_rows==70)  echo "selected";?>>70개씩 출력</option>
					<option value="100" <? if($tmp_rows==100) echo "selected";?>>100개씩 출력</option>
				</select>

			</form>
			</td>
		</tr>
      	<tr><td height=5></td></tr>
	</table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<form>
      	<tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="2%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
          <th width="7%">취소사유</th>
          <th width="8%">주문번호</th>
          <th>상품명</th>
          <th width="25%">옵 션</th>
          <th width="10%">취소요청일</th>
          <th width="10%">취소완료일</th>
          <th width="7%">상품가격</th>
          <th width="5%">수량</th>
          <th width="13%">주문상태</th>
        </th>
        <tr><td class="t_rd" colspan="20"></td></tr>
      	</form>
		<?
		while(($row = sql_fetch_obj($result)) && $rows){
			if($row->status == "CA") $stacolor = "6DCFF6";
			else if($row->status == "CI") $stacolor = "BD8CBF";
			else if($row->status == "CC") $stacolor = "ED1C24";
			else $stacolor = "";

		?>
		<form action="order_save.php" name="<?=$row->idx?>" method="get">
        <input type="hidden" name="mode"       value="cancel_status">
        <input type="hidden" name="page"       value="<?=$page?>">
        <input type="hidden" name="orderid"    value="<?=$row->orderid?>">
        <input type="hidden" name="idx"        value="<?=$row->idx?>">

        <input type="hidden" name="status"     value="<?=$status?>">
        <input type="hidden" name="reason"     value="<?=$reason?>">
        <input type="hidden" name="srh_prev"   value="<?=$srh_prev?>">
        <input type="hidden" name="srh_next"   value="<?=$srh_next?>">
        <input type="hidden" name="searchopt"  value="<?=$searchopt?>">
        <input type="hidden" name="searchkey"  value="<?=$searchkey?>">
        <input type="hidden" name="menucode"   value="<?=$menucode?>">

        <tr><td height="4"></td></tr>
        <tr>
          <td align="center" height="27"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><?=$row->reason?></td>
          <td align="center"><a href="order_info.php?orderid=<?=$row->orderid?>&page=<?=$page?>&<?=$param?>"><?=$row->orderid?></a></td>
          <td align="center">
          	<?=cut_str($row->prdname, 30) ?>
          </td>
          <td>
	        <?

				list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3);
				$optcode3_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode3_v2)."원)</span>";
				list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4);
				$optcode4_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode4_v2)."원)</span>";
				list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8);
				$optcode8_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode8_v2)."원)</span>";
				list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9);
				$optcode9_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode9_v2)."원)</span>";
				list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10);
				$optcode10_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode10_v2)."원)</span>";
				list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11);
				$optcode11_v2 = "<span class='pay_add_tit2'>(+".number_format($t_optcode11_v2)."원)</span>";

				if($row->opttitle5 != '')  echo "<span class=pay_add_tit>$row->opttitle5 : $row->optcode5</span>, " ;
				if($row->opttitle6 != '')  echo "<span class=pay_add_tit>$row->opttitle6 : $row->optcode6</span>, " ;
				if($row->opttitle7 != '')  echo "<span class=pay_add_tit>$row->opttitle7 : $row->optcode7</span>, <br>" ;

				if($row->opttitle3 != '')  echo "<span class=pay_add_tit>$row->opttitle3 : $optcode3_v</span> $optcode3_v2, " ;
				if($row->opttitle4 != '')  echo "<span class=pay_add_tit>$row->opttitle4 : $optcode4_v</span> $optcode4_v2, <br>" ;
				if($row->opttitle8 != '')  echo "<span class=pay_add_tit>$row->opttitle8 : $optcode8_v</span> $optcode8_v2, " ;
				if($row->opttitle9 != '')  echo "<span class=pay_add_tit>$row->opttitle9 : $optcode9_v</span> $optcode9_v2 ,<br>" ;
				if($row->opttitle10 != '') echo "<span class=pay_add_tit>$row->opttitle10 : $optcode10_v</span> $optcode10_v2, " ;
				if($row->opttitle11 != '') echo "<span class=pay_add_tit>$row->opttitle11 : $optcode11_v</span> $optcode11_v2, <br>" ;

				list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
				if($t_optcode_v2 != 0){
					$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
				} else {
					$optcode_v2 = "";
				}

				if($row->opttitle != '') echo "<span class=pay_add_tit>".$row->opttitle."</span>";
				if($row->opttitle != '' && $row->opttitle2 != '') echo "/";
				if($row->opttitle2 != '') echo "<span class=pay_add_tit>".$row->opttitle2."</span>";
				if($row->opttitle != '' || $row->opttitle2 != '') echo " : <span class=pay_add_tit>".$optcode_v."".$optcode_v2."</span>";
		 	?>
		  </td>
          <td align="center"><?=$row->ca_date?></td>
          <td align="center"><?=$row->cc_date?></td>
          <td align="right"><?=number_format($row->prdprice)?>원&nbsp;</td>
          <td align="center"><?=number_format($row->amount)?></td>
          <td align="center">

          <table cellpadding="2"><tr><td bgcolor=<?=$stacolor?>>
          <select name="chg_status" style="width:90" class="select2">
			  <option value="CA" <? if($row->status == "CA") echo "selected"; ?>>취소신청</option>
			  <option value="CI" <? if($row->status == "CI") echo "selected"; ?>>처리중</option>
			  <option value="CC" <? if($row->status == "CC") echo "selected"; ?>>취소완료</option>
          </select>
          </td>
          <td>
          	<input type="image" src="../image/btn_apply_s.gif" align="absmiddle">
          	<img src="../image/btn_view_s.gif" style="cursor:hand" align="absmiddle" onClick="viewCancel('<?=$row->idx?>')">
          </td>
          </tr></table>
          </td>
          <td align="center"></td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
       	<tr bgcolor="#FFFFFF" id="ccontent_<?=$row->idx?>" style="display:none">
          <td height="30" colspan="10" style="padding:3px">
            <table border="0"width="100%" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" height="30" align="align" class="t_name">취소사유</td>
                <td class="t_value"><?=$row->reason?></td>
              </tr>
              <tr>
                <td height="30" align="align" class="t_name">메모</td>
                <td class="t_value"><?=$row->memo?></td>
              </tr>
				<?
				if(!empty($row->repay)) {
					if(!strcmp($row->repay, "R")) $repay = "적립금";
					if(!strcmp($row->repay, "C")) $repay = "계좌이체";

				?>
              <tr>
                <td width="100" height="30" align="align" class="t_name">환불방법</td>
                <td class="t_value" colspan="9"><?=$repay?></td>
              </tr>
				<?
				}
				if(!empty($row->bank)) {
				?>
              <tr>
                <td width="100" height="30" align="align" class="t_name">은행명</td>
                <td class="t_value"><?=$row->bank?></td>
                <td width="100" align="align" class="t_name">계좌번호</td>
                <td class="t_value"><?=$row->account?></td>
                <td width="100" align="align" class="t_name">예금주</td>
                <td class="t_value"><?=$row->acc_name?></td>
              </tr>
			<?
			}
			?>
            </table>
          </td>
        </tr>
        </form>
        <?
        		$no--;
            $rows--;
         }

       	if($total <= 0){
       	?>
       	<tr><td height=30 colspan=10 align=center>개별취소 항목이 없습니다.</td></tr>
       	<tr><td colspan="20" class="t_line"></td></tr>
       	<?
       	}
				?>
			</table>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="5"></td></tr>
			 <tr>
			   <td width="30%">
				 <input type="button" value="선택삭제" class="btnListchk gray2" onclick="basketDelete()">
				 <input type="button" value="상태일괄변경" class="btnListchk" onclick="batchStatus()">
			   </td>
			   <td width="30%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
			   <td width="30%"></td>
			 </tr>
			</table>

<? include "../foot.php"; ?>