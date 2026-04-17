<?
$sql = "select * from wiz_meminfo";
$mem_info = sql_fetch($sql);

// 스킨위치
if(strpos($PHP_SELF, "/m/") !== false) {
	$skin_dir = "/twcenter/member/skin/".$mem_info['m_skin'];
} else {
	$skin_dir = "/twcenter/member/skin/".$mem_info['skin'];
}


$icon_size   = 25;								// 회원 아이콘 크기

$agreement   = $mem_info['agreement'];			// 회원약관
$safeinfo    = $mem_info['safeinfo'];				// 개인정보 보호정책

if(mobile_check()){
	$idpw_url    = "/m/member/idpw.php";
	$login_url   = "/m/member/login.php";
} else {
	$join_url    = "/".$mem_info['join_url'];		// 회원가입
	$login_url   = "/".$mem_info['login_url'];		// 로그인 페이지
	$logout_url  = "/twcenter/member/logout.php";		// 로그아웃 페이지
	$myinfo_url  = "/".$mem_info['myinfo_url'];		// 마이페이지
	$idpw_url    = "/".$mem_info['idpw_url'];		// 아이디,비번찾기 주소
}

// 입력정보 사용여부
$info_tmp = explode("/",$mem_info['infouse']);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_use[$info_tmp[$ii]] = true;
}
if(!isset($info_use["specChar"])) $info_use["specChar"] ='';

if($info_use["specChar"] == true) {
	$spectial_Char = "Y";
}

// 입력정보 필수여부
$info_tmp = explode("/",$mem_info['infoess']);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_ess[$info_tmp[$ii]] = true;
}

// 추가항목명
list($addname1, $addname2, $addname3, $addname4, $addname5) = explode("|", $mem_info['addname']);

// 직업목록
$i=0;
$array_job = explode(",",$mem_info['job_list']);
$job_list = "<label for='job' class='blind'>선택</label><select name='job' id='job' class='select'>\n";
$job_list .= "<option value=''>항목을 선택해 주세요</option>\n";
while(isset($array_job[$i])){
	$job_list .= "<option value='".$array_job[$i]."'>".$array_job[$i]."</option>\n";
	$i++;
}
$job_list .= "</select>\n";

// 학력목록
$i=0;
$array_sch = explode(",",$mem_info['sch_list']);
$sch_list = "<label for='scholarship' class='blind'>선택</label><select name='scholarship' id='scholarship' class='select'>\n";
$sch_list .= "<option value=''>항목을 선택해 주세요</option>\n";
while(isset($array_sch[$i])){
	$sch_list .= "<option value='".$array_sch[$i]."'>".$array_sch[$i]."</option>\n";
	$i++;
}
$sch_list .= "</select>\n";

// 수입목록
$i=0;
$array_income = explode(",",$mem_info['income_list']);
$income_list = "<label for='income' class='blind'>선택</label><select name='income' id='income' class='select'>\n";
$income_list .= "<option value=''>항목을 선택해 주세요</option>\n";
while(isset($array_income[$i])){
	$income_list .= "<option value='".$array_income[$i]."'>".$array_income[$i]."</option>\n";
	$i++;
}
$income_list .= "</select>\n";


// 관심분야목록 
if(!isset($my_info['consph'])) $my_info['consph'] = ' ';
$arrconsph=explode(",",$my_info['consph']);
for($ii=0; $ii<count($arrconsph); $ii++){
  $consph[$arrconsph[$ii]]="checked";
}

// 키값 접근 시 undefind key error 로 인한 코드 수정 25.05.23 유준호
$i=0;
$consph_list = "";
$array_consph = explode(",",$mem_info['consph_list']);
while(isset($array_consph[$i])){
	$value = $array_consph[$i];
	$checked = $consph[$value] ?? ' ';
	$consph_list .= "<input type='checkbox' name='consph[]'  value='$value' $checked > $value ";
	$i++;
//	기존코드
//	$consph_list .= "<input type='checkbox' name='consph[]'  value='".$array_consph[$i]."' ".$consph[$array_consph[$i]]."> ".$array_consph[$i]." ";
//	$i++;
}

// 이메일 / SMS (IDPW)확인방법
$method_tmp = explode("/",$mem_info['method']);
for($ii=0; $ii<count($method_tmp); $ii++){
	$method_use[$method_tmp[$ii]] = true;
}

$_email_data = array
(
	""	       => array("",":: 직접입력 ::"),
	"naver.com"    => array("naver.com","naver.com"),
	"daum.net"     => array("daum.net","daum.net"),
	"hanmail.net"  => array("hanmail.net","hanmail.net"),
	"gmail.com"    => array("gmail.com","gmail.com"),
	"hotmail.com"  => array("hotmail.com","hotmail.com"),
	"nate.com"     => array("nate.com","nate.com")
);

$_hphone_data = array
(
	""		   => array("",":: 번호선택 ::"),
	"010"		   => array("010","010"),
	"011"		   => array("011","011"),
	"016"		   => array("016","016"),
	"017"		   => array("017","017"),
	"018"		   => array("018","018"),
	"019"		   => array("019","019")
);

$_hphone_data2 = array
(
	"010"		   => array("010","010"),
	"011"		   => array("011","011"),
	"016"		   => array("016","016"),
	"017"		   => array("017","017"),
	"018"		   => array("018","018"),
	"019"		   => array("019","019")
);

$pi_array             = array('gif','jpg');			## gif / jpg만 업로드 가능

?>