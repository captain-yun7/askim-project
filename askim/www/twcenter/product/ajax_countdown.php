<?php
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";

$sdate = date("Y-m-d H:i:s", time());
$edate = $_POST["timelimit"];

$time_diff = strtotime($edate) - strtotime($sdate);

$day = floor(($time_diff) / (60*60*24));
$hour = floor(($time_diff-($day*60*60*24))/(60*60));
$hour2 = floor(($time_diff)/(60*60));
$min = floor(($time_diff-($day*60*60*24)-($hour*60*60))/(60));
$sec = $time_diff-($day*60*60*24)-($hour*60*60)-($min*60);

if($hour < 10) $hour = "0".$hour;
if($min < 10) $min = "0".$min;
if($sec < 10) $sec = "0".$sec;

//if($day > 0) echo $day."일 ";
echo $hour2.":".$min.":".$sec;
?>