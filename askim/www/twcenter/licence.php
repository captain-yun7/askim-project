<?
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/common.php';
//if(strpos($_SERVER['PHP_SELF'],"/twcenter/manage") !== false){

	$domain = $_SERVER['HTTP_HOST'];

	$domain = str_replace(".co.",".",$domain);
	$domain = str_replace(".or.",".",$domain);
	$domain = str_replace(".ac.",".",$domain);
	$domain = str_replace(".go.",".",$domain);

	include_once WIZHOME_PATH."/inc/site_info.php";

	$solution = strtolower($site_info['solution']);

	$key1 = substr($solution, 0, 3);
	$key2 = substr($solution, 3, strlen($solution));


	$dot_list = explode(".",$domain);
	$dname = $dot_list[count($dot_list)-2];

	$puny_code = decode($dname);

	// 한글도메인
	$puny_code = iconv("utf-8", "UHC", decode($dname));
	if(strcmp($dname, $puny_code) && $puny_code != false) {
		$dname = $puny_code;
	}

	$subname = $dot_list[count($dot_list)-3];
	$license_key = md5($key1.md5($dname).$key2);

	$site_auth = "false";
	$site_auth_sub = "false";

	// 도메인인증
	$sitekey_list = explode("\n", $site_info['site_key']);
	for($ii=0; $ii < count($sitekey_list); $ii++){
		if(trim($sitekey_list[$ii]) == $license_key){
			$site_auth = "true";
		}
	}

	// 2주체크 -> 1주
	if( ($site_info['site_date']+(60*60*24*7)) >= time()){
		$site_auth = "true";
	}

	// 서브도메인 8주체크 -> 1주
	if( ($site_info['site_date']+(60*60*24*7)) >= time()){
		$site_auth_sub = "true";
	}

	// 서브도메인 체크
	if(empty($subname) || !strcmp($subname, "www")) {

		if($site_auth == "false"){
			echo "<script>alert('라이센스키가 없거나 올바르지 않습니다.');document.location='/twcenter/site_key.php';</script>";
			exit;
		}

	} else {

		if($site_auth_sub == "false" && $site_auth == "false"){
			echo "<script>alert('라이센스키가 없거나 올바르지 않습니다.');document.location='/twcenter/site_key.php';</script>";
			exit;
		}

	}

//}
?>