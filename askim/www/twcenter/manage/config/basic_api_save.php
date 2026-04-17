<?
include_once "../../common.php";
include_once "../../inc/admin_check.php";

if($mode == "insert"){

	$sql_com = "";
	$sql_com .= " facebook_appid                    = '$facebook_appid'                         ";
	$sql_com .= " , facebook_appsecret              = '$facebook_appid'                         ";
	$sql_com .= " , facebook_redirect_url           = '$facebook_redirect_url'                  ";
	$sql_com .= " , kakao_api_key                   = '$kakao_api_key'                          ";
	$sql_com .= " , kakao_api_key2                  = '$kakao_api_key2'                         ";
	$sql_com .= " , kakao_redirect_url              = '$kakao_redirect_url'                     ";
	$sql_com .= " , twitter_consumer_key            = '$twitter_consumer_key'                   ";
	$sql_com .= " , twitter_consumer_secret         = '$twitter_consumer_secret'                ";
	$sql_com .= " , twitter_access_token            = '$twitter_access_token'                   ";
	$sql_com .= " , twitter_access_token_secret     = '$twitter_access_token_secret'            ";
	$sql_com .= " , twitter_redirect_url            = '$twitter_redirect_url'                   ";
	$sql_com .= " , google_client_id                = '$google_client_id'                       ";
	$sql_com .= " , google_client_secret            = '$google_client_secret'                   ";
	$sql_com .= " , google_redirect_url             = '$google_redirect_url'                    ";
	$sql_com .= " , naver_client_id                 = '$naver_client_id'                        ";
	$sql_com .= " , naver_client_secret             = '$naver_client_secret'                    ";
	$sql_com .= " , naver_redirect_url              = '$naver_redirect_url'                     ";
	$sql_com .= " , daum_map_key                    = '$daum_map_key'                           ";
	$sql_com .= " , daum_map_api_key                    = '$daum_map_api_key'                           ";

	$sql = "INSERT INTO wiz_api_info SET {$sql_com} ";
	query($sql);

} else {

	$sql_com = "";
	$sql_com .= " facebook_appid                    = '$facebook_appid'                         ";
	$sql_com .= " , facebook_appsecret              = '$facebook_appid'                         ";
	$sql_com .= " , facebook_redirect_url           = '$facebook_redirect_url'                  ";
	$sql_com .= " , kakao_api_key                   = '$kakao_api_key'                          ";
	$sql_com .= " , kakao_api_key2                  = '$kakao_api_key2'                         ";
	$sql_com .= " , kakao_redirect_url              = '$kakao_redirect_url'                     ";
	$sql_com .= " , twitter_consumer_key            = '$twitter_consumer_key'                   ";
	$sql_com .= " , twitter_consumer_secret         = '$twitter_consumer_secret'                ";
	$sql_com .= " , twitter_access_token            = '$twitter_access_token'                   ";
	$sql_com .= " , twitter_access_token_secret     = '$twitter_access_token_secret'            ";
	$sql_com .= " , twitter_redirect_url            = '$twitter_redirect_url'                   ";
	$sql_com .= " , google_client_id                = '$google_client_id'                       ";
	$sql_com .= " , google_client_secret            = '$google_client_secret'                   ";
	$sql_com .= " , google_redirect_url             = '$google_redirect_url'                    ";
	$sql_com .= " , naver_client_id                 = '$naver_client_id'                        ";
	$sql_com .= " , naver_client_secret             = '$naver_client_secret'                    ";
	$sql_com .= " , naver_redirect_url              = '$naver_redirect_url'                     ";
	$sql_com .= " , daum_map_key                    = '$daum_map_key'                           ";
	$sql_com .= " , daum_map_api_key                    = '$daum_map_api_key'                           ";

	$sql = "UPDATE wiz_api_info SET {$sql_com} ";
	query($sql);

}
complete("API 설정이 저장되었습니다.","basic_api_config.php?mode=update");

?>