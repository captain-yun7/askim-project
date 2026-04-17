<?php
$sql = "select * from bankda_member";
$result = query($sql);
$bankda_info = sql_fetch_arr($result);

if(!isset($bankda_info['bankda_phone'])) $bankda_info['bankda_phone'] = '';
list($bk_tphone1, $bk_tphone2, $bk_tphone3) = explode("-", $bankda_info['bankda_phone']);

if(!isset($bankda_info['bankda_email'])) $bankda_info['bankda_email'] = '';
list($bk_email1, $bk_email2) = explode("@", $bankda_info['bankda_email']);
?>