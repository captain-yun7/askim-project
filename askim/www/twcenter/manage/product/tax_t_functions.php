<?php

// 첫번째 파라미터의 URL 로 data 를 POST 로 요청 한 후 결과를 수신 합니다.
function file_post_contents( $url, $data=null, $optional_headers = null) {
	 $go_url = "http://anywiz.freebill.co.kr".$url; 

	//echo $go_url."<br/>".$url;
	
	$host_info = explode("/", $go_url);
	$host = $host_info[2];
	 $path = $host_info[3]."/".$host_info[4]."/".$host_info[5];
	 if($host_info[6]!="") $path.="/".$host_info[6];
	 if($host_info[7]!="") $path.="/".$host_info[7];
	 // 호스트,경로 맞춰 변환

	srand((double)microtime()*1000000);
	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
 //print_r($go_arr);
 // 바운더리 코드 생성

	$header = "POST /".$path ." HTTP/1.0\r\n";
	 $header .= "Host: ".$host."\r\n";
	 $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
 // 헤더 생성


	$header .= "Content-length: 0\r\n\r\n";
	


	$fp = fsockopen($host, 80);
	// 소켓통신 시작
	if ($fp) {
		$sock_return_value = "Connection Success <br>";
		fputs($fp, $header.$data);
		$rsp = '';
		while(!feof($fp)) {
			$rsp .= fgets($fp,8192);
		}
		fclose($fp);
		$msg = trim($rsp);
	}else {
		$sock_return_value = "Connection Failed <br>";
	}

	/*echo $sock_return_value ;
	echo "<xmp>".$msg."</xmp>";*/


		//echo $sock_return_value ;

		if($msg!=""){
			
			$rece_data=explode("\r\n\r\n", $msg);

			/*echo"<pre>";
			print_r($rece_data);
			echo"</pre>";*/
		
			return $rece_data[1];
		}else{

			return $go_url."URL결과의 내용을 읽는데 문제가 있습니다.".$php_errormsg;

		}

		
	
	
	
}


function tax_regist($url,$qr=null){

 $go_url = "http://anywiz.freebill.co.kr".$url; 

 $host_info = explode("/", $go_url);
 $host = $host_info[2];
 $path = $host_info[3]."/".$host_info[4]."/".$host_info[5];
 // 호스트,경로 맞춰 변환

srand((double)microtime()*1000000);
 $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
 //print_r($go_arr);
 // 바운더리 코드 생성

$header = "POST /".$path ." HTTP/1.0\r\n";
 $header .= "Host: ".$host."\r\n";
 $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
 // 헤더 생성

$data="";
if($qr!=""){
foreach($qr AS $index => $value){
  $data .="--$boundary\r\n";
  $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
  $data .= "\r\n".$value."\r\n";
  $data .="--$boundary\r\n";
 }
 $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
}
 // 본문 생성

$fp = fsockopen($host, 80);
 // 소켓통신 시작
if ($fp) {
  $sock_return_value = "Connection Success <br>";
  fputs($fp, $header.$data);
  $rsp = '';
  while(!feof($fp)) {
   $rsp .= fgets($fp,8192);
  }
  fclose($fp);
  $msg = trim($rsp);
 }else {
  $sock_return_value = "Connection Failed <br>";
 }

//echo $sock_return_value ;
 //echo "<xmp>".$msg."</xmp>";
 	if($msg!=""){
		$rece_data=explode("\r\n\r\n", $msg);

		/*echo"<pre>";
		print_r($rece_data);
		echo"</pre>";*/
		return $rece_data[1];
	}else{

		return $go_url."URL결과의 내용을 읽는데 문제가 있습니다.".$php_errormsg;

	}
 }
?>