<?
// 업로드 디렉토리 생성
$upfile_max = "5";
$upfile_path = "../../data/product2";
if(!is_dir($upfile_path)) {
	mkdir($upfile_path, 0707);
	chmod($upfile_path, 0707);	
}

if($prdcode != "" && !strcmp($mode, "update")){
	$sql = "
		select upfile1
			 , upfile2
			 , upfile3
			 , upfile4
			 , upfile5
			 , upfile1_name
			 , upfile2_name
			 , upfile3_name
			 , upfile4_name
			 , upfile5_name
		  from wiz_product2
		 where prdcode = '$prdcode'
	";
	$result = query($sql);
	$prd_row = sql_fetch_arr($result);
}
if($delupfile) {
	for($ii = 0; $ii < count($delupfile); $ii++) {
		if($prd_row[$delupfile[$ii]] != ""){
			@unlink($upfile_path."/".$prd_row[$delupfile[$ii]]);
		}
		$upfile_sql .= " , $delupfile[$ii]='', $delupfile[$ii]_name='' ";
	}
}
for($ii = 1; $ii <= $upfile_max; $ii++) {

	if(isset($_FILES['upfile'.$ii]) && is_uploaded_file($_FILES['upfile'.$ii]['tmp_name'])) {

		$upfile_size = $_FILES['upfile'.$ii]['size'];
		$upfile_name = $_FILES['upfile'.$ii]['name'];
		$upfile      = $_FILES['upfile'.$ii]['tmp_name'];

		if($upfile_size > 0){

			if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
				error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
			}

			file_check($upfile_name);

//			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $upfile_name);
//			$len = (strlen($ext) == 3) ? "-3" : "-4";

//			$upfile_tmp = $prdcode."_".$ii.".".substr($upfile_name,$len);

			$ext = getFileExt($upfile_name);
			$upfile_tmp = $prdcode."_".$ii.".".$ext;


			@copy($upfile, $upfile_path."/".$upfile_tmp);
			@chmod($upfile_path."/".$upfile_tmp, 0606);

			$upfile_sql .= " , upfile".$ii."='$upfile_tmp', upfile".$ii."_name='$upfile_name' ";

			${'upfile'.$ii.'_tmp'} = $upfile_tmp;
			${'upfile'.$ii.'_name'} = $upfile_name;

		}

	}
}
?> 
