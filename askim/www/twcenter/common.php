<?php
//error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );
//error_reporting( E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR );
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_WARNING);
//ini_set("error_reporting", E_ALL & ~E_NOTICE );
ini_set('display_errors', 0);


## 쿠키허용
@header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

## Clickjacking 방지 
//@header('X-Frame-Options: DENY'); 

if (!defined('WIZHOME_SET_TIME_LIMIT')) define('WIZHOME_SET_TIME_LIMIT', 0);		## 페이지 실행시간 조정 (php.ini에서 safe_mode=on일때 사용가능)
@set_time_limit(WIZHOME_SET_TIME_LIMIT);

$extract_array = array (
	'PHP_SELF',
	'_ENV',
	'_GET',
	'_POST',
	'_FILES',
	'_SERVER',
	'_COOKIE',
	'_SESSION',
	'_REQUEST',
	'HTTP_ENV_VARS',
	'HTTP_GET_VARS',
	'HTTP_POST_VARS',
	'HTTP_POST_FILES',
	'HTTP_SERVER_VARS',
	'HTTP_COOKIE_VARS',  
	'HTTP_SESSION_VARS',
	'GLOBALS'
	);
$extract_count = count($extract_array);
## _GET, _POST로 선언된 전역변수시 unset()
for ($i=0; $i<$extract_count; $i++) {
	if (isset($_GET[$extract_array[$i]]))  unset($_GET[$extract_array[$i]]);
	if (isset($_POST[$extract_array[$i]])) unset($_POST[$extract_array[$i]]);
}

define('WAY_URL',    $_SERVER['HTTP_HOST']);
define('WAY_PATH',   $_SERVER['DOCUMENT_ROOT']);
define('WIZHOME_DIR', "twcenter");
define('WIZHOME_PATH', WAY_PATH."/".WIZHOME_DIR);

define('WAY_twcenter_DIR', 'twcenter');
define('WAY_ADM_PATH', WAY_PATH."/".WAY_twcenter_DIR);

define('WIZHOME_twcenter_DIR'   , 'twcenter');
define('WIZHOME_BBS_DIR'     , 'bbs');
define('WIZHOME_BBSMAIN_DIR' , 'bbsmain');
define('WIZHOME_DATA_DIR'    , 'data');
define('WIZHOME_SESSION_DIR' , 'session');
define('WIZHOME_CONNECT_DIR' , 'connect');

define('WAY_BBS_DIR'     , 'bbs');
define('WAY_BBSMAIN_DIR' , 'bbsmain');
define('WAY_DATA_DIR'    , 'data');
define('WAY_SESSION_DIR' , 'session');
define('WAY_CONNECT_DIR' , 'connect');
define('WAY_DATA_DIR2', '/'.WAY_twcenter_DIR.'/'.WAY_DATA_DIR);

define('WIZHOME_SESSION_PATH', WAY_ADM_PATH.'/'.WAY_DATA_DIR.'/'.WAY_SESSION_DIR);
define('WIZHOME_CONNECT_PATH', WAY_ADM_PATH.'/'.WAY_DATA_DIR.'/'.WAY_CONNECT_DIR);
define('WIZHOME_DATA_PATH',    WAY_ADM_PATH.'/'.WAY_DATA_DIR);

define('WAY_SESSION_PATH', WAY_ADM_PATH.'/'.WAY_DATA_DIR.'/'.WAY_SESSION_DIR);
define('WAY_CONNECT_PATH', WAY_ADM_PATH.'/'.WAY_DATA_DIR.'/'.WAY_CONNECT_DIR);
define('WAY_DATA_PATH',    WAY_ADM_PATH.'/'.WAY_DATA_DIR);

define('COOKIE_DOMAIN',    ".".WAY_URL);

define('SQL_ERROR', TRUE);

$server_port = $_SERVER['SERVER_PORT'] != 80 ? ":".$_SERVER['SERVER_PORT'] : "";
define("WAY_URL_PORT",        $server_port);

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
	define("WAY_HOST",         "https://".WAY_URL.WAY_URL_PORT);
} else {
	define("WAY_HOST",         "http://".WAY_URL.WAY_URL_PORT);
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
	define("SSL",         "https://");
} else {
	define("SSL",         "http://");
}

