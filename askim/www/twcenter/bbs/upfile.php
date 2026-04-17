<?php
// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) {	
	$oldmask = umask(0);
	mkdir($upfile_path, DIR_PERM, true);
	umask($oldmask);
}

if($idx != "" && !strcmp($mode, "modify")){
	$sql = "
		select upfile1
		     , upfile2
			 , upfile3
			 , upfile4
			 , upfile5
			 , upfile6
			 , upfile7
			 , upfile8
			 , upfile9
			 , upfile10
			 , upfile11
			 , upfile12
			 , upfile1_name
			 , upfile2_name
			 , upfile3_name
			 , upfile4_name
			 , upfile5_name
			 , upfile6_name
			 , upfile7_name
			 , upfile8_name
			 , upfile9_name
			 , upfile10_name
			 , upfile11_name
			 , upfile12_name
			 , movie1
		  from wiz_bbs
		 where idx = '$idx'
	";
	$result = query($sql);
	$bbs_row = sql_fetch_arr($result);
}
if(!empty($delupfile)) {
	for($ii = 0; $ii < count($delupfile); $ii++) {

		if($bbs_row[$delupfile[$ii]] != ""){
			@unlink($upfile_path."/".$bbs_row[$delupfile[$ii]]);
			@unlink($upfile_path."/S".$bbs_row[$delupfile[$ii]]);
			@unlink($upfile_path."/M".$bbs_row[$delupfile[$ii]]);

			if(!strcmp($delupfile[$ii], "movie1")) {
				$upfile_sql .= " , ".$delupfile[$ii]." = '' ";
			} else {
				$upfile_sql .= " , ".$delupfile[$ii]."='', ".$delupfile[$ii]."_name='' ";
			}

		}
	}
}

for($ii = 1; $ii <= $upfile_max; $ii++) {

	$upfile_size = $_FILES['upfile'.$ii]['size'];
	if(!isset($_FILES['upfile'.$ii]['name'])) $_FILES['upfile'.$ii]['name'] = '';
	$upfile_name = stripslashes($_FILES['upfile'.$ii]['name']);
	if(strpos(strtolower(getenv("OS")), "windows") === false) {
		if(!isset($_FILES['upfile'.$ii]['tmp_name'])) $_FILES['upfile'.$ii]['tmp_name'] = '';
		$upfile      = stripslashes($_FILES['upfile'.$ii]['tmp_name']);
	} else {
		$upfile      = ($_FILES['upfile'.$ii]['tmp_name']);
	}

	if($upfile_size > 0){
		
		if(fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895 && fileperms($upfile_path) != 16837){			/// 2021-01-25 16837(705) 제외 ==> 210219 재수정, 정상업로드 가능한 경우도 있으므로 이후에 파일 정상업로드여부 추가 체크하는 것으로 변경
			$perm_error_msg = "파일업로드시 문제가 발생하였습니다.\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.";
			error($perm_error_msg,"");
		}

		file_check($upfile_name);

//		$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $upfile_name);
//		$str_len = (strlen($ext) == 3) ? "-3" : "-4";
		
//		$upfile_tmp = $upfile_idx."_".$ii.".".substr($upfile_name,$str_len);

		$ext = getFileExt($upfile_name);
		$upfile_tmp = $upfile_idx."_".$ii.".".$ext;
//echo $upfile;
		copy($upfile, $upfile_path."/".$upfile_tmp);
		if(@file($upfile_path."/".$upfile_tmp) == false) {				// 2021-02-19 파일정상업로드여부 체크
			echo("파일이 업로드되지 않았습니다.");
			echo $upfile_path."/".$upfile_tmp;
			exit;
		}
		chmod($upfile_path."/".$upfile_tmp, 0606);
		if($bbs_row['upfile'.$ii] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/S".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/M".$bbs_row['upfile'.$ii]);
		}

		$upfile_sql .= " , upfile".$ii."='$upfile_tmp', upfile".$ii."_name='$upfile_name' ";

		// 썸네일 만들기
		if(img_type($upfile_path."/".$upfile_tmp)){
			$srcimg = $upfile_tmp;
			$dstimg = "S".$upfile_tmp;
			img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);

			$dstimg = "M".$upfile_tmp;
			img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m, "width");

		}

		${'upfile'.$ii.'_tmp'} = $upfile_tmp;
		${'upfile'.$ii.'_name'} = $upfile_name;

	}

}

if($movie1['size'] > 0 && !$use_vimeo) {		//비메오 업로드가 실행되지 않았을 경우
	file_check($movie1['name']);

//	$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $movie1['name']);
//	$str_len = (strlen($ext) == 3) ? "-3" : "-4";

//	$movie1_tmp = $upfile_idx."_m1.".substr($movie1['name'],$str_len);

	$ext = getFileExt($movie1['name']);
	$movie1_tmp = $upfile_idx."_m1.".$ext;

	copy($movie1['tmp_name'], $upfile_path."/".$movie1_tmp);
	chmod($upfile_path."/".$movie1_tmp, 0606);
	if($bbs_row['movie1'] != ""){
		@unlink($upfile_path."/".$bbs_row['movie1']);
	}
	$movie1_sql = " , movie1='$movie1_tmp' ";

}
?>