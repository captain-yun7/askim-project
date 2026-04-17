<?
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

	$client_id = $site_info['insta_client_id'];			// Instagram 앱 ID
	$client_secret = $site_info['insta_client_secret'];		// Instagram 앱 시크릿 코드

	//단기토큰발급 (1시간)
	$redirect_uri = "https://".str_replace("www.", "", $HTTP_HOST)."/twcenter/insta/iauth.php";
	$code = $_REQUEST['code'];

	$url = "https://api.instagram.com/oauth/access_token";
	$post_data = array (
		"client_id"=>$client_id,
		"client_secret"=>$client_secret ,
		"grant_type"=>"authorization_code" ,
		"redirect_uri"=>$redirect_uri ,
		"code"=>$code
	);
	$result = curl_connect_post($url, $post_data);

	$access_token = $result['access_token'];

	if($access_token) {
		// 장기토큰으로 교체, db저장
		$url = "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=".$client_secret."&access_token=".$access_token;
		$result = curl_connect_get($url);

		$insta_access_token = $result['access_token'];
		$insta_expires_in = time()+$result['expires_in'];
		

		if($insta_access_token) {
			$sql_up = "update wiz_siteinfo set insta_access_token='$insta_access_token', insta_token_date=now(), insta_expires_in='$insta_expires_in'";
			if(query($sql_up)) {
				complete("인스타그램 토큰값이 생성되었습니다", "/twcenter/manage/config/basic_service_config.php");
			} else {
				error("토큰값이 저장되지 않았습니다.", "/twcenter/manage/config/basic_service_config.php");
			}
		} else {
			error("장기 토큰 생성에 실패했습니다.", "/twcenter/manage/config/basic_service_config.php");
		}
	} else {
		error("단기 토큰 생성에 실패했습니다.", "/twcenter/manage/config/basic_service_config.php");
	}
?>