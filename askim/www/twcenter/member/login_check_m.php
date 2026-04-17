<?php
header('Content-Type: text/html; charset=utf-8'); 

include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	error("잘못된 접근입니다.");
}
$id     = sql_filter(trim($_POST['id']));
$passwd = (trim($_POST['passwd']));
$passwd = md5($passwd);

$null_blank = md5('');
if((trim($id) == "" || trim($passwd) == $null_blank) && $dormancy != "Y") {
	error('잘못된 경로입니다.','about:blank');
}

$m_sql = "select * from wiz_member where id='$id' ";
$m_data = sql_fetch($m_sql);
/*
if($m_data['dchange_type'] == 'Y' && $m_data['dchange_date'] !== '0000-00-00 00:00:00') {
	$go_url = "/".$mem_info['login_url']."?dormancy=Y";
	InactiveMemberChangeTbl($id, 'm');
	alertrtn("고객님의 계정이 휴면해제 되었습니다");
	//error("해당아이디는 휴면상태입니다.\\n재 로그인후 정상접속됩니다.", $go_url);
}*/

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
			query("UPDATE wiz_member SET is_account_lock='N', login_try_time='', login_fail_count=0 WHERE id='$id' AND dchange_type = 'N' ");
		} else {
			error("로그인 중복실패로 로그인 접속이 차단되었습니다.\\n".$rtime."후에 재접속 바랍니다.");
		}
	}

}

// 회원로그인
$sql = "select id,passwd,name,hphone,tphone,email,level from wiz_member where id='$id' and passwd='$passwd' AND dchange_type = 'N' ";
$result = query($sql) or error("sql error");
if($mem_info = sql_fetch_arr($result)){

	$level_info = level_info();
	$level = $mem_info['level'];
	$level_value = $level_info[$level][level];

	$sql = "update wiz_member set visit = visit+1 , visit_time = now() where id='$id' AND dchange_type = 'N' ";
	$result = query($sql) or error("sql error");

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
		$result = query($sql) or error("sql error");
		$row = sql_fetch_arr($result);

		if($row['cnt'] < 1) {
			$title = $id."님의 미니홈피";
			$menu_use = "PRO/BBS/DATA/PHOTO/MOVIE/GUEST/";
			$sql = "insert into wiz_mini_info(memid, title, menu_use, wdate) values('$id', '$title', '$menu_use', now())";
			query($sql) or error("sql error");
		}

	}

	save_point("LOGIN", $id);

// 관리자 로그인
} else {

	$sql = "select id,passwd,name,hphone,tphone,email from wiz_admin where id='$id' and passwd='$passwd'";
	$result = query($sql) or error("sql error");

	if($mem_info = sql_fetch_arr($result)){
		error("관리자 아이디는 사용자페이지에 로그인 할 수 없습니다.","/m");
		exit;
		$level = 0;
		$level_value = 0;
	} else {

		if($site_info['login_limit_use'] == "Y") {

			$login_fail_count = $m_data['login_fail_count'];
			query("UPDATE wiz_member SET login_fail_count=login_fail_count+1 WHERE id='$id' AND dchange_type = 'N' ");

			$new_m_sql = "select * from wiz_member where id='$id' AND dchange_type = 'N' ";
			$new_m_data = sql_fetch($new_m_sql);

			if($new_m_data['login_fail_count'] >= $site_info['login_limit_count']){
				query("UPDATE wiz_member SET is_account_lock='Y', login_try_time=now() WHERE id='$id' AND dchange_type = 'N' ");
			}

		}

		$msg = "회원정보가 일치하지 않습니다.";
		echo "<script>alert(\"$msg\");history.go(-1);</script>";
		exit;
	}

}
/*
if(!empty($save_check)) {

	$time = time() + (60*60*24*10); // 현재시간 + 60초 * 60분 * 24시간 * 10일 (합이 10일 후)
	setcookie("save_id", $mem_info['id'], $time, "/");

}
*/

//php5 이상 세션등록
$_SESSION['wiz_session']['id']            = $mem_info['id'];
$_SESSION['wiz_session']['passwd']        = $mem_info['passwd'];
$_SESSION['wiz_session']['name']          = $mem_info['name'];
$_SESSION['wiz_session']['email']         = $mem_info['email'];
$_SESSION['wiz_session']['hphone']        = $mem_info['hphone'];
$_SESSION['wiz_session']['tphone']        = $mem_info['tphone'];
$_SESSION['wiz_session']['level']         = $level;
$_SESSION['wiz_session']['level_value']   = $level_value;

$_SESSION['wiz_session']['wiz_basket_id'] = $mem_info['id'];

if($site_inf['autologin_use'] == 'Y') { ?>

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
		$sql_up = "update wiz_basket_tmp set uniq_id='$basket_uniq_id', memid='$mem_info['id']' where idx='$product_idx_arr[$kk]'";
		query($sql_up);
	}

}

if($product_idx != ""){

	$product_idx_arr = explode("|",$product_idx);

	$basket_uniq_id = md5($_SESSION['wiz_session']['wiz_basket_id']);

	for($kk=0; $kk<count($product_idx_arr); $kk++){
		$sql_up = "update wiz_basket_tmp set uniq_id='$basket_uniq_id', memid='$mem_info['id']' where idx='$product_idx_arr[$kk]'";
		query($sql_up);
	}

}

if(empty($prev)) $prev = "http://".$_http_host."/m/";
echo "<script>document.location='$prev';</script>";

?>