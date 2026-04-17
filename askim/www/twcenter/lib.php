<?php
//define("__CASTLE_PHP_VERSION_BASE_DIR__", $_SERVER['DOCUMENT_ROOT']."/way_castle");
//include_once(__CASTLE_PHP_VERSION_BASE_DIR__ ."/castle_referee.php");

// prev, 스팸글 체크등 HTTP_HOST 체크 시 SSL 포트번호와 분리
if(strpos($_SERVER['HTTP_HOST'], ":") !== false) {
	list($_http_host, $_http_port) = explode(":", $_SERVER['HTTP_HOST']);
} else {
	$_http_host = $_SERVER['HTTP_HOST'];
	$_http_port = "";
}

//-- 모바일체크
$mobile_path = "m";
function mobile_check() {

	global $mobile_path;

	$mobile_array = array("iphone","ipod","android","blackberry","symbianos|sch-m\d+","opera mini","windows ce","nokia","sony","samsung","lgtelecom","skt","mobile","phone");

	$checkCount = 0;
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "ipad") === false) {
		for($i=0; $i<sizeof($mobile_array); $i++){
			if(preg_match("/$mobile_array[$i]/", strtolower($_SERVER['HTTP_USER_AGENT']))){
				$checkCount++;
				break;
			}
		}
	}

	// PC 테스트 시
	$referer = $_SERVER['HTTP_REFERER'];
	$parse_url = parse_url($referer);

	$parse_path = explode("/", $parse_url['path']);

	if($parse_path[1] == $mobile_path) {
		$checkCount += 1;
	}

	return ($checkCount >= 1) ? true : false;
}

function mobile_check_device() {

	$mobile_array = array("iphone","ipod","android","android","blackberry","symbianos|sch-m\d+","opera mini","windows ce","nokia","sony","samsung","lgtelecom","skt","mobile","phone");

	$checkCount = 0;
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "ipad") === false) {
		for($i=0; $i<sizeof($mobile_array); $i++){
			if(preg_match("/$mobile_array[$i]/", strtolower($_SERVER['HTTP_USER_AGENT']))){
				$checkCount++;
				break;
			}
		}
	}
	return ($checkCount >= 1) ? true : false;
}

function mobile_check2() {

	$mobile_array = array("iphone","ipod","android","blackberry","symbianos|sch-m\d+","opera mini","windows ce","nokia","sony","samsung","lgtelecom","skt","mobile","phone");

	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "ipad") === false) {
		for($i=0; $i<count($mobile_array); $i++){
			if(preg_match("/$mobile_array[$i]/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return $mobile_array[$i];
				break;
			}
		}
	}
	return "PC";

}


function dbconn($dbhost, $dbuser, $dbpass, $dbname=MYSQL_DB) {

	global $tw;

	if(function_exists('mysqli_connect') && extension_loaded('mysqli') && MYSQLI_USE) {
		$dbhost = explode(":", $dbhost);
		if (isset($dbhost[1])) $connect = mysqli_connect($dbhost[0], $dbuser, $dbpass, $dbname, $dbhost[1]);
		else				   $connect = mysqli_connect($dbhost[0], $dbuser, $dbpass, $dbname);

		if(!$connect) die("DBconn Error: ".mysqli_connect_error());
	} else {
		$connect = mysql_connect($dbhost, $dbuser, $dbpass);
		if(!$connect) die("DBconn Error: ".mysql_connect_error());
	}

	return $connect;

}

function select_dbconn($dbname, $connect) {

	global $tw;
	
	if(!$dbname)  $dbname  = $tw['db_name'];
	if(function_exists('mysqli_select_db') && extension_loaded('mysqli') && MYSQLI_USE) {
		return @mysqli_select_db($connect, $dbname);
	} else {
		return @mysql_select_db($dbname, $connect);
	}
}

function set_names($charset, $link) {
	if(function_exists('mysqli_set_charset') && extension_loaded('mysqli') && MYSQLI_USE) {
		return mysqli_set_charset($link, $charset);
	} else {
		return mysql_query("set names $charset", $link);
	}
}

function query($sql, $error=false, $link=null) {

	global $tw;

	if(!$link) $link = $tw['connect'];

	$sql = trim($sql);

	$sql = preg_replace("/^select.*from.*[\s\(]+union[\s\)]+.*/i ", "select 1", $sql);
	$sql = preg_replace("/^select.*from.*where.*`?information_schema`?.*/i", "select 1", $sql);

	if(function_exists('mysqli_query') && extension_loaded('mysqli') && MYSQLI_USE) {
		if($error){
			$result = @mysqli_query($link,$sql) or die("<p>".$sql."</p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : ".$_SERVER['SCRIPT_NAME']."</p>");
		} else {
			$result = @mysqli_query($link,$sql);
		}
	} else {
		if($error){
			$result = @mysql_query($sql,$link) or die("<p>".$sql."</p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : ".$_SERVER['SCRIPT_NAME']."</p>");
		} else {
			$result = @mysql_query($sql,$link);
		}
	}
	return $result;
}

function sql_fetch($sql, $error=false, $link=null) {

	global $tw;
	
	if(!$link) $link = $tw['connect'];

	$result = query($sql, $error, $link) or error(mysqli_error());
	$row = sql_fetch_arr($result);
//	print_r($row);

	return $row;
}

function sql_fetch_arr($result) {
	if(function_exists('mysqli_fetch_assoc') && extension_loaded('mysqli') && MYSQLI_USE) {
		return @mysqli_fetch_assoc($result);
	} else {
		return @mysql_fetch_assoc($result);
	}
}

function sql_fetch_obj($result) {
	if(function_exists('mysqli_fetch_object') && extension_loaded('mysqli') && MYSQLI_USE) {
		return @mysqli_fetch_object($result);
	} else {
		return @mysql_fetch_object($result);
	}
}

function sql_fetch_row($result) {
	if(function_exists('mysqli_num_rows') && extension_loaded('mysqli') && MYSQLI_USE) {
		return @mysqli_num_rows($result);
	} else {
		return @mysql_num_rows($result);
	}
}

function sql_fetch_object($sql, $error=false) {

	global $tw;

	if(empty($link) && !empty($tw)) $link = $tw['connect'];
	$result = query($sql, $error, $link);
	$row = sql_fetch_obj($result);
	return $row;
}

function sql_fetch_rows($sql, $error=false) {

	global $tw;

	if(!$link) $link = $tw['connect'];
	$result = query($sql, $error, $link);
	$row = sql_fetch_row($result);
	return $row;
}

function dbclose($link) {

	if(function_exists('mysqli_close') && extension_loaded('mysqli') && MYSQLI_USE) {
		return mysqli_close($link);
	} else {
		return mysql_close($link);
	}

}

/**
 * https://scrutinizer-ci.com/ (NodeFactory::array_map_deep())
 */
function array_map_deep($array, $callback)
{
	$new_array = array();
	if (is_array($array)) {
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$new_array[$key] = array_map_deep($val, $callback);
			} else {
				$new_array[$key] = call_user_func($callback, $val);
			}
		}
	} else {
		$new_array = call_user_func($callback, $array);
	}
	return $new_array;
}

function escapeString($str) {
	$pattern = "#(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop|join).*#i";
	$replace = "";

	if($pattern) {
		$str = preg_replace($pattern, $replace, $str);
	}

	$str = call_user_func('addslashes', $str.'');
	return $str;
}

function removeCRLF($str) {
	$str = str_replace("%0d", "", $str);
	$str = str_replace("%0a", "", $str);
	$str = str_replace("\r", "", $str);
	$str = str_replace("\n", "", $str);
	return $str;
}

// 랜덤한 10자리 정수를 리턴
function get_rand_number($len=10) {
    $len = abs((int)$len);

    if ($len < 1) $len = 1;

    else if ($len > 10) $len = 10;

    return rand(pow(10, $len - 1), (pow(10, $len) - 1));
}

// 에러 출력
function error($msg, $go_url=""){
	if($go_url == "") {
		echo "<script>alert(\"$msg\");history.go(-1);</script>";
		exit;
	} else {
		echo "<script>alert(\"$msg\");document.location=\"$go_url\";</script>";
		exit;
	}


}

// 경고창 출력
function alert($msg, $go_url=""){

	if($go_url == "")
		echo "<script>alert(\"$msg\");history.go(-1);</script>";
	else
		echo "<script>alert(\"$msg\");document.location=\"$go_url\";</script>";
}

// return 경고창만 출력
function alertrtn($msg){
	echo "<script>alert(\"$msg\");</script>";
}

function alertConfirmSess($msg, $u_id) {
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/session_msg_inc.php";
	exit;
}

function sess_del($u_id) {
	query("delete from wiz_session where user_id = '".$u_id."' ");
//	exit;
}

// 완료 메세지 출력
function complete($com_msg, $go_url=""){

	if($go_url == "")
		echo "<script>window.setTimeout(\"history.go(-1)\",600);</script>";
	else
		echo "<script>window.setTimeout(\"document.location='$go_url';\",600);</script>";
		echo "<body><table width=100% height=100%><tr><td align=center><font size=2>$com_msg</font></td></tr></table></body>";

}

function alert_gourl($msg, $go_url='')
{
	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/alert_msg_inc.php";
	exit;
}

// 테이블 데이타 얻기
function get_table($table, $search = ""){

	if($search != "") $search_sql = " where $search";
	$sql = "select * from ".$table.$search_sql;
	$result = query($sql);
	$data = sql_fetch_arr($result);

	return $data;

}

// 문자열 끊기 (이상의 길이일때는 ... 로 표시)
/*function cut_str($msg, $cut_size){

	if($cut_size<=0) return $msg;
	if(preg_match("/\[re\]/",$msg)) $cut_size=$cut_size+4;
	for($i=0;$i<$cut_size;$i++) if(ord($msg[$i])>127) $han++; else $eng++;
	$cut_size=$cut_size+(int)$han*0.6;
	$point=1;
	for ($i=0;$i<strlen($msg);$i++) {
		if ($point>$cut_size) return $pointtmp."...";
		if (ord($msg[$i])<=127) {
			$pointtmp.= $msg[$i];
			if ($point%$cut_size==0) return $pointtmp."...";
		} else {
			if ($point%$cut_size==0) return $pointtmp."...";
			$pointtmp.=$msg[$i].$msg[++$i];
			$point++;
		}
		$point++;
	}

	return $pointtmp;

}
*/
function cut_str($str, $len){
	
	if(!isset($str)) $str = '';
	preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match);
	$m = $match[0] ? $match[0] : "";
	$slen = empty($str) ? 0 : strlen($str); //문자열길이
	$tail = '...';
	$tlen = strlen($tail); //문자열길이 끝부분 처리
	if ($slen <= $len) return $str;
	$ret = array();
	$count = 0;
	for ($i=0; $i < $len; $i++){
		$count += (strlen($m[$i] ?? "") > 1)?2:1;

		if ($count + $tlen > $len) break;
		$ret[] = $m[$i];
	}
	return join('', $ret).$tail;
}



// 문자열의 마지막 문자를 * 로 처리해서 반환
function set_passwd($str){
	$re_str = "";
	$strlen = strlen($str) - 2;
	$re_str = substr($str,0,2);
	for($ii=0;$ii<$strlen;$ii++){
		$re_str .= "*";
	}

	return $re_str;
}


// 기본 레벨
function level_basic(){
	$sql = "select idx from wiz_level order by level desc limit 1";
	$result = query($sql);
	$row = sql_fetch_obj($result);

	return $row->idx;

}


// 회원등급 리스트
function level_list(){

	$sql = "select idx,level,name from wiz_level order by level desc, idx asc";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){
		echo "<option value='$row->idx'>$row->name</option>";
	}

}


// 등급정보
function level_info(){

	$level_info[""]['level'] = 10000;
	$level_info[""]['name'] = "전체";
	$level_info["-1"]['level'] = -1;
	$level_info["-1"]['name'] = "구매회원";
	$level_info["0"]['level'] = 0;
	$level_info["0"]['name'] = "관리자";

	$sql = "select * from wiz_level";
	$result = query($sql);

	while($row = sql_fetch_obj($result)){
		$level_info[$row->idx]['level'] = $row->level;
		$level_info[$row->idx]['name'] = $row->name;
	}

	return $level_info;

}

function get_level($lv){

	$sql = "select idx,level,name from wiz_level where idx='".$lv."' ";
	$result = query($sql);
	$row = sql_fetch_obj($result);
	
	return $row->name;

}


//관리자 등급
function twcenter_perm_list($perm) {
	$menucode = array("BASIC"=>"기본설정"
			, "BBS"=>"게시판"
			, "LOG"=>"접속통계"
			, "MEMBER"=>"회원관리"
			, "BANNER"=>"배너관리"
			, "FORMMAIL"=>"폼메일관리"
			, "POLL"=>"설문관리"
			, "SCHEGUAL"=>"스케쥴관리	"
			, "PRODUCT2"=>"상품관리"
			, "PAGE"=>"페이지관리"
			, "PRODUCT"=>"쇼핑몰관리");
	$perm_arr = explode("/", $perm);
	$perm_list = array();
	foreach($perm_arr as $pcode) {
		if($pcode) {
			if($menucode[strtoupper($pcode)]) $perm_list[] = $menucode[strtoupper($pcode)];
			else $perm_list[] = $pcode;
		}
	}
	return $perm_list;
}

// 비방글, 욕설체크
function check_abuse($str){

	global $bbs_info;
	global $poll_info;

	if(!empty($bbs_info)) {
		if($bbs_info['abuse'] == "Y") {
			$abuse_list = explode(",",$bbs_info['abtxt']);
			for($ii=0; $ii < count($abuse_list); $ii++){
				$abuse_list[$ii] = trim($abuse_list[$ii]);
				if(!empty($abuse_list[$ii])){
					if( strpos($str, $abuse_list[$ii]) !== false){
						error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
					}
				}
			}
		}
	}

	if(!empty($poll_info)) {
		if($poll_info['abuse'] == "Y") {
			$abuse_list = explode(",",$poll_info['abtxt']);
			for($ii=0; $ii < count($abuse_list); $ii++){
				$abuse_list[$ii] = trim($abuse_list[$ii]);
				if(!empty($abuse_list[$ii])){
					if( strpos($str, $abuse_list[$ii]) !== false){
						error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
					}
				}
			}
		}
	}

}

// 아이디,닉네임 금지단어
function check_prohibit($str){

	global $mem_info;

	if(!empty($mem_info)) {
		$prohibit_list = explode(",",$mem_info['prohibit_id']);
		for($ii=0; $ii < count($prohibit_list); $ii++){
			$prohibit_list[$ii] = trim($prohibit_list[$ii]);
			if(!empty($prohibit_list[$ii])){
				if( strpos($str, $prohibit_list[$ii]) !== false){
					error("'".$prohibit_list[$ii]."' 단어는 사용하실 수 없습니다.");
				}
			}
		}
	}

}

// 이미지 리사이즈
function img_resize($srcimg, $dstimg, $imgpath, $rewidth, $reheight, $mode=""){
	// $src_info[0] : width, $src_info[1] : height, $src_info[2] : type
	
	$src_info = getimagesize($imgpath."/".$srcimg);

	if($src_info[0] >= 5000) {
		set_time_limit(0);
		@ini_set('memory_limit', '-1');
		@ini_set('gd.jpeg_ignore_warning', 1);
	}
	
	if(!isset($src_info['channels'])) $src_info['channels'] = '';
	if(!strcmp($src_info['channels'], "4")) {
		//echo "<script>alert('현재 업로드하신 이미지는 CMYK 형식입니다. \\n\\n웹상에서 보이지 않을 수 있습니다.');</script>";
	}

	if($rewidth < $src_info[0] || $reheight < $src_info[1] ){

		if(!strcmp($mode, "width")) {

			$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);

		} else {

			if(($src_info[0]-$rewidth) > ($src_info[1]-$reheight)){
				$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);
			}else{
				$rewidth = round(($src_info[0]*$reheight)/$src_info[1]);
			}

		}

	}else{
		copy($imgpath."/".$srcimg,$imgpath."/".$dstimg);
		return;
	}

	if(function_exists("imageCreatetrueColor")) {

		if($src_info[2] == 1){
			$dst = @imageCreatetrueColor($rewidth,$reheight);

			$src = @ImageCreateFromGIF($imgpath."/".$srcimg);
			@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			@Imagejpeg($dst,$imgpath."/".$dstimg,100);

		}else if($src_info[2] == 2){

			$src = @ImageCreateFromJPEG($imgpath."/".$srcimg);

			$exifData = exif_read_data($imgpath."/".$srcimg);
			//0 값 방어용 코드 : rewidth 가 0 이 되면 파라미터 조건오류로 페이탈에러 발생 
			if (empty($rewidth) || $rewidth <= 0 || empty($reheight) || $reheight <= 0) {
			$rewidth  = $src_info[0];
			$reheight = $src_info[1];
			}
			$rewidth  = max(1, (int)round($rewidth));
			$reheight = max(1, (int)round($reheight));
			//
			if(!empty($exifData["Orientation"])){
				switch($exifData['Orientation']) {
					case 3:
						$src = imagerotate($src, 180, 0);
						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					case 6:
						$src = imagerotate($src, -90, 0);

						$oriWidth = $src_info[0];
						$oriHeight = $src_info[1];
						$ratio = $oriHeight / $oriWidth;
						$rewidth = $reheight * $ratio;
						$reheight = $reheight;

						$dst = @imageCreatetrueColor($rewidth,$reheight);

						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					case 8:
						$src = imagerotate($src, 90, 0);

						$oriWidth = $src_info[0];
						$oriHeight = $src_info[1];
						$ratio = $oriHeight / $oriWidth;
						$rewidth = $reheight * $ratio;
						$reheight = $reheight;

						$dst = @imageCreatetrueColor($rewidth,$reheight);

						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					/*
					작업자		: 이상민
					작업일시		: 2019-03-05
					작업내용		: exifdata 의 orientation 값으로 판단하여 회전시키는 영역에서 회전값이 필요 없을때에는 그대로 복제하도록 추가
					*/
					default:
						$src = imagerotate($src, 0, 0);
						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
				}
			} else {
				
				 // 값이 없거나 비정상이면 원본 크기 사용
				if (empty($rewidth) || empty($reheight) || $rewidth <= 0 || $reheight <= 0) {
				    $rewidth = $src_info[0];
				    $reheight = $src_info[1];
				}

				// 안전하게 정수 보정
				$rewidth  = max(1, (int)round($rewidth));
				$reheight = max(1, (int)round($reheight));

				// 다시 한 번 확인
				if ($rewidth <= 0 || $reheight <= 0) {
				    // 오류가 치명적일 경우 로그만 남기고 종료
				    error_log("Fatal: imagecreatetruecolor() invalid size: {$rewidth}x{$reheight}");
				    return;
				}

				$dst = @imageCreatetrueColor($rewidth,$reheight);
				@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			}
			@Imagejpeg($dst,$imgpath."/".$dstimg,100);

		}else if($src_info[2] == 3){

			$src = @ImageCreateFromPNG($imgpath."/".$srcimg);

			$exifData = exif_read_data($imgpath."/".$srcimg);
			
			//0 값 방어용 코드 : rewidth 가 0 이 되면 파라미터 조건오류로 페이탈에러 발생 
			if (empty($rewidth) || $rewidth <= 0 || empty($reheight) || $reheight <= 0) {
			$rewidth  = $src_info[0];
			$reheight = $src_info[1];
			}
			$rewidth  = max(1, (int)round($rewidth));
			$reheight = max(1, (int)round($reheight));
			//
			
			if(!empty($exifData["Orientation"])){
				switch($exifData['Orientation']) {
					case 3:
						$src = imagerotate($src, 180, 0);
						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					case 6:
						$src = imagerotate($src, -90, 0);

						$oriWidth = $src_info[0];
						$oriHeight = $src_info[1];
						$ratio = $oriHeight / $oriWidth;
						$rewidth = $reheight * $ratio;
						$reheight = $reheight;

						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					case 8:
						$src = imagerotate($src, 90, 0);

						$oriWidth = $src_info[0];
						$oriHeight = $src_info[1];
						$ratio = $oriHeight / $oriWidth;
						$rewidth = $reheight * $ratio;
						$reheight = $reheight;

						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
					/*
					작업자		: 이상민
					작업일시		: 2019-03-05
					작업내용		: exifdata 의 orientation 값으로 판단하여 회전시키는 영역에서 회전값이 필요 없을때에는 그대로 복제하도록 추가
					*/
					default:
						$src = imagerotate($src, 0, 0);
						$dst = @imageCreatetrueColor($rewidth,$reheight);
						@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
						@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
						break;
				}
			} else {

				/* 2021-05-17 투명도 관련 수정 기존 소스
				$dst = @imageCreatetrueColor($rewidth,$reheight);
				@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
				@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
				*/
				
				$dst = @imageCreatetrueColor($rewidth,$reheight);

				@imagealphablending($dst, false);
				@imagesavealpha($dst,true);
				
				$transparent = @imagecolorallocatealpha($dst, 255, 255, 255, 127);
				@imagefilledrectangle($dst, 0, 0, $rewidth, $reheight, $transparent); 

				//@imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));//투명도 부분 색 지정
				@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			
			}
			@Imagepng($dst,$imgpath."/".$dstimg);

		}else{

			copy($imgpath."/".$srcimg,$imgpath."/".$dstimg);

		}

		@imageDestroy($src);
		@imageDestroy($dst);

	} else {

			link($imgpath."/".$srcimg,$imgpath."/".$dstimg);

	}

}

// 파일이 이미지인지
function img_type($srcimg){
	if(is_file($srcimg)){

		$image_info = getimagesize($srcimg);
		switch ($image_info['mime']) {
			case 'image/gif': return true; break;
			case 'image/jpeg': return true; break;
			case 'image/png': return true; break;
			case 'image/x-ms-bmp': return true; break;
			default : return false; break;
		}
	}else{
		return false;
	}

}

// 페이지 리스트 출력
function print_pagelist($page, $list_amount, $page_count, $param, $page_type = ""){

   global $code, $catcode, $orderby, $skin_dir, $ptype;
   
   if($list_amount <= 0 ) $list_amount = 10; 

   if($skin_dir == "") $skin_dir = "/twcenter/manage";
   if($param != "") $param = "&".$param;

	if(($page%$list_amount) == 0) $tmp = $page-1;
	else $tmp = $page;

	$spage = floor($tmp/$list_amount)*$list_amount+1;
	if($spage <= 1) $ppage = 1;
	else $ppage = $spage - $list_amount;

	$epage = $spage+$list_amount-1;
	if($epage >= $page_count){
		$epage = $page_count;
		$npage = $page_count;
	}else{
		$npage = $epage + 1;
	}

	if(!empty($page_type)) {
		$page_name = strtolower($page_type)."page";
		if($page_type == "C") $param .= "&ptype=view";
	} else {
		$page_name = "page";
	}
	
	if(!isset($param)) $param = '';
	$param = preg_replace('/page=[0-9]&/','',$param);
	if($epage > 0) {

		echo "    <div class='page_num'><ul class='pagination'>\n";
		echo "                     <li class='arrow'><a href='$PHP_SELF?$page_name=1$param'>처음<span class='material-symbols-outlined'>keyboard_double_arrow_left</span></a></li>\n";
		echo "                     <li class='arrow'><a href='$PHP_SELF?$page_name=$ppage$param'>이전<span class='material-symbols-outlined'>keyboard_arrow_left</span></a></li>\n";
		for($spage; $spage <= $epage; $spage++){
		if($page == $spage) echo "                     <li><a href='javascript:;' class='active'>$spage</a></li>\n";
		else                echo "                     <li><a href='$PHP_SELF?$page_name=$spage$param'> $spage </a></li>\n";
		}
		echo "                     <li class='arrow'><a href='$PHP_SELF?$page_name=$npage$param'>다음<span class='material-symbols-outlined'>keyboard_arrow_right</span></a></li>\n";
		echo "                     <li class='arrow'><a href='$PHP_SELF?$page_name=$page_count$param'>맨끝<span class='material-symbols-outlined'>keyboard_double_arrow_right</span></a></li>\n";
		echo "                   </ul></div>\n";

/*		echo "    <ul class='page_navi'>";
		echo "      <li><a href='$PHP_SELF?ptype=$ptype&$page_name=1$param'>«</a></li>";
		echo "      <li><a href='$PHP_SELF?ptype=$ptype&$page_name=$ppage$param'>‹</a></li>";
		for($spage; $spage <= $epage; $spage++){
		  if($page == $spage) echo "<li><a href='javascript:;' class='active'>$spage</a></li>";
		  else echo "<li><a href='$PHP_SELF?ptype=$ptype&$page_name=$spage$param'> $spage </a></li>";
		}
		echo "      <li><a href='$PHP_SELF?ptype=$ptype&$page_name=$npage$param'>›</a></li>";
		echo "      <li><a href='$PHP_SELF?ptype=$ptype&$page_name=$page_count$param'>»</a></li>";
		echo "    </ul>";
*/
	}

}

// 게시판 저장
/*function save_bbs($code, $name, $email, $subject, $ctype, $content, $passwd=""){

  global $DOCUMENT_ROOT;
  global $upfile1, $upfile1_size, $upfile1_name;
  global $upfile2, $upfile2_size, $upfile2_name;
  global $upfile3, $upfile3_size, $upfile3_name;

	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = query($sql);
	if($row = sql_fetch_obj($result)){
		$prino = $row->prino+1;
	}

	$upfile_idx = date('Ymdhis').rand(1,9);
	if(!is_dir($_SERVER['DOCUMENT_ROOT']."/wizhome/bbs/upfile/$code")){
		echo exec("mkdir $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code");
		exec("chmod 705 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code");
	}

	if($upfile1_size > 0){
		$upfile1_tmp = $upfile_idx.".".substr($upfile1_name,-3);
		exec("cp $upfile1 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile1_tmp");
		exec("chmod 606 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile1_tmp");
	}
	if($upfile2_size > 0){
		$upfile2_tmp = $upfile_idx.".".substr($upfile2_name,-3);
		exec("cp $upfile2 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile2_tmp");
		exec("chmod 606 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile2_tmp");
	}
	if($upfile3_size > 0){
		$upfile3_tmp = $upfile_idx.".".substr($upfile3_name,-3);
		exec("cp $upfile3 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile3_tmp");
		exec("chmod 606 $_SERVER['DOCUMENT_ROOT']/wizhome/bbs/upfile/$code/$upfile3_tmp");
	}

	$authkey = rand(0,999);
	$content = str_replace("'","",$content);
	$content = str_replace("\"","",$content);

	$sql_com = "";
	$sql_com .= " code                 = '".$code."'               ";
	$sql_com .= " , prino              = '".$prino."'              ";
	$sql_com .= " , depno              = ''                        ";
	$sql_com .= " , notice             = ''                        ";
	$sql_com .= " , name               = '".$name."'               ";
	$sql_com .= " , email              = '".$email."'              ";
	$sql_com .= " , subject            = '".$subject."'            ";
	$sql_com .= " , content            = '".$content."'            ";
	$sql_com .= " , ctype              = '".$ctype."'              ";
	$sql_com .= " , upfile             = '".$upfile1_tmp."'        ";
	$sql_com .= " , upfile2            = '".$upfile2_tmp."'        ";
	$sql_com .= " , upfile3            = '".$upfile3_tmp."'        ";
	$sql_com .= " , upfile_name        = '".$upfile1_name."'       ";
	$sql_com .= " , upfile2_name       = '".$upfile2_name."'       ";
	$sql_com .= " , upfile3_name       = '".$upfile3_name."'       ";
	$sql_com .= " , wdate              = now()                     ";
	$sql_com .= " , passwd             = '".$passwd."'             ";
	$sql_com .= " , authkey            = '".$authkey."'            ";

	$sql = "insert into wiz_bbs set {$sql_com} ";
	query($sql);

} */

