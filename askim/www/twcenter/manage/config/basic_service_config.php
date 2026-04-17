<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

if(version_compare(phpversion(), '5.3.0', '>=')) { 
	$width = '35%';
} else {
	$width = '85%';
}

## 아이코드 잔여코인체크
if ($site_info['sms_use'] == 'Y' && ($site_info['sms_token'] || ($site_info['sms_id'] && $site_info['sms_pw']))) {
	
	$icodeinfo = get_icode_info($site_info['sms_token']);
	
	if($icodeinfo['plan']) {

		if ($icodeinfo['plan'] == 'A') {
			$sms_coin = '<span id="sms_coin">충전식' . ' / ' . number_format($icodeinfo['coin']).' 원 ';
			$sms_coin .= '<a href="https://icodekorea.com/company/credit_card_input.php?icode_id='.$site_info['sms_id'].'&icode_passwd='.$site_info['sms_pw'].'" target="_blank"><input type="button" value="충전하기" class="base_btn4 icode"></a></span>';
			$sms_join  = '';

		} else if ($icodeinfo['plan'] == 'C') {
			$sms_coin = '<span id="sms_coin">정액제' . ' / ' . number_format($icodeinfo['coin']).' 원</span><span class="sp_tab"></span>';
			$sms_join  = '';

		} else {
			$sms_coin  = '';
			$sms_join  = '';
		}

	} else {

		// 충전식 형태로만 할경우 밖으로 빼냄
		$sms_coin = '<span id="sms_coin">충전식' . ' / ' . number_format($icodeinfo['coin']).' 원 ';
		$sms_coin .= '<a href="https://icodekorea.com/company/credit_card_input.php?icode_id='.$site_info['sms_id'].'&icode_passwd='.$site_info['sms_pw'].'" target="_blank"><input type="button" value="충전하기" class="base_btn4 icode"></a></span>';
		$sms_join  = '';

	}

} else {
	$sms_coin = '';
	$sms_join = '<a href="https://www.icodekorea.com/?ctl=user_sign_on&act=agree&type=A&sellid=" target="_blank"><input type="button" value="회원가입" class="base_btn4 icode"></a>';
}

?>
<script>
function snsuse(){

	var snsUse = $(':radio[name="sns_use"]:checked').val();
	if(snsUse == "Y"){
		$("#s_show1").attr("disabled",false);
		$("#s_show2").attr("disabled",false);
		$("#s_show3").attr("disabled",false);
		$("#s_show4").attr("disabled",false);
	}else{
		$("#s_show1").attr("disabled",true);
		$("#s_show2").attr("disabled",true);
		$("#s_show3").attr("disabled",true);
		$("#s_show4").attr("disabled",true);
	}

}

function snsLoginuse(){

	var snsLoginUse = $(':radio[name="sns_login_use"]:checked').val();
	if(snsLoginUse == "Y"){
		$("#SL_show1").attr("disabled",false);
		$("#SL_show2").attr("disabled",false);
		$("#SL_show3").attr("disabled",false);
		$("#SL_show4").attr("disabled",false);
		$("#SL_show5").attr("disabled",false);
	}else{
		$("#SL_show1").attr("disabled",true);
		$("#SL_show2").attr("disabled",true);
		$("#SL_show3").attr("disabled",true);
		$("#SL_show4").attr("disabled",true);
		$("#SL_show5").attr("disabled",true);
	}

}

$(function() {

	$(".easypay_use").on("click", function() {
		var easypay_use = $('[name=easypay_use]:checked').val();
		if(easypay_use == "Y") {
			$("#kakao_use").show();
		} else {
			$("#kakao_use").hide();
		}
	});

	var site_easypay_use = "<?php echo $site_info['easypay_use'] ?>";
	if(site_easypay_use == "Y") {
		$("#kakao_use").css("display", "");
	} else {
		$("#kakao_use").css("display", "none");
	}

});

</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">부가서비스 설정</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">사이트운영에 필요한 부가서비스 정보를 설정합니다.</td>
	</tr>
