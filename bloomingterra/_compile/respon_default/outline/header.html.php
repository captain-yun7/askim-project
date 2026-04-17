<?php /* Template_ 2.2.8 2024/03/19 18:32:09 /home/bloomingterra/www/data/skin/respon_default/outline/header.html 000008997 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title><?php echo $TPL_VAR["cfg_site"]['title']?></title>
<link rel="shortcut icon" href="<?php echo _UPLOAD.'/conf/'.$TPL_VAR["cfg_site"]['favicon']?>" type="image/x-icon" />
<link rel="icon" href="<?php if($TPL_VAR["seo"]['favicon']){?><?php echo $TPL_VAR["seo"]['favicon']?><?php }?>" type="image/x-icon" />

<meta charset="UTF-8">
<meta name="title" content="<?php echo $TPL_VAR["seo"]['title']?>" />
<meta name="keywords" content="<?php echo $TPL_VAR["seo"]['keywords']?>">
<meta name="description" content="<?php echo $TPL_VAR["seo"]['description']?>">
<meta name="author" content="<?php echo $TPL_VAR["seo"]['author']?>">
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta name="format-detection" content="telephone=no" /><!--애플전화번호링크자동설정해제-->
<meta property="og:title" content="<?php echo $TPL_VAR["seo"]['og_title']?>" />
<meta property="og:description" content="<?php echo $TPL_VAR["seo"]['description']?>" />
<meta property="og:image" content="<?php if($TPL_VAR["seo"]['og_image']){?><?php echo $TPL_VAR["seo"]['og_image']?><?php }?>" />
<meta property="twitter:title" content="<?php echo $TPL_VAR["seo"]['og_title']?>" />
<meta property="twitter:description" content="<?php echo $TPL_VAR["seo"]['description']?>" />
<meta property="twitter:image" content="<?php if($TPL_VAR["seo"]['og_image']){?><?php echo $TPL_VAR["seo"]['og_image']?><?php }?>" />

<link rel="stylesheet" type="text/css" href="/lib/css/common.css" />
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/reset.css" />
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/aos.css" />
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/animate.min.css" />
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/common.css" /><!-- 서브 공통 css ｜ custom 금지 css -->
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/sub.css" /><!-- 서브 공통 custom 요소 css ｜ 개별페이지 css -->
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/skin.css" /><!-- 스킨 css ｜ 상하단·메인 -->




<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- <script src="/lib/js/jquery-2.2.4.min.js"></script> -->
<script src="/lib/js/jquery.validate.min.js"></script>
<script src="/lib/js/common.js"></script>
<script src="/lib/js/jquery-ui.min.js"></script>
<script src="/lib/js/moment-with-locales.min.js"></script>
<script src="<?php if(defined('_IS_SSL')){?>//ssl.daumcdn.net/dmaps<?php }else{?>//dmaps.daum.net<?php }?>/map_js_init/postcode.v2.js"></script>
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/slick.css">
<script src="<?php echo $TPL_VAR["js"]?>/js/slick.js"></script>
<!--#script src="../js/ui_common.js"></script-->
<script src="<?php echo $TPL_VAR["js"]?>/js/aos.js"></script>


<!-- swiper -->	
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/swiper.css" />
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/swiper-bundle.min.css" />

<script src="<?php echo $TPL_VAR["js"]?>/js/swiper.js"></script>
<script src="<?php echo $TPL_VAR["js"]?>/js/swiper-bundle.min.js"></script>
<!-- swiper -->	



<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<script src="<?php echo $TPL_VAR["js"]?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
	var CURRENT_DATE = "<?php echo CURRENT_DATE?>";
	$(function() {
		$.validator.setDefaults({
			onkeyup: false,
			onclick: false,
			onfocusout: false,
			ignore : '.ignore',
			showErrors: function(errorMap, errorList) {
				if(errorList.length < 1) {
					return;
				}
				alert(errorList[0].message);
				errorList[0].element.focus();
			}
		});

		//데이터 피커
		$(".startdate").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			showMonthAfterYear: true,
			dateFormat: "yy-mm-dd",
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],
			onClose : function (selectedDate){
				$(".enddate").datepicker( 'option', 'minDate', selectedDate );
			}
		});

		$(".enddate").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			showMonthAfterYear: true,
			dateFormat: "yy-mm-dd",
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],
			onClose : function(selectedDate) {
				$(".startdate").datepicker( "option", "maxDate", selectedDate );
			}
		});

		$(".datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			showMonthAfterYear: true,
			dateFormat: "yy-mm-dd",
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토']
		});
	});
</script>
<link rel="stylesheet" type="text/css" href="/data/skin/respon_default/css/cssreset-context-min.css"><!-- 에디터 css -->
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="index_"){?>
<script>
	$(document).ready(function(){
		$('body').css('overflow', 'hidden');
		$(".main_intro").addClass('on'); 

		setTimeout(function() {
		   $(".main_intro").addClass('effect'); 
		}, 1500);

		setTimeout(function() {
			$(".main_intro").addClass('fold');
			$('body').css('overflow', '');
			
        }, 5600);
		
		setTimeout(function() {
			$('.sec01 .lay01').addClass('on');
		}, 6200);
	});

</script>
<?php }?>
</head>
<body>
<div class="skip_nav">
	<a href="#header" class="skip_nav_link">상단메뉴 바로가기</a>
	<a href="#content" class="skip_nav_link">본문 바로가기</a>
	<a href="#sub_nav" class="skip_nav_link">본문 하위메뉴 바로가기</a>
	<a href="#footer" class="skip_nav_link">하단 바로가기</a>
</div><!-- 웹접근성용 바로가기 링크 모음 -->
<article id="wrap" class="clear <?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=='board'){?>sub_board sub_<?php echo $TPL_VAR["board_info"]['code']?><?php }elseif($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'){?> sub_<?php echo $TPL_VAR["CI"]->uri->rsegments[ 1]?> <?php echo $TPL_VAR["CI"]->uri->rsegments[ 1]?>_<?php echo $TPL_VAR["CI"]->uri->rsegments[ 2]?> <?php }else{?>main_index<?php }?>">
<?php if($TPL_VAR["header_hidden"]!='y'){?>
<?php $this->print_("nav",$TPL_SCP,1);?>

<?php }?>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="index_"){?>
	<div class="main_intro">
		<div class="bg"></div>		
		<div class="logo">
			<span class="b_logo"><img src="/data/skin/respon_default/images/skin/intro_b.png" alt="intro_b"></span>
			<span class="main_logo"><img src="/data/skin/respon_default/images/skin/intro_logo.png" alt="intro_logo"></span>
			<span class="r_logo"><img src="/data/skin/respon_default/images/skin/intro_r.png" alt="intro_r"></span>
		</div>
	</div>
<?php }?>
	<div id="container" class="clear">
		<div id="contents_wrap" class="clear">
			<div id="contents_box">
				<!-- 컨텐츠 시작 #contents -->
				<div id="content" class="clear ">
					<!-- #공통 상단요소 시작 -->
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=="board"){?>
<?php $this->print_("bbs_top",$TPL_SCP,1);?>

<?php }elseif($TPL_VAR["CI"]->uri->rsegments[ 1]=="goods"){?>
<?php if($TPL_VAR["CI"]->uri->uri_string=="goods/goods_list"||$TPL_VAR["CI"]->uri->uri_string=="goods/goods_view"){?>
<?php }elseif($TPL_VAR["CI"]->uri->uri_string=="goods/goods_search"){?>
<?php }?>
<?php }?>
					<!-- #공통 상단요소 끝 -->
					<!-- #서브 컨텐츠 시작 -->