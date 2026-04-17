<?
	
$sql = "select * from wiz_forminfo where code = '$form_code'";
$form_info = sql_fetch($sql);

// 스킨위치
//$skin_dir = "/twcenter/form/skin/".$form_info['skin'];

if($mobile_key == "M"){
	$skin_dir = "/twcenter/form/skin_m/".$form_info['skin'];
} else {
	$skin_dir = "/twcenter/form/skin/".$form_info['skin'];
}

?>