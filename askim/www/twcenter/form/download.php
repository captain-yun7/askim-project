<?
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

	$file = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/form/".$df_file;
	$filename = $df_filename;

	if(file_exists($file)) {

		save_point("BBS", $wiz_session['id'], "down", $idx);

		if(strstr($HTTP_USER_AGENT,"MSIE 5.5")){
			Header("Content-Type: doesn/matter");
			Header("content-length: ". filesize("$file"));
			Header("Content-Disposition: attachment; filename=$filename");
			Header("Content-Transfer-Encoding: binary");
			Header("Cache-Control: cache,must-revalidate");
			Header("Pragma: cache");
			Header("Expires: 0");
		}else{
			Header("Content-type: file/unknown");
			Header("content-length: ". filesize("$file"));
			Header("Content-Disposition: attachment; filename=$filename");
			Header("Content-Description: PHP3 Generated Data");
			Header("Cache-Control: cache,must-revalidate");
			Header("Pragma: cache");
			Header("Expires: 0");
		}

		if(is_file("$file")){
			$fp = fopen("$file","r");
			if(!fpassthru($fp)) {
				fclose($fp);
			}
		}

	}else{
		echo "<script>alert('첨부파일이 존재하지 않습니다.');</script>";
	}

?>