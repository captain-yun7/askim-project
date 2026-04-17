(function($){

	$(function() {

		var calendar = {
			showOn: "both", 
			buttonImage: "/twcenter/images/calendar_btn2.gif", 
			buttonImageOnly: true,
			showButtonPanel: true,
			dateFormat: "yy-mm-dd",
			currentText: '오늘', 
			closeText: '닫기', 
			changeMonth: true, 
			changeYear: true, 
			showMonthAfterYear: true,
			dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
			dayNamesMin: ['<font color=red>일</font>', '월', '화', '수', '목', '금', '<font color=#0071bf>토</font>'], 
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			yearRange: 'c-10:c+5'
		};

		$("#prev_date,#next_date").datepicker(calendar);

	});

})(jQuery);
