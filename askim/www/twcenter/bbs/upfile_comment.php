<?php
if(!is_dir($upfile_comm_path)) mkdir($upfile_comm_path, DIR_PERM);

if($comment_idx != "" && !strcmp($mode, "comment_e")){
	$sql = "select upfile1,upfile1_name from wiz_comment where idx = '$comment_idx'";
	$result = query($sql) or error("sql error");
	$bbs_comm_row = sql_fetch_arr($result);
}


$ii = 1;

if($bbs_comm_row[$delupfile] != ""){

	@unlink($upfile_comm_path."/".$bbs_comm_row[$delupfile]);
	@unlink($upfile_comm_path."/S".$bbs_comm_row[$delupfile]);
	@unlink($upfile_comm_path."/M".$bbs_comm_row[$delupfile]);

	$upfile_sql .= " , upfile1 = '', upfile1_name = '' ";

}

$upfile_size = $_FILES['upfile'.$ii]['size'];
$upfile_name = $_FILES['upfile'.$ii]['name'];
$upfile      = $_FILES['upfile'.$ii]['tmp_name'];

if($upfile_size > 0){
		
	if(fileperms($upfile_comm_path) != 16837 && fileperms($upfile_comm_path) != 16839 && fileperms($upfile_comm_path) != 16895){
		error(fileperms($upfile_comm_path));
		error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
	}

	file_check($upfile_name);

	$ext = end(explode('.', $upfile_name)); 
	$str_len = (strlen($ext) == 3) ? "-3" : "-4";

	$upfile_tmp = $upfile_idx."_".$ii.".".substr($upfile_name,$str_len);
	copy($upfile, $upfile_comm_path."/".$upfile_tmp);
	chmod($upfile_comm_path."/".$upfile_tmp, FILE_PERM);
	if($bbs_comm_row['upfile'.$ii] != ""){
		@unlink($upfile_comm_path."/".$bbs_comm_row['upfile'.$ii]);
		@unlink($upfile_comm_path."/S".$bbs_comm_row['upfile'.$ii]);
		@unlink($upfile_comm_path."/M".$bbs_comm_row['upfile'.$ii]);
	}

	$upfile_sql .= " , upfile".$ii."='$upfile_tmp', upfile".$ii."_name='$upfile_name' ";

	if(img_type($upfile_comm_path."/".$upfile_tmp)){
		$srcimg = $upfile_tmp;
		$dstimg = "S".$upfile_tmp;
		img_resize($srcimg, $dstimg, $upfile_comm_path, $imgsize_s, $imgsize_s);

		$dstimg = "M".$upfile_tmp;
		img_resize($srcimg, $dstimg, $upfile_comm_path, $imgsize_m, $imgsize_m, "width");

	}

	${'upfile'.$ii.'_tmp'} = $upfile_tmp;
	${'upfile'.$ii.'_name'} = $upfile_name;

}

?>