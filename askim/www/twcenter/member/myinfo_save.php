<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

if(empty($wiz_session['id'])) {
	error("로그인 후 이용해주세요.");
	exit;
}

if(!empty($sns_id)){
	$sql = "select id from wiz_member where id='{$wiz_session['id']}' ";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$wiz_session['id'] = $row['id'];
} else {
	$wiz_session['id'] = $wiz_session['id'];
}

if(!isset($mode)) $mode = '';

if(!strcmp($mode, "addfile_del")) {

	$sql = "select addinfo".$no." from wiz_member where id='{$wiz_session['id']}'";
	$result = query($sql);
	$row = sql_fetch_arr($result);

	$upfile_path = WIZHOME_PATH."/data/member";
	@unlink($upfile_path."/".$upfile);

	$tmp_value = str_replace("$upfile", "", $row["addinfo".$no]);

	$sql = "update wiz_member set addinfo".$no." = '".$tmp_value."' where id='{$wiz_session['id']}'";
	query($sql);

	alert("삭제하였습니다.",$PHP_SELF);

} else {

	$sql_pw = "select *from wiz_member where id='{$wiz_session['id']}'";
	$result_pw = query($sql_pw);
	$row_pw = sql_fetch_arr($result_pw);

	$old_pw = $row_pw['passwd'];
	
	if($wiz_session['login_Type'] != "sns"){
		if(md5($old_passwd)!=$old_pw){
			echo "<script>alert('현재 비밀번호가 일치하지 않습니다.');location.href='/member/mypage.php';</script>";
			exit;
		}
	}
	
	if(!isset($_POST['name'])) $_POST['name'] = '';
	
	$name         = trim($_POST['name']);
	$passwd1      = trim($_POST['passwd1']);
	$address1     = isset($_POST['address1'])      ? trim($_POST['address1'])      : "";
	$address2     = isset($_POST['address2'])      ? trim($_POST['address2'])      : "";

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

	if (!empty($consph) && is_array($consph)) {
		$consph_count = count($consph);
	} else {
		$consph_count = 0;
	}

	if (!empty($conprd) && is_array($conprd)) {
		$conprd_count = count($conprd);
	} else {
		$conprd_count = 0;
	}

	for($ii=0; $ii<$consph_count; $ii++){
		$tmpconsph .= $consph[$ii].",";
	}
	for($ii=0; $ii<$conprd_count; $ii++){
		$tmpconprd .= $conprd[$ii].",";
	}
	
	if(!isset($delphoto)) $delphoto = '';
	
	if(!strcmp($delphoto, "Y")) {

		$sql = "select photo from wiz_member where id = '{$wiz_session['id']}'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$upfile_path = WIZHOME_PATH."/data/member";
	 	@unlink($upfile_path."/".$row['photo']);

	}

	if(!isset($delicon)) $delicon =''; 
	if(!strcmp($delicon, "Y")) {

		$sql = "select icon from wiz_member where id = '{$wiz_session['id']}'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$upfile_path = WIZHOME_PATH."/data/member";
	 	@unlink($upfile_path."/".$row['icon']);

	}

	// 사진등록
	if($photo['size'] > 0){

		ImageResize_Upload_check($photo['name']);

		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $photo['name']);
		$len = (strlen($ext) == 3) ? "-3" : "-4";
		$file_ext = strtolower(substr($photo['name'],$len));

		$upfile_path = WIZHOME_PATH."/data/member";
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

		if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}

		$photo_name = $wiz_session['id'].".".$file_ext;

		foreach($pi_array as $k => $value) {
			@unlink($upfile_path."/".$id.".".$value);
		}
		copy($photo['tmp_name'], $upfile_path."/".$photo_name);

		$srcimg = $photo_name;
		$dstimg = $photo_name;
		$photo_width = "120";
		$photo_height = "80";
		img_resize($srcimg, $dstimg, $upfile_path, $photo_width, $photo_height);

		$photo_sql = " , photo = '$photo_name' ";

	}

	// 아이콘등록
	if($icon['size'] > 0){

		ImageResize_Upload_check($icon['name']);

		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $icon['name']);
		$len = (strlen($ext) == 3) ? "-3" : "-4";
		$file_ext = strtolower(substr($icon['name'],$len));

		$upfile_path = WIZHOME_PATH."/data/member";
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

		if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}

		$icon_name = $wiz_session['id']."_icon.".$file_ext;

		foreach($pi_array as $k => $value) {
			@unlink($upfile_path."/".$id."_icon.".$value);
		}
		copy($icon['tmp_name'], $upfile_path."/".$icon_name);

		$srcimg = $icon_name;
		$dstimg = $icon_name;
		$icon_width = $icon_size;
		img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);

		$icon_sql = " , icon = '$icon_name' ";

	}
	
	/*
	작업일시	: 2022-02-24
	작업자명	: 이상민
	작업내용	: 정보수정 시 휴대폰번호 필수입력일 경우 중복확인
	*/
	if($info_use['hphone']=="true"){
		if($hphone != ""){
			$sql = " select count(*) as cnt from wiz_member where hphone = '".$hphone."' and id <> '".$wiz_session['id']."' ";
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
	작업내용	: 정보수정 시 이메일 필수입력일 경우 중복확인
	*/
	if($info_use['email']=="true"){
		if($email != ""){
			$sql = " select count(*) as cnt from wiz_member where email = '".$email."' and id <> '".$wiz_session['id']."' ";
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

			if($row['ftype'] == "address") {

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

				if (!empty($fname[$no]) && is_array($fname[$no])) {
					$fname_count[$no] = count($fname[$no]);
				} else {
					$fname_count[$no] = 0;
				}

				for($ii=0;$ii<$fname_count[$no];$ii++){
					 $fvalue .= $fname[$no][$ii];
					 if($ii<$fname_count[$no]-1) $fvalue .= $split_char;
				}

			} else {

				$fvalue = $fname[$no];

			}

			${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";

		} else {

			$fvalue = "";

			for($ii = 1; $ii <= $row['fnum']; $ii++) {

				// 파일등록
				$upfile		 = $_FILES['upfile'.$ii]['tmp_name'];
				$upfile_size = $_FILES['upfile'.$ii]['size'];
				$upfile_name = $_FILES['upfile'.$ii]['name'];

				if($upfile_size > 0) {

					ImageResize_Upload_check($upfile_name);

					$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $upfile_name);
					$len = (strlen($ext) == 3) ? "-3" : "-4";
					$file_ext = strtolower(substr($upfile_name,$len));

					$upfile_path = WIZHOME_PATH."/data/member";
					if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
					$upfile_name = $wiz_session['id']."_".$no."_".$ii.".".$file_ext;

					exec("rm -f ".$upfile_path."/".$wiz_session['id']."_".$no."_".$ii.".*");
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

			if(!empty($fvalue)) {
				${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";
			}

		}

	}

	if($passwd1 != "") {

		$passwd = md5($passwd1);
		if($passwd != $m_passwd) {
			$passwd_date = ", pw_update = now()";
		}
		$passwd_sql = " , passwd = '$passwd' ";

		$sql_pass = "UPDATE wiz_member SET old_user='n' WHERE id = '{$wiz_session['id']}'";
		query($sql_pass);
	}
	

	$sql_com = "";
	$sql_com .= " nick             = '$nick'                         ";
	$sql_com .= " , resno          = '$resno'                        ";
	$sql_com .= " , email          = '$email'                        ";
	$sql_com .= " , tphone         = '$tphone'                       ";
	$sql_com .= " , hphone         = '$hphone'                       ";
	$sql_com .= " , comtel         = '$comtel'                       ";
	$sql_com .= " , homepage       = '$homepage'                     ";
	$sql_com .= " , post           = '$post'                         ";
	$sql_com .= " , address1       = '$address1'                     ";
	$sql_com .= " , address2       = '$address2'                     ";
	$sql_com .= " , reemail        = '$reemail'                      ";
	$sql_com .= " , resms          = '$resms'                        ";
	$sql_com .= " , birthday       = '$birthday'                     ";
	$sql_com .= " , bgubun         = '$bgubun'                       ";
	$sql_com .= " , marriage       = '$marriage'                     ";
	$sql_com .= " , memorial       = '$memorial'                     ";
	$sql_com .= " , scholarship    = '$scholarship'                  ";
	$sql_com .= " , job            = '$job'                          ";
	$sql_com .= " , income         = '$income'                       ";
	$sql_com .= " , car            = '$car'                          ";
	$sql_com .= " , hobby          = '$hobby'                        ";
	$sql_com .= " , consph         = '$tmpconsph'                    ";
	$sql_com .= " , conprd         = '$tmpconprd'                    ";
	$sql_com .= " , recom          = '$recom'                        ";
	//$sql_com .= " , level          = '$level'                        ";
	$sql_com .= " , intro          = '$intro'                        ";
	$sql_com .= " , memo           = '$memo'                         ";
	$sql_com .= " , mdate          = now()                           ";
	$sql_com .= " {$passwd_sql}		                                 ";
	$sql_com .= " {$photo_sql}		                                 ";
	$sql_com .= " {$icon_sql}		                                 ";
	$sql_com .= " {$addinfo1_sql}		                             ";
	$sql_com .= " {$addinfo2_sql}		                             ";
	$sql_com .= " {$addinfo3_sql}		                             ";
	$sql_com .= " {$addinfo4_sql}		                             ";
	$sql_com .= " {$addinfo5_sql}		                             ";

	$sql = "UPDATE wiz_member SET {$sql_com} WHERE id = '{$wiz_session['id']}' ";
	query($sql);

	if(empty($prev)) $prev = "http://".$_http_host;

	alert("회원정보를 수정하였습니다.",$prev);
}
?>