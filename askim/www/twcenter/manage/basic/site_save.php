<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?

// 기본정보설정
if($mode == "site_info"){

	$upfile_path = "../../data/config";

	// 사업자도장
	if($com_seal['size'] > 0){
		file_check($com_seal['name']);
		copy($com_seal['tmp_name'], $upfile_path."/com_seal.gif");
		chmod($upfile_path."/com_seal.gif", 0606);
	}

	if (isset($delseal) && $delseal === "Y") {

		$ext_array = array("jpg","jpeg","gif","png","bmp");
		foreach($ext_array as $key=>$value) {
			$files = 'com_seal.'.$value;
			$file_path = $upfile_path."/".$files;
			@unlink($file_path);
		}

	}

	$com_post = $com_post1;
	$com_address = $com_address1;
	if($ftp_pw != "") $ftp_pw_sql = " ftp_pw='$ftp_pw', ";

	$sql_com = "";
	$sql_com .= " site_name                 = '{$site_name}'                   ";
	$sql_com .= " , site_url                = '{$site_url}'                    ";
	$sql_com .= " , site_email              = '{$site_email}'                  ";
	$sql_com .= " , site_tel                = '{$site_tel}'                    ";
	$sql_com .= " , site_hand               = '{$site_hand}'                   ";
	$sql_com .= " , ftp_host                = '{$ftp_host}'                    ";
	$sql_com .= " , ftp_id                  = '{$ftp_id}'                      ";
	$sql_com .= $ftp_pw_sql;
	$sql_com .= " , com_num                 = '{$com_num}'                     ";
	$sql_com .= " , com_name                = '{$com_name}'                    ";
	$sql_com .= " , com_owner               = '{$com_owner}'                   ";
	$sql_com .= " , com_post                = '{$com_post}'                    ";
	$sql_com .= " , com_address             = '{$com_address}'                 ";
	$sql_com .= " , com_kind                = '{$com_kind}'                    ";
	$sql_com .= " , com_class               = '{$com_class}'                   ";
	$sql_com .= " , com_tel                 = '{$com_tel}'                     ";
	$sql_com .= " , com_fax                 = '{$com_fax}'                     ";
	$sql_com .= " , browser_title           = '{$browser_title}'               ";
	$sql_com .= " , searchkey               = '{$searchkey}'                   ";
	$sql_com .= " , searchkey_de            = '{$searchkey_de}'                ";
	$sql_com .= " , searchkey_cl            = '{$searchkey_cl}'                ";
	$sql_com .= " , asitem                  = '{$asitem}'                      ";
	$sql_com .= " , bbs_email               = '{$bbs_email}'                   ";

	$sql = "UPDATE wiz_siteinfo SET {$sql_com}";
	query($sql) or error("sql error");

	complete("기본정보 설정이 저장되었습니다.","site_info.php?menucode=$menucode");


// 도메인 정보
}else if($mode == "domain"){

	$type = "domain";

	if($submode == "insert"){

		$sql = "insert into wiz_otherinfo(idx,type,info01,info02,info03,info04,info05,info06,info07,info08,info09,info10)
										values('','$type','$info01','$info02','$info03','$info04','$info05','$info06','$info07','$info08','$info09','$info10')";
		query($sql) or error("sql error");
		echo "<script>alert('등록되었습니다.');self.close();opener.document.location.reload();</script>";

	}else if($submode == "update"){

		$sql = "update wiz_otherinfo set info01='$info01',info02='$info02',info03='$info03',info04='$info04',info05='$info05',info06='$info06',info07='$info07',info08='$info08',info09='$info09',info10='$info10' where idx = '$idx'";
		query($sql) or error("sql error");
		echo "<script>alert('수정되었습니다.');self.close();opener.document.location.reload();</script>";

	}else if($submode == "delete"){

		$sql = "delete from wiz_otherinfo where idx = '$idx'";
		query($sql) or error("sql error");
		alert("삭제되었습니다.","site_info.php?menucode=$menucode");

	}

// 이메일 정보
}else if($mode == "email"){

	$type = "email";

	if($submode == "insert"){

	  $sql = "insert into wiz_otherinfo(idx,type,info01,info02,info03,info04,info05,info06,info07,info08,info09,info10)
											values('','$type','$info01','$info02','$info03','$info04','$info05','$info06','$info07','$info08','$info09','$info10')";
	  query($sql) or error("sql error");
	  echo "<script>alert('등록되었습니다.');self.close();opener.document.location.reload();</script>";

	}else if($submode == "update"){

	  $sql = "update wiz_otherinfo set info01='$info01',info02='$info02',info03='$info03',info04='$info04',info05='$info05',info06='$info06',info07='$info07',info08='$info08',info09='$info09',info10='$info10' where idx = '$idx'";
	  query($sql) or error("sql error");
	  echo "<script>alert('수정되었습니다.');self.close();opener.document.location.reload();</script>";

	}else if($submode == "delete"){

	  $sql = "delete from wiz_otherinfo where idx = '$idx'";
	  query($sql) or error("sql error");
	  alert("삭제되었습니다.","site_info.php");

	}

}

?>