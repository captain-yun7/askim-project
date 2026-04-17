    <?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/sch_info.php";

$sql = "select upfile1,upfile2,upfile3,upfile1_name,upfile2_name,upfile3_name from wiz_bbs where idx = '$idx'";
$result = query($sql) or error("sql_error");
$row = sql_fetch_arr($result);

// stripslashes()
for($ii = 1; $ii <= $upfile_max; $ii++) {
	$row["upfile".$ii."_name"] = stripslashes($row["upfile".$ii."_name"]);
}

if($no == "1"){
	$file = "../data/bbs/$code/$row['upfile1']"; $filename = str_conv($row['upfile1_name'], "euc-kr");
}else if($no == "2"){
	$file = "../data/bbs/$code/$row['upfile2']"; $filename = str_conv($row['upfile2_name'], "euc-kr");
}else if($no == "3"){
	$file = "../data/bbs/$code/$row['upfile3']"; $filename = str_conv($row['upfile3_name'], "euc-kr");
}

if(file_exists($file)) {

   if( strstr($HTTP_USER_AGENT,"MSIE 5.5")){
       header("Content-Type: doesn/matter");
       header("Content-Disposition: filename=$filename");
       header("Content-Transfer-Encoding: binary");
       header("Pragma: no-cache");
       header("Expires: 0");
   }else{
       Header("Content-type: file/unknown");
       Header("Content-Disposition: attachment; filename=$filename");
       Header("Content-Description: PHP3 Generated Data");
       header("Pragma: no-cache");
       header("Expires: 0");
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