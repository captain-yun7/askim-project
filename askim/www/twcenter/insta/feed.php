<?
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

	if($site_info['insta_client_id'] != "" && $site_info['insta_access_token']) {			// 앱ID와 access token 이 있을 때만 실행

		$skin_dir = "/twcenter/insta/skin/".$site_info['insta_skin'];

		$url = "https://graph.instagram.com/me/media";
		$params = "?fields=id,caption,media_type,media_url,thumbnail_url,permalink&access_token=".$site_info['insta_access_token'];

		$result = curl_connect_get($url.$params);
		if($result['data']) {
			$data = $result['data'];

			echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;			

			@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_head.php";

			if(!$feed_cnt) $feed_cnt = sizeof($data);

			$i = 0;
			foreach($data as $k=>$feed) {
				if($i >= $feed_cnt) break;

				$caption			= cut_str($feed['caption'],20);
				$insta_media	= ($feed['media_type'] == 'VIDEO' && $feed['thumbnail_url']) ? $feed['thumbnail_url'] : $feed['media_url'];
				$insta_link		= $feed['permalink'];

				@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_body.php";

				$i++;
			}

			@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_foot.php";
		} else {
			echo "인스타그램 호출 오류";
		}
	}
?>