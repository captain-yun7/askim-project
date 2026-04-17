<? include_once "../../common.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?

// addslashes()
$alimtalk_id = addslashes($alimtalk_id);
$alimtalk_pw = addslashes($alimtalk_pw);

if($prevalimtalk != $alimtalk_pw){
	$alimtalk_pw = md5($alimtalk_pw);
	$alimtalk_pw_sql = ", alimtalk_pw='$alimtalk_pw' ";
} else {
	$alimtalk_pw_sql = "";
}
// 알림톡 정보수정
$sql = "UPDATE wiz_siteinfo SET alimtalk_type='$alimtalk_type', alimtalk_id='$alimtalk_id' $alimtalk_pw_sql ";
$result = query($sql) or error("sql error");


complete("저장되었습니다.","talk_charging.php?menucode=$menucode");

?>