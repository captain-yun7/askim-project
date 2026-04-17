<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
	if($mode == "insert"){
	
		$resno = $resno."-".$resno2;
		$post = $post1;
		$tphone = $tphone."-".$tphone2."-".$tphone3;
		$hphone = $hphone."-".$hphone2."-".$hphone3;

		if(!empty($permi)) { 
			for($ii=0; $ii<count($permi); $ii++){
				$tmp_permi .= $permi[$ii]."/";
			}
		}
	
		 // 아이콘등록
		if($icon['size'] > 0){
	
			file_check($icon['name']);
	
			$upfile_path = "../../data/member";
			if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
			$icon_name = $id."_icon.".getFileExt($icon['name']);
			
			copy($icon['tmp_name'], $upfile_path."/".$icon_name);
			chmod($upfile_path."/".$icon_name, 0606);
	
			$srcimg = $icon_name;
			$dstimg = $icon_name;
			$icon_width = $icon_size;
			img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);
	
		}

		$passwd = md5($passwd);

		$sql = "insert into wiz_admin(id, passwd, name, lev, icon, resno, email, tphone, hphone, post, address1, address2, part, permi, last, wdate, descript)
				values('$id', '$passwd', '$name', '$lev', '$icon_name', '$resno', '$email', '$tphone', '$hphone', '$post', '$address1', '$address2', '$part', '$tmp_permi', '$last', now(), '$descript')";
		$result = query($sql) or error("이미 등록된 아이디 입니다.");
		
		complete("관리자가 추가되었습니다.","twcenter_list.php?menucode=$menucode");
	
	}else if($mode == "update"){
	
		$resno = $resno."-".$resno2;
		$post = $post1;
		$tphone = $tphone."-".$tphone2."-".$tphone3;
		$hphone = $hphone."-".$hphone2."-".$hphone3;
		if(!empty($permi)) { 
			for($ii=0; $ii<count($permi); $ii++){
				$tmp_permi .= $permi[$ii]."/";
			}
		}
		if(!isset($delicon)) $delicon = '';
		if(!strcmp($delicon, "Y")) {
			
			$sql = "select icon from wiz_admin where id = '$id'";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_arr($result);
			
		 	$upfile_path = "../../data/member";
		 	@unlink($upfile_path."/".$row['icon']);
			
		}
	   // 아이콘등록
		if($icon['size'] > 0){

			$sql = "select icon from wiz_admin where id = '$id'";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_arr($result);
	
			file_check($icon['name']);
	
			$upfile_path = "../../data/member";
			if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
			//$icon_name = $id."_icon.".substr($icon['name'],-3);
			$icon_name = $id."_icon.".getFileExt($icon['name']);

			@unlink($upfile_path."/".$row['icon']);
			copy($icon['tmp_name'], $upfile_path."/".$icon_name);
			chmod($upfile_path."/".$icon_name, 0606);
	
			$srcimg = $icon_name;
			$dstimg = $icon_name;
			$icon_width = $icon_size;
			img_resize($srcimg, $dstimg, $upfile_path, $icon_width, $icon_height);
	
			$icon_sql = " icon = '$icon_name', ";
	
		}

		if($passwd != ""){
			$passwd = md5($passwd);
			$passwd_sql = " passwd = '$passwd', ";
		}

		$sql = "update wiz_admin set
	            $passwd_sql name = '$name', lev='$lev', $icon_sql resno = '$resno', email = '$email', tphone = '$tphone', hphone = '$hphone', post = '$post', address1 = '$address1', address2 = '$address2', part='$part', permi='$tmp_permi', descript = '$descript' where id = '$id'";
		$result = query($sql) or error("sql error");
		
//if($_SERVER['REMOTE_ADDR'] == "118.130.111.142") {echo $sql;exit;}
	
		complete("관리자 정보가 수정되었습니다.","twcenter_input.php?mode=update&id=$id&page=$page&menucode=$menucode");
	
	
	}else if($mode == "delete"){
	
		$sql = "select id from wiz_admin";
		$result = query($sql) or error("sql error");
		$total = sql_fetch_row($result);

		if($total <= 1) error("관리자계정이 하나밖에 없습니다. 삭제할 수 없습니다.");

		$i_sql = "select icon from wiz_admin where id = '$admin_id'";
		$i_res = query($i_sql);
		$i_row = sql_fetch_arr($i_res);
		$icon = $i_row['icon'];

		$sql = "delete from wiz_admin where id='$admin_id'";
		$result = query($sql) or error("sql error");

		$upfile_path = "../../data/member";
		@unlink($upfile_path."/".$icon);
	
		complete("관리자가 삭제되었습니다.","twcenter_list.php?page=$page&menucode=$menucode");
	
	
	}else if($mode == "logdel"){
	
		$sql = "delete from wiz_adminlog where admin_id='$admin_id'";
		$result = query($sql) or error("sql error");

		complete("로그가 삭제되었습니다.","twcenter_input.php?mode=update&twcenter_id=$twcenter_id&menucode=$menucode");
	
	}else if($mode == "unlock"){

		$sql = " update wiz_admin set is_account_lock='N', login_try_time='', login_fail_count=0 WHERE id='".$admin_id."'";
		$result = query($sql) or error("sql error");
		$logfile = fopen(LOG_PATH.date("Ymd")."_admin_login.log", "a+" );
		fwrite($logfile,"************************************************************************************************************************************************\r\n");
		fwrite( $logfile, date("Y-m-d H:i:s",time()). " post_data: \r\n");
		fwrite($logfile,"reset admin_id : ".$admin_id."\r\n");
		fwrite($logfile, "ip : ".$_SERVER["REMOTE_ADDR"]."\r\n");
		fwrite($logfile, "\r\n");
		fwrite($logfile,"************************************************************************************************************************************************\r\n");
		fwrite($logfile, "\r\n");
		fclose($logfile);

		complete("차단 해제되었습니다.","twcenter_list.php?page=$page&menucode=$menucode");

	} else if ($mode == "level_insert") { 

		$permi = implode("/", $_POST['permi']);
		$sql = "insert into wiz_admin_lev set
			name='$name',
			permi = '$permi',
			memo = '$memo' 
		";
		if(query($sql)) {
			complete("관리자 등급이 추가되었습니다", "twcenter_level.php");
		} else {
			error("관리자 등급 추가 중 오류가 발생했습니다");
		}

	} else if ($mode == "level_update") { 

		$permi = implode("/", $_POST['permi']);
		$sql = "update wiz_admin_lev set 
			name='$name',
			permi = '$permi',
			memo = '$memo'
			where idx='$idx'
		";
		if(query($sql)) {
			complete("관리자 등급 설정이 변경되었습니다", "twcenter_level.php");
		} else {
			error("관리자 등급 수정 중 오류가 발생했습니다");
		}

	} else if ($mode == "level_delete") { 
		$sql = "select count(*) as cnt from wiz_admin where lev='".$idx."'";
		$row = sql_fetch($sql);
		if($row['cnt'] > 0) {
			error("현재 해당 등급으로 설정된 관리자가 있습니다.\\n해당 등급으로 설정된 관리자의 등급 변경 또는 삭제 후 등급 삭제 가능합니다.");
		} else {
			$sql = "delete from wiz_admin_lev where idx='$idx'";
			if(query($sql)) {
				complete("관리자 등급 설정이 삭제되었습니다", "twcenter_level.php");
			} else {
				error("관리자 등급 삭제중 오류가 발생했습니다");
			}
		}
	} else {
		error("잘못된 접근입니다.");
	}
	
?>