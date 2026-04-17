<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$page_name = "사이트 관리정보";
$page_desc = "SMS충전 합니다.";
$navi_name = " 기본설정 > SMS충전";
?>
<? include "../head.php"; ?>


<script language="javascript">
<!--
function inputCheck(frm){

	//if(frm.sms_id.value == ""){
	//	alert("sms아이디를 입력하세요.");
	//	frm.sms_id.focus();
	//	return false;
	//}

}

function popJoin(stype) {

	if(stype == "J"){
		document.smsjoin.action = "http://www.icodekorea.com/res/join_company_fix.php";
		window.open("", "popJoin", "height=700, width=617, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	}else{
		window.open("", "popJoin", "height=600, width=617, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	}

	document.smsjoin.submit();

}

function popFill() {
   window.open("", "popFill", "height=500, width=667, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
   document.smsfill.submit();
}

-->
</script>
</head>

	<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">SMS관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">SMS를 충전합니다.</td>
        </tr>
      </table>

      <form name="frm" action="sms_info_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            	<tr>
                <td width="17%" align="left" class="t_name">SMS 종류</td>
                <td colspan="3" class="t_value">
                	<table width="100%">
                		<tr>
                			<td>
                				<span style="vertical-align: middle;"><input name="sms_type" type="radio" value="C" <? if($site_info['sms_type'] == "" || $site_info['sms_type'] == "C") echo "checked"; ?>></span><b>충전제</b> (건당 20원, 10만원 충전시 5000건 발송)
                			</td>
                			<td>
								<input type="button" value="SMS신청하기" class="base_btm reg" onClick="popJoin('C')">
								<input type="button" value="SMS충전하기" class="base_btm reg" onClick="popFill()">
                			</td>
                			<td rowspan="2" align="right">
 								
                			</td>
                		</tr>
                		<!-- <tr>
                			<td>
                				<input name="sms_type" type="radio" value="J" <? if($site_info['sms_type'] == "J") echo "checked"; ?>><b>정액제</b> (건당 20원, 월50,000원으로 2500건)
                			</td>
                			<td>
                				<img src="../image/btn_sms_apply2.gif" style="cursor:hand" onClick="popJoin('J')">
                			</td>
                		</tr> -->
                	</table>
					<?
					/*
					작업자		: 임서연
					작업일시		: 2020-03-05
					작업내용		: LMS 발송용 금액 안내 추가(수정 반영 작업)
					*/
					?>
					<div style="margin-top:5px; font-weight:normal; background:#f6f6f6; padding:10px;">
						<strong style="color:#333; font-weight:bold;">※ 발송요금</strong><br>- SMS(단문) : 20원 (최대 90byte / 한글 45자)<br>- LMS(장문) : 50원 (최대 2,000byte / 한글 1,000자)
					</div>
                </td>
              </tr>
              <tr>
                <td width="150" align="left" class="t_name" height="25">SMS 아이디</td>
                <td class="t_value">
                	way_<input name="sms_id" value="<?=str_replace("way_","",$site_info['sms_id'])?>" type="text" style="width:163" class="input">&nbsp;
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name" height="25">SMS 비밀번호</td>
                <td class="t_value"><input name="sms_pw" value="<?=$site_info['sms_pw']?>" type="password" size="30" class="input"></td>
              </tr>
			<?
			/*
			작업자		: 임서연
			작업일시		: 2020-03-05
			작업내용		: LMS 발송용 토큰 추가(수정 반영 작업)
			*/
			?>
			<?if($wiz_admin['designer'] == 'Y') { ?>
              <tr>
                <td align="left" class="t_name" height="25">SMS 토큰</td>
                <td class="t_value"><input name="sms_token" value="<?=$site_info['sms_token']?>" type="text" size="50" class="input"></td>
              </tr>
			  <? } ?>
			  <? /*2020-03-05 end*/?>
            </table>
          </td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0">
      	<tr><td align="center"><input type="submit" value="확인" class="base_btn reg"></td></tr>
      </table>
	  </form>

      <br>
		<table width="100%" border="0" cellspacing="10" cellpadding="8" bgcolor="9d9d9d" align="center">
			<tr>
				<td align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="6">
						<tr>
							<td colspan="2" align="center" style="border-bottom:1px solid #b1b1b1"><img src="../image/sms_info.gif" usemap="#SMS" />
								<map name="SMS" id="SMS"><area shape="rect" coords="369,13,553,58" href="http://web2002.co.kr/customer/faq.php?code=faq&category=&searchopt=subject&searchkey=%BB%E7%C0%FC&x=22&y=21" target="_blank" /><area shape="rect" coords="368,63,555,108" href="http://www.icodekorea.com/?ctl=login&r_url=http://www.icodekorea.com/callback_reg/number_register.php" target="_blank" /></map>
							</td>
						</tr>

						<tr>
							<td class="chk_alt">
								<font color=#000000><b>주의사항</b></font><br>
								- sms 아이디,비밀번호를 입력후 저장해야 충전이 가능합니다.<br>
								- 발송결과조회을 클릭하시면 발송한 sms에 대한 내용을 확인할 수 있습니다.<br>
								- 발송이 되지 않는 경우 방화벽에서 IP(211.172.232.124)와 Port(7192~7195)를 확인해보세요.<br>
								- 시스템상에서 잔액이 없을 시 자동으로 서비스가 종료됩니다.<br>
								- 회신번호는 "기본정보>관리자 휴대폰" 번호이므로 사용하고 계신 번호로 변경하시기 바랍니다.<br>
								- SMS서비스는 <a href="http://icodekorea.com" target="_blank">아이코드(http://icodekorea.com)</a>와 제휴하여 제공됩니다.<br>
									  - 비용/계산서관련 문의는 아이코드 연락하시기 바랍니다.
								</td>
								<td class="chk_alt" valign="top">
								<font color=#000000><b>사용법</b></font><br>
								<?
								/*
								작업자		: 임서연
								작업일시		: 2020-03-05
								작업내용		: 안내문구 수정(수정 반영 작업)
								*/
								?>
								1. 신청하기 버튼을 눌러 신청서를 작성, SMS를 충전 합니다.<br>
								2. <a href="https://www.icodekorea.com/?ctl=user_token" target="_blank">아이코드(https://www.icodekorea.com/?ctl=user_token)</a>사이트에서 토큰키를 확인합니다.<br>
								3. ID와 암호, 토큰키를 각각 입력한 후 저장합니다.<br>
								4. 1~3번을 다 마치시면 SMS 발송이 가능합니다.<br><br><br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center">
			<?php
			/*
			작업자		: 임서연
			작업일시		: 2020-03-05
			작업내용		: token 값 있을 때는 토큰값 이용, 없을때는 아이디/비번만 이용하여 조회하도록 수정 (LMS는 토큰 필수, SMS는 아이디/비번만으로도 발송됨)(수정 반영 작업)
			*/
			if ($site_info['sms_use'] == 'Y' && ($site_info['sms_token'] || ($site_info['sms_id'] && $site_info['sms_pw']))) {
				if($site_info['sms_token']) {
				
					$icodeinfo = get_icode_info($site_info['sms_token']);
					$coin      = $icodeinfo['coin'];
					$send_cnt  = $icodeinfo['send_cnt'];
					$fee       = $icodeinfo['fee'];
				
			?>

			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td width="25"><img src="../image/ic_tit.gif"></td>
					<td valign="bottom" align="left" class="tit">충전내용 </td>
					<td width="2"></td>
					<td align="right"><button onClick="window.location.reload()" class="btn_restart">새로고침</button></td>
				</tr>
				<tr><td height="5"></td></tr>
			</table>

			<table width="100%" border="0" cellpadding="3" cellspacing="1" class="t_style">
				<tr align="center">
					<th width="25%" class="t_name">요금제</td>
					<th width="25%" class="t_name">잔액</td>
					<th width="25%" class="t_name">건당비용</td>
					<th width="25%" class="t_name">남은SMS건수</td>
				</tr>
				<tr align="center"> 
					<td height="30" align="center">충전제</td>
					<td><?php echo number_format($coin) ?> 원</td>
					<td><?php echo $fee ?>원</td>
					<td><?php echo number_format($send_cnt) ?> 건</td>
				</tr>
			</table>
			<?php
				} else if($site_info['sms_id'] && $site_info['sms_pw']) {
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center">
         	<iframe src="http://www.icodekorea.com/res/userinfo.php?userid=<?=$site_info['sms_id']?>&userpw=<?=$site_info['sms_pw']?>&r_url=http://<?=$_SERVER['HTTP_HOST']?>/twcenter/manage/basic/sms_info.php" frameborder="0" width="100%" height="400"></iframe>
       </tr>
      </table>
<?
				}
			}

			/* 임서연 2020-03-05 end */

?>
		</td>
       </tr>
      </table>
			<!-- sms신청하기 -->
			<form name="smsjoin" method="post" target="popJoin" action="https://icodekorea.com/res/join_company_fix_a.php">
			<input type="hidden" name="sellid" value="web2002">
			</form>

			<!-- sms충전하기 -->
			<form name="smsfill" method="post" target="popFill" action="https://icodekorea.com/company/credit_card_input.php">
			</form>
<? include "../foot.php"; ?>