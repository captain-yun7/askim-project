<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

if($wiz_session['id'] && $mem_level != "0") $my_id = " AND id <> '$wiz_session['id']' ";

if($_POST['ckuse'] == "CHK" && isset($_POST['hphone']) && $_POST['hphone']) {

	$hphone = trim($_POST['hphone']);

	$_hp_info = sql_fetch( "SELECT COUNT(hphone) AS cnt FROM wiz_member WHERE hphone = '{$hphone}' {$my_id} " );
	if($_hp_info['cnt']) {
		echo "D";
	} else {
		echo "J";
	}

} else if($_POST['ckuse'] == "CHK" && isset($_POST['email']) && $_POST['email']) {

	$email = trim($_POST['email']);

	$_email_info = sql_fetch( "SELECT COUNT(email) AS cnt FROM wiz_member WHERE email = '{$email}' {$my_id} " );
	if($_email_info['cnt']) {
		echo "D";
	} else {
		echo "J";
	}


}

?>
