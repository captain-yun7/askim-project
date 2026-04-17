<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	error("잘못된 접근입니다.");
}


$id     = sql_filter(trim($_POST['id']));
$passwd = (trim($_POST['passwd']));
$passwd = md5($passwd);

$mypage_url = $mem_info['myinfo_url'];

$null_blank = md5('');
if((trim($id) == "" || trim($passwd) == $null_blank)) {
	error('잘못된 경로입니다.','about:blank');
}

$m_sql = "select * from wiz_member where id = '".$id."'  ";
$m_data = sql_fetch($m_sql);

## 로그인 접속제한
if($site_info['login_limit_use'] == "Y") {

	if($m_data['login_fail_count'] >= $site_info['login_limit_count']){

		$date1 = strtotime($m_data['login_try_time']);
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

		if($diff >= $site_info['login_limit_time']){
			query("update wiz_member 
			          set is_account_lock='N'
					    , login_try_time=''
						, login_fail_count=0 
					where id='$id' 
					  and dchange_type = 'N' 
				 ");
		} else {
			error("로그인 중복실패로 로그인 접속이 차단되었습니다.\\n".$rtime."후에 재접속 바랍니다.");
		}
	}

}

// 회원로그인
$sql = "
	select id
		 , passwd
		 , name
		 , hphone
		 , tphone
		 , email
		 , level
		 , login_fail_count
		 , login_try_time
		 , is_account_lock 
		 , pw_update
	  from wiz_member 
	 where id='$id' 
	   and passwd='$passwd'
";
$result = query($sql);
if($mem_info = sql_fetch_arr($result)){

	$level_info = level_info();
	$level = $mem_info['level'];
	$level_value = $level_info[$level]['level'];


	$sql = "
		update wiz_member 
		   set visit = visit+1 
		     , visit_time = now() 
		 where id='$id' 
		   and dchange_type = 'N' 
	";
	$result = query($sql);

	if($site_info['sesschk'] == 'Y'){

		if($site_info['coverage'] == 1) {
			$sess_sql = " AND sess_ip != '".$_SERVER['REMOTE_ADDR']."' ";
		} else {
			$sess_sql = " AND (sess_ip != '".$_SERVER['REMOTE_ADDR']."' OR sess_ip = '".$_SERVER['REMOTE_ADDR']."') ";
		}

		$sql = "SELECT * FROM wiz_session WHERE user_id = '$id' $sess_sql ";
		$sql .= " ORDER BY sess_datetime DESC LIMIT 1";
		$row = sql_fetch($sql);

		if($row){
			alertrtn("다른 컴퓨터에 로그인 되어 있습니다.\\n강제종료후 로그인 합니다.");
			query("delete from wiz_session where user_id = '$id'");
		}

	}

	##기존 제한카운터가 있을시 로그인후 초기화
	query("update wiz_member 
	          set is_account_lock='N'
			    , login_try_time=''
				, login_fail_count=0 
			where id='$id' 
			  and dchange_type = 'N' 
		 ");

	// 미니홈피 사용 시 미니홈피 자동생성
	if(!strcmp($site_info['mini_use'], "Y")) {

		$sql = "select count(idx) as cnt from wiz_mini_info where memid = '$id'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row['cnt'] < 1) {
			$title = $id."님의 미니홈피";
			$menu_use = "PRO/BBS/DATA/PHOTO/MOVIE/GUEST/";
			$sql = "insert into wiz_mini_info(memid, title, menu_use, wdate) values('$id', '$title', '$menu_use', now())";
			query($sql);
		}

	}
	save_point("LOGIN", $id);

// 관리자 로그인
} else {

	$sql = "
		select id
			 , passwd
			 , name
			 , hphone
			 , tphone
			 , email
			 , login_fail_count
			 , login_try_time
			 , is_account_lock 
		  from wiz_admin 
		 where id='$id' 
		   and passwd='$passwd'
	";
	$result = query($sql);
	if($mem_info = sql_fetch_arr($result)){
		error("관리자 아이디는 사용자페이지에 로그인 할 수 없습니다.","");
		exit;

		$level = 0;
		$level_value = 0;

	}else{

		if($site_info['login_limit_use'] == "Y") {

			$login_fail_count = $m_data['login_fail_count'];
			query("update wiz_member 
			          set login_fail_count=login_fail_count+1 
					where id='$id' 
					  and dchange_type = 'N' 
			     ");

			$new_m_sql = "select * from wiz_member where id='$id' AND dchange_type = 'N' ";
			$new_m_data = sql_fetch($new_m_sql);

			if($new_m_data['login_fail_count'] >= $site_info['login_limit_count']){
				query("update wiz_member 
				          set is_account_lock='Y'
						    , login_try_time=now() 
						where id='$id' 
						  and dchange_type = 'N' 
					  ");
			}

		}

		if($dormancy != "Y") {
			error("회원정보가 일치하지 않습니다.", "");
		}

	}

}

if(isset($_SESSION['wiz_admin']['id'])) unset($_SESSION['wiz_admin']);

$_SESSION['wiz_session']['id']            = $mem_info['id'];
$_SESSION['wiz_session']['passwd']        = $mem_info['passwd'];
$_SESSION['wiz_session']['name']          = $mem_info['name'];
$_SESSION['wiz_session']['email']         = $mem_info['email'];
$_SESSION['wiz_session']['hphone']        = $mem_info['hphone'];
$_SESSION['wiz_session']['tphone']        = $mem_info['tphone'];
$_SESSION['wiz_session']['level']         = $level;
$_SESSION['wiz_session']['level_value']   = $level_value;

$_SESSION['wiz_session']['wiz_basket_id'] = $mem_info['id'];

?>
<?php
if($site_info['autologin_use'] == 'Y') {
?>
<script>
var auto_login = "<?=$auto_login?>";
if(auto_login=="Y"){
	localStorage.setItem("id", "<?=$mem_info['id']?>");
	localStorage.setItem("passwd", "<?=$passwd?>");
	localStorage.setItem("auto_login", "Y");
}else{
	localStorage.setItem("id", "");
	localStorage.setItem("passwd", "");
	localStorage.setItem("auto_login", "N");
}
</script>
<?php
}

$tmp_basket_idx = array();
$tmp_sql = "select idx from wiz_basket_tmp where uniq_id = '".$_uniq_id."' ";
$tmp_res = query($tmp_sql);
while($tmp_row = sql_fetch_arr($tmp_res)) {
	$tmp_basket_idx[] = $tmp_row['idx'];
}

$uproduct_idx = implode("|", $tmp_basket_idx);

if($uproduct_idx != ""){

	$product_idx_arr = explode("|",$uproduct_idx);
	$basket_uniq_id = md5($_SESSION['wiz_session']['wiz_basket_id']);

	for($kk=0; $kk<count($product_idx_arr); $kk++){
		$sql_up = "
			update wiz_basket_tmp 
			   set uniq_id='$basket_uniq_id'
			     , memid='{$mem_info['id']}' 
			 where idx='{$product_idx_arr[$kk]}'
		";
		query($sql_up);
	}

}

if($product_idx != ""){

	$product_idx_arr = explode("|",$product_idx);

	$basket_uniq_id = md5($_SESSION['wiz_session']['wiz_basket_id']);

	for($kk=0; $kk<count($product_idx_arr)-1; $kk++){
		$sql_up = "
			update wiz_basket_tmp 
			   set uniq_id='$basket_uniq_id'
			     , memid='{$mem_info['id']}' 
			 where idx='{$product_idx_arr[$kk]}'
		";
		query($sql_up);
	}

}

if($site_info['login_pw_chg'] == "Y") {
	$pw_update = time_chk($mem_info['pw_update']);
	if($pw_update >= $site_info['login_pwchg_day']) {
		echo "<script>alert('개인정보 보호를 위해 비밀번호를 주기적으로 변경해주세요.'); document.location='/".$mypage_url."';</script>";
	}
}
if(strpos($prev, 'http') !== false &&  strpos(str_replace('www', '', $prev), str_replace('www', '', $HTTP_HOST)) === false) $prev='/';

if(strpos(basename($prev), 'join.php') !== false) {
	echo "<script>document.location='/';</script>";
} else if($prev_code_page != "") {
	if($prev == "") $prev = "http://".$_http_host;
	else $prev = "http://".$_http_host.urldecode($prev."&code_page=".$prev_code_page);
	echo "<script>document.location='$prev';</script>";
} else {
	if($prev == "") $prev = "http://".$_http_host;
	else $prev = "http://".$_http_host.urldecode($prev);
	echo "<script>document.location='$prev';</script>";
}

?>