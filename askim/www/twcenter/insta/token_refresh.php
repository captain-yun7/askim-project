<?
	$_SERVER['DOCUMENT_ROOT'] = "/home/iovis/www";

	include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

	$access_token = $site_info['insta_access_token'];

	$url = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=".$access_token;

	$result = curl_connect_get($url);

	if($result['access_token'] && $result['expires_in']) {
		$insta_access_token = $result['access_token'];
		$insta_expires_in = time()+$result['expires_in'];

		$sql_up = "update wiz_siteinfo set insta_access_token='$insta_access_token', insta_token_date=now(), insta_expires_in='$insta_expires_in'";
		if(query($sql_up)) { 
			complete("토큰이 갱신되었습니다", "/twcenter/manage/config/basic_service_config.php");
		} else {
			error("토큰값이 저장되지 않았습니다.", "/twcenter/manage/config/basic_service_config.php");
		}
	} else {
		if($result['error']['message']) {
			$errmsg = $result['error']['message'];
		} else {
			$errmsg = "토큰 갱신 중 오류가 발생했습니다.";
		}
		error($errmsg, "/twcenter/manage/config/basic_service_config.php");
	}
?>