<?php
include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/connect.php";     // 로그분석
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";


//현재위치
$location_array = array();

include $_SERVER['DOCUMENT_ROOT']."/menu.php"; // 메뉴

// $page_type 이 존재하지 않을 경우 공백으로 초기화 - 25.05.23 유준호
if (!isset($page_type)) {
    $page_type = '';
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- 네이버 소유확인 -->


<!-- 구글 소유확인 -->


<!-- 로봇 검색수집 -->
<meta name="robots" content="noindex,nofollow" />

<!-- 대표 url -->
<link rel="canonical" href="<?=SSL.$site_info['site_url']?>">

<!-- favicon -->
<link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/img/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/img/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
<link rel="manifest" href="/img/favicon/manifest.json">

<?php
$seo_param = array();
$seo_param['site_info'] = $site_info;
$seo_param['page_type'] = $page_type;
$seo_param['prdcode'] = $prdcode;
$seo_param['catcode'] = $catcode;
$seo_param['page_code'] = $page_code;
$seo_param['code'] = $code;
$seo_param['idx'] = $idx;
$seo_param['form_code'] = $form_code;
$seo_param['poll_code'] = $poll_code;
$seo_param['_page_title'] = $_page_title;
echo tw_function("get_seo", $seo_param);
?>

<? if($oper_info['chk_readglass'] == "Y") { ?>
<link rel="stylesheet" type="text/css" href="/comm/css/jquery.simpleLens.css">
<link rel="stylesheet" type="text/css" href="/comm/css/jquery.simpleGallery.css">
<? } ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..500,0..1,-50..200" /><!--구글아이콘-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script type='text/javascript' src='/comm/js/script.js?v=<?php echo time(); ?>'></script>
<script type="text/javascript" src="/comm/js/slick.min.js"></script> 
<script type="text/javascript" src="/comm/js/jquery.malihu.PageScroll2id.min.js"></script>
<script type="text/javascript" src="/comm/js/TweenMax.min.js"></script>
<script type="text/javascript" src="/comm/js/header.js"></script> 
<!--<script type="text/javascript" src="/comm/js/comm.js"></script> -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "에스킴컴퍼니",
    "address": {
        "@type": "PostalAddress",
        "postalCode": "04798",
        "addressRegion": "서울특별시",
        "addressLocality": "성동구",
        "streetAddress": "성수이로24길 31"
    }
}
</script>
<!-- aos 효과 제이쿼리 -->
<script type="text/javascript" src="/comm/js/aos.js"></script>
<link rel="stylesheet" type="text/css" href="/comm/css/aos.css" /> 

<!-- jarallax 제이쿼리 -->
<script type="text/javascript" src="/comm/js/jarallax.min.js"></script>
<script type="text/javascript" src="/comm/js/jarallax-video.min.js"></script>

<!-- 익스플로러 실행시 엣지 자동 전환 -->
<script type="text/javascript">
$(document).ready(function () {
	if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) {
		window.location = 'microsoft-edge:' + window.location;
		setTimeout(function() {
		  window.location = 'https://go.microsoft.com/fwlink/?linkid=2135547';
		}, 1);
	  }
});
</script>


<?php
if($code_page == 'main') {
?>
	

<link rel="stylesheet" href="/comm/css/odometer-theme-car.css" />	
<script type="text/javascript" src="/comm/js/odometer.js"></script>
	
<link rel="stylesheet" type="text/css" href="/comm/css/main_ver2.css?v=<?php echo time(); ?>" />
<script type="text/javascript" src="/comm/js/main.js"></script>

<?php } else if ( $code_page == 'shop' ) { ?>
<link rel="stylesheet" type="text/css" href="/comm/css/shop_main.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css?v=<?php echo time(); ?>" />
<script type="text/javascript" src="/comm/js/sub.js"></script> 
<script type="text/javascript" src="/comm/js/ui.js"></script> 

<?php } else if ($sub_css != '') { ?>
<?=$sub_css?>
<script type="text/javascript" src="/comm/js/sub.js"></script>
<script type="text/javascript" src="/comm/js/ui.js"></script>

<?php } else if ($sub_css == '' && $page_type == 'bbs' ) { ?>
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css?v=<?php echo time(); ?>" />
<script type="text/javascript" src="/comm/js/sub.js"></script>
<script type="text/javascript" src="/comm/js/ui.js"></script>

<?php
} else {
?>
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css?v=<?php echo time(); ?>" />
<script type="text/javascript" src="/comm/js/sub.js"></script>
<script type="text/javascript" src="/comm/js/ui.js"></script> 
<?
}
?>
<!-- 퀵용 도메인 검색 -->

<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 -->  
<?
if(strpos(strtolower(basename($_SERVER['SCRIPT_NAME'])), 'login.php') !== false) {
	if($wiz_session['id'] != ""){
		echo "<script>document.location = '/';</script>";
		exit;
	}
}
?>
<?
if($site_info['google_an_use'] == "Y"){
	echo "<script>".PHP_EOL;
	echo str_replace("\'", "'", $site_info['googleAnalyticsScript']).PHP_EOL;
	echo "</script>";
}
?>


