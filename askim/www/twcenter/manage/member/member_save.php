<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/mem_info.php";

if($mode !='update' && !empty($id)){ /* 2021-05-21 아이디 중복확인 한번더 체크*/
	$sql1 = "select id from wiz_member where id='$id'";
	$result2 = query($sql1);
	$total = sql_fetch_row($result2);

	$sql2 = "select id from wiz_admin where id = '$id'";
	$result2 = query($sql2);
	$total2 = sql_fetch_row($result2);

	$sql3 = "select designer_id from wiz_siteinfo where designer_id  = '$id' or anywiz_id = '".md5($id)."'";
	$result3 = query($sql3);
	$total3 = sql_fetch_row($result3);

	if($total > 0){
		error("'".$id."' 는 이미 사용중인 아이디 입니다.아이디를 확인해주세요.");
	}else if($total2+$total3 >0){
		error("'".$id."' 는 사용할 수 없는 아이디 입니다.");
	}
}

if($mode !='update' && !empty($nick)){ /* 2021-05-21 닉네임 중복확인 한번더 체크*/
	$nsql = "select nick from wiz_member where nick='$nick'";
	$nresult = query($nsql) or error("sql error");
	$ntotal = sql_fetch_row($nresult);

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
	$birthday = $_POST['birthday'];
	$birthday_b = ($birthday[1]) ? str_pad($birthday[1], 2, '0', STR_PAD_LEFT) : "";
	$birthday_c = ($birthday[2]) ? str_pad($birthday[2], 2, '0', STR_PAD_LEFT) : "";
	$birthday = $birthday[0]."-".$birthday_b."-".$birthday_c;
	$birthday = (strpos($birthday, '--') !== false) ? '' : $birthday;
} else {
	$birthday = "";
}
if(isset($_POST['memorial']) && $_POST['memorial']) {
	$memorial = $_POST['memorial'];
	$memorial_b = ($memorial[1]) ? str_pad($memorial[1], 2, '0', STR_PAD_LEFT) : "";
	$memorial_c = ($memorial[2]) ? str_pad($memorial[2], 2, '0', STR_PAD_LEFT) : "";
	$memorial = $memorial[0]."-".$memorial_b."-".$memorial_c;
	$memorial = (strpos($memorial, '--') !== false) ? '' : $memorial;
} else {
	$memorial = "";
}

$tmp_param = array();
if(isset($page)      && $page)      $tmp_param[] = "page=".$page;
if(isset($slevel)    && $slevel)    $tmp_param[] = "slevel=".$slevel;
if(isset($searchopt) && $searchopt) $tmp_param[] = "searchopt=".$searchopt;

$param   = ($tmp_param) ? implode("&", $tmp_param) : "";
$param   = $param."&".$menucodeParam;