function info_replace($site_info, $re_info, $msg, $order_info = ""){

	global $_http_host, $mem_info;

	$date = date('Y')."년 ".date('m')."월 ".date('d')."일";
	$msg = str_replace("{DATE}",       $date, $msg);

	if(!isset($re_info['id'])) $re_info['id'] = '';
	$msg = str_replace("{MEM_ID}",     $re_info['id'], $msg);

	if(!isset($re_info['passwd'])) $re_info['passwd'] = '';
	$msg = str_replace("{MEM_PW}",     $re_info['passwd'], $msg);

	if(!isset($re_info['name'])) $re_info['name'] = '';
	$msg = str_replace("{MEM_NAME}",   $re_info['name'], $msg);

	if(!isset($site_info['site_name'])) $site_info['site_name'] = '';
	$msg = str_replace("{SITE_NAME}",  $site_info['site_name'], $msg);

	if(!isset($site_info['site_email'])) $site_info['site_email'] = '';
	$msg = str_replace("{SITE_EMAIL}", $site_info['site_email'], $msg);

	if(!isset($site_info['site_tel'])) $site_info['site_tel'] = '';
	$msg = str_replace("{SITE_TEL}",   $site_info['site_tel'], $msg);

	$msg = str_replace("{SITE_URL}",   "http://".$_http_host, $msg);

	$msg = str_replace("{LINK}",       "http://".$_http_host."/".$mem_info['login_url'], $msg);

	if(!isset($site_info['com_owner'])) $site_info['com_owner'] = '';
	$msg = str_replace("{SITE_OWNER}", $site_info['com_owner'], $msg); //대표자명

	if(!isset($site_info['com_num'])) $site_info['com_num'] = '';
	$msg = str_replace("{SITE_NUM}", $site_info['com_num'], $msg); //사업자 등록번호

	if(!isset($site_info['com_address'])) $site_info['com_address'] = '';
	$msg = str_replace("{SITE_ADDRESS}", $site_info['com_address'], $msg); //주소

	if(!isset($site_info['com_fax'])) $site_info['com_fax'] = '';
	$msg = str_replace("{SITE_FAX}", $site_info['com_fax'], $msg); //팩스

	if(!isset($site_info['site_name'])) $site_info['site_name'] = '';
	$msg = str_replace("{SHOP_NAME}",  $site_info['site_name'], $msg);

	if(!isset($site_info['site_email'])) $site_info['site_email'] = '';
	$msg = str_replace("{SHOP_EMAIL}", $site_info['site_email'], $msg);

	if(!isset($site_info['site_tel'])) $site_info['site_tel'] = '';
	$msg = str_replace("{SHOP_TEL}",   $site_info['site_tel'], $msg);

	$msg = str_replace("{SHOP_URL}",   "http://".$_http_host, $msg);

	if(!isset($order_info)) $order_info = '';
	$msg = str_replace("{ORDER_INFO}", $order_info, $msg);

	if(!isset($re_info['as_day'])) $re_info['as_day'] = '';
	$msg = str_replace("{FORE_DATE}",  $re_info['as_day'], $msg);
	
	if(!isset($re_info['as_p'])) $re_info['as_p'] = '';
	$msg = str_replace("{RELE_DATE}",  $re_info['as_p'], $msg);
	
	if(!isset($re_info['as_q'])) $re_info['as_q'] = '';
	$msg = str_replace("{TRANS_NUM}",  $re_info['as_q'], $msg);
	
	if(!isset($re_info['as_o_8'])) $re_info['as_o_8'] = '';
	$msg = str_replace("{AS_PRICE}",   $re_info['as_o_8'], $msg);
	
	if(!isset($re_info['dormancy_date'])) $re_info['dormancy_date'] = '';
	$msg = str_replace("{DORMANCY_DATE}", $re_info['dormancy_date'], $msg);
	
	if(!isset($re_info['del_date'])) $re_info['del_date'] = '';
	$msg = str_replace("{DEL_DATE}", $re_info['del_date'], $msg);
	
	if(!isset($re_info['cert_chk'])) $re_info['cert_chk'] = '';
	$msg = str_replace("{CERT_NUMBER}", $re_info['cert_chk'], $msg);
	
	return $msg;

}

function site_replace($site_info, $re_info, $msg){

	global $_http_host;

	$date = date('Y')."년 ".date('m')."월 ".date('d')."일";

	$msg = str_replace("{SITE_URL}", "http://".$_http_host, $msg);

	return $msg;

}

// 이메일 발송
function send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content, $cc="", $bcc=""){

	$charset  = "utf-8"; 

	$se_name   = "=?$charset?B?" . base64_encode($se_name) . "?=";
	$subject = "=?$charset?B?" . base64_encode($subject) . "?=";

	$header  = "Return-Path: <$se_email>\n";
	$header .= "From: $se_name <$se_email>\n";
	$header .= "Reply-To: <$se_email>\n";
	if ($cc)  $header .= "Cc: $cc\n";
	if ($bcc) $header .= "Bcc: $bcc\n";
	$header .= "MIME-Version: 1.0\n";

	$header .= "Content-Type: TEXT/HTML; charset=$charset\n";
	$header .= "Content-Transfer-Encoding: base64 \r\n";

	$content = chunk_split(base64_encode($content));

	$result = @mail($re_email, $subject, $content, $header, "-f".$se_email);

	if(!$result) {
		$errlog = array("send_date"=>date("Y-m-d H:i:s"), "ip"=>$_SERVER['REMOTE_ADDR'], "re_name"=>$re_name, "re_email"=>$re_email, "subject"=>$subject); 
		@make_log(LOG_PATH."mail_err.log", $errlog);
	}

	return $result;

}

// icode-token (2017-08-18 추가)
function get_icode_info($token_key)
{
	$icode_url = "https://icodekorea.com/res/coin2.php?token_key={$token_key}";
	$coins       = get_coins($icode_url, $token_key);
	$recv_result = explode('-#^#-', $coins);
	//print_r($recv_result);
	$icode_info = array(
		'coin'		=> $recv_result[0], //잔액
		'send_cnt'	=> $recv_result[1], //발송가능건수
		'fee'		=> $recv_result[2], //건당요금
	);

	return $icode_info;
}

function get_coins($url, $token_key){

	$data = array(
		'token_key' => $token_key
	);

	foreach ($data as $n => $v) {
		$send_data[] = "$n=$v";
	}
	$send_data = implode('&', $send_data);

	$url = parse_url($url);

	$host = $url['host'];
	$path = $url['path'];

	$fp = fsockopen ($host, 80, $errno, $errstr, 10.0);
	if (!$fp)
	{
		die("$errstr ($errno)\n");
	}
	else
	{
		fputs($fp, "POST $path HTTP/1.1\r\n");
		fputs($fp, "Host: $host\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: " . strlen($send_data) . "\r\n");
		fputs($fp, "Connection:close" . "\r\n\r\n");
		fputs($fp, $send_data);

		$result = "";
		while(!feof($fp)) {
			$result .= fgets($fp, 128);
		}

		fclose($fp);

		$result  = explode("\r\n\r\n", $result, 2);
		$header  = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';

		return $content;

	}

}

// SMS 발송
function send_sms($send_num, $recv_num, $message, $send_name="", $mcode="", $send_type="") {

	global $site_info;

	if($site_info['sms_token'] != ""){ //토큰이 있을 때

		if(PHP_VERSION >= '5.2.0') {
			include(WAY_PATH.'/comm/sms/icode/utf8_5/conf/config.php');
			include_once(WAY_PATH.'/comm/sms/icode/utf8_5/component.php');
		} else {
			include(WAY_PATH.'/comm/sms/icode/utf8_4/conf/config.php');
			include_once(WAY_PATH.'/comm/sms/icode/utf8_4/component.php');
		}

		$SMS = new SMS;		/* SMS 모듈 클래스 생성 */
		$SMS->SMS_con($socket_host,$socket_port,$icode_key);		/* 아이코드 서버 접속 */

		/**
		 * 문자발송 Form을 사용하지 않고 자동 발송의 경우 수신번호가 1개일 경우 번호 마지막에 ";"를 붙인다
		 * ex) $strTelList = "0100000001;";
		*/
		if(strpos($recv_num, ";") === false) {
			$recv_num = $recv_num.";";
		}
		$recv_num = str_replace(",", ";", $recv_num);
		
		$re_num         = str_replace("-", "", $recv_num);
		$se_num         = str_replace("-", "", $send_num);

		$strTelList     = $re_num;					/* 수신번호 : 01000000001;0100000002; */
		$strCallBack    = $se_num;					/* 발신번호 : 0317281281 */
		$strSubject     = $_POST["strSubject"];		/* LMS제목  : LMS발송에 이용되는 제목( component.php 60라인을 참고 바랍니다. */
		$strData        = $message;					/* 메세지 : 발송하실 문자 메세지 */

		$chkSendFlag    = $_POST["chkSendFlag"];	/* 예약 구분자 : 0 즉시전송, 1 예약발송 */
		$R_YEAR         = $_POST["R_YEAR"];         /* 예약 : 년(4자리) 2016 */
		$R_MONTH        = $_POST["R_MONTH"];        /* 예약 : 월(2자리) 01 */
		$R_DAY          = $_POST["R_DAY"];          /* 예약 : 일(2자리) 31 */
		$R_HOUR         = $_POST["R_HOUR"];         /* 예약 : 시(2자리) 02 */
		$R_MIN          = $_POST["R_MIN"];          /* 예약 : 분(2자리) 59 */

		$strDest	    = explode(";",$strTelList);
		$nCount		    = count($strDest)-1;		// 문자 수신번호 갯수
		// 예약설정을 합니다.
		if ($chkSendFlag) $strDate = $R_YEAR.$R_MONTH.$R_DAY.$R_HOUR.$R_MIN;
		else $strDate = "";

		// 문자 발송에 필요한 항목을 배열에 추가
		$result = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $nCount, $strDate);
		// 패킷 정의의 결과에 따라 발송여부를 결정합니다.
		if ($result) {

			// 패킷이 정상적이라면 발송에 시도합니다.
			$result = $SMS->Send();

			if ($result) {

				$success = $fail = $kk = 0;
				$isStop = 0;
				foreach($SMS->Result as $result) {

					list($phone,$code)=explode(":",$result);
					if (substr($code,0,5)=="Error") {

						/*switch (substr($code,6,2)) {
							case '17':	 // "07: 발송대기 처리. 지연해소시 발송됨."
								echo json_encode(json_result("01", "일시적인 지연으로 인해 발송대기 처리되었습니다."));
								exit;
								break;
							case '23':	 // "23:데이터오류, 전송날짜오류, 발신번호미등록"
								echo json_encode(json_result("01", "데이터를 다시 확인해 주시기바랍니다."));
								exit;
								break;
							// 아래의 사유들은 발송진행이 중단됨.
							case '85':	 // "85:발송번호 미등록"
								echo json_encode(json_result("01", "등록되지 않는 발송번호 입니다."));
								exit;
								break;
							case '87':	 // "87:인증실패"
								echo json_encode(json_result("01", "인증실패입니다. 계약여부확인이 필요합니다."));
								break;
							case '88':	 // "88:연동모듈 발송불가"
								echo json_encode(json_result("01", "연동모듈 사용이 불가능합니다. 아이코드로 문의하세요."));
								exit;
								break;

							case '96':	 // "96:토큰 검사 실패"
								echo json_encode(json_result("01", "사용할 수 없는 토큰키입니다."));
								exit;
								break;
							case '97':	 // "97:잔여코인부족"
								echo json_encode(json_result("01", "잔여코인이 부족합니다."));
								exit;
								break;
							case '98':	 // "98:사용기간만료"
								echo json_encode(json_result("01", "사용기간이 만료되었습니다."));
								exit;
								break;
							case '99':	 // "99:인증실패"
								echo json_encode(json_result("01", "서비스 사용이 불가능합니다. 아이코드로 문의하세요."));
								exit;
								break;
							default:	 // "미 확인 오류"
								echo json_encode(json_result("01", "알 수 없는 오류로 전송이 실패하었습니다."));
								exit;
								break;
						}*/

						$fail++;

					} else {
						//echo $phone."로 전송했습니다. (msg seq : ".$code.")<br>";
						$renum  = explode(";",$recv_num);
						$re_num = hyphen_phone($renum[$kk]);

						/* -- ------------------------------------------------------------------------------------------- -- *\
						 * SMS / MMS 발송처리
						 * SMS LOG를 남기길 원하는 업체있을시 활성화시킴
						\* -- ------------------------------------------------------------------------------------------- -- */
						/*$date = date("YmdHis");
						$_gData = sql_fetch(" SELECT id,name,hphone FROM wiz_member WHERE hphone = '$re_num'" );

						$srecvid   = $_gData['id'];
						$srecvname = $_gData['name'];
						
						$MSG_BODY = $strData;
						$sm_type  = ($mcode == 'DirectSend') ? 'D' : 'S';

						$sql_com = "";
						$sql_com .= " set sm_time          = '" . $date . "'                      ";
						$sql_com .= ", sm_recvphone        = '" . $re_num. "'                     ";
						$sql_com .= ", sm_recvid           = '" . $srecvid. "'                    ";
						$sql_com .= ", sm_recvname         = '" . $srecvname. "'                  ";
						$sql_com .= ", sm_sendphone        = '" . $send_num . "'                  ";
						$sql_com .= ", sm_sendid           = '" . $site_info['site_tel'] . "'     ";
						$sql_com .= ", sm_sendname         = '" . $site_info['site_name'] . "'    ";
						$sql_com .= ", sm_message          = '" . $MSG_BODY . "'                  ";
						$sql_com .= ", sm_type             = '" . $sm_type . "'                   ";
						$sql_com .= ", MsgSeq              = '" . $code . "'                      ";

						query(" INSERT INTO wiz_sms_log {$sql_com} ");
						*/
						$kk++;
						$success++;

					}

				}

				$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.

				if($send_type != "") {
					echo json_encode(json_result("00", "전송이 완료되었습니다."));
					exit;
				}

			} else {
				if($send_type != "") {
					echo json_encode(json_result("01", "SMS 서버와 통신이 불안정합니다."));
					exit;
				}
			}
		}
	}else if($site_info['sms_token'] == "" && $site_info['sms_id'] !="" && $site_info['sms_pw'] !=""){ //토큰이 없고 아이디와 비번만 있는경우 실행
		global $DOCUMENT_ROOT, $sms_con, $oper_info, $site_info;

	if($oper_info == null) {
		include (WAY_PATH."/twcenter/inc/oper_info.php");
	}
	/**************************************************************************************
		SMS 클래스 사용 예제입니다.
	**************************************************************************************/
	include_once (WAY_PATH."/twcenter/inc/class.sms.php");

	$sms_server	= "211.172.232.124";	## SMS 서버
	$sms_id		= $site_info['sms_id'];				## icode 아이디
	$sms_pw		= $site_info['sms_pw'];				## icode 패스워드
	//$portcode	= 1;				## 정액제 : 2, 충전식 : 1
	if($site_info['sms_type'] == "" || $site_info['sms_type'] == "C") $portcode = 1;
	else if($site_info['sms_type'] == "J") $portcode = 2;

	$SMS	= new SMS;
	$SMS->SMS_con($sms_server,$sms_id,$sms_pw,$portcode);

	/**************************************************************************************
	1단계: 보낼 메시지를 저장합니다. 쇼핑몰에서 장바구니에 물건을 담는다고 생각하면 됩니다.

		일반 메시지를 보낼 경우 SMS->Add() 를 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. 보내는 사람 전화 (회신번호)
			3. 보내는 사람 이름
			4. 보내는 메시지 (80자 이내)
			5. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		URL을 보낼 경우 SMS->AddURL() 을 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. URL (50자 이내)
			3. 보내는 메시지 (80자 이내)
			4. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		잘못된 값이 들어갔을 경우 에러메시지가 리턴됩니다.

		※ .URL 콜백의 경우 건당 50원의 요금이 부과 됩니다.
		※ .SKT(011,017) 번호로 발송하실 경우 사용자 동의를 받지 않아 전송 실패일 경우에도
		    정상적으로 요금이 청구 됩니다.
		※ .KTF(016,018) 번호로 발송하실 경우 회신번호를 반드시 입력하셔야 정상적으로 송신이 됩니다.
	**************************************************************************************/

	$tran_phone	= str_replace("-", "", $recv_num);		# 수신번호
	$tran_callback	= str_replace("-", "", $send_num);			# 회신번호
	$tran_msg		= iconv("utf-8", "euc-kr", $message);	# 발송 메세지
	$tran_date	= "";				#발송시간
	#즉시 전송일 경우 $tran_date	= "" ;
	#예약 전송일 경우 $tran_date	= "200412312359";	# 2004년 12월 31일 23시 59분

	$result = $SMS->Add($tran_phone,"$tran_callback","$sms_id","$tran_msg","$tran_date");
	//if ($result) echo $result; else echo "일반메시지 입력 성공<BR>";

	//$result = $SMS->AddURL($tran_phone,"$tran_callback","w.yahoo.co.kr","테스트입니다","");
	//if ($result) echo $result; else echo "URL 입력 성공<BR>";
	//echo "<HR>";

	/**************************************************************************************
	2단계: 저장해둔 메시지를 전송합니다. 쇼핑몰에서 결제를 한다고 생각하면 됩니다.

		SMS->Send() 를 실행하면 모아둔 메시지를 모두 발송합니다.
		이때 SMS->Send()가 리턴하는 값은 true, false 입니다.
		이것은 서버와의 접속 상태를 나타냅니다.

		SMS->Send() 를 실행하고 난 후에는 메시지 발송 결과를 조회할 수 있습니다.
		메시지 발송 결과는 SMS->Result 배열에 저장되어 있습니다.
		데이타 형식은 "핸드폰 번호 : 메시지 고유번호" 입니다. 예) 0115511474:13622798
		전송이 제대로 되지 않은 건에 대해서는 에러 표시가 납니다. 예) 0195200107:Error

		만약 같은 클래스를 재사용할 경우, SMS->Init() 명령으로 메시지 발송 결과를 없애주십시오.
	**************************************************************************************/

	$result = $SMS->Send();
	if ($result) {
		//echo "SMS 서버에 접속했습니다.<br>";
		$success = $fail = 0;
		foreach($SMS->Result as $result) {
			list($phone,$code)=explode(":",$result);
			if ($code=="Error") {
				//echo $phone.'로 발송하는데 에러가 발생했습니다.<br>';
				$fail++;
			} else {
				//echo $phone."로 전송했습니다. (메시지번호:".$code.")<br>";
				$success++;
			}
		}
		//echo $success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br>";
		$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
		//echo json_encode(json_result("00", "전송이 완료되었습니다."));
		return $success;
	} else {
		return "error";
		//echo "에러: SMS 서버와 통신이 불안정합니다.<br>";
	}

	//echo "<table width='100%'><tr><td align='center'><span onClick='self.close()' style='cursor:pointer'>[닫기]</span></td></tr></table>";
	$SMS = null;
	}
}

// 메일내용 생성
function send_mailsms($type, $re_info, $ordmail="", $rtype=""){

	global $site_info;

	// 관리자 정보 가져오기
	include WIZHOME_PATH."/inc/site_info.php";

	$se_name  = $site_info['site_name'];
	$se_email = $site_info['site_email'];
	$se_tel   = $site_info['site_tel'];
	$se_hand  = $site_info['site_hand'];

	// 메일/sms 발송내용 가져오기
	$mail_info = get_table("wiz_mailsms", "code = '$type'");

	$mail_info['email_subj'] = info_replace($site_info, $re_info, $mail_info['email_subj']);
	$mail_info['email_msg']  = info_replace($site_info, $re_info, $mail_info['email_msg'], $ordmail);
	$mail_info['sms_msg']    = info_replace($site_info, $re_info, $mail_info['sms_msg']);
	$mail_info['email_msg']  = stripslashes($mail_info['email_msg']);

	//$mail_info['sms_msg']    = iconv("utf-8","euc-kr",$mail_info['sms_msg']);

	if($rtype == "email" || $rtype == "id" || $rtype == "E" || $rtype == "") {
		if($mail_info['email_send'] == "Y"){
			send_mail($se_name, $se_email, $re_info['name'], $re_info['email'], $mail_info['email_subj'], $mail_info['email_msg']);
		}
		if($mail_info['email_oper'] == "Y"){
			send_mail($se_name, $se_email, $se_name, $se_email, $mail_info['email_subj'], $mail_info['email_msg']);
		}
	}

	if($rtype == "" || $rtype == "S") {
		if($mail_info['sms_send'] == "Y"){
			send_sms($se_tel, $re_info['hphone'], $mail_info['sms_msg'], $se_name);
		}
		if($mail_info['sms_oper'] == "Y"){
			send_sms($se_tel, $se_hand, $mail_info['sms_msg'], $se_name);
		}
	}
}


// 중복로그인 방지 세션삭제
function del_session($id){

	$sess_path = WIZHOME_PATH."/data/session";
	$dirlist = opendir($sess_path);

	while($file = readdir($dirlist)){
		echo $file;
		if ($file != "." && $file != "..") {
			$sline = file($sess_path."/".$file,"r");
			$slist = explode(";",$sline[0]);
			$slist = explode(":",$slist[1]);
			if ($slist[2] == "\"".$id."\"") unlink($sess_path."/".$file);
		}
	}

}


// 포인트 저장
function save_point($ptype, $memid, $mode = "", $bidx = "", $cidx = "", $midx = ""){

	global $code;
	global $wiz_session;
	global $mem_level;

	include WIZHOME_PATH."/inc/site_info.php";
	include WIZHOME_PATH."/inc/bbs_info.php";
	$save_chk = false;

	if($mem_level != "0" && !strcmp($site_info['point_use'], "Y") && !empty($memid)) {

		$mem_point = get_point($memid);

		$save = "Y";

		if(!strcmp($ptype, "JOIN")) {
			$point = $site_info['join_point'];
			$memo = "회원가입 포인트";
		}

		if(!strcmp($ptype, "LOGIN")) {
			$point = $site_info['login_point'];
			$memo = "로그인 포인트";

			$sql = "
				select count(idx) as cnt 
				  from wiz_point 
				 where memid = '$memid' 
				   and ptype = 'LOGIN' 
				   and DATE_FORMAT(wdate, '%Y%m%d') = '".date('Ymd')."'
			";
			$result = query($sql);
			$row = sql_fetch_arr($result);

			$login_cnt = $row['cnt'];

			if($login_cnt > 0) $save = "N";

		}

		if(!strcmp($ptype, "MSG")) {
			$point = $site_info['msg_point'];
			$memo = "쪽지 보내기 포인트";
		}

		if(!strcmp($ptype, "BBS")) {
			$sql_point = "select count(*) as cnt from wiz_point where ptype='$ptype' and mode='$mode' and memid='$memid' and bidx='$bidx'";
			$row_point = sql_fetch($sql_point);
			if($row_point['cnt'] > 0) $save_chk = true;

			if(!strcmp($mode, "view")) {
				$point = $bbs_info['view_point'];
				$memo = "게시판 보기 포인트";
			}
			if(!strcmp($mode, "write")) {
				$point = $bbs_info['write_point'];
				$memo = "게시판 글쓰기 포인트";

			}
			if(!strcmp($mode, "down")) {
				$point = $bbs_info['down_point'];
				$memo = "게시판 다운로드 포인트";
			}
			if(!strcmp($mode, "recom")) {
				$point = $bbs_info['recom_point'];
				$memo = "게시판 추천 포인트";
			}
		}

		if(!strcmp($ptype, "COMMENT")) {
			$sql = "select code from wiz_bbs where idx = '$bidx'";
			$result = query($sql);
			$row = sql_fetch_arr($result);
			$code = $row['code'];

			include WIZHOME_PATH."/inc/bbs_info.php";

			$point = $bbs_info['comment_point'];
			$memo = "게시판 덧글 포인트";
		}

		if($mem_point + $point < 0) {
			$save = "N";
			error($bbs_info['point_msg']);
		}

		if(!strcmp($site_info['point_use'], "Y") && !strcmp($save, "Y") && $point != 0 && $save_chk == false) {

			$sql_com = "";
			$sql_com .= " bidx                 = '".$bidx."'               ";
			$sql_com .= " , cidx               = '".$cidx."'               ";
			$sql_com .= " , midx               = '".$midx."'               ";
			$sql_com .= " , ptype              = '".$ptype."'              ";
			$sql_com .= " , mode               = '".$mode."'               ";
			$sql_com .= " , memid              = '".$memid."'              ";
			$sql_com .= " , point              = '".$point."'              ";
			$sql_com .= " , memo               = '".$memo."'               ";
			$sql_com .= " , wdate              = now()                     ";

			$sql = "insert into wiz_point set {$sql_com} ";
			query($sql);

		}

	}
}

// 포인트 삭제
function delete_point($ptype, $memid, $mode = "", $bidx = "", $cidx = "", $midx = ""){

	include WIZHOME_PATH."/inc/site_info.php";

	if(!strcmp($site_info['point_use'], "Y")) {
		if(!strcmp($ptype, "BBS")) {

			$where_sql = " and bidx = '$bidx' and mode = '$mode' ";
			if(!strcmp($mode, "view")) $memo = "게시글 보기 포인트 삭제";
			if(!strcmp($mode, "write")) $memo = "게시글 삭제";
			if(!strcmp($mode, "down")) $memo = "게시글 다운로드 포인트 삭제";

		} else if(!strcmp($ptype, "COMMENT")) {

			$where_sql = " and cidx = '$cidx' ";
			$memo = "덧글 삭제";

		} else if(!strcmp($ptype, "MSG")) {

			$where_sql = " and midx = '$midx' ";
			$memo = "쪽지 삭제";

		}

		$sql = "select point from wiz_point where memid = '$memid' $where_sql";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row['point'] > 0) $point = "-".$row['point'];
		else $point = abs($row['point']);

		if(!empty($memid) && $point != 0) {

			$sql_com = "";
			$sql_com .= " bidx                 = '".$bidx."'               ";
			$sql_com .= " , cidx               = '".$cidx."'               ";
			$sql_com .= " , midx               = '".$midx."'               ";
			$sql_com .= " , ptype              = '".$ptype."'              ";
			$sql_com .= " , mode               = '".$mode."'               ";
			$sql_com .= " , memid              = '".$memid."'              ";
			$sql_com .= " , point              = '".$point."'              ";
			$sql_com .= " , memo               = '".$memo."'               ";
			$sql_com .= " , wdate              = now()                     ";

			$sql = "insert into wiz_point set {$sql_com} ";
			query($sql);

		}
	}
}

