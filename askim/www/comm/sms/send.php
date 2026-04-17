<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if(empty($_POST['se_name'])) error("이름이 전달되지않았습니다.");
if(empty($_POST['se_num1']) || empty($_POST['se_num2']) || empty($_POST['se_num3'])) error("연락처가 전달되지않았습니다.");
if(empty($_POST['message'])) error("내용이 전달되지않았습니다.");

$se_num = $se_num1."-".$se_num2."-".$se_num3;
$re_num = $site_info['site_hand'];

$message = "[".$se_name."]".$message;

send_sms($se_num, $re_num, $message, $se_name);

alert("정상적으로 접수되었습니다.", $PHP_SELF);

?>