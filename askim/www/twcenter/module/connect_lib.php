<?php
	ini_set('memory_limit','-1');

	require_once $_SERVER['DOCUMENT_ROOT']."/comm/plugin/browscap/Browscap.php";

	define("CACHE_DIR", WIZHOME_DATA_PATH."/cache");

	$browscap = new phpbrowscap\Browscap(CACHE_DIR);
	$browscap->updateMethod = 'cURL';
	$browscap->doAutoUpdate = false;
	$browscap->updateInterval = 2592000;

	$agent = $_SERVER['HTTP_USER_AGENT'];
	$os_browser = $browscap->getBrowser($agent);

	$browser    = $os_browser->Browser;
	$os         = $os_browser->Platform;
	$device     = $os_browser->Device_Type;
	$is_mobile  = $os_browser->isMobileDevice;
	$wdate      = date("Y-m-d");
	$wtime      = date("H:i:s");

	$device = ($is_mobile == 1) ? "Mobile" : "PC";

?>