// 회원 포인트
function get_point($memid){

	include WIZHOME_PATH."/inc/site_info.php";

	if(!strcmp($site_info['point_use'], "Y")) {
		if($memid == "") return 0;
		else {
			$sql = "select sum(point) as total_point from wiz_point where memid = '$memid' and memid != ''";
			$result = query($sql);
			$row = sql_fetch_arr($result);

			return $row['total_point'];
		}
	}
}

// 포인트 체크 포인트가 부족할때 false 충분할때 true
function check_point($memid, $point){

	global $wiz_session;
	global $mem_level;

	include WIZHOME_PATH."/inc/site_info.php";

	if ($mem_level != "0" && !strcmp($site_info['point_use'], "Y") && $wiz_session['level'] > 0) {
		$mem_point = get_point($memid);
		if($mem_point + $point < 0) return false;
		else return true;
	} else {
		return true;
	}
}

// 파일 확장자 체크
function file_check($filename, $file_str = "php|htm|html|inc|htm|shtm|ztx|dot|cgi|pl|phtm|ph|exe|php3|php4|php5|phtml|dll"){

	$fnames = explode(".", $filename);
	$fext = $fnames[count($fnames)-1];
	$fext = strtolower($fext);
	$file_str = strtolower($file_str);

	//업로드 금지 확장자 체크 (6버전이후 eregi함수는 사라짐)
	if(preg_match("/".$file_str."/", $fext)) {
		error("해당 파일은 업로드할 수 없는 형식입니다.");
		exit;
	}
}

//파일 확장자 체크 블랙리스트 => 화이트리스트
function upload_file_check($filename, $file_str = "jpg|jpeg|gif|png|hwp|ppt|pptx|doc|docx|xls|xlsx|pdf|txt|zip"){

	$fnames = explode(".", $filename);
	$fext = $fnames[count($fnames)-1];
	$fext = strtolower($fext);
	$file_str = strtolower($file_str);

	//업로드 금지 확장자 체크 (6버전이후 eregi함수는 사라짐)
	if(!preg_match("/".$file_str."/", $fext)) {
		error("해당 파일은 업로드할 수 없는 형식입니다.");
		exit;
	}
}

function ImageResize_Upload_check($filename)
{
	$allowed_ext = array("jpg","jpeg","gif","png");
	$fnames      = array_pop(explode('.', $filename));
	$ext         = strtolower($fnames);

	if(!in_array($ext, $allowed_ext)){
		error("해당 파일은 업로드할 수 없는 형식입니다.");
		exit;
	}
}

function return_bytes($val) {
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}

	return $val;
}

// 연결 페이지 업데이트
function update_page($type){

	global $code;
	global $catcode;
	global $PHP_SELF;

	if(strpos($PHP_SELF,"/manage/") == false) {

		switch ($type) {
			case "MEM_JOIN"		: $table = "wiz_meminfo";  $field = "join_url"; break;
			case "MEM_LOGIN"	: $table = "wiz_meminfo";  $field = "login_url"; break;
			case "MEM_IDPW" 	: $table = "wiz_meminfo";  $field = "idpw_url"; break;
			case "MEM_INFO" 	: $table = "wiz_meminfo";  $field = "myinfo_url"; break;
			case "MESSAGE" 		: $table = "wiz_siteinfo"; $field = "msg_url"; break;
			case "POINT" 		: $table = "wiz_siteinfo"; $field = "point_url"; break;
			case "SCH" 			: $table = "wiz_bbsinfo";  $field = "pageurl"; break;
			case "BBS" 			: $table = "wiz_bbsmain";  $field = "purl"; break;
			case "PRD" 			: $table = "wiz_category"; $field = "purl"; break;
			case "POLL" 		: $table = "wiz_pollinfo"; $field = "purl"; break;
			case "SEARCH"		: $table = "wiz_siteinfo"; $field = "search_url"; break;

			case "BASKET"		: $table = "wiz_prdinfo";  $field = "basket_url"; break;
			case "ORDER"		: $table = "wiz_prdinfo";  $field = "order_url"; break;
			case "ORDER_LIST"	: $table = "wiz_prdinfo";  $field = "order_list_url"; break;
			case "PRDSEARCH"	: $table = "wiz_prdinfo";  $field = "search_url"; break;
			case "WISHLIST"		: $table = "wiz_prdinfo";  $field = "wish_url"; break;

			case "_MEM_JOIN"	: $table = "wiz_meminfo";  $field = "m_join_url"; break;
			case "_MEM_LOGIN"	: $table = "wiz_meminfo";  $field = "m_login_url"; break;
			case "_MEM_IDPW" 	: $table = "wiz_meminfo";  $field = "m_idpw_url"; break;
			case "_MEM_INFO" 	: $table = "wiz_meminfo";  $field = "m_myinfo_url"; break;
			case "_MESSAGE" 	: $table = "wiz_siteinfo"; $field = "m_msg_url"; break;
			case "_POINT" 		: $table = "wiz_siteinfo"; $field = "m_point_url"; break;
			case "_SCH" 		: $table = "wiz_bbsinfo";  $field = "m_pageurl"; break;
			case "_BBS" 		: $table = "wiz_bbsmain";  $field = "m_purl"; break;
			case "_PRD" 		: $table = "wiz_category"; $field = "m_m_purl"; break;
			case "_POLL" 		: $table = "wiz_pollinfo"; $field = "m_purl"; break;
			case "_SEARCH"		: $table = "wiz_siteinfo"; $field = "m_search_url"; break;

			case "_BASKET"		: $table = "wiz_prdinfo";  $field = "m_basket_url"; break;
			case "_ORDER"		: $table = "wiz_prdinfo";  $field = "m_order_url"; break;
			case "_ORDER_LIST"	: $table = "wiz_prdinfo";  $field = "m_order_list_url"; break;
			case "_PRDSEARCH"	: $table = "wiz_prdinfo";  $field = "m_search_url"; break;
			case "_WISHLIST"	: $table = "wiz_prdinfo";  $field = "m_wish_url"; break;
		}

		if(empty($table) || empty($field)) {
			alert("연결 페이지 업데이트를 위한 함수를 정상적으로 불러들이지 못하였습니다. \\n\\n해당 페이지를 확인해주세요.");
		} else {

			$this_page = substr($_SERVER['PHP_SELF'], 1, strlen($_SERVER['PHP_SELF']));

			if(!empty($code) && (!strcmp($table, "wiz_bbsinfo") || !strcmp($table, "wiz_bbsmain") || !strcmp($table, "wiz_pollinfo"))) $code_sql = " where code = '$code' ";
			if(!empty($catcode) && !strcmp($table, "wiz_category")) {

				if($catcode != ""){

					$catcode01 = str_replace("00","",substr($catcode,0,2));
					$catcode02 = str_replace("00","",substr($catcode,2,2));
					$catcode03 = str_replace("00","",substr($catcode,4,2));
					$tmp_code = $catcode01.$catcode02.$catcode03;

					if($tmp_code != "") $catcode_sql = " where catcode like '$tmp_code%' ";
					else $catcode_sql = " where catcode = '$catcode' ";

					$code_sql = "";

				}

			}
			$sql = "select $field from $table $code_sql $catcode_sql";
			$result = query($sql);

			while($row = sql_fetch_arr($result)) {

				if(strcmp($row[$field], $this_page)) {
					$sql = "
						update $table 
						   set $field = '$this_page' $code_sql $catcode_sql
					";
					query($sql);
				}

			}

		}

	}

}

// 바로구메시 리다이렉션 문제로 wiz_prdinfo 의 url 변경이 않되는 문제 를 대신할 함수
function update_page_order($type){
	$sql = "
		update wiz_prdinfo 
		   set order_url='shop/order.php'
	";
	query($sql);
	//echo $sql;
}

// 보기페이지 이미지 리사이즈
function view_img_resize(){

	global $_ResizeCheck;

	if($_ResizeCheck) {
?>
<!-- 이미지 리사이즈를 위해서 처리하는 부분 -->


<script>
	function wiz_img_check(){
		
		//var wiz_main_table_width = document.getElementById('wiz_get_table_width').style.width;
		/*
		작업자	: 정나혜 
		작업일시	: 2021-04-08
		작업내용	: wiz_get_table_width의 스타일값이 지정되어있지 않을 경우 대비하여 제이쿼리의 width() 함수 사용
		*/
		var wiz_main_table_width = $("#wiz_get_table_width").width();
		var wiz_target_resize_num = document.wiz_target_resize.length;
		for(i=0;i<wiz_target_resize_num;i++){
			if(document.wiz_target_resize[i].width > wiz_main_table_width) {
				document.wiz_target_resize[i].width = wiz_main_table_width;
			}
		}
	}
	window.onload = wiz_img_check;
</script>

<?php
	}

}

function ContentImgResizeCheck($content,$code) {

	$img_pattern = "/<img([^>]*)>/i";
	preg_match_all($img_pattern, $content, $match);

	foreach($match[1] as $key=>$v) {

		$get_img = $v;
		preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $get_img, $matches);
		$get_src = $matches[1];
		preg_match("/style=[\"\']?([^\"\'>]+)/i", $get_img, $matches);
		$get_style = $matches[1];
		preg_match("/usemap=[\"\']?([^\"\'>]+)/i", $get_img, $matches);
		$get_usemap = $matches[1];
		preg_match("/width:\s*(\d+)px/", $get_style, $matches);
		$get_width = $matches[1];
		/* 사이즈가 페이지넓이보다 작으면 원본사이즈 보여줌 */
		//preg_match("/width:\s*(\d+)%/", $get_style, $matches);
		//$get_width2 = $matches[1];
		preg_match("/height:\s*(\d+)px/", $get_style, $matches);
		$get_height = $matches[1];

		// alt 추출
		preg_match("/alt=[\"\']?([^\"\'>]+)/i", $get_img, $matches);
		$get_alt = isset($matches[1]) ? htmlspecialchars($matches[1], ENT_QUOTES) : "";

		// title 추출
		preg_match("/title=[\"\']?([^\"\'>]+)/i", $get_img, $matches);
		$get_title = isset($matches[1]) ? htmlspecialchars($matches[1], ENT_QUOTES) : "";


		$img_p    = parse_url($get_src);
	//	print_r($img_p);
		$img_host = $img_p['host'];
		$img_path = $img_p['path'];
		$fname = basename(WAY_PATH.$img_path);
		$p_dir = WIZHOME_DATA_DIR."/webedit";

		if($get_usemap != "") $img_usemap = 'usemap="'.$get_usemap.'"';

		if(strpos($match[0][$key], $p_dir)) {
			if($get_width)
				$img_url  = '<img src="'.WAY_HOST.$img_path.'" width="'.$get_width.'" height="'.$get_height.'" '.$img_usemap.' alt="'.$get_alt.'" title="'.$get_title.'" />';
			else
				$img_url  = '<img src="'.WAY_HOST.$img_path.'" '.$img_usemap.' alt="'.$get_alt.'" title="'.$get_title.'" />';
		} else {
			$img_url  = '<img src="'.$get_src.'" '.$img_usemap.' alt="'.$get_alt.'" title="'.$get_title.'" />';
		}
		
		$img_enc  = base64_encode(urlencode($img_path));

	//	echo $match[0][$key]."<br>";
	//	echo $p_dir."<br>";
	//	echo strpos($match[0][$key], $p_dir)."<br>";
	//	echo $fname."<br>";
	//	echo preg_match("/\.(gif|jpg|jpeg|png)$/i", $fname)."<br>";

		if(strpos($match[0][$key], $p_dir) && preg_match("/\.(gif|jpg|jpeg|png)$/i", $fname)) {
			if($get_usemap != ""){
				$img_link = $img_url;
			} else {
				$img_link = '<a class=viewImg href=javascript:bbsviewImg("'.$code.'","'.$img_enc.'","edit")>'.$img_url.'</a>';
			}
		} else {
			$img_link = $img_url;
		}
		$content = str_replace($match[0][$key], $img_link, $content);

	}

	return $content;

}

// 자동등록방지코드 생성
function get_spam_check(){

	global $is_norobot;
	global $norobot_img;
	global $norobot_msg;
	global $norobot_key;
	global $spam_check;

	global $form_info;	// 폼메일 자동등록방지 코드 생성 시 필요

	if(!empty($form_info['idx'])) $idx = $form_info['idx'];

	$is_norobot = false;

	$tmp_str = substr(md5(rand()),0,12); // 임의의 md5 문자열을 생성

	list($usec, $sec) = explode(' ', microtime()); // 난수 발생기
	$seed =  (float)$sec + ((float)$usec * 100000);
	srand((int)$seed);
	$keylen = strlen($tmp_str);
	$div = (int)($keylen / 2);
	$arr = array();
	while (count($arr) < 6)
	{
	    unset($arr);
	    for ($i=0; $i<$keylen; $i++)
	    {
	        $rnd = rand(1, $keylen);
	        $arr[$rnd] = $rnd;
	        if ($rnd > $div) break;
	    }
	}

	sort($arr);	// 배열에 저장된 숫자를 차례대로 정렬

	$norobot_key = "";
	$norobot_str = "";
	$m = 0;

	for ($i=0; $i<count($arr); $i++)
	{
	    for ($k=$m; $k<$arr[$i]-1; $k++)
	        $norobot_str .= $tmp_str[$k];
	    $norobot_str .= "<font size=3 color=#FF0000><b>{$tmp_str[$k]}</b></font>";
	    $norobot_key .= $tmp_str[$k];
	    $m = $k + 1;

	}

	if ($m < $keylen) {
	    for ($k=$m; $k<$keylen; $k++)
	        $norobot_str .= $tmp_str[$k];
	}

	$norobot_str = "<font color=#999999>$norobot_str</font>";

	$ss_norobot_key = $norobot_key;
	$is_norobot = true;

	if (function_exists("imagecreate")) {	// 이미지 생성이 가능한 경우 자동등록체크코드를 이미지로 생성
	  $norobot_img = "<span class='norobot_img' style='background:#242b38;'><img id='norobot_key_img' src='/twcenter/bbs/norobot_image.php?ss_norobot_key=$norobot_key' border='0' align='absmiddle' alt='자동등록방지번호'></span>";
	  $norobot_msg = "<div class='sub_txt'>* 왼쪽의 자동등록방지 코드를 입력하세요. <a href='javascript:code_refresh".$idx."()'>[새로고침]</a></div>";
	}
	else {
	 $norobot_img = $norobot_str;
	 $norobot_msg = "* 왼쪽의 글자중 <FONT COLOR='red'>빨간글자</font>만 순서대로 입력하세요.";
	}
	$spam_check = $norobot_img."<input type='text' name='vcode' id='vcode' class='input vcode' title='자동등록방지 입력' /> ".$norobot_msg;

	?>
	<script Language="JavaScript" src="/twcenter/js/md5.js"></script>
	<script language="javascript">
	<!--

	function hex_md5(s) {
		return binl2hex(core_md5(str2binl(s), s.length * chrsz));
	}
	var md5_norobot_key<?php echo $idx ?> = "<?php echo md5($norobot_key) ?>";

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, "");
	}

	function code_refresh<?=$idx?>() {
		$.ajax({
			url : "/comm/ajax/ajax_spamcheck_refresh.php"
			, success : function(new_code) {
				$("#norobot_key_img").attr("src", "/twcenter/bbs/norobot_image.php?ss_norobot_key="+new_code);
				md5_norobot_key<?php echo $idx ?> = hex_md5(new_code);
				$("input[name=tmp_vcode<?=($idx) ? "_".$idx : ""?>").val(hex_md5(new_code));
			} , error : function(req, sta, err) {
				alert("오류가 발생했습니다");
			}
		});
	}

	//-->
	</script>
<?php
}

// 디렉토리 삭제
/*function rm_dir($path){
	$oDir = @openDir($path);
	while($entry = @readDir($oDir)) {
		if($entry <> '.' && $entry <> '..') {
			if(Is_Dir($path.'/'.$entry)) {
				rm_dir ($path.'/'.$entry);
			} else {
				@UnLink ($path.'/'.$entry);
			}
		}
	}
	@closeDir($oDir);
	@RmDir($path);
}*/

//디렉토리 삭제 
function rm_dir($path){
	if(!is_dir($path)) return;
	
	$oDir = @opendir($path);
	if ($oDir === false ) {
		return;
	}
	while(($entry = readdir($oDir)) !== false) {
		if($entry <> '.' && $entry <> '..') {
			if(is_dir($path.'/'.$entry)) {
				rm_dir ($path.'/'.$entry);
			} else {
				@unlink ($path.'/'.$entry);
			}
		}
	}
	@closedir($oDir);
	@rmdir($path);
}

//SQL 입력값 문자열 필터
//$str = 입력 문자열
function sql_filter($str){
	//1단계 ? ',",NULL 문자 필터링. 각 문자들에 백슬래쉬(\) 삽입됨. 필수 항목
	//출력시 stripslashes()함수를 이용하여 백슬래쉬(\)를 제거
	//if (!get_magic_quotes_gpc()) $str = addslashes($str);

	//3단계 ? 특수 문자 및 문자열 필터링
	//WHERE 구문에서 쓰여지는 데이터만 사용하는 것이 바람직하다.
	$search = array("--","#",";");
	$replace = array("\--","\#","\;");
	$str = str_replace($search, $replace, $str);

	return $str;
}

## 검색필드 SQL Injection Filter
function sqlfilter($str) {
	$str = (string)$str;
	
	// get_magic_quotes_gpc 제거 → 조건 없이 addslashes
	$str = addslashes($str);
	
	// 위험한 문자 제거
	return preg_replace("/[#&\+\%=\/\\\:;,\'\"\^`~\!\?\*$#<>()\[\]\{\}]/i", "", $str);
}

function sqlSearchfilter($search)
{
	$srh_pattern = array();
	$srh_pattern[] = '#\.*/+#';
	$srh_pattern[] = '#\\\*#';
	$srh_pattern[] = '#\.{2,}#';
	$srh_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

	$srh_replace = array();
	$srh_replace[] = '';
	$srh_replace[] = '';
	$srh_replace[] = '.';
	$srh_replace[] = '';
	
	if(!isset($search)) $search = '';
	$search = preg_replace($srh_pattern, $srh_replace, $search);

	return $search;
}

/* 인위적인 공격으로 xss_clean 우회했을경우 대비 (읽기, 쓰기 등) */
function xss_check($get_String, $get_HTML = true){
	
	if(!isset($get_String)) $get_String = '';

	$get_String = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $get_String);
	$get_String = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $get_String);
	$get_String = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $get_String);
	$get_String = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $get_String);
	$get_String = preg_replace("/(ex)(pression)/i", "&#101&#120;$2", $get_String);

	if(!$get_HTML) {
		$get_String = STR_REPLACE( "<", "&lt;", $get_String );
		$get_String = STR_REPLACE( ">", "&gt;", $get_String );
	}
	return $get_String;

}

function xss_clean($str, $get_html = true, $chk = true) {
	// 배열일 경우 재귀적으로 처리
	if (is_array($str)) {
		foreach ($str as $key => $val) {
			$str[$key] = xss_clean($val, $get_html, $chk);
		}
		return $str;
	}

	if (!empty($str) && (empty($_SESSION['wiz_admin']['id']))) {

		// 금지 단어 리스트
		$param_chk = array(
			"onstop","layer","javascript","eval","onactivae","onfocusinapplet","document","onclick","onkeydownxml","create",
			"onbeforecut","onkeyuplink","binding","ondeactivate","onloadscript","msgbox","ondragend","onbounceobject",
			"ondragleave","onmovestartframe","applet","ondragstart","onmouseoutilayer","onerror","onmouseupbgsound",
			"onabortbase","onstart","onfocus","onmovestartonmove","onrowexit","onunload","onsubmitinnerHTML","onpaste",
			"ondblclick","vpscriptcharset","onresize","ondrag","expressionstring","onselect","ondragenter","onchangeappend",
			"onscroll","ondragover","metaalert","ondrop","voidrefresh","oncopy","oncutilayer","blink","onfinish",
			"framesetcookie","onreset","onselectstart","script","join","declare","column_name","table_name","openrowset",
			"substr","substring","xp_","sysobjects","syscolumns","onload","alert"
		);

		if ($chk) {
			$chk_str = strtolower($str); // 이 부분에서 오류 발생 가능 → 위에서 이미 배열 확인 처리함
			foreach ($param_chk as $bad) {
				if (strpos($chk_str, $bad) !== false) {
					alert("금지단어가 포함되어 있습니다.\\n계속해서 문제발생시 관리자에게 문의하세요.","");
					exit();
				}
			}
		}

		if (!$get_html) {
			$str = str_replace("<", "&lt;", $str);
			$str = str_replace(">", "&gt;", $str);
			$str = str_replace("(", "&#40;", $str);
			$str = str_replace(")", "&#41;", $str);
			$str = str_replace('"', "&quot;", $str);
			$str = str_replace("'", "&#x27;", $str);
			$str = str_replace("/", "&#x2F;", $str);
		}
	}

	return $str;
}

function SQLInjectXssForward($str) {

	$str = preg_replace('/[\r\n\s\t\'\;\"\=\-\-\#\/*]+/','', $str);

	$pattern = array("onstop","layer","javascript","eval","onactivae","onfocusinapplet","document","onclick","onkeydownxml","create","onbeforecut","onkeyuplink","binding","ondeactivate","onloadscript","msgbox","ondragend","onbounceobject","embed","ondragleave","onmovestartframe","applet","ondragstart","onmouseoutilayer","onerror","onmouseupbgsound","embed","onabortbase","onstart","onfocus","onmovestartonmove","onrowexit","onunload","onsubmitinnerHTML","onpaste","ondblclick","vpscriptcharset","onresize","ondrag","expressionstring","onselect","ondragenter","onchangeappend","onscroll","ondragover","metaalert","ondrop","voidrefresh","oncopy","oncutilayer","blink","onfinish","framesetcookie","onreset","onselectstart","script","join","declare","column_name","table_name","openrowset","substr","substring","xp_","sysobjects","syscolumns","onload","alert", "sleep");

	for($i=0;$i<count($pattern);$i++){
		$chk_str = strtolower($str);
		$check = strpos($chk_str, $pattern[$i]);
		if ($check === false) {

		} else {
			$result = 1;
		}
	}

	return $result;

}

// 문자열 변환 in_charset → out_charset
function str_conv($str, $mode){
	if(!strcmp(strtolower($mode), 'utf-8')) {
		$in_charset = "utf-8";
		$out_charset = "euc-kr";
	} else if(!strcmp(strtolower($mode), 'euc-kr')) {
		$in_charset = "euc-kr";
		$out_charset = "utf-8";
	}

	if(iconv($out_charset,$out_charset,$str)==$str) return $str;
	else return iconv($in_charset,$out_charset,$str);
}

// 로봇 체크
function check_robots($user_agent){
	$user_agent = strtolower($user_agent);
	$robots = array('1noonbot', 'accoona-ai-agent', 'allblog.net', 'baiduspider', 'blogbeat', 'crawler', 'digext', 'drecombot', 'exabot', 'feedchecker', 'feedfetcher', 'gigabot', 'googlebot', 'hmse_robot', 'ip*works!', 'irlbot', 'jigsaw', 'lwp::simple', 'labrador', 'mj12bot', 'mirror checking', 'missigua locator', 'ng/2.0', 'naverbot', 'nutchcvs', 'pear http_request', 'postfavorites', 'sbider', 'w3c_validator', 'wisebot', 'y!j-bsc', 'yahoo! slurp', 'zyborg', 'archiver', 'carleson', 'cfetch', 'compatible; eolin', 'favicon', 'feedfinder', 'findlinks', 'geniebot', 'ichiro', 'kinjabot', 'larbin', 'lwp-trivial', 'msnbot', 'psbot', 'sogou', 'urllib/1.15', 'voyager');
	foreach($robots as $robot)
		if(strpos($user_agent, $robot) !== false)
			return false;
	return true;
}

// OS,브라우저 정보
function get_osbrowser($user_agent){

	$user_agent = strtolower($user_agent);
	if(strpos($user_agent, "mobile") !== false){

		if(strpos($user_agent, "gecko") !== false){
			if(strpos($user_agent, "netscape") !== false){
				$browser = "Netscape";
			}else if(strpos($user_agent, "firefox") ){
				$browser = "Firefox";
			}else{
				$browser = "Mozilla";
			}
		}else if(strpos($user_agent, "msie") !== false){
			if (strpos($user_agent, "opera") !== false){
				$browser = "Opera";
			}else if (strpos($user_agent, "msie or firefox") !== false){
				$browser = "MSIE or Firefox";
			}else{
				if(preg_match("/msie ([0-9]{1,}[\.0-9]{0,})/",$agent,$version)) {
					 $browser = 'MSIE '.$version[1];
				}
			}
		}else{
			$browser = "기타";
		}

		$device = "Mobile";

	} else {

		if(strpos($user_agent, "gecko") !== false){

			if(strpos($user_agent, "netscape") !== false){
				$browser = "Netscape";
			}else if(strpos($user_agent, "firefox") !== false){
				$browser = "Firefox";
			}else if(strpos($user_agent, "opera") !== false || (strpos($user_agent, "OPR") !== false)) {
				$browser = "Opera";
			}else if(strpos($user_agent, "chrome") !== false) {
				$browser = "Chrome";
			}else if(strpos($user_agent, "bot") !== false || strpos($user_agent, "slurp") !== false) {
				$browser = "Robot";
			}else if(strpos($user_agent, "mozilla") !== false) {
				$browser = "Mozilla";
			}else if(strpos($user_agent, "safari") !== false) {
				$browser = "Safari";
			}else if(preg_match("/msie ([0-9]{1,}[\.0-9]{0,})/",$agent,$version)) {
				 $browser = 'MSIE '.$version[1];
			}
		}else if(strpos($user_agent, "msie") !== false){
			if (strpos($user_agent, "opera") !== false){
				$browser = "Opera";
			}else if(strpos($user_agent, "msie or firefox") !== false){
				$browser = "MSIE or Firefox";
			}else{
				$browser = "";
				if(strpos($user_agent, "trident/4.0") !== false)	  $browser .= " IE 8";
				else if(strpos($user_agent, "trident/5.0") !== false) $browser .= " IE 9";
				else if(strpos($user_agent, "trident/6.0") !== false) $browser .= " IE 10";
				else if(strpos($user_agent, "trident/7.0") !== false) $browser .= " IE 11";
			}
		}else{
			$browser = "기타";
		}

		$device = "PC";

	}

	if(strpos($user_agent, "mobile")){

		if(strpos($user_agent, "iphone") !== false)											$os = "iPhone";
		else if(strpos($user_agent, "ipod") !== false)										$os = "iPod";
		else if(strpos($user_agent, "android") !== false)									$os = "Android";
		else if(strpos($user_agent, "blackberry") !== false)								$os = "Blackberry";
		else if(strpos($user_agent, "symbianos|sch-m\d+") !== false)						$os = "SymbianOS|SCH-M\d+";
		else if(strpos($user_agent, "opera mini") !== false)								$os = "Opera Mini";
		else if(strpos($user_agent, "windows phone 8.1") !== false)							$os = "Windows Phone 8.1";
		else if(strpos($user_agent, "windows phone 10.0") !== false)						$os = "Windows Phone 10.0";
		else if(strpos($user_agent, "sony") !== false)										$os = "Nokia";
		else if(strpos($user_agent, "samsung") !== false)									$os = "Samsung";
		else if(strpos($user_agent, "lgtelecom") !== false)									$os = "LGTelecom";
		else if(strpos($user_agent, "skt") !== false)										$os = "SKT";
		else if(strpos($user_agent, "mobile") !== false)									$os = "Mobile";
		else if(strpos($user_agent, "phone") !== false)										$os = "Phone";

	} else {

		if(strpos($user_agent, "windows 95") !== false)										$os = "Win95";
		else if(strpos($user_agent, "windows 98") !== false)								$os = "Win98";
		else if(strpos($user_agent, "windows nt 10.0") !== false)							$os = "Windows 10";
		else if(strpos($user_agent, "windows nt 6.2") !== false)							$os = "Windows 8";
		else if(strpos($user_agent, "windows nt 6.1") !== false)							$os = "Windows 7";
		else if(strpos($user_agent, "windows nt 6.0") !== false)							$os = "Windows Vista";
		else if(strpos($user_agent, "windows nt 5.2") !== false)							$os = "Win2003";
		else if(strpos($user_agent, "windows nt 5.01") !== false)							$os = "Win2000";
		else if(strpos($user_agent, "windows nt 5.1") !== false)							$os = "WinXP";
		else if(strpos($user_agent, "windows nt 5") !== false)								$os = "Win2000";
		else if(strpos($user_agent, "mac") !== false)										$os = "Mac";
		else if(strpos($user_agent, "linux") !== false)										$os = "Linux";
		else if(strpos($user_agent, "unix"))												$os = "Unix";
		else if(strpos($user_agent, "ipad") !== false)										$os = "IPad";
		else																				$os = "기타";

	}

	$data["browser"] = $browser;
	$data["os"]      = $os;
	$data["device"]  = $device;

	return $data;

}

