<?php
include "./common.php";

if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	error("잘못된 접근입니다.");
}

if(isset($_POST["admin_id"]) && isset($_POST["admin_pw"]) && $_POST["admin_id"]==="admin" && preg_match("/^[0-9]+$/", $_POST["admin_pw"])) { exit("Weak password blocked"); }
$start_page = SSL.$_http_host."/twcenter/manage/main/main.php";

$admin_id = trim($_POST['admin_id']);
$admin_pw = trim($_POST['admin_pw']);

if($admin_id == "") error("아이디를 입력하세요");
if($admin_pw == "") error("비밀번호를 입력하세요");

$null_blank = md5('');
if(trim($admin_id) == "" || trim($admin_pw) == $null_blank) {
	error('잘못된 경로입니다.','about:blank');
}

$user_ip = $_SERVER['REMOTE_ADDR'];
//echo " | ".$_SERVER["DOCUMENT_ROOT"]."/twcenter/data/log/".date("Ymd")."_twcenter_login.log ||| ";
$logfile = fopen(LOG_PATH.date("Ymd")."_admin_login.log", "a+" );

$sql = "select * from wiz_admin where id = '$admin_id'";
$result = query($sql);
$admin_info = sql_fetch_arr($result);

## 로그인 접속제한
if($site_info['login_limit_use'] == "Y") {
	fwrite($logfile,"************************************************************************************************************************************************\r\n");
	fwrite( $logfile, "Login Time : ".date("Y-m-d H:i:s",time())."\r\n");

	if($admin_info['login_fail_count'] >= $site_info['login_limit_count']){

		$date1 = strtotime($admin_info['login_try_time']);
		$date2 = strtotime(date("Y-m-d H:i:s"));

		$diff = (int)$date2 - $date1;

		$rtime = $site_info['login_limit_time'];

		if($rtime >= 0 && $rtime < 60) {
			$rtime = $rtime."초";
		} else if($rtime >= 60 && $rtime < 3600) {
			$rtime = floor($rtime/60)."분";
		} else if($rtime >= 3600 && $rtime < 86400) {
			$rtime = floor($rtime/3600)."시간";
		} else if($rtime >= 86400) {
			$rtime = floor($rtime/86400)."일";
		}

		if($diff >= $site_info['login_limit_time']){ //설정 시간이 지나면 자동 차단해제
			query("UPDATE wiz_admin SET is_account_lock='N', login_try_time='', login_fail_count=0 WHERE id='$admin_id' ");
		} else {
			fwrite($logfile, "\r\n");
			fwrite( $logfile, "[Fail : duplicate failure] \r\n");
			foreach($_POST as $KEY => $VALUES){
				if($KEY != 'admin_pw'){
					fwrite( $logfile, $KEY." : ".$VALUES."\r\n");
				}
			}
			fwrite($logfile, "ip : ".$_SERVER["REMOTE_ADDR"]."\r\n");
			fwrite($logfile,"************************************************************************************************************************************************\r\n");
			fwrite($logfile, "\r\n");
			fclose($logfile);
			error("로그인 중복실패로 로그인 접속이 차단되었습니다.\\n".$rtime."후에 재접속 바랍니다.");
		}
	}

}
$wiz_admin = array();