</table>
<br>
<!-- <form name="frm" action="basic_service_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)"> -->
<form name="frm" action="basic_service_save.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif"> SNS 스크랩</td>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> SNS 로그인연동</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">SNS 사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sns_use" value="Y" <?php if($oper_info['sns_use'] == "Y") echo "checked"; ?> onclick="snsuse();"></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="sns_use" value="N" <?php if($oper_info['sns_use'] == "N") echo "checked"; ?> onclick="snsuse();"></span>사용안함&nbsp;
						<?php
						$sns_list = explode("/",$oper_info['sns_method']);
						for($ii=0; $ii<count($sns_list); $ii++){
							$sns_method[$sns_list[$ii]] = true;
						}

						$disabled = ($oper_info['sns_use'] == "Y") ? "" : "disabled";
						?>

						<span style="vertical-align: middle"><input type="checkbox" id="s_show1" name="sns_method[]" value="FB" <?php if($sns_method["FB"]==true) echo "checked";?> <?php echo $disabled?>></span>페이스북&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" id="s_show2" name="sns_method[]" value="TT" <?php if($sns_method["TT"]==true) echo "checked";?> <?php echo $disabled?>></span>트위터&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" id="s_show3" name="sns_method[]" value="KT" <?php if($sns_method["KT"]==true) echo "checked";?> <?php echo $disabled?>></span>카카오톡&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" id="s_show4" name="sns_method[]" value="KS" <?php if($sns_method["KS"]==true) echo "checked";?> <?php echo $disabled?>></span>카카오스토리
					</td>

					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sns_login_use" value="Y" <?php if($oper_info['sns_login_use'] == "Y") echo "checked"; ?> onclick="snsLoginuse();"></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="sns_login_use" value="N" <?php if($oper_info['sns_login_use'] == "N") echo "checked"; ?> onclick="snsLoginuse();"></span>사용안함&nbsp;
						<?php
						$sns_login_list = explode("/",$oper_info['sns_login_method']);
						for($ii=0; $ii<count($sns_login_list); $ii++){
							$sns_login_method[$sns_login_list[$ii]] = true;
						}

						$disabled = ($oper_info['sns_login_use'] == "Y") ? "" : "disabled";
						?>

						<!--span style="vertical-align: middle"><input type="checkbox" id="SL_show1" name="sns_login_method[]" value="FB" <?php if($sns_login_method["FB"]==true) echo "checked";?> <?php echo $disabled?>></span>페이스북&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" id="SL_show2" name="sns_login_method[]" value="TT" <?php if($sns_login_method["TT"]==true) echo "checked";?> <?php echo $disabled?>></span>트위터&nbsp;-->
						<span style="vertical-align: middle"><input type="checkbox" id="SL_show3" name="sns_login_method[]" value="KT" <?php if($sns_login_method["KT"]==true) echo "checked";?> <?php echo $disabled?>></span>카카오톡&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" id="SL_show4" name="sns_login_method[]" value="NH" <?php if($sns_login_method["NH"]==true) echo "checked";?> <?php echo $disabled?>></span>네이버
						<span style="vertical-align: middle"><input type="checkbox" id="SL_show5" name="sns_login_method[]" value="GG" <?php if($sns_login_method["GG"]==true) echo "checked";?> <?php echo $disabled?>></span>구글
					</td>
				</tr>
			</table>
			
			<?
				/*
					인스타그램 연동
					작업일 : 2021-02-03
					작업자 : 김나연
				*/
				$insta_redirect_uri = "https://".str_replace("www.", "", $HTTP_HOST)."/twcenter/insta/iauth.php";
				$token_link = "https://api.instagram.com/oauth/authorize?client_id=".$site_info['insta_client_id']."&redirect_uri=".$insta_redirect_uri."&scope=user_profile,user_media&response_type=code";
				$token_refresh_link = "https://".$HTTP_HOST."/twcenter/insta/token_refresh.php";
				if($site_info['insta_token_date']) {
					$day_diff = ((time() - strtotime($site_info['insta_token_date'])) / 60 / 60);		//생성시간
					$insta_expires_in = $site_info['insta_expires_in'];
					if(($day_diff > 24 && time() < $insta_expires_in-3600) ) { 
						$token_refresh_btn = ' &nbsp; <input type="button" class="base_btm reg" onclick=\'location.href="'.$token_refresh_link.'"\' value="토큰만료일 연장">';
					} else if (time() > $insta_expires_in) {
						$token_refresh_btn = ' &nbsp; <input type="button" class="base_btm reg" onclick=\'location.href="'.$token_link.'"\' value="토큰 재발급">';
					} else {
						$token_refresh_btn = '';
					}
				}
			?>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> 인스타그램 연동 (https 필수)</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">인스타그램 앱 ID</td>
					<td width="35%" class="t_value">
						<input type="text" name="insta_client_id" value="<?php echo $site_info['insta_client_id'] ?>" class="input" size="50">&nbsp;
					</td>
					<td width="15%" class="t_name">인스타그램 앱 시크릿코드</td>
					<td width="35%" class="t_value">
						<input type="text" name="insta_client_secret" value="<?php echo $site_info['insta_client_secret'] ?>" class="input" size="50">&nbsp;
					</td>
				</tr>
				
				<tr>
					<td class="t_name">인스타그램 access token</td>
					<td class="t_value" colspan="3" style="word-break:break-all"><input type="text" name="insta_access_token" value="<?=$site_info['insta_access_token']?>" class="input" size="100"> &nbsp; 
						<? if($site_info['insta_client_id'] && $site_info['insta_client_secret']) { ?>
						<input type="button" class="base_btm reg" onclick="location.href='<?=$token_link?>'" value="<? echo ($site_info['insta_access_token']) ? "토큰 재발급" : "인증 및 토큰 발급";?>">
						<? } else { ?>
						인스타그램 앱 ID / 시크릿코드 입력 후 발급받을 수 있습니다.
						<? } ?>
					</td>
				</tr>
				<? if($site_info['insta_access_token']) { ?>
				<tr>
					<td class="t_name">access token 발급/갱신일</td>
					<td class="t_value"><?php echo $site_info['insta_token_date'] ?></td>
					<td class="t_name">access token 만료일</td>
					<td class="t_value"><?php if($site_info['insta_expires_in']) echo date("Y-m-d H:i:s", $site_info['insta_expires_in']) ?> <? echo $token_refresh_btn?></td>
				</tr>
				<? } ?>
				<tr>
					<td class="t_name">스킨</td>
					<td class="t_value" colspan="3">/twcenter/insta/skin/
					<select name="insta_skin" class="select">
					<?
						$dh = opendir($_SERVER['DOCUMENT_ROOT']."/twcenter/insta/skin");
						while(($file = readdir($dh)) !== false){
							if($file != "." && $file != ".."){
								$sel = ($file == $site_info['insta_skin']) ? " selected" : "";
								echo "<option value=\"".$file."\"".$sel.">".$file."</option>".PHP_EOL;
							}
						}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="t_name">삽입코드</td>
					<td class="t_value" colspan="3">
					<font color="red"><span>&lt;? $feed_cnt=10; include $_SERVER['DOCUMENT_ROOT']."/twcenter/insta/feed.php"; ?&gt; </font></span><BR>
					$feed_cnt : 출력 미디어 갯수 (미 지정시 25개 출력 - 인스타그램api 설정에 따라 변경될 수 있음 / 25 이상으로 지정해도 표시 불가)<br>
					</td>
				</tr>
			</table>

			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> SMS 사용
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- SMS서비스는 아이코드(<a href="http://icodekorea.com" target="_blank">http://icodekorea.com</a>) 에서 서비스 해드리며 해당업체와 계약이 되어있어야합니다.</li>
									<li>- SMS를 사용하는경우 기본설정에 "SMS관리" 메뉴가 생성되며 충전및발송 가능횟수를 조회할 수 있습니다.</li>
									<li>- 회원관리에 "SMS발송" 메뉴가 생성되어 전체발송이 가능하며 회원목록에서 개별,선택발송이 가능합니다.</li>
									<li>- 'SMS사용'체크시 회원아이디 / 비밀번호찾기일 경우 이메일 및 휴대폰번호로 임시비밀번호를 받을것인지 선택항목이 나타납니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sms_use" value="Y" <?php if($site_info['sms_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="sms_use" value="N" <?php if($site_info['sms_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">SMS 발송타입</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sms_send_type" value="S" <?php if($site_info['sms_send_type'] == "S") echo "checked"; ?>></span>SMS(단문)&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="sms_send_type" value="L" <?php if($site_info['sms_send_type'] == "L") echo "checked"; ?>></span>LMS(장문)
					</td>
				</tr>
				<tr>
					<td class="t_name">SMS 아이디 / 비밀번호</td>
					<td class="t_value">
						way_<input type="text" name="sms_id" value="<?php echo str_replace("way_","",$site_info['sms_id'])?>" class="input"> / 
						<input type="password" name="sms_pw" value="<?php echo $site_info['sms_pw']?>" class="input">
					</td>
					<td class="t_name">SMS 요금제 / 잔액</td>
					<td class="t_value">
						<?php echo $sms_coin; ?>
					</td>
				</tr>
				<tr>
					<td class="t_name">SMS 토큰키</td>
					<td class="t_value">
						<input type="text" name="sms_token" value="<?php echo $site_info['sms_token']?>" size="50" class="input">
					</td>
					<td class="t_name"></td>
					<td class="t_value">
						
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> 알림톡</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="alimtalk_use" value="Y" <?php if($site_info['alimtalk_use'] == "Y") echo "checked"; ?>></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="alimtalk_use" value="N" <?php if($site_info['alimtalk_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">알림톡 URL</td>
					<td width="35%" class="t_value">
						<input type="text" name="alimtalk_url" value="<?php echo $site_info['alimtalk_url'] ?>" class="input" size="50">&nbsp;
					</td>
					<td width="15%" class="t_name">알림톡 템플릿 URL</td>
					<td width="35%" class="t_value">
						<input type="text" name="alimtalk_temp_url" value="<?php echo $site_info['alimtalk_temp_url'] ?>" class="input" size="50">&nbsp;
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">알림톡 발송키</td>
					<td width="35%" class="t_value">
						<input type="text" name="alimtalk_senderkey" value="<?php echo $site_info['alimtalk_senderkey'] ?>" class="input" size="50">&nbsp;
					</td>
					<td width="15%" class="t_name">알림톡 고객구분</td>
					<td width="35%" class="t_value">
						<input type="text" name="alimtalk_custgubun" value="<?php echo $site_info['alimtalk_custgubun'] ?>" class="input" size="30">
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 실명인증 사용
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 실명인증을 사용하는 경우 회원가입 약관페이지에서 실명을 체크하게 됩니다.</li>
									<li>- 실명인증은 한국신용평가정보(주)에서 제공하며 <a href="http://www.namecheck.co.kr" target="_blank">http://www.namecheck.co.kr</a> 에서 신청하실 수 있습니다.</li>
									<li>- 신청 후 발급받은 아이디와 비밀번호를 입력 저장하면 바로 실명인증 체크가 가능합니다.</li>
									<li style="color: #ED253C;">주의) 신청후 받은 cb_namecheck 파일을 /twcenter/member 폴더에 업로드(전송타입 : 바이너리)후 707퍼미션을 줍니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="namecheck_use" value="Y" <?php if($site_info['namecheck_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="namecheck_use" value="N" <?php if($site_info['namecheck_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td class="t_name">실명인증 아이디 / 비밀번호</td>
					<td class="t_value">
						<input type="text" name="namecheck_id" value="<?php echo $site_info['namecheck_id']?>" class="input"> / 
						<input type="text" name="namecheck_pw" value="<?php echo $site_info['namecheck_pw']?>" class="input">
					</td>
				</tr>
			</table>

			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 간편결제 사용</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">간편결제 사용여부</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="easypay_use" value="Y" <?php if($site_info['easypay_use'] == "Y") echo "checked"; ?> class="easypay_use"></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="easypay_use" value="N" <?php if($site_info['easypay_use'] == "N") echo "checked"; ?> class="easypay_use"></span>사용안함
					</td>
				</tr>
			</table>

			<span id="kakao_use" style="display:none">
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 카카오페이 운영정보</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">Kakao Pay 사용</td>
					<td width="85%" class="t_value" colspan="3">
						<span style="vertical-align: middle"><input name="kakao_pay_use" value="Y" <? if($oper_info['kakao_pay_use']=='Y') echo "checked";?> type="checkbox"></span> 사용
					</td>
				</tr>
				<tr>
					<td class="t_name">Kakao CID</td>
					<td class="t_value" colspan="3">
						<input name="kakao_mid" value="<?php echo $oper_info['kakao_mid']?>" type="text" size="50" class="input" >
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">네이티브 앱 키</td>
					<td width="35%" class="t_value">
						<input name="kakao_native" value="<?php echo $oper_info['kakao_native']?>" type="text" size="50" class="input" >
					</td>
					<td width="15%" class="t_name">REST API 키</td>
					<td width="35%" class="t_value">
						<input name="kakao_restapi" value="<?php echo $oper_info['kakao_restapi']?>" type="text" size="50" class="input" >
					</td>
				</tr>
				<tr>
					<td class="t_name">JavaScript 키</td>
					<td class="t_value">
						<input name="kakao_js" value="<?php echo $oper_info['kakao_js']?>" type="text" size="50" class="input" >
					</td>
					<td class="t_name">Admin 키</td>
					<td class="t_value">
						<input name="kakao_admin" value="<?php echo $oper_info['kakao_admin']?>" type="text" size="50" class="input" >
					</td>
				</tr>

			</table>
			</span>

			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif"> 네이버 지식쇼핑</td>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif"> 네이버 공통인증키
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 네이버 공통 유입스크립트 적용을 위해 발급된 키값을 등록합니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="naver_use" value="Y" <?php if($oper_info['naver_use'] == "Y") echo "checked"; ?>></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="naver_use" value="N" <?php if($oper_info['naver_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">인증키값</td>
					<td width="35%" class="t_value">
						<input type="text" name="nhn_common_key" value="<?php echo $oper_info['nhn_common_key']?>" class="input" size="50">&nbsp;
					</td>
				</tr>
			</table>
			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif"> 네이버 지식쇼핑 입점안내</td>
					<td class="tit_sub"></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">입점안내</td>
					<td width="85%" class="t_value">
						<p>- 입점 안내 : http://join.shopping.naver.com/join/intro.nhn</p>
						<p>- 전체상품 URL : http://<?php echo $_SERVER['HTTP_HOST'] ?>/comm/naverEP/shop_naver.php</p>
						<p>- 요약상품 URL : http://<?php echo $_SERVER['HTTP_HOST'] ?>/comm/naverEP/shop_naver_summary.php</p>
						<p>- 입점신청은 '전체상품 URL' 만으로도 신청이 가능합니다.</p>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif"> 네이버 체크아웃
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 체크아웃연동시 네이버에서 할당받은 버튼키값을 등록합니다.</li>
									<li>- 네이버에서 발급받은 Shop Id 및 CertiKey값을 등록합니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="nhn_chkout_use" value="Y" <?php if($oper_info['nhn_chkout_use'] == "Y") echo "checked"; ?>></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="nhn_chkout_use" value="N" <?php if($oper_info['nhn_chkout_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">네이버 체크아웃 버튼키값</td>
					<td width="35%" class="t_value">
						<input type="text" name="nhn_chkout_key" value="<?php echo $oper_info['nhn_chkout_key'] ?>" class="input" size="50">&nbsp;
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Shop ID</td>
					<td width="35%" class="t_value">
						<input type="text" name="nhn_shopid" value="<?php echo $oper_info['nhn_shopid'] ?>" class="input" size="50">&nbsp;
					</td>
					<td width="15%" class="t_name">CertiKey</td>
					<td width="35%" class="t_value">
						<input type="text" name="nhn_certikey" value="<?php echo $oper_info['nhn_certikey'] ?>" class="input" size="50">&nbsp;
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">네이버 호스트</td>
					<td width="85%" class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="nhn_host_chk" value="T" <?php if($oper_info['nhn_host_chk'] == "T") echo "checked"; ?>></span>테스트 연동&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="nhn_host_chk" value="S" <?php if($oper_info['nhn_host_chk'] == "S") echo "checked"; ?>></span>실연동&nbsp;
						<input type="text" name="nhn_host" value="<?php echo $oper_info['nhn_host']?>" class="input" size="50">&nbsp;
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 무통장 자동입금서비스
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 서비스형태, 파트너쉽 아이디/비밀번호, 회원이름은 고정입니다.</li>
									<li>- 서비스시작일을 기준으로 입금조회 및 매칭작업이 이루어집니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="bankda_use" value="Y" <?php echo get_checked($oper_info['bankda_use'], 'Y') ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="bankda_use" value="N" <?php echo get_checked($oper_info['bankda_use'], 'N') ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">서비스형태</td>
					<td width="35%" class="t_value">
						<select name="bankda_service" class="select">
							<option value="premium" <?php echo get_selected($oper_info['premium'], 'premium') ?>>premium</option>
							<!-- <option value="express" <?php echo get_selected($oper_info['express'], 'express') ?>>express</option>
							<option value="standard" <?php echo get_selected($oper_info['standard'], 'standard') ?>>standard</option>
							<option value="basic" <?php echo get_selected($oper_info['basic'], 'basic') ?>>basic</option>
							<option value="entry" <?php echo get_selected($oper_info['entry'], 'entry') ?>>entry</option> -->
						</select>
					</td>
				</tr>
				<tr>
					<td class="t_name">파트너쉽 아이디 / 비밀번호</td>
					<td class="t_value">
						<input type="text" name="bankda_partner_id" value="<?php echo $oper_info['bankda_partner_id']?>" class="input" readonly> / 
						<input type="text" name="bankda_partner_pw" value="<?php echo $oper_info['bankda_partner_pw']?>" class="input" readonly>
					</td>
					<td class="t_name">파트너쉽 회원이름</td>
					<td class="t_value">
						<input type="text" name="bankda_partner_name" value="<?php echo $oper_info['bankda_partner_name']?>" class="input" readonly>
					</td>
				</tr>
				<tr>
					<td class="t_name">뱅크다 서비스시작일</td>
					<td class="t_value">
						<input type="text" id="wdate" name="bankda_service_date" value="<?php echo $oper_info['bankda_service_date']?>" class="input">
					</td>
					<td class="t_name">주문내역 매칭주기</td>
					<td class="t_value">
						<input type="text" name="bankda_match_time" value="<?php echo $bankda_match_time ?>" class="input Onum" size="10"> 분
					</td>
				</tr>
			</table>

			
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 비메오 업로드 사용 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">CLIENT ID</td>
					<td width="" class="t_value">
						<input name="vimeo_client_id" value="<?php echo $site_info['vimeo_client_id']?>" type="text" size="80" class="input" >
					</td>
				</tr>
				<tr>
					<td class="t_name">SECRET KEY</td>
					<td class="t_value">
						<input name="vimeo_client_secret" value="<?php echo $site_info['vimeo_client_secret']?>" type="text" size="80" class="input" >
					</td>
				</tr>
				<tr>
					<td class="t_name">ACCESS_TOKEN</td>
					<td class="t_value">
						<input name="vimeo_access_token" value="<?php echo $site_info['vimeo_access_token']?>" type="text" size="80" class="input" >
					</td>
				</tr>


			</table>

		</td>
	</tr>
</table>
<br>
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1>
	<tr>
		<td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
		</td>
	</tr>
</table>
</form>
<script>
	(function($){
		$(document).on('click','.btn-advice', function(){
			$(this).parent().addClass('show')
		});
		$(document).on('click','.advice .btn-close', function(){
			$(this).parents().removeClass('show');
		});
	})(jQuery);
</script>

<?php include "../foot.php"; ?>