function getBrowser()
{
	$agent = $_SERVER['HTTP_USER_AGENT'];

	$raw = array();
	$brows = parse_ini_file(dirname(__FILE__) . "/php_browscap.ini",true);

	foreach($brows as $k=>$v)
	{
		if(fnmatch($k, $agent))
		{
			$raw['browser_name_pattern'] = $k;
			$regex = preg_replace(
				array("/\./", "/\*/", "/\?/"),
				array("\.", ".*", ".?"),
				$k
			);
			$raw['browser_name_regex'] = strtolower("^$regex$");

			foreach($brows as $j=>$p)
			{
				if($v['Parent'] == $j)
				{
					foreach($brows as $q=>$r)
					{
						if($p['Parent'] == $q)
						{
							$raw = array_merge($raw, $r, $p, $v);
							foreach($raw as $d=>$z)
							{
								$i = strtolower($d);
								$list[$i] = $z;
							}
						}
					}
				}
			}
			break;
		}
	}
	return $list;
}

function getBrowser2() {
	$u_agent = $_SERVER['HTTP_USER_AGENT']; 
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version= "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
	elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac'; }
	elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }
	 
	// Next get the name of the useragent yes seperately and for good reason
	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { $bname = 'Internet Explorer'; $ub = "MSIE"; } 
	elseif(preg_match('/Firefox/i',$u_agent)) { $bname = 'Mozilla Firefox'; $ub = "Firefox"; } 
	elseif(preg_match('/Chrome/i',$u_agent)) { $bname = 'Google Chrome'; $ub = "Chrome"; } 
	elseif(preg_match('/Safari/i',$u_agent)) { $bname = 'Apple Safari'; $ub = "Safari"; } 
	elseif(preg_match('/Opera/i',$u_agent)) { $bname = 'Opera'; $ub = "Opera"; } 
	elseif(preg_match('/Netscape/i',$u_agent)) { $bname = 'Netscape'; $ub = "Netscape"; } 
	 
	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
	')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	 
	// check if we have a number
	if ($version==null || $version=="") {$version="?";}
	return array('userAgent'=>$u_agent, 'name'=>$bname, 'version'=>$version, 'platform'=>$platform, 'pattern'=>$pattern);
}

function getUserAgent(){

	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile") !== false){

		$iPod    = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");

		if($iPad || $iPhone || $iPod){
			return 'ios';
		} else if($android){
			return 'android';
		} else {
			return 'etc';
		}
	
	}
}

function getOS_default() { 
	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	$os_platform    =   "Unknown OS Platform";
	$os_array       =   array(
							'/windows nt 10/i'     =>  'Windows 10',
							'/windows nt 6.3/i'     =>  'Windows 8.1',
							'/windows nt 6.2/i'     =>  'Windows 8',
							'/windows nt 6.1/i'     =>  'Windows 7',
							'/windows nt 6.0/i'     =>  'Windows Vista',
							'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
							'/windows nt 5.1/i'     =>  'Windows XP',
							'/windows xp/i'         =>  'Windows XP',
							'/windows nt 5.0/i'     =>  'Windows 2000',
							'/windows me/i'         =>  'Windows ME',
							'/win98/i'              =>  'Windows 98',
							'/win95/i'              =>  'Windows 95',
							'/win16/i'              =>  'Windows 3.11',
							'/macintosh|mac os x/i' =>  'Mac OS X',
							'/mac_powerpc/i'        =>  'Mac OS 9',
							'/linux/i'              =>  'Linux',
							'/ubuntu/i'             =>  'Ubuntu',
							'/iphone/i'             =>  'iPhone',
							'/ipod/i'               =>  'iPod',
							'/ipad/i'               =>  'iPad',
							'/android/i'            =>  'Android',
							'/blackberry/i'         =>  'BlackBerry',
							'/webos/i'              =>  'Mobile'
						);
	foreach ($os_array as $regex => $value) { 
		if (preg_match($regex, $user_agent)) {
			$os_platform    =   $value;
		}
	}
	return $os_platform;
}

function getBrowser_default() {
	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	$browser        =   "Unknown Browser";
	$browser_array  =   array(
							'/msie/i'       =>  'Internet Explorer',
							'/firefox/i'    =>  'Firefox',
							'/safari/i'     =>  'Safari',
							'/chrome/i'     =>  'Chrome',
							'/edge/i'       =>  'Edge',
							'/opera/i'      =>  'Opera',
							'/netscape/i'   =>  'Netscape',
							'/maxthon/i'    =>  'Maxthon',
							'/konqueror/i'  =>  'Konqueror',
							'/mobile/i'     =>  'Mobile Browser'
						);
	foreach ($browser_array as $regex => $value) { 
		if (preg_match($regex, $user_agent)) {
			$browser    =   $value;
		}
	}
	return $browser;
}

// 게시물 번호 ($no)
function get_bbs_no($data) {

	global $bbs_info, $wiz_session;
	//@extract($data);

	$code        = sqlfilter($data['code']);
	$idx         = sqlfilter(intval($data['idx']));
	$page        = sqlfilter(intval($data['page']));
	$pos         = sqlfilter($data['pos']);
	$po          = sqlfilter($data['po']);
	$code_page   = sqlfilter($data['code_page']);
	$searchopt   = sqlfilter($data['searchopt']);
	$searchkey   = sqlfilter($data['searchkey']);
	$category    = sqlfilter(intval($data['category']));

	// 게시물 쿼리
	if($category) $category_sql = " and category = '$category' ";
	if($searchopt) {
		if(!strcmp($searchopt, "subcon")) $search_sql = " and (subject like '%$searchkey%' or content like '%$searchkey%') ";
		else $search_sql = " and $searchopt like '%$searchkey%' ";
	}
	// 자신이 쓴 글 또는 자신의 글에 달린 답변글
$my_sql = " and (memid='".$wiz_session['id']."' or memgrp like '".$wiz_session['id'].",%')";
	if($sido) $address_sql = " and address like '".$sido."%' ";
	if($gugun) $address_sql .= " and address like '%".$gugun."%' ";

	$sql = "select idx from wiz_bbs where code = '$code' $my_sql $category_sql $search_sql $address_sql order by prino desc";
	$result = query($sql);
	$total = sql_fetch_row($result);

	$rows  = $bbs_info['rows'];
	$lists = $bbs_info['lists'];
	if($rows == "")  $rows = "20";
	if($lists == "") $lists = "5";

	$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "
		select wb.*
			 , wb.wdate as wtime
			 , from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate
			 , wc.catname
			 , wc.caticon
		  from wiz_bbs as wb 
		 inner join wiz_bbscat as wc 
		    on wb.category = wc.idx
		 where wb.code = '$code' 
		   $category_sql $search_sql $my_sql $address_sql
		 order by wb.prino desc 
		 limit $start, $rows
	";
	$result = query($sql);

	while($row = sql_fetch_arr($result)){
		if($row['idx'] == $idx) break;
		$no--;
	}

	return $no;
}

/******************************************************************
*			 WizShop basket : SESSION -> DATABASE 변경
******************************************************************/

//쿠키 생성
function makeCookie( $name, $valuename, $exptime = "" ) {
	if(empty($exptime) || !$exptime){
		@setcookie("$name","$valuename","0","/", $_SERVER['HTTP_HOST'],false,true);
	}else{
		@setcookie("$name","$valuename","$exptime","/", $_SERVER['HTTP_HOST'],false,true);
	}
}

//장바구니를 위한 유니크 쿠키생성
function makeBasketCookie(){
	//쿠키가 생성되어있지 않으면 고유값 생성해서 만들기
	if(empty($_SESSION['wiz_session'])) {
		$_SESSION['wiz_session'] = array();
	}

	if(!empty($_SESSION['wiz_session']['wiz_basket_id'])){
		$basketid = md5($_SESSION['wiz_session']['wiz_basket_id']);
		makeCookie("uniq_id",$basketid);
		return $basketid;
	} else {
		if(empty($_COOKIE["uniq_id"])){
			$basketid = md5($REMOTE_ADDR.time());
			makeCookie("uniq_id",$basketid);
			return $basketid;
		}
	}

}

makeBasketCookie();

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
	$server_time = time();
	setcookie(md5($cookie_name), base64_encode($value), $g4['server_time'] + $expire, '/', $server_time);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
	return base64_decode($_COOKIE[md5($cookie_name)]);
}

// 결제방법
function pay_method($pay_method)
{

	if($pay_method == "PB") $pay_method = "무통장입금";
	else if($pay_method == "PC") $pay_method = "신용카드";
	else if($pay_method == "PN") $pay_method = "계좌이체";
	else if($pay_method == "PV") $pay_method = "가상계좌";
	else if($pay_method == "PH") $pay_method = "휴대폰";
	else if($pay_method == "KK") $pay_method = "카카오페이(간편결제)";

	return $pay_method;
}

function pay_method2($pay_method)
{

	if($pay_method == "PB") $pay_method = "<font color='#353944'>무통장입금</font>";
	else if($pay_method == "PC") $pay_method = "<font color='#1D7CD4'>신용카드</font>";
	else if($pay_method == "PN") $pay_method = "<font color='#9595B4'>계좌이체</font>";
	else if($pay_method == "PV") $pay_method = "<font color='#144259'>가상계좌</font>";
	else if($pay_method == "PH") $pay_method = "<font color='#EB4D40'>휴대폰</font>";
	else if($pay_method == "KK") $pay_method = "<font color='#F3CE26'>카카오페이</font>";

	return $pay_method;
}

// 배송상태
function order_status($status)
{

	if($status == "OR") $status = "<font color=#31b7ff>주문접수</font>";
	else if($status == "OY") $status = "<font color=#1f83ff>결제완료</font>";
	else if($status == "DR") $status = "<font color=#084fd0>배송준비중</font>";
	else if($status == "DI") $status = "<font color=#284a87>배송처리</font>";
	else if($status == "DC") $status = "<font color=#122c5c>배송완료</font>";
	else if($status == "OC") $status = "<font color=#e8514f>주문취소</font>";
	else if($status == "RD") $status = "<font color=#f5a430>취소요청</font>";
	else if($status == "RC") $status = "<font color=#e8514f>취소완료</font>";
	else if($status == "CD") $status = "<font color=#aa73ba>교환요청</font>";
	else if($status == "CC") $status = "<font color=#814d92>교환완료</font>";
	else if($status == "PY") $status = "<font color=#1f83ff>부분결제</font>";
	else if($status == "PZ") $status = "<font color=#e8514f>부분취소</font>";
	else if($status == "PR") $status = "<font color=#084fd0>부분배송준비</font>";
	else if($status == "PP") $status = "<font color=#284a87>부분배송처리</font>";
	else if($status == "PD") $status = "<font color=#122c5c>부분배송완료</font>";
	else if($status == "PC") $status = "<font color=#e8514f>부분주문취소</font>";
	else if($status == "PO") $status = "<font color=#f5a430>부분취소요청</font>";
	else if($status == "PE") $status = "<font color=#aa73ba>부분교환요청</font>";
	else if($status == "PQ") $status = "<font color=#814d92>부분교환처리</font>";

	return $status;
}

/******************************************************************
*			 결제연동에 필요한 함수들
******************************************************************/
//////////////////////////////////////////////////////////////////////
//order_ok 에 들어가는 함수
//(선행 $orderid ,  $resmsg = pg (order_update.php)에서 넘어온값들
//////////////////////////////////////////////////////////////////////

function Pay_result($pgname,$rescode){
	// rescode = 0000 이 성공메세지
	global $orderid,$resmsg,$pay_method;

	switch($pgname){
		case "DACOM":
			if($rescode == "0000" || $rescode == "C000"){
				$rescode = "0000";
			}else{
				$rescode = $rescode;
			}
			break;
		case "KCP":
			if($rescode == "0000"){
				$rescode = "0000";
			}else{
				$rescode = $rescode;
			}
			break;
		case "ALLTHEGATE":
			if($rescode == "y"){
				$rescode = "0000";
			}else{
				$rescode = $rescode;
			}
			break;
		case "INICIS":
			if($rescode == "00"){
				$rescode = "0000";
			}else{
				$rescode = $rescode;
			}
			break;
		case "KAKAOPAY":
			if($rescode == "3001"){
				$rescode = "0000";
			}else{
				$rescode = $rescode;
			}
			break;
	}

	$presult['orderid']    = $orderid;
	$presult['resmsg']     = $resmsg;
	$presult['rescode']    = $rescode;
	$presult['pay_method'] = $pay_method;

	return $presult;
}

// 등급할인액
function level_discount($idx,$prd_price)
{
	global $discount_msg;
	$discount_price = 0;

	if($idx != "" && $prd_price > 0)
	{
		$sql = "select * from wiz_level where idx = '$idx'";
		$result = query($sql);
		$row = sql_fetch_obj($result);

		if($row->discount > 0)
		{
		   if($row->distype == "W")
		   {
		   	$discount_price = $row->discount;
		   }else
		   {
		      $discount_price = floor(($prd_price*($row->discount/100))/100)*100;
			}
		}
	}

	if($discount_price > 0) $discount_msg = " <img src='/twcenter/product/image/icon_plus.gif'> 회원할인&nbsp;&nbsp;<span class='tbold'>".number_format($discount_price)."</span> 원";

	return $discount_price;

}

// 배송정책 이름
function deliver_name($deliver_method){
	if($deliver_method == "DA") return "전액무료";
	else if($deliver_method == "DB") return "수신자부담(착불)";
	else if($deliver_method == "DC") return "고정값";
	else if($deliver_method == "DD") return "구매가격별";
}

// 배송정책 이름 - 상품
function deliver_name_prd($deliver_method) {
	if($deliver_method == "DA") return "기본 배송정책";
	else if($deliver_method == "DB") return "무료배송";
	else if($deliver_method == "DC") return "상품별 배송비";
	else if($deliver_method == "DD") return "수신자부담(착불)";
}

// 배송비
function deliver_price($prd_price, $oper_info)
{
	global $deliver_msg; $deliver_price = 0;
	global $product_idx;
	global $_uniq_id;

	if(!empty($product_idx))
	{
		$_product_idx_val = explode("|", $product_idx);

		foreach($_product_idx_val as $key => $value){
			if(!empty($value)) $_product_idx_tmp .= " OR idx='{$value}'";
		}

		$_product_idx_tmp = substr($_product_idx_tmp,3);
		$_product_idx_sql = " and ({$_product_idx_tmp})";
	} else {
		/*
		작업자명	: 이상민
		작업일시	: 2020-04-21
		작업내용	: 장바구니 및 주문서 작성화면에서 배송비 계산 시 direct 구분없이 계산하여 배송비 계산오류에 따른 수정
		*/
		$_product_idx_sql = " and direct = 'basket' ";
	}

	// 장바구니에 담긴 상품의 배송정책 및 배송비
 	if(strpos($_SERVER['PHP_SELF'],"/twcenter/") !== false && strpos($_SERVER['PHP_SELF'],"print_estimate") === false) {
 		global $orderid;
 		$sql = "SELECT prdprice, amount, del_type, del_price FROM wiz_basket WHERE orderid = '$orderid'";
 	} else {
 		$sql = "
			SELECT wb.prdprice
				 , wb.amount
				 , wp.del_type
				 , wp.del_price 
			  FROM wiz_basket_tmp as wb 
			 INNER JOIN wiz_product as wp 
			    ON wb.prdcode = wp.prdcode 
			 WHERE wb.uniq_id='".$_uniq_id."' $_product_idx_sql
		";
 	}

	$result = query($sql);
	$basket_total = sql_fetch_row($result);
	while($row = sql_fetch_arr($result)) {

		if(empty($row['del_type'])) $row['del_type'] = "DA";							// 배송비 정책 누락시 기본 배송정책으로 설정

		if(!strcmp($row['del_type'], "DC")) $del_price += $row['del_price'];			// 상품별 배송비일 경우 배송비 합산
		$del_info[$row['del_type']]['cnt'] = $del_info[$row['del_type']]['cnt'] + 1;		// 배송방법별 합계

		if($row['del_type'] != "DA") $prd_price -= ($row['prdprice'] * $row['amount']);	// 기본 배송정책이 아닌 경우 상품가격 합산에서 차감(기본 배송정책의 상품가격만 합산)

		$del_type = $row['del_type'];
	}

	/**************************************************************************************
		기본 배송정책
	**************************************************************************************/
	// 기본 배송정책에 따른 배송방법 및 배송비
	if($oper_info['del_method'] == "DA") { // 배송비 전액무료
 		$deliver_price = 0;
		$deliver_msg = "배송비 전액무료";

	} else if($oper_info['del_method'] == "DB") { // 수신자부담 (착불)
		$deliver_price = 0;
		$deliver_msg = "수신자부담 (착불)";

	} else if($oper_info['del_method'] == "DC") { // 고정값
		$deliver_price = $oper_info['del_fixprice'];
		$deliver_msg = "고정 ".number_format($oper_info['del_fixprice'])."원";

	} else if($oper_info['del_method'] == "DD") { // 구매가격별
	
		if($oper_info['del_staprice'] <= $prd_price) $deliver_price = $oper_info['del_staprice2'];
		else $deliver_price = $oper_info['del_staprice3'];

		$deliver_msg = number_format($oper_info['del_staprice'])."원 이상구매시 ";

		if($oper_info['del_staprice2'] == 0) $deliver_msg .= "무료";
		else $oper_info['del_staprice2'] = $deliver_msg .= number_format($oper_info['del_staprice2'])."원";

 	}

	/**************************************************************************************
		상품별 배송정책
	**************************************************************************************/

	// 무료배송 상품과 함께 주문할 경우, 전체 배송비를 무료
	if(!strcmp($oper_info['del_prd'], "DA") && $del_info["DB"]['cnt'] > 0) {
		$deliver_price = 0; $deliver_msg = "배송비 전액무료";
	}

	// 장바구니 상품 갯수와 배송비가 없는 상품의 갯수가 같으면 배송비 0
	if($basket_total > 0 && $basket_total == $del_info["DB"]['cnt'] + $del_info["DD"]['cnt']) {
		$deliver_price = 0; $deliver_msg = "상품별 배송비";
	}

	// 장바구니에 상품별 배송비가 있는 경우 배송비 문구에 "상품별 배송비 제외" 추가
	if($del_info["DC"]['cnt'] > 0) $deliver_msg .= "<br>&nbsp;(상품별 배송비는 부과)";

	// 상품을 2개 이상 주문할 경우
	if($basket_total > 1) {

		// 상품별 배송비 + 기본 배송비
		if(!strcmp($oper_info['del_prd2'], "DA")) {

			// 장바구니 모든 상품이 상품별 배송인 경우 기본 배송료를 더하지 않음
			if($basket_total == $del_info["DC"]['cnt']) $deliver_price = $del_price;
			else $deliver_price = $deliver_price + $del_price;

		// 상품별 배송비, 기본 배송비 중 큰 값
		} else if(!strcmp($oper_info['del_prd2'], "DB")) {
			if($deliver_price < $del_price) $deliver_price = $del_price;
		}

	// 장바구니 상품이 1개이고 기본 배송료가 아닌경우
	} else if($basket_total > 0 && strcmp($del_type, "DA")) {
		$deliver_price = $del_price;	$deliver_msg = deliver_name_prd($del_type);
	}

 	return $deliver_price;
}