define('THIS_TIME_YMD',              date('Y-m-d'));
define('UPLOAD_MAX_FILESIZE',        ini_get('upload_max_filesize'));
define('POST_MAX_SIZE',              ini_get('post_max_size'));

define("WAY_GURL", $_SERVER['SCRIPT_NAME']);
define("ACT_URL",  $_SERVER['SCRIPT_NAME']);
define("VERSION",        date('YmdHis'));
define('MYSQLI_USE', true);
define('DIR_PERM',  0707);
define('FILE_PERM', 0606);
define('LOG_PATH', $_SERVER['DOCUMENT_ROOT']."/../log/");
//echo ($_SERVER['DOCUMENT_ROOT']."/../log/");
//exit;

$tw             = array();
$prd_info       = array();
$site_info      = array();
$oper_info      = array();
$mem_info       = array();

@ini_set("session.use_trans_sid", 0);									## PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags","");										## 링크에 PHPSESSID가 따라다니는것을 무력화

@ini_set("session.save_path", WAY_SESSION_PATH);

if(isset($SESSION_CACHE_LIMITER)){										## 브라우저 캐쉬보관여부(no-cache,private,public)
	@session_cache_limiter($SESSION_CACHE_LIMITER);
} else {
	@session_cache_limiter("no-cache, must-revalidate");
}

ini_set("session.cache_expire", 1440);										## 세션유효시간(분):: 세션만료
ini_set("session.gc_maxlifetime", 86400);									## 세션유지시간(초):: 움직임이 없을경우
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);
ini_set("session.cookie_httponly", 1);

session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", COOKIE_DOMAIN);

if(!function_exists('session_start_samesite')) { 
	function session_start_samesite($options = array()) { 
		$res = @session_start($options); 
		// IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None, http 환경에서는 설정하지 않습니다.
		if( 
			preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) 
			|| preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) 
			|| preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) 
			|| ! (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
		){
			return $res;
		}
		$headers = headers_list(); 
		foreach ($headers as $header) { 
			if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue; 
			$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None'; 
			header($header, false); 
			break; 
		} 
		return $res; 
	}
}
session_start_samesite();

// 자동 로그아웃 설정
$timeout_limit = 60 * 60; // 60분

$now = time();

// 사용자 세션 체크
if (!empty($_SESSION['wiz_session']['id'])) {
	if (isset($_SESSION['last_activity_user']) && ($now - $_SESSION['last_activity_user'] > $timeout_limit)) {
		session_unset();
		session_destroy();
		setcookie('PHPSESSID', '', time() - 3600, '/');
		setcookie('w_id', '', time() - 3600, '/');
		setcookie('w_auto', '', time() - 3600, '/');
		setcookie('uniq_id', '', time() - 3600, '/');
		header("Location: /index.php");
		exit;
	}
	$_SESSION['last_activity_user'] = $now;
}

// 관리자 세션 체크
else if (!empty($_SESSION['wiz_admin']['id'])) {
	if (isset($_SESSION['last_activity_admin']) && ($now - $_SESSION['last_activity_admin'] > $timeout_limit)) {
		session_unset();
		session_destroy();
		setcookie('PHPSESSID', '', time() - 3600, '/');
		header("Location: /twcenter/index.php");
		exit;
	}
	$_SESSION['last_activity_admin'] = $now;
}

/*------------------------------------------------------------------------------------------------*\
 * DB접속
\*------------------------------------------------------------------------------------------------*/
$dbconn_file = WAY_ADM_PATH."/dbcon.php";

