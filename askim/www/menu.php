<?php
// code_page switch 외부에서 하위메뉴별 배열선언 필요.
//title[숫자] 와 src[숫자] = $ThisPageNum
// **새창으로 열기 필요한경우 해당페이지에 선언 필요 >>   $location_info['company'][1]['target'] = "Y";

// 홈버튼
$location_info['site_home'] = "/";


$location_info['about_title'] = "About Us";
$location_info['about'][1]['title'] = "About Us";
$location_info['about'][1]['src'] = "/#about";
//$location_info['about'][1]['target'] = "Y";

$location_info['mns_title'] = "Media & Strength";
$location_info['mns'][1]['title'] = "Media & Strength";
$location_info['mns'][1]['src'] = "/#corevalue";
//$location_info['mns'][1]['target'] = "Y";


$location_info['portfolio_title'] = "Portfolio";
$location_info['portfolio'][1]['title'] = "포트폴리오";
$location_info['portfolio'][1]['src'] = "/portfolio/portfolio.php";
//$location_info['portfolio'][1]['target'] = "Y";

$location_info['contact_title'] = "Contact";
$location_info['contact'][1]['title'] = "Contact";
$location_info['contact'][1]['src'] = "/#contact";
//$location_info['contact'][1]['target'] = "Y";


$location_info['other_title'] = "User Guide";
$location_info['other'][1]['title'] = "개인정보취급방침";
$location_info['other'][1]['src'] = "/other/privacy.php";
$location_info['other'][2]['title'] = "검색결과";
if($code_page == "other"){
	if($ThisPageNum == 2){
		$location_info['other'][2]['src'] = "/other/search.php";
	} else {
		$location_info['other'][2]['src'] = "";
	}
}

// 서브페이지 visual_lnb 두번째칸에 표시될 대메뉴 부분 선언
$location_info['lnb'][1]['code_page'] = "about";
$location_info['lnb'][2]['code_page'] = "mns";
$location_info['lnb'][3]['code_page'] = "portfolio";
$location_info['lnb'][4]['code_page'] = "contact";
if($code_page == "other"){
	$location_info['lnb'][5]['code_page'] = "other";
} else {
	$location_info['lnb'][5]['code_page'] = "";
}


switch ( $code_page ) {
	case 'main':
		$ThisPageName = '메인.';
		break;
	case 'about':
		$ThisPageName = $location_info[$code_page.'_title'];
		//$sub_css = '<link rel="stylesheet" type="text/css" href="/comm/css/about.css" />';

		$location_array[2] = $location_info['about'][$ThisPageNum]['title'];
		$location_array[3] = $location_info['about'][$ThisPageNum]['src'];
		break;
	case 'mns':
		$ThisPageName = $location_info[$code_page.'_title'];
		//$sub_css = '<link rel="stylesheet" type="text/css" href="/comm/css/mns.css" />';
		
		$location_array[2] = $location_info['mns'][$ThisPageNum]['title'];
		$location_array[3] = $location_info['mns'][$ThisPageNum]['src'];
		break;
	case 'portfolio':
		$ThisPageName = $location_info[$code_page.'_title'];
		//$sub_css = '<link rel="stylesheet" type="text/css" href="/comm/css/portfolio.css" />';

		$location_array[2] = $location_info['portfolio'][$ThisPageNum]['title'];
		$location_array[3] = $location_info['portfolio'][$ThisPageNum]['src'];
		break;
	case 'contact':
		$ThisPageName = $location_info[$code_page.'_title'];
		//$sub_css = '<link rel="stylesheet" type="text/css" href="/comm/css/contact.css" />';

		$location_array[2] = $location_info['contact'][$ThisPageNum]['title'];
		$location_array[3] = $location_info['contact'][$ThisPageNum]['src'];
		break;
	case 'other':
		$ThisPageName = $location_info['other_title'];
		//$sub_css = '<link rel="stylesheet" type="text/css" href="/comm/css/other.css" />';

		$location_array[2] = $location_info['other'][$ThisPageNum]['title'];
		$location_array[3] = $location_info['other'][$ThisPageNum]['src'];
		break;
}


?>