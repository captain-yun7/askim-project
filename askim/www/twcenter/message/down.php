<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$sql = "select upfile, upfile_name from wiz_message where idx = '$idx'";
$result = query($sql) or error("sql_error");
$row = sql_fetch_arr($result);

$file = "../data/message/$se_id/$row['upfile']"; $filename = $row['upfile_name'];

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
