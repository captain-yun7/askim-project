/* [Stage2] 완전 정적화 — 스크롤 재킹/애니 제거. 카운터 최종값만 세팅 */
$(document).ready(function(){
	// data-count 최종값 표시
	$('[data-count]').each(function(){
		var n = $(this).attr('data-count');
		$(this).find('span').text(n);
	});
});
