<?
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";

if($mode == "memoIn"){

	if($site_info['memoinsert'] == "" || strpos($site_info['memoinsert'], '0000') !== false){
		$menamemo = mysqli_real_escape_string($connect, $menamemo);
		$time     = date("Y-m-d H:i:s");

		$sql = "UPDATE wiz_siteinfo SET manamemo='$manamemo', memoinsert='$time' ";
		query($sql);

	} else {

		$menamemo = mysqli_real_escape_string($connect, $menamemo);
		$time     = date("Y-m-d H:i:s");

		$sql = "UPDATE wiz_siteinfo SET manamemo='$manamemo', memoupdate='$time' ";
		query($sql);

	}

	echo "OK";

} else if($mode == "memoDe") {

	$time     = date("Y-m-d H:i:s");

	$sql = "UPDATE wiz_siteinfo SET manamemo='', memoupdate='$time' ";
	query($sql);

	echo "OK";

}
?>