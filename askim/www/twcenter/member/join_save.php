<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

// 스팸글 차단
$pos = strpos($HTTP_REFERER, $_http_host);
if($pos === false) error("잘못된 경로 입니다.");

if($info_use['spam'] == true){
	// 자동등록방지 코드 검사
	if(empty($_POST['tmp_vcode']) || empty($_POST['vcode'])) {
		error("자동등록방지 코드가 존재하지 않습니다.");
	} else if(strcmp($_POST['tmp_vcode'], md5($_POST['vcode']))) {
		error("자동등록방지 코드가 일치하지 않습니다.");
	}
}

if(($_POST['id'] == '' || $_POST['passwd1'] == '')) {
	error('잘못된 경로 입니다.');
}

if($sns_Login == 'NH') {
	$id = "NH".date("ymdHis").rand(100,999);
} else {
	$id = $_POST['id'];
}

if(!is_array($fname)) $fname = (array)[];


if(!empty($id)){ /* 2021-05-21 아이디 중복확인 한번더 체크*/
	$sql1 = "select count(id) as cnt from wiz_member where id='$id'";
	if($sns_Login != '') {
		$sql1 .= " or (sns_Login='$sns_Login' and sns_id='$sns_id')";
	}
	$result1 = query($sql1);
	$row1 = sql_fetch_arr($result1);
	$total = $row1['cnt'];

	$sql2 = "select count(id) as cnt from wiz_admin where id = '$id'";
	$result2 = query($sql2);
	$row2 = sql_fetch_arr($result2);
	$total2 = $row2['cnt'];

	$sql3 = "select count(designer_id) as cnt from wiz_siteinfo where designer_id  = '$id' or anywiz_id = '".md5($id)."'";
	$result3 = query($sql3);
	$row3 = sql_fetch_arr($result3);
	$total3 = $row3['cnt'];

	if($total > 0){
		error("'".$id."' 는 이미 사용중인 아이디 입니다.아이디를 확인해주세요.");
	}else if($total2+$total3 >0){
		error("'".$id."' 는 사용할 수 없는 아이디 입니다.");
	}
}

if(!empty($nick)){ /* 2021-05-21 닉네임 중복확인 한번더 체크*/
	$nsql = "select count(nick) as cnt from wiz_member where nick='$nick'";
	$nresult = query($nsql);
	$nrow = sql_fetch_arr($nresult);
	$ntotal = $nrow['cnt'];

	$nick_val = strip_tags(strtolower($nick));
	$filterid = explode(",", trim($mem_info['prohibit_id']));

	for($i=0; $i<count($filterid); $i++) {
		$f_string = $filterid[$i];
		$pos = strpos($nick_val, $f_string);

		if($pos !== false) {
			$res = 'true';
		}
	}

	if($res == "true"){
		$checkNick = "F";
	} else {
		$checkNick = "O";
	}

	if($ntotal > 0){
		error("'".$nick."' 는 이미 사용중인 닉네임 입니다.");
	}else if($checkNick == "F"){
		error("'".$nick."' 는 사용할 수 없는 닉네임 입니다.");
	}
}

$level = level_basic();
$passwd = $_POST['passwd1'];

if(isset($_POST['tphone']) && $_POST['tphone']) {
	$tphone = implode("-", array_filter($_POST['tphone']));
	$tphone = (strpos($tphone, '--') !== false) ? '' : $tphone;
} else {
	$tphone = "";
}
if(isset($_POST['hphone']) && $_POST['hphone']) {
	$hphone = implode("-", array_filter($_POST['hphone']));
	$hphone = (strpos($hphone, '--') !== false) ? '' : $hphone;
} else {
	$hphone = "";
}
if(isset($_POST['comtel']) && $_POST['comtel']) {
	$comtel = implode("-", array_filter($_POST['comtel']));
	$comtel = (strpos($comtel, '--') !== false) ? '' : $comtel;
} else {
	$comtel = "";
}
if(isset($_POST['email']) && $_POST['email']) {
	$email = implode("@", array_filter($_POST['email']));
} else {
	$email = "";
}

$resno   = $resno1."-".$resno2;
$conn_ip = $_SERVER['REMOTE_ADDR'];

if(isset($_POST['birthday']) && $_POST['birthday']) {
	$birthday = implode("-", array_filter($_POST['birthday']));
	$birthday = (strpos($birthday, '--') !== false) ? '' : $birthday;
} else {
	$birthday = "";
}