// 착불일경우 배송비 0원대신 착불료 표기
function deliver_price2($order_method, $order_price)
{
	//global $deliver_price = 0;
	
	$deliver_price = ($order_method == "DB" )? "착불" : number_format($order_price);
	
 	return $deliver_price;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   /shop/order_pay.php 에 사용되는것 , 결제방법,결제사를 파일 인쿠르드 ($oper_info , $pay_method , $order_info 선행)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Inc_payment($pay_method,$pay_agent){

//	if(PHP_VERSION >= '5.6.3') {
//		$pg_lguplus = "lguplus";
//	} else {
		$pg_lguplus = "dacom";
//	}

	// 무통장 입금
	if($pay_method == "PB"){
	  return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/pay_account.php";
	} else if($pay_method == "KK") {
		return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/kakaopay/pay_form.php";
	// 신용카드결제,계좌이체,휴대폰..
	} else {
		if($pay_agent == "DACOM"){
			return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/".$pg_lguplus."/pay_form.php";
		} else if($pay_agent == "KCP") {
			 return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/nhnkcp/pay_form.php";
		} else if($pay_agent == "ALLTHEGATE") {
			return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/allthegate/pay_form.php";
		} else if($pay_agent == "INICIS") {
			return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/inicis/pay_form.php";
		}elseif($pay_agent == "TOSS"){
			return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/toss/pay_form.php";
		}
  }

}

function Inc_payment_Mobile($pay_method,$pay_agent){
	// 무통장 입금
	if($pay_method == "PB"){
		return $_SERVER['DOCUMENT_ROOT']."/twcenter/product/pay_account.php";
	} else if($pay_method == "KK") {
		return $_SERVER['DOCUMENT_ROOT']."/m/sub/kakaopay/pay_form.php";
	// 신용카드결제,계좌이체,휴대폰..
	}else{
		if($pay_agent == "DACOM"){
			return $_SERVER['DOCUMENT_ROOT']."/m/sub/dacom/pay_form.php";
		} else if($pay_agent == "KCP") {
			return $_SERVER['DOCUMENT_ROOT']."/m/sub/nhnkcp/pay_form.php";
		} elseif($pay_agent == "INICIS") {
			return $_SERVER['DOCUMENT_ROOT']."/m/sub/inicis/pay_form.php";
		} else if($pay_agent == 'TOSS') { 
			return $_SERVER['DOCUMENT_ROOT']."/m/sub/toss/pay_form.php";
		}
  }

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//주문후 자신의 임시 장바구니 삭제
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_delbasket(){
	global $product_idx;
	global $_uniq_id;

	if(!empty($product_idx))
	{
		$_product_idx_val = explode("|", $product_idx);

		foreach($_product_idx_val as $key => $value){
			if(!empty($value)) $_product_idx_tmp .= " OR idx='{$value}'";
		}

		$_product_idx_tmp = substr($_product_idx_tmp,3);
		$_product_idx_sql = " and ({$_product_idx_tmp})";
	} else {
		$_product_idx_sql = "";
	}

	@query("DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_uniq_id."' $_product_idx_sql");
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//적립금 처리 (선행 인클루드 $order_info 주문정보배열)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_reserve(){
	Global $order_info;
	if($order_info->reserve_use > 0){
		$reserve_msg = "상품구입시 사용함";
		$sql = "INSERT INTO wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) VALUES('', '$order_info->send_id', '$reserve_msg', -$order_info->reserve_use, '$order_info->orderid', now())";
		query($sql);
	}
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//재고관리 (선행 $order_info 주문정보배열)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_stock(){

	Global $order_info, $site_info;

	if(is_array($order_info)) $orderid = $order_info['orderid'];
	else $orderid = $order_info->orderid;

	$j_sql = "
		SELECT wb.optcode
			 , wb.prdcode
			 , wb.amount
			 , wp.optcode as p_optcode
			 , wp.optcode2 as p_optcode2
			 , wp.optvalue
			 , wp.opt_use
			 , wp.shortage
		  FROM wiz_basket wb
			 , wiz_product wp
		 WHERE wb.orderid = '$orderid' 
		   AND wb.prdcode = wp.prdcode
	";
	$j_result = query($j_sql);
	while($row = sql_fetch_obj($j_result)){
		// 옵션별 재고관리 없는 제품이라면 전체재고 감소
		if(strcmp($row->opt_use, "Y")){

			if(!strcmp($row->shortage, "S")) {
				$sql = "
					UPDATE wiz_product 
					   SET stock = stock - $row->amount 
					     , ordercnt = if(ordercnt is null, 1, ordercnt + 1)
					 WHERE prdcode = '$row->prdcode'
				";
				$result = query($sql);
			} else {
				$sql = "UPDATE wiz_product SET ordercnt = if(ordercnt is null, 1, ordercnt + 1 ) WHERE prdcode = '$row->prdcode'";
				$result = query($sql);
			}

		// 옵션별 재고관리 상품
		}else{


			$opt_list_app = "";

			if(!empty($tmp_prdcode[$row->prdcode])) $row->optvalue = $tmp_prdcode[$row->prdcode];

			$opt1_arr = explode("^", $row->p_optcode);
			$opt2_arr = explode("^", $row->p_optcode2);
			$opt_tmp = explode("^^", $row->optvalue);

			$opt1_cnt = count($opt1_arr) - 1;
			$opt2_cnt = count($opt2_arr) - 1;

			if($opt1_cnt < 1) $opt1_cnt = 1;
			if($opt2_cnt < 1) $opt2_cnt = 1;


			$no = 0;
			for($ii = 0; $ii < $opt1_cnt; $ii++) {
				for($jj = 0; $jj < $opt2_cnt; $jj++) {

					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);
					$optcode = $opt1_arr[$ii];
					if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode .= "/";

					$optcode .= $opt2_arr[$jj];
					list($opt_v,$opt_p) = explode("^",$row->optcode);
					$optcode  = str_replace(" ","",$optcode);

					if(!strcmp($opt_v, $optcode)) {

						if($site_info['viewType'] == "I"){
							$opt_val = explode("&&",$row->optcode);
							for($i=0; $i<count($opt_val)-1; $i++){
								$exp = $opt_val[$i];
								//echo $exp."<br>";
//								if(strpos($exp, $row->optvalue) !==  false){
									list($p_name,$p_price,$p_reserve,$p_amount) = explode("^",$exp);
									list($price_v, $reserve_v, $stock_v)        = explode("^", $opt_tmp[$i]);
									$stock_v = $stock_v - $p_amount;

									$opt_list_app2 .= $price_v."^".$reserve_v."^".$stock_v."^^";
//								}
							}
						} else {
							$stock = $stock - $row->amount;
							$opt_list_app .= $price."^".$reserve."^".$stock."^^";
						}

					} else {
						$opt_list_app .= $opt_tmp[$no]."^^";
					}

					$no++;

				}
			}

			if($site_info['viewType'] == "I"){
				$opt_list_value = $opt_list_app2;
			} else {
				$opt_list_value = $opt_list_app;
			}
			$sql = "
				UPDATE wiz_product 
				   SET optvalue = '$opt_list_value'
				     , ordercnt = if(ordercnt is null, 1, ordercnt + 1 )
				 WHERE prdcode = '$row->prdcode'
			";
			query($sql);

			$tmp_prdcode[$row->prdcode] = $opt_list_value;

		}
	}

}

//임시 로그파일 생성하기
function make_log($file, $noti) {
		$fp = fopen($file, "a+");
		ob_start();
		print_r($noti);
		$msg = ob_get_contents();
		ob_end_clean();
		fwrite($fp, $msg);
		fclose($fp);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//결제처리(상태변경,주문 업데이트)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_payment($payment){

	global $oper_info;
	global $isDBOK;

	/*변수 초기화 시작*/
	$status				= "";
	$orderid			= "";
	$financecode		= "";
	$accountnumber		= "";
	$tno				= "";
	$esw_check			= "";
	$esw_stats			= "";
	$totalprice			= "";
	$paymentkind		= "";
	$pay_method			= "";
	$other_update		= "";
	$accountname		= "";
	$kakao_nonRepToken	= "";
	$cardcode			= "";
	$cardname			= "";
	$cardquota			= "";
	$cardinterest		= "";
	$cardci				= "";
	$cardbin			= "";
	$cardpoint			= "";
	$tid				= "";
	$cash_type			= "";
	$cash_type_yn		= "";
	$cash_num			= "";
	$cash_segno			= "";
	$cash_flag			= "";
	$hppay_telno		= "";
	/*변수 초기화 끝*/

	$status				= $payment['status'];								// 주문상태
	$orderid			= $payment['orderid'];								// 주문번호
	$financecode		= $payment['bankkind'];								// 은행코드
	$accountnumber		= $payment['accountno'];							// 계좌번호
	$accountname		= $payment['accountname'];							// 예금주
	$tno				= $payment['ttno'];									// 승인번호,TID번호,처리번호
	$es_check			= $payment['es_check'];								// 에스크로 사용여부
	$es_stats			= $payment['es_stats'];								// 에스크로 상태
	$tprice				= $payment['tprice'];								// 결제금액
	$paymentkind		= $payment['pgname'];								// pg사 종류
	$pay_method			= $payment['paymethod'];							// 결제방법
	$other_update		= $payment['otherupdate'];							// 기타 wiz_order 을 update 하는방법
	$cash_type			= $payment['cash_type'];							// 현금영수증 종류
	$cash_type_yn		= $payment['cash_type_yn'];							// 현금영수증 자진발급유무
	$cash_num			= $payment['cash_num'];								// 현금영수증 승인번호
	$cash_segno			= $payment['cash_segno'];							// 가상계좌 입금순서
	$cash_flag			= $payment['cash_flag'];							// 가상계좌 거래종류
	$hppay_telno		= $payment['hppay_telno'];							// 휴대폰 결제번호
	$kakao_nonRepToken	= $payment['kakao_nonRepToken'];					// 카드사코드
	$cardcode			= $payment['cardcode'];								// 카드사코드
	$cardname			= $payment['cardname'];								// 결제카드사명
	$cardquota			= $payment['cardquota'];							// 할부개월수
	$cardinterest		= $payment['cardinterest'];							// 무이자여부
	$cardci				= $payment['cardci'];								// 체크카드여부
	$cardbin			= $payment['cardbin'];								// 카드BIN번호
	$cardpoint			= $payment['cardpoint'];							// 카드사포인트사용여부
	$tid				= $payment['tid'];									// 거래아이디
	if(!isset($payment['financename'])) $payment['financename'] = ''; // 결제카드사가 선언되지 않았을 경우 빈 문자열로 초기화
	$financename		= iconv("euc-kr","utf-8",$payment['financename']);	// 결제카드사
	$financename_num	= $payment['financename_num'];						// 결제카드번호
	$account_dueDate	= $payment['account_dueDate'];			// 가상계좌 입금기한
	$vir_secret		= $payment['vir_secret'];								// 가상계좌 입금확인용 secret key

	@make_log(LOG_PATH."order_update_log.log","--START".date("Y/m/d H:i:s",time())."---------\r".print_r($payment, true).$status."\r".$orderid."\r".$financecode."\r".$accountnumber."\r".$tno."\r".$esw_check."\r".$esw_stats."\r".$totalprice."\r".$paymentkind."\r".$pay_method."\r");

	// 주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid='$orderid'";
	$result = query($sql);
	$order_info = sql_fetch_obj($result);

	if($pay_method == "PV" || $pay_method=="PN" || $pay_method=="PB") {

		if($paymentkind=='allthegate'){//올더게이트 은행 코드
			switch($financecode){
				case "03":$financecode = "기업은행";break;
				case "04":$financecode = "국민은행";break;
				case "05":$financecode = "외환은행";break;
				case "06":$financecode = "국민은행(구 주택은행)";break;
				case "07":$financecode = "수협중앙회";break;
				case "11":$financecode = "농협중앙회";break;
				case "12":$financecode = "단위농협";break;
				case "20":$financecode = "우리은행";break;
				case "21":$financecode = "조흥은행";break;
				case "23":$financecode = "제일은행";break;
				case "32":$financecode = "부산은행";break;
				case "71":$financecode = "우체국";break;
				case "81":$financecode = "하나은행";break;
				case "88":$financecode = "신한은행";break;
				default:$financecode = $financecode;
			}
		}else if($paymentkind=='dacom'){//데이콤 은행 코드
			switch($financecode){
				case "02":$financecode = "산업은행";break;
				case "03":$financecode = "기업은행";break;
				case "05":$financecode = "외환은행";break;
				case "06":$financecode = "국민은행";break;
				case "07":$financecode = "수협";break;
				case "11":$financecode = "농협";break;
				case "20":$financecode = "우리은행";break;
				case "23":$financecode = "제일은행";break;
				case "26":$financecode = "신한은행";break;
				case "27":$financecode = "씨티은행";break;
				case "31":$financecode = "대구은행";break;
				case "32":$financecode = "부산은행";break;
				case "34":$financecode = "광주은행";break;
				case "35":$financecode = "제주은행";break;
				case "37":$financecode = "전북은행";break;
				case "39":$financecode = "경남은행";break;
				case "45":$financecode = "새마을금고";break;
				case "48":$financecode = "신협";break;
				case "71":$financecode = "우체국";break;
				case "81":$financecode = "하나은행";break;
				case "88":$financecode = "신한은행";break;
				default:$financecode = $financecode;
			}
		}else if($paymentkind=='inicis'){//이니시스 은행코드
			switch($financecode){
					case "03":$financecode = "기업은행";break;
					case "04":$financecode = "국민은행";break;
					case "05":$financecode = "외환은행";break;
					case "06":$financecode = "국민은행(구 주택은행)";break;
					case "07":$financecode = "수협중앙회";break;
					case "11":$financecode = "농협중앙회";break;
					case "12":$financecode = "단위농협";break;
					case "20":$financecode = "우리은행";break;
					case "21":$financecode = "조흥은행";break;
					case "23":$financecode = "제일은행";break;
					case "32":$financecode = "부산은행";break;
					case "71":$financecode = "우체국";break;
					case "81":$financecode = "하나은행";break;
					case "88":$financecode = "신한은행";break;
				}
		}else if($paymentkind=='kcp'){//kcp 은행코드
			switch($financecode){
					case "39":$financecode = "경남은행";break;
					case "34":$financecode = "광주은행";break;
					case "04":$financecode = "국민은행";break;
					case "03":$financecode = "기업은행";break;
					case "11":$financecode = "농협";break;
					case "31":$financecode = "대구은행";break;
					case "64":$financecode = "산림조합";break;
					case "89":$financecode = "케이뱅크";break;
					case "45":$financecode = "새마을금고";break;
					case "07":$financecode = "수협";break;
					case "88":$financecode = "신한은행";break;
					case "48":$financecode = "신협";break;
					case "20":$financecode = "우리은행";break;
					case "71":$financecode = "우체국";break;
					case "35":$financecode = "제주은행";break;
					case "54":$financecode = "HSBC";break;
					case "23":$financecode = "SC제일은행";break;
					case "02":$financecode = "산업은행";break;
					case "37":$financecode = "전북은행";break;
					case "81":$financecode = "KEB하나은행";break;
					case "32":$financecode = "부산은행";break;
					case "27":$financecode = "한국시티은행";break;
					case "26":$financecode = "(구)신한은행";break;
					case "06":$financecode = "(구)주택은행";break;
					case "05":$financecode = "(구)외환은행";break;
					case "21":$financecode = "(구)조흥은행";break;
					case "25":$financecode = "(구)서울은행";break;
					case "53":$financecode = "(구)씨티은행";break;
					case "83":$financecode = "(구)평화은행";break;
				}
		}else{
			if($financecode == "05") $financecode = "외환은행";
			else if($financecode == "06") $financecode = "국민은행";
			else if($financecode == "11") $financecode = "농협은행";
			else if($financecode == "26") $financecode = "신한은행";
			else if($financecode == "81") $financecode = "하나은행";
			else if($financecode == "20") $financecode = "우리은행";
			else $financecode = $financecode;
		}
		if($accountname){$accountname = " , account_name='$accountname'";}
		if($financecode) {
			$pv_account = ", account='".$financecode." ".$accountnumber."'".$accountname.", account_dueDate='".$account_dueDate."',vir_secret='".$vir_secret."'";
		} else {
			$pv_account = ", account='".$accountnumber."'".$accountname;
		}

		if($pay_method=="PB") {
			$tmp_account = trim($accountnumber);
			list($obkname, $oaccount, $odepositor) = explode(" ",$tmp_account);
			$bk_actnumber  = ", bk_actnumber='".trim(str_replace("-", "", $oaccount))."' ";
		}

	}

	if($pay_method == "PC" || $pay_method == "KK"){
		if($pay_method == "PC"){
			$financename_     = ", financename='$financename$cardname' ";
			$financename_num_ = ", financename_num='$financename_num' ";
			$cardquota_       = ", cardquota = '$cardquota' ";
			$cardinterest_    = ", cardinterest = '$cardinterest' ";
		} 
		$cardname = ", cardname='$cardname' ";
	}

	if($pay_method == "PV" || $pay_method == "PN" || $pay_method == "PB") {
		$cash_num_        = ", cash_num = '$cash_num' ";
		$cash_type_       = ", cash_type = '$cash_type' ";
		$cash_type_yn_    = ", cash_type_yn = '$cash_type_yn' ";
		$cash_flag_       = ", cash_flag = '$cash_flag' ";
	}

	if($pay_method == "PH") {
		$hppay_telno_       = ", hppay_telno = '$hppay_telno' ";
	}

	// 이니시스 결제시 $financename이 없을 때 cardcode로 결제카드명 찾기 2020-03-19 정나혜
	if($pay_method == "PC" && $paymentkind=='inicis' && $financename == '' && $cardcode ) {
		switch($cardcode){
			case "01":$financename = "하나(외환)";break;
			case "03":$financename = "롯데";break;
			case "04":$financename = "현대";break;
			case "06":$financename = "국민";break;
			case "11":$financename = "BC";break;
			case "12":$financename = "삼성";break;
			case "14":$financename = "신한";break;
			case "21":$financename = "해외비자";break;
			case "22":$financename = "해외마스터";break;
			case "23":$financename = "해외JCB";break;
			case "26":$financename = "중국은련";break;
			case "32":$financename = "광주";break;
			case "33":$financename = "전북";break;
			case "34":$financename = "하나";break;
			case "35":$financename = "산업카드";break;
			case "41":$financename = "NH";break;
			case "43":$financename = "씨티";break;
			case "44":$financename = "우리";break;
			case "48":$financename = "신협체크";break;
			case "51":$financename = "수협";break;
			case "52":$financename = "제주";break;
			case "54":$financename = "MG새마을금고체크";break;
			case "55":$financename = "케이뱅크";break;
			case "56":$financename = "카카오뱅크";break;
			case "71":$financename = "우체국체크";break;
			case "95":$financename = "저축은행체크";break;
		}
		
		$financename_ = ", financename='$financename' ";
	}	


	if(!isset($tno)) $tno = '';
	if(trim($tno)){//승인번호,TID값
		$tno=" , tno='$tno' ";
	}else{
		$tno="";
	}
	
	if(!isset($kakao_nonRepToken)) $kakao_nonRepToken = '';
	if(trim($kakao_nonRepToken)){//카카오페이 부인방지토큰 외 기타
		$kakao_nonRepToken = " , cardcode='$cardcode' $cardname, cardquota='$cardquota', cardinterest='$cardinterest', cardci='$cardci', cardbin='$cardbin', cardpoint='$cardpoint', tid='$tid', kakao_nonRepToken='$kakao_nonRepToken' ";
	}else{
		$kakao_nonRepToken = "";
	}

	// 세금계산서 업데이트
	if(!strcmp($oper_info['tax_use'], "Y")) {
		$sql = "
			update wiz_tax 
			   set tax_date = now() 
			 where orderid = '$orderid'
		";
		query($sql);
	}

	if($status == "OY") {

		// 추천인 적립금 업데이트
		if($oper_info['reserve_recom'] > 0){
			if(!empty($order_info->send_id)){
				$ch_sql = "select recom from wiz_member where id='$order_info->send_id' ";
				$ch_result = query($ch_sql);
				$ch_row = sql_fetch_obj($ch_result);

				$reserve_msg_ch = "추천인(".$order_info->send_id.") 적립금 ";

				$sql_com = "";
				$sql_com .= " memid                  = '".$ch_row->recom."'                       ";
				$sql_com .= " , reservemsg           = '".$reserve_msg_ch."'                      ";
				$sql_com .= " , reserve              = '".$oper_info['reserve_recom']."'          ";
				$sql_com .= " , orderid              = '".$orderid."'                             ";
				$sql_com .= " , wdate                = now()                                      ";

				$sql = "insert into wiz_reserve set {$sql_com} ";
				query($sql);

			}
		}

		$oper_time = ", pay_date = now()";

		//세금계산서 발급완료
		if($cash_num!="" && $cash_num!="0"){
			$sql = "
				update wiz_tax 
				   set tax_pub='Y' 
				     , cash_num='$cash_num' 
				 where orderid = '$orderid'
			";
			query($sql);
		}
		//가상계좌일때 입금순서
		if(trim($cash_segno ?? '')){
			$cash_sql=", cash_segno='$cash_segno'  ";
		}
	
	}
	// 에스크로 체크한경우 에스크로 상태 업데이트
	if($oper_info['pay_escrow'] == "Y") {
		if($pay_method == "PV" || $pay_method == "PN" || $pay_method == 'PC'  || $pay_method == 'PH') //무통장 제외 모든 결제 에스크로 적용되도록 2020-03-10
			$escrow_=",escrow_check='$es_check',escrow_stats='$es_stats'";
	}

	// 쿠폰
	if($order_info->coupon_idx != "") {

		$today = date('Y-m-d');
		$coupon_list = explode("|", $order_info->coupon_idx);
		if(is_array($coupon_list)) {
			foreach($coupon_list as $c_idx => $cidx) {

				$c_sql = "select * from wiz_mycoupon where idx='".$cidx."'";
				$c_result = query($c_sql);
				$c_row = sql_fetch_arr($c_result);

				if($c_row['coupon_use'] == "N" && $c_row['coupon_sdate'] <= $today && $c_row['coupon_edate'] >= $today) {
					$sql = "
						update wiz_mycoupon 
						   set coupon_use = 'Y' 
						 where idx = '".$cidx."'
					";
					query($sql);
				} else {

				}
			}
		}

	}

	// 주문상태 변경
	$sql = "
		UPDATE wiz_order 
		   SET status='$status' 
		     $tno 
			 $pv_account 
			 $oper_time 
			 $escrow_ 
			 $other_update 
			 $cash_sql 
			 $kakao_nonRepToken 
			 $financename_ 
			 $financename_num_ 
			 $cardquota_ 
			 $cardinterest_ 
			 $cardname 
			 $bk_actnumber
			 $cash_num_
			 $cash_type_
			 $cash_type_yn_
			 $cash_flag_
			 $hppay_telno_
		 WHERE orderid='$orderid'
	";
	$result = query($sql);

	if(!$result) {
		@make_log(LOG_PATH."order_update_log.log","\r------------order update error-------------------\r".$sql."\r\r");
	} else {
		$isDBOK = true;
	}
	@make_log(LOG_PATH."order_update_log.log","\r".$sql."\r---------------------------End----------------------------------\r");

	$sql1 = "UPDATE wiz_basket SET status='$status' WHERE orderid='$orderid'";
	$result1 = query($sql1);

	return $result;
}

// 배송정보 전송
function escrow_delivery($order_info, $oper_info, $delsno="", $deldate="", $del_com="") {
	if(!$del_com) $del_com = $order_info['del_com']; //배송사 정보 누락시 추가 2020-03-10
	if(!strcmp($order_info['escrow_check'], "Y") && !strcmp($order_info['escrow_stats'], "IN")){ // 에스크로 주문건
		if(!strcmp($oper_info['pay_agent'], "DACOM")) {
			$oid	   = trim($order_info['orderid']);				// 주문번호
			$productid = trim($order_info['orderid']);				// 상품ID
			$dlvtype   = "03";										// 등록내용구분
			$dlvdate   = trim($deldate);							// 발송일자

			// 배송회사코드
			switch($oper_info['del_com']) {
				case "CJ대한통운" : $dlvcompcode = "CJ"; break;
				case "한진택배" : $dlvcompcode = "HJ"; break;
				case "일양로직스" : $dlvcompcode = "IY"; break;
				case "엘로우캡" : $dlvcompcode = "YC"; break;
				case "롯데택배" : $dlvcompcode = "HD"; break;
				case "경동택배" : $dlvcompcode = "KD"; break;
				case "로젠택배" : $dlvcompcode = "LG"; break;
				case "KGB택배" : $dlvcompcode = "KB"; break;
				case "합동택배" : $dlvcompcode = "HA"; break;
				case "KG로지스" : $dlvcompcode = "FE"; break;
				case "우편등기" : $dlvcompcode = "RP"; break;
				case "GTX로지스택배" : $dlvcompcode = "GT"; break;
				case "천일택배" : $dlvcompcode = "CI"; break;
				case "우체국택배" : $dlvcompcode = "PO"; break;
				case "대신택배" : $dlvcompcode = "DS"; break;
				case "편의점택배" : $dlvcompcode = "CN"; break;
			}

			$dlvcomp = trim($oper_info['del_com']);					// 배송회사명
			$dlvno   = trim($delsno);								// 운송장번호

			if(empty($dlvdate)) {
				echo "<script language='javascript'>alert('발송일자가 누락되었습니다.');self.close();</script>";
			}

			if(empty($dlvno)) {
				echo "<script language='javascript'>alert('운송장번호가 누락되었습니다.');self.close();</script>";
			}

			if(!empty($dlvdate) && !empty($dlvno)) {
				echo "<script>window.open('/twcenter/product/dacom/escrow_delivery.php?oid=$oid&productid=$productid&dlvtype=$dlvtype&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvcomp=$dlvcomp&dlvno=$dlvno', 'Delivery', 'width=250px,height=220px,scrollbars=yes');</script>";
			}
		} else if(!strcmp($oper_info['pay_agent'], "KCP")) {				// KCP
			/*
			작업자		: 이상민
			작업일시	: 2019-08-05
			작업내용	: KCP 에스크로 결제시 배송처리 통보
			*/
			switch($order_info["pay_method"]){
				case "PC":
					$escrow_mod = false;
					break;
				case "PN":
					$escrow_mod = true;
					break;
				case "PV":
					$escrow_mod = false;
					break;
			}

			if($escrow_mod == true && $del_com != ""){
				$post_data["mod_type"]		= "STE1";
				$post_data["pay_method"]	= $order_info["pay_method"];
				$post_data["tno"]			= $order_info["tno"];
				$post_data["orderid"]		= $order_info["orderid"];
				$post_data["deli_numb"]		= $order_info["deliver_num"];
				$post_data["deli_corp"]		= $del_com;
				$post_data["is_twcenter"]		= true;

				$url = WAY_HOST."/twcenter/product/nhnkcp/order_cancel.php";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$ch_result = curl_exec($ch);

				$escrow_result = json_decode($ch_result, true);

				switch($escrow_result["result"]){
					case "success":
						break;
					case "fail":
						$logfile = fopen( $_SERVER["DOCUMENT_ROOT"]."/twcenter/product/nhnkcp/log/".date("Ym")."/escrow_deli_".date("Ymd").".log", "a+" );
						fwrite( $logfile, "\r\n\r\n");
						fwrite( $logfile,"************************************************");
						fwrite( $logfile,"\r\n");
						fwrite( $logfile, date("Y-m-d H:i:s",time()). " result : \r\n");
						fwrite( $logfile, "fail : ".$escrow_result["msg"]."\r\n");
						fwrite( $logfile, "orderid : ".$order_info["orderid"]."\r\n");
						fwrite( $logfile, "\r\n");
						fwrite( $logfile,"************************************************");
						fclose($logfile);
						break;
				}
			}
		} else if(!strcmp($oper_info['pay_agent'], "INICIS")) {				// INICIS
			/*	DEMO UPDATE 20200305 START
				작업자 : 김나연
				작업일시 : 2020-03-05 2020-03-10(임서연 반영)
				작업내용 : 에스크로 결제시 배송정보 전송 수정 */

			$escrow_mod = ($order_info['escrow_check'] == 'Y') ? true : false;

			$del_comAr = explode("|", $del_com);

			switch($del_comAr[0]){
				case "대한통운":
					$dlv_exCode = "cjgls";
					break;
				case "아주택배":
					$dlv_exCode = "ajutb";
					break;
				case "KT로지스":
					$dlv_exCode = "ktlogistics";
					break;
				case "현대택배":
					$dlv_exCode = "hyundai";
					break;
				case "CJ대한통운":
				case "대한통운":
					$dlv_exCode = "cjgls";
					break;
				case "한진택배":
					$dlv_exCode = "hanjin";
					break;
				case "트라넷":
					$dlv_exCode = "tranet";
					break;
				case "하나로택배":
					$dlv_exCode = "Hanaro";
					break;
				case "사가와익스프레스":
					$dlv_exCode = "Sagawa";
					break;
				case "SEDEX":
					$dlv_exCode = "sedex";
					break;
				case "KGB택배":
					$dlv_exCode = "kgbls";
					break;
				case "로젠택배":
					$dlv_exCode = "kgb";
					break;
				case "옐로우캡":
					$dlv_exCode = "yellow";
					break;
				case "삼성HTH":
					$dlv_exCode = "hth";
					break;
				case "KG로지스":
				case "KG로지스택배":
					$dlv_exCode = "dongbu";
					break;
				case "우체국택배":
					$dlv_exCode = "EPOST";
					break;
				case "우편등기":
					$dlv_exCode = "registpost";
					break;
				case "기타택배":
					$dlv_exCode = "9999";
					break;
			}

			$dlv_exName = $del_comAr[0];

			if($order_info["deliver_price"] > 0) $dlv_charge = "BH";
			else $dlv_charge = "SH";

			if($deldate == "") $deldate = date("Y-m-d H:i:s", time());

			$dlv_invoiceday = date("Y-m-d H:i:s", strtotime($deldate));

			/*
			tid			: 거래아이디 = tno
			mid			: 상점아이디
			oid			: 상점 주문번호 = orderid
			EscrowType	: 에스크로 등록형태 등록 = I, 변경 = U
			invoice		: 운송장번호 = delsno
			dlv_name	: 배송등록자 = 관리자이름?
			dlv_exCode	: 택배사 코드 
			dlv_exName	: 택배사명
			dlv_charge	: 배송비 지급형태 무료배송 = SH, 유료배송 = BH
			dlv_invoiceday : 배송등록 확인일자 = 현재시점?
			sendName	: 송신자 이름 = 관리자이름?
			sendPost	: 송신자 우편번호 = 쇼핑몰상 주소지?
			sendAddr1	: 송신자 주소1 
			sendAddr2	: 송신자 주소2
			sendTel		: 송신자 전화번호 = 관리자 전화번호? 쇼핑몰 전화번호?
			recvName	: 수신자 이름
			recvPost	: 수신자 우편번호 = rece_post
			recvAddr	: 수신자 주소1 = rece_address
			recvTel		: 수신자 전화번호 = rece_hphone
			price		: 상품가격 = total_price
			*/
			$sql = "select * from wiz_siteinfo";
			$result = query($sql);
			$site_info = sql_fetch_arr($result);

			$sql_b = "select * from wiz_basket where orderid='".$order_info["orderid"]."'";
			$res_b = query($sql_b);
			$count_b = sql_fetch_row($res_b);
			$row_b = sql_fetch_arr($res_b);
			$goods = $row_b['prdname'];
			if($count_b > 1) $goods .= " 외".($count_b - 1);

			if($escrow_mod == true && $del_com != ""){

				$tid				= $order_info["tno"];
				$oid				= $order_info["orderid"];
				$EscrowType		= ($order_info['escrow_stats'] == 'SE') ? "U" : "I";
				$invoice			= $delsno;
				$dlv_name		= $site_info["site_name"];
				$dlv_exCode		= $dlv_exCode;
				$dlv_exName		= $dlv_exName;
				$dlv_charge		= $dlv_charge;
				$dlv_invoiceday	= $dlv_invoiceday;
				$sendName		= $site_info["site_name"];
				$sendPost		= $site_info["com_post"];
				$sendAddr1		= $site_info["com_address"];
				$sendAddr2		= "";
				$sendTel			= $site_info["com_tel"];
				$recvName		= $order_info["rece_name"];
				$recvPost			= $order_info["rece_post"];
				$recvAddr		= $order_info["rece_address"];
				$recvTel			= $order_info["rece_hphone"];
				$dlv_goodscode		= $row_b["prdcode"];
				$goods			= $goods;
				$price				= $order_info["total_price"];

				include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/inicis/order_delivery.php";

				$escrow_result = array("result"=>$resultCode, "msg"=>$resultMsg);
				
				if($escrow_result["result"] == 00){
						$sql_up = "update wiz_order set escrow_stats='SE' where orderid='".$order_info["orderid"]."'";
						query($sql_up);
				} else {
						$logfile = fopen( $_SERVER["DOCUMENT_ROOT"]."/twcenter/product/inicis/log/".date("Ym")."/escrow_deli_".date("Ymd").".log", "a+" );
						fwrite( $logfile, "\r\n\r\n");
						fwrite( $logfile,"************************************************");
						fwrite( $logfile,"\r\n");
						fwrite( $logfile, date("Y-m-d H:i:s",time()). " result : \r\n");
						fwrite( $logfile, "fail : ".$escrow_result["msg"]."\r\n");
						fwrite( $logfile, "orderid : ".$order_info["orderid"]."\r\n");
						fwrite( $logfile, "\r\n");
						fwrite( $logfile,"************************************************");
						fclose($logfile);
				}
			}

			/*	DEMO UPDATE 20200305 END 2020-03-10(임서연 반영)*/
		}
	}
}

// 주문상세 페이지 주문취소 버튼
function get_cancel_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $status;
	global $cancel_btn;

	$tno       = $order_info['tno'];					// 거래번호
	$pay_agent = strtolower($oper_info['pay_agent']);	// PG사

	$ord_info = sql_fetch("select pay_method from wiz_order where orderid = '".$orderid."' ");
	$pay_method = $ord_info['pay_method'];

	if($oper_info['ord_cancel_type'] == "C") {
		if($pay_method == "PB" || ($pay_method == "PV" && ($status == "OR" || $status == "OY"))) {
			$cancel_btn_s = true;
		} else {
			$cancel_btn_s = false;
		}
	} else {
		$cancel_btn_s = true;
	}

?>

	<script language="JavaScript">
	<!--

	// 주문취소
	function orderCancel(orderid, status) {

		var cancel_status_array = new Array();
		cancel_status_array[0] = "OR";
		cancel_status_array[1] = "OY";
		cancel_status_array[2] = "DI";
		cancel_status_array[3] = "DC";

		if($.inArray(status, cancel_status_array) < 0){
			switch(status){
				case "RD":
					alert("이미 취소요청한 상태입니다.");
					break;
				case "RC":
				case "OC":
					alert("취소처리가 완료된 상태입니다.");
					break;
				default:
					alert("주문을 취소할 수 없는 단계입니다. 관리자에게 문의해주세요.");
			}
		} else {
			var url = "/comm/shop/order_cancel.php?orderid=" + orderid;
			window.open(url, "orderCancel", "height=450, width=470, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, left=300, top=300");
		}
	}

	function orderPgCancel(orderid, status) {

		var cancel_status_array = new Array();
		cancel_status_array[0] = "OR";
		cancel_status_array[1] = "OY";
	//	cancel_status_array[2] = "DI";
	//	cancel_status_array[3] = "DC";

		if($.inArray(status, cancel_status_array) < 0){
			switch(status){
				case "RD":
					alert("이미 취소요청한 상태입니다.");
					break;
				case "RC":
				case "OC":
					alert("취소처리가 완료된 상태입니다.");
					break;
				default:
					alert("주문을 취소할 수 없는 단계입니다. 관리자에게 문의해주세요.");
			}
		} else {

			var pay_agent = "<?php echo $pay_agent ?>";
			if (confirm("주문내역을 취소하시겠습니까?")) {

				var pay_agent = "<?php echo $pay_agent ?>";
				var params = "";
					params += "orderid=" + orderid;
					<?php 
					switch($pay_agent){
						case "dacom":
					?>
					params += "&LGD_TID=<?php echo $tno ?>";
					<?php
							break;
						case "kcp":
							switch($status){
								case "OY":
								case "OR":
									$mod_type = "STE2";
									break;
								case "DI":
								case "DC":
									$mod_type = "STE3";
									break;
							}
					?>
					params += "&tno=<?php echo $tno ?>";
					params += "&mod_type=<?php echo $mod_type; ?>";
					<?php
							break;
						case "inicis":
					?>
					params += "&tid=<?php echo $tno ?>";
					<?php
							break;
					}
					?>
				$.ajax({
					type: "post"
					, cache: false
					, url: "/twcenter/product/" + pay_agent + "/order_cancel.php"
					, data: params
					, dataType: 'json'
					, beforeSend: function() {
						console.log(params);
					}
					, success: function(data) {
						console.log(data);
						if(data.result == "success"){
							alert(data.msg);
							document.location.reload();
						} else if(data.result == "fail"){
							alert(data.msg);
						}
					}
					, error: function(data){
						console.log("error : " +data);
					}
				});

			}
			return;

		
		}
	}

	// 주문취소 해제
	function orderRemoval(orderid)
	{
		if(confirm("주문취소를 해제하시겠습니까?")){
			document.location='/twcenter/product/order_status.php?orderid=' + orderid;
		}
	}

	//-->
	</script>

<?php

	if(mobile_check() == true) {
		//$cancel_btn = "<input type=\"button\" onClick=\"javascript:orderCancel('".$orderid."','".$status."')\" value=\"주문취소\" class=\"btn_orderCancel\">";
		if($cancel_btn_s) {
			$cancel_btn = "<input type=\"button\" onClick=\"javascript:orderCancel('".$orderid."','".$status."')\" value=\"주문취소\" class=\"btn_orderCancel\">";
		} else {
			$cancel_btn = "<input type=\"button\" onClick=\"javascript:orderPgCancel('".$orderid."','".$status."')\" value=\"주문취소\" class=\"btn_orderCancel\">";
		}

	} else {
		if($cancel_btn_s) {
			$cancel_btn = "<input type=\"button\" onClick=\"javascript:orderCancel('".$orderid."','".$status."')\" value=\"주문 취소 요청\" class=\"btn_type3\">";
		} else {
			$cancel_btn = "<input type=\"button\" onClick=\"javascript:orderPgCancel('".$orderid."','".$status."')\" value=\"주문 취소 요청\" class=\"btn_type3\">";
		}
	}

}

// 주문상세 페이지 교환요청 버튼
function get_exchange_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $exchange_btn;
	global $_prdidx;

?>

	<script language="JavaScript">
	<!--

	function orderexchange(orderid,prdidx)
	{
		<?php if($order_info['status'] == "CD"){ ?>
		alert("이미 교환요청한 상태입니다.");
		<?php }else if($order_info['status'] == "CC"){ ?>
		alert("교환처리가 완료된 상태입니다.");
		<?php }else{ ?>
		var url = "/twcenter/product/order_change.php?orderid=" + orderid + "&idx=" + prdidx;
	  window.open(url, "orderexchange", "height=270, width=470, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, left=300, top=300");
		<?php } ?>
	}

	// 주문취소 해제
	function orderRemoval(orderid)
	{
		if(confirm("주문취소를 해제하시겠습니까?")){
			document.location='/twcenter/product/order_status.php?orderid=' + orderid;
		}
	}

	//-->
	</script>

<?php

	//$exchange_btn = "<a href=\"javascript:orderexchange('".$orderid."','".$_prdidx."');\"><img src=\"/twcenter/images/but_exchange.gif\" border=\"0\"  alt=\"교환요청\"></a>";
	$exchange_btn = "<input type=\"button\" onClick=\"javascript:orderexchange('".$orderid."','".$_prdidx."')\" value=\"교환 요청\" class=\"btn_type3_1\">";
}
// 주문상세 페이지의 에스크로 버튼
function get_escrow_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $escrow_btn;

	if(!strcmp($oper_info['pay_test'], "Y")) {//테스트
		$oper_info['pay_id'] = "tanywiz";
		$oper_info['pay_key'] = "6f51f77a2b2222d642e20e445101a35f";
		$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		$mid = $oper_info['pay_id'];
		$pay_key = $oper_info['pay_key'];
		$tport = ":7085";
		$_htt = "";
	}else{//실거래
		$platform	= "service";
		$mid = $oper_info['pay_id'];
		$pay_key = $oper_info['pay_key'];
		$tport = "";
		$_htt = "s";
	}

	if($oper_info['pay_agent'] == "DACOM") {//데이콤
?>

	<script language="JavaScript" src="http://pg.dacom.net/mert/pg/eCredit.js"></script>
	<SCRIPT language=JavaScript src="http://pgweb.dacom.net<?php echo $tport ?>/js/DACOMEscrow.js"></SCRIPT>
<?php
	}
