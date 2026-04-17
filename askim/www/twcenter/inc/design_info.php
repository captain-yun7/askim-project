<?
/* 관리자 > 쇼핑몰관리 > 운영정보설정 > 카테고리설정 */

$design_info['site_align']	= "CENTER";			// 쇼핑몰정렬
$design_info['site_width']	= "900";				// 사이트 가로크기
$design_info['cate_sub']		= "Y";					// 카테고리 하위분류 출력
$design_info['cate_suby']	 	= "180";				// 세로좌표
$design_info['cate_subx']	 	= "150";				// 가로좌표
$design_info['cate_menuh']	= "20";					// 메뉴높이

$sql = "select cate_sub, site_align, site_width, cate_suby, cate_subx, cate_menuh from wiz_operinfo";
$design_info = sql_fetch($sql);
?>