if(file_exists($dbconn_file)) {

	include_once($dbconn_file);
	include_once(WAY_ADM_PATH."/lib.php");
	include_once(WAY_ADM_PATH."/lib_puny.php");

	define('MYSQL_HOST', $db_host);
	define('MYSQL_USER', $db_user);
	define('MYSQL_PASS', $db_pass);
	define('MYSQL_DB', $db_name);

	$connect = dbconn(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or error('DB connection error occurred.');
	select_dbconn(MYSQL_DB, $connect) or error('DB selection error occurred.');
	
	$tw['connect'] = $connect;

	set_names("utf8", $connect);

} else {
	echo "<script>document.location='./install.php';</script>";
	exit;
}

if(empty($_SESSION['wiz_admin']['id']) && empty($_SESSION['wiz_session']['id'])) {
	
	if(strpos($_SERVER['PHP_SELF'], "/ajax_upload.php") == false) {
		$_POST    = array_map_deep($_POST,    'escapeString');
	}
	$_GET     = array_map_deep($_GET,     'escapeString');
	$_COOKIE  = array_map_deep($_COOKIE,  'escapeString');
	$_REQUEST = array_map_deep($_REQUEST, 'escapeString');

}else{
	$_POST    = array_map_deep($_POST,    'addslashes');
	$_GET     = array_map_deep($_GET,     'addslashes');
	$_COOKIE  = array_map_deep($_COOKIE,  'addslashes');
	$_REQUEST = array_map_deep($_REQUEST, 'addslashes');
}

$_GET     = array_map_deep($_GET,     'removeCRLF');

$extract_array2 = array (
	'HTTP_GET_VARS',
	'HTTP_POST_VARS',
	'HTTP_SERVER_VARS',
	'HTTP_ENV_VARS',
	'HTTP_SESSION_VARS',
	'HTTP_COOKIE_VARS',
	'_REQUEST',
	'_FILES',
	'_GET',
	'_POST',
	'_COOKIE',
	'_SESSION',
	'_SERVER'
	);

foreach($extract_array2 as $k => $v){
	//@extract(${$v}); 
	if($k == '_GET' || $k == '_POST'){
		@extract(get_text("data", ${$v}));
	}else{
if (isset($$v) && is_array($$v)) {
    extract($$v);
}
	}
}

/*------------------------------------------------------------------------------------------------*\
 * 접속상황 및 이동경로
\*------------------------------------------------------------------------------------------------*/
$con_file = WAY_CONNECT_PATH."/".$_SERVER['REMOTE_ADDR'];
@touch( $con_file );

if(!empty($menucode)) $menucodeParam = "menucode=$menucode";
else $menucodeParam = "";

$prd_info  = sql_fetch("SELECT * FROM wiz_prdinfo");
$site_info = sql_fetch("SELECT * FROM wiz_siteinfo");
$oper_info = sql_fetch("SELECT * FROM wiz_operinfo");
$mem_info  = sql_fetch("SELECT * FROM wiz_meminfo");

if($oper_info['deliveryType'] == "P") 
	$deli_page = "_p";

/*------------------------------------------------------------------------------------------------*\
 * 다음우편번호
\*------------------------------------------------------------------------------------------------*/
define('DAUM_POSTCODE', '<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>');
$DAUM_POSTCODE = DAUM_POSTCODE;

/*------------------------------------------------------------------------------------------------*\
 * 휴면해제 방법
\*------------------------------------------------------------------------------------------------*/
$sleep_tmp = explode("/",$mem_info['release_sleep_type']);
for($ii=0; $ii<count($sleep_tmp); $ii++){
	$sleep_use[$sleep_tmp[$ii]] = true;
}

/*------------------------------------------------------------------------------------------------*\
 * session정보로  방법
\*------------------------------------------------------------------------------------------------*/
if($site_info['sesschk'] == 'Y') {
	include_once(WAY_ADM_PATH."/inc/session_db_lib.php");
//	session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc"); 
}

if(!strcmp($site_info['ssl_use'], "Y")) {
	$ssl = "https://".$_SERVER['HTTP_HOST'];
	if(!empty($site_info['ssl_port'])) $ssl .= ":".$site_info['ssl_port'];
} else {
	$hide_ssl_start = "<!--"; $hide_ssl_end = "-->";
}

/*------------------------------------------------------------------------------------------------*\
 * 접근아이피 차단
\*------------------------------------------------------------------------------------------------*/
if($site_info['denyip'] != ""){

	/** /twcenter이 포함되어있지 않고 접근아이피 차단사용이면..(웹상에서 사용) **/
	//if((strpos($PHP_SELF, "/twcenter/") === false || strpos($PHP_SELF, "/twcenter/") !== false) && $siteInfo['denyipuse'] == "Y"){
	if((strpos($PHP_SELF, "/twcenter/") === false || strpos($PHP_SELF, "/twcenter/") !== false) && $site_info['denyipuse'] == "Y"){

		$deny_ip = false;
		$ip_patt = explode("\n", trim($site_info['denyip']));
		for($i=0; $i<count($ip_patt); $i++){
			$ip_patt[$i] = trim($ip_patt[$i]);
			if(empty($ip_patt[$i])) continue;

			$ip_patt[$i] = str_replace(".", "\.", $ip_patt[$i]);
			$ip_patt[$i] = str_replace("*", "[0-9\.]*", $ip_patt[$i]);
			$pattern = "/^{$ip_patt[$i]}$/";
			$deny_ip = preg_match($pattern, $_SERVER['REMOTE_ADDR']);
			$msg = iconv("utf-8", "euc-kr", "접근이 불가능합니다.");
			if($deny_ip) error($msg, "about:blank");
		}

	}

}

/*------------------------------------------------------------------------------------------------*\
 * 접근아이피 허용
\*------------------------------------------------------------------------------------------------*/
if($site_info['permitip'] != ""){
	/** /twcenter이 포함되어있고 허용아이피사용이면.. (관리자모드 접근허용아이피 설정) **/
	if( (strpos($PHP_SELF, "/twcenter/manage/") !== false || $PHP_SELF == "/twcenter/index.php") && $site_info['permitipuse'] == "Y"){
		$permit_ip = false;
		$ip_patt = explode("\n", trim($site_info['permitip']));
		for($i=0; $i<count($ip_patt); $i++){
			$ip_patt[$i] = trim($ip_patt[$i]);
			if(empty($ip_patt[$i])) continue;

			$ip_patt[$i] = str_replace(".", "\.", $ip_patt[$i]);
			$ip_patt[$i] = str_replace("*", "[0-9\.]*", $ip_patt[$i]);
			$pattern = "/^{$ip_patt[$i]}$/";
			$permit_ip = preg_match($pattern, $_SERVER['REMOTE_ADDR']);
			if($permit_ip) break;
		}

		$msg = iconv("utf-8", "euc-kr", "접근이 불가능합니다.");
		if(!$permit_ip) error($msg, "about:blank");

	}

}

/*------------------------------------------------------------------------------------------------*\
 * SMS문자길이 DEFAULT
\*------------------------------------------------------------------------------------------------*/
$sms_length = ($site_info['sms_send_type'] == "S") ? "80" : "2000";

/*------------------------------------------------------------------------------------------------*\
 * 사이트 접근아이디 제한
\*------------------------------------------------------------------------------------------------*/
if($site_info['denyiduse'] == "Y"){

	if($site_info['denyid'] != ""){

		$deny_id = false;
		$id_patt = explode("\n", trim($site_info['denyid']));
		for($i=0; $i<count($id_patt); $i++){
			$id_patt[$i] = trim($id_patt[$i]);
			if(empty($id_patt[$i])) continue;

			$id_patt[$i] = str_replace(".", "\.", $id_patt[$i]);
			$id_patt[$i] = str_replace("*", "[0-9\.]*", $id_patt[$i]);
			$pattern = "/^{$id_patt[$i]}$/";
			$deny_id = preg_match($pattern, $id);

			$msg = iconv("utf-8", "euc-kr", "사이트접근이 제한되었습니다.");
			if($deny_id) error($msg, "/");
		}

	}

}

/*------------------------------------------------------------------------------------------------*\
 * 메뉴정보(공통)
\*------------------------------------------------------------------------------------------------*/
$menu_tmp = explode("/",$site_info['menu_use']);
for($ii=0; $ii<count($menu_tmp); $ii++){
	$menu_arr[$menu_tmp[$ii]] = true;
}

ob_start();

header('Content-Type: text/html; charset=utf-8');
$tnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $tnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

//header("X-Frame-Options: DENY"); 
header('X-Frame-Options: SAMEORIGIN');

//$_GET     = array_map_deep($_GET,     'xss_clean');
//$_GET     = array_map_deep($_GET,     'SQLInjectXssForward');

if(!empty($_SESSION['wiz_session']['wiz_basket_id'])) {
	$_uniq_id = md5($_SESSION['wiz_session']['wiz_basket_id']);
} else {
	$_uniq_id = $_COOKIE["uniq_id"];
}

if(isset($_REQUEST['$idx'])) {
	$idx = (int)sqlfilter(trim($_REQUEST['$idx']));
}

if(isset($_REQUEST['$page'])) {
	$page = (int)sqlfilter(trim($_REQUEST['$page']));
}

if(isset($_REQUEST['$category'])) {
	$category = sqlfilter(trim($_REQUEST['$category']));
}

if(isset($_REQUEST['$searchkey'])) {
	$searchkey = sqlfilter(trim($_REQUEST['$searchkey']));
}

if(isset($_REQUEST['$searchopt'])) {
	$searchopt = sqlfilter(trim($_REQUEST['$searchopt']));
}

if(isset($_REQUEST['$code_page'])) {
	$code_page = sqlfilter(trim($_REQUEST['$code_page']));
}

if(isset($_REQUEST['$pos'])) {
	$pos = (int)sqlfilter(trim($_REQUEST['$pos']));
}

if(isset($_REQUEST['$prdcode'])) {
	$prdcode = (int)sqlfilter(trim($_REQUEST['$prdcode']));
}

if(isset($_REQUEST['$ptype'])) {
	$ptype = sqlfilter(trim($_REQUEST['$ptype']));
}

if(isset($_REQUEST['$mode'])) {
	$mode = sqlfilter(trim($_REQUEST['$mode']));
}

if(isset($_REQUEST['$product_idx'])) {
	$product_idx = sqlfilter(trim($_REQUEST['$product_idx']));
}


$_SERVER["PHP_SELF"] = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, "utf-8");

