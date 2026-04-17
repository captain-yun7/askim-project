<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(isset($_SESSION['wiz_admin']['id']) && $_SESSION['wiz_admin']['id']){

	if($site_info['sesschk'] == 'Y') {

		setcookie("wiz_admin[id]", "", time()-3600, "/");
		setcookie("wiz_admin[name]", "", time()-3600, "/");
		setcookie("wiz_admin[email]", "", time()-3600, "/");
		setcookie("wiz_admin[designer]", "", time()-3600, "/");

		session_unset();
		session_destroy();


	} else if(isset($_SESSION['wiz_admin']['id']) && $_SESSION['wiz_admin']['id']) {

		session_unset();
		session_destroy();

		setcookie("wiz_admin[id]", "", time()-3600, "/");
		setcookie("wiz_admin[name]", "", time()-3600, "/");
		setcookie("wiz_admin[email]", "", time()-3600, "/");
		setcookie("wiz_admin[designer]", "", time()-3600, "/");

	} else {

		setcookie("wiz_admin[id]", "", time()-3600, "/");
		setcookie("wiz_admin[name]", "", time()-3600, "/");
		setcookie("wiz_admin[email]", "", time()-3600, "/");
		setcookie("wiz_admin[designer]", "", time()-3600, "/");
	}

}

echo "<script>document.location='./';</script>";
?>