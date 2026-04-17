<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

// =============================================//
// version : 1.5 (2003.07.3)
// 사용방법 : 
// nc_p.php Page에 Post 방식으로 a1=이름,a2=주민번호를 보낸다.
// =============================================//

// =============================================//
// 회원사 ID, 비밀번호 및 기타  설정
// =============================================//
// sURLnc의 값을 실제 이 페이지를 부르는 page로 설정해야 동작합니다.
// 외부 사용자가 이 URL을 스크래핑하여 불법으로 사용하는 것을 막기 위함.

// 	define("sURLnc", "@URLNC.PHP");   	// 이전 URL을 입력하세요. 
//	define("sURLnc", "http://www.test.co.kr/nc.php");

// @SITEID 및 @SITEPW 를 실제 부여 받은 사이트 id 및 패스워드로 로 바꾸기 바람니다.

$sSiteID = $site_info['namecheck_id'];  	// 사이트 id 
$sSitePW = $site_info['namecheck_pw'];   // 비밀번호

$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/twcenter/member/cb_namecheck";			// cb_namecheck 모듈이 설치된 위치 

// ============================================ //
// Main 시작 
// ============================================ //
// Passed Data value : 
// $a1 : 이름 
// $a2 : 주민번호
// ============================================ //

	$strJumin= $resno1.$resno2;		// 주민번호
	$strName = $name;		//이름
	
	$iReturnCode  = "";	

// sURLnc의 값을 실제 이 페이지를 부르는 page(HTTP_REFERER)로 설정해야 동작합니다.
// echo "HTTP_REFERER=($HTTP_REFERER)"; 로 값을 확인해 볼수 잇습니다.
// nc_p.php 페이지를 외부 사용자가 불법으로 사용하는 것을 막기 위함.
//	if ($HTTP_REFERER == sURLnc)
//	 {
		$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;		
//	 }
echo $iReturnCode;
//	echo "성명확인 서비스 결과<hr><p>성명 확인 결과 값이 저장된 \$iReturnCode를 이용하여 회원사 추가처리 루틴을 삽입<P>";
//	echo "iReturnCode=($iReturnCode)" ;

$iReturnMsg[2] = "본인 아님";
$iReturnMsg[4] = "시스템 장애";
$iReturnMsg[5] = "주민번호 오류";
$iReturnMsg[9] = "request 데이타 오류.";
$iReturnMsg[10] = "사이트 코드 오류";
$iReturnMsg[11] = "정지된 사이트";
$iReturnMsg[12] = "해당사이트 비밀번호 오류";
$iReturnMsg[13] = "사이트 인증 시스템 장애";
$iReturnMsg[15] = "Decoding 오류(Data)";
$iReturnMsg[16] = "Decoding 시스템장애";
$iReturnMsg[30] = "연결 장애 ";
$iReturnMsg[32] = "결과값 이상";
$iReturnMsg[34] = "통신중 장애발생";
$iReturnMsg[50] = "정보도용 차단 요청 주민번호";
$iReturnMsg[55] = "외국인 번호 확인 오류";
$iReturnMsg[56] = "외국인 번호 확인 오류";
$iReturnMsg[57] = "외국인 번호 확인 오류";
$iReturnMsg[58] = "출입국 관리소 통신 오류";

if($iReturnCode != "1"){
	echo "<script>alert('인증에 실패 하였습니다. 실패사유[".$iReturnMsg[$iReturnCode]."]');</script>";
}else{
	echo "<script>alert('실명인증 되었습니다.');parent.document.namecheckFrm.submit();</script>";
}
?>