/*if(isset($_POST['birthday']) && $_POST['birthday']) {
	$birthday = $_POST['birthday'];
	$birthday_b = str_pad($birthday[1], 2, '0', STR_PAD_LEFT);
	$birthday_c = str_pad($birthday[2], 2, '0', STR_PAD_LEFT);
	$birthday = $birthday[0]."-".$birthday_b."-".$birthday_c;
	$birthday = (strpos($birthday, '--') !== false) ? '' : $birthday;
} else {
	$birthday = "";
}*/
if(isset($_POST['memorial']) && $_POST['memorial']) {
	$memorial = $_POST['memorial'];
	$memorial_b = str_pad($memorial[1], 2, '0', STR_PAD_LEFT);
	$memorial_c = str_pad($memorial[2], 2, '0', STR_PAD_LEFT);
	$memorial = $memorial[0]."-".$memorial_b."-".$memorial_c;
	$memorial = (strpos($memorial, '--') !== false) ? '' : $memorial;
} else {
	$memorial = "";
}

for($ii=0; $ii<count($consph); $ii++){ $consph_checked .= $consph[$ii].","; }

// 주민번호 중복체크
if($resno != "-"){

	$sql = "select id from wiz_member where resno = '$resno'";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	if($total > 0) error("이미등록된 주민번호 입니다.\\n\\n관리자에게 문의하시기 바랍니다.");

}

// 사진등록
if($photo['size'] > 0){

	ImageResize_Upload_check($photo['name']);

///	$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $photo['name']);
///	$len = (strlen($ext) == 3) ? "-3" : "-4";
	//$file_ext = strtolower(substr($photo['name'],$len));
	$file_ext = getFileExt($photo['name']);

	$upfile_path = WIZHOME_PATH."/data/member";
	if(!is_dir($upfile_path)) {
		$oldmask = umask(0);
		mkdir($upfile_path, 0707);
		umask($oldmask);
	} else if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
		$oldmask = umask(0);
		chmod($upfile_path, 0707);
		umask($oldmask);
	}

	if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
		echo("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.".fileperms($upfile_path));
		exit;
	}

	$photo_name = $id.".".$file_ext;
	copy($photo['tmp_name'], $upfile_path."/".$photo_name);
	chmod($upfile_path."/".$photo_name, 0606);

	$srcimg = $photo_name;
	$dstimg = $photo_name;
	$photo_width = "120";
	img_resize($srcimg, $dstimg, $upfile_path, $photo_width, $photo_height);

}

// 아이콘등록
if($icon['size'] > 0){

	ImageResize_Upload_check($icon['name']);

	$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $icon['name']);
	$len = (strlen($ext) == 3) ? "-3" : "-4";
	$file_ext = strtolower(substr($icon['name'],$len));

	$upfile_path = WIZHOME_PATH."/data/member";
	if(!is_dir($upfile_path)) {
		$oldmask = umask(0);
		mkdir($upfile_path, DIR_PERM);
		umask($oldmask);
	} else if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
		chmod($upfile_path, DIR_PERM);
	}

	if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
		error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
	}

	$icon_name = $id."_icon.".$file_ext;
	copy($icon['tmp_name'], $upfile_path."/".$icon_name);
	chmod($upfile_path."/".$icon_name, 0606);

	$srcimg = $icon_name;
	$dstimg = $icon_name;
	$icon_width = $icon_size;
	img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);

}

/*
작업일시	: 2022-02-24
작업자명	: 이상민
작업내용	: 회원가입 시 휴대폰번호 중복확인
*/
if($info_use['hphone']=="true"){
	if($hphone != ""){
		$sql = " select count(*) as cnt from wiz_member where hphone = '".$hphone."' ";
		$row = sql_fetch($sql);

		if($row['cnt'] > 0){
			error("이미 가입된 휴대폰 번호 입니다.");
			exit;
		}
	} else {
		if($info_ess['hphone']=="true"){
			error("휴대폰 번호는 필수입력사항 입니다.");
			exit;
		}
	}
}

/*
작업일시	: 2022-02-24
작업자명	: 이상민
작업내용	: 회원가입 시 이메일 중복확인
*/
if($info_use['email']=="true"){
	if($email != ""){
		$sql = " select count(*) as cnt from wiz_member where email = '".$email."' ";
		$row = sql_fetch($sql);

		if($row['cnt'] > 0){
			error("이미 가입된 이메일주소 입니다.");
			exit;
		}
	} else {
		if($info_ess['email']=="true"){
			error("이메일 주소는 필수입력사항 입니다.");
			exit;
		}
	}
}

