<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

if(empty($date)) $date = date("Y-m-d");
else			 $date = trim($date);

$_cnt = sql_fetch("SELECT COUNT(idx) as cnt FROM wiz_schedule_main WHERE schdate='{$date}' ");

if($_cnt['cnt'] == 0){
	echo "no|0|".$date;
} else {
	echo "|".$_cnt['cnt']."|".$date;
}
?>