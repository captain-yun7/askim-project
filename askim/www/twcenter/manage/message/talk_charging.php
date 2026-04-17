<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$page_name = "사이트 관리정보";
$page_desc = "알림톡을 충전합니다.";
$navi_name = " 회원관리 > 알림톡충전";
?>
<? include "../head.php"; ?>


<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.alimtalk_id.value == ""){
		alert("알림톡 아이디를 입력하세요.");
		frm.alimtalk_id.focus();
		return false;
	}
	if(frm.alimtalk_pw.value == ""){
		alert("알림톡 비밀번호를 입력하세요.");
		frm.alimtalk_pw.focus();
		return false;
	}

}

function popFill() {
	<?php
	if($site_info['alimtalk_use'] == 'N' || $site_info['alimtalk_url'] == '' || $site_info['alimtalk_senderkey'] == '' || $site_info['alimtalk_custgubun'] == '') {
	?>
		alert('알림톡 설정값이 셋팅되어있지 않습니다.');
		return false;
	<?php } ?>
	if(!$(':input:radio[name=alimtalk_pay]:checked').val()) {
		alert('알림톡 충전건수를 선택해주세요');
		return false;
	} else {
		var alimtalk_pay = $(':input:radio[name=alimtalk_pay]:checked').val();
		$("#palimtalk_pay").val(alimtalk_pay);
	}

<? if(!empty($site_info['alimtalk_id']) && !empty($site_info['alimtalk_pw'])) { ?>
	window.open("", "popFill", "height=600, width=667, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	document.alimtalkfill.submit();
<? } else { ?>
	alert("알림톡아이디와 비밀번호를 입력하세요.");
<? } ?>

}

-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">알림톡 충전관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt"><?php echo $page_desc ?></td>
        </tr>
      </table>

      <br>
      <form name="frm" action="talk_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
      <input type="hidden" name="prevalimtalk" value="<?php echo $site_info['alimtalk_pw'] ?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            	<tr>
                <td width="17%" align="left" class="t_name">충전종류</td>
                <td colspan="3" class="t_value">
                	<table width="100%">
                		<tr>
                			<td>
                				<span style="vertical-align: middle">
								<input name="alimtalk_type" type="radio" value="C" <? if($site_info['alimtalk_type'] == "" || $site_info['alimtalk_type'] == "C") echo "checked"; ?>></span><b>충전제</b> (건당 15원)<br>
								<span style="vertical-align: middle"><input type="radio" name="alimtalk_pay" value="1000|16500"><b>1,000건 = 16,500원</b> (부가세포함)</span><br>
								<span style="vertical-align: middle"><input type="radio" name="alimtalk_pay" value="2000|33000"><b>2,000건 = 33,000원</b></span><br>
								<span style="vertical-align: middle"><input type="radio" name="alimtalk_pay" value="3000|49500"><b>3,000건 = 49,500원</b></span><br>
								<span style="vertical-align: middle"><input type="radio" name="alimtalk_pay" value="4000|66000"><b>4,000건 = 66,000원</b></span><br>
								<span style="vertical-align: middle"><input type="radio" name="alimtalk_pay" value="5000|82500"><b>5,000건 = 82,500원</b></span>
							</td>
                			<td rowspan="2" align="right">
								<input type="button" value="알림톡 신청하기" class="base_btm reg" onClick="popFill()">
								<input type="button" value="발송결과조회" class="base_btm blue" onClick="location.href='talk_message_list.php'">

                			</td>
                		</tr>
                	</table>
                </td>
              </tr>
              <tr>
                <td width="150" align="left" class="t_name" width="120" height="25">알림톡아이디</td>
                <td class="t_value">
                	<input name="alimtalk_id" value="<?php echo $site_info['alimtalk_id'] ?>" type="text" style="width:163" class="input"<?if($wiz_admin['designer'] !="Y") echo " readonly";?>>&nbsp;
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name" width="120" height="25">알림톡비밀번호</td>
                <td class="t_value"><input name="alimtalk_pw" value="<?php echo $site_info['alimtalk_pw'] ?>" type="password" size="30" class="input"<?if($wiz_admin['designer'] !="Y") echo " readonly";?>></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0">
      	<tr><td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
			</td></tr>
      </table>
	  </form>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center">
			<?php
			include_once($_SERVER['DOCUMENT_ROOT']."/twcenter/dbclass.php");
			$alimDB = new DB($AlimTalkDBConf);
			
			if($alimDB->getDbHost()) {

				$res = $alimDB->query("SELECT * FROM wiz_talk_charging WHERE alimtalk_id='".$site_info['alimtalk_id']."' AND alimtalk_pw='".$site_info['alimtalk_pw']."'");

				$row = $alimDB->fetch_array($res);

				print_r($row);

				$remain_cnt = $row['remain_cnt'];
				$remain_pay = $row['remain_pay'];
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td width="25"><img src="../image/ic_tit.gif"></td>
					<td valign="bottom" align="left" class="tit">알림톡 충전내용 </td>
					<td width="2"></td>
					<td align="right"><button onClick="window.location.reload()" class="btn_restart">새로고침</button></td>
				</tr>
				<tr><td height="5"></td></tr>
			</table>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="t_rd" colspan=6></td></tr>
				<tr class="t_th">
					<th width="25%">요금제</td>
					<th width="25%">건당비용</td>
					<th width="25%">잔액</td>
					<th width="25%">남은건수</td>
				</tr>
				<tr><td class="t_rd" colspan=6></td></tr>
				<tr align="center"> 
					<td height="30" align="center">충전제</td>
					<td>15원</td>
					<td><?php echo number_format($remain_pay ?? 0) ?> 원</td>
					<td><?php echo number_format($remain_cnt ?? 0) ?> 건</td>
				</tr>
				<tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
			</table>
			<?php 
			$alimDB->close();
			}
			?>
		 </td>
	   </tr>
      </table>
			<!-- 알림톡충전하기 -->
			<form name="alimtalkfill" method="post" target="popFill" action="https://www.web2002.co.kr/kcp_v6_talk/sample/alimtalk.order.php">
			<input type="hidden" name="alimtalk_id" value="<?php echo $site_info['alimtalk_id'] ?>">
			<input type="hidden" name="alimtalk_pw" value="<?php echo $site_info['alimtalk_pw'] ?>">
			<input type="hidden" name="alimtalk_profilename" value="<?php echo $site_info['site_name'] ?>">
			<input type="hidden" name="palimtalk_pay" id="palimtalk_pay">
			</form>

<? include "../foot.php"; ?>