if($admin_info['passwd'] == md5($admin_pw)){

	$sql = "update wiz_admin set last = now() where id='$admin_id'";
	$result = query($sql);

	if($site_info['sesschk'] == 'Y'){ //로그인중복체크

		if($site_info['coverage'] == 1) {
			$sess_sql = " AND sess_ip != '".$_SERVER['REMOTE_ADDR']."' ";
		} else {
			$sess_sql = " AND (sess_ip != '".$_SERVER['REMOTE_ADDR']."' OR sess_ip = '".$_SERVER['REMOTE_ADDR']."') ";
		}

		$sql = "SELECT * FROM wiz_session WHERE user_id = '".$admin_info['id']."' $sess_sql ";
		$sql .= " ORDER BY sess_datetime DESC LIMIT 1";
		$row = sql_fetch($sql);

		if($row){
			fwrite( $logfile, "[중복로그인 감지] \r\n");
			fwrite($logfile, "\r\n");
			fwrite($logfile,"************************************************************************************************************************************************\r\n");
			fwrite($logfile, "\r\n");
			fclose($logfile);
			//alertConfirmSess("다른 컴퓨터에 로그인 되어 있습니다.\\n강제종료후 로그인 하시겠습니까?", $admin_id);
			alertrtn("다른 컴퓨터에 로그인 되어 있습니다.\\n강제종료후 로그인 합니다.");
			query("delete from wiz_session where user_id = '$admin_id'");
		}

		$wiz_admin['id']          = $admin_info['id'];
		$wiz_admin['name']        = $admin_info['name'];
		$wiz_admin['email']       = $admin_info['email'];
		$wiz_admin['lev']       = $admin_info['lev'];
		$wiz_admin['ip']          = $user_ip;

	} else {

		$wiz_admin['id']          = $admin_info['id'];
		$wiz_admin['name']        = $admin_info['name'];
		$wiz_admin['email']       = $admin_info['email'];
		$wiz_admin['lev']       = $admin_info['lev'];
		$wiz_admin['ip']          = $user_ip;

	}
	if($admin_info['lev'] && $admin_info['lev'] != 10000) {
		$sql_lev = "select * from wiz_admin_lev where idx='".$admin_info['lev']."'";
		$row_lev = sql_fetch($sql_lev);
		$wiz_admin['permi'] = $row_lev['permi'];
	}

	if(isset($_SESSION['wiz_session']['id'])) unset($_SESSION['wiz_session']);
	$_SESSION['wiz_admin'] = $wiz_admin;


	##기존 제한카운터가 있을시 로그인후 초기화
	if($admin_info['login_fail_count'] > 0){
		fwrite( $logfile, "counter reset \r\n");
	}
	query("UPDATE wiz_admin SET is_account_lock='N', login_try_time='', login_fail_count=0 WHERE id='$admin_id' ");

	//MemberInactiveAccountNotice(30);
	//MemberInactiveChange();
	deleteOldTempFile();			// 게시판 첨부파일 임시파일 제거

	fwrite($logfile, "\r\n");
	fwrite($logfile, "[Success]\r\n");
	foreach($_POST as $KEY => $VALUES){
		if($KEY != 'admin_pw'){
			fwrite( $logfile, $KEY." : ".$VALUES."\r\n");
		}
	}
	fwrite($logfile, "ip : ".$_SERVER["REMOTE_ADDR"]."\r\n");
	fwrite($logfile,"************************************************************************************************************************************************\r\n");
	fwrite($logfile, "\r\n");
	fclose($logfile);

	echo "<script>document.location='$start_page'</script>";


} else {

	if($site_info['sesschk'] == 'Y'){// 중복로그인 체크

		if($site_info['coverage'] == 1) {
			$sess_sql = " AND sess_ip != '".$_SERVER['REMOTE_ADDR']."' ";
		} else {
			$sess_sql = " AND (sess_ip != '".$_SERVER['REMOTE_ADDR']."' OR sess_ip = '".$_SERVER['REMOTE_ADDR']."') ";
		}

		$sql = "SELECT * FROM wiz_session WHERE user_id = '".$site_info['designer_id']."' $sess_sql ";
		$sql .= " ORDER BY sess_datetime DESC LIMIT 1";
		$row = sql_fetch($sql);

		if($row){
			fwrite( $logfile, "[중복로그인 감지] \r\n");
			fwrite($logfile, "\r\n");
			fwrite($logfile,"************************************************************************************************************************************************\r\n");
			fwrite($logfile, "\r\n");
			fclose($logfile);
			alertrtn("다른 컴퓨터에 로그인 되어 있습니다.\\n강제종료후 로그인 합니다.");
			query("delete from wiz_session where user_id = '".$site_info['designer_id']."'");
		}

	}
	if($site_info['designer_id'] == $admin_id && $site_info['designer_pw'] == md5($admin_pw)) { //디자이너 아이디 로그인

		if($site_info['sesschk'] == 'Y'){//중복로그인 체크
			$wiz_admin['id']          = $site_info['designer_id'];
			$wiz_admin['name']        = $site_info['site_name'];
			$wiz_admin['email']       = $site_info['site_email'];
			$wiz_admin['designer']    = 'Y';
			$wiz_admin['ip']          = $user_ip;

		} else {

			$wiz_admin['id']          = $site_info['designer_id'];
			$wiz_admin['name']        = $site_info['site_name'];
			$wiz_admin['email']       = $site_info['site_email'];
			$wiz_admin['designer']    = 'Y';
			$wiz_admin['ip']          = $user_ip;

		}
		$_SESSION['wiz_admin'] = $wiz_admin;

//		MemberInactiveAccountNotice(30);
//		MemberInactiveChange();
		deleteOldTempFile();			// 게시판 첨부파일 임시파일 제거

		fwrite($logfile, "\r\n");
		fwrite($logfile, "[Success] \r\n");
		foreach($_POST as $KEY => $VALUES){
			if($KEY != 'admin_pw'){
				fwrite( $logfile, $KEY." : ".$VALUES."\r\n");
			}
		}
		fwrite($logfile, "ip : ".$_SERVER["REMOTE_ADDR"]."\r\n");
		fwrite($logfile,"************************************************************************************************************************************************\r\n");
		fwrite($logfile, "\r\n");
		fclose($logfile);
		echo "<script>location.href='".$start_page."';</script>";

	} else {

		if($site_info['login_limit_use'] == "Y") { //로그인 접속 제한
		
			$login_fail_count = $admin_info['login_fail_count'];

			query("UPDATE wiz_admin SET login_fail_count=login_fail_count+1 WHERE id='$admin_id'");

			$sql2 = "select * from wiz_admin where id = '$admin_id' and is_account_lock='N' ";
			$admin_info2 = sql_fetch($sql2);

			if($admin_info2['login_fail_count'] >= $site_info['login_limit_count']){
				query("UPDATE wiz_admin SET is_account_lock='Y', login_try_time=now() WHERE id='$admin_id' ");
			}

		}

		fwrite($logfile, "\r\n");
		fwrite($logfile, "[Fail] \r\n");
		foreach($_POST as $KEY => $VALUES){
			if($KEY != 'admin_pw'){
				fwrite( $logfile, $KEY." : ".$VALUES."\r\n");
			}
		}
		fwrite($logfile, "ip : ".$_SERVER["REMOTE_ADDR"]."\r\n");
		fwrite($logfile,"************************************************************************************************************************************************\r\n");
		fwrite($logfile, "\r\n");
		fclose($logfile);

		error("회원정보가 일치하지 않습니다.");

	}

}

?>
