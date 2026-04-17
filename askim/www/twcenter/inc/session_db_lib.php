<?php
/**
 * php.net http://kr.php.net/manual/kr/function.session-set-save-handler.php 참조
 */
function sess_open($save_path, $session_name)
{
	global $connect, $select_db, $db_host, $db_user, $db_pass, $db_name;

	if(!$connect) {
		$connect = @mysqli_connect($db_host, $db_user, $db_pass) or die("DB 접속시 에러가 발생했습니다.");
		$select_db = @mysqli_select_db($db_name, $connect);
		if(!$select_db)
			die("DB Select 에러가 발생했습니다");
			return $select_db;
	} else if($connect && $select_db){
		return $select_db;
	}

	return false;
}

function sess_close()
{
	global $connect;
	return mysqli_close($connect);
}

function sess_read($sess_id)
{
	global $connect;

	$sess_id = mysqli_real_escape_string($sess_id,$connect);

	$sql = "select sess_data from wiz_session where sess_id = '$sess_id'";
	$row = sql_fetch($sql,$connect);

	return $row['sess_data'];
}

function sess_write($sess_id, $sess_data)
{
	global $connect;

	$sess_id = mysqli_real_escape_string($sess_id,$connect);
	$sess_data = mysqli_real_escape_string($sess_data,$connect);
	$ss_datetime = date("Y-m-d H:i:s");

	if($_SESSION['wiz_admin']['id'] != ""){
		$u_id = $_SESSION['wiz_admin']['id'];
	} else if($_SESSION['wiz_session']['id'] != "") {
		$u_id = $_SESSION['wiz_session']['id'];
	}

	$referer = $_SERVER['HTTP_REFERER'];
	$parse_url = parse_url($referer);
	$host = $parse_url['host'];

	if(isset($u_id)) {
		$sql = "replace into wiz_session values ('$sess_id', '$ss_datetime', '$sess_data', '$_SERVER['REMOTE_ADDR']', '$u_id')";
		$row = query($sql,false);
	}

	return $row;
}

function sess_destroy($sess_id)
{
	global $connect;

	$sess_id = mysqli_real_escape_string($sess_id,$connect);

	$sql = "delete from wiz_session where sess_id = '$sess_id'";
	$row = query($sql,$connect);

	return $row;
}

function sess_gc($maxlifetime)
{
	global $connect;

	## 현재시간을 기준으로 10을 차감(로그인된 세션이 있을수 있으므로)
	$prev_sess_datetime = strtotime(date("Y-m-d H:i:s")) - $maxlifetime;
	$prev_sess_datetime = date("Y-m-d H:i:s", $prev_sess_datetime);

	$sql = "delete from wiz_session where sess_datetime < '$prev_sess_datetime'";
	$row = query($sql,$connect);

	return $row;
}


?>