?>
	<script language="JavaScript">
	<!--

	// 에스크로 수령확인 Dacom
	function linkESCROW(oid)
	{
	   var merturl = "http://<?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/dacom/escrow_save.php";
	   checkDacomESC ("<?php echo $mid ?>", oid,'');
	   location.reload();
	}

	// 에스크로 수령확인 Kcp
	function linkESCROW_kcp(site_cd,tno,order_no)
	{
	   var url = "http://twcenter.kcp.co.kr/Modules/Sale/ESCROW/n_order_confirm.jsp?site_cd=" + site_cd + "&tno=" + tno + "&order_no=" + order_no;
	   window.open(url, "linkESCROW", "height=440, width=630, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=300, top=300");
	   location.reload();
	}

	// 에스크로 구매취소 Kcp
	function linkESCROW_cancel_kcp(site_cd,tno,order_no)
	{
	   var url = "http://twcenter.kcp.co.kr/Modules/Sale/ESCROW/n_deli_cancel.jsp?site_cd=" + site_cd + "&tno=" + tno + "&order_no=" + order_no;
	   window.open(url, "linkESCROW", "height=440, width=630, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=300, top=300");
	   location.reload();
	}

	//-->
	</script>
<?php
	///////////////////////////////////////////
	//에스크로 사용일경우 수령확인 버튼 출력 //
	///////////////////////////////////////////
	if($oper_info['pay_escrow'] == "Y"){
		if($oper_info['pay_agent'] == "DACOM") {//데이콤 버튼
			if($order_info['escrow_stats']!='US'){//에스크로 정보 : 수령확인 을안하였으면
				$escrow_btn = "<a href=\"javascript:linkESCROW('".$mid."', '".$orderid."');\"><img src=\"/twcenter/images/but_receok.gif\" border=\"0\" alt=\"수령하기\"></a>";
			}
		} else if($oper_info['pay_agent'] == "KCP") {//KCP버튼
			$escrow_btn = "<img src=\"/twcenter/images/but_receok.gif\" border=\"0\" onClick=\"linkESCROW_kcp('".$oper_info['pay_id']."', '".$order_info['tno']."', '".$orderid."')\" style=\"cursor:pointer\" alt=\"수령하기\">";
		}
	}
}

// 주문상세 페이지 세금계산서 버튼
function get_tax_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $tax_btn;

?>
	<script language="JavaScript">
	<!--

	// 세금계산서 출력
	function printTax(orderid) {
	<?php
	$print_tax_check = false;
	$status = order_status($order_info['status']);
	$tax_status = order_status($oper_info['tax_status']);

	if(!strcmp($order_info['status'], "OC") || !strcmp($order_info['status'], "RD") || !strcmp($order_info['status'], "RC")) {
	?>
		alert("주문취소로 세금계산서가 폐기되었습니다.");
	<?php
	} else {

		if(!strcmp($oper_info['tax_status'], "OY")) {
			if(strcmp($order_info['status'], "OR")) {
				$print_tax_check = true;
			}
		} else if(!strcmp($oper_info['tax_status'], "DC")) {
			if(!strcmp($order_info['status'], "DC") || !strcmp($order_info['status'], "CC")) {
				$print_tax_check = true;
			}
		}

		if($print_tax_check) {
	?>
		var url = "/twcenter/product/print_tax.php?orderid=" + orderid;
		window.open(url, "taxPub", "height=750, width=670, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
	<?php
		} else {
	?>
		alert("현재 주문상태(<?php echo $status ?>)에서는 세금계산서를 발급할 수 없습니다. \n\n<?php echo $tax_status ?> 이후에 세금계산서 발행이 가능합니다.");
	<?php
		}
	}
	?>

	}
	//-->
	</script>
<?php
	if(!strcmp($oper_info['tax_use'], "Y")) {
		$tax_btn = "<span onClick=\"printTax('".$orderid."')\" style=\"cursor:pointer\">[세금계산서]</span>";
	}
}

// 영수증 출력
function receipt_link($oper_info, $ord_info) {

	$card_icon = "카드영수증";
	$cash_icon = "현금영수증";

	$cash_arr = array("PN","PV","PB");
	$status_array = array("OY","DR","DI","DC");

	/** -- -------------------------------------------- -- **\
	 ** 데이콤
	\** -- -------------------------------------------- -- **/
	if(!strcmp($oper_info['pay_agent'], "DACOM")) {

		/**
		 * 테스트
		 */
		if(!strcmp($oper_info['pay_test'], "Y")) {//테스트
			$oper_info['pay_id'] = "t".$oper_info['pay_id'];
			$pay_key             = $oper_info['pay_key'];
			$platform	         = "test";
			$mid                 = $oper_info['pay_id'];
			$pay_key             = $oper_info['pay_key'];
		/**
		 * 실거래
		 */
		} else {
			$platform	         = "service";
			$mid                 = $oper_info['pay_id'];
			$pay_key             = $oper_info['pay_key'];
		}

		/**
		 * 결제방법 출력
		 */
		switch($ord_info->pay_method){
			case "PC"://신용카드
				$pay_method = "SC0010";break;
			case "PN"://계좌이체
				$pay_method = "SC0030";break;
			case "PV"://가상계좌
				$pay_method = "SC0040";break;
			case "PH";//휴대폰
				$pay_method = "SC0060";break;
		}

		echo "<script language='JavaScript' src='/twcenter/product/dacom/receipt_link.js'></script>";

		/**
		 * 신용카드
		 */
		if(!strcmp($ord_info->pay_method, "PC")) {

			$authdata = md5($mid.$ord_info->tno.$pay_key);
			echo "<a href=\"javascript:showReceiptByTID('".$mid."', '".$ord_info->tno."', '".$authdata."','".$platform."')\">".$card_icon."</a>";


		/**
		 * 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		 */
		} else if(in_array($ord_info->pay_method,$cash_arr)) {

			switch($ord_info->pay_method) {
				case "PN" : $pay_method = "BANK"; break;
				case "PV" : $pay_method = "CAS"; break;
				case "PB" : $pay_method = "CR"; break;
			}

			//가상계좌입금순서
			$cash_segno="";
			if($ord_info->pay_method=="PV"){
				$cash_segno = $ord_info->cash_segno;
			}

			if($oper_info['cash_receipts_use'] == "Y" && $oper_info['receipts_issue'] == "A") {
				
				if(in_array($ord_info->status, $status_array)) {
					$cash_icon = "현금영수증";
				} else {
					$cash_icon = "";
				}
			}

			echo "<a href=\"javascript:showCashReceipts('".$mid."','".$ord_info->orderid."','".$cash_segno."','".$pay_method."','".$platform."')\">".$cash_icon."</a>";

		/**
		 * 무통장입금
		 */
		} else {

			$authdata = md5($mid.$ord_info->orderid.$pay_method.$pay_key);
			echo "<a href=\"javascript:showReceiptByOID('".$mid."','".$ord_info->orderid."','".$pay_method."','".$authdata."','".$platform."')\"></a>";

		}

	/** -- -------------------------------------------- -- **\
	 ** KCP
	\** -- -------------------------------------------- -- **/
	} else if(!strcmp($oper_info['pay_agent'], "KCP")) {

		$params = "";
		$params .= "cmd=card_bill";
		$params .= "&tno=".$ord_info->tno;
		$params .= "&order_no=".$ord_info->orderid;
		$params .= "&trade_mony=".$ord_info->total_price;

		$params2 = "";
		$params2 .= "cmd=cash_bill";
		$params2 .= "&cash_no=".$ord_info->cash_num;
		$params2 .= "&order_id=".$ord_info->orderid;
		$params2 .= "&trade_mony=".$ord_info->total_price;

		/**
		 * 테스트
		 */
		if(!strcmp($oper_info['pay_test'], "Y")) {
			$go_url = "https://testtwcenter8.kcp.co.kr/assist/bill.BillActionNew.do";
		} else {
			$go_url = "https://twcenter8.kcp.co.kr/assist/bill.BillActionNew.do";
		}

		switch($ord_info->pay_method){
			case "PC":
				$card_url = $go_url."?".$params;
				break;
			case "PV":
				$cash_url = $go_url."?".$params2;
				break;
			case "PN":
				$cash_url = $go_url."?".$params2;
				break;
		}

		/**
		 * 신용카드
		 */
		if(!strcmp($ord_info->pay_method, "PC")) {
?>
			<script language="JavaScript">
			<!--
			function receipt() {
				<?php if(empty($ord_info->tno)) { ?>
					alert("KCP거래번호가 없습니다.");
				<?php } else { ?>
					var url = "<?php echo $card_url ?>";
					window.open(url, "Receipt", "width=455, height=815");
				<?php } ?>
			}
			-->
			</script>
			<a href="javascript:receipt()"><?php echo $card_icon ?></a>
<?php
		/**
		 * 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		 */
		} else if(in_array($ord_info->pay_method,$cash_arr)) {
			if($ord_info->cash_num != ""){
?>
			<script language="JavaScript">
			<!--
			function receiptCash() {
				var url = "<?php echo $cash_url ?>";
				window.open(url, "Receipt", "width=370, height=625"); 
			}
			-->
			</script>
			<a href="javascript:receiptCash()"><?php echo $cash_icon ?></a>
<?php
			}
		/**
		 * 무통장입금
		 */
		} else {

		}

	/** -- -------------------------------------------- -- **\
	 ** ALL THE GATE
	\** -- -------------------------------------------- -- **/
	} else if(!strcmp($oper_info['pay_agent'], "ALLTHEGATE") && $oper_info['kakao_pay_use'] == 'Y') {

		if(!strcmp($oper_info['pay_test'], "Y")) {
			$oper_info['pay_id'] = "aegis";
			$oper_info['pay_key'] = "6f51f77a2b2222d642e20e445101a35f";
		}

		/**
		 * 신용카드
		 */
		if(!strcmp($ord_info->pay_method, "PC")) {
?>
			<script language=javascript>
			<!--
			function show_receipt(approve, send_no, send_dt)
			{
				if("<?php echo $ord_info->status ?>"!= "" && "<?php echo $ord_info->pay_method ?>"=="PC")
				{
					if(parseInt(send_dt) < parseInt("<?php echo date('Ymd') ?>")) {

						window.open("http://www.allthegate.com/support/card_search.html", "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=yes,resizable=no,width=630,height=510,top=0,left=150");

					} else {
						var sRetailer_id = "<?php echo $oper_info['pay_id'] ?>";

						url="http://www.allthegate.com/customer/receiptLast3.jsp"
						url=url+"?sRetailer_id="+sRetailer_id;	// 상점아이디
						url=url+"&approve="+approve;						// 승인번호
						url=url+"&send_no="+send_no;						// 거래고유번호
						url=url+"&send_dt="+send_dt.substring(0,8);		// 승인시각

						window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=420,height=700,top=0,left=150");
					}
				}
				else
				{
					alert("해당하는 결제내역이 없습니다");
				}
			}
			-->
			</script>
			<a href="javascript:show_receipt('<?php echo $ord_info->tno ?>', '<?php echo $ord_info->orderid ?>', '<?php echo substr(str_replace("-", "", $ord_info->pay_date), 0, 8) ?>')"><?php echo $card_icon ?></a>
<?php
		/**
		 * 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		 */
		} else if(in_array($ord_info->pay_method,$cash_arr)) {

		/**
		 * 무통장입금
		 */
		} else {

		}
	} else if(!strcmp($oper_info['pay_agent'], "INICIS")) {
		/*
		작업자명	: 이상민
		작업일시	: 2020-05-22
		작업내용	: 이니시스 신용카드/현금영수증 링크버튼 추가
		*/
		switch($ord_info->pay_method){
			case "PC":
				// 신용카드 매출전표
				echo "<a href=\"javascript:open_receipt('".$ord_info->tno."&noMethod=1');\">매출전표</a>";
				break;
			case "PN":
			case "PV":
				//가상계좌, 계좌이체
				echo "<a href=\"javascript:open_receipt('".$ord_info->tno."&clpaymethod=22');\">현금영수증</a>";
				break;
			
		}

	}

}

// 현금영수증 발급사유
function get_cash_type_name($cash_type) {

	switch($cash_type) {
		case "C" : $name = "사업자 지출증빙용"; break;
		case "P" : $name = "개인소득 공제용"; break;
		default  : $name = ""; break;
	}

	return $name;

}
// 현금영수증 신청정보
function get_cash_type2_name($cash_type2) {

	switch($cash_type2) {
		case "CARDNUM" : $name = "현금영수증 카드번호"; break;
		case "COMNUM" : $name = "사업자번호"; break;
		case "HPHONE" : $name = "휴대전화번호"; break;
		case "RESNO" : $name = "주민등록번호"; break;
		default  : $name = ""; break;
	}

	return $name;

}

function get_rand_str($len=5) {
	$code_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	for($ii = 0; $ii < $len; $ii++) {
$code_number .= $code_char[rand()%strlen($code_char)];
	}

	return $code_number;
}

function xml2array($content, $get_attributes = 1, $priority = 'tag')
{
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return array ();
    }
    $parser = xml_parser_create('');
    $url = $content;
    $url_list = parse_url($url);

    // URL
    if($url_list['host'] != "") {

			if(!($socket = fsockopen($url_list['host'], 80, $errno, $errstr, 5))) { // URL에 소켓 연결
				echo " $errno : $errstr ";
				exit;
			}

			$header = "GET {$url} HTTP/1.0\n\n";
			fwrite($socket, $header);

			$data = '';
			while(!feof($socket)) { $data .= fgets($socket); }
			fclose($socket);

			$data = explode("\r\n\r\n", $data, 2);
			$contents = $data[1];

		// XML Data
		} else {

			$contents=$content;

		}

    //xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); //xml  파서에서 옵션설정 인코딩
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); ///대문자로변경
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); //공백값무시
    xml_parse_into_struct($parser, trim($contents), $xml_values); //읽어들인 xml를 이용해 배열에 xml 구조를 담는다
    xml_parser_free($parser); //파서해제

    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array ();

    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        {
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }

    return ($xml_array);
}

// 우편번호 검색
function get_zipcode_list($address) {

	// 도로명주소 관련 변수
	$site_info['zipcode_key']	= "5C38658E5EBBDFC5AD88D24EC7D80449";	// API키
	$site_info['zipcode_url']	= "ws.didim365.com";									// API주소
	$site_info['zipcode_enc']	= "utf-8";														// 인코딩 : utf-8, utf-8
	$site_info['zipcode_dr']	= "t";																// 도로명주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_jb']	= "t";																// 지번주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_de']	= "f";																// 도로명영어주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_bn']	= "f";																// 대량배달/건물명 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_sp']	= "t";																// 건물번호/지번 제외여부 : t(포함), f(포함안함)

	$search_count = 0;
	if($address) {

		$WS_URL = $site_info['zipcode_url'];

		$GET_URL = "http://".$WS_URL."/address/addr.aspx?sd=" . urlencode("");
		$GET_URL .= "&sg=" . urlencode("");
		$GET_URL .= "&r=x";
		$GET_URL .= "&enc=".$site_info['zipcode_enc'];
		$GET_URL .= "&k=" . urlencode($address);
		$GET_URL .= "&dr=".$site_info['zipcode_dr'];
		$GET_URL .= "&jb=".$site_info['zipcode_jb'];
		$GET_URL .= "&de=".$site_info['zipcode_de'];
		$GET_URL .= "&bn=".$site_info['zipcode_bn'];
		$GET_URL .= "&sp=".$site_info['zipcode_sp'];
		$GET_URL .= "&key=".$site_info['zipcode_key'];
		$GET_URL .= "&ts=" . time();

		$parser = xml2array($GET_URL);

		$doc_el = $parser['Didim365-Address'];

		$Result = $doc_el['Result'];
		$Message = $doc_el['Message'];

		if ($Result == "True")
		{
			$Cnt = $doc_el['Count'];
			if($Cnt == 1) {
				$doc_el['Data']['Item'][0] = $doc_el['Data']['Item'];
			}
			foreach($doc_el['Data']['Item'] as $item)
			{
				//  <항상 포함>
				// zipno : 우편번호
				//  <옵션에 따라 포함>
				// doro : 도로명 주소
				// doroen : 도로면 영문 주소
				// jibun : 지번주소

				$ZipNo	= $item['ZipNo'];
				$Doro	= str_conv($item['Doro'], $site_info['zipcode_enc']);
				$Jibun	= str_conv($item['JiBun'], $site_info['zipcode_enc']);

				if(is_array($item)) {
					$list[$search_count][zip1]	= substr($ZipNo,0,3);
					$list[$search_count][zip2]	= substr($ZipNo,3,3);
					$list[$search_count][set_addr]	= $Doro;
					$list[$search_count][addr]	= $Doro.($Jibun ? '<br/>'.$Jibun : '');
					$list[$search_count][bunji]	= "";
					$list[$search_count][jibun]	= $Jibun;

					$list[$search_count][encode_addr] = urlencode($list[$search_count][addr]);
					$search_count++;
				}
			}
		}

		return $list;

	}
}

function sns_Login($type){

	if($type) {

		return $user_Id;
		return $user_Name;
		return $user_Email;
		return $login_Type;
		return $sns_Login;

	}
}

if (!defined('PHP_EOL')) {
	switch (strtoupper(substr(PHP_OS, 0, 3))) {
		// Windows
		case 'WIN':
			define('PHP_EOL', "\r\n");
			break;

		// Mac
		case 'DAR':
			define('PHP_EOL', "\r");
			break;

		// Unix
		default:
			define('PHP_EOL', "\n");
	}
}

function get_dirname() {
	$dir = getcwd();
	$temp = explode("/", $dir);
	$dirname = $temp[sizeof($temp)-1];

	return $dirname;
}

function hyphen_date($val){
	return preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})$/", "\\1-\\2-\\3", $val);
}

## 휴대폰 하이픈설정
function hyphen_phone($val){

	if(strlen($val) == 11) {
		return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})$/", "\\1-\\2-\\3", $val);
	} else if(strlen($val) == 10) {
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})$/", "\\1-\\2-\\3", $val);
	} else {
		return $val;
	}

}

## 일반전화 하이픈설정
function hyphen_tel($val){

	$areanum = substr($val, 0, 2);
	if($areanum == 02) {

		if(strlen($val) == 9) {
			return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})$/", "\\1-\\2-\\3", $val);
		} else if(strlen($val) == 10) {
			return preg_replace("/([0-9]{2})([0-9]{4})([0-9]{4})$/", "\\1-\\2-\\3", $val);
		} else {
			return $val;
		}

	} else {

		if(strlen($val) == 8) {
			return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $val);
		} else if(strlen($val) == 10) {
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})$/", "\\1-\\2-\\3", $val);
		} else if(strlen($val) == 11) {
			return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})$/", "\\1-\\2-\\3", $val);
		} else if(strlen($val) == 12) {
			return preg_replace("/([0-9]{4})([0-9]{4})([0-9]{4})$/", "\\1-\\2-\\3", $val);
		} else {
			return $val;
		}

	}

}

