<?php
$sql = "select * from wiz_siteinfo";
$site_info = sql_fetch($sql);
$_alimtalk_id = $site_info['alimtalk_id'];

$viewType = $site_info['viewType'];

if(!strcmp($site_info['ssl_use'], "Y")) {
	$ssl = "https://".$_SERVER['HTTP_HOST'];
	if(!empty($site_info['ssl_port'])) $ssl .= ":".$site_info['ssl_port'];
} else {
	$hide_ssl_start = "<!--"; $hide_ssl_end = "-->";
}

if(strcmp($site_info['autologin_use'], "Y")) {
	$hide_auto_start = "<!--"; $hide_auto_end = "-->";
}

if(strcmp($site_info['mobile_use'], "Y")) {
	$hide_mobile_start = "<!--"; $hide_mobile_end = "-->";
}

$icon_size = 25;

$_area_arry = array
(
	""	  => array("","--지역선택--"),
	"02"  => array("02","서울(02)"),
	"031" => array("031","경기(031)"),
	"032" => array("032","인천(032)"),
	"033" => array("033","강원(033)"),
	"041" => array("041","충남(041)"),
	"042" => array("042","대전(042)"),
	"043" => array("043","충북(043)"),
	"044" => array("044","세종(044)"),
	"051" => array("051","부산(051)"),
	"052" => array("052","울산(052)"),
	"053" => array("053","대구(053)"),
	"054" => array("054","경북(054)"),
	"055" => array("055","경남(055)"),
	"061" => array("061","전남(061)"),
	"062" => array("062","광주(062)"),
	"063" => array("063","전북(063)"),
	"064" => array("064","제주(064)"),
	"070" => array("070","070"),
	"080" => array("080","080"),
	"0303" => array("0303","0303"),
	"0504" => array("0504","0504"),
	"0505" => array("0505","0505"),
	"0506" => array("0506","0506"),
	"0507" => array("0507","0507")
);

$_hp_array = array
(
	""		=> array("","--선택--"),
	"010"	=> array("010","010"),
	"011"	=> array("011","011"),
	"016"   => array("016","016"),
	"017"   => array("017","017"),
	"018"   => array("018","018"),
	"019"   => array("019","019")
);

$_email_array = array
(
	""			   => array("","직접입력"),
	"naver.com"    => array("naver.com","naver.com"),
	"daum.net"     => array("daum.net","daum.net"),
	"hanmail.net"  => array("hanmail.net","hanmail.net"),
	"gmail.com"   => array("gmail.com","gmail.com"),
	"hotmail.com"   => array("hotmail.com","hotmail.com"),
	"nate.com"   => array("nate.com","nate.com")
);

$admin_menu = [
	"BASIC"=>"기본설정",
	"BBS"=>"게시판관리",
	"MEMBER"=>"회원관리",
	"MESSAGE"=>"메세지설정",
	"FORMMAIL"=>"폼메일관리",
	"POLL"=>"설문관리",
	"SCHEGUAL"=>"일정관리",
	"PRODUCT2"=>"상품관리",
	"PAGE"=>"페이지관리",
	"BANNER"=>"디자인관리",
	"LOG"=>"접속통계",
	"PRODUCT"=>"쇼핑몰관리"];
?>
