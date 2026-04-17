<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name from wiz_product where prdcode = '$prdcode'";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);

$file = "../data/product/$code/".$row[upfile.$no]; $filename = str_conv($row[upfile.$no._name], "euc-kr");

if(file_exists($file)) {

   if( strstr($HTTP_USER_AGENT,"MSIE 5.5")){
       Header("Content-Type: doesn/matter");
       Header("content-length: ". filesize("$file"));
       Header("Content-Disposition: attachment; filename=$filename");
       Header("Content-Transfer-Encoding: binary");
       Header("Cache-Control: cache,must-revalidate");
       Header("Pragma: cache");
       Header("Expires: 0");
   }else{
        $extAr = explode(".", $filename);

		$ext = strtolower($extAr[sizeof($extAr)-1]);

		switch($ext){
			case "aac":
				Header("Content-type: audio/aac");
				break;
			case "doc":
				Header("Content-type: application/msword");
				break;
			case "pdf":
				Header("Content-type: application/pdf");
				break;
			case "ppt":
				Header("Content-type: application/vnd.ms-powerpoint");
				break;
			case "csv":
				Header("Content-type: text/csv");
				break;
			case "html": 
			case "htm":
				Header("Content-type: text/html");
				break;
			case "gif": 
				Header("Content-type: image/gif");
				break;
			case "avi": 
				Header("Content-type: video/x-msvideo");
				break;
			case "mpeg": 
				Header("Content-type: video/mpeg");
				break;
			case "tar": 
				Header("Content-type: application/x-tar");
				break;
			case "tif": 
			case "tiff": 
				Header("Content-type: image/tiff");
				break;
			case "jpeg": 
			case "jpg": 
				Header("Content-type: image/jpeg");
				break;
			case "zip": 
				Header("Content-type: application/zip");
				break;
			case "7z": 
				Header("Content-type: application/x-7z-compressed");
				break;
			case "xls": 
				Header("Content-type: application/vnd.ms-excel");
				break;
		}

       //Header("Content-type: file/unknown");
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
   echo "<script>alert('첨부파일이 존재하지 않습니다.');history.go(-1);</script>";
}

?>