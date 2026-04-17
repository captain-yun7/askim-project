<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta http-equiv="Expires" content="0"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$site_info['admin_title']?></title>
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<link href="../wiz_style.css?ver=<?php echo VERSION ?>" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="../paging.css?ver=<?php echo VERSION ?>" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery.form.js"></script>
<script type="text/javascript" src="../../js/clipboard.min.js"></script>
<script type="text/javascript" src="../../js/jquery.cookie.js"></script>
<script type="text/javascript" src="/comm/js/script.js?ver=<?php echo VERSION ?>"></script>
<script type="text/javascript" src="../../js/lib.js?ver=<?php echo VERSION ?>"></script>
<script type="text/javascript" src="../../js/preloader.js?ver=<?php echo VERSION ?>"></script>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


$(document).on("click ", ".left_table dl", function(){
	if($(this).hasClass("on") === true){
		
		$(this).children(".depth_list").not(".nogrp").slideUp(300);
		$(this).parents("dl").removeClass("on");
		$(this).removeClass("on");
		
	}else {

		$(".left_table dl").removeClass("on");
		$(".depth_list").not(".nogrp").removeClass("on");
		$(".left_table dl .depth_list").not(".nogrp").slideUp(300);
		
		$(this).children(".depth_list").not(".nogrp").slideDown(300);
		$(this).parents("dl").not(".nogrp").addClass("on");
		$(this).addClass("on");
	}
});

$(document).on("click", ".btn_tree_expand", function() {
	if($(this).hasClass("open")) {
		$(".left_table dl .depth_list").slideDown(300);
	} else if ($(this).hasClass("close")) {
		$(".left_table dl .depth_list:not(.nogrp)").slideUp(300);
	}
	$(".left_table").find("dl.on").each(function() {
		if($(this).find("p.clickover").length < 1) {
			$(this).removeClass("on");
		}
	});
});
//-->
</script>
</head>

