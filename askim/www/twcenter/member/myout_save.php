<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if($reason == "") error("탈퇴사유를 입력하세요");
if($content == "") error("내용을 입력하세요");

if(empty($wiz_session['id'])) {
	error("로그인 후 이용해주세요.");
	exit;
}

$upfile_path = WIZHOME_PATH."/data/member";

// 추가항목이 파일인 경우 업로드 파일 삭제
$sql = "select addinfo1, addinfo2, addinfo3, addinfo4, addinfo5 from wiz_member where id = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");
$my_info = sql_fetch_arr($result);

$sql = "select * from wiz_formfield where fidx = 'addinfo' and ftype = 'file' order by fprior asc, idx asc";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	$no = $row['fprior'];

	$tmp_array = explode("|", $my_info["addinfo".$no]);

	for($ii = 0; $ii < count($tmp_array); $ii++) {
		@unlink($upfile_path."/".$tmp_array[$ii]);
	}

}

// 미니홈피 기능 사용 시 미니홈피 관련 데이터 삭제
if(!strcmp($site_info['mini_use'], "Y")) {

	include "../mini/inc/mini_info.php";

	$sql = "select photo,miniurl from wiz_mini_info where memid = '{$wiz_session['id']}'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	$miniurl_path = "$DOCUMENT_ROOT/$mini_dir/{$row['miniurl']}";

	if(!empty($row['miniurl'])) rm_dir("$miniurl_path");

	if(!empty($row['photo'])) @unlink(WIZHOME_PATH."/data/mini/".$row['photo']);

	rm_dir(WIZHOME_PATH."/data/minibbs/bbs/".$wiz_session['id']);
	rm_dir(WIZHOME_PATH."/data/minibbs/data/".$wiz_session['id']);
	rm_dir(WIZHOME_PATH."/data/minibbs/movie/".$wiz_session['id']);
	rm_dir(WIZHOME_PATH."/data/minibbs/photo/".$wiz_session['id']);
	rm_dir(WIZHOME_PATH."/data/minibbs/visit/".$wiz_session['id']);
	rm_dir(WIZHOME_PATH."/data/music/".$wiz_session['id']);

	$sql = "delete from wiz_mini_bbs where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_data where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_photo where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_movie where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_guest where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_bbscat where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_comment where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_conrefer where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_contime where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_friend  where myid = '{$wiz_session['id']}' or frdid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_info  where memid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_music  where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");

	$sql = "delete from wiz_mini_profile  where miniid = '{$wiz_session['id']}'";
	query($sql) or error("sql error");
}

@unlink($upfile_path."/".$wiz_session['id'].".gif");
@unlink($upfile_path."/".$wiz_session['id'].".jpg");
@unlink($upfile_path."/".$wiz_session['id']."_icon.gif");
@unlink($upfile_path."/".$wiz_session['id']."_icon.jpg");

// 회원테이블 삭제
$sql = "delete from wiz_member where id = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

$code = "[memout]";
$addmsg = "탈퇴사유 : ".$reason."<br><br>";
$addmsg .= "충고내용 : ".$message;

// 탈퇴내용 작성
$sql = "insert into wiz_bbs(idx,code,memid,name,subject,content,ip,wdate) values('','$code','{$wiz_session['id']}','{$wiz_session['name']}','$reason','$content','$REMOTE_ADDR',unix_timestamp('".date('Y-m-d H:i:s')."'))";
$result = query($sql) or error("sql error");

// 쿠폰 삭제
$sql = "delete from wiz_mycoupon where memid = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

// 회원포인트 삭제
$sql = "delete from wiz_point where memid = '{$wiz_session['id']}'";
@query($sql);

// 찜리스트 삭제
$sql = "delete from wiz_wishlist where memid = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

// 적립금 삭제
$sql = "delete from wiz_reserve where memid = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

// 인증내역 삭제
$sql = "delete from wiz_member_cert where id = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

// 주문내역 삭제(주문자 아이디를 [out] 으로 처리)
$sql = "update wiz_order set send_id = '".$wiz_session['id']."[out]' where  send_id = '{$wiz_session['id']}'";
$result = query($sql) or error("sql error");

// 회원탈퇴 메일/SMS 발송
$re_info['id'] = $wiz_session['id'];
$re_info['name'] = $wiz_session['name'];
$re_info['email'] = $wiz_session['email'];
$re_info['hphone'] = $wiz_session['hphone'];
send_mailsms("mem_out", $re_info);

// 로그아웃
//session_unregister("wiz_session");
unset($_SESSION['wiz_session']);

alert("회원탈퇴가 완료되었습니다.","/");

?>