## json (PHP < 5.2)
if (!function_exists('json_decode')) {
	function json_decode($data, $assoc = false) {
		require_once $_SERVER['DOCUMENT_ROOT'].'/twcenter/lib/json_lib.php';
		if ($assoc) {
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} else {
			$json = new Services_JSON;
		}
		return $json->decode($data);
	}
}

if (!function_exists('json_encode')) {
	function json_encode($data) {
		require_once $_SERVER['DOCUMENT_ROOT'].'/twcenter/lib/json_lib.php';
		$json = new Services_JSON;
		return $json->encode($data);
	}
}

## curl_setopt_array (PHP < 5.2)
if (!function_exists('curl_setopt_array')) {
	function curl_setopt_array(&$ch, $curl_options)
	{
		foreach ($curl_options as $option => $value) {
			if (!curl_setopt($ch, $option, $value)) {
				return false;
			}
		}
		return true;
	}
}

if (!function_exists('http_build_query')) {
	function http_build_query($data, $prefix = null, $sep = '', $key = '')
	{
		$ret = array();
		foreach ((array )$data as $k => $v) {
			$k = urlencode($k);
			if (is_int($k) && $prefix != null) {
				$k = $prefix . $k;
			}

			if (!empty($key)) {
				$k = $key . "[" . $k . "]";
			}

			if (is_array($v) || is_object($v)) {
				array_push($ret, http_build_query($v, "", $sep, $k));
			} else {
				array_push($ret, $k . "=" . urlencode($v));
			}
		}

		if (empty($sep)) {
			$sep = ini_get("arg_separator.output");
		}

		return implode($sep, $ret);
	}
}

function json_result($code, $msg) {
	$arr = array("result" => $code, "msg" => $msg);
	return $arr;
}

function json_result_data($code, $msg, $data) {
	$arr = array("result" => $code, "msg" => $msg, "data" => $data);
	return $arr;
}



function template_inspectionStatus($inspectionStatus){
	if($inspectionStatus=="REG"){
		return "등록";
	}else if($inspectionStatus=="REQ"){
		return "심사요청";
	}else if($inspectionStatus=="APR"){
		return "승인";
	}else if($inspectionStatus=="REJ"){
		return "반려";
	}
}

function template_status($status){
	if($status=="S"){
		return "중단";
	}else if($status=="A"){
		return "정상";
	}else if($status=="R"){
		return "대기(발송전)";
	}
}

function templete_error_code($error_code){
	if($error_code=="403"){
		return "403 (권한없음)";
	}else if($error_code=="405"){
		return "405 (파라미터 오류)";
	}else if($error_code=="504"){
		return "504 (템플릿 코드 중복)";
	}else if($error_code=="505"){
		return "505 (템플릿 이름 중복)";
	}else if($error_code=="506"){
		return "506 (템플릿 내용이 1000자 초과)";
	}else if($error_code=="507"){
		return "507 (유효하지 않은 발신 프로필)";
	}else if($error_code=="508"){
		return "508 (요청한 데이터가 없음)";
	}else if($error_code=="509"){
		return "509 (요청을 처리할수 있는 상태가 아님)";
	}else{
		return $error_code;
	}
}

function send_alimtalk($templateCode,$talk_info){

	global $AlimTalkDBConf;
	global $db_host;
	global $db_user;
	global $db_pass;
	global $db_name;


	//알림톡 보내기
	$sql = "select * from wiz_siteinfo";
	$result = query($sql);
	$site_info = sql_fetch_arr($result);

	
	$server       = $site_info['alimtalk_url'];
	$senderKey    = $site_info['alimtalk_senderkey'];
	$custGubun    = $site_info['alimtalk_custgubun'];
	$alimtalk_use = $site_info['alimtalk_use'];
	$url = $server."/v1/A/".$custGubun."/messages";	

	//$sql_template = "select * from wiz_talk_3way where templateCode='$templateCode'";
	$sql_template = "select * from wiz_talk where templateCode='$templateCode'";
	$result_template = query($sql_template) or error(mysql_error());
	$row_template = sql_fetch_arr($result_template);

	// 템플릿 데이터 존재하지 않을 경우 알림톡 미전송 - 2023-01-27 김나연 수정
	if(!$row_template['idx'] || trim($row_template['templateContent']) == "") {
		return false;
	}

	$phoneNum = $talk_info['hphone'];
	$message = $row_template['templateContent'];

	$message = str_replace("#{UID}", $talk_info['id'] ?? "", $message);
	$message = str_replace("#{MEM_PW}", $talk_info['passwd'] ?? "", $message);			//-- 비밀번호 찾기
	$message = str_replace("#{상품명}", $talk_info['prdname']." /\n" ?? "", $message);
	$message = str_replace("#{옵션}  /  ", $talk_info['prdoption']." /\n" ?? "", $message);
	$message = str_replace("#{수량}", $talk_info['prdamount']."\n" ?? "", $message);
	$message = str_replace("#{택배사명}", $talk_info['del_com'] ?? "", $message);
	$message = str_replace("#{운송장번호}", $talk_info['deliver_num'] ?? "", $message);
	$message = str_replace("#{SHOPNAME}", $site_info['site_name'] ?? "", $message);
	$message = str_replace("#{NAME}", $talk_info['name'] ?? "", $message);
	$message = str_replace("#{HPHONE}", $talk_info['hphone'] ?? "", $message);
	$message = str_replace("#{TPHONE}", $talk_info['tphone'] ?? "", $message);
	$message = str_replace("#{SUBJECT}", $talk_info['subject'] ?? "", $message);
	$message = str_replace("#{MANA_SUBJECT}", $talk_info['mana_subject'] ?? "", $message);
	$message = str_replace("#{WDATE}", $talk_info['wdate'] ?? "", $message);
	$message = str_replace("#{ADDINFO1}", $talk_info['addinfo1'] ?? "", $message);
	$message = str_replace("#{ADDINFO2}", $talk_info['addinfo2'] ?? "", $message);
	$message = str_replace("#{ADDINFO3}", $talk_info['addinfo3'] ?? "", $message);
	$message = str_replace("#{ADDINFO4}", $talk_info['addinfo4'] ?? "", $message);
	$message = str_replace("#{ADDINFO5}", $talk_info['addinfo5'] ?? "", $message);
	$message = str_replace("#{URL}", $talk_info['url'] ?? "", $message);
	$message = str_replace("#{LINKM}", $talk_info['link_m'] ?? "", $message);
	$message = str_replace("#{LINKP}", $talk_info['link_p'] ?? "", $message);

	$message_conv = $message;

	$reqDtm = date("YmdHis");

	//alimtalk DB 에 별도로 발송내역 쌓일수있도록 처리
	$db_host_alimtalk = $AlimTalkDBConf['db_host'];
	$db_user_alimtalk = $AlimTalkDBConf['db_user'];
	$db_pass_alimtalk = $AlimTalkDBConf['db_pass'];
	$db_name_alimtalk = $AlimTalkDBConf['db_name'];

	$company_id   = $site_info['alimtalk_id'];

	$connect_alimtalk = dbconn($db_host_alimtalk, $db_user_alimtalk, $db_pass_alimtalk, $db_name_alimtalk) or error('AlimTalk DB connection error');
	select_dbconn($db_name_alimtalk, $connect_alimtalk) or error('AlimTalk DB selection error occurred.');



	// 충전이 0이상일때만 발송
	$query1 = "
		SELECT COUNT(idx) AS cnt
			, company_name
		     , remain_cnt
		  FROM wiz_talk_charging
		 WHERE company_id = '{$company_id}'
	";
	$rs1 = query($query1,true,$connect_alimtalk);
	$rw1 = sql_fetch_arr($rs1);
	$company_name = $rw1['company_name'];

	//if($rw1['cnt'] == 0) error('알림톡을 이용하기 위해서 충전이 필요합니다.');

	//if($rw1['remain_cnt'] > 0 && $alimtalk_use == 'Y' && $rw1['cnt'] > 0) {

		$sql_message = "select count(idx) cnt from wiz_talk_message where templateCode='$templateCode'";
		$result_message = query($sql_message,true,$connect_alimtalk);
		$row_message = sql_fetch_arr($result_message);
		if($row_message['cnt']=="" && $row_message['cnt']=="0"){
			$message_number = "1";
		}else{
			$message_number = $row_message['cnt']+1;
		}

		$custMsgSn = $templateCode."_".$message_number;

		/* 추가 start */
		$button = array();
		if($row_template["btn_name"]) {
			$button[0]['name'] = $row_template['btn_name'];
			$button[0][type] = "WL";
			$button[0][url_mobile] = str_replace("#{PAGELINK}", $talk_info['btn_link'], stripslashes($row_template['linkMo']));
			$button[0][url_pc] = str_replace("#{PAGELINK}", $talk_info['btn_link'], stripslashes($row_template['linkPc']));
		}
		$btn_json = "[".json_encode($button)."]";
		/* 추가 end */ 

		$post_data = array("custMsgSn"=>$custMsgSn, "senderKey"=>$senderKey, "templateCode"=>$templateCode, "phoneNum"=>$phoneNum, "message"=>$message_conv, "reqDtm" => $reqDtm, "button"=>$button);
		

		$post_data_json = json_encode($post_data);
		$post_data_json = "[".$post_data_json."]";

		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data_json);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array("content-type: application/json")); 
		$ch_result = curl_exec ($ch);
		$json_response = json_decode($ch_result, true);	

		// 저장시 싱글쿼테이션 오류 방지 - 2023-01-27 김나연 수정
		$return_sn				= addslashes($json_response[0]['sn']);
		$return_custMsgSn	= addslashes($json_response[0]['custMsgSn']);
		$return_code			= addslashes($json_response[0]['code']);
		$return_altCode		= addslashes($json_response[0]['altCode']);
		$return_altMsg		= addslashes($json_response[0]['altMsg']);
		$return_sndDtm		= addslashes($json_response[0]['sndDtm']);
		$return_rcptDtm		= addslashes($json_response[0]['rcptDtm']);
		
		//alimtalk DB 에 별도로 발송내역 쌓일수있도록 처리
		$sql_com = "";
		$sql_com .= " sn                       = '$return_sn'                       ";
		$sql_com .= " , custMsgSn              = '$return_custMsgSn'                ";
		$sql_com .= " , code                   = '$return_code'                     ";
		$sql_com .= " , altCode                = '$return_altCode'                  ";
		$sql_com .= " , altMsg                 = '$return_altMsg'                   ";
		$sql_com .= " , sndDtm                 = '$return_sndDtm'                   ";
		$sql_com .= " , rcptDtm                = '$return_rcptDtm'                  ";
		$sql_com .= " , templateCode           = '$templateCode'                    ";
		$sql_com .= " , phoneNum               = '$phoneNum'                        ";
		$sql_com .= " , message                = '$message_conv'                    ";
		$sql_com .= " , company_id             = '$company_id'                      ";
		$sql_com .= " , company_name            = '$company_name'                   ";

		$sql_in = "insert into wiz_talk_message set {$sql_com} ";
		query($sql_in,true,$connect_alimtalk);

		if($return_altMsg == 'success') {
			$query = "
				SELECT *
				  FROM wiz_talk_charging
				 WHERE company_id = '{$company_id}'
			";
			$rs = query($query,true,$connect_alimtalk);
			$rw = sql_fetch_arr($rs);

			$remain_cnt = (int)$rw['remain_cnt'] - 1;
			$remain_pay = (int)$rw['remain_pay'] - 15;

			$_up_sql = "
				UPDATE wiz_talk_charging 
				   SET remain_cnt='$remain_cnt'
				     , remain_pay='$remain_pay' 
				 WHERE company_id='$company_id' 
			";
			query($_up_sql,true,$connect_alimtalk);
		}

		dbclose($connect_alimtalk);

		$connect = dbconn($db_host, $db_user, $db_pass) or error('DB connection error occurred.');
		select_dbconn($db_name, $connect) or error('DB selection error occurred.');

		return $return_code;

	/*
	}else{
		$connect = dbconn($db_host, $db_user, $db_pass) or error('DB connection error occurred.');
		select_dbconn($db_name, $connect) or error('DB selection error occurred.');
	}*/
}

function bankda_conn($type, $userid, $userpw="", $username="", $usertel="", $useremail=""){

	global $BankdaDBConf;

	$db_host_bankda = $BankdaDBConf['db_host'];
	$db_user_bankda = $BankdaDBConf['db_user'];
	$db_pass_bankda = $BankdaDBConf['db_pass'];
	$db_name_bankda = $BankdaDBConf['db_name'];

	$user_id      = $userid;
	$user_pw      = $userpw;
	$user_name    = $username;
	$user_tel     = $usertel;
	$user_email   = $useremail;

	$connect_bankda = dbconn($db_host_bankda, $db_user_bankda, $db_pass_bankda) or error('bankda DB connection error.');
	select_dbconn($db_name_bankda, $connect_bankda) or error('bankda DB connection error.');

	if($type == "I") {

		$query1 = "
			SELECT COUNT(idx) AS cnt
			  FROM bankda_member
			 WHERE user_id = '{$user_id}'
		";
		$rs1 = query($query1,true,$connect_bankda);
		$rw1 = sql_fetch_arr($rs1);

		if($rw1['cnt'] == 0) {

			$sql_com = "";
			$sql_com .= " user_id                  = '$user_id'                     ";
			$sql_com .= " , user_pw                = '$user_pw'                     ";
			$sql_com .= " , user_name              = '$user_name'                   ";
			$sql_com .= " , user_tel               = '$user_tel'                    ";
			$sql_com .= " , user_email             = '$user_email'                  ";
			$sql_com .= " , user_domain            = '".$_SERVER['HTTP_HOST']."'    ";
			$sql_com .= " , wdate                  = now()                          ";

			$sql = "insert into bankda_member SET {$sql_com} ";
			query($sql,true,$connect_bankda);

		}

	} else if($type == "M") {

		$sql_com = "";
		$sql_com .= " user_pw                  = '$user_pw'                     ";
		$sql_com .= " , user_name              = '$user_name'                   ";
		$sql_com .= " , user_tel               = '$user_tel'                    ";
		$sql_com .= " , user_email             = '$user_email'                  ";
		$sql_com .= " , user_domain            = '".$_SERVER['HTTP_HOST']."'    ";
		$sql_com .= " , mdate                  = now()                          ";

		$sql = "
			update bankda_member 
			   set {$sql_com} 
			 where user_id = '".$user_id."' 
		";
		query($sql,true,$connect_bankda);

	} else if($type == "D") {

		$sql = "delete from bankda_member where user_id = '".$user_id."' ";
		query($sql,true,$connect_bankda);

	}

}

function time_chk($uptime) {

	$now_time = date('Y-m-d H:i:s');
	$time_check = strtotime($now_time) - strtotime($uptime);

	$total_time = $time_check;

	$days  = floor($total_time/86400);
	$time  = $total_time - ($days*86400);
	$hours = floor($time/3600);
	$time  = $time - ($hours*3600);
	$min   = floor($time/60);
	$sec   = $time - ($min*60);

	/*if($days == 0 && $hours == 0 && $min == 0) {
		$r_time = $sec."초";
	} else if($days == 0 && $hours == 0) {
		$r_time = $min."분";
	} else if($days == 0) {
		$r_time = $hours."시간";
	} else {
		$r_time = $days."일";
	}*/

	$r_time = $days;

	return $r_time;

}

function MemberInactiveAccountNotice($day='') {

	global $site_info;

	$last_visit_time  = date("Y-m-d", strtotime("-11months"));	// 11개월전(sms발송)

	$sql = "
		select idx, id, name, hphone, email, visit, visit_time, wdate
		  from wiz_member 
		 where dchange_type != 'Y'
		 and ( 
					(visit_time != '0000-00-00 00:00:00' and visit_time is not null and visit_time <= '".$last_visit_time." 00:00:00') 
					or (visit_time = '0000-00-00 00:00:00' and wdate <= '".$last_visit_time." 00:00:00')
				)
		 and send_dormail != 'Y'
	";
	$result = query($sql);
	for($i=0;$row = sql_fetch_arr($result);$i++){
		if($row['visit_time'] == "0000-00-00 00:00:00"){
			$dormancy_date = date("Y-m-d", strtotime($row['wdate'])+(86400*365));
		} else {
			$dormancy_date = date("Y-m-d", strtotime($row['visit_time'])+(86400*365));
		}
		if($dormancy_date < date("Y-m-d")) continue;

		$re_info['name'] = $row['name'];
		$re_info['email'] = $row['email'];
		$re_info['hphone'] = $row['hphone'];
		$re_info['dormancy_date'] = $dormancy_date;

		send_mailsms("mem_dormancy", $re_info);
		$sql_up = "update wiz_member set send_dormail='Y' where idx='".$row['idx']."'";
		query($sql_up);
	}

	/* 휴면전환 1년 경과 데이터 삭제 안내 - 2022-02-08 김나연 */
	$sql_dor = "
		select idx, id, name, email, hphone, dchange_date, send_dormail
		from wiz_member_dormancy
		where dchange_date <= '".$last_visit_time." 00:00:00'
		and send_dormail != 'Y'
	";
	$res_dor = query($sql_dor);
	while($row_dor = sql_fetch_arr($res_dor)) {
		$del_date = date("Y-m-d", strtotime($row_dor['dchange_date'])+(86400*365));
		if($del_date < date("Y-m-d")) continue;

		$re_info['name'] = $row_dor['name'];
		$re_info['email'] = $row_dor['email'];
		$re_info['hphone'] = $row_dor['hphone'];
		$re_info['del_date'] = $del_date;
		send_mailsms("mem_del", $re_info);

		$sql_dorup = "update wiz_member_dormancy set send_dormail='Y' where idx='".$row_dor['idx']."'";
		query($sql_dorup);
	}
}

// 휴면계정 대상자에게 sms발송
/*
function MemberrInactiveNoticeSMS($r_id) {

	global $site_info;

	$r_id_chk = explode(",", $r_id);
	$strTelList = '';
	if($r_id_chk) {
		foreach($r_id_chk as $v) {
			$_minfo = sql_fetch("select hphone from wiz_member where id = '".$v."' ");
			if(trim($_minfo['hphone'])) {
				$strTelList .= $_minfo['hphone'].";";
				$message = "휴면공지입니다.";
			}
		}
		send_sms($site_info['site_tel'], $strTelList, $message);
	}
}*/

function MemberTblMatching() {
	$sql_col = " select table_name, column_name, column_type
		from information_schema.columns
		where table_name='wiz_member' or table_name='wiz_member_dormancy'";
	$res_col = query($sql_col);
	$member_column = array();
	$dormancy_column = array();
	$column_info = array();
	while($row_col = sql_fetch_arr($res_col)) {
		if($row_col['table_name'] == "wiz_member") {
			$member_column[] = $row_col['column_name'];
			$column_info[$row_col['column_name']] = $row_col;
		} else if ($row_col['table_name'] == "wiz_member_dormancy") {
			$dormancy_column[] = $row_col['column_name'];
		}
	}

	$no_col = array();
	foreach($member_column as $mem_col) {
		if(in_array($mem_col, $dormancy_column) == false) {
			$no_col[] = $mem_col."|".$before_col;
		}
		$before_col = $mem_col;
	}
	if(sizeof($no_col) > 0 ) {
		foreach($no_col as $column) {
			list($add_column, $before_column) = explode("|", $column);
			$column_type = $column_info[$add_column]['column_type'];
			$sql_addcol = "ALTER TABLE wiz_member_dormancy ADD COLUMN ".$add_column." ".$column_type;
			if($before_column) $sql_addcol .= " AFTER ".$before_column."";
			query($sql_addcol);
		}
	}
}

function MemberInactiveChange() {
	/* wiz_member 와 wiz_member_dormancy 컬럼이 일치하지 않을 경우 wiz_member_dormancy에 추가 */
	MemberTblMatching();

	$default_day = 365;
	//$last_visit_time = date("Y-m-d", time() - ($default_day * 86400));	// 접속로그인 1년 지난것 추출
	
	$last_visit_time = date("Y-m-d", strtotime("-1years"));

	$sql = "
		select id 
		  from wiz_member 
		 where ((visit_time != '0000-00-00 00:00:00' and visit_time is not null and visit_time <= '".$last_visit_time." 00:00:00') 
					or (visit_time = '0000-00-00 00:00:00' and wdate <= '".$last_visit_time." 00:00:00'))
		 and dchange_type !='Y'
		 order by visit_time asc 
	";
	$result = query($sql);
	$id_array = array();
	for($i=0;$row = sql_fetch_arr($result);$i++){
		$id_array[] = $row['id'];
	}

	$r_id = implode(",", $id_array);
	if($r_id) {
		MemberInactiveChangeTbl($r_id);
	}

	/* 휴면전환 1년 경과 데이터 삭제 - 2022-02-08 김나연 */
	$thisTBL = "wiz_member_dormancy";
	$upfile_path = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/member";
	if(empty($site_info)) {
		include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
	}

	$sql_dordel = "select id 
		from wiz_member_dormancy
		where dchange_date <= '".$last_visit_time." 00:00:00' ";
	$res_dordel = query($sql_dordel);
	while($row_dordel = sql_fetch_arr($res_dordel)) {
		$id = $row_dordel['id'];

		// 미니홈피 기능 사용 시 미니홈피 관련 데이터 삭제
		if(!strcmp($site_info['mini_use'], "Y")) {

			@include $_SERVER['DOCUMENT_ROOT']."/twcenter/mini/inc/mini_info.php";

			$sql = "select photo,miniurl from wiz_mini_info where memid = '$id'";
			$result = query($sql);
			$row = sql_fetch_arr($result);

			$miniurl_path = $_SERVER['DOCUMENT_ROOT']."/".$mini_dir."/".$row['miniurl'];

			if(!empty($row['miniurl'])) rm_dir($miniurl_path);

			if(!empty($row['photo'])) @unlink(WIZHOME_PATH."/data/mini/".$row['photo']);

			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/minibbs/bbs/".$id);
			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/minibbs/data/".$id);
			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/minibbs/movie/".$id);
			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/minibbs/photo/".$id);
			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/minibbs/visit/".$id);
			rm_dir($_SERVER['DOCUMENT_ROOT']."/twcenter/data/music/".$id);

			$sql = "delete from wiz_mini_bbs where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_data where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_photo where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_movie where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_guest where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_bbscat where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_comment where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_conrefer where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_contime where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_friend  where myid = '$id' or frdid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_info  where memid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_music  where miniid = '$id'";
			query($sql);

			$sql = "delete from wiz_mini_profile  where miniid = '$id'";
			query($sql);
		}

		// 추가항목이 파일인 경우 업로드 파일 삭제
		$sql = "select addinfo1, addinfo2, addinfo3, addinfo4, addinfo5 from {$thisTBL} where id = '$id'";
		$result = query($sql);
		$my_info = sql_fetch_arr($result);

		$sql = "select * from wiz_formfield where fidx = 'addinfo' and ftype = 'file' order by fprior asc, idx asc";
		$result = query($sql);
		while($row = sql_fetch_arr($result)){

			$no = $row['fprior'];

			$tmp_array = explode("|", $my_info["addinfo".$no]);
			if(!empty($tmp_array)) {
				for($ii = 0; $ii < count($tmp_array); $ii++) {
					@unlink($upfile_path."/".$tmp_array[$ii]);
				}
			}

		}

		// 회원테이블에서 삭제
		$sql = "delete from {$thisTBL} where id = '$id'";
		$result = query($sql);

		@unlink($upfile_path."/".$id.".gif");
		@unlink($upfile_path."/".$id.".jpg");
		@unlink($upfile_path."/".$id."_icon.gif");
		@unlink($upfile_path."/".$id."_icon.jpg");

		// 회원포인트 삭제
		$sql = "delete from wiz_point where memid = '$id'";
		@query($sql);

		// 찜리스트 삭제
		$sql = "delete from wiz_wishlist where memid = '$id'";
		@query($sql);

		// 적립금 삭제
		$sql = "delete from wiz_reserve where memid = '$id'";
		@query($sql);

		// 쿠폰 삭제
		$sql = "delete from wiz_mycoupon where memid = '$id'";
		@query($sql);

		// 주문내역 삭제(주문자 아이디를 [out] 으로 처리)
		$sql = "update wiz_order set send_id = '".$id."[out]' where send_id = '$id'";
		@query($sql);

		$sql = "DELETE FROM wiz_delivery_set  WHERE de_id = '$id'";
		@query($sql);

		$i++;
	}
}

## 회원목록 --> 휴면처리목록
function MemberInactiveChangeTbl($r_id, $s="") {

	$r_id_chk = explode(",", $r_id);
	for($i=0; $i<count($r_id_chk); $i++) {

		$v = $r_id_chk[$i];
		$_minfo = sql_fetch("select * from wiz_member where id = '".$v."' ");

		if(trim($_minfo['id'])) {

			$sql = "
				update wiz_member 
				   set dchange_date = now()
				     , dchange_type = 'Y' 
				 where id = '".$_minfo['id']."' 
			";
			query($sql);

			$sql = "replace wiz_member_dormancy select * from wiz_member where id = '".$_minfo['id']."' ";
			$res = query($sql);
			if(!$res) {
				error("휴면처리 중 오류가 발생했습니다.");
			}

			$wsql = "
				update wiz_member_dormancy 
				   set wdate = '".$_minfo['wdate']."',
				   send_dormail = 'N'
				 where id = '".$_minfo['id']."' 
			";
			query($wsql);

			$sql_com = "";
			$sql_com .= " passwd             = ''               ";
			$sql_com .= " , name             = ''               ";
			$sql_com .= " , photo            = ''               ";
			$sql_com .= " , icon             = ''               ";
			$sql_com .= " , nick             = ''               ";
			$sql_com .= " , resno            = ''               ";
			$sql_com .= " , email            = ''               ";
			$sql_com .= " , tphone           = ''               ";
			$sql_com .= " , hphone           = ''               ";
			$sql_com .= " , comtel           = ''               ";
			$sql_com .= " , homepage         = ''               ";
			$sql_com .= " , post             = ''               ";
			$sql_com .= " , address1         = ''               ";
			$sql_com .= " , address2         = ''               ";
			$sql_com .= " , reemail          = ''               ";
			$sql_com .= " , resms            = ''               ";
			$sql_com .= " , birthday         = ''               ";
			$sql_com .= " , bgubun           = ''               ";
			$sql_com .= " , marriage         = ''               ";
			$sql_com .= " , memorial         = ''               ";
			$sql_com .= " , scholarship      = ''               ";
			$sql_com .= " , job              = ''               ";
			$sql_com .= " , income           = ''               ";
			$sql_com .= " , car              = ''               ";
			$sql_com .= " , hobby            = ''               ";
			$sql_com .= " , consph           = ''               ";
			$sql_com .= " , conprd           = ''               ";
			$sql_com .= " , level            = ''               ";
			$sql_com .= " , recom            = ''               ";
			$sql_com .= " , visit            = ''               ";
			$sql_com .= " , visit_time       = ''               ";
			$sql_com .= " , intro            = ''               ";
			$sql_com .= " , memo             = ''               ";
			$sql_com .= " , addinfo1         = ''               ";
			$sql_com .= " , addinfo2         = ''               ";
			$sql_com .= " , addinfo3         = ''               ";
			$sql_com .= " , addinfo4         = ''               ";
			$sql_com .= " , addinfo5         = ''               ";
			$sql_com .= " , addinfo6         = ''               ";
			$sql_com .= " , addinfo7         = ''               ";
			$sql_com .= " , addinfo8         = ''               ";
			$sql_com .= " , addinfo9         = ''               ";
			$sql_com .= " , addinfo10         = ''               ";			
			$sql_com .= " , addinfo11         = ''               ";
			$sql_com .= " , addinfo12         = ''               ";
			$sql_com .= " , addinfo13         = ''               ";
			$sql_com .= " , addinfo14         = ''               ";
			$sql_com .= " , addinfo15         = ''               ";
			$sql_com .= " , addinfo16         = ''               ";
			$sql_com .= " , addinfo17         = ''               ";
			$sql_com .= " , addinfo18         = ''               ";
			$sql_com .= " , addinfo19         = ''               ";
			$sql_com .= " , addinfo20         = ''               ";			
			$sql_com .= " , wdate            = ''               ";
			$sql_com .= " , mdate            = ''               ";
			$sql_com .= " , old_user         = ''               ";
			$sql_com .= " , sns_id           = ''               ";
			$sql_com .= " , sns_login        = ''               ";
			$sql_com .= " , ip               = ''               ";
			$sql_com .= " , login_fail_count = ''               ";
			$sql_com .= " , login_try_time   = ''               ";
			$sql_com .= " , is_account_lock  = ''               ";
			$sql_com .= " , pw_update        = ''               ";
			$sql_com .= " , drestore_date    = ''               ";
			$sql_com .= " , cert_number      = ''               ";

			$sql = "
				update wiz_member 
				   set 
				   {$sql_com} 
				 where id = '".$_minfo['id']."' 
			";
			query($sql);

		}

	}

}

