<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/bankda_info.php";
include_once"../head.php";

include_once $_SERVER['DOCUMENT_ROOT']."/comm/API/xml/xml.php";

$bk_sql = "select count(idx) as cnt from bankda_member ";
$bk_res = query($bk_sql);
$bk_row = sql_fetch_arr($bk_res);
$bk_cnt = $bk_row['cnt'];

$param = array(
	'service_type'  => $oper_info['bankda_service'],
	'partner_id'    => $oper_info['bankda_partner_id'],
	'user_id'       => $bankda_info['bankda_id'],
	'user_pw'       => $bankda_info['bankda_pw'],
	'char_set'      => 'utf-8'
);


$get_url = "https://ssl.bankda.com/partnership/partner/account_list_userid_xml.php";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $get_url);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($curl);

$parser = new myXMLParser($xml);
$parser->Parse();

//echo "<xmp>";
//print_r($parser->document->account);
//echo "</xmp>";
foreach($parser->document->account as $account) {
	$total = $account->tagAttrs['count'];
}


?>
<script>
function bankdaMem(t){

	var w = 800;
	var h = 500;

	if(t == 'A')      url = "bankda_service_add.php";
	else if(t == "M") url = "bankda_service_modify.php";
	else if(t == "D") url = "bankda_service_delete.php";

	var window_left = (screen.availWidth - w) / 2;
	var window_top  = (screen.availHeight - h) / 2;

	window.open(url,"accountAdd","width=" + w +", height=" + h + ", scrollbars=yes,top=" + window_top + ",left=" + window_left);
}

function accountPop(t,i){

	var w = 800;
	if(t == 'A') var h = 650;
	else         var h = 500;

	var window_left = (screen.availWidth - w) / 2;
	var window_top  = (screen.availHeight - h) / 2;
	var url;
	if(t == 'A')      url = "bankda_account_add.php";
	else if(t == "M") url = "bankda_account_modify.php?idx=" + i;
	else if(t == "D") url = "bankda_account_delete.php?idx=" + i;

	window.open(url,"account","width=" + w +", height=" + h + ", scrollbars=yes,top=" + window_top + ",left=" + window_left);
}

</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">자동입금확인 서비스</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt"></td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 계좌수 : <strong id="total_prd_cnt"><?php echo $total?></strong></span>
		</td>
		<td align="right">
		<?php
		if($oper_info['bankda_use'] == 'Y' && $wiz_admin['designer'] == 'Y') {
			if($bk_cnt <= 0) {
			?>
			<input type="button" value="서비스신청" class="btnListchk3" onClick="bankdaMem('A')"> 
			<?php
			}
			if($bk_cnt > 0) {
			?>
			<input type="button" value="회원정보수정" class="btnListchk3" onClick="bankdaMem('M')"> 
			<input type="button" value="서비스해지" class="btnListchk3" onClick="bankdaMem('D')">
			<input type="button" value="계좌추가" class="btnListchk3" onClick="accountPop('A')">
		<?php
			}
		}
		?>
		</td>
	</tr>
</table>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="20%">은행명(별명)</th>
		<th width="*">계좌번호</th>
		<th width="15%">계좌상태</th>
		<th width="15%">계좌조회결과</th>
		<th width="15%">최종조회</th>
		<th width="13%">기능</td>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<?php
	foreach($parser->document->account as $account) {
	
		foreach($account->account_info as $account_info) {

			$bkname            = $account_info->tagAttrs['bkname'];					//-- 은행명
			$renames           = $account_info->tagAttrs['renames'];				//-- 별명
			$actaccountnum     = $account_info->tagAttrs['actaccountnum'];			//-- 계좌번호
			$acttag            = $account_info->tagAttrs['acttag'];					//-- 계좌상태
			$act_status        = $account_info->tagAttrs['act_status'];				//-- 최종조회결과
			$regdate           = $account_info->tagAttrs['regdate'];				//-- 계좌등록일
			$last_scraping_dtm = $account_info->tagAttrs['last_scraping_dtm'];		//-- 최종조회일시
			$accounttype       = $account_info->tagAttrs['accounttype'];			//-- 개인 or 법인

			if($renames) $bkname .= " (".$renames.")";
			if($acttag == "오류") $t_acttag = "<font color='red'><strong>".$acttag."</strong></font>";
			else                 $t_acttag = "<strong>".$acttag."</strong>";

			if($act_status == 'Good') {
				$act_status_msg = "<font color='red'><strong>정상적으로 조회중입니다.</strong></font>";
			} else {
				$act_status_msg = "정상적인 계좌입니다.";
			}

			$bk_sql = "select idx from bank_account where bkacctno = '".$actaccountnum."' ";
			$bk_res = query($bk_sql);
			$bk_row = sql_fetch_obj($bk_res);

	?>
	<tr>
		<td height="38" align="center"><strong><?php echo $bkname ?></strong></td>
		<td align="center"><?php echo $actaccountnum ?></td>
		<td align="center"><?php echo $t_acttag ?></td>
		<td align="center"><?php echo $act_status_msg ?></td>
		<td align="center"><?php echo $last_scraping_dtm ?></td>
		<td align="center">
			<input type="button" value="수정" class="base_btm blue2" onclick="accountPop('M', '<?php echo $bk_row->idx ?>')"> 
			<input type="button" value="삭제" class="base_btm reg" onclick="accountPop('D', '<?php echo $bk_row->idx ?>')">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?php
		}
	}
	if($total <= 0) {
	?>
	<tr>
		<td height="38" align="center" colspan="6">-- 등록된 계좌가 없습니다 --</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<? include "../foot.php"; ?>