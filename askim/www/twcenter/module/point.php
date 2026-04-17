<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/point_info.php";

if(!empty($wiz_session['id']) && $point_info['point_use'] == "Y") {
	
	$total_point = number_format(get_point($wiz_session['id']));
	$point_url = "/".$point_info['point_url'];
	
	$my_point = "<a href='".$point_url."' class=''>포인트 : ".$total_point." P</a>";
}

?>