## 휴면목록 --> 회원목록;
function InactiveMemberChangeTbl($r_id, $s) {

	$r_id_chk = explode("|", $r_id);
	
	if($r_id_chk) {

		for($i=0; $i<count($r_id_chk); $i++) {

			$v = $r_id_chk[$i];
			if($s == 'a') {
				$_minfo = sql_fetch("select * from wiz_member_dormancy where idx = '".$v."' ");
			} else {
				$_minfo = sql_fetch("select * from wiz_member_dormancy where id = '".$v."' ");
			}

			if(trim($_minfo['id']) && $_minfo['dchange_date'] !== "0000-00-00 00:00:00") {

				$sql = "replace wiz_member select * from wiz_member_dormancy where id = '".$_minfo['id']."' ";
				query($sql);

				$sql = "
					update wiz_member 
					   set dchange_date = '0000-00-00 00:00:00' 
					     , dchange_type = 'N'
						 , drestore_date = now()
						 , visit_time = now()
						 , send_dormail = 'N'
					 where id = '".$_minfo['id']."'
				";
				query($sql);

				$sql = "delete from wiz_member_dormancy where id = '".$_minfo['id']."' ";
				query($sql);

			}

		}

	}

}

function get_masking_email($email) {
	if (empty($email) || strpos($email, '@') === false) return '';
	$em = explode("@", $email);

	if (count($em) < 2) return ''; // 유효한 이메일이 아님
	$name = implode('@', array_slice($em, 0, count($em) - 1));
	$len  = floor(strlen($name) / 2);

	return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

function get_masking_phone($type, $str, $res='') {
	$str = str_replace('-', '', $str);
	$strlen = mb_strlen($str, 'utf-8');
	$masking = "";
	
	if($type == 'N') {
		switch($strlen) {
			case 2: $masking = mb_strcut($str, 0, 3, 'utf-8').'*'; break;
			case 3: $masking = mb_strcut($str, 0, 3, 'utf-8').'*'.mb_strcut($str, 8, 11, 'utf-8'); break;
			case 4: $masking = mb_strcut($str, 0, 3, 'utf-8').'*'.mb_strcut($str, 12, 15, 'utf-8'); break;
			default: $masking = mb_strcut($str, 0, 3, 'utf-8').'*'.mb_strcut($str, 12, 15, 'utf-8'); break;
		}
	} else if($type == 'P') {
		if($res == "B") {
			switch($strlen) {
				case 10: $masking = mb_substr($str, 0, 3)."-".mb_substr($str, 3, 1)."**-".mb_substr($str, 6, 2)."**"; break;
				case 11: $masking = mb_substr($str, 0, 3)."-".mb_substr($str, 3, 2)."**-".mb_substr($str, 7, 2)."**"; break;
				default: $masking = mb_substr($str, 0, 3)."-".mb_substr($str, 3, 2)."**-".mb_substr($str, 7, 2)."**"; break;
			}
		} else {
			switch($strlen) {
				case 10: $masking = mb_substr($str, 0, 3)."-***-".mb_substr($str, 6, 4); break;
				case 11: $masking = mb_substr($str, 0, 3)."-****-".mb_substr($str, 7, 4); break;
				default: $masking = mb_substr($str, 0, 3)."-****-".mb_substr($str, 7, 4); break;
			}
		}
	}

	return $masking;
}

## 다중체크박스 검색 (value, 타이틀, 검색된값, checkbox 네임)
function checkSearch($arrayValue, $arrayName, $srhString, $checkname) {

	$multichk_data_array = array();
	
	if(!isset($srhString)) $srhString = '';
	$multi_data      = explode("/",$srhString);
	foreach($multi_data as $k=>$v) {
		$multichk_data_array[$v] = "checked";
	}

	$options = "";
	$data    = $arrayValue;
	foreach($data as $key=>$value) {
		$value      = trim($value);
		$value_name = $arrayName[$key];

		if($value == 'AA') $onclick = "id=\"chkAll\" onclick=\"checkAll('sdel_type')\" ";
		else               $onclick ="";

		$options .= "<input type='checkbox' name='{$checkname}[]' class='{$checkname}' value='{$value}' {$onclick} {$multichk_data_array[$value]}>" . $value_name . "<span class='sp_tab'></span>\n";
	}

	return $options;

}

function get_selected($fld, $val) {
	return ($fld == $val) ? ' selected="selected"' : '';
}

function get_checked($fld, $val) {
	return ($fld == $val) ? ' checked="checked"' : '';
}

function bankda_bankcode($bkcode) {

	switch($bkcode){
		case "03":$bkname = "기업은행";break;
		case "04":$bkname = "국민은행";break;
		case "13":$bkname = "농협";break;
		case "20":$bkname = "우리은행";break;
		case "23":$bkname = "제일(SC)은행";break;
		case "26":$bkname = "신한은행";break;
		case "28":$bkname = "씨티은행";break;
		case "31":$bkname = "대구은행";break;
		case "32":$bkname = "부산은행";break;
		case "34":$bkname = "광주은행";break;
		case "35":$bkname = "제주은행";break;
		case "37":$bkname = "전북은행";break;
		case "39":$bkname = "경남은행";break;
		case "45":$bkname = "새마을금고";break;
		case "48":$bkname = "신협";break;
		case "71":$bkname = "우체국";break;
		case "81":$bkname = "하나은행";break;
		case "88":$bkname = "수협";break;
		case "89":$bkname = "산업은행";break;
		case "91":$bkname = "케이뱅크";break;
		default:$bkname = "";
	}

	return $bkname;
}

function bk_match_result($bkcode) {

	switch($bkcode){
		case "MT":$bk_result = "입금대기";break;
		case "MA":$bk_result = "<font color='red'>매칭실패(불일치)</font>";break;
		case "MB":$bk_result = "<font color='#D5656F'>매칭실패(동명이인)</font>";break;
		/*
		작업일시	: 2020-10-13
		작업자명	: 이상민
		작업내용	: 입금자명과 금액이 동일한 주문이 2건이상 존재할 때 자동으로 매칭시키지 않고 실패처리하여 관리자가 수동매칭시킬 수 있도록 구분값 추가
		*/
		case "MS":$bk_result = "<font color='#D5656F'>매칭실패(주문금액동일)</font>";break;
		case "MC":$bk_result = "<font color='#2DA7FE'>매칭성공(자동)</font>";break;
		case "MD":$bk_result = "<font color='#3C75AB'>매칭성공(관리자)</font>";break;
		case "ME":$bk_result = "<font color='#6463D1'>관리자입금확인</font>";break;
		case "MF":$bk_result = "<font color='#515860'>관리자미확인</font>";break;
		case "MZ":$bk_result = "<font color='red'>매칭실패(계좌상이)</font>";break;
		case "MM":$bk_result = "<font color='#F16820'>수동매칭</font>";break;
		default:$bk_result = "";
	}

	return $bk_result;
}

## 회원정보 리턴
function get_mem($str, $fields = "*") {
	return sql_fetch(sprintf("SELECT {$fields} FROM wiz_member WHERE id='%s';", addslashes($str.'')));
}


function get_text($type, $str){
	if($type == "input"){
		$source[] = "/\"/";
		$target[] = "&#034;";
	}
	$source[] = "/\'/";
	$target[] = "&#039;";

	if(!isset($str)) $str = '';
	return preg_replace($source, $target, $str);
}


function curl_connect_get( $api_url ){
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api_url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt( $ch, CURLOPT_TIMEOUT, 120 );
	$json_return = curl_exec( $ch );
	curl_close( $ch ); 
	return json_decode( $json_return , true); 
}

function curl_connect_post($api_url, $postdata, $postType='') {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api_url );
	if($postType == 'json') {
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
	}
	curl_setopt( $ch, CURLOPT_POST, true);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt( $ch, CURLOPT_TIMEOUT, 120 );
	$json_return = curl_exec( $ch );
	curl_close( $ch ); 
	return json_decode( $json_return, true ); 
}

/* 
	확장자 추출 함수 추가
	작업자 : 김나연
	작업일 : 2020-11-11
	작업내용 : 업로드 파일의 확장자명을 추출하는 함수, 기존 솔루션의 확장자 추출이 파일명 끝 3자리만 추출하여 4자리 확장자일 경우 확장자명 오류 발생, 가급적 추가된 함수 이용 바람
*/
function getFileExt($filename) {
	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	return $ext;
}


/* 
	재고수량 재확인
	작업자 : 김나연
	작업일 : 2020-12-10
	작업내용 : 장바구니에 담은 상품의 재고수량여부 재확인, 재고수량 이상일 경우 수량을 현 재고 수량으로 업데이트 하고 알림 출력
*/
function basketCheckAmount($uniq_id, $product_idx="") {
	$basket_idx = "";
	if($product_idx != "") {
		$bidxs = explode("|", $product_idx);
		foreach($bidxs as $k=>$idx) {
			if($idx) {
				$basket_idx .= ($basket_idx) ? " or " : " and (";
				$basket_idx .= " idx='$idx'";
			}
		}
		if($basket_idx) $basket_idx .= ")";
	} else {
		$basket_idx = "and direct='basket'";
	}
	$sql = "SELECT wb.*, wp.shortage, wp.stock
			  FROM wiz_basket_tmp as wb
			  LEFT JOIN wiz_product as wp
				ON wb.prdcode = wp.prdcode
			 WHERE wb.uniq_id = '".$uniq_id."'
			 $basket_idx
			 ";
	$res = query($sql);
	$amount_update = 0;
	$total_amount = 0;

	while($row = sql_fetch_arr($res)) {
		if($row['shortage'] == "Y") {
			$sql_up = "update wiz_basket_tmp set amount = '0' where idx='".$row['idx']."'";
			if(query($sql_up)) {
				$amount_update++;
			}
		} else if($row['shortage'] == "S" && ($row['stock'] < $row['amount'])){
			$total_amount += $row['stock'];
			$sql_up = "update wiz_basket_tmp set amount = '".$row['stock']."' where idx='".$row['idx']."'";
			if(query($sql_up)) {
				$amount_update++;
			}
		} else {
			$total_amount += $row['amount'];
		}
	}

	if($product_idx && $total_amount < 1) {
		echo "<script>alert('주문 가능한 상품이 없습니다.'); history.back();</script>";
	} else if($amount_update >= 1) {
		echo "<script>alert('재고수량이 부족한 상품이 있습니다. 주문수량을 확인하세요.')</script>";
	}
}

function sql_password($value)
{
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" select password('$value') as pass ");
    return $row['pass'];
}

function discount_update($orderid) {
	$sql = "select orderid, prd_price, coupon_use, reserve_use, discount_price from wiz_order where orderid='$orderid'";
	$order_info= sql_fetch($sql);

	$total_price = $order_info['prd_price'];
	$discount_price = $order_info['discount_price'];
	$coupon_use = $order_info['coupon_use'];
	$reserve_use = $order_info['reserve_use'];

	$sql = "select idx, prdprice, amount from wiz_basket where orderid='$orderid'";
	$res = query($sql);
	$basket_cnt = sql_fetch_row($res);
	$no = 1;
	$total_percent = $total_coupon = $total_discount = $total_reserve = 0;
	while($row = sql_fetch_arr($res)){
		$prdprice = $row['prdprice'] * $row['amount'];

		if($no == $basket_cnt) {
			$prd_discount = $discount_price - $total_discount;
			$prd_coupon = $coupon_use - $total_coupon;
			$prd_reserve = $reserve_use - $total_reserve;
		} else {
			$discount_percent = $prdprice / $total_price;
			$prd_discount = round($discount_price * $discount_percent);

			$prd_percent = ($prdprice - $prd_discount) / ($total_price - $discount_price);
			$prd_coupon = round($coupon_use * $prd_percent);
			$prd_reserve = round($reserve_use * $prd_percent);
		}

		$total_discount += $prd_discount;
		$total_coupon += $prd_coupon;
		$total_reserve += $prd_reserve;
		$sql_up = "update wiz_basket set discount_price='".$prd_discount."', coupon_use='".$prd_coupon."', reserve_use='".$prd_reserve."' where idx='".$row['idx']."'";
		query($sql_up);
		$no++;
	}
}

function prd_pay_price($prdinfo) {
	if(!is_array($prdinfo)) {
		$tmp_array = array();
		foreach($prdinfo as $key=>$val) {
			$tmp_array[$key] = $val;
		}
		$prdinfo = $tmp_array;
	}
	return ($prdinfo['prdprice'] * $prdinfo['amount']) - $prdinfo['discount_price'] - $prdinfo['coupon_use'];// - $prdinfo['reserve_use'];
}

function use_coupon_check($coupon_idx, $send_id) {
	$use_coupon_idx = explode("|", $coupon_idx);
	foreach($use_coupon_idx as $cou_idx) {
		if($cou_idx) {
			$sql_coupon = "select * from wiz_mycoupon where idx='".$cou_idx."' and memid='".$send_id."'";
			$row_coupon = sql_fetch($sql_coupon);
			if($row_coupon['idx'] == '') {
				error("존재하지 않는 쿠폰입니다.");
			} else if ($row_coupon['coupon_use'] == 'Y') {
				error("이미 사용한 쿠폰입니다.");
			} else if ($row_coupon['coupon_edate'] < date("Y-m-d")) {
				error("적용하신 쿠폰의 유효기간이 만료되었습니다. 다시 주문해주세요.");
			}
		}
	}
}

function deleteOldTempFile() {
	$yesterday = strtotime("-12hours");
	//$yesterday = strtotime("-10minutes");

	$tempdir = WAY_DATA_PATH."/temp/";
	$sql = "select * from wiz_tempfile where wdate < '".$yesterday."'";
	$res = query($sql);
	while($row = sql_fetch_arr($res)) {
		if(@is_file($tempdir.$row['filename'])) {
			@unlink($tempdir.$row['filename']);
		}
	}
	$sql_del = "delete from wiz_tempfile where wdate < '".$yesterday."'";
	query($sql_del);
}

function fromBase64toBinary($tempfile, $savefile) {
	$base64_str = "";

	if (@is_file($tempfile)) {
		$orgfile = fopen($tempfile, "r");
		if ($orgfile !== false) {
			$cnt = 0;
			while ($line = fgets($orgfile)) {
				$base64_str .= $line;
				if ($cnt > 10000000) break;
				else $cnt++;
			}
			fclose($orgfile);  // $orgfile 닫기
		} else {
			error_log("파일을 열 수 없습니다: $tempfile");
		}
	}

	$binary = base64_decode($base64_str);

	$fp = fopen($savefile, "w");
	if ($fp !== false) {
		fwrite($fp, $binary);
		fclose($fp);
	} else {
		error_log("파일을 쓸 수 없습니다: $savefile");
	}
}

/* 파일 확장자 체크, 파일전송 받는 페이지에서 호출 */
function fileExtCheck() {
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
	$use_ext = explode("\n", str_replace("\r", "", strtolower($site_info['use_ext'])));

	foreach($_FILES as $file) {
		if($file['name']) {
			$ext = strtolower(getFileExt($file['name']));
			if( ($site_info['ext_config'] == "U" && !in_array($ext, $use_ext)) || ($site_info['ext_config'] == "D" && in_array($ext, $use_ext))) {
				error("[ ".$ext." ] 는 허용되지 않은 확장자입니다.");
			}
		}
	}
}

/* 
	기능 : 2차원배열 재정렬하여 리턴
	작업일 : 2022-12-21
	작업자 : 김나연
	parameter : $arr - 정렬할 배열, $sort_key - 정렬할 키값
*/
function arr_resort($arr, $sort_key) {
	foreach ((array) $arr as $key => $value) {
		$sort[$key] = $value[$sort_key];
	}

	array_multisort($sort, SORT_ASC, $arr);
	return $arr;
}

function get_grplist($type) {
	$tbl = "wiz_".$type."_grp";
	$sql_grp = "select * from ".$tbl." order by prior, idx";
	$res_grp = query($sql_grp);
	$grplist = array();
	while($row_grp = sql_fetch_arr($res_grp)) {
		$grplist[] = $row_grp;
	}
	return $grplist;
}

function grp_update($msg, $loc) {
	if(!$loc) $loc = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "";

	echo "<script>\n";
	echo "alert(\"".$msg."\");\n";
	echo "opener.grp_reload();\n";

	if($loc) echo "location.href = \"".$loc."\";\n";
	else echo "history.back();\n";

	echo "</script>";
}

function get_uniqid(){
	query(" LOCK TABLE wiz_uniqid WRITE ");
	while (1) {
		// 년월일시분초에 100분의 1초 두자리를 추가함 (1/100 초 앞에 자리가 모자르면 0으로 채움)
		$key = sprintf('%.0f', microtime(true) * 1000000); // 항상 정수 문자열
		
		$result = query(" insert into wiz_uniqid set uq_id = '$key' ", false);
		if ($result) break; // 쿼리가 정상이면 빠진다.
		
		// insert 하지 못했으면 일정시간 쉰다음 다시 유일키를 만든다.
		usleep(10000); // 100분의 1초를 쉰다
	}
	query(" UNLOCK TABLES ");
	return $key;
}

function tw_function($workType, $param){
	switch($workType){
		case "get_seo":
			$str = array();

			$site_info = $param['site_info'];
			$page_type = $param['page_type'];
			$prdcode = $param['prdcode'];
			$catcode = $param['catcode'];
			$page_code = $param['page_code'];
			$code = $param['code'];
			$idx = $param['idx'];
			$form_code = $param['form_code'];
			$poll_code = $param['poll_code'];

			$str['browser_title']	= $site_info['browser_title'];
			$str['description']		= $site_info['searchkey_de'];
			$str['classification']	= $site_info['searchkey_cl'];
			$str['keywords']		= $site_info['searchkey'];
			$str['seoimg_src']		= SSL.WAY_URL."/img/logo_img.jpg";
			$str['og_title']		= $site_info['browser_title'];

			switch($page_type){
				case "shop":
					if(intval($prdcode) > 0){
						//제품상세페이지 일 경우
						$sql = " select * from wiz_product where prdcode = '".$prdcode."' ";
						$row = sql_fetch($sql);

						$str['og_title'] = strip_tags($row['prdname']);
						$prdimg = $row['prdimg_M1'];
						if($prdimg != "" && file_exists(WAY_DATA_PATH."/prdimg/".$prdimg)){
							$str['seoimg_src'] = SSL.WAY_URL.WAY_DATA_DIR2."/prdimg/".$prdimg;
						}

						if($row['prd_seo_use'] == "Y"){
							$str['browser_title']	= ($row['prd_br_title'] ?? '') ? $row['prd_br_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
							$str['description']		= ($row['prd_descript'] ?? '') ? $row['prd_descript'] : $site_info['searchkey_de'];
							$str['classification']	= ($row['prd_classification'] ?? '') ? $row['prd_classification'] : $site_info['searchkey_cl'];
							$str['keywords']		= ($row['prd_keywords'] ?? '') ? $row['prd_keywords'] : $site_info['searchkey'];
						}
					} else {
						//제품상세페이지 외
						if(intval($catcode) > 0 && $catcode != "00000000"){
							//분류값이 있을 때. 즉, 목록일 때
							$sql = " select * from wiz_category where catcode = '".intval($catcode)."' ";
							$row = sql_fetch($sql);

							$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
							$str['description']		= ($row['searchkey_de'] ?? '') ? $row['searchkey_de'] : $site_info['searchkey_de'];
							$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
							$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
						}
					}
					break;
				case "product2":
					if(intval($prdcode) > 0){
						//제품상세페이지 일 경우
						$sql = " select * from wiz_product2 where prdcode = '".$prdcode."' ";
						$row = sql_fetch($sql);

						$str['og_title'] = strip_tags($row['prdname']);
						$prdimg = $row['prdimg_M1'];
						if($prdimg != "" && file_exists(WAY_DATA_PATH."/product2/".$prdimg)){
							$str['seoimg_src'] = SSL.WAY_URL.WAY_DATA_DIR2."/product2/".$prdimg;
						}

						if($row['prd_seo_use'] == "Y"){
							$str['browser_title']	= ($row['prd_br_title'] ?? '') ? $row['prd_br_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
							$str['description']		= ($row['prd_descript'] ?? '') ? $row['prd_descript'] : $site_info['searchkey_de'];
							$str['classification']	= ($row['prd_classification'] ?? '') ? $row['prd_classification'] : $site_info['searchkey_cl'];
							$str['keywords']		= ($row['prd_keywords'] ?? '') ? $row['prd_keywords'] : $site_info['searchkey'];
						}
					} else {
						//제품상세페이지 외
						if(intval($catcode) > 0 && $catcode != "00000000"){
							//분류값이 있을 때. 즉, 목록일 때
							$sql = " select * from wiz_category2 where catcode = '".intval($catcode)."' ";
							$row = sql_fetch($sql);

							$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
							$str['description']		= ($row['searchkey_de'] ?? '') ? $row['searchkey_de'] : $site_info['searchkey_de'];
							$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
							$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
						}
					}
					break;
				case "page":
					$sql = " select * from wiz_page where code = '".$page_code."'";
					$row = sql_fetch($sql);

					$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
					$str['description']		= ($row['searchkey_de'] ?? '') ? $row['searchkey_de'] : $site_info['searchkey_de'];
					$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
					$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
					break;
				case "bbs":
					include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";

					if($ptype == "view"){
						$sql = " select subject, upfile1 from wiz_bbs where code = '".$code."' and idx = '".intval($idx)."' ";
						$row = sql_fetch($sql);
						
						$str['og_title'] = strip_tags($row['subject']);
						$str['seoimg_src'] = SSL.WAY_URL.WAY_DATA_DIR2."/bbs/".$code."/".$row['upfile1'];
					}

					$str['browser_title']	= ($bbs_info['browser_title'] ?? '') ? $bbs_info['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
					$str['description']		= ($bbs_info['searchkey_de'] ?? '') ? $bbs_info['searchkey_de'] : $site_info['searchkey_de'];
					$str['classification']	= ($bbs_info['searchkey_cl'] ?? '') ? $bbs_info['searchkey_cl'] : $site_info['searchkey_cl'];
					$str['keywords']		= ($bbs_info['searchkey'] ?? '') ? $bbs_info['searchkey'] : $site_info['searchkey'];
					break;
				case "form":
					$sql = " select * from wiz_forminfo where code = '".$form_code."'";
					$row = sql_fetch($sql);

					$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
					$str['description']		= ($row['searchkey_de'] ?? '') ? $bbs_info['searchkey'] : $site_info['searchkey_de'];
					$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
					$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
					break;
				case "poll":
					$sql = " select * from wiz_pollinfo where code = '".$poll_code."'";
					$row = sql_fetch($sql);

					$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
					$str['description']		= ($row['searchkey_de'] ?? '') ? $row['searchkey_de'] : $site_info['searchkey_de'];
					$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
					$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
					break;
				case "sch":
					$sql = " select * from wiz_bbsinfo where type='SCH' and code='".$code."' ";
					$row = sql_fetch($sql);

					$str['browser_title']	= ($row['browser_title'] ?? '') ? $row['browser_title']." | ".$site_info['browser_title'] : $site_info['browser_title'];
					$str['description']		= ($row['searchkey_de'] ?? '') ? $row['searchkey_de'] : $site_info['searchkey_de'];
					$str['classification']	= ($row['searchkey_cl'] ?? '') ? $row['searchkey_cl'] : $site_info['searchkey_cl'];
					$str['keywords']		= ($row['searchkey'] ?? '') ? $row['searchkey'] : $site_info['searchkey'];
					break;
				case "banner":
					$str['browser_title']	= ($_page_title ?? '') ? $_page_title." | ".$site_info['browser_title'] : $site_info['browser_title'];
					break;
				case "member":
					$str['browser_title']	= ($_page_title ?? '') ? $_page_title." | ".$site_info['browser_title'] : $site_info['browser_title'];
					break;
			}

			$print_seo = "";
			$print_seo .= '<meta name="description" content="'.$str['description'].'" />';
			$print_seo .= '<meta name="keywords" content="'.$str['keywords'].'" />';
			$print_seo .= '<meta property="og:type" content="website" />';
			$print_seo .= '<meta property="og:title" content="'.$str['og_title'].'" />';
			$print_seo .= '<meta property="og:url" content="'.SSL.WAY_URL.$_SERVER['REQUEST_URI'].'" />';
			$print_seo .= '<meta property="og:site_name" content="'.$site_info['site_name'].'" />';
			$print_seo .= '<meta property="og:description" content="'.$str['description'].'" />';
			$print_seo .= '<meta property="og:locale" content="ko_KR" />';
			$print_seo .= '<meta property="og:image" content="'.$str['seoimg_src'].'" />';
			$print_seo .= '<meta property="og:image:width" content="1200" />';
			$print_seo .= '<meta property="og:image:height" content="630" />';
			$print_seo .= '<meta name="twitter:card" content="summary_large_image" />';
			$print_seo .= '<meta name="twitter:title" content="'.$str['og_title'].'" />';
			$print_seo .= '<meta name="twitter:description" content="'.$str['description'].'" />';
			$print_seo .= '<meta name="twitter:image" content="'.$str['seoimg_src'].'" />';
			$print_seo .= '<title>'.$str['browser_title'].'</title>';

			return $print_seo;
			break;
	}
}
?>