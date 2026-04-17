<?php
$sql = "select * from wiz_api_info";
$result = query($sql);
$api_info = sql_fetch_arr($result);
$api_cnt = sql_fetch_row($result);

$appkey = $api_info['daum_map_key'];
$appkey_api = $api_info['daum_map_api_key'];

?>