$_SERVER["PHP_SELF"] = htmlentities($_SERVER["PHP_SELF"]);

$_SERVER["PHP_SELF"] = preg_replace("/\"/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\&quot\;/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\(/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\)/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\:/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\!/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\+/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\=/i", "", $_SERVER["PHP_SELF"]);
$_SERVER["PHP_SELF"] = preg_replace("/\\\/i", "", $_SERVER["PHP_SELF"]);

$PHP_SELF = $_SERVER["PHP_SELF"];

//공격ip차단 
$block_ip = array("103.127.126.137", "20.213.87.216", "54.184.134.169", "185.87.48.113", "138.199.21.213", "91.240.118.188", "4.246.147.115", "91.230.225.28", "13.93.77.77", "121.169.33.158", "20.196.202.184");

//화이트아이피 처리
$white_ip = array("118.130.111.142");

if(in_array($_SERVER['REMOTE_ADDR'], $block_ip)) exit;

/*
	기능 : injection 공격자 검증 및 차단 - GET PARAMETER 검증
	작업자 : 김나연
	작업일 : 2022-11-23 
*/

// 관리자 로그인 되어있을 경우 pass
if(empty($_SESSION['wiz_admin']['id']) && in_array($_SERVER['REMOTE_ADDR'], $white_ip) == false) {
	
	if(!isset($_SESSION['injection_try'])) $_SESSION['injection_try']='0';

	if($_SESSION['injection_try'] >= 5) {		//누적 공격 의심 횟수 5이상 일 시 접속차단
		exit;
	}
	$injection_str = array("'", " or ", "+or", "or+", "sleep", "dbms", "show ", " database", "union");			//injection 의심단어 목록
	
	foreach($_GET as $k=>$v) {			//GET Parameter 전체 체크
		$v = strtolower(stripslashes(urldecode($v)));

		//sleep 과 ' 가 함께 들어가있는 경우 + information_schema 명백한 공격으로 판단, 횟수 증가시키지 않고 바로 차단
		if((strpos($v, "'") !== false && strpos($v, "sleep") !== false) || strpos($v, "information_schema") !== false) {
			$_SESSION['injection_try'] = 5;
		} else {
			foreach($injection_str as $chk_str) {			//의심 단어 체크(공격 정황 횟수 누적)
				if(strpos($v, $chk_str) !== false) {
					if(empty($_SESSION['injection_try'])) $_SESSION['injection_try'] = 1;
					else $_SESSION['injection_try'] = $_SESSION['injection_try']+1;

					$_SESSION['injection_param'] = $_SESSION['injection_param']."\n[".date("Y-m-d H:i:s")."] [".$chk_str."] ".$_SERVER['QUERY_STRING'];

					$_GET[$k] = str_replace($chk_str, "", $_GET[$k]);		//전송된 get parameter value 의심 단어 제거
				}
			}
		}
		if($_SESSION['injection_try'] >= 5) {
			$logfile = LOG_PATH."/attack_".date("Ymd").".log";
			$attacker['HOST'] = $_SERVER['HTTP_HOST'];
			$attacker['IP'] = $_SERVER['REMOTE_ADDR'];
			$attacker['TIME'] = date("Y-m-d H:i:s");
			$attacker['TOTAL_INJECTION'] = $_SESSION['injection_param'];

			@make_log($logfile, print_r($attacker,1));
			exit;
		}
	}
}