$sql = "SELECT * FROM wiz_formfield WHERE fidx = 'addinfo' ORDER BY fprior ASC, idx ASC";
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

		} else if($row['ftype'] != "radio") {

			$split_char = "|";
			if($row['ftype'] == "pdate") $split_char = "~";
			if(!empty($fname)) {
				for ($ii = 0; $ii < count((array)$fname[$no]); $ii++) {
					$fvalue .= $fname[$no][$ii];
					if($ii<count($fname[$no])-1) $fvalue .= $split_char;
				}
			}
		} else {

			$fvalue = $fname[$no];

		}

		if($mode == "insert") {
			${"addinfo".$no} = $fvalue;
		} else if($mode == "update") {
			${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";
		}

	} else {

		$fvalue = "";

		for($ii = 1; $ii <= $row['fnum']; $ii++) {

			// 파일등록
			$upfile		 = $_FILES['upfile'.$ii]['tmp_name'];
			$upfile_size = $_FILES['upfile'.$ii]['size'];
			$upfile_name = $_FILES['upfile'.$ii]['name'];

			if($upfile_size > 0){

				ImageResize_Upload_check($upfile_name);

//				$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $upfile_name);
//				$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//				$file_ext = strtolower(substr($upfile_name,$len));

				$file_ext = getFileExt($upfile_name);

				$upfile_path = "../../data/member";
				if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

				if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
					error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
				}

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

		if($mode == "insert") {
			${"addinfo".$no} = $fvalue;
		} else if($mode == "update") {
			if(!empty($fvalue)) {
				${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";
			}
		}
		//if(!empty($fvalue)) {
		//	${"addinfo".$no."_sql"} = " , addinfo".$no." = '".$fvalue."' ";
		//}

	}

}

// 회원등록
if($mode == "insert"){

	$resno 		= $resno1."-".$resno2;
	$post 		= $post;

	if(!empty($consph)) {
		for($ii=0; $ii<count($consph); $ii++){ $tmpconsph .= $consph[$ii].","; }
	}
	if(!empty($conprd)) {
		for($ii=0; $ii<count($conprd); $ii++){ $tmpconprd .= $conprd[$ii].","; }
	}
	// 사진등록
	if($photo['size'] > 0){

		ImageResize_Upload_check($photo['name']);

//		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $photo['name']);
//		$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//		$file_ext = strtolower(substr($photo['name'],$len));

		$file_ext = getFileExt($photo['name']);

		$upfile_path = "../../data/member";
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
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

//		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $icon['name']);
//		$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//		$file_ext = strtolower(substr($icon['name'],$len));

		$file_ext = getFileExt($icon['name']);

		$upfile_path = "../../data/member";
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
		$icon_name = $id."_icon.".$file_ext;
		copy($icon['tmp_name'], $upfile_path."/".$icon_name);
		chmod($upfile_path."/".$icon_name, 0606);

		$srcimg = $icon_name;
		$dstimg = $icon_name;
		$icon_width = $icon_size;
		img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);

	}

	$passwd = md5($passwd);
	$conn_ip = $_SERVER['REMOTE_ADDR'];

	$sql_com = "";
	$sql_com .= " id                 = '$id'		         ";
	$sql_com .= " , passwd           = '$passwd'	         ";
	$sql_com .= " , name             = '$name'		         ";
	$sql_com .= " , photo            = '$photo_name'         ";
	$sql_com .= " , icon             = '$icon_name'	         ";
	$sql_com .= " , nick             = '$nick'		         ";
	$sql_com .= " , resno            = '$resno'		         ";
	$sql_com .= " , email            = '$email'		         ";
	$sql_com .= " , tphone           = '$tphone'	         ";
	$sql_com .= " , hphone           = '$hphone'	         ";
	$sql_com .= " , comtel           = '$comtel'	         ";
	$sql_com .= " , homepage         = '$homepage'	         ";
	$sql_com .= " , post             = '$post'		         ";
	$sql_com .= " , address1         = '$address1'	         ";
	$sql_com .= " , address2         = '$address2'	         ";
	$sql_com .= " , reemail          = '$reemail'	         ";
	$sql_com .= " , resms            = '$resms'		         ";
	$sql_com .= " , birthday         = '$birthday'	         ";
	$sql_com .= " , bgubun           = '$bgubun'	         ";
	$sql_com .= " , marriage         = '$marriage'	         ";
	$sql_com .= " , memorial         = '$memorial'	         ";
	$sql_com .= " , scholarship      = '$scholarship'        ";
	$sql_com .= " , job              = '$job'		         ";
	$sql_com .= " , income           = '$income'	         ";
	$sql_com .= " , car              = '$car'		         ";
	$sql_com .= " , hobby            = '$hobby'		         ";
	$sql_com .= " , consph           = '$tmpconsph'	         ";
	$sql_com .= " , conprd           = '$tmpconprd'	         ";
	$sql_com .= " , level            = '$level'		         ";
	$sql_com .= " , recom            = '$recom'		         ";
	$sql_com .= " , visit            = '1'		             ";
	$sql_com .= " , visit_time       = now()                 ";
	$sql_com .= " , intro            = '$intro'		         ";
	$sql_com .= " , memo             = '$memo'		         ";
	$sql_com .= " , addinfo1         = '$addinfo1'	         ";
	$sql_com .= " , addinfo2         = '$addinfo2'	         ";
	$sql_com .= " , addinfo3         = '$addinfo3'	         ";
	$sql_com .= " , addinfo4         = '$addinfo4'	         ";
	$sql_com .= " , addinfo5         = '$addinfo5'	         ";
	$sql_com .= " , wdate            = now()		         ";
	$sql_com .= " , ip               = '$conn_ip'            ";
	$sql_com .= " , pw_update        = now()		         ";

	$sql = "INSERT INTO wiz_member SET {$sql_com} ";
	query($sql);

	complete("회원을 등록하였습니다.","member_list.php?$param");

// 회원정보 수정
} else if($mode == "update") {

	$m_qry = sql_fetch("select passwd from wiz_member where idx='$idx' ");
	$m_passwd = $m_qry['passwd'];

	$resno = $resno1."-".$resno2;
	$post   = $post;

	if(!empty($consph)) {
		for($ii=0; $ii<count($consph); $ii++){
			$tmpconsph .= $consph[$ii].",";
		}
	}
	if(!empty($conprd)) {
		for($ii=0; $ii<count($conprd); $ii++){
			$tmpconprd .= $conprd[$ii].",";
		}
	}
	
	if(!isset($delphoto)) $delphoto = '';
	if(!strcmp($delphoto, "Y")) {

		$sql = "select photo from wiz_member where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$upfile_path = "../../data/member";
		@unlink($upfile_path."/".$row['photo']);

	}
	if(!isset($delicon)) $delicon = '';
	if(!strcmp($delicon, "Y")) {

		$sql = "select icon from wiz_member where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$upfile_path = "../../data/member";
		@unlink($upfile_path."/".$row['icon']);

	}

	// 사진등록
	if($photo['size'] > 0){

		ImageResize_Upload_check($photo['name']);

//		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $photo['name']);
//		$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//		$file_ext = strtolower(substr($photo['name'],$len));

		$file_ext = getFileExt($photo['name']);

		$upfile_path = "../../data/member";
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
		$photo_name = $id.".".$file_ext;

		foreach($pi_array as $k => $value) {
			@unlink($upfile_path."/".$id.".".$value);
		}

		copy($photo['tmp_name'], $upfile_path."/".$photo_name);
		chmod($upfile_path."/".$photo_name, 0606);

		$srcimg = $photo_name;
		$dstimg = $photo_name;
		$photo_width = "120";
		img_resize($srcimg, $dstimg, $upfile_path, $photo_width, $photo_height);

		$photo_sql = " , photo = '$photo_name' ";

	}

	// 아이콘등록
	if($icon['size'] > 0){

		ImageResize_Upload_check($icon['name']);

//		$ext = preg_replace('#^.*\.([^.]+)$#D', '$1', $icon['name']);
//		$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//		$file_ext = strtolower(substr($icon['name'],$len));
		$file_ext = getFileExt($icon['name']);

		$upfile_path = "../../data/member";
		
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
		$icon_name = $id."_icon.".$file_ext;

		foreach($pi_array as $k => $value) {
			@unlink($upfile_path."/".$id."_icon.".$value);
		}

		copy($icon['tmp_name'], $upfile_path."/".$icon_name);
		chmod($upfile_path."/".$icon_name, 0606);

		$srcimg = $icon_name;
		$dstimg = $icon_name;
		$icon_width = $icon_size;
		img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);

		$icon_sql = " , icon = '$icon_name' ";

	}

	if($passwd != "") {
		$passwd = md5($passwd);
		if($passwd != $m_passwd) {
			$passwd_date = ", pw_update = now()";
		}
		$passwd_sql = " , passwd = '$passwd' ";
	}

	$sql_com = "";
	$sql_com .= " name             = '$name'                         ";
	$sql_com .= " , nick           = '$nick'                         ";
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
	$sql_com .= " , level          = '$level'                        ";
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

	$thisTBL =  ($inputType == 'dormancy') ? "wiz_member_dormancy" : "wiz_member";

	$sql = "UPDATE {$thisTBL} SET {$sql_com} WHERE idx = '$idx' ";
	query($sql);
	if(!empty($re_post) && is_array($re_post)) {
		foreach($re_post as $key=>$value) {

			if(isset($_POST['re_tphone'][$key]) && $_POST['re_tphone'][$key]) $re_tphone[$key] = implode("-", $_POST['re_tphone'][$key]);
			if(isset($_POST['re_hphone'][$key]) && $_POST['re_hphone'][$key]) $re_hphone[$key] = implode("-", $_POST['re_hphone'][$key]);

			if($_POST['bucode'][$key]) $bucode_sql = ", bucode = '{$_POST['bucode'][$key]}' ";
			else					   $bucode_sql = "";

			$sql_com = "";
			$sql_com .= " re_name         = '{$_POST['re_name'][$key]}'        ";
			$sql_com .= " , re_post       = '{$_POST['re_post'][$key]}'        ";
			$sql_com .= " , re_addr       = '{$_POST['re_addr'][$key]}'        ";
			$sql_com .= " , re_addr2      = '{$_POST['re_addr2'][$key]}'       ";
			$sql_com .= " , re_tphone     = '{$re_tphone[$key]}'               ";
			$sql_com .= " , re_hphone     = '{$re_hphone[$key]}'               ";
			$sql_com .= " $bucode_sql                                          ";

			$sql = "UPDATE wiz_delivery_set SET {$sql_com} WHERE idx='{$didx[$key]}' ";
			query($sql) or error("sql error");

		}
	}
	if($prdparam == ""){
		$go_url = ($inputType == 'dormancy') ? "member_dormant_account_input" : "member_input";
		complete("회원정보를 수정하였습니다.",$go_url.".php?mode=$mode&idx=$idx&$param");
	} else {
		echo "<script>alert('회원정보를 수정하였습니다.'); document.location='/twcenter/manage/product/order_list.php?".urldecode($prdparam)."'</script>";
	}


