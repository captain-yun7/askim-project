<?
include_once "../../common.php";

$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name from wiz_form where idx = '$idx'";
$result = query($sql);
$row = sql_fetch_arr($result);

if($no == "1"){
	$file = "../../data/form/".$row['upfile1']; $filename = str_conv($row['upfile1_name'], "euc-kr");
}else if($no == "2"){
	$file = "../../data/form/".$row['upfile2'];  $filename = str_conv($row['upfile2_name'], "euc-kr");
}else if($no == "3"){
	$file = "../../data/form/".$row['upfile3'];  $filename = str_conv($row['upfile3_name'], "euc-kr");
}else if($no == "4"){
	$file = "../../data/form/".$row['upfile4'];  $filename = str_conv($row['upfile4_name'], "euc-kr");
}else if($no == "5"){
	$file = "../../data/form/".$row['upfile5'];  $filename = str_conv($row['upfile5_name'], "euc-kr");
}

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
   echo "<script>alert('첨부파일이 존재하지 않습니다.');history.go(-1);</script>";
}

?>

<?
/*
$filename = $file;
$file = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/form/".urlencode($file);


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
*/
?>