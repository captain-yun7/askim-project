<?/*
작업자명	: 김나연
작업일시	: 2021-10-28
작업내용	: 파일분할전송 save
*/

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) {	
	$oldmask = umask(0);
	mkdir($upfile_path, DIR_PERM, true);
	umask($oldmask);
}

if($mode == "insert" || $mode == "reply") {
	if(!empty($_POST['upfiles']) && is_array($_POST['upfiles'])) {
		//echo "<xmp>";
	//	print_r($upfiles);
		$fileList = array();
		$fno=1;
		$mno = 1;
		foreach($_POST['upfiles'] as $v) {
			list($filetype, $filename, $orgfile) = explode("?", $v);
			if($filetype == "upfile") {
				$no = $fno;
				$fno++;
			} else if ($filetype == "movie") {
				$no = $mno;
				$mno++;
			}
			$fileList[$filetype.$no]['filename'] = $filename;
			$fileList[$filetype.$no]['orgfile'] = $orgfile;
		}

		foreach($fileList as $fname =>$fval) {
			${$fname."_name"} = $fval['orgfile'];
			$ext = getFileExt(${$fname."_name"});
			${$fname."_tmp"} = $upfile_idx."_".$ii.".".$ext;		//$fval['filename'].".".$ext;
			fromBase64toBinary($fval['filename'], $upfile_path."/".${$fname."_tmp"});
		}

		$upfile_sql_new = "";
		for($i=1; $i<=12; $i++) {
			$upfile_sql_new .= ", upfile".$i."='".${"upfile".$i."_tmp"}."', upfile".$i."_name='".${"upfile".$i."_name"}."'
			";
		}
		// 동영상
		if($movie1_tmp) {
			$movie1_sql = " , movie1='$movie1_tmp' ";
		}
	}
//	echo "</xmp>";
//	echo $upfile_sql_new;
//	exit;

} else if ($mode == "modify") {
	$fno = 1;
	$mno = 1;
///	echo "<xmp>";
	$sql= "select * from wiz_bbs where idx='$idx'";
	$bbs_row = sql_fetch($sql);
	if($_POST['delfiles']) {
		$delfiles = explode("|", $_POST['delfiles']);
		//echo "\ndelfiles >> ".print_r($delfiles,1);
		foreach($delfiles as $no) {
			if($no) {
				$delfile = $upfile_path."/".$bbs_row['upfile'.$no];
				@unlink($delfile);
				//echo $delfile." << del".PHP_EOL;
			}
		}
	}

	//echo "\noldfile >> ".print_r($oldfiles,1);
	if($_POST['oldfiles']) {
		foreach($_POST['oldfiles'] as $oldfile) {
			if($oldfile) {
				$filename = explode("?", $oldfile);
				${"upfile".$fno."_tmp"} = $filename[0];
				${"upfile".$fno."_name"} = $filename[1];
				$fno++;
			}
		}
	}

	if($_POST['upfiles']) {
		foreach($_POST['upfiles'] as $v) {
			list($filetype, $filename, $orgfile) = explode("?", $v);
			if($filetype == "upfile") {
				$no = $fno;
				$fno++;
			} else if ($filetype == "movie") {
				$no = $mno;
				$mno++;
			}
			$fileList[$filetype.$no]['filename'] = $filename;
			$fileList[$filetype.$no]['orgfile'] = $orgfile;
		}

		foreach($fileList as $fname =>$fval) {
			${$fname."_name"} = $fval['orgfile'];
			$ext = getFileExt(${$fname."_name"});
			${$fname."_tmp"} = $upfile_idx."_".$ii.".".$ext;	//$fval['filename'].".".$ext;
			fromBase64toBinary($fval['filename'], $upfile_path."/".${$fname."_tmp"});
		}

		$upfile_sql_new = "";
		for($i=1; $i<=12; $i++) {
			$upfile_sql_new .= ", upfile".$i."='".${"upfile".$i."_tmp"}."', upfile".$i."_name='".${"upfile".$i."_name"}."'
			";
		}
	}

	// 동영상
	if($movie1_tmp) {
		$movie1_sql = " , movie1='$movie1_tmp' ";
	}
	/*
	print_r($fileList['movie1']);
	if($use_vimeo) {
		echo "use vimeo";
	} else {
		$movie1_sql = " , movie1='$movie1_tmp' ";
	}
	*/
//	echo $upfile_sql_new;
//	echo $movie1_sql;
//	echo "</xmp>";
//	exit;

}


?>