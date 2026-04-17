<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?

// addslashes()
$sms_id	= addslashes($sms_id);
$sms_pw = addslashes($sms_pw);

// sms 정보수정
$sms_id = "way_".$sms_id;
	/*
	작업자		: 임서연
	작업일시		: 2020-03-05
	작업내용		: 업데이트 쿼리 수정 > sms_token ='$sms_token' 추가(수정 반영 작업)
	*/
$sql = "update wiz_siteinfo set sms_type='$sms_type', sms_id='$sms_id', sms_pw='$sms_pw'";
if($wiz_admin['designer'] == 'Y') $sql .= ",sms_token ='$sms_token'";
$result = query($sql) or error("sql error");


complete("저장되었습니다.","sms_fill.php?menucode=$menucode");

?>