<body>
<div id="loading"><img id="loading-image" src="../image/loading_1.gif" alt="Loading..." /></div>
<!-- @ Menu : Start @ -->
<div id="header">
  <div id="gnb">
	<div class="top_01">
	<h1><a href="/twcenter/manage/main/main.php">ADMIN<span>ISTRATOR</span></a></h1>
		<ul>
			<li class="bghide"><a href="http://<?=$HTTP_HOST?>" target="_blank">내 홈페이지</a></li>
			<li><a href="/twcenter/manage/main/main.php">관리자 메인</a></li>
			<li class="logout"><a href="../../logout.php">로그아웃</a></li>
			<li class="threeway"><a href="http://www.web2002.co.kr/member/work_list.php" target="_blank">유지보수 신청</a></li>
		</ul>
	</div>
  </div>
  <div id="navigation">
	<ul class="gnb_l">

	<?
	// 메뉴사용여부
	$menu_tmp = explode("/",$site_info['menu_use']);
	$adm_permi = explode("/", strtoupper($wiz_admin['permi'] ?? ' '));
	for($ii=0; $ii<count($menu_tmp); $ii++){
		if($menu_tmp[$ii]) {
			$menu_arr[$menu_tmp[$ii]] = true;
			if($wiz_admin['lev'] == 10000 || $wiz_admin['designer'] == "Y"){
				$perm_check[$menu_tmp[$ii]] = true;
			} else {
				$perm_check[$menu_tmp[$ii]] = in_array($menu_tmp[$ii], $adm_permi);
			}
		}
	}
	?>
		
		<? if(!isset($menucode)) $menucode = ''; ?>
		
		<? if($wiz_admin['designer'] == "Y"){ ?>
		<li class="first"><a href="../config/basic_config.php">환경설정</a></li>
		<? } ?>

		<? if($menu_arr["BASIC"]==true && $perm_check["BASIC"]){ ?>
		<li <? if(strpos($menucode, 'BASIC') !== false) echo "class='clickover'"?>><a href="../basic/site_info.php?menucode=BASIC" class="menu">기본설정</a></li>
		<? } ?>

		<? if($menu_arr["BBS"]==true && $perm_check["BBS"]){ ?>
		<li <? if(strpos($menucode, 'BBS') !== false) echo "class='clickover'"?>><a href="../bbs/bbs_list.php?menucode=BBS" class="menu">게시판관리</a></li>
		<? } ?>

		<? if($menu_arr["MESSAGE"]==true && $perm_check["MESSAGE"]){ ?>
		<li <? if(strpos($menucode, 'MESSAGE') !== false) echo "class='clickover'"?>><a href="../message/mail_list.php?menucode=MESSAGE" class="menu">메세지관리</a></li>
		<? } ?>

		<? if($menu_arr["MEMBER"]==true && $perm_check["MEMBER"]){ ?>
		<li <? if(strpos($menucode, 'MEMBER') !== false) echo "class='clickover'"?>><a href="../member/member_list.php?menucode=MEMBER" class="menu">회원관리</a></li>
		<? } ?>

		<? if($menu_arr["FORMMAIL"]==true && $perm_check["FORMMAIL"]){?>
		<li <? if(strpos($menucode, 'FORMMAIL') !== false) echo "class='clickover'"?>><a href="../form/form_list.php?menucode=FORMMAIL" class="menu">폼메일관리</a></li>
		<? } ?>

		<? if($menu_arr["POLL"]==true && $perm_check["POLL"]){?>
		<li <? if(strpos($menucode, 'POLL') !== false) echo "class='clickover'"?>><a href="../poll/pollinfo_list.php?menucode=POLL" class="menu">설문관리</a></li>
		<? } ?>

		<? if($menu_arr["SCHEGUAL"]==true && $perm_check["SCHEGUAL"]){?>
		<li <? if(strpos($menucode, 'SCHEGUAL') !== false) echo "class='clickover'"?>><a href="../schedule/sch_list.php?menucode=SCHEGUAL" class="menu">일정관리</a></li>
		<? } ?>

		<? if($menu_arr["PRODUCT2"]==true && $perm_check["PRODUCT2"]){?>
		<li <? if(!strcmp($menucode, 'PRODUCT2')) echo "class='clickover'"?>><a href="../product2/prd_list.php?menucode=PRODUCT2" class="menu">상품관리</a></li>
		<? } ?>

		<? if($menu_arr["PAGE"]==true && $perm_check["PAGE"]){?>
		<li <? if(strpos($menucode, 'PAGE') !== false) echo "class='clickover'"?>><a href="../page/page_list.php?menucode=PAGE" class="menu">페이지관리</a></li>
		<? } ?>

		<? if($menu_arr["BANNER"]==true && $perm_check["BANNER"]){?>
		<li <? if(strpos($menucode, 'BANNER') !== false) echo "class='clickover'"?>><a href="../banner/banner_list.php?menucode=BANNER" class="menu">디자인관리</a></li>
		<? } ?>

		<? if($menu_arr["LOG"]==true && $perm_check["LOG"]){?>
		<li <? if(strpos($menucode, 'LOG') !== false) echo "class='clickover'"?>><a href="../connect/connect_list.php?menucode=LOG" class="menu">접속통계</a></li>
		<? } ?>

		<? if($menu_arr["PRODUCT"]==true && $perm_check["PRODUCT"]){?>
		<li <? if(!strcmp($menucode, 'PRODUCT')) echo "class='clickover'"?>><a href="../product/order_list.php?menucode=PRODUCT" class="menu">쇼핑몰관리</a></li>
		<? } ?>

	</ul>
  </div>
</div>

<div id="contents_wrap">
  <div id="menu">
	  <div class="btn_navi_openclose">
		<a href="#" onFocus="this.blur();" onclick="navigation_btn();"></a>
	  </div>
	  <div id="left_area" >
		<?php include("./left_menu.php"); ?>
	  </div>
  </div>
  <div id="contents">
