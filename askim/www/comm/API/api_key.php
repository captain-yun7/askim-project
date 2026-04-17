<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";
/*
작업자명	: 이상민
작업일시	: 22-01-03
작업내용	: redirect_url 주소 SSL환경 반영
*/
//facebook App_Info
$facebook_appId              = $api_info['facebook_appid'];													//사용자가 등록한 페이스북 App_ID 
$facebook_appsecret          = $api_info['facebook_appsecret'];												//사용자가 등록한 페이스북 App_SECRET 
$facebook_login_location     = SSL.WAY_URL."/".$api_info['facebook_redirect_url'];		//로그인후 이동할 URL 정보 
$facebook_logout_location    = "/";																			//로그아웃 후 이동할 URL 정보


//google App_Info
$google_client_id            = $api_info['google_client_id'];												//사용자가 등록한 구글 App_ID
$google_client_secret        = $api_info['google_client_secret'];											//사용자가 등록한 구글 App_SECRET_ID
$google_redirect_uri         = SSL.WAY_URL."/".$api_info['google_redirect_url'];		//사용자가 등록한 구글 App_REDIRECT_URL
$google_login_location       = "/";																			//로그인후 이동할 URL 정보 
$google_active_location      = "/comm/API/google.callback.act.php";											//로그인후 실행할 URL 정보 


//kakao App_Info
$kakao_api_key               = $api_info['kakao_api_key'];													//사용자가 등록한 카카오 App_ID
$kakao_login_location        = SSL.WAY_URL."/".$api_info['kakao_redirect_url'];			//로그인후 이동할 URL 정보 


//naver App_Info
$naver_client_id             = $api_info['naver_client_id'];												//사용자가 등록한 네이버 App_ID
$naver_client_secret         = $api_info['naver_client_secret'];											//사용자가 등록한 네이버 App_SECRET_ID
$naver_redirect_uri          = SSL.WAY_URL."/".$api_info['naver_redirect_url'];			//사용자가 등록한 네이버 App_REDIRECT_URL
$naver_login_location        = "/";																			//로그인후 이동할 URL 정보 
$naver_active_location       = "/comm/API/naver.callback.act.php";											//로그인후 이동할 URL 정보 


//twitter App_Info
$twitter_consumer_key        = $api_info['twitter_consumer_key'];											//사용자가 등록한 트위터 App_Consumer_Key
$twitter_consumer_secret     = $api_info['twitter_consumer_secret'];										//사용자가 등록한 트위터 App_Consumer_Secret
$twitter_access_token        = $api_info['twitter_access_token'];											//사용자가 등록한 트위터 App_Access_Token
$twitter_access_token_secret = $api_info['twitter_access_token_secret'];									//사용자가 등록한 트위터 App_Access_Token_Secret
$twitter_redirect_uri        = SSL.WAY_URL."/".$api_info['twitter_redirect_url'];		//사용자가 등록한 트위터 App_REDIRECT_URL
$twitter_login_location      = "/";																			//로그인후 이동할 URL 정보 
$twitter_active_location     = "/comm/API/twitter.callback.act.php";										//로그인후 실행할 URL 정보 
?>