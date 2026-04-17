<?
$sql = "select point_use, point_skin, point_url from wiz_siteinfo";
$point_info = sql_fetch($sql);

// 스킨위치
$skin_dir = "/twcenter/point/skin/".$point_info['point_skin'];

?>