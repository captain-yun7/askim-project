<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

if($_POST['ckuse'] == "CHK"){

	if($wiz_session['id'] && $mem_level != "0") $my_id = " AND id <> '$wiz_session['id']' ";

	$_hp_info = sql_fetch( "SELECT COUNT(hphone) AS cnt FROM wiz_member WHERE hphone = '{$hphone}' {$my_id} " );
	if($_hp_info['cnt']) {
		echo "D";
	} else {
		echo "J";
	}
}

?>