$sql = "select * from wiz_formfield where fidx = 'addinfo' order by fprior asc, idx asc";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	$no = $row['fprior'];

	if($row['ftype'] != "file"){

		$fvalue = "";

		if($row['ftype'] == "address"){

			$fpost = ${"f".$no."_post"};
			$faddress1 = ${"f".$no."_address1"};
			$faddress2 = ${"f".$no."_address2"};

			$fvalue = $fpost."|".$faddress1."|".$faddress2;

		} else if($row['ftype'] == "tdate") {

			$year1  = $fname[$no][0];
			$month1 = $fname[$no][1];
			$day1   = $fname[$no][2];
			$time1  = $fname[$no][3];

			$year2  = $fname[$no][4];
			$month2 = $fname[$no][5];
			$day2   = $fname[$no][6];
			$time2  = $fname[$no][7];

			$fvalue = $year1."-".$month1."-".$day1."&nbsp;".$time1."~";
			$fvalue .= $year2."-".$month2."-".$day2."&nbsp;".$time2;

		} else if($row['ftype'] == "email") {

			$email1 = ${"f".$no."_email1"};
			$email2 = ${"f".$no."_email2"};

			if(!empty($email1) || !empty($email2)) $fvalue = $email1."@".$email2;

		} else if($row['ftype'] != "radio") {

			$split_char = "|";
			if($row['ftype'] == "pdate") $split_char = "~";
			
			if(!isset($fname[$no])) $fname[$no] = (array)[]; 

			for($ii=0;$ii<count($fname[$no]);$ii++){
				$fvalue .= $fname[$no][$ii];
				if($ii<count($fname[$no])-1) $fvalue .= $split_char;
			}

		} else {

			$fvalue = $fname[$no];

		}

		${"addinfo".$no} = $fvalue;
		
		${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";

	} else {

		$fvalue = "";

		for($ii = 1; $ii <= $row['fnum']; $ii++) {

			// 파일등록
			$upfile		 = $_FILES['upfile'.$ii]['tmp_name'];
			$upfile_size = $_FILES['upfile'.$ii]['size'];
			$upfile_name = $_FILES['upfile'.$ii]['name'];

			if($upfile_size > 0){

				ImageResize_Upload_check($upfile_name);

				$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $upfile_name);
				$len = (strlen($ext) == 3) ? "-3" : "-4";
				$file_ext = strtolower(substr($upfile_name,$len));

				$upfile_path = WIZHOME_PATH."/data/member";
				if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
				$upfile_name = $id."_".$no."_".$ii.".".$file_ext;

				exec("rm -f ".$upfile_path."/".$id."_".$no."_".$ii.".*");
				copy($upfile, $upfile_path."/".$upfile_name);
				chmod($upfile_path."/".$upfile_name, 0606);


				$split_char = "|";

				$fvalue .= $upfile_name;
				if($ii < $row['fnum']) $fvalue .= $split_char;

			} else {

				$split_char = "|";

				$fvalue .= ${"tmp_upfile_".$no."_".$ii};
				if($ii < $row['fnum']) $fvalue .= $split_char;

			}
		}

		${"addinfo".$no} = $fvalue;

		if(!empty($fvalue)) {
			${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";
		}

	}

	$no++;
}

$sql_com = "";
$sql_com .= " id                 = '$id'                      ";
$sql_com .= " , passwd           = '".md5($passwd)."'         ";
$sql_com .= " , name             = '$name'                    ";
$sql_com .= " , photo            = '$photo_name'              ";
$sql_com .= " , icon             = '$icon_name'               ";
$sql_com .= " , nick             = '$nick'                    ";
$sql_com .= " , resno            = '$resno'                   ";
$sql_com .= " , email            = '$email'                   ";
$sql_com .= " , tphone           = '$tphone'                  ";
$sql_com .= " , hphone           = '$hphone'                  ";
$sql_com .= " , comtel           = '$comtel'                  ";
$sql_com .= " , homepage         = '$homepage'                ";
$sql_com .= " , post             = '$post'                    ";
$sql_com .= " , address1         = '$address1'                ";
$sql_com .= " , address2         = '$address2'                ";
$sql_com .= " , reemail          = '$reemail'                 ";
$sql_com .= " , resms            = '$resms'                   ";
$sql_com .= " , birthday         = '$birthday'                ";
$sql_com .= " , bgubun           = '$bgubun'                  ";
$sql_com .= " , marriage         = '$marriage'                ";
$sql_com .= " , memorial         = '$memorial'                ";
$sql_com .= " , scholarship      = '$scholarship'             ";
$sql_com .= " , job              = '$job'                     ";
$sql_com .= " , income           = '$income'                  ";
$sql_com .= " , car              = '$car'                     ";
$sql_com .= " , hobby            = '$hobby'                   ";
$sql_com .= " , consph           = '$consph_checked'          ";
$sql_com .= " , conprd           = '$conprd'                  ";
$sql_com .= " , level            = '$level'                   ";
$sql_com .= " , recom            = '$recom'                   ";
$sql_com .= " , visit            = '$visit'                   ";
$sql_com .= " , visit_time       = '$visit_time'              ";
$sql_com .= " , intro            = '$intro'                   ";
$sql_com .= " , memo             = '$memo'                    ";
$sql_com .= " , addinfo1         = '$addinfo1'                ";
$sql_com .= " , addinfo2         = '$addinfo2'                ";
$sql_com .= " , addinfo3         = '$addinfo3'                ";
$sql_com .= " , addinfo4         = '$addinfo4'                ";
$sql_com .= " , addinfo5         = '$addinfo5'                ";
$sql_com .= " , wdate            = now()                      ";
$sql_com .= " , sns_id           = '$sns_id'                  ";
$sql_com .= " , sns_Login        = '$sns_Login'               ";
$sql_com .= " , ip               = '$conn_ip'                 ";
$sql_com .= " , pw_update        = now()                      ";

