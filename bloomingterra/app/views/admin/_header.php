<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<link rel="stylesheet" href="/lib/admin/css/reset.css">
<link rel="stylesheet" href="/lib/admin/css/admin_css.css">
<link rel="stylesheet" href="/lib/admin/css/bbs.css">
<link rel="stylesheet" href="/lib/admin/css/calendar-eraser_lim.css">
<link rel="stylesheet" href="/lib/admin/css/context-menu.css">
<link rel="stylesheet" href="/lib/admin/css/drag-drop-folder-tree.css">
<link rel="stylesheet" href="/lib/admin/css/mini_tree.css">
<link rel="stylesheet" href="/lib/admin/css/skin.css">
<link rel="stylesheet" href="/lib/admin/css/tooltipster.bundle.min.css">
<link rel="stylesheet" href="/lib/admin/css/jquery-ui.min.css">
<link rel="stylesheet" href="/lib/css/common.css">
<!-- <link rel="stylesheet" href="/lib/admin/css/gabia_point.css"> --> <!-- 가비아 포인트 색상 변경 -->


<script src="/lib/js/jquery-2.2.4.min.js"></script>
<script src="/lib/js/jquery-ui.min.js"></script>
<script src="<?=$_SERVER['SERVER_PORT'] == 443 ? "https://ssl.daumcdn.net/dmaps" : "http://dmaps.daum.net"?>/map_js_init/postcode.v2.js"></script>
<script src="/lib/js/underscore.js"></script>
<script src="/lib/js/jquery.validate.min.js"></script>
<script src="/lib/js/tooltipster.bundle.min.js"></script>
<script src="/lib/js/moment-with-locales.min.js"></script>
<script src="/lib/js/common.js"></script>
<script>
	var CURRENT_DATE = "<?=CURRENT_DATE?>";
	$(function() {
		$.validator.setDefaults({
			ignore : '.ignore',
			onkeyup: false, 
			onclick: false,
			onfocusout: false,
			showErrors: function(errorMap, errorList) {
				if(errorList.length < 1) {
					return;
				}
				alert(errorList[0].message);
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

	var hd_h = $('#header').outerHeight(),
		tit_h = $('.main_tit').outerHeight();
	$(window).scroll(function() {
		if ( $(this).scrollTop() > hd_h + tit_h ) {
			$('.main_tit').addClass('fixed');
		} else {
			$('.main_tit').removeClass('fixed');
		}
	});

	$(function() {
		$('#contents').css({'padding-top':tit_h});
	});
</script>
</head>
<body>
<div id="wrapper">
	<div id="wrap">
		<div id="container">