/*
작업자	: 정나혜
작업일시	: 2024-01-11
작업내용	: 리퍼러 감지하여 세션에 저장(쓰리웨이 관리자 참고)
*/

if (!isset($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = '';

$refer = parse_url($_SERVER['HTTP_REFERER']);
$host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
$query = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);

if($_SERVER['REMOTE_ADDR'] == "118.130.111.142" ){
//	echo session_id()."<br>";
	//print_r($_SESSION)."<br>";

	//print_r($refer);
}

$browser    = getBrowser_default();
$os         = getOs_default();
$tmp_device = getUserAgent();
if($tmp_device == 'ios' || $tmp_device == 'android' ||  $tmp_device == 'etc') {
	$device = "Mobile(".$tmp_device.")";
} else {
	$device = "PC";
}

$_SESSION["sess_ori_referer"] = $_SERVER['HTTP_REFERER'];
$_SESSION["sess_os"] = $os;
$_SESSION["sess_device"] = $device;
$_SESSION["sess_browser"] = $browser;

if(strstr(strval($host), 'naver.com')){ // 네이버일 경우
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	$queryAr = explode("&", $query);
	for($i=0;$i<sizeof($queryAr);$i++){
		$queryItemAr = explode("=", $queryAr[$i]);
		
		// 221115 기준 - 네이버에서 query외에 다른 리퍼러 보내주지 않음. campaign, content, medium, term 은 _GET으로 수신할것
		switch($queryItemAr[0]){
			case "query": // 검색어
				$keywords = urldecode($queryItemAr[1]);
				break;
			case "utm_campaign": // 캠페인
				$utm_campaign = urldecode($queryItemAr[1]);
				break;
			case "utm_content": // 그룹
				$utm_content = urldecode($queryItemAr[1]);
				break;
			case "utm_medium": //cpc 
				$utm_medium = urldecode($queryItemAr[1]);
				break;
		}
	}
	if(substr($utm_campaign, 0, 1) == "%"){
		$utm_campaign	= urldecode($_GET["utm_campaign"]);		// 캠페인
	}

	if(substr($utm_content, 0, 1) == "%"){
		$utm_content	= urldecode($_GET["utm_content"]);		// 그룹
	}

	if(substr($utm_medium, 0, 1) == "%"){
		$utm_medium		= urldecode($_GET["utm_medium"]);		// cpc
	}

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= $refer["host"];
	$_SESSION["sess_referer_site_name"]	= "네이버";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= $keywords;
	$_SESSION["sess_referer_query"]		= $query;
	$_SESSION["sess_referer_campaign"]	= $utm_campaign;
	$_SESSION["sess_referer_content"]	= $utm_content;
	$_SESSION["sess_referer_medium"]	= $utm_medium;

	


} else if(strstr(strval($host), 'zum.com')){ // zum일 경우
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	$queryAr = explode("&", $query);
	for($i=0;$i<sizeof($queryAr);$i++){
		$queryItemAr = explode("=", $queryAr[$i]);

		switch($queryItemAr[0]){
			case "query": // 검색어
				$keywords = urldecode($queryItemAr[1]);
				break;
			case "utm_campaign": // 캠페인
				$utm_campaign = urldecode($queryItemAr[1]);
				break;
			case "utm_content": // 그룹
				$utm_content = urldecode($queryItemAr[1]);
				break;
			case "utm_medium": //cpc 
				$utm_medium = urldecode($queryItemAr[1]);
				break;

		}
	}

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= $refer["host"];
	$_SESSION["sess_referer_site_name"]	= "줌(ZUM)";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= $keywords;
	$_SESSION["sess_referer_query"]		= $query;
	$_SESSION["sess_referer_campaign"]	= $utm_campaign;
	$_SESSION["sess_referer_content"]	= $utm_content;
	$_SESSION["sess_referer_medium"]	= $utm_medium;

} else if(strstr(strval($host), 'bing.com')){ // 190723 기준 bing일 경우 query 없이 넘어옴
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= $refer["host"];
	$_SESSION["sess_referer_site_name"]	= "빙(bing)";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= "bing 검색 유입";

} else if(strstr(strval($host), 'yahoo.com')){ // 190723 기준 yahoo일 경우 query 없이 넘어옴
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= $refer["host"];
	$_SESSION["sess_referer_site_name"]	= "야후";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= "yahoo 검색 유입";

} else if(strstr(strval($host), 'jobkorea.co.kr')){ // 190723 기준 jobkorea일 경우 query가 있지만 검색어가 없기에 큰 의미가 없음
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= $refer["host"];
	$_SESSION["sess_referer_site_name"]	= "잡코리아";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= "jobkorea 검색 유입";

} else if(strstr(strval($host), 'google.com')){ // 190723 기준 google 광고일 경우 query 없이 파라미터로 넘어옴
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	if($_GET["utm_source"] == "google"){
		$keywords		= urldecode($_GET["utm_conterm"]);		// 키워드 대체필드
		$utm_campaign	= urldecode($_GET["utm_campaign"]);	// 캠페인
		$utm_content	= urldecode($_GET["utm_content"]);		// 그룹
		$utm_medium		= urldecode($_GET["utm_medium"]);		// cpc
	}

	if($keywords == "") $keywords = "google 검색 유입";

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= "google.com";
	$_SESSION["sess_referer_site_name"]	= "구글";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= $keywords;
	$_SESSION["sess_referer_query"]		= $query;
	$_SESSION["sess_referer_campaign"]	= $utm_campaign;
	$_SESSION["sess_referer_content"]	= $utm_content;
	$_SESSION["sess_referer_medium"]	= $utm_medium;
} else if(strstr(strval($host), 'google.co.kr')){ 
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	if($_GET["utm_source"] == "google"){
		$keywords		= urldecode($_GET["utm_conterm"]);		// 키워드 대체필드
		$utm_campaign	= urldecode($_GET["utm_campaign"]);	// 캠페인
		$utm_content	= urldecode($_GET["utm_content"]);		// 그룹
		$utm_medium		= urldecode($_GET["utm_medium"]);		// cpc
	}

	if($keywords == "") $keywords = "google 검색 유입";

	$_SESSION["sess_referer"]			= $refer;
	$_SESSION["sess_referer_host"]		= "google.co.kr";
	$_SESSION["sess_referer_site_name"]	= "구글";
	$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
	$_SESSION["sess_referer_keyword"]	= $keywords;
	$_SESSION["sess_referer_query"]		= $query;
	$_SESSION["sess_referer_campaign"]	= $utm_campaign;
	$_SESSION["sess_referer_content"]	= $utm_content;
	$_SESSION["sess_referer_medium"]	= $utm_medium;
}


if($_SESSION["sess_referer"] == ""){
	$_SESSION["sess_referer"]			= "";
	$_SESSION["sess_referer_host"]		= "";
	$_SESSION["sess_referer_site_name"]	= "";
	$_SESSION["sess_referer_time"]		= "";
	$_SESSION["sess_referer_keyword"]	= "";
	$_SESSION["sess_referer_query"]		= "";
	$_SESSION["sess_referer_campaign"]	= "";
	$_SESSION["sess_referer_content"]	= "";
	$_SESSION["sess_referer_medium"]	= "";

	if($refer["host"] != ""){
		$_SESSION["sess_referer"]			= $refer;
		$_SESSION["sess_referer_host"]		= $refer["host"];
		if(!isset($query)) $query = '';
		$_SESSION["sess_referer_query"]		= urldecode($query);
		$_SESSION["sess_referer_site_name"]	= $refer["host"];
		$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
		$_SESSION["sess_referer_keyword"]	= $refer["host"]." 유입";
	} else {
		$_SESSION["sess_referer"]			= $refer;
		$_SESSION["sess_referer_host"]		= "미확인";
		if(!isset($query)) $query = ''; 
		$_SESSION["sess_referer_query"]		= urldecode($query);
		$_SESSION["sess_referer_site_name"]	= "미확인";
		$_SESSION["sess_referer_time"]		= date("Y-m-d H:i:s", time());
		$_SESSION["sess_referer_keyword"]	= "미확인";
	}
}
?>