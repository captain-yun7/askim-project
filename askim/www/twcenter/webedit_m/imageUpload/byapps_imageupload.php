<?
require_once("config.php");

$tempfile = $_FILES['file']['tmp_name'];
$filename = $_FILES['file']['name'];

$type = substr($filename, strrpos($filename, ".")+1);
$found = false;
switch ($type) {
	case "jpg":
	case "jpeg":
	case "gif":
	case "png":
		$found = true;
}

if ($found != true) {
	exit;
}

$savefile = SAVE_DIR . '/' . $filename;

$image_upload_tmp_dir= SAVE_DIR;

if(preg_match("/png|jpg|gif/i",$_POST['type'])){
	if(substr($_POST['data'],0,4)=="http"&&preg_match("/\.(jp[e]?g|gif|png)$/i",$_POST['data'])) {
		if(!preg_match("/https\:\/\//i",$_POST['data'])&&preg_match("/https\:\//i",$_POST['data'])){
			$_POST['data']=str_replace("https:/","https://",$_POST['data']);
		}
		$data = GetImageFromUrl($_POST['data']);
	}else{
		$data = base64_decode($_POST['data']);
	}

	$success = file_put_contents($filename, $data);

	$return=($success) ? "http://이미지 경로를 도메인주소/".$filename:"";
}

function GetImageFromUrl($link) { 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_POST, 0); 
	curl_setopt($ch,CURLOPT_URL,$link); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0');
	if(preg_match("/https\:/i",$link)){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	$result=curl_exec($ch); 
	curl_close($ch);

	return $result; 
}

header("Content-Type:text/html;charset:utf-8");
echo $return;
exit;
?>