<?
$sql = "select msg_use, msg_skin, msg_url, msg_editor_use from wiz_siteinfo";
$msg_info = sql_fetch($sql);

// 스킨위치
$skin_dir = "/twcenter/message/skin/".$msg_info['msg_skin'];

$img_width = "440";
?>