// 회원 삭제
} else if($mode == "deluser") {

	$i=0;
	$upfile_path = WIZHOME_PATH."/data/member";
	$array_seluser = explode("|",$seluser);

	if($delt == 'd') {
		$thisTBL = "wiz_member_dormancy";
		$gourl   = "member_dormant_account_list.php";
	} else {
		$thisTBL = "wiz_member";
		$gourl   = "member_list.php";
	}

	while($array_seluser[$i]){

		$id = $array_seluser[$i];

		// 미니홈피 기능 사용 시 미니홈피 관련 데이터 삭제
		if(!strcmp($site_info['mini_use'], "Y")) {

			@include "../../mini/inc/mini_info.php";

			$sql = "select photo,miniurl from wiz_mini_info where memid = '$id'";
			$result = query($sql);
			$row = sql_fetch_arr($result);

			$miniurl_path = $DOCUMENT_ROOT."/".$mini_dir."/".$row['miniurl'];

			if(!empty($row['miniurl'])) rm_dir($miniurl_path);

			if(!empty($row['photo'])) @unlink(WIZHOME_PATH."/data/mini/".$row['photo']);

			rm_dir(WIZHOME_PATH."/data/minibbs/bbs/".$id);
			rm_dir(WIZHOME_PATH."/data/minibbs/data/".$id);
			rm_dir(WIZHOME_PATH."/data/minibbs/movie/".$id);
			rm_dir(WIZHOME_PATH."/data/minibbs/photo/".$id);
			rm_dir(WIZHOME_PATH."/data/minibbs/visit/".$id);
			rm_dir(WIZHOME_PATH."/data/music/".$id);

			$sql = "delete from wiz_mini_bbs where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_data where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_photo where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_movie where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_guest where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_bbscat where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_comment where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_conrefer where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_contime where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_friend  where myid = '$id' or frdid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_info  where memid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_music  where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_profile  where miniid = '$id'";
			query($sql);
		}

		// 추가항목이 파일인 경우 업로드 파일 삭제
		$sql = "select addinfo1, addinfo2, addinfo3, addinfo4, addinfo5 from {$thisTBL} where id = '$id'";
		$result = query($sql);
		$my_info = sql_fetch_arr($result);

		$sql = "select * from wiz_formfield where fidx = 'addinfo' and ftype = 'file' order by fprior asc, idx asc";
		$result = query($sql);
		while($row = sql_fetch_arr($result)){

			$no = $row['fprior'];

			$tmp_array = explode("|", $my_info["addinfo".$no]);
			if(!empty($tmp_array)) {
				for($ii = 0; $ii < count($tmp_array); $ii++) {
					@unlink($upfile_path."/".$tmp_array[$ii]);
				}
			}

		}

		// 회원테이블에서 삭제
		$sql = "delete from {$thisTBL} where id = '$id'";
		$result = query($sql);

		@unlink($upfile_path."/".$id.".gif");
		@unlink($upfile_path."/".$id.".jpg");
		@unlink($upfile_path."/".$id."_icon.gif");
		@unlink($upfile_path."/".$id."_icon.jpg");

		// 회원포인트 삭제
		$sql = "delete from wiz_point where memid = '$id'";
		@query($sql);

		// 찜리스트 삭제
		$sql = "delete from wiz_wishlist where memid = '$id'";
		@query($sql);

		// 적립금 삭제
		$sql = "delete from wiz_reserve where memid = '$id'";
		@query($sql);

		// 쿠폰 삭제 2020-05-21 정나혜 추가
		$sql = "delete from wiz_mycoupon where memid = '$id'";
		@query($sql);

		// 주문내역 삭제(주문자 아이디를 [out] 으로 처리)
		$sql = "update wiz_order set send_id = '".$id."[out]' where send_id = '$id'";
		@query($sql);

		$sql = "DELETE FROM wiz_delivery_set  WHERE de_id = '$id'";
		query($sql) or error("sql error");

		$i++;

	}

	complete("회원을 삭제하였습니다.",$gourl."?$param");


// 탈퇴회원 삭제
} else if($mode == "memoutdel") {

	$sql = "delete from wiz_bbs where code = '[memout]' and idx='$idx'";
	query($sql);

	complete("삭제되었습니다.","out_list.php?page=$page&menucode=$menucode");

// 포인트 저장
} else if($mode == "point") {

	$point = $point_gubun.$point;

	$sql_com = "";
	$sql_com .= " bidx               = '$bidx'		         ";
	$sql_com .= " , cidx             = '$cidx'		         ";
	$sql_com .= " , midx             = '$midx'		         ";
	$sql_com .= " , ptype            = '$ptype'	             ";
	$sql_com .= " , mode             = '$mode'		         ";
	$sql_com .= " , memid            = '$memid'		         ";
	$sql_com .= " , point            = '$point'		         ";
	$sql_com .= " , memo             = '$memo'		         ";
	$sql_com .= " , wdate            = now()		         ";

	$sql = "INSERT INTO wiz_point SET {$sql_com} ";
	query($sql);

	complete("포인트가 적립되었습니다.", "member_point.php?id=$memid&name=$name&menucode=$menucode");

// 포인트 삭제
} else if($mode == "delpoint") {

	$sql = "delete from wiz_point where idx = '$idx'";
	query($sql) or error("sql error");

  complete("포인트가 삭제되었습니다.", "member_point.php?id=$memid&name=$name&menucode=$menucode");

// 가입약관 및 개인정보 보호정책
} else if($mode == "config") {

	$agreement = $_POST["agreement"];
	$safeinfo = $_POST["safeinfo"];
	//if(!get_magic_quotes_gpc()) $agreement = addslashes($agreement);
	//if(!get_magic_quotes_gpc()) $safeinfo = addslashes($safeinfo);

	$sql = "update wiz_meminfo set agreement='$agreement', safeinfo='$safeinfo'";
	$result = query($sql) or error("sql error");

	complete("수정되었습니다.","member_config.php?menucode=$menucode");

// 추가항목 File 인 경우 파일 삭제
} else if(!strcmp($mode, "addfile_del")) {

	$no 		= $_GET["no"];
	$upfile = $_GET["upfile"];

	$sql = "select addinfo".$no." from wiz_member where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	$upfile_path = "../../data/member";
	@unlink($upfile_path."/".$upfile);

	$tmp_value = str_replace("$upfile", "", $row["addinfo".$no]);

	$sql = "update wiz_member set addinfo".$no." = '".$tmp_value."' where idx='$idx'";
	query($sql) or error("sql error");

  complete("회원정보를 수정하였습니다.","member_input.php?mode=update&idx=$idx&$param");

// 각 회원별 적립금 적용
}else if($mode == "reserve"){

	$memid      = $_POST['memid'];
	$reservemsg = $_POST['reservemsg'];
	$reserve    = $_POST['reserve_gubun'].$_POST['reserve'];

	$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$memid', '$reservemsg', '$reserve', '$orderid', now())";
	$result = query($sql) or error("sql error");

	complete("적립금을 적용하였습니다.","member_reserve.php?id=$memid&name=$name&menucode=$menucode");

// 각 회원별 적립금 삭제
}else if($mode == "delreserve"){

	$sql = "delete from wiz_reserve where idx = '$idx'";
	$result = query($sql) or error("sql error");

	complete("해당 적립내역을 삭제하였습니다.","member_reserve.php?id=$memid&name=$name&menucode=$menucode");

// 휴면해제
} else if($mode == "daccountChg") {

	InactiveMemberChangeTbl(trim($idx), 'a');
	complete("회원목록으로 전환되었습니다..","member_list.php?menucode=$menucode");

} else if($mode == "allchange") {

	InactiveMemberChangeTbl(trim($seluser), 'm');
	complete("선택회원을 휴면해제하였습니다.","member_dormant_account_list.php?$param");

} else if($mode == "dormancyChg") {

	MemberInactiveChangeTbl(trim($seluser));
	complete("선택회원을 휴면처리하였습니다.","member_list.php?$param");

} else if($mode == "unlock"){
	if(sizeof($uid) == 0){
		echo "차단해제할 회원을 하나이상 선택하시기 바랍니다.";
	} else {
		for($i=0;$i<sizeof($uid);$i++){
			$sql = " UPDATE wiz_member SET is_account_lock='N', login_try_time='', login_fail_count=0 WHERE id='".$uid[$i]."'";
			$result = query($sql);
		}
		echo "차단해제되었습니다.";
	}
}
?>