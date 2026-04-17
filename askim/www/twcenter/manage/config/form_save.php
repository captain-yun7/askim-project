<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/admin_check.php"; ?>
<?php
$upfile_path = "../../data/form/title";						// 업로드파일 위치

if($mode == "update"){

	$sql_com = "";
	$sql_com .= " code                 = '$code'                      ";
	$sql_com .= " , title              = '$title'                     ";
	$sql_com .= " , skin               = '$skin'                      ";
	$sql_com .= " , rece_sms           = '$rece_sms'                  ";
	$sql_com .= " , rece_email         = '$rece_email'                ";
	$sql_com .= " , rece_bbs           = '$rece_bbs'                  ";
	$sql_com .= " , sms_list           = '$sms_list'                  ";
	$sql_com .= " , email_list         = '$email_list'                ";
	$sql_com .= " , agree_use          = '$agree_use'                 ";
	$sql_com .= " , agree_text         = '$agree_text'                ";
	$sql_com .= " , browser_title      = '$browser_title'             ";
	$sql_com .= " , searchkey_de       = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl       = '$searchkey_cl'              ";
	$sql_com .= " , searchkey          = '$searchkey'                 ";

	$sql = "UPDATE wiz_forminfo SET {$sql_com} WHERE idx = '$idx' ";
	query($sql);

	complete("폼메일 정보를 수정하였습니다.","form_input.php?mode=update&idx=$idx");


}else if($mode == "delete"){

	$sql = "delete from wiz_forminfo where idx = '$idx'";
	query($sql);

	$sql = "select fimg from wiz_formfield where fidx = '$idx'";
	$result = query($sql);
	while ($row = sql_fetch_arr($result)) {
		@unlink($upfile_path."/".$row['fimg']);
	}

	$sql = "delete from wiz_formfield where fidx = '$idx'";
	query($sql);

	complete("해당 폼메일을 삭제되었습니다.","form_config.php");


}else if($mode == "field"){

	// 업로드 디렉토리 생성
	if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

	// 큰 따옴표(") -> 작은 따옴표(')
	$fname = str_replace("\"", "\'", (string)$fname);

	if($sub_mode == "insert"){

		$sql = "select max(fprior) as max_prior from wiz_formfield where fidx = '$fidx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$fprior = $row['max_prior'] + 1;

		if (is_array($flist) && count($flist) > 0) {
		    for ($ii = 0; $ii < count($flist); $ii++) {
		        $tmp_flist .= $flist[$ii];
		        if ($ii < count($flist) - 1) $tmp_flist .= "|";
		    }
		}
	
		if(!strcmp($ftype, "spamcheck")) $fessen = "Y";

		$sql_com = "";
		$sql_com .= " fidx					= '$fidx'					  ";
		$sql_com .= " , fprior              = '$fprior'                   ";
		$sql_com .= " , fname               = '$fname'                    ";
		$sql_com .= " , ftype               = '$ftype'                    ";
		$sql_com .= " , fessen              = '$fessen'                   ";
		$sql_com .= " , fsize               = '$fsize'                    ";
		$sql_com .= " , fnum                = '$fnum'                     ";
		$sql_com .= " , fimg                = '$fimg_tmp'                 ";
		$sql_com .= " , flist               = '$tmp_flist'                ";

		$sql = "INSERT INTO wiz_formfield SET {$sql_com} ";
		query($sql);

		$idx = mysqli_insert_id($connect);

		if(is_uploaded_file($_FILES['fimg']['tmp_name'])) {

			$fimg['size']     = $_FILES['fimg']['size'];
			$fimg['name']     = $_FILES['fimg']['name'];
			$fimg['tmp_name'] = $_FILES['fimg']['tmp_name'];

			if($fimg['size'] > 0) {

				ImageResize_Upload_check($fimg['name']);
				$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $fimg['name']);
				$len = (strlen($ext) == 3) ? "-3" : "-4";
				$f_ext = strtolower(substr($fimg['name'],$len));

				$fimg_tmp = $fidx."_form_".$idx.".".$f_ext;
				copy($fimg['tmp_name'], $upfile_path."/".$fimg_tmp);
				chmod($upfile_path."/".$fimg_tmp, 0606);

				$sql = "update wiz_formfield set fimg = '$fimg_tmp' where idx = '$idx'";
				query($sql);

			}

		}

		if(!strcmp($continue, "Y")) $idx_param = "&fidx=$fidx";
		else $idx_param = "&idx=$idx";

		echo "<script>alert('필드를 추가하였습니다.'); document.location='form_field_input.php?code=$code".$idx_param."'; window.opener.document.location.reload(); </script>";

	} else if($sub_mode == "update") {

		for($ii = 0; $ii < count($flist); $ii++) {
			$tmp_flist .= $flist[$ii];
			if($ii < count($flist) - 1) $tmp_flist .= "|";
		}

		if(is_uploaded_file($_FILES['fimg']['tmp_name'])) {

			$fimg['size']     = $_FILES['fimg']['size'];
			$fimg['name']     = $_FILES['fimg']['name'];
			$fimg['tmp_name'] = $_FILES['fimg']['tmp_name'];

			if($fimg['size'] > 0) {

				$sql = "select fidx, fimg from wiz_formfield where idx = '$idx'";
				$result = query($sql) or error("sql_error");
				$row = sql_fetch_arr($result);

				ImageResize_Upload_check($fimg['name']);

				if($fimg['size'] > 0) {

					ImageResize_Upload_check($fimg['name']);
					$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $fimg['name']);
					$len = (strlen($ext) == 3) ? "-3" : "-4";
					$f_ext = strtolower(substr($fimg['name'],$len));

					$fimg_tmp = $row['fidx']."_form_".$idx.".".$f_ext;
					copy($fimg['tmp_name'], $upfile_path."/".$fimg_tmp);
					chmod($upfile_path."/".$fimg_tmp, 0606);

					if($row['fimg'] != "" && strcmp($row['fimg'], $fimg_tmp)){
						@unlink($upfile_path."/".$row['fimg']);
					}
					$fimg_sql = " , fimg='$fimg_tmp' ";
				}

			}

		}

		if(!strcmp($ftype, "spamcheck")) $fessen = "Y";

		$sql_com = "";
		$sql_com .= " fname                 = '$fname'                    ";
		$sql_com .= " , ftype               = '$ftype'                    ";
		$sql_com .= " , fessen              = '$fessen'                   ";
		$sql_com .= " , fsize               = '$fsize'                    ";
		$sql_com .= " , fnum                = '$fnum'                     ";
		$sql_com .= " , flist               = '$tmp_flist'                ";
		$sql_com .= " $fimg_sql                                           ";

		$sql = "UPDATE wiz_formfield SET {$sql_com} WHERE idx = '$idx' ";
		query($sql);

		echo "<script>alert('필드를 수정하였습니다.'); document.location='form_field_input.php?mode=update&idx=$idx&fidx=$fidx&code=$code'; window.opener.document.location.reload(); </script>";

	} else if($sub_mode == "delete") {

		$sql = "select fimg from wiz_formfield where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row['fimg'] != ""){
			@unlink($upfile_path."/".$row['fimg']);
		}

		$sql = "delete from wiz_formfield where idx = '$idx'";
		$result = query($sql);

		complete("필드를 삭제하였습니다.","form_field.php?mode=update&fmode=$fmode&fidx=$fidx&code=$code");

	}else if($sub_mode == "delimg") {

		$sql = "select fimg from wiz_formfield where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row['fimg'] != ""){
			@unlink($upfile_path."/".$row['fimg']);

			$sql = "update wiz_formfield set fimg = '' where idx = '$idx'";
			query($sql);
		}

		complete("항목 이미지를 삭제하였습니다.","form_field_input.php?mode=update&idx=$idx&fidx=$fidx&code=$code");

	}

// 진열순서
}else if($mode == "prior"){

	$sql = "select idx, fprior from wiz_formfield where fidx = '$fidx' ";

	// 1단계위로
	if($posi == "up"){

		$sql .= " and fprior <= '$prior' and idx != '$idx' order by fprior desc limit 1";
		$result = query($sql);

		if($row = sql_fetch_obj($result)){

			$sql = "update wiz_formfield set fprior = '$row->fprior' where idx = '$idx'";
			query($sql);

			$sql = "update wiz_formfield set fprior = '$prior' where idx = '$row->idx'";
			query($sql);

		}

	// 1단계아래로
	}else if($posi == "down"){

		$sql .= " and fprior >= '$prior' and idx != '$idx' order by fprior asc  limit 1";
		$result = query($sql);

		if($row = sql_fetch_obj($result)){

			$sql = "update wiz_formfield set fprior = '$row->fprior' where idx = '$idx'";
			query($sql);

			$sql = "update wiz_formfield set fprior = '$prior' where idx = '$row->idx'";
			query($sql);

		}

	}

	complete("진열순서를 변경하였습니다.","form_field.php?fmode=$fmode&fidx=$fidx&code=$code");

}
?>