<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/msg_info.php";

if(!empty($wiz_session['id']) && $msg_info['msg_use'] == "Y") {
	$sql = "select count(idx) as cnt from wiz_message where re_id = '$wiz_session['id']' and status != 'Y' and re_status != 'N' group by re_id";
	echo $sql;
	
	$result =  query($sql) or error(msyql_error());
	$row = sql_fetch_arr($result);
	
	$msg_count = number_format($row['cnt']);
	if($msg_count == "") $msg_count = 0;
	$msg_url = "/".$msg_info['msg_url'];
	
	$msg_count = "<a href='".$msg_url."' class=''>쪽지 : ".$msg_count." 통</a>";
}
?>