$sql = "INSERT INTO wiz_member SET {$sql_com} ";
query($sql);

// 가입포인트 저장
// save_point("JOIN", $id);

// 적립금 처리
if($oper_info['reserve_use'] == "Y"){



	// 회원가입 적립테이블에 저장
	
	if($oper_info['reserve_join'] > 0){

		$reserve_msg = "회원가입 적립금";

		$sql_com = "";
		$sql_com .= " memid                 = '$id'                              ";
		$sql_com .= " , reservemsg          = '$reserve_msg'                     ";
		$sql_com .= " , reserve             = '{$oper_info['reserve_join']}'         ";
		$sql_com .= " , orderid             = ''                                 ";
		$sql_com .= " , wdate               = now()                              ";

		$sql = "INSERT INTO wiz_reserve SET {$sql_com} ";
		query($sql);

	}

}

if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {

	$templateCode         = $site_info['alimtalk_id']."_mem_join";
	$talk_info['id']      = $id;
	$talk_info['hphone']  = $hphone;
	$talk_info['wdate']   = date("Y-m-d");

	$return_code = send_alimtalk($templateCode,$talk_info);

	if($return_code != "AS") {
		if($site_info['sms_use'] == 'Y') {
			$re_info['id']      = $id;
			$re_info['name']    = $name;
			$re_info['passwd']  = $passwd;
			$re_info['hphone']  = $hphone;
			$re_info['email']   = $email;
			send_mailsms("mem_join", $re_info);		// 알림톡 전송 오류 시 메일/sms발송
		}
	} else {
		send_mailsms("mem_join", $re_info, '', 'E');			// 알림톡 전송되었을 때는 메일만 발송
	}

} else {

	// 가입메일,SMS발송
	$re_info['id']      = $id;
	$re_info['name']    = $name;
	$re_info['passwd']  = $passwd;
	$re_info['hphone']  = $hphone;
	$re_info['email']   = $email;
	send_mailsms("mem_join", $re_info);

}


/* 
	회원가입 후 자동 로그인 
	update 2020-05-29  김나연
*/
$level_info = level_info();
$level_value = $level_info[$level]['level'];

$_SESSION['wiz_session']['id']            = $id;
$_SESSION['wiz_session']['passwd']        = $passwd;
$_SESSION['wiz_session']['name']          = $name;
$_SESSION['wiz_session']['email']         = $email;
$_SESSION['wiz_session']['hphone']        = $hphone;
$_SESSION['wiz_session']['tphone']        = $tphone;
$_SESSION['wiz_session']['level']         = $level;
$_SESSION['wiz_session']['level_value']   = $level_value;

$_SESSION['wiz_session']['wiz_basket_id'] = $id;

if($sns_id!="") { // sns로그인일 때 로그인타입 세션 추가 2021-06-01 임서연
	$_SESSION['wiz_session']['login_Type'] = "sns";
}

$sql = "
	update wiz_member 
	   set visit = visit+1 
		 , visit_time = now() 
	 where id='$id' 
	   and dchange_type = 'N' 
";
$result = query($sql);

if(empty($prev)) $prev = SSL.$_http_host;
?>
<script>
	document.location = "<?=$prev?>?ptype=ok&id=<?=$id?>";
</script>