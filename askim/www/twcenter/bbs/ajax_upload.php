<?
	$updir = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/temp/";

	$mode = $_POST['mode'];

	if($mode == "upload") {
		$sessUpload = $_POST['sessUpload'];
		$filename = $_POST['filename'];
		$filelength = $_POST['filelength'];
		$data = str_replace(" ", "+", urldecode($_POST['data']));
		$sno = $_POST['sno'];

		if(strlen($data) != $filelength) {			//파일전송길이 체크
			echo "error|".strlen($data)."|".$filelength;
			exit;
		}
		if($data) {
			
			if($sno == 1) {			//최초업로드 시 db기록 (업로드 중단 파일 삭제를 위해)
				include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

				// 업로드 디렉토리 생성
				if(!is_dir($updir)) {	
					$oldmask = umask(0);
					mkdir($updir, DIR_PERM, true);
					umask($oldmask);
				}

				$sql_in = "insert into wiz_tempfile set filename='".$sessUpload."', wdate='".time()."'";
				query($sql_in);
			}

			$temp_file = $updir.$sessUpload;
			$fp = fopen($temp_file, "a+");
			ob_start();
			print($data);
			$msg = ob_get_contents();
			ob_end_clean();
			fwrite($fp, $msg);
			fclose($fp);

			echo strlen($data);
		}
	} else if($mode == "fileDel") {
		include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

		@unlink($updir.$sessUpload);
		$sql = "delete from wiz_tempfile where filename='".$sessUpload."'";
		query($sql);

		echo "ok";
		
	}
?>