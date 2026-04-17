<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$sms_id      = "way_".$sms_id;
if($sns_use == "Y"){
	for($ii=0; $ii<count($sns_method); $ii++){
		$sns_tmp .= $sns_method[$ii]."/";
	}
} else {
	$sns_tmp = "";
}

if($sns_login_use == "Y"){
	for($ii=0; $ii<count($sns_login_method); $ii++){
		$sns_login_tmp .= $sns_login_method[$ii]."/";
	}
} else {
	$sns_login_tmp = "";
}

if($bankda_use == "Y" && ($bankda_match_time == 0 || $bankda_match_time == "")) {
	error("주문내역 매칭주기를 분단위로 입력하세요.");
	exit;
}

$bankda_match_time_sec = 1000*60*$bankda_match_time;

$sql = "

	update wiz_siteinfo set 

	sms_use                = '$sms_use'
	,sms_id                 = '$sms_id'
	,sms_pw                 = '$sms_pw'
	,sms_send_type			= '$sms_send_type'
	,sms_token				= '$sms_token'
	,namecheck_use          = '$namecheck_use'
	,namecheck_id           = '$namecheck_id'
	,namecheck_pw           = '$namecheck_pw'
	,alimtalk_use           = '$alimtalk_use'
	,alimtalk_url           = '$alimtalk_url'
	,alimtalk_temp_url      = '$alimtalk_temp_url'
	,alimtalk_senderkey     = '$alimtalk_senderkey'
	,alimtalk_custgubun     = '$alimtalk_custgubun'
	,easypay_use            = '$easypay_use'
	,insta_client_id			= '$insta_client_id'
	,insta_client_secret	= '$insta_client_secret'
	,insta_access_token	= '$insta_access_token'
	,insta_skin				= '$insta_skin'
	,vimeo_client_id			= '$vimeo_client_id'
	,vimeo_client_secret		= '$vimeo_client_secret'
	,vimeo_access_token		= '$vimeo_access_token'
	
";
query($sql);

$sql2 = "

	update wiz_operinfo set 

	sns_use                = '$sns_use'
	,sns_method             = '$sns_tmp'
	,kakao_appid            = '$kakao_appid'
	,kakao_pay_use          = '$kakao_pay_use'
	,kakao_mid              = '$kakao_mid'
	,kakao_enckey           = '$kakao_enckey'
	,kakao_hash             = '$kakao_hash'
	,kakao_canpwd           = '$kakao_canpwd'
	,kakao_merchant_sign    = '$kakao_merchant_sign'
	,kakao_native           = '$kakao_native'
	,kakao_restapi          = '$kakao_restapi'
	,kakao_js               = '$kakao_js'
	,kakao_admin            = '$kakao_admin'
	,naver_use              = '$naver_use'
	,nhn_common_key         = '$nhn_common_key'
	,nhn_chkout_use         = '$nhn_chkout_use'
	,nhn_chkout_key         = '$nhn_chkout_key'
	,nhn_shopid             = '$nhn_shopid'
	,nhn_certikey           = '$nhn_certikey'
	,nhn_host_chk           = '$nhn_host_chk'
	,nhn_host               = '$nhn_host'
	,sns_login_use          = '$sns_login_use'
	,sns_login_method       = '$sns_login_tmp'
	,bankda_use             = '$bankda_use'
	,bankda_service         = '$bankda_service'
	,bankda_partner_id      = '$bankda_partner_id'
	,bankda_partner_pw      = '$bankda_partner_pw'
	,bankda_partner_name    = '$bankda_partner_name'
	,bankda_service_date    = '$bankda_service_date'
	,bankda_match_time      = '$bankda_match_time_sec'

";

query($sql2);

complete("부가서비스설정이 저장되었습니다.","basic_service_config.php");

?>