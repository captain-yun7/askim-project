<?

// 파일업로드 설정
$upfile_path = WIZHOME_PATH."/data/message/".$se_id;		// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
$imgsize_s = $bbs_info[simgsize];
$imgsize_m = $bbs_info[mimgsize];

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

if($idx != ""){
	$sql = "select upfile,upfile_name from wiz_message where idx = '$idx'";
	$result = query($sql) or error("sql_error");
	$msg_row = sql_fetch_arr($result);
}

if($upfile[size] > 0){

	if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
		error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
	}

	file_check($upfile[name]);
	$upfile_name = $upfile[name];

	$upfile_tmp = $upfile_idx.".".substr($upfile[name],-3);
	copy($upfile[tmp_name], $upfile_path."/".$upfile_tmp);
	chmod($upfile_path."/".$upfile_tmp, 0606);
	if($msg_row[upfile] != ""){
		@unlink($upfile_path."/".$msg_row[upfile]);
	}

	$upfile_sql = " , upfile='$upfile_tmp', upfile_name='$upfile_name' ";

	// 썸네일 만들기
	if(img_type($upfile_path."/".$upfile_tmp)){
		$srcimg = $upfile_tmp;
		$dstimg = "S".$upfile_tmp;
		img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);

		$dstimg = "M".$upfile_tmp;
		img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m);
	}

}

?>