<?php if($oper_info["nhn_common_key"] != ""){ ?>
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
if(!wcs_add) var wcs_add = {};
wcs_add["wa"] = "<?php echo $oper_info['nhn_common_key']; ?>";
wcs.inflow("<?php echo $_SERVER['HTTP_HOST']; ?>");
</script>
<?php } ?>
<? /* 장바구니 개수 */
$sql_bcnt = "
	select * from 
			wiz_basket_tmp 
		WHERE 
			uniq_id='".$_uniq_id."' 
			and direct = 'basket'
";
$result_bcnt = query($sql_bcnt);
$row_bcnt = sql_fetch_row($result_bcnt);
if($row_bcnt != 0){
	$b_cnt = $row_bcnt;
}else{
	$b_cnt = 0;
}
?>
</head>
<body>
<!-- wrapper -->
<div class="wrapper <?php if(isset($code) && $code == 'search'){?>search_none<?php }?>">

	<h1 class="blind">Askim company</h1>

	<header class="header">
		<div class="header_in basic_in">
			<div class="logo"><a href="/"><span class="blind">Askim company</span></a></div>
			<div class="top_util">
				<dl class="sns_list">
					<dd class="sns01"><a href="https://blog.naver.com/askimcompany" target="_blank" title="새 창 열림"><span class="blind">네이버 블로그</span></a></dd>
					<dd class="sns02"><a href="https://www.instagram.com/paulseee/" target="_blank" title="새 창 열림"><span class="blind">인스타그램</span></a></dd>
					<dd class="sns03"><a href="https://www.youtube.com/channel/UCM72MD-6k_uvBMKyXAcvlgQ" target="_blank" title="새 창 열림"><span class="blind">유튜브</span></a></dd>
				</dl>
				<div class="brochure_btn"><a href="/twcenter/bbs/down.php?code=download&idx=1&no=1">Brochure</a></div>
				<ul class="head_btn">
					<li class="showMask search_btn"><a href="javascript:;"><span class="blind">search</span><?php include $_SERVER['DOCUMENT_ROOT']."/img/search.svg";?></a></li>
					<li class="menu_btn"><a href="javascript:;" class="menuBtn"><span class="blind">menu</span><?php include $_SERVER['DOCUMENT_ROOT']."/img/menu.svg";?></a></li>
				</ul>
			</div>
		</div>
		<nav class="gnb" aria-labelledby="gnb">
			<ul>
				<li><a href="<?php echo $location_info['about'][1]['src']; ?>" <? if(isset($location_info['about'][1]['target']) && $location_info['about'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['about_title']; ?></a></li>
				<li><a href="<?php echo $location_info['mns'][1]['src']; ?>" <? if(isset($location_info['mns'][1]['target']) && $location_info['mns'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['mns_title']; ?></a></li>
				<li><a href="<?php echo $location_info['portfolio'][1]['src']; ?>" <? if(isset($location_info['portfolio'][1]['target']) && $location_info['portfolio'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['portfolio_title']; ?></a></li>
				<li><a href="<?php echo $location_info['contact'][1]['src']; ?>" <? if(isset($location_info['contact'][1]['target']) && $location_info['contact'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['contact_title']; ?></a></li>
			</ul>
		</nav>
	</header>
		
	<div id="menu">
		<div class="menu_close">Close</div>
		<ul class="basic_in">
			<li><a href="<?php echo $location_info['about'][1]['src']; ?>" <? if(isset($location_info['about'][1]['target']) && $location_info['about'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['about_title']; ?></a></li>
			<li><a href="<?php echo $location_info['mns'][1]['src']; ?>" <? if(isset($location_info['mns'][1]['target']) && $location_info['mns'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['mns_title']; ?></a></li>
			<li><a href="<?php echo $location_info['portfolio'][1]['src']; ?>" <? if(isset($location_info['portfolio'][1]['target']) && $location_info['portfolio'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['portfolio_title']; ?></a></li>
			<li><a href="<?php echo $location_info['contact'][1]['src']; ?>" <? if(isset($location_info['contact'][1]['target']) && $location_info['contact'][1]['target'] =="Y") { echo " target='_blank'"; } ?>><?php echo $location_info['contact_title']; ?></a></li>
		</ul>
	</div>
		

  <?php
  if ( $code_page == 'main' ) {
  ?>

<main class="container">



  <?php
  } else {
  ?>


<main class="container">

 


<?php
		switch ($code_page) {
		case 'portfolio':#포트폴리오
?>  

	<?php if($ptype != "view") {?>
	<div class="subtitle basic_in">
		<h2 class="fs65" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200" data-aos-once="true"><?=$location_array[2]?></h2>
		<p class="fs21" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300" data-aos-once="true">에스킴컴퍼니의 캠페인 사례를 소개합니다.</p>
	</div>
	<?php }?>

<?php
		break;
		case 'other':#이용안내

?>   

	<div class="subtitle basic_in">
		<h2 class="fs65" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200" data-aos-once="true"><?=$location_array[2]?></h2>
	</div>
<?
			break;
	}
?>			
	 

<?php
}#if
?>
