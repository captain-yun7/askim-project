<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

// 로고등록
if (!empty($_FILES['admin_logo']['tmp_name']) && is_uploaded_file($_FILES['admin_logo']['tmp_name'])) {

	$twcenter_logo_size = $_FILES['twcenter_logo']['size'];
	$twcenter_logo_name = $_FILES['twcenter_logo']['name'];
	$twcenter_logo_file = $_FILES['twcenter_logo']['tmp_name'];

	if($twcenter_logo_size > 0){

		ImageResize_Upload_check($twcenter_logo_name);

		$upfile_path = WIZHOME_PATH."/data/config";
		@copy($twcenter_logo_file, $upfile_path."/twcenter_logo.gif");
		@chmod($upfile_path."/twcenter_logo.gif", 0606);

	}

}

for($ii=0; $ii<count($menu_use); $ii++){ $tmp_menu .= $menu_use[$ii]."/"; }

$site_key = trim($site_key);
//$googleAnalyticsScript = str_replace("'","\'",$googleAnalyticsScript);
$googleAnalyticsScript = addslashes($googleAnalyticsScript);

$up_date = date("Y-m-d");

$denyip      = trim($denyip);
$permitip    = trim($permitip);
$denyid      = trim($denyid);
$designer_pw = (trim($designer_pw) == "") ? $site_info['designer_pw'] : md5($designer_pw);
$sms_id      = "way_".$sms_id;

$sql = "

	update wiz_siteinfo set 

	site_key                = '$site_key'
	,admin_title            = '$admin_title'
	,admin_copyright        = '$admin_copyright'
	,addbbs_use             = '$addbbs_use'
	,ssl_use                = '$ssl_use'
	,ssl_port               = '$ssl_port'
	,msg_use                = '$msg_use'
	,msg_editor_use         = '$msg_editor_use'
	,point_use              = '$point_use'
	,designer_id            = '$designer_id'
	,designer_pw            = '$designer_pw'
	,menu_use               = '$tmp_menu'
	,mini_use               = '$mini_use'
	,estimate_use           = '$estimate_use'
	,autologin_use          = '$autologin_use'
	,mobile_use             = '$mobile_use'
	,app_use                = '$app_use'
	,mobile_show_use		= '$mobile_show_use'
	,google_an_use          = '$google_an_use'
	,google_tracking        = '$google_tracking'
	,googleAnalyticsScript  = '$googleAnalyticsScript'
	,event_coupon_use       = '$event_coupon_use'
	,viewType               = '$viewType'
	,browscap               = '$browscap'
	,up_date                = '$up_date'
	,ipkisakey              = '$ipkisakey'
	,denyipuse              = '$denyipuse'
	,denyip                 = '$denyip'
	,permitipuse            = '$permitipuse'
	,permitip               = '$permitip'
	,sesschk                = '$sesschk'
	,coverage               = '$coverage'
	,login_limit_use        = '$login_limit_use'
	,login_limit_count      = '$login_limit_count'
	,login_limit_time       = '$login_limit_time'
	,login_pw_chg           = '$login_pw_chg'
	,login_pwchg_day        = '$login_pwchg_day'
	,sess_del_use           = '$sess_del_use'
	,sess_del_time          = '$sess_del_time'
	,denyiduse              = '$denyiduse'
	,denyid                 = '$denyid'
	,ext_config                 = '$ext_config'
	,use_ext                 = '$use_ext'
	,recaptcha_sitekey      = '$recaptcha_sitekey'
	,recaptcha_secretkey    = '$recaptcha_secretkey'
	
";
query($sql);

if($_POST['sesschk'] == 'N') {
	query("delete from wiz_session" );
}

$sql2 = "
	update wiz_operinfo 
	   set deliveryType = '$deliveryType'
";
query($sql2);
complete("기본설정이 저장되었습니다.","basic_config.php");

?>