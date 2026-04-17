<?/*
작업자명	: 김나연
작업일시	: 2021-10-28
작업내용	: 파일분할전송 save
*/
$temp_dir = WAY_DATA_PATH."/temp/";

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) {	
	$oldmask = umask(0);
	mkdir($upfile_path, DIR_PERM, true);
	umask($oldmask);
}

if($mode == "insert" || $mode == "reply") {
	if(!empty($_POST['upfiles']) && is_array($_POST['upfiles'])) {
		$fileList = array();
		$fno = 1;
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
			if($fname == "movie1") {
				${$fname."_tmp"} = $upfile_idx."_m1.".$ext;
			} else {
				${$fname."_tmp"} = $fval['filename'].".".$ext;  //$upfile_idx."_".$ii.".".$ext;		//$fval['filename'].".".$ext;
			}
			fromBase64toBinary($temp_dir.$fval['filename'], $upfile_path."/".${$fname."_tmp"});

			//이미지리사이즈
			if(img_type($upfile_path."/".${$fname."_tmp"})){
				$srcimg = ${$fname."_tmp"};
				$dstimg = "S".${$fname."_tmp"};
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);

				$dstimg = "M".${$fname."_tmp"};
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m, "width");
			}
		}

		$upfile_sql_new = "";
		for($i=1; $i<=12; $i++) {
			$upfile_sql_new .= ", upfile".$i."='".${"upfile".$i."_tmp"}."', upfile".$i."_name='".${"upfile".$i."_name"}."'
			";
		}
		// 동영상
		if($movie1_tmp) {
			if($use_vimeo) {			//vimeo 업로드
				$movie1_file = $upfile_path."/".$movie1_tmp;
				$uri = $lib->upload($movie1_file, array("name"=>$subject, "description" => "", "privacy"=>array("view"=>"anybody")));
				// 해당 파일의 정보를 요청한다.
				$video_data = $lib->request($uri);
				// 업로드가 성공한 파일의 링크를 받는다.
				$link = '';
				if($video_data['status'] == 200) {
					$link = $video_data['body']['link'];
					if($link) {
						$movie1_sql = " , movie1='$link' ";
						@unlink($movie1_file);
					} else {		//업로드 실패 등 발생 시 서버 내 파일로 저장
						$movie1_sql = " , movie1='".$movie1_tmp."'";
					}
				}
			} else {			// 서버 업로드
				$movie1_sql = " , movie1='$movie1_tmp' ";
			}
		}
	}
} else if ($mode == "modify") {
	$fno = 1;
	$mno = 1;
///	echo "<xmp>";
	$sql= "select * from wiz_bbs where idx='$idx'";
	$bbs_row = sql_fetch($sql);
	if($_POST['delfiles']) {
		$delfiles = explode("|", $_POST['delfiles']);
		//echo "\ndelfiles >> ".print_r($delfiles,1);
		$upfile_sql_new = "";
		foreach($delfiles as $no) {
			if($no) {
				$delfile = $upfile_path."/".$bbs_row['upfile'.$no];
				$delfileM = $upfile_path."/M".$bbs_row['upfile'.$no];
				$delfileS = $upfile_path."/S".$bbs_row['upfile'.$no];
				@unlink($delfile);
				@unlink($delfileM);
				@unlink($delfileS);
			}
		}
	}
	if($_POST['del_movie'] == "Y") {
		if(strpos($bbs_row['movie1'], "vimeo.com") === false) {
			//비메오 링크가 아닐 경우
			$delfile = $upfile_path."/".$bbs_row['movie1'];
			@unlink($delfile);
		}
		$movie1_sql = " , movie1='' ";
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
			if($fname == "movie1") {
				${$fname."_tmp"} = $upfile_idx."_m1.".$ext;
			} else {
				${$fname."_tmp"} = $fval['filename'].".".$ext;		//$upfile_idx."_".$ii.".".$ext;	//
			}
			fromBase64toBinary($temp_dir.$fval['filename'], $upfile_path."/".${$fname."_tmp"});

			//이미지리사이즈
			if(img_type($upfile_path."/".${$fname."_tmp"})){
				$srcimg = ${$fname."_tmp"};
				$dstimg = "S".${$fname."_tmp"};
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);

				$dstimg = "M".${$fname."_tmp"};
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m, "width");
			}
		}
	}
	$upfile_sql_new = "";
	for($i=1; $i<=12; $i++) {
		$upfile_sql_new .= ", upfile".$i."='".${"upfile".$i."_tmp"}."', upfile".$i."_name='".${"upfile".$i."_name"}."'
		";
	}

	// 동영상
	if($movie1_tmp) {
		if($use_vimeo) {			//vimeo 업로드
			$movie1_file = $upfile_path."/".$movie1_tmp;
			$uri = $lib->upload($movie1_file, array("name"=>$subject, "description" => "", "privacy"=>array("view"=>"anybody")));

			// 해당 파일의 정보를 요청한다.
			$video_data = $lib->request($uri);
			// 업로드가 성공한 파일의 링크를 받는다.
			$link = '';
			if($video_data['status'] == 200) {
				$link = $video_data['body']['link'];
				if($link) {
					$movie1_sql = " , movie1='$link' ";
					@unlink($movie1_file);
				} else {		//업로드 실패 등 발생 시 서버 내 파일로 저장
					$movie1_sql = " , movie1='".$movie1_tmp."'";
				}
			}
		} else {			// 서버 업로드
			$movie1_sql = " , movie1='$movie1_tmp' ";
		}
	}
}


?>